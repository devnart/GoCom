<?php if ( ! defined('WOODMART_THEME_DIR')) exit('No direct script access allowed');

/**
 * ---------------------------------------------------------------------------
 * Array of widgets that will be imported
 * ---------------------------------------------------------------------------
 */

$posts_in_menu_sidebar = get_page_by_title( 'Posts in menu', OBJECT, 'woodmart_sidebar' );
$posts_in_menu_sidebar = 'sidebar-' . $posts_in_menu_sidebar->ID;

return array(
	'sidebar-shop' => array(
		'widgets' => array(
			'woocommerce_product_categories' => array(
		    	'title' => 'Categories'
		    ),
			'woocommerce_price_filter' => array(
		    	'title' => 'Filter by price',
			),
			'woodmart-woocommerce-layered-nav' => array(
		    	'title' => 'Filter by color',
		    	'attribute' => 'color',
		    	'query_type' => 'and',
		    	'display' => 'list',
		    	'size' => 'normal',
		    	'labels' => 'on',
		    ),
			'woocommerce_products' => array(
		    	'title' => 'Products'
		    ),
			
		),
		'flush' => true
	),
	'sidebar-product-single' => array(
		'widgets' => array(
			'woocommerce_products' => array(
		    	'title' => 'Products'
		    ),
			'woodmart-recent-posts' => array(
		    	'title' => 'Recent Posts',
		    	'limit' => 3,
		    	'thumb_height' => 60,
		    	'thumb_width' => 75,
		    ),
		),
		'flush' => false
	),
	$posts_in_menu_sidebar => array(
		'widgets' => array(
			'woodmart-recent-posts' => array(
		    	'title' => 'Recent Posts',
		    	'limit' => 3,
		    	'thumb_height' => 60,
		    	'thumb_width' => 75,
		    ),
		),
		'flush' => true
	),
	'sidebar-1' => array(
		'widgets' => array(
			'categories' => array(
				'title' => 'Categories'
			),
			'woodmart-recent-posts' => array(
		    	'title' => 'Recent Posts',
		    	'limit' => 3,
		    	'thumb_height' => 60,
		    	'thumb_width' => 75,
		    ),
		    'woodmart-instagram' => array(
		    	'title' => 'Our Instagram',
		    	'username' => 'ozdesignfurniture',
		    	'number' => '9',
		    	'size' => 'thumbnail',
		    	'target' => '_blank',
		    ),
		),
		'flush' => true
	),
	'footer-1' => array(
		'widgets' => array(
			'text' => array(
		    	'text' => '
<div class="footer-logo" style="max-width: 80%; margin-bottom: 10px;"><img src="http://dummy.xtemos.com/woodmart/wp-content/themes/woodmart/images/wood-logo-dark.svg"  style="margin-bottom: 10px;" /></div>
<p>Condimentum adipiscing vel neque dis nam parturient orci at scelerisque neque dis nam parturient.</p>
<div style="line-height: 2;"><i class="fa fa-location-arrow" style="width: 15px; text-align: center; margin-right: 4px;"></i> 451 Wall Street, UK, London<br>
<i class="fa fa-mobile" style="width: 15px; text-align: center; margin-right: 4px;"></i> Phone: (064) 332-1233<br>
<i class="fa fa-envelope-o" style="width: 15px; text-align: center; margin-right: 4px;"></i> Fax: (099) 453-1357</div>
		    	'
		    ),
		),
		'flush' => true
	),
	'footer-2' => array(
		'widgets' => array(
			'woodmart-recent-posts' => array(
		    	'title' => 'Recent Posts',
		    	'limit' => 2,
		    	'thumb_height' => 60,
		    	'thumb_width' => 75,
		    ),
		),
		'flush' => true
	),
	'footer-3' => array(
		'widgets' => array(
			'text' => array(
			    'title' => 'Our Stores',
			    'text' => '
<ul class="menu">
     <li><a href="#">New York</a></li>
     <li><a href="#">London SF</a></li>
     <li><a href="#">Cockfosters BP</a></li>
     <li><a href="#">Los Angeles</a></li>
     <li><a href="#">Chicago</a></li>
     <li><a href="#">Las Vegas</a></li>
</ul>
		    	'
		    ),
		),
		'flush' => false
	),
	'footer-4' => array(
		'widgets' => array(
			'text' => array(
			    'title' => 'Useful links',
			    'text' => '
<ul class="menu">
<li><a href="#">Privacy Policy</a></li>
<li><a href="#">Returns</a></li>
<li><a href="#">Terms &amp; Conditions</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Latest News</a></li>
<li><a href="#">Our Sitemap</a></li>
</ul>
		    	'
		    ),
		),
		'flush' => false
	),
	'footer-5' => array(
		'widgets' => array(
			'text' => array(
			    'title' => 'Footer Menu',
			    'text' => '
<ul class="menu">
<li><a href="#">Instagram profile</a></li>
<li><a href="#">New Collection</a></li>
<li><a href="#">Woman Dress</a></li>
<li><a href="#">Contact Us</a></li>
<li><a href="#">Latest News</a></li>
<li><a href="#" target="_blank" style="font-style: italic;">Purchase Theme</a></li>
</ul>
		    	'
		    ),
		),
		'flush' => false
	)
);
