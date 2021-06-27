<?php
/**
 * Products brands map.
 *
 * @package xts
 */

namespace XTS\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Products_Brands extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_products_brands';
	}

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Brands', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-product-brands';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'wd-elements' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		/**
		 * Content tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_content_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
			]
		);

		$this->add_control(
			'number',
			[
				'label'       => esc_html__( 'Number', 'woodmart' ),
				'description' => esc_html__( 'Enter the number of categories to display for this element.', 'woodmart' ),
				'type'        => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'   => esc_html__( 'Order by', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => '',
					'name'    => esc_html__( 'Name', 'woodmart' ),
					'term_id' => esc_html__( 'ID', 'woodmart' ),
					'slug'    => esc_html__( 'Slug', 'woodmart' ),
					'random'  => esc_html__( 'Random order', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Sort order', 'woodmart' ),
				'description' => 'Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''     => esc_html__( 'Inherit', 'woodmart' ),
					'DESC' => esc_html__( 'Descending', 'woodmart' ),
					'ASC'  => esc_html__( 'Ascending', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide empty', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'ids',
			[
				'label'       => esc_html__( 'Brands', 'woodmart' ),
				'description' => esc_html__( 'List of product brands to show. Leave empty to show all.', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_taxonomies_by_query',
				'render'      => 'woodmart_get_taxonomies_title_by_id',
				'taxonomy'    => woodmart_get_opt( 'brands_attribute' ),
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->end_controls_section();

		/**
		 * Style tab.
		 */

		/**
		 * General settings.
		 */
		$this->start_controls_section(
			'general_style_section',
			[
				'label' => esc_html__( 'General', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'hover',
			[
				'label'   => esc_html__( 'Brand images hover', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default' => esc_html__( 'Default', 'woodmart' ),
					'simple'  => esc_html__( 'Simple', 'woodmart' ),
					'alt'     => esc_html__( 'Alternate', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'brand_style',
			[
				'label'   => esc_html__( 'Style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array(
					'default'  => esc_html__( 'Default', 'woodmart' ),
					'bordered' => esc_html__( 'Bordered', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'style',
			[
				'label'   => esc_html__( 'Layout', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'carousel',
				'options' => array(
					'carousel' => esc_html__( 'Carousel', 'woodmart' ),
					'grid'     => esc_html__( 'Grid', 'woodmart' ),
					'list'     => esc_html__( 'Links List', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns', 'woodmart' ),
				'description' => esc_html__( 'Number of columns in the grid.', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 3,
				],
				'size_units'  => '',
				'range'       => [
					'px' => [
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					],
				],
				'condition'   => [
					'style' => array( 'grid', 'list' ),
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Carousel settings.
		 */
		$this->start_controls_section(
			'carousel_style_section',
			[
				'label'     => esc_html__( 'Carousel', 'woodmart' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'style' => 'carousel',
				],
			]
		);

		$this->add_control(
			'slides_per_view',
			[
				'label'       => esc_html__( 'Slides per view', 'woodmart' ),
				'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 3,
				],
				'size_units'  => '',
				'range'       => [
					'px' => [
						'min'  => 1,
						'max'  => 8,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'hide_pagination_control',
			[
				'label'        => esc_html__( 'Hide pagination control', 'woodmart' ),
				'description'  => esc_html__( 'If "YES" pagination control will be removed.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
				'condition'    => [
					'style' => 'carousel',
				],
			]
		);

		$this->add_control(
			'hide_prev_next_buttons',
			[
				'label'        => esc_html__( 'Hide prev/next buttons', 'woodmart' ),
				'description'  => esc_html__( 'If "YES" prev/next control will be removed', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
				'condition'    => [
					'style' => 'carousel',
				],
			]
		);

		$this->add_control(
			'wrap',
			[
				'label'        => esc_html__( 'Slider loop', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
				'condition'    => [
					'style' => 'carousel',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Slider autoplay', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'speed',
			[
				'label'       => esc_html__( 'Slider speed', 'woodmart' ),
				'description' => esc_html__( 'Duration of animation between slides (in ms)', 'woodmart' ),
				'default'     => '5000',
				'type'        => Controls_Manager::NUMBER,
				'condition'   => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'scroll_carousel_init',
			[
				'label'        => esc_html__( 'Init carousel on scroll', 'woodmart' ),
				'description'  => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$default_settings = [
			'username'             => 'flickr',
			'number'               => 20,
			'hover'                => 'default',
			'target'               => '_self',
			'link'                 => '',
			'ids'                  => '',
			'style'                => 'carousel',
			'brand_style'          => 'default',
			'slides_per_view'      => 3,
			'columns'              => 3,
			'orderby'              => '',
			'hide_empty'           => 0,
			'order'                => 'ASC',
			'scroll_carousel_init' => 'no',
			'custom_sizes'         => apply_filters( 'woodmart_brands_shortcode_custom_sizes', false ),
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), array_merge( woodmart_get_owl_atts(), $default_settings ) );

		$carousel_id = 'brands_' . rand( 1000, 9999 );

		$attribute = woodmart_get_opt( 'brands_attribute' );

		if ( empty( $attribute ) || ! taxonomy_exists( $attribute ) ) {
			echo '<div class="wd-notice wd-info">' . esc_html__( 'You must select your brand attribute in Theme Settings -> Shop -> Brands', 'woodmart' ) . '</div>';
			return;
		}

		$settings['columns']         = isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : 3;
		$settings['slides_per_view'] = isset( $settings['slides_per_view']['size'] ) ? $settings['slides_per_view']['size'] : 3;

		$owl_attributes = '';

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'brands-items-wrapper',
						'brands-widget',
						'slider-' . $carousel_id,
						'brands-hover-' . $settings['hover'],
						'brands-style-' . $settings['brand_style'],
					],
					'id'    => [
						$carousel_id,
					],
				],
				'items'   => [
					'class' => [
						'brand-item',
					],
				],
			]
		);

		if ( $settings['style'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'brands-' . $settings['style'] );
		}

		if ( 'carousel' === $settings['style'] ) {
			woodmart_enqueue_inline_style( 'owl-carousel' );
			$settings['scroll_per_page'] = 'yes';
			$settings['carousel_id']     = $carousel_id;
			$owl_attributes              = woodmart_get_owl_attributes( $settings );

			$this->add_render_attribute( 'items_wrapper', 'class', 'owl-carousel ' . woodmart_owl_items_per_slide( $settings['slides_per_view'], array(), false, false, $settings['custom_sizes'] ) );
			$this->add_render_attribute( 'wrapper', 'class', 'wd-carousel-container' );
			$this->add_render_attribute( 'wrapper', 'class', 'wd-carousel-spacing-0' );

			if ( 'yes' === $settings['scroll_carousel_init'] ) {
				woodmart_enqueue_js_library( 'waypoints' );
				$this->add_render_attribute( 'wrapper', 'class', 'scroll-init' );
			}

			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$this->add_render_attribute( 'wrapper', 'class', 'disable-owl-mobile' );
			}
		} else {
			$this->add_render_attribute( 'items_wrapper', 'class', 'row' );
			$this->add_render_attribute( 'items_wrapper', 'class', 'wd-spacing-0' );
			$this->add_render_attribute( 'items', 'class', woodmart_get_grid_el_class( 0, $settings['columns'] ) );
		}

		$args = array(
			'taxonomy'   => $attribute,
			'hide_empty' => $settings['hide_empty'],
			'order'      => $settings['order'],
			'number'     => $settings['number'],
		);

		if ( $settings['orderby'] ) {
			$args['orderby'] = $settings['orderby'];
		}

		if ( 'random' === $settings['orderby'] ) {
			$args['orderby'] = 'id';
			$brand_count     = wp_count_terms(
				$attribute,
				array(
					'hide_empty' => $settings['hide_empty'],
				)
			);

			$offset = rand( 0, $brand_count - (int) $settings['number'] );
			if ( $offset <= 0 ) {
				$offset = '';
			}
			$args['offset'] = $offset;
		}

		if ( $settings['ids'] ) {
			$args['include'] = $settings['ids'];
		}

		$brands   = get_terms( $args );
		$taxonomy = get_taxonomy( $attribute );

		if ( 'random' === $settings['orderby'] ) {
			shuffle( $brands );
		}

		if ( woodmart_is_shop_on_front() ) {
			$link = home_url();
		} else {
			$link = get_post_type_archive_link( 'product' );
		}

		woodmart_enqueue_inline_style( 'brands' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> <?php echo $owl_attributes; ?>>
			<div <?php echo $this->get_render_attribute_string( 'items_wrapper' ); ?>>
				<?php if ( ! is_wp_error( $brands ) && count( $brands ) > 0 ) : ?>
					<?php foreach ( $brands as $key => $brand ) : ?>
						<?php
						$image       = get_term_meta( $brand->term_id, 'image', true );
						$filter_name = 'filter_' . sanitize_title( str_replace( 'pa_', '', $attribute ) );

						if ( is_object( $taxonomy ) && $taxonomy->public ) {
							$attr_link = get_term_link( $brand->term_id, $brand->taxonomy );
						} else {
							$attr_link = add_query_arg( $filter_name, $brand->slug, $link );
						}
						?>

						<div <?php echo $this->get_render_attribute_string( 'items' ); ?>>
							<a title="Brand <?php echo esc_html( $brand->name ); ?>" href="<?php echo esc_url( $attr_link ); ?>">
								<?php if ( 'list' === $settings['style'] || ! $image ) : ?>
									<span class="brand-title-wrap">
										<?php echo esc_html( $brand->name ); ?>
									</span>
								<?php else : ?>
									<?php
									echo wp_get_attachment_image(
										attachment_url_to_postid( $image ),
										'full',
										false,
										array(
											'title' => $brand->name,
											'alt'   => $brand->name,
										)
									);
									?>
								<?php endif; ?>
							</a>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Products_Brands() );
