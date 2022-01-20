<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class Product extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'weight' => $this->weight,
            'weight_unit_id' => $this->weight_unit_id,
            'price' => $this->price,
            'sale_price' => CurrencyHelper::toCurrencyFormat($this->sale_price),
            'currency_id' => $this->currency_id,
            'disabled_at' => $this->disabled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
