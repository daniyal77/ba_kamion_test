<?php

namespace App\Enums;

enum BodyType: string
{
    case Sedan = 'sedan';
    case Suv = 'suv';
    case Hatchback = 'hatchback';
    case Pickup = 'pickup';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
