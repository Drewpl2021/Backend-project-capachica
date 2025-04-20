<?php

namespace App\Traits;

use App\Models\User;

trait TokenHelper
{
    // Crear token para un usuario
    public function createToken($user)
    {
        return $user->createToken('Token')->accessToken;
    }

    // Revocar token de un usuario
    public function revokeToken($user)
    {
        $user->tokens->each(function ($token) {
            $token->delete();
        });
    }
}
