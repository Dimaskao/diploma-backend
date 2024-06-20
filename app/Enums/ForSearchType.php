<?php

namespace App\Enums;

enum ForSearchType : string
{
    case Users = 'users';
    case Companies = 'companies';
    case All = 'all';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        $array = [];
        foreach (self::cases() as $case) {
            $array[$case->value] = $case->name;
        }
        return $array;
    }
}
