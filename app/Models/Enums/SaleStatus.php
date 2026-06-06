<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum SaleStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case PartiallyPaid = 'partially_paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Paid => 'Pagada',
            self::PartiallyPaid => 'Parcialmente pagada',
            self::Cancelled => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Paid => 'success',
            self::PartiallyPaid => 'info',
            self::Cancelled => 'danger',
        };
    }

    public function isPaid(): bool
    {
        return $this === self::Paid;
    }

    public function isPending(): bool
    {
        return in_array($this, [self::Pending, self::PartiallyPaid]);
    }
}
