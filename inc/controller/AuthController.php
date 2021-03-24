<?php
class AuthController
{
    public static function init()
    {
        register_rest_route('auth/v1', '/login', [
            'methods' => 'POST',
            'callback' => 'AuthController::authorize',
            'args' => ['username', 'password'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('auth/v1', '/register', [
            'methods' => 'POST',
            'callback' => 'AuthController::create_user',
            'args' => [
                'username',
                'password',
                'email',
                'first_name',
                'last_name',
            ],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('auth/v1', '/logout', [
            'methods' => 'POST',
            'callback' => 'AuthController::unauthorize',
            'permission_callback' => 'AuthMiddleware::validate',
        ]);
        register_rest_route('auth/v1', '/me', [
            'methods' => 'GET',
            'callback' => 'AuthController::me_info',
            'permission_callback' => 'AuthMiddleware::validate',
        ]);
    }

    public static function me_info($req) 
    {
        return ApiResult::success($req->get_param('user'));
    }

    public static function authorize($req)
    {
        $user = wp_authenticate(
            wp_slash($req->get_param('username')),
            wp_slash($req->get_param('password'))
        );

        if (is_wp_error($user)) {
            return ApiResult::error(strip_tags($user->get_error_message()));
        }

        $token_data = VueWpDao::get_token_by_user_id($user->ID);
        if (empty($token_data)) {
            $token_data = (object) TokenUtil::generate_token($user);
            if (!VueWpDao::save_token($user->ID, $token_data)) {
                return ApiResult::error(__('Cannot generate token'));
            }
        }

        return ApiResult::success([
            'id' => $user->ID,
            'display_name' => $user->display_name,
            'nicename' => $user->user_nicename,
            'access_token' => $token_data->token,
            'expired_at' => $token_data->expired_at,
        ]);
    }

    public static function unauthorize($req)
    {
        $user = $req->get_param('user');
        $remove_token = VueWpDao::remove_token($user->id);
        if (!$remove_token) {
            return ApiResult::error('Failed to Unauthorizing');
        }

        return ApiResult::success(null);
    }

    public static function create_user($req)
    {
        $user_id = wp_create_user(
            wp_slash($req->get_param('username')),
            wp_slash($req->get_param('password')),
            wp_slash($req->get_param('email'))
        );

        if (is_wp_error($user_id)) {
            return ApiResult::error(strip_tags($user_id->get_error_message()));
        }

        $user_update = ['ID' => $user_id];

        if (isset($req['first_name'])) {
            $user_update['first_name'] = wp_slash($req['first_name']);
        }

        if (isset($req['last_name'])) {
            $user_update['last_name'] = wp_slash($req['last_name']);
        }

        $update_user = wp_update_user($user_update);

        if (is_wp_error($update_user)) {
            wp_delete_user($user_id);
            return ApiResult::error(strip_tags($user_id->get_error_message()));
        }

        $user = get_user_by('id', $user_id);
        $token_data = (object) TokenUtil::generate_token($user);
        VueWpDao::save_token($user->ID, $token_data);

        return ApiResult::success([
            'id' => $user_id,
            'display_name' => $user->display_name,
            'nicename' => $user->user_nicename,
            'access_token' => $token_data->token,
            'expired_at' => $token_data->expired_at,
        ], 'Registration success');
    }
}
