<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">
    <div class="nav-container">

        <div class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.jpg" alt="Site Logo">
            </a>
        </div>

        <nav class="nav-links" id="navLinks">
            <a href="#hero">Home</a>
            <a href="#products">About</a>
            <a href="#products">Gallery</a>
            <a href="#products">Personal Award</a>
            <a href="#products">Painting Award</a>
            <a href="#products">Abstract Painting</a>
            <a href="#products">Portrait Painting</a>
            <a href="#products">Glass Painting</a>
            <a href="#products">Natural Painting</a>
            <a href="#products">Contact</a>
        </nav>

        <div class="menu-toggle" onclick="toggleMenu()">
            ☰
        </div>

    </div>
</header>

