<?php
/**
 * Object that handles theme options page.
 *
 * @package xts
 */

namespace XTS;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Create page and display the form with all sections and fields.
 */
class Page extends Singleton {

	/**
	 * Options set prefix.
	 *
	 * @var array
	 */
	public $opt_name = 'woodmart';

	/**
	 * Options array loaded from the database.
	 *
	 * @var array
	 */
	private $_options;

	/**
	 * Array of all the available sections.
	 *
	 * @var array
	 */
	private $_sections;

	/**
	 * Array of all the available Field objects.
	 *
	 * @var array
	 */
	private $_fields;

	/**
	 * Array of all the available Presets.
	 *
	 * @var array
	 */
	private $_presets;

	/**
	 * Register hooks and load base data.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'admin_page' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_links' ), 100 );

		$this->_presets = Presets::get_all();
	}

	/**
	 * Load all field objects and add them to the sections set.
	 *
	 * @since 1.0.0
	 */
	private function load_fields() {
		$this->_sections = Options::get_sections();
		$this->_fields   = Options::get_fields();

		foreach ( $this->_fields as $key => $field ) {
			$this->_sections[ $field->args['section'] ]['fields'][] = $field;
		}

		$this->_options = Options::get_options();
	}

	/**
	 * Add theme settings links to the admin bar.
	 *
	 * @since 1.0.0
	 *
	 * @param object $admin_bar Admin bar object.
	 */
	public function admin_bar_links( $admin_bar ) {
		$admin_bar->add_node(
			array(
				'id'    => 'theme-dashboard',
				'title' => esc_html__( 'Theme Dashboard', 'woodmart' ),
				'href'  => admin_url( 'admin.php?page=xtemos_options' ),
				'meta'  => array(
					'title' => esc_html__( 'Theme Dashboard', 'woodmart' ),
				),
			)
		);

		$admin_bar->add_node(
			array(
				'id'     => 'theme-settings',
				'title'  => esc_html__( 'Theme Settings', 'woodmart' ),
				'href'   => admin_url( 'admin.php?page=xtemos_options' ),
				'parent' => 'theme-dashboard',
				'meta'   => array(
					'title' => esc_html__( 'Theme Settings', 'woodmart' ),
				),
			)
		);

		$header = whb_get_header();
		$hb_url = admin_url( 'admin.php?page=woodmart_dashboard&tab=builder' );

		if ( $header && ! is_admin() ) {
			$header_id = $header->get_id();
			$hb_url    = admin_url( 'admin.php?page=woodmart_dashboard&tab=builder#/builder/' . $header_id );
		}

		$admin_bar->add_node(
			array(
				'id'     => 'header-builder',
				'title'  => esc_html__( 'Edit header', 'woodmart' ),
				'href'   => $hb_url,
				'parent' => 'theme-dashboard',
				'meta'   => array(
					'title' => esc_html__( 'Edit header', 'woodmart' ),
				),
			)
		);

		$active_presets = Presets::get_active_presets();
		$all_presets    = Presets::get_all();
		if ( $active_presets ) {
			$admin_bar->add_node(
				array(
					'id'     => 'theme-settings-presets',
					'title'  => esc_html__( 'Active presets', 'woodmart' ),
					'href'   => admin_url( 'admin.php?page=xtemos_options' ),
					'parent' => 'theme-dashboard',
					'meta'   => array(
						'title' => esc_html__( 'Active presets', 'woodmart' ),
					),
				)
			);

			foreach ( $active_presets as $preset ) {
				$name = isset( $all_presets[ $preset ]['name'] ) ? $all_presets[ $preset ]['name'] : 'Preset name';

				$admin_bar->add_node(
					array(
						'id'     => 'theme-settings-presets-' . $preset,
						'title'  => $name,
						'href'   => admin_url( 'admin.php?page=xtemos_options&preset=' . $preset ),
						'parent' => 'theme-settings-presets',
						'meta'   => array(
							'title' => $name,
						),
					)
				);
			}
		}
	}

	/**
	 * Callback to register a page in the dashboard.
	 *
	 * @since 1.0.0
	 */
	public function admin_page() {

		$this->load_fields();

		$logo = WOODMART_ASSETS . '/images/theme-admin-icon.svg';

		if ( woodmart_get_opt( 'white_label_sidebar_icon_logo' ) && woodmart_get_opt( 'white_label' ) ) {
			$image_data = woodmart_get_opt( 'white_label_sidebar_icon_logo' );

			if ( isset( $image_data['url'] ) && $image_data['url'] ) {
				$logo = wp_get_attachment_image_url( $image_data['id'] );
			}
		}

		// Create admin page.
		add_menu_page(
			esc_html__( 'Theme Settings', 'woodmart' ),
			esc_html__( 'Theme Settings', 'woodmart' ),
			'manage_options',
			'xtemos_options',
			array( &$this, 'page_content' ),
			$logo,
			61
		);
		foreach ( $this->_sections as $key => $section ) {
			if ( isset( $section['parent'] ) ) {
				continue;
			}
			add_submenu_page(
				'xtemos_options',
				$section['name'],
				$section['name'],
				'manage_options',
				'xtemos_options&tab=' . $key,
				array( &$this, 'page_content' )
			);
		}
		remove_submenu_page( 'xtemos_options', 'xtemos_options' );
	}

