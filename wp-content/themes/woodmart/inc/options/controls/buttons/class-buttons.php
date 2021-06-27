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
class Buttons extends Field {

	/**
	 * Options array from arguments.
	 *
	 * @var array
	 */
	private $_options;

	/**
	 * Is it images buttons set.
	 *
	 * @var boolean
	 */
	private $_is_images_set;

	/**
	 * Contruct the object.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args Field args array.
	 * @param arary  $options Options from the database.
	 * @param string $type Field type.
	 * @param string $object Field object.
	 */
	public function __construct( $args, $options, $type = 'options', $object = 'post' ) {
		parent::__construct( $args, $options, $type, $object );

		$this->_options = $this->get_field_options();

		if ( empty( $this->_options ) ) {
			echo 'Options for this field are not provided in the map function.';
			return;
		}

		$first_option = reset( $this->_options );

		$this->_is_images_set = isset( $first_option['image'] );

		if ( $this->_is_images_set ) {
			$this->extra_css_class .= ' xts-images-set';
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
		if ( empty( $this->_options ) ) {
			echo 'Options for this field are not provided in the map function.';
			return;
		}

		$btn_class = $this->_is_images_set ? 'xts-set-item xts-set-btn-img' : 'xts-set-item xts-set-btn';
		?>
			<div class="xts-btns-set">
				<?php foreach ( $this->_options as $key => $option ) : ?>
					<div class="<?php echo esc_attr( $btn_class . ( ( $this->get_field_value() == $key ) ? ' xts-btns-set-active' : '' ) ); ?>" data-value="<?php echo esc_attr( $key ); ?>">
						<?php if ( $this->_is_images_set ) : ?>
							<img src="<?php echo esc_url( $option['image'] ); ?>" title="<?php echo esc_attr( $option['name'] ); ?>" alt="<?php echo esc_attr( $option['name'] ); ?>">
							<span><?php echo esc_html( $option['name'] ); ?></span>
						<?php else : ?>
							<?php echo esc_html( $option['name'] ); ?>
						<?php endif ?>
					</div>
				<?php endforeach ?>
			</div>
			<input type="hidden" name="<?php echo esc_attr( $this->get_input_name() ); ?>" value="<?php echo esc_attr( $this->get_field_value() ); ?>"/>
		<?php
	}

}
