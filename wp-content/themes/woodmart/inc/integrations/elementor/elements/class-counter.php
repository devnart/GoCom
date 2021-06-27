<?php
/**
 * Counter map.
 */

use Elementor\Group_Control_Typography;
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
class Counter extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_counter';
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
		return esc_html__( 'Animated Counter', 'woodmart' );
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
		return 'wd-icon-counter';
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
			'label',
			[
				'label'   => esc_html__( 'Label', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Projects',
			]
		);

		$this->add_control(
			'value',
			[
				'label'       => esc_html__( 'Actual value', 'woodmart' ),
				'description' => esc_html__( 'Our final point. For ex.: 95', 'woodmart' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '45',
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
			'size',
			[
				'label'   => esc_html__( 'Size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''            => esc_html__( 'Default', 'woodmart' ),
					'small'       => esc_html__( 'Small', 'woodmart' ),
					'large'       => esc_html__( 'Large', 'woodmart' ),
					'extra-large' => esc_html__( 'Extra large', 'woodmart' ),
				],
				'default' => '',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'value_typography',
				'label'    => esc_html__( 'Value typography', 'woodmart' ),
				'selector' => '{{WRAPPER}} .counter-value',
			]
		);

		$this->add_control(
			'align',
			[
				'label'   => esc_html__( 'Align', 'woodmart' ),
				'type'    => 'wd_buttons',
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/left.jpg',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/center.jpg',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/align/right.jpg',
					],
				],
				'default' => 'center',
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label'   => esc_html__( 'Color scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					''       => esc_html__( 'Inherit', 'woodmart' ),
					'light'  => esc_html__( 'Light', 'woodmart' ),
					'dark'   => esc_html__( 'Dark', 'woodmart' ),
					'custom' => esc_html__( 'Custom', 'woodmart' ),
				],
				'default' => '',
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Custom color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woodmart-counter' => 'color: {{VALUE}}',
				],
				'condition' => [
					'color_scheme' => 'custom',
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
		$default_settings = [
			'label'        => '',
			'value'        => 100,

			'color_scheme' => '',
			'size'         => 'default',
			'align'        => 'center',
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'woodmart-counter',
						'counter-' . $settings['size'],
						'text-' . $settings['align'],
						'color-scheme-' . $settings['color_scheme'],
					],
				],
				'counter' => [
					'class'      => [
						'counter-value',
					],
					'data-state' => [
						'new',
					],
					'data-final' => [
						$settings['value'],
					],
				],
				'label'   => [
					'class' => [
						'counter-label',
					],
				],
			]
		);

		$this->add_inline_editing_attributes( 'label' );

		woodmart_enqueue_js_library( 'waypoints' );
		woodmart_enqueue_js_script( 'counter-element' );
		woodmart_enqueue_inline_style( 'counter' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<span <?php echo $this->get_render_attribute_string( 'counter' ); ?>>
				<?php echo esc_attr( $settings['value'] ); ?>
			</span>

			<?php if ( $settings['label'] ) : ?>
				<span <?php echo $this->get_render_attribute_string( 'label' ); ?>>
					<?php echo esc_html( $settings['label'] ); ?>
				</span>
			<?php endif ?>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Counter() );
