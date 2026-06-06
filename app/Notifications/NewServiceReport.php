<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ServiceReport;

class NewServiceReport extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ServiceReport $report,
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
        $order = $this->report->serviceOrder;
        $vehicle = $order->vehicle;

        return (new MailMessage)
            ->subject("Nuevo reporte de servicio - Orden #{$order->order_number}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Se ha generado un nuevo reporte de servicio para su orden.")
            ->line("**Orden:** #{$order->order_number}")
            ->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})")
            ->line("")
            ->line("**Detalle del reporte:**")
            ->line($this->report->diagnosis ?? 'Sin diagnóstico detallado.')
            ->line("")
            ->line("**Trabajo realizado:**")
            ->line($this->report->work_performed ?? 'No especificado.')
            ->line("");

        // Include recommendations if any
        if (!empty($this->report->recommendations)) {
            $mail = (new MailMessage)
                ->subject("Nuevo reporte de servicio - Orden #{$order->order_number}")
                ->greeting("Hola {$notifiable->name},")
                ->line("Se ha generado un nuevo reporte de servicio para su orden.")
                ->line("**Orden:** #{$order->order_number}")
                ->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})")
                ->line("")
                ->line("**Diagnóstico:**")
                ->line($this->report->diagnosis ?? 'Sin diagnóstico detallado.')
                ->line("")
                ->line("**Trabajo realizado:**")
                ->line($this->report->work_performed ?? 'No especificado.')
                ->line("")
                ->line("**Recomendaciones:**")
                ->line($this->report->recommendations)
                ->action('Ver Reporte Completo', url("/mi-cuenta/ordenes/{$order->id}"))
                ->line('Gracias por confiar en AutoScan.');

            return $mail;
        }

        $mail = (new MailMessage)
            ->subject("Nuevo reporte de servicio - Orden #{$order->order_number}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Se ha generado un nuevo reporte de servicio para su orden.")
            ->line("**Orden:** #{$order->order_number}")
            ->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})")
            ->line("")
            ->line("**Diagnóstico:**")
            ->line($this->report->diagnosis ?? 'Sin diagnóstico detallado.')
            ->line("")
            ->line("**Trabajo realizado:**")
            ->line($this->report->work_performed ?? 'No especificado.')
            ->action('Ver Reporte Completo', url("/mi-cuenta/ordenes/{$order->id}"))
            ->line('Gracias por confiar en AutoScan.');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        $order = $this->report->serviceOrder;

        return [
            'report_id'     => $this->report->id,
            'order_id'      => $order->id,
            'order_number'  => $order->order_number,
            'vehicle'       => [
                'brand' => $order->vehicle->brand,
                'model' => $order->vehicle->model,
                'plate' => $order->vehicle->plate,
            ],
            'diagnosis'     => $this->report->diagnosis,
            'title'         => "Nuevo reporte - Orden #{$order->order_number}",
            'message'       => "Se generó un nuevo reporte de servicio con el diagnóstico: " . ($this->report->diagnosis ?? 'Sin diagnóstico detallado.'),
            'type'          => 'new_service_report',
            'url'           => url("/mi-cuenta/ordenes/{$order->id}"),
        ];
    }
}
