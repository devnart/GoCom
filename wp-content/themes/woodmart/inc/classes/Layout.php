<?php
if ( ! defined( 'WOODMART_THEME_DIR' ) ) exit( 'No direct script access allowed' );

/**
 * WOODMART_Layout Class set up layout settings 
 * for the current page when initializing
 * based on theme options and custom metaboxes
 */

class WOODMART_Layout {

	/**
	 * ID for the current page/post/product/project
	 * @var integer
	 */
	private $page_id = 0;

	/**
	 * Sidebar name
	 * @var string
	 */
	private $sidebar_name = 'sidebar-1';

	/**
	 * CSS bootstrap class for the content section
	 * @var string
	 */	
	private $content_class = '';

	/**
	 * Width of the content X/12
	 * @var integer
	 */
	private $content_col_width = 0;

	/**
	 * CSS bootstrap class for the sidebar section
	 * @var string
	 */	
	private $sidebar_class = '';

	/**
	 * Width of the sidebar X/12
	 * @var integer
	 */
	private $sidebar_col_width = 0;

	/**
	 * Sidebar position
	 * @var string
	 */
	private $page_layout = '';

	/**
	 * Add wordpress actions
	 * 
	 */
	public function __construct() {

		if( is_admin() ) return;

		add_action( 'wp', array($this, 'set_page_id'), 1);

		add_action( 'wp', array($this, 'init'), 500);
	}

	/**
	 * Set page id
	 * 
	 */
	public function set_page_id() {

		//ar(woodmart_get_the_ID());

		$this->page_id = woodmart_get_the_ID( array( 'singulars' => array( 'product' ) ) );

	}

	/**
	 * Set up all properties
	 * 
	 */
	public function init() {

		//ar(woodmart_get_the_ID());

		$this->_set_sidebar_name();

		$this->_set_page_layout();

		$this->_set_sidebar_col_width();

		$this->_set_content_col_width();

		$this->_set_sidebar_class();

		$this->_set_content_class();

	}

	/**
	 * Set the name of sidebar
	 * 
	 */
	private function _set_sidebar_name() {
		$specific = '';
		$page_id = $this->get_page_id();

		if( woodmart_woocommerce_installed() && ( is_product_taxonomy() || is_shop() || is_product_category() || is_product_tag() || woodmart_is_product_attribute_archive() ) ) {
			$this->sidebar_name = 'sidebar-shop';
		} else if( is_singular( 'product' ) ) {
			$this->sidebar_name = 'sidebar-product-single';
		}

		if( $page_id != 0 ) {
			$specific = get_post_meta( $page_id, '_woodmart_custom_sidebar', true );
		}

		if( $specific != '' && $specific !== 'none' ) {
			$this->sidebar_name = $specific;
		}

	}


	/**
	 * Get CSS class for the content DIV 
	 * 
	 * @return string
	 */
	public function get_content_class() {
		return $this->content_class;
	}

	/**
	 * Set CSS class for the content DIV 
	 * 
	 */
	private function _set_content_class() {
		$cl = 'col-lg-';
		$size = $this->get_content_col_width();
		$sidebar_size = $this->get_sidebar_col_width();
		$layout = $this->get_page_layout();

		$this->content_class = $cl . $size . ' col-12';

		$this->content_class .= ( $layout == 'full-width' || $size == 12 ) ? ' col-md-12' : ' col-md-9';
	}

	/**
	 * Get content column width
	 * 
	 * @return string
	 */
	public function get_content_col_width() {
		return $this->content_col_width;
	}

	/**
	 * Set content column width
	 * 
	 */
	private function _set_content_col_width() {
		$sidebar_width = $this->get_sidebar_col_width();
		$this->content_col_width = 12 - $sidebar_width;
	}

	/**
	 * Get CSS class for the sidebar DIV 
	 * 
	 * @return string
	 */
	public function get_sidebar_class() {
		return $this->sidebar_class;
	}

