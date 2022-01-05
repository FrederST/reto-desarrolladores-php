<?php

namespace App\Listeners;

use App\Events\ProductCreatedOrUpdated;
use Illuminate\Support\Facades\Log;

class LogProductCreatedOrUpdated
{
    public function handle(ProductCreatedOrUpdated $event): void
    {
        Log::channel('product')->info($event->message(), $event->product()->toArray());
    }
}
