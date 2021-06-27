<?php
/**
 * Upload your custom fonts.
 *
 * @package xts
 */

namespace XTS\Options\Controls;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

use XTS\Options\Field;

/**
 * Custom fonts control class.
 */
class Custom_Fonts extends Field {
	/**
	 * Default field value.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $_default_value = array(
		'font-name'   => '',
		'font-weight' => 400,
		'font-woff'   => array(
			'url' => '',
			'id'  => '',
		),
		'font-woff2'  => array(
			'url' => '',
			'id'  => '',
		),
		'font-ttf'    => array(
			'url' => '',
			'id'  => '',
		),
		'font-svg'    => array(
			'url' => '',
			'id'  => '',
		),
		'font-eot'    => array(
			'url' => '',
			'id'  => '',
		),
	);

	/**
	 * Contruct the object.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args     Field args array.
	 * @param array  $options  Options from the database.
	 * @param string $type     Field type.
	 * @param string $object   Object.
	 */
	public function __construct( $args, $options, $type = 'options', $object = 'post' ) {
		parent::__construct( $args, $options, $type, $object );

		$this->args = $args;
	}

	/**
	 * Displays the field control HTML.
	 *
	 * @since 1.0.0
	 */
	public function render_control() {
		$value = $this->get_field_value();

		// get last index from the array.
		$key = 0;
		if ( is_array( $value ) ) {
			end( $value );
			$key = key( $value );
		}

		?>
			<div id="<?php echo esc_attr( $this->get_id() ); ?>" data-id="<?php echo esc_attr( $this->get_id() ); ?>" data-key="<?php echo esc_attr( $key ); ?>" class="xts-custom-fonts">

				<div class="xts-custom-fonts-sections">
					<?php if ( is_array( $value ) && count( $value ) > 0 ) : ?>
						<?php foreach ( $value as $index => $value ) : ?>
							<?php $this->render_section( $index ); ?>
						<?php endforeach; ?>
					<?php else : ?>
						<?php $this->render_section( 0 ); ?>
					<?php endif; ?>
				</div>

				<?php $this->section_template( false, $this->_default_value ); ?>

				<div class="xts-custom-fonts-btn-add xts-inline-btn xts-inline-btn-add"><?php esc_html_e( 'Add font', 'woodmart' ); ?></div>

			</div>
		<?php
	}

	/**
	 * Renders one typography settings section based on index.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $index  Section index.
	 */
	public function render_section( $index ) {
		$default_value = $this->_default_value;
		$value         = $this->get_field_value();
		$section_value = array();

		if ( '{{index}}' === $index ) {
			return;
		}

		if ( isset( $value[ $index ] ) ) {
			$section_value = wp_parse_args( $value[ $index ], $default_value );
		} else {
			$section_value = $default_value;
		}

		$this->section_template( $index, $section_value );
	}

