<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';
    
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }
    
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => function () use ($request) {
                try {
                    $ziggy = \Tightenco\Ziggy\Ziggy::json()->toArray();
                    return [
                        'location' => $request->url(),
                        'aside' => $ziggy['aside'] ?? [],
                    ];
                } catch (\Throwable $e) {
                    return [
                        'location' => $request->url(),
                        'aside' => [],
                    ];
                }
            },
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            'notifications' => [
                'count' => $request->user() ? $request->user()->unreadNotifications()->count() : 0,
            ],
            'app' => [
                'name' => config('app.name'),
                'env' => config('app.env'),
            ],
            'settings' => [
                'workshop_name' => \App\Models\Setting::get('workshop_name', 'AutoScan'),
                'currency' => \App\Models\Setting::get('currency', 'USD'),
                'tax_percentage' => \App\Models\Setting::get('tax_percentage', 16),
            ],
        ]);
    }
}
