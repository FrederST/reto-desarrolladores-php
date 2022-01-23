<?php

namespace App\ViewModels\Order;

use App\Models\Order;
use App\ViewModels\Concerns\HasPaginator;
use App\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    use HasPaginator;

    public function __construct()
    {
        parent::__construct(new Order());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'orders' => auth()->user()->orders()->paginate(),
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
