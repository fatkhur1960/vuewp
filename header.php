<?php status_header(200); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
    <?php wp_head(); ?>
  </head>

  <body data-spy="scroll" data-target=".fixed-top" <?php body_class(); ?>>
    <?php wp_body_open(); ?>
