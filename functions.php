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

/**
 * Disable the emoji's
 */
function disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );	
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	
	// Remove from TinyMCE
	add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
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
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
  
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
    
    
    wp_enqueue_script( 'a309appJs', get_theme_file_uri('/js/app.js'), [], null, true );
    
    //Singular JS
    if( is_singular() ){
        wp_enqueue_script( 'a309CommentsJs', get_theme_file_uri('/js/app_comments.js'), ['a309appJs'], null, true );
    }
    
    //Index JS
    if( is_home() )
    {
        wp_enqueue_script( 'a309IndexJs', get_theme_file_uri('/js/app_index.js'), ['a309appJs'], null, true );
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
   
  register_rest_route( 'a309/v1', '/get-post/(?P<id>\d+)/user/(?P<user_id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_post_by_id',
  ) );
  
  register_rest_route( 'a309/v1', '/get-posts/offset/(?P<offset>\d+)/per-page/(?P<per_page>\d+)', array(
    'methods' => 'GET',
    'callback' => 'a309_get_posts',
  ) );
  
  
    
   register_rest_route( 'a309/v1', '/get-comments/post/(?P<post_id>\d+)/page/(?P<page_no>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_comments_post',
  ) );
   
}
 

function a309_get_post_template($wpQueryArgs = null ,$full = true){
    
        $my_posts = new WP_Query($wpQueryArgs);  

   if($my_posts->have_posts()) : 
       ob_start();
        while ( $my_posts->have_posts() ) : $my_posts->the_post(); 

          get_template_part( 'parts/article', null, [ 'full_content' => $full ]);  
          
        endwhile; //end the while loop
        $template = ob_get_clean ();
endif; // end of the loop. 
     
return $template;
    
}


function get_post_by_id($data){
 global $withcomments;
 $withcomments = true;

$data['user_id'] = intval($data['user_id']);
$user_id = $data['user_id'] > 0 ? $data['user_id']: 0;
 
global $current_user;
$current_user = new stdClass();
$current_user->ID =  $user_id;

 $template = '';
 $postId  = '';
 $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'p' => $data['id'],   // id of the post you want to query
    );
    
 $template = a309_get_post_template($args, true);
 
    wp_send_json([ 'article' => $template, 'post_id' =>  $data['id'] ]);
 
}


function a309_get_posts($data){
     $template = '';
     $data['per_page'] = intval($data['per_page']);
     $data['per_page'] = $data['per_page']  < 0 ? 0 : $data['per_page'];
     $data['offset'] = intval($data['offset']);
     $data['offset'] = $data['offset']  < 0 ? 0 : $data['offset'];
     
     
     $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $data['per_page'],   // id of the post you want to query
        'offset' => $data['offset'],
    );
     
     $my_posts = new WP_Query($args);
     
     $template = a309_get_post_template($args, false);
     
     wp_send_json([ 'articles' => $template, 'offset' =>  $data['offset'], 'per_page' => $data['per_page']]);
     
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


// Ajax Comment

function change_comment_action_url( $defaults ) {
	$defaults['action'] = get_template_directory_uri().'/inc/comment-post.php';
	return $defaults;
}

add_filter( 'comment_form_defaults', 'change_comment_action_url');


/**
 * Change default gravatar.
 */


function new_gravatar ($avatar_defaults) {
$myavatar = esc_url('https://lh3.googleusercontent.com/EzkHGdrERM11DUN8nQ8q5BL5Exv_sMwIXxFRJXn0w86swBLOnz3m7O30HVDKAThS4WZCSu4a7_pPARhMANMan454geUcJI9h7kTFwcxuXTQNDDzV95rq6eY63gmmBDIIu5BE6yfrnqSF7e_ku-D4UJ6qO8xoPT7FbMilUn6nz7F3iOWFbA12sOiQqaji83B_mURH8P6_ji1_DA3CkeheDaHCigqCSt2HFUnrTLvG4HKsMSKUrqyV8PotI_hmHALQPOd_KRORdf780wy5Pg6n6wk4UkJ2Ab0aNKAOoZdIFfN1iRRD2nSyj7NCrKSNqiadQbMEtvjFRic4TbeXSX3zHeokHHNAEypxgBo8Xx7lmfuXLs_uewVO0bWsVWU-x_UZNuVcR8QK0TT5fUANp-DlL0TxlhyhpMZEZuBQr1JlPwrXwf9gnT4TX_jIV_mh6l0pMJV9TVl0Fnx5c0_V5QaZF0hYgl9CM4AhGHo9BcRcRJaqb30OU9B8AYs8SCBODb6_CT8DaILT-E9WxYv5PIhk1NpNjatdkyt8yNSZ0kMVof2YxZzQ1_5bZgJoqjI7WjI3OtL2eMyLos0_mwtkLsjItQ6VWQzrvfsxBmWlpgRWRpxKdS1LYt9u04fN0hipxdz-XaA4k4VluR6gezE-NViLYYroVes14YfENjnIwDbQIY36IuCJRrPX0iVlubSg=s64-no?authuser=0');
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}
 
add_filter( 'avatar_defaults', 'new_gravatar' );