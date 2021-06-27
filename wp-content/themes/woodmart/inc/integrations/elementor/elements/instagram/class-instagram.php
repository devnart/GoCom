<?php
/**
 * Instagram map.
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
class Instagram extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_instagram';
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
		return esc_html__( 'Instagram', 'woodmart' );
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
		return 'wd-icon-instagram';
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

		$this->add_control(
			'data_source',
			[
				'label'       => esc_html__( 'Source type', 'woodmart' ),
				'description' => 'API request type<br>
Scrape - parse Instagram page and take photos by username. Now deprecated and may be blocked by Instagram.<br>
AJAX - send AJAX request to the Instagram page on frontend. Works more stable then simple scrape.<br>
API - the best safe and legal option to obtain Instagram photos. Requires Instagram APP configuration. <br>
Follow our documentation <a href="https://xtemos.com/docs/woodmart/faq-guides/setup-instagram-api/" target="_blank">here</a>',
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'scrape' => esc_html__( 'Scrape (deprecated)', 'woodmart' ),
					'ajax'   => esc_html__( 'AJAX (deprecated)', 'woodmart' ),
					'api'    => esc_html__( 'API', 'woodmart' ),
					'images' => esc_html__( 'Images', 'woodmart' ),
				],
				'default'     => 'scrape',
			]
		);

		$this->add_control(
			'username',
			[
				'label'   => esc_html__( 'Username', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'flickr',
			]
		);

		$this->add_control(
			'size',
			[
				'label'     => esc_html__( 'Photo size', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'medium'    => esc_html__( 'Medium', 'woodmart' ),
					'thumbnail' => esc_html__( 'Thumbnail', 'woodmart' ),
					'large'     => esc_html__( 'Large', 'woodmart' ),
				],
				'default'   => 'medium',
				'condition' => [
					'data_source!' => [ 'images' ],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Images settings.
		 */
		$this->start_controls_section(
			'images_section',
			[
				'label'     => esc_html__( 'Images', 'woodmart' ),
				'condition' => [
					'data_source' => [ 'images' ],
				],
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

		$this->add_control(
			'images_size',
			[
				'label'       => esc_html__( 'Images size', 'woodmart' ),
				'description' => esc_html__( 'Enter image size. Example: \'thumbnail\', \'medium\', \'large\', \'full\'. Leave empty to use \'thumbnail\' size.', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'thumbnail',
			]
		);

		$this->add_control(
			'images_link',
			[
				'label' => esc_html__( 'Images link', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'images_likes',
			[
				'label'       => esc_html__( 'Likes limit', 'woodmart' ),
				'description' => esc_html__( 'Example: 1000-10000', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '1000-10000',
			]
		);

		$this->add_control(
			'images_comments',
			[
				'label'       => esc_html__( 'Comments limit', 'woodmart' ),
				'description' => esc_html__( 'Example: 0-1000', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '0-1000',
			]
		);

		$this->end_controls_section();

		/**
		 * Content settings.
		 */
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'woodmart' ),
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__( 'Instagram text', 'woodmart' ),
				'type'  => Controls_Manager::WYSIWYG,
			]
		);

		$this->add_control(
			'target',
			[
				'label'   => esc_html__( 'Open link in', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'_self'  => esc_html__( 'Current window (_self)', 'woodmart' ),
					'_blank' => esc_html__( 'New window (_blank)', 'woodmart' ),
				],
				'default' => '_self',
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link text', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->end_controls_section();

		/**
		 * Style tab.
		 */

		/**
		 * Layout settings.
		 */
		$this->start_controls_section(
			'layout_content_section',
			[
				'label' => esc_html__( 'Layout', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'design',
			[
				'label'   => esc_html__( 'Layout', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'grid'   => esc_html__( 'Grid', 'woodmart' ),
					'slider' => esc_html__( 'Slider', 'woodmart' ),
				],
				'default' => 'grid',
			]
		);

		$this->add_control(
			'per_row',
			[
				'label'      => esc_html__( 'Photos per row', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 3,
				],
				'size_units' => '',
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 12,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'number',
			[
				'label'      => esc_html__( 'Number of photos', 'woodmart' ),
				'type'       => Controls_Manager::SLIDER,
				'default'    => [
					'size' => 9,
				],
				'size_units' => '',
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 12,
						'step' => 1,
					],
				],
			]
		);

		$this->add_control(
			'spacing',
			[
				'label'        => esc_html__( 'Add spaces between photos', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'spacing_custom',
			[
				'label'     => esc_html__( 'Space between', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					0  => esc_html__( '0 px', 'woodmart' ),
					2  => esc_html__( '2 px', 'woodmart' ),
					6  => esc_html__( '6 px', 'woodmart' ),
					10 => esc_html__( '10 px', 'woodmart' ),
					20 => esc_html__( '20 px', 'woodmart' ),
					30 => esc_html__( '30 px', 'woodmart' ),
				],
				'default'   => 6,
				'condition' => [
					'spacing' => '1',
				],
			]
		);

		$this->add_control(
			'rounded',
			[
				'label'        => esc_html__( 'Rounded corners for images', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'hide_mask',
			[
				'label'        => esc_html__( 'Hide likes and comments', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'hide_pagination_control',
			[
				'label'        => esc_html__( 'Hide pagination control', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
				'condition'    => [
					'design' => [ 'slider' ],
				],
			]
		);

		$this->add_control(
			'hide_prev_next_buttons',
			[
				'label'        => esc_html__( 'Hide prev/next buttons', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
				'condition'    => [
					'design' => [ 'slider' ],
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
		woodmart_elementor_instagram_template( $this->get_settings_for_display() );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Instagram() );
