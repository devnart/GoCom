<?php if ( ! defined("WOODMART_THEME_DIR")) exit("No direct script access allowed");

/**
 * ------------------------------------------------------------------------------------------------
 * Default header builder structure
 * ------------------------------------------------------------------------------------------------
 */

$header_structure = array(
	'id' => 'root',
	'type' => 'root',
	'content' => array(
		0 => array(
			'id' => 'top-bar',
			'type' => 'row',
			'content' => array(
				0 => array(
					'id' => 'column5',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => '6l5y1eay522jehk73pi2',
							'type' => 'text',
							'params' => array(
								'content' => array(
									'id' => 'content',
									'value' => '<strong class="color-white">ADD ANYTHING HERE OR JUST REMOVE IT…</strong>',
									'type' => 'editor',
								) ,
								'css_class' => array(
									'id' => 'css_class',
									'value' => '',
									'type' => 'text',
								) ,
							) ,
						) ,
					) ,
				) ,
				1 => array(
					'id' => 'column6',
					'type' => 'column',
					'content' => array() ,
				) ,
				2 => array(
					'id' => 'column7',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => '61qbocnh2ezx7e7al7jd',
							'type' => 'social',
							'params' => array(
								'type' => array(
									'id' => 'type',
									'value' => 'share',
									'type' => 'selector',
								) ,
								'size' => array(
									'id' => 'size',
									'value' => 'small',
									'type' => 'selector',
								) ,
								'color' => array(
									'id' => 'color',
									'value' => 'light',
									'type' => 'selector',
								) ,
								'align' => array(
									'id' => 'align',
									'value' => 'left',
									'type' => 'selector',
								) ,
								'style' => array(
									'id' => 'style',
									'value' => '',
									'type' => 'selector',
								) ,
								'form' => array(
									'id' => 'form',
									'value' => 'circle',
									'type' => 'selector',
								) ,
								'el_class' => array(
									'id' => 'el_class',
									'value' => '',
									'type' => 'text',
								) ,
							) ,
						) ,
					) ,
				) ,
				3 => array(
					'id' => 'column_mobile1',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => 'nugd58vqvv5sdr3bc5dd',
							'type' => 'social',
							'params' => array(
								'type' => array(
									'id' => 'type',
									'value' => 'share',
									'type' => 'selector',
								) ,
								'size' => array(
									'id' => 'size',
									'value' => 'small',
									'type' => 'selector',
								) ,
								'color' => array(
									'id' => 'color',
									'value' => 'light',
									'type' => 'selector',
								) ,
								'align' => array(
									'id' => 'align',
									'value' => 'left',
									'type' => 'selector',
								) ,
								'style' => array(
									'id' => 'style',
									'value' => '',
									'type' => 'selector',
								) ,
								'form' => array(
									'id' => 'form',
									'value' => 'circle',
									'type' => 'selector',
								) ,
								'el_class' => array(
									'id' => 'el_class',
									'value' => '',
									'type' => 'text',
								) ,
							) ,
						) ,
					) ,
				) ,
			) ,
			'params' => array(
				'flex_layout' => array(
					'id' => 'flex_layout',
					'value' => 'flex-middle',
					'type' => 'selector',
				) ,
				'height' => array(
					'id' => 'height',
					'value' => 42,
					'type' => 'slider',
				) ,
				'mobile_height' => array(
					'id' => 'mobile_height',
					'value' => 40,
					'type' => 'slider',
				) ,
				'hide_desktop' => array(
					'id' => 'hide_desktop',
					'value' => false,
					'type' => 'switcher',
				) ,
				'hide_mobile' => array(
					'id' => 'hide_mobile',
					'value' => false,
					'type' => 'switcher',
				) ,
				'sticky' => array(
					'id' => 'sticky',
					'value' => false,
					'type' => 'switcher',
				) ,
				'sticky_height' => array(
					'id' => 'sticky_height',
					'value' => 40,
					'type' => 'slider',
				) ,
				'color_scheme' => array(
					'id' => 'color_scheme',
					'value' => 'dark',
					'type' => 'selector',
				) ,
				'background' => array(
					'id' => 'background',
					'value' => array(
						'background-color' => array(
							'r' => 130,
							'g' => 183,
							'b' => 53,
							'a' => 1,
						) ,
					) ,
					'type' => 'bg',
				) ,
				'border' => array(
					'id' => 'border',
					'value' => '',
					'type' => 'border',
				) ,
			) ,
		) ,
		1 => array(
			'id' => 'general-header',
			'type' => 'row',
			'content' => array(
				0 => array(
					'id' => 'column8',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => '250rtwdwz5p8e5b7tpw0',
							'type' => 'logo',
							'params' => array(
								'image' => array(
									'id' => 'image',
									'value' => '',
									'type' => 'image',
								) ,
								'width' => array(
									'id' => 'width',
									'value' => 250,
									'type' => 'slider',
								) ,
								'sticky_image' => array(
									'id' => 'sticky_image',
									'value' => '',
									'type' => 'image',
								) ,
								'sticky_width' => array(
									'id' => 'sticky_width',
									'value' => 150,
									'type' => 'slider',
								) ,
							) ,
						) ,
					) ,
				) ,
				1 => array(
					'id' => 'column9',
					'type' => 'column',
					'content' => array() ,
				) ,
				2 => array(
					'id' => 'column10',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => '2b8mjqhbtvxz16jtxdrd',
							'type' => 'account',
							'params' => array(
								'display' => array(
									'id' => 'display',
									'value' => 'text',
									'type' => 'selector',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
								'with_username' => array(
									'id' => 'with_username',
									'value' => false,
									'type' => 'switcher',
								) ,
								'login_dropdown' => array(
									'id' => 'login_dropdown',
									'value' => true,
									'type' => 'switcher',
								) ,
								'form_display' => array(
									'id' => 'form_display',
									'value' => 'dropdown',
									'type' => 'selector',
								) ,
							) ,
						) ,
						1 => array(
							'id' => 'duljtjrl87kj7pmuut6b',
							'type' => 'search',
							'params' => array(
								'display' => array(
									'id' => 'display',
									'value' => 'full-screen',
									'type' => 'selector',
								) ,
								'search_style' => array(
									'id' => 'search_style',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'categories_dropdown' => array(
									'id' => 'categories_dropdown',
									'value' => false,
									'type' => 'switcher',
								) ,
								'ajax' => array(
									'id' => 'ajax',
									'value' => true,
									'type' => 'switcher',
								) ,
								'ajax_result_count' => array(
									'id' => 'ajax_result_count',
									'value' => 20,
									'type' => 'slider',
								) ,
								'post_type' => array(
									'id' => 'post_type',
									'value' => 'post',
									'type' => 'selector',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
							) ,
						) ,
						2 => array(
							'id' => 'i8977fqp1lmve3hyjltf',
							'type' => 'wishlist',
							'params' => array(
								'design' => array(
									'id' => 'design',
									'value' => 'icon',
									'type' => 'selector',
								) ,
								'hide_product_count' => array(
									'id' => 'hide_product_count',
									'value' => true,
									'type' => 'switcher',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
							) ,
						) ,
						3 => array(
							'id' => '5u866sftq6yga790jxf3',
							'type' => 'cart',
							'params' => array(
								'position' => array(
									'id' => 'position',
									'value' => 'side',
									'type' => 'selector',
								) ,
								'style' => array(
									'id' => 'style',
									'value' => '2',
									'type' => 'selector',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'bag',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
							) ,
						) ,
					) ,
				) ,
				3 => array(
					'id' => 'column_mobile2',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => 'wn5z894j1g5n0yp3eeuz',
							'type' => 'burger',
							'params' => array(
								'style' => array(
									'id' => 'style',
									'value' => 'text',
									'type' => 'selector',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
								'position' => array(
									'id' => 'position',
									'value' => 'left',
									'type' => 'selector',
								) ,
								'search_form' => array(
									'id' => 'search_form',
									'value' => true,
									'type' => 'switcher',
								) ,
								'categories_menu' => array(
									'id' => 'categories_menu',
									'value' => true,
									'type' => 'switcher',
								) ,
								'menu_id' => array(
									'id' => 'menu_id',
									'value' => '',
									'type' => 'select',
								) ,
							) ,
						) ,
					) ,
				) ,
				4 => array(
					'id' => 'column_mobile3',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => 'g5z57bkgtznbk6v9pll5',
							'type' => 'logo',
							'params' => array(
								'image' => array(
									'id' => 'image',
									'value' => '',
									'type' => 'image',
								) ,
								'width' => array(
									'id' => 'width',
									'value' => 140,
									'type' => 'slider',
								) ,
								'sticky_image' => array(
									'id' => 'sticky_image',
									'value' => '',
									'type' => 'image',
								) ,
								'sticky_width' => array(
									'id' => 'sticky_width',
									'value' => 150,
									'type' => 'slider',
								) ,
							) ,
						) ,
					) ,
				) ,
				5 => array(
					'id' => 'column_mobile4',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => 'u6cx6mzhiof1qeysah9h',
							'type' => 'cart',
							'params' => array(
								'position' => array(
									'id' => 'position',
									'value' => 'side',
									'type' => 'selector',
								) ,
								'style' => array(
									'id' => 'style',
									'value' => '5',
									'type' => 'selector',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'bag',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
							) ,
						) ,
					) ,
				) ,
			) ,
			'params' => array(
				'flex_layout' => array(
					'id' => 'flex_layout',
					'value' => 'flex-middle',
					'type' => 'selector',
				) ,
				'height' => array(
					'id' => 'height',
					'value' => 104,
					'type' => 'slider',
				) ,
				'mobile_height' => array(
					'id' => 'mobile_height',
					'value' => 60,
					'type' => 'slider',
				) ,
				'hide_desktop' => array(
					'id' => 'hide_desktop',
					'value' => false,
					'type' => 'switcher',
				) ,
				'hide_mobile' => array(
					'id' => 'hide_mobile',
					'value' => false,
					'type' => 'switcher',
				) ,
				'sticky' => array(
					'id' => 'sticky',
					'value' => false,
					'type' => 'switcher',
				) ,
				'sticky_height' => array(
					'id' => 'sticky_height',
					'value' => 60,
					'type' => 'slider',
				) ,
				'color_scheme' => array(
					'id' => 'color_scheme',
					'value' => 'dark',
					'type' => 'selector',
				) ,
				'background' => array(
					'id' => 'background',
					'value' => '',
					'type' => 'bg',
				) ,
				'border' => array(
					'id' => 'border',
					'value' => array(
						'width' => '1',
						'color' => array(
							'r' => 129,
							'g' => 129,
							'b' => 129,
							'a' => 0.200000000000000011102230246251565404236316680908203125,
						) ,
						'sides' => array(
							0 => 'bottom',
						) ,
					) ,
					'type' => 'border',
				) ,
			) ,
		) ,
		2 => array(
			'id' => 'header-bottom',
			'type' => 'row',
			'content' => array(
				0 => array(
					'id' => 'column11',
					'type' => 'column',
					'content' => array(
						0 => array(
							'id' => 'tiueim5f5uazw1f1dm8r',
							'type' => 'mainmenu',
							'params' => array(
								'menu_style' => array(
									'id' => 'menu_style',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'menu_align' => array(
									'id' => 'menu_align',
									'value' => 'left',
									'type' => 'selector',
								) ,
								'full_screen' => array(
									'id' => 'full_screen',
									'value' => false,
									'type' => 'switcher',
								) ,
								'icon_type' => array(
									'id' => 'icon_type',
									'value' => 'default',
									'type' => 'selector',
								) ,
								'custom_icon' => array(
									'id' => 'custom_icon',
									'value' => '',
									'type' => 'image',
								) ,
							) ,
						) ,
					) ,
				) ,
				1 => array(
					'id' => 'column12',
					'type' => 'column',
					'content' => array() ,
				) ,
				2 => array(
					'id' => 'column13',
					'type' => 'column',
					'content' => array() ,
				) ,
				3 => array(
					'id' => 'column_mobile5',
					'type' => 'column',
					'content' => array() ,
				) ,
			) ,
			'params' => array(
				'flex_layout' => array(
					'id' => 'flex_layout',
					'value' => 'flex-middle',
					'type' => 'selector',
				) ,
				'height' => array(
					'id' => 'height',
					'value' => 50,
					'type' => 'slider',
				) ,
				'mobile_height' => array(
					'id' => 'mobile_height',
					'value' => 50,
					'type' => 'slider',
				) ,
				'hide_desktop' => array(
					'id' => 'hide_desktop',
					'value' => false,
					'type' => 'switcher',
				) ,
				'hide_mobile' => array(
					'id' => 'hide_mobile',
					'value' => true,
					'type' => 'switcher',
				) ,
				'sticky' => array(
					'id' => 'sticky',
					'value' => false,
					'type' => 'switcher',
				) ,
				'sticky_height' => array(
					'id' => 'sticky_height',
					'value' => 50,
					'type' => 'slider',
				) ,
				'color_scheme' => array(
					'id' => 'color_scheme',
					'value' => 'dark',
					'type' => 'selector',
				) ,
				'background' => array(
					'id' => 'background',
					'value' => '',
					'type' => 'bg',
				) ,
				'border' => array(
					'id' => 'border',
					'value' => '',
					'type' => 'border',
				) ,
			) ,
		) ,
	) ,
);

return apply_filters( 'woodmart_default_header_structure', $header_structure );
