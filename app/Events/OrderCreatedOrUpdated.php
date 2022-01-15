<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreatedOrUpdated
{
    use Dispatchable;
    use SerializesModels;

    private Order $order;
    private string $message;

    public function __construct(Order $order, string $message = 'Order Crated')
    {
        $this->order = $order;
        $this->message = $message;
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function message(): string
    {
        return $this->message;
    }
}
