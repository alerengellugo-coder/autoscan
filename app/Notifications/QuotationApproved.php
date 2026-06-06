<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Quotation;

class QuotationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Quotation $quotation,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $client = $this->quotation->client;
        $order = $this->quotation->serviceOrder;

        return [
            'quotation_id'      => $this->quotation->id,
            'quotation_number'  => $this->quotation->quotation_number,
            'client_name'       => $client ? $client->name : 'Cliente',
            'order_id'          => $order ? $order->id : null,
            'order_number'      => $order ? $order->order_number : null,
            'total'             => $this->quotation->total,
            'title'             => "Cotización #{$this->quotation->quotation_number} aprobada",
            'message'           => "El cliente {$client?->name} ha aprobado la cotización por $" . number_format($this->quotation->total, 2),
            'type'              => 'quotation_approved',
            'url'               => url("/admin/cotizaciones/{$this->quotation->id}"),
        ];
    }
}
