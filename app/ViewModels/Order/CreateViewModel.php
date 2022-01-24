<?php

namespace App\ViewModels\Order;

use App\Http\Resources\ShoppingCartResource;
use App\Models\Order;
use App\ViewModels\ViewModel;
use Khsing\World\World;

class CreateViewModel extends ViewModel
{
    public function __construct()
    {
        parent::__construct(new Order());
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'countries' => World::Countries(),
            'shoppingCart' => ShoppingCartResource::collection(auth()->user()->shoppingCart->shoppingCartItems()->with('product')->get()),
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
