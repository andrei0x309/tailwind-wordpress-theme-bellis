<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php if(bellis_the_is_dark()): ?>class="dark" <?php endif; ?> >
  <head>
      
<?php if(bellis_is_amp() ): ?>
<link rel="stylesheet" href="<?php echo mix('amp-style.css'); ?>" />
<?php else: ?>
<link rel="stylesheet" href="<?php echo mix('style.css'); ?>" />
<?php endif; ?>
    
   <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="apple-touch-icon" sizes="57x57" href="/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/icon/flashsoft-blog-192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/icon/flashsoft-blog-96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/icon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?php wp_head(); ?>
    
<?php if( ! bellis_is_amp() ): ?>
    <script>
     var A309TH = {}; window.A309TH.theme_URI = '<?php echo get_stylesheet_directory_uri() ?>';
     window.A309TH.current_user_id = '<?php echo wp_get_current_user()->ID ?>';
    </script>
    <link rel="prefetch" href="<?php echo get_stylesheet_directory_uri() ?>/fonts/icomoon.woff" as="font" type="font/woff" crossorigin="">
<?php endif; ?>
  <?php if(isset($args['head_aditional_code']))echo $args['head_aditional_code'];?>
  </head>

 <body class="<?php echo implode(' ',get_body_class()) ?> break-word" >
<?php if(bellis_is_amp() ): 
 $stateData['theme_URI'] = get_stylesheet_directory_uri(); 
?>        
<amp-state id="A309TH"><script type="application/json">
   <?php echo wp_json_encode($stateData); ?>
</script>
</amp-state> 
  <!-- <amp-script style="position: absolute;z-index: -1;height:100vh;" class="hidscr" layout="fixed" height="100" width="1" src="<?php echo get_stylesheet_directory_uri() ?>/js/AMP/amp_base.js"></amp-script>
   <amp-script width="1" height="1" src="<?php echo get_stylesheet_directory_uri() ?>/js/AMP/amp_base.js"></amp-script> -->
<?php endif; ?>    
    
 
 <header class="header bg-gray-200 pb-2">
 <div class="justify-center flex flex-row items-center h-full w-full m-auto md:w-80p">
 <div class="logo min-h-full w-64 mt-6 py-2">
 <a href="<?php echo site_url(); ?>">
 <img height="120" width="400" class="h-full ml-4" src="<?php echo get_theme_file_uri( 'images/bellisLogo.svg' ) ?>"  alt="blackellis.eu Blog Logo" >
 </a>
      </div>
   
      <nav class="mt-24 header-nav min-h-full md:flex flex-row text-gray-100 text-center px-4">
            <input class="menu-btn" type="checkbox" id="menu-btn" />
           <label class="menu-icon" for="menu-btn"><span class="navicon"></span></label>
 
        <?php if ( has_nav_menu( 'secondary-menu' ) ) { ?>
                <?php wp_nav_menu( [ 'theme_location' => 'secondary-menu', 'walker' => new Walker_Nav_Menu_Custom ] ); ?>
	<?php }?>
      </nav>
      <?php get_search_form(['bellis_menu' => true]); ?>

<?php if (!bellis_is_amp()): ?>     
<button id="theme-switch" class="mt-24 ml-5 w-16 h-6 rounded-full bg-white flex items-center transition duration-300 focus:outline-none shadow">
    <div id="svg-theme-switch" class="border border-white w-9 h-9 relative rounded-full transition duration-500 transform bg-gray-700 -translate-x-2 p-1 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
    </div>
</button>
 <?php endif; ?>
</div>
  </header>

