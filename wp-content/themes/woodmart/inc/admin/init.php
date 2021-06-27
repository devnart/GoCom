<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue admin scripts
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_admin_scripts' ) ) {
	function woodmart_admin_scripts() {
		$version = woodmart_get_theme_info( 'Version' );

		wp_enqueue_script( 'woodmart-admin-scripts', WOODMART_ASSETS . '/js/admin.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-admin-options', WOODMART_ASSETS . '/js/options.js', array(), $version, true );

		$std_fonts = woodmart_get_config( 'standard-fonts' );
		$custom_fonts_data = woodmart_get_opt( 'multi_custom_fonts' );
		$custom_fonts      = array();
		if ( isset( $custom_fonts_data['{{index}}'] ) ) {
			unset( $custom_fonts_data['{{index}}'] );
		}

		if ( is_array( $custom_fonts_data ) ) {
			foreach ( $custom_fonts_data as $font ) {
				if ( ! $font['font-name'] ) {
					continue;
				}

				$custom_fonts[ $font['font-name'] ] = $font['font-name'];
			}
		}

		$typekit_fonts = woodmart_get_opt( 'typekit_fonts' );

		if ( $typekit_fonts ) {
			$typekit = explode( ',', $typekit_fonts );
			foreach ( $typekit as $font ) {
				$custom_fonts[ ucfirst( trim( $font ) ) ] = trim( $font );
			}
		}

		wp_localize_script(
			'woodmart-admin-options',
			'xtsTypography',
			array(
				'stdfonts'    => $std_fonts,
				'googlefonts' => XTS\Google_Fonts::$all_google_fonts,
				'customFonts' => $custom_fonts,
			)
		);

		if ( apply_filters( 'woodmart_gradients_enabled', true ) ) {
			wp_enqueue_script( 'woodmart-colorpicker-scripts', WOODMART_ASSETS . '/js/colorpicker.min.js', array(), $version, true );
			wp_enqueue_script( 'woodmart-gradient-scripts', WOODMART_ASSETS . '/js/gradX.min.js', array(), $version, true );
		}

		if ( woodmart_get_opt( 'size_guides' ) ) {
			wp_enqueue_script( 'woodmart-edittable-scripts', WOODMART_ASSETS . '/js/jquery.edittable.min.js', array(), $version, true );
		}

		// Slider
		wp_enqueue_script( 'jquery-ui-slider' );

		// Datepicker
		if ( ! isset( $_GET['page'] ) || ( isset( $_GET['page'] ) && 'rp_wcdpd_settings' !== $_GET['page'] ) ) {
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-datetimepicker', WOODMART_ASSETS . '/js/datetimepicker.min.js', array(), $version, true );
		}

		// VC Fields
		wp_enqueue_script( 'woodmart-image-hotspot', WOODMART_ASSETS . '/js/vc-fields/image-hotspot.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-title-divider', WOODMART_ASSETS . '/js/vc-fields/title-divider.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-slider', WOODMART_ASSETS . '/js/vc-fields/slider.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-responsive-size', WOODMART_ASSETS . '/js/vc-fields/responsive-size.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-image-select', WOODMART_ASSETS . '/js/vc-fields/image-select.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-colorpicker', WOODMART_ASSETS . '/js/vc-fields/colorpicker.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-datepicker', WOODMART_ASSETS . '/js/vc-fields/datepicker.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-switch', WOODMART_ASSETS . '/js/vc-fields/switch.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-button-set', WOODMART_ASSETS . '/js/vc-fields/button-set.js', array(), $version, true );
		wp_enqueue_script( 'woodmart-vc-functions', WOODMART_ASSETS . '/js/vc-fields/vc-functions.js', array(), $version, true );

		woodmart_admin_scripts_localize();
	}

	add_action( 'admin_init', 'woodmart_admin_scripts', 100 );
}

