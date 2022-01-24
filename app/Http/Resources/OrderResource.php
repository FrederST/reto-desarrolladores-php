<?php

namespace App\Http\Resources;

use App\Helpers\CurrencyHelper;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class OrderResource extends JsonResource
{
    public function toArray($request): array|Arrayable|JsonSerializable
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'user_id' => $this->order_number,
            'status' => $this->status,
            'grand_total' => CurrencyHelper::toCurrencyFormat($this->grand_total),
            'item_count' => $this->item_count,
            'payment_method' => $this->payment_method,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            'city_id' => $this->city_id,
            'country_id' => $this->country_id,
            'post_code' => $this->post_code,
            'phone_number' => $this->phone_number,
            'notes' => $this->notes,
            'payment_process_id' => $this->payment_process_id,
            'payment_process_url' => $this->payment_process_url,
        ];
    }
}
