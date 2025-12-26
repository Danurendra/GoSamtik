<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ServiceType;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $collections = Collection::where('user_id', $request->user()->id)
            ->with(['serviceType', 'driver. user'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->service_type, fn($q, $type) => $q->where('service_type_id', $type))
            ->orderByDesc('scheduled_date')
            ->paginate(10);

        $serviceTypes = ServiceType::active()->ordered()->get();

        return view('collections.index', compact('collections', 'serviceTypes'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::active()->ordered()->get();

        return view('collections.create', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            // HAPUS SPASI setelah titik dua
            'waste_size' => 'required',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time_start' => 'required',
            'scheduled_time_end' => 'required',
            'service_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            // Tambahan: Terima koordinat jika ada (biar driver tau lokasi tepatnya)
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        // Cek Hari Libur
        // Pastikan model Holiday punya method static isHoliday()
        // Jika belum ada, bisa pakai: Holiday::whereDate('date', $validated['scheduled_date'])->exists()
        if (Holiday::whereDate('date', $validated['scheduled_date'])->exists()) {
            return back()
                ->withInput() // Biar inputan user nggak hilang
                ->withErrors(['scheduled_date' => 'Maaf, tanggal tersebut adalah hari libur layanan kami.']);
        }

        $serviceType = ServiceType::findOrFail($validated['service_type_id']);

        //Logic Hitung Harga Berdasarkan Ukuran
        $multiplier = 1;
        $weight = 5; // Default kg

        switch ($request->waste_size) {
            case 'small':
                $multiplier = 1;
                $weight = 5;
                break;
            case 'medium':
                $multiplier = 2.5; // Lebih mahal 2.5x
                $weight = 20;
                break;
            case 'large':
                $multiplier = 5; // Lebih mahal 5x
                $weight = 50;
                break;
        }

        // Jika tipe sampahnya "Bulk Items" (Furniture), mungkin ada biaya tambahan fix
        if ($serviceType->slug === 'bulk-items') {
            $multiplier = 1; // Reset multiplier, pakai harga flat mahal
            $weight = 100;   // Berat asumsi
        }

        $finalPrice = $serviceType->base_price * $multiplier;

        // 2. Simpan Data
        // Kita gunakan DB Transaction biar kalau error di tengah jalan, data nggak nyangkut
        $collection = DB::transaction(function () use ($request, $validated,
            $serviceType, $weight, $finalPrice) {
            return Collection::create([
                'user_id' => $request->user()->id,
                'service_type_id' => $validated['service_type_id'],
                'waste_size' => $request->waste_size,
                'estimated_weight' => $weight,
                'collection_type' => 'one_time',
                'scheduled_date' => $validated['scheduled_date'],
                'scheduled_time_start' => $validated['scheduled_time_start'],
                'scheduled_time_end' => $validated['scheduled_time_end'],
                'service_address' => $validated['service_address'],
                'latitude' => $request->latitude ?? null,   // Masukkan koordinat
                'longitude' => $request->longitude ?? null, // Masukkan koordinat
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $serviceType->base_price, // Pastikan kolom base_price ada di tabel service_types
                'status' => 'pending',
            ]);
        });

        // 3. Redirect ke Halaman Bayar
        return redirect()->route('payments.checkout', $collection)
            ->with('success', 'Jadwal penjemputan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    public function show(Collection $collection)
    {
        $this->authorize('view', $collection);

        $collection->load(['serviceType', 'driver.user', 'rating', 'payment']);

        return view('collections.show', compact('collection'));
    }

    public function cancel(Request $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        if (! $collection->canBeCancelled()) {
            return back()->with('error', 'This collection cannot be cancelled.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $collection->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        // TODO: Process refund if payment was made

        return redirect()->route('collections.index')
            ->with('success', 'Collection cancelled successfully.');
    }

    public function reschedule(Request $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        if (!$collection->canBeCancelled()) {
            return back()->with('error', 'This collection cannot be rescheduled.');
        }

        $validated = $request->validate([
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time_start' => 'required|date_format:H:i',
            'scheduled_time_end' => 'required|date_format:H:i|after:scheduled_time_start',
        ]);

        if (Holiday::isHoliday($validated['scheduled_date'])) {
            return back()->withErrors(['scheduled_date' => 'Selected date is a holiday. ']);
        }

        $collection->update($validated);

        return back()->with('success', 'Collection rescheduled successfully.');
    }
}
