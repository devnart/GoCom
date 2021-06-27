<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Array of specific optinos
 * ------------------------------------------------------------------------------------------------
 */

$rules = array(
	'product_tabs_location' => array(
		'will-be' => 'standard',
		'if' => 'product_tabs_layout',
		'in_array' => array( 'tabs' )
	),
);

if( ! is_user_logged_in() ) {
	$rules['promo_popup'] = array(
		'will-be' => false,
		'if' => 'maintenance_mode',
		'in_array' => array(true)
	);
}

return apply_filters( 'woodmart_get_specific_options', $rules );