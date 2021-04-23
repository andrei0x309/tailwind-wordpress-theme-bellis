<?php get_header(null);?>
<div id="main" class="main flex w-full mt-6 mb-6 justify-center">
<main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8 dark:bg-dark-bg dark:text-dark-text">
<?php if (have_posts()) {
    while (have_posts()) {
        the_post();
        get_template_part('parts/article', null, ['full_content' => true]);
    }
}

?>
</main>
<?php get_sidebar();?>
</div>

<?php get_footer();
get_template_part('parts/end-markup');
