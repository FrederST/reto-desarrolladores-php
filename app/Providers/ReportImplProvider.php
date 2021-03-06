<?php

namespace App\Providers;

use App\Constants\ReportTypes;
use App\Reports\OrdersReport;
use App\Reports\ProductsReport;
use App\Reports\ReportBase;
use Illuminate\Support\ServiceProvider;

class ReportImplProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(ReportBase::class, function () {
            return match (request()->input('type')) {
                ReportTypes::TYPE_PRODUCT => new ProductsReport(),
                ReportTypes::TYPE_ORDERS => new OrdersReport(),
                default => throw new \Exception('No suitable report implementation was found'),
            };
        });
    }
}
