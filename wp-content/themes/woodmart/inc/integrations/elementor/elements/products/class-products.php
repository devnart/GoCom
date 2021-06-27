<?php
/**
 * Products map.
 *
 * @package xts
 */

namespace XTS\Elementor;

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
class Products extends Widget_Base {
	/**
	 * Get widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wd_products';
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
		return esc_html__( 'Products (grid or carousel)', 'woodmart' );
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
		return 'wd-icon-products';
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
	 * Get attribute taxonomies
	 *
	 * @since 1.0.0
	 */
	public function get_product_attributes_array() {
		$attributes = [];

		if ( woodmart_woocommerce_installed() ) {
			foreach ( wc_get_attribute_taxonomies() as $attribute ) {
				$attributes[] = 'pa_' . $attribute->attribute_name;
			}
		}

		return $attributes;
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
			'post_type',
			[
				'label'       => esc_html__( 'Data source', 'woodmart' ),
				'description' => esc_html__( 'Select content type for your grid.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'product',
				'options'     => array(
					'product'            => esc_html__( 'All Products', 'woodmart' ),
					'featured'           => esc_html__( 'Featured Products', 'woodmart' ),
					'sale'               => esc_html__( 'Sale Products', 'woodmart' ),
					'new'                => esc_html__( 'Products with NEW label', 'woodmart' ),
					'bestselling'        => esc_html__( 'Bestsellers', 'woodmart' ),
					'ids'                => esc_html__( 'List of IDs', 'woodmart' ),
					'top_rated_products' => esc_html__( 'Top Rated Products', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'include',
			[
				'label'       => esc_html__( 'Include only', 'woodmart' ),
				'description' => esc_html__( 'Add products by title.', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_posts_by_query',
				'render'      => 'woodmart_get_posts_title_by_id',
				'post_type'   => 'product',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type' => 'ids',
				],
			]
		);

		$this->add_control(
			'taxonomies',
			[
				'label'       => esc_html__( 'Categories or tags', 'woodmart' ),
				'description' => esc_html__( 'List of product categories.', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_taxonomies_by_query',
				'render'      => 'woodmart_get_taxonomies_title_by_id',
				'taxonomy'    => array_merge( [ 'product_cat', 'product_tag' ], $this->get_product_attributes_array() ),
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->add_control(
			'orderby',
			[
				'label'       => esc_html__( 'Order by', 'woodmart' ),
				'description' => esc_html__( 'Select order type. If "Meta value" or "Meta value Number" is chosen then meta key is required.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''               => '',
					'date'           => esc_html__( 'Date', 'woodmart' ),
					'id'             => esc_html__( 'ID', 'woodmart' ),
					'author'         => esc_html__( 'Author', 'woodmart' ),
					'title'          => esc_html__( 'Title', 'woodmart' ),
					'modified'       => esc_html__( 'Last modified date', 'woodmart' ),
					'comment_count'  => esc_html__( 'Number of comments', 'woodmart' ),
					'menu_order'     => esc_html__( 'Menu order', 'woodmart' ),
					'meta_value'     => esc_html__( 'Meta value', 'woodmart' ),
					'meta_value_num' => esc_html__( 'Meta value number', 'woodmart' ),
					'rand'           => esc_html__( 'Random order', 'woodmart' ),
					'price'          => esc_html__( 'Price', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'offset',
			[
				'label'       => esc_html__( 'Offset', 'woodmart' ),
				'description' => esc_html__( 'Number of grid elements to displace or pass over.', 'woodmart' ),
				'type'        => Controls_Manager::TEXT,
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->add_control(
			'query_type',
			[
				'label'   => esc_html__( 'Query type', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'OR',
				'options' => array(
					'OR'  => esc_html__( 'OR', 'woodmart' ),
					'AND' => esc_html__( 'AND', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'order',
			[
				'label'       => esc_html__( 'Sort order', 'woodmart' ),
				'description' => 'Designates the ascending or descending order. More at <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>.',
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''     => esc_html__( 'Inherit', 'woodmart' ),
					'DESC' => esc_html__( 'Descending', 'woodmart' ),
					'ASC'  => esc_html__( 'Ascending', 'woodmart' ),
				),
				'condition'   => [
					'post_type!' => 'ids',
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label'       => esc_html__( 'Meta key', 'woodmart' ),
				'description' => esc_html__( 'Input meta key for grid ordering.', 'woodmart' ),
				'type'        => Controls_Manager::TEXTAREA,
				'condition'   => [
					'orderby' => [ 'meta_value', 'meta_value_num' ],
				],
			]
		);

		$this->add_control(
			'exclude',
			[
				'label'       => esc_html__( 'Exclude', 'woodmart' ),
				'description' => esc_html__( 'Exclude posts, pages, etc. by title.', 'woodmart' ),
				'type'        => 'wd_autocomplete',
				'search'      => 'woodmart_get_posts_by_query',
				'render'      => 'woodmart_get_posts_title_by_id',
				'post_type'   => 'product',
				'multiple'    => true,
				'label_block' => true,
				'condition'   => [
					'post_type!' => 'ids',
				],
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
			'layout',
			[
				'label'   => esc_html__( 'Products hover', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'     => esc_html__( 'Grid', 'woodmart' ),
					'list'     => esc_html__( 'List', 'woodmart' ),
					'carousel' => esc_html__( 'Carousel', 'woodmart' ),
				),
			]
		);

		$this->add_control(
			'columns',
			[
				'label'       => esc_html__( 'Columns', 'woodmart' ),
				'description' => esc_html__( 'Number of columns in the grid.', 'woodmart' ),
				'type'        => Controls_Manager::SLIDER,
				'default'     => [
					'size' => 4,
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
					'layout' => 'grid',
				],
			]
		);

		$this->add_control(
			'products_masonry',
			[
				'label'       => esc_html__( 'Masonry grid', 'woodmart' ),
				'description' => esc_html__( 'Products may have different sizes.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''        => esc_html__( 'Inherit', 'woodmart' ),
					'enable'  => esc_html__( 'Enable', 'woodmart' ),
					'disable' => esc_html__( 'Disable', 'woodmart' ),
				),
				'condition'   => [
					'layout' => 'grid',
				],
			]
		);

		$this->add_control(
			'products_different_sizes',
			[
				'label'       => esc_html__( 'Products grid with different sizes', 'woodmart' ),
				'description' => esc_html__( 'In this situation, some of the products will be twice bigger in width than others. Recommended to use with 6 columns grid only.', 'woodmart' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => array(
					''        => esc_html__( 'Inherit', 'woodmart' ),
					'enable'  => esc_html__( 'Enable', 'woodmart' ),
					'disable' => esc_html__( 'Disable', 'woodmart' ),
				),
				'condition'   => [
					'layout' => 'grid',
				],
			]
		);

		$this->add_control(
			'spacing',
			[
				'label'     => esc_html__( 'Space between', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'' => esc_html__( 'Inherit', 'woodmart' ),
					0  => esc_html__( '0 px', 'woodmart' ),
					2  => esc_html__( '2 px', 'woodmart' ),
					6  => esc_html__( '6 px', 'woodmart' ),
					10 => esc_html__( '10 px', 'woodmart' ),
					20 => esc_html__( '20 px', 'woodmart' ),
					30 => esc_html__( '30 px', 'woodmart' ),
				],
				'default'   => '',
				'condition' => [
					'layout'                => [ 'grid', 'carousel' ],
					'highlighted_products!' => [ '1' ],
				],
			]
		);

		$this->add_control(
			'items_per_page',
			[
				'label'       => esc_html__( 'Items per page', 'woodmart' ),
				'description' => esc_html__( 'Number of items to show per page.', 'woodmart' ),
				'default'     => 12,
				'type'        => Controls_Manager::NUMBER,
			]
		);

		$this->add_control(
			'pagination',
			[
				'label'     => esc_html__( 'Pagination', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''         => esc_html__( 'Inherit', 'woodmart' ),
					'more-btn' => esc_html__( 'Load more button', 'woodmart' ),
					'infinit'  => esc_html__( 'Infinit scrolling', 'woodmart' ),
					'arrows'   => esc_html__( 'Arrows', 'woodmart' ),
					'links'    => esc_html__( 'Links', 'woodmart' ),
				),
				'condition' => [
					'layout!' => 'carousel',
				],
			]
		);

		$this->add_control(
			'shop_tools',
			[
				'label'        => esc_html__( 'Shop tools', 'woodmart' ),
				'description'  => esc_html__( 'Per page, Sorting, Columns', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'condition'    => [
					'pagination' => 'links',
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
					'layout' => 'carousel',
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
				'description'  => esc_html__( 'This option allows you to init carousel script only when visitor scroll the page to the slider. Useful for performance optimization.\'', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();

		/**
		 * Products design settings.
		 */
		$this->start_controls_section(
			'products_design_style_section',
			[
				'label' => esc_html__( 'Products design', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'element_title',
			[
				'label'     => esc_html__( 'Element title', 'woodmart' ),
				'type'      => Controls_Manager::TEXT,
				'condition' => [
					'highlighted_products' => '1',
				],
			]
		);

		$this->add_control(
			'product_hover',
			[
				'label'     => esc_html__( 'Products hover', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'inherit',
				'options'   => array(
					'inherit'  => esc_html__( 'Inherit from Theme Settings', 'woodmart' ),
					'info-alt' => esc_html__( 'Full info on hover', 'woodmart' ),
					'info'     => esc_html__( 'Full info on image', 'woodmart' ),
					'alt'      => esc_html__( 'Icons and "add to cart" on hover', 'woodmart' ),
					'icons'    => esc_html__( 'Icons on hover', 'woodmart' ),
					'quick'    => esc_html__( 'Quick', 'woodmart' ),
					'button'   => esc_html__( 'Show button on hover on image', 'woodmart' ),
					'base'     => esc_html__( 'Show summary on hover', 'woodmart' ),
					'standard' => esc_html__( 'Standard button', 'woodmart' ),
					'tiled'    => esc_html__( 'Tiled', 'woodmart' ),
				),
				'condition' => [
					'layout!' => 'list',
				],
			]
		);

		$this->add_control(
			'img_size',
			[
				'label'   => esc_html__( 'Image size', 'woodmart' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'large',
				'options' => woodmart_get_all_image_sizes_names( 'elementor' ),
			]
		);

		$this->add_control(
			'img_size_custom',
			[
				'label'       => esc_html__( 'Image dimension', 'woodmart' ),
				'type'        => Controls_Manager::IMAGE_DIMENSIONS,
				'description' => esc_html__( 'You can crop the original image size to any custom size. You can also set a single value for height or width in order to keep the original size ratio.', 'woodmart' ),
				'condition'   => [
					'img_size' => 'custom',
				],
			]
		);

		$this->add_control(
			'sale_countdown',
			[
				'label'        => esc_html__( 'Sale countdown', 'woodmart' ),
				'description'  => esc_html__( 'Countdown to the end sale date will be shown. Be sure you have set final date of the product sale price.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'stock_progress_bar',
			[
				'label'        => esc_html__( 'Stock progress bar', 'woodmart' ),
				'description'  => esc_html__( 'Display a number of sold and in stock products as a progress bar.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'highlighted_products',
			[
				'label'        => esc_html__( 'Highlighted products', 'woodmart' ),
				'description'  => esc_html__( 'Create an eye-catching section of special products to promote them on your store.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
			]
		);

		$this->add_control(
			'products_bordered_grid',
			[
				'label'        => esc_html__( 'Bordered grid', 'woodmart' ),
				'description'  => esc_html__( 'Add borders between the products in your grid.', 'woodmart' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '0',
				'label_on'     => esc_html__( 'Yes', 'woodmart' ),
				'label_off'    => esc_html__( 'No', 'woodmart' ),
				'return_value' => '1',
				'condition'    => [
					'highlighted_products!' => [ '1' ],
				],
			]
		);

		$this->add_control(
			'product_quantity',
			[
				'label'     => esc_html__( 'Quantity input on product', 'woodmart' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''        => esc_html__( 'Inherit', 'woodmart' ),
					'enable'  => esc_html__( 'Enable', 'woodmart' ),
					'disable' => esc_html__( 'Disable', 'woodmart' ),
				),
				'condition' => [
					'product_hover' => [ 'standard', 'quick' ],
				],
			]
		);

		$this->end_controls_section();

		/**
		 * Extra settings.
		 */
		$this->start_controls_section(
			'extra_style_section',
			[
				'label' => esc_html__( 'Extra', 'woodmart' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
		woodmart_elementor_products_template( $this->get_settings_for_display() );
	}
}

Plugin::instance()->widgets_manager->register_widget_type( new Products() );
