<?php

namespace App\ViewModels\Product;

use App\Models\Currency;
use App\Models\Product;
use App\Models\WeightUnit;
use App\ViewModels\Concerns\HasPaginator;
use App\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    use HasPaginator;

    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'products' => $this->collection,
            'weight_units' => WeightUnit::all(),
            'currencies' => Currency::all(),
        ]);
    }

    protected function buttons(): array
    {
        return [];
    }

    protected function title(): string
    {
        return 'Products';
    }
}
