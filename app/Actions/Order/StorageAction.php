<?php

namespace App\Actions\Order;

use App\Actions\Action;
use App\Builders\PaymentBuilder;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class StorageAction extends Action
{
    public function execute(array $data, Model $order): Model
    {
        $shoppingCart = auth()->user()->shoppingCart;

        $order->order_number = 'ORD-' . strtoupper(uniqid());
        $order->grand_total = $shoppingCart->shoppingCartItems->sum('total');
        $order->item_count = $shoppingCart->shoppingCartItems->sum('quantity');
        $order->user_id = auth()->user()->id;
        $order->first_name = $data['first_name'];
        $order->last_name = $data['last_name'];
        $order->address = $data['address'];
        $order->country_id = $data['country_id'];
        $order->city_id = $data['city_id'];
        $order->post_code = $data['post_code'];
        $order->phone_number = $data['phone_number'];
        $order->notes = $data['notes'];
        $order->payment_method = array_key_exists('payment_method', $data) ? $data['payment_method'] : config('shop.default_payment_method');

        $order->save();
        $order->refresh();

        foreach ($shoppingCart->shoppingCartItems as $item) {
            $orderItem = new OrderItem([
                'product_id' => $item->product->id,
                'quantity' => $item->quantity,
                'price' => $item->total,
            ]);

            $item->product->quantity -= $item->quantity;
            $item->product->save();
            $order->orderItems()->save($orderItem);
        }

        $shoppingCart->shoppingCartItems()->delete();

        $paymentClass = PaymentBuilder::build($order->payment_method, config('shop.payment_methods.' . $order->payment_method));

        throw_if(
            $paymentClass->makePayment($order) == null,
            OrderRetryException::class,
            'Error Connecting to ' . $order->payment_method
        );

        $order->refresh();

        return $order;
    }
}
