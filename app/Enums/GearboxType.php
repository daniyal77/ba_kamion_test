<?php

namespace App\Enums;

enum GearboxType: string
{
    case Manual = 'manual';
    case Automatic = 'automatic';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
