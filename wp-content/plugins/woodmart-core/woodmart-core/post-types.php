<?php
class WOODMART_Post_Types {
	
	public $domain = 'woodmart_starter';
	
	protected static $_instance = null;
	
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'woodmart' ), '2.1' );
	}
	
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'woodmart' ), '2.1' );
	}
	
	public function __construct() {
		
		// Hook into the 'init' action
		add_action( 'init', array($this, 'register_blocks'), 1 );
		add_action( 'init', array($this, 'size_guide'), 1 );
		add_action( 'init', array($this, 'slider'), 1 );
		
		// Duplicate post action for slides
		add_filter( 'post_row_actions', array($this, 'duplicate_slide_action'), 10, 2 );
		add_filter( 'admin_action_woodmart_duplicate_post_as_draft', array($this, 'duplicate_post_as_draft'), 10, 2 );
		
		// Manage slides list columns
		add_filter( 'manage_edit-woodmart_slide_columns', array($this, 'edit_woodmart_slide_columns') ) ;
		add_action( 'manage_woodmart_slide_posts_custom_column', array($this, 'manage_woodmart_slide_columns'), 10, 2 );
		
		// Add shortcode column to block list
		add_filter( 'manage_edit-cms_block_columns', array($this, 'edit_html_blocks_columns') ) ;
		add_action( 'manage_cms_block_posts_custom_column', array($this, 'manage_html_blocks_columns'), 10, 2 );
		
		add_filter( 'manage_edit-portfolio_columns', array($this, 'edit_portfolio_columns') ) ;
		add_action( 'manage_portfolio_posts_custom_column', array($this, 'manage_portfolio_columns'), 10, 2 );
		
		add_action( 'init', array($this, 'register_sidebars'), 1 );
		add_action( 'init', array($this, 'register_portfolio'), 1 );
		
		add_action( 'init', array($this, 'slider'), 1 );
		
		
	}
	
	// **********************************************************************// 
	// ! Register Custom Post Type for WoodMart slider
	// **********************************************************************// 
	public function slider() {
		
		if ( function_exists( 'woodmart_get_opt' ) && ! woodmart_get_opt( 'woodmart_slider', '1' ) ) return;
		
		$labels = array(
			'name'                => esc_html__( 'WoodMart Slider', 'woodmart' ),
			'singular_name'       => esc_html__( 'Slide', 'woodmart' ),
			'menu_name'           => esc_html__( 'Slides', 'woodmart' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'woodmart' ),
			'all_items'           => esc_html__( 'All Items', 'woodmart' ),
			'view_item'           => esc_html__( 'View Item', 'woodmart' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'woodmart' ),
			'add_new'             => esc_html__( 'Add New', 'woodmart' ),
			'edit_item'           => esc_html__( 'Edit Item', 'woodmart' ),
			'update_item'         => esc_html__( 'Update Item', 'woodmart' ),
			'search_items'        => esc_html__( 'Search Item', 'woodmart' ),
			'not_found'           => esc_html__( 'Not found', 'woodmart' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'woodmart' ),
		);
		
		$args = array(
			'label'               => 'woodmart_slide',
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 29,
			'menu_icon'           => 'dashicons-images-alt2',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'capability_type'     => 'page',
		);
		
		register_post_type( 'woodmart_slide', $args );
		
		$labels = array(
			'name'					=> esc_html__( 'Sliders', 'woodmart' ),
			'singular_name'			=> esc_html__( 'Slider', 'woodmart' ),
			'search_items'			=> esc_html__( 'Search Sliders', 'woodmart' ),
			'popular_items'			=> esc_html__( 'Popular Sliders', 'woodmart' ),
			'all_items'				=> esc_html__( 'All Sliders', 'woodmart' ),
			'parent_item'			=> esc_html__( 'Parent Slider', 'woodmart' ),
			'parent_item_colon'		=> esc_html__( 'Parent Slider', 'woodmart' ),
			'edit_item'				=> esc_html__( 'Edit Slider', 'woodmart' ),
			'update_item'			=> esc_html__( 'Update Slider', 'woodmart' ),
			'add_new_item'			=> esc_html__( 'Add New Slider', 'woodmart' ),
			'new_item_name'			=> esc_html__( 'New Slide', 'woodmart' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Sliders', 'woodmart' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used sliders', 'woodmart' ),
			'menu_name'				=> esc_html__( 'Slider', 'woodmart' ),
		);
		
		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
			'hierarchical'      => true,
			'show_tagcloud'     => false,
			'show_ui'           => true,
			'query_var'         => false,
			'rewrite'           => false,
			'query_var'         => false,
			'capabilities'      => array(),
		);
		
		register_taxonomy( 'woodmart_slider', array( 'woodmart_slide' ), $args );
	}
	
	public function duplicate_slide_action( $actions, $post ) {
		if( $post->post_type != 'woodmart_slide' ) return $actions;
		
		if (current_user_can('edit_posts')) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=woodmart_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce' ) . '" title="Duplicate this item" rel="permalink">Duplicate</a>';
		}
		return $actions;
	}
	
	public function duplicate_post_as_draft() {
		global $wpdb;
		if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'woodmart_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
			wp_die('No post to duplicate has been supplied!');
		}
		
		/*
		 * Nonce verification
		 */
		if ( !isset( $_GET['duplicate_nonce'] ) || !wp_verify_nonce( $_GET['duplicate_nonce'], basename( __FILE__ ) ) )
			return;
		
		/*
		 * get the original post id
		 */
		$post_id = (isset($_GET['post']) ? absint( $_GET['post'] ) : absint( $_POST['post'] ) );
		/*
		 * and all the original post data then
		 */
		$post = get_post( $post_id );
		
		/*
		 * if you don't want current user to be the new post author,
		 * then change next couple of lines to this: $new_post_author = $post->post_author;
		 */
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;
		
		/*
		 * if post data exists, create the post duplicate
		 */
		if (isset( $post ) && $post != null) {
			
			/*
			 * new post data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title . ' (duplicate)',
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order
			);
			
			/*
			 * insert the post by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );
			
			/*
			 * get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
			foreach ($taxonomies as $taxonomy) {
				$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
				wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
			}
			
			/*
			 * duplicate all post meta just in two SQL queries
			 */
			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
			if (count($post_meta_infos)!=0) {
				$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
				foreach ($post_meta_infos as $meta_info) {
					$meta_key = $meta_info->meta_key;
					if( $meta_key == '_wp_old_slug' ) continue;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
				$sql_query.= implode(" UNION ALL ", $sql_query_sel);
				$wpdb->query($sql_query);
			}
			
			
			/*
			 * finally, redirect to the edit post screen for the new draft
			 */
			wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
			exit;
		} else {
			wp_die('Post creation failed, could not find original post: ' . $post_id);
		}
	}
	
	// **********************************************************************//
	// ! Register Custom Post Type for Size Guide
	// **********************************************************************//
	public function size_guide() {
		
		if ( function_exists( 'woodmart_get_opt' ) && ! woodmart_get_opt( 'size_guides', '1' ) ) return;
		
		$labels = array(
			'name'                => esc_html__( 'Size Guides', 'woodmart' ),
			'singular_name'       => esc_html__( 'Size Guide', 'woodmart' ),
			'menu_name'           => esc_html__( 'Size Guides', 'woodmart' ),
			'add_new'             => esc_html__( 'Add new', 'woodmart' ),
			'add_new_item'        => esc_html__( 'Add new size guide', 'woodmart' ),
			'new_item'            => esc_html__( 'New size guide', 'woodmart' ),
			'edit_item'           => esc_html__( 'Edit size guide', 'woodmart' ),
			'view_item'           => esc_html__( 'View size guide', 'woodmart' ),
			'all_items'           => esc_html__( 'All size guides', 'woodmart' ),
			'search_items'        => esc_html__( 'Search size guides', 'woodmart' ),
			'not_found'           => esc_html__( 'No size guides found.', 'woodmart' ),
			'not_found_in_trash'  => esc_html__( 'No size guides found in trash.', 'woodmart' )
		);
		
		$args = array(
			'label'               => esc_html__( 'woodmart_size_guide', 'woodmart' ),
			'description'         => esc_html__( 'Size guide to place in your products', 'woodmart' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 29,
			'menu_icon'           => 'dashicons-editor-kitchensink',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);
		
		register_post_type( 'woodmart_size_guide', $args );
	}
	
	// **********************************************************************//
	// ! Register Custom Post Type for HTML Blocks
	// **********************************************************************//
	
	public function register_blocks() {
		
		$labels = array(
			'name'                => esc_html__( 'HTML Blocks', 'woodmart' ),
			'singular_name'       => esc_html__( 'HTML Block', 'woodmart' ),
			'menu_name'           => esc_html__( 'HTML Blocks', 'woodmart' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'woodmart' ),
			'all_items'           => esc_html__( 'All Items', 'woodmart' ),
			'view_item'           => esc_html__( 'View Item', 'woodmart' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'woodmart' ),
			'add_new'             => esc_html__( 'Add New', 'woodmart' ),
			'edit_item'           => esc_html__( 'Edit Item', 'woodmart' ),
			'update_item'         => esc_html__( 'Update Item', 'woodmart' ),
			'search_items'        => esc_html__( 'Search Item', 'woodmart' ),
			'not_found'           => esc_html__( 'Not found', 'woodmart' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'woodmart' ),
		);
		
		$args = array(
			'label'               => esc_html__( 'cms_block', 'woodmart' ),
			'description'         => esc_html__( 'CMS Blocks for custom HTML to place in your pages', 'woodmart' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 29,
			'menu_icon'           => 'dashicons-schedule',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);
		
		register_post_type( 'cms_block', $args );
		
	}
	
	
	public function edit_html_blocks_columns( $columns ) {
		
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => esc_html__( 'Title', 'woodmart' ),
			'shortcode' => esc_html__( 'Shortcode', 'woodmart' ),
			'date' => esc_html__( 'Date', 'woodmart' ),
		);
		
		return $columns;
	}
	
	
	public function manage_html_blocks_columns($column, $post_id) {
		switch( $column ) {
			case 'shortcode' :
				echo '<strong>[html_block id="'.$post_id.'"]</strong>';
				break;
		}
	}
	
	// **********************************************************************// 
	// ! Register Custom Post Type for additional sidebars
	// **********************************************************************// 
	public function register_sidebars() {
		
		$labels = array(
			'name'                => esc_html__( 'Sidebars', 'woodmart' ),
			'singular_name'       => esc_html__( 'Sidebar', 'woodmart' ),
			'menu_name'           => esc_html__( 'Sidebars', 'woodmart' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'woodmart' ),
			'all_items'           => esc_html__( 'All Items', 'woodmart' ),
			'view_item'           => esc_html__( 'View Item', 'woodmart' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'woodmart' ),
			'add_new'             => esc_html__( 'Add New', 'woodmart' ),
			'edit_item'           => esc_html__( 'Edit Item', 'woodmart' ),
			'update_item'         => esc_html__( 'Update Item', 'woodmart' ),
			'search_items'        => esc_html__( 'Search Item', 'woodmart' ),
			'not_found'           => esc_html__( 'Not found', 'woodmart' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'woodmart' ),
		);
		
		$args = array(
			'label'               => esc_html__( 'woodmart_sidebar', 'woodmart' ),
			'description'         => esc_html__( 'You can create additional custom sidebar and use them in Visual Composer', 'woodmart' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 67,
			'menu_icon'           => 'dashicons-welcome-widgets-menus',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'page',
		);
		
		register_post_type( 'woodmart_sidebar', $args );
		
	}
	
	
	
	// **********************************************************************// 
	// ! Register Custom Post Type for portfolio
	// **********************************************************************// 
	public function register_portfolio() {
		
		if ( function_exists( 'woodmart_get_opt' ) && woodmart_get_opt( 'disable_portfolio' ) ) return;
		
		$portfolio_slug = function_exists( 'woodmart_get_opt' ) ? woodmart_get_opt( 'portfolio_item_slug', 'portfolio' ) : 'portfolio';
		$portfolio_cat_slug = function_exists( 'woodmart_get_opt' ) ?  woodmart_get_opt( 'portfolio_cat_slug', 'project-cat' ) : 'project-cat';
		
		$labels = array(
			'name'                => esc_html__( 'Portfolio', 'woodmart' ),
			'singular_name'       => esc_html__( 'Project', 'woodmart' ),
			'menu_name'           => esc_html__( 'Projects', 'woodmart' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'woodmart' ),
			'all_items'           => esc_html__( 'All Items', 'woodmart' ),
			'view_item'           => esc_html__( 'View Item', 'woodmart' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'woodmart' ),
			'add_new'             => esc_html__( 'Add New', 'woodmart' ),
			'edit_item'           => esc_html__( 'Edit Item', 'woodmart' ),
			'update_item'         => esc_html__( 'Update Item', 'woodmart' ),
			'search_items'        => esc_html__( 'Search Item', 'woodmart' ),
			'not_found'           => esc_html__( 'Not found', 'woodmart' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'woodmart' ),
		);
		
		$args = array(
			'label'               => esc_html__( 'portfolio', 'woodmart' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 28,
			'menu_icon'           => 'dashicons-format-gallery',
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'rewrite'             => array('slug' => $portfolio_slug ),
			'capability_type'     => 'page',
		);
		
		register_post_type( 'portfolio', $args );
		
		/**
		 * Create a taxonomy category for portfolio
		 *
		 * @uses  Inserts new taxonomy object into the list
		 * @uses  Adds query vars
		 *
		 * @param string  Name of taxonomy object
		 * @param array|string  Name of the object type for the taxonomy object.
		 * @param array|string  Taxonomy arguments
		 * @return null|WP_Error WP_Error if errors, otherwise null.
		 */
		
		$labels = array(
			'name'					=> esc_html__( 'Project Categories', 'woodmart' ),
			'singular_name'			=> esc_html__( 'Project Category', 'woodmart' ),
			'search_items'			=> esc_html__( 'Search Categories', 'woodmart' ),
			'popular_items'			=> esc_html__( 'Popular Project Categories', 'woodmart' ),
			'all_items'				=> esc_html__( 'All Project Categories', 'woodmart' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'woodmart' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'woodmart' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'woodmart' ),
			'update_item'			=> esc_html__( 'Update Category', 'woodmart' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'woodmart' ),
			'new_item_name'			=> esc_html__( 'New Category', 'woodmart' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'woodmart' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'woodmart' ),
			'menu_name'				=> esc_html__( 'Category', 'woodmart' ),
		);
		
		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => false,
			'hierarchical'      => true,
			'show_tagcloud'     => true,
			'show_ui'           => true,
			'rewrite'           => array('slug' => $portfolio_cat_slug ),
			'query_var'         => true,
			'capabilities'      => array(),
		);
		
		register_taxonomy( 'project-cat', array( 'portfolio' ), $args );
		
	}
	
	
	public function edit_woodmart_slide_columns( $columns ) {
		
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'thumb' => '',
			'title' => esc_html__( 'Title', 'woodmart' ),
			'slide-slider' => esc_html__( 'Slider', 'woodmart' ),
			'date' => esc_html__( 'Date', 'woodmart' ),
		);
		
		return $columns;
	}
	
	
	public function manage_woodmart_slide_columns($column, $post_id) {
		switch( $column ) {
			case 'thumb' :
				if( has_post_thumbnail( $post_id ) ) {
					the_post_thumbnail( array(60,60) );
				}
				break;
			case 'slide-slider' :
				$terms = get_the_terms( $post_id, 'woodmart_slider' );
				
				if ( $terms && ! is_wp_error( $terms ) ) :
					
					$cats_links = array();
					
					foreach ( $terms as $term ) {
						$cats_links[] = $term->name;
					}
					
					$cats = join( ", ", $cats_links );
					?>
					
					<span><?php echo $cats; ?></span>
				
				<?php endif;
				break;
		}
	}
	
	
	
	public function edit_portfolio_columns( $columns ) {
		
		$columns = array(
			'cb' => '<input type="checkbox" />',
			'thumb' => '',
			'title' => esc_html__( 'Title', 'woodmart' ),
			'project-cat' => esc_html__( 'Categories', 'woodmart' ),
			'date' => esc_html__( 'Date', 'woodmart' ),
		);
		
		return $columns;
	}
	
	
	public function manage_portfolio_columns($column, $post_id) {
		switch( $column ) {
			case 'thumb' :
				if( has_post_thumbnail( $post_id ) ) {
					the_post_thumbnail( array(60,60) );
				}
				break;
			case 'project-cat' :
				$terms = get_the_terms( $post_id, 'project-cat' );
				
				if ( $terms && ! is_wp_error( $terms ) ) :
					
					$cats_links = array();
					
					foreach ( $terms as $term ) {
						$cats_links[] = $term->name;
					}
					
					$cats = join( ", ", $cats_links );
					?>
					
					<span><?php echo $cats; ?></span>
				
				<?php endif;
				break;
		}
	}
	
	/**
	 * Get the plugin url.
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', __FILE__ ) );
	}
	
	/**
	 * Get the plugin path.
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}
	
	
}

function WOODMART_Theme_Plugin() {
	return WOODMART_Post_Types::instance();
}

$GLOBALS['woodmart_theme_plugin'] = WOODMART_Theme_Plugin();

if ( ! function_exists( 'woodmart_compress' ) ) {
	function woodmart_compress($variable){
		return base64_encode($variable);
	}
}

if ( ! function_exists( 'woodmart_get_file' ) ) {
	function woodmart_get_file($variable){
		return file_get_contents($variable);
	}
}

if ( ! function_exists( 'woodmart_decompress' ) ) {
	function woodmart_decompress($variable){
		return base64_decode($variable);
	}
}

if ( ! function_exists( 'woodmart_get_svg' ) ) {
	function woodmart_get_svg( $file ){
		if ( ! apply_filters( 'woodmart_svg_cache', true ) ) {
			return file_get_contents( $file );
		}
		
		$file_path = array_reverse( explode( '/', $file ) );
		$slug = 'wdm-svg-' . $file_path[2] . '-' . $file_path[1] . '-' . $file_path[0];
		$content = get_transient( $slug );
		
		if ( ! $content ) {
			$file_get_contents = file_get_contents( $file );
			
			if ( strstr( $file_get_contents, '<svg' ) ) {
				$content = woodmart_compress( $file_get_contents );
				set_transient( $slug, $content, apply_filters( 'woodmart_svg_cache_time', 60 * 60 * 24 * 7 ) );
			}
		}
		
		return woodmart_decompress( $content );
	}
}

// **********************************************************************//
// ! It could be useful if you using nginx instead of apache
// **********************************************************************//

if (!function_exists('getallheaders')) {
	function getallheaders() {
		$headers = array();
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}

// **********************************************************************// 
// ! Support shortcodes in text widget
// **********************************************************************// 

add_filter('widget_text', 'do_shortcode');
?>
