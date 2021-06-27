<?php
/**
 * Elementor config file
 */

use Elementor\Core\Files\CSS\Post_Preview;
use Elementor\Group_Control_Image_Size;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use XTS\Elementor\Controls\Autocomplete;
use XTS\Elementor\Controls\Google_Json;
use XTS\Elementor\Controls\Buttons;

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit; // Direct access not allowed.
}

if ( ! function_exists( 'woodmart_elementor_maybe_init_cart' ) ) {
	/**
	 * Ini woo cart in elementor.
	 */
	function woodmart_elementor_maybe_init_cart() {
		if ( ! woodmart_woocommerce_installed() ) {
			return;
		}

		WC()->initialize_session();
	}

	add_action( 'elementor/editor/before_enqueue_scripts', 'woodmart_elementor_maybe_init_cart' );
}

if ( ! function_exists( 'woodmart_elementor_register_elementor_locations' ) ) {
	/**
	 * Register Elementor Locations.
	 *
	 * @param ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager theme manager.
	 *
	 * @return void
	 */
	function woodmart_elementor_register_elementor_locations( $elementor_theme_manager ) {
		$elementor_theme_manager->register_location(
			'header',
			[
				'is_core'         => false,
				'public'          => false,
				'label'           => esc_html__( 'Header', 'woodmart' ),
				'edit_in_content' => false,
			]
		);

		$elementor_theme_manager->register_location(
			'footer',
			[
				'is_core'         => false,
				'public'          => false,
				'label'           => esc_html__( 'Footer', 'woodmart' ),
				'edit_in_content' => false,
			]
		);
	}

	add_action( 'elementor/theme/register_locations', 'woodmart_elementor_register_elementor_locations' );
}

if ( ! function_exists( 'woodmart_elementor_custom_shapes' ) ) {
	/**
	 * Custom shapes.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_elementor_custom_shapes() {
		return [
			'wd_clouds'       => [
				'title'    => '[XTemos] Clouds',
				'has_flip' => false,
				'path'     => WOODMART_THEMEROOT . '/images/svg/clouds-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/clouds-top.svg',
			],
			'wd_curved_line'  => [
				'title'    => '[XTemos] Curved line',
				'has_flip' => true,
				'path'     => WOODMART_THEMEROOT . '/images/svg/curved-line-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/curved-line-top.svg',
			],
			'wd_paint_stroke' => [
				'title'    => '[XTemos] Paint stroke',
				'has_flip' => true,
				'path'     => WOODMART_THEMEROOT . '/images/svg/paint-stroke-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/paint-stroke-top.svg',
			],
			'wd_sweet_wave'   => [
				'title'    => '[XTemos] Sweet wave',
				'has_flip' => true,
				'path'     => WOODMART_THEMEROOT . '/images/svg/sweet-wave-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/sweet-wave-top.svg',
			],
			'wd_triangle'     => [
				'title'    => '[XTemos] Triangle',
				'has_flip' => false,
				'path'     => WOODMART_THEMEROOT . '/images/svg/triangle-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/triangle-top.svg',
			],
			'wd_waves_small'  => [
				'title'    => '[XTemos] Waves small',
				'has_flip' => false,
				'path'     => WOODMART_THEMEROOT . '/images/svg/waves-small-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/waves-small-top.svg',
			],
			'wd_waves_wide'   => [
				'title'    => '[XTemos] Waves wide',
				'has_flip' => false,
				'path'     => WOODMART_THEMEROOT . '/images/svg/waves-wide-top.svg',
				'url'      => WOODMART_IMAGES . '/svg/waves-wide-top.svg',
			],
		];
	}

	add_filter( 'elementor/shapes/additional_shapes', 'woodmart_elementor_custom_shapes' );
}

if ( ! function_exists( 'woodmart_elementor_custom_animations' ) ) {
	/**
	 * Custom animations.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_elementor_custom_animations() {
		return [
			'XTemos' => [
				'wd-anim-slide-from-bottom' => 'Slide From Bottom',
				'wd-anim-slide-from-top'    => 'Slide From Top',
				'wd-anim-slide-from-left'   => 'Slide From Left',
				'wd-anim-slide-from-right'  => 'Slide From Right',
				'wd-anim-left-flip-y'       => 'Left Flip Y',
				'wd-anim-right-flip-y'      => 'Right Flip Y',
				'wd-anim-top-flip-x'        => 'Top Flip X',
				'wd-anim-bottom-flip-x'     => 'Bottom Flip X',
				'wd-anim-zoom-in'           => 'Zoom In',
				'wd-anim-rotate-z'          => 'Rotate Z',
			],
		];
	}

	add_filter( 'elementor/controls/animations/additional_animations', 'woodmart_elementor_custom_animations' );
}

if ( ! function_exists( 'woodmart_elementor_get_content' ) ) {
	/**
	 * Retrieve builder content for display.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id The post ID.
	 *
	 * @return string
	 */
	function woodmart_elementor_get_content( $id ) {
		$inline_css = apply_filters( 'woodmart_elementor_content_inline_css', true );

		$content = Plugin::$instance->frontend->get_builder_content_for_display( $id, $inline_css );

		if ( $inline_css ) {
			wp_deregister_style( 'elementor-post-' . $id );
			wp_dequeue_style( 'elementor-post-' . $id );
		}

		return $content;
	}
}

