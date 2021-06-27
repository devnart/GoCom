<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* Shortcodes css formatter
*/
if ( ! function_exists( 'woodmart_parse_shortcodes_css_data' ) ) {
	function woodmart_parse_shortcodes_css_data( $content ) {
		if ( ! class_exists( 'WPBMap' ) ) {
			return;
		}
		$css_data = '';

		WPBMap::addAllMappedShortcodes();
		preg_match_all( '/' . get_shortcode_regex() . '/', $content, $shortcodes );

		foreach ( $shortcodes[2] as $index => $tag ) {
			$shortcode       = WPBMap::getShortCode( $tag );
			$attr_array      = shortcode_parse_atts( trim( $shortcodes[3][ $index ] ) );
			$woodmart_fields = array(
				'woodmart_responsive_size',
				'woodmart_colorpicker',
			);

			if ( isset( $shortcode['params'] ) && ! empty( $shortcode['params'] ) ) {
				foreach ( $shortcode['params'] as $param ) {
					if ( isset( $param['type'] ) && in_array( $param['type'], $woodmart_fields ) && isset( $attr_array[ $param['param_name'] ] ) ) {
						$css_data .= $attr_array[ $param['param_name'] ] . '[|]';
					}
				}
			}
		}

		foreach ( $shortcodes[5] as $shortcode_content ) {
			$css_data .= woodmart_parse_shortcodes_css_data( $shortcode_content );
		}

		return $css_data;
	}
}

if ( ! function_exists( 'woodmart_save_shortcodes_css_data' ) ) {
	function woodmart_save_shortcodes_css_data( $post_id ) {
		$post       = get_post( $post_id );
		$css_data   = woodmart_parse_shortcodes_css_data( $post->post_content );
		$data_array = explode( '[|]', $css_data );
		$css        = woodmart_shortcodes_css_data_to_css( $data_array );

		if ( empty( $css ) ) {
			delete_post_meta( $post_id, 'woodmart_shortcodes_custom_css' );
		} else {
			update_post_meta( $post_id, 'woodmart_shortcodes_custom_css', $css );
		}
	}

	add_action( 'save_post', 'woodmart_save_shortcodes_css_data' );
}

if ( ! function_exists( 'woodmart_print_shortcodes_css' ) ) {
	function woodmart_print_shortcodes_css( $id = null ) {
		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$id = wc_get_page_id( 'shop' );
		}

		if ( ! $id ) {
			$id = get_the_ID();
		}

		if ( $id ) {
			$css = get_post_meta( $id, 'woodmart_shortcodes_custom_css', true );
			if ( ! empty( $css ) ) {
				echo '<style data-type="woodmart_shortcodes-custom-css">' . $css . '</style>';
			}
		}
	}

	add_action( 'wp_head', 'woodmart_print_shortcodes_css', 1000 );
}


if ( ! function_exists( 'woodmart_shortcodes_css_data_to_css' ) ) {
	function woodmart_shortcodes_css_data_to_css( $css_data ) {
		$results         = '';
		$sorted_css_data = array();
		$css             = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		foreach ( $css_data as $value ) {
			$decompressed_data = function_exists( 'woodmart_decompress' ) ? json_decode( woodmart_decompress( $value ) ) : '';

			if ( is_object( $decompressed_data ) ) {
				foreach ( $decompressed_data->data as $size => $css_value ) {
					foreach ( $decompressed_data->css_args as $css_prop => $classes_array ) {
						foreach ( $classes_array as $css_class ) {
							$selector = '#wd-' . $decompressed_data->selector_id . $css_class;
							if ( $css_prop == 'font-size' ) {
								$sorted_css_data[ $size ][ $selector ]['line-height'] = str_replace( 'px', '', $css_value ) + 10 . 'px';
							}
							if ( $css_prop == 'line-height' ) {
								unset( $sorted_css_data[ $size ][ $selector ]['line-height'] );
							}
							$sorted_css_data[ $size ][ $selector ][ $css_prop ] = $css_value;
						}
					}
				}
			}
		}

		foreach ( $sorted_css_data as $size => $selectors ) {
			foreach ( $selectors as $selector => $css_data ) {
				$css[ $size ] .= $selector . '{';
				foreach ( $css_data as $css_prop => $css_value ) {
					$css[ $size ] .= $css_prop . ':' . $css_value . ';';
				}
				$css[ $size ] .= '}';
			}
		}

		foreach ( $css as $size => $css_value ) {
			if ( $size == 'desktop' && $css_value ) {
				$results .= $css_value;
			} elseif ( $size == 'tablet' && $css_value ) {
				$results .= '@media (max-width: 1024px) {' . $css_value . '}';
			} elseif ( $size == 'mobile' && $css_value ) {
				$results .= '@media (max-width: 767px) {' . $css_value . '}';
			}
		}

		return $results;
	}
}
