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
            $q->where('name', 'like', "%{$criteria->getField('name')}%")
                ->orWhere('description', 'like', "%{$criteria->getField('description')}%")
                ->orWhere('sale_price', 'like', "%{$criteria->getField('sale_price')}%")
                ->orWhere('weight_unit_id', 'like', "%{$criteria->getField('weight_unit_id')}%")
                ->orWhereDate('created_at', '=', "%{$criteria->getField('created_at')}%");
        });
    }
}
