<?php

namespace App\Events;

use App\Models\ServiceOrder;
use App\Models\Enums\OrderStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ServiceOrder $order,
        public OrderStatus $oldStatus,
        public OrderStatus $newStatus,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('orders.' . $this->order->id),
            new Channel('notifications'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
            'new_status_label' => $this->newStatus->label(),
            'vehicle' => $this->order->vehicle->full_name,
            'client_id' => $this->order->client_id,
            'updated_at' => $this->order->updated_at->toISOString(),
        ];
    }
}
