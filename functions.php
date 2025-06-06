<?php

require_once ABSPATH.'wp-admin/includes/file.php';

/*add_filter('A2A_SHARE_SAVE_services',        function ($A2A_SHARE_SAVE_services) {
    foreach(array_keys($A2A_SHARE_SAVE_services) as $key){
                $A2A_SHARE_SAVE_services[$key]['color'] = '434343';
                }
            return($A2A_SHARE_SAVE_services);
        });

*/
// They added fix per suggestion https://wordpress.org/support/topic/suggestion-add-suport-for-print-on-amp/#post-14280359

function theme_thumbnail_get_alt()
{
    global $post;
    $image_id = get_post_thumbnail_id();
    if ($image_id) {
        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
    }
    $alt = '';
    if ($image_alt) {
        $alt = $image_alt;
    }

    return $alt;
}

function theme_resize_img_src($src, $size = 500)
{
    $img_src = explode('=', $src);
    if (count($img_src) > 1) {
        return $img_src[0].'=w'.$size;
    }

    return $src;
}

/* Exclude Pages from search */
function remove_pages_from_search($query)
{
    if ($query->is_search) {
        $query->set('post_type', 'post');
    }

    return $query;
}
add_filter('pre_get_posts', 'remove_pages_from_search');

// PWA FILTERS

add_filter('web_app_manifest', function ($manifest) {
    $manifest['short_name'] = 'Ellis Blog';

    return $manifest;
});

add_filter('web_app_manifest', function ($manifest) {
    $manifest['icons'] = [
        [
            'src' => home_url('/icon/blackellis-blog-96.png'),
            'sizes' => '96x96',
            'type' => 'image/png',
            'purpose' => 'any',
        ],
        [
            'src' => home_url('/icon/blackellis-blog-192.png'),
            'sizes' => '192x192',
            'type' => 'image/png',
            'purpose' => 'any',
        ],
        [
            'src' => home_url('/icon/blackellis-blog-192.png'),
            'sizes' => '192x192',
            'type' => 'image/png',
            'purpose' => 'maskable',
        ],
        [
            'src' => home_url('/icon/android-chrome-512x512-1.png'),
            'sizes' => '512x512',
            'type' => 'image/png',
            'purpose' => 'any',
        ],
        [
            'src' => home_url('/icon/android-chrome-512x512-1.png'),
            'sizes' => '512x512',
            'type' => 'image/png',
            'purpose' => 'maskable',
        ],
    ];

    return $manifest;
});

// Remove Generator
remove_action('wp_head', 'wp_generator');

// Remove Admin Bar
// add_filter('show_admin_bar', '__return_false');

// Remove Jquery
// add_filter( 'wp_enqueue_scripts', 'change_default_jquery', PHP_INT_MAX );

/*function change_default_jquery()
{
    wp_dequeue_script('jquery');
    wp_deregister_script('jquery');
}*/

// yarp CSS
add_action('wp_print_styles', 'deregister_yarpp_header_styles');
function deregister_yarpp_header_styles()
{
    wp_dequeue_style('yarppWidgetCss');
    wp_deregister_style('yarppRelatedCss');
}

add_action('wp_footer', 'deregister_yarpp_footer_styles');
function deregister_yarpp_footer_styles()
{
    wp_dequeue_style('yarppRelatedCss');
}

// addtoany CSS
add_action('init', 'disable_css_add_to_any');
function disable_css_add_to_any()
{
    remove_action('wp_enqueue_scripts', 'A2A_SHARE_SAVE_stylesheet', 20);
}

/**
 * Disable the emoji's.
 */
function disable_emojis()
{
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Remove from TinyMCE
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
}
add_action('init', 'disable_emojis');

/**
 * Filter out the tinymce emoji plugin.
 */
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, ['wpemoji']);
    } else {
        return [];
    }
}

if (!function_exists('theme_wp_debug')) {
    function theme_wp_debug($debug = '')
    {
        $debugInfo = "--- Theme Asset Debug Info ---\n";
        $debugInfo .= $debug;
        $debugInfo .= "--- Theme Asset Debug Info End ---\n";

        wp_die(
            nl2br(esc_html($debugInfo)), // The content shown to the user
            esc_html('Debug Error'), // The title of the error page
            [
                'response' => 500, // Set HTTP status code
                'exit' => true,    // Ensure script exits
                'exception' => $e, // Pass the original Exception object for WP's error handler to log if configured
            ]
        );
    }
}

if (!function_exists('vite_assets')) {
    function vite_assets($path, $only_path = false): string
    {
        static $manifest;
        global $wp_filesystem;
        WP_Filesystem();
        $assetFolder = 'assets';

        $publicPath = get_template_directory().'/';

        if (!$manifest) {
            if (!file_exists($manifestPath = (get_template_directory().'/'.$assetFolder.'/.vite/manifest.json'))) {
                throw new Exception('The Vite asset manifest does not exist. '.$manifestPath);
            }
            $manifest = json_decode($wp_filesystem->get_contents($manifestPath), true);
        }

        // theme_wp_debug(var_dump($manifest, true));

        if (!array_key_exists($path, $manifest)) {
            throw new Exception("Unable to locate asset file: {$path}. Please check your ".'vite output paths and try again.', $manifest);
        }

        $filePath = $assetFolder.'/'.$manifest[$path]['file'];

        if ($only_path) {
            return $filePath;
        }

        return get_theme_file_uri($filePath);
    }
}

