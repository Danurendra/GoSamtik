<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionSchedule;
use App\Models\ServiceType;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscriptions = Subscription:: where('user_id', $request->user()->id)
            ->with(['subscriptionPlan.serviceType', 'schedules'])
            ->orderByDesc('created_at')
            ->get();

        return view('subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::active()->ordered()->with(['subscriptionPlans' => function($q) {
            $q->active()->orderBy('frequency_per_week');
        }])->get();

        return view('subscriptions.create', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'service_address' => 'required|string|max:500',
            'selected_days' => 'required|array|min:1',
            'selected_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);

        // Validate number of selected days matches plan frequency
        if (count($validated['selected_days']) !== $plan->frequency_per_week) {
            return back()->withErrors([
                'selected_days' => "Please select exactly {$plan->frequency_per_week} day(s) for this plan."
            ])->withInput();
        }

        $subscription = DB::transaction(function () use ($validated, $plan, $request) {
            // Create subscription
            $subscription = Subscription::create([
                'user_id' => $request->user()->id,
                'subscription_plan_id' => $plan->id,
                'service_address' => $validated['service_address'],
                'special_instructions' => $validated['special_instructions'] ?? null,
                'status' => 'active',
                'start_date' => now(),
                'next_billing_date' => now()->addMonth(),
            ]);

            // Create schedules for selected days
            foreach ($validated['selected_days'] as $day) {
                SubscriptionSchedule:: create([
                    'subscription_id' => $subscription->id,
                    'day_of_week' => $day,
                    'preferred_time_start' => $validated['time_start'],
                    'preferred_time_end' => $validated['time_end'],
                ]);
            }

            // Generate collections for the first month
            $this->generateRecurringCollections($subscription);

            return $subscription;
        });

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully!  Your recurring collections have been scheduled.');
    }

    public function show(Subscription $subscription)
    {
        $this->authorize('view', $subscription);

        $subscription->load(['subscriptionPlan. serviceType', 'schedules', 'collections' => function($q) {
            $q->orderByDesc('scheduled_date')->take(10);
        }]);

        // Get upcoming collections
        $upcomingCollections = $subscription->collections()
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->take(5)
            ->get();

        return view('subscriptions.show', compact('subscription', 'upcomingCollections'));
    }

    public function edit(Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $subscription->load(['subscriptionPlan.serviceType', 'schedules']);

        $serviceTypes = ServiceType::active()->ordered()->with(['subscriptionPlans' => function($q) {
            $q->active()->orderBy('frequency_per_week');
        }])->get();

        return view('subscriptions.edit', compact('subscription', 'serviceTypes'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $validated = $request->validate([
            'service_address' => 'required|string|max:500',
            'selected_days' => 'required|array|min:1',
            'selected_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time_start' => 'required|date_format:H:i',
            'time_end' => 'required|date_format:H:i|after:time_start',
            'special_instructions' => 'nullable|string|max:1000',
        ]);

        $plan = $subscription->subscriptionPlan;

        // Validate number of selected days matches plan frequency
        if (count($validated['selected_days']) !== $plan->frequency_per_week) {
            return back()->withErrors([
                'selected_days' => "Please select exactly {$plan->frequency_per_week} day(s) for this plan."
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $subscription) {
            // Update subscription
            $subscription->update([
                'service_address' => $validated['service_address'],
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            // Delete old schedules and create new ones
            $subscription->schedules()->delete();

            foreach ($validated['selected_days'] as $day) {
                SubscriptionSchedule::create([
                    'subscription_id' => $subscription->id,
                    'day_of_week' => $day,
                    'preferred_time_start' => $validated['time_start'],
                    'preferred_time_end' => $validated['time_end'],
                ]);
            }

            // Cancel future pending collections and regenerate
            $subscription->collections()
                ->where('scheduled_date', '>', now())
                ->where('status', 'pending')
                ->update(['status' => 'cancelled']);

            // Generate new collections
            $this->generateRecurringCollections($subscription);
        });

        return redirect()->route('subscriptions.show', $subscription)
            ->with('success', 'Subscription updated successfully!');
    }

    public function pause(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        if (!$subscription->canBePaused()) {
            return back()->with('error', 'This subscription cannot be paused.');
        }

        $validated = $request->validate([
            'paused_until' => 'required|date|after:today|before:' .  now()->addMonths(3)->format('Y-m-d'),
        ]);

        $subscription->update([
            'status' => 'paused',
            'paused_until' => $validated['paused_until'],
        ]);

        // Cancel upcoming collections during pause period
        $subscription->collections()
            ->where('scheduled_date', '>', now())
            ->where('scheduled_date', '<=', $validated['paused_until'])
            ->where('status', 'pending')
            ->update([
                'status' => 'cancelled',
                'cancellation_reason' => 'Subscription paused by customer',
            ]);

        return back()->with('success', 'Subscription paused until ' . Carbon::parse($validated['paused_until'])->format('M d, Y'));
    }

    public function resume(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        if ($subscription->status !== 'paused') {
            return back()->with('error', 'This subscription is not paused.');
        }

        $subscription->update([
            'status' => 'active',
            'paused_until' => null,
        ]);

        // Generate new collections from today
        $this->generateRecurringCollections($subscription);

        return back()->with('success', 'Subscription resumed successfully!  New collections have been scheduled.');
    }

    public function cancel(Request $request, Subscription $subscription)
    {
        $this->authorize('update', $subscription);

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        DB::transaction(function () use ($subscription, $validated) {
            $subscription->update([
                'status' => 'cancelled',
                'end_date' => now(),
            ]);

            // Cancel all future pending collections
            $subscription->collections()
                ->where('scheduled_date', '>', now())
                ->where('status', 'pending')
                ->update([
                    'status' => 'cancelled',
                    'cancellation_reason' => $validated['cancellation_reason'],
                ]);
        });

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription cancelled successfully.');
    }

    /**
     * Generate recurring collections for a subscription
     */
    protected function generateRecurringCollections(Subscription $subscription, int $weeksAhead = 4): void
    {
        $subscription->load(['schedules', 'subscriptionPlan.serviceType']);

        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addWeeks($weeksAhead)->endOfDay();

        $dayMapping = [
            'sunday' => Carbon::SUNDAY,
            'monday' => Carbon::MONDAY,
            'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon:: WEDNESDAY,
            'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY,
            'saturday' => Carbon:: SATURDAY,
        ];

        foreach ($subscription->schedules as $schedule) {
            $dayOfWeek = $dayMapping[$schedule->day_of_week];
            
            // Find the next occurrence of this day
            $currentDate = $startDate->copy();
            
            if ($currentDate->dayOfWeek !== $dayOfWeek) {
                $currentDate->next($dayOfWeek);
            }

            // Generate collections for each occurrence
            while ($currentDate <= $endDate) {
                // Check if collection already exists for this date
                $exists = Collection::where('subscription_id', $subscription->id)
                    ->whereDate('scheduled_date', $currentDate)
                    ->exists();

                // Check if it's a holiday
                $isHoliday = \App\Models\Holiday::isHoliday($currentDate);

                if (! $exists && !$isHoliday) {
                    Collection::create([
                        'user_id' => $subscription->user_id,
                        'subscription_id' => $subscription->id,
                        'service_type_id' => $subscription->subscriptionPlan->service_type_id,
                        'collection_type' => 'recurring',
                        'scheduled_date' => $currentDate->toDateString(),
                        'scheduled_time_start' => $schedule->preferred_time_start,
                        'scheduled_time_end' => $schedule->preferred_time_end,
                        'service_address' => $subscription->service_address,
                        'special_instructions' => $subscription->special_instructions,
                        'total_amount' => $subscription->subscriptionPlan->per_pickup_price,
                        'status' => 'pending',
                    ]);
                }

                // Move to next week
                $currentDate->addWeek();
            }
        }
    }
}