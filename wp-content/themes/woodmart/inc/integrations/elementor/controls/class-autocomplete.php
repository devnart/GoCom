<?php
/**
 * Elementor autocomplete controls
 *
 * @package xts
 */
namespace XTS\Elementor\Controls;

use Elementor\Base_Data_Control;

/**
 * Elementor wd_autocomplete control.
 *
 * @since 1.0.0
 */
class Autocomplete extends Base_Data_Control {
	/**
	 * Get wd_autocomplete control type.
	 *
	 * Retrieve the control type, in this case `wd_autocomplete`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @return string Control type.
	 */
	public function get_type() {
		return 'wd_autocomplete';
	}

	/**
	 * Get wd_autocomplete control default settings.
	 *
	 * Retrieve the default settings of the wd_autocomplete control. Used to return the
	 * default settings while initializing the wd_autocomplete control.
	 *
	 * @since  1.8.0
	 * @access protected
	 *
	 * @return array Control default settings.
	 */
	protected function get_default_settings() {
		return [
			'label_block' => true,
			'multiple'    => false,
			'taxonomy'    => false,
			'post_type'   => false,
			'options'     => [],
			'callback'    => '',
		];
	}

	/**
	 * Enqueue control scripts and styles.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script( 'xts-autocomplete-control', WOODMART_THEME_DIR . '/inc/integrations/elementor/assets/js/autocomplete.js', [ 'jquery' ], woodmart_get_theme_info( 'Version' ), false );
	}

	/**
	 * Render wd_autocomplete control output in the editor.
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
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label
				}}}</label>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select id="<?php echo esc_attr( $control_uid ); ?>" class="elementor-select2" type="select2" {{ multiple }} data-setting="{{ data.name }}" data-post-type="{{ data.post_type }}" data-taxonomy="{{ data.taxonomy }}" data-placeholder="<?php echo esc_attr__( 'Search', 'woodmart' ); ?>">
					<# _.each( data.options, function( option_title, option_value ) {
					var value = data.controlValue;
					if ( typeof value == 'string' ) {
						var selected = ( option_value === value ) ? 'selected' : '';
					} else if ( null !== value ) {
						var value = _.values( value );
						var selected = ( -1 !== value.indexOf( option_value ) ) ? 'selected' : '';
					}
					#>
					<option {{ selected }} value="{{ option_value }}">{{{ option_title }}}</option>
					<# } ); #>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
