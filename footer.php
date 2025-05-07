 <footer class="footer pt-4 flex w-full items-center text-center text-sm text-white content-center justify-center">

     <?php
    if (is_active_sidebar('footer-sidebar')) {
        dynamic_sidebar('footer-sidebar');
    }

     wp_footer(); ?>

 </footer>