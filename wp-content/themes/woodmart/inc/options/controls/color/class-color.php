<?php
/**
 * Color picker button control.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;
use XTS\Options;

/**
 * Input type text field control.
 */
class Color extends Field {
	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 *
	 * @return void.
	 */
	public function render_control() {
		$default         = Options::get_default( $this->args );
		$type            = isset( $this->args['data_type'] ) ? $this->args['data_type'] : 'array';
		$idle_input_name = 'hex' === $type ? $this->get_input_name() : $this->get_input_name( 'idle' );

		$value = $this->get_field_value();
		$idle  = $this->get_field_value( 'idle' );
		$hover = $this->get_field_value( 'hover' );

		if ( ! $idle && isset( $default['idle'] ) ) {
			$idle = $default['idle'];
		}

		if ( ! $hover && isset( $default['hover'] ) ) {
			$hover = $default['hover'];
		}

		if ( 'hex' === $type ) {
			$value_hex = $value;
		} else {
			$value_hex = $idle;
		}

		?>
			<?php if ( isset( $this->args['selector_hover'] ) || isset( $this->args['selector_hover_var'] ) ) : ?>
				<div class="xts-option-with-label">
					<span><?php esc_html_e( 'Regular', 'woodmart' ); ?></span>
			<?php endif; ?>
			<input class="color-picker" type="text" name="<?php echo esc_attr( $idle_input_name ); ?>" value="<?php echo esc_attr( $value_hex ); ?>" data-alpha-enabled="<?php echo isset( $this->args['alpha'] ) ? esc_attr( $this->args['alpha'] ) : 'true'; ?>" data-default-color="<?php echo isset( $default['idle'] ) ? esc_attr( $default['idle'] ) : ''; ?>" />

			<?php if ( isset( $this->args['selector_hover'] ) || isset( $this->args['selector_hover_var'] ) ) : ?>
				</div>
				<div class="xts-option-with-label">
					<span><?php esc_html_e( 'Hover', 'woodmart' ); ?></span>
					<input class="color-picker" type="text" name="<?php echo esc_attr( $this->get_input_name( 'hover' ) ); ?>" value="<?php echo esc_attr( $hover ); ?>" data-alpha-enabled="<?php echo isset( $this->args['alpha'] ) ? esc_attr( $this->args['alpha'] ) : 'true'; ?>" data-default-color="<?php echo isset( $default['hover'] ) ? esc_attr( $default['hover'] ) : ''; ?>" />
				</div>
			<?php endif; ?>			
		<?php
	}

	/**
	 * Enqueue colorpicker lib.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', WOODMART_ASSETS . '/js/wp-color-picker-alpha.js', array( 'wp-color-picker' ), woodmart_get_theme_info( 'Version' ), true );
	}

	/**
	 * Output field's css code on the color.
	 *
	 * @since 1.0.0
	 *
	 * @return  string $output Generated CSS code.
	 */
	public function css_output() {
		if ( empty( $this->get_field_value() ) ) {
			return '';
		}

		$default = Options::get_default( $this->args );
		$idle    = $this->get_field_value( 'idle' );
		$hover   = $this->get_field_value( 'hover' );

		if ( ! $idle && isset( $default['idle'] ) ) {
			$idle = $default['idle'];
		}

		if ( ! $hover && isset( $default['hover'] ) ) {
			$hover = $default['hover'];
		}

		$output = '';

		if ( isset( $this->args['selector'] ) && $idle ) {
			$output .= $this->args['selector'] . '{' . "\n";
			$output .= "\t" . 'color:' . $idle . ';' . "\n";
			$output .= '}' . "\n\n";
		}

		if ( isset( $this->args['selector_hover'] ) && $hover ) {
			$output .= $this->args['selector_hover'] . '{' . "\n";
			$output .= "\t" . 'color:' . $hover . ';' . "\n";
			$output .= '}' . "\n\n";
		}

		if ( isset( $this->args['selector_bg'] ) && $idle ) {
			$output .= $this->args['selector_bg'] . '{' . "\n";
			$output .= "\t" . 'background-color:' . $idle . ';' . "\n";
			$output .= '}' . "\n\n";
		}

		if ( isset( $this->args['selector_var'] ) || isset( $this->args['selector_hover_var'] ) ) {
			$output .= ':root{' . "\n";
		}

		if ( isset( $this->args['selector_var'] ) && $idle ) {
			$output .= $this->args['selector_var'] . ':' . $idle . ';' . "\n";
		}

		if ( isset( $this->args['selector_hover_var'] ) && $hover ) {
			$output .= $this->args['selector_hover_var'] . ':' . $hover . ';' . "\n";
		}

		if ( isset( $this->args['selector_var'] ) || isset( $this->args['selector_hover_var'] ) ) {
			$output .= '}' . "\n";
		}

		return $output;
	}
}
