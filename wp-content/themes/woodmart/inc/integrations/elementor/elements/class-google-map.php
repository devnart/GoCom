<?php
/**
 * Google map map.
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
class Google_Map extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_google_map';
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
		return esc_html__( 'Google Map', 'woodmart' );
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
		return 'wd-icon-google-map';
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
			'lat',
			[
				'label'       => esc_html__( 'Latitude (required)', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'description' => 'You can use <a href="http://universimmedia.pagesperso-orange.fr/geo/loc.htm" target="_blank">this service</a> to get coordinates of your location.',
				'default'     => '51.50735',
			]
		);

		$this->add_control(
			'lon',
			[
				'label'   => esc_html__( 'Longitude (required)', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '-0.12776',
			]
		);

		$this->add_control(
			'google_key',
			[
				'label'       => esc_html__( 'Google API key (required)', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'description' => 'Obtain API key <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a> to use our Google Map VC element.',
			]
		);

		$this->end_controls_section();

		/**
		 * Marker settings.
		 */
		$this->start_controls_section(
			'marker_content_section',
			[
				'label' => esc_html__( 'Marker', 'woodmart' ),
			]
		);

		$this->add_control(
			'marker_icon',
			[
				'label' => esc_html__( 'Choose image', 'woodmart' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'marker_icon',
				'default'   => 'thumbnail',
				'separator' => 'none',
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
			]
		);

		$this->add_control(
			'marker_text',
			[
				'label' => esc_html__( 'Text', 'woodmart' ),
				'type'  => Controls_Manager::TEXT,
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
			'content_type',
			[
				'label'   => esc_html__( 'Content type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'text'       => esc_html__( 'Text', 'woodmart' ),
					'html_block' => esc_html__( 'HTML Block', 'woodmart' ),
				],
				'default' => 'text',
			]
		);

		$this->add_control(
			'content',
			[
				'label'     => esc_html__( 'Content', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => woodmart_get_elementor_html_blocks_array(),
				'default'   => '0',
				'condition' => [
					'content_type' => 'html_block',
				],
			]
		);

		$this->add_control(
			'text',
			[
				'label'     => esc_html__( 'Text', 'woodmart' ),
				'type'      => Controls_Manager::WYSIWYG,
				'condition' => [
					'content_type' => 'text',
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
			'mask',
			[
				'label'       => esc_html__( 'Map mask', 'woodmart' ),
				'description' => esc_html__( 'Add an overlay to your map to make the content look cleaner on the map.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					''      => esc_html__( 'Without', 'woodmart' ),
					'dark'  => esc_html__( 'Dark', 'woodmart' ),
					'light' => esc_html__( 'Light', 'woodmart' ),
				],
				'default'     => '',
			]
		);

		$this->add_control(
			'zoom',
			[
				'label'       => esc_html__( 'Zoom', 'woodmart' ),
				'description' => esc_html__( 'Zoom level when focus the marker 0 - 19', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 15,
				],
				'size_units'  => '',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 19,
						'step' => 1,
					],
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label'          => esc_html__( 'Map height', 'woodmart' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => '400',
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min'  => 100,
						'max'  => 2000,
						'step' => 10,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .google-map-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'scroll',
			[
				'label'        => esc_html__( 'Zoom with mouse wheel', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'style_json',
			[
				'label'       => esc_html__( 'Styles (JSON)', 'woodmart' ),
				'type'        => 'wd_google_json',
				'description' => 'Styled maps allow you to customize the presentation of the standard Google base maps, changing the visual display of such elements as roads, parks, and built-up areas.<br> You can find more Google Maps styles on the website: <a target="_blank" href="https://snazzymaps.com/">Snazzy Maps</a><br> Just copy JSON code and paste it here.',
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
			'content_horizontal',
			[
				'label'   => esc_html__( 'Horizontal position', 'woodmart' ),
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

		$this->add_control(
			'content_vertical',
			[
				'label'   => esc_html__( 'Vertical position', 'woodmart' ),
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

		$this->add_responsive_control(
			'width',
			[
				'label'          => esc_html__( 'Width', 'woodmart' ),
				'type'           => Controls_Manager::SLIDER,
				'default'        => [
					'size' => 300,
					'unit' => 'px',
				],
				'tablet_default' => [
					'unit' => 'px',
				],
				'mobile_default' => [
					'unit' => 'px',
				],
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min'  => 100,
						'max'  => 2000,
						'step' => 10,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .wd-google-map-content' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Lazy loading settings.
		 */
		$this->start_controls_section(
			'lazy_loading_style_section',
			[
				'label' => esc_html__( 'Lazy loading', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'init_type',
			[
				'label'       => esc_html__( 'Init event', 'woodmart' ),
				'description' => esc_html__( 'For a better performance you can initialize the Google map only when you scroll down the page or when you click on it.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'page_load'   => esc_html__( 'On page load', 'woodmart' ),
					'scroll'      => esc_html__( 'On scroll', 'woodmart' ),
					'button'      => esc_html__( 'On button click', 'woodmart' ),
					'interaction' => esc_html__( 'On user interaction', 'woodmart' ),
				],
				'default'     => 'page_load',
			]
		);

		$this->add_control(
			'init_offset',
			[
				'label'       => esc_html__( 'Scroll offset', 'woodmart' ),
				'description' => esc_html__( 'Zoom level when focus the marker 0 - 19', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 100,
				],
				'size_units'  => '',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 10,
					],
				],
				'condition'   => [
					'init_type' => 'scroll',
				],
			]
		);

		$this->add_control(
			'map_init_placeholder',
			[
				'label'     => esc_html__( 'Choose image', 'woodmart' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => [
					'init_type' => [ 'scroll', 'button', 'interaction' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'map_init_placeholder',
				'default'   => 'thumbnail',
				'separator' => 'none',
				'condition' => [
					'init_type' => [ 'scroll', 'button', 'interaction' ],
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
			'title'                     => '',
			'lat'                       => 45.9,
			'lon'                       => 10.9,
			'style_json'                => '',
			'zoom'                      => 15,
			'height'                    => 400,
			'scroll'                    => 'no',
			'mask'                      => '',
			'marker_text'               => '',
			'marker_content'            => '',
			'content_vertical'          => 'top',
			'content_horizontal'        => 'left',
			'content_width'             => 300,
			'google_key'                => woodmart_get_opt( 'google_map_api_key' ),
			'marker_icon'               => '',

			'init_type'                 => 'page_load',
			'init_offset'               => '100',
			'map_init_placeholder'      => '',
			'map_init_placeholder_size' => '',
		];

		$settings = wp_parse_args( $this->get_settings_for_display(), $default_settings );
		$uniqid   = uniqid();
		$minified = woodmart_get_opt( 'minified_js' ) ? '.min' : '';
		$version  = woodmart_get_theme_info( 'Version' );

		wp_enqueue_script( 'wd-google-map-api', 'https://maps.google.com/maps/api/js?libraries=geometry&v=3.44&key=' . $settings['google_key'], array(), $version, true );
		wp_enqueue_script( 'wd-maplace', WOODMART_THEME_DIR . '/js/libs/maplace' . $minified . '.js', array( 'wd-google-map-api' ), $version, true );

		woodmart_enqueue_js_script( 'google-map-element' );

		$this->add_render_attribute(
			[
				'content_wrapper' => [
					'class' => [
						'container',
						'wd-google-map-content-wrap',
						'wd-items-' . $settings['content_vertical'],
						'wd-justify-' . $settings['content_horizontal'],
					],
				],
			]
		);

		$this->add_render_attribute( 'wrapper', 'class', 'google-map-container' );

		if ( $settings['mask'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'map-mask-' . $settings['mask'] );
		}
		if ( 'page_load' !== $settings['init_type'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'map-lazy-loading' );
		}
		if ( $settings['content'] || $settings['text'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'map-container-with-content' );
		}

		// Image settings.
		if ( isset( $settings['marker_icon']['id'] ) && $settings['marker_icon']['id'] ) {
			$marker_icon = woodmart_get_image_url( $settings['marker_icon']['id'], 'marker_icon', $settings );
		} else {
			$marker_icon = WOODMART_ASSETS_IMAGES . '/google-icon.png';
		}

		$map_args = array(
			'latitude'           => $settings['lat'],
			'longitude'          => $settings['lon'],
			'zoom'               => $settings['zoom']['size'],
			'mouse_zoom'         => $settings['scroll'],
			'init_type'          => $settings['init_type'],
			'init_offset'        => isset( $settings['init_offset']['size'] ) ? $settings['init_offset']['size'] : '',
			'json_style'         => $settings['style_json'],
			'marker_icon'        => $marker_icon,
			'elementor'          => true,
			'marker_text_needed' => $settings['marker_text'] || $settings['title'] ? 'yes' : 'no',
			'marker_text'        => '<h3 style="min-width:300px; text-align:center; margin:15px;">' . $settings['title'] . '</h3>' . esc_html( $settings['marker_text'] ),
			'selector'           => 'wd-map-id-' . $uniqid,
		);

		// Placeholder settings.
		if ( isset( $settings['map_init_placeholder']['id'] ) && $settings['map_init_placeholder']['id'] ) {
			$placeholder = $image_output = woodmart_get_image_html( $settings, 'map_init_placeholder' );
		} else {
			$placeholder = '<img src="' . WOODMART_ASSETS_IMAGES . '/google-map-placeholder.jpg">';
		}

		woodmart_enqueue_inline_style( 'map' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> data-map-args='<?php echo wp_json_encode( $map_args ); ?>'>
			
			<?php if ( 'page_load' !== $settings['init_type'] && $placeholder ) : ?>
				<div class="wd-map-placeholder wd-fill">
					<?php echo $placeholder; ?>
				</div>
			<?php endif ?>
			
			<?php if ( 'button' === $settings['init_type'] ) : ?>
				<div class="wd-init-map-wrap wd-fill">
					<a href="#" rel="noffollow" class="btn btn-color-white wd-init-map">
						<svg xmlns="http://www.w3.org/2000/svg" width="250.378" height="254.167" viewBox="0 0 66.246 67.248"><g transform="translate(-172.531 -218.455) scale(.98012)"><rect ry="5.238" rx="5.238" y="231.399" x="176.031" height="60.099" width="60.099" fill="#34a668" paint-order="markers stroke fill"/><path d="M206.477 260.9l-28.987 28.987a5.218 5.218 0 0 0 3.78 1.61h49.621c1.694 0 3.19-.798 4.146-2.037z" fill="#5c88c5"/><path d="M226.742 222.988c-9.266 0-16.777 7.17-16.777 16.014.007 2.762.663 5.474 2.093 7.875.43.703.83 1.408 1.19 2.107.333.502.65 1.005.95 1.508.343.477.673.957.988 1.44 1.31 1.769 2.5 3.502 3.637 5.168.793 1.275 1.683 2.64 2.466 3.99 2.363 4.094 4.007 8.092 4.6 13.914v.012c.182.412.516.666.879.667.403-.001.768-.314.93-.799.603-5.756 2.238-9.729 4.585-13.794.782-1.35 1.673-2.715 2.465-3.99 1.137-1.666 2.328-3.4 3.638-5.169.315-.482.645-.962.988-1.439.3-.503.617-1.006.95-1.508.359-.7.76-1.404 1.19-2.107 1.426-2.402 2-5.114 2.004-7.875 0-8.844-7.511-16.014-16.776-16.014z" fill="#dd4b3e" paint-order="markers stroke fill"/><ellipse ry="5.564" rx="5.828" cy="239.002" cx="226.742" fill="#802d27" paint-order="markers stroke fill"/><path d="M190.301 237.283c-4.67 0-8.457 3.853-8.457 8.606s3.786 8.607 8.457 8.607c3.043 0 4.806-.958 6.337-2.516 1.53-1.557 2.087-3.913 2.087-6.29 0-.362-.023-.722-.064-1.079h-8.257v3.043h4.85c-.197.759-.531 1.45-1.058 1.986-.942.958-2.028 1.548-3.901 1.548-2.876 0-5.208-2.372-5.208-5.299 0-2.926 2.332-5.299 5.208-5.299 1.399 0 2.618.407 3.584 1.293l2.381-2.38c0-.002-.003-.004-.004-.005-1.588-1.524-3.62-2.215-5.955-2.215zm4.43 5.66l.003.006v-.003z" fill="#fff" paint-order="markers stroke fill"/><path d="M215.184 251.929l-7.98 7.979 28.477 28.475c.287-.649.449-1.366.449-2.123v-31.165c-.469.675-.934 1.349-1.382 2.005-.792 1.275-1.682 2.64-2.465 3.99-2.347 4.065-3.982 8.038-4.585 13.794-.162.485-.527.798-.93.799-.363-.001-.697-.255-.879-.667v-.012c-.593-5.822-2.237-9.82-4.6-13.914-.783-1.35-1.673-2.715-2.466-3.99-1.137-1.666-2.327-3.4-3.637-5.169l-.002-.003z" fill="#c3c3c3"/><path d="M212.983 248.495l-36.952 36.953v.812a5.227 5.227 0 0 0 5.238 5.238h1.015l35.666-35.666a136.275 136.275 0 0 0-2.764-3.9 37.575 37.575 0 0 0-.989-1.44c-.299-.503-.616-1.006-.95-1.508-.083-.162-.176-.326-.264-.489z" fill="#fddc4f" paint-order="markers stroke fill"/><path d="M211.998 261.083l-6.152 6.151 24.264 24.264h.781a5.227 5.227 0 0 0 5.239-5.238v-1.045z" fill="#fff" paint-order="markers stroke fill"/></g></svg>
						<span><?php esc_attr_e( 'Show map', 'woodmart' ); ?></span>
					</a>
				</div>
			<?php endif ?>
			
			<div class="wd-google-map-wrapper wd-fill">
				<div id="wd-map-id-<?php echo esc_attr( $uniqid ); ?>" class="wd-google-map without-content wd-fill"></div>
			</div>
			
			<?php if ( $settings['content'] || $settings['text'] ) : ?>
				<div <?php echo $this->get_render_attribute_string( 'content_wrapper' ); ?>>
					<div class="wd-google-map-content reset-last-child">
						<?php if ( 'html_block' === $settings['content_type'] ) : ?>
							<?php echo woodmart_get_html_block( $settings['content'] ); // phpcs:ignore ?>
						<?php else : ?>
							<?php echo wpautop( do_shortcode( $settings['text'] ) ); ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endif ?>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Google_Map() );
