<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Redirect based on user role
        if ($user->isAdmin() || $user->isProvider()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isDriver()) {
            return redirect()->route('driver.dashboard');
        }

        // Customer Dashboard
        return $this->customerDashboard($user);
    }

    /**
     * Customer Dashboard
     */
    protected function customerDashboard($user)
    {
        // Get upcoming collections
        $upcomingCollections = Collection::where('user_id', $user->id)
            ->upcoming()
            ->with(['serviceType', 'driver. user'])
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        // Get active subscriptions
        $activeSubscriptions = Subscription::where('user_id', $user->id)
            ->active()
            ->with(['subscriptionPlan. serviceType', 'schedules'])
            ->get();

        // Get recent collections
        $recentCollections = Collection::where('user_id', $user->id)
            ->completed()
            ->with(['serviceType', 'rating'])
            ->orderByDesc('completed_at')
            ->take(5)
            ->get();

        // Statistics
        $stats = [
            'total_collections' => Collection::where('user_id', $user->id)->completed()->count(),
            'active_subscriptions' => $activeSubscriptions->count(),
            'pending_collections' => Collection::where('user_id', $user->id)->pending()->count(),
            'total_spent' => Payment::where('user_id', $user->id)->completed()->sum('total_amount'),
        ];

        return view('dashboard', compact(
            'upcomingCollections',
            'activeSubscriptions',
            'recentCollections',
            'stats'
        ));
    }
}