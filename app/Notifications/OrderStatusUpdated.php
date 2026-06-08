<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceOrder;
use App\Models\Enums\OrderStatus;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ServiceOrder $order,
        public OrderStatus $oldStatus,
        public OrderStatus $newStatus,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'pending'     => 'Pendiente',
            'in_progress' => 'En Progreso',
            'waiting'     => 'En Espera',
            'completed'   => 'Completada',
            'cancelled'   => 'Cancelada',
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus->value] ?? $this->oldStatus->value;
        $newStatusLabel = $statusLabels[$this->newStatus->value] ?? $this->newStatus->value;

        $mail = (new MailMessage)
            ->subject("Actualización de su orden de servicio #{$this->order->order_number}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Le informamos que el estado de su orden de servicio ha sido actualizado.")
            ->line("**Orden:** #{$this->order->order_number}")
            ->line("**Vehículo:** {$this->order->vehicle->brand} {$this->order->vehicle->model} ({$this->order->vehicle->plate})")
            ->line("**Estado anterior:** {$oldStatusLabel}")
            ->line("**Nuevo estado:** {$newStatusLabel}");

        // Add contextual message based on new status
        if ($this->newStatus->value === 'completed') {
            $mail->line('Su vehículo ha sido diagnosticado y reparado exitosamente. Puede pasar a recogerlo en nuestro taller.')
                 ->action('Ver Orden de Servicio', url("/mi-cuenta/ordenes/{$this->order->id}"));
        } elseif ($this->newStatus->value === 'waiting') {
            $mail->line('Estamos esperando la disponibilidad de repuestos o su aprobación para continuar con el servicio.')
                 ->action('Ver Orden de Servicio', url("/mi-cuenta/ordenes/{$this->order->id}"));
        } elseif ($this->newStatus->value === 'in_progress') {
            $mail->line('Nuestros técnicos están trabajando en su vehículo. Le notificaremos cuando el servicio esté completo.')
                 ->action('Ver Orden de Servicio', url("/mi-cuenta/ordenes/{$this->order->id}"));
        } elseif ($this->newStatus->value === 'cancelled') {
            $mail->line('La orden de servicio ha sido cancelada. Si tiene alguna pregunta, no dude en contactarnos.');
        } else {
            $mail->action('Ver Orden de Servicio', url("/mi-cuenta/ordenes/{$this->order->id}"));
        }

        $mail->line('Gracias por confiar en AutoScan.');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'order_id'      => $this->order->id,
            'order_number'  => $this->order->order_number,
            'vehicle'       => [
                'brand' => $this->order->vehicle->brand,
                'model' => $this->order->vehicle->model,
                'plate' => $this->order->vehicle->plate,
            ],
            'old_status'    => $this->oldStatus->value,
            'new_status'    => $this->newStatus->value,
            'title'         => "Orden #{$this->order->order_number} actualizada",
            'message'       => "El estado cambió de {$this->oldStatus->value} a {$this->newStatus->value}.",
            'type'          => 'order_status_update',
            'url'           => url("/mi-cuenta/ordenes/{$this->order->id}"),
        ];
    }
}
