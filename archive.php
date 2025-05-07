<?php

$style = '<style>'.
file_get_contents(__DIR__.vite_assets('/dev-assets/scss/base/post-list.scss'))
.'</style>';

get_header(null, ['head_aditional_code' => $style]); ?>

<div id="main" class="main flex w-full pb-6 justify-center">

<main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8 dark:bg-dark-bg dark:text-dark-text">
        
    <h1 class="list-posts-title"> Post For Date:  <span><?php the_archive_title(); ?></span></h1>
 <?php
 if (have_posts()) {
     while (have_posts()) {
         the_post();

         get_template_part('parts/article', null, ['full_content' => false]);
     }

     echo the_posts_pagination();
 } else {
     ?>
    <p class="search-no-result">No result for your query <<< <?php echo get_search_query(); ?> >>></p>
 <?php } ?>
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer();
get_template_part('parts/end-markup');
