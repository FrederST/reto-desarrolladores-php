<?php

namespace App\ViewModels\ShoppingCart;

use App\Models\ShoppingCart;
use App\ViewModels\ViewModel;

class IndexViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new ShoppingCart());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'shoppingCart' => auth()->user()->shoppingCart->shoppingCartItems()->with('product')->get(),
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
}
