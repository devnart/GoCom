<?php
/**
 * Mega menu map.
 */

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
class Mega_Menu extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_mega_menu';
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
		return esc_html__( 'Mega Menu widget', 'woodmart' );
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
		return 'wd-icon-mega-menu';
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
			'title',
			[
				'label'   => esc_html__( 'Title', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Title text example',
			]
		);

		$this->add_control(
			'nav_menu',
			[
				'label'   => esc_html__( 'Choose Menu', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => woodmart_get_menus_array( 'elementor' ),
				'default' => '',
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
		
		$this->add_control(
			'color',
			[
				'label'     => esc_html__( 'Title background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .widget-title' => 'background-color: {{VALUE}}',
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
			'title'                 => '',
			'nav_menu'              => '',
			'style'                 => '',
			'color'                 => '',
			'woodmart_color_scheme' => 'light',
		];
		
		$settings     = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$this->add_render_attribute(
			[
				'title'     => [
					'class' => [
						'widget-title',
						'color-scheme-' . $settings['woodmart_color_scheme'],
					],
				],
			]
		);
		
		$this->add_inline_editing_attributes( 'title' );

		?>
		<div class="widget_nav_mega_menu">
			<?php if ( $settings['title'] ) : ?>
				<h5 <?php echo $this->get_render_attribute_string( 'title' ); ?>>
					<?php echo wp_kses( $settings['title'], woodmart_get_allowed_html() ); ?>
				</h5>
			<?php endif; ?>
			<?php
			wp_nav_menu(
				array(
					'container'  => '',
					'fallback_cb' => '',
					'menu'        => $settings['nav_menu'],
					'menu_class'  => 'menu wd-nav wd-nav-vertical' . woodmart_get_old_classes( ' vertical-navigation' ),
					'walker'      => new WOODMART_Mega_Menu_Walker(),
				)
			);
			?>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Mega_Menu() );
