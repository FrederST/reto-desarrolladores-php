<?php

namespace App\Http\Requests\Product;

class UpdateRequest extends StoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        array_walk($rules, fn (&$item) => $item[0] = 'filled');
        return $rules;
    }
}
