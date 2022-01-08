<?php

namespace Database\Seeders;

use App\Models\ShoppingCart;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'email' => 'test@test.com',
        ]);
        $user->assignRole('admin');
        $user->shoppingCart()->save(new ShoppingCart(['user_id' => $user->id]));
    }
}
