<?php

namespace App\Enums;

use Illuminate\Support\Facades\Log;
use ReflectionClass;

abstract class BaseEnum
{
    public static function values(): array
    {
        return array_values(self::class()->getConstants());
    }

    public static function toArray(): array
    {
        $cases = self::class()->getConstants();
        $array = [];
        foreach ($cases as $name => $value) {
            $array[$value] = $name;
        }
        return $array;
    }

    private static function class(): ReflectionClass
    {
        return new ReflectionClass(static::class);
    }
}
