<?php

namespace App\Enums;

use ReflectionClass;

abstract class BaseEnum
{
    public static function values(): array
    {
        $class = new ReflectionClass(static::class);
        return array_column($class->getConstants(), 'value');
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
