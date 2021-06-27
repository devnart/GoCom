<?php
/**
 * Class WPML_Elementor_WD_Banner_Carousel
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Banner_Carousel
 */
class WPML_Elementor_WD_Banner_Carousel extends WPML_Elementor_Module_With_Items {
	/**
	 * Get item field.
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'content_repeater';
	}

	/**
	 * Get fields.
	 *
	 * @return array
	 */
	public function get_fields() {
		return array(
			'link' => array( 'url' ),
			'subtitle',
			'title',
			'content',
			'btn_text',
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
			case 'subtitle':
				return esc_html__( '[Banner carousel] - Subtitle', 'woodmart' );
			case 'title':
				return esc_html__( '[Banner carousel] - Title', 'woodmart' );
			case 'content':
				return esc_html__( '[Banner carousel] - Content', 'woodmart' );
			case 'btn_text':
				return esc_html__( '[Banner carousel] - Button text', 'woodmart' );
			case 'url':
				return esc_html__( '[Banner carousel] - Link', 'woodmart' );
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
			case 'subtitle':
			case 'title':
			case 'content':
			case 'btn_text':
				return 'LINE';
			case 'url':
				return 'LINK';
			default:
				return '';
		}
	}
}
