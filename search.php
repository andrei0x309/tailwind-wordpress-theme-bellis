<?php 

$style = '<style>'.
file_get_contents( __DIR__ . '/css/search-results.css')
.'</style>';

get_header(null,['head_aditional_code' => $style]);  ?>

<div id="main" class="main flex w-full mt-6 mb-6 justify-center">
 
    
<main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
        
<?php get_search_form(['a309_search_btn' => true]); ?>
    <h1 class="list-posts-title"> Search Results for:  <span><?php echo get_search_query(); ?></span></h1>
 <?php 
 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
 
  get_template_part( 'parts/article', null, [ 'full_content' => false ]);  
 
 endwhile;
 
 echo the_posts_pagination();
 }else {
 ?>
    <p class="search-no-result">No result for your query &lt;&lt;&lt; <?php echo get_search_query(); ?> &gt;&gt;&gt;</p>
 <?php } ?>
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer();
get_template_part( 'parts/end-markup');