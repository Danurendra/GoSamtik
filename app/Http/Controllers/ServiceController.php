<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display all available services (public page)
     */
    public function index()
    {
        $serviceTypes = ServiceType::active()
            ->ordered()
            ->with(['subscriptionPlans' => function($query) {
                $query->active()->orderBy('frequency_per_week');
            }])
            ->get();

        return view('services.index', compact('serviceTypes'));
    }

    /**
     * Display a specific service type details
     */
    public function show(ServiceType $serviceType)
    {
        if (!$serviceType->is_active) {
            abort(404);
        }

        $serviceType->load(['subscriptionPlans' => function($query) {
            $query->active()->orderBy('frequency_per_week');
        }]);

        // Get related services
        $relatedServices = ServiceType::active()
            ->where('id', '!=', $serviceType->id)
            ->ordered()
            ->take(3)
            ->get();

        return view('services.show', compact('serviceType', 'relatedServices'));
    }

    /**
     * Display pricing page
     */
    public function pricing()
    {
        $serviceTypes = ServiceType::active()
            ->ordered()
            ->with(['subscriptionPlans' => function($query) {
                $query->active()->orderBy('frequency_per_week');
            }])
            ->get();

        // Get the most popular plans
        $popularPlans = SubscriptionPlan::active()
            ->where('is_popular', true)
            ->with('serviceType')
            ->get();

        return view('services.pricing', compact('serviceTypes', 'popularPlans'));
    }
}