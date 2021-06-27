<?php
/**
 * XTS template library file.
 *
 * @package xts
 */

namespace XTS\Elementor;

use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Base;
use WP_Error;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * XTS template library source.
 *
 * @since 1.0.0
 */
class XTS_Library_Source extends Source_Base {
	/**
	 * Get remote template ID.
	 *
	 * Retrieve the remote template ID.
	 *
	 * @since 1.0.0
	 *
	 * @return string The remote template ID.
	 */
	public function get_id() {
		return 'xts';
	}

	/**
	 * Get remote template title.
	 *
	 * Retrieve the remote template title.
	 *
	 * @since 1.0.0
	 *
	 * @return string The remote template title.
	 */
	public function get_title() {
		return esc_html__( 'XTemos', 'woodmart' );
	}

	/**
	 * Register remote template data.
	 *
	 * Used to register custom template data like a post type, a taxonomy or any
	 * other data.
	 *
	 * @since 1.0.0
	 */
	public function register_data() {
	}

	/**
	 * Get remote templates.
	 *
	 * Retrieve remote templates from Elementor.com servers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Optional. Nou used in remote source.
	 *
	 * @return array Remote templates.
	 */
	public function get_items( $args = [] ) {
		return [];
	}

	/**
	 * Get remote template.
	 *
	 * Retrieve a single remote template from Elementor.com servers.
	 *
	 * @since 1.0.0
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return array Remote template.
	 */
	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	/**
	 * Get template content.
	 *
	 * Retrieve the templates content received from a remote server.
	 *
	 * @since 1.0.0
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return object|WP_Error The template content.
	 */
	private function get_template_content( $template_id ) {
		$response = wp_remote_get( WOODMART_DEMO_URL . '?woodmart_action=woodmart_get_template&id=' . $template_id );
		
		if ( is_wp_error( $response ) || ! is_array( $response )) {
			return $response;
		}

		$data = json_decode( $response['body'], true );

		if ( is_object( $data ) ) {
			if ( property_exists( $data, 'error' ) ) {
				return new WP_Error( 'no_data', $data->error->message );
			}
		}

		return json_decode( $data['element']['config_elementor'], true );
	}

	/**
	 * Get remote template data.
	 *
	 * Retrieve the data of a single remote template from Elementor.com servers.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $args Custom template arguments.
	 * @param string $context Optional. The context. Default is `display`.
	 *
	 * @return array|WP_Error Remote Template data.
	 */
	public function get_data( array $args, $context = 'display' ) {
		if ( 'update' === $context ) {
			$data = $args['data'];
		} else {
			$data = $this->get_template_content( $args['template_id'] );
		}

		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		$post_id  = $args['editor_post_id'];
		$document = Plugin::$instance->documents->get( $post_id );
		if ( $document ) {
			$data['content'] = $document->get_elements_raw_data( $data['content'], true );
		}

		if ( 'update' === $context ) {
			update_post_meta( $post_id, '_elementor_data', $data['content'] );
		}

		return $data;
	}

	/**
	 * Save remote template.
	 *
	 * Remote template from Elementor.com servers cannot be saved on the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $template_data Remote template data.
	 *
	 * @return WP_Error
	 */
	public function save_item( $template_data ) {
		return new WP_Error( 'invalid_request', 'Cannot save template to a remote source' );
	}

	/**
	 * Update remote template.
	 *
	 * Remote template from Elementor.com servers cannot be updated on the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.0.0
	 *
	 * @param array $new_data New template data.
	 *
	 * @return WP_Error
	 */
	public function update_item( $new_data ) {
		return new WP_Error( 'invalid_request', 'Cannot update template to a remote source' );
	}

	/**
	 * Delete remote template.
	 *
	 * Remote template from Elementor.com servers cannot be deleted from the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.0.0
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return WP_Error
	 */
	public function delete_template( $template_id ) {
		return new WP_Error( 'invalid_request', 'Cannot delete template from a remote source' );
	}

	/**
	 * Export remote template.
	 *
	 * Remote template from Elementor.com servers cannot be exported from the
	 * database as they are retrieved from remote servers.
	 *
	 * @since 1.0.0
	 *
	 * @param int $template_id The template ID.
	 *
	 * @return WP_Error
	 */
	public function export_template( $template_id ) {
		return new WP_Error( 'invalid_request', 'Cannot export template from a remote source' );
	}
}