	/**
	 * Set CSS class for the sidebar DIV 
	 * 
	 * @return string
	 */
	private function _set_sidebar_class() {

		$cl = 'col-lg-';
		$size = $this->get_sidebar_col_width();
		$content_size = $this->get_content_col_width();

		$this->sidebar_class = $cl . $size . ' col-md-3 col-12';

		$layout = $this->get_page_layout();

		if ( $layout == 'sidebar-left' ) {
			$this->sidebar_class .= ' order-last order-md-first';
		} elseif ( $layout == 'sidebar-right' ) {
			$this->sidebar_class .= ' order-last';
		}

		if( ! strstr( $this->sidebar_class, 'col-lg-0' ) ) {
			$this->sidebar_class .= ' ' . $layout;
		}

		if ( woodmart_woocommerce_installed() && is_product() ) {
			$this->sidebar_class .= ' single-product-sidebar';
		}

	}

	/**
	 * Set content column width
	 * 
	 * @return integer
	 */
	public function get_sidebar_col_width() {
		return $this->sidebar_col_width;
	}

	/**
	 * Set sidebar column width
	 * 
	 */
	private function _set_sidebar_col_width() {

		$size = 2;
		$specific = '';

		// Set here page ID. Will be used to get custom value from metabox of specific PAGE | BLOG PAGE | SHOP PAGE.
		$page_id = $this->get_page_id();
		$this->sidebar_col_width = woodmart_get_opt( 'sidebar_width' );

		if( $page_id != 0 ) {
			$specific = get_post_meta( $page_id, '_woodmart_sidebar_width', true );
		}

		// Get specific sidebar size for Shop Page
		if( woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || woodmart_is_product_attribute_archive() ) ) {
			$this->sidebar_col_width = woodmart_get_opt( 'shop_sidebar_width' );
		} else if(is_singular( 'product' )) {
			// Get specific layout for SINGLE PRODUCT PAGE
			$this->sidebar_col_width = woodmart_get_opt( 'single_sidebar_width' );
		} else if( is_home() || is_singular( 'post' ) || is_archive() ) {
			// Get specific sidebar size for Blog Page
			$this->sidebar_col_width = woodmart_get_opt( 'blog_sidebar_width' );
		}

		if( $specific != '' && $specific != 'default' ) {
			// Set specific sidebar size FOR THIS PAGE
			$this->sidebar_col_width = $specific;
		}
        // Remove theme sidebar for dokan store list page

        if( function_exists( 'dokan_is_store_page' )  && dokan_is_store_page() ) {
        	$this->sidebar_col_width = 0;
        }

		$layout = $this->get_page_layout();

		// Remove sidebar if it has no widgets
		$sidebar_name = $this->get_sidebar_name();

		if ( ! is_active_sidebar( $sidebar_name ) && $sidebar_name != 'sidebar-product-single' ) {
			$this->sidebar_col_width = 0;
		} 

		if( $layout == 'full-width' ) {
			$this->sidebar_col_width = 0;
		}

