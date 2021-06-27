<?php

$classes = $params['inline'] ? ' wd-inline' : '';
$classes .= woodmart_get_old_classes( ' whb-text-element' );
?>

<div class="wd-header-text set-cont-mb-s reset-last-child <?php echo esc_attr( $params['css_class'] ); ?><?php echo esc_attr( $classes ); ?>"><?php echo do_shortcode($params['content']); ?></div>
