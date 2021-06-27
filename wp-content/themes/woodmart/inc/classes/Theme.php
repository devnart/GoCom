<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

class WOODMART_Theme {
	private $register_classes = array();

	public function __construct() {
		$this->register_classes = array(
			'notices',
			'options',
			'layout',
			'import',
			'vctemplates',
			'api',
			'license',
			'wpbcssgenerator',
			'themesettingscss',
			'pagecssfiles',
		);

		$this->core_plugin_classes();
		$this->general_files_include();
		$this->wpb_files_include();
		$this->register_classes();
		$this->wpb_element_files_include();
		$this->shortcodes_files_include();

		if ( is_admin() ) {
			$this->admin_files_include();
		}

		add_action( 'init', array( $this, 'elementor_files_include' ), 20 );
	}

	private function general_files_include() {
		$files = array(
			'helpers',
			'functions',
			'template-tags/template-tags',
			'template-tags/portfolio',
			'theme-setup',
			'enqueue',

			'classes/Singleton',
			'classes/Googlefonts',
			'classes/Stylesstorage',

			'widgets/widgets',

			// General modules
			'modules/lazy-loading',
			'modules/search',
			'modules/mobile-optimization',
			'modules/nav-menu-images/nav-menu-images',
			'modules/sticky-toolbar',
			'modules/white-label',

			// Header builder
			'builder/Builder',
			'builder/Frontend',
			'builder/functions',

			// Woocommerce integration
			'integrations/woocommerce/functions',
			'integrations/woocommerce/helpers',
			'integrations/woocommerce/template-tags',

			// Woocommerce modules.
			'integrations/woocommerce/modules/attributes-meta-boxes',
			'integrations/woocommerce/modules/product-360-view',
			'integrations/woocommerce/modules/size-guide',
			'integrations/woocommerce/modules/swatches',
			'integrations/woocommerce/modules/catalog-mode',
			'integrations/woocommerce/modules/maintenance',
			'integrations/woocommerce/modules/progress-bar',
			'integrations/woocommerce/modules/quick-shop',
			'integrations/woocommerce/modules/quick-view',
			'integrations/woocommerce/modules/brands',
			'integrations/woocommerce/modules/compare',
			'integrations/woocommerce/modules/quantity',
			'integrations/woocommerce/modules/wishlist/class-wc-wishlist',
			'integrations/woocommerce/modules/class-adjacent-products',
			'integrations/woocommerce/modules/comment-images/class-wc-comment-images',

			// Plugin integrations.
			'integrations/wcmp',
			'integrations/wcfm',
			'integrations/wcfmmp',
			'integrations/imagify',
			'integrations/yith-compare',
			'integrations/yith-wishlist',
			'integrations/dokan',
			'integrations/tgm-plugin-activation',

			'options/class-field',
			'options/class-metabox',
			'options/class-metaboxes',
			'options/class-presets',
			'options/class-options',
			'options/class-sanitize',
			'options/class-page',

			'options/controls/background/class-background',
			'options/controls/buttons/class-buttons',
			'options/controls/checkbox/class-checkbox',
			'options/controls/color/class-color',
			'options/controls/custom-fonts/class-custom-fonts',
			'options/controls/editor/class-editor',
			'options/controls/image-dimensions/class-image-dimensions',
			'options/controls/instagram-api/class-instagram-api',
			'options/controls/notice/class-notice',
			'options/controls/import/class-import',
			'options/controls/range/class-range',
			'options/controls/select/class-select',
			'options/controls/switcher/class-switcher',
			'options/controls/text-input/class-text-input',
			'options/controls/textarea/class-textarea',
			'options/controls/typography/google-fonts',
			'options/controls/typography/class-typography',
			'options/controls/upload/class-upload',
			'options/controls/upload-list/class-upload-list',
			'options/controls/color/class-color',
			'options/controls/reset/class-reset',

			'admin/settings/general',
			'admin/settings/general-layout',
			'admin/settings/page-title',
			'admin/settings/footer',
			'admin/settings/typography',
			'admin/settings/colors',
			'admin/settings/blog',
			'admin/settings/portfolio',
			'admin/settings/shop',
			'admin/settings/product',
			'admin/settings/login',
			'admin/settings/custom-css',
			'admin/settings/custom-js',
			'admin/settings/social',
			'admin/settings/performance',
			'admin/settings/other',
			'admin/settings/maintenance',
			'admin/settings/white-label',
			'admin/settings/import',

			'admin/metaboxes/pages',
			'admin/metaboxes/products',
			'admin/metaboxes/slider',

			'integrations/woocommerce/modules/variation-gallery',
			'integrations/woocommerce/modules/variation-gallery-new',
		);

		if ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
			$wpml = array(
				'integrations/wpml/wpml',
				'integrations/wpml/class-wpml-elementor-wd-testimonials',
				'integrations/wpml/class-wpml-elementor-wd-banner-carousel',
				'integrations/wpml/class-wpml-elementor-wd-extra-menu-list',
				'integrations/wpml/class-wpml-elementor-wd-image-hotspot',
				'integrations/wpml/class-wpml-elementor-wd-pricing-tables',
				'integrations/wpml/class-wpml-elementor-wd-list',
				'integrations/wpml/class-wpml-elementor-wd-product-filters',
				'integrations/wpml/class-wpml-elementor-wd-timeline',
				'integrations/wpml/class-wpml-elementor-wd-infobox-carousel',
				'integrations/wpml/class-wpml-elementor-wd-products-tabs',
			);

			$files = array_merge( $files, $wpml );
		}

