<?php

namespace App\Http\Controllers\Api;

use App\Actions\Product\StorageAction;
use App\Actions\Product\UpdateAction;
use App\Events\ProductCreatedOrUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ImportRequest;
use App\Http\Requests\Product\UpdateRequest;
use App\Http\Requests\ProductImage\StoreRequest;
use App\Http\Resources\Product as ResourcesProduct;
use App\Jobs\ImportProducts;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('abilities:create', ['only' => ['store', 'import']]);
        $this->middleware('abilities:read', ['only' => ['index']]);
        $this->middleware('abilities:update', ['only' => ['update', 'disable']]);
        $this->middleware('abilities:delete', ['only' => ['destroy']]);
    }

    public function search(Request $request): JsonResponse
    {
        $data = Product::where('name', 'LIKE', '%' . $request->searchTerm . '%')->get();
        return response()->json($data);
    }

    public function index(): AnonymousResourceCollection
    {
        $products = Product::filter(request()->input('filter', []))->with('images')->paginate();
        return ResourcesProduct::collection($products);
    }

    public function store(StoreRequest $request, StorageAction $storageAction): Product
    {
        $product = $storageAction->execute($request->validated(), new Product());
        ProductCreatedOrUpdated::dispatch($product);
        return $product;
    }

    public function update(UpdateRequest $request, Product $product, UpdateAction $updateAction): Product
    {
        $product = $updateAction->execute($request->validated(), $product);
        ProductCreatedOrUpdated::dispatch($product, 'Product Updated');
        return $product;
    }

    public function destroy(Product $product): Product
    {
        foreach ($product->images() as $image) {
            Storage::delete($image->path);
            $image->delete();
        }
        $product->delete();
        return $product;
    }

    public function disable(Product $product): Product
    {
        $product->update(['disabled_at' => now()]);
        return $product;
    }

    public function import(ImportRequest $importRequest): void
    {
        $path = $importRequest->file('products')->storeAs('imports/products', uniqid() . '.csv');
        ImportProducts::dispatch($path, auth()->user()->id);
    }
}
