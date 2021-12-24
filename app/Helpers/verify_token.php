<?php

use Laravel\Sanctum\Sanctum;

if (!function_exists("verify_token")) {
    function verify_token($token)
    {
        $expiration = null;
        $model = Sanctum::$personalAccessTokenModel;
        $accessToken = $model::findToken($token);
        if (!$accessToken) {
            return false;
        }

        $isValid =
            !$expiration ||
            $accessToken->created_at->gt(now()->subMinutes($expiration));
        if (is_callable(Sanctum::$accessTokenAuthenticationCallback)) {
            $isValid = (bool) (Sanctum::$accessTokenAuthenticationCallback)(
                $accessToken,
                $isValid
            );
        }

        return $isValid;
    }
}
