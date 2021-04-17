<?php 

if ( ! defined( 'ABSPATH' ) ) {
    header( 'HTTP/1.1 301 Moved Permanently' );
    header( 'Location: /' );
    die();
}

get_header(); 
?>

<div id="main" class="main flex w-full mt-6 mb-6 justify-center">
 
    
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
     
        
         <?php 

 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
 
  get_template_part( 'parts/article', null, [ 'full_content' => false ]);  
 
 endwhile;
 }
 
  //echo the_posts_pagination();
 
 ?>
<?php if(a309_is_amp() ): the_posts_pagination();
else: ?>
        <div class="text-center m-2"> 
        <button id="show-more-posts-btn">Load more Posts</button>
        </div>
<?php endif; ?>
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer();    
get_template_part( 'parts/end-markup');