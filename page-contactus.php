<?php
/* Template Name: Contact Us */
get_header();
?>

<!-- ================= BANNER ================= -->
<!-- <section class="contact-banner" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/contactus.jpeg');">
</section> -->

<!-- ================= CONTACT FORM ================= -->
<section class="contact-form-section" data-aos="fade-up">
    <div class="container">
        <?php
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
                the_content(); // <- This renders your Contact Form 7 shortcode
            endwhile;
        endif;
        ?>
    </div>
</section>

<!-- ================= ADDRESS SECTION ================= -->
<!-- <section class="contact-address-section py-10 bg-gray-100">
    <div class="container mx-auto text-center">
        <h2 class="text-2xl font-bold mb-4">Our Address</h2>
        <p class="mb-2">123 Main Street, Suite 456</p>
        <p class="mb-2">Cityville, State 12345</p>
        <p class="mb-2">Phone: (123) 456-7890</p>
        <p>Email: info@example.com</p>
    </div>
</section> -->

<?php get_footer(); ?>