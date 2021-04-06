<?php

require_once(ABSPATH . 'wp-admin/includes/file.php');

$A309_IS_AMP = false;

/**
 * Determine whether this is an AMP response.
 *
 * Note that this must only be called after the parse_query action.
 *
 * @return bool Is AMP endpoint (and AMP plugin is active).
 */
function a309_is_amp() {
    global $A309_IS_AMP;
    return $A309_IS_AMP ;
}


// Exclude having CSS selectors being tree-shaken.
 /*add_filter( 'amp_content_sanitizers',
	function ( $sanitizers ) {
		$sanitizers['AMP_Style_Sanitizer']['dynamic_element_selectors'] = array_merge(
			! empty( $sanitizers['AMP_Style_Sanitizer']['dynamic_element_selectors'] ) ? $sanitizers['AMP_Style_Sanitizer']['dynamic_element_selectors'] : [],
			[
				// Modified from from protected AMP_Style_Sanitizer::$DEFAULT_ARGS.
				'amp-list',
				'amp-live-list',
				'[submit-error]',
				'[submit-success]',
				'amp-script',

				// New.
				'.full-search-modal',
			]
		);
		return $sanitizers;
	}
); */

/*add_filter('A2A_SHARE_SAVE_services',        function ($A2A_SHARE_SAVE_services) {
    foreach(array_keys($A2A_SHARE_SAVE_services) as $key){
                $A2A_SHARE_SAVE_services[$key]['color'] = '434343';
                }
            return($A2A_SHARE_SAVE_services);
        });
  
*/
// They added fix per suggestion https://wordpress.org/support/topic/suggestion-add-suport-for-print-on-amp/#post-14280359
add_filter( 'addtoany_icons_bg_color', function() {
	if ( a309_is_amp() ) {
		return '#434343';
	}
} );


function a309_setup_amp(){
    if(function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()){
        global $A309_IS_AMP;
        $A309_IS_AMP = true;
        add_filter('addtoany_script_disabled', '__return_true');
    }
}

add_action( 'wp', 'a309_setup_amp' );


/* Exclude Pages from search */
function remove_pages_from_search($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}
add_filter('pre_get_posts','remove_pages_from_search');

// PWA FILTERS

add_filter( 'web_app_manifest', function( $manifest ) {
    $manifest['short_name'] = 'FlashSoft Blog';
    return $manifest;
} );


add_filter( 'web_app_manifest', function ( $manifest ) {
	$manifest['icons'] = array(
            	array(
			'src'     => home_url( '/icon/flashsoft-blog-96.png' ),
			'sizes'   => '96x96',
			'type'    => 'image/png',
			'purpose' => 'any',
		),
		array(
			'src'     => home_url( '/icon/flashsoft-blog-192.png' ),
			'sizes'   => '192x192',
			'type'    => 'image/png',
			'purpose' => 'any',
		),
		array(
			'src'     => home_url( '/icon/flashsoft-blog-192.png' ),
			'sizes'   => '192x192',
			'type'    => 'image/png',
			'purpose' => 'maskable',
		),
		array(
			'src'     => home_url( '/icon/flashsoft-blog-512.png' ),
			'sizes'   => '512x512',
			'type'    => 'image/png',
			'purpose' => 'any',
		),
		array(
			'src'     => home_url( '/icon/blog-icon-512.png' ),
			'sizes'   => '512x512',
			'type'    => 'image/png',
			'purpose' => 'maskable',
		),
	);
	return $manifest;
} );






// Remove Generator
remove_action('wp_head', 'wp_generator');

// Remove Admin Bar
add_filter('show_admin_bar', '__return_false');

// Dev AMP
add_filter( 'amp_dev_tools_user_default_enabled', '__return_true' );

// Remove Jquery
add_filter( 'wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX );

function change_default_jquery( ){
    wp_dequeue_script( 'jquery');
    wp_deregister_script( 'jquery');   
}


// yarp CSS
add_action( 'wp_print_styles', 'deregister_yarpp_header_styles' );
function deregister_yarpp_header_styles() {
   wp_dequeue_style('yarppWidgetCss');
   wp_deregister_style('yarppRelatedCss'); 
}

add_action( 'wp_footer', 'deregister_yarpp_footer_styles' );
function deregister_yarpp_footer_styles() {
   wp_dequeue_style('yarppRelatedCss');
}

// addtoany CSS
add_action( 'init', 'disable_css_add_to_any');
function disable_css_add_to_any() {
    remove_action( 'wp_enqueue_scripts', 'A2A_SHARE_SAVE_stylesheet', 20);
}

 

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
 if(!a309_is_amp()) {
    // APP CSS
    if( is_singular() ){
    wp_enqueue_style( 'a309-single', get_template_directory_uri() . '/css/single.css',false, null,'all');
    }
    
    // App JS
    wp_enqueue_script( 'a309-app', get_theme_file_uri('/js/app.js'), [], null, true );
    
    //Singular JS
    if( is_singular() ){
        wp_enqueue_script( 'a309-comments', get_theme_file_uri('/js/app_comments.js'), ['a309-app'], null, true );
    }
    
    //Index JS
    if( is_home() )
    {
        wp_enqueue_script( 'a309-index', get_theme_file_uri('/js/app_index.js'), ['a309-app'], null, true );
    }
 }else{
    // APP CSS
    if( is_singular() ){
    if(!a309_is_amp()) wp_enqueue_style( 'a309-single', get_template_directory_uri() . '/css/single.css',false, null,'all');
    else wp_enqueue_style( 'a309-single-amp', get_template_directory_uri() . '/css/single-amp.css',false, null,'all');
    }
 }
}

