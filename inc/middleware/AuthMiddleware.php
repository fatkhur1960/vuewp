<?php
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class AuthMiddleware
{
    public static function validate(WP_REST_Request $req)
    {
        $access_token = $req->get_header('X-Access-Token');
        if (empty($access_token)) {
            return ApiResult::error(__('Header X-Access-Token is not set'));
        }

        try {
            $val = TokenUtil::validate_token($access_token);

            $token = VueWpDao::get_token_by_user_id($val->data->id);
            $user = get_userdata($val->data->id);
            if (empty($token) || !$user) {
                return ApiResult::error(
                    __('Sorry, you are not allowed to do that!!!.')
                );
            }

            $req->set_param(
                'user',
                (object) [
                    'id' => $user->ID,
                    'username' => $user->user_login,
                    'user_email' => $user->user_email,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'display_name' => $user->display_name,
                    'avatar_url' => get_user_meta(
                        $user->ID,
                        'avatar_url',
                        true
                    ),
                    'description' => $user->description,
                    'posts' => count_user_posts($user->ID, 'post', true),
                    'user_registered' => $user->user_registered,
                ]
            );

            return true;
        } catch (ExpiredException $e) {
            return ApiResult::error('Access Token Expired');
        } catch (SignatureInvalidException $e) {
            return ApiResult::error('Invalid Access Token');
        }
    }
}
