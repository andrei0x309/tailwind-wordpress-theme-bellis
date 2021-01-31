<?php
 
require_once(ABSPATH . 'wp-admin/includes/file.php');

// Remove Admin Bar
add_filter('show_admin_bar', '__return_false');
// Remove Jquery
/*add_filter( 'wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX );

function change_default_jquery( ){
    wp_dequeue_script( 'jquery');
    wp_deregister_script( 'jquery');   
}
*/


 
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
/*
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});
*/

function add_adidtional_css_js() {
    

    
    wp_enqueue_style( 'a309CommentsCss', get_template_directory_uri() . '/css/comments.css',false, null,'all');
    
    //Singular JS
    if( is_singular() ){
        wp_enqueue_script( 'a309CommentsJs', get_theme_file_uri('/js/app_comments.js'), [], null, true );
    }
    
    //Index JS
    if( is_home() )
    {
        wp_enqueue_script( 'a309IndexJs', get_theme_file_uri('/js/app_index.js'), [], null, true );
    }
 
}

add_action( 'wp_enqueue_scripts', 'add_adidtional_css_js' );


function wpse71451_enqueue_comment_reply() {
    if ( get_option( 'thread_comments' ) ) { 
        wp_enqueue_script( 'comment-reply' ); 
    }
}
// Hook into comment_form_before
add_action( 'comment_form_before', 'wpse71451_enqueue_comment_reply' );
 

add_action('rest_api_init', 'change_rest_post' );
function change_rest_post(){
   
  register_rest_route( 'a309/v1', '/get-post/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_post_by_id',
  ) );
    
   register_rest_route( 'a309/v1', '/get-comments/post/(?P<post_id>\d+)/page/(?P<page_no>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_comments_post',
  ) );
   
}
 


function get_post_by_id($data){
 global $withcomments;
 $withcomments = true;
 $template = '';
 $postId  = '';
 $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'p' => $data['id'],   // id of the post you want to query
    );
    $my_posts = new WP_Query($args);  

   if($my_posts->have_posts()) : 
       $postId = $data['id'];
       ob_start();
        while ( $my_posts->have_posts() ) : $my_posts->the_post(); 

          get_template_part( 'parts/article', null, [ 'full_content' => true ]);  
          
        endwhile; //end the while loop
        $template = ob_get_clean ();
endif; // end of the loop. 
        wp_send_json([ 'article' => $template, 'post_id' => $postId ]);
}


function get_comments_post($data){

// setup a fake POST to trick wp_list_comments
global $post; 
$post = new stdClass();
$post->ID = $data['post_id'];
setup_postdata( $post );


    $template = wp_list_comments(
				array(
					'avatar_size' => 60,
					'style'       => 'ol',
					'short_ping'  => true,
                                        'max_depth' => 5,
                                        'per_page' => 5,
                                        'page' =>    $data['page_no'],
                                        'echo' => false,
                                       
				), );
		 
 wp_reset_postdata();
 
      wp_send_json([ 'comments' => $template, 'post_id' => $data['post_id'], 'page_no' => $data['page_no'] ]);
}