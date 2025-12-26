<?php get_header(); ?>

<!-- Hero Section -->
<section id="hero">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/hero.jpg" alt="Hero Image">
    <div class="hero-btn-wrap">
        <button class="hero-btn">Explore</button>
    </div>
</section>

<!-- Gallery Section -->
<section id="products">
    <h1>Our Products</h1>
    <p>Check out our latest collection</p>
    <div class="gallery">
        <!-- Example Product Card -->
        <div class="product-card">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/product1.jpg" alt="Product 1">
            <div class="product-info">
                <h3>Product 1</h3>
                <span class="price">$99</span>
                <button class="cart-btn">Add to Cart</button>
            </div>
        </div>
  
    </div>
</section>

<?php get_footer(); ?>
