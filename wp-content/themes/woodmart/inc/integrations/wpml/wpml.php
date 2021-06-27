<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

function woodmart_wpml_elementor_widgets_to_translate_filter( $widgets ) {
	$widgets['wd_title'] = array(
		'conditions' => array( 'widgetType' => 'wd_title' ),
		'fields'     => array(
			'subtitle'    => array(
				'field'       => 'subtitle',
				'type'        => esc_html__( '[Section title] - Subtitle', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'title'       => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Section title] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'after_title' => array(
				'field'       => 'after_title',
				'type'        => esc_html__( '[Section title] - Text', 'woodmart' ),
				'editor_type' => 'AREA',
			),
		),
	);

	$widgets['wd_slider'] = array(
		'conditions' => array( 'widgetType' => 'wd_slider' ),
		'fields'     => array(
			'slider' => array(
				'field'       => 'slider',
				'type'        => esc_html__( '[Slider] - Slider', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_extra_menu_list'] = array(
		'conditions'        => array( 'widgetType' => 'wd_extra_menu_list' ),
		'fields'            => array(
			'title' => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Extra menu list] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'label' => array(
				'field'       => 'label',
				'type'        => esc_html__( '[Extra menu list] - Label text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'link'  => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Extra menu list] - Link', 'woodmart' ),
				'editor_type' => 'LINK',
			),
		),
		'integration-class' => 'WPML_Elementor_WD_Extra_Menu_List',
	);

	$widgets['wd_counter'] = array(
		'conditions' => array( 'widgetType' => 'wd_counter' ),
		'fields'     => array(
			'label' => array(
				'field'       => 'label',
				'type'        => esc_html__( '[Animated Counter] - Label', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_author_area'] = array(
		'conditions' => array( 'widgetType' => 'wd_author_area' ),
		'fields'     => array(
			'title'       => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Author area] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'author_name' => array(
				'field'       => 'author_name',
				'type'        => esc_html__( '[Author area] - Author name', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'content'     => array(
				'field'       => 'content',
				'type'        => esc_html__( '[Author area] - Author bio', 'woodmart' ),
				'editor_type' => 'AREA',
			),
			'link_text'   => array(
				'field'       => 'link_text',
				'type'        => esc_html__( '[Author area] - Link text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'link'        => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Author area] - Link', 'woodmart' ),
				'editor_type' => 'LINK',
			),
		),
	);

	$widgets['wd_team_member'] = array(
		'conditions' => array( 'widgetType' => 'wd_team_member' ),
		'fields'     => array(
			'name'     => array(
				'field'       => 'name',
				'type'        => esc_html__( '[Team member] - Name', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'position' => array(
				'field'       => 'position',
				'type'        => esc_html__( '[Team member] - Position', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'content'  => array(
				'field'       => 'content',
				'type'        => esc_html__( '[Team member] - Content', 'woodmart' ),
				'editor_type' => 'AREA',
			),
		),
	);

	$widgets['wd_mega_menu'] = array(
		'conditions' => array( 'widgetType' => 'wd_mega_menu' ),
		'fields'     => array(
			'title' => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Mega Menu] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_menu_price'] = array(
		'conditions' => array( 'widgetType' => 'wd_menu_price' ),
		'fields'     => array(
			'title'       => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Menu price] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'description' => array(
				'field'       => 'description',
				'type'        => esc_html__( '[Menu price] - Description', 'woodmart' ),
				'editor_type' => 'AREA',
			),
			'price'       => array(
				'field'       => 'price',
				'type'        => esc_html__( '[Menu price] - Price', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'link'        => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Menu price] - Link', 'woodmart' ),
				'editor_type' => 'LINK',
			),
		),
	);

	$widgets['wd_popup'] = array(
		'conditions' => array( 'widgetType' => 'wd_popup' ),
		'fields'     => array(
			'text' => array(
				'field'       => 'text',
				'type'        => esc_html__( '[Popup] - Button text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_google_map'] = array(
		'conditions' => array( 'widgetType' => 'wd_google_map' ),
		'fields'     => array(
			'title'       => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Google Map] - Marker title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'marker_text' => array(
				'field'       => 'marker_text',
				'type'        => esc_html__( '[Google Map] - Marker text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'text'        => array(
				'field'       => 'text',
				'type'        => esc_html__( '[Google Map] - Text', 'woodmart' ),
				'editor_type' => 'AREA',
			),
		),
	);

	$widgets['wd_button'] = array(
		'conditions' => array( 'widgetType' => 'wd_button' ),
		'fields'     => array(
			'text' => array(
				'field'       => 'text',
				'type'        => esc_html__( '[Button] - Text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'link' => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Button] - Link', 'woodmart' ),
				'editor_type' => 'LINK',
			),
		),
	);

	$widgets['wd_banner'] = array(
		'conditions' => array( 'widgetType' => 'wd_banner' ),
		'fields'     => array(
			'link'     => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Promo Banner] - Link', 'woodmart' ),
				'editor_type' => 'LINK',
			),
			'subtitle' => array(
				'field'       => 'subtitle',
				'type'        => esc_html__( '[Promo Banner] - Subtitle', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'title'    => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Promo Banner] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'content'  => array(
				'field'       => 'content',
				'type'        => esc_html__( '[Promo Banner] - Content', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'btn_text' => array(
				'field'       => 'btn_text',
				'type'        => esc_html__( '[Promo Banner] - Button text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_banner_carousel'] = array(
		'conditions'        => array( 'widgetType' => 'wd_banner_carousel' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Banner_Carousel',
	);

	$widgets['wd_infobox'] = array(
		'conditions' => array( 'widgetType' => 'wd_infobox' ),
		'fields'     => array(
			'icon_text' => array(
				'field'       => 'icon_text',
				'type'        => esc_html__( '[Information box] - Icon text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'subtitle'  => array(
				'field'       => 'subtitle',
				'type'        => esc_html__( '[Information box] - Subtitle', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'title'     => array(
				'field'       => 'title',
				'type'        => esc_html__( '[Information box] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'content'   => array(
				'field'       => 'content',
				'type'        => esc_html__( '[Information box] - Content', 'woodmart' ),
				'editor_type' => 'AREA',
			),
			'btn_text'  => array(
				'field'       => 'btn_text',
				'type'        => esc_html__( '[Information box] - Button text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
			'link'      => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Information box] - Link', 'woodmart' ),
				'editor_type' => 'LINK',
			),
		),
	);

	$widgets['wd_instagram'] = array(
		'conditions' => array( 'widgetType' => 'wd_instagram' ),
		'fields'     => array(
			'content' => array(
				'field'       => 'content',
				'type'        => esc_html__( '[Instagram] - Content', 'woodmart' ),
				'editor_type' => 'AREA',
			),
			'link'    => array(
				'field'       => 'url',
				'type'        => esc_html__( '[Instagram] - Link text', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_products_tabs'] = array(
		'conditions'        => array( 'widgetType' => 'wd_products_tabs' ),
		'fields'            => array(
			'title' => array(
				'field'       => 'title',
				'type'        => esc_html__( '[AJAX Products tabs] - Tabs title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
		'integration-class' => 'WPML_Elementor_WD_Products_Tabs',
	);

	$widgets['wd_products_widget'] = array(
		'conditions' => array( 'widgetType' => 'wd_products_widget' ),
		'fields'     => array(
			'title' => array(
				'field'       => 'title',
				'type'        => esc_html__( '[WC Products widget] - Title', 'woodmart' ),
				'editor_type' => 'LINE',
			),
		),
	);

	$widgets['wd_testimonials'] = array(
		'conditions'        => array( 'widgetType' => 'wd_testimonials' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Testimonials',
	);

	$widgets['wd_image_hotspot'] = array(
		'conditions'        => array( 'widgetType' => 'wd_image_hotspot' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Image_Hotspot',
	);

	$widgets['wd_pricing_tables'] = array(
		'conditions'        => array( 'widgetType' => 'wd_pricing_tables' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Pricing_Tables',
	);

	$widgets['wd_list'] = array(
		'conditions'        => array( 'widgetType' => 'wd_list' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_List',
	);

	$widgets['wd_product_filters'] = array(
		'conditions'        => array( 'widgetType' => 'wd_product_filters' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Product_Filters',
	);

	$widgets['wd_timeline'] = array(
		'conditions'        => array( 'widgetType' => 'wd_timeline' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Timeline',
	);

	$widgets['wd_infobox_carousel'] = array(
		'conditions'        => array( 'widgetType' => 'wd_infobox_carousel' ),
		'fields'            => array(),
		'integration-class' => 'WPML_Elementor_WD_Infobox_Carousel',
	);

	return $widgets;
}

add_filter( 'wpml_elementor_widgets_to_translate', 'woodmart_wpml_elementor_widgets_to_translate_filter' );

if ( ! function_exists( 'woodmart_wpml_js_data' ) ) {
	function woodmart_wpml_js_data() {
		if ( ! woodmart_get_opt( 'ajax_shop' ) || ! defined( 'WCML_VERSION' ) || ! defined( 'ICL_SITEPRESS_VERSION' ) ) {
			return;
		}

		$data = array(
			'languages' => apply_filters( 'wpml_active_languages', null ),
		);

		echo '<script>';
		echo 'var woodmart_wpml_js_data = ' . wp_json_encode( $data );
		echo '</script>';
	}

	add_action( 'woodmart_after_header', 'woodmart_wpml_js_data' );
}

if ( ! function_exists( 'woodmart_wpml_compatibility' ) ) {
	function woodmart_wpml_compatibility( $ajax_actions ) {
		$ajax_actions[] = 'woodmart_ajax_add_to_cart';
		$ajax_actions[] = 'woodmart_quick_view';
		$ajax_actions[] = 'woodmart_ajax_search';
		$ajax_actions[] = 'woodmart_get_products_shortcode';
		$ajax_actions[] = 'woodmart_get_products_tab_shortcode';
		$ajax_actions[] = 'woodmart_update_cart_item';
		$ajax_actions[] = 'woodmart_load_html_dropdowns';
		$ajax_actions[] = 'woodmart_quick_shop';

		return $ajax_actions;
	}

	add_filter( 'wcml_multi_currency_ajax_actions', 'woodmart_wpml_compatibility', 10, 1 );
}

if ( ! function_exists( 'woodmart_wpml_variation_gallery_data' ) ) {
	function woodmart_wpml_variation_gallery_data( $post_id_from, $post_id_to, $meta_key ) {
		if ( 'woodmart_variation_gallery_data' === $meta_key ) {
			$translated_lang  = apply_filters( 'wpml_post_language_details', '', $post_id_to );
			$translated_lang  = isset( $translated_lang['language_code'] ) ? $translated_lang['language_code'] : '';
			$original_value   = get_post_meta( $post_id_from, 'woodmart_variation_gallery_data', true );
			$translated_value = $original_value;
			if ( ! empty( $original_value ) && is_array( $original_value ) ) {
				foreach ( $original_value as $key => $value ) {
					$translated_variation_id = apply_filters( 'wpml_object_id', $key, 'product_variation', false, $translated_lang );

					$translated_value[ $translated_variation_id ] = $value;
					unset( $translated_value[ $key ] );
				}
				update_post_meta( $post_id_to, 'woodmart_variation_gallery_data', $translated_value );
			}
		}
	}

	add_action( 'wpml_after_copy_custom_field', 'woodmart_wpml_variation_gallery_data', 10, 3 );
}

if ( ! function_exists( 'woodmart_wpml_register_header_builder_strings' ) ) {
	function woodmart_wpml_register_header_builder_strings( $file ) {
		global $wpdb;

		if ( is_string( $file ) && 'woodmart' === basename( dirname( $file ) ) && class_exists( 'WPML_Admin_Text_Configuration' ) ) {
			$file       .= ':whb';
			$admin_texts = array();
			$headers     = get_option( 'whb_saved_headers', [] );
			foreach ( $headers as $key => $header ) {
				$admin_texts[] = array(
					'value' => '',
					'attr'  => array( 'name' => 'whb_' . $key ),
					'key'   => array(
						array(
							'value' => '',
							'attr'  => array( 'name' => 'structure' ),
							'key'   => array(
								array(
									'value' => '',
									'attr'  => array( 'name' => 'content' ),
									'key'   => array(
										array(
											'value' => '',
											'attr'  => array( 'name' => '*' ),
											'key'   => array(
												array(
													'value' => '',
													'attr' => array( 'name' => 'content' ),
													'key'  => array(
														array(
															'value' => '',
															'attr' => array( 'name' => '*' ),
															'key' => array(
																array(
																	'value' => '',
																	'attr' => array( 'name' => 'content' ),
																	'key' => array(
																		array(
																			'value' => '',
																			'attr' => array( 'name' => '*' ),
																			'key' => array(
																				array(
																					'value' => '',
																					'attr' => array( 'name' => 'params' ),
																					'key' => array(
																						array(
																							'value' => '',
																							'attr' => array( 'name' => 'content' ),
																							'key' => array(
																								array(
																									'value' => '',
																									'attr' => array( 'name' => 'value' ),
																									'key' => array(),
																								),
																							),
																						),
																						array(
																							'value' => '',
																							'attr' => array( 'name' => 'title' ),
																							'key' => array(
																								array(
																									'value' => '',
																									'attr' => array( 'name' => 'value' ),
																									'key' => array(),
																								),
																							),
																						),
																						array(
																							'value' => '',
																							'attr' => array( 'name' => 'subtitle' ),
																							'key' => array(
																								array(
																									'value' => '',
																									'attr' => array( 'name' => 'value' ),
																									'key' => array(),
																								),
																							),
																						),
																					),
																				),
																			),
																		),
																	),
																),
															),
														),
													),
												),
											),
										),
									),
								),
							),
						),
					),
				);
			}

			$object = (object) array(
				'config'             => array(
					'wpml-config' => array(
						'admin-texts' => array(
							'value' => '',
							'key'   => $admin_texts,
						),
					),
				),
				'type'               => 'theme',
				'admin_text_context' => 'woodmart-header-builder',
			);

			$config       = new WPML_Admin_Text_Configuration( $object );
			$config_array = $config->get_config_array();

			if ( ! empty( $config_array ) ) {
				$st_records          = new WPML_ST_Records( $wpdb );
				$import              = new WPML_Admin_Text_Import( $st_records, new WPML_WP_API() );
				$config_handler_hash = md5( serialize( 'whb' ) );
				$import->parse_config( $config_array, $config_handler_hash );
			}
		}
	}

	add_filter( 'wpml_parse_config_file', 'woodmart_wpml_register_header_builder_strings' );
}
