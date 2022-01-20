<?php

namespace App\Actions\Product;

use App\Actions\Action;
use App\Helpers\CurrencyHelper;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class StorageAction extends Action
{
    public function execute(array $data, Model $product): Model
    {
        $product->code = array_key_exists('code', $data) ? $data['code'] : $this->generateProductCode();
        $product->name = $data['name'];
        $product->description = $data['description'];
        $product->quantity = $data['quantity'];
        $product->weight = $data['weight'];
        $product->weight_unit_id = $data['weight_unit_id'];
        $product->price = $data['price'];
        $product->currency_id = CurrencyHelper::getDefaultCurrency()->id;
        $product->sale_price = CurrencyHelper::parseCurrency($data['sale_price']);

        $product->save();

        return $product;
    }

    private function generateProductCode() {
        $number = mt_rand(1000000000, 9999999999);

        if ($this->codeNumberExists($number)) {
            return $this->generateProductCode();
        }

        return $number;
    }

    private function codeNumberExists($number) {
        return Product::where('code', $number)->exists();
    }
}
