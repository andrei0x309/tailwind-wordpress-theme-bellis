<?php get_header(); ?>

<div class="main flex w-full mt-6 mb-6 justify-center">
 
    
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
     
        
         <?php 

 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
 
  get_template_part( 'parts/article', null, [ 'full_content' => false ]);  
 
 endwhile;
 }
 
  //echo the_posts_pagination();
 
 ?>
        <div class="text-center m-2"> 
        <button id="show-more-post-btn">Load more Posts</button>
        </div>
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer() ?>    
    </body>
</html>
