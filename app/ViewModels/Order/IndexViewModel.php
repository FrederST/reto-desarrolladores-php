<?php

namespace App\ViewModels\Order;

use App\Models\Order;
use App\Models\ShoppingCart;
use App\ViewModels\ViewModel;
use Illuminate\Pagination\LengthAwarePaginator;

class IndexViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new ShoppingCart());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'orders' => Order::paginate(),
        ]);
    }

    protected function buttons(): array
    {
        return [];
    }

    protected function title(): string
    {
        return 'Orders';
    }

}
