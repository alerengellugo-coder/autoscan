<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Quotation;

class QuotationSent extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->quotation->serviceOrder;
        $vehicle = $order ? $order->vehicle : null;

        $mail = (new MailMessage)
            ->subject("Nueva cotización #{$this->quotation->quotation_number}")
            ->greeting("Hola {$notifiable->name},")
            ->line("Le informamos que se ha generado una nueva cotización para su servicio.");

        if ($vehicle) {
            $mail->line("**Vehículo:** {$vehicle->brand} {$vehicle->model} ({$vehicle->plate})");
        }

        if ($order) {
            $mail->line("**Orden de servicio:** #{$order->order_number}");
        }

        $mail->line("")
             ->line("**Resumen de la cotización:**")
             ->line("**Subtotal:** $" . number_format($this->quotation->subtotal, 2))
             ->line("**Impuesto:** $" . number_format($this->quotation->tax, 2))
             ->line("**Total:** $" . number_format($this->quotation->total, 2))
             ->line("");

        // List items if available
        if ($this->quotation->items && $this->quotation->items->count() > 0) {
            $mail->line("**Productos/Servicios incluidos:**");
            foreach ($this->quotation->items as $item) {
                $line = "- {$item->description}";
                if ($item->quantity > 1) {
                    $line .= " (x{$item->quantity})";
                }
                $line .= " - $" . number_format($item->total, 2);
                $mail->line($line);
            }
            $mail->line("");
        }

        // Validity information
        $validityDays = config('autoscan.quotation_validity_days', 15);
        $validUntil = $this->quotation->valid_until
            ? $this->quotation->valid_until->format('d/m/Y')
            : now()->addDays($validityDays)->format('d/m/Y');

        $mail->line("Esta cotización es válida hasta el **{$validUntil}**.")
             ->action('Ver Cotización Completa', url("/mi-cuenta/cotizaciones/{$this->quotation->id}"))
             ->line('Para aprobar la cotización, visite el enlace anterior o contacte a nuestro taller.')
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
        return [
            'quotation_id'      => $this->quotation->id,
            'quotation_number'  => $this->quotation->quotation_number,
            'order_id'          => $this->quotation->serviceOrder ? $this->quotation->serviceOrder->id : null,
            'order_number'      => $this->quotation->serviceOrder ? $this->quotation->serviceOrder->order_number : null,
            'subtotal'          => $this->quotation->subtotal,
            'tax_amount'        => $this->quotation->tax,
            'total'             => $this->quotation->total,
            'status'            => $this->quotation->status->value,
            'valid_until'       => $this->quotation->valid_until?->toDateTimeString(),
            'title'             => "Nueva cotización #{$this->quotation->quotation_number}",
            'message'           => "Se generó una cotización por $" . number_format($this->quotation->total, 2),
            'type'              => 'quotation_sent',
            'url'               => url("/mi-cuenta/cotizaciones/{$this->quotation->id}"),
        ];
    }
}
