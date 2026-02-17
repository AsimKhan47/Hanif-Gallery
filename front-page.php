<?php
/**
 * Front Page Template
 */
get_header(); 
?>

<!-- ================= HERO SECTION ================= -->
<section id="hero">
    <div class="hero-bg" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/top-bar.jpeg');"></div>

    <div class="hero-bottom" data-aos="fade-up" data-aos-duration="1500">
        <div class="hero-menu-toggle" id="heroMenuToggle">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <a href="<?php echo esc_url(home_url('/products/')); ?>" class="hero-btn">
            VIEW ALL PAINTINGS
        </a>
    </div>

   <div class="hero-menu" id="heroMenu">
    <?php
    wp_nav_menu([
        'theme_location' => 'primary_menu',
        'container'      => false,
        'menu_class'     => 'hero-nav',
        'fallback_cb'    => false
    ]);
    ?>
</div>
</section>

<!-- ================= CATEGORIES SECTION ================= -->
<section id="categories" data-aos="fade-up" data-aos-duration="1500" >
    <h1 class="section-title">Explore Art Categories</h1>
    <p class="section-subtitle">Choose a style that inspires you</p>

    <div class="category-grid">
        <?php
        $categories = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
        ]);

        if ($categories && !is_wp_error($categories)) :
            foreach ($categories as $category) :
                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                $image = wp_get_attachment_url($thumbnail_id);
        ?>
            <div class="category-card">
                <a href="#" data-category="<?php echo esc_attr($category->slug); ?>">
                    <?php if ($image) : ?>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                    <?php endif; ?>
                    <h3><?php echo esc_html($category->name); ?></h3>
                </a>
            </div>
        <?php endforeach; endif; ?>
    </div>
</section>

<!-- ================= FILTERED PRODUCTS ================= -->
<section id="filtered-products" >
<?php
if (!empty($categories)) :
    foreach ($categories as $category) :
        $products = new WP_Query([
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'tax_query'      => [[
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $category->slug,
            ]],
        ]);

        if ($products->have_posts()) :
