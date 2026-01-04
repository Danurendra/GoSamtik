<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ServiceType;
use App\Models\Payment;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil data Collections (Order User)
        $collections = Collection::where('user_id', $request->user()->id)
            ->with(['serviceType', 'driver.user', 'payment'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->service_type, fn($q, $type) => $q->where('service_type_id', $type)) // Filter berdasarkan tipe
            ->orderByDesc('created_at') // Atau 'scheduled_date'
            ->paginate(10);

        // 2. Ambil data Service Types (INI YANG HILANG SEBELUMNYA)
        // Menggunakan where('is_active', true) agar aman jika scope active() belum dibuat di model
        $serviceTypes = ServiceType::where('is_active', true)->get();

        // 3. Kirim kedua variable ke View
        return view('collections.index', compact('collections', 'serviceTypes'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', true)->orderBy('sort_order')->get();
        return view('collections.create', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'waste_size' => 'required|in:small,medium,large',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time_start' => 'required',
            'scheduled_time_end' => 'required',
            'service_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // 2. Cek Hari Libur
//        if (Holiday::whereDate('holiday_date', $validated['scheduled_date'])->exists()) {
//            return back()->withInput()->withErrors(['scheduled_date' => 'Maaf, tanggal tersebut adalah hari libur.']);
//        }

        // 3. Hitung Harga (Server Side)
        $serviceType = ServiceType::findOrFail($validated['service_type_id']);

        $multiplier = match ($request->waste_size) {
            'medium' => 2.5,
            'large' => 5.0,
            default => 1.0, // small
        };

        $finalPrice = $serviceType->base_price * $multiplier;

        // 4. Simpan ke Database (Transaction)
        $collection = DB::transaction(function () use ($request, $validated, $serviceType, $finalPrice) {

            // A. Buat Data Collection (Order)
            // PENTING: Semua field wajib harus diisi disini
            $newCollection = Collection::create([
                'user_id' => $request->user()->id, // <-- Ini yang tadi error (1364)
                'service_type_id' => $validated['service_type_id'],
                'waste_size' => $request->waste_size,

                // Perhatikan nama kolom di DB Anda (apakah 'collection_date' atau 'scheduled_date'?)
                // Berdasarkan migration Anda, sepertinya 'collection_date'
                'scheduled_date' => $validated['scheduled_date'],
                'time_slot_start' => $validated['scheduled_time_start'],
                'time_slot_end' => $validated['scheduled_time_end'],
                'scheduled_time_start' => $validated['scheduled_time_start'],
                'scheduled_time_end' => $validated['scheduled_time_end'],
                'service_address' => $validated['service_address'],
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $finalPrice,
                'status' => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // B. Buat Data Payment (Manual Transfer)
            Payment::create([
                'collection_id' => $newCollection->id,
                'user_id'       => $request->user()->id,
                'total_amount'  => $finalPrice,
                'payment_type'  => 'one_time',
                'payment_method'=> 'manual_transfer', // Kita set manual dulu
                'payment_status'=> 'pending',
                'transaction_id'=> 'MANUAL-' . $newCollection->id . '-' . time(),
            ]);

            return $newCollection;
        });

        // 5. Redirect ke Detail Order (Bukan Checkout Midtrans)
        return redirect()->route('collections.show', $collection)
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran manual.');
    }

    public function show(Collection $collection)
    {
        // Pastikan user cuma bisa lihat punya sendiri
        if ($collection->user_id !== auth()->id()) {
            abort(403);
        }

        $collection->load(['serviceType', 'driver', 'payment']);
        return view('collections.show', compact('collection'));
    }
}
