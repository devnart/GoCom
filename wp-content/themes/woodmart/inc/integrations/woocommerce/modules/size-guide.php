<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );


/**
 * ------------------------------------------------------------------------------------------------
 * Actions
 * ------------------------------------------------------------------------------------------------
 */
//Save Edit Table Action
add_action( 'save_post_woodmart_size_guide', 'woodmart_sguide_table_save' );
add_action( 'edit_post_woodmart_size_guide', 'woodmart_sguide_table_save' );

add_action( 'save_post_woodmart_size_guide', 'woodmart_sguide_hide_table_save' );
add_action( 'edit_post_woodmart_size_guide', 'woodmart_sguide_hide_table_save' );

//Save Edit Product Action
add_action( 'save_post', 'woodmart_sguide_dropdown_save' );
add_action( 'edit_post', 'woodmart_sguide_dropdown_save' );

//Add size guide to product page
add_action( 'woocommerce_single_product_summary', 'woodmart_sguide_display', 38 );


//Metaboxes template
if( ! function_exists( 'woodmart_sguide_metaboxes' ) ) {
    function woodmart_sguide_metaboxes( $post ) {

        if ( get_current_screen()->action == 'add' ) {
            $tables = array(
                array( 'Size', 'UK', 'US', 'EU', 'Japan' ),
                array( 'XS', '6 - 8', '4', '34', '7' ),
                array( 'S', '8 -10', '6', '36', '9'  ),
                array( 'M', '10 - 12', '8', '38', '11'  ),
                array( 'L', '12 - 14', '10', '40', '13'  ),
                array( 'XL', '14 - 16', '12', '42', '15'  ),
                array( 'XXL', '16 - 28', '14', '44', '17'  )
            );
        } else {
            $tables = get_post_meta( $post->ID, 'woodmart_sguide' );
            $tables = $tables[0];
        }

        woodmart_sguide_table_template( $tables );
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Table
 * ------------------------------------------------------------------------------------------------
 */
//Table template
if( ! function_exists( 'woodmart_sguide_table_template' ) ) {
    function woodmart_sguide_table_template( $tables ) {
        ?>
        <textarea class="woodmart-sguide-table-edit" name="woodmart-sguide-table" style="display:none;">
            <?php echo json_encode( $tables ); ?>
        </textarea>
        <?php
    }
}

//Save table action
if( ! function_exists( 'woodmart_sguide_table_save' ) ) {
    function woodmart_sguide_table_save( $post_id ){

        if ( !isset( $_POST['woodmart-sguide-table'] ) ) return;

        $size_guide = json_decode( stripslashes( $_POST['woodmart-sguide-table'] ) );

        update_post_meta( $post_id, 'woodmart_sguide', $size_guide );
        
        //Save product category
        woodmart_sguide_save_category( $post_id );
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Dropdown
 * ------------------------------------------------------------------------------------------------
 */
//Dropdown template
if( ! function_exists( 'woodmart_sguide_dropdown_template' ) ) {
    function woodmart_sguide_dropdown_template( $post ){
        $arg = array(
            'post_type' => 'woodmart_size_guide',
            'numberposts' => -1
        );

        $sguide_list = get_posts( $arg );

        $sguide_post_id = get_post_meta( $post->ID, 'woodmart_sguide_select' );

        $sguide_post_id = isset( $sguide_post_id[0] ) ? $sguide_post_id[0] : '';
        
        ?>
            <select name="woodmart_sguide_select">
                <option value="none" <?php selected( 'none', $sguide_post_id ); ?>>— None —</option>
                
                <?php foreach ( $sguide_list as $sguide_post ): ?>
                    <option value="<?php echo esc_attr( $sguide_post->ID ); ?>" <?php selected( $sguide_post_id, $sguide_post->ID ); ?>>
						<?php echo wp_kses( $sguide_post->post_title, woodmart_get_allowed_html() ); ?>
					</option>
                <?php endforeach; ?>
                
            </select><br><br>
            
            <label>
                <input type="checkbox" name="woodmart_disable_sguide" id="woodmart_disable_sguide" <?php checked( 'disable', $sguide_post_id, true ); ?>> 
                <?php esc_html_e( 'Hide size guide from this product', 'woodmart' ) ?>
            </label>
        <?php
    }
}

//Dropdown Save
if( ! function_exists( 'woodmart_sguide_dropdown_save' ) ) {
    function woodmart_sguide_dropdown_save( $post_id ){
        if ( isset( $_POST['woodmart_sguide_select'] ) && $_POST['woodmart_sguide_select'] ) {
			
            if ( isset( $_POST['woodmart_disable_sguide'] ) && $_POST['woodmart_disable_sguide'] == 'on' ) {
                update_post_meta( $post_id, 'woodmart_sguide_select', 'disable' );
            } else {
                update_post_meta( $post_id, 'woodmart_sguide_select', sanitize_text_field( $_POST['woodmart_sguide_select'] ) );
            }
            
        }
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Display
 * ------------------------------------------------------------------------------------------------
 */
 
//Size guide display
if( ! function_exists( 'woodmart_sguide_display' ) ) {
    function woodmart_sguide_display( $post_id = false ){
        $post_id = ( $post_id ) ? $post_id : get_the_ID();
        
        $sguide_post_id = get_post_meta( $post_id, 'woodmart_sguide_select' );
        
        if ( isset( $sguide_post_id[0] ) && $sguide_post_id[0] === 'disable' ) {
        	return;
        }
        
        if ( isset( $sguide_post_id[0] ) && !empty( $sguide_post_id[0] ) && $sguide_post_id[0] != 'none' ){
            $sguide_post_id = $sguide_post_id[0];
        }else{
            $terms = wp_get_post_terms( $post_id, 'product_cat' );
            if ( $terms ) {
                foreach( $terms as $term ){
					if ( get_term_meta( $term->term_id, 'woodmart_chosen_sguide', true ) ) {
                        $sguide_post_id = get_term_meta( $term->term_id, 'woodmart_chosen_sguide', true );
                    }else{
                        $sguide_post_id = false;
                    }
                }
            }
        }
	
	    if ( $sguide_post_id ) {
            $sguide_post = get_post( $sguide_post_id );
	        $size_tables = get_post_meta( $sguide_post_id, 'woodmart_sguide' );
            
            woodmart_sguide_display_table_template( $sguide_post, $size_tables );
        }
    }
}

//Size guide display template
if( ! function_exists( 'woodmart_sguide_display_table_template' ) ) {
    function woodmart_sguide_display_table_template( $sguide_post, $size_tables ){
        $is_quick_view = woodmart_loop_prop( 'is_quick_view' );

        if ( !woodmart_get_opt( 'size_guides' ) || $is_quick_view || !$size_tables || !$sguide_post ) return;
        
        $show_table = get_post_meta( $sguide_post->ID, 'woodmart_sguide_hide_table' );
        $show_table = isset( $show_table[0] ) ? $show_table[0] : 'show';

	    woodmart_enqueue_js_library( 'magnific' );
	    woodmart_enqueue_js_script( 'popup-element' );
	    woodmart_enqueue_inline_style( 'mfp-popup' );
	    woodmart_enqueue_inline_style( 'size-guide' );
        ?>
            <style data-type="vc_shortcodes-custom-css">
                <?php echo get_post_meta( $sguide_post->ID, '_wpb_shortcodes_custom_css', true ); ?>
                <?php echo get_post_meta( $sguide_post->ID, 'woodmart_shortcodes_custom_css', true ); ?>
			/* */
            </style>
            <div id="woodmart_sizeguide" class="mfp-with-anim mfp-hide wd-popup wd-sizeguide <?php echo woodmart_get_old_classes( ' woodmart-content-popup' ); ?>">
                <h4 class="wd-sizeguide-title"><?php echo esc_html( $sguide_post->post_title ); ?></h4>
                <div class="wd-sizeguide-content"><?php echo do_shortcode( $sguide_post->post_content ); ?></div>
                <?php if ( $show_table == 'show' ): ?>
                    <div class="responsive-table">
                        <table class="wd-sizeguide-table">
                            <?php foreach ( $size_tables as $table ): ?>
                                <?php foreach ( $table as $row ): ?>
                                    <tr>
                                        <?php foreach ( $row as $col ): ?>
                                            <td><?php echo esc_html( $col ); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="wd-sizeguide-btn wd-action-btn wd-style-text wd-sizeguide-icon">
                <a class="wd-open-popup" rel="noffollow" href="#woodmart_sizeguide"><?php esc_html_e( 'Size Guide', 'woodmart' ); ?></a>
            </div>
        <?php
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Category
 * ------------------------------------------------------------------------------------------------
 */
 
//Size guide save category
if( ! function_exists( 'woodmart_sguide_save_category' ) ) {
    function woodmart_sguide_save_category( $post_id ) {
        if ( isset( $_POST['woodmart_sguide_category'] ) ) {
            $selected_sguide_category = woodmart_clean( $_POST['woodmart_sguide_category'] );
            update_post_meta( $post_id, 'woodmart_chosen_cats', $selected_sguide_category );
            
            $terms = get_terms( 'product_cat' );
            foreach ( $selected_sguide_category as $selected_sguide_cat ) {
	            update_term_meta( $selected_sguide_cat, 'woodmart_chosen_sguide', $post_id );
            }   
            foreach( $terms as $term ){
                if ( !in_array( $term->term_id, $selected_sguide_category ) ) {
					if ( $post_id == get_term_meta( $term->term_id, 'woodmart_chosen_sguide', true ) ) {
						update_term_meta( $term->term_id, 'woodmart_chosen_sguide', '' );
                    }
                }
            }
        }
        else{
            update_post_meta( $post_id, 'woodmart_chosen_cats', '' );
	        $terms = get_terms( 'product_cat' );
	        foreach( $terms as $term ){
		        update_term_meta( $term->term_id, 'woodmart_chosen_sguide', '' );
	        }
        }
    }
}

//Size guide category template
if( ! function_exists( 'woodmart_sguide_category_template' ) ) {
    function woodmart_sguide_category_template( $post ) {
        $arg = array(
            'taxonomy'     => 'product_cat',
            'orderdby'     => 'name',
            'hierarchical' => 1
        );

        $chosen_cats = get_post_meta( $post->ID, 'woodmart_chosen_cats' );
        
        if ( ! empty( $chosen_cats ) ) $chosen_cats = $chosen_cats[0];

        $sguide_cat_list = get_categories( $arg );
        
        ?>
        <ul>
            <?php foreach ( $sguide_cat_list as $sguide_cat ): ?>
                <?php $checked = false; ?>
                <?php if ( is_array( $chosen_cats ) && in_array( $sguide_cat->term_id, $chosen_cats ) ) $checked = 'checked'; ?>
                <li>
                    <input type="checkbox" name="woodmart_sguide_category[]" value="<?php echo esc_attr( $sguide_cat->term_id ); ?>" <?php echo esc_attr( $checked ); ?>>
					<?php echo wp_kses( $sguide_cat->name, woodmart_get_allowed_html() ); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php
    }
}

/**
 * ------------------------------------------------------------------------------------------------
 * Hide table
 * ------------------------------------------------------------------------------------------------
 */
//Size guide hide table template
if( ! function_exists( 'woodmart_sguide_hide_table_template' ) ) {
    function woodmart_sguide_hide_table_template( $post ) {
        $disable_table = get_post_meta( $post->ID, 'woodmart_sguide_hide_table' );
        $disable_table = isset( $disable_table[0] ) ? $disable_table[0] : 'show';
        ?>
        <label>
            <input type="checkbox" name="woodmart_sguide_hide_table" id="woodmart_sguide_hide_table" <?php checked( 'hide', $disable_table, true ); ?> > 
            <?php esc_html_e( 'Hide size guide table', 'woodmart' ) ?>
        </label>
        <?php
    }
}
//Size guide hide table save
if( ! function_exists( 'woodmart_sguide_hide_table_save' ) ) {
    function woodmart_sguide_hide_table_save( $post_id ){
        if ( isset( $_POST['woodmart_sguide_hide_table'] ) && $_POST['woodmart_sguide_hide_table'] == 'on' ) {
            update_post_meta( $post_id, 'woodmart_sguide_hide_table', 'hide' );
        } else {
            update_post_meta( $post_id, 'woodmart_sguide_hide_table', 'show' );
        }
    }
}
