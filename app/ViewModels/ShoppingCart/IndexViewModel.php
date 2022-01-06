<?php

namespace App\ViewModels\ShoppingCart;

use App\Helpers\CurrencyHelper;
use App\Models\Currency;
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
            'shoppingCarts' => $this->getCartsMapped(),
        ]);
    }

    protected function buttons(): array
    {
        return [];
    }

    protected function title(): string
    {
        return 'Shopping Cart';
    }

    private function getCartsMapped(): LengthAwarePaginator
    {
        return $this->model()
            ->with('product')
            ->paginate()
            ->through(function ($cart) {
                $cart['total'] = CurrencyHelper::toCurrencyFormat($cart['total']);
                return $cart;
            });
    }
}
