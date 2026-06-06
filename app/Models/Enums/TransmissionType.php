<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum TransmissionType: string
{
    case Automatic = 'automatic';
    case Manual = 'manual';
    case CVT = 'cvt';

    public function label(): string
    {
        return match ($this) {
            self::Automatic => 'Automática',
            self::Manual => 'Manual',
            self::CVT => 'CVT',
        };
    }
}
