<?php
/**
 * Elementor buttons controls
 *
 * @package xts
 */

namespace XTS\Elementor\Controls;

use Elementor\Base_Data_Control;

/**
 * Elementor wd_buttons control.
 *
 * @since 1.0.0
 */
class Buttons extends Base_Data_Control {

	/**
	 * Get wd_buttons control type.
	 *
	 * Retrieve the control type, in this case `wd_buttons`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'wd_buttons';
	}

	/**
	 * Get wd_buttons control default settings.
	 *
	 * Retrieve the default settings of the wd_buttons control. Used to return the
	 * default settings while initializing the wd_buttons control.
	 *
	 * @since  1.8.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'options' => [],
		];
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script( 'xts-buttons-control', WOODMART_THEME_DIR . '/inc/integrations/elementor/assets/js/buttons.js', [ 'jquery' ], woodmart_get_theme_info( 'Version' ), false );
	}

	/**
	 * Render wd_buttons control output in the editor.
	 *
	 * Used to generate the control HTML in the editor using Underscore JS
	 * template. The variables for the class are available using `data` JS
	 * object.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<#
		var first_key = Object.keys(options)[0];
		var has_image = typeof options[first_key].image != 'undefined';
		var has_style = typeof options[first_key].style != 'undefined';
		var type  = has_image ? ' xts-images-set' : '';
		var style = has_style ? ' xts-style-' + options[first_key].style : '';
		#>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label
				}}}</label>
			<div class="elementor-control-input-wrapper">
				<input type="hidden" id="<?php echo esc_attr( $control_uid ); ?>" class="xts-buttons-input" data-setting="{{ data.name }}">
				<div class="xts-btns-set{{ type }}{{ style }}">
					<# _.each( options, function( option_settings, option_value ) { #>
					<#
					var type = has_image ? ' xts-set-btn-image' : ' xts-set-btn';
					var value = data.controlValue;
					if ( typeof value == 'string' ) {
					var selected = ( option_value === value ) ? ' xts-btns-set-active' : '';
					} else if ( null !== value ) {
					var value = _.values( value );
					var selected = ( -1 !== value.indexOf( option_value ) ) ? ' xts-btns-set-active' : '';
					}
					#>
					<div class="xts-set-item{{ selected }}{{ type }}" data-value="{{ option_value }}">
						<# if ( option_settings['image'] ) { #>
						<img src="{{{ option_settings['image'] }}}">
						<span class="xts-set-tooltip">{{{ option_settings['title'] }}}</span>
						<# } else { #>
						{{{ option_settings['title'] }}}
						<# } #>
					</div>
					<# } );
					#>
				</div>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
