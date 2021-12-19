<?php

namespace App\Actions\Customer;

use App\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StorageAction extends Action
{
    public function execute(array $data, Model $user): Model
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->password = Hash::make('password');
        $user->save();
        $user->assignRole('customer');
        Log::channel('customer')->info('Customer/User Created', $user->toArray());
        return $user;
    }

}
