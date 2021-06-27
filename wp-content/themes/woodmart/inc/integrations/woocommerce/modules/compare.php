<?php
/**
 * WooCommerce compare functions
 *
 * @package woodmart
 */

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}


if ( ! function_exists( 'woodmart_compare_shortcode' ) ) {
	/**
	 * WooCommerce compare page shortcode.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_shortcode() {
		if ( ! woodmart_woocommerce_installed() ) return;
		ob_start();
		?>
			<?php woodmart_get_compared_products_table(); ?>
		<?php
		return ob_get_clean();
	}
}


if ( ! function_exists( 'woodmart_add_to_compare' ) ) {
	/**
	 * Add product to comapre
	 *
	 * @since 3.3
	 */
	function woodmart_add_to_compare() {
		$id = sanitize_text_field( $_GET['id'] );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'wpml_object_id_filter' ) ) {
			global $sitepress;
			$id = wpml_object_id_filter( $id, 'product', true, $sitepress->get_default_language() );
		}

		$cookie_name = woodmart_compare_cookie_name();

		if ( woodmart_is_product_in_compare( $id ) ) {
			woodmart_compare_json_response();
		}

		$products = woodmart_get_compared_products();

		$products[] = $id;

		setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

		$_COOKIE[$cookie_name] = json_encode( $products );

		woodmart_compare_json_response();
	}

	add_action( 'wp_ajax_woodmart_add_to_compare', 'woodmart_add_to_compare' );
	add_action( 'wp_ajax_nopriv_woodmart_add_to_compare', 'woodmart_add_to_compare' );
}

if ( ! function_exists( 'woodmart_add_to_compare_loop_btn' ) ) {
	/**
	 * Add to compare button on loop product.
	 *
	 * @since 1.0.0
	 */
	function woodmart_add_to_compare_loop_btn() {
		if ( woodmart_get_opt( 'compare' ) && woodmart_get_opt( 'compare_on_grid' ) ) {
			woodmart_add_to_compare_btn( 'wd-action-btn wd-style-icon wd-compare-icon' );
			// return;
		}

		if ( ! class_exists( 'YITH_Woocompare' ) || 'yes' != get_option( 'yith_woocompare_compare_button_in_products_list' ) ) {
			return;
		}

		echo '<div class="product-compare-button wd-action-btn wd-style-icon wd-compare-icon">';
			global $product;
			$product_id = $product->get_id();

			// return if product doesn't exist
			if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) ) {
			echo '</div>';
			return;
			}

			$is_button = ! isset( $button_or_link ) || ! $button_or_link ? get_option( 'yith_woocompare_is_button' ) : $button_or_link;

			if ( ! isset( $button_text ) || $button_text == 'default' ) {
				$button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'woodmart' ) );
			}

			printf( '<a href="%s" class="%s" data-product_id="%d" rel="nofollow noopener">%s</a>', woodmart_compare_add_product_url( $product_id ), 'compare', $product_id, $button_text );
		echo '</div>';
	}
}

if ( ! function_exists( 'woodmart_add_to_compare_single_btn' ) ) {
	/**
	 * Add to compare button on single product.
	 *
	 * @since 1.0.0
	 */
	function woodmart_add_to_compare_single_btn() {
		if ( woodmart_get_opt( 'compare' ) ) {
			woodmart_add_to_compare_btn( 'wd-action-btn wd-style-text wd-compare-icon' );
			// return;
		}

		if ( ! class_exists( 'YITH_Woocompare' ) || 'yes' != get_option( 'yith_woocompare_compare_button_in_product_page' ) ) {
			return;
		}

		echo '<div class="product-compare-button wd-action-btn wd-style-text wd-compare-icon">';
			global $product;
			$product_id = $product->get_id();

			// return if product doesn't exist
			if ( empty( $product_id ) || apply_filters( 'yith_woocompare_remove_compare_link_by_cat', false, $product_id ) ) {
			echo '</div>';
			return;
			}

			$is_button = ! isset( $button_or_link ) || ! $button_or_link ? get_option( 'yith_woocompare_is_button' ) : $button_or_link;

			if ( ! isset( $button_text ) || $button_text == 'default' ) {
				$button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'woodmart' ) );
			}

			printf( '<a href="%s" class="%s" data-product_id="%d" rel="nofollow noopener">%s</a>', woodmart_compare_add_product_url( $product_id ), 'compare', $product_id, $button_text );
		echo '</div>';
	}
}

