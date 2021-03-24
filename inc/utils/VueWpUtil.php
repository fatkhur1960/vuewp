<?php
class VueWpUtil
{
    /**
     * @param $username
     * Makes the username in an appropriate format. Removes white space and some special characters.
     * Also turns it into lowercase. And put a prefix before the username if user_prefix is set.
     * If this formated username is valid returns it, else return false.
     *
     * @return bool|string
     */
    public static function sanitizeUserName($username)
    {
        if (empty($username)) {
            return false;
        }

        $username = strtolower($username);

        $username = preg_replace('/\s+/', '', $username);

        $sanitized_user_login = sanitize_user($username, true);

        if (empty($sanitized_user_login)) {
            return false;
        }

        if (!validate_username($sanitized_user_login)) {
            return false;
        }

        return $sanitized_user_login;
    }
}
