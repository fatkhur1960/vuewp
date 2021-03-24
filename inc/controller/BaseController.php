<?php
class BaseController
{
    // Register our routes.
    public static function init()
    {
        register_rest_route('page/v1', 'detail/(?P<slug>[a-zA-Z0-9-]+)', [
            'methods' => 'GET',
            'callback' => 'BaseController::post_single',
            'args' => ['slug'],
            'permission_callback' => '__return_true',
        ]);
        register_rest_field(['slides'], 'image', [
            'get_callback' => 'BaseController::get_rest_featured_image',
            'update_callback' => null,
            'schema' => null,
        ]);
        register_rest_field(['post'], 'fimg_url', [
            'get_callback' => 'BaseController::get_rest_featured_image',
            'update_callback' => null,
            'schema' => null,
        ]);
        register_rest_field(['post'], 'author_meta', [
            'get_callback' => 'BaseController::get_rest_author',
            'update_callback' => null,
            'schema' => null,
        ]);
        register_rest_field(['post'], 'post_categories', [
            'get_callback' => 'BaseController::get_rest_categories',
            'update_callback' => null,
            'schema' => null,
        ]);

        self::rest_api_filter_add_filters();
    }

    public static function get_rest_featured_image(
        $object,
        $field_name,
        $request
    ) {
        if ($object['featured_media']) {
            $img = wp_get_attachment_image_src(
                $object['featured_media'],
                'app-thumb'
            );
            return $img[0];
        } else {
            return get_template_directory_uri() .
                '/assets/images/default-thumbnail.png';
        }
        return false;
    }

    public static function get_rest_author($object, $field_name, $request)
    {
        if ($object['author']) {
            $author = get_user_by('ID', $object['author']);
            return [
                'name' => $author->display_name,
                'slug' => $author->nickname,
            ];
        }
        return false;
    }

    public static function get_rest_categories($object, $field_name, $request)
    {
        if ($object['categories']) {
            $cats = [];
            foreach ($object['categories'] as $catID) {
                $cat = get_category($catID);
                array_push($cats, [
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                ]);
            }

            return $cats;
        }
        return false;
    }

    public static function rest_api_filter_add_filters()
    {
        foreach (
            get_post_types(['show_in_rest' => true], 'objects')
            as $post_type
        ) {
            add_filter(
                'rest_' . $post_type->name . '_query',
                'BaseController::rest_api_filter_add_filter_param',
                10,
                2
            );
        }
    }

    public static function rest_api_filter_add_filter_param($args, $request)
    {
        // Bail out if no filter parameter is set.
        if (empty($request['filter']) || !is_array($request['filter'])) {
            return $args;
        }

        $filter = $request['filter'];

        if (
            isset($filter['posts_per_page']) &&
            ((int) $filter['posts_per_page'] >= 1 &&
                (int) $filter['posts_per_page'] <= 100)
        ) {
            $args['posts_per_page'] = $filter['posts_per_page'];
        }

        global $wp;
        $vars = apply_filters('rest_query_vars', $wp->public_query_vars);

        // Allow valid meta query vars.
        $vars = array_unique(
            array_merge($vars, [
                'meta_query',
                'meta_key',
                'meta_value',
                'meta_compare',
            ])
        );

        foreach ($vars as $var) {
            if (isset($filter[$var])) {
                $args[$var] = $filter[$var];
            }
        }
        return $args;
    }

    public static function post_single($req)
    {
        $p = get_page_by_path($req->get_param('slug'));

        if (!empty($p)) {
            $meta = get_post_meta($p->ID, 'show_sidebar');
            if (!empty($meta)) {
                $p->show_sidebar = true;
            } else {
                $p->show_sidebar = false;
            }

            return $p;
        }

        return false;
    }
}
