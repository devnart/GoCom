<?php
/**
 * HTML dropdown select control.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Switcher field control.
 */
class Select extends Field {
	/**
	 * Is select multiple.
	 *
	 * @since 1.0.0
	 */
	private function is_multiple() {
		return isset( $this->args['multiple'] );
	}

	/**
	 * Is select select2.
	 *
	 * @since 1.0.0
	 */
	private function is_select2() {
		return isset( $this->args['select2'] );
	}

	/**
	 * Is select autocomplete.
	 *
	 * @since 1.0.0
	 */
	private function is_autocomplete() {
		return isset( $this->args['autocomplete'] );
	}

	/**
	 * Is select autocomplete.
	 *
	 * @since 1.0.0
	 */
	private function has_empty_option() {
		return isset( $this->args['empty_option'] );
	}

	/**
	 * Is select select2.
	 *
	 * @since 1.0.0
	 */
	private function get_new_input_name() {
		if ( $this->is_multiple() ) {
			return $this->get_input_name() . '[]';
		} else {
			return $this->get_input_name();
		}
	}

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		$options = $this->get_field_options();
		$value   = $this->get_field_value();
		$name    = $this->get_new_input_name();

		if ( ! $this->is_multiple() && ! $this->is_autocomplete() && ! $this->is_select2() ) {
			$this->get_default_control();
			return;
		}

		$classes    = '';
		$attributes = '';

		if ( $this->is_multiple() ) {
			$attributes .= ' multiple';

			$order = $this->get_field_value();

			if ( is_array( $order ) && $order ) {
				usort(
					$options,
					function ( $a, $b ) use ( $order ) {
						$pos_a = array_search( $a['value'], $order ); // phpcs:ignore
						$pos_b = array_search( $b['value'], $order ); // phpcs:ignore
						return $pos_a - $pos_b;
					}
				);
			}
		}

		if ( $this->is_autocomplete() ) {
			$autocomplete_type   = $this->args['autocomplete']['type'];
			$autocomplete_value  = $this->args['autocomplete']['value'];
			$autocomplete_search = $this->args['autocomplete']['search'];

			$classes    .= ' xts-autocomplete';
			$attributes .= ' data-type="' . $autocomplete_type . '"';
			$attributes .= ' data-value="' . $autocomplete_value . '"';
			$attributes .= ' data-search="' . $autocomplete_search . '"';

			$options = $this->args['autocomplete']['render']( $value );
		}

		if ( $this->is_select2() ) {
			$classes .= ' xts-select2';
		}

		if ( ! $options && ! $this->has_empty_option() && ! $this->is_autocomplete() ) {
			esc_html_e( 'Options for this field are not provided in the map function.', 'woodmart' );
			return;
		}

		?>
		<select class="xts-select<?php echo esc_attr( $classes ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php echo $attributes; // phpcs:ignore ?>>
			<?php if ( $this->has_empty_option() ) : ?>
				<option value=""><?php esc_html_e( 'Select', 'woodmart' ); ?></option>
			<?php endif; ?>

			<?php foreach ( $options as $key => $option ) : ?>
				<?php
				$selected = false;

				if ( is_array( $value ) && in_array( $option['value'], $value, false ) ) { // phpcs:ignore
					$selected = true;
				} elseif ( ! is_array( $value ) && strval( $value ) === strval( $option['value'] ) ) {
					$selected = true;
				}

				?>
				<option value="<?php echo esc_attr( $option['value'] ); ?>" <?php selected( true, $selected ); ?>><?php echo esc_html( $option['name'] ); ?></option>
			<?php endforeach ?>
		</select>
		<?php if ( $this->is_multiple() ) : ?>
			<div class="xts-select2-all-wrap">
				<a href="#" class="xts-btn xts-select2-all">
					<?php esc_html_e( 'Select all', 'woodmart' ); ?>
				</a>
				
				<a href="#" class="xts-btn xts-unselect2-all">
					<?php esc_html_e( 'Deselect all', 'woodmart' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Get default control template.
	 *
	 * @since 1.0.0
	 */
	public function get_default_control() {
		?>
		<select class="xts-select" name="<?php echo esc_attr( $this->get_input_name() ); ?>">
			<?php if ( isset( $this->args['empty_option'] ) && $this->args['empty_option'] ) : ?>
				<option value=""><?php esc_html_e( 'Select', 'woodmart' ); ?></option>
			<?php endif; ?>
			
			<?php foreach ( $this->get_field_options() as $key => $option ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this->get_field_value(), $key ); ?>><?php echo esc_html( $option['name'] ); ?></option>
			<?php endforeach ?>
		</select>
		<?php
	}
	/**
	 * Enqueue colorpicker lib.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		if ( isset( $this->args['select2'] ) && $this->args['select2'] ) {
			wp_enqueue_script( 'select2', WOODMART_ASSETS . '/js/select2.full.min.js', array(), woodmart_get_theme_info( 'Version' ), true );
		}
	}
}
