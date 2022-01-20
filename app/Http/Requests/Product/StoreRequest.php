<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    private const GTE_RULE = 'gte:0';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['string', 'digits_between:1,10'],
            'name' => ['required', 'string', 'min:1', 'max:100'],
            'description' => ['required', 'string'],
            'quantity' => ['required', 'numeric', self::GTE_RULE],
            'weight' => ['required', 'numeric', self::GTE_RULE],
            'weight_unit_id' => ['required', 'numeric', 'exists:weight_units,id', self::GTE_RULE],
            'price' => ['required', 'numeric', self::GTE_RULE],
            'sale_price' => ['required', 'numeric', 'gte:price'],
        ];
    }
}
