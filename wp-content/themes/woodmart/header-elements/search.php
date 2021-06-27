<?php
	$extra_class = '';
	$count       = ( $params['display'] == 'dropdown' ) ? 20 : 40;
	$icon_type   = $params['icon_type'];

if ( $params['display'] == 'form' ) {
	$search_style = isset( $params['search_style'] ) ? $params['search_style'] : 'default';
	woodmart_search_form(
		array(
			'ajax'                   => $params['ajax'],
			'count'                  => $params['ajax_result_count'],
			'post_type'              => $params['post_type'],
			'show_categories'        => $params['categories_dropdown'],
			'icon_type'              => $icon_type,
			'search_style'           => $search_style,
			'custom_icon'            => $params['custom_icon'],
			'wrapper_custom_classes' => 'wd-header-search-form',
		)
	);
	return;
}

if ( $icon_type == 'custom' ) {
	$extra_class .= ' wd-tools-custom-icon';
}

if ( 'dropdown' === $params['display'] ) {
	$extra_class .= ' wd-event-hover';
}

$extra_class .= woodmart_get_old_classes( ' search-button' );

?>
<div class="wd-header-search wd-tools-element<?php echo esc_attr( $extra_class ); ?>" title="<?php echo esc_attr__( 'Search', 'woodmart' ); ?>">
	<a href="javascript:void(0);">
		<span class="wd-tools-icon<?php echo woodmart_get_old_classes( ' search-button-icon' ); ?>">
			<?php
			if ( $icon_type == 'custom' ) {
				echo whb_get_custom_icon( $params['custom_icon'] );
			}
			?>
		</span>
	</a>
	<?php if ( $params['display'] == 'dropdown' ) : ?>
		<?php
			woodmart_search_form(
				array(
					'ajax'        => $params['ajax'],
					'count'       => $params['ajax_result_count'],
					'post_type'   => $params['post_type'],
					'type'        => 'dropdown',
					'icon_type'   => $icon_type,
					'custom_icon' => $params['custom_icon'],
				)
			);
		?>
	<?php endif ?>
</div>
