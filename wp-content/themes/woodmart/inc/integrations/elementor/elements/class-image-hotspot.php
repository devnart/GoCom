<?php
/**
 * Image hotspot map.
 */

use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;
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
class Image_Hotspot extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_image_hotspot';
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
		return esc_html__( 'Image Hotspot', 'woodmart' );
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
		return 'wd-icon-hotspot';
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
			'image',
			[
				'label'   => esc_html__( 'Choose image', 'woodmart' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'large',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		/**
		 * Items settings.
		 */
		$this->start_controls_section(
			'items_content_section',
			[
				'label' => esc_html__( 'Items', 'woodmart' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'hotspot_tabs' );

		$repeater->start_controls_tab(
			'content_tab',
			[
				'label' => esc_html__( 'Content', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'hotspot_type',
			[
				'label'   => esc_html__( 'Type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'text'    => esc_html__( 'Text', 'woodmart' ),
					'product' => esc_html__( 'Product', 'woodmart' ),
				],
				'default' => 'product',
			]
		);

		$repeater->add_control(
			'hotspot_dropdown_side',
			[
				'label'       => esc_html__( 'Dropdown side', 'woodmart' ),
				'description' => esc_html__( 'Show the content on left or right side, top or bottom.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'left'   => esc_html__( 'Left', 'woodmart' ),
					'right'  => esc_html__( 'Right', 'woodmart' ),
					'top'    => esc_html__( 'Top', 'woodmart' ),
					'bottom' => esc_html__( 'Bottom', 'woodmart' ),
				],
				'default'     => 'left',
			]
		);

		/**
		 * Text settings
		 */
		$repeater->add_control(
			'title',
			[
				'label'     => esc_html__( 'Title', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Title, click to edit.',
				'condition' => [
					'hotspot_type' => [ 'text' ],
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'     => esc_html__( 'Choose image', 'woodmart' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'hotspot_type' => [ 'text' ],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'large',
				'separator' => 'none',
				'condition' => [
					'hotspot_type' => [ 'text' ],
				],
			]
		);

		$repeater->add_control(
			'link_text',
			[
				'label'     => esc_html__( 'Link text', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Button',
				'condition' => [
					'hotspot_type' => [ 'text' ],
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'     => esc_html__( 'Link', 'woodmart' ),
				'type'      => Controls_Manager::URL,
				'default'   => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
				'condition' => [
					'hotspot_type' => [ 'text' ],
				],
			]
		);

		$repeater->add_control(
			'content',
			[
				'label'     => esc_html__( 'Content', 'woodmart' ),
				'type'      => Controls_Manager::TEXTAREA,
				'condition' => [
					'hotspot_type' => [ 'text' ],
				],
			]
		);

		/**
		 * Product settings
		 */
		$repeater->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Select product', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_posts_by_query',
				'render'      => 'woodmart_get_posts_title_by_id',
				'post_type'   => 'product',
				'multiple'    => false,
				'label_block' => true,
				'condition'   => [
					'hotspot_type' => [ 'product' ],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'position_tab',
			[
				'label' => esc_html__( 'Position', 'woodmart' ),
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_horizontal',
			[
				'label'     => esc_html__( 'Horizontal position (%)', 'woodmart' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 50,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wd-image-hotspot' => 'left: {{SIZE}}%;',
				],
			]
		);

		$repeater->add_responsive_control(
			'hotspot_position_vertical',
			[
				'label'     => esc_html__( 'Vertical position (%)', 'woodmart' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 50,
				],
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}.wd-image-hotspot' => 'top: {{SIZE}}%;',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		/**
		 * Repeater settings
		 */
		$this->add_control(
			'items',
			[
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $repeater->get_controls(),
				'default' => [
					[
						'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
					],
				],
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
			'icon',
			[
				'label'   => esc_html__( 'Hotspot icon', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'alt'     => esc_html__( 'Alternative', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'action',
			[
				'label'       => esc_html__( 'Hotspot action', 'woodmart' ),
				'description' => esc_html__( 'Open hotspot content on click or hover', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'hover' => esc_html__( 'Hover', 'woodmart' ),
					'click' => esc_html__( 'Click', 'woodmart' ),
				],
				'default'     => 'hover',
			]
		);

		$this->add_control(
			'woodmart_color_scheme',
			[
				'label'   => esc_html__( 'Color Scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''      => esc_html__( 'Inherit', 'woodmart' ),
					'light' => esc_html__( 'Light', 'woodmart' ),
					'dark'  => esc_html__( 'Dark', 'woodmart' ),
				],
				'default' => '',
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
			'image'                 => '',
			'action'                => 'hover',
			'icon'                  => 'default',
			'woodmart_color_scheme' => 'dark',
			'items'                 => [],
		];

		$settings     = wp_parse_args( $this->get_settings_for_display(), $default_settings );
		$image_output = '';

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'wd-image-hotspot-wrapper',
						'hotspot-action-' . $settings['action'],
						'hotspot-icon-' . $settings['icon'],
						'color-scheme-' . $settings['woodmart_color_scheme'],
					],
				],
			]
		);

		if ( isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
			$image_url = woodmart_get_image_url( $settings['image']['id'], 'image', $settings );
			$image_output = '<img class="wd-image-hotspot-img" src="' . esc_url( $image_url ) . '">';
		} elseif ( isset( $settings['image']['url'] ) ) {
			$image_output = '<img class="wd-image-hotspot-img" src="' . esc_url( $settings['image']['url'] ) . '">';
		}

		wp_enqueue_script( 'imagesloaded' );
		woodmart_enqueue_js_script( 'hotspot-element' );
		woodmart_enqueue_js_script( 'product-more-description' );
		woodmart_enqueue_inline_style( 'image-hotspot' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="wd-image-hotspot-hotspots">
				<?php if ( $image_output ) : ?>
					<?php echo $image_output; // phpcs:ignore ?>
				<?php endif; ?>
				
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<?php
					$default_settings = [
						'hotspot'               => '',
						'hotspot_type'          => 'product',
						'hotspot_dropdown_side' => 'left',
						'product_id'            => '',
						'title'                 => '',
						'link_text'             => '',
						'link'                  => '',
						'image'                 => '',
					];

					$settings   = wp_parse_args( $item, $default_settings );
					$attributes = '';
					$args       = [];

					if ( 'product' === $settings['hotspot_type'] && $settings['product_id'] ) {
						$product = wc_get_product( $settings['product_id'] );

						if ( ! $product ) {
							return;
						}

						$args = array(
							'class'      => implode(
								' ',
								array_filter(
									array(
										'button',
										'product_type_' . $product->get_type(),
										$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
										$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
									)
								)
							),
							'attributes' => wc_implode_html_attributes(
								array(
									'data-product_id' => $product->get_id(),
									'rel'             => 'nofollow',
								)
							),
						);

					}

					if ( 'text' === $settings['hotspot_type'] && ( $settings['title'] || $settings['content'] || $settings['link_text'] || isset( $settings['image']['id'] ) ) ) {
						$attributes   = woodmart_get_link_attrs( $settings['link'] );
						$image_output = '';

						if ( isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
							$image_output = woodmart_get_image_html( $settings, 'image' );
						}
					}

					?>
					<div class="wd-image-hotspot hotspot-type-<?php echo esc_attr( $settings['hotspot_type'] ); ?> elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<span class="hotspot-sonar"></span>
						<div class="hotspot-btn wd-fill"></div>

						<?php if ( 'product' === $settings['hotspot_type'] && isset( $product ) && $product ) : ?>
							<div class="hotspot-product hotspot-content hotspot-dropdown-<?php echo esc_attr( $settings['hotspot_dropdown_side'] ); ?>">
								<div class="hotspot-content-image">
									<a href="<?php echo esc_url( get_permalink( $product->get_ID() ) ); ?>">
										<?php echo $product->get_image(); ?>
									</a>
								</div>
								
								<h4 class="hotspot-content-title">
									<a href="<?php echo esc_url( get_permalink( $product->get_ID() ) ); ?>">
										<?php echo esc_html( $product->get_title() ); ?>
									</a>
								</h4>
								
								<?php if ( wc_review_ratings_enabled() ) : ?>
									<?php echo wc_get_rating_html( $product->get_rating_count(), $product->get_average_rating() ); ?>
								<?php endif; ?>
								
								<div class="price">
									<?php echo $product->get_price_html(); ?>
								</div>
								
								<div class="hotspot-content-text wd-more-desc set-cont-mb-s reset-last-child<?php echo woodmart_get_old_classes( ' woodmart-more-desc' ); ?>">
									<div class="wd-more-desc-inner<?php echo woodmart_get_old_classes( ' woodmart-more-desc-inner' ); ?>">
										<?php echo do_shortcode( $product->get_short_description() ); ?>
									</div>
									<a href="#" rel="noffollow" class="wd-more-desc-btn<?php echo woodmart_get_old_classes( ' woodmart-more-desc-btn' ); ?>"><span></span></a>
								</div>
								
								<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="<?php echo esc_attr( $args['class'] ); ?>" <?php echo $args['attributes']; ?>>
									<?php echo esc_html( $product->add_to_cart_text() ); ?>
								</a>
							</div>
						<?php else : ?>
							<div class="hotspot-text hotspot-content hotspot-dropdown-<?php echo esc_attr( $settings['hotspot_dropdown_side'] ); ?>">
								<div class="hotspot-content-image">
									<?php echo $image_output; ?>
								</div>
								
								<h4 class="hotspot-content-title">
									<?php echo esc_html( $settings['title'] ); ?>
								</h4>
								
								<div class="hotspot-content-text set-cont-mb-s reset-last-child">
									<?php echo esc_html( $settings['content'] ); ?>
								</div>
								
								<a class="btn btn-color-primary btn-size-small" <?php echo $attributes; ?>>
									<?php echo esc_html( $settings['link_text'] ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Image_Hotspot() );
