<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Route;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DriverDashboardController extends Controller
{
    public function index(Request $request)
    {
        $driver = $request->user()->driver;

        if (!$driver) {
            abort(403, 'You are not registered as a driver.');
        }

        // Today's route
        $todayRoute = Route::where('driver_id', $driver->id)
            ->whereDate('date', today())
            ->with(['stops.collection. user', 'stops.collection.serviceType'])
            ->first();

        // Today's collections
        $todayCollections = Collection::where('driver_id', $driver->id)
            ->whereDate('scheduled_date', today())
            ->with(['user', 'serviceType'])
            ->orderBy('scheduled_time_start')
            ->get();

        // Statistics
        $stats = [
            'total_collections' => $driver->total_collections,
            'today_collections' => $todayCollections->count(),
            'completed_today' => $todayCollections->where('status', 'completed')->count(),
            'pending_today' => $todayCollections->whereIn('status', ['pending', 'confirmed', 'in_progress'])->count(),
            'average_rating' => $driver->average_rating,
        ];

        // This week's collections
        $weekCollections = Collection::where('driver_id', $driver->id)
            ->whereBetween('scheduled_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->with(['user', 'serviceType'])
            ->orderBy('scheduled_date')
            ->orderBy('scheduled_time_start')
            ->get()
            ->groupBy(function ($collection) {
                return $collection->scheduled_date->format('Y-m-d');
            });

        // Recent ratings
        $recentRatings = Rating::where('driver_id', $driver->id)
            ->with(['user', 'collection. serviceType'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('driver.dashboard', compact(
            'driver',
            'todayRoute',
            'todayCollections',
            'stats',
            'weekCollections',
            'recentRatings'
        ));
    }
}