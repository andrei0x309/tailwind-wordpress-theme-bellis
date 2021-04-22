<?php 
$a309wp_unique_id = wp_unique_id( 'search-form-' );

//$a309wp_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
$a309wp_aria_label = '';
$a309wp_search_button = isset($args['bellis_search_btn']) && $args['bellis_search_btn'] == true;;
$a309wp_search_formen_menu = isset($args['bellis_menu']) && $args['bellis_menu'] == true;
 
?>
<?php if($a309wp_search_formen_menu): ?>

<?php if(bellis_is_amp() ): ?>
<amp-script layout="container" src="<?php echo get_stylesheet_directory_uri() ?>/js/AMP/amp_search_modal.js" sandbox="allow-forms">
    <button id="menu-search-btn" class="icon-search link-search-icon" aria-label="search button to open search modal"></button>
</amp-script> 
<?php else: ?>
<button id="menu-search-btn" class="icon-search link-search-icon" aria-label="search button to open search modal"></button>  
<?php endif; ?>

<?php else: ?>
<form role="search" <?php echo $a309wp_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<!-- <label for="<?php echo esc_attr( $a309wp_unique_id ); ?>"><?php _e( 'Search&hellip;', 'twentytwentyone' ); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations ?></label> -->
    <div ><i class="icon-search"></i>
        <input placeholder="Search" type="search" id="<?php echo esc_attr( $a309wp_unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" />
         
            <?php if($a309wp_search_button){ ?>
        <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'twentytwentyone' ); ?>" />
         <?php  } ?>
        </div>
</form>
<?php endif;