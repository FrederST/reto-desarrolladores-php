<?php

namespace App\Actions\Order;

use App\Actions\Action;
use App\Builders\PaymentBuilder;
use App\Constants\OrderStatus;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class RetryPaymentAction
{
    public function execute(Model $order): Model
    {

        $payment_class = PaymentBuilder::build($order->payment_method, config('shop.payment_methods.' . $order->payment_method));

        if ($order->status == OrderStatus::STATUS_APPROVED) {
            return $order;
        }

        $payment_class->makePayment($order);
        $order->refresh();
        return $order;

    }
}
