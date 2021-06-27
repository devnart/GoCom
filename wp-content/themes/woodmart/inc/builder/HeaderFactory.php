<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Wrapper for our header class instance. CRUD actions
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_HeaderFactory' ) ) {
	class WOODMART_HB_HeaderFactory {

    private $_elements = null;
    private $_list = null;

	public function __construct( $elements, $list ) {
      $this->_elements = $elements;
	  $this->_list = $list;
	}

    public function get_header( $id ) {
      return new WOODMART_HB_Header( $this->_elements, $id );
    }

    public function update_header( $id, $name, $structure, $settings ) {
      
      $header = new WOODMART_HB_Header( $this->_elements, $id );

      $header->set_name( $name );
      $header->set_structure( $structure );
      $header->set_settings( $settings );

      $header->save();

      return $header;
    }

    public function create_new( $id, $name, $structure = false, $settings = false ) {

      $header = new WOODMART_HB_Header( $this->_elements, $id, true );

      if( $structure ) {
	      $header->set_structure( $structure );
      }
      if( $settings ) $header->set_settings( $settings );

      $header->set_name( $name );
      $header->save();

      return $header;

    }

	}

}
