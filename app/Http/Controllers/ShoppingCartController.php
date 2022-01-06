<?php

namespace App\Http\Controllers;

use App\Actions\ShoppingCart\StorageAction;
use App\Http\Requests\ShoppingCart\StoreRequest;
use App\Models\ShoppingCart;
use App\ViewModels\ShoppingCart\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ShoppingCartController extends Controller
{
    public const SHOPPING_CART_INDEX = 'shoppingCart.index';

    public function index(): Response
    {
        return Inertia::render('ShoppingCart/Index', (new IndexViewModel())->toArray());
    }

    public function store(StoreRequest $request, StorageAction $storageAction): RedirectResponse
    {
        $storageAction->execute($request->validated(), new ShoppingCart());
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShoppingCart  $shoppingCart
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShoppingCart $shoppingCart)
    {
        //
    }
}
