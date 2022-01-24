<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class ShoppingCartResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'shopping_cart_id' => $this->shopping_cart_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'total' => CurrencyHelper::toCurrencyFormat($this->total),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'product' => $this->whenLoaded('product'),
        ];
    }
}
