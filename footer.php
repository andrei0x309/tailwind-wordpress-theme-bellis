 <footer class="footer flex w-full items-center text-center text-4xl content-center justify-center">

     <?php  
    if(is_active_sidebar('footer-sidebar')){
dynamic_sidebar('footer-sidebar');
}

wp_footer(); ?>

 </footer>

