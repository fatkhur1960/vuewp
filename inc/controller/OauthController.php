<?php
class OauthController
{
    public function __construct()
    {
        Provider::add_provider(new TwitterClient());
        Provider::add_provider(new FacebookClient());
    }

    public static function init()
    {
        register_rest_route('auth/v1', '/(?P<provider>\w[\w\s\-]*)/request', [
            'methods' => 'GET',
            'args' => [
                'provider' => [
                    'required' => true,
                    'validate_callback' => 'Provider::validate_provider',
                ],
            ],
            'callback' => 'OauthController::authorize',
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('auth/v1', '/(?P<provider>\w[\w\s\-]*)/login', [
            'methods' => 'POST',
            'callback' => 'OauthController::request_user',
            'args' => [
                'provider' => [
                    'required' => true,
                    'validate_callback' => 'Provider::validate_provider',
                ],
                'creds' => ['required' => false],
                'oauth_token' => ['required' => false],
                'oauth_verifier' => ['required' => false],
                'code' => ['required' => false],
            ],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function authorize($request)
    {
        $provider = Provider::$enabledProviders[$request['provider']];
        try {
            $data = $provider->authorize();
        } catch (Exception $e) {
            return ApiResult::error(strip_tags($e->getMessage()));
        }

        return $data;
    }

    public static function request_user($request)
    {
        $provider = Provider::$enabledProviders[$request['provider']];
        try {
            $data = $provider->get_user($request);
        } catch (Exception $e) {
            return ApiResult::error(strip_tags($e->getMessage()));
        }

        return $data;
    }
}
