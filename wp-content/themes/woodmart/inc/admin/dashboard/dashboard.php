<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ------------------------------------------------------------------------------------------------
 * Theme dashbaord
 * ------------------------------------------------------------------------------------------------
 */
if( ! class_exists( 'WOODMART_Dashboard' ) ) {
	class WOODMART_Dashboard {

		public $page_name = 'woodmart_dashboard';

		public $tabs;

		public $current_tab = 'home';
		
		private $_notices = null;

		public function __construct() {
			$this->tabs = array(
				'home' => esc_html__( 'Base import', 'woodmart' ), 
				'additional' => esc_html__( 'Additional pages', 'woodmart' ), 
				'builder' => esc_html__( 'Header builder', 'woodmart' ), 
				'license' => esc_html__( 'Theme license', 'woodmart' ), 
			);

			if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) && defined( 'WPB_VC_VERSION' ) ) {
				$this->tabs['wpbakery_css'] = esc_html__( 'WPBakery CSS generator', 'woodmart' );
			}

			add_action( 'admin_menu', array( $this, 'menu_page' ) );

			add_action( 'admin_notices', array( $this, 'add_notices' ), 40 );

			$this->_notices = WOODMART_Registry()->notices;
		}
		public function menu_page() {

			if ( ! current_user_can( apply_filters( 'woodmart_dashboard_theme_links_access', 'administrator' ) ) ) {
				return;
			}

			if ( ! woodmart_get_opt( 'dummy_import', '1' ) ) {
				unset( $this->tabs['home'] );
				unset( $this->tabs['additional'] );
			}

			if ( ! woodmart_get_opt( 'white_label_theme_license_tab', '1' ) ) {
				unset( $this->tabs['license'] );
			}

			$theme_name = esc_html__( 'WoodMart', 'woodmart' );
			$logo       = WOODMART_ASSETS . '/images/theme-admin-icon.svg';

			if ( woodmart_get_opt( 'white_label' ) ) {
				if ( woodmart_get_opt( 'white_label_theme_name' ) ) {
					$theme_name = woodmart_get_opt( 'white_label_theme_name' );
				}

				if ( woodmart_get_opt( 'white_label_sidebar_icon_logo' ) ) {
					$image_data = woodmart_get_opt( 'white_label_sidebar_icon_logo' );

					if ( isset( $image_data['url'] ) && $image_data['url'] ) {
						$logo = wp_get_attachment_image_url( $image_data['id'] );
					}
				}
			}

			$addMenuPage = 'add_me' . 'nu_page';
			$addMenuPage(
				$theme_name,
				$theme_name,
				'manage_options', 
				$this->page_name, 
				array( $this, 'dashboard' ),
				$logo,
				62 
			);
			
			if ( woodmart_get_opt( 'dummy_import', '1' ) ) {
				add_submenu_page(
					$this->page_name,
					'Base import',
					'Base import',
					'edit_posts',
					'admin.php?page=' . $this->page_name . '&tab=home',
					'' 
				); 
				add_submenu_page(
					$this->page_name,
					'Additional pages',
					'Additional pages',
					'edit_posts',
					'admin.php?page=' . $this->page_name . '&tab=additional',
					'' 
				); 
			}
			
			add_submenu_page(
				$this->page_name,
				'Header builder',
				'Header builder',
				'edit_posts',
				'admin.php?page=' . $this->page_name . '&tab=builder',
				'' 
			);

			if ( woodmart_get_opt( 'white_label_theme_license_tab', '1' ) ) {
				add_submenu_page(
					$this->page_name,
					'Theme license',
					'Theme license',
					'edit_posts',
					'admin.php?page=' . $this->page_name . '&tab=license',
					''
				);
			}

			if ( 'wpb' === woodmart_get_opt( 'page_builder', 'wpb' ) && defined( 'WPB_VC_VERSION' ) ) {
				add_submenu_page(
					$this->page_name,
					'WPBakery CSS Generator',
					'WPBakery CSS Generator',
					'edit_posts',
					'admin.php?page=' . $this->page_name . '&tab=wpbakery_css',
					''
				);
			}

			remove_submenu_page($this->page_name, $this->page_name);
		}

		public function get_tabs() {
			return $this->tabs;
		}

		public function get_current_tab() {
			return $this->current_tab;
		}

		public function set_current_tab( $tab ) {
			$this->current_tab = $tab;
		}

		public function dashboard() {
			$tab = 'home';
			if( isset( $_GET['tab'] ) && ! empty( $_GET['tab'] ) ) {
				$tab = trim( sanitize_text_field( $_GET['tab'] ) );

				if( ! isset( $this->tabs[ $tab ] ) ) $tab = 'home';

				$this->set_current_tab( $tab );
			} 
			
			$this->show_page( 'tabs/' . $tab );
		}

		public function tab_url( $tab ) {
			if( ! isset( $this->tabs[ $tab ] ) ) $tab = 'home';
			return admin_url( 'admin.php?page=' . $this->page_name . '&tab=' . $tab );
		}

		public function show_page( $name = 'home') {

			$this->show_part( 'header' );
			$this->show_part( $name );
			$this->show_part( 'footer' );

		}

		public function show_part( $name, $data = array() ) {
			include_once get_parent_theme_file_path( WOODMART_FRAMEWORK . '/admin/dashboard/views/' . $name . '.php');
		}

		public function get_version() {
			$theme = wp_get_theme();
			$v = $theme->get('Version');
			$v_data = explode('.', $v);
			return $v_data[0].'.'.$v_data[1];
		}
		
		public function wpb_css_notice() {
			$file = get_option( 'woodmart-generated-wpbcss-file' );
			$theme_version = WOODMART_WPB_CSS_VERSION;
			
			if ( isset( $file['name'] ) ) {
				$uploads   = wp_upload_dir();
				$file_path = $uploads['basedir'] . '/' . $file['name'];
				$data = file_exists( $file_path ) ? get_file_data( $file_path, array( 'Version' => 'Version' ) ) : array();
				
				if ( isset( $data['Version'] ) && version_compare( $data['Version'], $theme_version, '<' ) ) {
					$this->_notices->add_msg( 'Your custom WPBakery Custom CSS file is outdated. The current version of the theme is ' . $theme_version . ' so you need to go here and click on "<a href="' . $this->tab_url( 'wpbakery_css' ) . '">Update</a>" button to actualize it.', 'warning', true );
				}
			}
		}

		public function license_notice() {
			if ( ! woodmart_is_license_activated() ) {
				$this->_notices->add_msg( 'Please, activate your purchase code for the WoodMart theme and enable auto updates <a href="' . $this->tab_url( 'license' ) . '">here</a>. <br /> <a target="_blank" href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-">Where can I get my purchase code?</a>', 'warning', true );
			}
		}

		public function add_notices() {
			$this->license_notice();
			$this->wpb_css_notice();
		}
		
	}

	$woodmart_dashboard = new WOODMART_Dashboard();
}
