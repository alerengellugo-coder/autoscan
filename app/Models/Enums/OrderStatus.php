<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Diagnosing = 'diagnosing';
    case InProgress = 'in_progress';
    case WaitingParts = 'waiting_parts';
    case QualityCheck = 'quality_check';
    case Completed = 'completed';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Diagnosing => 'En diagnóstico',
            self::InProgress => 'En progreso',
            self::WaitingParts => 'Esperando repuestos',
            self::QualityCheck => 'Control de calidad',
            self::Completed => 'Completado',
            self::Delivered => 'Entregado',
            self::Cancelled => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Diagnosing => 'info',
            self::InProgress => 'primary',
            self::WaitingParts => 'secondary',
            self::QualityCheck => 'accent',
            self::Completed => 'success',
            self::Delivered => 'success',
            self::Cancelled => 'danger',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [
            self::Pending,
            self::Diagnosing,
            self::InProgress,
            self::WaitingParts,
            self::QualityCheck,
        ]);
    }

    public function isFinal(): bool
    {
        return in_array($this, [
            self::Completed,
            self::Delivered,
            self::Cancelled,
        ]);
    }
}
