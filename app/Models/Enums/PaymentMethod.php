<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum PaymentMethod: string
{
    case Cash = 'cash';
    case Card = 'card';
    case Transfer = 'transfer';
    case Credit = 'credit';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Efectivo',
            self::Card => 'Tarjeta',
            self::Transfer => 'Transferencia',
            self::Credit => 'Crédito',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Cash => 'banknotes',
            self::Card => 'credit-card',
            self::Transfer => 'building-columns',
            self::Credit => 'file-invoice',
        };
    }
}
