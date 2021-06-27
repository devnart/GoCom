<?php
/**
 * Title map.
 *
 * @package xts
 */

namespace XTS\Elementor;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class Images_Gallery extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_images_gallery';
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
		return esc_html__( 'Images gallery', 'woodmart' );
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
		return 'wd-icon-images-gallery';
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
			'ids',
			[
				'label'   => esc_html__( 'Images', 'woodmart' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => [],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'ids', // Need images id.
				'default'   => 'large',
				'separator' => 'none',
			]
		);

		$this->end_controls_section();

		/**
		 * Click action settings.
		 */
		$this->start_controls_section(
			'click_action_section',
			[
				'label' => esc_html__( 'Click action', 'woodmart' ),
			]
		);

		$this->add_control(
			'on_click',
			[
				'label'   => esc_html__( 'On click action', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'lightbox' => esc_html__( 'Lightbox', 'woodmart' ),
					'links'    => esc_html__( 'Custom link', 'woodmart' ),
					'none'     => esc_html__( 'None', 'woodmart' ),
				],
				'default' => 'lightbox',
			]
		);

		$this->add_control(
			'target_blank',
			[
				'label'        => esc_html__( 'Open in new tab', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'condition'    => [
					'on_click' => [ 'links' ],
				],
			]
		);

		$this->add_control(
			'caption',
			[
				'label'        => esc_html__( 'Images captions', 'woodmart' ),
				'description'  => esc_html__( 'Display images captions below the images when you open them in lightbox. Captions are based on titles of your photos and can be edited in Dashboard -> Media.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'condition'    => [
					'on_click' => [ 'lightbox' ],
				],
			]
		);

		$this->add_control(
			'custom_links',
			[
				'label'       => esc_html__( 'Custom links', 'woodmart' ),
				'description' => esc_html__( 'Enter links for each slide (Note: divide links with linebreaks (Enter).', 'woodmart' ),
				'type'        => Controls_Manager::TEXTAREA,
				'condition'   => [
					'on_click' => [ 'links' ],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Extra settings.
		 */
		$this->start_controls_section(
			'extra_content_section',
			[
				'label' => esc_html__( 'Extra', 'woodmart' ),
			]
		);

		$this->add_control(
			'lazy_loading',
			[
				'label'        => esc_html__( 'Lazy loading for images', 'woodmart' ),
				'description'  => esc_html__( 'Enable lazy loading for images for this element.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
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
			'layout_style_section',
			[
				'label' => esc_html__( 'Layout', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'view',
			[
				'label'   => esc_html__( 'View', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'grid'      => esc_html__( 'Default grid', 'woodmart' ),
					'masonry'   => esc_html__( 'Masonry grid', 'woodmart' ),
					'carousel'  => esc_html__( 'Carousel', 'woodmart' ),
					'justified' => esc_html__( 'Justified gallery', 'woodmart' ),
				],
				'default' => 'grid',
			]
		);

		$this->add_control(
			'spacing',
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
				'default'   => 0,
				'condition' => [
					'view!' => [ 'justified' ],
				],
			]
		);

		$this->add_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns', 'woodmart' ),
				'description' => esc_html__( 'Number of columns in the grid.', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 3,
				],
				'size_units'  => '',
				'range'       => [
					'px' => [
						'min'  => 1,
						'max'  => 6,
						'step' => 1,
					],
				],
				'condition'   => [
					'view' => [ 'grid', 'masonry' ],
				],
			]
		);

		$this->add_control(
			'horizontal_align',
			[
				'label'     => esc_html__( 'Horizontal  align', 'woodmart' ),
				'type'      => 'wd_buttons',
				'options'   => [
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
				'default'   => 'center',
				'condition' => [
					'view' => [ 'grid', 'masonry', 'carousel' ],
				],
			]
		);

		$this->add_control(
			'vertical_align',
			[
				'label'     => esc_html__( 'Vertical  align', 'woodmart' ),
				'type'      => 'wd_buttons',
				'options'   => [
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
				'default'   => 'middle',
				'condition' => [
					'view' => [ 'grid', 'masonry', 'carousel' ],
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
				'label'     => esc_html__( 'Carousel', 'woodmart' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'view' => [ 'carousel' ],
				],
			]
		);

		$this->add_control(
			'slides_per_view',
			[
				'label'       => esc_html__( 'Slides per view', 'woodmart' ),
				'description' => esc_html__( 'Set numbers of slides you want to display at the same time on slider\'s container for carousel mode.', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 4,
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
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'center_mode',
			[
				'label'        => esc_html__( 'Center mode', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'scroll_carousel_init',
			[
				'label'        => esc_html__( 'Init carousel on scroll', 'woodmart' ),
				'description'  => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.\'', 'woodmart' ),
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
		$default_settings = [
			'ids'                  => '',
			'columns'              => 3,
			'link'                 => '',
			'spacing'              => 0,
			'on_click'             => 'lightbox',
			'target_blank'         => false,
			'custom_links'         => '',
			'view'                 => 'grid',
			'caption'              => false,
			'speed'                => '5000',
			'autoplay'             => 'no',
			'scroll_per_page'      => 'yes',
			'lazy_loading'         => 'no',
			'scroll_carousel_init' => 'no',
			'horizontal_align'     => 'center',
			'vertical_align'       => 'middle',
			'custom_sizes'         => apply_filters( 'woodmart_images_gallery_shortcode_custom_sizes', false ),
		];

		$settings                    = wp_parse_args( $this->get_settings_for_display(), $default_settings );

		$settings['columns'] = isset( $settings['columns']['size'] ) ? $settings['columns']['size'] : 3;
		$settings['slides_per_view'] = isset( $settings['slides_per_view']['size'] ) ? $settings['slides_per_view']['size'] : 4;

		$this->add_render_attribute(
			[
				'wrapper' => [
					'class' => [
						'wd-images-gallery',
						'wd-justify-' . $settings['horizontal_align'],
						'wd-items-' . $settings['vertical_align'],
						'view-' . $settings['view'],
					],
				],
				'gallery' => [
					'class' => [
						'gallery-images',
					],
				],
				'item'    => [
					'class' => [
						'wd-gallery-item',
					],
				],
			]
		);

		$owl_attributes = '';

		if ( 'lightbox' === $settings['on_click'] ) {
			woodmart_enqueue_js_library( 'photoswipe-bundle' );
			woodmart_enqueue_inline_style( 'photoswipe' );
			woodmart_enqueue_js_script( 'photoswipe-images' );
			$this->add_render_attribute( 'wrapper', 'class', 'photoswipe-images' );
		}

		if ( 'masonry' === $settings['view'] ) {
			wp_enqueue_script( 'imagesloaded' );
			woodmart_enqueue_js_library( 'isotope-bundle' );
			woodmart_enqueue_js_script( 'image-gallery-element' );
		}

		if ( 'justified' === $settings['view'] ) {
			woodmart_enqueue_js_library( 'justified' );
			woodmart_enqueue_inline_style( 'justified' );
			woodmart_enqueue_js_script( 'image-gallery-element' );
		}

		if ( $settings['view'] === 'carousel' ) {
			woodmart_enqueue_inline_style( 'owl-carousel' );
			$owl_attributes = woodmart_get_owl_attributes( $settings );
			$this->add_render_attribute( 'gallery', 'class', 'owl-carousel ' . woodmart_owl_items_per_slide( $settings['slides_per_view'], array(), false, false, $settings['custom_sizes'] ) );

			$this->add_render_attribute( 'wrapper', 'class', 'wd-carousel-spacing-' . $settings['spacing'] );
			$this->add_render_attribute( 'wrapper', 'class', 'wd-carousel-container' );

			if ( 'yes' === $settings['scroll_carousel_init'] ) {
				woodmart_enqueue_js_library( 'waypoints' );
				$this->add_render_attribute( 'wrapper', 'class', 'scroll-init' );
			}

			if ( woodmart_get_opt( 'disable_owl_mobile_devices' ) ) {
				$this->add_render_attribute( 'wrapper', 'class', 'disable-owl-mobile' );
			}
		}

		if ( 'grid' === $settings['view'] || 'masonry' === $settings['view'] ) {
			$this->add_render_attribute( 'gallery', 'class', 'row' );
			$this->add_render_attribute( 'gallery', 'class', 'wd-spacing-' . $settings['spacing'] );
			$this->add_render_attribute( 'item', 'class', woodmart_get_grid_el_class( 0, $settings['columns'] ) );
		}

		if ( 'yes' === $settings['lazy_loading'] ) {
			woodmart_lazy_loading_init( true );
			woodmart_enqueue_inline_style( 'lazy-loading' );
		}

		if ( count( $settings['ids'] ) < 1 ) {
			return;
		}

		woodmart_enqueue_inline_style( 'image-gallery' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> <?php echo 'carousel' === $settings['view'] ? $owl_attributes : ''; ?>>
			<div <?php echo $this->get_render_attribute_string( 'gallery' ); ?>>
				<?php foreach ( $settings['ids'] as $index => $image ) : ?>
					<?php
					$index++;
					$image_url  = woodmart_get_image_url( $image['id'], 'ids', $settings );

					if ( apply_filters( 'woodmart_image_gallery_caption', false ) ) {
						$title = wp_get_attachment_caption( $image['id'] );
					} else {
						$attachment = get_post( $image['id'] );
						$title      = trim( strip_tags( $attachment->post_title ) );
					}
					$image_data = wp_get_attachment_image_src( $image['id'], 'full' );
					$link       = $image_data[0];

					if ( 'links' === $settings['on_click'] ) {
						$custom_links = explode( "\n", $settings['custom_links'] );
						$link         = isset( $custom_links[ $index ] ) ? $custom_links[ $index ] : '';
					}

					$link_attrs = woodmart_get_link_attrs(
						array(
							'url'  => $link,
							'data' => 'data-width="' . esc_attr( $image_data[1] ) . '" data-height="' . esc_attr( $image_data[2] ) . '" data-index="' . esc_attr( $index ) . '" data-elementor-open-lightbox="no"',
						)
					);

					if ( $settings['target_blank'] ) {
						$link_attrs .= ' target="_blank"';
					}

					if ( $settings['caption'] ) {
						$link_attrs .= ' title="' . $title . '"';
					}

					?>
					<div <?php echo $this->get_render_attribute_string( 'item' ); ?>>
						<?php if ( 'none' !== $settings['on_click'] ) : ?>
							<a <?php echo $link_attrs; ?>>
						<?php endif ?>
						
							<?php echo apply_filters( 'woodmart_image', '<img src="' . esc_url( $image_url ) . '">' ); ?>
							
						<?php if ( 'none' !== $settings['on_click'] ) : ?>
							</a>
						<?php endif ?>
					</div>
					<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Images_Gallery() );
