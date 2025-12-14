<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\User;
use App\Models\Payment;
use App\Models\Driver;
use App\Models\Subscription;
use App\Models\Complaint;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Today's statistics
        $todayStats = [
            'collections_today' => Collection::whereDate('scheduled_date', today())->count(),
            'completed_today' => Collection::whereDate('scheduled_date', today())->where('status', 'completed')->count(),
            'pending_today' => Collection::whereDate('scheduled_date', today())->whereIn('status', ['pending', 'confirmed'])->count(),
        ];

        // Overall statistics
        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_drivers' => Driver::count(),
            'active_subscriptions' => Subscription:: where('status', 'active')->count(),
            'total_collections' => Collection::count(),
            'completed_collections' => Collection::where('status', 'completed')->count(),
            'pending_collections' => Collection::whereIn('status', ['pending', 'confirmed'])->count(),
            'open_complaints' => Complaint::whereIn('status', ['open', 'in_progress'])->count(),
        ];

        // Revenue statistics
        $revenue = [
            'total' => Payment::where('status', 'completed')->sum('total_amount'),
            'this_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'last_month' => Payment::where('status', 'completed')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('total_amount'),
        ];

        // Calculate growth percentage
        $revenue['growth'] = $revenue['last_month'] > 0 
            ? round((($revenue['this_month'] - $revenue['last_month']) / $revenue['last_month']) * 100, 1)
            : 0;

        // Recent collections
        $recentCollections = Collection::with(['user', 'serviceType', 'driver.user'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Upcoming collections for today
        $todayCollections = Collection::with(['user', 'serviceType', 'driver.user'])
            ->whereDate('scheduled_date', today())
            ->orderBy('scheduled_time_start')
            ->get();

        // Available drivers
        $availableDrivers = Driver::with('user')
            ->where('availability_status', 'available')
            ->get();

        // Recent complaints
        $recentComplaints = Complaint::with('user')
            ->whereIn('status', ['open', 'in_progress'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'todayStats',
            'stats',
            'revenue',
            'recentCollections',
            'todayCollections',
            'availableDrivers',
            'recentComplaints'
        ));
    }

    public function reports()
    {
        // TODO: Implement reports page
        return view('admin.reports');
    }

    public function exportReport(Request $request)
    {
        // TODO: Implement report export
    }
}