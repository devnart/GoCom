<?php
/**
 * Class WPML_Elementor_WD_Infobox_Carousel
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Infobox_Carousel
 */
class WPML_Elementor_WD_Infobox_Carousel extends WPML_Elementor_Module_With_Items {
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
			'icon_text',
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
			case 'url':
				return esc_html__( '[Infobox carousel] - Link', 'woodmart' );
			case 'icon_text':
				return esc_html__( '[Infobox carousel] - Icon text', 'woodmart' );
			case 'subtitle':
				return esc_html__( '[Infobox carousel] - Subtitle', 'woodmart' );
			case 'title':
				return esc_html__( '[Infobox carousel] - Title', 'woodmart' );
			case 'content':
				return esc_html__( '[Infobox carousel] - Content', 'woodmart' );
			case 'btn_text':
				return esc_html__( '[Infobox carousel] - Button text', 'woodmart' );
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
			case 'url':
			case 'icon_text':
			case 'subtitle':
			case 'title':
			case 'btn_text':
				return 'LINE';
			case 'content':
				return 'AREA';
			default:
				return '';
		}
	}
}