		foreach ( $files as $file ) {
			$path = get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php' );
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	private function register_classes() {
		foreach ( $this->register_classes as $class ) {
			WOODMART_Registry::getInstance()->$class;
		}
	}

	public function elementor_files_include() {
		if ( ! did_action( 'elementor/loaded' ) || 'elementor' !== woodmart_get_opt( 'page_builder', 'wpb' ) ) {
			return;
		}

		$files = array(
			'integrations/elementor/elementor',
			'integrations/elementor/elements/class-title',
			'integrations/elementor/elements/class-images-gallery',
			'integrations/elementor/elements/class-slider',
			'integrations/elementor/elements/class-extra-menu-list',
			'integrations/elementor/elements/class-3d-view',
			'integrations/elementor/elements/class-search',
			'integrations/elementor/elements/class-counter',
			'integrations/elementor/elements/class-author-area',
			'integrations/elementor/elements/class-countdown',
			'integrations/elementor/elements/class-list',
			'integrations/elementor/elements/class-twitter',
			'integrations/elementor/elements/class-social',
			'integrations/elementor/elements/class-team-member',
			'integrations/elementor/elements/class-testimonials',
			'integrations/elementor/elements/class-mega-menu',
			'integrations/elementor/elements/class-menu-price',
			'integrations/elementor/elements/class-menu-anchor',
			'integrations/elementor/elements/class-popup',
			'integrations/elementor/elements/class-pricing-tables',
			'integrations/elementor/elements/class-timeline',
			'integrations/elementor/elements/class-google-map',
			'integrations/elementor/elements/class-image-hotspot',
			'integrations/elementor/elements/class-contact-form-7',
			'integrations/elementor/elements/class-mailchimp',
			'integrations/elementor/elements/button/class-button',
			'integrations/elementor/elements/button/button',
			'integrations/elementor/elements/button/global-button',
			'integrations/elementor/elements/blog/class-blog',
			'integrations/elementor/elements/blog/blog',
			'integrations/elementor/elements/banner/banner',
			'integrations/elementor/elements/banner/class-banner',
			'integrations/elementor/elements/banner/class-banner-carousel',
			'integrations/elementor/elements/infobox/infobox',
			'integrations/elementor/elements/infobox/class-infobox',
			'integrations/elementor/elements/infobox/class-infobox-carousel',
			'integrations/elementor/elements/instagram/class-instagram',
			'integrations/elementor/elements/instagram/instagram',
			'integrations/elementor/elements/portfolio/class-portfolio',
			'integrations/elementor/elements/portfolio/portfolio',

			'integrations/elementor/default-elements/column',
			'integrations/elementor/default-elements/common',
			'integrations/elementor/default-elements/section',
			'integrations/elementor/default-elements/text-editor',
			'integrations/elementor/default-elements/accordion',
			'integrations/elementor/default-elements/video',

			'integrations/elementor/controls/class-autocomplete',
			'integrations/elementor/controls/class-buttons',
			'integrations/elementor/controls/class-google-json',

			'integrations/elementor/template-library/class-xts-library-source',
			'integrations/elementor/template-library/class-xts-library',
		);

		$woo_files = array(
			'integrations/elementor/elements/class-size-guide',
			'integrations/elementor/elements/products/class-products',
			'integrations/elementor/elements/products/products',
			'integrations/elementor/elements/products-tabs/class-products-tabs',
			'integrations/elementor/elements/products-tabs/products-tabs',
			'integrations/elementor/elements/class-product-filters',
			'integrations/elementor/elements/class-wishlist',
			'integrations/elementor/elements/class-compare',
			'integrations/elementor/elements/class-product-categories',
			'integrations/elementor/elements/class-products-brands',
			'integrations/elementor/elements/class-widget-products',
		);

		if ( woodmart_woocommerce_installed() ) {
			$files = array_merge( $files, $woo_files );
		}

		foreach ( $files as $file ) {
			$path = get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php' );
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	private function wpb_files_include() {
		if ( 'wpb' !== woodmart_get_opt( 'page_builder', 'wpb' ) ) {
			return;
		}

		$files = array(
			'integrations/visual-composer/functions',
			'integrations/visual-composer/fields/vc-functions',
			'integrations/visual-composer/fields/image-hotspot',
			'integrations/visual-composer/fields/title-divider',
			'integrations/visual-composer/fields/slider',
			'integrations/visual-composer/fields/responsive-size',
			'integrations/visual-composer/fields/image-select',
			'integrations/visual-composer/fields/dropdown',
			'integrations/visual-composer/fields/css-id',
			'integrations/visual-composer/fields/gradient',
			'integrations/visual-composer/fields/colorpicker',
			'integrations/visual-composer/fields/datepicker',
			'integrations/visual-composer/fields/switch',
			'integrations/visual-composer/fields/button-set',
			'integrations/visual-composer/fields/empty-space',
		);

		foreach ( $files as $file ) {
			$path = get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php' );
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	private function wpb_element_files_include() {
		$files = array(
			'social',
			'info-box',
			'button',
			'author-area',
			'promo-banner',
			'instagram',
			'user-panel',
			'images-gallery',
			'size-guide',
		);

		$woo_files = array(
			'products-tabs',
			'brands',
			'categories',
			'product-filters',
			'products',
			'products-widget',
		);

		$wpb_files = array(
			'parallax-scroll',
			'3d-view',
			'products-tabs',
			'ajax-search',
			'counter',
			'blog',
			'brands',
			'countdown-timer',
			'extra-menu',
			'google-map',
			'image-hotspot',
			'list',
			'mega-menu',
			'menu-price',
			'popup',
			'portfolio',
			'pricing-tables',
			'categories',
			'product-filters',
			'products',
			'responsive-text-block',
			'title',
			'row-divider',
			'slider',
			'team-member',
			'testimonials',
			'timeline',
			'twitter',
			'products-widget',
			'video-poster',
			'compare',
			'wishlist',
		);

		if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
			$files = array_merge( $files, $wpb_files );

			if ( ! woodmart_woocommerce_installed() ) {
				$files = array_diff( $files, $woo_files );
			}
		}

		foreach ( $files as $file ) {
			$path = get_template_directory() . '/inc/integrations/visual-composer/maps/' . $file . '.php';
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	private function shortcodes_files_include() {
		$files = array(
			'social',
			'html-block',
			'products',
			'info-box',
			'button',
			'author-area',
			'promo-banner',
			'instagram',
			'user-panel',
			'posts-slider',
			'slider',
			'images-gallery',
			'size-guide',
			'blog',
			'gallery',
		);

		$wpb_files = array(
			'3d-view',
			'ajax-search',
			'countdown-timer',
			'counter',
			'extra-menu',
			'google-map',
			'mega-menu',
			'menu-price',
			'popup',
			'portfolio',
			'pricing-tables',
			'responsive-text-block',
			'row-divider',
			'team-member',
			'testimonials',
			'timeline',
			'title',
			'twitter',
			'list',
			'image-hotspot',
			'products-tabs',
			'brands',
			'categories',
			'product-filters',
			'products-widget',
		);

		$woo_files = array(
			'products-tabs',
			'brands',
			'categories',
			'product-filters',
			'products',
			'products-widget',
			'size-guide',
		);

		if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) ) {
			$files = array_merge( $files, $wpb_files );

			if ( ! woodmart_woocommerce_installed() ) {
				$files = array_diff( $files, $woo_files );
			}
		}

		foreach ( $files as $file ) {
			$path = get_template_directory() . '/inc/shortcodes/' . $file . '.php';

			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	private function admin_files_include() {
		$files = array(
			'builder/Builder',
			'builder/Backend',
			'admin/dashboard/dashboard',
			'admin/init',
			'admin/functions',
		);

		foreach ( $files as $file ) {
			$path = get_parent_theme_file_path( WOODMART_FRAMEWORK . '/' . $file . '.php' );
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}

	private function core_plugin_classes() {
		if ( class_exists( 'WOODMART_Auth' ) ) {
			$files = array(
				'vendor/opauth/twitteroauth/twitteroauth',
				'vendor/autoload',
			);

			foreach ( $files as $file ) {
				$path = apply_filters( 'woodmart_require', WOODMART_PT_3D . $file . '.php' );
				if ( file_exists( $path ) ) {
					require_once $path;
				}
			}

			$this->register_classes[] = 'auth';
		}
	}
}
