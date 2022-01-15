<?php

namespace App\Actions\Order;

use App\Builders\PaymentBuilder;
use App\Constants\OrderStatus;
use App\Exceptions\OrderRetryException;
use Illuminate\Database\Eloquent\Model;

class RetryPaymentAction
{
    public function execute(Model $order): string
    {
        $payment_class = PaymentBuilder::build($order->payment_method, config('shop.payment_methods.' . $order->payment_method));

        throw_if($order->status == OrderStatus::STATUS_APPROVED || $order->status == OrderStatus::STATUS_PENDING,
        OrderRetryException::class);

        $payment_class->makePayment($order);
        $order->refresh();
        return $order->payment_process_url;
    }
}
