<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------------------------------------
 * WPBakery custom templates library
 * ------------------------------------------------------------------------------------------------
 */

class WOODMART_Vctemplates {

	public $folder = '';

	private $_importer = null;

	private $_template_data = array();

	public function __construct() {
		$this->_config();
		$this->_hooks();
	}

	public function library( $data ) {
		// $templates = woodmart_get_config('vc-templates');

		if ( woodmart_get_opt( 'white_label' ) ) {
			$title = 'Templates library';
		} else {
			$title = 'WoodMart templates library';
		}

		$data[] = array(
			'category' => 'woodmart_templates',
			'category_name' => $title,
			'category_weight' => 5,
			'category_description' => 'WPBakery predefined template parts and layouts from XTemos Studio. Designed for WoodMart WordPress template.',
			'templates' => array()
		);

		return $data;
	}

	public function render_template_code( $template_id, $template_type ) {
		$this->_load_template($template_id);
		echo $this->get_template_content( $template_id ); //Dynamic data is escaped earlier.
	}	

	public function render_template_HTML_code( $template_id, $template_type ) {
		$this->_load_template($template_id);
		$content = $this->get_template_content( $template_id );

		if( ! $content ) return;

		WPBMap::addAllMappedShortcodes();

		vc_frontend_editor()->setTemplateContent( $content );
		vc_frontend_editor()->enqueueRequired();
		vc_include_template( 'editors/frontend_template.tpl.php', array(
			'editor' => vc_frontend_editor(),
		) );

		die(); // no needs to do anything more. optimization.
	}

	public function get_template_content( $id ) {
		$shortcodes = $this->_get_shortcodes( $id );

		if( ! $shortcodes ) return;

		$replace_vars = array();

		if( $config_json = $this->_get_config( $id ) ) {
			$config = json_decode($config_json, true);

			if( is_array( $config ) ) {
				if( isset( $config['assets'] ) && $config['assets'] ) {
					foreach ($config['assets'] as $asset) {
						switch ($asset['type']) {
							case 'external-image':
									
								if($id = $this->_add_media($asset['src'])) {
									$replace_vars[ '{{' . $asset['id'] . '}}' ] = $id;
								}

							break;
							case 'external-image-url':
									
								if($id = $this->_add_media($asset['src'])) {
									$image = wp_get_attachment_image_src($id, 'full');
									if( isset($image[0]) ) 
										$replace_vars[ '{{' . $asset['id'] . '}}' ] = $image[0];
								}

							break;
						}
					}
				}
			}
		}

		if( ! empty( $replace_vars ) ) {
			$shortcodes = $this->_replace_vars($shortcodes, $replace_vars);
		}

		return $shortcodes;
	}


	public function render_template( $category ) {
		$category['output'] = '';

		$category['output'] .= '<div class="vc_column woodmart-templates-heading">';
		$category['output'] .= '<div class="vc_column vc_col-sm-9">';
		if ( isset( $category['category_name'] ) ) {
			$category['output'] .= '<h3>' . esc_html( $category['category_name'] ) . '</h3>';
		}
		if ( isset( $category['category_description'] ) ) {
			$category['output'] .= '<p class="vc_description">' . esc_html( $category['category_description'] ) . '</p>';
		}
		$category['output'] .= '</div>';
		
		$category['output'] .= '<div class="vc_column vc_col-sm-3"><input type="text" class="woodmart-templates-search" placeholder="Start typing to search..." /></div>';


		$category['output'] .= '</div>';
		$category['output'] .= '
		<div class="vc_column vc_col-sm-12">
			<div class="woodmart-templates-list xtemos-loading vc_ui-template-list vc_templates-list-my_templates vc_ui-list-bar" data-vc-action="collapseAll">';

		$category['output'] .= '
				<div class="xtemos-loader-wrapper">
					<div class="xtemos-loader">
						<div class="xtemos-loader-el"></div>
						<div class="xtemos-loader-el"></div>
					</div>
				</div>';

		$category['output'] .= '
			</div>
		</div>';

		$category['output'] .= '';

		return $category;
	}

	
	public function render_item( $template ) {
		$name = isset( $template['name'] ) ? esc_html( $template['name'] ) : esc_html( __( 'No title', 'woodmart' ) );
		$template_id = esc_attr( $template['unique_id'] );
		$template_id_hash = md5( $template_id ); // needed for jquery target for TTA
		$template_name = esc_html( $name );
		$template_name_lower = esc_attr( vc_slugify( $template_name ) );
		$template_type = esc_attr( isset( $template['type'] ) ? $template['type'] : 'custom' );
		$custom_class = esc_attr( isset( $template['custom_class'] ) ? $template['custom_class'] : '' );

		$output = <<<HTML
					<div class="woodmart-template-item $custom_class"
						data-template_id="$template_id"
						data-template_id_hash="$template_id_hash"
						data-category="$template_type"
						data-template_unique_id="$template_id"
						data-template_name="$template_name_lower"
						data-template_title="$template_name"
						data-template_type="$template_type"
						data-vc-content=".vc_ui-template-content">
HTML;
		$output .= $this->render_template_window( $name, $template );
		$output .= <<<HTML
						<div class="vc_ui-template-content" data-js-content>
						</div>
					</div>
HTML;

		return $output;
	}

