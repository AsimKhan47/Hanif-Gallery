<?php
get_header();
global $product;

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $product = wc_get_product(get_the_ID());
        ?>
        
        <section class="single-product-container" >
            <div class="product-card-single">

                <!-- PRODUCT IMAGE -->
                <div class="product-image">
                    <a href="<?php echo get_the_post_thumbnail_url($product->get_id(), 'full'); ?>" class="product-lightbox-link">
                        <?php if (has_post_thumbnail()) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url($product->get_id(), 'medium'); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                        <?php endif; ?>
                    </a>
                </div>

                <!-- PRODUCT INFO -->
                <div class="product-info">
                    <h1 class="product-title"><?php echo esc_html($product->get_name()); ?></h1>

                    <div class="product-price"><?php echo $product->get_price_html(); ?></div>

                    <div class="product-description">
                        <?php
                        $desc = $product->get_short_description();
                        if (empty($desc)) $desc = $product->get_description();
                        echo wp_kses_post($desc);
                        ?>
                    </div>

                    <?php if ($product->is_purchasable()) : ?>
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="cart-btn">
                            Add to Cart
                        </a>
                    <?php endif; ?>

                    <!-- ❤️ LIKE BUTTON -->
                    <button class="product-like-btn" data-id="<?php echo $product->get_id(); ?>">
                        ❤️ <span class="like-count"><?php echo (int) get_post_meta($product->get_id(), '_product_likes', true); ?></span>
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
                                echo '<div class="single-comment"><strong>' . esc_html($c['user']) . ':</strong> ' . esc_html($c['text']) . '</div>';
                            }
                        }
                        ?>
                    </div>
                </div>

            </div>
        </section>

        <!-- LIGHTBOX OVERLAY -->
        <div id="lightboxOverlay">
            <img src="" alt="Product Image">
        </div>

        <style>
        /* ================== SINGLE PRODUCT CSS ================== */

        .single-product-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
        }

        .product-card-single {
            display: flex;
            flex-wrap: wrap;
            gap: 40px;
        }

        .product-image {
            flex: 1 1 400px;
        }

        .product-image img {
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .product-info {
            flex: 1 1 400px;
            display: flex;
            flex-direction: column;
        }

        .product-title {
            font-size: 32px;
			color:black;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 24px;
            color: black;
            margin-bottom: 20px;
        }

        .product-description {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .cart-btn {
            display: inline-block;
            background: #ffcc00;
            color: black;
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            margin-bottom: 15px;
            transition: background 0.3s;
        }

        .cart-btn:hover {
            background: #ffcc00;
        }

        .product-like-btn {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .custom-comment-form textarea {
            width: 100%;
            margin-bottom: 5px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .custom-comment-form button {
            background: #1d4ed8;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .custom-comments-list .single-comment {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }

        /* LIGHTBOX */
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

        body.overlay-active {
            overflow: hidden;
        }
        </style>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lightboxOverlay = document.getElementById('lightboxOverlay');
            const lightboxImage = lightboxOverlay.querySelector('img');

            // Image click lightbox
            document.querySelectorAll('.product-lightbox-link img').forEach(img => {
                img.addEventListener('click', function () {
                    lightboxImage.src = this.src;
                    lightboxOverlay.classList.add('active');
                    document.body.classList.add('overlay-active');
                });
            });

            lightboxOverlay.addEventListener('click', function(e) {
                if (e.target !== lightboxImage) {
                    lightboxOverlay.classList.remove('active');
                    lightboxImage.src = '';
                    document.body.classList.remove('overlay-active');
                }
            });

            // LIKE BUTTON
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

            // COMMENT BUTTON
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

<?php
    endwhile;
endif;

get_footer();