	/**
	 * Render the options page content.
	 *
	 * @since 1.0.0
	 */
	public function page_content() {
		$wrapper_classes = '';

		if ( isset( $_GET['preset'] ) ) { // phpcs:ignore
			$wrapper_classes .= ' xts-preset-active';
		}
		?>

			<div class="wrap">	
				<div class="xts-page">
					<div class="xts-page-inner">
						<div class="xts-options xts-dashboard<?php echo esc_attr( $wrapper_classes ); ?>">
							<div class="xts-options-form">
								<h2></h2>
								<?php do_action( 'xts_before_theme_settings' ); ?>

								<?php if ( isset( $_GET['settings-updated'] ) ) : // phpcs:ignore ?>
									<?php do_action( 'xts_theme_settings_save' ); ?>
								<?php endif; ?>
								<div class="xts-options-form-row xts-row">
									<div class="xts-col xts-col-xxl-9">
										<div class="xts-options-header">
											<div class="xts-options-theme-data">
												<h2 class="xts-options-theme-name">
													<?php if ( woodmart_get_opt( 'white_label' ) && woodmart_get_opt( 'white_label_theme_name' ) ) : ?>
														<?php echo esc_html( woodmart_get_opt( 'white_label_theme_name' ) ); ?>
													<?php else : ?>
														Woodmart
													<?php endif; ?>
												</h2>
												<span class="xts-options-theme-version">
													<?php echo esc_html( woodmart_get_theme_info( 'Version' ) ); ?>
												</span>
											</div>
											<div class="xts-options-search">
												<input type="text" placeholder="<?php esc_html_e( 'Start typing to find options...', 'woodmart' ); ?>">
											</div>
										</div>
										<form action="options.php" method="post">
											<div class="xts-fields-tabs">
												<div class="xts-sections-nav">
													<ul>
														<?php $this->display_sections_tree(); ?>
													</ul>
												</div>
												<div class="xts-sections">
													<?php $this->display_message(); ?>
													<?php $this->display_sections(); ?>
													<div class="xts-options-actions">
														<input type="hidden" class="xts-last-tab-input" name="xts-<?php echo esc_attr( $this->opt_name ); ?>-options[last_tab]" value="<?php echo esc_attr( $this->get_last_tab() ); ?>" />
														<button class="xts-save-options-btn xts-btn xts-btn-primary"><?php esc_html_e( 'Save options', 'woodmart' ); ?></button>

														<?php if ( isset( $_GET['preset'] ) ) : // phpcs:ignore ?>
															<a href="<?php echo esc_url( admin_url( 'admin.php?page=xtemos_options' ) ); ?>" class="xts-btn xts-btn-disable">
																<?php esc_html_e( 'To global settings', 'woodmart' ); ?>
															</a>
														<?php endif; ?>
													</div>
												</div>
											</div>
											<input type="hidden" name="page_options" value="xts-<?php echo esc_attr( $this->opt_name ); ?>-options" />
											<input type="hidden" name="action" value="update" />
											<?php if ( Presets::get_current_preset() ) : ?>
												<input type="hidden" class="xts-fields-to-save" name="xts-<?php echo esc_attr( $this->opt_name ); ?>-options[fields_to_save]" value="<?php echo esc_attr( $this->get_fields_to_save() ); ?>" />
												<input type="hidden" name="xts-<?php echo esc_attr( $this->opt_name ); ?>-options[preset]" value="<?php echo esc_attr( Presets::get_current_preset() ); ?>" />
											<?php endif; ?>
											<?php settings_fields( 'xts-options-group' ); ?>
										</form>
									</div>
									<div class="xts-col xts-col-xxl-3">
										<?php Presets::output_ui(); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php do_action( 'xts_after_theme_settings' ); ?>
		<?php
	}

	/**
	 * Get last visited tab by visitor.
	 *
	 * @since 1.0.0
	 */
	private function get_last_tab() {

		reset( $this->_sections );

		$first_tab = key( $this->_sections );

		$current_tab = $first_tab;

		if ( isset( $this->_options['last_tab'] ) && isset( $_GET['settings-updated'] ) ) {
			$current_tab = $this->_options['last_tab'];
		} elseif ( isset( $_GET['tab'] ) ) {
			$current_tab = $_GET['tab'];
		}

		return $current_tab;
	}

