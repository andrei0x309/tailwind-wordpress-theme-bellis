<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <link rel="stylesheet" href="<?php echo mix('style.css'); ?>" />
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <title><?php wp_title(); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
    <script>
     var A309TH = {}; window.A309TH.theme_URI = '<?php echo get_stylesheet_directory_uri() ?>'
     window.A309TH.current_user_id = '<?php echo wp_get_current_user()->ID ?>';
    </script>
  </head>
   <body  <?php body_class( ); ?>>
  <header class="header flex flex-row bg-gray-200 items-center">
      <div class="logo min-h-full h-16 w-40 bg-white py-2 rounded-tr-full">
 <img height="48" width="96" class="h-full ml-4" src="<?php echo get_theme_file_uri( 'images/blogLogo.svg' ) ?>"  alt="flashsoft.eu Blog Logo" >
      </div>
   
      <nav class="header-nav  min-h-full md:flex flex-row text-gray-100 text-center px-4">
            <input class="menu-btn" type="checkbox" id="menu-btn" />
           <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
 
        <?php if ( has_nav_menu( 'secondary-menu' ) ) { ?>
                <?php wp_nav_menu( [ 'theme_location' => 'secondary-menu', 'walker' => new Walker_Nav_Menu_Custom ] ); ?>
	<?php }?>
      </nav>

      
  </header>

