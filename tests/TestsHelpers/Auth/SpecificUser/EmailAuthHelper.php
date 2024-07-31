<?php

namespace TestsHelpers\Auth\SpecificUser;

class EmailAuthHelper
{
    public static function generateRandomEmail(): string
    {
        return self::generateRandomString(rand(1, 10)) . '@test.com';
    }

    private static function generateRandomString($length = 16): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
