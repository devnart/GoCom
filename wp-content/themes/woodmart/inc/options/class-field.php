<?php
/**
 * Basic field abstract class.
 *
 * @package xts
 */

namespace XTS\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Sanitize;

/**
 * Abstract class for the field.
 */
abstract class Field {

	/**
	 * ID of the field
	 *
	 * @var int
	 */
	private $_id;

	/**
	 * Args array for the field
	 *
	 * @var array
	 */
	public $args = array();

	/**
	 * Options array from the database for the field value.
	 *
	 * @var array
	 */
	public $options = array();

	/**
	 * Options set prefix.
	 *
	 * @var string
	 */
	public $opt_name = 'woodmart';

	/**
	 * Field type
	 *
	 * @var string
	 */
	private $_type;

	/**
	 * Post object. Required for metabox field to get the value from the database.
	 *
	 * @var null
	 */
	private $_post = null;

	/**
	 * Term object. Required for metabox field to get the value from the database.
	 *
	 * @var null
	 */
	private $_term = null;

	/**
	 * Metabox object. Post or term.
	 *
	 * @var null
	 */
	private $_object = null;

	/**
	 * Presets IDs.
	 *
	 * @var null
	 */
	private $_presets = false;

	/**
	 * Is this field inherits value. (not use preset value)
	 *
	 * @var boolean
	 */
	private $_inherit_value;

	/**
	 * Extra wrapper CSS class.
	 *
	 * @var string
	 */
	public $extra_css_class = '';
	
	/**
	 * Construct the object.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Field args array.
	 * @param array $options Options from the database.
	 * @param string $type Field type.
	 * @param string $object $object   Object for post or term.
	 */
	public function __construct( $args, $options, $type = 'options', $object = 'post' ) {
		$this->args = $args;
		$this->_id  = $args['id'];

		if ( $options ) {
			$this->options = $options;
		}

		$this->_type   = $type;
		$this->_object = $object;

		$this->extra_css_class  = 'xts-' . $this->args['type'] . '-control';
		$this->extra_css_class .= ' xts-' . $this->args['id'] . '-field';

		if ( $this->dependency_class() ) {
			$this->extra_css_class .= ' ' . $this->dependency_class();
		}

		if ( isset( $this->args['class'] ) ) {
			$this->extra_css_class .= ' ' . $this->args['class'];
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 300 );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_enqueue' ), 300 );
	}
	
	/**
	 * Validate field value. For example check file ID and URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string or array $value Field value.
	 *
	 * @return mixed
	 */
	public function validate( $value ) {
		return $value;
	}

	/**
	 * ID getter
	 *
	 * @since 1.0.0
	 *
	 * @return int field id value.
	 */
	public function get_id() {
		return $this->_id;
	}

	/**
	 * Update options array. Needed for presets functionality on the demo website.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $options New options array.
	 */
	public function override_options( $options ) {
		$this->options = $options;
	}

	/**
	 * Set post
	 *
	 * @since 1.0.0
	 *
	 * @param  object $object Post object for metaboxes fields.
	 */
	public function set_post( $object ) {
		if ( is_a( $object, 'WP_Post' ) && 'metabox' === $this->_type ) {
			$this->_post = $object;
		}
	}
	
	/**
	 * Render the field HTML based on the control class.
	 *
	 * @since 1.0.0
	 *
	 * @param object $object Post or Term object for metaboxes fields.
	 * @param bool $preset Current field preset ID.
	 */
	public function render( $object = null, $preset = false ) {
		if ( $preset ) {
			$this->_presets = array( $preset );
		}

		if ( $preset && $this->is_inherit_value() ) {
			$this->extra_css_class .= ' xts-field-disabled';
		}

		if ( is_a( $object, 'WP_Post' ) ) {
			$this->_post = $object;
		} elseif ( is_a( $object, 'WP_Term' ) ) {
			$this->_term = $object;
		}

		$this->before();
		
		echo '<div class="xts-option-control">';
		$this->render_control();
		echo '</div>';

		$this->after();

	}

