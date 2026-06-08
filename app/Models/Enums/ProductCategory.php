<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum ProductCategory: string
{
    case Oil = 'oil';
    case Filter = 'filter';
    case Brake = 'brake';
    case Battery = 'battery';
    case Electrical = 'electrical';
    case ScanTool = 'scan_tool';
    case Accessory = 'accessory';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Oil => 'Aceite',
            self::Filter => 'Filtro',
            self::Brake => 'Frenos',
            self::Battery => 'Batería',
            self::Electrical => 'Eléctrico',
            self::ScanTool => 'Herramienta de escaneo',
            self::Accessory => 'Accesorio',
            self::Other => 'Otro',
        };
    }
}
