<?php

declare(strict_types=1);

namespace App\Models\Enums;

enum EngineType: string
{
    case Gasoline = 'gasoline';
    case Diesel = 'diesel';
    case Electric = 'electric';
    case Hybrid = 'hybrid';

    public function label(): string
    {
        return match ($this) {
            self::Gasoline => 'Gasolina',
            self::Diesel => 'Diésel',
            self::Electric => 'Eléctrico',
            self::Hybrid => 'Híbrido',
        };
    }
}
