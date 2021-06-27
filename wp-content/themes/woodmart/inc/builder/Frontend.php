<?php

if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Frontend class that initiallize current header for the page and generates its structure HTML + CSS
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Frontend' ) ) {
	class WOODMART_HB_Frontend {
		private static $_instance = null;

		public $builder = null;

		private $_element_classes = array();

		private $_structure = array();

		private $_storage;

		public $header = null;

		public function __construct() {
			$this->builder = WOODMART_Header_Builder::get_instance();

			$this->init();
		}

		protected function __clone() {}

		static public function get_instance() {

			if( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		public function init() {
			add_action( 'wp_print_styles', array( $this, 'styles'), 200 );
			add_action( 'init', array( $this, 'get_elements') );
			add_action( 'wp', array( $this, 'print_header_styles' ) );
		}

		public function get_elements() {
			// Fix VC map issue. Load our elements when Visual Composer is loaded
			$this->_element_classes = $this->builder->elements->elements_classes;
		}
		
		/**
		 * Load elements classes list.
		 *
		 * @since 1.0.0
		 */
		public function print_header_styles() {
			$id           = $this->get_current_id();
			$this->header = $this->builder->factory->get_header( $id );
			$styles       = new WOODMART_HB_Styles();
			
			$this->_storage = new WOODMART_Stylesstorage( $this->get_current_id(), 'option', '', false );

			if ( ! $this->_storage->is_css_exists() ) {
				$this->_storage->write( $styles->get_all_css( $this->header->get_structure(), $this->header->get_options() ), true );
			}

			$this->_storage->print_styles();
		}

		public function styles() {
			$id = $this->get_current_id();
			$this->header = $this->builder->factory->get_header( $id );
			$this->_structure = $this->header->get_structure();
			$options = $this->header->get_options();
		}

		public function get_current_id() {
			$id = $this->builder->manager->get_default_header();
			$page_id = woodmart_page_ID();
			$default_header          = woodmart_get_opt( 'default_header' );
			$custom_post_header = woodmart_get_opt( 'single_post_header');
			$custom_portfolio_header = woodmart_get_opt( 'single_portfolio_header');
			$custom_product_header = woodmart_get_opt( 'single_product_header');
			$custom = get_post_meta( $page_id, '_woodmart_whb_header', true );

			if ( $default_header ) {
				$id = $default_header;
			}

			if ( ! empty( $custom_post_header ) && $custom_post_header != 'none' && is_singular( 'post' ) ) {
				$id = $custom_post_header;
			}
			if ( ! empty( $custom_product_header ) && $custom_product_header != 'none' && woodmart_woocommerce_installed() && is_product() ) {
				$id = $custom_product_header;
			}
			if ( ! empty( $custom_portfolio_header ) && $custom_portfolio_header != 'none' && is_singular( 'portfolio' ) ) {
				$id = $custom_portfolio_header;
			}

			if ( ! empty( $custom ) && $custom != 'none' && get_option( 'whb_' . $custom ) ){
				$id = $custom;
			}
			
			return apply_filters( 'woodmart_get_current_header_id', $id );
		}

		public function generate_header() {
			$this->_render_element( $this->_structure );
			do_action('whb_after_header');
		}

		private function _render_element( $el ) {
			$children = '';
			$type     = ucfirst( $el['type'] );

			if ( ! isset( $el['params'] ) ) {
				$el['params'] = array();
			}

			if ( isset( $el['content'] ) && is_array( $el['content'] ) ) {
				if ( wp_is_mobile() && woodmart_get_opt( 'mobile_optimization', 0  ) && isset( $el['desktop_only'] ) ) {
					return;
				}

				ob_start();

				foreach ( $el['content'] as $element ) {
					$this->_render_element( $element );
				}
				$children = ob_get_clean();
			}

			if ( $type == 'Row' && $this->_is_empty_row( $el ) || $type == 'Column' && $this->_is_empty_column( $el ) ) {
				$children = false;
			}

			if ( isset( $this->_element_classes[ $type ] ) ) {
				$obj = $this->_element_classes[ $type ];
				$obj->render( $el, $children );
			}
		}


		private function _is_empty_row( $el ) {
			$isEmpty = true;

			foreach ($el['content'] as $key => $column) {
				if( ! $this->_is_empty_column( $column ) ) $isEmpty = false;
			}

			return $isEmpty;
		}

		private function _is_empty_column( $el ) {
			return empty( $el['content'] );
		}
	}

	$_GLOBALS['woodmart_hb_frontend'] = WOODMART_HB_Frontend::get_instance();

}
