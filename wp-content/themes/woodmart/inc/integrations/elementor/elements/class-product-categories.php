<?php
/**
 * Title map.
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
class Product_Categories extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_product_categories';
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
		return esc_html__( 'Product categories', 'woodmart' );
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
		return 'wd-icon-product-categories';
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
				'label'       => esc_html__( 'Order by', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''           => '',
					'id'         => esc_html__( 'ID', 'woodmart' ),
					'date'       => esc_html__( 'Date', 'woodmart' ),
					'title'      => esc_html__( 'Title', 'woodmart' ),
					'menu_order' => esc_html__( 'Menu order', 'woodmart' ),
					'modified'   => esc_html__( 'Last modified date', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Sort order', 'woodmart' ),
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
			'ids',
			[
				'label'       => esc_html__( 'Categories', 'woodmart' ),
				'description' => esc_html__( 'List of product categories.', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_taxonomies_by_query',
				'render'      => 'woodmart_get_taxonomies_title_by_id',
				'taxonomy'    => 'product_cat',
				'multiple'    => true,
				'label_block' => true,
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label'        => esc_html__( 'Hide empty', 'woodmart' ),
				'description'  => esc_html__( 'Don’t display categories that don’t have any products assigned.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Style tab.
		 */

		/**
		 * Design settings.
		 */
		$this->start_controls_section(
			'design_style_section',
			[
				'label' => esc_html__( 'Design', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'categories_design',
			[
				'label'       => esc_html__( 'Categories design', 'woodmart' ),
				'description' => esc_html__( 'Overrides option from Theme Settings -> Shop', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'inherit',
				'options'     => array(
					'inherit'       => esc_html__( 'Inherit from Theme Settings', 'woodmart' ),
					'default'       => esc_html__( 'Default', 'woodmart' ),
					'alt'           => esc_html__( 'Alternative', 'woodmart' ),
					'center'        => esc_html__( 'Center title', 'woodmart' ),
					'replace-title' => esc_html__( 'Replace title', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'categories_with_shadow',
			[
				'label'     => esc_html__( 'Categories with shadow', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''        => esc_html__( 'Inherit', 'woodmart' ),
					'enable'  => esc_html__( 'Enable', 'woodmart' ),
					'disable' => esc_html__( 'Disable', 'woodmart' ),
				),
				'condition' => [
					'categories_design' => [ 'alt', 'default' ],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Layout settings.
		 */
		$this->start_controls_section(
			'layout_style_section',
			[
				'label' => esc_html__( 'Layout', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'style',
			[
				'label'       => esc_html__( 'Layout', 'woodmart' ),
				'description' => esc_html__( 'Try out our creative styles for categories block.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => array(
					'default'       => esc_html__( 'Default', 'woodmart' ),
					'masonry'       => esc_html__( 'Masonry', 'woodmart' ),
					'masonry-first' => esc_html__( 'Masonry (with first wide)', 'woodmart' ),
					'carousel'      => esc_html__( 'Carousel', 'woodmart' ),
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
					'size' => 4,
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
					'style' => [ 'masonry', 'default' ],
				],
			]
		);
		
		$this->add_control(
			'spacing',
			[
				'label'     => esc_html__( 'Space between', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''  => esc_html__( 'Inherit', 'woodmart' ),
					0  => esc_html__( '0 px', 'woodmart' ),
					2  => esc_html__( '2 px', 'woodmart' ),
					6  => esc_html__( '6 px', 'woodmart' ),
					10 => esc_html__( '10 px', 'woodmart' ),
					20 => esc_html__( '20 px', 'woodmart' ),
					30 => esc_html__( '30 px', 'woodmart' ),
				],
				'default'   => '',
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
					'style' => [ 'carousel' ],
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
				'condition' => [
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

		/**
		 * Extra settings.
		 */
		$this->start_controls_section(
			'extra_style_section',
			[
				'label' => esc_html__( 'Extra', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lazy_loading',
			[
				'label'        => esc_html__( 'Lazy loading for images', 'woodmart' ),
				'description'  => esc_html__( 'Enable lazy loading for images for this element.', 'woodmart' ),
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
		if ( ! woodmart_woocommerce_installed() ) {
			return;
		}

		$default_settings = [
			// Query.
			'number'                 => null,
			'orderby'                => '',
			'order'                  => 'ASC',
			'ids'                    => '',

			// Layout.
			'columns'                => '4',
			'hide_empty'             => 'yes',
			'spacing'                => woodmart_get_opt( 'products_spacing' ),
			'style'                  => 'default',

			// Design.
			'categories_design'      => woodmart_get_opt( 'categories_design' ),
			'categories_with_shadow' => woodmart_get_opt( 'categories_with_shadow' ),

			// Extra.
			'lazy_loading'           => 'no',
			'scroll_carousel_init'   => 'no',
			'custom_sizes'           => apply_filters( 'woodmart_categories_shortcode_custom_sizes', false ),
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		if ( ! $settings['spacing'] ) {
			$settings['spacing'] = woodmart_get_opt( 'products_spacing' );
		}

		// Query.
		$query_args = array(
			'taxonomy'   => 'product_cat',
			'order'      => $settings['order'],
			'hide_empty' => 'yes' === $settings['hide_empty'],
			'include'    => $settings['ids'],
			'pad_counts' => true,
			'number'     => $settings['number'],
		);

		if ( $settings['orderby'] ) {
			$query_args['orderby'] = $settings['orderby'];
		}

		$categories = get_terms( $query_args );

		// Settings.
		if ( 'masonry' === $settings['style'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'categories-masonry' );
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_library( 'isotope-bundle' );
			woodmart_enqueue_js_script( 'shop-masonry' );
		}

		woodmart_set_loop_prop( 'products_different_sizes', false );

		if ( 'masonry-first' === $settings['style'] ) {
			woodmart_set_loop_prop( 'products_different_sizes', [ 1 ] );
			$this->add_render_attribute( 'wrapper', 'class', 'categories-masonry' );
			$settings['columns']['size'] = 4;
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_library( 'isotope-bundle' );
			woodmart_enqueue_js_script( 'shop-masonry' );
		}

		if ( 'inherit' === $settings['categories_design'] ) {
			$settings['categories_design'] = woodmart_get_opt( 'categories_design' );
		}

		$settings['columns'] = isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : 4;

		woodmart_set_loop_prop( 'product_categories_design', $settings['categories_design'] );
		woodmart_set_loop_prop( 'product_categories_shadow', $settings['categories_with_shadow'] );
		woodmart_set_loop_prop( 'products_columns', $settings['columns'] );
		woodmart_set_loop_prop( 'product_categories_style', $settings['style'] );

		// Wrapper classes.
		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'products',
						'woocommerce',
						'columns' . $settings['columns'],
						'categories-style-' . $settings['style'],
					],
				],
			]
		);

		// Lazy loading.
		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_init( true );
			woodmart_enqueue_inline_style( 'lazy-loading' );
		}

		woodmart_enqueue_inline_style( 'categories-loop' );

		?>
		<?php if ( $categories ) : ?>
			<?php if ( 'carousel' === $settings['style'] ) : ?>
				<?php
				$carousel_id                 = 'carousel-' . uniqid();
				$settings['carousel_id']     = $carousel_id;
				$settings['post_type']       = 'product';
				$settings['slides_per_view'] = isset( $settings['slides_per_view']['size'] ) ? $settings['slides_per_view']['size'] : 3;

				// Wrapper classes.
				if ( 'yes' === $settings['scroll_carousel_init'] ) {
					woodmart_enqueue_js_library( 'waypoints' );
					$this->add_render_attribute( 'wrapper', 'class', 'scroll-init' );
				}
				if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
					$this->add_render_attribute( 'wrapper', 'class', 'disable-owl-mobile' );
				}
				$this->add_render_attribute( 'wrapper', 'class', 'wd-carousel-spacing-' . $settings['spacing'] );
				$this->add_render_attribute( 'wrapper', 'class', 'categories-style-' . $settings['style'] );
				$this->add_render_attribute( 'wrapper', 'id', $carousel_id );

				// Owl classes.
				woodmart_enqueue_inline_style( 'owl-carousel' );
				$this->add_render_attribute(
					[
						'owl' => [
							'class' => [
								'owl-carousel',
								'carousel-items',
								woodmart_owl_items_per_slide( $settings['slides_per_view'], array(), 'product', false, $settings['custom_sizes'] ),
							],
						],
					]
				);
				?>

				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> <?php echo woodmart_get_owl_attributes( $settings ); ?>>
					<div <?php echo $this->get_render_attribute_string( 'owl' ); ?>>
						<?php foreach ( $categories as $category ) : ?>
							<?php
							wc_get_template(
								'content-product-cat.php',
								array(
									'category' => $category,
								)
							);
							?>
						<?php endforeach; ?>
					</div>
				</div>
			<?php else : ?>
				<?php
				$this->add_render_attribute( 'wrapper', 'class', 'row' );
				$this->add_render_attribute( 'wrapper', 'class', 'wd-spacing-' . $settings['spacing'] );
				?>

				<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
					<?php foreach ( $categories as $category ) : ?>
						<?php
						wc_get_template(
							'content-product-cat.php',
							array(
								'category' => $category,
							)
						);
						?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php
		woodmart_reset_loop();
		wc_reset_loop();

		// Lazy loading.
		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_deinit();
		}
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Product_Categories() );
