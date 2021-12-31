<?php

namespace Database\Seeders;

use App\Models\WeightUnit;
use File;
use Illuminate\Database\Seeder;

class WeightUnitSeeder extends Seeder
{
    public function run(): void
    {
        $weightUnits = json_decode(File::get('database/data/weightUnits.json'));

        foreach ($weightUnits as $key => $value) {
            WeightUnit::create([
                'weight_unit_alias' => $key,
                'weight_unit_name' => $value,
            ]);
        }
    }
}