	/**
	 * Before the control output.
	 *
	 * @since 1.0.0
	 */
	public function before() {
		?>
			<div class="xts-field xts-settings-field <?php echo esc_attr( $this->extra_css_class ); ?>" <?php $this->get_dependency_data_attribute(); ?>>
				<?php if ( ( isset( $this->args['description'] ) && ! empty( $this->args['description'] ) ) || ( isset( $this->args['name'] ) && ! empty( $this->args['name'] ) ) ) : ?>
					<div class="xts-option-title">
						<label>
							<span>
								<?php if ( isset( $this->args['name'] ) && ! empty( $this->args['name'] ) ) : ?>
									<?php echo $this->args['name']; // phpcs:ignore ?>
								<?php endif; ?>
							</span>

							<?php if ( false !== $this->_presets && 'notice' !== $this->args['type'] && isset( $_GET['preset'] ) ) : // phpcs:ignore ?>
								<div class="xts-inherit-checkbox-wrapper">
									Inherit <input type="checkbox" <?php checked( true, $this->is_inherit_value() ); ?> data-name="<?php echo esc_attr( $this->args['id'] ); ?>" value="1">
								</div>
							<?php endif; ?>
						</label>

						<?php if ( isset( $this->args['description'] ) && ! empty( $this->args['description'] ) ) : ?>
							<p class="xts-field-description"><?php echo $this->args['description']; // phpcs:ignore ?></p>
						<?php endif; ?>
					</div>
				<?php endif; ?>
		<?php
	}

	/**
	 * Set field's presets IDs.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $id Presets Ids.
	 */
	public function set_presets( $id ) {
		$this->_presets = $id;
	}

	/**
	 * Set inherit value flag.
	 *
	 * @since 1.0.0
	 *
	 * @param  boolean $value Yes or no.
	 */
	public function inherit_value( $value ) {
		$this->_inherit_value = $value;
	}

	/**
	 * Inherit value flag getter.
	 *
	 * @since 1.0.0
	 */
	public function is_inherit_value() {
		return $this->_inherit_value;
	}

	/**
	 * Echo dependency data attribute.
	 *
	 * @since 1.0.0
	 */
	private function get_dependency_data_attribute() {
		if ( ! isset( $this->args['requires'] ) ) {
			return;
		}

		$data = '';

		foreach ( $this->args['requires'] as $dependency ) {
			if ( is_array( $dependency['value'] ) ) {
				$dependency['value'] = implode( ',', $dependency['value'] );
			}
			$data .= $dependency['key'] . ':' . $dependency['compare'] . ':' . $dependency['value'] . ';';
		}

		echo 'data-dependency="' . esc_attr( $data ) . '"';
	}

	/**
	 * Get dependency class.
	 *
	 * @since 1.0.0
	 */
	private function dependency_class() {
		if ( ! isset( $this->args['requires'] ) ) {
			return;
		}

		$shown = true;

		foreach ( $this->args['requires'] as $dependency ) {
			if ( $shown == false ) {
				continue;
			}

			switch ( $dependency['compare'] ) {
				case 'equals':
					if ( isset( $this->options[ $dependency['key'] ] ) ) {
						if ( is_array( $dependency['value'] ) ) {
							$shown = in_array( $this->options[ $dependency['key'] ], $dependency['value'] );
						} else {
							$shown = $this->options[ $dependency['key'] ] == $dependency['value'];
						}
					}
				break;
				case 'not_equals':
					if ( isset( $this->options[ $dependency['key'] ] ) ) {
						if ( is_array( $dependency['value'] ) ) {
							$shown = ! in_array( $this->options[ $dependency['key'] ], $dependency['value'] );
						} else {
							$shown = $this->options[ $dependency['key'] ] != $dependency['value'];
						}
					}
				break;
			}
		}

		return ( $shown ) ? 'xts-shown' : 'xts-hidden';
	}

