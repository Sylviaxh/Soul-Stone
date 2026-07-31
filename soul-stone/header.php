<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
  </head>
  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <header class="site-header">
      <a class="logo-link" href="<?php echo esc_url(home_url('/')); ?>" aria-label="Soul Stone homepage"><img class="logo-mark" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/logo-mark.png" alt=""><span class="brand-name">Soul Stone</span></a>
      <button class="menu-button" aria-label="Open navigation" aria-expanded="false"><span></span></button>
      <div class="nav-wrap">
        <nav class="main-nav">
          <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
          <div class="nav-dropdown">
            <a class="nav-dropdown-toggle" href="<?php echo esc_url(soul_stone_page_url('products')); ?>">Products <span aria-hidden="true">⌄</span></a>
            <div class="nav-dropdown-menu" aria-label="Product sections">
              <a href="<?php echo esc_url(soul_stone_page_url('products')); ?>#collection-gallery">Collection Gallery</a>
              <a href="<?php echo esc_url(soul_stone_page_url('products')); ?>#new-arrivals">New Arrivals</a>
              <a href="<?php echo esc_url(soul_stone_page_url('products')); ?>#gift-ideas">Gift Ideas</a>
            </div>
          </div>
          <a href="<?php echo esc_url(soul_stone_page_url('materials')); ?>">Materials</a>
          <a href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Custom Design</a>
          <a href="<?php echo esc_url(soul_stone_page_url('about')); ?>">About Us</a>
          <a href="<?php echo esc_url(is_user_logged_in() ? soul_stone_page_url('account') : soul_stone_page_url('login')); ?>"><?php echo is_user_logged_in() ? 'Account' : 'Login'; ?></a>
          <a class="cart-link" href="<?php echo esc_url(soul_stone_page_url('cart')); ?>" aria-label="Cart"><span class="bag-icon" aria-hidden="true"></span><span class="cart-count"><?php echo esc_html((string) soul_stone_wc_cart_count()); ?></span></a>
        </nav>
        <a class="black-button" href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Start Designing</a>
      </div>
    </header>
