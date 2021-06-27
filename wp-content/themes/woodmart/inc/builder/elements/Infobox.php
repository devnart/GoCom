<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	WPBakery Button element
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Infobox' ) ) {
	class WOODMART_HB_Infobox extends WOODMART_HB_Element {

		public function __construct() {

			$this->args = array(
				'text' => esc_html__( 'Text with icon', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-infobox.svg',
			);	

			$this->vc_element = 'woodmart_info_box';
			parent::__construct();
			$this->template_name = 'info-box';
		}

		public function map() { }
	}
}
