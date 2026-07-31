<?php
/* Template Name: Soul Stone Product */
get_header();

$product_id = isset($_GET['id']) ? sanitize_text_field(wp_unslash($_GET['id'])) : '';
$product = $product_id ? soul_stone_find_frontend_product($product_id) : null;
$price = $product ? (float) $product['price'] : 0;
?>
<main>
  <section class="section product-detail" id="productDetail" data-server-product="<?php echo $product ? '1' : '0'; ?>">
    <?php if ($product) : ?>
      <img class="product-detail-image" id="detailImage" src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>">
      <div class="product-detail-info">
        <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Back to All Products</a>
        <span class="eyebrow" id="detailTheme"><?php echo esc_html($product['theme']); ?></span>
        <h1 id="detailTitle"><?php echo esc_html($product['name']); ?></h1>
        <span class="detail-price" id="detailPrice">$<?php echo esc_html(number_format($price, 0)); ?> AUD</span>
        <p class="detail-description" id="detailDescription"><?php echo esc_html($product['short'] ?: 'Soul Stone handmade crystal jewelry.'); ?></p>
        <div class="detail-specs">
          <span><b>Theme</b><em id="detailSpecTheme"><?php echo esc_html($product['theme']); ?></em></span>
          <span><b>Main Stone</b><em id="detailSpecStone"><?php echo esc_html($product['stone']); ?></em></span>
          <span><b>Bead Size</b><em id="detailSpecSizes"><?php echo esc_html($product['sizes']); ?></em></span>
          <span><b>Price</b><em id="detailSpecPrice">$<?php echo esc_html(number_format($price, 0)); ?> AUD</em></span>
        </div>
        <p class="detail-description" id="detailLong"><?php echo esc_html($product['long'] ?: 'Final product copy, bead sizing, material notes and delivery details can be edited in the product backend.'); ?></p>
        <div class="detail-actions">
          <?php if (!empty($product['wc_id']) && function_exists('wc_get_cart_url')) : ?>
            <form method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
              <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $product['wc_id']); ?>">
              <input type="hidden" name="quantity" value="1">
              <button class="black-button" id="detailAddCart" type="submit">Add to Cart</button>
            </form>
          <?php else : ?>
            <button class="black-button add-cart" id="detailAddCart" type="button" data-product="<?php echo esc_attr($product['name']); ?>" data-price="<?php echo esc_attr((string) $price); ?>" data-image="<?php echo esc_url($product['image']); ?>" data-theme="<?php echo esc_attr($product['theme']); ?>" data-stone="<?php echo esc_attr($product['stone']); ?>">Add to Cart</button>
          <?php endif; ?>
          <a class="outline-button" href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Customize Similar</a>
          <?php if (!empty($product['edit_url']) && current_user_can('edit_products')) : ?>
            <a class="outline-button" href="<?php echo esc_url($product['edit_url']); ?>">Edit Product</a>
          <?php endif; ?>
        </div>
      </div>
    <?php else : ?>
      <img class="product-detail-image" id="detailImage" src="<?php echo esc_url(soul_stone_theme_asset_url('assets/collection-bracelets.png')); ?>" alt="">
      <div class="product-detail-info">
        <a class="text-link" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Back to All Products</a>
        <span class="eyebrow" id="detailTheme">Soul Stone</span>
        <h1 id="detailTitle">Product not found</h1>
        <span class="detail-price" id="detailPrice">$0 AUD</span>
        <p class="detail-description" id="detailDescription">This product link is not available yet. Please return to All Products.</p>
        <div class="detail-actions">
          <a class="black-button" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">View Products</a>
        </div>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
