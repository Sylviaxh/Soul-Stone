<?php
/* Template Name: Soul Stone Cart */
get_header();

soul_stone_ensure_wc_cart();
$has_wc_cart = function_exists('WC') && WC()->cart;
$cart_items = $has_wc_cart ? WC()->cart->get_cart() : array();
$cart_total = $has_wc_cart ? WC()->cart->get_cart_total() : '$0 AUD';
$checkout_url = function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : '#';
?>
<main>
  <section class="cart-hero">
    <div>
      <span class="eyebrow">Your Selection</span>
      <h1>Cart</h1>
      <p>Review your Soul Stone pieces, custom bracelet details and design previews before checkout.</p>
    </div>
    <div class="cart-hero-summary">
      <span>Estimated total</span>
      <strong id="cartHeroTotal"><?php echo wp_kses_post($cart_total); ?></strong>
      <small><?php echo $has_wc_cart ? 'Connected to WooCommerce cart.' : 'Secure checkout connection coming next.'; ?></small>
    </div>
  </section>

  <section class="section cart-section">
    <article class="card cart-panel">
      <div class="card-body">
        <h2>Your cart</h2>

        <?php if ($has_wc_cart && function_exists('wc_print_notices')) : ?>
          <?php wc_print_notices(); ?>
        <?php endif; ?>

        <div id="cartItems" <?php echo $has_wc_cart && $cart_items ? 'data-server-cart="1"' : ''; ?>>
          <?php if ($has_wc_cart) : ?>
            <?php if (!$cart_items) : ?>
              <p class="empty-cart">Your cart is empty. Start with a piece that matches your intention.</p>
            <?php endif; ?>

            <?php foreach ($cart_items as $cart_item_key => $cart_item) : ?>
              <?php
                $product = $cart_item['data'];
                if (!$product || !$product->exists()) {
                  continue;
                }

                $is_custom_design = !empty($cart_item['soul_stone_custom_design']);
                $product_id = $cart_item['product_id'];
                $catalog_item = $is_custom_design ? array() : soul_stone_product_to_catalog_item(wc_get_product($product_id));
                $product_name = $is_custom_design ? ($cart_item['soul_stone_design_name'] ?? 'Custom Bracelet Design') : $product->get_name();
                $theme = $is_custom_design ? 'Custom Design' : ($catalog_item['theme'] ?? 'Soul Stone');
                $stone = $is_custom_design ? 'Mixed stones and accessories' : ($catalog_item['stone'] ?? 'Crystal');
                $sizes = $is_custom_design ? ($cart_item['soul_stone_design_length'] ?? 'Custom length') : ($catalog_item['sizes'] ?? '6mm / 8mm / 10mm available');
                $image = $catalog_item['image'] ?? soul_stone_product_image_url($product_id);
                $detail_url = $is_custom_design ? soul_stone_page_url('custom-design') : add_query_arg('id', rawurlencode((string) $product_id), soul_stone_page_url('product'));
                $design_items = $is_custom_design ? ($cart_item['soul_stone_design_items'] ?? array()) : array();
                $materials = $is_custom_design ? ($cart_item['soul_stone_design_materials'] ?? '') : $stone;
              ?>
              <div class="cart-swipe">
                <a class="cart-delete" href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>">Delete</a>
                <article class="cart-line">
                  <div class="cart-media">
                    <?php if ($is_custom_design) : ?>
                      <?php echo soul_stone_design_preview_html($design_items, $product_name); ?>
                    <?php else : ?>
                      <a href="<?php echo esc_url($detail_url); ?>">
                        <img class="cart-thumb" src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($product_name); ?>">
                      </a>
                    <?php endif; ?>
                  </div>
                  <div class="cart-info">
                    <span class="cart-kicker"><?php echo esc_html($theme); ?></span>
                    <strong><a href="<?php echo esc_url($detail_url); ?>"><?php echo esc_html($product_name); ?></a></strong>
                    <div class="cart-spec-grid">
                      <div><span>Theme</span><strong><?php echo esc_html($theme); ?></strong></div>
                      <div><span>Main stone</span><strong><?php echo esc_html($stone); ?></strong></div>
                      <div><span>Quantity</span><strong><?php echo esc_html((string) $cart_item['quantity']); ?></strong></div>
                      <div><span>Unit price</span><strong><?php echo wp_kses_post(WC()->cart->get_product_price($product)); ?></strong></div>
                    </div>
                    <div class="cart-material-block">
                      <em>Materials</em>
                      <div class="cart-material-list" aria-label="Materials">
                        <?php if ($is_custom_design && !empty($cart_item['soul_stone_design_id'])) : ?>
                          <span><?php echo esc_html('Design #' . $cart_item['soul_stone_design_id']); ?></span>
                        <?php endif; ?>
                        <span><?php echo esc_html($materials ?: $stone); ?></span>
                        <span><?php echo esc_html($sizes); ?></span>
                      </div>
                    </div>
                  </div>
                  <div class="cart-line-summary">
                    <span>Subtotal</span>
                    <b><?php echo wp_kses_post(WC()->cart->get_product_subtotal($product, $cart_item['quantity'])); ?></b>
                    <a class="cart-remove-inline" href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>">Remove</a>
                  </div>
                </article>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <div class="cart-total">
          <span>Total</span>
          <strong id="cartTotal"><?php echo wp_kses_post($cart_total); ?></strong>
        </div>

        <div class="cart-actions">
          <a class="black-button" href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Continue Shopping</a>
          <?php if ($has_wc_cart && $cart_items) : ?>
            <a class="outline-button" href="<?php echo esc_url($checkout_url); ?>">Checkout</a>
          <?php endif; ?>
        </div>
      </div>
    </article>
  </section>
</main>
<?php get_footer(); ?>
