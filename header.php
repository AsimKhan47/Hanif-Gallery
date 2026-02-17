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

        <!-- LOGO -->
        <div class="logo">
            <a href="/">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.jpeg" alt="Site Logo">
            </a>
        </div>

        <!-- MANUAL MENU -->
      <nav class="nav-links" id="navLinks">
    <?php
    wp_nav_menu([
        'theme_location' => 'primary_menu',
        'container' => false,        // Remove default <div>
        'menu_class' => '',           // Optional: class for <ul>
        'fallback_cb' => false        // If menu not set, show nothing
    ]);
    ?>
</nav>

        <!-- MOBILE TOGGLE -->
        <button class="menu-toggle" aria-label="Toggle Menu" onclick="toggleMenu()">
            ☰
        </button>

    </div>
</header>

<script>
function toggleMenu() {
    const nav = document.getElementById('navLinks');
    nav.classList.toggle('active');
}
	document.querySelectorAll('.menu-item-has-children > a').forEach(item => {
    item.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            e.preventDefault();
            this.parentElement.classList.toggle('open');
        }
    });
});
</script>

<?php wp_footer(); ?>
</body>
</html>
