<?php
if (
  (function_exists('is_shop') && is_shop())
  || (function_exists('is_product_taxonomy') && is_product_taxonomy())
  || is_post_type_archive('product')
) {
  require get_template_directory() . '/page-shop.php';
  return;
}

get_header();

if (function_exists('woocommerce_content')) {
  woocommerce_content();
}

get_footer();
