<?php
/**
 * Checkbox control.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Checkbox field control.
 */
class Checkbox extends Field {

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		?>
			<input type="checkbox" class="xts-checkbox" value="on" name="<?php echo esc_attr( $this->get_input_name() ); ?>" <?php checked( $this->get_field_value(), 'on' ); ?>>
		<?php
	}
}


