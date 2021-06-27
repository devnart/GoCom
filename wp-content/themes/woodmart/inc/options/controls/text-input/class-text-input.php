<?php
/**
 * HTML text input control.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Input type text field control.
 */
class Text_Input extends Field {


	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		$classes = '';
		
		if ( isset( $this->args['datepicker'] ) ) {
			$classes .= 'date-picker-field';
		}
		?>
			<input class="<?php echo esc_attr( $classes ); ?>" type="text" placeholder="<?php echo esc_attr( $this->_get_placeholder() ); ?>" name="<?php echo esc_attr( $this->get_input_name() ); ?>" value="<?php echo esc_attr( $this->get_field_value() ); ?>">
		<?php
	}

	/**
	 * Get input's placeholder text from arguments.
	 *
	 * @since 1.0.0
	 */
	private function _get_placeholder() {
		return isset( $this->args['placeholder'] ) ? $this->args['placeholder'] : '';
	}
}


