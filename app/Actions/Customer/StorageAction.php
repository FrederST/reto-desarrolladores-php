<?php

namespace App\Actions\Customer;

use App\Actions\Action;
use App\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

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
        $user->shoppingCart()->save(new ShoppingCart(['user_id' => $user->id]));
        return $user;
    }
}
