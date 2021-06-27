<?php
/**
 * Range slider.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Range slider control.
 */
class Range extends Field {


	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		?>
			<div class="xts-range-slider"></div>
			<input type="hidden" class="xts-range-value" data-start="<?php echo esc_attr( $this->get_field_value() ); ?>" data-min="<?php echo esc_attr( $this->args['min'] ); ?>" data-max="<?php echo esc_attr( $this->args['max'] ); ?>" data-step="<?php echo esc_attr( $this->args['step'] ); ?>" name="<?php echo esc_attr( $this->get_input_name() ); ?>" value="<?php echo esc_attr( $this->get_field_value() ); ?>">
			<span class="xts-range-field-value-display"><?php esc_html_e( 'Value:', 'woodmart' ); ?> <span class="xts-range-field-value-text"></span></span>
		<?php
	}

	/**
	 * Enqueue slider jquery ui.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'xts-jquery-ui', WOODMART_ASSETS . '/css/jquery-ui.css', array(), woodmart_get_theme_info( 'Version' ) );
	}
}


