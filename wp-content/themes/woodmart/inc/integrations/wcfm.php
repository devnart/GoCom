<?php

if ( ! function_exists( 'woodmart_wcfm_stock_progress_bar_field' ) ) {
	function woodmart_wcfm_stock_progress_bar_field( $fields, $product_id ) {
		$value = get_post_meta( $product_id, 'woodmart_total_stock_quantity', true );

		$fields['woodmart_total_stock_quantity'] = array(
			'label'       => esc_html__( 'Initial number in stock', 'woodmart' ),
			'type'        => 'text',
			'class'       => 'wcfm-text',
			'label_class' => 'wcfm_title',
			'value'       => $value,
			'hints'       => esc_html__( 'Required for stock progress bar option.', 'woodmart' ),
		);

		return $fields;
	}

	add_filter( 'wcfm_product_fields_stock', 'woodmart_wcfm_stock_progress_bar_field', 10, 2 );
}

if ( ! function_exists( 'woodmart_wcfm_save_total_stock_quantity' ) ) {
	function woodmart_wcfm_save_total_stock_quantity( $post_id, $form_data ) {
		update_post_meta( $post_id, 'woodmart_total_stock_quantity', $form_data['woodmart_total_stock_quantity'] );
	}


	add_action( 'after_wcfm_products_manage_meta_save', 'woodmart_wcfm_save_total_stock_quantity', 10, 2 );
}
