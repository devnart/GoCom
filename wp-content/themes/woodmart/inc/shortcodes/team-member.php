<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
* ------------------------------------------------------------------------------------------------
* Team member shortcode
* ------------------------------------------------------------------------------------------------
*/
if( ! function_exists( 'woodmart_shortcode_team_member' ) ) {
	function woodmart_shortcode_team_member( $atts, $content = "" ) {
		$output = $title = $classes = '';
		extract( shortcode_atts( array(
	        'align' => 'left',
	        'name' => '',
	        'position' => '',
	        'twitter' => '',
	        'facebook' => '',
	        'skype' => '',
	        'linkedin' => '',
	        'instagram' => '',
	        'image' => '',
	        'img_size' => '270x170',
			'style' => 'default', // circle colored
			'size' => 'default', // circle colored
			'form' => 'circle',
			'woodmart_color_scheme' => 'dark',
			'layout' => 'default',
			'css_animation' => 'none',
			'el_class' => ''
		), $atts ) );

		$classes .= ' member-layout-' . $layout;
		$classes .= ' color-scheme-' . $woodmart_color_scheme;
		$classes .= woodmart_get_css_animation( $css_animation );
		$classes .= ( $el_class ) ? ' ' . $el_class : '';

		$img_id = preg_replace( '/[^\d]/', '', $image );
		
		$img = '';
		
		if ( function_exists( 'wpb_getImageBySize' ) ) {
			$img = wpb_getImageBySize( array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'team-member-avatar-image' ) );
		}

		woodmart_enqueue_inline_style( 'social-icons' );
		woodmart_enqueue_inline_style( 'team-member' );

	    $socials = '';

        if ($linkedin != '' || $twitter != '' || $facebook != '' || $skype != '' || $instagram != '') {
            $socials .= '<div class="member-social"><div class="wd-social-icons' . woodmart_get_old_classes( ' woodmart-social-icons' ) . ' icons-design-' . esc_attr( $style ) . ' icons-size-' . esc_attr( $size ) .' social-form-' . esc_attr( $form ) .'">';
                if ($facebook != '') {
                    $socials .= '<a rel="noopener noreferrer nofollow" class="wd-social-icon social-facebook" href="'.esc_url( $facebook ).'"><span class="wd-icon"></span></a>';
                }
                if ($twitter != '') {
                    $socials .= '<a rel="noopener noreferrer nofollow" class="wd-social-icon social-twitter" href="'.esc_url( $twitter ).'"><span class="wd-icon"></span></a>';
                }
                if ($linkedin != '') {
                    $socials .= '<a rel="noopener noreferrer nofollow" class="wd-social-icon social-linkedin" href="'.esc_url( $linkedin ).'"><span class="wd-icon"></span></a>';
                }
                if ($skype != '') {
                    $socials .= '<a rel="noopener noreferrer nofollow" class="wd-social-icon social-skype" href="'.esc_url( $skype ).'"><span class="wd-icon"></span></a>';
                }
                if ($instagram != '') {
                    $socials .= '<a rel="noopener noreferrer nofollow" class="wd-social-icon social-instagram" href="'.esc_url( $instagram ).'"><span class="wd-icon"></span></a>';
                }
            $socials .= '</div></div>';
        }

	    $output .= '<div class="team-member wd-wpb text-' . esc_attr( $align ) . ' '. esc_attr( $classes ) .'">';

		    if(isset( $img['thumbnail'] ) && $img['thumbnail'] != ''){

	            $output .= '<div class="member-image-wrapper"><div class="member-image">';
	                $output .=  $img['thumbnail'];
	            $output .= '</div></div>';
		    }

	        $output .= '<div class="member-details">';
	            if($name != ''){
	                $output .= '<h4 class="member-name">' . ( $name ) . '</h4>';
	            }
			    if($position != ''){
				    $output .= '<span class="member-position">' . ( $position ) . '</span>';
			    }

			    if($content){
				    $output .= '<div class="member-bio">';
				    $output .= do_shortcode($content);
				    $output .=  '</div>';
			    }

	    		$output .= $socials;

	    	$output .= '</div>';



	    $output .= '</div>';


	    return $output;
	}
}
