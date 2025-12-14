<?php

namespace App\Http\Controllers;

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
    public function checkout(Collection $collection)
    {
        $this->authorize('update', $collection);

        // Check if already paid
        if ($collection->payment && $collection->payment->isCompleted()) {
            return redirect()->route('collections.show', $collection)
                ->with('info', 'This collection has already been paid.');
        }

        $collection->load('serviceType');

        return view('payments.checkout', compact('collection'));
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