<?php
/**
 * Pricing tables map.
 */

use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
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
class Pricing_Tables extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_pricing_tables';
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
		return esc_html__( 'Pricing tables', 'woodmart' );
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
		return 'wd-icon-pricing-tables';
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

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'pricing_tabs' );

		$repeater->start_controls_tab(
			'text_tab',
			[
				'label' => esc_html__( 'Text', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'name',
			[
				'label'   => esc_html__( 'Pricing plan name', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Base',
			]
		);

		$repeater->add_control(
			'features_list',
			[
				'label'       => esc_html__( 'Featured list', 'woodmart' ),
				'description' => esc_html__( 'Start each feature text from a new line', 'woodmart' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => 'Feature 1
Feature 2
Feature 3',
			]
		);

		$repeater->add_control(
			'price_value',
			[
				'label'   => esc_html__( 'Price value', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '199',
			]
		);

		$repeater->add_control(
			'price_suffix',
			[
				'label'   => esc_html__( 'Price suffix', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'per month',
			]
		);

		$repeater->add_control(
			'currency',
			[
				'label'   => esc_html__( 'Price currency', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '$',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'button_tab',
			[
				'label' => esc_html__( 'Button', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'button_label',
			[
				'label'   => esc_html__( 'Text', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Add to cart',
			]
		);

		$repeater->add_control(
			'button_type',
			[
				'label'   => esc_html__( 'Type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'custom'  => esc_html__( 'Custom', 'woodmart' ),
					'product' => esc_html__( 'Product add to cart', 'woodmart' ),
				],
				'default' => 'custom',
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
					'button_type' => 'custom',
				],
			]
		);

		$repeater->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Select identificator', 'woodmart' ),
				'description' => esc_html__( 'Input product ID or product SKU or product title to see suggestions.', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_posts_by_query',
				'render'      => 'woodmart_get_posts_title_by_id',
				'post_type'   => 'product',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'button_type' => 'product',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'label_tab',
			[
				'label' => esc_html__( 'Label', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'label',
			[
				'label' => esc_html__( 'Label text', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$repeater->add_control(
			'label_color',
			[
				'label'   => esc_html__( 'Label color', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''       => '',
					'red'    => esc_html__( 'Red', 'woodmart' ),
					'green'  => esc_html__( 'Green', 'woodmart' ),
					'blue'   => esc_html__( 'Blue', 'woodmart' ),
					'yellow' => esc_html__( 'Yellow', 'woodmart' ),
				],
				'default' => 'red',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'style_tab',
			[
				'label' => esc_html__( 'Style', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'style',
			[
				'label'   => esc_html__( 'Style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'alt'     => esc_html__( 'Alternative', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$repeater->add_control(
			'best_option',
			[
				'label'        => esc_html__( 'Best option', 'woodmart' ),
				'description'  => esc_html__( 'Highlight this price plan as best value with extra styles.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$repeater->add_control(
			'with_bg_image',
			[
				'label'        => esc_html__( 'With background image', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$repeater->add_control(
			'bg_image',
			[
				'label'     => esc_html__( 'Choose image', 'woodmart' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'with_bg_image' => [ 'yes' ],
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'bg_image',
				'default'   => 'thumbnail',
				'separator' => 'none',
				'condition' => [
					'with_bg_image' => [ 'yes' ],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'items',
			[
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ name }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'name'          => 'Base',
						'price_value'   => '199',
						'currency'      => '$',
						'label'         => 'Base',
						'label_color'   => 'green',
						'features_list' => '16 gb
3 GB LPDDR3
5.2-inch
Helio X25 processor
21.16 megapixel',
					],
					[
						'name'          => 'Premium',
						'price_value'   => '260',
						'currency'      => '$',
						'label'         => 'Extra',
						'label_color'   => 'yellow',
						'features_list' => '32 gb
4 GB LPDDR3
5.2-inch
Helio X25 processor
21.16 megapixel',
					],
					[
						'name'          => 'Performance',
						'price_value'   => '360',
						'currency'      => '$',
						'label'         => 'Full',
						'label_color'   => 'red',
						'features_list' => '128 gb
4 GB LPDDR3
5.2-inch
Helio X25 processor
21.16 megapixel',
					],
				],
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
		global $post;

		$default_settings = [
			'items' => '',
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		woodmart_enqueue_inline_style( 'pricing-table' );

		?>
		<div class="pricing-tables-wrapper">
			<div class="pricing-tables" >
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<?php
					$default_settings = [
						'name'          => '',
						'price_value'   => '',
						'price_suffix'  => 'per month',
						'currency'      => '',
						'features_list' => '',
						'label'         => '',
						'label_color'   => 'red',
						'link'          => '',
						'button_label'  => '',
						'button_type'   => 'custom',
						'id'            => '',
						'style'         => 'default',
						'best_option'   => '',
						'with_bg_image' => '',
						'bg_image'      => '',
					];

					$settings = wp_parse_args( $item, $default_settings );
					$bg_style = '';

					$repeater_wrapper_key  = $this->get_repeater_setting_key( 'wrapper', 'items', $index );
					$repeater_footer_key   = $this->get_repeater_setting_key( 'footer', 'items', $index );
					$repeater_name_key     = $this->get_repeater_setting_key( 'name', 'items', $index );
					$repeater_value_key    = $this->get_repeater_setting_key( 'price_value', 'items', $index );
					$repeater_suffix_key   = $this->get_repeater_setting_key( 'price_suffix', 'items', $index );
					$repeater_currency_key = $this->get_repeater_setting_key( 'currency', 'items', $index );
					$repeater_btn_key      = $this->get_repeater_setting_key( 'button_label', 'items', $index );

					$this->add_render_attribute(
						[
							$repeater_wrapper_key  => [
								'class' => [
									'wd-price-table',
									'price-style-' . $settings['style'],
								],
							],
							$repeater_footer_key   => [
								'class' => [
									'wd-plan-footer',
								],
							],
							$repeater_name_key     => [
								'class' => [
									'wd-plan-title title',
								],
							],
							$repeater_value_key    => [
								'class' => [
									'wd-price-value',
								],
							],
							$repeater_suffix_key   => [
								'class' => [
									'wd-price-suffix',
								],
							],
							$repeater_currency_key => [
								'class' => [
									'wd-price-currency',
								],
							],
							$repeater_btn_key      => [
								'class' => [
									'button',
									'price-plan-btn',
								],
							],
						]
					);

					$this->add_inline_editing_attributes( $repeater_name_key );
					$this->add_inline_editing_attributes( $repeater_value_key );
					$this->add_inline_editing_attributes( $repeater_suffix_key );
					$this->add_inline_editing_attributes( $repeater_currency_key );
					$this->add_inline_editing_attributes( $repeater_btn_key );

					if ( $settings['label'] ) {
						$this->add_render_attribute( $repeater_wrapper_key, 'class', 'price-with-label label-color-' . $settings['label_color'] );
					}

					if ( 'yes' === $settings['best_option'] ) {
						$this->add_render_attribute( $repeater_wrapper_key, 'class', 'price-highlighted' );
					}

					if ( 'yes' === $settings['with_bg_image'] && $settings['bg_image'] ) {
						$this->add_render_attribute( $repeater_wrapper_key, 'class', 'with-bg-image' );
						$bg_style = 'background-image:url(' . esc_url( woodmart_get_image_url( $settings['bg_image']['id'], 'bg_image', $settings ) ) . ')';
					}

					$features = explode( "\n", $settings['features_list'] );

					$product = false;

					if ( 'product' === $settings['button_type'] && $settings['product_id'] && function_exists( 'wc_setup_product_data' ) ) {
						$product_data = get_post( $settings['product_id'][0] );
						$product      = is_object( $product_data ) && in_array( $product_data->post_type, array( 'product', 'product_variation' ) ) ? wc_setup_product_data( $product_data ) : false;
					}

					?>
					<div <?php echo $this->get_render_attribute_string( $repeater_wrapper_key ); ?>>
						<div class="wd-plan">
							<div class="wd-plan-name">
								<span <?php echo $this->get_render_attribute_string( $repeater_name_key ); ?>>
									<?php echo wp_kses( $settings['name'], woodmart_get_allowed_html() ); ?>
								</span>
							</div>
						</div>

						<div class="wd-plan-inner">
							<?php if ( $settings['label'] ) : ?>
								<div class="price-label">
									<span>
										<?php echo wp_kses( $settings['label'], woodmart_get_allowed_html() ); ?>
									</span>
								</div>
							<?php endif ?>

							<div class="wd-plan-price" style="<?php echo esc_attr( $bg_style ); ?>">
								<?php if ( $settings['currency'] ) : ?>
									<span <?php echo $this->get_render_attribute_string( $repeater_currency_key ); ?>>
										<?php echo wp_kses( $settings['currency'], woodmart_get_allowed_html() ); ?>
									</span>
								<?php endif ?>
								
								<?php if ( $settings['price_value'] ) : ?>
									<span <?php echo $this->get_render_attribute_string( $repeater_value_key ); ?>>
										<?php echo wp_kses( $settings['price_value'], woodmart_get_allowed_html() ); ?>
									</span>
								<?php endif ?>
								
								<?php if ( $settings['price_suffix'] ) : ?>
									<span <?php echo $this->get_render_attribute_string( $repeater_suffix_key ); ?>>
										<?php echo wp_kses( $settings['price_suffix'], woodmart_get_allowed_html() ); ?>
									</span>
								<?php endif ?>
							</div>

							<?php if ( ! empty( $features[0] ) ) : ?>
								<div class="wd-plan-features">
									<?php foreach ( $features as $value ) : ?>
										<div class="wd-plan-feature">
											<?php echo wp_kses( $value, woodmart_get_allowed_html() ); ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif ?>

							<div <?php echo $this->get_render_attribute_string( $repeater_footer_key ); ?>>
								<?php if ( 'product' === $settings['button_type'] && $product ) : ?>
									<?php
									woodmart_enqueue_js_library( 'magnific' );
									woodmart_enqueue_js_script( 'action-after-add-to-cart' );
									woodmart_enqueue_inline_style( 'add-to-cart-popup' );
									woodmart_enqueue_inline_style( 'mfp-popup' );
									woocommerce_template_loop_add_to_cart();
									?>
								<?php else : ?>
									<?php if ( $settings['button_label'] ) : ?>
										<a <?php echo woodmart_get_link_attrs( $settings['link'] ); ?> <?php echo $this->get_render_attribute_string( $repeater_btn_key ); ?>>
											<?php echo wp_kses( $settings['button_label'], woodmart_get_allowed_html() ); ?>
										</a>
									<?php endif ?>
								<?php endif ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		
		<?php

		if ( 'product' === $settings['button_type'] && function_exists( 'wc_setup_product_data' ) ) {
			// Restore Product global in case this is shown inside a product post
			wc_setup_product_data( $post );
		}
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Pricing_Tables() );
