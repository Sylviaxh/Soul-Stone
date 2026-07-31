<?php
/* Template Name: Soul Stone Sign Up */
get_header();

$auth_status = isset($_GET['auth_status']) ? sanitize_key(wp_unslash($_GET['auth_status'])) : '';
$auth_message = $auth_status ? soul_stone_auth_message($auth_status) : null;
?>
<main>
  <section class="account-hero auth-hero-compact">
    <div>
      <span class="eyebrow">Join Soul Stone</span>
      <h1>Sign Up</h1>
      <p>Create an account for saved custom designs, cart details and future order history.</p>
    </div>
  </section>

  <section class="section auth-section">
    <?php if ($auth_message) : ?>
      <div class="auth-message <?php echo $auth_message[0] === 'success' ? 'is-success' : 'is-error'; ?>">
        <?php echo esc_html($auth_message[1]); ?>
      </div>
    <?php endif; ?>

    <?php if (is_user_logged_in()) : ?>
      <article class="auth-card auth-single-card">
        <span class="eyebrow">Already Signed In</span>
        <h2>Your account is ready.</h2>
        <p>You are already logged in. Continue exploring Soul Stone or review your account details.</p>
        <div class="account-actions">
          <a class="auth-primary-button" href="<?php echo esc_url(home_url('/')); ?>">Go to Homepage</a>
          <a class="outline-button" href="<?php echo esc_url(soul_stone_page_url('account')); ?>">View Account</a>
        </div>
      </article>
    <?php else : ?>
      <article class="auth-card auth-single-card">
        <span class="eyebrow">New Customer</span>
        <h2>Create Account</h2>
        <p>Register with your email and a secure password before saving designs and order details.</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="soul_stone_register">
          <?php wp_nonce_field('soul_stone_register_action', 'soul_stone_register_nonce'); ?>

          <div class="auth-field">
            <label for="soul-register-username">Username</label>
            <input id="soul-register-username" name="username" type="text" autocomplete="username" placeholder="Enter username" required>
          </div>

          <div class="auth-field">
            <label for="soul-register-email">Email</label>
            <input id="soul-register-email" name="email" type="email" autocomplete="email" placeholder="Enter email" required>
          </div>

          <div class="auth-field">
            <label for="soul-register-password">Password</label>
            <input id="soul-register-password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="11" pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,11}" placeholder="Enter password" required>
            <small class="auth-help">8-11 characters, with at least one letter and one number.</small>
          </div>

          <div class="auth-field">
            <label for="soul-register-confirm">Confirm Password</label>
            <input id="soul-register-confirm" name="confirm_password" type="password" autocomplete="new-password" minlength="8" maxlength="11" placeholder="Enter password again" required>
          </div>

          <button class="auth-primary-button" type="submit">Sign Up</button>
        </form>

        <p class="auth-switch-link">Already sign up ! <a href="<?php echo esc_url(soul_stone_page_url('login')); ?>">Click here to login with your account</a></p>
      </article>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
