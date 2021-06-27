<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Widget user panel
* ------------------------------------------------------------------------------------------------
*/

if( ! function_exists( 'woodmart_shortcode_user_panel' )) {
	function woodmart_shortcode_user_panel($atts) {
		if( ! woodmart_woocommerce_installed() ) return;
		$click = $output = $title_out = $class = '';
		extract(shortcode_atts( array(
			'title' => '',
		), $atts ));

		$class .= ' ';

		$user = wp_get_current_user();

		ob_start(); ?>

			<div class="woodmart-user-panel<?php echo esc_attr( $class ); ?>">

				<?php if ( ! is_user_logged_in() ): ?>
					<?php printf( wp_kses( __('Please, <a href="%s">log in</a>', 'woodmart'), array(
							'a' => array(
								'href' => array()
							)
						) ), get_permalink( get_option('woocommerce_myaccount_page_id') )); ?>
				<?php else: ?>


					<div class="user-avatar">
						<?php echo get_avatar( $user->ID, 92, '', 'author-avatar' ); ?>
					</div>

					<div class="user-info">
						<span><?php printf( wp_kses( __('Welcome, <strong>%s</strong>', 'woodmart'), array(
								'strong' => array()								
							) ), $user->user_login ) ?></span>
						<a href="<?php echo esc_url( wp_logout_url( home_url('/') ) ); ?>" class="logout-link"><?php esc_html_e('Logout', 'woodmart'); ?></a>
					</div>

				<?php endif ?>

			</div>

		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
