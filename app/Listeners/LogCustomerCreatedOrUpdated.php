<?php

namespace App\Listeners;

use App\Events\CustomerCreatedOrUpdated;
use App\Helpers\MaskHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogCustomerCreatedOrUpdated
{
    public function handle(CustomerCreatedOrUpdated $event): void
    {
        $customer = $event->customer()->toArray();
        $customer['email'] = MaskHelper::email($customer['email']);
        $customer['phone'] = Str::mask($customer['phone'], '*', -7, 4);
        Log::channel('customer')->info($event->message(), $customer);
    }
}
