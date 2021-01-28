<?php
require_once(ABSPATH . 'wp-admin/includes/file.php');

// Remove Admin Bar
add_filter('show_admin_bar', '__return_false');
// Remove Jquery
add_filter( 'wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX );

function change_default_jquery( ){
    wp_dequeue_script( 'jquery');
    wp_deregister_script( 'jquery');   
}


if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = ''): String
    {
        static $manifest;
        global $wp_filesystem;
        WP_Filesystem();
        
        $publicPath = get_template_directory()."/";
        
        if (! $manifest) {            
            if (! file_exists($manifestPath = (get_template_directory() .'/mix-manifest.json') )) {
                throw new Exception('The Mix manifest does not exist. '. $manifestPath);
            }
            $manifest = json_decode($wp_filesystem->get_contents($manifestPath), true);
        }
        
        $path = "/{$path}";

        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        }
        return get_theme_file_uri( $manifest[$path] );

    }

}


 
/* theme register menu and sidebar and footer */

add_action( 'widgets_init', 'theme_slug_widgets_init' );
function theme_slug_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Main Sidebar', 'a309' ),
        'id' => 'main-sidebar',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'a309' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget'  => '</div>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
'name' => 'Footer Sidebar',
'id' => 'footer-sidebar',
'description' => 'Appears in the footer area',
'before_widget' => '<aside id="%1$s" class="widget %2$s">',
'after_widget' => '</aside>',
'before_title' => '<h3 class="widget-title">',
'after_title' => '</h3>',
) );
    
    
}


require_once get_template_directory() .'/inc/nav_menu.php';
add_action( 'init', 'wpa309_theme_reg_menus' );
function wpa309_theme_reg_menus(){
    
    register_nav_menus( array(
      'primary-menu'    => __( 'Primary' ),
      'secondary-menu'  => __( 'Secondary' ),
      'mobile'          => __( 'Mobile' )
    ) );

    
}



/* theme Setup */

if ( ! isset( $content_width ) ){
    $content_width = 900;
}


function wpa309_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( "post-thumbnails" );
    
}
 
add_action( 'after_setup_theme', 'wpa309_theme_setup' );

// Remove Jquery Migrate 
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});


function index_js_enqueue() {
    if( is_front_page() )
    {
        wp_enqueue_script( 'indexJs', get_theme_file_uri('/js/app_index.js'), [], null, true );
    }
}
add_action( 'wp_enqueue_scripts', 'index_js_enqueue' );






add_action('rest_api_init', 'change_rest_post' );
function change_rest_post(){
    /*
    register_rest_field( array('post'),
        'fimg_url',
        array(
            'get_callback'    => 'get_rest_featured_image',
            'update_callback' => null,
            'schema'          => null,
        )
    );
      */
   
    
   register_rest_field( array('post'),
        'fimg_html',
        array(
            'get_callback'    => 'get_rest_featured_image_html',
            'update_callback' => null,
            'schema'          => null,
        )
    );
   
       register_rest_field( array('post'),
        'post_categories',
        array(
            'get_callback'    => 'get_post_classes',
            'update_callback' => null,
            'schema'          => null,
        )
    );
   
    register_rest_field( array('post'),
        'post_categories',
        array(
            'get_callback'    => 'get_post_classes',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    
    register_rest_field( array('post'),
        'post_author',
        array(
            'get_callback'    => 'get_post_classes',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    
    
}

function get_rest_featured_image( $object ) {
    if( $object['featured_media'] ){
        return nelioefi_get_thumbnail_src( $object['id'] );
    }
    return false;
}

function get_rest_featured_image_html( $object ) {
    if( $object['featured_media'] ){
        return nelioefi_get_html_thumbnail( $object['id'], null, ['class' => 'm-auto'] );
    }
    return false;
}


function get_post_classes($object){
    return get_post_class('post-body mb-4', $object['id']);
}

function get_post_categories($object){
    return get_post_class('post-body mb-4', $object['id']);
}
