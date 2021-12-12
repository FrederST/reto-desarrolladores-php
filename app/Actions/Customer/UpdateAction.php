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

        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->update([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'],
            ]);
        }
    }

    protected function updateVerifiedUser(User $user, array $input)
    {
        $user->update([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();
    }
}
