<?php
/**
 * Dynamic css
 *
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options;
use XTS\Presets;

/**
 * Dynamic css class
 *
 * @since 1.0.0
 */
class WOODMART_Themesettingscss {
	public $storage;

	/**
	 * Set up all properties
	 */
	public function __construct() {
		add_action( 'xts_after_theme_settings', array( $this, 'reset_data' ), 100 );
		add_action( 'xts_after_theme_settings', array( $this, 'write_file' ), 200 );

		if ( is_admin() ) {
			add_action( 'init', array( $this, 'set_storage' ), 100 );
		} else {
			add_action( 'wp', array( $this, 'set_storage' ), 10200 );
			add_action( 'wp', array( $this, 'print_styles' ), 10300 );
		}
	}

	/**
	 * Set storage.
	 *
	 * @since 1.0.0
	 */
	public function set_storage() {
		$this->storage = new WOODMART_Stylesstorage( $this->get_file_name() );
	}

	/**
	 * Get all css.
	 *
	 * @since 1.0.0
	 *
	 * @param string $preset_id
	 *
	 * @return mixed|void
	 */
	private function get_all_css( $preset_id = '' ) {
		$options = Options::get_instance();

		$options->load_defaults();
		$options->load_options();
		$options->load_presets( $preset_id );
		$options->override_options_from_meta();
		$options->setup_globals();

		$css  = $options->get_css_output();
		$css .= $this->get_icons_font_css();
		$css .= $this->get_theme_settings_css();
		$css .= $this->get_custom_fonts_css();
		$css .= $this->get_custom_css();

		return apply_filters( 'woodmart_get_all_theme_settings_css', $css );
	}

	/**
	 * Get file name.
	 *
	 * @since 1.0.0
	 */
	private function get_file_name() {
		$active_presets = Presets::get_active_presets();
		$preset_id      = isset( $active_presets[0] ) ? $active_presets[0] : 'default';
		return 'theme_settings_' . $preset_id;
	}

	/**
	 * Write file.
	 *
	 * @since 1.0.0
	 */
	public function reset_data() {
		if ( ! isset( $_GET['settings-updated'] ) ) {
			return;
		}

		$this->storage->reset_data();
	}

	/**
	 * Write file.
	 *
	 * @since 1.0.0
	 */
	public function write_file() {
		if ( ! isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && 'xtemos_options' !== $_GET['page'] ) ) { // phpcs:ignore
			return;
		}

		$this->storage->write( $this->get_all_css() );

