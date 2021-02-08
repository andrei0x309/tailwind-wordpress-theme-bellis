<?php

$style = '
    
<style>

    .not-found-subtitle {
    text-align: center;
    font-size: 1.5em;
    text-transform: uppercase;
    margin-top: -0.6em;
    margin-bottom: 0.6em;
    }
   
   .not-found-404 {
            font-size: 3.7em;
            text-align: center;
            padding: 0.2em;
            transform: skewX(357deg);
            font-weight: 700;
            text-shadow: 1px 1px 2px #2a3d66;
            color: #3d598c;
            }
            
.not-found-back-button {

display: block;
    margin: 0.5em auto 1em auto;
    width: 13em;
    padding: .5em 0 .5em 0.8em;
    background-color: #2a3d66;
    border-radius: 0.6em;
    color: darkorange;
    border: 1px solid #2a3d66;
    font-weight: 600;
    background: linear-gradient(
180deg
, rgba(42,61,102,1) 23%, rgba(33,46,75,1) 88%, rgba(35,36,40,1) 100%);

}

.not-found-back-button:hover {
color: white;
}

.not-found-back-button:hover .not-found-back-icon {
 color: darkorange;
}


.not-found-back-icon {
    color: white;
    display: inline-block;
    transform: translateX(-2px) translateY(2px) rotate(
90deg
);

}


</style>';
         
get_header(null,['head_aditional_code' => $style]); ?>

<div class="main flex w-full mt-6 mb-6 justify-center">
 
    $
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
     
                    <header class="page-header">
                <p
                    class="not-found-404"
                    
                    >404</p>
                <h1 class="page-title not-found-subtitle"><?php _e( 'Resource Not Found', 'a309wp' ); ?></h1>
            </header>
            
                    <div class="page-wrapper">
                <div class="page-content">
                    <a class="not-found-back-button" href="<?php echo site_url(); ?>" title="back to home button"><i class="icon-triangle-down not-found-back-icon"></i><?php _e( 'back to the front page', 'a309wp' ); ?></a>
                    
                    
                    
                    
                    <p><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'twentythirteen' ); ?></p>
 
                    <?php get_search_form(); ?>
                </div><!-- .page-content -->
            </div><!-- .page-wrapper -->
 
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer() ?>    
    </body>
</html>
