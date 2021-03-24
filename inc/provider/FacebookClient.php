<?php

class FacebookClient extends VueWpProvider
{
    public $id = 'facebook';

    private const DEFAULT_GRAPH_VERSION = 'v9.0';

    protected $scopes = ['public_profile', 'email'];

    protected $fields = ['email', 'last_name', 'first_name', 'name', 'picture'];

    protected $accessTokenData = [];

    public function __construct()
    {
        parent::__construct($this->id);

        $this->endpointAccessToken =
            'https://graph.facebook.com/' .
            self::DEFAULT_GRAPH_VERSION .
            '/oauth/access_token';
        $this->endpointRestAPI =
            'https://graph.facebook.com/' . self::DEFAULT_GRAPH_VERSION . '/';
    }

    private function getEndpointAuthorization()
    {
        $endpointAuthorization = 'https://www.facebook.com/';
        $endpointAuthorization .= self::DEFAULT_GRAPH_VERSION . '/dialog/oauth';

        return $endpointAuthorization;
    }

    public function authorize()
    {
        return ApiResult::success([
            'auth_uri' =>
                $this->getEndpointAuthorization() .
                $this->query_string([
                    'client_id' => FACEBOOK_APP_ID,
                    'redirect_uri' => FACEBOOK_CALLBACK,
                    'scope' => implode(',', $this->scopes),
                    'state' => md5(time()),
                ]),
        ]);
    }

    public function get_user($req)
    {
        if (empty($this->accessTokenData)) {
            $accessTokenData = $this->get($this->endpointAccessToken, [
                'code' => $req->get_param('code'),
                'client_id' => FACEBOOK_APP_ID,
                'client_secret' => FACEBOOK_APP_SECRET,
                'redirect_uri' => FACEBOOK_CALLBACK,
            ]);

            if (is_wp_error($accessTokenData)) {
                return ApiResult::error(
                    $accessTokenData->get_error_message(),
                    $accessTokenData->get_error_data(),
                );
            }

            $this->accessTokenData = $accessTokenData;
        }

        return $this->getTokenUserData();
    }

    private function getTokenUserData()
    {
        $userData = $this->get($this->endpointRestAPI . 'me', [
            'access_token' => $this->accessTokenData['access_token'],
            'fields' => implode(',', $this->fields),
        ]);

        if (is_wp_error($userData)) {
            return ApiResult::error(
                $userData->get_error_message(),
                $userData->get_error_data(),
            );
        }

        $this->setAuthUserData($userData);

        $user = $this->liveConnectGetUserProfile();
        if (is_wp_error($user)) {
            return ApiResult::error($user->get_error_message());
        }

        $token_data = (object) TokenUtil::generate_token($user);

        // Convert the response to array and display it.
        return ApiResult::success([
            'id' => $user->ID,
            'display_name' => $user->display_name,
            'nicename' => $user->user_nicename,
            'access_token' => $token_data->token,
            'expired_at' => $token_data->expired_at,
        ]);

        return ApiResult::success($userData);
    }

    private function get(string $url, array $body)
    {
        $error = new WP_Error();
        $http_args = [
            'timeout' => 15,
            'user-agent' => 'WordPress',
            'body' => $body,
        ];

        $request = wp_remote_get($url, $http_args);

        if (is_wp_error($request)) {
            $error->add(
                '',
                $request->get_error_message(),
                $request->get_error_data()
            );
            return $error;
        } elseif (wp_remote_retrieve_response_code($request) !== 200) {
            $response = $this->getResponse($request);
            try {
                $error->add('', $response['error']['message']);
                return $error;
            } catch (Exception $e) {
                $error->add('', 'Facebook authentication error');
                return $error;
            }
        }

        return $this->getResponse($request);
    }

    public function getAuthUserData($key)
    {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return !empty($this->authUserData['email'])
                    ? $this->authUserData['email']
                    : '';
            case 'name':
                return $this->authUserData['name'];
            case 'first_name':
                return $this->authUserData['first_name'];
            case 'last_name':
                return $this->authUserData['last_name'];
            case 'picture':
                $profilePicture = $this->authUserData['picture'];
                if (
                    !empty($profilePicture) &&
                    !empty($profilePicture['data'])
                ) {
                    if (
                        isset($profilePicture['data']['is_silhouette']) &&
                        !$profilePicture['data']['is_silhouette']
                    ) {
                        return $profilePicture['data']['url'];
                    }
                }

                return '';
        }
    }
}
