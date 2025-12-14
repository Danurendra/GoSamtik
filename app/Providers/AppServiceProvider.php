<?php

namespace App\Providers;

use App\Models\Collection;
use App\Models\Subscription;
use App\Models\Complaint;
use App\Policies\CollectionPolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\ComplaintPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register Policies (optional in Laravel 11 if following naming conventions)
        Gate::policy(Collection::class, CollectionPolicy::class);
        Gate::policy(Subscription::class, SubscriptionPolicy::class);
        Gate::policy(Complaint::class, ComplaintPolicy::class);
    }
}