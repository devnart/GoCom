<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
 * ------------------------------------------------------------------------------------------------
 * Execute after import
 * ------------------------------------------------------------------------------------------------
 */

if ( ! class_exists( 'WOODMART_ImportVersion_base' ) ) :
	class WOODMART_ImportVersion_base extends WOODMART_ImportVersion {

		public $shop_page_id;
		public $menu_id;

		public function before() {

		}

		public function after() {
			$this->menu_locations();
			$this->blog_page();
			$this->shop_page();
			$this->shop_menu();
			$this->categories_images();
			$this->set_attribute_terms_colors();
			$this->set_attribute_terms_images();
			$this->enable_VC();
			$this->configure_elementor();
			$this->show_all_fields_menu();
			$this->size_guide();
			$this->enable_myaccount_registration();
			$this->update_product_lookup_tables();
			$this->woo_pages_sidebar();
		}
		
		public function woo_pages_sidebar() {
			$pages = apply_filters( 'woocommerce_create_pages', array(
				'cart'     => array(
					'name'    => _x( 'cart', 'Page slug', 'woodmart' ),
					'title'   => _x( 'Cart', 'Page title', 'woodmart' ),
					'content' => '[' . apply_filters( 'woocommerce_cart_shortcode_tag', 'woocommerce_cart' ) . ']'
				),
				'checkout' => array(
					'name'    => _x( 'checkout', 'Page slug', 'woodmart' ),
					'title'   => _x( 'Checkout', 'Page title', 'woodmart' ),
					'content' => '[' . apply_filters( 'woocommerce_checkout_shortcode_tag', 'woocommerce_checkout' ) . ']'
				),
			) );
			
			foreach ( $pages as $key => $page ) {
				$option  = 'woocommerce_' . $key . '_page_id';
				$page_id = get_option( $option );
				update_post_meta( $page_id, '_woodmart_main_layout', 'full-width' );
			}
			
			update_option( 'woocommerce_single_image_width', '1200' ); 		// Single product image
			update_option( 'woocommerce_thumbnail_image_width', '600' ); 	// Gallery and catalog image
		}
		
		public function update_product_lookup_tables() {
			if ( ! wc_update_product_lookup_tables_is_running() ) {
				wc_update_product_lookup_tables();
			}
		}

		public function menu_locations() {
			global $wpdb;

			$location        = 'main-menu';
			$mobile_location = 'mobile-menu';

			$tablename = $wpdb->prefix . 'terms';
			$menu_ids  = $wpdb->get_results(
				'
		    SELECT term_id, name
		    FROM ' . $tablename . " 
		    WHERE name IN ('Main navigation', 'Mobile navigation', 'Categories')
		    ORDER BY name ASC
		    "
			);

			$locations = get_theme_mod( 'nav_menu_locations' );

			foreach ( $menu_ids as $menu ) {
				if ( $menu->name == 'Main navigation' ) {
					$this->menu_id = $menu->term_id;
					if ( ! has_nav_menu( $location ) ) {
						$locations[ $location ] = $this->menu_id;
					}
				}

				if ( $menu->name == 'Mobile navigation' ) {
					if ( ! has_nav_menu( $mobile_location ) ) {
						$locations[ $mobile_location ] = $menu->term_id;
					}
				}
			}

			set_theme_mod( 'nav_menu_locations', $locations );

		}

		public function blog_page() {
			// Add blog item to the menu
			$blog_page_title = 'Blog';
			$blog_page       = get_page_by_title( $blog_page_title );
			if ( ! is_null( $blog_page ) ) {
				update_option( 'page_for_posts', $blog_page->ID );
				update_option( 'show_on_front', 'page' );
			}

			// Move Hello World post to trash
			wp_trash_post( 1 );

			// Move Sample Page to trash
			wp_trash_post( 2 );
		}

		public function shop_page() {
			// Setup shop page
			$this->shop_page_id = $this->add_menu_item_by_title(
				'Shop',
				2,
				false,
				'main',
				false,
				array(
					'block'  => '201',
					'design' => 'full-width',
				)
			);

			$shop_heading_image_id = 363;
			$url                   = wp_get_attachment_url( $shop_heading_image_id );

			$shop_metas = array(
				// '_woodmart_page-title-size' => 'small',
				'_woodmart_title_image' => $url,
			);

			foreach ( $shop_metas as $key => $value ) {
				update_post_meta( $this->shop_page_id, $key, $value );
			}

		}

		public function shop_menu() {

		}

		public function categories_images() {
			$categories    = array( 'Accessories', 'Clocks', 'Cooking', 'Furniture', 'Lighting', 'Toys' );
			$attachment_id = 117;
			foreach ( $categories as $cat ) {
				$cat = get_term_by( 'name', $cat, 'product_cat' );
				add_term_meta( $cat->term_id, 'thumbnail_id', $attachment_id );
			}
		}

		public function set_attribute_terms_colors() {
			global $wpdb;
			$terms      = array(
				'Beige' => '#f0e8c4',
				'Black' => '#000000',
				'Brown' => '#ad6424',
			);
			$product_id = 53;
			$post_terms = array();
			foreach ( $terms as $term_name => $color ) {
				$term = get_term_by( 'name', $term_name, 'pa_color' );
				add_term_meta( $term->term_id, 'color', $color, true );
				$post_terms[] = $term->term_id;
			}

			wp_set_object_terms( $product_id, $post_terms, 'pa_color' );

			foreach ( $post_terms as $value ) {
				$wpdb->update(
					$wpdb->term_taxonomy,
					array(
						'count' => 1,   // string
					),
					array( 'term_id' => $value ),
					array(
						'%d',
					),
					array( '%d' )
				);
			}
			delete_transient( 'wc_term_counts' );

			// $this->response->add_msg( 'Terms updated' );
		}

		public function set_attribute_terms_images() {
			global $wpdb;

			$terms = array(
				'pa_brand'    => array(
					'Magisso'       => array(
						'image' => wp_get_attachment_url( 71 ),
					),
					'Alessi'        => array(
						'image' => wp_get_attachment_url( 63 ),
					),
					'Eva Solo'      => array(
						'image' => wp_get_attachment_url( 64 ),
					),
					'Flos'          => array(
						'image' => wp_get_attachment_url( 65 ),
					),
					'Joseph Joseph' => array(
						'image' => wp_get_attachment_url( 67 ),
					),
					'Hay'           => array(
						'image' => wp_get_attachment_url( 66 ),
					),
					'KLÖBER'        => array(
						'image' => wp_get_attachment_url( 68 ),
					),
					'Louis Poulsen' => array(
						'image' => wp_get_attachment_url( 70 ),
					),
					'Vitra'         => array(
						'image' => wp_get_attachment_url( 64 ),
					),
				),
				'product_cat' => array(
					'Accessories' => array(
						'category_icon_alt' => wp_get_attachment_url( 161 ),
						'category_icon'     => wp_get_attachment_url( 166 ),
					),
					'Clocks'      => array(
						'category_icon_alt' => wp_get_attachment_url( 162 ),
						'category_icon'     => wp_get_attachment_url( 164 ),
					),
					'Cooking'     => array(
						'category_icon_alt' => wp_get_attachment_url( 163 ),
						'category_icon'     => wp_get_attachment_url( 165 ),
					),
					'Furniture'   => array(
						'category_icon_alt' => wp_get_attachment_url( 161 ),
						'category_icon'     => wp_get_attachment_url( 166 ),
					),
					'Lighting'    => array(
						'category_icon_alt' => wp_get_attachment_url( 162 ),
						'category_icon'     => wp_get_attachment_url( 164 ),
					),
					'Toys'        => array(
						'category_icon_alt' => wp_get_attachment_url( 163 ),
						'category_icon'     => wp_get_attachment_url( 165 ),
					),
				),
			);

			foreach ( $terms as $attr_name => $data ) {
				foreach ( $data as $term_name => $meta_values ) {
					$term = get_term_by( 'name', $term_name, $attr_name );
					foreach ( $meta_values as $key => $value ) {
						add_term_meta( $term->term_id, $key, $value, true );
					}
				}
			}

			// $this->response->add_msg( 'Terms updated' );
		}

		public function enable_VC() {
			if ( ! function_exists( 'vc_path_dir' ) ) {
				return;
			}
			$file = vc_path_dir( 'SETTINGS_DIR', 'class-vc-roles.php' );
			if ( ! file_exists( $file ) ) {
				return;
			}
			require_once $file;
			if ( ! class_exists( 'Vc_Roles' ) ) {
				return;
			}
			$vc_roles = new Vc_Roles();
			$data     = $vc_roles->save(
				array(
					'administrator' => json_decode( '{"post_types":{"_state":"custom","post":"1","page":"1","woodmart_slide":"1","woodmart_size_guide":"1","cms_block":"1","woodmart_sidebar":"0","portfolio":"1","product":"1"},"backend_editor":{"_state":"1","disabled_ce_editor":"0"},"frontend_editor":{"_state":"1"},"post_settings":{"_state":"1"},"settings":{"_state":"1"},"templates":{"_state":"1"},"shortcodes":{"_state":"1"},"grid_builder":{"_state":"1"},"presets":{"_state":"1"}}' ),
				)
			);
			// echo json_encode( $data );
		}

		public function configure_elementor() {
			$post_types = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
			$post_types[] = 'product';
			$post_types[] = 'portfolio';
			$post_types[] = 'cms_block';
			$post_types[] = 'woodmart_slide';

			update_option( 'elementor_cpt_support', $post_types );
			update_option( 'elementor_disable_color_schemes', 'yes' );
			update_option( 'elementor_disable_typography_schemes', 'yes' );
		}

		public function show_all_fields_menu() {
			$user_id = 1;
			update_user_meta( $user_id, 'managenav-menuscolumnshidden', array() );
			update_user_meta( $user_id, 'metaboxhidden_nav-menus', array() );
		}

		public function size_guide() {
			$selected_sguide_category = array(
				40,
				38,
				39,
				36,
				37,
				35,
			);
			$sguide_id                = 1217;
			if ( function_exists( 'update_woocommerce_term_meta' ) ) {
				foreach ( $selected_sguide_category as $selected_sguide_cat ) {
					update_woocommerce_term_meta( $selected_sguide_cat, 'woodmart_chosen_sguide', $sguide_id );
				}
			}
		}

		public function enable_myaccount_registration() {
			update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
		}
	}

endif;