	public function render_template_window( $template_name, $template_data ) {
		ob_start();
		$template_id = esc_attr( $template_data['unique_id'] );
		$template_name = esc_html( $template_name );
		$add_template_title = esc_attr__( 'Add template', 'woodmart' );
		$image_src = WOODMART_THEME_DIR . '/inc/configs/templates-library/' . $template_id . '/preview.jpg';

		echo <<<HTML
			<div class="woodmart-template-image">
				<img src="$image_src" title="$template_name" alt="$template_name" />
				<button class="" type="button" label="Add this template" title="Add this template" data-template-handler="">$add_template_title</button>
			</div>

			<h3 class="woodmart-template-title">$template_name</h3>
			
HTML;

		return ob_get_clean();
	}

	private function _config() {
		$this->folder = WOODMART_CONFIGS . '/templates-library/';
		$this->_load_importers();
	}

	private function _hooks() {
		add_filter( 'vc_get_all_templates', array($this, 'library'), 1, 1 );
		add_filter( 'vc_templates_render_backend_template', array($this, 'render_template_code'), 10, 2 );
		add_filter( 'vc_templates_render_frontend_template', array($this, 'render_template_HTML_code'), 10, 2 );
		add_filter( 'vc_templates_render_category', array($this, 'render_template'), 1, 1 );
	}

	private function _get_folder($id) { 
		return $this->folder . $id . '/';
	}

	private function _get_file($id, $filename) {
		$folder = $this->_get_folder($id);
		$file = $folder . $filename;

		if( file_exists( $file ) ) {
			ob_start();
			include $file;
			$content = ob_get_clean();
		} else {
			return false;
		}

		return $content;
	}

	private function _get_shortcodes($id) { 
		return $this->_template_data['element']['content'];
		// return $this->_get_file($id, 'shortcodes.txt');
	}

	private function _load_template($id) {
		$response = wp_remote_get(WOODMART_DEMO_URL . '?woodmart_action=woodmart_get_template&id=' . $id);
		$body = '';

		if ( is_array( $response ) ) {
			if ( isset( $response['body'] ) ) {
				$body = $response['body'];

				$this->_template_data = json_decode( $body, true );
			}

			if ( isset( $response['errors'] ) || ! isset( $response['body'] ) ) {
				die( json_encode( $response ) );
			}
		}

		return $body;
		// die();
	}

	private function _get_config($id) { 
		return $this->_template_data['element']['config'];
		// return $this->_get_file($id, 'config.json');
	}

	private function _replace_vars( $code, $vars ) {

		$code = str_replace(array_keys($vars), $vars, $code);

		return $code;
	}

	private function _add_media($src) {

		$postdata = array();

		if( $id = $this->_media_exists( $src ) ) {
			$media_id = $id;
		} else {
			$media_id = $this->_importer->process_attachment($postdata, $src);
			$this->_save_media_id( $src, $media_id );
		}

		if( is_wp_error($media_id) || ! $media_id ) return false;

		return $media_id;
	}

	private function _media_exists($src) {
		$media = get_option('woodmart-vc-imported-media');


		if( ! $media || ! is_array( $media ) ) return false;

		if( $id = array_search($src, $media) ) {
			$image = wp_get_attachment_image_src($id, 'full');
			if( isset($image[0]) ) return $id;
		}

		return false;
	}

	private function _save_media_id($src, $id) {
		if( is_wp_error($id) || ! $id ) return false;

		$media = get_option('woodmart-vc-imported-media');

		if( ! $media || ! is_array( $media ) ) $media = array();

		if( $exists_id = array_search($src, $media) ) unset($media[$exists_id]);
		
		$media[$id] = $src;

		return update_option('woodmart-vc-imported-media', $media);
		
	}

	private function _load_importers() {

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if( ! function_exists( 'WOODMART_Theme_Plugin' ) ) {

			return false;
		}

		$importerError = false;

		//check if wp_importer, the base importer class is available, otherwise include it
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) 
				require_once($class_wp_importer);
			else 
				$importerError = true;
		}

		$plugin_dir = WOODMART_Theme_Plugin()->plugin_path();

		$path = apply_filters('woodmart_require', $plugin_dir . '/importer/wordpress-importer.php');

		if( file_exists( $path ) ) {
			require_once $path;
		} else {
			return false;
		}

		if($importerError !== false) {
			// $this->response->send_fail_msg( "The Auto importing script could not be loaded. Please use the wordpress importer and import the XML file that is located in your themes folder manually." );
			return false;
		} 

		if(class_exists('WP_Importer') && class_exists('WOODCORE_Import')){
			
			$this->_importer = new WOODCORE_Import();
			$this->_importer->fetch_attachments = true;

		} else {

			// $this->response->send_fail_msg( 'Can\'t find WP_Importer or WOODCORE_Import class' );
			return false;

		}

	}
}
