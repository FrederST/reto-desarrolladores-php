<?php

namespace App\Listeners;

use App\Events\OrderCreatedOrUpdated;
use Illuminate\Support\Facades\Log;

class LogOrderCreatedOrUpdated
{
    public function handle(OrderCreatedOrUpdated $event): void
    {
        $order = $event->order()->toArray();
        Log::channel('order')->info($event->message(), $order);
    }
}
