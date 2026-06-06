<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerGates();
    }

    /**
     * Define authorization gates for role-based access control.
     */
    protected function registerGates(): void
    {
        // Gate: Is Admin
        Gate::define('is-admin', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Is Technician
        Gate::define('is-technician', function ($user) {
            return $user->hasRole('technician');
        });

        // Gate: Is Client
        Gate::define('is-client', function ($user) {
            return $user->hasRole('client');
        });

        // Gate: Manage Users (admin only)
        Gate::define('manage-users', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Manage Vehicles (admin, technician can view)
        Gate::define('manage-vehicles', function ($user) {
            return $user->hasAnyRole(['admin', 'technician']);
        });

        // Gate: Manage Orders
        Gate::define('manage-orders', function ($user) {
            return $user->hasAnyRole(['admin', 'technician']);
        });

        // Gate: Manage Products (admin only)
        Gate::define('manage-products', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Manage Quotations (admin only)
        Gate::define('manage-quotations', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Manage Sales (admin only)
        Gate::define('manage-sales', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: View Reports
        Gate::define('view-reports', function ($user) {
            return $user->hasAnyRole(['admin', 'technician', 'client']);
        });

        // Gate: Delete Reports (admin only)
        Gate::define('delete-reports', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Manage Settings (admin only)
        Gate::define('manage-settings', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: View Dashboard
        Gate::define('view-dashboard', function ($user) {
            return $user->hasAnyRole(['admin', 'technician', 'client']);
        });

        // Gate: Cancel Sale (admin only)
        Gate::define('cancel-sale', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Register Payment (admin only)
        Gate::define('register-payment', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Convert Quotation to Sale (admin only)
        Gate::define('convert-quotation', function ($user) {
            return $user->hasRole('admin');
        });

        // Gate: Generate PDF (admin only)
        Gate::define('generate-pdf', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
