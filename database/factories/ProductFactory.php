<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Product;
use App\Models\WeightUnit;
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
            'weight_unit_id' => WeightUnit::inRandomOrder()->first()->id,
            'price'=> $this->faker->numberBetween(0, 1265),
            'sale_price' => $this->faker->numberBetween(0, 1265),
            'currency_id' => Currency::inRandomOrder()->first()->id,
        ];
    }
}
