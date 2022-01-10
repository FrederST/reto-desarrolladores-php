<?php

namespace App\Filters\Conditions\Product;

use App\Filters\Condition;
use App\Filters\Criteria;
use Illuminate\Database\Eloquent\Builder;

class ProductQuery extends Condition
{
    public static function append(Builder $query, Criteria $criteria): void
    {
        $query->where(function ($q) use ($criteria) {
            $q->where('products.name', 'like', "%{$criteria}%")
                ->orWhere('products.description', 'like', "%{$criteria}%")
                ->orWhere('products.sale_price', 'like', "%{$criteria}%")
                ->orWhere('products.weight_unit_id', 'like', "%{$criteria}%");
        });
    }
}
