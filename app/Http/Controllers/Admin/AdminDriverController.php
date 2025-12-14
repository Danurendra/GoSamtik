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

class AdminDriverController extends Controller
{
    /**
     * Display all drivers
     */
    public function index(Request $request)
    {
        $query = Driver::with('user');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('vehicle_plate', 'like', "%{$search}%");
        }

        // Filter by availability status
        if ($request->filled('status')) {
            $query->where('availability_status', $request->status);
        }

        $drivers = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Stats
        $stats = [
            'total' => Driver::count(),
            'available' => Driver::where('availability_status', 'available')->count(),
            'on_route' => Driver::where('availability_status', 'on_route')->count(),
            'offline' => Driver::where('availability_status', 'offline')->count(),
        ];

        return view('admin.drivers.index', compact('drivers', 'stats'));
    }

    /**
     * Show create driver form
     */
    public function create()
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a new driver
     */
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
            // Create user account
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'driver',
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // Create driver profile
            Driver::create([
                'user_id' => $user->id,
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
                'vehicle_type' => $validated['vehicle_type'],
                'vehicle_plate' => $validated['vehicle_plate'],
                'vehicle_capacity' => $validated['vehicle_capacity'],
                'availability_status' => 'available',
                'average_rating' => 0,
                'total_collections' => 0,
            ]);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver created successfully!');
    }

    /**
     * Display a specific driver
     */
    public function show(Driver $driver)
    {
        $driver->load('user');

        // Statistics
        $stats = [
            'total_collections' => $driver->total_collections,
            'average_rating' => $driver->average_rating,
            'completed_this_month' => Collection::where('driver_id', $driver->id)
                ->where('status', 'completed')
                ->whereMonth('completed_at', now()->month)
                ->count(),
            'total_routes' => Route::where('driver_id', $driver->id)->count(),
        ];

        // Recent collections
        $recentCollections = Collection::where('driver_id', $driver->id)
            ->with(['user', 'serviceType'])
            ->orderByDesc('scheduled_date')
            ->take(10)
            ->get();

        // Recent ratings
        $recentRatings = $driver->ratings()
            ->with(['user', 'collection. serviceType'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Upcoming assignments
        $upcomingCollections = Collection::where('driver_id', $driver->id)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('scheduled_date', '>=', today())
            ->with(['user', 'serviceType'])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        return view('admin.drivers.show', compact(
            'driver',
            'stats',
            'recentCollections',
            'recentRatings',
            'upcomingCollections'
        ));
    }

    /**
     * Show edit driver form
     */
    public function edit(Driver $driver)
    {
        $driver->load('user');
        return view('admin.drivers.edit', compact('driver'));
    }

    /**
     * Update a driver
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $driver->user_id,
            'phone' => 'required|string|max:20',
            'license_number' => 'required|string|max:50|unique: drivers,license_number,' . $driver->id,
            'license_expiry' => 'required|date',
            'vehicle_type' => 'required|string|max: 50',
            'vehicle_plate' => 'required|string|max:20|unique:drivers,vehicle_plate,' . $driver->id,
            'vehicle_capacity' => 'nullable|string|max:50',
            'availability_status' => 'required|in:available,on_route,offline,inactive',
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
                'vehicle_capacity' => $validated['vehicle_capacity'],
                'availability_status' => $validated['availability_status'],
            ]);
        });

        return redirect()->route('admin.drivers.show', $driver)
            ->with('success', 'Driver updated successfully!');
    }

    /**
     * Update driver status
     */
    public function updateStatus(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'availability_status' => 'required|in:available,on_route,offline,inactive',
        ]);

        $driver->update(['availability_status' => $validated['availability_status']]);

        return back()->with('success', 'Driver status updated.');
    }

    /**
     * Delete a driver
     */
    public function destroy(Driver $driver)
    {
        // Check if driver has pending collections
        $pendingCollections = Collection::where('driver_id', $driver->id)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->count();

        if ($pendingCollections > 0) {
            return back()->with('error', 'Cannot delete driver with pending collections. Please reassign them first.');
        }

        DB::transaction(function () use ($driver) {
            // Set driver_id to null on completed collections
            Collection::where('driver_id', $driver->id)->update(['driver_id' => null]);
            
            // Delete driver profile
            $driver->delete();
            
            // Optionally delete user account or mark as inactive
            $driver->user->update(['status' => 'inactive']);
        });

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver removed successfully.');
    }
}