<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Quotation;

class QuotationApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Quotation $quotation,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->quotation->serviceOrder;
        $vehicle = $order ? $order->vehicle : null;

        return (new MailMessage)
            ->subject("Cotización #{$this->quotation->quotation_number} aprobada")
            ->greeting("Hola {$notifiable->name},")
            ->line("Le informamos que el cliente ha aprobado la cotización.")
            ->line("**Cotización:** #{$this->quotation->quotation_number}")
            ->line("**Total:** $" . number_format($this->quotation->total, 2))
            ->when($vehicle, fn ($mail) => $mail->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})"))
            ->line('Puede proceder con el servicio correspondiente.')
            ->action('Ver Cotización', url("/admin/cotizaciones/{$this->quotation->id}"))
            ->line('Gracias por confiar en AutoScan.');
    }

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
