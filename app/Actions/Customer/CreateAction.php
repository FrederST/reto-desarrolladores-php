<?php

namespace App\Actions\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAction
{
    public function create(array $input): User
    {
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'password' => Hash::make('password'),
        ]);
        $user->assignRole('customer');
        return $user;
    }
}
