<?php

namespace App\Services;

use App\Models\Collection;
use App\Models\Driver;
use App\Models\Route;
use App\Models\RouteStop;
use Carbon\Carbon;
use Illuminate\Support\Collection as LaravelCollection;

class RouteGenerationService
{
    /**
     * Generate routes for a specific date
     */
    public function generateRoutesForDate(Carbon $date): array
    {
        // Get all confirmed/pending collections for the date that don't have a driver assigned
        $collections = Collection::whereDate('scheduled_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereNull('driver_id')
            ->with(['user', 'serviceType'])
            ->orderBy('scheduled_time_start')
            ->get();

        if ($collections->isEmpty()) {
            return ['routes_created' => 0, 'collections_assigned' => 0];
        }

        // Get available drivers
        $drivers = Driver:: where('availability_status', 'available')
            ->with('user')
            ->get();

        if ($drivers->isEmpty()) {
            return ['routes_created' => 0, 'collections_assigned' => 0, 'error' => 'No available drivers'];
        }

        $routesCreated = 0;
        $collectionsAssigned = 0;

        // Simple assignment:  distribute collections evenly among drivers
        $collectionsPerDriver = ceil($collections->count() / $drivers->count());

        foreach ($drivers as $driverIndex => $driver) {
            // Get collections for this driver
            $driverCollections = $collections->slice(
                $driverIndex * $collectionsPerDriver,
                $collectionsPerDriver
            );

            if ($driverCollections->isEmpty()) {
                continue;
            }

            // Create route
            $route = Route::create([
                'driver_id' => $driver->id,
                'date' => $date,
                'status' => 'planned',
                'total_stops' => $driverCollections->count(),
                'completed_stops' => 0,
            ]);

            // Create route stops
            $sequenceOrder = 1;
            foreach ($driverCollections as $collection) {
                RouteStop::create([
                    'route_id' => $route->id,
                    'collection_id' => $collection->id,
                    'sequence_order' => $sequenceOrder++,
                    'status' => 'pending',
                ]);

                // Assign driver to collection
                $collection->update([
                    'driver_id' => $driver->id,
                    'status' => 'confirmed',
                ]);

                $collectionsAssigned++;
            }

            $routesCreated++;
        }

        return [
            'routes_created' => $routesCreated,
            'collections_assigned' => $collectionsAssigned,
        ];
    }

    /**
     * Generate routes for tomorrow (to be called by scheduler)
     */
    public function generateRoutesForTomorrow(): array
    {
        return $this->generateRoutesForDate(Carbon::tomorrow());
    }

    /**
     * Optimize existing route (simple optimization by time)
     */
    public function optimizeRoute(Route $route): void
    {
        $stops = $route->stops()
            ->with('collection')
            ->get()
            ->sortBy(function ($stop) {
                return $stop->collection->scheduled_time_start;
            });

        $sequenceOrder = 1;
        foreach ($stops as $stop) {
            $stop->update(['sequence_order' => $sequenceOrder++]);
        }
    }

    /**
     * Reassign a collection to a different driver
     */
    public function reassignCollection(Collection $collection, Driver $newDriver): bool
    {
        // Remove from existing route if any
        $existingStop = RouteStop::where('collection_id', $collection->id)->first();
        if ($existingStop) {
            $oldRoute = $existingStop->route;
            $existingStop->delete();
            
            // Update old route stop count
            $oldRoute->update([
                'total_stops' => $oldRoute->stops()->count(),
            ]);

            // Reorder remaining stops
            $this->reorderRouteStops($oldRoute);
        }

        // Find or create route for new driver
        $route = Route::firstOrCreate(
            [
                'driver_id' => $newDriver->id,
                'date' => $collection->scheduled_date,
            ],
            [
                'status' => 'planned',
                'total_stops' => 0,
                'completed_stops' => 0,
            ]
        );

        // Add new stop
        $maxSequence = $route->stops()->max('sequence_order') ?? 0;
        
        RouteStop::create([
            'route_id' => $route->id,
            'collection_id' => $collection->id,
            'sequence_order' => $maxSequence + 1,
            'status' => 'pending',
        ]);

        // Update route stop count
        $route->update([
            'total_stops' => $route->stops()->count(),
        ]);

        // Update collection
        $collection->update([
            'driver_id' => $newDriver->id,
            'status' => 'confirmed',
        ]);

        return true;
    }

    /**
     * Reorder stops in a route after deletion
     */
    protected function reorderRouteStops(Route $route): void
    {
        $stops = $route->stops()->orderBy('sequence_order')->get();
        
        $sequenceOrder = 1;
        foreach ($stops as $stop) {
            $stop->update(['sequence_order' => $sequenceOrder++]);
        }
    }
}