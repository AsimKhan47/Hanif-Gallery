<?php
// Theme setup
function gallery_setup() {
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('post-thumbnails');

    register_nav_menus(array(
        'primary' => __('Primary Menu', 'gallery'),
    ));
}
add_action('after_setup_theme', 'gallery_setup');


// Enqueue CSS & JS
function gallery_enqueue_assets() {

    // Load main stylesheet (style.css)
    wp_enqueue_style('gallery-style', get_stylesheet_uri());

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
