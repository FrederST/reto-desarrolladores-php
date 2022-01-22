<?php

namespace App\Constants;

class ReportStatus
{
    public const STATUS_CREATED = 'CREATED';
    public const STATUS_IN_PROCESS = 'IN_PROGRESS';
    public const STATUS_FINISHED = 'FINISHED';
    public const STATUS_FAIL = 'FAIL';

    public const STATUSES = [
        self::STATUS_CREATED,
        self::STATUS_IN_PROCESS,
        self::STATUS_FINISHED,
        self::STATUS_FAIL,
    ];
}
