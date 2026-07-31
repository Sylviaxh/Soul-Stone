<?php
function soul_stone_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'soul_stone_setup');

function soul_stone_assets() {
  wp_enqueue_style('soul-stone-style', get_stylesheet_uri(), array(), '1.0.18');
  wp_enqueue_script('soul-stone-script', get_template_directory_uri() . '/assets/js/script.js', array(), '1.0.18', true);
  wp_add_inline_script('soul-stone-script', 'window.SOUL_STONE_THEME_URI = ' . wp_json_encode(get_template_directory_uri()) . ';', 'before');
  wp_add_inline_script('soul-stone-script', 'window.SOUL_STONE_WC_CART_COUNT = ' . wp_json_encode(soul_stone_wc_cart_count()) . ';', 'before');
  wp_add_inline_script('soul-stone-script', 'window.SOUL_STONE_DESIGN_SAVE = ' . wp_json_encode(array(
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce' => wp_create_nonce('soul_stone_save_custom_design'),
    'isLoggedIn' => is_user_logged_in(),
    'loginUrl' => soul_stone_page_url('login'),
    'accountUrl' => soul_stone_page_url('account'),
    'cartUrl' => soul_stone_page_url('cart'),
  )) . ';', 'before');
}
add_action('wp_enqueue_scripts', 'soul_stone_assets');

function soul_stone_ensure_wc_cart() {
  if (function_exists('WC') && function_exists('wc_load_cart') && !WC()->cart) {
    wc_load_cart();
  }
}

function soul_stone_wc_cart_count() {
  soul_stone_ensure_wc_cart();
  return function_exists('WC') && WC()->cart ? (int) WC()->cart->get_cart_contents_count() : 0;
}

function soul_stone_page_url($slug = '') {
  $slug = trim((string) $slug, '/');
  return $slug === '' ? home_url('/') : home_url('/' . $slug . '/');
}

function soul_stone_create_default_pages() {
  $pages = array(
    'products' => 'Products',
    'shop' => 'Shop',
    'product' => 'Product',
    'materials' => 'Materials',
    'custom-design' => 'Custom Design',
    'about' => 'About Us',
    'login' => 'Login',
    'sign-up' => 'Sign Up',
    'account' => 'Account',
    'cart' => 'Cart',
    'checkout' => 'Checkout',
  );

  foreach ($pages as $slug => $title) {
    if (get_page_by_path($slug)) {
      continue;
    }

    wp_insert_post(array(
      'post_title' => $title,
      'post_name' => $slug,
      'post_status' => 'publish',
      'post_type' => 'page',
      'post_content' => '',
    ));
  }
}
add_action('after_switch_theme', 'soul_stone_create_default_pages');
add_action('init', 'soul_stone_create_default_pages');

function soul_stone_assign_woocommerce_pages() {
  if (!class_exists('WooCommerce')) {
    return;
  }

  update_option('woocommerce_coming_soon', 'no');
  update_option('woocommerce_store_pages_only', 'no');

  $page_options = array(
    'cart' => 'woocommerce_cart_page_id',
    'checkout' => 'woocommerce_checkout_page_id',
    'shop' => 'woocommerce_shop_page_id',
  );

  foreach ($page_options as $slug => $option_name) {
    $page = get_page_by_path($slug);
    if (!$page) {
      continue;
    }

    $current_id = absint(get_option($option_name));
    if (!$current_id || !get_post($current_id)) {
      update_option($option_name, $page->ID);
    }
  }
}
add_action('after_switch_theme', 'soul_stone_assign_woocommerce_pages');
add_action('init', 'soul_stone_assign_woocommerce_pages', 20);

function soul_stone_register_custom_designs() {
  register_post_type('soul_custom_design', array(
    'labels' => array(
      'name' => 'Custom Bracelet Orders',
      'singular_name' => 'Custom Bracelet Order',
    ),
    'public' => false,
    'show_ui' => false,
    'supports' => array('title', 'author'),
    'capability_type' => 'post',
  ));
}
add_action('init', 'soul_stone_register_custom_designs');

function soul_stone_auth_redirect($status, $action = 'login') {
  $target_slug = $action === 'register' ? 'sign-up' : 'login';

  wp_safe_redirect(add_query_arg(array(
    'auth_status' => sanitize_key($status),
    'auth_action' => sanitize_key($action),
  ), soul_stone_page_url($target_slug)));
  exit;
}

function soul_stone_home_redirect($status = '') {
  wp_safe_redirect(home_url('/'));
  exit;
}

function soul_stone_password_is_valid($password) {
  return is_string($password) && preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,11}$/', $password);
}

function soul_stone_customer_role() {
  return get_role('customer') ? 'customer' : 'subscriber';
}

