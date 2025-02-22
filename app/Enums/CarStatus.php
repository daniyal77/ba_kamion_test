<?php

namespace App\Enums;

enum CarStatus: string
{
    case Available = 'available';
    case Sold = 'sold';
    case Unavailable = 'unavailable';

    public static function getValues(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