		if ( ! Presets::get_active_presets() && isset( $_GET['settings-updated'] ) && Presets::get_all() ) { // phpcs:ignore
			$index = 0;
			foreach ( Presets::get_all() as $preset ) {
				$index++;
				$this->storage->set_data_name( 'theme_settings_' . $preset['id'] );
				$this->storage->set_data( 'xts-theme_settings_' . $preset['id'] . '-file-data' );
				$this->storage->set_css_data( 'xts-theme_settings_' . $preset['id'] . '-css-data' );
				$this->storage->reset_data();
				$this->storage->delete_file();

				if ( $index <= apply_filters( 'xts_theme_settings_presets_file_reset_count', 10 ) ) {
					$this->storage->write( $this->get_all_css( $preset['id'] ) );
				}
			}
		}
	}

	/**
	 * Print styles.
	 *
	 * @since 1.0.0
	 */
	public function print_styles() {
		if ( ! $this->storage->is_css_exists() ) {
			$this->storage->write( $this->get_all_css(), true );
		}

		$this->storage->print_styles();
	}

	/**
	 * Get custom css.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_custom_css() {
		$output          = '';
		$custom_css      = woodmart_get_opt( 'custom_css' );
		$css_desktop     = woodmart_get_opt( 'css_desktop' );
		$css_tablet      = woodmart_get_opt( 'css_tablet' );
		$css_wide_mobile = woodmart_get_opt( 'css_wide_mobile' );
		$css_mobile      = woodmart_get_opt( 'css_mobile' );

		if ( $custom_css ) {
			$output .= $custom_css;
		}

		if ( $css_desktop ) {
			$output .= '@media (min-width: 1025px) {' . "\n";
			$output .= "\t" . $css_desktop . "\n";
			$output .= '}' . "\n\n";
		}

		if ( $css_tablet ) {
			$output .= '@media (min-width: 768px) and (max-width: 1024px) {' . "\n";
			$output .= "\t" . $css_tablet . "\n";
			$output .= '}' . "\n\n";
		}

		if ( $css_wide_mobile ) {
			$output .= '@media (min-width: 577px) and (max-width: 767px) {' . "\n";
			$output .= "\t" . $css_wide_mobile . "\n";
			$output .= '}' . "\n\n";
		}

		if ( $css_mobile ) {
			$output .= '@media (max-width: 576px) {' . "\n";
			$output .= "\t" . $css_mobile . "\n";
			$output .= '}' . "\n\n";
		}

		return $output;
	}

	/**
	 * Get custom fonts css.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_custom_fonts_css() {
		$fonts = woodmart_get_opt( 'multi_custom_fonts' );

		$output       = '';
		$font_display = woodmart_get_opt( 'google_font_display' );

		if ( isset( $fonts['{{index}}'] ) ) {
			unset( $fonts['{{index}}'] );
		}

		if ( ! $fonts ) {
			return $output;
		}

		foreach ( $fonts as $key => $value ) {
			$eot   = isset( $value['font-eot'] ) ? $this->get_custom_font_url( $value['font-eot'] ) : '';
			$woff  = isset( $value['font-woff'] ) ? $this->get_custom_font_url( $value['font-woff'] ) : '';
			$woff2 = isset( $value['font-woff2'] ) ? $this->get_custom_font_url( $value['font-woff2'] ) : '';
			$ttf   = isset( $value['font-ttf'] ) ? $this->get_custom_font_url( $value['font-ttf'] ) : '';
			$svg   = isset( $value['font-svg'] ) ? $this->get_custom_font_url( $value['font-svg'] ) : '';

			if ( ! $value['font-name'] ) {
				continue;
			}

			$output .= '@font-face {' . "\n";
			$output .= "\t" . 'font-family: "' . sanitize_text_field( $value['font-name'] ) . '";' . "\n";

			if ( $eot ) {
				$output .= "\t" . 'src: url("' . esc_url( $eot ) . '");' . "\n";
			}

			if ( $eot || $woff || $woff2 || $ttf || $svg ) {
				$output .= "\t" . 'src: ';

				if ( $eot ) {
					$output .= 'url("' . esc_url( $eot ) . '#iefix") format("embedded-opentype")';
				}

				if ( $woff ) {
					if ( $eot ) {
						$output .= ', ' . "\n";
					}
					$output .= 'url("' . esc_url( $woff ) . '") format("woff")';
				}

				if ( $woff2 ) {
					if ( $woff || $eot ) {
						$output .= ', ' . "\n";
					}
					$output .= 'url("' . esc_url( $woff2 ) . '") format("woff2")';
				}

				if ( $ttf ) {
					if ( $woff2 || $woff || $eot ) {
						$output .= ', ' . "\n";
					}
					$output .= 'url("' . esc_url( $ttf ) . '") format("truetype")';
				}

				if ( $svg ) {
					if ( $ttf || $woff2 || $woff || $eot ) {
						$output .= ', ' . "\n";
					}
					$output .= 'url("' . esc_url( $svg ) . '#' . sanitize_text_field( $value['font-name'] ) . '") format("svg")';
				}

				$output .= ';' . "\n";
			}

			if ( isset( $value['font-weight'] ) && $value['font-weight'] ) {
				$output .= "\t" . 'font-weight: ' . sanitize_text_field( $value['font-weight'] ) . ';' . "\n";
			} else {
				$output .= "\t" . 'font-weight: normal;' . "\n";
			}

			if ( 'disable' !== $font_display ) {
				$output .= "\t" . 'font-display:' . $font_display . ';' . "\n";
			}

			$output .= "\t" . 'font-style: normal;' . "\n";
			$output .= '}' . "\n\n";
		}

		return $output;
	}

	/**
	 * Icons font css.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_icons_font_css() {
		$output                = '';
		$url                   = woodmart_remove_https( WOODMART_THEME_DIR );
		$current_theme_version = woodmart_get_theme_info( 'Version' );

		$font_display = woodmart_get_opt( 'icons_font_display' );

		// Our font.
		if ( apply_filters( 'woodmart_old_icon_font_style', false ) ) {
			$output .= '@font-face {' . "\n";
			$output .= "\t" . 'font-weight: normal;' . "\n";
			$output .= "\t" . 'font-style: normal;' . "\n";
			$output .= "\t" . 'font-family: "woodmart-font";' . "\n";
			$output .= "\t" . 'src: url("' . $url . '/fonts/woodmart-font.eot?v=' . $current_theme_version . '");' . "\n";
			$output .= "\t" . 'src: url("' . $url . '/fonts/woodmart-font.eot?#iefix&v=' . $current_theme_version . '") format("embedded-opentype"),' . "\n";
			$output .= "\t" . 'url("' . $url . '/fonts/woodmart-font.woff?v=' . $current_theme_version . '") format("woff"),' . "\n";
			$output .= "\t" . 'url("' . $url . '/fonts/woodmart-font.woff2?v=' . $current_theme_version . '") format("woff2"),' . "\n";
			$output .= "\t" . 'url("' . $url . '/fonts/woodmart-font.ttf?v=' . $current_theme_version . '") format("truetype"),' . "\n";
			$output .= "\t" . 'url("' . $url . '/fonts/woodmart-font.svg?v=' . $current_theme_version . '#woodmart-font") format("svg");' . "\n";
		} else {
			$output .= '@font-face {' . "\n";
			$output .= "\t" . 'font-weight: normal;' . "\n";
			$output .= "\t" . 'font-style: normal;' . "\n";
			$output .= "\t" . 'font-family: "woodmart-font";' . "\n";
			$output .= "\t" . 'src: url("' . $url . '/fonts/woodmart-font.woff?v=' . $current_theme_version . '") format("woff"),' . "\n";
			$output .= "\t" . 'url("' . $url . '/fonts/woodmart-font.woff2?v=' . $current_theme_version . '") format("woff2");' . "\n";
		}

		if ( 'disable' !== $font_display ) {
			$output .= "\t" . 'font-display:' . $font_display . ';' . "\n";
		}

		$output .= '}' . "\n\n";

		return $output;
	}

	/**
	 * Get custom font url.
	 *
	 * @since 1.0.0
	 *
	 * @param array $font Font data.
	 *
	 * @return string
	 */
	public function get_custom_font_url( $font ) {
		$url = '';

		if ( isset( $font['id'] ) && $font['id'] ) {
			$url = wp_get_attachment_url( $font['id'] );
		} elseif ( is_array( $font ) ) {
			$url = $font['url'];
		}

		return woodmart_remove_https( $url );
	}

	/**
	 * Get theme settings css.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_theme_settings_css() {
		$widgets_scroll = woodmart_get_opt( 'widgets_scroll' );
		$widgets_height = woodmart_get_opt( 'widget_heights' );

		// Quick view.
		$quick_view_width = woodmart_get_opt( 'quickview_width' );

		// Shop popup.
		$shop_popup_width = woodmart_get_opt( 'popup_width' );

		// Age verify.
		$age_verify_width = woodmart_get_opt( 'age_verify_width' );

		// Header banner.
		$header_banner_height        = woodmart_get_opt( 'header_banner_height' );
		$header_banner_height_mobile = woodmart_get_opt( 'header_banner_mobile_height' );

		$site_custom_width     = woodmart_get_opt( 'site_custom_width' );
		$predefined_site_width = woodmart_get_opt( 'site_width' );

		$text_font    = woodmart_get_opt( 'text-font' );
		$primary_font = woodmart_get_opt( 'primary-font' );

		$site_width = '';

		if ( 'full-width' === $predefined_site_width ) {
			$site_width = 1222;
		} elseif ( 'boxed' === $predefined_site_width ) {
			$site_width = 1160;
		} elseif ( 'boxed-2' === $predefined_site_width ) {
			$site_width = 1160;
		} elseif ( 'wide' === $predefined_site_width ) {
			$site_width = 1600;
		} elseif ( 'custom' === $predefined_site_width ) {
			$site_width = $site_custom_width;
		}

		// Default button.
		$default_btn_style       = woodmart_get_opt( 'btns_default_style' );
		$default_btn_color       = 'light' === woodmart_get_opt( 'btns_default_color_scheme' ) ? '#fff' : '#333';
		$default_btn_color_hover = 'light' === woodmart_get_opt( 'btns_default_color_scheme_hover' ) ? '#fff' : '#333';

		// Shop button.
		$shop_btn_style       = woodmart_get_opt( 'btns_shop_style' );
		$shop_btn_color       = 'light' === woodmart_get_opt( 'btns_shop_color_scheme' ) ? '#fff' : '#333';
		$shop_btn_color_hover = 'light' === woodmart_get_opt( 'btns_shop_color_scheme_hover' ) ? '#fff' : '#333';

		// Accent button.
		$accent_btn_style       = woodmart_get_opt( 'btns_accent_style' );
		$accent_btn_color       = 'light' === woodmart_get_opt( 'btns_accent_color_scheme' ) ? '#fff' : '#333';
		$accent_btn_color_hover = 'light' === woodmart_get_opt( 'btns_accent_color_scheme_hover' ) ? '#fff' : '#333';

		// Forms.
		$form_style = woodmart_get_opt( 'form_fields_style' );
		$form_width = woodmart_get_opt( 'form_border_width' );

		ob_start();
		// phpcs:disable
		?>
	:root{
	<?php if ( 'rounded' === $form_style ) : ?>
		--wd-form-brd-radius: 35px;
	<?php endif; ?>

	<?php if ( 'semi-rounded' === $form_style ) : ?>
		--wd-form-brd-radius: 5px;
	<?php endif; ?>

	<?php if ( 'square' === $form_style || 'underlined' === $form_style ) : ?>
		--wd-form-brd-radius: 0px;
	<?php endif; ?>

	--wd-form-brd-width: <?php echo esc_attr( $form_width ); ?>px;

	--btn-default-color: <?php echo esc_attr( $default_btn_color ) ?>;
	--btn-default-color-hover: <?php echo esc_attr( $default_btn_color_hover ) ?>;

	--btn-shop-color: <?php echo esc_attr( $shop_btn_color ) ?>;
	--btn-shop-color-hover: <?php echo esc_attr( $shop_btn_color_hover ) ?>;

	--btn-accent-color: <?php echo esc_attr( $accent_btn_color ) ?>;
	--btn-accent-color-hover: <?php echo esc_attr( $accent_btn_color_hover ) ?>;
	<?php if ( 'flat' === $default_btn_style ) : ?>
		--btn-default-brd-radius: 0px;
		--btn-default-box-shadow: none;
		--btn-default-box-shadow-hover: none;
		--btn-default-box-shadow-active: none;
		--btn-default-bottom: 0px;
	<?php endif; ?>

	<?php if ( 'flat' === $shop_btn_style ) : ?>
		--btn-shop-brd-radius: 0px;
		--btn-shop-box-shadow: none;
		--btn-shop-box-shadow-hover: none;
		--btn-shop-box-shadow-active: none;
		--btn-shop-bottom: 0px;
	<?php endif; ?>

	<?php if ( 'flat' === $accent_btn_style ) : ?>
		--btn-accent-brd-radius: 0px;
		--btn-accent-box-shadow: none;
		--btn-accent-box-shadow-hover: none;
		--btn-accent-box-shadow-active: none;
		--btn-accent-bottom: 0px;
	<?php endif; ?>

	<?php if ( '3d' === $default_btn_style ) : ?>
		--btn-default-bottom-active: -1px;
		--btn-default-brd-radius: 0px;
		--btn-default-box-shadow: inset 0 -2px 0 rgba(0, 0, 0, .15);
		--btn-default-box-shadow-hover: inset 0 -2px 0 rgba(0, 0, 0, .15);
	<?php endif; ?>

	<?php if ( '3d' === $shop_btn_style ) : ?>
		--btn-shop-bottom-active: -1px;
		--btn-shop-brd-radius: 0px;
		--btn-shop-box-shadow: inset 0 -2px 0 rgba(0, 0, 0, .15);
		--btn-shop-box-shadow-hover: inset 0 -2px 0 rgba(0, 0, 0, .15);
	<?php endif; ?>

	<?php if ( '3d' === $accent_btn_style ) : ?>
		--btn-accent-bottom-active: -1px;
		--btn-accent-brd-radius: 0px;
		--btn-accent-box-shadow: inset 0 -2px 0 rgba(0, 0, 0, .15);
		--btn-accent-box-shadow-hover: inset 0 -2px 0 rgba(0, 0, 0, .15);
	<?php endif; ?>

	<?php if ( 'rounded' === $default_btn_style ) : ?>
		--btn-default-brd-radius: 35px;
		--btn-default-box-shadow: none;
		--btn-default-box-shadow-hover: none;
	<?php endif; ?>

	<?php if ( 'rounded' === $shop_btn_style ) : ?>
		--btn-shop-brd-radius: 35px;
		--btn-shop-box-shadow: none;
		--btn-shop-box-shadow-hover: none;
	<?php endif; ?>

	<?php if ( 'rounded' === $accent_btn_style ) : ?>
		--btn-accent-brd-radius: 35px;
		--btn-accent-box-shadow: none;
		--btn-accent-box-shadow-hover: none;
	<?php endif; ?>

	<?php if ( 'semi-rounded' === $default_btn_style ) : ?>
		--btn-default-brd-radius: 5px;
		--btn-default-box-shadow: none;
		--btn-default-box-shadow-hover: none;
	<?php endif; ?>

	<?php if ( 'semi-rounded' === $shop_btn_style ) : ?>
		--btn-shop-brd-radius: 5px;
		--btn-shop-box-shadow: none;
		--btn-shop-box-shadow-hover: none;
	<?php endif; ?>

	<?php if ( 'semi-rounded' === $accent_btn_style ) : ?>
		--btn-accent-brd-radius: 5px;
		--btn-accent-box-shadow: none;
		--btn-accent-box-shadow-hover: none;
	<?php endif; ?>
	}

	/* Site width */
	<?php if ( $site_width ) : ?>
	/* Header Boxed */
	@media (min-width: 1025px) {
		.whb-boxed:not(.whb-sticked):not(.whb-full-width) .whb-main-header {
			max-width: <?php echo esc_html( $site_width - 30 ); ?>px;
		}
	}

	.container {
		max-width: <?php echo esc_html( $site_width ); ?>px;
	}
	<?php endif; ?>

	<?php if ( $site_width && 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ): ?>

	@media (min-width: <?php echo esc_html( $site_width ); ?>px) {

		[data-vc-full-width]:not([data-vc-stretch-content]) {
			padding-left: calc((100vw - <?php echo esc_html( $site_width ); ?>px) / 2);
			padding-right: calc((100vw - <?php echo esc_html( $site_width ); ?>px) / 2);
		}

		.platform-Windows [data-vc-full-width]:not([data-vc-stretch-content]) {
			padding-left: calc((100vw - <?php echo esc_html( $site_width + 17 ); ?>px) / 2);
			padding-right: calc((100vw - <?php echo esc_html( $site_width + 17 ); ?>px) / 2);
		}
	}

	<?php elseif ( $site_width && 'enabled' === woodmart_get_opt( 'negative_gap' ) ) : ?>

		.elementor-section.wd-section-stretch > .elementor-column-gap-no {
			max-width: <?php echo esc_html( $site_width - 30 ); ?>px;
		}

		.elementor-section.wd-section-stretch > .elementor-column-gap-narrow {
			max-width: <?php echo esc_html( $site_width - 30 + 10); ?>px;
		}

		.elementor-section.wd-section-stretch > .elementor-column-gap-default {
			max-width: <?php echo esc_html( $site_width - 30 + 20); ?>px;
		}

		.elementor-section.wd-section-stretch > .elementor-column-gap-extended {
			max-width: <?php echo esc_html( $site_width - 30 + 30); ?>px;
		}

		.elementor-section.wd-section-stretch > .elementor-column-gap-wide {
			max-width: <?php echo esc_html( $site_width - 30 + 40); ?>px;
		}

		.elementor-section.wd-section-stretch > .elementor-column-gap-wider {
			max-width: <?php echo esc_html( $site_width - 30 + 60); ?>px;
		}
		
		@media (min-width: <?php echo esc_html( $site_width + 17 ); ?>px) {

			.platform-Windows .wd-section-stretch > .elementor-container {
				margin-left: auto;
				margin-right: auto;
			}
		}

		@media (min-width: <?php echo esc_html( $site_width ); ?>px) {

			html:not(.platform-Windows) .wd-section-stretch > .elementor-container {
				margin-left: auto;
				margin-right: auto;
			}
		}

	<?php endif; ?>

