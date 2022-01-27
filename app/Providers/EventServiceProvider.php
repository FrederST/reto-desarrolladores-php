<?php

namespace App\Providers;

use App\Events\CustomerCreatedOrUpdated;
use App\Events\OrderCreatedOrUpdated;
use App\Events\ProductCreatedOrUpdated;
use App\Listeners\LogCustomerCreatedOrUpdated;
use App\Listeners\LogOrderCreatedOrUpdated;
use App\Listeners\LogProductCreatedOrUpdated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CustomerCreatedOrUpdated::class => [
            LogCustomerCreatedOrUpdated::class,
        ],
        ProductCreatedOrUpdated::class => [
            LogProductCreatedOrUpdated::class,
        ],
        OrderCreatedOrUpdated::class => [
            LogOrderCreatedOrUpdated::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
