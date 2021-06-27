<?php
/**
 * Class WPML_Elementor_WD_Timeline
 *
 * @package woodmart
 */

/**
 * Class WPML_Elementor_WD_Timeline
 */
class WPML_Elementor_WD_Timeline extends WPML_Elementor_Module_With_Items {
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
			'title',
			'title_primary',
			'content',
			'title_secondary',
			'title_primary',
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
			case 'title':
				return esc_html__( '[Timeline] - Breakpoint title', 'woodmart' );
			case 'title_primary':
				return esc_html__( '[Timeline] - Title primary', 'woodmart' );
			case 'content':
				return esc_html__( '[Timeline] - Content primary', 'woodmart' );
			case 'title_secondary':
				return esc_html__( '[Timeline] - Title secondary', 'woodmart' );
			case 'content_secondary':
				return esc_html__( '[Timeline] - Content secondary', 'woodmart' );
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
			case 'title_primary':
			case 'title_secondary':
				return 'LINE';
			case 'content_secondary':
			case 'content':
				return 'AREA';
			default:
				return '';
		}
	}
}
