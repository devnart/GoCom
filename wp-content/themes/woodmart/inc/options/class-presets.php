<?php
/**
 * Static singleton class for presets functions.
 *
 * @package xts
 */

namespace XTS;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

/**
 * Create presets post type and functionality.
 */
class Presets extends Singleton {
	/**
	 * All presets.
	 *
	 * @var array
	 */
	private static $presets;

	/**
	 * Register hooks and load base data.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'wp_ajax_xts_new_preset_action', array( $this, 'new_preset_action' ) );
		add_action( 'wp_ajax_xts_remove_preset_action', array( $this, 'remove_preset_action' ) );
		add_action( 'wp_ajax_xts_save_preset_conditions_action', array( $this, 'save_preset_conditions_action' ) );
		add_action( 'wp_ajax_xts_get_entity_ids_action', array( $this, 'get_entity_ids_action' ) );

		$this->load_presets();
	}

	/**
	 * Load presets from the database.
	 *
	 * @since 1.0.0
	 */
	public function load_presets() {
		$presets = get_option( 'xts-options-presets' );

		if ( ! $presets || empty( $presets ) ) {
			$presets = array();
		}

		self::$presets = $presets;
	}

	/**
	 * AJAX action for saving preset conditions.
	 *
	 * @since 1.0.0
	 */
	public function save_preset_conditions_action() {
		check_ajax_referer( 'xts-preset-form', 'security' );

		$id = isset( $_POST['preset'] ) ? (int) sanitize_text_field( $_POST['preset'] ) : false; // phpcs:ignore

		$condition = array(
			'relation' => 'OR',
			'rules'    => array(),
		);

		if ( $id && isset( $_POST['data'] ) && is_array( $_POST['data'] ) ) {
			foreach ( $_POST['data'] as $key => $rule ) { // phpcs:ignore
				$condition['rules'][] = wp_parse_args(
					$rule,
					array(
						'type'       => '',
						'comparison' => '=',
						'post_type'  => '',
						'taxonomy'   => '',
						'custom'     => '',
						'value_id'   => '',
					)
				);
			}
		}

		$this->update_preset_conditions( $id, $condition );

		$this->ajax_response(
			array(
				'error_msg'   => esc_html__( 'Something went wrong during the AJAX request.', 'woodmart' ),
				'success_msg' => esc_html__( 'Options preset has been successfully saved.', 'woodmart' ),
			)
		);

		wp_die();
	}

	/**
	 * Update preset conditions.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id        Preset's id.
	 * @param array   $condition Conditions array.
	 */
	public function update_preset_conditions( $id, $condition ) {
		self::$presets[ $id ]['condition'] = $condition;

		$this->update_presets();
	}

	/**
	 * Create preset AJAX action.
	 *
	 * @since 1.0.0
	 */
	public function new_preset_action() {
		check_ajax_referer( 'xts-preset-form', 'security' );

		$name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : 'New preset'; // phpcs:ignore

		$id = $this->add_preset( $name );

		$this->ajax_response(
			array(
				'id' => $id,
			)
		);

		wp_die();
	}

	/**
	 * Remove preset AJAX action.
	 *
	 * @since 1.0.0
	 */
	public function remove_preset_action() {
		check_ajax_referer( 'xts-preset-form', 'security' );

		$id = isset( $_POST['id'] ) ? (int) sanitize_text_field( $_POST['id'] ) : false; // phpcs:ignore

		if ( ! $id ) {
			wp_die();
		}

		$this->remove_preset( $id );

		$this->ajax_response();

		wp_die();
	}

	/**
	 * Create a preset in the database.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $name Preset name.
	 *
	 * @return int|string|null
	 */
	public function add_preset( $name ) {
		$all = self::get_all();

		end( $all );

		$last_id = key( $all );

		if ( empty( $all ) ) {
			$last_id = apply_filters( 'xts_presets_start_id', 0 );
		}

		$id = $last_id + 1;

		$new_preset = array(
			'id'        => $id,
			'name'      => $name,
			'condition' => array(),
		);

		self::$presets[ $id ] = $new_preset;

		$this->update_presets();

		return $id;
	}