function soul_stone_handle_register() {
  if (!isset($_POST['soul_stone_register_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['soul_stone_register_nonce'])), 'soul_stone_register_action')) {
    soul_stone_auth_redirect('security_failed', 'register');
  }

  $username = isset($_POST['username']) ? sanitize_user(wp_unslash($_POST['username']), true) : '';
  $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
  $password = isset($_POST['password']) ? (string) wp_unslash($_POST['password']) : '';
  $confirm_password = isset($_POST['confirm_password']) ? (string) wp_unslash($_POST['confirm_password']) : '';

  if ($username === '' || $email === '' || $password === '' || $confirm_password === '') {
    soul_stone_auth_redirect('missing_fields', 'register');
  }

  if (!is_email($email)) {
    soul_stone_auth_redirect('invalid_email', 'register');
  }

  if (!soul_stone_password_is_valid($password)) {
    soul_stone_auth_redirect('password_policy', 'register');
  }

  if ($password !== $confirm_password) {
    soul_stone_auth_redirect('password_mismatch', 'register');
  }

  if (username_exists($username)) {
    soul_stone_auth_redirect('user_exists', 'register');
  }

  if (email_exists($email)) {
    soul_stone_auth_redirect('email_exists', 'register');
  }

  $user_id = wp_create_user($username, $password, $email);

  if (is_wp_error($user_id)) {
    soul_stone_auth_redirect('register_failed', 'register');
  }

  $user = new WP_User($user_id);
  $user->set_role(soul_stone_customer_role());

  wp_set_current_user($user_id);
  wp_set_auth_cookie($user_id);
  soul_stone_home_redirect('registered');
}
add_action('admin_post_nopriv_soul_stone_register', 'soul_stone_handle_register');
add_action('admin_post_soul_stone_register', 'soul_stone_handle_register');

function soul_stone_handle_login() {
  if (!isset($_POST['soul_stone_login_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['soul_stone_login_nonce'])), 'soul_stone_login_action')) {
    soul_stone_auth_redirect('security_failed', 'login');
  }

  $login = isset($_POST['log']) ? sanitize_text_field(wp_unslash($_POST['log'])) : '';
  $password = isset($_POST['pwd']) ? (string) wp_unslash($_POST['pwd']) : '';

  if ($login === '' || $password === '') {
    soul_stone_auth_redirect('missing_fields', 'login');
  }

  if (is_email($login)) {
    $user = get_user_by('email', $login);
    $login = $user ? $user->user_login : $login;
  }

  $signed_in = wp_signon(array(
    'user_login' => $login,
    'user_password' => $password,
    'remember' => !empty($_POST['remember']),
  ), is_ssl());

  if (is_wp_error($signed_in)) {
    soul_stone_auth_redirect('login_failed', 'login');
  }

  soul_stone_home_redirect('logged_in');
}
add_action('admin_post_nopriv_soul_stone_login', 'soul_stone_handle_login');
add_action('admin_post_soul_stone_login', 'soul_stone_handle_login');

function soul_stone_handle_logout() {
  if (!isset($_POST['soul_stone_logout_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['soul_stone_logout_nonce'])), 'soul_stone_logout_action')) {
    soul_stone_auth_redirect('security_failed', 'account');
  }

  wp_logout();
  soul_stone_auth_redirect('logged_out', 'login');
}
add_action('admin_post_soul_stone_logout', 'soul_stone_handle_logout');

function soul_stone_account_redirect($status) {
  wp_safe_redirect(add_query_arg('account_status', sanitize_key($status), soul_stone_page_url('account')));
  exit;
}

function soul_stone_require_logged_in_account_action($nonce_name, $nonce_action) {
  if (!is_user_logged_in()) {
    soul_stone_auth_redirect('security_failed', 'login');
  }

  if (!isset($_POST[$nonce_name]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_name])), $nonce_action)) {
    soul_stone_account_redirect('security_failed');
  }
}

function soul_stone_handle_profile_update() {
  soul_stone_require_logged_in_account_action('soul_stone_profile_nonce', 'soul_stone_profile_action');

  $user_id = get_current_user_id();
  $current_user = get_userdata($user_id);
  $first_name = sanitize_text_field(wp_unslash($_POST['first_name'] ?? ''));
  $last_name = sanitize_text_field(wp_unslash($_POST['last_name'] ?? ''));
  $display_name = sanitize_text_field(wp_unslash($_POST['display_name'] ?? ''));
  $email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
  $phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));

  if ($display_name === '' || $email === '') {
    soul_stone_account_redirect('missing_fields');
  }

  if (!is_email($email)) {
    soul_stone_account_redirect('invalid_email');
  }

  $existing_user_id = email_exists($email);
  if ($existing_user_id && (int) $existing_user_id !== $user_id) {
    soul_stone_account_redirect('email_exists');
  }

  $result = wp_update_user(array(
    'ID' => $user_id,
    'user_email' => $email,
    'display_name' => $display_name,
    'first_name' => $first_name,
    'last_name' => $last_name,
  ));

  if (is_wp_error($result)) {
    soul_stone_account_redirect('profile_failed');
  }

  update_user_meta($user_id, 'billing_phone', $phone);
  update_user_meta($user_id, 'shipping_first_name', $first_name);
  update_user_meta($user_id, 'shipping_last_name', $last_name);
  update_user_meta($user_id, 'billing_first_name', $first_name);
  update_user_meta($user_id, 'billing_last_name', $last_name);

  if ($current_user && $current_user->user_email !== $email) {
    clean_user_cache($user_id);
  }

  soul_stone_account_redirect('profile_updated');
}
add_action('admin_post_soul_stone_update_profile', 'soul_stone_handle_profile_update');

function soul_stone_address_fields() {
  return array('first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', 'phone');
}

function soul_stone_handle_address_update() {
  soul_stone_require_logged_in_account_action('soul_stone_address_nonce', 'soul_stone_address_action');

  $user_id = get_current_user_id();
  $address_types = array('billing', 'shipping');

  foreach ($address_types as $type) {
    foreach (soul_stone_address_fields() as $field) {
      if ($type === 'shipping' && $field === 'phone') {
        continue;
      }

      $key = $type . '_' . $field;
      $value = sanitize_text_field(wp_unslash($_POST[$key] ?? ''));
      update_user_meta($user_id, $key, $value);
    }
  }

  soul_stone_account_redirect('address_updated');
}
add_action('admin_post_soul_stone_update_addresses', 'soul_stone_handle_address_update');

function soul_stone_handle_password_update() {
  soul_stone_require_logged_in_account_action('soul_stone_password_nonce', 'soul_stone_password_action');

  $user_id = get_current_user_id();
  $user = get_userdata($user_id);
  $current_password = (string) wp_unslash($_POST['current_password'] ?? '');
  $new_password = (string) wp_unslash($_POST['new_password'] ?? '');
  $confirm_password = (string) wp_unslash($_POST['confirm_password'] ?? '');

  if ($current_password === '' || $new_password === '' || $confirm_password === '') {
    soul_stone_account_redirect('missing_fields');
  }

  if (!$user || !wp_check_password($current_password, $user->user_pass, $user_id)) {
    soul_stone_account_redirect('current_password_failed');
  }

  if (!soul_stone_password_is_valid($new_password)) {
    soul_stone_account_redirect('password_policy');
  }

  if ($new_password !== $confirm_password) {
    soul_stone_account_redirect('password_mismatch');
  }

  wp_set_password($new_password, $user_id);
  wp_set_current_user($user_id);
  wp_set_auth_cookie($user_id);

  soul_stone_account_redirect('password_updated');
}
add_action('admin_post_soul_stone_update_password', 'soul_stone_handle_password_update');

function soul_stone_auth_message($status) {
  $messages = array(
    'missing_fields' => array('error', 'Please complete all required fields.'),
    'invalid_email' => array('error', 'Please enter a valid email address.'),
    'password_policy' => array('error', 'Password must be 8-11 characters and include letters and numbers.'),
    'password_mismatch' => array('error', 'The two passwords do not match.'),
    'user_exists' => array('error', 'That username is already registered.'),
    'email_exists' => array('error', 'That email is already registered.'),
    'register_failed' => array('error', 'We could not create the account. Please try again.'),
    'login_failed' => array('error', 'The login details did not match an account.'),
    'security_failed' => array('error', 'This form expired. Please try again.'),
    'registered' => array('success', 'Account created. You are signed in.'),
    'logged_in' => array('success', 'Welcome back. You are signed in.'),
    'logged_out' => array('success', 'You have been signed out.'),
    'profile_updated' => array('success', 'Profile details updated.'),
    'address_updated' => array('success', 'Address details updated.'),
    'password_updated' => array('success', 'Password updated.'),
    'profile_failed' => array('error', 'Profile details could not be updated.'),
    'current_password_failed' => array('error', 'Current password is incorrect.'),
  );

  $status = sanitize_key($status);
  return isset($messages[$status]) ? $messages[$status] : null;
}

function soul_stone_product_sections() {
  return array(
    'collection-gallery' => 'Collection Gallery',
    'new-arrivals' => 'New Arrivals',
    'gift-ideas' => 'Gift Ideas',
  );
}

function soul_stone_product_themes() {
  return array('Transformation', 'Self Love', 'Protection', 'Focus', 'New Beginning', 'Gift');
}

function soul_stone_product_stones() {
  return array('Moonstone', 'Rose Quartz', 'Obsidian', 'Amethyst', 'Clear Quartz', 'Aquamarine', "Cat's Eye", 'Silver Obsidian');
}

function soul_stone_theme_asset_url($asset) {
  return get_template_directory_uri() . '/' . ltrim($asset, '/');
}

function soul_stone_default_products() {
  return array(
    array(
      'slug' => 'transformation-moonstone-bracelet',
      'name' => 'Transformation Moonstone Bracelet',
      'price' => 49,
      'theme' => 'Transformation',
      'stone' => 'Moonstone',
      'section' => 'collection-gallery',
      'image' => 'assets/collection-bracelets.png',
      'short' => 'Moonstone, amethyst and clear quartz with a celestial charm.',
      'long' => 'A soft luminous bracelet designed for personal growth and inner renewal. The placeholder composition pairs moonstone for new chapters, amethyst for calm reflection, and clear quartz for clarity of intention.',
    ),
    array(
      'slug' => 'self-love-rose-quartz-bracelet',
      'name' => 'Self Love Rose Quartz Bracelet',
      'price' => 45,
      'theme' => 'Self Love',
      'stone' => 'Rose Quartz',
      'section' => 'collection-gallery',
      'image' => 'assets/collection-bracelets.png',
      'short' => 'Soft rose quartz and moonstone tones for tenderness.',
      'long' => 'A gentle everyday bracelet for heart-led confidence and softness. Rose quartz is the main placeholder stone, supported by pale accents for a warm, delicate Soul Stone feeling.',
    ),
    array(
      'slug' => 'protection-obsidian-bracelet',
      'name' => 'Protection Obsidian Bracelet',
      'price' => 48,
      'theme' => 'Protection',
      'stone' => 'Obsidian',
      'section' => 'collection-gallery',
      'image' => 'assets/obsidian-preview.png',
      'short' => 'Obsidian and clear quartz for grounding and boundaries.',
      'long' => 'A darker protection-led piece with obsidian as the central stone. This default product description can later be replaced with exact bead sizes, charm materials, and care details.',
    ),
    array(
      'slug' => 'focus-amethyst-bracelet',
      'name' => 'Focus Amethyst Bracelet',
      'price' => 46,
      'theme' => 'Focus',
      'stone' => 'Amethyst',
      'section' => 'collection-gallery',
      'image' => 'assets/collection-bracelets.png',
      'short' => 'Amethyst and clear quartz for study and clarity.',
      'long' => 'A calm purple-toned bracelet for focus, studying, and mental quiet. The current image and text are placeholders, ready for your real product photography.',
    ),
    array(
      'slug' => 'new-beginning-moonstone-bracelet',
      'name' => 'New Beginning Moonstone Bracelet',
      'price' => 49,
      'theme' => 'New Beginning',
      'stone' => 'Moonstone',
      'section' => 'collection-gallery',
      'image' => 'assets/hero-bracelet.png',
      'short' => 'A luminous piece for graduation, moving and fresh starts.',
      'long' => 'A bright moonstone-led bracelet for new work, moving home, graduation, or any fresh chapter. The detail page is structured so you can add measurements and shipping notes later.',
    ),
    array(
      'slug' => 'clear-quartz-charm-bracelet',
      'name' => 'Clear Quartz Charm Bracelet',
      'price' => 42,
      'theme' => 'Focus',
      'stone' => 'Clear Quartz',
      'section' => 'collection-gallery',
      'image' => 'assets/stone-clear-quartz.png',
      'short' => 'A minimal bracelet for clarity and intention stacking.',
      'long' => 'A clean clear quartz design for simple daily wear. It works as a placeholder for minimalist pieces and can later be paired with charm options or metal finishes.',
    ),
    array(
      'slug' => 'aquamarine-calm-bracelet',
      'name' => 'Aquamarine Calm Bracelet',
      'price' => 52,
      'theme' => 'New Beginning',
      'stone' => 'Aquamarine',
      'section' => 'new-arrivals',
      'image' => 'assets/stone-aquamarine.png',
      'short' => 'Pale aquamarine for quiet courage and communication.',
      'long' => 'A blue-toned bracelet direction for calm communication and gentle courage. The placeholder layout lets customers understand the mood before final photography is added.',
    ),
    array(
      'slug' => 'pink-catseye-glow-bracelet',
      'name' => "Pink Cat's Eye Glow Bracelet",
      'price' => 44,
      'theme' => 'Self Love',
      'stone' => "Cat's Eye",
      'section' => 'new-arrivals',
      'image' => 'assets/stone-pink-catseye.png',
      'short' => "Pink cat's eye beads with a silky confident glow.",
      'long' => "A luminous pink cat's eye bracelet for softness with a little shine. This product detail can later include bead finish, elastic size, and packaging options.",
    ),
    array(
      'slug' => 'silver-obsidian-insight-bracelet',
      'name' => 'Silver Obsidian Insight Bracelet',
      'price' => 54,
      'theme' => 'Protection',
      'stone' => 'Silver Obsidian',
      'section' => 'new-arrivals',
      'image' => 'assets/stone-silver-obsidian.png',
      'short' => 'Silver obsidian for reflection and inner strength.',
      'long' => 'A reflective silver obsidian piece for grounding, insight, and steadiness. The default content keeps the catalog usable while leaving space for your final story and materials.',
    ),
    array(
      'slug' => 'new-chapter-gift-set',
      'name' => 'New Chapter Gift Set',
      'price' => 68,
      'theme' => 'Gift',
      'stone' => 'Moonstone',
      'section' => 'gift-ideas',
      'image' => 'assets/hero-bracelet.png',
      'short' => 'A moonstone bracelet with a default intention card.',
      'long' => 'A gift-ready placeholder set for birthdays, graduations, farewell gifts, or fresh starts. The future version can include packaging photos, card copy, and personalization fields.',
    ),
    array(
      'slug' => 'custom-intention-gift-card',
      'name' => 'Custom Intention Gift Card',
      'price' => 75,
      'theme' => 'Gift',
      'stone' => 'Clear Quartz',
      'section' => 'gift-ideas',
      'image' => 'assets/about-studio.png',
      'short' => 'A custom design option for a personal intention-led gift.',
      'long' => 'A flexible custom design placeholder for customers who want a more personal Soul Stone piece. Later this can connect directly to your custom design form.',
    ),
    array(
      'slug' => 'friendship-intention-pair',
      'name' => 'Friendship Intention Pair',
      'price' => 88,
      'theme' => 'Gift',
      'stone' => 'Rose Quartz',
      'section' => 'gift-ideas',
      'image' => 'assets/collection-bracelets.png',
      'short' => 'Two coordinated bracelets for friendship or shared milestones.',
      'long' => 'A paired bracelet set for friends, sisters, partners, or shared intentions. The placeholder price and story are ready to be replaced once the final set is confirmed.',
    ),
  );
}

function soul_stone_seed_default_products($force = false) {
  if (!class_exists('WC_Product_Simple')) {
    return 0;
  }

  if (!$force && get_option('soul_stone_default_products_seeded')) {
    return 0;
  }

  $created = 0;
  foreach (soul_stone_default_products() as $item) {
    $sku = 'soul-stone-' . $item['slug'];
    $product_id = function_exists('wc_get_product_id_by_sku') ? wc_get_product_id_by_sku($sku) : 0;

    if ($product_id) {
      continue;
    }

    $product = new WC_Product_Simple();
    $product->set_name($item['name']);
    $product->set_slug($item['slug']);
    $product->set_sku($sku);
    $product->set_regular_price((string) $item['price']);
    $product->set_price((string) $item['price']);
    $product->set_short_description($item['short']);
    $product->set_description($item['long']);
    $product->set_status('publish');
    $product->set_catalog_visibility('visible');
    $product->set_manage_stock(false);
    $product->set_stock_status('instock');
    $product_id = $product->save();

    update_post_meta($product_id, '_soul_stone_theme', $item['theme']);
    update_post_meta($product_id, '_soul_stone_main_stone', $item['stone']);
    update_post_meta($product_id, '_soul_stone_section', $item['section']);
    update_post_meta($product_id, '_soul_stone_bead_sizes', '6mm / 8mm / 10mm available');
    update_post_meta($product_id, '_soul_stone_image_url', soul_stone_theme_asset_url($item['image']));
    $created += 1;
  }

  update_option('soul_stone_default_products_seeded', time());
  return $created;
}
add_action('admin_init', 'soul_stone_seed_default_products');

function soul_stone_product_image_url($product_id) {
  $image = get_the_post_thumbnail_url($product_id, 'large');
  if ($image) {
    return $image;
  }

  $fallback = get_post_meta($product_id, '_soul_stone_image_url', true);
  return $fallback ? esc_url_raw($fallback) : soul_stone_theme_asset_url('assets/collection-bracelets.png');
}

function soul_stone_product_to_catalog_item($product) {
  if (!$product || !is_a($product, 'WC_Product')) {
    return null;
  }

  $product_id = $product->get_id();
  if (get_post_meta($product_id, '_soul_stone_custom_design_product', true) === 'yes' || absint(get_option('soul_stone_custom_design_product_id')) === $product_id) {
    return null;
  }

  if (method_exists($product, 'get_catalog_visibility') && $product->get_catalog_visibility() === 'hidden') {
    return null;
  }

  $price = $product->get_price();
  $theme = get_post_meta($product_id, '_soul_stone_theme', true) ?: 'Soul Stone';
  $stone = get_post_meta($product_id, '_soul_stone_main_stone', true) ?: 'Crystal';
  $section = get_post_meta($product_id, '_soul_stone_section', true) ?: 'collection-gallery';
  $sections = soul_stone_product_sections();

  if (!isset($sections[$section])) {
    $section = 'collection-gallery';
  }

  return array(
    'id' => $product_id,
    'wc_id' => $product_id,
    'slug' => get_post_field('post_name', $product_id),
    'name' => $product->get_name(),
    'price' => $price === '' ? 0 : (float) $price,
    'theme' => $theme,
    'stone' => $stone,
    'section' => $section,
    'image' => soul_stone_product_image_url($product_id),
    'short' => wp_strip_all_tags($product->get_short_description()),
    'long' => wp_strip_all_tags($product->get_description()),
    'sizes' => get_post_meta($product_id, '_soul_stone_bead_sizes', true) ?: '6mm / 8mm / 10mm available',
    'edit_url' => get_edit_post_link($product_id, ''),
  );
}

function soul_stone_catalog_products() {
  if (!function_exists('wc_get_products')) {
    return array();
  }

  $products = wc_get_products(array(
    'status' => array('publish'),
    'limit' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
  ));

  return array_values(array_filter(array_map('soul_stone_product_to_catalog_item', $products)));
}

function soul_stone_frontend_products() {
  $products = soul_stone_catalog_products();
  if ($products) {
    return $products;
  }

  return array_map(function ($item) {
    $item['id'] = $item['slug'];
    $item['wc_id'] = 0;
    $item['image'] = soul_stone_theme_asset_url($item['image']);
    $item['sizes'] = '6mm / 8mm / 10mm available';
    return $item;
  }, soul_stone_default_products());
}

function soul_stone_find_frontend_product($id) {
  $id = sanitize_text_field((string) $id);

  if ($id !== '' && function_exists('wc_get_product')) {
    $product = is_numeric($id) ? wc_get_product((int) $id) : null;

    if (!$product) {
      $post = get_page_by_path($id, OBJECT, 'product');
      $product = $post ? wc_get_product($post->ID) : null;
    }

    if ($product && $product->get_status() === 'publish') {
      return soul_stone_product_to_catalog_item($product);
    }
  }

  foreach (soul_stone_frontend_products() as $product) {
    if ((string) $product['id'] === $id || $product['slug'] === $id) {
      return $product;
    }
  }

  return null;
}

function soul_stone_admin_product_fields() {
  if (!function_exists('woocommerce_wp_select') || !function_exists('woocommerce_wp_text_input')) {
    return;
  }

  echo '<div class="options_group">';
  woocommerce_wp_select(array(
    'id' => '_soul_stone_theme',
    'label' => 'Soul Stone Theme',
    'options' => array_combine(soul_stone_product_themes(), soul_stone_product_themes()),
    'desc_tip' => true,
    'description' => 'Used by the shop filters and product cards.',
  ));
  woocommerce_wp_select(array(
    'id' => '_soul_stone_main_stone',
    'label' => 'Main Stone',
    'options' => array_combine(soul_stone_product_stones(), soul_stone_product_stones()),
    'desc_tip' => true,
    'description' => 'Used by the shop filters and product detail page.',
  ));
  woocommerce_wp_select(array(
    'id' => '_soul_stone_section',
    'label' => 'Shop Section',
    'options' => soul_stone_product_sections(),
    'desc_tip' => true,
    'description' => 'Controls Collection Gallery, New Arrivals, or Gift Ideas.',
  ));
  woocommerce_wp_text_input(array(
    'id' => '_soul_stone_bead_sizes',
    'label' => 'Bead Sizes',
    'placeholder' => '6mm / 8mm / 10mm available',
  ));
  woocommerce_wp_text_input(array(
    'id' => '_soul_stone_image_url',
    'label' => 'Fallback Image URL',
    'description' => 'Optional. Product image is preferred; this is used when no featured image is set.',
    'desc_tip' => true,
  ));
  echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'soul_stone_admin_product_fields');

function soul_stone_save_admin_product_fields($product) {
  $fields = array('_soul_stone_theme', '_soul_stone_main_stone', '_soul_stone_section', '_soul_stone_bead_sizes', '_soul_stone_image_url');

  foreach ($fields as $field) {
    if (!isset($_POST[$field])) {
      continue;
    }

    $value = $field === '_soul_stone_image_url'
      ? esc_url_raw(wp_unslash($_POST[$field]))
      : sanitize_text_field(wp_unslash($_POST[$field]));
    $product->update_meta_data($field, $value);
  }
}
add_action('woocommerce_admin_process_product_object', 'soul_stone_save_admin_product_fields');

function soul_stone_product_admin_columns($columns) {
  $columns['soul_stone_theme'] = 'Theme';
  $columns['soul_stone_stone'] = 'Main Stone';
  $columns['soul_stone_section'] = 'Soul Section';
  return $columns;
}
add_filter('manage_edit-product_columns', 'soul_stone_product_admin_columns');

function soul_stone_product_admin_column_content($column, $post_id) {
  if ($column === 'soul_stone_theme') {
    echo esc_html(get_post_meta($post_id, '_soul_stone_theme', true) ?: '-');
  }

  if ($column === 'soul_stone_stone') {
    echo esc_html(get_post_meta($post_id, '_soul_stone_main_stone', true) ?: '-');
  }

  if ($column === 'soul_stone_section') {
    $sections = soul_stone_product_sections();
    $section = get_post_meta($post_id, '_soul_stone_section', true);
    echo esc_html($sections[$section] ?? '-');
  }
}
add_action('manage_product_posts_custom_column', 'soul_stone_product_admin_column_content', 10, 2);

function soul_stone_admin_menu() {
  $capability = current_user_can('manage_woocommerce') ? 'manage_woocommerce' : 'manage_options';
  add_menu_page('Soul Stone Products', 'Soul Stone Products', $capability, 'soul-stone-products', 'soul_stone_products_admin_page', 'dashicons-products', 56);
  add_submenu_page('soul-stone-products', 'Custom Bracelet Orders', 'Custom Bracelet Orders', $capability, 'soul-stone-custom-orders', 'soul_stone_custom_orders_admin_page');
}
add_action('admin_menu', 'soul_stone_admin_menu');

function soul_stone_products_admin_page() {
  if (!current_user_can('manage_options') && !current_user_can('manage_woocommerce')) {
    wp_die(esc_html__('You do not have permission to manage products.', 'soul-stone'));
  }

  $products = function_exists('wc_get_products') ? wc_get_products(array('status' => array('publish', 'draft'), 'limit' => -1)) : array();
  ?>
  <div class="wrap">
    <h1>Soul Stone Products</h1>
    <?php if (!class_exists('WooCommerce')) : ?>
      <div class="notice notice-error"><p>WooCommerce is required for product management.</p></div>
    <?php else : ?>
      <?php if (isset($_GET['seeded'])) : ?>
        <div class="notice notice-success"><p><?php echo esc_html((int) $_GET['seeded']); ?> default products imported. Existing products were skipped.</p></div>
      <?php endif; ?>
      <p>Manage product names, prices, images, descriptions, stock and Soul Stone display fields from WooCommerce products.</p>
      <p>
        <a class="button button-primary" href="<?php echo esc_url(admin_url('post-new.php?post_type=product')); ?>">Add New Product</a>
        <a class="button" href="<?php echo esc_url(admin_url('edit.php?post_type=product')); ?>">Open WooCommerce Products</a>
        <a class="button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=soul_stone_seed_products'), 'soul_stone_seed_products')); ?>">Import Default Products</a>
      </p>
      <table class="widefat striped">
        <thead><tr><th>Product</th><th>Price</th><th>Theme</th><th>Main Stone</th><th>Section</th><th>Status</th></tr></thead>
        <tbody>
          <?php if (!$products) : ?>
            <tr><td colspan="6">No products yet. Import the defaults or add your first product.</td></tr>
          <?php endif; ?>
          <?php foreach ($products as $product) : $item = soul_stone_product_to_catalog_item($product); ?>
            <tr>
              <td><a href="<?php echo esc_url(get_edit_post_link($product->get_id())); ?>"><?php echo esc_html($product->get_name()); ?></a></td>
              <td><?php echo esc_html('$' . $item['price'] . ' AUD'); ?></td>
              <td><?php echo esc_html($item['theme']); ?></td>
              <td><?php echo esc_html($item['stone']); ?></td>
              <td><?php echo esc_html(soul_stone_product_sections()[$item['section']] ?? $item['section']); ?></td>
              <td><?php echo esc_html(ucfirst($product->get_status())); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
  <?php
}

function soul_stone_handle_seed_products() {
  if (!current_user_can('manage_options') && !current_user_can('manage_woocommerce')) {
    wp_die(esc_html__('You do not have permission to manage products.', 'soul-stone'));
  }

  check_admin_referer('soul_stone_seed_products');
  delete_option('soul_stone_default_products_seeded');
  $created = soul_stone_seed_default_products(true);
  wp_safe_redirect(add_query_arg('seeded', (string) $created, admin_url('admin.php?page=soul-stone-products')));
  exit;
}
add_action('admin_post_soul_stone_seed_products', 'soul_stone_handle_seed_products');

function soul_stone_sanitize_design_items($raw_items) {
  $items = json_decode((string) $raw_items, true);
  if (!is_array($items)) {
    return array();
  }

  return array_values(array_map(function ($item) {
    return array(
      'type' => sanitize_key($item['type'] ?? ''),
      'name' => sanitize_text_field($item['name'] ?? ''),
      'zh' => sanitize_text_field($item['zh'] ?? ''),
      'color' => sanitize_hex_color($item['color'] ?? '') ?: '',
      'size' => isset($item['size']) ? (float) $item['size'] : 0,
      'symbol' => sanitize_text_field($item['symbol'] ?? ''),
      'image' => esc_url_raw($item['image'] ?? ''),
      'manualAngle' => isset($item['manualAngle']) && is_numeric($item['manualAngle']) ? (float) $item['manualAngle'] : null,
    );
  }, $items));
}

function soul_stone_custom_design_product_id() {
  if (!class_exists('WC_Product_Simple')) {
    return 0;
  }

  $product_id = absint(get_option('soul_stone_custom_design_product_id'));
  $product = $product_id ? wc_get_product($product_id) : null;

  if ($product) {
    return $product_id;
  }

  $product = new WC_Product_Simple();
  $product->set_name('Custom Bracelet Design');
  $product->set_status('publish');
  $product->set_catalog_visibility('hidden');
  $product->set_regular_price('10');
  $product->set_price('10');
  $product->set_sold_individually(false);
  $product->set_virtual(false);
  $product->set_manage_stock(false);
  $product_id = $product->save();

  if ($product_id) {
    update_post_meta($product_id, '_soul_stone_custom_design_product', 'yes');
    update_option('soul_stone_custom_design_product_id', $product_id);
  }

  return absint($product_id);
}

function soul_stone_design_preview_html($items, $label = 'Custom bracelet') {
  if (!$items || !is_array($items)) {
    return '';
  }

  $count = max(count($items), 1);
  $beads = '';

  foreach (array_values($items) as $index => $item) {
    $angle = isset($item['manualAngle']) && is_numeric($item['manualAngle']) ? (float) $item['manualAngle'] : ((360 / $count) * $index - 90);
    $radians = deg2rad($angle);
    $x = 50 + cos($radians) * 33;
    $y = 50 + sin($radians) * 33;
    $size = isset($item['size']) ? max(14, min(30, (float) $item['size'] * 2.5)) : 18;
    $type = sanitize_html_class($item['type'] ?? 'stone');
    $color = sanitize_hex_color($item['color'] ?? '') ?: '#d2a762';
    $symbol = !empty($item['symbol']) ? esc_html($item['symbol']) : '';

    $beads .= sprintf(
      '<span class="cart-design-bead is-%1$s" style="--x:%2$s%%;--y:%3$s%%;--bead-color:%4$s;--bead-size:%5$spx">%6$s</span>',
      esc_attr($type),
      esc_attr(number_format($x, 2, '.', '')),
      esc_attr(number_format($y, 2, '.', '')),
      esc_attr($color),
      esc_attr(number_format($size, 1, '.', '')),
      $symbol
    );
  }

  return '<div class="cart-design-preview" role="img" aria-label="' . esc_attr($label) . ' preview"><span class="cart-design-ring"></span>' . $beads . '</div>';
}

function soul_stone_apply_custom_design_cart_price($cart) {
  if (is_admin() && !defined('DOING_AJAX')) {
    return;
  }

  if (!$cart) {
    return;
  }

  foreach ($cart->get_cart() as $cart_item) {
    if (empty($cart_item['soul_stone_custom_design']) || empty($cart_item['data'])) {
      continue;
    }

    $price = isset($cart_item['soul_stone_design_total']) ? (float) $cart_item['soul_stone_design_total'] : 10;
    $cart_item['data']->set_price(max(0, $price));
  }
}
add_action('woocommerce_before_calculate_totals', 'soul_stone_apply_custom_design_cart_price', 20);

function soul_stone_custom_design_cart_item_name($name, $cart_item) {
  if (!empty($cart_item['soul_stone_custom_design']) && !empty($cart_item['soul_stone_design_name'])) {
    return esc_html($cart_item['soul_stone_design_name']);
  }

  return $name;
}
add_filter('woocommerce_cart_item_name', 'soul_stone_custom_design_cart_item_name', 10, 2);

function soul_stone_custom_design_item_data($item_data, $cart_item) {
  if (empty($cart_item['soul_stone_custom_design'])) {
    return $item_data;
  }

  $fields = array(
    'Length' => $cart_item['soul_stone_design_length'] ?? '',
    'Materials' => $cart_item['soul_stone_design_materials'] ?? '',
    'Pieces' => trim(($cart_item['soul_stone_design_stone_count'] ?? '0') . ' stones / ' . ($cart_item['soul_stone_design_accessory_count'] ?? '0') . ' accessories'),
    'Design ID' => $cart_item['soul_stone_design_id'] ?? '',
  );

  foreach ($fields as $name => $value) {
    if ($value !== '') {
      $item_data[] = array(
        'name' => $name,
        'value' => esc_html((string) $value),
      );
    }
  }

  return $item_data;
}
add_filter('woocommerce_get_item_data', 'soul_stone_custom_design_item_data', 10, 2);

function soul_stone_add_custom_design_order_meta($item, $cart_item_key, $values) {
  if (empty($values['soul_stone_custom_design'])) {
    return;
  }

  $item->add_meta_data('Soul Stone Custom Design', 'Yes', true);
  $item->add_meta_data('Design ID', $values['soul_stone_design_id'] ?? '', true);
  $item->add_meta_data('Length', $values['soul_stone_design_length'] ?? '', true);
  $item->add_meta_data('Materials', $values['soul_stone_design_materials'] ?? '', true);
  $item->add_meta_data('Stones', $values['soul_stone_design_stone_count'] ?? 0, true);
  $item->add_meta_data('Accessories', $values['soul_stone_design_accessory_count'] ?? 0, true);
  $item->add_meta_data('Design Items', wp_json_encode($values['soul_stone_design_items'] ?? array()), true);

  if (!empty($values['soul_stone_design_id'])) {
    update_post_meta(absint($values['soul_stone_design_id']), '_soul_design_status', 'Ordered');
  }
}
add_action('woocommerce_checkout_create_order_line_item', 'soul_stone_add_custom_design_order_meta', 10, 3);

function soul_stone_attach_custom_design_order_id($order_id) {
  $order = function_exists('wc_get_order') ? wc_get_order($order_id) : null;
  if (!$order) {
    return;
  }

  foreach ($order->get_items() as $item) {
    $design_id = absint($item->get_meta('Design ID'));
    if ($design_id) {
      update_post_meta($design_id, '_soul_design_order_id', $order_id);
    }
  }
}
add_action('woocommerce_checkout_order_processed', 'soul_stone_attach_custom_design_order_id');

function soul_stone_save_custom_design() {
  if (!is_user_logged_in()) {
    wp_send_json_error(array('message' => 'Please login to save this custom bracelet.'), 401);
  }

  check_ajax_referer('soul_stone_save_custom_design', 'nonce');

  $user_id = get_current_user_id();
  $product_name = sanitize_text_field(wp_unslash($_POST['product_name'] ?? 'Custom Bracelet'));
  $total = isset($_POST['total']) ? (float) wp_unslash($_POST['total']) : 0;
  $length = sanitize_text_field(wp_unslash($_POST['length'] ?? ''));
  $materials = sanitize_text_field(wp_unslash($_POST['materials'] ?? ''));
  $stone_count = isset($_POST['stone_count']) ? absint($_POST['stone_count']) : 0;
  $accessory_count = isset($_POST['accessory_count']) ? absint($_POST['accessory_count']) : 0;
  $items = soul_stone_sanitize_design_items(wp_unslash($_POST['items'] ?? '[]'));

  if (!$items) {
    wp_send_json_error(array('message' => 'Choose at least one stone or accessory before saving.'), 400);
  }

  $post_id = wp_insert_post(array(
    'post_type' => 'soul_custom_design',
    'post_status' => 'private',
    'post_author' => $user_id,
    'post_title' => $product_name . ' - ' . current_time('Y-m-d H:i'),
  ), true);

  if (is_wp_error($post_id)) {
    wp_send_json_error(array('message' => 'The custom bracelet could not be saved.'), 500);
  }

  update_post_meta($post_id, '_soul_design_product_name', $product_name);
  update_post_meta($post_id, '_soul_design_total', $total);
  update_post_meta($post_id, '_soul_design_length', $length);
  update_post_meta($post_id, '_soul_design_materials', $materials);
  update_post_meta($post_id, '_soul_design_stone_count', $stone_count);
  update_post_meta($post_id, '_soul_design_accessory_count', $accessory_count);
  update_post_meta($post_id, '_soul_design_items', wp_json_encode($items));
  update_post_meta($post_id, '_soul_design_status', 'Saved to cart');

  $cart_item_key = '';
  if (function_exists('WC')) {
    soul_stone_ensure_wc_cart();
    $custom_product_id = soul_stone_custom_design_product_id();

    if ($custom_product_id && WC()->cart) {
      $cart_item_key = WC()->cart->add_to_cart($custom_product_id, 1, 0, array(), array(
        'soul_stone_custom_design' => true,
        'soul_stone_design_id' => $post_id,
        'soul_stone_design_name' => $product_name,
        'soul_stone_design_total' => max(0, $total),
        'soul_stone_design_length' => $length,
        'soul_stone_design_materials' => $materials,
        'soul_stone_design_stone_count' => $stone_count,
        'soul_stone_design_accessory_count' => $accessory_count,
        'soul_stone_design_items' => $items,
        'unique_key' => md5($post_id . microtime(true)),
      ));
    }
  }

  wp_send_json_success(array(
    'id' => $post_id,
    'cartItemKey' => $cart_item_key,
    'cartCount' => soul_stone_wc_cart_count(),
    'cartUrl' => soul_stone_page_url('cart'),
    'checkoutUrl' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : soul_stone_page_url('checkout'),
    'message' => $cart_item_key ? 'Custom bracelet added to cart and saved to your account.' : 'Custom bracelet saved to your account.',
  ));
}
add_action('wp_ajax_soul_stone_save_custom_design', 'soul_stone_save_custom_design');

function soul_stone_user_custom_designs($user_id, $limit = 6) {
  return get_posts(array(
    'post_type' => 'soul_custom_design',
    'post_status' => array('private', 'publish'),
    'author' => (int) $user_id,
    'numberposts' => (int) $limit,
    'orderby' => 'date',
    'order' => 'DESC',
  ));
}

function soul_stone_custom_design_meta($post_id) {
  return array(
    'name' => get_post_meta($post_id, '_soul_design_product_name', true),
    'total' => get_post_meta($post_id, '_soul_design_total', true),
    'length' => get_post_meta($post_id, '_soul_design_length', true),
    'materials' => get_post_meta($post_id, '_soul_design_materials', true),
    'stone_count' => get_post_meta($post_id, '_soul_design_stone_count', true),
    'accessory_count' => get_post_meta($post_id, '_soul_design_accessory_count', true),
    'items' => json_decode((string) get_post_meta($post_id, '_soul_design_items', true), true) ?: array(),
    'status' => get_post_meta($post_id, '_soul_design_status', true) ?: 'Saved',
    'order_id' => get_post_meta($post_id, '_soul_design_order_id', true),
  );
}

function soul_stone_custom_orders_admin_page() {
  if (!current_user_can('manage_options') && !current_user_can('manage_woocommerce')) {
    wp_die(esc_html__('You do not have permission to view custom bracelet orders.', 'soul-stone'));
  }

  $orders = get_posts(array(
    'post_type' => 'soul_custom_design',
    'post_status' => array('private', 'publish'),
    'numberposts' => 100,
    'orderby' => 'date',
    'order' => 'DESC',
  ));
  ?>
  <div class="wrap">
    <h1>Custom Bracelet Orders</h1>
    <p>These records are created when a logged-in customer adds a custom bracelet design to cart.</p>
    <table class="widefat striped">
      <thead>
        <tr><th>Date</th><th>Customer</th><th>Design</th><th>Order</th><th>Total</th><th>Length</th><th>Pieces</th><th>Materials</th><th>Status</th></tr>
      </thead>
      <tbody>
        <?php if (!$orders) : ?>
          <tr><td colspan="9">No custom bracelet orders yet.</td></tr>
        <?php endif; ?>
        <?php foreach ($orders as $order) : ?>
          <?php
            $meta = soul_stone_custom_design_meta($order->ID);
            $customer = get_user_by('id', $order->post_author);
          ?>
          <tr>
            <td><?php echo esc_html(get_the_date('Y-m-d H:i', $order)); ?></td>
            <td><?php echo esc_html($customer ? $customer->user_email : 'Guest'); ?></td>
            <td><?php echo esc_html($meta['name'] ?: $order->post_title); ?><br><small>ID: <?php echo esc_html((string) $order->ID); ?></small></td>
            <td>
              <?php if (!empty($meta['order_id'])) : ?>
                <a href="<?php echo esc_url(admin_url('post.php?post=' . absint($meta['order_id']) . '&action=edit')); ?>">#<?php echo esc_html((string) $meta['order_id']); ?></a>
              <?php else : ?>
                <?php echo esc_html('-'); ?>
              <?php endif; ?>
            </td>
            <td><?php echo esc_html('$' . number_format((float) $meta['total'], 0) . ' AUD'); ?></td>
            <td><?php echo esc_html($meta['length']); ?></td>
            <td><?php echo esc_html($meta['stone_count'] . ' stones, ' . $meta['accessory_count'] . ' accessories'); ?></td>
            <td><?php echo esc_html($meta['materials']); ?></td>
            <td><?php echo esc_html($meta['status']); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php
}
