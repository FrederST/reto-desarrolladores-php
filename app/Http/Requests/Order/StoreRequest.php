<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public const MAX_STRING = 'max:255';

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['string', self::MAX_STRING],
            'first_name' => ['required', 'string', self::MAX_STRING],
            'last_name' => ['required', 'string', self::MAX_STRING],
            'address' => ['required', 'string', 'max:500'],
            'country_id' => ['required', 'numeric', 'exists:world_countries,id'],
            'city_id' => ['required', 'numeric', 'exists:world_cities,id'],
            'post_code' => ['numeric'],
            'phone_number' => ['required', 'numeric', 'digits:10'],
            'notes' => ['string'],
        ];
    }
}
