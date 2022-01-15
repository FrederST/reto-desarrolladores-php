<?php

namespace App\Actions\Order;

use App\Builders\PaymentBuilder;
use App\Constants\OrderStatus;
use App\Exceptions\OrderRetryException;
use Illuminate\Database\Eloquent\Model;

class RetryPaymentAction
{
    public function execute(Model $order): Model
    {
        $paymentClass = PaymentBuilder::build($order->payment_method, config('shop.payment_methods.' . $order->payment_method));

        throw_if(
            $order->status == OrderStatus::STATUS_APPROVED || $order->status == OrderStatus::STATUS_PENDING,
            OrderRetryException::class
        );

        throw_if(
            $paymentClass->makePayment($order) == null,
            OrderRetryException::class,
            'Error Connecting to ' . $order->payment_method
        );
        $order->refresh();
        return $order;
    }
}
