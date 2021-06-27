<?php
$extra_class   = $custom_icon = $custom_icon_width = $custom_icon_height = $icon_classes = '';
$icon_type     = $params['icon_type'];
$cart_position = $params['position'];

$extra_class .= ' wd-design-' . $params['style'];

if ( $icon_type == 'bag' ) {
	$icon_classes .= ' wd-icon-alt';
	$extra_class  .= woodmart_get_old_classes( ' woodmart-cart-alt' );
}

if ( $icon_type == 'custom' ) {
	$extra_class .= ' wd-tools-custom-icon';
}

if ( $cart_position == 'side' ) {
	$extra_class .= ' cart-widget-opener';
}

if ( woodmart_get_opt( 'mini_cart_quantity' ) ) {
	woodmart_enqueue_js_script( 'mini-cart-quantity' );
}

woodmart_enqueue_js_script( 'on-remove-from-cart' );

if ( $cart_position != 'side' && $cart_position != 'without' ) {
	$extra_class .= ' wd-event-hover';
}

$dropdowns_classes = '';

$extra_class       .= woodmart_get_old_classes( ' woodmart-shopping-cart' );
$extra_class       .= woodmart_get_old_classes( ' woodmart-cart-design-' . $params['style'] );
$icon_classes      .= woodmart_get_old_classes( ' woodmart-cart-icon' );
$dropdowns_classes .= woodmart_get_old_classes( ' dropdown-cart' );


if ( 'light' === whb_get_dropdowns_color() ) {
	$dropdowns_classes .= ' color-scheme-light';
}

if ( ! woodmart_woocommerce_installed() || $params['style'] == 'disable' || ( ! is_user_logged_in() && woodmart_get_opt( 'login_prices' ) ) ) {
	return;
} ?>

<div class="wd-header-cart wd-tools-element<?php echo esc_attr( $extra_class ); ?>">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr__( 'Shopping cart', 'woodmart' ); ?>">
		<span class="wd-tools-icon<?php echo esc_attr( $icon_classes ); ?>">
			<?php if ( $icon_type == 'custom' ) : ?>
				<?php echo whb_get_custom_icon( $params['custom_icon'] ); ?>
			<?php endif; ?>
			<?php if ( '2' == $params['style'] || '5' == $params['style'] ) : ?>
				<?php woodmart_cart_count(); ?>
			<?php endif; ?>
		</span>
		<span class="wd-tools-text<?php echo woodmart_get_old_classes( ' woodmart-cart-totals' ); ?>">
			<?php if ( '2' != $params['style'] && '5' != $params['style'] ) : ?>
				<?php woodmart_cart_count(); ?>
			<?php endif; ?>

			<span class="subtotal-divider">/</span>
			<?php woodmart_cart_subtotal(); ?>
		</span>
	</a>
	<?php if ( $cart_position != 'side' && $cart_position != 'without' ) : ?>
		<div class="wd-dropdown wd-dropdown-cart<?php echo esc_attr( $dropdowns_classes ); ?>">
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</div>
	<?php endif; ?>
</div>
