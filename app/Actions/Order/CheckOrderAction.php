<?php

namespace App\Actions\Order;

use App\Builders\PaymentBuilder;
use App\Constants\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class CheckOrderAction
{
    public function execute(Model $order): Model
    {
        $payment_class = PaymentBuilder::build($order->payment_method, config('shop.payment_methods.' . $order->payment_method));

        $payment_class->checkStatus($order);

        $order->refresh();

        return $order;
    }
}