<?php
spl_autoload_register(function ($class_name) {
    $dirs = [
        'widget',
        'admin',
        'controller',
        'middleware',
        'utils',
        'provider',
    ];
    foreach ($dirs as $dir) {
        $CLASSES_DIR = VUEWP_PATH . "/inc/$dir/";
        $file = $CLASSES_DIR . $class_name . '.php';
        $file = str_replace('\\', '/', $file);
        if (file_exists($file)) {
            include_once $file;
        }
    }
});