<?php status_header(200); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet"> 
    <link href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/css/fontawesome-all.css" rel="stylesheet">
    <link href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/css/swiper.css" rel="stylesheet">
    <link href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/css/magnific-popup.css" rel="stylesheet">
    <link href="<?php echo get_bloginfo(
        'template_directory'
    ); ?>/assets/css/styles.css" rel="stylesheet">
    <title>Loading...</title>
    <?php wp_head(); ?>
  </head>

  <body data-spy="scroll" data-target=".fixed-top" <?php body_class(); ?>>
    <?php wp_body_open(); ?>
