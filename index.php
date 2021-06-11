<?php if (!defined('ABSPATH')) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /');
    exit();
}

get_header();
?>

<div id="main" class="main flex w-full mt-6 mb-6 justify-center">
 
    <main class="flex flex-col content w-full sm:w-full md:w-5/12 lg:w-5/12 xl:w-5/12 dark:text-dark-text">
     
        
         <?php if (have_posts()) {
    while (have_posts()) {
        the_post();

        get_template_part('parts/article', null, ['full_content' => false]);
    }
}

  //echo the_posts_pagination();

 ?>
 
       <div class="text-center m-2"> 
        <button id="show-more-posts-btn">Load more Posts</button>
        </div>
 
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer();
get_template_part('parts/end-markup');
