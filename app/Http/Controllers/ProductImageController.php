<?php

namespace App\Http\Controllers;

use App\Actions\ProductImage\StorageAction;
use App\Events\ProductCreatedOrUpdated;
use App\Http\Requests\ProductImage\StoreRequest;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function upload(StoreRequest $request, string $productId, StorageAction $storageAction): ProductImage
    {
        $productImage = $storageAction->execute(array_merge($request->validated(), ['product_id' => $productId]), new ProductImage());
        ProductCreatedOrUpdated::dispatch($productImage->product, 'Created Product Image');
        return $productImage;
    }

    public function destroy(ProductImage $productImage): void
    {
        Storage::delete($productImage->path);
        $productImage->delete();
    }
}