/* Quick view */
div.wd-popup.popup-quick-view {
	max-width: <?php echo esc_html( $quick_view_width ); ?>px;
}

/* Shop popup */
div.wd-popup.wd-promo-popup {
	max-width: <?php echo esc_html( $shop_popup_width ); ?>px;
}

/* Age verify */
div.wd-popup.wd-age-verify {
	max-width: <?php echo esc_html( $age_verify_width ); ?>px;
}

/* Header Banner */
.header-banner {
	height: <?php echo esc_html( $header_banner_height ); ?>px;
}

body.header-banner-display .website-wrapper {
	margin-top:<?php echo esc_html( $header_banner_height ); ?>px;
}

/* Tablet */
@media (max-width: 1024px) {
	/* header Banner */
	.header-banner {
		height: <?php echo esc_html( $header_banner_height_mobile ); ?>px;
	}
	
	body.header-banner-display .website-wrapper {
		margin-top:<?php echo esc_html( $header_banner_height_mobile ); ?>px;
	}
}

<?php if( $widgets_scroll ): ?>
.woodmart-woocommerce-layered-nav .wd-scroll-content {
	max-height: <?php echo esc_attr( $widgets_height ); ?>px;
}
		<?php endif; ?>

<?php if ( woodmart_get_opt( 'rev_slider_inherit_theme_font' ) ): ?>
		<?php if ( isset( $text_font[0] ) && isset( $text_font[0]['font-family'] ) ): ?>
rs-slides [data-type=text],
rs-slides [data-type=button] {
	font-family: <?php echo esc_html( $text_font[0]['font-family'] ); ?> !important;
}
	<?php endif; ?>

		<?php if ( isset( $primary_font[0] ) && isset( $primary_font[0]['font-family'] ) ): ?>
rs-slides h1[data-type=text],
rs-slides h2[data-type=text],
rs-slides h3[data-type=text],
rs-slides h4[data-type=text],
rs-slides h5[data-type=text],
rs-slides h6[data-type=text] {
	font-family: <?php echo esc_html( $primary_font[0]['font-family'] ); ?> !important;
}
	<?php endif; ?>
		<?php endif; ?>
<?php

		return ob_get_clean();
	}
}
