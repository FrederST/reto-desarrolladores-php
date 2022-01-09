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

        $statusInfo = $payment_class->getStatus($order);
        $currenStatus = $statusInfo['status']['status'];

        if ($currenStatus == OrderStatus::STATUS_APPROVED) {
            $order->status = OrderStatus::STATUS_APPROVED;
        } elseif ($currenStatus == OrderStatus::STATUS_REJECTED) {
            $order->status = OrderStatus::STATUS_REJECTED;
        }

        $order->save();

        return $order;
    }
}
