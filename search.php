<?php 

$style = '
<style>
.search-form div {
    position: relative;
    margin: 1em 1em 2em 1em;
}
.search-form div input[type="search"] {
    padding: 0.2em 0 0.1em 1.7em;
    border: 1px solid #8e8c8c;
    box-shadow: -1px 1px 2px 1px #8a8a8a;
    width: 100%;
}
.search-form div i {
    position: absolute;
    top: px;
    color: #b3b3b3;
    padding: 0.18em;
    top: 5px;
    left: 2px;
}
h1.search-title{
    font-size: 1.6em;
    padding: 0.7em;
    width: 100%;
    text-align: center;
    border-bottom: 1px solid #bbb;
    border-top: 1px solid #bbb;
    margin-bottom: 1em;
    }
    
input[type="submit"]{
    position: absolute;
    right: 0.1em;
    background: #1b2426;
    color: #ffffff;
    padding: 0.18em;
    box-shadow: 0px 0px 0px 1px #435a5f;
    border: 1px solid #9b51e0;
    transition: all 0.4s ease-in;
}
input[type="submit"]:hover{
    color: #ffaa3e;
    border: 1px solid #ff9933;
}

p.search-no-result{
    font-size: 1.6em;
    text-align: center;
    margin-top: 2em;
}

</style>
';

get_header(null,['head_aditional_code' => $style]);  ?>

<div class="main flex w-full mt-6 mb-6 justify-center">
 
    
<main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
        
<?php get_search_form(['a309_search_btn' => true]); ?>
    <h1 class="search-title"> Search Results for:  <span><?php echo get_search_query(); ?></span></h1>
 <?php 
 if ( have_posts() ) { 
 while ( have_posts() ) : the_post();
 
  get_template_part( 'parts/article', null, [ 'full_content' => false ]);  
 
 endwhile;
 
 echo the_posts_pagination();
 }else {
 ?>
    <p class="search-no-result">No result for your query <<< <?php echo get_search_query(); ?> >>></p>
 <?php } ?>
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer() ?>    
    </body>
</html>
