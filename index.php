<?php get_header(); ?>

<main class="main flex w-full mt-6 mb-6 justify-center">
 
    
    
    <div class="content bg-white w-full sm:w-full md:w-3/5 lg:w-3/5 xl:w-3/5 p-10">
     
        
         <?php 
 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
 ?>
 <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
     <header><h2 class="blog-post-title"><?php the_title(); ?></h2>
 <p class="blog-post-meta"><?php the_date(); ?> by <?php the_author(); ?></p></header>
 
     
 <?php the_content(); ?>
 </article><!-- /.blog-post -->
 <?php
 endwhile;
 } 
 ?>

    </div>
    <aside class="sidebar w-1/4 ml-8 bg-gray-100 hidden sm:hidden md:flex lg:flex xl:flex text-4xl content-center items-center justify-center" >
      aside content here
    </aside>
  </main>
  <footer class="footer flex w-full items-center text-center text-4xl content-center justify-center">
    footer content here
  </footer>
       <?php wp_footer(); ?>
    </body>
</html>
