<?php
/**
 * Timeline map.
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
class Timeline extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_timeline';
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
		return esc_html__( 'Timeline', 'woodmart' );
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
		return 'wd-icon-timeline';
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

		$repeater->add_control(
			'item_type',
			[
				'label'   => esc_html__( 'Item type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'item'       => esc_html__( 'Timeline items', 'woodmart' ),
					'breakpoint' => esc_html__( 'Breakpoint', 'woodmart' ),
				],
				'default' => 'item',
			]
		);

		/**
		 * Breakpoint settings.
		 */
		$repeater->add_control(
			'title',
			[
				'label'     => esc_html__( 'Title', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'Title example',
				'condition' => [
					'item_type' => 'breakpoint',
				],
			]
		);

		$repeater->add_control(
			'color_bg',
			[
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333333',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .woodmart-timeline-breakpoint-title' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'item_type' => 'breakpoint',
				],
			]
		);

		/**
		 * Item settings.
		 */
		$repeater->add_control(
			'position',
			[
				'label'     => esc_html__( 'Position', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'left'       => esc_html__( 'Left', 'woodmart' ),
					'right'      => esc_html__( 'Right', 'woodmart' ),
					'full-width' => esc_html__( 'Full Width', 'woodmart' ),
				],
				'default'   => 'left',
				'condition' => [
					'item_type' => 'item',
				],
			]
		);

		$repeater->add_control(
			'color_bg_item',
			[
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}, {{WRAPPER}} {{CURRENT_ITEM}} .timeline-col-primary, {{WRAPPER}} {{CURRENT_ITEM}} .timeline-col-secondary' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} {{CURRENT_ITEM}} .timeline-arrow' => 'color: {{VALUE}}',
				],
				'condition' => [
					'item_type' => 'item',
				],
			]
		);

		$repeater->start_controls_tabs(
			'tabs',
			[
				'condition' => [
					'item_type' => 'item',
				],
			]
		);

		$repeater->start_controls_tab(
			'primary_tab',
			[
				'label' => esc_html__( 'Primary', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'image_primary',
			[
				'label'   => esc_html__( 'Choose image', 'woodmart' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_primary',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'title_primary',
			[
				'label'   => esc_html__( 'Title', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Title example. Click to edit.',
			]
		);

		$repeater->add_control(
			'content',
			[
				'label'   => esc_html__( 'Text', 'woodmart' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => 'Inceptos diam proin justo in nibh enim quam.',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'secondary_tab',
			[
				'label' => esc_html__( 'Secondary', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'image_secondary',
			[
				'label'   => esc_html__( 'Choose image', 'woodmart' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_secondary',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$repeater->add_control(
			'title_secondary',
			[
				'label'   => esc_html__( 'Title', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Title example. Click to edit.',
			]
		);

		$repeater->add_control(
			'content_secondary',
			[
				'label'   => esc_html__( 'Text', 'woodmart' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => 'Inceptos diam proin justo in nibh enim quam.',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		$this->add_control(
			'items',
			[
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ item_type }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'item_type' => 'item',
					],
					[
						'item_type' => 'breakpoint',
					],
					[
						'item_type' => 'item',
					],
					[
						'item_type' => 'breakpoint',
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
			'line_style',
			[
				'label'   => esc_html__( 'Line style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'dashed'  => esc_html__( 'Dashed', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'item_style',
			[
				'label'   => esc_html__( 'Item style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'shadow'  => esc_html__( 'With shadow', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'line_color',
			[
				'label'     => esc_html__( 'Color of line', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#e1e1e1',
				'selectors' => [
					'{{WRAPPER}} .dot-start, {{WRAPPER}} .dot-end' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .woodmart-timeline-line' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label'     => esc_html__( 'Color of dots', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#1e73be',
				'selectors' => [
					'{{WRAPPER}} .woodmart-timeline-dot' => 'background-color: {{VALUE}}',
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
			'line_style' => 'default',
			'item_style' => 'default',
			'items'      => [],
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'wd-timeline-wrapper',
						'wd-item-' . $settings['item_style'],
						'wd-line-' . $settings['line_style'],
					],
				],
			]
		);

		woodmart_enqueue_inline_style( 'timeline' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<div class="woodmart-timeline-line">
				<span class="line-dot dot-start"></span>
				<span class="line-dot dot-end"></span>
			</div>

			<div class="wd-timeline">
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<?php
					$default_settings = [
						'title'             => '',
						'title_primary'     => '',
						'image_primary'     => '',
						'title_secondary'   => '',
						'content_secondary' => '',
						'image_secondary'   => '',
						'position'          => 'left',
					];

					$settings = wp_parse_args( $item, $default_settings );

					$repeater_item_key       = $this->get_repeater_setting_key( 'item', 'items', $index );
					$repeater_breakpoint_key = $this->get_repeater_setting_key( 'breakpoint', 'items', $index );
					$repeater_title_key      = $this->get_repeater_setting_key( 'title_primary', 'items', $index );
					$repeater_text_key       = $this->get_repeater_setting_key( 'content', 'items', $index );
					$this->add_render_attribute(
						[
							$repeater_item_key       => [
								'class' => [
									'wd-timeline-item',
									'wd-item-position-' . $settings['position'],
									'elementor-repeater-item-' . $item['_id'],
								],
							],
							$repeater_breakpoint_key => [
								'class' => [
									'wd-timeline-breakpoint',
									'elementor-repeater-item-' . $item['_id'],
								],
							],
							$repeater_title_key      => [
								'class' => [
									'wd-timeline-title',
								],
							],
							$repeater_text_key       => [
								'class' => [
									'wd-timeline-content',
									'set-cont-mb-s reset-last-child',
								],
							],
						]
					);

					// Image settings.
					$image_primary   = '';
					$image_secondary = '';

					if ( isset( $settings['image_primary']['id'] ) && $settings['image_primary']['id'] ) {
						$image_primary = woodmart_get_image_html( $settings, 'image_primary' );
					} elseif ( isset( $settings['image_primary']['url'] ) && $settings['image_primary']['url'] ) {
						$image_primary = '<img src="' . esc_url( $settings['image_primary']['url'] ) . '">';
					}

					if ( isset( $settings['image_secondary']['id'] ) && $settings['image_secondary']['id'] ) {
						$image_secondary = woodmart_get_image_html( $settings, 'image_secondary' );
					} elseif ( isset( $settings['image_secondary']['url'] ) && $settings['image_secondary']['url'] ) {
						$image_secondary = '<img src="' . esc_url( $settings['image_secondary']['url'] ) . '">';
					}

					?>
					<?php if ( 'breakpoint' === $settings['item_type'] ) : ?>
						<div <?php echo $this->get_render_attribute_string( $repeater_breakpoint_key ); ?>>
							<span class="woodmart-timeline-breakpoint-title">
								<?php echo esc_attr( $settings['title'] ); ?>
							</span>
						</div>
					<?php else : ?>
						<div <?php echo $this->get_render_attribute_string( $repeater_item_key ); ?>>
							<div class="woodmart-timeline-dot"></div>

							<div class="timeline-col timeline-col-primary">
								<span class="timeline-arrow"></span>
								<?php if ( $image_primary ) : ?>
									<div class="wd-timeline-image">
										<?php echo $image_primary; ?>
									</div>
								<?php endif ?>

								<?php if ( $settings['title_primary'] ) : ?>
									<h4 class="wd-timeline-title">
										<?php echo esc_attr( $settings['title_primary'] ); ?>
									</h4>
								<?php endif; ?>

								<div class="wd-timeline-content set-cont-mb-s reset-last-child">
									<?php echo do_shortcode( $settings['content'] ); ?>
								</div>
							</div>

							<div class="timeline-col timeline-col-secondary">
								<span class="timeline-arrow"></span>
								<?php if ( $image_secondary ) : ?>
									<div class="wd-timeline-image">
										<?php echo $image_secondary; ?>
									</div>
								<?php endif ?>

								<?php if ( $settings['title_secondary'] ) : ?>
									<h4 class="wd-timeline-title">
										<?php echo esc_attr( $settings['title_secondary'] ); ?>
									</h4>
								<?php endif; ?>

								<div class="wd-timeline-content set-cont-mb-s reset-last-child">
									<?php echo do_shortcode( $settings['content_secondary'] ); ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Timeline() );
