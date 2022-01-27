<?php

namespace App\Actions\Order;

use App\Builders\PaymentBuilder;
use App\Constants\OrderStatus;
use Illuminate\Database\Eloquent\Model;

class CheckOrderAction
{
    public function execute(Model $order): Model
    {
        $paymentClass = PaymentBuilder::build($order->payment_method, config('shop.payment_methods.' . $order->payment_method));

        if ($order->status == OrderStatus::STATUS_APPROVED) {
            return $order;
        }

        $paymentClass->checkStatus($order);
        $order->refresh();
        return $order;
    }
}
