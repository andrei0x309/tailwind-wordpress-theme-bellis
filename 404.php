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
       <div class="lg:flex items-center container mx-auto my-auto">
      <?php  while ( $my_posts->have_posts() ) : $my_posts->the_post(); 
 ?>
   <!-- Card -->
      <div class="lg:m-4 shadow-md hover:shadow-lg hover:bg-gray-100 rounded-lg bg-white my-12 mx-8">
        <!-- Card Image -->
        <img src="https://picsum.photos/id/29/2106/1404" alt=""class="overflow-hidden">
        <!-- Card Content -->
        <div class="p-4">
          <h3 class="font-medium text-gray-600 text-lg my-2 uppercase"><?php the_title(); ?></h3>
          <div class="text-justify"><?php the_excerpt();  ?></div>
          <div class="mt-5">
            <a href="" class="hover:bg-gray-700 rounded-full py-2 px-3 font-semibold hover:text-white bg-gray-400 text-gray-100">Read More</a>
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
<?php get_footer() ?>    
    </body>
</html>
