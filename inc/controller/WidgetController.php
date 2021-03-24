<?php
class WidgetController
{
    public static function init()
    {
        register_rest_route('widget/v1', '/list', [
            'methods' => 'GET',
            'callback' => 'WidgetController::get_widgets',
            'permission_callback' => '__return_true',
        ]);
        register_rest_route('widget/v1', '/get', [
            'methods' => 'GET',
            'callback' => 'WidgetController::get_widget',
            'args' => ['id'],
            'permission_callback' => '__return_true',
        ]);
    }

    public static function get_widget($request)
    {
        global $wp_registered_sidebars;

        foreach ($wp_registered_sidebars as $sidebar) {
            if ($sidebar['id'] == $request->get_param('id')) {
                $sidebar_name = $sidebar['name'];
                return self::rh_get_widget_data_for($sidebar_name);
            }
        }

        return false;
    }

    public static function get_widgets()
    {
        global $wp_registered_sidebars;

        $output = [];
        foreach ($wp_registered_sidebars as $sidebar) {
            if (empty($sidebar['name'])) {
                continue;
            }
            $sidebar_name = $sidebar['name'];
            $output[$sidebar['id']] = self::rh_get_widget_data_for(
                $sidebar_name
            );
        }
        return $output;
    }

    private static function rh_get_widget_data_for($sidebar_name)
    {
        global $wp_registered_sidebars, $wp_registered_widgets;

        // Holds the final data to return
        $output = [];

        // Loop over all of the registered sidebars looking for the one with the same name as $sidebar_name
        $sidebar_id = false;
        foreach ($wp_registered_sidebars as $sidebar) {
            if ($sidebar['name'] == $sidebar_name) {
                // We now have the Sidebar ID, we can stop our loop and continue.
                $sidebar_id = $sidebar['id'];
                break;
            }
        }

        if (!$sidebar_id) {
            // There is no sidebar registered with the name provided.
            return $output;
        }

        // A nested array in the format $sidebar_id => array( 'widget_id-1', 'widget_id-2' ... );
        $sidebars_widgets = wp_get_sidebars_widgets();
        $widget_ids = $sidebars_widgets[$sidebar_id];

        if (!$widget_ids) {
            // Without proper widget_ids we can't continue.
            return [];
        }

        // Loop over each widget_id so we can fetch the data out of the wp_options table.
        foreach ($widget_ids as $id) {
            // The name of the option in the database is the name of the widget class.
            $option_name =
                $wp_registered_widgets[$id]['callback'][0]->option_name;

            // Widget data is stored as an associative array. To get the right data we need to get the right key which is stored in $wp_registered_widgets
            $key = $wp_registered_widgets[$id]['params'][0]['number'];

            $widget_data = get_option($option_name);

            // Add the widget data on to the end of the output array.
            $output[] = (object) $widget_data[$key];
        }

        return $output;
    }
}
