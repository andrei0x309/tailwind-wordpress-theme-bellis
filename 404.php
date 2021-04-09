<?php

$style = '<style>'.
file_get_contents( __DIR__ . '/css/404.css')
.'</style>';
         
get_header(null,['head_aditional_code' => $style]); ?>

<div class="main flex w-full mt-6 mb-6 justify-center">
 
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
     
                    <header class="page-header">
                <p class="not-found-404">404</p>
                <h1 class="page-title not-found-subtitle"><?php _e( 'Resource Not Found', 'a309wp' ); ?></h1>
            </header>
            
                    <div class="page-wrapper">
                <div class="page-content">
                 <a class="not-found-back-button" href="<?php echo site_url(); ?>" title="back to home button"><i class="icon-triangle-down not-found-back-icon"></i><?php _e( 'back to the front page', 'a309wp' ); ?></a>
 
                  <h3 class="mt-6 page-title not-found-subtitle"><?php _e( 'Check the latest articles:', 'a309wp' ); ?></h3>
                     
                     <?php
 
      $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 3,  
        'offset' => 0,
    );
     
     $my_posts = new WP_Query($args);
     
   global $post;
   $yoast_head = null;
   if($my_posts->have_posts()) : ?>
        <div class="block lg:flex justify-between md:-mx-2">
      <?php  while ( $my_posts->have_posts() ) : $my_posts->the_post(); 
 ?>
   <!-- Card -->
        <div class="break-all lg:w-full xl:w-1/3 md:mx-2 mb-4 md:mb-0">
        <div class="bg-gray-50 rounded-lg overflow-hidden shadow-lg relative">
        <!-- Card Image -->
        <?php 
        $alt = get_post_meta( $id, '_nelioefi_alt', true );
	if ( isset( $attr['alt'] ) )
		$alt = $attr['alt'];
	if ( !$alt )
		$alt = '';
        ?>
        <div style="padding-top: 56.25%;" class="w-full relative">
            <img style="top:0" class="w-full object-cover object-center absolute" width="200" height="200" loading="lazy" src="<?php echo get_the_post_thumbnail_url($post->ID) ?>" alt="<?php echo $alt;?>">
        </div>
        <!-- Card Content -->
        <div class="p-4 h-auto">
        <a href="<?php echo get_the_permalink(); ?>" class="block text-blue-500 hover:text-blue-600 font-semibold mb-2 text-lg lg:text-base xl:text-lg uppercase">
              <h2><?php the_title(); ?></h2>
        </a>
 
          <div class="text-gray-600 text-sm leading-relaxed block lg:text-xs xl:text-sm"><?php
          the_advanced_excerpt('length=276&length_type=charcaters&no_custom=1&ellipsis=%26hellip;&exclude_tags=img,p,strong&add_link=0&finish=exact');
          ?></div>
          <div class="mt-5 text-center">
            <a href="<?php echo get_the_permalink(); ?>" class="text-sm hover:bg-gray-800 rounded-full py-2 px-3 font-semibold hover:text-white bg-gray-600 text-gray-100">View Full Article</a>
          </div>
        </div>
        </div>
        </div>
 <?php        
          
 
          endwhile; //end the while loop
?>   </div> <?php 
endif; // end of the loop. 
     
 
 ?>
 
                </div><!-- .page-content -->
            </div><!-- .page-wrapper -->
 
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer();
get_template_part( 'parts/end-markup');