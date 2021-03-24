<?php

use Risan\OAuth1\OAuth1Factory;

class TwitterClient extends VueWpProvider
{
    private $client;

    public $id = 'twitter';
    public $profile_image_size = 'normal';
    private $base_url = 'https://api.twitter.com/';
    private $path = '/account/verify_credentials';

    public function __construct()
    {
        parent::__construct($this->id);

        $this->client = OAuth1Factory::create([
            'client_credentials_identifier' => TWITTER_CLIENT_ID,
            'client_credentials_secret' => TWITTER_CLIENT_SECRET,
            'temporary_credentials_uri' => $this->base_url . 'oauth/request_token',
            'authorization_uri' => $this->base_url . 'oauth/authorize',
            'token_credentials_uri' => $this->base_url . 'oauth/access_token',
            'callback_uri' => TWITTER_CALLBACK,
        ]);
    }

    public function get_user($req)
    {
        $this->getCredential($req);

        $auth_url = $this->base_url . '1.1' . $this->path . '.json';
        $response = $this->client->request(
            'GET',
            $auth_url . $this->query_string([
                'include_email' => true,
                'include_entities' => false,
                'skip_status' => true,
            ])
        );

        $this->setAuthUserData(
            json_decode($response->getBody()->getContents(), true)
        );

        $user = $this->liveConnectGetUserProfile();
        if(is_wp_error($user)) {
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
    }

    public function authorize()
    {
        // STEP 1: Obtain a temporary credentials (also known as the request token)
        $temporaryCredentials = $this->client->requestTemporaryCredentials();

        // STEP 2: Generate and redirect user to authorization URI.
        $authorizationUri = $this->client->buildAuthorizationUri(
            $temporaryCredentials
        );
        return ApiResult::success([
            'auth_uri' => $authorizationUri,
            'creds' => serialize($temporaryCredentials),
        ]);
    }

    private function getCredential($req)
    {
        // Get back the previosuly generated temporary credentials (step 1).
        $temporaryCredentials = unserialize($req->get_param('creds'));

        $tokenCredentials = $this->client->requestTokenCredentials(
            $temporaryCredentials,
            $req->get_param('oauth_token'),
            $req->get_param('oauth_verifier')
        );

        $this->client->setTokenCredentials($tokenCredentials);
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getAuthUserData($key)
    {
        switch ($key) {
            case 'id':
                return $this->authUserData['id'];
            case 'email':
                return !empty($this->authUserData['email'])
                    ? $this->authUserData['email']
                    : '';
            case 'description':
                return !empty($this->authUserData['description'])
                    ? $this->authUserData['description']
                    : '';
            case 'name':
                return $this->authUserData['name'];
            case 'username':
                return $this->authUserData['screen_name'];
            case 'first_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[0]) ? $name[0] : '';
            case 'last_name':
                $name = explode(' ', $this->getAuthUserData('name'), 2);

                return isset($name[1]) ? $name[1] : '';
            case 'picture':
                $profile_image_size = $this->profile_image_size;
                $profile_image = $this->authUserData['profile_image_url_https'];
                $avatar_url = '';
                if (!empty($profile_image)) {
                    switch ($profile_image_size) {
                        case 'mini':
                            $avatar_url = str_replace(
                                '_normal.',
                                '_' . $profile_image_size . '.',
                                $profile_image
                            );
                            break;
                        case 'normal':
                            $avatar_url = $profile_image;
                            break;
                        case 'bigger':
                            $avatar_url = str_replace(
                                '_normal.',
                                '_' . $profile_image_size . '.',
                                $profile_image
                            );
                            break;
                        case 'original':
                            $avatar_url = str_replace(
                                '_normal.',
                                '.',
                                $profile_image
                            );
                            break;
                    }
                }

                return $avatar_url;
        }
    }
}
