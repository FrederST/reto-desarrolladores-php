<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class OrderItemResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'price' => CurrencyHelper::toCurrencyFormat($this->price),
            'product' => $this->whenLoaded('product'),
        ];
    }
}