if ( ! function_exists( 'woodmart_get_link_attrs' ) ) {
	/**
	 * Get image url
	 *
	 * @since 1.0.0
	 *
	 * @param array $link Link data array.
	 *
	 * @return string
	 */
	function woodmart_get_link_attrs( $link ) {
		$link_attrs = '';

		if ( isset( $link['url'] ) && $link['url'] ) {
			$link_attrs = ' href="' . esc_url( $link['url'] ) . '"';

			if ( isset( $link['is_external'] ) && 'on' === $link['is_external'] ) {
				$link_attrs .= ' target="_blank"';
			}

			if ( isset( $link['nofollow'] ) && 'on' === $link['nofollow'] ) {
				$link_attrs .= ' rel="nofollow noopener"';
			}
		}

		if ( isset( $link['class'] ) ) {
			$link_attrs .= ' class="' . esc_attr( $link['class'] ) . '"';
		}

		if ( isset( $link['data'] ) ) {
			$link_attrs .= $link['data'];
		}

		if ( isset( $link['custom_attributes'] ) ) {
			$custom_attributes = Utils::parse_custom_attributes( $link['custom_attributes'] );
			foreach ( $custom_attributes as $key => $value ) {
				$link_attrs .= ' ' . $key . '="' . $value . '"';
			}
		}

		return $link_attrs;
	}
}

if ( ! function_exists( 'woodmart_elementor_get_render_icon' ) ) {
	/**
	 * Render Icon
	 *
	 * @since 1.0.0
	 *
	 * @param array  $icon       Icon Type, Icon value.
	 * @param array  $attributes Icon HTML Attributes.
	 * @param string $tag        Icon HTML tag, defaults to <i>.
	 *
	 * @return mixed|string
	 */
	function woodmart_elementor_get_render_icon( $icon, $attributes = [], $tag = 'i' ) {
		ob_start();
		Icons_Manager::render_icon( $icon, $attributes, $tag );
		return ob_get_clean();
	}
}

