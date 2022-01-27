<?php

namespace App\Http\Controllers;

use App\Actions\ShoppingCart\AddToCartAction;
use App\Actions\ShoppingCart\DeleteToCartAction;
use App\Actions\ShoppingCart\UpdateToCartAction;
use App\Http\Requests\ShoppingCart\StoreRequest;
use App\Http\Requests\ShoppingCart\UpdateRequest;
use App\Models\ShoppingCartItem;
use App\ViewModels\ShoppingCart\IndexViewModel;
use Illuminate\Http\RedirectResponse;
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

    public function update(UpdateRequest $request, ShoppingCartItem $shoppingCartItem, UpdateToCartAction $updateToCartAction): RedirectResponse
    {
        $updateToCartAction->execute($request->validated(), $shoppingCartItem);
        return Redirect::route(self::SHOPPING_CART_INDEX);
    }

    public function destroy(ShoppingCartItem $shoppingCartItem, DeleteToCartAction $deleteToCartAction): RedirectResponse
    {
        $deleteToCartAction->execute($shoppingCartItem);
        return Redirect::route(self::SHOPPING_CART_INDEX);
    }
}
