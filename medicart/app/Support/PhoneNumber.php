<?php

namespace App\Support;

final class PhoneNumber
{
    public const COUNTRY_CODE = '+';

    public static function normalize(string $localNumber): string
    {
        return self::COUNTRY_CODE.$localNumber;
    }

    public static function local(?string $phone): string
    {
        if (! $phone) {
            return '';
        }

        return preg_replace('/^\+880/', '', $phone) ?? $phone;
    }
}