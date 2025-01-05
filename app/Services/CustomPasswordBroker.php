<?php

namespace App\Services;

use Illuminate\Auth\Passwords\PasswordBroker as BasePasswordBroker;

class CustomPasswordBroker extends BasePasswordBroker
{
    /**
     * Send a password reset link to a user.
     *
     * @param  array  $credentials
     * @return string
     */
    public function sendResetLink(array $credentials)
    {
        $user = $this->getUser($credentials);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        // We will place a reset token in the user's session so that we can verify
        // the code exists and is valid when the user clicks on it. We will not
        // flash the old input and only keep the email address for security.
        $this->tokens->deleteExisting($user);

        $token = $this->tokens->create($user);

        $this->emailResetLink($user, $token);

        return static::RESET_LINK_SENT;
    }
}