	/**
	 * Remove preset from the database.
	 *
	 * @since 1.0.0
	 *
	 * @param integer $id Remove preset by id.
	 */
	public function remove_preset( $id ) {
		if ( ! isset( self::$presets[ $id ] ) ) {
			return;
		}

		unset( self::$presets[ $id ] );

		$this->update_presets();
	}

	/**
	 * Update presets option.
	 *
	 * @since 1.0.0
	 */
	public function update_presets() {
		update_option( 'xts-options-presets', self::$presets );
	}

	/**
	 * Send AJAX response data.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data Additional data array.
	 */
	public function ajax_response( $data = array() ) {
		ob_start();
		self::output_ui();
		$ui = ob_get_clean();

		echo wp_json_encode(
			array_merge(
				array(
					'ui' => $ui,
				),
				$data
			)
		);
	}

	/**
	 * AJAX action to load entities names.
	 *
	 * @since 1.0.0
	 */
	public function get_entity_ids_action() {
		check_ajax_referer( 'xts-preset-form', 'security' );

		$type = isset( $_POST['type'] ) ? sanitize_text_field( $_POST['type'] ) : false; // phpcs:ignore
		$name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : false; // phpcs:ignore

		$items = array();

		switch ( $type ) {
			case 'term_id':
				$args = array(
					'hide_empty' => false,
					'fields'     => 'all',
					'name__like' => $name,
				);

				$terms = get_terms( $args );

				if ( count( $terms ) > 0 ) {
					foreach ( $terms as $term ) {
						$items[] = array(
							'id'   => $term->term_id,
							'text' => $term->name,
						);
					}
				}
				break;
			case 'post_id':
				$args = array(
					's'              => $name,
					'post_type'      => get_post_types( array( 'public' => true ) ),
					'posts_per_page' => 100,
				);

				$posts = get_posts( $args );

				if ( count( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						$items[] = array(
							'id'   => $post->ID,
							'text' => $post->post_title,
						);
					}
				}
				break;
		}

		echo wp_json_encode(
			array(
				'results' => $items,
			)
		);

		wp_die();
	}

	/**
	 * Output Presets UI.
	 *
	 * @since 1.0.0
	 */
	public static function output_ui() {
		?>
		<div class="xts-presets-wrapper" data-current-id="<?php echo esc_attr( self::get_current_preset() ); ?>" data-preset-url="<?php echo esc_url( admin_url( 'admin.php?page=xtemos_options&preset=' ) ); ?>" data-base-url="<?php echo esc_url( admin_url( 'admin.php?page=xtemos_options' ) ); ?>">
			<div class="xts-options-header xts-presets-title">
				<h3>
					<?php esc_html_e( 'Options presets', 'woodmart' ); ?>
				</h3>
			</div>

			<?php if ( self::get_current_preset() ) : ?>
				<div class="xts-current-preset">
					<div class="xts-presets-response"></div>
					<h4>
						<?php echo esc_html( self::$presets[ self::get_current_preset() ]['name'] ); ?>
						<?php esc_html_e( 'rules', 'woodmart' ); ?>
					</h4>

					<div class="xts-preset-conditions">
						<form action="">
							<?php self::display_current_conditions(); ?>

							<?php wp_nonce_field( 'xts-preset-form' ); ?>

							<button type="submit" class="xts-btn xts-btn-primary xts-btn-shadow xf-save">
								<?php esc_html_e( 'Save conditions', 'woodmart' ); ?>
							</button>

							<a href="#" class="xts-add-preset-rule xts-btn">
								<?php esc_html_e( 'Add new rule', 'woodmart' ); ?>
							</a>
						</form>
					</div>
					<div class="xtemos-loader">
						<div class="xtemos-loader-el"></div>
						<div class="xtemos-loader-el"></div>
					</div>
				</div>

				<div class="xts-rule-template">
					<?php self::rule_template(); ?>
				</div>
			<?php else : ?>
				<?php wp_nonce_field( 'xts-preset-form' ); ?>
			<?php endif; ?>

			<div class="xts-presets-list xts-design-list">
				<?php if ( is_array( self::$presets ) && count( self::$presets ) ) : ?>
					<h4>
						<?php esc_html_e( 'All options presets', 'woodmart' ); ?>
					</h4>

					<ul>
						<?php foreach ( self::$presets as $id => $preset ) : ?>
							<li class="<?php echo self::get_current_preset() === $id ? 'xts-active' : ''; ?>">
								<a href="<?php echo esc_url( admin_url( 'admin.php?page=xtemos_options&preset=' . $id ) ); ?>">
									<span class="xts-preset-title"><?php echo esc_html( $preset['name'] ); ?></span>
									<button href="#" class="xts-remove-preset-btn xts-btn xts-btn-disable" data-id="<?php echo esc_attr( $id ); ?>">
										<?php echo esc_html__( 'Delete', 'woodmart' ); ?>
									</button>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php else : ?>
					<div class="xts-notice xts-info">
						<?php esc_html_e( 'There are no custom presets yet.', 'woodmart' ); ?>
					</div>
				<?php endif; ?>

				<button class="xts-add-new-preset xts-btn xts-btn-primary">
					<?php esc_html_e( 'Add a new preset', 'woodmart' ); ?>
				</button>
			</div>
		</div>
		<?php
	}

