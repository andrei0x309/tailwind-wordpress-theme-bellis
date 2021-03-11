<?php /* Template Name: Offline Page */ ?>
<?php
$style = '<style>'.
file_get_contents( __DIR__ . '/../css/page-offline.css')
.'</style>';
         
get_header(null,['head_aditional_code' => $style]); ?>

<div class="main flex w-full mt-6 mb-6 justify-center">
 
    
    <main class="flex flex-col content bg-white w-full sm:w-full md:w-7/12 lg:w-7/12 xl:w-7/12 p-8">
         <div class="offline">
  <div class="wrapper">
    <h1> 🔴 OFFLINE</h1>
    <h4>Please check your internet connection</h4>
  </div>
  </div>
    </main>
<?php get_sidebar(); ?>
  </div>
<?php get_footer() ?>    
    </body>
</html>




        
        
 
 