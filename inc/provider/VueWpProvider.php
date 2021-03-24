<?php
abstract class VueWpProvider
{
    protected $authUserData = [];
    private $clientId = '';

    public function __construct($id)
    {
        $this->clientId = $id;
    }

    abstract function getAuthUserData($key);

    protected function setAuthUserData(array $data)
    {
        $this->authUserData = $data;
    }

    protected function liveConnectGetUserProfile()
    {
        $user_id = VueWpDao::getUserIDByProviderIdentifier(
            $this->getAuthUserData('id'),
            $this->clientId
        );
        if ($user_id !== null && !get_user_by('id', $user_id)) {
            VueWpDao::removeConnectionByUserID($user_id, $this->clientId);
            $user_id = null;
        }

        if ($user_id == null) {
            return $this->prepareRegister();
        }
        return $this->login($user_id);
    }

    private function prepareRegister()
    {
        $user_id = false;
        $providerUserID = $this->getAuthUserData('id');
        $email = $this->getAuthUserData('email');

        if (empty($email)) {
            $email = '';
        } else {
            $user_id = email_exists($email);
        }

        if ($user_id === false) {
            return $this->register($providerUserID, $email);
        } else {
            if (!$this->linkUserIdentifier($user_id, $providerUserID)) {
                $error = new WP_Error();
                $error->add(
                    'link_user_error',
                    'Unable link current account to existing user'
                );
                return $error;
            }
            return $this->login($user_id);
        }
    }

    private function login($user_id)
    {
        return get_userdata($user_id);
    }

    /**
     * @param $providerID
     * @param $email
     * Registers the user.
     */
    private function register($providerID, $email)
    {
        $sanitized_user_login = false;
        $sanitized_user_login = VueWpUtil::sanitizeUserName(
            $this->getAuthUserData('first_name') .
                $this->getAuthUserData('last_name')
        );
        if ($sanitized_user_login === false) {
            $sanitized_user_login = VueWpUtil::sanitizeUserName(
                $this->getAuthUserData('username')
            );
            if ($sanitized_user_login === false) {
                $sanitized_user_login = VueWpUtil::sanitizeUserName(
                    $this->getAuthUserData('name')
                );
            }
        }

        $email = $this->getAuthUserData('email');
        $userData = [
            'email' => $email,
            'username' => $sanitized_user_login,
        ];

        /**
         * -If neither of the usernames ( first_name & last_name, secondary_name) are appropriate, the fallback username will be combined with and id that was sent by the provider.
         * -In this way we can generate an appropriate username.
         */
        if (empty($userData['username'])) {
            $userData['username'] = sanitize_user(md5(uniqid(rand())), true);
        }

        /**
         * If the username is already in use, it will get a number suffix, that is not registered yet.
         */
        $default_user_name = $userData['username'];
        $i = 1;
        while (username_exists($userData['username'])) {
            $userData['username'] = $default_user_name . $i;
            $i++;
        }

        /**
         * Generates a random password. And set the default_password_nag to true. So the user get notify about randomly generated password.
         */
        if (empty($userData['password'])) {
            $userData['password'] = wp_generate_password(12, false);

            add_action('user_register', [
                $this,
                'registerCompleteDefaultPasswordNag',
            ]);
        }

        $user_data = [
            'user_login' => wp_slash($userData['username']),
            'user_email' => wp_slash($userData['email']),
            'user_pass' => $userData['password'],
        ];

        $name = $this->getAuthUserData('name');
        if (!empty($name)) {
            $user_data['display_name'] = $name;
        }

        $first_name = $this->getAuthUserData('first_name');
        if (!empty($first_name)) {
            $user_data['first_name'] = $first_name;
        }

        $last_name = $this->getAuthUserData('last_name');
        if (!empty($last_name)) {
            $user_data['last_name'] = $last_name;
        }

        $description = $this->getAuthUserData('description');
        if (!empty($description)) {
            $user_data['description'] = $description;
        }

        $user_id = wp_insert_user($user_data);

        if (is_wp_error($user_id)) {
            return $user_id;
        } elseif ($user_id === 0) {
            $error = new WP_Error();
            $error->add('create_user_error', 'Unable to create user');
            return $error;
        }

        $link = $this->linkUserIdentifier(
            $user_id,
            $this->getAuthUserData('id')
        );
        if (!$link) {
            if (wp_delete_user($user_id)) {
                $error = new WP_Error();
                $error->add(
                    'create_user_error',
                    'Unable to create user with ' . $this->clientId
                );
                return $error;
            }
        }

        // Create avatar
        add_user_meta(
            $user_id,
            'avatar_url',
            $this->getAuthUserData('picture')
        );

        return get_userdata($user_id);
    }

    /**
     * By setting the default_password_nag to true, will inform the user about random password usage.
     */
    public function registerCompleteDefaultPasswordNag($user_id)
    {
        update_user_option($user_id, 'default_password_nag', true, true);
    }

    private function linkUserIdentifier($user_id, $identifier)
    {
        return VueWpDao::saveUserIdentifier(
            $user_id,
            $identifier,
            $this->clientId
        );
    }

    protected function getResponse($request)
    {
        return json_decode(wp_remote_retrieve_body($request), true);
    }

    protected function query_string(array $data)
    {
        $queries = [];
        foreach ($data as $key => $value) {
            array_push($queries, "$key=$value");
        }

        return '?' . implode('&', $queries);
    }
}
