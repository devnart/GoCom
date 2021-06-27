<?php
/**
 * CSS and JS code editor.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Editor class.
 */
class Editor extends Field {
	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		wp_enqueue_code_editor( array() );
		?>
			<textarea data-language="<?php echo esc_attr( $this->args['language'] ); ?>" name="<?php echo esc_attr( $this->get_input_name() ); ?>"><?php echo $this->get_field_value(); // phpcs:ignore ?></textarea>
		<?php
	}
}
