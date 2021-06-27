<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	WPBakery Social buttons element
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_Social' ) ) {
	class WOODMART_HB_Social extends WOODMART_HB_Element {

		public function __construct() {

			$this->args = array(
				'text' => esc_html__( 'Social links icons', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-social.svg',
			);	

			$this->vc_element = 'social_buttons';
			parent::__construct();
			$this->template_name = 'social';
		}

		public function map() {}

	}
}