	/**
	 * Displays the section template.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $index  Section index.
	 * @param array   $section_value  Section data.
	 */
	public function section_template( $index, $section_value ) {
		$hide_class = false === $index ? ' xts-custom-fonts-template hide' : '';
		$index      = false === $index ? '{{index}}' : $index;

		$font_weight = array(
			esc_html__( 'Ultra-Light 100', 'woodmart' ) => 100,
			esc_html__( 'Light 200', 'woodmart' )       => 200,
			esc_html__( 'Book 300', 'woodmart' )        => 300,
			esc_html__( 'Normal 400', 'woodmart' )      => 400,
			esc_html__( 'Medium 500', 'woodmart' )      => 500,
			esc_html__( 'Semi-Bold 600', 'woodmart' )   => 600,
			esc_html__( 'Bold 700', 'woodmart' )        => 700,
			esc_html__( 'Extra-Bold 800', 'woodmart' )  => 800,
			esc_html__( 'Ultra-Bold 900', 'woodmart' )  => 900,
		);

		$font_name = esc_html__( 'Custom font', 'woodmart' );
		if ( $section_value['font-name'] && $section_value['font-weight'] ) {
			$font_name .= ' - ' . $section_value['font-name'] . ' (' . $section_value['font-weight'] . ')';
		}

		?>

			<div class="xts-custom-fonts-section<?php echo esc_attr( $hide_class ); ?>" data-id="<?php echo esc_attr( $this->get_id() ); ?>-<?php echo esc_attr( $index ); ?>">
				<h3 class="xts-custom-fonts-title"><?php echo esc_html( $font_name ); ?></h3>
				<div class="xts-custom-fonts-field">
					<h4 class="xts-custom-fonts-label">
						<?php esc_html_e( 'Font name', 'woodmart' ); ?>
					</h4>
					<input type="text" name="<?php echo esc_attr( $this->get_input_name( $index, 'font-name' ) ); ?>" value="<?php echo esc_attr( $section_value['font-name'] ); ?>">
					<p><?php esc_html_e( 'Enter your name with letters and spacing only. It will be used in a list of fonts under the Typography section. For example: Indie Flower', 'woodmart' ); ?></p>
				</div>

				<div class="xts-custom-fonts-field">
					<h4 class="xts-custom-fonts-label">
						<?php esc_html_e( 'Font name', 'woodmart' ); ?>
					</h4>
					<select name="<?php echo esc_attr( $this->get_input_name( $index, 'font-weight' ) ); ?>">
						<?php foreach ( $font_weight as $key => $value ) : ?>
							<?php
								$selected = $section_value['font-weight'] == $value ? 'selected' : '';
							?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php echo esc_attr( $selected ); ?>>
								<?php echo esc_html( $key ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>

				<?php foreach ( $this->args['fonts'] as $font ) : ?>
					<?php
						/* translators: 1: Font name */
						$title  = sprintf( __( 'Font (.%s)', 'woodmart' ), esc_attr( $font ) );
						$values = $section_value[ 'font-' . $font ];
						$name   = $this->get_input_name( $index, 'font-' . $font );
					?>
					<?php $this->upload_template( $title, $values, $name ); ?>
				<?php endforeach; ?>

				<div class="xts-custom-fonts-btn-remove xts-inline-btn xts-inline-btn-remove"><?php esc_html_e( 'Remove', 'woodmart' ); ?></div>

			</div>
		<?php
	}

	/**
	 * Displays the upload field template.
	 *
	 * @since 1.0.0
	 *
	 * @param string $title Field title.
	 * @param array  $values Field values.
	 * @param array  $name Field name.
	 */
	public function upload_template( $title, $values, $name ) {
        $url = '';

        if ( isset( $values['id'] ) && $values['id'] ) {
            $url = wp_get_attachment_url( $values['id'] );
        } elseif ( is_array( $values ) ) {
            $url = $values['url'];
        }

		?>
			<div class="xts-custom-fonts-field xts-upload-control">
				<h4 class="xts-custom-fonts-title"><?php echo esc_html( $title ); ?></h4>
				<div class="xts-upload-preview">
					<input type="text" class="xts-upload-preview-input" disabled value="<?php echo esc_url( $url ); ?>">
				</div>
				<div class="xts-upload-btns">
					<button class="xts-btn xts-upload-btn"><?php esc_html_e( 'Upload', 'woodmart' ); ?></button>
					<button class="xts-btn xts-remove-upload-btn<?php echo ( isset( $url ) && ! empty( $url ) ) ? ' xts-active' : ''; ?>"><?php esc_html_e( 'Remove', 'woodmart' ); ?></button>

					<input type="hidden" class="xts-upload-input-url" name="<?php echo esc_attr( $name . '[url]' ); ?>" value="<?php echo esc_attr( $values['url'] ); ?>" />
					<input type="hidden" class="xts-upload-input-id" name="<?php echo esc_attr( $name . '[id]' ); ?>" value="<?php echo esc_attr( $values['id'] ); ?>" />
				</div>
			</div>
		<?php
	}
}