if ( ! function_exists( 'woodmart_add_to_compare_btn' ) ) {
	/**
	 * Add to compare button.
	 *
	 * @since 1.0.0
	 *
	 * @param string $classes Extra classes.
	 */
	function woodmart_add_to_compare_btn( $classes = '' ) {
		global $product;
		woodmart_enqueue_js_script( 'woodmart-compare' );
		?>
			<div class="wd-compare-btn product-compare-button <?php echo esc_attr( $classes ); ?>">
				<a href="<?php echo esc_url( woodmart_get_compare_page_url() ); ?>" data-id="<?php echo esc_attr( $product->get_id() ); ?>" data-added-text="<?php esc_html_e( 'Compare products', 'woodmart' ); ?>">
					<?php esc_html_e( 'Compare', 'woodmart' ); ?>
				</a>
			</div>
		<?php
	}
}

if ( ! function_exists( 'woodmart_compare_json_response' ) ) {
	/**
	 * Compare JSON reponse.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_json_response() {
		$count = 0;
		$products = woodmart_get_compared_products();

		ob_start();

		woodmart_get_compared_products_table();

		$table_html = ob_get_clean();

		if ( is_array( $products ) ) {
			$count = count( $products );
		}

		wp_send_json( array(
			'count' => $count,
			'table' => $table_html,
		) );
	}
}

if ( ! function_exists( 'woodmart_get_compare_page_url' ) ) {
	/**
	 * Get compare page ID.
	 *
	 * @since 3.3
	 */
	function woodmart_get_compare_page_url() {
		$page_id = woodmart_get_opt( 'compare_page' );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'wpml_object_id_filter' ) ) {
			$page_id = wpml_object_id_filter( $page_id, 'page', true );
		}

		return get_permalink( $page_id );
	}
}


if ( ! function_exists( 'woodmart_remove_from_compare' ) ) {
	/**
	 * Add product to comapre
	 *
	 * @since 3.3
	 */
	function woodmart_remove_from_compare() {
		$id = sanitize_text_field( $_GET['id'] );

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'wpml_object_id_filter' ) ) {
			global $sitepress;
			$id = wpml_object_id_filter( $id, 'product', true, $sitepress->get_default_language() );
		}

		$cookie_name = woodmart_compare_cookie_name();

		if ( ! woodmart_is_product_in_compare( $id ) ) {
			woodmart_compare_json_response();
		}

		$products = woodmart_get_compared_products();

		foreach ( $products as $k => $product_id ) {
			if ( intval( $id ) == $product_id ) {
				unset( $products[ $k ] );
			}
		}

		if ( empty( $products ) ) {
			setcookie( $cookie_name, false, 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[$cookie_name] = false;
		} else {
			setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[$cookie_name] = json_encode( $products );
		}

		woodmart_compare_json_response();
	}

	add_action( 'wp_ajax_woodmart_remove_from_compare', 'woodmart_remove_from_compare' );
	add_action( 'wp_ajax_nopriv_woodmart_remove_from_compare', 'woodmart_remove_from_compare' );
}


if ( ! function_exists( 'woodmart_is_product_in_compare' ) ) {
	/**
	 * Is product in compare
	 *
	 * @since 3.3
	 */
	function woodmart_is_product_in_compare( $id ) {
		$list = woodmart_get_compared_products();

		return in_array( $id, $list, true );
	}
}


if ( ! function_exists( 'woodmart_get_compare_count' ) ) {
	/**
	 * Get compare number.
	 *
	 * @since 3.3
	 */
	function woodmart_get_compare_count() {
		$count = 0;
		$products = woodmart_get_compared_products();

		if ( is_array( $products ) ) {
			$count = count( $products );
		}

		return $count;
	}
}


if ( ! function_exists( 'woodmart_compare_cookie_name' ) ) {
	/**
	 * Get compare cookie namel.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_cookie_name() {
		$name = 'woodmart_compare_list';

        if ( is_multisite() ) $name .= '_' . get_current_blog_id();

        return $name;

	}
}


if ( ! function_exists( 'woodmart_get_compared_products' ) ) {
	/**
	 * Get compared products IDs array
	 *
	 * @since 3.3
	 */
	function woodmart_get_compared_products() {
		$cookie_name = woodmart_compare_cookie_name();
        return isset( $_COOKIE[ $cookie_name ] ) ? json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) : array();
	}
}

