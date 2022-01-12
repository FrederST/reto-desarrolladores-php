<?php

namespace App\Listeners;

use App\Events\CustomerCreatedOrUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LogCustomerCreatedOrUpdated
{
    public function handle(CustomerCreatedOrUpdated $event): void
    {
        $customer = $event->customer()->toArray();
        $customer['email'] = Str::limit($customer['email'], 6, '***');
        $customer['phone'] = Str::limit($customer['phone'], 5, '***');
        Log::channel('customer')->info($event->message(), $customer);
    }
}
