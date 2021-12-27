<?php

namespace App\Http\Requests\Product;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use PasswordValidationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'quantity' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'sale_price' => ['required', 'numeric'],
            'status' => ['required'],
        ];
    }
}
