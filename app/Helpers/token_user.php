<?php

use Laravel\Sanctum\Sanctum;

if (!function_exists("token_user")) {
    function token_user($token)
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

        return $isValid ? $accessToken->tokenable : false;
    }
}
