<?php

namespace App\Builders;

use App\Payment\Contracts\PaymentContract;
use App\Payment\PlaceToPay;

class PaymentBuilder
{
    private const PAYMENT_METHODS = [
        'place_to_pay' => PlaceToPay::class
    ];

    public static function build(string $payment, array $config): PaymentContract
    {
        $payment_class = self::PAYMENT_METHODS[$payment];
        return new $payment_class($config);
    }
}
