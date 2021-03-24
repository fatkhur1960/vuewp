<?php
require_once VUEWP_PATH . '/inc/config.php';
include_once VUEWP_PATH . '/vendor/autoload.php';
include_once VUEWP_PATH . '/inc/autoload.php';

ini_set('memory_limit', '1024M');

class VueWpTemplate
{
    public static function init()
    {
        // Create dao tables
        add_action('after_switch_theme', 'VueWpDao::create_table');

        // Remove all default WP template redirects/lookups
        remove_action('template_redirect', 'redirect_canonical');

        // Redirect all requests to index.php so the Vue app is loaded and 404s aren't thrown
        add_action('init', function () {
            add_rewrite_rule('^/(.+)/?', 'index.php', 'top');
        });

        // Register REST API
        add_action('rest_api_init', 'Controller::init_routes');

        // Register theme setup
        add_action('after_setup_theme', 'VueWpTemplate::theme_setup');
    }

    // Load scripts
    public static function load_vue_scripts()
    {
        wp_enqueue_script(
            'vuewp-js',
            get_stylesheet_directory_uri() . '/dist/scripts/index.js',
            ['jquery'],
            filemtime(get_stylesheet_directory() . '/dist/scripts/index.js'),
            true
        );

        wp_enqueue_style(
            'vuewp-css',
            get_stylesheet_directory_uri() . '/dist/styles.css',
            null,
            filemtime(get_stylesheet_directory() . '/dist/styles.css')
        );
    }

    // Create theme setup
    public static function theme_setup()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');
        // add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        set_post_thumbnail_size(1568, 9999);

        // Create content excerpt
        add_filter(
            'excerpt_length',
            function () {
                return 20;
            },
            999
        );

        // Load vuejs scripts
        add_action(
            'wp_enqueue_scripts',
            'VueWpTemplate::load_vue_scripts',
            100
        );

        add_action('widgets_init', 'VueWpTemplate::register_template_widget');

        // Register Slide Post Type
        SlidePost::init();

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

        register_sidebar([
            'name' => 'Section: Program Unggulan',
            'id' => 'proker',
        ]);

        register_sidebar([
            'name' => 'Section: Contact Form',
            'id' => 'contact_form',
        ]);

        register_sidebar([
            'name' => 'Section: Footer',
            'id' => 'footer',
        ]);
    }

    // Register custom widget
    public static function register_template_widget()
    {
        register_widget('ProgramWidget');
    }
}

VueWpTemplate::init();
