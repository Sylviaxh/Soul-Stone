<?php
/* Template Name: Soul Stone Login */
get_header();

$auth_status = isset($_GET['auth_status']) ? sanitize_key(wp_unslash($_GET['auth_status'])) : '';
$auth_message = $auth_status ? soul_stone_auth_message($auth_status) : null;
?>
<main>
  <section class="account-hero auth-hero-compact">
    <div>
      <span class="eyebrow">Soul Stone Account</span>
      <h1><?php echo is_user_logged_in() ? 'Account' : 'Login'; ?></h1>
      <p><?php echo is_user_logged_in() ? 'Manage your Soul Stone account, saved designs and future order details.' : 'Welcome back. Login to continue with your saved designs and shopping details.'; ?></p>
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
        <h2>You are logged in.</h2>
        <p>Go to your account page to review profile details, admin access and future saved design information.</p>
        <div class="account-actions">
          <a class="auth-primary-button" href="<?php echo esc_url(soul_stone_page_url('account')); ?>">View Account</a>
          <a class="outline-button" href="<?php echo esc_url(home_url('/')); ?>">Go to Homepage</a>

          <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="soul_stone_logout">
            <?php wp_nonce_field('soul_stone_logout_action', 'soul_stone_logout_nonce'); ?>
            <button class="outline-button" type="submit">Logout</button>
          </form>
        </div>
      </article>
    <?php else : ?>
      <article class="auth-card auth-single-card">
        <span class="eyebrow">Returning Customer</span>
        <h2>Login</h2>
        <p>Use your email or username to continue with your Soul Stone account.</p>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
          <input type="hidden" name="action" value="soul_stone_login">
          <?php wp_nonce_field('soul_stone_login_action', 'soul_stone_login_nonce'); ?>

          <div class="auth-field">
            <label for="soul-login-id">Email or Username</label>
            <input id="soul-login-id" name="log" type="text" autocomplete="username" placeholder="Enter email or username" required>
          </div>

          <div class="auth-field">
            <label for="soul-login-password">Password</label>
            <input id="soul-login-password" name="pwd" type="password" autocomplete="current-password" placeholder="Enter password" required>
          </div>

          <label class="auth-check">
            <input name="remember" type="checkbox" value="1">
            <span>Keep me signed in</span>
          </label>

          <button class="auth-primary-button" type="submit">Login</button>
        </form>

        <p class="auth-switch-link">New to Soul Stone? <a href="<?php echo esc_url(soul_stone_page_url('sign-up')); ?>">Click here to sign up.</a></p>
      </article>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
