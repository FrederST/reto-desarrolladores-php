<?php

namespace App\ViewModels\Product;

use App\Helpers\CurrencyHelper;
use App\Models\Currency;
use App\Models\Product;
use App\Models\WeightUnit;
use App\ViewModels\ViewModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class IndexViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Product());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'products' => $this->getProductsMapped(),
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

    private function getProductsMapped()
    {
        return $this->model()->with('images')->paginate(6)
        ->through(function ($product) {
            $product['sale_price'] = CurrencyHelper::toCurrencyFormat($product['sale_price'], Currency::find($product['currency_id'])->alphabetic_code);

            return $product;
         });
    }
}