if ( ! function_exists( 'woodmart_get_posts_by_query' ) ) {
	/**
	 * Get post by search
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_posts_by_query() {
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : ''; // phpcs:ignore
		$post_type     = isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'post'; // phpcs:ignore
		$results       = array();

		$query = new WP_Query(
			array(
				's'              => $search_string,
				'post_type'      => $post_type,
				'posts_per_page' => - 1,
			)
		);

		if ( ! isset( $query->posts ) ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			$results[] = array(
				'id'   => $post->ID,
				'text' => $post->post_title,
			);
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_woodmart_get_posts_by_query', 'woodmart_get_posts_by_query' );
	add_action( 'wp_ajax_nopriv_woodmart_get_posts_by_query', 'woodmart_get_posts_by_query' );
}

if ( ! function_exists( 'woodmart_get_posts_title_by_id' ) ) {
	/**
	 * Get post title by ID
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_posts_title_by_id() {
		$ids       = isset( $_POST['id'] ) ? $_POST['id'] : array(); // phpcs:ignore
		$post_type = isset( $_POST['post_type'] ) ? $_POST['post_type'] : 'post'; // phpcs:ignore
		$results   = array();

		$query = new WP_Query(
			array(
				'post_type'      => $post_type,
				'post__in'       => $ids,
				'posts_per_page' => - 1,
				'orderby'        => 'post__in',
			)
		);

		if ( ! isset( $query->posts ) ) {
			return;
		}

		foreach ( $query->posts as $post ) {
			$results[ $post->ID ] = $post->post_title;
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_woodmart_get_posts_title_by_id', 'woodmart_get_posts_title_by_id' );
	add_action( 'wp_ajax_nopriv_woodmart_get_posts_title_by_id', 'woodmart_get_posts_title_by_id' );
}

if ( ! function_exists( 'woodmart_get_taxonomies_title_by_id' ) ) {
	/**
	 * Get taxonomies title by id
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_taxonomies_title_by_id() {
		$ids     = isset( $_POST['id'] ) ? $_POST['id'] : array(); // phpcs:ignore
		$results = array();

		$args = array(
			'include' => $ids,
		);

		$terms = get_terms( $args );

		if ( is_array( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {
					$results[ $term->term_id ] = $term->name . ' (' . $term->taxonomy . ')';
				}
			}
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_woodmart_get_taxonomies_title_by_id', 'woodmart_get_taxonomies_title_by_id' );
	add_action( 'wp_ajax_nopriv_woodmart_get_taxonomies_title_by_id', 'woodmart_get_taxonomies_title_by_id' );
}

if ( ! function_exists( 'woodmart_get_taxonomies_by_query' ) ) {
	/**
	 * Get taxonomies by search
	 *
	 * @since 1.0.0
	 */
	function woodmart_get_taxonomies_by_query() {
		$search_string = isset( $_POST['q'] ) ? sanitize_text_field( wp_unslash( $_POST['q'] ) ) : ''; // phpcs:ignore
		$taxonomy      = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : ''; // phpcs:ignore
		$results       = array();

		$args = array(
			'taxonomy'   => $taxonomy,
			'hide_empty' => false,
			'search'     => $search_string,
		);

		$terms = get_terms( $args );

		if ( is_array( $terms ) && $terms ) {
			foreach ( $terms as $term ) {
				if ( is_object( $term ) ) {
					$results[] = array(
						'id'   => $term->term_id,
						'text' => $term->name . ' (' . $term->taxonomy . ')',
					);
				}
			}
		}

		wp_send_json( $results );
	}

	add_action( 'wp_ajax_woodmart_get_taxonomies_by_query', 'woodmart_get_taxonomies_by_query' );
	add_action( 'wp_ajax_nopriv_woodmart_get_taxonomies_by_query', 'woodmart_get_taxonomies_by_query' );
}

if ( ! function_exists( 'woodmart_register_elementor_controls' ) ) {
	/**
	 * Registering New Controls
	 *
	 * @since 1.0.0
	 */
	function woodmart_register_elementor_controls() {
		$controls_manager = Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'wd_autocomplete', new Autocomplete() );
		$controls_manager->register_control( 'wd_buttons', new Buttons() );
		$controls_manager->register_control( 'wd_google_json', new Google_Json() );
	}

	add_action( 'elementor/controls/controls_registered', 'woodmart_register_elementor_controls' );
}

if ( ! function_exists( 'woodmart_elementor_has_location' ) ) {
	/**
	 * Custom shapes.
	 *
	 * @since 1.0.0
	 */
	function woodmart_elementor_has_location( $location ) {
		if ( ! woodmart_is_elementor_pro_installed() ) {
			return false;
		}

		$conditions_manager = \ElementorPro\Plugin::instance()->modules_manager->get_modules( 'theme-builder' )->get_conditions_manager();
		$documents          = $conditions_manager->get_documents_for_location( $location );

		return ! empty( $documents );
	}
}

