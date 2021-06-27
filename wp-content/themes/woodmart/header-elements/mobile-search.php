<?php

$extra_class = '';
$icon_type   = $params['icon_type'];

if ( $icon_type == 'custom' ) {
	$extra_class .= ' wd-tools-custom-icon';
}

$extra_class .= woodmart_get_old_classes( ' search-button' );

$settings = whb_get_settings();

if ( $params['display'] == 'form' ) {
	$search_style = isset( $params['search_style'] ) ? $params['search_style'] : 'default';
	woodmart_search_form(
		array(
			'ajax'                   => $settings['search']['ajax'],
			'post_type'              => $settings['search']['post_type'],
			'icon_type'              => $icon_type,
			'search_style'           => $search_style,
			'custom_icon'            => $params['custom_icon'],
			'wrapper_custom_classes' => 'wd-header-search-form-mobile' . woodmart_get_old_classes( ' woodmart-mobile-search-form' ),
		)
	);
	return;
}

woodmart_enqueue_js_script( 'mobile-search' );

?>

<div class="wd-header-search wd-tools-element wd-header-search-mobile<?php echo esc_attr( $extra_class ); ?>">
	<a href="#" rel="nofollow noopener">
		<span class="wd-tools-icon<?php echo woodmart_get_old_classes( ' search-button-icon' ); ?>">
			<?php
			if ( $icon_type == 'custom' ) {
				echo whb_get_custom_icon( $params['custom_icon'] );
			}
			?>
		</span>
	</a>
</div>
