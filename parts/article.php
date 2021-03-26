<?php 

$articleFull = (isset($args['full_content']) && $args['full_content']);
        
?>
<article itemscope itemtype="http://schema.org/BlogPosting" class="<?php echo $articleFull? '':'content-va-on ' ?>post-body mb-2 <?php echo implode(' ',get_post_class()) ?>" id="post-<?php the_ID(); ?>" data-id="<?php the_ID(); ?>" data-slug="<?php echo $post->post_name ;?>" data-title="<?php echo $post->post_title ;?>" <?php post_class(''); ?>>
     <header>
         <?php  if($articleFull): ?>
         <h1 itemprop="headline" class="blog-post-title">
         <?php          
         else: ?>
         <h2 itemprop="headline" class="blog-post-title blog-post-title-link">
         <a href="<?php echo esc_url( get_the_permalink() ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>">
         <?php endif; ?>
        
         <?php the_title(); ?>
        
         <?php  if($articleFull): ?>
            </h1>
         <?php
         else: ?>
          </a>
         </h2>
         <?php endif; ?>
         
        <div class="blog-post-meta flex flex-row">
            <address class="author px-2 pt-3 pb-6"><a rel="author" title="Author's page" href="<?php echo site_url(); ?>/author/andrei0x309/"><i class="icon-user-solid-square"></i> <?php the_author(); ?></a></address> 
            <time class="px-2 pt-3 pb-6" itemprop="published" datetime="<?php echo get_the_date('Y-m-d'); ?>" title="<?php echo get_the_date(); ?>"><i class="icon-calendar"></i> <?php echo get_the_date(); ?></time>
            <span class="px-2 pt-3 pb-6" ><i class="icon-folder"></i> <?php the_category( ', ' ); ?></span> 
        </div>     
     </header><!--  !-->
     <div itemprop="articleBody" class="article-body">
     <?php
          if ( function_exists('yoast_breadcrumb') && $articleFull ):
     yoast_breadcrumb( '<div id="breadcrumbs" class="mb-2">','</div>' );
     endif;
 
     ?>
     
 <?php if(has_post_thumbnail()) { ?>
           <?php if (!$articleFull) { ?> <a href="<?php echo get_the_permalink(); ?>" 
            title="<?php echo esc_attr( get_the_title() ); ?>" id="featured-thumbnail-<?php the_ID(); ?>" 
           class="post-image post-image-left p-0 <?php echo $articleFull ? '': 'post-image-link'; ?>"><?php } ?>
                <?php echo '<div class="pr-4 pl-4 pb-6 featured-thumbnail w-full content-center justify-center md:w-2/5 md:float-left text-center">'; the_post_thumbnail([500,280],array('title' => '', 'class' => 'm-auto')); echo '</div>'; ?>
            <?php if (!$articleFull) { ?> </a> <?php } ?>
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
 
      
      
 if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { 


     if(a309_is_amp()): ?>
 <amp-script layout="container"  src="<?php echo get_stylesheet_directory_uri() ?>/js/AMP/amp_addtoany.js">
         <?php   
    $a2a_output = ADDTOANY_SHARE_SAVE_KIT( array( 
        'buttons' => array( 
            'twitter',
            'reddit',
            'pinterest',
            'whatsapp',
            'facebook',
            'email',
            'print' ),
    'output_later' => true) );
$a2a_output = preg_replace('/<a class="a2a_button_print".*?>/mi', '<a class="a2a_button_print" on="tap:AMP.print()"  href="#" title="Print" rel="nofollow noopener">', $a2a_output);
 echo str_replace('style="background-color:#0166ff"', 'style="background-color:#434343"', $a2a_output);
    
     else:
          ADDTOANY_SHARE_SAVE_KIT( array( 
        'buttons' => array( 
            'twitter',
            'reddit',
            'pinterest',
            'whatsapp',
            'facebook',
            'email',
            'print' ),
    ) );
     endif;
      if(a309_is_amp()): ?>
       </amp-script>  
      <?php  endif;
}    
      
 }else{
   the_excerpt();  
 }
 ?>
 </div>
 </article><!-- /.blog-post -->
 <?php
 

 
 if($articleFull){
 echo do_shortcode('[yarpp]');
 
 
 comments_template();
  }