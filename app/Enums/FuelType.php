<?php

namespace App\Enums;

enum FuelType: string
{
    case Gasoline = 'gasoline';
    case Diesel = 'diesel';
    case Electric = 'electric';
    case Hybrid = 'hybrid';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
