<?php
/**
 * Front Page Template
 * Template for the homepage
 */
get_header(); 
?>

<!-- Hero Section -->
<section id="hero">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/herobg.png" alt="Hero Image">

    <div class="hero-btn-wrap">
        <button class="hero-btn">
            VIEW ALL PAINTINGS
        </button>
    </div>
</section>

<!-- Products Section -->
<section id="products">
    <h1>You might like it.</h1>
    <p>"A selection of contemporary artworks based on your taste"</p>

    <div class="gallery">
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 12,
        );

        $products = new WP_Query($args);

        if ($products->have_posts()) :
            while ($products->have_posts()) : $products->the_post();
                $product = wc_get_product(get_the_ID());
                ?>
                <div class="product-card">
                    <?php if (has_post_thumbnail()) : ?>
                        <!-- Add class 'popup-img' for JS -->
                        <img class="popup-img" 
                             src="<?php the_post_thumbnail_url('medium'); ?>" 
                             data-full="<?php the_post_thumbnail_url('full'); ?>" 
                             alt="<?php the_title(); ?>">
                    <?php endif; ?>

                    <div class="product-info">
                        <h3><?php the_title(); ?></h3>

                        <?php if ($product) : ?>
                            <span class="price"><?php woocommerce_template_loop_price(); ?></span>
                        <?php endif; ?>
                        <br>
                        <?php if ($product && $product->is_purchasable()) : ?>
                            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" 
                               class="cart-btn">
                                <?php echo esc_html($product->add_to_cart_text()); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>No products found.</p>';
        endif;
        ?>
    </div>
</section>

<!-- Lightbox Overlay -->
<div id="image-popup-overlay" class="image-popup-overlay">
    <img src="" alt="Product Image">
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('image-popup-overlay');
    const popupImg = overlay.querySelector('img');

    // Click on any product image
    document.querySelectorAll('.popup-img').forEach(img => {
        img.addEventListener('click', () => {
            popupImg.src = img.dataset.full || img.src; // use full image if available
            overlay.classList.add('active'); // show overlay
        });
    });

    // Click on overlay to close
    overlay.addEventListener('click', () => {
        overlay.classList.remove('active');
        popupImg.src = '';
    });
});
</script>



<?php get_footer(); ?>