if ( ! function_exists( 'woodmart_elementor_enqueue_editor_styles' ) ) {
	/**
	 * Enqueue elementor editor custom styles
	 *
	 * @since 1.0.0
	 */
	function woodmart_elementor_enqueue_editor_styles() {
		wp_enqueue_style( 'woodmart-elementor-editor-style', WOODMART_THEME_DIR . '/inc/integrations/elementor/assets/css/editor.css', array( 'elementor-editor' ), woodmart_get_theme_info( 'Version' ) );
	}

	add_action( 'elementor/editor/before_enqueue_styles', 'woodmart_elementor_enqueue_editor_styles' );
}

if ( ! function_exists( 'woodmart_add_elementor_widget_categories' ) ) {
	/**
	 * Add theme widget categories
	 *
	 * @since 1.0.0
	 */
	function woodmart_add_elementor_widget_categories() {
		Plugin::instance()->elements_manager->add_category(
			'wd-elements',
			array(
				'title' => esc_html__( '[XTemos] Elements', 'woodmart' ),
				'icon'  => 'fab fa-plug',
			)
		);
	}

	woodmart_add_elementor_widget_categories();
}

if ( ! function_exists( 'woodmart_get_image_html' ) ) {
	/**
	 * Get image url
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings       Control settings.
	 * @param string $image_size_key Settings key for image size.
	 *
	 * @return string
	 */
	function woodmart_get_image_html( $settings, $image_size_key = '' ) {
		if ( ! woodmart_is_elementor_installed() ) {
			return wp_get_attachment_image( $settings[ $image_size_key ]['id'], $settings[ $image_size_key . '_size' ] );
		}

		return Group_Control_Image_Size::get_attachment_image_html( $settings, $image_size_key );
	}
}

if ( ! function_exists( 'woodmart_elementor_is_edit_mode' ) ) {
	/**
	 * Whether the edit mode is active.
	 *
	 * @since 1.0.0
	 */
	function woodmart_elementor_is_edit_mode() {
		if ( ! woodmart_is_elementor_installed() ) {
			return false;
		}

		return Plugin::$instance->editor->is_edit_mode();
	}
}

if ( ! function_exists( 'woodmart_elementor_is_preview_mode' ) ) {
	/**
	 * Whether the preview mode is active.
	 *
	 * @since 1.0.0
	 */
	function woodmart_elementor_is_preview_mode() {
		return Plugin::$instance->preview->is_preview_mode();
	}
}

if ( ! function_exists( 'woodmart_elementor_is_preview_page' ) ) {
	/**
	 * Whether the preview page.
	 *
	 * @since 1.0.0
	 */
	function woodmart_elementor_is_preview_page() {
		return isset( $_GET['preview_id'] );
	}
}

if ( ! function_exists( 'woodmart_get_image_url' ) ) {
	/**
	 * Get image url
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id             Image id.
	 * @param string  $image_size_key Settings key for image size.
	 * @param array   $settings       Control settings.
	 *
	 * @return string
	 */
	function woodmart_get_image_url( $id, $image_size_key, $settings ) {
		if ( ! woodmart_is_elementor_installed() ) {
			return wp_get_attachment_image_src( $id, $settings[ $image_size_key . '_size' ] )[0];
		}

		return Group_Control_Image_Size::get_attachment_image_src( $id, $image_size_key, $settings );
	}
}

