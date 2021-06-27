<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$tabs = woodmart_get_opt( 'login_tabs' );
$reg_text = woodmart_get_opt( 'reg_text' );
$login_text = woodmart_get_opt( 'login_text' );
$account_link = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

$class = 'wd-registration-page';
$class .= woodmart_get_old_classes( ' woodmart-registration-page' );

if ( $tabs && get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
	woodmart_enqueue_js_script( 'login-tabs' );
	$class .= ' wd-register-tabs';
	$class .= woodmart_get_old_classes( ' woodmart-register-tabs' );
}

if ( get_option( 'woocommerce_enable_myaccount_registration' ) !== 'yes' ) {
	$class .= ' wd-no-registration';
}

if ( $login_text && $reg_text ) {
    $class .= ' with-login-reg-info';
}

if ( isset( $_GET['action'] ) && 'register' === $_GET['action'] && $tabs ) {
	$class .= ' active-register';
}

//WC 3.5.0
if ( function_exists( 'WC' ) && version_compare( WC()->version, '3.5.0', '<' ) ) {
	wc_print_notices();
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<div class="<?php echo esc_attr( $class ); ?>">

<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

<div class="row" id="customer_login">

	<div class="col-12 col-md-6 col-login">

<?php endif; ?>

		<h2 class="wd-login-title"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

		<?php woodmart_login_form( true, add_query_arg( 'action', 'login', $account_link ) ); ?>
		
<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>

	</div>

	<div class="col-12 col-md-6 col-register">

		<h2 class="wd-login-title"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

		<form method="post" action="<?php echo esc_url( add_query_arg( 'action', 'register', $account_link ) ); ?>" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

			<?php do_action( 'woocommerce_register_form_start' ); ?>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( $_POST['username'] ) : ''; ?>" />
				</p>

			<?php endif; ?>

			<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
				<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( $_POST['email'] ) : ''; ?>" />
			</p>

			<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

				<p class="woocommerce-FormRow woocommerce-FormRow--wide form-row form-row-wide">
					<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
				</p>

			<?php else : ?>

				<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

			<?php endif; ?>

			<!-- Spam Trap -->
			<div style="<?php echo ( ( is_rtl() ) ? 'right' : 'left' ); ?>: -999em; position: absolute;"><label for="trap"><?php esc_html_e( 'Anti-spam', 'woocommerce' ); ?></label><input type="text" name="email_2" id="trap" tabindex="-1" /></div>

			<?php do_action( 'woocommerce_register_form' ); ?>
			
			<p class="woocommerce-form-row form-row">
				<?php wp_nonce_field( 'woocommerce-register' ); ?>
				<button type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
			</p>

			<?php do_action( 'woocommerce_register_form_end' ); ?>

		</form>

	</div>

	<?php if ( $tabs ): ?>
		<div class="col-12 col-md-6 col-register-text">

			<div class="title wd-login-divider register-or<?php echo woodmart_get_old_classes( ' wood-login-divider' ); ?>"><span><?php esc_html_e( 'Or', 'woodmart' ); ?></span></div>

			<?php 
				$reg_title = woodmart_get_opt( 'reg_title' ) ? woodmart_get_opt( 'reg_title' ) : esc_html__( 'Register', 'woocommerce' );
				$login_title = woodmart_get_opt( 'login_title' ) ? woodmart_get_opt( 'login_title' ) : esc_html__( 'Login', 'woocommerce' );

				$title = $reg_title;

				if ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) {
					$title = $login_title;
				}
			?>

			<?php if ( $login_text || $reg_text ): ?>
				<h2 class="wd-login-title"><?php echo esc_html( $title ); ?></h2>
			<?php endif ?>

			<?php if ( $login_text ): ?>
				<div class="login-info"><?php echo do_shortcode( $login_text ); ?></div>
			<?php endif ?>

			<?php if ( $reg_text ): ?>
				<div class="registration-info"><?php echo do_shortcode( $reg_text ); ?></div>
			<?php endif ?>

			<?php 
				$button_text = esc_html__( 'Register', 'woocommerce' );

				if ( isset( $_GET['action'] ) && 'register' === $_GET['action'] ) {
					$button_text = esc_html__( 'Login', 'woocommerce' );
				}
			?>

			<a href="#" rel="nofollow noopener" class="btn wd-switch-to-register" data-login="<?php esc_html_e( 'Login', 'woocommerce') ?>" data-login-title="<?php echo esc_attr( $login_title ) ?>" data-reg-title="<?php echo esc_attr( $reg_title ) ?>" data-register="<?php esc_html_e( 'Register', 'woocommerce') ?>"><?php echo esc_html( $button_text ); ?></a>

		</div>
	<?php endif ?>
	
</div>
<?php endif; ?>

</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
