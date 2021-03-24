<?php
class VueWpDao
{
    private static $access_tokens = 'access_tokens';
    private static $vuewp_users = 'vuewp_users';

    public static function create_table()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $access_tokens = $wpdb->prefix . self::$access_tokens;
        $vuewp_users = $wpdb->prefix . self::$vuewp_users;

        if (
            $wpdb->get_var("show tables like '$access_tokens'") !=
            $access_tokens &&
            $wpdb->get_var("show tables like '$vuewp_users'") != $vuewp_users
        ) {
            $sql1 = "CREATE TABLE $access_tokens (
				`id` mediumint(9) NOT NULL AUTO_INCREMENT,
                wp_user_id INT(10) NOT NULL,
                token TEXT NOT NULL,
				expired_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				PRIMARY KEY (id)
			) $charset_collate;";

            $sql2 = "CREATE TABLE $vuewp_users (
                `id` mediumint(9) NOT NULL AUTO_INCREMENT,
                wp_user_id INT(10) NOT NULL,
                identifier VARCHAR(100) NOT NULL,
                auth_type VARCHAR(15) NOT NULL,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
            dbDelta($sql1);
            dbDelta($sql2);
        }
    }

    public static function save_token(int $user_id, object $token_data)
    {
        global $wpdb;
        $access_tokens = $wpdb->prefix . self::$access_tokens;

        $insert = $wpdb->insert(
            $access_tokens,
            [
                'expired_at' => date('Y-m-d H:i:s', $token_data->expired_at),
                'token' => $token_data->token,
                'wp_user_id' => $user_id,
            ],
            ['%s', '%s', '%d']
        );

        return $insert;
    }

    public static function remove_token(int $user_id)
    {
        global $wpdb;
        $access_tokens = $wpdb->prefix . self::$access_tokens;

        return $wpdb->delete(
            $access_tokens,
            [
                'wp_user_id' => $user_id,
            ],
            ['%d']
        );
    }

    public static function get_token_by_user_id(int $user_id)
    {
        global $wpdb;
        $access_tokens = $wpdb->prefix . self::$access_tokens;

        $token = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $access_tokens WHERE wp_user_id = %s",
                $user_id
            )
        );
        return $token;
    }

    public static function get_token(string $token)
    {
        global $wpdb;
        $access_tokens = $wpdb->prefix . self::$access_tokens;

        $token = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $access_tokens WHERE token = %s",
                $token
            )
        );
        return $token;
    }

    public static function saveUserIdentifier(int $user_id, string $identifier, string $type)
    {
        global $wpdb;
        $vuewp_users = $wpdb->prefix . self::$vuewp_users;

        $insert = $wpdb->insert(
            $vuewp_users,
            [
                'auth_type' => $type,
                'identifier' => $identifier,
                'wp_user_id' => $user_id,
            ],
            ['%s', '%s', '%d']
        );

        return $insert;
    }

    public static function getUserIDByProviderIdentifier($identifier, $type)
    {
        /** @var $wpdb WPDB */
        global $wpdb;
        $vuewp_users = $wpdb->prefix . self::$vuewp_users;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT wp_user_id FROM $vuewp_users WHERE auth_type = %s AND identifier = %s",
                [$type, $identifier]
            )
        );
    }

    public static function getProviderIdentifierByUserID($user_id, $type)
    {
        /** @var $wpdb WPDB */
        global $wpdb;
        $vuewp_users = $wpdb->prefix . self::$vuewp_users;

        return $wpdb->get_var(
            $wpdb->prepare(
                "SELECT identifier FROM $vuewp_users WHERE auth_type = %s AND wp_user_id = %d",
                [$type, $user_id]
            )
        );
    }

    /**
     * @param $user_id
     * Delete the link between the user account and the provider.
     */
    public static function removeConnectionByUserID($user_id, $type)
    {
        /** @var $wpdb WPDB */
        global $wpdb;
        $vuewp_users = $wpdb->prefix . self::$vuewp_users;

        return $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM $vuewp_users WHERE auth_type = %s AND wp_user_id = %d",
                [$type, $user_id]
            )
        );
    }
}
