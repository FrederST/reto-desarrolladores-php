<?php

namespace App\Filters\ModelFilters;

use App\Filters\Conditions\Merchants\MerchantQuery;
use App\Filters\Filter;
use App\Models\Product;

class ProductFilters extends Filter
{
    protected string $model = Product::class;

    protected array $applicableConditions = [
        'product_query' => MerchantQuery::class,
    ];

    protected function select(): Filter
    {
        $this->query->select(
            'products.id',
            'products.code',
            'products.name',
            'products.description',
            'products.quantity',
            'products.weight',
            'products.weight_unit_id',
            'products.price',
            'products.sale_price',
            'products.currency_id',
            'products.disabled_at',
            'products.created_at',
            'products.updated_at',
        );

        return $this;
    }
}
