<?php

namespace App\Listeners;

use App\Events\CustomerCreatedOrUpdated;
use Illuminate\Support\Facades\Log;

class LogCustomerCreatedOrUpdated
{
    public function handle(CustomerCreatedOrUpdated $event): void
    {
        Log::channel('customer')->info($event->message(), $event->customer()->toArray());
    }
}
