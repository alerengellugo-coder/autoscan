<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/*
|--------------------------------------------------------------------------
| AutoScan Custom Channels
|--------------------------------------------------------------------------
|
| Custom channels for the AutoScan workshop management system.
*/

// Channel for workshop-specific notifications
Broadcast::channel('workshop.{workshopId}', function ($user, $workshopId) {
    return $user->workshops()->where('id', $workshopId)->exists();
});

// Channel for order status updates
Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    return $user->can('view', \App\Models\Order::findOrFail($orderId));
});

// Channel for real-time task assignments
Broadcast::channel('tasks.{mechanicId}', function ($user, $mechanicId) {
    return (int) $user->id === (int) $mechanicId;
});

// Channel for workshop-wide announcements
Broadcast::channel('announcements.{workshopId}', function ($user, $workshopId) {
    return $user->workshops()->where('id', $workshopId)->exists();
});

// Channel for inventory alerts
Broadcast::channel('inventory-alerts.{workshopId}', function ($user, $workshopId) {
    return $user->can('manage-inventory') && $user->workshops()->where('id', $workshopId)->exists();
});
