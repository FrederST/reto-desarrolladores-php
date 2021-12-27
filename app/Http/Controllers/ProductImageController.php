<?php

namespace App\Http\Controllers;

use App\Actions\ProductImage\StorageAction;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{

    public function upload(Request $request, string $productId, StorageAction $storageAction): ProductImage
    {
        return $storageAction->execute(array_merge($request->all(), ['product_id' => $productId]), new ProductImage);
    }

    public function destroy(ProductImage $productImage): void
    {
        Storage::delete($productImage->path);
        $productImage->delete();
    }
}
