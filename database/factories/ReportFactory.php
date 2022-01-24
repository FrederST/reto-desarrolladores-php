<?php

namespace Database\Factories;

use App\Constants\ReportStatus;
use App\Constants\ReportTypes;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElements(ReportTypes::TYPES),
            'status' => $this->faker->randomElements(ReportStatus::STATUSES),
            'info' => $this->faker->text(),
            'path'=> '',
            'filters' => '',
            'user_id' => User::factory(),
        ];
    }
}
