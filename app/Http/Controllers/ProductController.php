<?php

namespace App\Http\Controllers;

use App\Actions\Product\StorageAction;
use App\Actions\Product\UpdateAction;
use App\Http\Requests\Product\StoreRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public const PRODUCT_INDEX = 'products.index';

    public function index(): Response
    {
        return Inertia::render('Product/Index', ['products' =>  Product::with('images')->paginate(6)]);
    }

    public function store(StoreRequest $request, StorageAction $storageAction): RedirectResponse
    {
        $storageAction->execute($request->validated(), new Product);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function update(StoreRequest $request, Product $product, UpdateAction $updateAction): RedirectResponse
    {
        $updateAction->execute($request->validated(), $product);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images() as $image) {
            Storage::delete($image->path);
            $image->delete();
        }
        $product->delete();
        return Redirect::route(self::PRODUCT_INDEX);
    }

}
