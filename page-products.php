<?php
/* Template Name: All Products Page */
get_header(); ?>

<section id="products" >
    <h1 class="section-title">All Products</h1>
    <p class="section-subtitle">Browse all our available artworks</p>

    <div class="gallery" data-aos="fade-up" data-aos-duration="1500">
        <?php
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1, // show all products
            'orderby'        => 'date',
            'order'          => 'DESC'
        );

        $products = new WP_Query($args);

        if ($products->have_posts()) :
            while ($products->have_posts()) : $products->the_post();
                $product = wc_get_product(get_the_ID());
                ?>
                     <div class="product-card"  >
    <div class="product-image">
        <a href="<?php the_post_thumbnail_url('full'); ?>" class="product-lightbox-link">
            <?php if (has_post_thumbnail()) : ?>
                <img src="<?php the_post_thumbnail_url('medium'); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
            <?php endif; ?>
        </a>
    </div>

    <div class="product-info">
        <h3 class="product-title">
            <a href="<?php echo get_permalink( $product->get_id() ); ?>">
                <?php echo esc_html( $product->get_name() ); ?>
            </a>
        </h3>

        <?php
        $desc = $product->get_short_description();
        if (empty($desc)) $desc = $product->get_description();
        if ($desc) :
        ?>
            <p class="product-desc"><?php echo wp_kses_post($desc); ?></p>
        <?php endif; ?>

        <span class="price"><?php echo $product->get_price_html(); ?></span>

        <?php if ($product->is_purchasable()) : ?>
            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="cart-btn">
                Add to Cart
            </a>
        <?php endif; ?>

        <!-- ❤️ LIKE BUTTON -->
        <button class="product-like-btn" data-id="<?php echo $product->get_id(); ?>">
            ❤️ <span class="like-count">
                <?php echo (int) get_post_meta($product->get_id(), '_product_likes', true); ?>
            </span>
        </button>

        <!-- 💬 COMMENT FORM -->
        <div class="custom-comment-form">
            <textarea id="comment-text-<?php echo $product->get_id(); ?>" rows="2" placeholder="Write a comment..."></textarea>
            <button class="submit-comment-btn" data-id="<?php echo $product->get_id(); ?>">Submit</button>
        </div>

        <!-- 📝 COMMENTS LIST -->
        <div class="custom-comments-list" id="comments-list-<?php echo $product->get_id(); ?>">
            <?php
            $comments = get_post_meta($product->get_id(), '_product_comments', true);
            if (is_array($comments)) {
                foreach ($comments as $c) {
                    echo '<div class="single-comment"><strong>' .
                        esc_html($c['user']) . ':</strong> ' .
                        esc_html($c['text']) . '</div>';
                }
            }
            ?>
        </div>
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

<!-- ================= LIGHTBOX OVERLAY ================= -->
<div id="lightboxOverlay">
    <img src="" alt="Product Image">
</div>

<!-- ================= CSS ================= -->
<style>
/* Lightbox overlay */
#lightboxOverlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100vw; height: 100vh;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(8px);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    cursor: pointer;
}
#lightboxOverlay.active {
    display: flex;
}
#lightboxOverlay img {
    max-width: 90%;
    max-height: 90%;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    cursor: auto;
}

/* Optional: disable scrolling when lightbox is open */
body.overlay-active {
    overflow: hidden;
}

/* ======= CUSTOM LIKE & COMMENT ======= */
.product-like-btn {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    margin-top: 10px;
}

.custom-comment-form textarea {
    width: 100%;
    margin-bottom: 5px;
}

.single-comment {
    padding: 5px 0;
    border-bottom: 1px solid #eee;
}
</style>

<!-- ================= JAVASCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ========== LIGHTBOX ==========
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImage = lightboxOverlay.querySelector('img');
    const popupImages = document.querySelectorAll('.popup-img');

    popupImages.forEach(img => {
        img.addEventListener('click', function() {
            lightboxImage.src = this.dataset.full;
            lightboxOverlay.style.display = 'flex';
            document.body.classList.add('overlay-active');
        });
    });

    lightboxOverlay.addEventListener('click', function(e) {
        if (e.target !== lightboxImage) {
            lightboxOverlay.style.display = 'none';
            lightboxImage.src = '';
            document.body.classList.remove('overlay-active');
        }
    });

  
  /* ❤️ LIKE */
    document.querySelectorAll('.product-like-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'action=product_like&post_id=' + this.dataset.id
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    this.querySelector('.like-count').textContent = d.data;
                }
            });
        });
    });

    /* 💬 COMMENT */
    document.querySelectorAll('.submit-comment-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const textarea = document.getElementById('comment-text-' + id);
            if (!textarea.value.trim()) return alert('Write a comment');

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'action=product_comment&post_id=' + id + '&text=' + encodeURIComponent(textarea.value)
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const list = document.getElementById('comments-list-' + id);
                    list.innerHTML = '';
                    d.data.forEach(c => {
                        list.innerHTML += '<div class="single-comment"><strong>' + c.user + ':</strong> ' + c.text + '</div>';
                    });
                    textarea.value = '';
                }
            });
        });
    });

});
</script>

<?php get_footer(); ?>
