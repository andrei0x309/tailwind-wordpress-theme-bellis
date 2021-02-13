<?php 
$a309wp_unique_id = wp_unique_id( 'search-form-' );

//$a309wp_aria_label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
$a309wp_aria_label = '';
$a309wp_search_button = false;
$a309wp_search_formen_menu = isset($args['a309_menu']) && $args['a309_menu'] == true;
 
?>
<?php if($a309wp_search_formen_menu): ?>
<button id="menu-search-btn" class="icon-search link-search-icon"></button>  
<?php else: ?>
<form role="search" <?php echo $a309wp_aria_label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<!-- <label for="<?php echo esc_attr( $a309wp_unique_id ); ?>"><?php _e( 'Search&hellip;', 'twentytwentyone' ); // phpcs:ignore: WordPress.Security.EscapeOutput.UnsafePrintingFunction -- core trusts translations ?></label> -->
    <div ><i class="icon-search"></i>
        <input placeholder="Search" type="search" id="<?php echo esc_attr( $a309wp_unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" />
         </div>
            <?php if($a309wp_search_button){ ?>
        <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'twentytwentyone' ); ?>" />
         <?php  } ?>
</form>
<?php endif;