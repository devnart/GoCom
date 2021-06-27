<?php
/**
 * Menu price map.
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
class Menu_Price extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_menu_price';
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
		return esc_html__( 'Menu price', 'woodmart' );
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
		return 'wd-icon-menu-price';
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
				'default' => 'Weight Watchers General Tso\'s Chicken',
			]
		);

		$this->add_control(
			'description',
			[
				'label'   => esc_html__( 'Description', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'In a medium bowl, whisk together broth, cornstarch, sugar, soy sauce, vinegar and ginger; set aside.',
			]
		);

		$this->add_control(
			'price',
			[
				'label'   => esc_html__( 'Price', 'woodmart' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '$399.00',
			]
		);

		$this->add_control(
			'link',
			[
				'label'   => esc_html__( 'Link', 'woodmart' ),
				'type'    => Controls_Manager::URL,
				'default' => [
					'url'         => '',
					'is_external' => false,
					'nofollow'    => false,
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose image', 'woodmart' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image',
				'default'   => 'thumbnail',
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
			'image'       => '',
			'title'       => '',
			'description' => '',
			'price'       => '',
			'link'        => '',
		];

		$settings     = wp_parse_args( $this->get_settings_for_display(), $default_settings );
		$image_output = '';

		$this->add_render_attribute(
			[
				'wrapper'     => [
					'class' => [
						'wd-menu-price',
						woodmart_get_old_classes( 'woodmart-menu-price' ),
					],
				],
				'price'       => [
					'class' => [
						'menu-price-price',
						'price',
					],
				],
				'description' => [
					'class' => [
						'menu-price-details',
					],
				],
			]
		);

		$this->add_inline_editing_attributes( 'title' );
		$this->add_inline_editing_attributes( 'price' );
		$this->add_inline_editing_attributes( 'description' );

		// Image settings.
		if ( isset( $settings['image']['id'] ) && $settings['image']['id'] ) {
			$image_output = '<span class="img-wrapper">' . woodmart_get_image_html( $settings, 'image' ) . '</span>';
		}

		// Link settings.
		if ( $settings['link'] && $settings['link']['url'] ) {
			$this->add_render_attribute( 'wrapper', 'class', 'cursor-pointer' );
		}
		if ( isset( $settings['link']['is_external'] ) && 'on' === $settings['link']['is_external'] ) {
			$onclick = 'window.open(\'' . esc_url( $settings['link']['url'] ) . '\',\'_blank\')';
		} else {
			$onclick = 'window.location.href=\'' . esc_url( $settings['link']['url'] ) . '\'';
		}

		woodmart_enqueue_inline_style( 'menu-price' );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?> <?php echo $settings['link']['url'] && ! woodmart_elementor_is_edit_mode() ? 'onclick="' . $onclick . '"' : ''; ?>>

			<?php if ( $image_output ) : ?>
				<div class="menu-price-image">
					<?php echo $image_output; ?>
				</div>
			<?php endif ?>

			<div class="menu-price-desc-wrapp">
				<div class="menu-price-heading">
					<?php if ( $settings['title'] ) : ?>
						<h3 class="menu-price-title wd-entities-title">
							<span <?php echo $this->get_render_attribute_string( 'title' ); ?>>
								<?php echo wp_kses( $settings['title'], woodmart_get_allowed_html() ); ?>
							</span>
						</h3>
					<?php endif ?>

					<div <?php echo $this->get_render_attribute_string( 'price' ); ?>>
						<?php echo wp_kses( $settings['price'], woodmart_get_allowed_html() ); ?>
					</div>
				</div>
				
				<?php if ( $settings['description'] ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'description' ); ?>>
						<?php echo do_shortcode( $settings['description'] ); ?>
					</div>
				<?php endif ?>
			</div>
		</div>
		<?php
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Menu_Price() );
