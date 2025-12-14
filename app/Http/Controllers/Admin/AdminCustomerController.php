<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Collection;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    /**
     * Display all customers
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')
            ->withCount(['collections', 'subscriptions']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $customers = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        // Stats
        $stats = [
            'total' => User::where('role', 'customer')->count(),
            'active' => User::where('role', 'customer')->where('status', 'active')->count(),
            'new_this_month' => User::where('role', 'customer')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Display a specific customer
     */
    public function show(User $user)
    {
        if ($user->role !== 'customer') {
            abort(404);
        }

        $user->load(['collections. serviceType', 'subscriptions.subscriptionPlan']);

        // Get statistics
        $stats = [
            'total_collections' => $user->collections()->count(),
            'completed_collections' => $user->collections()->where('status', 'completed')->count(),
            'active_subscriptions' => $user->subscriptions()->where('status', 'active')->count(),
            'total_spent' => Payment::where('user_id', $user->id)->where('status', 'completed')->sum('total_amount'),
        ];

        // Recent collections
        $recentCollections = $user->collections()
            ->with('serviceType')
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        // Active subscriptions
        $subscriptions = $user->subscriptions()
            ->with('subscriptionPlan. serviceType')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.customers.show', compact('user', 'stats', 'recentCollections', 'subscriptions'));
    }

    /**
     * Update customer status
     */
    public function updateStatus(Request $request, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $user->update(['status' => $validated['status']]);

        return back()->with('success', 'Customer status updated.');
    }
}