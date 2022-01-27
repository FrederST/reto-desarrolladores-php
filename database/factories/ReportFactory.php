<?php

namespace Database\Factories;

use App\Constants\ReportStatus;
use App\Constants\ReportTypes;
use App\Models\Product;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        return [
            'type' => Arr::random(ReportTypes::TYPES),
            'status' => Arr::random(ReportStatus::STATUSES),
            'info' => $this->faker->text(),
            'path'=> '',
            'filters' => '',
            'user_id' => User::factory(),
        ];
    }
}
