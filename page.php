<?php
get_header();
?>

<div class="page-content container">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) : the_post();
            the_content(); // <-- this will render your page content & shortcodes
        endwhile;
    endif;
    ?>
</div>

<?php
get_footer();
