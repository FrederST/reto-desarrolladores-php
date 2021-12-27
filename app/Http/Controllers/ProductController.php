<?php

namespace App\Http\Controllers;

use App\Actions\Product\StorageAction;
use App\Actions\Product\UpdateAction;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public const PRODUCT_INDEX = 'products.index';

    public function index(): Response
    {
        return Inertia::render('Product/Index', ['products' =>  Product::with('images')->paginate(6)]);
    }

    public function store(Request $request, StorageAction $storageAction): RedirectResponse
    {
        $storageAction->execute($request->all(), new Product);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function update(Request $request, Product $product, UpdateAction $updateAction): RedirectResponse
    {
        $updateAction->execute($request->all(), $product);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return Redirect::route(self::PRODUCT_INDEX);
    }

}
