<?php

namespace App\Constants;

class WeightUnits
{
    public const TONELESS = 't';
    public const KILOGRAMS = 'kg';
    public const GRAMS = 'g';
    public const MILLIGRAMS = 'mg';
    public const MICROGRAMS = 'µg';
    public const NANOGRAMS = 'ng';
    public const PICOGRAMS = 'pg';

    public const UNITS = [
        self::TONELESS,
        self::KILOGRAMS,
        self::GRAMS,
        self::MILLIGRAMS,
        self::MICROGRAMS,
        self::NANOGRAMS,
        self::PICOGRAMS,
    ];
}
