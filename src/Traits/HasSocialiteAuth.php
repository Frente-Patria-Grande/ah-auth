<?php

namespace FrentePatriaGrande\AHAuth\Traits;

use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\Token;

trait HasSocialiteAuth
{
    public function getAhAccessToken(): string
    {
        if ($this->ah_access_token_expires_at->isPast()) {
            /** @var Token $token */
            $token = Socialite::driver('argentina-humana')->refreshToken($this->ah_refresh_token);
            $this->update([
                'ah_access_token' => $token->token,
                'ah_refresh_token' => $token->refreshToken,
                'ah_access_token_expires_at' => now()->addSeconds($token->expiresIn),
            ]);
        }

        return $this->ah_access_token;
    }
}
