<?php if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * WOODMART_Import
 *
 */

class WOODMART_Import {
	
	private $_woodmart_versions = array();

	private $_response;

	private $_version;

	private $_process = array();

	private $_sliders = array();

	private $_builder = 'wpb';

	public function __construct() {
		$this->_response = WOODMART_Registry::getInstance()->ajaxresponse;

		$saved_builder = get_option( 'woodmart_base_import_builder', 'none' );

		if( $saved_builder == 'none' ) {
			$this->_builder = woodmart_get_opt( 'page_builder', 'wpb' );
		} else {
			$this->_builder = $saved_builder;
		}

		$this->_woodmart_versions = woodmart_get_config( 'versions' );

		if ( 'elementor' === $this->_builder ) {
			$versions = woodmart_get_config( 'versions' );
			
			foreach ( $versions as $key => $value ) {
				if ( isset( $value['elementor'] ) && ! $value['elementor'] ) {
					unset( $versions[ $key ] );
				}
			}
			
			$this->_woodmart_versions = $versions;
		}

		add_action( 'wp_ajax_woodmart_import_data', array( $this, 'import_action' ) );

		if( isset( $_GET['clean_data'] ) ) $this->clean_imported_version_data();

		if( isset( $_GET['clean_attr'] ) ) $this->clean_import_attributes_data();

	}
	
	public function get_builder() {
		return $this->_builder;
	}

	public function admin_import_screen( $type = false ) {
		$btn_label = esc_html__( 'Import page', 'woodmart' );
		$activate_label = '';
		$shop_page = get_option( 'woocommerce_shop_page_id' );
		$builder = true;
		if ( woodmart_woocommerce_installed() ) {
			$this->import_attributes();
		}
		?>
			<div class="wrap metabox-holder woodmart-import-page">
				
				<?php if ( $type === 'base' ) : ?>
					<?php if ( ! function_exists( 'is_shop' ) ): ?>
						<p class="woodmart-notice">
							<?php
								printf(
									__('To import data properly we recommend you to install <strong><a href="%s">WooCommerce</a></strong> plugin', 'woodmart'),
									esc_url( add_query_arg( 'page', urlencode( 'tgmpa-install-plugins' ), self_admin_url( 'themes.php' ) ) )
								);
							?>
						</p>
					<?php endif ?>
					
					<?php if ( !$shop_page ): ?>
						<p class="woodmart-warning">
							<?php
								esc_html_e( 'It seems that you didn\'t run WooCommerce setup wizard or didn\'t create a shop and the import can\'t be run now. You need to run WooCommerce setup wizard or install pages manually via WooCommerce -> System status -> Tools.', 'woodmart' );
							?>
						</p>
					<?php endif ?>
	
					<?php if( $this->_required_plugins() ): ?>
						<p class="woodmart-warning">
							<?php
								printf(
									__('You need to install the following plugins to use our import function: <strong><a href="%s">%s</a></strong>', 'woodmart'),
									esc_url( add_query_arg( 'page', urlencode( 'tgmpa-install-plugins' ), self_admin_url( 'themes.php' ) ) ),
									implode(', ', $this->_required_plugins())
								);
							?>
						</p>
					<?php endif; ?>
	
					<?php if ( defined( 'ELEMENTOR_VERSION' ) && defined( 'WPB_PLUGIN_DIR' ) ): ?>
						<?php $builder = false; ?>
						<p class="woodmart-warning">
							<?php
								esc_html_e( 'Please, deactivate one of the builders and leave only ONE plugin either WPBakery page builder or Elementor.', 'woodmart' );
							?>
						</p>
					<?php endif ?>
					
					<?php if ( ! defined( 'ELEMENTOR_VERSION' ) && ! defined( 'WPB_PLUGIN_DIR' ) ): ?>
						<?php $builder = false; ?>
						<p class="woodmart-warning">
							<?php
								esc_html_e( 'You need to choose and activate one of the builder plugin either WPBakery page builder or Elementor (recommended).', 'woodmart' );
							?>
						</p>
					<?php endif ?>
					
					<?php if ( defined( 'ELEMENTOR_VERSION' ) && ! defined( 'WPB_PLUGIN_DIR' ) && ! $this->is_version_imported( 'base' ) ): ?>
						<p class="woodmart-notice">
							<?php
								esc_html_e( 'You have Elementor installed and activated. All the dummy content will be imported for this particular plugin only.', 'woodmart' );
							?>
						</p>
					<?php endif ?>
					
					<?php if ( defined( 'WPB_PLUGIN_DIR' ) && ! defined( 'ELEMENTOR_VERSION' ) && ! $this->is_version_imported( 'base' ) ): ?>
						<p class="woodmart-notice">
							<?php
								esc_html_e( 'You have WPbakery page builder installed and activated. All the dummy content will be imported for this builder only.', 'woodmart' );
							?>
						</p>
					<?php endif ?>
				<?php endif; ?>

				<form action="#" method="post" class="woodmart-import-form">

					<div class="woodmart-response"></div>

					<?php if ( $type == 'base' ): ?>
						<?php

							$btn_label = esc_html__( 'Import base data', 'woodmart' );
							$activate_label = esc_html__( 'Activate base version', 'woodmart' );

							if( $this->is_version_imported('base') ) $btn_label = $activate_label;

							$this->page_preview();

							$list = $this->_woodmart_versions;

							$all = 'base';

							foreach ($list as $slug => $version) {
								if( $slug == $all ) continue;
								$all .= ','.$slug;
							}

						?>

						<div class="import-form-fields">

						<input type="hidden" class="woodmart_version" name="woodmart_version" value="base">
						<!-- <input type="hidden" class="woodmart_versions" name="woodmart_versions" value="furniture,food,organic"> -->
						<input type="hidden" class="woodmart_versions" name="woodmart_versions" value="<?php echo esc_attr( $all ); ?>">

						<?php if( ! $this->is_version_imported('base') ): ?>
							
							<div class="full-import-box">

								<fieldset>
									<legend>Recommended</legend>
									<label for="full_import">
										<input type="checkbox" id="full_import" name="full_import" value="yes" checked="checked">
										Include all pages and versions
									</label>
									<br>
									<small>
										By checking this option you will get <strong>ALL pages and versions</strong>
										imported in one click.
									</small>
								</fieldset>
							
							</div>

						<?php endif ?>

					<?php else: ?>
						<?php

							if( $type == 'version' ) $btn_label = esc_html__( 'Set up version', 'woodmart' );

							$this->versions_select( $type );
						?>
					<?php endif ?>


					<?php if ( ! $this->_required_plugins() && $shop_page && $builder ): ?>
						<p class="submit">
							<input type="submit" name="woodmart-submit" id="woodmart-submit" class="button button-primary" value="<?php echo esc_attr( $btn_label ); ?>" data-activate="<?php echo esc_attr( $btn_label ); ?>">
						</p>
					<?php endif ?>

					<div class="woodmart-import-progress animated" data-progress="0">
						<div style="width: 0;"></div>
					</div>

					</div><!-- .import-form-fields -->

				</form>

			</div>
		<?php
	}

