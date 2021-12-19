<?php

namespace App\Http\Requests\Customer;

class UpdateRequest extends StoreRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        return array_replace($rules, ['email' => ['required', 'string', 'email', 'max:255']]);
    }
}
