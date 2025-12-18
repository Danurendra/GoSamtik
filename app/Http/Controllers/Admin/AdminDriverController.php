<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\User;
use App\Models\Collection;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminDriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::with('user');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhere('vehicle_plate', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('availability_status', $request->status);
        }

        $drivers = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Driver::count(),
            'available' => Driver::where('availability_status', 'available')->count(),
            'on_route' => Driver::where('availability_status', 'on_route')->count(),
            'offline' => Driver::where('availability_status', 'offline')->count(),
        ];

        return view('admin.drivers.index', compact('drivers', 'stats'));
    }

    public function create()
    {
        return view('admin.drivers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'license_number' => 'required|string|max:50|unique:drivers,license_number',
            'license_expiry' => 'required|date|after:today',
            'vehicle_type' => 'required|string|max:50',
            'vehicle_plate' => 'required|string|max:20|unique:drivers,vehicle_plate',
            'vehicle_capacity' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'driver',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            Driver::create([
                'user_id' => $user->id,
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
                'vehicle_type' => $validated['vehicle_type'],
                'vehicle_plate' => $validated['vehicle_plate'],
                'vehicle_capacity' => $validated['vehicle_capacity'] ?? null,
                'availability_status' => 'available',
                'average_rating' => 0,
                'total_collections' => 0,
            ]);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver created successfully!');
    }

    public function show(Driver $driver)
    {
        $driver->loadMissing('user');

        $stats = [
            'total_collections' => $driver->total_collections,
            'average_rating' => $driver->average_rating,
            'completed_this_month' => Collection::where('driver_id', $driver->id)
                ->where('status', 'completed')
                ->whereMonth('completed_at', now()->month)
                ->count(),
            'total_routes' => Route::where('driver_id', $driver->id)->count(),
        ];

        $recentCollections = Collection::where('driver_id', $driver->id)
            ->with(['user', 'serviceType'])
            ->latest('scheduled_date')
            ->limit(10)
            ->get();

        $recentRatings = $driver->ratings()
            ->with(['user', 'collection.serviceType'])
            ->latest()
            ->limit(5)
            ->get();

        $upcomingCollections = Collection::where('driver_id', $driver->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->whereDate('scheduled_date', '>=', today())
            ->with(['user', 'serviceType'])
            ->orderBy('scheduled_date')
            ->limit(5)
            ->get();

        return view('admin.drivers.show', compact(
            'driver',
            'stats',
            'recentCollections',
            'recentRatings',
            'upcomingCollections'
        ));
    }

    public function edit(Driver $driver)
    {
        $driver->loadMissing('user');
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
{
    $driver->loadMissing('user');

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $driver->user_id,
        'phone' => 'required|string|max:20',

        'license_number' => 'required|string|max:50|unique:drivers,license_number,' . $driver->id,
        'license_expiry' => 'required|date',
        'vehicle_type' => 'required|string|max:50',
        'vehicle_plate' => 'required|string|max:20|unique:drivers,vehicle_plate,' . $driver->id,
        'vehicle_capacity' => 'nullable|string|max:50',

        // 🔥 FIX DI SINI
        'availability_status' => 'required|in:available,on_route,offline',
    ]);

    DB::transaction(function () use ($validated, $driver) {
        // Update user
        $driver->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        // Update driver
        $driver->update([
            'license_number' => $validated['license_number'],
            'license_expiry' => $validated['license_expiry'],
            'vehicle_type' => $validated['vehicle_type'],
            'vehicle_plate' => $validated['vehicle_plate'],
            'vehicle_capacity' => $validated['vehicle_capacity'] ?? null,

            // 🔥 NILAI AMAN UNTUK ENUM
            'availability_status' => $validated['availability_status'],
        ]);
    });

    return redirect()
        ->route('admin.drivers.show', $driver)
        ->with('success', 'Driver updated successfully!');
}


    public function updateStatus(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'availability_status' => 'required|in:available,on_route,offline,inactive',
        ]);

        $driver->update($validated);

        return back()->with('success', 'Driver status updated.');
    }

    public function destroy(Driver $driver)
    {
        $driver->loadMissing('user');

        $pending = Collection::where('driver_id', $driver->id)
            ->whereIn('status', ['pending','confirmed','in_progress'])
            ->exists();

        if ($pending) {
            return back()->with('error', 'Cannot delete driver with pending collections.');
        }

        DB::transaction(function () use ($driver) {
            Collection::where('driver_id', $driver->id)->update(['driver_id' => null]);
            $driver->delete();
            $driver->user->update(['status' => 'inactive']);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver removed successfully.');
    }
}
