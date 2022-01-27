<?php

namespace Database\Factories;

use App\Constants\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Khsing\World\Models\Country;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $country = Country::first();
        return [
            'payment_method' => 'place_to_pay',
            'user_id' => User::factory(),
            'order_number' => $this->faker->name(),
            'grand_total' => $this->faker->numberBetween(),
            'item_count' => $this->faker->numberBetween(2, 5555333),
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->lastName(),
            'address' => $this->faker->address(),
            'country_id' => $country->id,
            'city_id' => $country->cities()->first()->id,
            'post_code' => $this->faker->numberBetween(1, 10),
            'phone_number' => $this->faker->phoneNumber(),
            'notes' => $this->faker->text(),
            'status' => OrderStatus::STATUS_PENDING,
        ];
    }
}