	/**
	 * After the control output.
	 *
	 * @since 1.0.0
	 */
	public function after() {
		?>
			</div>
		<?php
	}
	
	/**
	 * Get input name for form tags like input, textarea etc.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $subkey Subkey for array fields.
	 * @param bool $subkey2 Subkey for array fields. Second level.
	 * @param bool $subkey3 Subkey for array fields. Third level.
	 *
	 * @return string input field name.
	 */
	public function get_input_name( $subkey = false, $subkey2 = false, $subkey3 = false ) {
		$name = 'xts-' . $this->opt_name . '-options';

		$name .= '[' . $this->args['id'] . ']';

		if ( 'metabox' === $this->_type ) {
			$name = $this->args['id'];
		}

		if ( false !== $subkey ) {
			$name .= '[' . $subkey . ']';
		}

		if ( false !== $subkey2 ) {
			$name .= '[' . $subkey2 . ']';
		}

		if ( false !== $subkey3 ) {
			$name .= '[' . $subkey3 . ']';
		}

		return $name;
	}
	
	/**
	 * Get field value from options array or from post meta data.
	 *
	 * @since 1.0.0
	 *
	 * @param bool $subkey Subkey for array fields.
	 *
	 * @return mixed Field value.
	 */
	public function get_field_value( $subkey = false ) {
		$val = '';

		$object = $this->_post ? $this->_post : $this->_term;

		if ( 'metabox' === $this->_type && ! is_null( $object ) ) {
			$object_id = $this->_post ? $this->_post->ID : $this->_term->term_id;
			$val       = get_metadata( $this->_object, $object_id, $this->get_input_name(), true );
		} elseif ( false !== $this->_presets ) {
			foreach ( $this->_presets as $preset_id ) {
				if ( isset( $this->options[ $preset_id ] ) && isset( $this->options[ $preset_id ][ $this->args['id'] ] ) ) {
					$val = $this->options[ $preset_id ][ $this->args['id'] ];
				}
			}

			if ( empty( $val ) && '0' !== $val ) {
				$val = $this->options[ $this->args['id'] ];
			}
		} elseif ( isset( $this->options[ $this->args['id'] ] ) ) {
			$val = $this->options[ $this->args['id'] ];
		}

		// Single metadata value, or array of values. If the $meta_type or $object_id parameters are invalid, false is returned. If the meta value isn't set, an empty string or array is returned, respectively.
		if ( 'metabox' === $this->_type && empty( $val ) && '0' !== $val ) {
			$val = isset( $this->args['default'] ) ? $this->args['default'] : '';
		}

		$val = $this->validate( $val );

		if ( $subkey ) {
			return isset( $val[ $subkey ] ) ? $val[ $subkey ] : '';
		}

		if ( isset( $val['{{index}}'] ) ) {
			unset( $val['{{index}}'] );
		}

		return $val;
	}

	/**
	 * Get field options array. For select or buttons set field type.
	 *
	 * @since 1.0.0
	 *
	 * @return array Field options array.
	 */
	public function get_field_options() {
		if ( ! isset( $this->args['options'] ) ) {
			return array();
		}

		return $this->args['options'];
	}

	/**
	 * Enqueue required scripts and styles for controls.
	 *
	 * @since 1.0.0
	 */
	public function enqueue() {}

	/**
	 * Enqueue required scripts and styles on frontend.
	 *
	 * @since 1.0.0
	 */
	public function frontend_enqueue() {}


	/**
	 * Output field's css code on frontend based on the control and its value.
	 *
	 * @since 1.0.0
	 */
	public function css_output() {}
	
	
	/**
	 * Sanitize field and its value before saving.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value Field value string.
	 *
	 * @return mixed.
	 */
	public function sanitize( $value ) {
		$sanitization = new Sanitize( $this, $value );

		return $sanitization->sanitize();
	}
}
