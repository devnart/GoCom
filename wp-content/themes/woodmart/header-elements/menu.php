<?php
$menu_style = ( $params['menu_style'] ) ? $params['menu_style'] : 'default';
$location   = 'main-menu';
$classes    = 'text-' . $params['menu_align'];

if ( 'bordered' === $params['menu_style'] ) {
	$classes .= ' wd-full-height';
}
$classes .= woodmart_get_old_classes( ' navigation-style-' . $menu_style );
?>
<div class="wd-header-nav wd-header-secondary-nav <?php echo esc_attr( $classes ); ?>" role="navigation">
	<?php
	if ( wp_get_nav_menu_object( $params['menu_id'] ) && wp_get_nav_menu_items( $params['menu_id'] ) ) {
		wp_nav_menu(
			array(
				'container'  => '',
				'menu'       => $params['menu_id'],
				'menu_class' => 'menu wd-nav wd-nav-secondary wd-style-' . $menu_style,
				'walker'     => new WOODMART_Mega_Menu_Walker(),
			)
		);
	}
	?>
</div><!--END MAIN-NAV-->
