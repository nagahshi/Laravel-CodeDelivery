<?php

namespace CodeDelivery\OAuth2;

use Illuminate\Support\Facades\Auth;

/**
 * Description of PasswordVerifier
 *
 * @author Willian
 */
class PasswordVerifier
{

    public function verify($username, $password)
    {
        $credentials = [
            'email' => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials))
        {
            return Auth::user()->id;
        } else
        {
            abort(403, 'User not found');
        }

        return false;
    }

}
