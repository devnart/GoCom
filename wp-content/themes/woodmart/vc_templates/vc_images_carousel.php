<?php
$output = $title = $onclick = $custom_links = $img_size = $custom_links_target = $images = $el_class = $partial_view = '';
$mode = $slides_per_view = $wrap = $autoplay = $hide_pagination_control = $hide_prev_next_buttons = $speed = '';
extract( shortcode_atts( array(
	'title' => '',
	'onclick' => 'link_image',
	'custom_links' => '',
	'custom_links_target' => '',
	'img_size' => 'thumbnail',
	'images' => '',
	'el_class' => '',
	'mode' => 'horizontal',
	'slides_per_view' => '1',
	'wrap' => '',
	'autoplay' => '',
	'hide_pagination_control' => '',
	'hide_prev_next_buttons' => '',
	'speed' => '5000',
	'scroll_per_page' => 'yes',
	'partial_view' => ''
), $atts ) );
$gal_images = '';
$link_start = '';
$link_end = '';
$el_start = '';
$el_end = '';
$slides_wrap_start = '';
$slides_wrap_end = '';
$pretty_rand = $onclick == 'link_image' ? rand() : '';

if ( $onclick == 'link_image' ) {
	wp_enqueue_script( 'prettyphoto' );
	wp_enqueue_style( 'prettyphoto' );
}

$el_class = $this->getExtraClass( $el_class );

if ( $images == '' ) $images = '-1,-2,-3';

if ( $onclick == 'custom_link' ) {
	$custom_links = explode( ',', $custom_links );
}

$images = explode( ',', $images );
$i = - 1;
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_images_carousel wpb_content_element' . $el_class . ' vc_clearfix', $this->settings['base'], $atts );
$carousel_id = 'vc_images-carousel-' . WPBakeryShortCode_VC_images_carousel::getCarouselIndex();

$custom_sizes = apply_filters( 'woodmart_vc_carousel_custom_sizes', false );

?>
<div class="<?php echo apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->settings['base'], $atts ) ?>">
	<div class="wpb_wrapper">
		<?php echo  wpb_widget_title( array( 'title' => $title, 'extraclass' => 'wpb_gallery_heading' ) ) ?>
		<div id="<?php echo esc_attr( $carousel_id ); ?>" class="vc_slide vc_images_carousel">

			<!-- Wrapper for slides -->
			<div class="owl-carousel <?php echo woodmart_owl_items_per_slide( $slides_per_view, array(), false, false, $custom_sizes ); ?>">
						<?php foreach ( $images as $attach_id ): ?>
							<?php
								$i ++;
								if ( $attach_id > 0 ) {
									$post_thumbnail = wpb_getImageBySize( array( 'attach_id' => $attach_id, 'thumb_size' => $img_size ) );
								} else {
									$post_thumbnail = array();
									$post_thumbnail['thumbnail'] = '<img src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
									$post_thumbnail['p_img_large'][0] = vc_asset_url( 'vc/no_image.png' );
								}
								$thumbnail = $post_thumbnail['thumbnail'];
							?>
							<div class="owl-carousel-item ">
								<div class="owl-carousel-item-inner">
									<?php if ( $onclick == 'link_image' ): ?>
									<?php $p_img_large = $post_thumbnail['p_img_large']; ?>
									<a class="prettyphoto"
									   href="<?php echo esc_url($p_img_large[0]); ?>" <?php echo ' rel="prettyPhoto[rel-' . $pretty_rand . ']"' ?>>
										<?php echo apply_filters( 'vc_images_carousel_thumbnail', $thumbnail ); ?>
									</a>
									<?php elseif ( $onclick == 'custom_link' && isset( $custom_links[$i] ) && $custom_links[$i] != '' ): ?>
									<a
									  href="<?php echo esc_url( $custom_links[$i] ); ?>"<?php echo ( ! empty( $custom_links_target ) ? ' target="' . $custom_links_target . '"' : '' ) ?>>
										<?php echo apply_filters( 'vc_images_carousel_thumbnail', $thumbnail ); ?>
									</a>
									<?php else: ?>
										<?php echo apply_filters( 'vc_images_carousel_thumbnail', $thumbnail ); ?>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

<?php 
	$func_name = 'carousel_' . $carousel_id;
	$func_name = function() use( $carousel_id, $speed, $slides_per_view, $autoplay, $hide_pagination_control, $hide_prev_next_buttons, $scroll_per_page, $wrap, $custom_sizes ) {
		
		$items = woodmart_get_owl_items_numbers( $slides_per_view, false, $custom_sizes );

		wp_add_inline_script( 'woodmart-theme', '
			jQuery( document ).ready(function( $ ) {
				$("#' . esc_js( $carousel_id ) . ' .owl-carousel").owlCarousel({
		            rtl: $("body").hasClass("rtl"),
		            items: ' . esc_js( $items["desktop"] ) . ', 
		            responsive: {
		            	1025: {
		            		items: ' . esc_js( $items["desktop"] ) . '
		            	},
		            	769: {
		            		items: ' . esc_js( $items["tablet_landscape"] ) . '
						},
						577: {
		            		items: ' . esc_js( $items["tablet"] ) . '
		            	},
		            	0: {
		            		items: ' . esc_js( $items["mobile"] ) . '
		            	}
		            },
		            autoplay: ' . ( ($autoplay == "yes") ? "true" : "false" ). ',
		            autoplayTimeout: ' . esc_js( $speed ) . ',
		            dots: ' . ( ($hide_pagination_control == "yes") ? "false" : "true" ). ',
		            nav: ' . ( ($hide_prev_next_buttons == "yes") ? "false" : "true") . ',
		            slideBy:  ' .( ($scroll_per_page == "yes") ? '"page"' : 1 ). ',
		            navText:false,
		            navClass : [\'owl-prev wd-btn-arrow\', \'owl-next wd-btn-arrow\'],
		            loop: ' . ( ($wrap == "yes") ? "true" : "false" ). ',
				});
			});
		', 'after' );
	};

	$func_name( $carousel_id, $speed, $slides_per_view, $autoplay, $hide_pagination_control, $hide_prev_next_buttons, $scroll_per_page, $wrap, $custom_sizes );
 ?>
