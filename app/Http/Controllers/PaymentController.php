<?php

namespace App\Http\Controllers;

use Midtrans\Config;
use Midtrans\Snap;

use App\Models\Collection;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Show checkout page for a collection
     */

    public function __construct()
    {
        // Set konfigurasi Midtrans
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    public function create()
    {
        // Kirim data ServiceType ke view
        $serviceTypes = ServiceType::where('is_active', true)->get();
        return view('collections.create', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'waste_size' => 'required|in:small,medium,large',
            'service_type_id' => 'required|exists:service_types,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time_start' => 'required',
            'scheduled_time_end' => 'required',
            'service_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:255',
        ]);

        // 2. Ambil Harga Dasar dari Database (JANGAN dari input form hidden)
        $serviceType = ServiceType::findOrFail($request->service_type_id);
        $basePrice = $serviceType->base_price;

        // 3. Tentukan Multiplier (Harus sama persis dengan logika JS di frontend)
        $multiplier = match ($request->waste_size) {
            'medium' => 2.5,
            'large' => 5.0,
            default => 1.0, // small
        };

        // 4. Hitung Total Akhir
        $totalAmount = $basePrice * $multiplier;

        // 5. Simpan ke Database
        $collection = Collection::create([
            'user_id' => auth()->id(),
            'service_type_id' => $serviceType->id,
            'waste_size' => $request->waste_size,
            'total_amount' => $totalAmount, // <-- Harga Valid Server-Side
            'collection_date' => $request->scheduled_date,
            'time_slot_start' => $request->scheduled_time_start,
            'time_slot_end' => $request->scheduled_time_end,
            'service_address' => $request->service_address,
            'notes' => $request->notes,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // 6. Redirect langsung ke halaman pembayaran Midtrans
        return redirect()->route('payment.checkout', $collection->id);
    }
    public function checkout(Collection $collection)
    {
        //  load serviceType untuk ambil harga
        $collection->load('serviceType');

        // 1. Validasi user pemilik order
        if (auth()->id() !== $collection->user_id) {
            abort(403);
        }

        // 2. Cek jika sudah lunas
        if ($collection->payment_status === 'paid') {
            return redirect()->route('collections.show', $collection)
                ->with('status', 'Tagihan sudah lunas!');
        }

        // Ambil harga yang sudah dihitung saat user create order
        $amountToPay = $collection->total_amount;

        $payment = Payment::firstOrCreate(
            ['collection_id' => $collection->id],
            [
                'user_id' => $collection->user_id,
                'total_amount' => $amountToPay, // <-- Gunakan ini
                'transaction_id' => 'TRX-' . $collection->id . '-' . time(),
                'payment_type' => 'one_time',
                'payment_status' => 'pending',
            ]
        );

        // 3. Request Snap Token ke Midtrans (Logika tetap sama)
        if (empty($payment->snap_token)) {
            $params = [
                'transaction_details' => [
                    'order_id' => $payment->transaction_id,
                    'gross_amount' => (int) $payment->amount,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'item_details' => [
                    [
                        'id' => 'COL-' . $collection->id,
                        'price' => (int) $payment->amount,
                        'quantity' => 1,
                        // Tampilkan nama service type di halaman pembayaran Midtrans
                        'name' => $collection->serviceType->name ?? 'Jasa Angkut Sampah',
                    ]
                ]
            ];

            $payment->snap_token = Snap::getSnapToken($params);
            $payment->save();
        }

        return view('payments.checkout', compact('collection', 'payment'));
    }
    /**
     * Process the payment
     */
    public function process(Request $request)
    {
        $validated = $request->validate([
            'collection_id' => 'required|exists:collections,id',
            'payment_method' => 'required|in:credit_card,bank_transfer,e_wallet',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_expiry' => 'required_if:payment_method,credit_card',
            'card_cvc' => 'required_if:payment_method,credit_card',
            'card_name' => 'required_if: payment_method,credit_card',
        ]);

        $collection = Collection::findOrFail($validated['collection_id']);
        $this->authorize('update', $collection);

        // Check if already paid
        if ($collection->payment && $collection->payment->isCompleted()) {
            return redirect()->route('collections.show', $collection)
                ->with('error', 'This collection has already been paid.');
        }

        try {
            DB::transaction(function () use ($collection, $validated, $request) {
                // Calculate amounts
                $amount = $collection->total_amount;
                $taxRate = 0.10; // 10% tax
                $taxAmount = $amount * $taxRate;
                $totalAmount = $amount + $taxAmount;

                // Create payment record
                $payment = Payment::create([
                    'user_id' => $request->user()->id,
                    'collection_id' => $collection->id,
                    'payment_type' => 'one_time',
                    'amount' => $amount,
                    'tax_amount' => $taxAmount,
                    'discount_amount' => 0,
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'completed', // In real app, this would be 'pending' until gateway confirms
                    'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
                    'paid_at' => now(),
                ]);

                // Create invoice
                Invoice::create([
                    'payment_id' => $payment->id,
                    'user_id' => $request->user()->id,
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                    'subtotal' => $amount,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'status' => 'paid',
                ]);

                // Update collection status
                $collection->update([
                    'status' => 'confirmed',
                ]);

                // TODO: Send confirmation email
                // TODO: Send receipt email
            });

            return redirect()->route('payments.success', ['collection' => $collection->id])
                ->with('success', 'Payment completed successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Payment failed.  Please try again.  ' . $e->getMessage());
        }
    }

    /**
     * Payment success page
     */
    public function success(Request $request)
    {
        $collection = Collection::with(['serviceType', 'payment. invoice'])
            ->findOrFail($request->query('collection'));

        $this->authorize('view', $collection);

        return view('payments.success', compact('collection'));
    }
}
