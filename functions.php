<?php
// Theme setup
function gallery_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');

    // Register Primary Menu
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'gallery')
    ));
}
add_action('after_setup_theme', 'gallery_setup');


// Enqueue CSS & JS
function gallery_enqueue_assets() {

    // Load main stylesheet (assets/css/style.css)
    wp_enqueue_style(
        'gallery-style',
        get_template_directory_uri() . '/style.css',
        array(),
        filemtime(get_template_directory() . '/style.css')
    );

    // Load custom JS file
    wp_enqueue_script(
        'gallery-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        array(),       // No dependencies
        false,         // No version
        true           // Load in footer
    );
}
add_action('wp_enqueue_scripts', 'gallery_enqueue_assets');

function mytheme_register_menus() {
    register_nav_menus([
        'primary_menu' => __('Primary Menu', 'mytheme'),
    ]);
}
add_action('after_setup_theme', 'mytheme_register_menus');


// ❤️ PRODUCT LIKE
add_action('wp_ajax_product_like', 'handle_product_like');
add_action('wp_ajax_nopriv_product_like', 'handle_product_like');

function handle_product_like() {
    $post_id = intval($_POST['post_id']);
    if (!$post_id) wp_send_json_error('Invalid ID');

    $likes = (int) get_post_meta($post_id, '_product_likes', true);
    $likes++;
    update_post_meta($post_id, '_product_likes', $likes);

    wp_send_json_success($likes);
}

// 💬 PRODUCT COMMENT
add_action('wp_ajax_product_comment', 'handle_product_comment');
add_action('wp_ajax_nopriv_product_comment', 'handle_product_comment');

function handle_product_comment() {
    $post_id = intval($_POST['post_id']);
    $text = sanitize_text_field($_POST['text']);

    if (!$post_id || !$text) wp_send_json_error('Invalid data');

    $comments = get_post_meta($post_id, '_product_comments', true);
    if (!is_array($comments)) $comments = [];

    $comments[] = [
        'user' => is_user_logged_in() ? wp_get_current_user()->display_name : 'Guest',
        'text' => $text
    ];

    update_post_meta($post_id, '_product_comments', $comments);

    wp_send_json_success($comments);
}

add_action('wp_enqueue_scripts', function() {
    // AOS CSS
    wp_enqueue_style('aos-css', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css', [], '2.3.4');
    
    // AOS JS
    wp_enqueue_script('aos-js', 'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js', ['jquery'], '2.3.4', true);

    // Initialize AOS
    wp_add_inline_script('aos-js', 'AOS.init({ duration: 1000, once: true });');
});


   