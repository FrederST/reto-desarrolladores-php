<?php

namespace App\Actions\Product;

use App\Actions\Action;
use App\Helpers\CurrencyHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class UpdateAction extends Action
{
    public function execute(array $data, Model $product): Model
    {
        foreach ($data as $field => $value) {
            $product->{$field} = $value;
        }

        if (array_key_exists('sale_price', $data)) {
            $product->sale_price = CurrencyHelper::parseCurrency($data['sale_price']);
        }

        $product->save();

        Log::channel('product')->info('Product Updated', $product->toArray());
        return $product;
    }
}
