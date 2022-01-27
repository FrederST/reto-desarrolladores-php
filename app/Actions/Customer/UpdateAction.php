<?php

namespace App\Actions\Customer;

use App\Actions\Action;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class UpdateAction extends Action
{
    public function execute(array $data, Model $user): Model
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ]);

        if ($data['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $user->update([
                'email_verified_at' => null,
            ]);
            $user->sendEmailVerificationNotification();
        }
        return $user;
    }
}
