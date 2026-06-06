<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Share common data with all Inertia responses
        Inertia::share([
            // Authenticated user
            'auth' => function () {
                $user = Auth::user();

                return [
                    'user' => $user ? [
                        'id'         => $user->id,
                        'name'       => $user->name,
                        'email'      => $user->email,
                        'phone'      => $user->phone,
                        'avatar'     => $user->avatar,
                        'roles'      => $user->getRoleNames()->toArray(),
                        'permissions' => $user->getAllPermissions()->pluck('name')->toArray(),
                    ] : null,
                ];
            },

            // Flash messages (success, error, warning, info)
            'flash' => function () {
                return [
                    'success' => Session::get('success'),
                    'error'   => Session::get('error'),
                    'warning' => Session::get('warning'),
                    'info'    => Session::get('info'),
                ];
            },

            // Unread notification count
            'notificationCount' => function () {
                return Auth::user() ? Auth::user()->unreadNotifications()->count() : 0;
            },

            // Application name from settings
            'appName' => function () {
                try {
                    $setting = DB::table('settings')->where('key', 'workshop_name')->first();
                    return $setting ? $setting->value : config('app.name', 'AutoScan');
                } catch (\Exception $e) {
                    return config('app.name', 'AutoScan');
                }
            },

            // General settings (cached for performance)
            'settings' => function () {
                try {
                    return DB::table('settings')->pluck('value', 'key')->toArray();
                } catch (\Exception $e) {
                    return [];
                }
            },

            // Current locale
            'locale' => function () {
                return app()->getLocale();
            },

            // CSRF token
            'csrf_token' => function () {
                return csrf_token();
            },
        ]);
    }
}