	public function base_import_screen() {
		$this->admin_import_screen( 'base' );
	}

	public function versions_import_screen() {
		$this->admin_import_screen( 'version' );
	}

	public function pages_import_screen() {
		$this->admin_import_screen( 'page' );
	}

	public function elements_import_screen() {
		$this->admin_import_screen( 'element' );
	}

	public function shops_import_screen() {
		$this->admin_import_screen( 'shop' );
	}

	public function products_import_screen() {
		$this->admin_import_screen( 'product' );
	}

	public function versions_select( $type = false ) {
		$first_version = 'base';

		$list = $this->_woodmart_versions;

		if( $type ) {
			$list = array_filter( $this->_woodmart_versions, function( $el ) use($type) {
				return $type == $el['type'];
			});

			ksort( $list );
			// reset($array);
			$first_version = key($list);
		}

		$this->page_preview( $first_version );
		$list = array_reverse($list);
		ksort( $list );
		?>
			<div class="import-form-fields">
			<select class="woodmart_version" name="woodmart_version">
				<option>--select--</option>
				<?php foreach ($list as $key => $value): ?>
					<option value="<?php echo esc_attr( $key ); ?>" data-imported="<?php echo true == $this->is_version_imported( $key ) ? 'yes' : 'no'; ?>"><?php echo esc_html( $value['title'] ); ?></option>
				<?php endforeach ?>
			</select>
		<?php
	}


	public function page_preview( $version = 'base' ) {
		?>
			<div class="page-preview">
				<img src="<?php echo WOODMART_DUMMY_URL; ?><?php echo esc_attr( $version ); ?>/preview.jpg" data-dir="<?php echo WOODMART_DUMMY_URL; ?>">
			</div>
		<?php
	}

