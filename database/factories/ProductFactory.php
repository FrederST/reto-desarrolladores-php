<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'quantity' => $this->faker->numberBetween(),
            'weight' => $this->faker->numberBetween(0, 1265),
            'price'=> $this->faker->numberBetween(0, 1265),
            'sale_price' => $this->faker->numberBetween(0, 1265),
        ];
    }
}
