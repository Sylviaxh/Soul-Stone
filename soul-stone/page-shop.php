<?php
/* Template Name: Soul Stone Shop */
get_header();

$products = soul_stone_frontend_products();
$themes = array_values(array_unique(array_filter(array_map(function ($product) {
  return $product['theme'];
}, $products))));
$stones = array_values(array_unique(array_filter(array_map(function ($product) {
  return $product['stone'];
}, $products))));
sort($themes);
sort($stones);
?>
<main>
  <section class="shop-hero">
    <div>
      <span class="eyebrow">Shop Soul Stone</span>
      <h1>All Products</h1>
      <p>Browse the live Soul Stone catalog. Admins can update names, prices, images and product details from the WordPress product backend.</p>
      <div class="shop-nav" aria-label="Product sections">
        <a href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">All</a>
        <?php foreach (soul_stone_product_sections() as $section_slug => $section_label) : ?>
          <a href="<?php echo esc_url(soul_stone_page_url('shop')); ?>#<?php echo esc_attr($section_slug); ?>"><?php echo esc_html($section_label); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <div class="shop-hero-image" role="img" aria-label="Soul Stone bracelet collection"></div>
  </section>

  <section class="section catalog-section" id="collection-gallery">
    <div class="catalog-head">
      <div>
        <span class="eyebrow">Product Catalog</span>
        <h2 class="section-title">Choose by intention.</h2>
      </div>
      <p>Use the filters to browse products by theme, main stone and price range.</p>
    </div>

    <div class="filter-panel" aria-label="Product filters">
      <div class="filter-field">
        <label for="themeFilter">Theme</label>
        <select id="themeFilter">
          <option value="all">All themes</option>
          <?php foreach ($themes as $theme) : ?>
            <option value="<?php echo esc_attr($theme); ?>"><?php echo esc_html($theme); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-field">
        <label for="stoneFilter">Main stone</label>
        <select id="stoneFilter">
          <option value="all">All stones</option>
          <?php foreach ($stones as $stone) : ?>
            <option value="<?php echo esc_attr($stone); ?>"><?php echo esc_html($stone); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="filter-field">
        <label for="priceFilter">Price</label>
        <select id="priceFilter">
          <option value="all">All prices</option>
          <option value="0-49">Under $50</option>
          <option value="50-69">$50 - $69</option>
          <option value="70-999">$70+</option>
        </select>
      </div>
      <button class="outline-button" type="button" id="clearFilters">Clear Filters</button>
    </div>

    <div class="product-list-grid" id="productGrid">
      <?php foreach ($products as $product) : ?>
        <?php
          $detail_url = add_query_arg('id', rawurlencode((string) $product['id']), soul_stone_page_url('product'));
          $price = (float) $product['price'];
        ?>
        <article class="shop-product-card" data-theme="<?php echo esc_attr($product['theme']); ?>" data-stone="<?php echo esc_attr($product['stone']); ?>" data-price="<?php echo esc_attr((string) $price); ?>" data-category="<?php echo esc_attr($product['section']); ?>">
          <a class="shop-product-link" href="<?php echo esc_url($detail_url); ?>">
            <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>">
            <div class="shop-product-body">
              <span class="product-meta"><?php echo esc_html($product['theme']); ?></span>
              <h3><?php echo esc_html($product['name']); ?></h3>
              <p><?php echo esc_html($product['short'] ?: 'Soul Stone handmade crystal jewelry.'); ?></p>
              <span class="product-price">$<?php echo esc_html(number_format($price, 0)); ?> AUD</span>
            </div>
          </a>
          <div class="shop-product-body">
            <?php if (!empty($product['wc_id']) && function_exists('wc_get_cart_url')) : ?>
              <form method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr((string) $product['wc_id']); ?>">
                <input type="hidden" name="quantity" value="1">
                <button class="black-button" type="submit">Add to Cart</button>
              </form>
            <?php else : ?>
              <button class="black-button add-cart" type="button" data-product="<?php echo esc_attr($product['name']); ?>" data-price="<?php echo esc_attr((string) $price); ?>" data-image="<?php echo esc_url($product['image']); ?>" data-theme="<?php echo esc_attr($product['theme']); ?>" data-stone="<?php echo esc_attr($product['stone']); ?>">Add to Cart</button>
            <?php endif; ?>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <p class="catalog-empty" id="catalogEmpty">No products match these filters yet.</p>
    <div class="pagination" id="catalogPagination" aria-label="Product pagination"></div>
  </section>
</main>
<?php get_footer(); ?>