if ( ! function_exists( 'woodmart_is_products_have_field' ) ) {
	/**
	 * Checks if the products have such a field.
	 *
	 * @since 3.4
	 */
	function woodmart_is_products_have_field( $field_id, $products ) {
		foreach ( $products as $product_id => $product ) {
			if ( isset( $product[ $field_id ] ) && ( ! empty( $product[ $field_id ] ) && apply_filters( 'woodmart_compare_empty_field_symbol', '-' ) !== $product[ $field_id ] && 'N/A' !== $product[ $field_id ] ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'woodmart_get_compared_products_table' ) ) {
	/**
	 * Get compared products data table HTML
	 *
	 * @since 3.3
	 */
	function woodmart_get_compared_products_table() {
		$products = woodmart_get_compared_products_data();
		$fields = woodmart_get_compare_fields();
		$empty_compare_text = woodmart_get_opt( 'empty_compare_text' );

		?>
		<div class="wd-compare-table">
			<?php
			if ( ! empty( $products ) ) {
				array_unshift( $products, array() );
				foreach ( $fields as $field_id => $field ) {
					if ( ! woodmart_is_products_have_field( $field_id, $products ) ) {
						continue;
					}
					?>
						<div class="wd-compare-row compare-<?php echo esc_attr( $field_id ); ?>">
							<?php foreach ( $products as $product_id => $product ) : ?>
								<?php if ( ! empty( $product ) ) : ?>
									<div class="wd-compare-col compare-value" data-title="<?php echo esc_attr( $field ); ?>">
										<?php woodmart_compare_display_field( $field_id, $product ); ?>
									</div>
								<?php else: ?>
									<div class="wd-compare-col compare-field">
										<?php echo esc_html( $field ); ?>
									</div>
								<?php endif; ?>

							<?php endforeach ?>
						</div>
					<?php
				}	
			} else {
				?>
					<p class="wd-empty-compare wd-empty-page">
						<?php esc_html_e('Compare list is empty.', 'woodmart'); ?>
					</p>
					<?php if ( $empty_compare_text ) : ?>
						<div class="wd-empty-page-text"><?php echo wp_kses( $empty_compare_text, array('p' => array(), 'h1' => array(), 'h2' => array(), 'h3' => array(), 'strong' => array(), 'em' => array(), 'span' => array(), 'div' => array() , 'br' => array()) ); ?></div>
					<?php endif; ?>
					<p class="return-to-shop">
						<a class="button" href="<?php echo esc_url( apply_filters( 'woodmart_compare_return_to_shop_url', wc_get_page_permalink( 'shop' ) ) ); ?>">
							<?php esc_html_e( 'Return to shop', 'woodmart' ); ?>
						</a>
					</p>
				<?php
			}

			?>
		</div>
		<?php
	}
}


if ( ! function_exists( 'woodmart_get_compare_fields' ) ) {
	/**
	 * Get compare fields data.
	 *
	 * @since 3.3
	 */
	function woodmart_get_compare_fields() {
		$fields = array(
			'basic' => ''
		);

		$fields_settings = woodmart_get_opt('fields_compare');
		
		if ( class_exists( 'XTS\Options' ) && count( $fields_settings ) > 1 ) {
			$fields_labels = woodmart_compare_available_fields( true );

			foreach ( $fields_settings as $field ) {
				if ( isset( $fields_labels [ $field ] ) ) {
					$fields[ $field ] = $fields_labels [ $field ]['name'];
				}
			}

			return $fields;
		}

		if ( isset( $fields_settings['enabled'] ) && count( $fields_settings['enabled'] ) > 1 ) {
			$fields = $fields + $fields_settings['enabled'];
			unset( $fields['placebo'] );
		}


		return $fields;
	}
}


if ( ! function_exists( 'woodmart_compare_display_field' ) ) {
	/**
	 * Get compare fields data.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_display_field( $field_id, $product ) {
		woodmart_enqueue_js_script( 'woodmart-compare' );

		$type = $field_id;

		if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
			$type = 'attribute';
		}
		
		switch ( $type ) {
			case 'basic':
				echo '<div class="compare-basic-content">';
					echo '<div class="wd-action-btn wd-style-text wd-cross-icon"><a href="#" rel="noffollow" class="wd-compare-remove" data-id="' . esc_attr( $product['id'] ) . '">' . esc_html__( 'Remove', 'woodmart' ) . '</a></div>';
					echo '<a class="product-image" href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['image']. '</a>';
					echo '<a class="wd-entities-title" href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['title']. '</a>';
					echo wp_kses_post( $product['basic']['rating'] );
					echo '<div class="price">';
						echo wp_kses_post( $product['basic']['price'] );
					echo '</div>';
					if ( ! woodmart_get_opt( 'catalog_mode' ) ) {
						echo apply_filters( 'woodmart_compare_add_to_cart_btn', $product['basic']['add_to_cart'] );
					}
				echo '</div>';
			break;

			case 'attribute':
				if ( $field_id === woodmart_get_opt( 'brands_attribute' ) ) {
					$brands = wc_get_product_terms( $product['id'], $field_id, array( 'fields' => 'all' ) );

					if ( empty( $brands ) ) {
						echo apply_filters( 'woodmart_compare_empty_field_symbol', '-' );
						return;
					}

					foreach ($brands as $brand) {
						$image = get_term_meta( $brand->term_id, 'image', true);

						if( ! empty( $image ) ) {
							echo '<div class="wd-compare-brand' . woodmart_get_old_classes( ' woodmart-compare-brand' ) . '">';
								echo apply_filters( 'woodmart_image', '<img src="' . esc_url( $image ) . '" title="' . esc_attr( $brand->name ) . '" alt="' . esc_attr( $brand->name ) . '" />' );
							echo '</div>';
						} else {
							echo wp_kses_post( $product[ $field_id ] );
						}

					}
				} else {
					echo wp_kses_post( $product[ $field_id ] );
				}
				break;

			case 'weight':
				if ( $product[ $field_id ] ) {
					$unit = $product[ $field_id ] !== apply_filters( 'woodmart_compare_empty_field_symbol', '-' ) ? get_option( 'woocommerce_weight_unit' ) : '';
					echo wc_format_localized_decimal( $product[ $field_id ] ) . ' ' . esc_attr( $unit );
				} 
				break;

			case 'description':
				echo apply_filters( 'woocommerce_short_description', $product[ $field_id ] );
				break;

			default:
				echo wp_kses_post( $product[ $field_id ] );
				break;
		}
	}
}


if ( ! function_exists( 'woodmart_get_compared_products_data' ) ) {
	/**
	 * Get compared products data
	 *
	 * @since 3.3
	 */
	function woodmart_get_compared_products_data() {
		$ids = woodmart_get_compared_products();

		if ( empty( $ids ) ) {
			return array();
		}

		$args = array(
			'include' => $ids,
			'limit'   => 100,
		);

		$products = wc_get_products( $args );

		$products_data = array();

		$fields = woodmart_get_compare_fields();

		$fields = array_filter( $fields, function(  $field ) {
			return 'pa_' === substr( $field, 0, 3 );
		}, ARRAY_FILTER_USE_KEY );

		$divider = apply_filters( 'woodmart_compare_empty_field_symbol', '-' );

		foreach ( $products as $product ) {
			$rating_count = $product->get_rating_count();
			$average = $product->get_average_rating();

			$products_data[ $product->get_id() ] = array(
				'basic' => array(
					'title' => $product->get_title() ? $product->get_title() : $divider,
					'image' => $product->get_image() ? $product->get_image() : $divider,
					'rating' => wc_get_rating_html( $average, $rating_count ),
					'price' => $product->get_price_html() ? $product->get_price_html() : $divider,
					'add_to_cart' => woodmart_compare_add_to_cart_html( $product ) ? woodmart_compare_add_to_cart_html( $product ) :$divider,
				),
				'id' => $product->get_id(),
				'image_id' => $product->get_image_id(),
				'permalink' => $product->get_permalink(),
				'dimensions' => wc_format_dimensions( $product->get_dimensions( false ) ),
				'description' => $product->get_short_description() ? $product->get_short_description() : $divider,
				'weight' => $product->get_weight() ? $product->get_weight() : $divider,
				'sku' => $product->get_sku() ? $product->get_sku() : $divider,
				'availability' => woodmart_compare_availability_html( $product ),
			);

			foreach ( $fields as $field_id => $field_name ) {
				if ( taxonomy_exists( $field_id ) ) {
					$products_data[ $product->get_id() ][ $field_id ] = array();
					$orderby = wc_attribute_orderby( $field_id ) ? wc_attribute_orderby( $field_id ) : 'name';
					$terms = wp_get_post_terms( $product->get_id(), $field_id, array(
							'orderby' => $orderby
					) );
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$term = sanitize_term( $term, $field_id );
							$products_data[ $product->get_id() ][ $field_id ][] = $term->name;
						}
					} else {
						$products_data[ $product->get_id() ][ $field_id ][] = apply_filters( 'woodmart_compare_empty_field_symbol', '-' );
					}
					$products_data[ $product->get_id() ][ $field_id ] = implode( ', ', $products_data[ $product->get_id() ][ $field_id ] );
				}
			}
		}

		return $products_data;
	}
}

if ( ! function_exists( 'woodmart_compare_availability_html' ) ) {
	/**
	 * Get product availability html.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_availability_html( $product ) {
		$html         = '';
		$availability = $product->get_availability();

		if( empty( $availability['availability'] ) ) {
			$availability['availability'] = __( 'In stock', 'woocommerce' );
		}

		if ( ! empty( $availability['availability'] ) ) {
			ob_start();

			wc_get_template( 'single-product/stock.php', array(
				'product'      => $product,
				'class'        => $availability['class'],
				'availability' => $availability['availability'],
			) );

			$html = ob_get_clean();
		}

		return apply_filters( 'woocommerce_get_stock_html', $html, $product );
	}
}


if ( ! function_exists( 'woodmart_compare_add_to_cart_html' ) ) {
	/**
	 * Get product add to cart html.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_add_to_cart_html( $product ) {
		if ( ! $product ) return;

		$defaults = array(
			'quantity'   => 1,
			'class'      => implode( ' ', array_filter( array(
				'button',
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
			) ) ),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
		}

		return apply_filters( 'woocommerce_loop_add_to_cart_link', 
			sprintf( '<a href="%s" data-quantity="%s" class="%s add-to-cart-loop" %s><span>%s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
		$product, $args );
	}
}





if ( ! function_exists( 'woodmart_compare_available_fields' ) ) {
	/**
	 * All available fields for Theme Settings sorter option.
	 *
	 * @since 3.3
	 */
	function woodmart_compare_available_fields( $new = false) {
		$product_attributes = array();

		if( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			$product_attributes = wc_get_attribute_taxonomies();
		}

		if ( $new ) {
			$options = array(
				'description' => array(
					'name'  => esc_html__( 'Description', 'woodmart' ),
					'value' => 'description',
				),
				'dimensions' => array(
					'name'  => esc_html__( 'Dimensions', 'woodmart' ),
					'value' => 'dimensions',
				),
				'weight' => array(
					'name'  => esc_html__( 'Weight', 'woodmart' ),
					'value' => 'weight',
				),
				'availability' => array(
					'name'  => esc_html__( 'Availability', 'woodmart' ),
					'value' => 'availability',
				),
				'sku' => array(
					'name'  => esc_html__( 'Sku', 'woodmart' ),
					'value' => 'sku',
				),

			);

			if ( count( $product_attributes ) > 0 ) {
				foreach ( $product_attributes as $attribute ) {
					$options[ 'pa_' . $attribute->attribute_name ] = array(
						'name'  => wc_attribute_label( $attribute->attribute_label ),
						'value' => 'pa_' . $attribute->attribute_name,
					);
				}	
			}
			return $options;
		}

		$fields = array(
			'enabled'  => array(
				'description' => esc_html__( 'Description', 'woodmart' ),
				'sku' => esc_html__( 'Sku', 'woodmart' ),
				'availability' => esc_html__( 'Availability', 'woodmart' ),
			),
			'disabled' => array(
				'weight' => esc_html__( 'Weight', 'woodmart' ),
				'dimensions' => esc_html__( 'Dimensions', 'woodmart' ),
			)
		);

		if ( count( $product_attributes ) > 0 ) {
			foreach ( $product_attributes as $attribute ) {
				$fields['disabled'][ 'pa_' . $attribute->attribute_name ] = $attribute->attribute_label;
			}	
		}

		return $fields;
	}
}


