<?php

if ( ! defined( 'WOODMART_THEME_DIR' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * ------------------------------------------------------------------------------------------------
 * Woodmart stock progress bar
 * ------------------------------------------------------------------------------------------------
 */

if ( ! function_exists( 'woodmart_stock_progress_bar' ) ) {
	function woodmart_stock_progress_bar() {
		$product_id  = get_the_ID();
		$total_stock = get_post_meta( $product_id, 'woodmart_total_stock_quantity', true );

		if ( ! $total_stock ) {
			return;
		}

		$current_stock = round( get_post_meta( $product_id, '_stock', true ) );

		$total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
		$percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;

		if ( $current_stock > 0 ) {
			echo '<div class="wd-stock-progress-bar">';
				echo '<div class="stock-info">';
					echo '<div class="total-sold">' . esc_html__( 'Ordered:', 'woodmart' ) . '<span>' . esc_html( $total_sold ) . '</span></div>';
					echo '<div class="current-stock">' . esc_html__( 'Items available:', 'woodmart' ) . '<span>' . esc_html( $current_stock ) . '</span></div>';
				echo '</div>';
				echo '<div class="progress-area" title="' . esc_html__( 'Sold', 'woodmart' ) . ' ' . esc_attr( $percentage ) . '%">';
					echo '<div class="progress-bar"style="width:' . esc_attr( $percentage ) . '%;"></div>';
				echo '</div>';
			echo '</div>';
		}
	}
}

if ( ! function_exists( 'woodmart_total_stock_quantity_input' ) ) {
	function woodmart_total_stock_quantity_input() {
		echo '<div class="options_group">';
			woocommerce_wp_text_input(
				array(
					'id'          => 'woodmart_total_stock_quantity',
					'label'       => esc_html__( 'Initial number in stock', 'woodmart' ),
					'desc_tip'    => 'true',
					'description' => esc_html__( 'Required for stock progress bar option', 'woodmart' ),
					'type'        => 'text',
				)
			);
		echo '</div>';
	}

	add_action( 'woocommerce_product_options_inventory_product_data', 'woodmart_total_stock_quantity_input' );
}

if ( ! function_exists( 'woodmart_save_total_stock_quantity' ) ) {
	function woodmart_save_total_stock_quantity( $post_id ) { // phpcs:ignore
		$stock_quantity = isset( $_POST['woodmart_total_stock_quantity'] ) && $_POST['woodmart_total_stock_quantity'] ? wc_clean( $_POST['woodmart_total_stock_quantity'] ) : ''; // phpcs:ignore

		update_post_meta( $post_id, 'woodmart_total_stock_quantity', $stock_quantity );
	}

	add_action( 'woocommerce_process_product_meta_simple', 'woodmart_save_total_stock_quantity' );
	add_action( 'woocommerce_process_product_meta_variable', 'woodmart_save_total_stock_quantity' );
	add_action( 'woocommerce_process_product_meta_grouped', 'woodmart_save_total_stock_quantity' );
	add_action( 'woocommerce_process_product_meta_external', 'woodmart_save_total_stock_quantity' );
}