add_action( 'wp_enqueue_scripts', 'add_adidtional_css_js' );


function wpse71451_enqueue_comment_reply() {
    if ( get_option( 'thread_comments' ) && !a309_is_amp() ) { 
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
    'permission_callback' => '__return_true',
  ) );
    
    
  register_rest_route( 'a309/v1', '/get-y-morph/plugin/(?P<plugin>.*)', array(
    'methods' => 'GET',
    'callback' => 'a309_get_y_morf',
    'permission_callback' => '__return_true',
  ) );
  
  register_rest_route( 'a309/v1', '/get-posts/offset/(?P<offset>\d+)/per-page/(?P<per_page>\d+)', array(
    'methods' => 'GET',
    'callback' => 'a309_get_posts',
    'permission_callback' => '__return_true',
  ) );
  
  register_rest_route( 'a309/v1', '/get-comments-no/post/(?P<post_id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_top_level_comments_number',
    'permission_callback' => '__return_true',
  ) );
    
   register_rest_route( 'a309/v1', '/get-comments/post/(?P<post_id>\d+)/page/(?P<page_no>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_comments_post',
    'permission_callback' => '__return_true',
  ) );
   
      register_rest_route( 'a309/v1', '/gr-widget', array(
    'methods' => 'GET',
    'callback' => 'a309_get_gr_widget',
    'permission_callback' => '__return_true',
  ) );
   
}
 
function a309_get_gr_widget(){
    echo file_get_contents("https://www.goodreads.com/review/custom_widget/52338687.Andrei's%20bookshelf:%20read?cover_position=left&cover_size=small&num_books=5&order=d&shelf=read&show_author=1&show_cover=1&show_rating=1&show_review=1&show_tags=1&show_title=1&sort=date_added&widget_bg_color=FFFFFF&widget_bg_transparent=true&widget_border_width=none&widget_id=1613506906&widget_text_color=000000&widget_title_size=medium&widget_width=medium");
}


//https://my.yoast.com/api/downloads/file/morphology-en-v4?plugin_version=15.7&site=https%3A%2F%2Fblackellis.eu
function a309_get_y_morf(){
    
     global $wp_filesystem;
            WP_Filesystem();
     
            $jsonPath = (get_template_directory() .'/res/y-morf.json');
            if (! file_exists( $jsonPath )) {
               wp_send_json([ 'error' => true]);
            }
            $json = json_decode($wp_filesystem->get_contents( $jsonPath ), true);
             wp_send_json($json);
 
}


function a309_get_post_template($wpQueryArgs = null ,$full = true, $yoastSeo = false){
    
   $my_posts = new WP_Query($wpQueryArgs);  
   global $post;
   $yoast_head = null;
   $template = '';

   if($my_posts->have_posts()) : 
       while ( $my_posts->have_posts() ) : $my_posts->the_post(); 
          if($yoastSeo){
          ob_start();
          do_action("wpseo_head");
          $yoast_head = ob_get_clean();
          }
          ob_start();
          get_template_part( 'parts/article', null, [ 'full_content' => $full ]);
          $template .= ob_get_clean();
          endwhile; //end the while loop
endif; // end of the loop.
 
if($yoastSeo){
    return ['template' => $template, 'yoast_head' => $yoast_head ];
}else{
    return $template;
}  

    
}


function get_post_by_id($data){
 global $withcomments;
 global $wp_query;
 $wp_query->is_singular = true;
 $withcomments = true;
 
$data['user_id'] = intval($data['user_id']);
$user_id = $data['user_id'] > 0 ? $data['user_id']: 0;
 
global $current_user;
$current_user = new stdClass();
$current_user->ID =  $user_id;

 $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'p' => $data['id'],   // id of the post you want to query
    );
    
  $post_html = a309_get_post_template($args, true, true);

  wp_send_json([ 'article' => $post_html['template'], 'post_id' =>  $data['id'], 'yoast_seo' => $post_html['yoast_head']  ]);
 
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
     
     $template = a309_get_post_template($args, false , false);
     
     wp_send_json([ 'articles' => $template, 'offset' =>  $data['offset'], 'per_page' => $data['per_page']]);
     
}

