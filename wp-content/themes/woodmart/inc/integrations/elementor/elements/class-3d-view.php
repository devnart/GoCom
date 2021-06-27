<?php
/**
 * 360 degree view map.
 */

use Elementor\Group_Control_Image_Size;
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
class View_3d extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_name() {
		return 'wd_3d_view';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_title() {
		return esc_html__( '360 degree view', 'woodmart' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 */
	public function get_icon() {
		return 'wd-icon-3d-view';
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
			'images',
			[
				'label'   => esc_html__( 'Images', 'woodmart' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => [],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'images', // Need images id.
				'default'   => 'large',
				'separator' => 'none',
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
			'images' => '',
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		woodmart_enqueue_js_library( 'threesixty' );
		woodmart_enqueue_js_script( 'view3d-element' );
		woodmart_enqueue_inline_style( '360degree' );

		if ( count( $settings['images'] ) < 2 ) {
			return;
		}

		$image_data = wp_get_attachment_image_src( $settings['images'][0]['id'], $settings['images_size'] );

		$args = [
			'frames_count' => count( $settings['images'] ),
			'images'       => [],
			'width'        => $image_data[1],
			'height'       => $image_data[2],
		];

		foreach ( $settings['images'] as $key => $image ) {
			$args['images'][] = woodmart_get_image_url( $image['id'], 'images', $settings );
		}

		?>
		<div class="wd-threed-view" data-args='<?php echo wp_json_encode( $args ); ?>'>
			<ul class="threed-view-images"></ul>
			<div class="spinner">
				<span>0%</span>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new View_3d() );
