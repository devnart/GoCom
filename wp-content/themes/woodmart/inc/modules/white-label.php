<?php

if ( ! function_exists( 'woodmart_white_label' ) ) {
	function woodmart_white_label() {
		if ( ! woodmart_get_opt( 'white_label' ) || ( ! woodmart_get_opt( 'white_label_options_logo' ) && ! woodmart_get_opt( 'white_label_appearance_screenshot' ) ) ) {
			return;
		}

		add_filter( 'woodmart_header_builder_admin_footer_links', '__return_false' );
		add_filter( 'woodmart_dashboard_links', '__return_false' );

		$icon_data       = woodmart_get_opt( 'white_label_options_logo' );
		$screenshot_data = woodmart_get_opt( 'white_label_appearance_screenshot' );
		?>

		<style>
			<?php if ( $screenshot_data['id'] ) : ?>
			.theme[aria-describedby="woodmart-action woodmart-name"] img, .theme[aria-describedby="woodmart-child-action woodmart-child-name"] img, .wd-woodmart-theme img, .wd-woodmart-theme .theme-info{
				display: none;
			}

			.theme-browser .theme[aria-describedby="woodmart-action woodmart-name"]:focus .theme-screenshot, .theme-browser .theme[aria-describedby="woodmart-action woodmart-name"]:hover .theme-screenshot, .theme-browser .theme[aria-describedby="woodmart-child-action woodmart-child-name"]:focus .theme-screenshot, .theme-browser .theme[aria-describedby="woodmart-child-action woodmart-child-name"]:hover .theme-screenshot {
				opacity: 0.4;
			}

			.theme[aria-describedby="woodmart-action woodmart-name"] .theme-screenshot,  .theme[aria-describedby="woodmart-child-action woodmart-child-name"] .theme-screenshot, .wd-woodmart-theme .screenshot{
				background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $screenshot_data['id'] ) ); ?>) !important;
				background-repeat: no-repeat !important;
				background-position: center center !important;
				background-size: contain !important;
				background-color: transparent !important;
			}

			.theme-name#woodmart-name span , .theme-name#woodmart-child-name span{
				font-size: 15px;
			}
			<?php endif; ?>

			<?php if ( woodmart_get_opt( 'white_label_theme_name' ) ) : ?>
			.theme-name#woodmart-name:after {
				content: "<?php echo esc_html( woodmart_get_opt( 'white_label_theme_name' ) ); ?>";
				font-size: 15px;
				margin-left: 5px;
			}

			.theme-name#woodmart-name, .theme-name#woodmart-child-name {
				font-size:0
			}

			.theme-name#woodmart-child-name:after {
				content: "<?php echo esc_html( woodmart_get_opt( 'white_label_theme_name' ) ); ?>-child";
				font-size: 15px;
				margin-left: 5px;
			}
			<?php endif; ?>

			<?php if ( $icon_data['id'] ) : ?>
			.xts-options-theme-name:before {
				background-image: url(<?php echo esc_url( wp_get_attachment_image_url( $icon_data['id'] ) ); ?>) !important;
			}
			<?php endif; ?>
		</style>
		<?php
	}

	add_filter( 'admin_print_styles', 'woodmart_white_label', -100 );
}
