<?php
class SlidePost
{
    public static function init()
    {
        // Hooking up our function to theme setup
        add_action('init', 'SlidePost::slide_post_type', 0);

        // Add slide post capability to administrator
        add_action('admin_init', 'SlidePost::add_slide_caps', 999);
    }

    /*
    * Creating a function to create our CPT
    */
    public static function slide_post_type()
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
            'label' => __('slides', 'twentytwenty'),
            'description' => __('Homescreen slider', 'twentytwenty'),
            'labels' => $labels,
            'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
            'taxonomies' => ['sliders'],
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'menu_position' => 5,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => ['slide', 'slides'],
            'show_in_rest' => true,
            'map_meta_cap' => true,
        ];

        // Registering your Custom Post Type
        register_post_type('slides', $args);
    }

    public static function add_slide_caps()
    {
        $role = get_role('administrator');
        $role->add_cap('edit_slide');
        $role->add_cap('edit_slides');
        $role->add_cap('edit_others_slides');
        $role->add_cap('publish_slides');
        $role->add_cap('read_slide');
        $role->add_cap('read_private_slides');
        $role->add_cap('delete_slide');
        $role->add_cap('delete_slides');
        $role->add_cap('delete_others_slides');
        $role->add_cap('edit_published_slides'); //added
        $role->add_cap('delete_published_slides'); //added
    }
}
