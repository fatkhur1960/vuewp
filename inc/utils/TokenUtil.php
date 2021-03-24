<?php
use Firebase\JWT\JWT;

class TokenUtil
{
    private static $issuer_claim = 'SATIR';
    private static $audience_claim = 'PCIPNUIPPNU';

    public static function validate_token($token)
    {
        return JWT::decode($token, SECURE_AUTH_KEY, ['HS256']);
    }

    public static function generate_token($user)
    {
        $issuedat_claim = time(); // issued at
        $notbefore_claim = $issuedat_claim + 10; //not before in seconds
        $expire_claim = $issuedat_claim + 60 * 60 * 24 * 7; // expire in next 7 days
        $token = [
            'iss' => self::$issuer_claim,
            'aud' => self::$audience_claim,
            'iat' => $issuedat_claim,
            'nbf' => $notbefore_claim,
            'exp' => $expire_claim,
            'data' => [
                'id' => $user->ID,
                'email' => $user->user_email,
            ],
        ];

        return [
            'token' => JWT::encode($token, SECURE_AUTH_KEY),
            'expired_at' => $expire_claim,
        ];
    }
}
