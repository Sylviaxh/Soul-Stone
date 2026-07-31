<?php
/* Template Name: Soul Stone Account */
get_header();

$current_user = wp_get_current_user();
$user_roles = is_user_logged_in() ? (array) $current_user->roles : array();
$primary_role = $user_roles ? reset($user_roles) : 'customer';
$saved_designs = is_user_logged_in() ? soul_stone_user_custom_designs($current_user->ID, 6) : array();
$customer_orders = array();

if (is_user_logged_in() && function_exists('wc_get_orders')) {
  $customer_orders = wc_get_orders(array(
    'customer_id' => $current_user->ID,
    'limit' => 6,
    'orderby' => 'date',
    'order' => 'DESC',
  ));
}

$account_status = isset($_GET['account_status']) ? sanitize_key(wp_unslash($_GET['account_status'])) : '';
$account_message = $account_status ? soul_stone_auth_message($account_status) : null;

if (!function_exists('soul_stone_account_meta')) {
  function soul_stone_account_meta($user_id, $key, $fallback = '') {
    $value = get_user_meta($user_id, $key, true);
    return $value !== '' ? $value : $fallback;
  }
}
?>
<main>
  <section class="account-hero account-page-hero">
    <div>
      <span class="eyebrow">Your Soul Stone Space</span>
      <h1>Account</h1>
      <p>Manage your profile, saved design direction and future orders from one calm place.</p>
    </div>
  </section>

  <section class="section auth-section">
    <?php if (!is_user_logged_in()) : ?>
      <article class="auth-card auth-single-card">
        <span class="eyebrow">Login Required</span>
        <h2>Please login first.</h2>
        <p>Your account page is private. Login or create an account to continue.</p>
        <div class="account-actions">
          <a class="auth-primary-button" href="<?php echo esc_url(soul_stone_page_url('login')); ?>">Login</a>
          <a class="outline-button" href="<?php echo esc_url(soul_stone_page_url('sign-up')); ?>">Sign Up</a>
        </div>
      </article>
    <?php else : ?>
      <div class="account-dashboard">
        <article class="auth-card account-profile-card">
          <span class="eyebrow">Profile</span>
          <h2>Hello, <?php echo esc_html($current_user->display_name ?: $current_user->user_login); ?></h2>
          <p>Manage your saved details, addresses, orders and custom bracelet records in one place.</p>

          <div class="account-summary">
            <div><span>User ID</span><strong><?php echo esc_html((string) $current_user->ID); ?></strong></div>
            <div><span>Username</span><strong><?php echo esc_html($current_user->user_login); ?></strong></div>
            <div><span>Email</span><strong><?php echo esc_html($current_user->user_email); ?></strong></div>
            <div><span>Phone</span><strong><?php echo esc_html(soul_stone_account_meta($current_user->ID, 'billing_phone', 'Not set')); ?></strong></div>
            <div><span>Account Type</span><strong><?php echo esc_html(ucfirst($primary_role)); ?></strong></div>
          </div>

          <div class="account-actions">
            <?php if (current_user_can('manage_options')) : ?>
              <a class="auth-primary-button" href="<?php echo esc_url(admin_url()); ?>">Open WordPress Admin</a>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
              <input type="hidden" name="action" value="soul_stone_logout">
              <?php wp_nonce_field('soul_stone_logout_action', 'soul_stone_logout_nonce'); ?>
              <button class="outline-button" type="submit">Logout</button>
            </form>
          </div>
        </article>

        <div class="account-panel-grid">
          <?php if ($account_message) : ?>
            <div class="auth-message is-<?php echo esc_attr($account_message[0]); ?>"><?php echo esc_html($account_message[1]); ?></div>
          <?php endif; ?>

          <section class="account-mini-panel account-form-panel">
            <span>Account Details</span>
            <strong>Edit profile</strong>
            <p>Update the contact details used for your checkout and order communication.</p>
            <form class="account-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
              <input type="hidden" name="action" value="soul_stone_update_profile">
              <?php wp_nonce_field('soul_stone_profile_action', 'soul_stone_profile_nonce'); ?>
              <div class="account-form-grid">
                <label>First name
                  <input type="text" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" autocomplete="given-name">
                </label>
                <label>Last name
                  <input type="text" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" autocomplete="family-name">
                </label>
                <label>Display name
                  <input type="text" name="display_name" value="<?php echo esc_attr($current_user->display_name ?: $current_user->user_login); ?>" required autocomplete="name">
                </label>
                <label>Email
                  <input type="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" required autocomplete="email">
                </label>
                <label>Phone
                  <input type="tel" name="phone" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, 'billing_phone')); ?>" autocomplete="tel">
                </label>
              </div>
              <button class="auth-primary-button" type="submit">Save Profile</button>
            </form>
          </section>

          <section class="account-mini-panel account-form-panel">
            <span>Addresses</span>
            <strong>Billing & shipping</strong>
            <p>These details can pre-fill checkout and help keep order handling tidy.</p>
            <form class="account-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
              <input type="hidden" name="action" value="soul_stone_update_addresses">
              <?php wp_nonce_field('soul_stone_address_action', 'soul_stone_address_nonce'); ?>
              <div class="account-address-columns">
                <?php foreach (array('billing' => 'Billing Address', 'shipping' => 'Shipping Address') as $type => $label) : ?>
                  <fieldset>
                    <legend><?php echo esc_html($label); ?></legend>
                    <div class="account-form-grid">
                      <label>First name
                        <input type="text" name="<?php echo esc_attr($type); ?>_first_name" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_first_name', $current_user->first_name)); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing given-name' : 'shipping given-name'); ?>">
                      </label>
                      <label>Last name
                        <input type="text" name="<?php echo esc_attr($type); ?>_last_name" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_last_name', $current_user->last_name)); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing family-name' : 'shipping family-name'); ?>">
                      </label>
                      <label>Company
                        <input type="text" name="<?php echo esc_attr($type); ?>_company" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_company')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing organization' : 'shipping organization'); ?>">
                      </label>
                      <label>Country
                        <input type="text" name="<?php echo esc_attr($type); ?>_country" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_country', 'AU')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing country' : 'shipping country'); ?>">
                      </label>
                      <label class="account-form-wide">Address line 1
                        <input type="text" name="<?php echo esc_attr($type); ?>_address_1" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_address_1')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing address-line1' : 'shipping address-line1'); ?>">
                      </label>
                      <label class="account-form-wide">Address line 2
                        <input type="text" name="<?php echo esc_attr($type); ?>_address_2" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_address_2')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing address-line2' : 'shipping address-line2'); ?>">
                      </label>
                      <label>City
                        <input type="text" name="<?php echo esc_attr($type); ?>_city" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_city')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing address-level2' : 'shipping address-level2'); ?>">
                      </label>
                      <label>State
                        <input type="text" name="<?php echo esc_attr($type); ?>_state" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_state')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing address-level1' : 'shipping address-level1'); ?>">
                      </label>
                      <label>Postcode
                        <input type="text" name="<?php echo esc_attr($type); ?>_postcode" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, $type . '_postcode')); ?>" autocomplete="<?php echo esc_attr($type === 'billing' ? 'billing postal-code' : 'shipping postal-code'); ?>">
                      </label>
                      <?php if ($type === 'billing') : ?>
                        <label>Phone
                          <input type="tel" name="billing_phone" value="<?php echo esc_attr(soul_stone_account_meta($current_user->ID, 'billing_phone')); ?>" autocomplete="billing tel">
                        </label>
                      <?php endif; ?>
                    </div>
                  </fieldset>
                <?php endforeach; ?>
              </div>
              <button class="auth-primary-button" type="submit">Save Addresses</button>
            </form>
          </section>

          <section class="account-mini-panel account-form-panel">
            <span>Password</span>
            <strong>Update password</strong>
            <p>Password must be 8-11 characters and include both letters and numbers.</p>
            <form class="account-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
              <input type="hidden" name="action" value="soul_stone_update_password">
              <?php wp_nonce_field('soul_stone_password_action', 'soul_stone_password_nonce'); ?>
              <div class="account-form-grid">
                <label>Current password
                  <input type="password" name="current_password" required autocomplete="current-password">
                </label>
                <label>New password
                  <input type="password" name="new_password" required autocomplete="new-password">
                </label>
                <label>Confirm password
                  <input type="password" name="confirm_password" required autocomplete="new-password">
                </label>
              </div>
              <button class="auth-primary-button" type="submit">Update Password</button>
            </form>
          </section>

          <section class="account-mini-panel account-design-panel">
            <span>Saved Designs</span>
            <strong><?php echo esc_html($saved_designs ? count($saved_designs) . ' saved' : 'No saved designs yet'); ?></strong>
            <p>Custom bracelet designs are saved when you add a custom bracelet to cart while logged in.</p>
            <?php if ($saved_designs) : ?>
              <div class="saved-design-list">
                <?php foreach ($saved_designs as $design) : ?>
                  <?php $meta = soul_stone_custom_design_meta($design->ID); ?>
                  <article class="saved-design-item">
                    <div>
                      <b><?php echo esc_html($meta['name'] ?: $design->post_title); ?></b>
                      <small><?php echo esc_html(get_the_date('Y-m-d H:i', $design)); ?></small>
                    </div>
                    <p><?php echo esc_html($meta['materials']); ?></p>
                    <dl>
                      <div><dt>Total</dt><dd><?php echo esc_html('$' . number_format((float) $meta['total'], 0) . ' AUD'); ?></dd></div>
                      <div><dt>Length</dt><dd><?php echo esc_html($meta['length']); ?></dd></div>
                      <div><dt>Pieces</dt><dd><?php echo esc_html($meta['stone_count'] . ' stones / ' . $meta['accessory_count'] . ' accessories'); ?></dd></div>
                    </dl>
                  </article>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(soul_stone_page_url('custom-design')); ?>">Start designing</a>
          </section>

          <article class="account-mini-panel">
            <span>Orders</span>
            <strong><?php echo esc_html($customer_orders ? count($customer_orders) . ' recent' : 'No orders yet'); ?></strong>
            <p>Your WooCommerce order history will appear here after checkout.</p>
            <?php if ($customer_orders) : ?>
              <div class="account-order-list">
                <?php foreach ($customer_orders as $order) : ?>
                  <?php
                    $items = $order->get_items();
                    $item_names = array();
                    foreach ($items as $item) {
                      $item_names[] = $item->get_name();
                    }
                  ?>
                  <article class="account-order-item">
                    <div class="account-order-top">
                      <b><?php echo esc_html('#' . $order->get_order_number()); ?></b>
                      <mark><?php echo esc_html(wc_get_order_status_name($order->get_status())); ?></mark>
                    </div>
                    <p><?php echo esc_html(implode(' · ', array_slice($item_names, 0, 3))); ?><?php echo count($item_names) > 3 ? esc_html('...') : ''; ?></p>
                    <dl>
                      <div><dt>Date</dt><dd><?php echo esc_html($order->get_date_created() ? $order->get_date_created()->date_i18n('Y-m-d') : ''); ?></dd></div>
                      <div><dt>Total</dt><dd><?php echo wp_kses_post($order->get_formatted_order_total()); ?></dd></div>
                    </dl>
                    <details class="account-order-details">
                      <summary>View details</summary>
                      <ul>
                        <?php foreach ($items as $item) : ?>
                          <li>
                            <strong><?php echo esc_html($item->get_name()); ?></strong>
                            <span><?php echo esc_html('Qty ' . $item->get_quantity()); ?></span>
                            <?php
                              $meta_lines = array();
                              foreach ($item->get_formatted_meta_data('') as $meta) {
                                $meta_lines[] = wp_strip_all_tags($meta->display_key . ': ' . $meta->display_value);
                              }
                            ?>
                            <?php if ($meta_lines) : ?>
                              <small><?php echo esc_html(implode(' · ', $meta_lines)); ?></small>
                            <?php endif; ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </details>
                  </article>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <a href="<?php echo esc_url(soul_stone_page_url('shop')); ?>">Browse products</a>
          </article>

          <article class="account-mini-panel">
            <span>Cart</span>
            <strong>Review selection</strong>
            <p>Continue to your cart to review products and custom bracelet previews.</p>
            <a href="<?php echo esc_url(soul_stone_page_url('cart')); ?>">Open cart</a>
          </article>
        </div>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
