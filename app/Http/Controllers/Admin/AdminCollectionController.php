<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Driver;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminCollectionController extends Controller
{
    /**
     * Display all collections
     */
    public function index(Request $request)
    {
        $query = Collection::with(['user', 'serviceType', 'driver. user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by service type
        if ($request->filled('service_type')) {
            $query->where('service_type_id', $request->service_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('scheduled_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('scheduled_date', '<=', $request->date_to);
        }

        // Filter by driver assignment
        if ($request->filled('assignment')) {
            if ($request->assignment === 'unassigned') {
                $query->whereNull('driver_id');
            } elseif ($request->assignment === 'assigned') {
                $query->whereNotNull('driver_id');
            }
        }

        // Search by customer name or address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('service_address', 'like', "%{$search}%");
            });
        }

        $collections = $query->orderByDesc('scheduled_date')
            ->orderBy('scheduled_time_start')
            ->paginate(15)
            ->withQueryString();

        $serviceTypes = ServiceType::active()->ordered()->get();
        $drivers = Driver::with('user')->where('availability_status', '!=', 'inactive')->get();

        // Statistics
        $stats = [
            'total' => Collection::count(),
            'pending' => Collection::whereIn('status', ['pending', 'confirmed'])->count(),
            'in_progress' => Collection::where('status', 'in_progress')->count(),
            'completed_today' => Collection::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'unassigned' => Collection::whereNull('driver_id')
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
        ];

        return view('admin.collections.index', compact(
            'collections',
            'serviceTypes',
            'drivers',
            'stats'
        ));
    }

    public function verifyPayment(Collection $collection)
    {
        // Update status di tabel Collections
        $collection->update(['payment_status' => 'paid']);

        // Update status di tabel Payments (jika ada relasinya)
        if ($collection->payment) {
            $collection->payment->update([
                'payment_status' => 'success', // atau 'completed' sesuai enum db
                'payment_date'   => now(),
            ]);
        }

        return back()->with('success', 'Pembayaran berhasil diverifikasi manual!');
    }
    /**
     * Display a specific collection
     */
    public function show(Collection $collection)
    {
        $collection->load([
            'user',
            'serviceType',
            'driver.user',
            'payment.invoice',
            'rating',
            'subscription. subscriptionPlan',
        ]);

        $availableDrivers = Driver::with('user')
            ->where('availability_status', 'available')
            ->get();

        return view('admin.collections.show', compact('collection', 'availableDrivers'));
    }

    /**
     * Assign a driver to a collection
     */
    public function assignDriver(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $driver = Driver::findOrFail($validated['driver_id']);

        $collection->update([
            'driver_id' => $driver->id,
            'status' => $collection->status === 'pending' ? 'confirmed' : $collection->status,
        ]);

        return back()->with('success', "Driver {$driver->user->name} assigned successfully.");
    }

    /**
     * Update collection status
     */
    public function updateStatus(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled,missed',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'completed') {
            $updateData['completed_at'] = now();
        }

        $collection->update($updateData);

        return back()->with('success', 'Collection status updated.');
    }
}
