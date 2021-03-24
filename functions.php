<?php
if (!defined('VUEWP_PATH_FILE')) {
    define('VUEWP_PATH_FILE', __FILE__);
}

if (!defined('VUEWP_PATH')) {
    define('VUEWP_PATH', dirname(VUEWP_PATH_FILE));
}

if (!version_compare(PHP_VERSION, '7.0', '>=')) {
    add_action('admin_notices', 'vuewp_fail_php_version');
} elseif (!version_compare(get_bloginfo('version'), '4.6', '>=')) {
    add_action('admin_notices', 'vuewp_fail_wp_version');
} else {
    require_once VUEWP_PATH . '/inc/vuewp-template.php';
}

function vuewp_fail_php_version()
{
    /* translators: %2$s: PHP version */
    $message = sprintf(
        esc_html__(
            '%1$s requires PHP version %2$s+, plugin is currently NOT ACTIVE.',
            'nextend-facebook-connect'
        ),
        'Nextend Social Login',
        '7.0'
    );
    $html_message = sprintf('<div class="error">%s</div>', wpautop($message));
    echo wp_kses_post($html_message);
}

function vuewp_fail_wp_version()
{
    /* translators: %2$s: WordPress version */
    $message = sprintf(
        esc_html__(
            '%1$s requires WordPress version %2$s+. Because you are using an earlier version, the plugin is currently NOT ACTIVE.',
            'nextend-facebook-connect'
        ),
        'Nextend Social Login',
        '4.6'
    );
    $html_message = sprintf('<div class="error">%s</div>', wpautop($message));
    echo wp_kses_post($html_message);
}
