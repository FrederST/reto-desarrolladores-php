<?php

namespace App\Http\Controllers;

use App\Actions\ShoppingCart\AddToCartAction;
use App\Actions\ShoppingCart\DeleteToCartAction;
use App\Http\Requests\ShoppingCart\StoreRequest;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartItem;
use App\ViewModels\ShoppingCart\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ShoppingCartItemController extends Controller
{
    public const SHOPPING_CART_INDEX = 'shoppingCartItems.index';

    public function index(): Response
    {
        return Inertia::render('ShoppingCart/Index', (new IndexViewModel())->toArray());
    }

    public function store(StoreRequest $request, AddToCartAction $addToCartAction): RedirectResponse
    {
        $addToCartAction->execute($request->validated(), new ShoppingCartItem());
        return Redirect::route(self::SHOPPING_CART_INDEX);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShoppingCart  $shoppingCart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShoppingCart $shoppingCart)
    {
        //
    }

    public function destroy(ShoppingCartItem $shoppingCartItem, DeleteToCartAction $deleteToCartAction): RedirectResponse
    {
        $deleteToCartAction->execute($shoppingCartItem);
        return Redirect::route(self::SHOPPING_CART_INDEX);
    }
}