/* theme register menu and sidebar and footer */

add_action('widgets_init', 'theme_slug_widgets_init');
function theme_slug_widgets_init()
{
    register_sidebar([
        'name' => __('Main Sidebar', 'a309'),
        'id' => 'main-sidebar',
        'description' => __('Widgets in this area will be shown on all posts and pages.', 'a309'),
        'before_widget' => '<div id="%1$s" class="widget widget-side %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ]);

    register_sidebar([
        'name' => 'Footer Sidebar',
        'id' => 'footer-sidebar',
        'description' => 'Appears in the footer area',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ]);
}

require_once get_template_directory().'/inc/nav_menu.php';
add_action('init', 'wptheme_theme_reg_menus');
function wptheme_theme_reg_menus()
{
    register_nav_menus([
        'primary-menu' => __('Primary'),
        'secondary-menu' => __('Secondary'),
        'mobile' => __('Mobile'),
    ]);
}

/* theme Setup */

if (!isset($content_width)) {
    $content_width = 1100;
}

function wptheme_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('automatic-feed-links');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script']);
}

add_action('after_setup_theme', 'wptheme_theme_setup');

// Remove Jquery Migrate
/*
add_action('wp_default_scripts', function ($scripts) {
    if (!empty($scripts->registered['jquery'])) {
        $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
    }
});
*/

function add_adidtional_css_js()
{
    // APP CSS
    if (is_singular()) {
        wp_enqueue_style('theme-single', vite_assets('dev-assets/scss/single.scss'), false, null, 'all');
    }

    // App JS
    wp_enqueue_script('theme-app', vite_assets('dev-assets/js/app.js'), [], null, true);

    // Singular JS
    if (is_singular()) {
        wp_enqueue_script('theme-comments', vite_assets('dev-assets/js/app_comments.js'), ['theme-app'], null, true);
    }

    // Index JS
    if (is_home()) {
        wp_enqueue_script('theme-index', vite_assets('dev-assets/js/app_index.js'), ['theme-app'], null, true);
    }
}

add_action('wp_enqueue_scripts', 'add_adidtional_css_js');

function theme_enqueue_comment_reply()
{
    if (get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
// Hook into comment_form_before
add_action('comment_form_before', 'theme_enqueue_comment_reply');

add_action('rest_api_init', 'change_rest_post');
function change_rest_post()
{
    register_rest_route('theme/v1', '/get-post/(?P<id>\d+)/user/(?P<user_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_post_by_id',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('theme/v1', '/get-posts/offset/(?P<offset>\d+)/per-page/(?P<per_page>\d+)', [
        'methods' => 'GET',
        'callback' => 'theme_get_posts',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('theme/v1', '/get-comments-no/post/(?P<post_id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_top_level_comments_number',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('theme/v1', '/get-comments/post/(?P<post_id>\d+)/page/(?P<page_no>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_comments_post',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('theme/v1', '/theme-switch/(?P<color>\w+)', [
        'methods' => 'GET',
        'callback' => 'theme_set_theme_cookie',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('a309/v1', '/get-y-morph/plugin/(?P<plugin>.*)', [
        'methods' => 'GET',
        'callback' => 'theme_get_y_morpf',
        'permission_callback' => '__return_true',
    ]);
}

function theme_get_y_morpf()
{
    global $wp_filesystem;
    WP_Filesystem();

    $jsonPath = (get_template_directory().'/res/y-morf.json');
    if (!file_exists($jsonPath)) {
        wp_send_json(['error' => true]);
    }
    $json = json_decode($wp_filesystem->get_contents($jsonPath), true);
    wp_send_json($json);
}

function theme_get_post_template($wpQueryArgs = null, $full = true, $yoastSeo = false)
{
    $my_posts = new WP_Query($wpQueryArgs);
    global $post;
    $yoast_head = null;
    $template = '';

    if ($my_posts->have_posts()) {
        while ($my_posts->have_posts()) {
            $my_posts->the_post();
            if ($yoastSeo) {
                ob_start();
                do_action('wpseo_head');
                $yoast_head = ob_get_clean();
            }
            ob_start();
            get_template_part('parts/article', null, ['full_content' => $full]);
            $template .= ob_get_clean();
        } // end the while loop
    } // end of the loop.

    if ($yoastSeo) {
        return ['template' => $template, 'yoast_head' => $yoast_head];
    } else {
        return $template;
    }
}

function get_post_by_id($data)
{
    global $withcomments;
    global $wp_query;
    $wp_query->is_singular = true;
    $withcomments = true;

    $data['user_id'] = intval($data['user_id']);
    $user_id = $data['user_id'] > 0 ? $data['user_id'] : 0;

    global $current_user;
    $current_user = new stdClass();
    $current_user->ID = $user_id;

    $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'p' => $data['id'],   // id of the post you want to query
    ];

    $post_html = theme_get_post_template($args, true, true);

    wp_send_json(['article' => $post_html['template'], 'post_id' => $data['id'], 'yoast_seo' => $post_html['yoast_head']]);
}

function theme_get_posts($data)
{
    $template = '';
    $data['per_page'] = intval($data['per_page']);
    $data['per_page'] = $data['per_page'] < 0 ? 0 : $data['per_page'];
    $data['offset'] = intval($data['offset']);
    $data['offset'] = $data['offset'] < 0 ? 0 : $data['offset'];

    $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $data['per_page'],   // id of the post you want to query
        'offset' => $data['offset'],
    ];

    $template = theme_get_post_template($args, false, false);

    wp_send_json(['articles' => $template, 'offset' => $data['offset'], 'per_page' => $data['per_page']]);
}

function get_top_level_comments_number($data)
{
    global $wpdb;
    $data['post_id'] = esc_sql($data['post_id']);
    $noComments = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_parent = 0 AND comment_post_ID = '".$data['post_id']."'");
    wp_send_json(['commentNumber' => $noComments]);
}

function get_comments_post($data)
{
    // setup a fake POST to trick wp_list_comments
    global $post;
    $post = new stdClass();

    $post->ID = $data['post_id'];
    setup_postdata($post);

    $comment_args = [
        'avatar_size' => 60,
        'reverse_top_level' => true,
        'reverse_children' => true,
        'style' => 'ol',
        'short_ping' => true,
        'max_depth' => 5,
        'per_page' => 5,
        'page' => $data['page_no'],
        'echo' => false,
    ];

    $template = wp_list_comments($comment_args);

    wp_reset_postdata();

    wp_send_json(['comments' => $template, 'post_id' => $data['post_id'], 'page_no' => $data['page_no']]);
}

// Ajax Comment

function change_comment_action_url($defaults)
{
    $defaults['action'] = get_template_directory_uri().'/inc/comment-post.php';

    return $defaults;
}

add_filter('comment_form_defaults', 'change_comment_action_url');

/**
 * Change default gravatar.
 */
function new_gravatar($avatar_defaults)
{
    // lh3.googleusercontent.com/pw/ACtC-3fPRRszLuGmIgM3DK1IUTQxyEChtxk6_NuMMD5vj68hV9WEPKnzXpnwXHSv2MLoEhVHeUYhLIh5aC0MqBk8rsF11BSqNA9LRJKzrhjuPp6KnEZs47i4LXcERl35m2m34B5kHvf68yYSSrtqdK0zMKN_=s64-no
    $myavatar = esc_url('https://i1.wp.com/lh3.googleusercontent.com/pw/ACtC-3fPRRszLuGmIgM3DK1IUTQxyEChtxk6_NuMMD5vj68hV9WEPKnzXpnwXHSv2MLoEhVHeUYhLIh5aC0MqBk8rsF11BSqNA9LRJKzrhjuPp6KnEZs47i4LXcERl35m2m34B5kHvf68yYSSrtqdK0zMKN_=s64-no?ssl=1');
    $avatar_defaults[$myavatar] = 'Default Gravatar';

    return $avatar_defaults;
}

add_filter('avatar_defaults', 'new_gravatar');

/**
 * SMTP email.
 */
/*
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
*/

function theme_is_dark()
{
    if (!isset($_COOKIE['theme_color'])) {
        return false;
    }
    if ('dark' === $_COOKIE['theme_color']) {
        return true;
    }

    return false;
}

function theme_set_theme_cookie($data)
{
    if (!in_array($data['color'], ['light', 'dark'])) {
        $data['color'] = 'light';
    }
    if (isset($_COOKIE['theme_color'])) {
        unset($_COOKIE['theme_color']);
    }
    setcookie('theme_color', $data['color'], time() + 31556926, '/', '', true); // 1 year

    wp_send_json(['result' => $_COOKIE['theme_color']]);
}

function theme_login_logo()
{ ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_site_url(); ?>/icon/blackellis-blog-96.png);
		height:96px;
		width:96px;
		background-size: 96px 96px;
		background-repeat: no-repeat;
        	padding-bottom: 10px;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'theme_login_logo');

// put direct in wp-config.php
// function remove_update_notifications($value)
// {
//     if (isset($value) && is_object($value)) {
//         unset($value->response['wp-seo-premium/wp-seo-premium.php']);
//         unset($value->response['wp-seo/wp-seo.php']);
//         unset($value->response['wp-hide-security-enhancer-pro/wp-hide.php']);
//     }

//     return $value;
// }
// add_filter('site_transient_update_plugins', 'remove_update_notifications');
// remove w3tc comment
add_filter('w3tc_can_print_comment', '__return_false', 10, 1);
