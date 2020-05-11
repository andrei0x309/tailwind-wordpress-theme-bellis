<?php get_header(); ?>

<div class="main flex w-full mt-6 mb-6 justify-center">
 
    
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
     
        
         <?php 

 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
 ?>
 <article id="post-<?php the_ID(); ?>" <?php post_class('post-body mb-4'); ?>>
     <header><h2 class="blog-post-title">
         <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
         <?php the_title(); ?>
         </a>
         </h2>
        <div class="blog-post-meta flex flex-row">
            <address class="author px-2 pt-3 pb-6"><a rel="author" href="/author/john-doe"><i class="icon-twitter"></i> <?php the_author(); ?></a></address> 
            <time class="px-2 pt-3 pb-6 " pubdate datetime="<?php echo get_the_date('Y-m-d'); ?>" title="<?php echo get_the_date(); ?>"><i class="icon-calendar"></i> <?php echo get_the_date(); ?></time>
            <span class="px-2 pt-3 pb-6 " ><i class="icon-tags"></i> <?php the_category( ', ' ); ?></span> 
        </div>     
     </header><!--  !-->
 
 <?php if(has_post_thumbnail()) { ?>
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" id="featured-thumbnail" class="post-image post-image-left">
                <?php echo '<div class="pr-4 pb-6 featured-thumbnail w-full content-center justify-center md:w-2/5 md:float-left">'; the_post_thumbnail('',array('title' => '', 'class' => 'm-auto')); echo '</div>'; ?>
            </a>
<?php } ?>         
         
  
 
     
 <?php the_excerpt(); ?>
 </article><!-- /.blog-post -->
 <?php
 endwhile;
 }
 
  echo the_posts_pagination();
 
 ?>

    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer() ?>    
    </body>
</html>
