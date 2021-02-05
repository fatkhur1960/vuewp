<?php
// Remove all default WP template redirects/lookups
remove_action('template_redirect', 'redirect_canonical');

// Redirect all requests to index.php so the Vue app is loaded and 404s aren't thrown
function remove_redirects()
{
    add_rewrite_rule('^/(.+)/?', 'index.php', 'top');
}
add_action('init', 'remove_redirects');

function custom_excerpt_length($length)
{
    return 20;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// Load scripts
function load_vue_scripts()
{
    wp_enqueue_script(
        'vuejs-wordpress-theme-starter-js',
        get_stylesheet_directory_uri() . '/dist/scripts/index.js',
        ['jquery'],
        filemtime(get_stylesheet_directory() . '/dist/scripts/index.js'),
        true
    );

    wp_enqueue_style(
        'vuejs-wordpress-theme-starter-css',
        get_stylesheet_directory_uri() . '/dist/styles.css',
        null,
        filemtime(get_stylesheet_directory() . '/dist/styles.css')
    );
}
add_action('wp_enqueue_scripts', 'load_vue_scripts', 100);

function theme_setup()
{
    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size(1568, 9999);

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style',
        'navigation-widgets',
    ]);

    /**
     * Add support for core custom logo.
     *
     * @link https://codex.wordpress.org/Theme_Logo
     */
    add_theme_support('custom-logo', [
        'height' => 200,
        'width' => 110,
        'flex-width' => false,
        'flex-height' => false,
    ]);

    register_nav_menus([
        'main-menu' => __('Main Menu'),
    ]);
}
add_action('after_setup_theme', 'theme_setup');

add_action('rest_api_init', 'register_rest_images');
function register_rest_images()
{
    register_rest_field(['post'], 'fimg_url', [
        'get_callback' => 'get_rest_featured_image',
        'update_callback' => null,
        'schema' => null,
    ]);
}
function get_rest_featured_image($object, $field_name, $request)
{
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

add_action('rest_api_init', 'register_rest_authors');
function register_rest_authors()
{
    register_rest_field(['post'], 'author_meta', [
        'get_callback' => 'get_rest_author',
        'update_callback' => null,
        'schema' => null,
    ]);
}
function get_rest_author($object, $field_name, $request)
{
    if ($object['author']) {
        $author = get_user_by('ID', $object['author']);
        return ['name' => $author->display_name, 'slug' => $author->nickname];
    }
    return false;
}

add_action('rest_api_init', 'register_rest_categories');
function register_rest_categories()
{
    register_rest_field(['post'], 'post_categories', [
        'get_callback' => 'get_rest_categories',
        'update_callback' => null,
        'schema' => null,
    ]);
}
function get_rest_categories($object, $field_name, $request)
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

add_action('rest_api_init', 'rest_api_filter_add_filters');
/**
 * Add the necessary filter to each post type
 **/
function rest_api_filter_add_filters()
{
    foreach (
        get_post_types(['show_in_rest' => true], 'objects')
        as $post_type
    ) {
        add_filter(
            'rest_' . $post_type->name . '_query',
            'rest_api_filter_add_filter_param',
            10,
            2
        );
    }
}

/**
 * Add the filter parameter
 *
 * @param  array           $args    The query arguments.
 * @param  WP_REST_Request $request Full details about the request.
 * @return array $args.
 **/
function rest_api_filter_add_filter_param($args, $request)
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

// Our custom post type function
function create_posttype()
{
    register_post_type(
        'slides',
        // CPT Options
        [
            'labels' => [
                'name' => __('Slides'),
                'singular_name' => __('Slide'),
            ],
            'public' => true,
            'has_archive' => true,
            'rewrite' => ['slug' => 'slides'],
            'show_in_rest' => true,
        ]
    );
}
// Hooking up our function to theme setup
add_action('init', 'create_posttype');

/*
 * Creating a function to create our CPT
 */

function custom_post_type()
{
    // Set UI labels for Custom Post Type
    $labels = [
        'name' => _x('Slides', 'Post Type General Name', 'twentytwenty'),
        'singular_name' => _x(
            'Slide',
            'Post Type Singular Name',
            'twentytwenty'
        ),
        'menu_name' => __('Slides', 'twentytwenty'),
        'parent_item_colon' => __('Parent Slide', 'twentytwenty'),
        'all_items' => __('All Slides', 'twentytwenty'),
        'view_item' => __('View Slide', 'twentytwenty'),
        'add_new_item' => __('Add New Slide', 'twentytwenty'),
        'add_new' => __('Add New', 'twentytwenty'),
        'edit_item' => __('Edit Slide', 'twentytwenty'),
        'update_item' => __('Update Slide', 'twentytwenty'),
        'search_items' => __('Search Slide', 'twentytwenty'),
        'not_found' => __('Not Found', 'twentytwenty'),
        'not_found_in_trash' => __('Not found in Trash', 'twentytwenty'),
    ];

    // Set other options for Custom Post Type

    $args = [
        'label' => __('movies', 'twentytwenty'),
        'description' => __('Movie news and reviews', 'twentytwenty'),
        'labels' => $labels,
        // Features this CPT supports in Post Editor
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        // You can associate this CPT with a taxonomy or custom taxonomy.
        'taxonomies' => ['genres'],
        /* A hierarchical CPT is like Pages and can have
         * Parent and child items. A non-hierarchical CPT
         * is like Posts.
         */

        'hierarchical' => false,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_in_admin_bar' => true,
        'menu_position' => 5,
        'can_export' => true,
        'has_archive' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => false,
        'capability_type' => 'post',
        'show_in_rest' => true,
    ];

    // Registering your Custom Post Type
    register_post_type('slides', $args);
}

/* Hook into the 'init' action so that the function
 * Containing our post type registration is not
 * unnecessarily executed.
 */

add_action('init', 'custom_post_type', 0);

add_action('rest_api_init', 'register_rest_slides');
function register_rest_slides()
{
    register_rest_field(['slides'], 'image', [
        'get_callback' => 'get_rest_featured_image',
        'update_callback' => null,
        'schema' => null,
    ]);
}

function validate_post_slug(WP_REST_Request $request)
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
function validate_category_slug(WP_REST_Request $request)
{
    $cat = get_category_by_slug($request->get_param('slug'));

    if (!$cat) {
        return ['valid' => false];
    }

    return ['valid' => true];
}
function validate_page_id(WP_REST_Request $request)
{
    $posts = get_post($request->get_param('id'));

    if (empty($posts)) {
        return ['valid' => false];
    }

    return ['valid' => true];
}
function validate_author($request) {
    $author = get_the_author_meta('display_name', $request->get_param('nickname'));

    if (empty($author)) {
        return ['valid' => false, 'res' => $request->get_param('nickname')];
    }

    return ['valid' => true];
}
add_action('rest_api_init', function () {
    register_rest_route('validator/v1', '/post', [
        'methods' => 'GET',
        'callback' => 'validate_post_slug',
        'args' => ['slug'],
    ]);
    register_rest_route('validator/v1', '/category', [
        'methods' => 'GET',
        'callback' => 'validate_category_slug',
        'args' => ['slug'],
    ]);
    register_rest_route('validator/v1', '/author', [
        'methods' => 'GET',
        'callback' => 'validate_author',
        'args' => ['nickname'],
    ]);
    register_rest_route('validator/v1', '/page', [
        'methods' => 'GET',
        'callback' => 'validate_page_id',
        'args' => [
            'id' => [
                'validate_callback' => function ($param, $request, $key) {
                    return is_numeric($param);
                },
            ],
        ],
    ]);
});
