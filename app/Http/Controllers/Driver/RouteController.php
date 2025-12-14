<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\RouteStop;
use App\Models\Collection;
use App\Models\Driver;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display list of driver's routes
     */
    public function index(Request $request)
    {
        $driver = $request->user()->driver;

        if (!$driver) {
            abort(403, 'You are not registered as a driver.');
        }

        $routes = Route::where('driver_id', $driver->id)
            ->with(['stops.collection. serviceType'])
            ->orderByDesc('date')
            ->paginate(10);

        return view('driver.routes.index', compact('routes', 'driver'));
    }

    /**
     * Display a specific route
     */
    public function show(Request $request, Route $route)
    {
        $driver = $request->user()->driver;

        if (!$driver || $route->driver_id !== $driver->id) {
            abort(403);
        }

        $route->load(['stops.collection.user', 'stops.collection.serviceType']);

        return view('driver.routes.show', compact('route', 'driver'));
    }

    /**
     * Start a route
     */
    public function start(Request $request, Route $route)
    {
        $driver = $request->user()->driver;

        if (!$driver || $route->driver_id !== $driver->id) {
            abort(403);
        }

        if ($route->status !== 'planned') {
            return back()->with('error', 'This route has already been started or completed.');
        }

        $route->update([
            'status' => 'in_progress',
            'start_time' => now()->format('H:i:s'),
        ]);

        // Update driver status
        $driver->update(['availability_status' => 'on_route']);

        // Update all collections to in_progress
        Collection::whereIn('id', $route->stops->pluck('collection_id'))
            ->where('status', 'confirmed')
            ->update(['status' => 'in_progress']);

        return back()->with('success', 'Route started!  Drive safely.');
    }

    /**
     * Complete a route
     */
    public function complete(Request $request, Route $route)
    {
        $driver = $request->user()->driver;

        if (!$driver || $route->driver_id !== $driver->id) {
            abort(403);
        }

        if ($route->status !== 'in_progress') {
            return back()->with('error', 'This route is not in progress.');
        }

        $route->update([
            'status' => 'completed',
            'end_time' => now()->format('H:i:s'),
        ]);

        // Update driver status
        $driver->update(['availability_status' => 'available']);

        return redirect()->route('driver.dashboard')
            ->with('success', 'Route completed successfully!  Great job! ');
    }

    /**
     * Mark arrival at a stop
     */
    public function arriveAtStop(Request $request, RouteStop $routeStop)
    {
        $driver = $request->user()->driver;

        if (!$driver || $routeStop->route->driver_id !== $driver->id) {
            abort(403);
        }

        $routeStop->markAsArrived();

        return back()->with('success', 'Arrival recorded.');
    }

    /**
     * Complete a stop
     */
    public function completeStop(Request $request, RouteStop $routeStop)
    {
        $driver = $request->user()->driver;

        if (!$driver || $routeStop->route->driver_id !== $driver->id) {
            abort(403);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $routeStop->markAsCompleted($validated['notes'] ?? null);

        // Update driver's total collections
        $driver->increment('total_collections');

        // Check if all stops are completed
        $route = $routeStop->route;
        if ($route->completed_stops >= $route->total_stops) {
            $route->markAsCompleted();
            $driver->update(['availability_status' => 'available']);
        }

        return back()->with('success', 'Collection completed! ');
    }

    /**
     * Skip a stop
     */
    public function skipStop(Request $request, RouteStop $routeStop)
    {
        $driver = $request->user()->driver;

        if (!$driver || $routeStop->route->driver_id !== $driver->id) {
            abort(403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $routeStop->update([
            'status' => 'skipped',
            'notes' => $validated['reason'],
        ]);

        // Update collection status
        $routeStop->collection->update([
            'status' => 'missed',
            'driver_notes' => $validated['reason'],
        ]);

        return back()->with('info', 'Stop skipped.');
    }

    /**
     * Update driver's current location
     */
    public function updateLocation(Request $request)
    {
        $driver = $request->user()->driver;

        if (!$driver) {
            return response()->json(['error' => 'Not a driver'], 403);
        }

        $validated = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $driver->updateLocation($validated['latitude'], $validated['longitude']);

        return response()->json(['success' => true]);
    }
}