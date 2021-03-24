<?php
class ValidatorController
{
    public static function init()
    {
        register_rest_route('validator/v1', '/post', [
            'methods' => 'GET',
            'callback' => 'ValidatorController::validate_post_slug',
            'args' => ['slug'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('validator/v1', '/category', [
            'methods' => 'GET',
            'callback' => 'ValidatorController::validate_category_slug',
            'args' => ['slug'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('validator/v1', '/author', [
            'methods' => 'GET',
            'callback' => 'ValidatorController::validate_author',
            'args' => ['nickname'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('validator/v1', '/page', [
            'methods' => 'GET',
            'callback' => 'ValidatorController::validate_page_slug',
            'args' => ['slug'],
            'permission_callback' => '__return_true',
        ]);
    }
    public static function validate_post_slug(WP_REST_Request $request)
    {
        $posts = get_posts([
            'name' => $request->get_param('slug'),
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 1,
        ]);

        if (empty($posts)) {
            return ['valid' => false];
        }

        return ['valid' => true];
    }
    public static function validate_category_slug(WP_REST_Request $request)
    {
        $cat = get_category_by_slug($request->get_param('slug'));

        if (!$cat) {
            return ['valid' => false];
        }

        return ['valid' => true, 'data' => $cat->name];
    }
    public static function validate_page_slug(WP_REST_Request $request)
    {
        $posts = get_page_by_path($request->get_param('slug'));

        if (empty($posts)) {
            return ['valid' => false];
        }

        return ['valid' => true];
    }
    public static function validate_author($request)
    {
        $author = get_user_by('slug', $request->get_param('nickname'));

        if (empty($author)) {
            return ['valid' => empty($author), 'data' => null];
        }

        return ['valid' => true, 'data' => $author->data->display_name];
    }
}
