<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );}

/**
* ------------------------------------------------------------------------------------------------
* Mega Menu widget
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'woodmart_shortcode_mega_menu' ) ) {
	function woodmart_shortcode_mega_menu( $atts, $content ) {
		$output = $title_html = '';
		extract(
			shortcode_atts(
				array(
					'title'                 => '',
					'nav_menu'              => '',
					'style'                 => '',
					'color'                 => '',
					'woodmart_color_scheme' => 'light',
					'el_class'              => '',
					'woodmart_css_id'       => '',
				),
				$atts
			)
		);

		$class = $el_class;

		if ( ! $woodmart_css_id ) {
			$woodmart_css_id = uniqid();
		}
		$widget_id = 'wd-' . $woodmart_css_id;

		ob_start(); ?>

			<div id="<?php echo esc_attr( $widget_id ); ?>" class="widget_nav_mega_menu <?php echo esc_attr( $class ); ?>">

				<?php if ( $title ) : ?>
					<h5 class="widget-title color-scheme-<?php echo esc_attr( $woodmart_color_scheme ); ?>">
						<?php echo wp_kses( $title, woodmart_get_allowed_html() ); ?>
					</h5>
				<?php endif; ?>

				<?php
					wp_nav_menu(
						array(
							'fallback_cb' => '',
							'container'   => '',
							'menu'        => $nav_menu,
							'menu_class'  => 'menu wd-nav wd-nav-vertical' . woodmart_get_old_classes( ' vertical-navigation' ),
							'walker'      => new WOODMART_Mega_Menu_Walker(),
						)
					);
				?>

				<?php
				if ( $color && ! woodmart_is_css_encode( $color ) ) {
					$css = '#' . esc_attr( $widget_id ) . ' .widget-title {';
					$css .= 'background-color: ' . esc_attr( $color ) . ';';
					$css .= '}';

					wp_add_inline_style( 'woodmart-inline-css', $css );
				}
				?>
			</div>
		<?php
		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}
}
