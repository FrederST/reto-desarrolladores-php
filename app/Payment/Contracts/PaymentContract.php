<?php

namespace App\Payment\Contracts;

use App\Models\Order;

interface PaymentContract
{
    public function makePayment(Order $order): string;

    public function checkStatus(Order $order): void;

    public function getStatus(Order $order): array;
}
