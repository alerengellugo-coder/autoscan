<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum ServiceType: string
{
    case Diagnostic = 'diagnostic';
    case Repair = 'repair';
    case Maintenance = 'maintenance';
    case Scan = 'scan';
    case Electrical = 'electrical';
    case Bodywork = 'bodywork';

    public function label(): string
    {
        return match ($this) {
            self::Diagnostic => 'Diagnóstico',
            self::Repair => 'Reparación',
            self::Maintenance => 'Mantenimiento',
            self::Scan => 'Escaneo',
            self::Electrical => 'Eléctrico',
            self::Bodywork => 'Carrocería',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Diagnostic => 'magnifying-glass',
            self::Repair => 'wrench',
            self::Maintenance => 'gear',
            self::Scan => 'barcode',
            self::Electrical => 'bolt',
            self::Bodywork => 'car',
        };
    }
}
