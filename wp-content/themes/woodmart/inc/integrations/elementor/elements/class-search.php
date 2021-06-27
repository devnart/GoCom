<?php
/**
 * Search map.
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
class Search extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'wd_search';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'AJAX Search', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'wd-icon-search';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 * @access public
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
				'label'   => esc_html__( 'Number results to show', 'woodmart' ),
				'default' => 12,
				'type'    => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'search_post_type',
			[
				'label'   => esc_html__( 'Search post type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'product'   => esc_html__( 'Product', 'woodmart' ),
					'post'      => esc_html__( 'Post', 'woodmart' ),
					'portfolio' => esc_html__( 'Portfolio', 'woodmart' ),
				],
				'default' => 'product',
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

		$this->add_control(
			'price',
			[
				'label'        => esc_html__( 'Show price', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'thumbnail',
			[
				'label'        => esc_html__( 'Show thumbnail', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'category',
			[
				'label'        => esc_html__( 'Show category', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '1',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
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
			'number'                => 3,
			'price'                 => 1,
			'thumbnail'             => 1,
			'category'              => 1,
			'search_post_type'      => 'product',
			'woodmart_color_scheme' => 'dark',
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'wd-el-search',
						'woodmart-ajax-search',
						woodmart_get_old_classes( 'woodmart-vc-ajax-search' ),
						'wd-color-' . $settings['woodmart_color_scheme'],
					],
				],
			]
		);

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			woodmart_search_form(
				array(
					'ajax'            => true,
					'post_type'       => $settings['search_post_type'],
					'count'           => $settings['number'],
					'thumbnail'       => $settings['thumbnail'],
					'price'           => $settings['price'],
					'show_categories' => $settings['category'],
				)
			);
			?>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Search() );
