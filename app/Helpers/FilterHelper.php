<?php

namespace App\Helpers;

class FilterHelper
{
    public static function removeNullValues(array $conditions): array
    {
        foreach ($conditions as $key => $value) {
            if (!isset($conditions[$key])) {
                unset($conditions[$key]);
            } else {
                foreach ($conditions[$key] as $key2 => $value) {
                    if (!isset($conditions[$key][$key2])) {
                        unset($conditions[$key][$key2]);
                    }
                }
            }
        }
        return $conditions;
    }
}
