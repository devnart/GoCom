<?php
/**
 * Class WPML_Elementor_WD_Image_Hotspot
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Image_Hotspot
 */
class WPML_Elementor_WD_Image_Hotspot extends WPML_Elementor_Module_With_Items {
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
			'link_text',
			'link' => array( 'url' ),
			'content',
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
			case 'content':
				return esc_html__( '[Image Hotspot] - Content', 'woodmart' );
			case 'link_text':
				return esc_html__( '[Image Hotspot] - Link text', 'woodmart' );
			case 'url':
				return esc_html__( '[Image Hotspot] - Link', 'woodmart' );
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
			case 'link_text':
			case 'url':
				return 'LINE';
			case 'content':
				return 'AREA';
			default:
				return '';
		}
	}
}
