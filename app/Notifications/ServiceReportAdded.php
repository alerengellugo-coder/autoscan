<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\ServiceOrder;
use App\Models\ServiceReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification: ServiceReportAdded
 *
 * Sent to the client when a technician adds a new service report
 * to their vehicle's service order.
 */
class ServiceReportAdded extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public ServiceOrder $serviceOrder,
        public ServiceReport $serviceReport
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Nuevo informe - Orden {$this->serviceOrder->order_number}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Se ha agregado un nuevo informe de servicio a su orden **{$this->serviceOrder->order_number}**.")
            ->line("Vehículo: {$this->serviceOrder->vehicle->full_name} ({$this->serviceOrder->vehicle->plate_formatted})")
            ->line("Técnico: {$this->serviceReport->technician->name}")
            ->line("Descripción: {$this->serviceReport->description}")
            ->action('Ver Orden de Servicio', url(route('service-orders.show', $this->serviceOrder)))
            ->line('Si tiene alguna pregunta, no dude en contactarnos.')
            ->salutation('Saludos cordiales, AutoScan');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Nuevo informe de servicio',
            'message' => "Se ha agregado un informe a la orden {$this->serviceOrder->order_number}.",
            'service_order_id' => $this->serviceOrder->id,
            'service_order_number' => $this->serviceOrder->order_number,
            'service_report_id' => $this->serviceReport->id,
            'vehicle' => $this->serviceOrder->vehicle?->full_name,
            'technician' => $this->serviceReport->technician?->name,
            'type' => 'service_report_added',
        ];
    }
}
