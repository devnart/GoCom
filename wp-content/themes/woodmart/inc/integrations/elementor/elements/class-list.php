<?php
/**
 * List map.
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
class Icon_List extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_list';
	}

	/**
	 * Get widget title.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'List', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'wd-icon-list';
	}

	/**
	 * Get widget categories.
	 *
	 * @since  1.0.0
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
	 * @since  1.0.0
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

		$repeater->add_control(
			'list_content',
			[
				'label'   => esc_html__( 'Content', 'woodmart' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Far far away there live the blind live in Bookmarksgrove right.',
			]
		);

		$this->add_control(
			'list_items',
			[
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ list_content }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'list_content' => 'Far far away, behind the word mountains, far las.',
					],
					[
						'list_content' => 'Vokalia and Consonantia, there live the blind tex.
',
					],
					[
						'list_content' => 'Separated they live in Bookmarksgrove right attr.',
					],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Icon settings.
		 */
		$this->start_controls_section(
			'icon_content_section',
			[
				'label' => esc_html__( 'Icon', 'woodmart' ),
			]
		);

		$this->add_control(
			'list_type',
			[
				'label'   => esc_html__( 'Type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'icon'      => esc_html__( 'With icon', 'woodmart' ),
					'image'     => esc_html__( 'With image', 'woodmart' ),
					'ordered'   => esc_html__( 'Ordered', 'woodmart' ),
					'unordered' => esc_html__( 'Unordered', 'woodmart' ),
					'without'   => esc_html__( 'Without icon', 'woodmart' ),
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => esc_html__( 'Choose image', 'woodmart' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'list_type' => [ 'image' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'thumbnail',
				'separator' => 'none',
				'condition' => [
					'list_type' => [ 'image' ],
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label'     => esc_html__( 'Icon', 'woodmart' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					'list_type' => [ 'icon' ],
				],
			]
		);

		$this->end_controls_section();

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
				'label'   => esc_html__( 'Predefined size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default (14px)', 'woodmart' ),
					'medium'      => esc_html__( 'Medium (16px)', 'woodmart' ),
					'large'       => esc_html__( 'Large (18px)', 'woodmart' ),
					'extra-large' => esc_html__( 'Extra Large (22px)', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label'   => esc_html__( 'Color scheme', 'woodmart' ),
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

		/**
		 * Icon settings.
		 */
		$this->start_controls_section(
			'icon_style_section',
			[
				'label'     => esc_html__( 'Icon', 'woodmart' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'list_type!' => [ 'without', 'image' ],
				],
			]
		);

		$this->add_control(
			'list_style',
			[
				'label'     => esc_html__( 'Style', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'rounded' => esc_html__( 'Rounded', 'woodmart' ),
					'square'  => esc_html__( 'Square', 'woodmart' ),
				],
				'default'   => 'default',
				'condition' => [
					'list_type' => [ 'icon', 'ordered', 'unordered' ],
				],
			]
		);

		$this->add_control(
			'icons_color',
			[
				'label'     => esc_html__( 'Icons color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#2C2C2C',
				'selectors' => [
					'{{WRAPPER}} .list-icon' => 'color: {{VALUE}}',
				],
				'condition' => [
					'list_type' => [ 'icon', 'ordered', 'unordered' ],
				],
			]
		);

		$this->add_control(
			'icons_bg_color',
			[
				'label'     => esc_html__( 'Icons background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#EAEAEA',
				'selectors' => [
					'{{WRAPPER}} .list-icon' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'list_style' => [ 'rounded', 'square' ],
				],
			]
		);

		$this->add_control(
			'icon_size',
			[
				'label'     => esc_html__( 'Icon size', 'woodmart' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wd-list .list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'list_type!' => [ 'image' ],
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
	 * @since  1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$default_settings = [
			'icon'         => '',
			'image'        => '',

			'color_scheme' => '',
			'size'         => 'default',

			'list'         => '',
			'list_type'    => 'icon',
			'list_style'   => 'default',
			'list_items'   => '',
		];

		$settings    = wp_parse_args( $this->get_settings_for_display(), $default_settings );
		$icon_output = '';

		if ( ! $settings['list_items'] ) {
			return;
		}

		$this->add_render_attribute(
			[
				'list' => [
					'class' => [
						'wd-list',
						'color-scheme-' . $settings['color_scheme'],
						woodmart_get_new_size_classes( 'list', $settings['size'], 'text' ),
						'wd-list-type-' . $settings['list_type'],
						'wd-list-style-' . $settings['list_style'],
					],
				],
			]
		);

		if ( 'rounded' === $settings['list_style'] || 'square' === $settings['list_style'] ) {
			$this->add_render_attribute( 'list', 'class', 'wd-list-shape-icon' );
		}

		// Icon settings.
		$custom_image_size = isset( $settings['image_custom_dimension']['width'] ) && $settings['image_custom_dimension']['width'] ? $settings['image_custom_dimension'] : [
			'width'  => 128,
			'height' => 128,
		];

		if ( 'image' === $settings['list_type'] && isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
			$icon_output = woodmart_get_image_html( $settings, 'image' );

			if ( woodmart_is_svg( woodmart_get_image_url( $settings['image']['id'], 'image', $settings ) ) ) {
				$icon_output = '<span class="svg-icon img-wrapper" style="width:' . esc_attr( $custom_image_size['width'] ) . 'px; height:' . esc_attr( $custom_image_size['height'] ) . 'px;">' . woodmart_get_any_svg( woodmart_get_image_url( $settings['image']['id'], 'image', $settings ), rand( 999, 9999 ) ) . '</span>';
			}
		} elseif ( 'icon' === $settings['list_type'] && $settings['icon'] ) {
			$icon_output = woodmart_elementor_get_render_icon( $settings['icon'] );
		}

		woodmart_enqueue_inline_style( 'list' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'list' ); ?>>
			<ul>
				<?php foreach ( $settings['list_items'] as $index => $item ) : ?>
					<?php
					$repeater_label_key = $this->get_repeater_setting_key( 'list_content', 'list_items', $index );
					$this->add_render_attribute(
						[
							$repeater_label_key => [
								'class' => [
									'list-content',
								],
							],
						]
					);

					$this->add_inline_editing_attributes( $repeater_label_key );
					?>
					<li class="elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
						<?php if ( 'without' !== $settings['list_type'] ) : ?>
							<div class="list-icon">
								<?php echo $icon_output; ?>
							</div>
						<?php endif ?>

						<span <?php echo $this->get_render_attribute_string( $repeater_label_key ); ?>>
							<?php echo $item['list_content']; ?>
						</span>
					</li>
				<?php endforeach ?>
			</ul>
		</div>

		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Icon_List() );
