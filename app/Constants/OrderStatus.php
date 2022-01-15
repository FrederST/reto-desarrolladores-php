<?php

namespace App\Constants;

class OrderStatus
{
    public const STATUS_APPROVED = 'APPROVED';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_REJECTED = 'REJECTED';
    public const STATUS_FAILED = 'FAILED';
    public const STATUS_DECLINED = 'DECLINED';
    public const STATUS_WAIT = 'WAIT';

    public const STATUSES = [
        self::STATUS_APPROVED,
        self::STATUS_PENDING,
        self::STATUS_REJECTED,
        self::STATUS_FAILED,
        self::STATUS_DECLINED,
        self::STATUS_WAIT,
    ];
}