function get_top_level_comments_number( $data ) {

    global $wpdb;
    $data['post_id'] = esc_sql($data['post_id']);
    $noComments = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_parent = 0 AND comment_post_ID = '".$data['post_id']."'" );
    wp_send_json([ 'commentNumber' => $noComments ]);
}

function get_comments_post($data){

// setup a fake POST to trick wp_list_comments
global $post; 
$post = new stdClass();

$post->ID = $data['post_id'];
setup_postdata( $post );

$comment_args = array(
					'avatar_size' => 60,
                                        'reverse_top_level' => true,
                                        'reverse_children' => true,
					'style'       => 'ol',
					'short_ping'  => true,
                                        'max_depth' => 5,
                                        'per_page' => 5,
                                        'page' =>    $data['page_no'],
                                        'echo' => false,
                                       
				);

$template = wp_list_comments( $comment_args );
		 
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
    
//lh3.googleusercontent.com/pw/ACtC-3fPRRszLuGmIgM3DK1IUTQxyEChtxk6_NuMMD5vj68hV9WEPKnzXpnwXHSv2MLoEhVHeUYhLIh5aC0MqBk8rsF11BSqNA9LRJKzrhjuPp6KnEZs47i4LXcERl35m2m34B5kHvf68yYSSrtqdK0zMKN_=s64-no
$myavatar = esc_url('https://i1.wp.com/lh3.googleusercontent.com/pw/ACtC-3fPRRszLuGmIgM3DK1IUTQxyEChtxk6_NuMMD5vj68hV9WEPKnzXpnwXHSv2MLoEhVHeUYhLIh5aC0MqBk8rsF11BSqNA9LRJKzrhjuPp6KnEZs47i4LXcERl35m2m34B5kHvf68yYSSrtqdK0zMKN_=s64-no?ssl=1');
$avatar_defaults[$myavatar] = "Default Gravatar";
return $avatar_defaults;
}
 
add_filter( 'avatar_defaults', 'new_gravatar' );


/**
 * SMTP email.
 */

    // SMTP Authentication
    function send_smtp_email( $phpmailer ) {
    	$phpmailer->isSMTP();
    	$phpmailer->Host       = SMTP_HOST;
    	$phpmailer->SMTPAuth   = SMTP_AUTH;
    	$phpmailer->Port       = SMTP_PORT;
    	$phpmailer->Username   = SMTP_USER;
    	$phpmailer->Password   = SMTP_PASS;
    	$phpmailer->SMTPSecure = SMTP_SECURE;
    	$phpmailer->From       = SMTP_FROM;
    	$phpmailer->FromName   = SMTP_NAME;
    }
    
add_action( 'phpmailer_init', 'send_smtp_email' );


add_filter( 'cron_schedules', 'three_days_add_cron_interval' );
function three_days_add_cron_interval( $schedules ) { 
    $schedules['three_days'] = array(
        'interval' => 259200,
        'display'  => esc_html__( 'Every 3 Days' ), );
    return $schedules;
}


if ( ! wp_next_scheduled( 'expire_posts' ) ) {
    wp_schedule_event( time(), 'three_days', 'a309_update_good_reads' );
}
 

add_action( 'a309_update_good_reads', 'a309_update_good_reads' );


 function a309_update_good_reads () {
   
     $option = get_option('widget_custom_html');
     if($option){
     $widgetArrId = 2;
     $goodReadsJs = file_get_contents("https://www.goodreads.com/review/custom_widget/52338687.Andrei's%20bookshelf:%20read?cover_position=left&cover_size=small&num_books=5&order=d&shelf=read&show_author=1&show_cover=1&show_rating=1&show_review=1&show_tags=1&show_title=1&sort=date_added&widget_bg_color=FFFFFF&widget_bg_transparent=true&widget_border_width=none&widget_id=1613506906&widget_text_color=000000&widget_title_size=medium&widget_width=medium");

     preg_match ( '/=[^~]+widget_div =/ms' , $goodReadsJs , $mArr);
     $iRep = str_replace('  var widget_div =' , '' , $mArr[0]);
     $iRep = preg_replace ( '|\\\/|m' , '/' , $iRep);
     $iRep = preg_replace ( '|\\\\n|m' , '' , $iRep);
     $iRep = preg_replace ( '/\\\\"/m' , '"' , $iRep);
     $iRep = preg_replace ( '/<center>[^\x07]+center>/m' , '' , $iRep);
     $iRep = preg_replace ( '/border="0"/m' , '' , $iRep);
     $iRep = preg_replace ( "/\\\\'/m" , "'" , $iRep);
     $iRep = preg_replace ( "/<noscript>[^\x07]+noscript>/m" , "" , $iRep);
     $iRep = preg_replace ( '|<br[^\x07]+<|m' , '<' , $iRep);
     preg_match ( '|<div class="gr[^\x07]+$|m' , $iRep , $mArr);
     $option[$widgetArrId]['content'] = substr($mArr[0], 0, -2);
     
     update_option('widget_custom_html', $option);
 
     }
  }
 