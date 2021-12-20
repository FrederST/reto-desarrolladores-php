<?php

namespace App\Actions\Product;

use App\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UpdateAction extends Action
{
    public function execute(array $data, Model $product): Model
    {
        foreach ($data as $field => $value) {
            $product->{$field} = $value;
        }

        $product->save();

        Log::channel('product')->info('Product Updated', $product->toArray());
        return $product;
    }
}