if ( ! function_exists( 'woodmart_frontend_editor_enqueue_scripts' ) ) {
	function woodmart_frontend_editor_enqueue_scripts() {
		$version = woodmart_get_theme_info( 'Version' );
		woodmart_enqueue_js_library( 'cookie' );
		wp_enqueue_script( 'woodmart-frontend-editor-functions', WOODMART_ASSETS . '/js/vc-fields/frontend-editor-functions.js', array(), $version, true );
	}

	add_action( 'vc_frontend_editor_enqueue_js_css', 'woodmart_frontend_editor_enqueue_scripts', 100 );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Localize admin script function
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_admin_scripts_localize' ) ) {
	function woodmart_admin_scripts_localize() {
		wp_localize_script( 'woodmart-admin-scripts', 'woodmartConfig', woodmart_admin_script_local() );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Get localization array for admin scripts
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_admin_script_local' ) ) {
	function woodmart_admin_script_local() {
		$localize_data = array(
			'ajax'                             => admin_url( 'admin-ajax.php' ),
			'get_builder_elements_nonce'       => wp_create_nonce( 'woodmart-get-builder-elements-nonce' ),
			'get_builder_element_nonce'        => wp_create_nonce( 'woodmart-get-builder-element-nonce' ),
			'builder_load_header_nonce'        => wp_create_nonce( 'woodmart-builder-load-header-nonce' ),
			'builder_save_header_nonce'        => wp_create_nonce( 'woodmart-builder-save-header-nonce' ),
			'builder_remove_header_nonce'      => wp_create_nonce( 'woodmart-builder-remove-header-nonce' ),
			'builder_set_default_header_nonce' => wp_create_nonce( 'woodmart-builder-set-default-header-nonce' ),
			'import_nonce'                     => wp_create_nonce( 'woodmart-import-nonce' ),
			'mega_menu_added_thumbnail_nonce'  => wp_create_nonce( 'woodmart-mega-menu-added-thumbnail-nonce' ),
			'get_hotspot_image_nonce'          => wp_create_nonce( 'woodmart-get-hotspot-image-nonce' ),
		);

		// If we are on edit product attribute pages
		if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'product_attributes' && ! empty( $_GET['edit'] ) && function_exists( 'wc_attribute_taxonomy_name_by_id' ) ) {
			$attribute_id   = sanitize_text_field( wp_unslash( $_GET['edit'] ) ); // phpcs:ignore
			$taxonomy_ids                            = wc_get_attribute_taxonomy_ids();
			$attribute_name                          = array_search( $attribute_id, $taxonomy_ids, false );
			$localize_data['attributeSwatchSize']    = woodmart_wc_get_attribute_term( 'pa_' . $attribute_name, 'swatch_size' );
			$localize_data['attributeShowOnProduct'] = woodmart_wc_get_attribute_term( 'pa_' . $attribute_name, 'show_on_product' );
		}

		$all_fields   = XTS\Options::get_fields();
		$all_sections = XTS\Options::get_sections();

		$options_data = array();
		foreach ( $all_fields as $field ) {
			$path       = '';
			$section_id = $field->args['section'];
			$section    = $all_sections[ $section_id ];

			if ( isset( $section['parent'] ) ) {
				$path = $all_sections[ $section['parent'] ]['name'] . ' -> ' . $section['name'];
			} else {
				$path = $section['name'];
			}

			$text = $field->args['name'];
			if ( isset( $field->args['description'] ) ) {
				$text .= ' ' . $field->args['description'];
			}
			if ( isset( $field->args['tags'] ) ) {
				$text .= ' ' . $field->args['tags'];
			}

			$options_data[] = array(
				'id'         => $field->args['id'],
				'title'      => $field->args['name'],
				'text'       => $text,
				'section_id' => $section['id'],
				'icon'       => isset( $section['icon'] ) ? $section['icon'] : $all_sections[ $section['parent'] ]['icon'],
				'path'       => $path,
			);
		}

		$localize_data['xtsOptions'] = $options_data;

		$localize_data['searchOptionsPlaceholder'] = esc_js( __( 'Search for options', 'woodmart' ) );
		$localize_data['ajaxUrl']                  = admin_url( 'admin-ajax.php' );
		$localize_data['demoAjaxUrl']              = WOODMART_DEMO_URL . 'wp-admin/admin-ajax.php';

		return apply_filters( 'woodmart_admin_script_local', $localize_data );
	}
}


/**
 * ------------------------------------------------------------------------------------------------
 * Enqueue admin styles
 * ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists( 'woodmart_enqueue_admin_styles' ) ) {
	function woodmart_enqueue_admin_styles() {
		$version = woodmart_get_theme_info( 'Version' );
		if ( is_admin() ) {
			wp_enqueue_style( 'woodmart-admin-style', WOODMART_ASSETS . '/css/theme-admin.css', array(), $version );

			if ( apply_filters( 'woodmart_gradients_enabled', true ) ) {
				wp_enqueue_style( 'woodmart-colorpicker-style', WOODMART_ASSETS . '/css/colorpicker.css', array(), $version );
				wp_enqueue_style( 'woodmart-gradient-style', WOODMART_ASSETS . '/css/gradX.css', array(), $version );
			}
			if ( woodmart_get_opt( 'size_guides' ) ) {
				wp_enqueue_style( 'woodmart-edittable-style', WOODMART_ASSETS . '/css/jquery.edittable.min.css', array(), $version );
			}

			wp_enqueue_style( 'woodmart-jquery-ui', WOODMART_ASSETS . '/css/jquery-ui.css', array(), $version );
		}

	}

	add_action( 'admin_enqueue_scripts', 'woodmart_enqueue_admin_styles' );
}

