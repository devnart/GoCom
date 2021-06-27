<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
 * ------------------------------------------------------------------------------------------------
 * Abstract class for all elements used in the builder. This class is used both on backend and
 * on the frontend.
 * ------------------------------------------------------------------------------------------------
 */

if ( ! class_exists( 'WOODMART_HB_Element' ) ) {
	abstract class WOODMART_HB_Element {

		public $args = array();

		public $template_name;

		public $vc_element = false;

		public function __construct() {
			// if( $this->vc_element && function_exists('vc_mapper') ) {
			// vc_mapper()->init();
			if ( $this->vc_element && is_admin() ) {
				$this->visual_composer_to_header();
			} else {
				$this->map();
			}
		}

		public function get_header_options() {
			return WOODMART_Header_Builder::get_instance()->structure->get_header_options();
		}

		public function get_args() {
			return $this->args;
		}

		public function render( $el, $children = '' ) {

			if ( $this->vc_element ) {
				$args = $this->_parse_vc_args( $el );
			} else {
				$args = $this->_parse_args( $el );
			}

			extract( $args );

			$path = '/header-elements/' . $this->template_name . '.php';

			$located = '';

			if ( file_exists( get_stylesheet_directory() . $path ) ) {
				$located = get_stylesheet_directory() . $path;
			} elseif ( file_exists( get_template_directory() . $path ) ) {
				$located = get_template_directory() . $path;
			}

			if ( file_exists( $located ) ) {
				require $located;
			}
		}

		private function _parse_args( $el ) {

			$a = array();

			foreach ( $el['params'] as $arg ) {
				$a[ $arg['id'] ] = $arg['value'];
			}

			unset( $el['content'] );

			$el['params'] = $a;

			return $el;
		}

		private function _parse_vc_args( $el ) {

			$a = array();

			foreach ( $el['params'] as $arg ) {

				if ( $arg['id'] == 'link' ) {

					$url = '';

					if ( isset( $arg['value'] ) && isset( $arg['value']['url'] ) ) {
						$url = $arg['value']['url'];
					}

					$value = 'url:' . rawurlencode( $url );

					if ( isset( $arg['value'] ) && isset( $arg['value']['blank'] ) && $arg['value']['blank'] ) {
						$value .= '|target:_blank';
					}

					$a[ $arg['id'] ] = $value;
				} elseif ( $arg['type'] == 'switcher' ) {
					$a[ $arg['id'] ] = $arg['value'] ? 'yes' : 'no';
				} elseif ( $arg['type'] == 'image' ) {
					$a[ $arg['id'] ] = ( isset( $arg['value']['id'] ) ) ? $arg['value']['id'] : '';
				} else {
					$a[ $arg['id'] ] = $arg['value'];
				}
			}

			unset( $el['content'] );

			$el['params'] = $a;

			return $el;

		}

		public function has_background( $params ) {
			return( isset( $params['background'] ) && ( isset( $params['background']['background-color'] ) || isset( $params['background']['background-image'] ) ) );
		}

		public function has_border( $params ) {
			return( isset( $params['border'] ) && isset( $params['border']['width'] ) && (int) $params['border']['width'] > 0 );
		}

		public function visual_composer_to_header() {
			$vc_element = 'woodmart_get_' . $this->vc_element . '_shortcode_args';
			$vc_args    = $vc_element();

			$old_args = $this->args;

			$this->args = array(
				'type'            => $this->_get_type_from_vc( $this->vc_element ),
				'title'           => $vc_args['name'],
				'text'            => $old_args['text'],
				'icon'            => $old_args['icon'],
				'editable'        => true,
				'container'       => false,
				'edit_on_create'  => true,
				'drag_target_for' => array(),
				'drag_source'     => 'content_element',
				'removable'       => true,
				'addable'         => true,
				'params'          => $this->_get_params_from_vc( $vc_args['params'] ),
			);

		}

		private function _get_params_from_vc( $params ) {
			$new_params = array();

			if ( empty( $params ) ) {
				return array();
			}

			foreach ( $params as $key => $param ) {
				if ( ! $param || $param['type'] == 'woodmart_title_divider' || $param['type'] == 'woodmart_css_id' ) {
					continue;
				}
				$new_param = $this->_get_param_from_vc( $param );
				if ( $new_param ) {
					$new_params[ $param['param_name'] ] = $new_param;
				}
			}

			return $new_params;
		}

		private function _get_param_from_vc( $param ) {
			$new_param = array();

			$new_param['id']    = $param['param_name'];
			$new_param['title'] = isset( $param['heading'] ) ? $param['heading'] : '';
			$new_param['value'] = '';
			$dropdown_params    = array( 'dropdown', 'woodmart_dropdown', 'woodmart_button_set', 'woodmart_image_select' );

			if ( isset( $param['description'] ) ) {
				$new_param['description'] = $param['description'];
			}
			if ( isset( $param['group'] ) ) {
				$new_param['tab'] = $param['group'];
			} else {
				$new_param['tab'] = 'General';
			}

			if ( isset( $param['dependency'] ) ) {
				if ( isset( $param['dependency']['value_not_equal_to'] ) ) {
					$new_param['requires'] = array(
						$param['dependency']['element'] => array(
							'comparison' => 'not_equal',
							'value'      => $param['dependency']['value_not_equal_to'],
						),
					);
				}
				if ( isset( $param['dependency']['value'] ) && is_array( $param['dependency']['value'] ) ) {
					$new_param['requires'] = array(
						$param['dependency']['element'] => array(
							'comparison' => 'equal',
							'value'      => $param['dependency']['value'][0],
						),
					);
				}
			}

			switch ( $param['type'] ) {
				case 'textfield':
					$new_param['type'] = 'text';
					break;

				case 'vc_link':
					$new_param['type'] = 'link';
					break;

				case in_array( $param['type'], $dropdown_params ):
					$options           = $this->_get_options_from_vc( $param['value'] );
					$first_option      = reset( $options );
					$new_param['type'] = 'select';
					if ( count( $options ) < 6 ) {
						$new_param['type'] = 'selector';
					}
					$new_param['options'] = $options;
					$new_param['value']   = $first_option['value'];
					break;

				case 'checkbox':
					$new_param['type']  = 'switcher';
					$new_param['value'] = false;
					break;

				case 'textfield':
					$new_param['type'] = 'text';
					break;

				case 'iconpicker':
					$new_param['type'] = 'text';
					break;

				case 'attach_image':
					$new_param['type'] = 'image';
					break;

				case 'textarea_html':
					$new_param['type'] = 'editor';
					break;

				case 'textarea':
					$new_param['type'] = 'textarea';
					break;

				case 'colorpicker':
					$new_param['type'] = 'color';
					break;

				case 'woodmart_switch':
					$new_param['type'] = 'switcher';
					break;

				case 'woodmart_colorpicker':
					$new_param['type'] = 'color';
					break;

				default:
					$new_param = false;
					break;
			}

			return $new_param;
		}

		private function _get_options_from_vc( $options ) {
			$new_options = array();

			foreach ( $options as $name => $value ) {
				$new_options[ $value ] = array(
					'label' => $name,
					'value' => $value,
				);
			}

			return $new_options;
		}

		private function _get_type_from_vc( $type ) {
			switch ( $type ) {
				case 'woodmart_button':
					return 'button';
				break;

				case 'woodmart_info_box':
					return 'infobox';
				break;

				case 'social_buttons':
					return 'social';
				break;

			}
		}

		public function get_menu_options() {
			$array = array();

			$menus = wp_get_nav_menus();

			foreach ( $menus as $menu ) {
				$array[ $menu->slug ] = array(
					'label' => $menu->name,
					'value' => $menu->slug,
				);
			}

			return $array;
		}

	}

}
