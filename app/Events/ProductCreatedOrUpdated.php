<?php

namespace App\Events;

use App\Models\Product;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreatedOrUpdated
{
    use Dispatchable;
    use SerializesModels;

    private Product $product;
    private string $message;

    public function __construct(Product $product, string $message = 'Product Crated')
    {
        $this->product = $product;
        $this->message = $message;
    }

    public function product(): Product
    {
        return $this->product;
    }

    public function message(): string
    {
        return $this->message;
    }
}
