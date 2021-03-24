<?php
class Provider
{
    public static $providers = ['facebook', 'twitter'];
    public static $enabledProviders = array();

    public static function add_provider($provider)
    {
        self::$enabledProviders[$provider->id] = $provider;
    }

    public static function validate_provider($provider)
    {
        if (!in_array($provider, self::$providers)) {
            return ApiResult::error(
                'auth_error',
                'Invalid auth provider'
            );
        }

        return true;
    }
}