		if(empty($this->sidebar_col_width)) {
			$this->sidebar_col_width = 0;
		}

	}

	/**
	 * Get page layout (sidebar position)
	 * 
	 * @return string
	 */
	public function get_page_layout() {
		return $this->page_layout;
	}

	/**
	 * Set page layout (sidebar position)
	 * 
	 */
	private function _set_page_layout() {
		global $post, $WCMp;

		$specific = '';

		// Set here page ID. Will be used to get custom value from metabox of specific PAGE | BLOG PAGE | SHOP PAGE.
		$page_id = $this->get_page_id();

		$this->page_layout = 'sidebar-right';
		$this->page_layout = woodmart_get_opt( 'main_layout' );

		if ( is_singular( 'portfolio' ) || is_post_type_archive( 'portfolio' ) ) {
			$this->page_layout = 'full-width';
		}

		if( $page_id != 0 ) {
			$specific = get_post_meta( $page_id, '_woodmart_main_layout', true );
		}

		if( woodmart_woocommerce_installed() && ( is_shop() || is_product_category() || is_product_tag() || woodmart_is_product_attribute_archive() ) ) {
			// Get specific layout for Shop Page
			$this->page_layout = woodmart_get_opt( 'shop_layout' );
		} else if( $this->is_account_pages() ) {
			$this->page_layout = 'full-width';
		} else if( is_singular( 'product' ) ) {
			// Get specific layout for SINGLE PRODUCT PAGE
			$this->page_layout = woodmart_get_opt( 'single_product_layout' );
		} else if( isset( $WCMp ) && is_tax( $WCMp->taxonomy->taxonomy_name ) ) {
			$this->page_layout = woodmart_get_opt( 'blog_layout' );
		} else if( is_singular( 'post' ) || woodmart_is_blog_archive() ) {
			// Get specific layout for Blog Page
			$this->page_layout = woodmart_get_opt( 'blog_layout' );

			// Disable sidebar if blog design is Masonry Grid
			if( woodmart_get_opt( 'blog_design' ) == 'masonry' && ! is_singular( 'post' ) ) {
				$this->page_layout = 'full-width';
			}
		}

		if( $specific != '' && $specific != 'default' ) {
			// Set specific layout FOR THIS PAGE
			$this->page_layout = $specific;
		}
	}

    /**
     * Check if it is account page
     *
     * @return boolean
     */
	public function is_account_page() {
		if( function_exists( 'is_account_page' ) ) {
			return is_account_page();
		} else {
			return false;
		}
	}

    /**
     * Check if it is some account pages
     *
     * @return boolean
     */
	public function is_account_pages() {
		$wishlist_page_id = woodmart_get_opt( 'wishlist_page' );

		if( function_exists( 'is_account_page' ) ) {
			if( is_account_page() ) return true;
		}

		if( $this->get_page_id() == $wishlist_page_id ) {
			return true;
		}
		
		return false;
	}


    /**
     * Class for page content container
     *
     * @return mixed
     */
	public function get_main_container_class() {
		$class = 'container';

		// Different class for product page
		if( woodmart_woocommerce_installed() && is_singular( 'product' ) && !get_query_var( 'edit' ) && ( ( function_exists( 'woodmart_elementor_has_location' ) && ! woodmart_elementor_has_location( 'single' ) ) || ! function_exists( 'woodmart_elementor_has_location' ) ) ) {
			$class = 'container-fluid';
		}
		
		if ( ( woodmart_tpl2id( 'portfolio.php' ) == woodmart_page_ID() ) && woodmart_get_opt( 'portfolio_full_width' ) ) {
			$class = 'container-fluid container-no-gutters';
		}
		
		if ( woodmart_is_elementor_full_width() ) {
			$class = 'container-fluid container-no-gutters';
		}

		return $class;

	}

    /**
     * Gets the value of page_id.
     *
     * @return mixed
     */
    public function get_page_id() {
        return $this->page_id;
    }

    /**
     * Gets the value of sidebar_name.
     *
     * @return mixed
     */
    public function get_sidebar_name() {
        return $this->sidebar_name;
    }

}

if( ! function_exists( 'woodmart_page_ID' ) ) {
	function woodmart_page_ID() {
		return WOODMART_Registry()->layout->get_page_id();
	}
}

if( ! function_exists( 'woodmart_get_content_class' ) ) {
	function woodmart_get_content_class() {
		return WOODMART_Registry()->layout->get_content_class();
	}
}

if( ! function_exists( 'woodmart_get_content_col_width' ) ) {
	function woodmart_get_content_col_width() {
		return WOODMART_Registry()->layout->get_content_col_width();
	}
}

if( ! function_exists( 'woodmart_get_sidebar_class' ) ) {
	function woodmart_get_sidebar_class() {
		return WOODMART_Registry()->layout->get_sidebar_class();
	}
}

if( ! function_exists( 'woodmart_get_page_layout' ) ) {
	function woodmart_get_page_layout() {
		return WOODMART_Registry()->layout->get_page_layout();
	}
}

if( ! function_exists( 'woodmart_get_sidebar_col_width' ) ) {
	function woodmart_get_sidebar_col_width() {
		return WOODMART_Registry()->layout->get_sidebar_col_width();
	}
}

if( ! function_exists( 'woodmart_get_sidebar_name' ) ) {
	function woodmart_get_sidebar_name() {
		return WOODMART_Registry()->layout->get_sidebar_name();
	}
}

if( ! function_exists( 'woodmart_get_main_container_class' ) ) {
	function woodmart_get_main_container_class() {
		return WOODMART_Registry()->layout->get_main_container_class();
	}
}
