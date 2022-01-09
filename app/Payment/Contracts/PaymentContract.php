<?php

namespace App\Payment\Contracts;

use App\Models\Order;

interface PaymentContract
{
    public function makePayment(Order $order): string;

    public function isApproved(Order $order): bool;

    public function getStatus(Order $order): array;

}
