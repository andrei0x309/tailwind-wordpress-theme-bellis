<?php 

$articleFull = (isset($args['full_content']) && $args['full_content']);
        
?>

<article id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" data-slug="<?php echo $post->post_name ;?>" data-title="<?php echo $post->post_title ;?>""  <?php post_class('post-body mb-2'); ?>>
     <header>
         <?php  if($articleFull): ?>
         <h1 class="blog-post-title">
         <?php          
         else: ?>
         <h2 class="blog-post-title">
         <?php endif; ?>
         <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
         <?php the_title(); ?>
         </a>
         <?php  if($articleFull): ?>
            </h1>
         <?php
 
         else: ?>
         </h2>
         <?php endif; ?>
         
        <div class="blog-post-meta flex flex-row">
            <address class="author px-2 pt-3 pb-6"><a rel="author" title="Author's page" href="<?php echo site_url(); ?>/author/andrei0x309/"><i class="icon-user-solid-square"></i> <?php the_author(); ?></a></address> 
            <time class="px-2 pt-3 pb-6 " pubdate datetime="<?php echo get_the_date('Y-m-d'); ?>" title="<?php echo get_the_date(); ?>"><i class="icon-calendar"></i> <?php echo get_the_date(); ?></time>
            <span class="px-2 pt-3 pb-6 " ><i class="icon-folder"></i> <?php the_category( ', ' ); ?></span> 
        </div>     
     </header><!--  !-->
     <div class="article-body">
     <?php
          if ( function_exists('yoast_breadcrumb') && $articleFull ):
     yoast_breadcrumb( '<div id="breadcrumbs" class="mb-2">','</div>' );
     endif;
 
     ?>
     
 <?php if(has_post_thumbnail()) { ?>
            <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" id="featured-thumbnail-<?php the_ID(); ?>" class="post-image post-image-left classic-link p-0">
                <?php echo '<div class="pr-4 pb-6 featured-thumbnail w-full content-center justify-center md:w-2/5 md:float-left">'; the_post_thumbnail('',array('title' => '', 'class' => 'm-auto')); echo '</div>'; ?>
            </a>
<?php } ?>         
         
  
 <?php  if($articleFull){ 
      the_content(); 
       
       /*
       $tags = get_the_tags();
       if($tags){ ?>
     <div class="blog-post-meta flex flex-row">
         <span class="px-2 pt-3 pb-6 " ><i class="icon-tags"></i> <?php echo implode(', ', array_map(function($term) {
    return $term->name;
}, $tags))  ; ?></span> 
     </div>      
       <?php
      }    */
 }else{
   the_excerpt();  
 }
 ?>
 </div>
 </article><!-- /.blog-post -->
 <?php  
 if($articleFull){
 comments_template();
  }