?>
<div class="category-products" id="category-<?php echo esc_attr($category->slug); ?>" style="display:none;">
    <h2 class="section-title"><?php echo esc_html($category->name); ?> Paintings</h2>

     <div class="gallery">
            <?php
            while ($products->have_posts()) : $products->the_post();
                $product = wc_get_product(get_the_ID());
                if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
                    $product = wc_get_product( get_the_ID() );
                }
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
            <a href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
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
            <input type="text" id="comment-user-<?php echo $product->get_id(); ?>" placeholder="Your Name" />
            <textarea id="comment-text-<?php echo $product->get_id(); ?>" rows="2" placeholder="Write a comment..."></textarea>
            <button class="submit-comment-btn" data-id="<?php echo $product->get_id(); ?>">Submit</button>
        </div>

        <!-- 📝 COMMENTS LIST -->
        <div class="custom-comments-list" id="comments-list-<?php echo $product->get_id(); ?>">
            <?php
            $comments = get_post_meta($product->get_id(), '_product_comments', true);
            if (is_array($comments)) {
                foreach ($comments as $c) {
                    echo '<div class="single-comment"><strong>' . esc_html($c['user']) . ':</strong> ' . esc_html($c['text']) . '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>

            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    <div class="view-all-wrapper">
        <a class="hero-bt1" href="<?php echo esc_url(home_url('/products/')); ?>">
            View All Paintings
        </a>
    </div>
</div>
<?php endif; endforeach; endif; ?>
</section>

<!-- ================= LIGHTBOX OVERLAY ================= -->
<div id="lightboxOverlay">
    <img src="" alt="Product Image">
</div>

<!-- ================= ADMIN FLAG ================= -->
<script>
const isAdmin = <?php echo current_user_can('manage_options') ? 'true' : 'false'; ?>;
</script>

<!-- ================= JAVASCRIPT ================= -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ===== HERO MENU TOGGLE ===== */
    const toggle = document.getElementById('heroMenuToggle');
    const menu = document.getElementById('heroMenu');
    if (toggle && menu) {
        toggle.addEventListener('click', e => {
            e.stopPropagation();
            menu.classList.toggle('active');
            toggle.classList.toggle('active');
        });
        document.addEventListener('click', e => {
            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.remove('active');
                toggle.classList.remove('active');
            }
        });
    }

    /* ===== CATEGORY FILTER ===== */
const categoryLinks = document.querySelectorAll('.category-card a');
const categorySections = document.querySelectorAll('.category-products');

categoryLinks.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();

        // Hide all sections
        categorySections.forEach(section => section.style.display = 'none');

        // Show target section
        const target = document.getElementById('category-' + link.dataset.category);
        if (target) {
            target.style.display = 'block';

            // Smooth scroll to the section
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
    /* ===== LIGHTBOX ===== */
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImage = lightboxOverlay.querySelector('img');

    document.querySelectorAll('.product-lightbox-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            lightboxImage.src = this.href;
            lightboxOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    });

    lightboxOverlay.addEventListener('click', function(e) {
        if (e.target !== lightboxImage) {
            lightboxOverlay.style.display = 'none';
            lightboxImage.src = '';
            document.body.style.overflow = 'auto';
        }
    });

    /* ===== LIKE BUTTON ===== */
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

    /* ===== COMMENT SUBMISSION ===== */
    document.querySelectorAll('.submit-comment-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const user = document.getElementById('comment-user-' + id).value.trim();
            const textarea = document.getElementById('comment-text-' + id);
            const text = textarea.value.trim();

            if (!user) return alert('Please enter your name');
            if (!text) return alert('Write a comment');

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'action=product_comment&post_id=' + id + '&text=' + encodeURIComponent(text) + '&user=' + encodeURIComponent(user)
            })
            .then(r => r.json())
            .then(d => {
                if (d.success) {
                    const list = document.getElementById('comments-list-' + id);
                    list.innerHTML = '';
                    d.data.forEach((c,i) => {
                        let html = '<div class="single-comment"><strong>' + c.user + ':</strong> ' + c.text;
                        if(isAdmin){
                            html += ' <button class="remove-comment-btn" data-id="'+id+'" data-index="'+i+'">❌</button>';
                        }
                        html += '</div>';
                        list.innerHTML += html;
                    });
                    textarea.value = '';
                    document.getElementById('comment-user-' + id).value = '';
                }
            });
        });
    });

    /* ===== REMOVE COMMENT (ADMIN ONLY) ===== */
    document.addEventListener('click', function(e){
        if(e.target.classList.contains('remove-comment-btn')) {
            const post_id = e.target.dataset.id;
            const index = e.target.dataset.index;

            fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'action=remove_product_comment&post_id=' + post_id + '&index=' + index
            })
            .then(r => r.json())
            .then(d => {
                if(d.success){
                    e.target.parentElement.remove();
                }
            });
        }
    });

});
</script>

<!-- ================= LIGHTBOX CSS ================= -->
<style>
#lightboxOverlay {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
    justify-content: center;
    align-items: center;
    z-index:9999;
    cursor:pointer;
}
#lightboxOverlay img {
    max-width:90%;
    max-height:90%;
    border-radius:4px;
    box-shadow:0 10px 30px rgba(0,0,0,0.5);
    cursor:auto;
}

/* COMMENTS */
.custom-comment-form input,
.custom-comment-form textarea {
    width: 100%;
    margin-bottom:5px;
    padding:8px;
    border-radius:6px;
    border:1px solid #ccc;
}

.custom-comment-form button {
    padding:6px 12px;
    border:none;
    border-radius:6px;
    background:#1d4ed8;
    color:#fff;
    cursor:pointer;
}

.single-comment {
    padding:5px 0;
    border-bottom:1px solid #eee;
    position:relative;
}

.remove-comment-btn {
    background:none;
    border:none;
    color:red;
    cursor:pointer;
    font-size:14px;
    margin-left:10px;
}
</style>

<?php get_footer(); ?>
