<?php
/**
 * Class WPML_Elementor_WD_Extra_Menu_List
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Extra_Menu_List
 */
class WPML_Elementor_WD_Extra_Menu_List extends WPML_Elementor_Module_With_Items {
	/**
	 * Get item field.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'menu_items_repeater';
	}

	/**
	 * Get fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'title',
			'label',
			'link' => array( 'url' ),
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
			case 'label':
				return esc_html__( '[Extra menu list] - Label text', 'woodmart' );
			case 'title':
				return esc_html__( '[Extra menu list] - Title', 'woodmart' );
			case 'url':
				return esc_html__( '[Extra menu list] - Link', 'woodmart' );
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
			case 'title':
			case 'label':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
