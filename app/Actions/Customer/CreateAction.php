<?php

namespace App\Actions\Customer;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
        Log::channel('customer')->info('Customer/User Created', $user->toArray());
        return $user;
    }
}
