<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder;

class OrderCheckedIn extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject("Check-in: Orden de servicio #{$this->order->order_number}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Hemos recibido su vehículo en nuestro taller. A continuación los detalles:")
            ->line("**Orden:** #{$this->order->order_number}")
            ->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})")
            ->line("**Tipo de servicio:** {$this->order->service_type->label()}")
            ->line("**Descripción:** {$this->order->description}")
            ->line('Nuestros técnicos realizarán un escaneo y diagnóstico completo. Le enviaremos una cotización una vez terminado.')
            ->action('Seguir mi Orden', url("/mi-cuenta/ordenes/{$this->order->id}"))
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
            'title'         => "Check-in: Orden #{$this->order->order_number}",
            'message'       => "Tu vehículo {$vehicle->brand} {$vehicle->model} ha sido recibido en el taller.",
            'type'          => 'order_check_in',
            'url'           => url("/mi-cuenta/ordenes/{$this->order->id}"),
        ];
    }
}
