<?php

namespace App\Filters\ModelFilters;

use App\Filters\Filter;
use App\Models\Order;

class OrderFilters extends Filter
{
    protected string $model = Order::class;

    protected function select(): Filter
    {
        $this->query->select(
            'orders.id',
            'orders.order_number',
            'orders.user_id',
            'orders.status',
            'orders.grand_total',
            'orders.item_count',
            'orders.payment_method',
            'orders.first_name',
            'orders.last_name',
            'orders.address',
            'orders.country_id',
            'orders.city_id',
            'orders.post_code',
            'orders.phone_number',
            'orders.notes',
            'orders.payment_process_id',
            'orders.payment_process_url',
            'orders.created_at',
            'orders.updated_at',
        );

        return $this;
    }
}
