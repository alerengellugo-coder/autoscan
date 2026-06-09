<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendOrderStatusNotification implements ShouldQueue
{
    /**
     * The name of the connection the job should be sent to.
     */
    public string $connection = 'database';

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 30;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * Sends a notification to the vehicle owner (client) when the
     * status of a service order changes.
     */
    public function handle(OrderStatusChanged $event): void
    {
        $order = $event->order;
        $vehicle = $order->vehicle;

        // Ensure the vehicle and client exist
        if (!$vehicle || !$vehicle->client) {
            Log::warning("OrderStatusChanged: Order #{$order->order_number} has no vehicle or no client associated. Notification skipped.");
            return;
        }

        $client = $vehicle->client;

        // Check if auto-notify setting is enabled
        try {
            $autoNotify = \Illuminate\Support\Facades\DB::table('settings')
                ->where('key', 'auto_notify_clients')
                ->value('value');

            if ($autoNotify !== '1' && $autoNotify !== 1 && $autoNotify !== true) {
                Log::info("OrderStatusChanged: Auto-notify is disabled. Notification for order #{$order->order_number} was not sent.");
                return;
            }
        } catch (\Exception $e) {
            // If settings table doesn't exist or fails, default to sending
            Log::warning("OrderStatusChanged: Could not check auto_notify_clients setting. Defaulting to send notification.");
        }

        // Send the notification to the client
        try {
            $client->notify(new OrderStatusUpdated(
                order: $order,
                oldStatus: $event->oldStatus,
                newStatus: $event->newStatus,
            ));

            Log::info("OrderStatusChanged: Notification sent to client {$client->name} ({$client->email}) for order #{$order->order_number}");
        } catch (\Throwable $e) {
            Log::warning("OrderStatusChanged: Failed to notify client {$client->name} for order #{$order->order_number}: {$e->getMessage()}");
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(OrderStatusChanged $event, \Throwable $exception): void
    {
        Log::error("SendOrderStatusNotification failed for order #{$event->order->order_number}: {$exception->getMessage()}");
    }
}