	public function import_action() {
		check_ajax_referer( 'woodmart-import-nonce', 'security' );

		if( empty( $_GET['woodmart_version'] ) ) $this->_response->send_fail_msg( 'Wrong version name' );

		$versions = explode( ',', sanitize_text_field( $_GET['woodmart_version'] ) );

		if ( defined( 'ELEMENTOR_VERSION' ) ) {
			$this->_builder = 'elementor';
		} elseif ( defined( 'WPB_PLUGIN_DIR' ) ) {
			$this->_builder = 'wpb';
		} else {
			$this->_response->send_fail_msg( 'You need to choose and activate one of the builder plugin either WPBakery page builder or Elementor.' );
		}

		if ( 'elementor' === $this->_builder ) {
			$config_versions = woodmart_get_config( 'versions' );

			foreach ( $config_versions as $key => $value ) {
				if ( isset( $value['elementor'] ) && ! $value['elementor'] ) {
					$search = array_search( $key, $versions );
					if ( false !== $search ) {
						unset( $versions[ $search ] );
					}
				}
			}
		}

		$sequence = false;

		if( isset( $_GET['sequence'] ) && $_GET['sequence'] == 'true'  ) {
			$sequence = true;
		}

		foreach ( $versions as $version ) {
			$this->_version = $version;
			if( empty( $version ) ) continue;

			// What exactly do we want to import? XML, options...?
			$this->_process = explode( ',', $this->_woodmart_versions[ $this->_version ]['process'] );
			if ( isset( $this->_woodmart_versions[ $this->_version ]['sliders'] ) ) {
				$this->_sliders = explode( ',', $this->_woodmart_versions[ $this->_version ]['sliders'] );
			}

			
			$type = $this->_woodmart_versions[$this->_version]['type'];

			if( $sequence && $type == 'version' ) $this->_process = array('xml', 'sliders', 'page_menu', 'wood_slider');
			if( $sequence && ( $type == 'shop' || $type == 'product' ) ) $this->_process = array();
			if( $sequence && $version == 'base' ) $this->_process = array('xml', 'home', 'shop', 'menu', 'widgets', 'options', 'sliders', 'wood_slider', 'before', 'after', 'headers');
			
			if( $sequence && $type == 'extras' ) $this->_process = array( 'extras' );

			if( $version == 'base' && $this->is_version_imported( 'base' ) ) {
				$this->_response->add_msg( 'Page content was imported previously' );
				foreach (array('xml', 'sliders', 'before', 'after') as $val) {
					if( ( $key = array_search($val, $this->_process ) ) !== false ) {
						unset( $this->_process[ $key ] );
					}
				}
			}

			// Run import of all elements defined in $_process
			$import = new WOODMART_Importversion( $this->_version, $this->_process, $this->_sliders, $this->_builder );
			$import->run_import();

			if ( $version == 'base' ) {
				$this->add_imported_version( 'base' );
				update_option( 'woodmart_base_import_builder', $this->_builder );
			}
		}

		$this->_response->send_response();

	}

	public function get_imported_versions_css_classes() {
		$versions = $this->imported_versions();
		$class = implode( ' imported-', $versions);
		if( ! empty( $class ) ) $class = ' imported-' . $class;
		return $class;
	}

	public function imported_versions() {
		$data = get_option('woodmart_imported_versions');
		if( empty( $data ) ) $data = array();
		return $data;
	}

	public function add_imported_version( $version = false ) {
		if( ! $version ) $version = $this->_version;
		$imported = $this->imported_versions();
		if( $this->is_version_imported() ) return;
		$imported[] = $version;
		return update_option( 'woodmart_imported_versions', $imported );
	}

	public function is_version_imported( $version = false ) {
		if( ! $version ) $version = $this->_version;
		$imported = $this->imported_versions();
		return in_array( $version, $imported);
	}

	public function clean_imported_version_data(){
		return delete_option( 'woodmart_imported_versions' );
	}

	private function _required_plugins() {
		$plugins = array();

		if( ! class_exists('RevSliderSlider') ) {
			$plugins[] = 'Slider Revolution';
		}

		if( ! class_exists('WOODMART_Post_Types') ) {
			$plugins[] = 'Woodmart Core';
		}

		if( ! empty( $plugins ) ) {
			return $plugins;
		}

		return false;
	}

	private function _get_version_folder( $version = false ) {
		if( ! $version ) $version = $this->_version;

		return $this->_file_path . $this->_version . '/';
	}

	public function import_attributes() {
		if ( get_option( 'woodmart_import_attributes' ) == true ) return;

		$import_attributes = $this->create_attributes();

		update_option( 'woodmart_import_attributes', $import_attributes );
	}

	public function clean_import_attributes_data(){
		return delete_option( 'woodmart_import_attributes' );
	}

	public function create_attributes() {
		global $wpdb;

		$attribute_color = $this->get_attribute_to_add( 'Color' );
		$attribute_brand = $this->get_attribute_to_add( 'Brand' );

		$brand = true;
		$color = true;

		if ( function_exists( 'wc_get_attribute_taxonomies' ) && wc_get_attribute_taxonomies() ){
			foreach ( wc_get_attribute_taxonomies() as $key => $value ) {
				if ( $value->attribute_name == 'brand' ) $brand = false;
				if ( $value->attribute_name == 'color' ) $color = false;
			}
		}

		if ( $brand ) $wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute_brand );
		if ( $color ) $wpdb->insert( $wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute_color );

		flush_rewrite_rules();
		delete_transient( 'wc_attribute_taxonomies' );
		
		if ( function_exists( 'wc' ) && ( $brand || $color ) ) {
			return true;
		}else{
			return false;
		}

	}

	public function get_attribute_to_add( $name = 'Color' ) {
		$attribute = array(
			'attribute_label'   => $name,
			'attribute_type'    => 'select',
			'attribute_orderby' =>  '',
			'attribute_public'  => 0
		);

		if ( empty( $attribute['attribute_name'] ) && function_exists( 'wc_sanitize_taxonomy_name' ) ) {
			$attribute['attribute_name'] = wc_sanitize_taxonomy_name( $attribute['attribute_label'] );
		}

		return $attribute;
	}

}
