<?php
/**
 * Class WPML_Elementor_WD_Product_Filters
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Product_Filters
 */
class WPML_Elementor_WD_Product_Filters extends WPML_Elementor_Module_With_Items {
	/**
	 * Get item field.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'items';
	}

	/**
	 * Get fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'categories_title',
			'attributes_title',
			'stock_title',
			'price_title',
		);
	}

	/**
	 * Get title.
	 *
	 * @param string $field Field.
	 *
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'categories_title':
				return esc_html__( '[Product filters] - Categories title', 'woodmart' );
			case 'attributes_title':
				return esc_html__( '[Product filters] - Attributes title', 'woodmart' );
			case 'stock_title':
				return esc_html__( '[Product filters] - Stock title', 'woodmart' );
			case 'price_title':
				return esc_html__( '[Product filters] - Price title', 'woodmart' );
			default:
				return '';
		}
	}

	/**
	 * Get editor type.
	 *
	 * @param string $field Field.
	 *
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'categories_title':
			case 'attributes_title':
			case 'stock_title':
			case 'price_title':
				return 'LINE';
			default:
				return '';
		}
	}
}
