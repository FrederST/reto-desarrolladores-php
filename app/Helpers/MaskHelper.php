<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class MaskHelper
{
    public static function email(string $email): string
    {
        $data = explode('@', $email);
        $index = strlen($data[0]) / 2;
        return Str::mask($data[0], '*', $index) . '@' . $data[1];
    }
}
