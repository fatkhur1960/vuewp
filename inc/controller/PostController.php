<?php

class PostController
{
    public static $namespace = 'post/v1';

    public static function init()
    {
        register_rest_route(self::$namespace, '/add', [
            'methods' => 'POST',
            'callback' => 'PostController::create_post',
            'permission_callback' => 'AuthMiddleware::validate',
            'args' => [
                'post_content' => ['required' => true],
                'post_title' => ['required' => true],
                'post_category' => ['required' => false],
                'tags_input' => ['required' => false],
                'attachment_id' => ['required' => false],
            ],
        ]);
        register_rest_route(self::$namespace, '/update', [
            'methods' => 'POST',
            'callback' => 'PostController::update_post',
            'permission_callback' => 'AuthMiddleware::validate',
            'args' => [
                'id' => ['required' => true],
                'post_content' => ['required' => true],
                'post_title' => ['required' => true],
                'post_category' => ['required' => false],
                'tags_input' => ['required' => false],
            ],
        ]);
        register_rest_route(self::$namespace, '/delete', [
            'methods' => 'POST',
            'callback' => 'PostController::delete_post',
            'permission_callback' => 'AuthMiddleware::validate',
            'args' => [
                'id' => ['required' => true],
            ],
        ]);
        register_rest_route(self::$namespace, '/upload', [
            'methods' => 'POST',
            'callback' => 'PostController::create_attachment',
            'permission_callback' => 'AuthMiddleware::validate',
        ]);
    }

    public static function create_attachment(WP_Rest_Request $req)
    {
        $data = $req->get_file_params();
        $file = $data['file'];
        $file_tmp = file_get_contents($file['tmp_name']);
        $uploadfile = wp_upload_dir(Date('yyyy/mm'));

        $upload = wp_upload_bits($file['name'], null, $file_tmp);
        move_uploaded_file($upload['file'], $uploadfile['path']);

        $filename = $upload['file'];

        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = [
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit',
        ];

        $attach_id = wp_insert_attachment($attachment, $filename, 0, true);
        if(is_wp_error($attach_id)) {
            return ApiResult::error($attach_id->get_error_message());
        }

        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attach_data = wp_generate_attachment_metadata($attach_id, $filename);

        wp_update_attachment_metadata($attach_id, $attach_data);

        return ApiResult::success(['attachment_id' => $attach_id]);
    }

    public static function create_post($req)
    {
        $user = $req->get_param('user');
        $insert = wp_insert_post(
            [
                'post_author' => $user->id,
                'post_title' => $req->get_param('post_title'),
                'post_content' => $req->get_param('post_content'),
                'post_category' => $req->get_param('post_category'),
                'tags_input' => $req->get_param('tags_input'),
            ],
            true
        );

        if (is_wp_error($insert)) {
            return ApiResult::error($insert->get_error_message());
        }

        if($req->get_param('attachment_id')) {
            set_post_thumbnail($insert, $req->get_param('attachment_id'));
        }

        $post = get_post($insert, OBJECT, 'display');
        return ApiResult::success($post, 'Post successfully created');
    }

    public static function update_post($req)
    {
        $insert = wp_insert_post(
            [
                'ID' => $req->get_param('id'),
                'post_title' => $req->get_param('post_title'),
                'post_content' => $req->get_param('post_content'),
                'post_category' => $req->get_param('post_category'),
                'tags_input' => $req->get_param('tags_input'),
            ],
            true
        );

        if (is_wp_error($insert)) {
            return ApiResult::error($insert->get_error_message());
        }

        $post = get_post($insert, OBJECT, 'display');
        return ApiResult::success($post, 'Post successfully updated');
    }

    public static function delete_post($req)
    {
        $delete = wp_delete_post($req->get_param('id'), true);
        if (empty($delete)) {
            return ApiResult::error('Failed deleting post');
        }

        return ApiResult::success('', 'Post deleted');
    }
}
