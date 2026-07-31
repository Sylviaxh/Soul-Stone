<?php
/* Template Name: Soul Stone Products */
get_header();
?>
<main>
      <section class="products-hero">
        <div class="products-hero-copy">
          <span class="eyebrow">Shop by intention</span>
          <h1>Products</h1>
          <p>Explore handmade crystal bracelets designed around self-love, protection, focus, transformation and new beginnings.</p>
          <div class="products-hero-links">
            <a href="<?php echo esc_url(soul_stone_page_url('products')); ?>#collection-gallery">Collection Gallery</a>
            <a href="<?php echo esc_url(soul_stone_page_url('products')); ?>#new-arrivals">New Arrivals</a>
            <a href="<?php echo esc_url(soul_stone_page_url('products')); ?>#gift-ideas">Gift Ideas</a>
          </div>
        </div>
        <div class="products-hero-gallery" aria-label="Soul Stone product collections">
          <img class="products-hero-main" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt="Crystal bracelet collection">
          <img class="products-hero-accent products-hero-accent-top" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero-bracelet.png" alt="Soft crystal bracelet">
          <img class="products-hero-accent products-hero-accent-bottom" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/obsidian-preview.png" alt="Protection bracelet">
        </div>
      </section>
      <section class="section"><div class="product-grid">
        <a class="card product-entry-card" id="collection-gallery" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>#collection-gallery"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/collection-bracelets.png" alt=""><div class="card-body"><h2>Collection Gallery</h2><p>Browse signature intention pieces and limited handmade drops.</p><span class="outline-button">View Products</span></div></a>
        <a class="card product-entry-card" id="new-arrivals" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>#new-arrivals"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/hero-bracelet.png" alt=""><div class="card-body"><h2>New Arrivals</h2><p>Moonstone, rose quartz and amethyst bracelets for soft new chapters.</p><span class="outline-button">View Products</span></div></a>
        <a class="card product-entry-card" id="gift-ideas" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>#gift-ideas"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/obsidian-preview.png" alt=""><div class="card-body"><h2>Gift Ideas</h2><p>Meaningful crystal jewelry for graduation, birthdays and fresh starts.</p><span class="outline-button">View Products</span></div></a>
      </div></section>
    </main>
<?php get_footer(); ?>
