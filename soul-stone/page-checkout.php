<?php
/* Template Name: Soul Stone Checkout */
get_header();

soul_stone_ensure_wc_cart();
$has_checkout = function_exists('woocommerce_checkout');
$cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>
<main>
  <section class="checkout-hero">
    <div>
      <span class="eyebrow">Secure Checkout</span>
      <h1>Complete your order</h1>
      <p>Confirm your pieces, shipping details and payment method. Custom bracelet information will stay attached to your order for the studio.</p>
    </div>
    <div class="checkout-hero-note">
      <span>Items</span>
      <strong><?php echo esc_html((string) $cart_count); ?></strong>
      <small>Powered by WooCommerce checkout.</small>
    </div>
  </section>

  <section class="section checkout-section">
    <article class="checkout-panel">
      <?php if ($has_checkout) : ?>
        <?php woocommerce_checkout(); ?>
      <?php else : ?>
        <h2>Checkout is not ready yet.</h2>
        <p>Install and activate WooCommerce to enable checkout, orders and payment settings.</p>
        <a class="black-button" href="<?php echo esc_url(soul_stone_page_url('cart')); ?>">Back to cart</a>
      <?php endif; ?>
    </article>
  </section>
</main>
<?php get_footer(); ?>
