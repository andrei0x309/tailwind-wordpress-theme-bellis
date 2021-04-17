<?php

$style = '
<meta name="test" content="tttt eeee ssss tttt">    
<noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>';
         
get_header(null,['head_aditional_code' => $style]); ?>
 
<div id="main" class="main flex w-full mt-6 mb-6 justify-center">
 
    
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8 ">
     
        
         <?php 

 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
  
  get_template_part( 'parts/article', null, [ 'full_content' => true ]);  
 
 endwhile;
 } 
 

 ?>



 
    </main>
<?php get_sidebar(); ?> 
</div>

<?php get_footer();
get_template_part( 'parts/end-markup');