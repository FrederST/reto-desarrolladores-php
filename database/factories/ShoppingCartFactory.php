<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingCartFactory extends Factory
{

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->assignRole('admin')->id,
        ];
    }
}
