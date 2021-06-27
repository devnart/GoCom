<?php
/**
 * Class WPML_Elementor_WD_List
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_List
 */
class WPML_Elementor_WD_List extends WPML_Elementor_Module_With_Items {
	/**
	 * Get item field.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'list_items';
	}

	/**
	 * Get fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'list_content',
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
			case 'list_content':
				return esc_html__( '[List] - Content', 'woodmart' );
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
			case 'list_content':
				return 'AREA';
			default:
				return '';
		}
	}
}
