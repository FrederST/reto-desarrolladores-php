<?php

namespace App\Console\Commands;

use App\Actions\Order\CheckOrderAction;
use App\Constants\OrderStatus;
use App\Models\Order;
use App\Notifications\OrderStatusChange;
use Illuminate\Console\Command;

class CheckOrders extends Command
{
    protected $signature = 'check:orders';

    protected $description = 'Command description';

    public function handle(CheckOrderAction $checkOrderAction)
    {
        $ordersPending = Order::where('status', OrderStatus::STATUS_PENDING)
        ->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, "' . now() . '") > 7')->get();

        foreach ($ordersPending as $item) {
            $order = $checkOrderAction->execute($item);
            $order->user->notify(new OrderStatusChange($order));
            $this->info('Checked Order ' . $order->id);
        }
    }
}
