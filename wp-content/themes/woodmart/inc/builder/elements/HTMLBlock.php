<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 *	HTML Block element
 * ------------------------------------------------------------------------------------------------
 */

if( ! class_exists( 'WOODMART_HB_HTMLBlock' ) ) {
	class WOODMART_HB_HTMLBlock extends WOODMART_HB_Element {

		public function __construct() {
			parent::__construct();
			$this->template_name = 'html-block';
		}

		public function map() {

			$options = $this->_get_options();
			$first = reset($options);

			$this->args = array(
				'type' => 'HTMLBlock',
				'title' => esc_html__( 'HTML Block', 'woodmart' ),
				'text' => esc_html__( 'WPBakery builder', 'woodmart' ),
				'icon' => WOODMART_ASSETS_IMAGES . '/header-builder/icons/hb-ico-html-block.svg',
				'editable' => true,
				'container' => false,
				'edit_on_create' => true,
				'drag_target_for' => array(),
				'drag_source' => 'content_element',
				'removable' => true,
				'addable' => true,
				'params' => array(
					'block_id' => array(
						'id' => 'block_id',
						'title' => esc_html__( 'HTML Block', 'woodmart' ),
						'type' => 'select',
						'tab' => esc_html__( 'General', 'woodmart' ),
						'value' => isset( $first['value'] ) ? $first['value'] : '',
						'options' => $options,
						'description' => esc_html__( 'Choose which HTML block to dislay in the header.', 'woodmart' ),
					),
				)
			);
		}

		private function _get_options() {
			$array = array();
			$args = array( 'posts_per_page' => 250, 'post_type' => 'cms_block' );
			$blocks_posts = get_posts( $args );
			foreach ( $blocks_posts as $post ) {
				$array[$post->ID] = array(
					'label' => $post->post_title,
					'value' => $post->ID
				);
			}
			return $array;
		}

	}
}
