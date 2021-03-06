<?php

namespace App\Http\Controllers;

use App\Actions\Product\StorageAction;
use App\Actions\Product\UpdateAction;
use App\Events\ProductCreatedOrUpdated;
use App\Http\Requests\Product\ImportRequest;
use App\Http\Requests\Product\StoreRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ImportProducts;
use App\Models\Product;
use App\ViewModels\Product\IndexViewModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public const PRODUCT_INDEX = 'products.index';

    public function index(IndexViewModel $indexViewModel): Response
    {
        $products = Product::filter(request()->input('filter', []))->with('images')->paginate();
        return Inertia::render('Product/Index', $indexViewModel->collection(ProductResource::collection($products)));
    }

    public function store(StoreRequest $request, StorageAction $storageAction): RedirectResponse
    {
        $product = $storageAction->execute($request->validated(), new Product());
        ProductCreatedOrUpdated::dispatch($product);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function update(UpdateRequest $request, Product $product, UpdateAction $updateAction): RedirectResponse
    {
        $product = $updateAction->execute($request->validated(), $product);
        ProductCreatedOrUpdated::dispatch($product, 'Product Updated');
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

    public function disable(Product $product): RedirectResponse
    {
        $product->update(['disabled_at' => now()]);
        return Redirect::route(self::PRODUCT_INDEX);
    }

    public function import(ImportRequest $importRequest): RedirectResponse
    {
        $path = $importRequest->file('products')->storeAs('imports/products', uniqid() . '.csv');
        ImportProducts::dispatch($path, auth()->user()->id);
        return Redirect::route(self::PRODUCT_INDEX);
    }
}
