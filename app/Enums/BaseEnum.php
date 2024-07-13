<?php

namespace App\Enums;

use Illuminate\Support\Facades\Log;
use ReflectionClass;

abstract class BaseEnum
{
    public static function values(): array
    {
        $class = new ReflectionClass(static::class);
        return array_values($class->getConstants());
    }

    public static function toArray(): array
    {
        $class = new ReflectionClass(static::class);
        $cases = $class->getConstants();
        $array = [];
        foreach ($cases as $name => $value) {
            $array[$value] = $name;
        }
        return $array;
    }

}
