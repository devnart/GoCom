<?php
/**
 * Class WPML_Elementor_WD_Pricing_Tables
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Pricing_Tables
 */
class WPML_Elementor_WD_Pricing_Tables extends WPML_Elementor_Module_With_Items {
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
			'name',
			'features_list',
			'price_value',
			'price_suffix',
			'currency',
			'button_label',
			'link' => array( 'url' ),
			'label',
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
			case 'name':
				return esc_html__( '[Pricing tables] - Name', 'woodmart' );
			case 'features_list':
				return esc_html__( '[Pricing tables] - Featured list', 'woodmart' );
			case 'price_value':
				return esc_html__( '[Pricing tables] - Price value', 'woodmart' );
			case 'price_suffix':
				return esc_html__( '[Pricing tables] - Price suffix', 'woodmart' );
			case 'currency':
				return esc_html__( '[Pricing tables] - Currency', 'woodmart' );
			case 'button_label':
				return esc_html__( '[Pricing tables] - Button text', 'woodmart' );
			case 'url':
				return esc_html__( '[Pricing tables] - Button link', 'woodmart' );
			case 'label':
				return esc_html__( '[Pricing tables] - Label text', 'woodmart' );
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
			case 'name':
			case 'price_value':
			case 'price_suffix':
			case 'currency':
			case 'button_label':
			case 'label':
				return 'LINE';
			case 'url':
				return 'LINK';
			case 'features_list':
				return 'AREA';
			default:
				return '';
		}
	}
}
