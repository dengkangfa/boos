<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class DkfEloquentUserProvider extends EloquentUserProvider
{
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        if (strlen($plain) === 60) {
            return $plain === $user->getAuthPassword();
        }
        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}