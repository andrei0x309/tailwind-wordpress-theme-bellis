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
    padding-bottom: 0.4em;
    width: 100%;
    border-bottom: 1px solid #a0aec0;
    margin-bottom: 0.8em;
    padding-left: 0.5em;
    }
    
input[type="submit"]{
    position: absolute;
    right: 0.1em;
    background: #1b2426;
    color: #ffffff;
    padding: 0.17em;
    box-shadow: 0px 0px 0px 1px #32373c;
    border: 1px solid #555;
    transition: all 0.4s ease-in;
    cursor:pointer;
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

nav.pagination{
font-size: 1.2em;
}

nav.pagination h2{
padding: 0.6em;
margin-top: -1.2em;
}
nav.pagination a.page-numbers, span.page-numbers{
padding: 0.2em;
    border: 1px solid #b9bdb6;
    color: #0274be;
    box-shadow: 1px 1px 1px #a0aec0;
    transition: all 0.3s ease-in;
    }

nav.pagination span.page-numbers, nav.pagination a.page-numbers:hover {
    border: 1px solid #1b2426;
    box-shadow: 1px 1px 2px 1px #a0aec0;
    background-color: #1b2426;
    color: #ffaa3e;
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
