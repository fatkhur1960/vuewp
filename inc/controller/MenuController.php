<?php

class MenuController
{
    // Register our routes.
    public static function init()
    {
        register_rest_route('menu/v1', '/list', [
            'methods' => 'GET',
            'callback' => 'MenuController::get_menu',
            'permission_callback' => '__return_true',
        ]);
    }

    public static function get_menu()
    {
        $locations = get_nav_menu_locations(); //get all menu locations
        $wp_menu_items = wp_get_nav_menu_items($locations['main-menu']); //get the menu object

        $rest_menu_items = [];
        foreach ($wp_menu_items as $item_object) {
            $rest_menu_items[] = self::format_menu_item($item_object);
        }

        $rest_menu_items = self::nested_menu_items($rest_menu_items, 0);

        return $rest_menu_items;
    }

    private static function format_menu_item(
        $menu_item,
        $children = false,
        $menu = []
    ) {
        $item = (array) $menu_item;

        $menu_item = [
            'id' => abs($item['ID']),
            'order' => (int) $item['menu_order'],
            'parent' => abs($item['menu_item_parent']),
            'title' => $item['title'],
            'url' => $item['url'],
            'attr' => $item['attr_title'],
            'target' => $item['target'],
            'classes' => implode(' ', $item['classes']),
            'xfn' => $item['xfn'],
            'description' => $item['description'],
            'object_id' => abs($item['object_id']),
            'object' => $item['object'],
            'object_slug' => get_post($item['object_id'])->post_name,
            'type' => $item['type'],
            'type_label' => $item['type_label'],
        ];

        if ($children === true && !empty($menu)) {
            $menu_item['children'] = self::get_nav_menu_item_children(
                $item['ID'],
                $menu
            );
        }

        return $menu_item;
    }

    private static function nested_menu_items(&$menu_items, $parent = null)
    {
        $parents = [];
        $children = [];

        // Separate menu_items into parents & children.
        array_map(function ($i) use ($parent, &$children, &$parents) {
            if ($i['id'] != $parent && $i['parent'] == $parent) {
                $parents[] = $i;
            } else {
                $children[] = $i;
            }
        }, $menu_items);

        foreach ($parents as &$parent) {
            if (self::has_children($children, $parent['id'])) {
                $parent['children'] = self::nested_menu_items(
                    $children,
                    $parent['id']
                );
            }
        }

        return $parents;
    }

    private static function get_nav_menu_item_children(
        $parent_id,
        $nav_menu_items,
        $depth = true
    ) {
        $nav_menu_item_list = [];

        foreach ((array) $nav_menu_items as $nav_menu_item):
            if ($nav_menu_item->menu_item_parent == $parent_id):
                $nav_menu_item_list[] = self::format_menu_item(
                    $nav_menu_item,
                    true,
                    $nav_menu_items
                );

                if ($depth) {
                    if (
                        $children = self::get_nav_menu_item_children(
                            $nav_menu_item->ID,
                            $nav_menu_items
                        )
                    ) {
                        $nav_menu_item_list = array_merge(
                            $nav_menu_item_list,
                            $children
                        );
                    }
                }
            endif;
        endforeach;

        return $nav_menu_item_list;
    }

    private static function has_children($items, $id)
    {
        return array_filter($items, function ($i) use ($id) {
            return $i['parent'] == $id;
        });
    }
}
