<?php
/**
 * Class WPML_Elementor_WD_Testimonials
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Testimonials
 */
class WPML_Elementor_WD_Testimonials extends WPML_Elementor_Module_With_Items {
	/**
	 * Get item field.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'items_repeater';
	}

	/**
	 * Get fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'name',
			'title',
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
			case 'name':
				return esc_html__( '[Testimonials] - Name', 'woodmart' );
			case 'title':
				return esc_html__( '[Testimonials] - Title', 'woodmart' );
			case 'content':
				return esc_html__( '[Testimonials] - Content', 'woodmart' );
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
			case 'title':
				return 'LINE';
			case 'content':
				return 'AREA';
			default:
				return '';
		}
	}
}