	/**
	 * Display saved/imported message.
	 *
	 * @since 1.0.0
	 */
	private function display_message() {
		$message = $this->get_last_message();

		$text = false;

		if ( 'save' === $message ) {
			$text = esc_html__( 'Settings are successfully saved.', 'woodmart' );
		} elseif ( 'import' === $message ) {
			$text = esc_html__( 'New options are successfully imported.', 'woodmart' );
		} elseif ( 'reset' === $message ) {
			$text = esc_html__( 'All options are set to default values.', 'woodmart' );
		}

		if ( $text ) {
			echo '<div class="xts-options-message">' . $text . '</div>'; // phpcs:ignore
		}
	}

	/**
	 * Get last message.
	 *
	 * @since 1.0.0
	 */
	private function get_last_message() {

		return ( isset( $this->_options['last_message'] ) && isset( $_GET['settings-updated'] ) ) ? $this->_options['last_message'] : ''; // phpcs:ignore
	}

	/**
	 * Display sections navigation tree.
	 *
	 * @since 1.0.0
	 */
	private function display_sections_tree() {
		$current_tab   = $this->get_last_tab();
		$active_parent = '';

		if ( isset( $this->_sections[ $current_tab ]['parent'] ) ) {
			$active_parent = $this->_sections[ $current_tab ]['parent'];
		}

		foreach ( $this->_sections as $key => $section ) {
			if ( isset( $section['parent'] ) ) {
				continue;
			}

			$subsections = array_filter(
				$this->_sections,
				function( $el ) use ( $section ) {
					return isset( $el['parent'] ) && $el['parent'] === $section['id'];
				}
			);

			$classes = '';

			if ( $key === $current_tab || $key === $active_parent ) {
				$classes .= ' xts-active-nav';
			}
			if ( is_array( $subsections ) && count( $subsections ) > 0 ) {
				$classes .= ' xts-has-child';
			}

			?>
				<li class="<?php echo esc_attr( $classes ); ?>">
					<a href="javascript:void(0);" data-id="<?php echo esc_attr( $key ); ?>" data-id="<?php echo esc_attr( $key ); ?>">
						<span class="xts-section-icon">
							<i class="<?php echo esc_html( $section['icon'] ); ?>"></i>
						</span>
						<?php echo $section['name']; // phpcs:ignore ?>
					</a>

					<?php if ( is_array( $subsections ) && count( $subsections ) > 0 ) : ?>
						<ul>
							<?php foreach ( $subsections as $key => $subsection ) : ?>
								<li class="xts-subsection-nav <?php echo ( $key === $current_tab ) ? 'xts-active-nav' : ''; ?>">
									<a href="javascript:void(0);" data-id="<?php echo esc_attr( $key ); ?>">
										<?php echo $subsection['name']; // phpcs:ignore ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

				</li>
			<?php
		}
	}

	/**
	 * Loop through all the sections and render all the fields.
	 *
	 * @since 1.0.0
	 */
	private function display_sections() {
		foreach ( $this->_sections as $key => $section ) {
			?>
			<div class="xts-fields-section <?php echo ( $this->get_last_tab() !== $key ) ? 'xts-hidden' : 'xts-active-section'; ?>" data-id="<?php echo esc_attr( $key ); ?>">
				<div class="xts-section-title">
					<h3><?php echo esc_html( $section['name'] ); ?></h3>
				</div>
				<div class="xts-fields-wrapper">
					<?php
					$previus_group = false;
					foreach ( $section['fields'] as $key => $field ) {
						if ( $previus_group && ( ! isset( $field->args['group'] ) || $previus_group !== $field->args['group'] ) ) {
							echo '</div><!-- close group ' . esc_html( $previus_group ) . '-->';
							$previus_group = false;
						}
						if ( isset( $field->args['group'] ) && $previus_group !== $field->args['group'] ) {
							$previus_group = $field->args['group'];
							echo '<div class="xts-group-title"><span>' . esc_html( $previus_group ) . '</span></div>';
							echo '<div class="xts-fields-group">';
						}
						if ( $this->is_inherit_field( $field->get_id() ) ) {
							$field->inherit_value( true );
						}
						$field->render( null, Presets::get_current_preset() );
					}
					if ( $previus_group ) {
						echo '</div><!-- close group ' . esc_html( $previus_group ) . '-->';
						$previus_group = false;
					}
					?>
				</div>
			</div>
			<?php
		}

	}

	/**
	 * Get fields to save value.
	 *
	 * @since 1.0.0
	 */
	private function get_fields_to_save() {
		if ( ! isset( $this->_options[ Presets::get_current_preset() ] ) || ! isset( $this->_options[ Presets::get_current_preset() ]['fields_to_save'] ) ) {
			return '';
		}

		return $this->_options[ Presets::get_current_preset() ]['fields_to_save'];
	}

	/**
	 * Is field by id inherits value.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Field's id.
	 *
	 * @return bool
	 */
	private function is_inherit_field( $id ) {
		return false === strpos( $this->get_fields_to_save(), $id );
	}
}

Page::get_instance();
