<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ProductResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'code' => sprintf('%010d', $this->code),
            'name' => $this->name,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'weight' => [
                'value' => $this->weight,
                'unit' => $this->weightUnit->weight_unit_alias,
            ],
            'price' => [
                'value' => $this->price,
                'currency' => $this->currency->alphabetic_code,
            ],
            'sale_price' => [
                'value' => CurrencyHelper::toCurrencyFormat($this->sale_price),
                'currency' => $this->currency->alphabetic_code,
            ],
            'disabled_at' => $this->disabled_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'images' => $this->whenLoaded('images'),
        ];
    }
}
