<?php
/**
 * JS scripts.
 *
 * @version 1.0
 * @package xts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

return array(
	// Global.
	'woodmart-theme'                   => array(
		array(
			'title'     => esc_html__( 'Helpers', 'woodmart' ),
			'name'      => 'woodmart-theme',
			'file'      => '/js/scripts/global/helpers',
			'in_footer' => true,
		),
	),
	'age-verify'                       => array(
		array(
			'title'     => esc_html__( 'Age verify', 'woodmart' ),
			'name'      => 'age-verify',
			'file'      => '/js/scripts/global/ageVerify',
			'in_footer' => true,
		),
	),
	'ajax-search'                      => array(
		array(
			'title'     => esc_html__( 'AJAX search', 'woodmart' ),
			'name'      => 'ajax-search',
			'file'      => '/js/scripts/global/ajaxSearch',
			'in_footer' => true,
		),
	),
	'animations-offset'                => array(
		array(
			'title'     => esc_html__( 'Element animations', 'woodmart' ),
			'name'      => 'animations-offset',
			'file'      => '/js/scripts/global/animationsOffset',
			'in_footer' => true,
		),
	),
	'back-history'                     => array(
		array(
			'title'     => esc_html__( 'Back history button', 'woodmart' ),
			'name'      => 'back-history',
			'file'      => '/js/scripts/global/backHistory',
			'in_footer' => true,
		),
	),
	'btns-tooltips'                    => array(
		array(
			'title'     => esc_html__( 'Tooltips', 'woodmart' ),
			'name'      => 'btns-tooltips',
			'file'      => '/js/scripts/global/btnsToolTips',
			'in_footer' => true,
		),
	),
	'cookies-popup'                    => array(
		array(
			'title'     => esc_html__( 'Cookies popup', 'woodmart' ),
			'name'      => 'cookies-popup',
			'file'      => '/js/scripts/global/cookiesPopup',
			'in_footer' => true,
		),
	),
	'footer'                           => array(
		array(
			'title'     => esc_html__( 'Footer widget title toggle', 'woodmart' ),
			'name'      => 'footer',
			'file'      => '/js/scripts/global/footer',
			'in_footer' => true,
		),
	),
	'hidden-sidebar'                   => array(
		array(
			'title'     => esc_html__( 'Off canvas sidebars', 'woodmart' ),
			'name'      => 'hidden-sidebar',
			'file'      => '/js/scripts/global/hiddenSidebar',
			'in_footer' => true,
		),
	),
	'lazy-loading'                     => array(
		array(
			'title'     => esc_html__( 'Lazy loading', 'woodmart' ),
			'name'      => 'lazy-loading',
			'file'      => '/js/scripts/global/lazyLoading',
			'in_footer' => true,
		),
	),
	'mfp-popup'                        => array(
		array(
			'title'     => esc_html__( 'Magnific popup', 'woodmart' ),
			'name'      => 'mfp-popup',
			'file'      => '/js/scripts/global/mfpPopup',
			'in_footer' => true,
		),
	),
	'owl-carousel'                     => array(
		array(
			'title'     => esc_html__( 'OWL carousel', 'woodmart' ),
			'name'      => 'owl-carousel',
			'file'      => '/js/scripts/global/owlCarouselInit',
			'in_footer' => true,
		),
	),
	'parallax'                         => array(
		array(
			'title'     => esc_html__( 'Background parallax', 'woodmart' ),
			'name'      => 'parallax',
			'file'      => '/js/scripts/global/parallax',
			'in_footer' => true,
		),
	),
	'photoswipe-images'                => array(
		array(
			'title'     => esc_html__( 'Image gallery element photoswipe', 'woodmart' ),
			'name'      => 'photoswipe-images',
			'file'      => '/js/scripts/global/photoswipeImages',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Photoswipe', 'woodmart' ),
			'name'      => 'photoswipe',
			'file'      => '/js/scripts/global/callPhotoSwipe',
			'in_footer' => true,
		),
	),
	'promo-popup'                      => array(
		array(
			'title'     => esc_html__( 'Promo popup', 'woodmart' ),
			'name'      => 'promo-popup',
			'file'      => '/js/scripts/global/promoPopup',
			'in_footer' => true,
		),
	),
	'scroll-top'                       => array(
		array(
			'title'     => esc_html__( 'Scroll to top button', 'woodmart' ),
			'name'      => 'scroll-top',
			'file'      => '/js/scripts/global/scrollTop',
			'in_footer' => true,
		),
	),
	'search-full-screen'               => array(
		array(
			'title'     => esc_html__( 'Search full screen', 'woodmart' ),
			'name'      => 'search-full-screen',
			'file'      => '/js/scripts/global/searchFullScreen',
			'in_footer' => true,
		),
	),
	'sticky-column'                    => array(
		array(
			'title'     => esc_html__( 'Sticky column', 'woodmart' ),
			'name'      => 'sticky-column',
			'file'      => '/js/scripts/global/stickyColumn',
			'in_footer' => true,
		),
	),
	'sticky-footer'                    => array(
		array(
			'title'     => esc_html__( 'Sticky footer', 'woodmart' ),
			'name'      => 'sticky-footer',
			'file'      => '/js/scripts/global/stickyFooter',
			'in_footer' => true,
		),
	),
	'sticky-social-buttons'            => array(
		array(
			'title'     => esc_html__( 'Sticky social buttons', 'woodmart' ),
			'name'      => 'sticky-social-buttons',
			'file'      => '/js/scripts/global/stickySocialButtons',
			'in_footer' => true,
		),
	),
	'widgets-hidable'                  => array(
		array(
			'title'     => esc_html__( 'Widget title toggle', 'woodmart' ),
			'name'      => 'widgets-hidable',
			'file'      => '/js/scripts/global/widgetsHidable',
			'in_footer' => true,
		),
	),
	'masonry-layout'                   => array(
		array(
			'title'     => esc_html__( 'Masonry', 'woodmart' ),
			'name'      => 'masonry-layout',
			'file'      => '/js/scripts/global/masonryLayout',
			'in_footer' => true,
		),
	),
	// Blog.
	'blog-load-more'                   => array(
		array(
			'title'     => esc_html__( 'Blog load more', 'woodmart' ),
			'name'      => 'blog-load-more',
			'file'      => '/js/scripts/blog/blogLoadMore',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Load more button', 'woodmart' ),
			'name'      => 'click-on-scroll-btn',
			'file'      => '/js/scripts/global/clickOnScrollButton',
			'in_footer' => true,
		),
	),
	// Elements.
	'banner-element'                   => array(
		array(
			'title'     => esc_html__( 'Banner element parallax', 'woodmart' ),
			'name'      => 'banner-element',
			'file'      => '/js/scripts/elements/banner',
			'in_footer' => true,
		),
	),
	'button-element'                   => array(
		array(
			'title'     => esc_html__( 'Button element smooth scroll', 'woodmart' ),
			'name'      => 'button-element',
			'file'      => '/js/scripts/elements/button',
			'in_footer' => true,
		),
	),
	'popup-element'                    => array(
		array(
			'title'     => esc_html__( 'Popup element', 'woodmart' ),
			'name'      => 'popup-element',
			'file'      => '/js/scripts/elements/contentPopup',
			'in_footer' => true,
		),
	),
	'countdown-element'                => array(
		array(
			'title'     => esc_html__( 'Countdown element', 'woodmart' ),
			'name'      => 'countdown-element',
			'file'      => '/js/scripts/elements/countDownTimer',
			'in_footer' => true,
		),
	),
	'counter-element'                  => array(
		array(
			'title'     => esc_html__( 'Animated counter element', 'woodmart' ),
			'name'      => 'counter-element',
			'file'      => '/js/scripts/elements/counter',
			'in_footer' => true,
		),
	),
	'google-map-element'               => array(
		array(
			'title'     => esc_html__( 'Google map element', 'woodmart' ),
			'name'      => 'google-map-element',
			'file'      => '/js/scripts/elements/googleMap',
			'in_footer' => true,
		),
	),
	'hotspot-element'                  => array(
		array(
			'title'     => esc_html__( 'Hotspot element', 'woodmart' ),
			'name'      => 'hotspot-element',
			'file'      => '/js/scripts/elements/hotSpot',
			'in_footer' => true,
		),
	),
	'image-gallery-element'            => array(
		array(
			'title'     => esc_html__( 'Image gallery element', 'woodmart' ),
			'name'      => 'image-gallery-element',
			'file'      => '/js/scripts/elements/imageGallery',
			'in_footer' => true,
		),
	),
	'infobox-element'                  => array(
		array(
			'title'     => esc_html__( 'Infobox element SVG animation', 'woodmart' ),
			'name'      => 'infobox-element',
			'file'      => '/js/scripts/elements/infoBox',
			'in_footer' => true,
		),
	),
	'instagram-element'                => array(
		array(
			'title'     => esc_html__( 'Instagram element', 'woodmart' ),
			'name'      => 'instagram-element',
			'file'      => '/js/scripts/elements/instagram',
			'in_footer' => true,
		),
	),
	'slider-element'                   => array(
		array(
			'title'     => esc_html__( 'Slider element', 'woodmart' ),
			'name'      => 'slider-element',
			'file'      => '/js/scripts/elements/slider',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'OWL carousel', 'woodmart' ),
			'name'      => 'owl-carousel',
			'file'      => '/js/scripts/global/owlCarouselInit',
			'in_footer' => true,
		),
	),
	'video-element'                    => array(
		array(
			'title'     => esc_html__( 'Video element', 'woodmart' ),
			'name'      => 'video-element',
			'file'      => '/js/scripts/elements/video',
			'in_footer' => true,
		),
	),
	'view3d-element'                   => array(
		array(
			'title'     => esc_html__( 'View 3D element', 'woodmart' ),
			'name'      => 'view3d-element',
			'file'      => '/js/scripts/elements/view3d',
			'in_footer' => true,
		),
	),
	// Header.
	'header-banner'                    => array(
		array(
			'title'     => esc_html__( 'Header banner', 'woodmart' ),
			'name'      => 'header-banner',
			'file'      => '/js/scripts/header/headerBanner',
			'in_footer' => true,
		),
	),
	'header-builder'                   => array(
		array(
			'title'     => esc_html__( 'Header builder', 'woodmart' ),
			'name'      => 'header-builder',
			'file'      => '/js/scripts/header/headerBuilder',
			'in_footer' => true,
		),
	),
	'mobile-search'                    => array(
		array(
			'title'     => esc_html__( 'Mobile search element', 'woodmart' ),
			'name'      => 'mobile-search',
			'file'      => '/js/scripts/header/mobileSearchIcon',
			'in_footer' => true,
		),
	),
	// Menu.
	'full-screen-menu'                 => array(
		array(
			'title'     => esc_html__( 'Full screen menu', 'woodmart' ),
			'name'      => 'full-screen-menu',
			'file'      => '/js/scripts/menu/fullScreenMenu',
			'in_footer' => true,
		),
	),
	'menu-dropdowns-ajax'              => array(
		array(
			'title'     => esc_html__( 'Menu dropdowns AJAX', 'woodmart' ),
			'name'      => 'menu-dropdowns-ajax',
			'file'      => '/js/scripts/menu/menuDropdownsAJAX',
			'in_footer' => true,
		),
	),
	'menu-offsets'                     => array(
		array(
			'title'     => esc_html__( 'Menu offsets', 'woodmart' ),
			'name'      => 'menu-offsets',
			'file'      => '/js/scripts/menu/menuOffsets',
			'in_footer' => true,
		),
	),
	'menu-setup'                       => array(
		array(
			'title'     => esc_html__( 'Menu element click action', 'woodmart' ),
			'name'      => 'menu-setup',
			'file'      => '/js/scripts/menu/menuSetUp',
			'in_footer' => true,
		),
	),
	'mobile-navigation'                => array(
		array(
			'title'     => esc_html__( 'Mobile navigation', 'woodmart' ),
			'name'      => 'mobile-navigation',
			'file'      => '/js/scripts/menu/mobileNavigation',
			'in_footer' => true,
		),
	),
	'more-categories-button'           => array(
		array(
			'title'     => esc_html__( 'More categories button', 'woodmart' ),
			'name'      => 'more-categories-button',
			'file'      => '/js/scripts/menu/moreCategoriesButton',
			'in_footer' => true,
		),
	),
	'one-page-menu'                    => array(
		array(
			'title'     => esc_html__( 'One page menu', 'woodmart' ),
			'name'      => 'one-page-menu',
			'file'      => '/js/scripts/menu/onePageMenu',
			'in_footer' => true,
		),
	),
	'simple-dropdown'                  => array(
		array(
			'title'     => esc_html__( 'Simple dropdown', 'woodmart' ),
			'name'      => 'simple-dropdown',
			'file'      => '/js/scripts/menu/simpleDropdown',
			'in_footer' => true,
		),
	),
	// Portfolio.
	'ajax-portfolio'                   => array(
		array(
			'title'     => esc_html__( 'Portfolio AJAX', 'woodmart' ),
			'name'      => 'portfolio-portfolio',
			'file'      => '/js/scripts/portfolio/ajaxPortfolio',
			'in_footer' => true,
		),
	),
	'portfolio-effect'                 => array(
		array(
			'title'     => esc_html__( 'Portfolio effect', 'woodmart' ),
			'name'      => 'portfolio-effect',
			'file'      => '/js/scripts/portfolio/portfolioEffect',
			'in_footer' => true,
		),
	),
	'portfolio-load-more'              => array(
		array(
			'title'     => esc_html__( 'Portfolio load more', 'woodmart' ),
			'name'      => 'portfolio-load-more',
			'file'      => '/js/scripts/portfolio/portfolioLoadMore',
			'in_footer' => true,
		),
	),
	'portfolio-photoswipe'             => array(
		array(
			'title'     => esc_html__( 'Portfolio photoswipe', 'woodmart' ),
			'name'      => 'portfolio-photoswipe',
			'file'      => '/js/scripts/portfolio/portfolioPhotoSwipe',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Photoswipe', 'woodmart' ),
			'name'      => 'photoswipe',
			'file'      => '/js/scripts/global/callPhotoSwipe',
			'in_footer' => true,
		),
	),
	'portfolio-wd-nav-portfolios'      => array(
		array(
			'title'     => esc_html__( 'Portfolio masonry filters', 'woodmart' ),
			'name'      => 'portfolio-wd-nav-portfolios',
			'file'      => '/js/scripts/portfolio/portfolioMasonryFilters',
			'in_footer' => true,
		),
	),
	// WC.
	'action-after-add-to-cart'         => array(
		array(
			'title'     => esc_html__( 'Action after add to cart', 'woodmart' ),
			'name'      => 'action-after-add-to-cart',
			'file'      => '/js/scripts/wc/actionAfterAddToCart',
			'in_footer' => true,
		),
	),
	'add-to-cart-all-types'            => array(
		array(
			'title'     => esc_html__( 'Single product AJAX add to cart', 'woodmart' ),
			'name'      => 'add-to-cart-all-types',
			'file'      => '/js/scripts/wc/addToCartAllTypes',
			'in_footer' => true,
		),
	),
	'ajax-filters'                     => array(
		array(
			'title'     => esc_html__( 'AJAX shop', 'woodmart' ),
			'name'      => 'ajax-filters',
			'file'      => '/js/scripts/wc/ajaxFilters',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Sort by widget action', 'woodmart' ),
			'name'      => 'sort-by-widget',
			'file'      => '/js/scripts/wc/sortByWidget',
			'in_footer' => true,
		),
	),
	'cart-widget'                      => array(
		array(
			'title'     => esc_html__( 'Cart widget', 'woodmart' ),
			'name'      => 'cart-widget',
			'file'      => '/js/scripts/wc/cartWidget',
			'in_footer' => true,
		),
	),
	'categories-accordion'             => array(
		array(
			'title'     => esc_html__( 'Categories accordion', 'woodmart' ),
			'name'      => 'categories-accordion',
			'file'      => '/js/scripts/wc/categoriesAccordion',
			'in_footer' => true,
		),
	),
	'categories-dropdown'              => array(
		array(
			'title'     => esc_html__( 'Categories dropdown', 'woodmart' ),
			'name'      => 'categories-dropdown',
			'file'      => '/js/scripts/wc/categoriesDropdowns',
			'in_footer' => true,
		),
	),
	'categories-menu'                  => array(
		array(
			'title'     => esc_html__( 'Categories menu', 'woodmart' ),
			'name'      => 'categories-menu',
			'file'      => '/js/scripts/wc/categoriesMenu',
			'in_footer' => true,
		),
	),
	'comment-image'                    => array(
		array(
			'title'     => esc_html__( 'Single product review images', 'woodmart' ),
			'name'      => 'comment-image',
			'file'      => '/js/scripts/wc/commentImage',
			'in_footer' => true,
		),
	),
	'filter-dropdowns'                 => array(
		array(
			'title'     => esc_html__( 'Layered navigation dropdowns', 'woodmart' ),
			'name'      => 'filter-dropdowns',
			'file'      => '/js/scripts/wc/filterDropdowns',
			'in_footer' => true,
		),
	),
	'filters-area'                     => array(
		array(
			'title'     => esc_html__( 'Shop filters area', 'woodmart' ),
			'name'      => 'filters-area',
			'file'      => '/js/scripts/wc/filtersArea',
			'in_footer' => true,
		),
	),
	'grid-quantity'                    => array(
		array(
			'title'     => esc_html__( 'Quantity on products grid', 'woodmart' ),
			'name'      => 'grid-quantity',
			'file'      => '/js/scripts/wc/gridQuantity',
			'in_footer' => true,
		),
	),
	'header-categories-menu'           => array(
		array(
			'title'     => esc_html__( 'Header categories menu', 'woodmart' ),
			'name'      => 'header-categories-menu',
			'file'      => '/js/scripts/wc/headerCategoriesMenu',
			'in_footer' => true,
		),
	),
	'init-zoom'                        => array(
		array(
			'title'     => esc_html__( 'Single product image zoom', 'woodmart' ),
			'name'      => 'init-zoom',
			'file'      => '/js/scripts/wc/initZoom',
			'in_footer' => true,
		),
	),
	'login-dropdown'                   => array(
		array(
			'title'     => esc_html__( 'Login dropdown', 'woodmart' ),
			'name'      => 'login-dropdown',
			'file'      => '/js/scripts/wc/loginDropdown',
			'in_footer' => true,
		),
	),
	'login-sidebar'                    => array(
		array(
			'title'     => esc_html__( 'Login sidebar', 'woodmart' ),
			'name'      => 'login-sidebar',
			'file'      => '/js/scripts/wc/loginSidebar',
			'in_footer' => true,
		),
	),
	'login-tabs'                       => array(
		array(
			'title'     => esc_html__( 'Login tabs', 'woodmart' ),
			'name'      => 'login-tabs',
			'file'      => '/js/scripts/wc/loginTabs',
			'in_footer' => true,
		),
	),
	'mini-cart-quantity'               => array(
		array(
			'title'     => esc_html__( 'Mini cart quantity', 'woodmart' ),
			'name'      => 'mini-cart-quantity',
			'file'      => '/js/scripts/wc/miniCartQuantity',
			'in_footer' => true,
		),
	),
	'on-remove-from-cart'              => array(
		array(
			'title'     => esc_html__( 'Remove from cart loader', 'woodmart' ),
			'name'      => 'on-remove-from-cart',
			'file'      => '/js/scripts/wc/onRemoveFromCart',
			'in_footer' => true,
		),
	),
	'product-360-button'               => array(
		array(
			'title'     => esc_html__( 'Single product 360 button', 'woodmart' ),
			'name'      => 'product-360-button',
			'file'      => '/js/scripts/wc/product360Button',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'View 3D element', 'woodmart' ),
			'name'      => 'view3d-element',
			'file'      => '/js/scripts/elements/view3d',
			'in_footer' => true,
		),
	),
	'product-accordion'                => array(
		array(
			'title'     => esc_html__( 'Single product accordion', 'woodmart' ),
			'name'      => 'product-accordion',
			'file'      => '/js/scripts/wc/productAccordion',
			'in_footer' => true,
		),
	),
	'product-filters'                  => array(
		array(
			'title'     => esc_html__( 'Product filters', 'woodmart' ),
			'name'      => 'product-filters',
			'file'      => '/js/scripts/wc/productFilters',
			'in_footer' => true,
		),
	),
	'product-hover'                    => array(
		array(
			'title'     => esc_html__( 'Product base hover', 'woodmart' ),
			'name'      => 'product-hover',
			'file'      => '/js/scripts/wc/productHover',
			'in_footer' => true,
		),
	),
	'product-images'                   => array(
		array(
			'title'     => esc_html__( 'Single product image photoswipe', 'woodmart' ),
			'name'      => 'product-images',
			'file'      => '/js/scripts/wc/productImages',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Photoswipe', 'woodmart' ),
			'name'      => 'photoswipe',
			'file'      => '/js/scripts/global/callPhotoSwipe',
			'in_footer' => true,
		),
	),
	'product-images-gallery'           => array(
		array(
			'title'     => esc_html__( 'Single product image gallery', 'woodmart' ),
			'name'      => 'product-images-gallery',
			'file'      => '/js/scripts/wc/productImagesGallery',
			'in_footer' => true,
		),
	),
	'product-more-description'         => array(
		array(
			'title'     => esc_html__( 'Product more description', 'woodmart' ),
			'name'      => 'product-more-description',
			'file'      => '/js/scripts/wc/productMoreDescription',
			'in_footer' => true,
		),
	),
	'products-load-more'               => array(
		array(
			'title'     => esc_html__( 'Product load more', 'woodmart' ),
			'name'      => 'products-load-more',
			'file'      => '/js/scripts/wc/productsLoadMore',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Load more button', 'woodmart' ),
			'name'      => 'click-on-scroll-btn',
			'file'      => '/js/scripts/global/clickOnScrollButton',
			'in_footer' => true,
		),
	),
	'products-tabs'                    => array(
		array(
			'title'     => esc_html__( 'Single product tabs', 'woodmart' ),
			'name'      => 'products-tabs',
			'file'      => '/js/scripts/wc/productsTabs',
			'in_footer' => true,
		),
	),
	'product-video'                    => array(
		array(
			'title'     => esc_html__( 'Single product video button', 'woodmart' ),
			'name'      => 'product-video',
			'file'      => '/js/scripts/wc/productVideo',
			'in_footer' => true,
		),
	),
	'quick-shop'                       => array(
		array(
			'title'     => esc_html__( 'Quick shop', 'woodmart' ),
			'name'      => 'quick-shop',
			'file'      => '/js/scripts/wc/quickShop',
			'in_footer' => true,
		),
	),
	'quick-view'                       => array(
		array(
			'title'     => esc_html__( 'Quick view', 'woodmart' ),
			'name'      => 'quick-view',
			'file'      => '/js/scripts/wc/quickView',
			'in_footer' => true,
		),
	),
	'shop-loader'                      => array(
		array(
			'title'     => esc_html__( 'Shop loader', 'woodmart' ),
			'name'      => 'shop-loader',
			'file'      => '/js/scripts/wc/shopLoader',
			'in_footer' => true,
		),
	),
	'shop-masonry'                     => array(
		array(
			'title'     => esc_html__( 'Shop masonry', 'woodmart' ),
			'name'      => 'shop-masonry',
			'file'      => '/js/scripts/wc/shopMasonry',
			'in_footer' => true,
		),
	),
	'shop-page-init'                   => array(
		array(
			'title'     => esc_html__( 'Shop page init', 'woodmart' ),
			'name'      => 'shop-page-init',
			'file'      => '/js/scripts/wc/shopPageInit',
			'in_footer' => true,
		),
		array(
			'title'     => esc_html__( 'Load more button', 'woodmart' ),
			'name'      => 'click-on-scroll-btn',
			'file'      => '/js/scripts/global/clickOnScrollButton',
			'in_footer' => true,
		),
	),
	'single-product-tabs-accordion'    => array(
		array(
			'title'     => esc_html__( 'Single product tabs accordion', 'woodmart' ),
			'name'      => 'single-product-tabs-accordion',
			'file'      => '/js/scripts/wc/singleProductTabsAccordion',
			'in_footer' => true,
		),
	),
	'single-product-tabs-comments-fix' => array(
		array(
			'title'     => esc_html__( 'Single product tab comments url hash', 'woodmart' ),
			'name'      => 'single-product-tabs-comments-fix',
			'file'      => '/js/scripts/wc/singleProductTabsCommentsFix',
			'in_footer' => true,
		),
	),
	'sticky-add-to-cart'               => array(
		array(
			'title'     => esc_html__( 'Single product sticky add to cart', 'woodmart' ),
			'name'      => 'sticky-add-to-cart',
			'file'      => '/js/scripts/wc/stickyAddToCart',
			'in_footer' => true,
		),
	),
	'sticky-details'                   => array(
		array(
			'title'     => esc_html__( 'Single product sticky details', 'woodmart' ),
			'name'      => 'sticky-details',
			'file'      => '/js/scripts/wc/stickyDetails',
			'in_footer' => true,
		),
	),
	'sticky-sidebar-btn'               => array(
		array(
			'title'     => esc_html__( 'Sticky sidebar button', 'woodmart' ),
			'name'      => 'sticky-sidebar-btn',
			'file'      => '/js/scripts/wc/stickySidebarBtn',
			'in_footer' => true,
		),
	),
	'swatches-limit'                   => array(
		array(
			'title'     => esc_html__( 'Swatches limit', 'woodmart' ),
			'name'      => 'swatches-limit',
			'file'      => '/js/scripts/wc/swatchesLimit',
			'in_footer' => true,
		),
	),
	'swatches-on-grid'                 => array(
		array(
			'title'     => esc_html__( 'Swatches on grid', 'woodmart' ),
			'name'      => 'swatches-on-grid',
			'file'      => '/js/scripts/wc/swatchesOnGrid',
			'in_footer' => true,
		),
	),
	'swatches-variations'              => array(
		array(
			'title'     => esc_html__( 'Swatches variations', 'woodmart' ),
			'name'      => 'swatches-variations',
			'file'      => '/js/scripts/wc/swatchesVariations',
			'in_footer' => true,
		),
	),
	'variations-price'                 => array(
		array(
			'title'     => esc_html__( 'Variations price', 'woodmart' ),
			'name'      => 'variations-price',
			'file'      => '/js/scripts/wc/variationsPrice',
			'in_footer' => true,
		),
	),
	'wishlist'                         => array(
		array(
			'title'     => esc_html__( 'Wishlist', 'woodmart' ),
			'name'      => 'wishlist',
			'file'      => '/js/scripts/wc/wishlist',
			'in_footer' => true,
		),
	),
	'woodmart-compare'                 => array(
		array(
			'title'     => esc_html__( 'Compare', 'woodmart' ),
			'name'      => 'woodmart-compare',
			'file'      => '/js/scripts/wc/woodmartCompare',
			'in_footer' => true,
		),
	),
	'woocommerce-comments'             => array(
		array(
			'title'     => esc_html__( 'WooCommerce comments', 'woodmart' ),
			'name'      => 'woocommerce-comments',
			'file'      => '/js/scripts/wc/woocommerceComments',
			'in_footer' => true,
		),
	),
	'woocommerce-notices'              => array(
		array(
			'title'     => esc_html__( 'WooCommerce notices', 'woodmart' ),
			'name'      => 'woocommerce-notices',
			'file'      => '/js/scripts/wc/woocommerceNotices',
			'in_footer' => true,
		),
	),
	'woocommerce-price-slider'         => array(
		array(
			'title'     => esc_html__( 'WooCommerce price slider', 'woodmart' ),
			'name'      => 'woocommerce-price-slider',
			'file'      => '/js/scripts/wc/woocommercePriceSlider',
			'in_footer' => true,
		),
	),
	'woocommerce-quantity'             => array(
		array(
			'title'     => esc_html__( 'WooCommerce quantity', 'woodmart' ),
			'name'      => 'woocommerce-quantity',
			'file'      => '/js/scripts/wc/woocommerceQuantity',
			'in_footer' => true,
		),
	),
	'woocommerce-wrapp-table'          => array(
		array(
			'title'     => esc_html__( 'WooCommerce responsive table', 'woodmart' ),
			'name'      => 'woocommerce-wrapp-table',
			'file'      => '/js/scripts/wc/woocommerceWrappTable',
			'in_footer' => true,
		),
	),
);
