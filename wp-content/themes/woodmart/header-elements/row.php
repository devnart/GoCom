<?php

	$class .= 'whb-' . $id;
	$inner_class = 'whb-' . $id . '-inner';
	$class .= ( $params['sticky'] ) ? ' whb-sticky-row' : ' whb-not-sticky-row';
	$class .= ( $this->has_background($params) ) ? ' whb-with-bg' : ' whb-without-bg';
	if( $this->has_border($params) ) {
		$class .= ( isset( $params['border']['value']['applyFor'] ) ) ? ' whb-border-' . $params['border']['value']['applyFor'] : ' whb-border-fullwidth';
	} else {
		$class .= ' whb-without-border';
	}
	$class .=  ' whb-color-' . $params['color_scheme'];
	$class .=  ' whb-flex-' . $params['flex_layout'];
	$class .= ( $params['hide_desktop'] ) ? ' whb-hidden-desktop' : '';
	$class .= ( $params['hide_mobile'] ) ? ' whb-hidden-mobile' : '';
	$class .= ( $params['shadow'] ) ? ' whb-with-shadow' : '';
	if( ! $children ) return;

 ?>

<div class="whb-row <?php echo esc_attr( $class ); ?>">
	<div class="container">
		<div class="whb-flex-row <?php echo esc_attr( $inner_class ); ?>">
			<?php echo apply_filters( 'woodmart_header_builder_row_children', $children ); ?>
		</div>
	</div>
</div>
