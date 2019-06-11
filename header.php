<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <link rel="stylesheet" href="<?php echo mix('style.css'); ?>" />
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
      <?php wp_head(); ?>
  </head>
   <body  <?php body_class( ); ?>>
  <header class="header flex flex-row bg-gray-200 items-center">
      <div class="logo min-h-full h-16 w-56 bg-white px-4 py-2 rounded-tr-full">
 <img src="<?php echo get_theme_file_uri( 'images/na309BL.png' ) ?>" />
      </div>
      <nav class="menu flex-none text-gray-100 text-center px-4 py-2 m-2">
      nav MENU here
    </nav>
  </header>

