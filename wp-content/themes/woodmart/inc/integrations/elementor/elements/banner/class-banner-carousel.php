<?php
/**
 * Promo banner map.
 */

use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
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
class Banner_Carousel extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_banner_carousel';
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
		return esc_html__( 'Banners carousel', 'woodmart' );
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
		return 'wd-icon-banner-carousel';
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

		$repeater->start_controls_tabs( 'banner_tabs' );

		$repeater->start_controls_tab(
			'link_tab',
			[
				'label' => esc_html__( 'Link', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'woodmart' ),
				'description' => esc_html__( 'Enter URL if you want this banner to have a link.', 'woodmart' ),
				'type'        => Controls_Manager::URL,
				'default'     => [
					'url'         => '#',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'image_tab',
			[
				'label' => esc_html__( 'Image', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'image_type',
			[
				'label'   => esc_html__( 'Display', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'background' => esc_html__( 'As background', 'woodmart' ),
					'image'      => esc_html__( 'As image', 'woodmart' ),
				],
				'default' => 'image',
			]
		);

		$repeater->add_control(
			'image',
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
				'name'      => 'image',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$repeater->add_responsive_control(
			'image_height',
			[
				'label'     => esc_html__( 'Image height', 'woodmart' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 300,
				],
				'range'     => [
					'px' => [
						'min'  => 100,
						'max'  => 2000,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .banner-image' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'image_type' => [ 'background' ],
				],
			]
		);

		$repeater->add_control(
			'image_bg_position',
			[
				'label'     => esc_html__( 'Background position', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'center center' => esc_html__( 'Center', 'woodmart' ),
					'center top'    => esc_html__( 'Top', 'woodmart' ),
					'center bottom' => esc_html__( 'Bottom', 'woodmart' ),
					'left center'   => esc_html__( 'Left', 'woodmart' ),
					'right center'  => esc_html__( 'Right', 'woodmart' ),
				],
				'default'   => 'center center',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .banner-image' => 'background-position: {{VALUE}};',
				],
				'condition' => [
					'image_type' => [ 'background' ],
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'text_tab',
			[
				'label' => esc_html__( 'Text', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'woodmart' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Banner subtitle text',
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'   => esc_html__( 'Title', 'woodmart' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => 'Banner title, click to edit.',
			]
		);

		$repeater->add_control(
			'content',
			[
				'label' => esc_html__( 'Content', 'woodmart' ),
				'type'  => Controls_Manager::TEXTAREA,
			]
		);

		$repeater->add_control(
			'btn_text',
			[
				'label'   => esc_html__( 'Button text', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Read more',
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'layout_tab',
			[
				'label' => esc_html__( 'Layout', 'woodmart' ),
			]
		);

		$repeater->add_control(
			'horizontal_alignment',
			[
				'label'   => esc_html__( 'Content horizontal alignment', 'woodmart' ),
				'type'    => 'wd_buttons',
				'options' => [
					'left'   => [
						'title' => esc_html__( 'Left', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/left.png',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/center.png',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/content-align/horizontal/right.png',
					],
				],
				'default' => 'left',
			]
		);

		$repeater->add_control(
			'vertical_alignment',
			[
				'label'   => esc_html__( 'Content vertical alignment', 'woodmart' ),
				'type'    => 'wd_buttons',
				'options' => [
					'top'    => [
						'title' => esc_html__( 'Top', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/top.png',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/middle.png',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'woodmart' ),
						'image' => WOODMART_ASSETS_IMAGES . '/settings/content-align/vertical/bottom.png',
					],
				],
				'default' => 'top',
			]
		);

		$repeater->add_control(
			'text_alignment',
			[
				'label'   => esc_html__( 'Text alignment', 'woodmart' ),
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
				'default' => 'left',
			]
		);

		$repeater->add_responsive_control(
			'width',
			[
				'label'          => esc_html__( 'Width', 'woodmart' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units'     => [ '%' ],
				'range'          => [
					'%' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .promo-banner:not(.banner-content-background) .content-banner, {{WRAPPER}} {{CURRENT_ITEM}} .promo-banner.banner-content-background .wrapper-content-banner' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'increase_spaces',
			[
				'label'        => esc_html__( 'Increase spaces', 'woodmart' ),
				'description'  => esc_html__( 'Suggest to use this option if you have large banners. Padding will be set in percentage to your screen width.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$repeater->end_controls_tab();

		$repeater->end_controls_tabs();

		/**
		 * Repeater settings
		 */
		$this->add_control(
			'content_repeater',
			[
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ title }}}',
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title'    => 'Banner title, click to edit.',
						'subtitle' => 'Banner subtitle text',
					],
					[
						'title'    => 'Banner title, click to edit.',
						'subtitle' => 'Banner subtitle text',
					],
					[
						'title'    => 'Banner title, click to edit.',
						'subtitle' => 'Banner subtitle text',
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
			'style',
			[
				'label'       => esc_html__( 'Style', 'woodmart' ),
				'description' => esc_html__( 'You can use some of our predefined styles for your banner content.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'default'            => esc_html__( 'Default', 'woodmart' ),
					'mask'               => esc_html__( 'Color mask', 'woodmart' ),
					'shadow'             => esc_html__( 'Mask with shadow', 'woodmart' ),
					'border'             => esc_html__( 'Bordered', 'woodmart' ),
					'background'         => esc_html__( 'Bordered background', 'woodmart' ),
					'content-background' => esc_html__( 'Content background', 'woodmart' ),
				],
				'default'     => 'default',
			]
		);

		$this->add_control(
			'hover',
			[
				'label'       => esc_html__( 'Hover effect', 'woodmart' ),
				'description' => esc_html__( 'Set beautiful hover effects for your banner.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'zoom'         => esc_html__( 'Zoom image', 'woodmart' ),
					'parallax'     => esc_html__( 'Parallax', 'woodmart' ),
					'background'   => esc_html__( 'Background', 'woodmart' ),
					'border'       => esc_html__( 'Bordered', 'woodmart' ),
					'zoom-reverse' => esc_html__( 'Zoom reverse', 'woodmart' ),
					'none'         => esc_html__( 'Disable', 'woodmart' ),
				],
				'default'     => 'none',
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
			'title_size',
			[
				'label'   => esc_html__( 'Predefined size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default (22px)', 'woodmart' ),
					'small'       => esc_html__( 'Small (16px)', 'woodmart' ),
					'large'       => esc_html__( 'Large (26px)', 'woodmart' ),
					'extra-large' => esc_html__( 'Extra Large (36px)', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_content_bg_color',
			[
				'label'     => esc_html__( 'Content background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wrapper-content-banner' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'style' => 'content-background',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Carousel settings.
		 */
		$this->start_controls_section(
			'carousel_style_section',
			[
				'label' => esc_html__( 'Carousel', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
			'slider_spacing',
			[
				'label'   => esc_html__( 'Space between', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					0  => esc_html__( '0 px', 'woodmart' ),
					2  => esc_html__( '2 px', 'woodmart' ),
					6  => esc_html__( '6 px', 'woodmart' ),
					10 => esc_html__( '10 px', 'woodmart' ),
					20 => esc_html__( '20 px', 'woodmart' ),
					30 => esc_html__( '30 px', 'woodmart' ),
				],
				'default' => 30,
			]
		);

		$this->add_control(
			'scroll_per_page',
			[
				'label'        => esc_html__( 'Scroll per page', 'woodmart' ),
				'description'  => esc_html__( 'Scroll per page not per item. This affect next/prev buttons and mouse/touch dragging.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
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
				'condition'   => [
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
		 * Title settings.
		 */
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Tag', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => esc_html__( 'h1', 'woodmart' ),
					'h2'   => esc_html__( 'h2', 'woodmart' ),
					'h3'   => esc_html__( 'h3', 'woodmart' ),
					'h4'   => esc_html__( 'h4', 'woodmart' ),
					'h5'   => esc_html__( 'h5', 'woodmart' ),
					'h6'   => esc_html__( 'h6', 'woodmart' ),
					'p'    => esc_html__( 'p', 'woodmart' ),
					'div'  => esc_html__( 'div', 'woodmart' ),
					'span' => esc_html__( 'span', 'woodmart' ),
				],
				'default' => 'h4',
			]
		);

		$this->add_control(
			'title_decoration_style',
			[
				'label'   => esc_html__( 'Text decoration style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'  => esc_html__( 'Default', 'woodmart' ),
					'colored'  => esc_html__( 'Colored', 'woodmart' ),
					'bordered' => esc_html__( 'Bordered', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_title_color',
			[
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'label'    => esc_html__( 'Custom typography', 'woodmart' ),
				'selector' => '{{WRAPPER}} .banner-title',
			]
		);

		$this->end_controls_section();

		/**
		 * Subtitle settings.
		 */
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__( 'Subtitle', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'subtitle_style',
			[
				'label'   => esc_html__( 'Subtitle style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'    => esc_html__( 'Default', 'woodmart' ),
					'background' => esc_html__( 'Background', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'   => esc_html__( 'Predefined color', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'primary' => esc_html__( 'Primary', 'woodmart' ),
					'alt'     => esc_html__( 'Alternative', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_subtitle_color',
			[
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'custom_subtitle_bg_color',
			[
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-subtitle' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'subtitle_style' => [ 'background' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'label'    => esc_html__( 'Custom typography', 'woodmart' ),
				'selector' => '{{WRAPPER}} .banner-subtitle',
			]
		);

		$this->end_controls_section();

		/**
		 * Content settings.
		 */
		$this->start_controls_section(
			'content_style_section',
			[
				'label' => esc_html__( 'Content', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'content_text_size',
			[
				'label'   => esc_html__( 'Predefined size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default (14px)', 'woodmart' ),
					'medium'  => esc_html__( 'Medium (16px)', 'woodmart' ),
					'large'   => esc_html__( 'Large (18px)', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'custom_text_color',
			[
				'label'     => esc_html__( 'Color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .banner-inner' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typography',
				'label'    => esc_html__( 'Custom typography', 'woodmart' ),
				'selector' => '{{WRAPPER}} .banner-inner',
			]
		);

		$this->end_controls_section();

		/**
		 * Button settings.
		 */
		$this->start_controls_section(
			'button_style_section',
			[
				'label' => esc_html__( 'Button', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'btn_position',
			[
				'label'     => esc_html__( 'Button position', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'hover'  => esc_html__( 'Show on hover', 'woodmart' ),
					'static' => esc_html__( 'Static', 'woodmart' ),
				],
				'default'   => 'hover',
				'condition' => [
					'style!' => 'content-background',
				],
			]
		);

		$this->add_control(
			'btn_size',
			[
				'label'   => esc_html__( 'Predefined size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default', 'woodmart' ),
					'extra-small' => esc_html__( 'Extra Small', 'woodmart' ),
					'small'       => esc_html__( 'Small', 'woodmart' ),
					'large'       => esc_html__( 'Large', 'woodmart' ),
					'extra-large' => esc_html__( 'Extra Large', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label'   => esc_html__( 'Predefined color', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'woodmart' ),
					'primary' => esc_html__( 'Primary', 'woodmart' ),
					'alt'     => esc_html__( 'Alternative', 'woodmart' ),
					'black'   => esc_html__( 'Black', 'woodmart' ),
					'white'   => esc_html__( 'White', 'woodmart' ),
					'custom'  => esc_html__( 'Custom', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->start_controls_tabs(
			'button_tabs_style',
			[
				'condition' => [
					'color' => [ 'custom' ],
				],
			]
		);

		$this->start_controls_tab(
			'button_tab_normal',
			[
				'label' => esc_html__( 'Normal', 'woodmart' ),
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'     => esc_html__( 'Background color', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wd-button-wrapper a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label'   => esc_html__( 'Text color scheme', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
				],
				'default' => 'inherit',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'button_tab_hover',
			[
				'label' => esc_html__( 'Hover', 'woodmart' ),
			]
		);

		$this->add_control(
			'bg_color_hover',
			[
				'label'     => esc_html__( 'Background color hover', 'woodmart' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wd-button-wrapper:hover a' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color_scheme_hover',
			[
				'label'   => esc_html__( 'Text color scheme on hover', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'inherit' => esc_html__( 'Inherit', 'woodmart' ),
					'dark'    => esc_html__( 'Dark', 'woodmart' ),
					'light'   => esc_html__( 'Light', 'woodmart' ),
				],
				'default' => 'inherit',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_style',
			[
				'label'   => esc_html__( 'Style', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'default'  => esc_html__( 'Default', 'woodmart' ),
					'bordered' => esc_html__( 'Bordered', 'woodmart' ),
					'link'     => esc_html__( 'Link button', 'woodmart' ),
					'3d'       => esc_html__( '3D', 'woodmart' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'btn_shape',
			[
				'label'     => esc_html__( 'Shape', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'rectangle'  => esc_html__( 'Rectangle', 'woodmart' ),
					'round'      => esc_html__( 'Circle', 'woodmart' ),
					'semi-round' => esc_html__( 'Round', 'woodmart' ),
				],
				'condition' => [
					'btn_style!' => [ 'link' ],
				],
				'default'   => 'rectangle',
			]
		);

		$this->add_control(
			'button_icon_heading',
			[
				'label'     => esc_html__( 'Icon', 'woodmart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		woodmart_get_button_style_icon_map( $this );

		$this->add_control(
			'button_layout_heading',
			[
				'label'     => esc_html__( 'Layout', 'woodmart' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'full_width',
			[
				'label'        => esc_html__( 'Full width', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'button_hr',
			[
				'type'  => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->add_control(
			'hide_btn_tablet',
			[
				'label'        => esc_html__( 'Hide button on tablet', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'hide_btn_mobile',
			[
				'label'        => esc_html__( 'Hide button on mobile', 'woodmart' ),
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
		woodmart_elementor_banner_carousel_template( $this->get_settings_for_display() );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Banner_Carousel() );
