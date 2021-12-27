<?php

namespace App\Actions\ProductImage;

use App\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class StorageAction extends Action
{
    public function execute(array $data, Model $productImage): Model
    {
        $productId = $data['product_id'];
        $image = $data['image'];
        $storagePath = 'productsImgs/' . $productId . '/images';
        $imageName = 'product-' . time() . '.' . $image->getClientOriginalExtension();
        $productImage->product_id = $productId;
        $productImage->path = $image->storeAs($storagePath, $imageName);
        $productImage->save();
        Log::channel('product')->info('Crated Product Image', $productImage->toArray());
        return $productImage;
    }
}
