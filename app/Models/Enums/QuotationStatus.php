<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum QuotationStatus: string
{
    case Draft = 'draft';
    case PendingClient = 'pending_client';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Borrador',
            self::PendingClient => 'Pendiente de cliente',
            self::Approved => 'Aprobada',
            self::Rejected => 'Rechazada',
            self::Expired => 'Expirada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'secondary',
            self::PendingClient => 'warning',
            self::Approved => 'success',
            self::Rejected => 'danger',
            self::Expired => 'info',
        };
    }

    public function isEditable(): bool
    {
        return in_array($this, [self::Draft, self::PendingClient]);
    }
}
