<?php

namespace App\ViewModels\Report;

use App\Builders\PaymentBuilder;
use App\Constants\OrderStatus;
use App\Constants\ReportTypes;
use App\Models\Report;
use App\ViewModels\Concerns\HasPaginator;
use App\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    use HasPaginator;

    public function __construct()
    {
        parent::__construct(new Report());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'reports' => $this->collection,
            'orderStatuses' => OrderStatus::STATUSES,
            'paymentMethods' => array_keys(config('shop.payment_methods')),
        ]);
    }

    protected function buttons(): array
    {
        return [];
    }

    protected function title(): string
    {
        return 'Reports';
    }
}
