<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder;

class OrderDelivered extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public ServiceOrder $order,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $vehicle = $this->order->vehicle;
        $reports = $this->order->reports;
        $reportCount = $reports ? $reports->count() : 0;

        return (new MailMessage)
            ->subject("Entrega: Orden de servicio #{$this->order->order_number} completada")
            ->greeting("Hola {$notifiable->name},")
            ->line("Tu vehículo está listo para ser retirado.")
            ->line("**Orden:** #{$this->order->order_number}")
            ->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})")
            ->line("**Informes de servicio:** {$reportCount}")
            ->line('Puedes pasar a recoger tu vehículo en nuestro taller en horario laboral.')
            ->line('Si tienes alguna pregunta sobre el servicio realizado, no dudes en contactarnos.')
            ->action('Ver Detalles de la Orden', url("/mi-cuenta/ordenes/{$this->order->id}"))
            ->line('Gracias por confiar en AutoScan.');
    }

    public function toDatabase(object $notifiable): array
    {
        $vehicle = $this->order->vehicle;

        return [
            'order_id'      => $this->order->id,
            'order_number'  => $this->order->order_number,
            'vehicle'       => [
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'plate' => $vehicle->plate,
            ],
            'title'         => "Entrega: Orden #{$this->order->order_number}",
            'message'       => "Tu vehículo {$vehicle->brand} {$vehicle->model} está listo para recoger.",
            'type'          => 'order_delivered',
            'url'           => url("/mi-cuenta/ordenes/{$this->order->id}"),
        ];
    }
}
