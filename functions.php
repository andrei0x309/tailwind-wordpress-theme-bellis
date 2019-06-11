<?php
 
require_once(ABSPATH . 'wp-admin/includes/file.php');



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



function enquereThemeCss(): Void{
    
    wp_register_style('az309', get_template_directory_uri() . '/bootstrap/css/bootstrap.min.css' );
    
}


 
function theme_support_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'automatic-feed-links' );
}
 
add_action( 'after_setup_theme', 'theme_support_setup' );
