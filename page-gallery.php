<?php
/* Template Name: Gallery Page */
get_header();
?>

<section class="gallery-section">
  <div class="container">
    <h1 class="gallery-title"><?php the_title(); ?></h1>

    <div class="gallery-grid">
      <div class="gallery-item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery1.jpeg" alt="Gallery Image 1">
      </div>

      <div class="gallery-item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/gallery2.jpeg" alt="Gallery Image 2">
      </div>

      
    </div>
  </div>
</section>

<?php get_footer(); ?>
