<?php

namespace App\Filters\Conditions\Order;

use App\Filters\Condition;
use App\Filters\Criteria;
use Illuminate\Database\Eloquent\Builder;

class OrderQuery extends Condition
{
    public static function append(Builder $query, Criteria $criteria): void
    {
        $query->where(function ($q) use ($criteria) {
            $q->where('orders.status', 'like', "%{$criteria}%")
                ->orWhere('orders.payment_method', 'like', "%{$criteria}%")
                ->orWhereDate('orders.created_at', '=', "%{$criteria}%");
        });
    }
}
