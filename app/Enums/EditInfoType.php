<?php

namespace App\Enums;

enum EditInfoType : string
{
    case Add = 'add';
    case Remove = 'remove';

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
