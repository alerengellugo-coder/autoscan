<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Quotation;

class QuotationRejected extends Notification implements ShouldQueue
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
        $client = $this->quotation->client;
        $vehicle = $this->quotation->vehicle;

        return (new MailMessage)
            ->subject("Cotización #{$this->quotation->quotation_number} rechazada")
            ->greeting("Hola {$notifiable->name},")
            ->line("Le informamos que el cliente ha rechazado una cotización.")
            ->line("**Cotización:** #{$this->quotation->quotation_number}")
            ->line("**Total:** $" . number_format($this->quotation->total, 2))
            ->when($client, fn ($mail) => $mail->line("**Cliente:** {$client->name}"))
            ->when($vehicle, fn ($mail) => $mail->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})"))
            ->line('Puede contactar al cliente para ofrecer alternativas.')
            ->action('Ver Cotización', url("/admin/cotizaciones/{$this->quotation->id}"))
            ->line('Gracias por confiar en AutoScan.');
    }

    public function toDatabase(object $notifiable): array
    {
        $client = $this->quotation->client;

        return [
            'quotation_id'      => $this->quotation->id,
            'quotation_number'  => $this->quotation->quotation_number,
            'client_name'       => $client ? $client->name : 'Cliente',
            'total'             => $this->quotation->total,
            'title'             => "Cotización #{$this->quotation->quotation_number} rechazada",
            'message'           => "El cliente {$client?->name} ha rechazado la cotización por $" . number_format($this->quotation->total, 2),
            'type'              => 'quotation_rejected',
            'url'               => url("/admin/cotizaciones/{$this->quotation->id}"),
        ];
    }
}