	/**
	 * Display current preset conditions form.
	 *
	 * @since 1.0.0
	 */
	public static function display_current_conditions() {
		$preset    = self::$presets[ self::get_current_preset() ];
		$condition = $preset['condition'];

		?>
		<div class="xts-condition-rules">
			<?php if ( ! empty( $condition['rules'] ) ) : ?>
				<?php foreach ( $condition['rules'] as $rule ) : ?>
					<?php self::rule_template( false, $rule ); ?>
				<?php endforeach; ?>
			<?php else : ?>
				<?php self::rule_template( false ); ?>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * HTML template for one rule.
	 *
	 * @since 1.0.0
	 *
	 * @param bool  $hidden Is this template hidden.
	 * @param array $rule   Rule array.
	 */
	public static function rule_template( $hidden = true, $rule = array() ) {
		$rule = wp_parse_args(
			$rule,
			array(
				'type'       => '',
				'comparison' => '=',
				'post_type'  => '',
				'taxonomy'   => '',
				'custom'     => '',
				'value_id'   => '',
			)
		);

		$post_types = get_post_types(
			array(
				'public' => true,
			)
		);

		$taxonomies = get_taxonomies(
			array(
				'public' => true,
			)
		);

		$custom_conditions = apply_filters(
			'xts_get_custom_conditions_for_preset',
			array(
				'search'         => 'Search results',
				'blog'           => 'Default "Your Latest Posts" screen',
				'front'          => 'Front page',
				'archives'       => 'All archives',
				'author'         => 'Author archives',
				'error404'       => '404 error screens',
				'shop'           => 'Shop page',
				'single_product' => 'Single product',
				'cart'           => 'Cart page',
				'checkout'       => 'Checkout page',
				'account'        => 'Account pages',
				'is_mobile'      => 'Is mobile device',
			)
		);

		$title = false;

		if ( 'post_id' === $rule['type'] && ! empty( $rule['value_id'] ) ) {
			$title = get_the_title( $rule['value_id'] );
		}

		if ( 'term_id' === $rule['type'] && ! empty( $rule['value_id'] ) ) {
			$term_object = false;

			$taxonomies = get_taxonomies();

			foreach ( $taxonomies as $tax_type_key => $taxonomy ) {
				$term_object = get_term_by( 'id', $rule['value_id'], $taxonomy );
				if ( ! $term_object ) {
					break;
				}
			}

			if ( $term_object ) {
				$title = $term_object->name;
			}
		}

		?>
		<div class="xts-rule <?php echo $hidden ? 'xts-hidden' : ''; ?>">
			<select class="xts-rule-type">
				<option value=""><?php esc_html_e( '--type--', 'woodmart' ); ?></option>
				<option value="post_type" <?php selected( 'post_type', $rule['type'] ); ?>><?php esc_html_e( 'Post type', 'woodmart' ); ?></option>
				<option value="post_id" <?php selected( 'post_id', $rule['type'] ); ?>><?php esc_html_e( 'Post ID', 'woodmart' ); ?></option>
				<option value="taxonomy" <?php selected( 'taxonomy', $rule['type'] ); ?>><?php esc_html_e( 'Taxonomy', 'woodmart' ); ?></option>
				<option value="term_id" <?php selected( 'term_id', $rule['type'] ); ?>><?php esc_html_e( 'Term ID', 'woodmart' ); ?></option>
				<option value="custom" <?php selected( 'custom', $rule['type'] ); ?>><?php esc_html_e( 'Custom', 'woodmart' ); ?></option>
			</select>

			<select class="xts-rule-comparison">
				<option value="equals" <?php selected( 'equals', $rule['comparison'] ); ?>><?php esc_html_e( 'equals', 'woodmart' ); ?></option>
				<option value="not_equals" <?php selected( 'not_equals', $rule['comparison'] ); ?>><?php esc_html_e( 'not equals', 'woodmart' ); ?></option>
			</select>

			<select class="xts-rule-post-type <?php echo 'post_type' !== $rule['type'] ? 'xts-hidden' : ''; ?>">
				<?php foreach ( $post_types as $key => $type ) : ?>
					<option value="<?php echo esc_attr( $type ); ?>" <?php selected( $type, $rule['post_type'] ); ?>><?php echo esc_html( $type ); ?></option>
				<?php endforeach; ?>
			</select>

			<select class="xts-rule-taxonomy <?php echo 'taxonomy' !== $rule['type'] ? 'xts-hidden' : ''; ?>">
				<?php foreach ( $taxonomies as $key => $taxonomy ) : ?>
					<option value="<?php echo esc_attr( $taxonomy ); ?>" <?php selected( $taxonomy, $rule['taxonomy'] ); ?>><?php echo esc_html( $taxonomy ); ?></option>
				<?php endforeach; ?>
			</select>

			<select class="xts-rule-custom <?php echo 'custom' !== $rule['type'] ? 'xts-hidden' : ''; ?>">
				<?php foreach ( $custom_conditions as $key => $condition ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $rule['custom'] ); ?>><?php echo esc_html( $condition ); ?></option>
				<?php endforeach; ?>
			</select>

			<div class="xts-rule-value-wrapper <?php echo 'post_id' !== $rule['type'] && 'term_id' !== $rule['type'] ? 'xts-hidden' : ''; ?>">
				<select data-placeholder="<?php esc_attr_e( 'Start typing...', 'woodmart' ); ?>" class="xts-rule-value-id" data-value="<?php echo esc_attr( $rule['value_id'] ); ?>">
					<?php if ( $title ) : ?>
						<option value="<?php echo esc_attr( $rule['value_id'] ); ?>" selected="selected"><?php echo esc_html( $title ); ?></option>
					<?php endif; ?>
				</select>
			</div>

			<a href="#" class="xts-remove-preset-rule">
				<?php esc_html_e( 'Delete', 'woodmart' ); ?>
			</a>
		</div>
		<?php
	}

	/**
	 * Get currently editing preset.
	 *
	 * @since 1.0.0
	 */
	public static function get_current_preset() {
		return isset( $_REQUEST['preset'] ) && isset( self::$presets[ $_REQUEST['preset'] ] ) ? intval( $_REQUEST['preset'] ) : false; // phpcs:ignore
	}

	/**
	 * Presets getter.
	 *
	 * @since 1.0.0
	 */
	public static function get_all() {
		return self::$presets;
	}

	/**
	 * Get presets that active for the current page.
	 *
	 * @since 1.0.0
	 */
	public static function get_active_presets() {
		$all            = self::get_all();
		$active_presets = array();

		foreach ( $all as $preset ) {
			if ( empty( $preset['condition'] ) || ! isset( $preset['condition']['rules'] ) || empty( $preset['condition']['rules'] ) ) {
				continue;
			}

			$rules = $preset['condition']['rules'];
			foreach ( $rules as $rule ) {
				$is_active = false;
				switch ( $rule['type'] ) {
					case 'post_type':
						$condition = get_post_type() === $rule['post_type'];
						$is_active = 'equals' === $rule['comparison'] ? $condition : ! $condition;
						break;
					case 'post_id':
						if ( $rule['value_id'] && ! is_admin() ) {
							$condition = woodmart_get_the_ID() == $rule['value_id'];
							$is_active = 'equals' === $rule['comparison'] ? $condition : ! $condition;
						}
						break;
					case 'term_id':
						$object  = get_queried_object();
						$term_id = is_object( $object ) && property_exists( $object, 'term_id' ) ? get_queried_object()->term_id : false;

						if ( $term_id ) {
							$condition = $term_id == $rule['value_id'];
							$is_active = 'equals' === $rule['comparison'] ? $condition : ! $condition;
						}
						break;
					case 'taxonomy':
						$object = get_queried_object();

						$taxonomy = is_object( $object ) && property_exists( $object, 'taxonomy' ) ? get_queried_object()->taxonomy : false;

						if ( $taxonomy ) {
							$condition = $taxonomy == $rule['taxonomy'];
							$is_active = 'equals' === $rule['comparison'] ? $condition : ! $condition;
						}
						break;
					case 'custom':
						switch ( $rule['custom'] ) {
							case 'search':
								$is_active = 'equals' === $rule['comparison'] ? is_search() : ! is_search();
								break;
							case 'blog':
								$condition = woodmart_get_the_ID() == get_option( 'page_for_posts' );
								$is_active = 'equals' === $rule['comparison'] ? $condition : ! $condition;
								break;
							case 'front':
								$condition = woodmart_get_the_ID() == get_option( 'page_on_front' );
								$is_active = 'equals' === $rule['comparison'] ? $condition : ! $condition;
								break;
							case 'archives':
								$is_active = 'equals' === $rule['comparison'] ? is_archive() : ! is_archive();
								break;
							case 'author':
								$is_active = 'equals' === $rule['comparison'] ? is_author() : ! is_author();
								break;
							case 'error404':
								$is_active = 'equals' === $rule['comparison'] ? is_404() : ! is_404();
								break;
							case 'shop':
								$is_active = 'equals' === $rule['comparison'] ? is_shop() : ! is_shop();
								break;
							case 'single_product':
								$is_active = 'equals' === $rule['comparison'] ? is_product() : ! is_product();
								break;
							case 'cart':
								$is_active = 'equals' === $rule['comparison'] ? is_cart() : ! is_cart();
								break;
							case 'checkout':
								$is_active = 'equals' === $rule['comparison'] ? is_checkout() : ! is_checkout();
								break;
							case 'account':
								$is_active = 'equals' === $rule['comparison'] ? is_account_page() : ! is_account_page();
								break;
							case 'is_mobile':
								$is_active = 'equals' === $rule['comparison'] ? wp_is_mobile() : ! wp_is_mobile();
								break;
						}
						break;
				}

				if ( isset( $_GET['page'] ) && isset( $_GET['preset'] ) && 'xtemos_options' === $_GET['page'] ) { // phpcs:ignore
					$is_active    = true;
					$preset['id'] = $_GET['preset']; // phpcs:ignore
				}

				if ( $is_active ) {
					$active_presets[] = $preset['id'];
				}
			}
		}

		foreach ( $all as $preset ) {
			if ( isset( $_GET['opts'] ) && $preset['name'] === $_GET['opts'] ) { // phpcs:ignore
				array_push( $active_presets, $preset['id'] );
			}
		}

		return apply_filters( 'xts_active_options_presets', array_unique( $active_presets ), $all );
	}
}

Presets::get_instance();
