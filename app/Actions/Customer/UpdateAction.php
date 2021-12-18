<?php

namespace App\Actions\Customer;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UpdateAction
{
    public function update(User $user, array $input)
    {
        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        $user->update([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
        ]);

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $user->update([
                'email_verified_at' => null,
            ]);

            $user->sendEmailVerificationNotification();
        }
    }
}
