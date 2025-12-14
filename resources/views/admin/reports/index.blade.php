<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Payment;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Driver;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $dateRange = $this->getDateRange($period);

        // Revenue Statistics
        $revenueStats = $this->getRevenueStats($dateRange);

        // Collection Statistics
        $collectionStats = $this->getCollectionStats($dateRange);

        // Customer Statistics
        $customerStats = $this->getCustomerStats($dateRange);

        // Top Services
        $topServices = $this->getTopServices($dateRange);

        // Driver Performance
        $driverPerformance = $this->getDriverPerformance($dateRange);

        // Chart Data
        $revenueChartData = $this->getRevenueChartData($period);
        $collectionChartData = $this->getCollectionChartData($period);

        return view('admin.reports.index', compact(
            'period',
            'dateRange',
            'revenueStats',
            'collectionStats',
            'customerStats',
            'topServices',
            'driverPerformance',
            'revenueChartData',
            'collectionChartData'
        ));
    }

    /**
     * Get date range based on period
     */
    protected function getDateRange(string $period): array
    {
        return match($period) {
            'today' => [
                'start' => Carbon::today(),
                'end' => Carbon:: today()->endOfDay(),
            ],
            'this_week' => [
                'start' => Carbon::now()->startOfWeek(),
                'end' => Carbon::now()->endOfWeek(),
            ],
            'this_month' => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
            'last_month' => [
                'start' => Carbon::now()->subMonth()->startOfMonth(),
                'end' => Carbon::now()->subMonth()->endOfMonth(),
            ],
            'this_year' => [
                'start' => Carbon::now()->startOfYear(),
                'end' => Carbon::now()->endOfYear(),
            ],
            default => [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth(),
            ],
        };
    }

    /**
     * Get revenue statistics
     */
    protected function getRevenueStats(array $dateRange): array
    {
        $currentRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->sum('total_amount');

        $previousStart = $dateRange['start']->copy()->subDays($dateRange['start']->diffInDays($dateRange['end']));
        $previousEnd = $dateRange['start']->copy()->subDay();

        $previousRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$previousStart, $previousEnd])
            ->sum('total_amount');

        $growth = $previousRevenue > 0 
            ? round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100, 1)
            : 0;

        return [
            'current' => $currentRevenue,
            'previous' => $previousRevenue,
            'growth' => $growth,
            'transaction_count' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->count(),
        ];
    }

    /**
     * Get collection statistics
     */
    protected function getCollectionStats(array $dateRange): array
    {
        $baseQuery = Collection::whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']]);

        return [
            'total' => (clone $baseQuery)->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'pending' => (clone $baseQuery)->whereIn('status', ['pending', 'confirmed'])->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
            'missed' => (clone $baseQuery)->where('status', 'missed')->count(),
            'completion_rate' => $this->calculateCompletionRate($dateRange),
        ];
    }

    /**
     * Calculate completion rate
     */
    protected function calculateCompletionRate(array $dateRange): float
    {
        $total = Collection::whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']])
            ->whereIn('status', ['completed', 'cancelled', 'missed'])
            ->count();

        if ($total === 0) return 0;

        $completed = Collection::whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']])
            ->where('status', 'completed')
            ->count();

        return round(($completed / $total) * 100, 1);
    }

    /**
     * Get customer statistics
     */
    protected function getCustomerStats(array $dateRange): array
    {
        return [
            'total' => User::where('role', 'customer')->count(),
            'new' => User::where('role', 'customer')
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->count(),
            'active_subscribers' => Subscription::where('status', 'active')->count(),
            'churned' => Subscription::where('status', 'cancelled')
                ->whereBetween('updated_at', [$dateRange['start'], $dateRange['end']])
                ->count(),
        ];
    }

    /**
     * Get top services
     */
    protected function getTopServices(array $dateRange): \Illuminate\Support\Collection
    {
        return ServiceType::withCount(['collections' => function ($query) use ($dateRange) {
                $query->whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']]);
            }])
            ->withSum(['collections' => function ($query) use ($dateRange) {
                $query->whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']])
                    ->where('status', 'completed');
            }], 'total_amount')
            ->orderByDesc('collections_count')
            ->take(5)
            ->get();
    }

    /**
     * Get driver performance
     */
    protected function getDriverPerformance(array $dateRange): \Illuminate\Support\Collection
    {
        return Driver::with('user')
            ->withCount(['collections' => function ($query) use ($dateRange) {
                $query->whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']])
                    ->where('status', 'completed');
            }])
            ->orderByDesc('collections_count')
            ->take(5)
            ->get();
    }

    /**
     * Get revenue chart data
     */
    protected function getRevenueChartData(string $period): array
    {
        $labels = [];
        $data = [];

        if ($period === 'this_year') {
            // Monthly data for the year
            for ($i = 1; $i <= 12; $i++) {
                $date = Carbon::create(null, $i, 1);
                $labels[] = $date->format('M');
                $data[] = Payment::where('status', 'completed')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $i)
                    ->sum('total_amount');
            }
        } else {
            // Daily data for shorter periods
            $days = match($period) {
                'today' => 1,
                'this_week' => 7,
                'this_month', 'last_month' => 30,
                default => 30,
            };

            $startDate = $period === 'last_month' 
                ? Carbon::now()->subMonth()->startOfMonth()
                : Carbon::now()->subDays($days - 1);

            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $labels[] = $date->format('M d');
                $data[] = Payment::where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->sum('total_amount');
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get collection chart data
     */
    protected function getCollectionChartData(string $period): array
    {
        $labels = [];
        $completed = [];
        $cancelled = [];

        $days = match($period) {
            'today' => 1,
            'this_week' => 7,
            'this_month', 'last_month' => 30,
            'this_year' => 12,
            default => 30,
        };

        if ($period === 'this_year') {
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = Carbon::create(null, $i, 1)->format('M');
                $completed[] = Collection::where('status', 'completed')
                    ->whereYear('scheduled_date', now()->year)
                    ->whereMonth('scheduled_date', $i)
                    ->count();
                $cancelled[] = Collection:: where('status', 'cancelled')
                    ->whereYear('scheduled_date', now()->year)
                    ->whereMonth('scheduled_date', $i)
                    ->count();
            }
        } else {
            $startDate = $period === 'last_month'
                ? Carbon::now()->subMonth()->startOfMonth()
                : Carbon::now()->subDays($days - 1);

            for ($i = 0; $i < min($days, 30); $i++) {
                $date = $startDate->copy()->addDays($i);
                $labels[] = $date->format('M d');
                $completed[] = Collection::where('status', 'completed')
                    ->whereDate('scheduled_date', $date)
                    ->count();
                $cancelled[] = Collection::where('status', 'cancelled')
                    ->whereDate('scheduled_date', $date)
                    ->count();
            }
        }

        return [
            'labels' => $labels,
            'completed' => $completed,
            'cancelled' => $cancelled,
        ];
    }

    /**
     * Export report as CSV
     */
    public function export(Request $request)
    {
        $period = $request->get('period', 'this_month');
        $type = $request->get('type', 'collections');
        $dateRange = $this->getDateRange($period);

        $filename = "{$type}_report_{$period}_" . now()->format('Y-m-d') . ". csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($type, $dateRange) {
            $file = fopen('php://output', 'w');

            if ($type === 'collections') {
                fputcsv($file, ['ID', 'Customer', 'Service', 'Date', 'Status', 'Amount', 'Driver']);

                Collection::whereBetween('scheduled_date', [$dateRange['start'], $dateRange['end']])
                    ->with(['user', 'serviceType', 'driver. user'])
                    ->orderBy('scheduled_date')
                    ->chunk(100, function ($collections) use ($file) {
                        foreach ($collections as $collection) {
                            fputcsv($file, [
                                $collection->id,
                                $collection->user->name,
                                $collection->serviceType->name,
                                $collection->scheduled_date->format('Y-m-d'),
                                $collection->status,
                                $collection->total_amount,
                                $collection->driver? ->user?->name ?? 'Unassigned',
                            ]);
                        }
                    });
            } elseif ($type === 'revenue') {
                fputcsv($file, ['ID', 'Customer', 'Type', 'Amount', 'Method', 'Status', 'Date']);

                Payment::whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                    ->with('user')
                    ->orderBy('created_at')
                    ->chunk(100, function ($payments) use ($file) {
                        foreach ($payments as $payment) {
                            fputcsv($file, [
                                $payment->id,
                                $payment->user->name,
                                $payment->payment_type,
                                $payment->total_amount,
                                $payment->payment_method,
                                $payment->status,
                                $payment->created_at->format('Y-m-d H:i:s'),
                            ]);
                        }
                    });
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}