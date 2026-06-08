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

// Channel for private user notifications
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Channel for service order updates
Broadcast::channel('orders.{orderId}', function ($user, $orderId) {
    // Admins and technicians can listen to any order channel
    if ($user->hasRole(['admin', 'technician'])) {
        return true;
    }

    // Clients can only listen to orders that belong to their vehicles
    $order = \App\Models\ServiceOrder::find($orderId);

    return $order && $order->vehicle && (int) $order->vehicle->client_id === (int) $user->id;
});

// Channel for global notifications (admin & technician only)
Broadcast::channel('notifications', function ($user) {
    return $user->hasRole(['admin', 'technician']);
});
