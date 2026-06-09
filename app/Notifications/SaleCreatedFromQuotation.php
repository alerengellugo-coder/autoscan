<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Sale;

class SaleCreatedFromQuotation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Sale $sale,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Venta #{$this->sale->sale_number} creada")
            ->greeting("Hola {$notifiable->name},")
            ->line("Le informamos que se ha generado una venta a partir de su cotización aprobada.")
            ->line("**Venta:** #{$this->sale->sale_number}")
            ->line("**Total:** $" . number_format($this->sale->total, 2))
            ->line("**Estado:** Pendiente de pago")
            ->action('Ver Venta', url("/mi-cuenta/ventas"))
            ->line('Gracias por confiar en AutoScan.');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'sale_id'           => $this->sale->id,
            'sale_number'       => $this->sale->sale_number,
            'total'             => $this->sale->total,
            'title'             => "Venta #{$this->sale->sale_number} creada",
            'message'           => "Se ha creado una venta por $" . number_format($this->sale->total, 2) . " desde su cotización aprobada.",
            'type'              => 'sale_created',
            'url'               => url("/mi-cuenta/ventas"),
        ];
    }
}
