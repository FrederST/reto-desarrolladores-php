<?php

namespace App\ViewModels\Product;

use App\Models\Currency;
use App\Models\Product;
use App\Models\WeightUnit;
use App\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'products' => $this->model()->with('images')->paginate(6),
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