if ( ! function_exists( 'woodmart_get_all_image_sizes' ) ) {
	/**
	 * Retrieve available image sizes
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function woodmart_get_all_image_sizes() {
		global $_wp_additional_image_sizes;

		$default_image_sizes = array( 'thumbnail', 'medium', 'medium_large', 'large' );
		$image_sizes         = array();

		foreach ( $default_image_sizes as $size ) {
			$image_sizes[ $size ] = array(
				'width'  => (int) get_option( $size . '_size_w' ),
				'height' => (int) get_option( $size . '_size_h' ),
				'crop'   => (bool) get_option( $size . '_crop' ),
			);
		}

		if ( $_wp_additional_image_sizes ) {
			$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
		}

		$image_sizes['full'] = array();

		return $image_sizes;
	}
}

if ( ! function_exists( 'woodmart_get_all_image_sizes_names' ) ) {
	/**
	 * Retrieve available image sizes names
	 *
	 * @since 1.0.0
	 *
	 * @param string $style Array output style.
	 *
	 * @return array
	 */
	function woodmart_get_all_image_sizes_names( $style = 'default' ) {
		$available_sizes = woodmart_get_all_image_sizes();
		$image_sizes     = array();

		foreach ( $available_sizes as $size => $size_attributes ) {
			$name = ucwords( str_replace( '_', ' ', $size ) );
			if ( is_array( $size_attributes ) && ( isset( $size_attributes['width'] ) || isset( $size_attributes['height'] ) ) ) {
				$name .= ' - ' . $size_attributes['width'] . ' x ' . $size_attributes['height'];
			}

			if ( 'elementor' === $style ) {
				$image_sizes[ $size ] = $name;
			} elseif ( 'header_builder' === $style ) {
				$image_sizes[ $size ] = array(
					'label' => $name,
					'value' => $size,
				);
			} elseif ( 'default' === $style ) {
				$image_sizes[ $size ] = array(
					'name'  => $name,
					'value' => $size,
				);
			} elseif ( 'widget' === $style ) {
				$image_sizes[ $name ] = $size;
			}
		}

		if ( 'elementor' === $style ) {
			$image_sizes['custom'] = esc_html__( 'Custom', 'woodmart' );
		} elseif ( 'header_builder' === $style ) {
			$image_sizes['custom'] = array(
				'label' => esc_html__( 'Custom', 'woodmart' ),
				'value' => 'custom',
			);
		} elseif ( 'default' === $style ) {
			$image_sizes['custom'] = array(
				'name'  => esc_html__( 'Custom', 'woodmart' ),
				'value' => 'custom',
			);
		} elseif ( 'widget' === $style ) {
			$image_sizes[ esc_html__( 'Custom', 'woodmart' ) ] = 'custom';
		}

		return $image_sizes;
	}
}

if ( ! function_exists( 'woodmart_add_custom_font_group' ) ) {
	/**
	 * Add custom font group to font control
	 *
	 * @since 1.0.0
	 *
	 * @param array $font_groups Default font groups.
	 *
	 * @return array
	 */
	function woodmart_add_custom_font_group( $font_groups ) {
		$font_groups = array( 'wd_fonts' => esc_html__( 'Theme fonts', 'woodmart' ) ) + $font_groups;

		return $font_groups;
	}

	add_filter( 'elementor/fonts/groups', 'woodmart_add_custom_font_group' );
}

if ( ! function_exists( 'woodmart_add_custom_fonts_to_theme_group' ) ) {
	/**
	 * Add custom fonts to theme group
	 *
	 * @since 1.0.0
	 *
	 * @param array $additional_fonts Additional fonts.
	 *
	 * @return array
	 */
	function woodmart_add_custom_fonts_to_theme_group( $additional_fonts ) {
		$theme_fonts  = array();
		$content_font = woodmart_get_opt( 'primary-font' );
		$title_font   = woodmart_get_opt( 'text-font' );
		$alt_font     = woodmart_get_opt( 'secondary-font' );

		if ( isset( $content_font[0] ) && isset( $content_font[0]['font-family'] ) && $content_font[0]['font-family'] ) {
			$theme_fonts[ $content_font[0]['font-family'] ] = 'wd_fonts';
		}

		if ( isset( $title_font[0] ) && isset( $title_font[0]['font-family'] ) && $title_font[0]['font-family'] ) {
			$theme_fonts[ $title_font[0]['font-family'] ] = 'wd_fonts';
		}

		if ( isset( $alt_font[0] ) && isset( $alt_font[0]['font-family'] ) && $alt_font[0]['font-family'] ) {
			$theme_fonts[ $alt_font[0]['font-family'] ] = 'wd_fonts';
		}

		$additional_fonts = $theme_fonts + $additional_fonts;

		return $additional_fonts;
	}

	add_filter( 'elementor/fonts/additional_fonts', 'woodmart_add_custom_fonts_to_theme_group' );
}
