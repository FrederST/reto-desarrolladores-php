<?php

namespace App\Constants;

class ImportStatus
{
    public const STATUS_SUCCESS = 'SUCCESS';
    public const STATUS_FAIL = 'FAIL';

    public const STATUSES = [
        self::STATUS_SUCCESS,
        self::STATUS_FAIL,
    ];
}
