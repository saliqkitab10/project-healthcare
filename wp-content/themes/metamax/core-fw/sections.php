<?php

function cwsfw_get_sections() {

	$l_components = cwsfw_get_local_components();
	$g_components = array();
	if (function_exists('cws_core_get_base_components')) {
		$g_components = cws_core_get_base_components();
		$g_components = cws_core_merge_components($g_components, $l_components);
	}

	$settings = array(
		'general_setting' => array(
			'type' => 'section',
			'title' => esc_html__( 'Header', 'metamax' ),
			'icon' => array('fa', 'header'),
			'layout' => array(
				'general_cont' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Header', 'metamax' ),
					'layout' => array(

						'header' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'order' => array(
									'type' => 'group',
									'addrowclasses' => 'group sortable drop grid-col-12 no_overflow',
									'tooltip' => array(
										'title' => esc_html__( 'Header Order', 'metamax' ),
										'content' => esc_html__( 'Drag to reorder and customize the header.', 'metamax' ),
									),
									'title' => esc_html__('Header order', 'metamax' ),
									'button_title' => esc_html__('Add new sidebar', 'metamax' ),
									'value' => array(
										array('title' => esc_html__('Top Bar', 'metamax'),'val' => 'top_bar_box'),
										array('title' => esc_html__('Header Zone', 'metamax'),'val' => 'drop_zone_start'),
										array('title' => esc_html__('Logo', 'metamax'),'val' => 'logo_box'),
										array('title' => esc_html__('Menu', 'metamax'),'val' => 'menu_box'),
										array('title' => esc_html__('Header Zone', 'metamax'),'val' => 'drop_zone_end'),
										array('title' => esc_html__('Title area', 'metamax'),'val' => 'title_box'),
									),
									'layout' => array(
										'title' => array(
											'type' => 'text',
											'value' => '',
											'atts' => 'data-role="title"',
											'title' => esc_html__('Sidebar', 'metamax' ),
										),

										'val' => array(
											'type' => 'text',
										)

									)
								),
								'outside_slider' => array(
									'title' => esc_html__( 'Header Overlays Slider', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-4',
									'type' => 'checkbox',
                                    'atts' => 'checked',
								),					
								'customize' => array(
									'title' => esc_html__( 'Customize', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-4',
									'type' => 'checkbox',
									'atts' => 'checked data-options="e:background_image;e:overlay;e:spacings;e:override_topbar_color;e:override_menu_color;e:override_menu_color_hover;e:override_topbar_color_hover;"',
								),
								'background_image' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 inside-box groups',
									'layout' => '%image_layout%',
								),
								'overlay' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 disable inside-box groups',
									'layout' => array(
										'type'	=> array(
											'title'		=> esc_html__( 'Add Color overlay', 'metamax' ),
											'addrowclasses' => 'grid-col-4',
											'type'	=> 'select',
											'source'	=> array(
												'none' => array( esc_html__( 'None', 'metamax' ),  true, 'd:opacity;d:color;d:gradient;' ),
												'color' => array( esc_html__( 'Color', 'metamax' ),  false, 'e:opacity;e:color;d:gradient;' ),
												'gradient' => array( esc_html__('Gradient', 'metamax' ), false, 'e:opacity;d:color;e:gradient;' )
											),
										),
										'color'	=> array(
											'title'	=> esc_html__( 'Color', 'metamax' ),
											'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
											'addrowclasses' => 'grid-col-4',
											'value' => METAMAX_FIRST_COLOR,
											'type'	=> 'text',
										),
										'opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'Opacity (%)', 'metamax' ),
											'placeholder' => esc_html__( 'In percents', 'metamax' ),
											'value' => '100',
											'addrowclasses' => 'grid-col-4',
										),
										'gradient' => array(
											'type' => 'fields',
											'addrowclasses' => 'grid-col-12 disable box inside-box groups',
											'layout' => '%gradient_layout%',
										),
									),
								),
								'override_menu_color'	=> array(
									'title'	=> esc_html__( 'Override Menu\'s Font Color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'addrowclasses' => 'grid-col-6 disable',
									'value' => '#ffffff',
									'type'	=> 'text',
								),
								'override_menu_color_hover'	=> array(
									'title'	=> esc_html__( 'Override Menu\'s Font Color on Hover', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'addrowclasses' => 'grid-col-6 disable',
									'value' => '#ffffff',
									'type'	=> 'text',
								),
								'override_topbar_color'	=> array(
									'title'	=> esc_html__( 'Override TopBar\'s Font Color', 'metamax' ),
									'atts' => 'data-default-color="#c5cfff"',
									'addrowclasses' => 'grid-col-6 disable',
									'value' => '#c5cfff',
									'type'	=> 'text',
								),
								'override_topbar_color_hover' => array(
									'title'	=> esc_html__( 'Override TopBar\'s Font Color on Hover', 'metamax' ),
									'atts' => 'data-default-color="#feb556"',
									'addrowclasses' => 'grid-col-6 disable',
									'value' => '#feb556',
									'type'	=> 'text',
								),
								'spacings' => array(
									'title' => esc_html__( 'Add Spacings', 'metamax' ),
									'type' => 'margins',
									'addrowclasses' => 'disable grid-col-4 two-inputs',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '10'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '10'),
									),
								),
							),
						),

					)
				),
				'logo_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Logo', 'metamax' ),
					'layout' => array(

						'logo_box' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'enable' => array(
									'title' => esc_html__( 'Logo', 'metamax' ),
									'addrowclasses' => 'checkbox alt grid-col-12',
									'type' => 'checkbox',
									'atts' => 'checked',
								),															
								'default'	=> array(
									'title'		=> esc_html__( 'Default Logo Variation', 'metamax' ),
									'addrowclasses' => 'grid-col-12',
									'type'	=> 'select',
									'source'	=> array(
										'dark' => array( esc_html__( 'Dark', 'metamax' ),  false, '' ),
										'light' => array( esc_html__( 'Light', 'metamax' ),  true, '' ),
									),
								),
								'dark' => array(
									'title' => esc_html__( 'Dark Logo', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'addrowclasses' => 'grid-col-6',
									'layout' => array(
										'is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution logo', 'metamax' ),
											'addrowclasses' => 'checkbox',
											'type' => 'checkbox',
										),
									),
								),
								'light' => array(
									'title' => esc_html__( 'Light Logo', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'addrowclasses' => 'grid-col-6',
									'layout' => array(
										'is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution logo', 'metamax' ),
											'addrowclasses' => 'checkbox',
											'type' => 'checkbox',
										),
									),
								),
								'dimensions' => array(
									'title' => esc_html__( 'Dimensions', 'metamax' ),
									'type' => 'dimensions',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'width' => array('placeholder' => esc_html__( 'Width', 'metamax' ), 'value' => ''),
										'height' => array('placeholder' => esc_html__( 'Height', 'metamax' ), 'value' => ''),
									),
								),				
																				
								'position' => array(
									'title' => esc_html__( 'Position', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'left' => array( esc_html__('Left', 'metamax'), true, 'd:site_name_in_menu;e:with_site_name;', '/img/align-left.png' ),
										'center' =>array( esc_html__('Center', 'metamax'), false, 'e:site_name_in_menu;e:with_site_name;', '/img/align-center.png', ),
										'right' =>array( esc_html__('Right', 'metamax'), false, 'd:site_name_in_menu;e:with_site_name;', '/img/align-right.png', ),
									),
								),
								'margin' => array(
									'title' => esc_html__( 'Margins (px)', 'metamax' ),
									'type' => 'margins',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '0'),
										'left' => array('placeholder' => esc_html__( 'left', 'metamax' ), 'value' => '0'),
										'right' => array('placeholder' => esc_html__( 'Right', 'metamax' ), 'value' => '0'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '0'),
									),
								),														
								'in_menu' => array(
									'title' => esc_html__( 'Logo in menu box', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
									'atts' => 'checked data-options="d:wide;d:overlay;d:border;"',
								),
								'wide' => array(
									'title' => esc_html__( 'Apply Full-Width Container', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
								),

								'overlay' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => array(
										'type'	=> array(
											'title'		=> esc_html__( 'Add Color overlay', 'metamax' ),
											'addrowclasses' => 'grid-col-4',
											'type'	=> 'select',
											'source'	=> array(
												'none' => array( esc_html__( 'None', 'metamax' ),  true, 'd:opacity;d:color;d:gradient;' ),
												'color' => array( esc_html__( 'Color', 'metamax' ),  false, 'e:opacity;e:color;d:gradient;' ),
												'gradient' => array( esc_html__('Gradient', 'metamax' ), false, 'e:opacity;d:color;e:gradient;' )
											),
										),
										'color'	=> array(
											'title'	=> esc_html__( 'Color', 'metamax' ),
											'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
											'addrowclasses' => 'grid-col-4',
											'value' => METAMAX_FIRST_COLOR,
											'type'	=> 'text',
										),
										'opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'Opacity (%)', 'metamax' ),
											'placeholder' => esc_html__( 'In percents', 'metamax' ),
											'value' => '40',
											'addrowclasses' => 'grid-col-4',
										),
										'gradient' => array(
											'type' => 'fields',
											'addrowclasses' => 'grid-col-12 disable box inside-box groups',
											'layout' => '%gradient_layout%',
										),
									),
								),
								'border' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 inside-box groups',
									'layout' => '%border_layout%',
								),
							),
						),

					)
				),
				'menu_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Menu', 'metamax' ),
					'layout' => array(

						'menu_box' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'enable' => array(
									'title' => esc_html__( 'Menu', 'metamax' ),
									'addrowclasses' => 'checkbox alt grid-col-12',
									'type' => 'checkbox',
									'atts' => 'checked',
								),
								'position' => array(
									'title' => esc_html__( 'Menu Alignment', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'left' => array( esc_html__( 'Left', 'metamax' ), 	false, '', '/img/align-left.png' ),
										'center' =>array( esc_html__( 'Center', 'metamax' ), false, '', '/img/align-center.png' ),
										'right' =>array( esc_html__( 'Right', 'metamax' ), true, '', '/img/align-right.png' ),
									),
								),
								'search_place' => array(
									'title' => esc_html__( 'Search Icon Location', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'none' => array( esc_html__( 'None', 'metamax' ), 	false, '', '/img/no_layout.png' ),
										'top' => array( esc_html__( 'Top', 'metamax' ), 	false, '', '/img/search-social-right.png' ),
										'left' =>array( esc_html__( 'Left', 'metamax' ), false, '', '/img/search-menu-left.png' ),
										'right' =>array( esc_html__( 'Right', 'metamax' ), true, '', '/img/search-menu-right.png' ),
									),
								),							
								'mobile_place' => array(
									'title' => esc_html__( 'Mobile Menu Location', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'left' =>array( esc_html__( 'Left', 'metamax' ), true, '', '/img/hamb-left.png' ),
										'right' =>array( esc_html__( 'Right', 'metamax' ), false, '', '/img/hamb-right.png' ),
									),
								),
								'background_color' => array(
									'title' => esc_html__( 'Background color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'value' => '#ffffff',
									'addrowclasses' => 'grid-col-6',
									'type' => 'text',
								),
								'background_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Opacity', 'metamax' ),
									'placeholder' => esc_html__( 'In percents', 'metamax' ),
									'addrowclasses' => 'grid-col-6',
									'value' => '0'
								),
								'font_color' => array(
									'type' => 'text',
									'title' => esc_html__( 'Override Font color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'value' => '#ffffff',
									'addrowclasses' => 'grid-col-6',
								),
								'font_color_hover' => array(
									'type' => 'text',
									'title' => esc_html__( 'Override Font hover color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'value' => '#ffffff',
									'addrowclasses' => 'grid-col-6',
								),
                                'highlight_color' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Highlight color', 'metamax' ),
                                    'atts' => 'data-default-color="#ffe27a"',
                                    'value' => '#ffe27a',
                                    'addrowclasses' => 'grid-col-12',
                                ),

                                'submenu_font_color' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Submenu item Font color', 'metamax' ),
                                    'atts' => 'data-default-color="#ffffff"',
                                    'value' => '#ffffff',
                                    'addrowclasses' => 'grid-col-6',
                                ),
                                'submenu_font_color_hover' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Submenu item Font color on Hover', 'metamax' ),
                                    'atts' => 'data-default-color="#9c8635"',
                                    'value' => '#9c8635',
                                    'addrowclasses' => 'grid-col-6',
                                ),
                                'submenu_bg_color' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Submenu item Background color', 'metamax' ),
                                    'atts' => 'data-default-color="'.METAMAX_THIRD_COLOR.'"',
                                    'value' => METAMAX_THIRD_COLOR,
                                    'addrowclasses' => 'grid-col-6',
                                ),
                                'submenu_bg_color_hover' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Submenu item Background color on Hover', 'metamax' ),
                                    'atts' => 'data-default-color="#ffe27a"',
                                    'value' => '#ffe27a',
                                    'addrowclasses' => 'grid-col-6',
                                ),

								'border' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => '%border_layout%',
								),
								'margin' => array(
									'title' => esc_html__( 'Add Spacings', 'metamax' ),
									'type' => 'margins',
									'addrowclasses' => 'grid-col-12 two-inputs',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '35'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '35'),
									),
								),
								'menu_mode'	=> array(
									'title'		=> esc_html__( 'Desktop Menu on Devices', 'metamax' ),
									'type'	=> 'select',
									'addrowclasses' => 'grid-col-12',
									'source'	=> array(
										'default' => array( esc_html__( 'Default', 'metamax' ),  true ),
										'portrait' => array( esc_html__( 'Portrait', 'metamax' ), false ),
										'landdscape' => array( esc_html__( 'Landscape', 'metamax' ), false ),
										'both' => array( esc_html__( 'Both', 'metamax' ), false ),
									),
								),				
								'sandwich' => array(
									'title' => esc_html__( 'Use mobile menu on desktop PCs', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-4',
									'type' => 'checkbox',
								),
								'wide' => array(
									'title' => esc_html__( 'Apply Full-Width Menu', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-4',
									'type' => 'checkbox',
								),
							),
						),

					)
				),
				'mobile_menu_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Mobile', 'metamax' ),
					'layout' => array(

						'mobile_menu_box' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'mobile' => array(
									'title' => esc_html__( 'Mobile Logo', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'addrowclasses' => 'grid-col-6',
									'layout' => array(
										'logo_mobile_is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution mobile logo', 'metamax' ),
											'addrowclasses' => 'checkbox',
											'type' => 'checkbox',
										),
									),
								),
								'navigation' => array(
									'title' => esc_html__( 'Navigation Logo', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'addrowclasses' => 'grid-col-6',
									'layout' => array(
										'logo_mobile_is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution mobile logo', 'metamax' ),
											'addrowclasses' => 'checkbox',
											'type' => 'checkbox',
										),
									),
								),
								'dimensions_mobile' => array(
									'title' => esc_html__( 'Mobile Logo Dimensions', 'metamax' ),
									'type' => 'dimensions',
									'addrowclasses' => 'grid-col-6',
									'value' => array(
										'width' => array('placeholder' => esc_html__( 'Width', 'metamax' ), 'value' => ''),
										'height' => array('placeholder' => esc_html__( 'Height', 'metamax' ), 'value' => ''),
									),
								),
								'dimensions_navigation' => array(
									'title' => esc_html__( 'Navigation Logo Dimensions', 'metamax' ),
									'type' => 'dimensions',
									'addrowclasses' => 'grid-col-6',
									'value' => array(
										'width' => array('placeholder' => esc_html__( 'Width', 'metamax' ), 'value' => ''),
										'height' => array('placeholder' => esc_html__( 'Height', 'metamax' ), 'value' => ''),
									),
								),
                                'font_color' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Mobile menu item color', 'metamax' ),
                                    'atts' => 'data-default-color="#0a0202"',
                                    'value' => '#0a0202',
                                    'addrowclasses' => 'grid-col-6',
                                ),
                                'font_color_active' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Mobile menu active item color', 'metamax' ),
                                    'atts' => 'data-default-color="'.METAMAX_FIRST_COLOR.'"',
                                    'value' => METAMAX_FIRST_COLOR,
                                    'addrowclasses' => 'grid-col-6',
                                ),
                                'bg_color' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Mobile menu background color', 'metamax' ),
                                    'atts' => 'data-default-color="#ffffff"',
                                    'value' => '#ffffff',
                                    'addrowclasses' => 'grid-col-6',
                                ),
                                'divider_color' => array(
                                    'type' => 'text',
                                    'title' => esc_html__( 'Mobile menu divider color', 'metamax' ),
                                    'atts' => 'data-default-color="#f0f0f0"',
                                    'value' => '#f0f0f0',
                                    'addrowclasses' => 'grid-col-6',
                                ),
							),
						),

					)
				),
				'sticky_menu_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Sticky', 'metamax' ),
					'layout' => array(

						'sticky_menu' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'enable' => array(
									'title' => esc_html__( 'Sticky menu', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-6 alt',
									'type' => 'checkbox',
								),
								'sticky' => array(
									'title' => esc_html__( 'Sticky Logo', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'addrowclasses' => 'grid-col-6',
									'layout' => array(
										'logo_sticky_is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution sticky logo', 'metamax' ),
											'addrowclasses' => 'checkbox',
											'type' => 'checkbox',
										),
									),
								),
								'dimensions_sticky' => array(
									'title' => esc_html__( 'Sticky Logotype Dimensions', 'metamax' ),
									'type' => 'dimensions',
									'addrowclasses' => 'grid-col-6',
									'value' => array(
										'width' => array('placeholder' => esc_html__( 'Width', 'metamax' ), 'value' => ''),
										'height' => array('placeholder' => esc_html__( 'Height', 'metamax' ), 'value' => ''),
									),
								),
								'mode'	=> array(
									'title'		=> esc_html__( 'Select a Sticky\'s Mode', 'metamax' ),
									'type'	=> 'select',
									'addrowclasses' => 'grid-col-6',
									'source'	=> array(
										'smart' => array( esc_html__( 'Smart', 'metamax' ),  true ),
										'simple' => array( esc_html__( 'Simple', 'metamax' ), false ),
									),
								),
								'margin_sticky' => array(
									'title' => esc_html__( 'Sticky Menu Spacings', 'metamax' ),
									'type' => 'margins',
									'tooltip' => array(
										'title' => esc_html__('Sticky menu spacings', 'metamax'),
										'content' => esc_html__('These values should not exceed the menu spacings, which are set in Menu\'s section', 'metamax'),
									),
									'addrowclasses' => 'grid-col-6 two-inputs',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '12'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '12'),
									),
								),			
								'background_color' => array(
									'title' => esc_html__( 'Background color', 'metamax' ),
									'tooltip' => array(
										'title' => esc_html__( 'Background Color', 'metamax' ),
										'content' => esc_html__( 'This color is applied to header section including top bar.', 'metamax' ),
									),
									'atts' => 'data-default-color="'.METAMAX_FIRST_COLOR.'"',
									'value' => METAMAX_FIRST_COLOR,
									'addrowclasses' => 'grid-col-6',
									'type' => 'text',
								),
								'background_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Background Opacity', 'metamax' ),
									'tooltip' => array(
										'title' => esc_html__( 'Background Opacity', 'metamax' ),
										'content' => esc_html__( 'This option will apply the transparent header when set to "0".', 'metamax' ),
									),
									'placeholder' => esc_html__( 'In percents', 'metamax' ),
									'addrowclasses' => 'grid-col-6',
									'value' => '100'
								),
								'font_color' => array(
									'title' => esc_html__( 'Override Font color', 'metamax' ),
									'tooltip' => array(
										'title' => esc_html__( 'Override Font Color', 'metamax' ),
										'content' => esc_html__( 'This color is applied to main menu items and menu icons, submenus will use the color which is set in Typography section.<br /> This option is very useful when transparent menu is set.', 'metamax' ),
									),
									'atts' => 'data-default-color="#ffffff"',
									'value' => '#ffffff',
									'addrowclasses' => 'grid-col-6',
									'type' => 'text',
								),
								'font_color_hover' => array(
									'title' => esc_html__( 'Override Font color on hover', 'metamax' ),
									'tooltip' => array(
										'title' => esc_html__( 'Override Font Color on hover', 'metamax' ),
										'content' => esc_html__( 'This color is applied to main menu items and menu icons on mouse hover, submenus will use the color which is set in Typography section.<br /> This option is very useful when transparent menu is set.', 'metamax' ),
									),
									'atts' => 'data-default-color="#ffe27a"',
									'value' => '#ffe27a',
									'addrowclasses' => 'grid-col-6',
									'type' => 'text',
								),
								'border' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => '%border_layout%',
								),
								'shadow' => array(
									'title' => esc_html__( 'Add Shadow', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
                                    'atts' => 'checked',
								),
							),
						),

					)
				),
				'title_area_cont' => array(
					'type' => 'tab',
					'icon' => array( 'fas', 'fa-book' ),
					'title' => esc_html__( 'Title', 'metamax' ),
					'layout' => array(

						'title_box' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'enable' => array(
									'title' => esc_html__( 'Title area', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12 alt',
									'type' => 'checkbox',
									'atts' => 'checked',
								),							
								'no_title' => array(
									'title' => esc_html__( 'Hide Page Title', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
								),	
								'customize' => array(
									'title' => esc_html__( 'Customize', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
									'atts' => 'checked data-options="e:show_on_posts;e:show_on_archives;e:font_color;e:helper_font_color;e:helper_hover_font_color;e:background_image;e:subtitle_content;e:overlay;e:use_pattern;e:effect;e:spacings;e:border;"',
								),
								'show_on_posts' => array(
									'title' => esc_html__( 'Use Custom Settings on Posts', 'metamax' ),
									'addrowclasses' => 'disable checkbox grid-col-4',
									'type' => 'checkbox',
									'atts' => 'disable checked',
								),
								'show_on_archives' => array(
									'title' => esc_html__( 'Use Custom Settings on Archives', 'metamax' ),
									'addrowclasses' => 'disable checkbox grid-col-6',
									'type' => 'checkbox',
									'atts' => 'disable checked',
								),
								'background_image' => array(
									'type' => 'fields',
									'addrowclasses' => 'disable box grid-col-12 inside-box groups',
									'layout' => '%image_layout%',
								),
                                'subtitle_content' => array(
                                    'title' => esc_html__( 'Subtitle Content', 'metamax' ),
                                    'addrowclasses' => 'disable grid-col-12 full_row',
                                    'type' => 'textarea',
                                    'atts' => 'rows="2"',
                                ),
								'spacings' => array(
									'title' => esc_html__( 'Add Top/Bottom Spacings', 'metamax' ),
									'type' => 'margins',
									'addrowclasses' => 'disable two-inputs grid-col-6',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '37'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '36'),
									),
								),
								'font_color' => array(
									'title'	=> esc_html__( 'Override Title Color', 'metamax' ),
									'atts' => 'data-default-color="#ffe27a"',
									'value' => '#ffe27a',
									'addrowclasses' => 'disable grid-col-6',
									'type'	=> 'text',
								),
								'helper_font_color' => array(
									'title'	=> esc_html__( 'Subtitle content/Breadcrumbs Font Color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'value' => '#ffffff',
									'addrowclasses' => 'disable grid-col-6',
									'type'	=> 'text',
								),
								'helper_hover_font_color' => array(
									'title'	=> esc_html__( 'Breadcrumbs hover Font Color', 'metamax' ),
									'atts' => 'data-default-color="#ffe27a"',
									'value' => '#ffe27a',
									'addrowclasses' => 'disable grid-col-6',
									'type'	=> 'text',
								),
								'overlay' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 disable box inside-box groups',
									'layout' => array(
										'type'	=> array(
											'title'		=> esc_html__( 'Add Color overlay', 'metamax' ),
											'addrowclasses' => 'grid-col-4',
											'type'	=> 'select',
											'source'	=> array(
												'none' => array( esc_html__( 'None', 'metamax' ),  true, 'd:opacity;d:color;d:gradient;' ),
												'color' => array( esc_html__( 'Color', 'metamax' ),  false, 'e:opacity;e:color;d:gradient;' ),
												'gradient' => array( esc_html__('Gradient', 'metamax' ), false, 'e:opacity;d:color;e:gradient;' )
											),
										),
										'color'	=> array(
											'title'	=> esc_html__( 'Color', 'metamax' ),
											'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
											'addrowclasses' => 'grid-col-4',
											'value' => METAMAX_FIRST_COLOR,
											'type'	=> 'text',
										),
										'opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'Opacity (%)', 'metamax' ),
											'placeholder' => esc_html__( 'In percents', 'metamax' ),
											'value' => '100',
											'addrowclasses' => 'grid-col-4',
										),
										'gradient' => array(
											'type' => 'fields',
											'addrowclasses' => 'grid-col-12 disable box inside-box groups',
											'layout' => '%gradient_layout%',
										),
									),
								),
								'use_pattern' => array(
									'title' => esc_html__( 'Add pattern', 'metamax' ),
									'addrowclasses' => 'disable checkbox grid-col-12',
									'type' => 'checkbox',
									'atts' => 'data-options="e:pattern_image;"',
								),
								'pattern_image' => array(
									'type' => 'fields',
									'title' => esc_html__( 'Pattern image', 'metamax' ),
									'addrowclasses' => 'disable box grid-col-12 inside-box groups',
									'layout' => '%image_layout%',
								),
								'border' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => '%border_layout%',
								),
							),
						),

					)
				),
				'top_bar_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Top Bar', 'metamax' ),
					'layout' => array(

						'top_bar_box' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'enable' => array(
									'title' => esc_html__( 'Customize', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox alt',
									'type' => 'checkbox',
								),
								'wide' => array(
									'title' => esc_html__( 'Apply Full-Width Top Bar', 'metamax' ),
									'addrowclasses' => 'grid-col-4 checkbox',
									'type' => 'checkbox',
								),							
								'language_bar' => array(
									'title' => esc_html__( 'Add Language Bar', 'metamax' ),
									'addrowclasses' => 'grid-col-4 checkbox',
									'atts' => 'data-options="e:language_bar_position;"',
									'type' => 'checkbox',
								),
								'social_place' => array(
									'title' => esc_html__( 'Social Icons Alignment', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-6',
									'value' => array(
										'left' =>array( esc_html__( 'Left', 'metamax' ), false, '', '/img/social-left.png' ),
										'right' =>array( esc_html__( 'Right', 'metamax' ), true, '', '/img/social-right.png' ),
									),
								),								
								'language_bar_position' => array(
									'title' => esc_html__( 'Language Bar Alignment', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'disable grid-col-6',
									'value' => array(
										'left' => array( esc_html__( 'Left', 'metamax' ), 	false, '', '/img/multilingual-left.png' ),
										'right' =>array( esc_html__( 'Right', 'metamax' ), true, '', '/img/multilingual-right.png' ),
									),
								),
                                'text_position' => array(
                                    'title' => esc_html__( 'Content text position', 'metamax' ),
                                    'type' => 'radio',
                                    'subtype' => 'images',
                                    'addrowclasses' => 'disable grid-col-6',
                                    'value' => array(
                                        'left' => array( esc_html__( 'Left', 'metamax' ), 	false, '', '/img/multilingual-left.png' ),
                                        'right' =>array( esc_html__( 'Right', 'metamax' ), true, '', '/img/multilingual-right.png' ),
                                    ),
                                ),
                                'info_position' => array(
                                    'title' => esc_html__( 'Info position', 'metamax' ),
                                    'type' => 'radio',
                                    'subtype' => 'images',
                                    'addrowclasses' => 'disable grid-col-6',
                                    'value' => array(
                                        'left' => array( esc_html__( 'Left', 'metamax' ), 	false, '', '/img/multilingual-left.png' ),
                                        'right' =>array( esc_html__( 'Right', 'metamax' ), true, '', '/img/multilingual-right.png' ),
                                    ),
                                ),
								'toggle_share' => array(
									'title' => esc_html__( 'Toggle Social Icons', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox',
									'atts' => 'checked',
									'type' => 'checkbox',
								),
								'text' => array(
									'title' => esc_html__( 'Content', 'metamax' ),
									'addrowclasses' => 'grid-col-8 full_row',
									'tooltip' => array(
										'title' => esc_html__( 'Indent Adjusting', 'metamax' ),
										'content' => esc_html__( 'Adjust Indents by multiple spaces.<br /> Line breaks are working too.', 'metamax' ),
									),
									'type' => 'text'
								),
								'content_items' => array(
									'type' => 'group',
									'addrowclasses' => 'grid-col-12 group expander sortable box',
									'title' => esc_html__('Top Bar Info', 'metamax' ),
									'button_title' => esc_html__('Add new info row', 'metamax' ),
									'layout' => array(
										'icon' => array(
											'type' => 'select',
											'addrowclasses' => 'grid-col-3 fai',
											'source' => 'fa',
											'title' => esc_html__('Select the icon', 'metamax' )
										),
										'title' => array(
											'type' => 'text',
											'atts' => 'data-role="title"',
											'addrowclasses' => 'grid-col-3',
											'title' => esc_html__('Write main info', 'metamax' ),
										),
										'url' => array(
											'type' => 'text',
											'addrowclasses' => 'grid-col-3',
											'title' => esc_html__('Write URL', 'metamax' ),
										),
										'link_type' => array(
											'type' => 'select',
											'addrowclasses' => 'grid-col-3 fai',
											'source' => array(
												'link' => array( esc_html__( 'Link', 'metamax' ),  true, '' ),
												'mailto:' => array( esc_html__( 'Email', 'metamax' ),  false, '' ),
												'skype:' => array( esc_html__( 'Skype', 'metamax' ),  false, '' ),
												'tel:' => array( esc_html__( 'Phone', 'metamax' ),  false, '' ),
											),
											'title' => esc_html__('Select link type', 'metamax' )
										),
									),
								),
								'background_color' => array(
									'title' => esc_html__( 'Customize Background', 'metamax' ),
									'atts' => 'data-default-color="#1b2e7d"',
									'value' => '#1b2e7d',
									'addrowclasses' => 'new_row grid-col-3',
									'type' => 'text',
								),
								'font_color' => array(
									'title' => esc_html__( 'Font Color', 'metamax' ),
									'atts' => 'data-default-color="#c5cfff"',
									'value' => '#c5cfff',
									'addrowclasses' => 'grid-col-3',
									'type' => 'text',
								),
								'hover_font_color' => array(
									'title' => esc_html__( 'Hover Color', 'metamax' ),
									'atts' => 'data-default-color="#feb556"',
									'value' => '#feb556',
									'addrowclasses' => 'grid-col-3',
									'type' => 'text',
								),									
								'background_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Opacity (%)', 'metamax' ),
									'placeholder' => esc_html__( 'In percents', 'metamax' ),
									'value' => '100',
									'addrowclasses' => 'grid-col-3',
								),
								'border' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => '%border_layout%',
								),
								'spacings' => array(
									'title' => esc_html__( 'Add Spacings (px)', 'metamax' ),
									'type' => 'margins',
									'addrowclasses' => 'new_row grid-col-12 two-inputs',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '10'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '10'),
									),
								),	
							),
						),

					)
				),
				'side_panel_cont' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Sidebar', 'metamax' ),
					'layout' => array(

						'side_panel' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'enable' => array(
									'title' => esc_html__( 'Side Panel', 'metamax' ),
									'addrowclasses' => 'alt checkbox grid-col-12',
									'type' => 'checkbox',
								),	
								'place' => array(
									'title' => esc_html__( 'Menu Icon Location', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-6',
									'value' => array(
										'topbar_left' =>array( esc_html__( 'TopBar (Left)', 'metamax' ), false, '', '/img/top-hamb-left.png' ),
										'topbar_right' => array( esc_html__( 'TopBar (Right)', 'metamax' ), 	false, '', '/img/top-hamb-right.png' ),
										'menu_left' =>array( esc_html__( 'Menu (Left)', 'metamax' ), true, '', '/img/hamb-left.png' ),
										'menu_right' =>array( esc_html__( 'Menu (Right)', 'metamax' ), false, '', '/img/hamb-right.png' ),
									),
								),
								'position' => array(
									'title' 			=> esc_html__('Side Panel Position', 'metamax' ),
									'type' 				=> 'radio',
									'subtype' 			=> 'images',
									'addrowclasses' => 'grid-col-6',
									'value' 			=> array(
										'left' 				=> 	array( esc_html__('Left', 'metamax' ), true, '',	'/img/left.png' ),
										'right' 			=> 	array( esc_html__('Right', 'metamax' ), false, '', '/img/right.png' ),
									),
								),
								'sidebar' => array(
									'title' 		=> esc_html__('Select the Sidebar Area', 'metamax' ),
									'type' 			=> 'select',
									'addrowclasses' => 'new_row grid-col-6',
									'source' 		=> 'sidebars',
									'value' => 'side_panel',
								),
								'appear'	=> array(
									'title'		=> esc_html__( 'Animation Format', 'metamax' ),
									'type'	=> 'select',
									'addrowclasses' => 'grid-col-6',
									'source'	=> array(
										'fade' => array( esc_html__( 'Fade & Slide', 'metamax' ),  true ),
										'slide' => array( esc_html__( 'Slide', 'metamax' ), false ),
										'pull' => array( esc_html__( 'Pull', 'metamax' ), false ),
									),
								),
								'logo' => array(
									'title' => esc_html__( 'Logo', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'addrowclasses' => 'grid-col-6',
									'layout' => array(
										'is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution logo', 'metamax' ),
											'addrowclasses' => 'checkbox',
											'type' => 'checkbox',
										),
									),
								),
								'logo_position' => array(
									'title' => esc_html__( 'Logo position', 'metamax' ),
									'addrowclasses' => 'grid-col-3',
									'type' => 'radio',
									'value' => array(
										'left' => array( esc_html__( 'Left', 'metamax' ),  true, '' ),
										'center' =>array( esc_html__( 'Center', 'metamax' ), false,  '' ),
										'right' =>array( esc_html__( 'Right', 'metamax' ), false,  '' ),
									),
								),
								'logo_dimensions' => array(
									'title' => esc_html__( 'Logo Dimensions', 'metamax' ),
									'type' => 'dimensions',
									'addrowclasses' => 'grid-col-3',
									'value' => array(
										'width' => array('placeholder' => esc_html__( 'Width', 'metamax' ), 'value' => ''),
										'height' => array('placeholder' => esc_html__( 'Height', 'metamax' ), 'value' => ''),
									),
								),							
								'background_image' => array(
									'title' => esc_html__( 'Background image', 'metamax' ),
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 inside-box groups',
									'layout' => '%image_layout%',
								),
								'overlay' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 inside-box groups',
									'layout' => array(
										'type'	=> array(
											'title'		=> esc_html__( 'Add Color overlay', 'metamax' ),
											'addrowclasses' => 'grid-col-4',
											'type'	=> 'select',
											'source'	=> array(
												'none' => array( esc_html__( 'None', 'metamax' ),  false, 'd:opacity;d:color;d:gradient;' ),
												'color' => array( esc_html__( 'Color', 'metamax' ),  true, 'e:opacity;e:color;d:gradient;' ),
												'gradient' => array( esc_html__('Gradient', 'metamax' ), false, 'e:opacity;d:color;e:gradient;' )
											),
										),
										'color'	=> array(
											'title'	=> esc_html__( 'Color', 'metamax' ),
											'atts' => 'data-default-color="#1b2e7d"',
											'addrowclasses' => 'grid-col-4',
											'value' => '#1b2e7d',
											'type'	=> 'text',
										),
										'opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'Opacity (%)', 'metamax' ),
											'placeholder' => esc_html__( 'In percents', 'metamax' ),
											'value' => '100',
											'addrowclasses' => 'grid-col-4',
										),
										'gradient' => array(
											'type' => 'fields',
											'addrowclasses' => 'grid-col-12 disable box inside-box groups',
											'layout' => '%gradient_layout%',
										),
									),
								),
								'font_color'	=> array(
									'title'	=> esc_html__( 'Font color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'addrowclasses' => 'grid-col-4',
									'value' => '#ffffff',
									'type'	=> 'text',
								),
								'font_color_hover'	=> array(
									'title'	=> esc_html__( 'Font color on Hover', 'metamax' ),
									'atts' => 'data-default-color="#ffe27a"',
									'addrowclasses' => 'grid-col-4',
									'value' => '#ffe27a',
									'type'	=> 'text',
								),
								'fixed_bg' => array(
									'title'	=> esc_html__( 'Fixed info Background', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'addrowclasses' => 'grid-col-4',
									'value' => '#ffffff',
									'type'	=> 'text',
								),
								'add_social' => array(
									'title' => esc_html__( 'Add social icon to Fixed Info Bar', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
								),
								'bottom_bar' => array(
									'type' => 'fields',
									'addrowclasses' => 'inside-box groups grid-col-12 box',
									'layout' => array(
										'info_icons' => array(
											'type' => 'group',
											'addrowclasses' => 'group sortable grid-col-12 box',
											'title' => esc_html__('Fixed Information', 'metamax' ),
											'button_title' => esc_html__('Add new information row', 'metamax' ),
											'button_icon' => 'fas fa-plus',
											'layout' => array(
												'title' => array(
													'type' => 'text',
													'atts' => 'data-role="title"',
													'addrowclasses' => 'grid-col-3',
													'title' => esc_html__('Title', 'metamax' ),
												),
												'url' => array(
													'type' => 'text',
													'addrowclasses' => 'grid-col-3',
													'title' => esc_html__('Link', 'metamax' ),
												),
												'icon' => array(
													'type' => 'select',
													'addrowclasses' => 'fai grid-col-3',
													'source' => 'fa',
													'title' => esc_html__('Icon', 'metamax' )
												),
												'link_type' => array(
													'type' => 'select',
													'addrowclasses' => 'grid-col-3',
													'source' => array(
														'link' => array( esc_html__( 'Link', 'metamax' ),  true, '' ),
														'mailto:' => array( esc_html__( 'Email', 'metamax' ),  false, '' ),
														'skype:' => array( esc_html__( 'Skype', 'metamax' ),  false, '' ),
														'tel:' => array( esc_html__( 'Phone', 'metamax' ),  false, '' ),
													),
													'title' => esc_html__('Select link type', 'metamax' )
												),
											)
										),																
									),
								),
							),
						),
					)
				),
			)
		),
		// end of sections
		'footer_options' => array(
			'type' => 'section',
			'title' => esc_html__('Footer', 'metamax' ),
			'icon' => array('fa', 'list-alt'),
			'layout' => array(
				'footer_cont' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array( 'fas', 'fa-book' ),
					'title' => esc_html__( 'Footer', 'metamax' ),
					'layout' => array(

						'footer' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box main-box',
							'layout' => array(
								'wide' => array(
									'title' => esc_html__( 'Apply Full-Width Footer', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-12',
									'type' => 'checkbox',
								),
                                'logo_enable' => array(
                                    'title' => esc_html__( 'Enable Footer Logo', 'metamax' ),
                                    'addrowclasses' => 'checkbox grid-col-12',
                                    'type' => 'checkbox',
                                    'atts' => 'checked data-options="e:logo;e:dimensions;"',
                                ),
                                'logo' => array(
                                    'title' => esc_html__( 'Footer Logo', 'metamax' ),
                                    'type' => 'media',
                                    'url-atts' => 'readonly',
                                    'addrowclasses' => 'grid-col-6 disable',
                                    'layout' => array(
                                        'is_high_dpi' => array(
                                            'title' => esc_html__( 'High-Resolution footer logo', 'metamax' ),
                                            'addrowclasses' => 'checkbox',
                                            'type' => 'checkbox',
                                        ),
                                    ),
                                ),
                                'dimensions' => array(
                                    'title' => esc_html__( 'Dimensions', 'metamax' ),
                                    'type' => 'dimensions',
                                    'addrowclasses' => 'grid-col-6 disable',
                                    'value' => array(
                                        'width' => array('placeholder' => esc_attr__( 'Width', 'metamax' ), 'value' => ''),
                                        'height' => array('placeholder' => esc_attr__( 'Height', 'metamax' ), 'value' => ''),
                                    ),
                                ),
                                'icon_enable' => array(
                                    'title' => esc_html__( 'Enable Footer Icon', 'metamax' ),
                                    'addrowclasses' => 'checkbox grid-col-12',
                                    'type' => 'checkbox',
                                    'atts' => 'checked data-options="e:icon_color;e:icon_bg_color;"',
                                ),
                                'icon_color' => array(
                                    'title' => esc_html__( 'Footer Icon Color', 'metamax' ),
                                    'atts' => 'data-default-color="#9d5f36"',
                                    'value' => '#9d5f36',
                                    'addrowclasses' => 'grid-col-6 disable',
                                    'type' => 'text',
                                ),
                                'icon_bg_color' => array(
                                    'title' => esc_html__( 'Footer Icon Background Color', 'metamax' ),
                                    'atts' => 'data-default-color="#ffe27a"',
                                    'value' => '#ffe27a',
                                    'addrowclasses' => 'grid-col-6 disable',
                                    'type' => 'text',
                                ),

                                'layout' => array(
									'type' => 'select',
									'title' => esc_html__( 'Select a layout', 'metamax' ),
									'addrowclasses' => 'grid-col-4',
									'source' => array(
										'1' => array( esc_html__( '1/1 Column', 'metamax' ),  false ),
										'2' => array( esc_html__( '2/2 Column', 'metamax' ), false ),
										'3' => array( esc_html__( '3/3 Column', 'metamax' ), false ),
										'4' => array( esc_html__( '4/4 Column', 'metamax' ), true ),
										'66-33' => array( esc_html__( '2/3 + 1/3 Column', 'metamax' ), false ),
										'33-66' => array( esc_html__( '1/3 + 2/3 Column', 'metamax' ), false ),
										'25-75' => array( esc_html__( '1/4 + 3/4 Column', 'metamax' ), false ),
										'25-25-50' => array( esc_html__( '1/4 + 1/4 + 2/4 Column', 'metamax' ), false ),
										'50-25-25' => array( esc_html__( '2/4 + 1/4 + 1/4 Column', 'metamax' ), false ),
										'25-50-25' => array( esc_html__( '1/4 + 2/4 + 1/4 Column', 'metamax' ), false ),
									),
								),
								'sidebar' => array(
									'title' 		=> esc_html__('Select Footer\'s Sidebar Area', 'metamax' ),
									'type' 			=> 'select',
									'addrowclasses' => 'grid-col-4',
									'source' 		=> 'sidebars',
                                    'value'         => 'footer',
								),
								'alignment' => array(
									'type' => 'select',
									'title' => esc_html__( 'Copyrights alignment', 'metamax' ),
									'addrowclasses' => 'grid-col-4',
									'source' => array(
										'left' => array( esc_html__( 'Left', 'metamax' ),  true ),
										'center' => array( esc_html__( 'Center', 'metamax' ), false ),
										'right' => array( esc_html__( 'Right', 'metamax' ), false ),
									),
								),
								'copyrights_text' => array(
									'title' => esc_html__( 'Copyrights content', 'metamax' ),
									'type' => 'textarea',
									'addrowclasses' => 'grid-col-12 full_row',
									'value' => esc_html__('Copyright © 2019. All Rights Reserved Metamax', 'metamax'),
									'atts' => 'rows="6"',
								),
                                'footer_info_text' => array(
                                    'title' => esc_html__( 'Footer info content', 'metamax' ),
                                    'type' => 'textarea',
                                    'addrowclasses' => 'grid-col-12 full_row',
                                    'value' => esc_html__("We’re on a mission to build a better future where technology creates good jobs for everyone.", "metamax"),
                                    'atts' => 'rows="6"',
                                ),
								'background_image' => array(
									'type' => 'fields',
									'addrowclasses' => 'box grid-col-12 inside-box groups',
									'layout' => '%image_layout%',
								),
								'title_color' => array(
									'title' => esc_html__( 'Widgets Title Color', 'metamax' ),
									'atts' => 'data-default-color="#ffffff"',
									'value' => '#ffffff',
									'addrowclasses' => 'grid-col-4',
									'type' => 'text',
								),								
								'font_color' => array(
									'title' => esc_html__( 'Font Color', 'metamax' ),
									'atts' => 'data-default-color="#bbd0ff"',
									'value' => '#bbd0ff',
									'addrowclasses' => 'grid-col-4',
									'type' => 'text',
								),
								'font_color_hover' => array(
									'title' => esc_html__( 'Font Color on Hover', 'metamax' ),
									'atts' => 'data-default-color="#ffe27a"',
									'value' => '#ffe27a',
									'addrowclasses' => 'grid-col-4',
									'type' => 'text',
								),
								'copyrights_background_color' => array(
									'title'	=> esc_html__( 'Background Color (Copyrights)', 'metamax' ),
									'atts'	=> 'data-default-color="#0d2969"',
									'value' => '#0d2969',
									'addrowclasses' => 'grid-col-4',
									'type'	=> 'text'
								),
								'copyrights_font_color' => array(
									'title' => esc_html__( 'Font color (Copyrights)', 'metamax' ),
									'atts' => 'data-default-color="#7a94cd"',
									'value' => '#7a94cd',
									'addrowclasses' => 'grid-col-4',
									'type' => 'text',
								),
								'copyrights_hover_color' => array(
									'title' => esc_html__( 'Hover color (Copyrights)', 'metamax' ),
									'atts' => 'data-default-color="#ffe27a"',
									'value' => '#ffe27a',
									'addrowclasses' => 'grid-col-4',
									'type' => 'text',
								),				
								'pattern_image' => array(
									'type' => 'fields',
									'title' => esc_html__( 'Pattern Image', 'metamax' ),
									'addrowclasses' => 'box grid-col-12 inside-box groups',
									'layout' => '%image_layout%',
								),
								'overlay' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => array(
										'type'	=> array(
											'title'		=> esc_html__( 'Color overlay', 'metamax' ),
											'addrowclasses' => 'grid-col-4',
											'type'	=> 'select',
											'source'	=> array(
												'none' => array( esc_html__( 'None', 'metamax' ),  false, 'd:opacity;d:color;d:gradient;' ),
												'color' => array( esc_html__( 'Color', 'metamax' ),  true, 'e:opacity;e:color;d:gradient;' ),
												'gradient' => array( esc_html__('Gradient', 'metamax' ), false, 'e:opacity;d:color;e:gradient;' )
											),
										),
										'color'	=> array(
											'title'	=> esc_html__( 'Color', 'metamax' ),
											'atts' => 'data-default-color="#0d2969"',
											'addrowclasses' => 'grid-col-4',
											'value' => '#0d2969',
											'type'	=> 'text',
											'customizer' => array( 'show' => true )
										),
										'opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'Opacity (%)', 'metamax' ),
											'placeholder' => esc_html__( 'In percents', 'metamax' ),
											'value' => '100',
											'addrowclasses' => 'grid-col-4',
										),
										'gradient' => array(
											'type' => 'fields',
											'addrowclasses' => 'grid-col-12 disable box inside-box groups',
											'layout' => '%gradient_layout%',
										),
									),
								),
								'border' => array(
									'type' => 'fields',
									'addrowclasses' => 'grid-col-12 box inside-box groups',
									'layout' => '%border_layout%',
								),
                                'content_items' => array(
                                    'type' => 'group',
                                    'addrowclasses' => 'grid-col-12 group expander sortable box',
                                    'title' => esc_html__('Top Bar Info', 'metamax' ),
                                    'button_title' => esc_html__('Add new info row', 'metamax' ),
                                    'layout' => array(
                                        'icon' => array(
                                            'type' => 'select',
                                            'addrowclasses' => 'grid-col-3 fai',
                                            'source' => 'fa',
                                            'title' => esc_html__('Select the icon', 'metamax' )
                                        ),
                                        'title' => array(
                                            'type' => 'text',
                                            'atts' => 'data-role="title"',
                                            'addrowclasses' => 'grid-col-3',
                                            'title' => esc_html__('Write main info', 'metamax' ),
                                        ),
                                        'url' => array(
                                            'type' => 'text',
                                            'addrowclasses' => 'grid-col-3',
                                            'title' => esc_html__('Write URL', 'metamax' ),
                                        ),
                                        'link_type' => array(
                                            'type' => 'select',
                                            'addrowclasses' => 'grid-col-3 fai',
                                            'source' => array(
                                                'link' => array( esc_html__( 'Link', 'metamax' ),  true, '' ),
                                                'mailto:' => array( esc_html__( 'Email', 'metamax' ),  false, '' ),
                                                'skype:' => array( esc_html__( 'Skype', 'metamax' ),  false, '' ),
                                                'tel:' => array( esc_html__( 'Phone', 'metamax' ),  false, '' ),
                                            ),
                                            'title' => esc_html__('Select link type', 'metamax' )
                                        ),
                                    ),
                                ),
								'instagram_feed' => array(
									'title' => esc_html__( 'Add Instagram Feed', 'metamax' ),
									'addrowclasses' => 'checkbox grid-col-6',
									'type' => 'checkbox',
									'atts' => 'data-options="e:instagram_feed_shortcode;e:instagram_feed_full_width;"',
								),
								'instagram_feed_full_width' => array(
									'title' => esc_html__( 'Apply Full-Width Feed', 'metamax' ),
									'addrowclasses' => 'disable checkbox grid-col-12',
									'type' => 'checkbox',
								),							
								'instagram_feed_shortcode' => array(
									'title' => esc_html__( 'Instagram Shortcode', 'metamax' ),
									'addrowclasses' => 'disable grid-col-12 full_row',
									'type' => 'textarea',
									'atts' => 'rows="3"',
									'default' => '',
									'value' => '[instagram-feed cols=8 num=8 imagepadding=0 imagepaddingunit=px showheader=false showbutton=true showfollow=true]'
								),
								'spacings' => array(
									'title' => esc_html__( 'Add Spacings (px)', 'metamax' ),
									'type' => 'margins',
									'addrowclasses' => 'new_row grid-col-12 two-inputs',
									'value' => array(
										'top' => array('placeholder' => esc_html__( 'Top', 'metamax' ), 'value' => '70'),
										'bottom' => array('placeholder' => esc_html__( 'Bottom', 'metamax' ), 'value' => '0'),
									),
								),	

							),
						),
					),
				),	
			)
		),	// end of sections

		'styling_options' => array(
			'type' => 'section',
			'title' => esc_html__('Styling options', 'metamax' ),
			'icon' => array('fa', 'paint-brush'),
			'layout' => array(
				'theme_colors' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Theme colors', 'metamax' ),
					'layout' => array(
						'theme_first_color' => array(
							'title' => esc_html__( 'Theme Main color', 'metamax' ),
							'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
							'value' => METAMAX_FIRST_COLOR,
							'addrowclasses' => 'grid-col-4',
							'type' => 'text',
						),						
						'theme_second_color' => array(
							'title' => esc_html__( 'Theme Light Color', 'metamax' ),
							'atts' => 'data-default-color="' . METAMAX_SECOND_COLOR . '"',
							'value' => METAMAX_SECOND_COLOR,
							'addrowclasses' => 'grid-col-4',
							'type' => 'text',
						),
                        'theme_third_color' => array(
                            'title' => esc_html__( 'Theme Dark Color', 'metamax' ),
                            'atts' => 'data-default-color="' . METAMAX_THIRD_COLOR . '"',
                            'value' => METAMAX_THIRD_COLOR,
                            'addrowclasses' => 'grid-col-4',
                            'type' => 'text',
                        ),
					)
				)
			),
		),	// end of sections

		'layout_options' => array(
			'type' => 'section',
			'title' => esc_html__('Page layouts', 'metamax' ),
			'icon' => array('fa', 'columns'),
			'layout'	=> array(
				'layout_options_homepage'	=> array(
					'type' => 'tab',
					'init'	=> 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Home', 'metamax' ),
					'layout' => array(
						'home-slider-type' => array(
							'title' => esc_html__('Slider', 'metamax' ),
							'type' => 'radio',
							'value' => array(
								'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:home-header-slider-options;d:slidersection-start;d:static_img_section' ),
								'img-slider'=>	array( esc_html__('Image Slider', 'metamax' ), false, 'e:home-header-slider-options;d:slidersection-start;d:static_img_section' ),
								'video-slider' => 	array( esc_html__('Video Slider', 'metamax' ), false, 'd:home-header-slider-options;e:slidersection-start;d:static_img_section' ),
								'stat-img-slider' => 	array( esc_html__('Static image', 'metamax' ), false, 'd:home-header-slider-options;d:slidersection-start;e:static_img_section' ),
							),
						),
						'home-header-slider-options' => array(
							'title' => esc_html__( 'Slider shortcode', 'metamax' ),
							'addrowclasses' => 'disable',
							'type' => 'text',
							'value' => '[rev_slider homepage]',
						),
						'slidersection-start' => array(
							'title' => esc_html__( 'Video Slider Setting', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'disable groups',
							'layout' => array(
								'slider_switch' => array(
									'title' => esc_html__( 'Slider', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:slider_shortcode;"',
								),
								'slider_shortcode' => array(
									'title' => esc_html__( 'Slider shortcode', 'metamax' ),
									'addrowclasses' => 'grid-col-12 disable box',
									'type' => 'text',
								),
								'set_video_header_height' => array(
									'title' => esc_html__( 'Set Video height', 'metamax' ),
									'type' => 'checkbox',
									'addrowclasses' => 'grid-col-12 checkbox',
									'atts' => 'data-options="e:video_header_height"',
								),
								'video_header_height' => array(
									'title' => esc_html__( 'Video height', 'metamax' ),
									'addrowclasses' => 'grid-col-12 disable box',
									'type' => 'number',
									'value' => '600',
								),
								'video_type' => array(
									'title' => esc_html__('Video type', 'metamax' ),
									'addrowclasses' => 'grid-col-12',
									'type' => 'radio',
									'value' => array(
										'self_hosted' => 	array( esc_html__('Self-hosted', 'metamax' ), true, 'e:sh_source;d:youtube_source;d:vimeo_source' ),
										'youtube'=>	array( esc_html__('Youtube clip', 'metamax' ), false, 'd:sh_source;e:youtube_source;d:vimeo_source' ),
										'vimeo' => 	array( esc_html__('Vimeo clip', 'metamax' ), false, 'd:sh_source;d:youtube_source;e:vimeo_source' ),
									),
								),
								'sh_source' => array(
									'title' => esc_html__( 'Add video', 'metamax' ),
									'addrowclasses' => 'grid-col-12 box',
									'url-atts' => 'readonly',
									'type' => 'media',
								),
								'youtube_source' => array(
									'title' => esc_html__( 'Youtube video code', 'metamax' ),
									'addrowclasses' => 'grid-col-12 disable box',
									'type' => 'text',
								),
								'vimeo_source' => array(
									'title' => esc_html__( 'Vimeo embed url', 'metamax' ),
									'addrowclasses' => 'grid-col-12 disable box',
									'type' => 'text',
								),
								'color_overlay_type' => array(
									'title' => esc_html__( 'Overlay', 'metamax' ),
									'addrowclasses' => 'grid-col-4',
									'type' => 'select',
									'source' => array(
										'none' => array( esc_html__( 'None', 'metamax' ), 	true, 'd:overlay_color;d:slider_gradient_settings;d:color_overlay_opacity;'),
										'color' => array( esc_html__( 'Color', 'metamax' ), 	false, 'e:overlay_color;d:slider_gradient_settings;e:color_overlay_opacity;'),
										'gradient' =>array( esc_html__( 'Gradient', 'metamax' ), false, 'd:overlay_color;e:slider_gradient_settings;e:color_overlay_opacity;'),
									),
								),
								'overlay_color' => array(
									'title' => esc_html__( 'Color', 'metamax' ),
									'addrowclasses' => 'grid-col-12',
									'atts' => 'data-default-color=""',
									'addrowclasses' => 'box',
									'type' => 'text',
								),
								'color_overlay_opacity' => array(
									'type' => 'number',
									'addrowclasses' => 'grid-col-4 box',
									'title' => esc_html__( 'Opacity', 'metamax' ),
									'placeholder' => esc_attr__( 'In percents', 'metamax' ),
									'value' => '40'
								),
								'slider_gradient_settings' => array(
									'title' => esc_html__( 'Gradient settings', 'metamax' ),
									'addrowclasses' => 'grid-col-12',
									'type' => 'fields',
									'addrowclasses' => 'disable box groups',
									'layout' => array(
										'first_color' => array(
											'type' => 'text',
											'addrowclasses' => 'grid-col-6',
											'title' => esc_html__( 'From', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'second_color' => array(
											'type' => 'text',
											'addrowclasses' => 'grid-col-6',
											'title' => esc_html__( 'To', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'first_color_opacity' => array(
											'type' => 'number',
											'addrowclasses' => 'grid-col-6',
											'title' => esc_html__( 'From (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'second_color_opacity' => array(
											'type' => 'number',
											'addrowclasses' => 'grid-col-6',
											'title' => esc_html__( 'To (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'type' => array(
											'title' => esc_html__( 'Gradient type', 'metamax' ),
											'addrowclasses' => 'grid-col-12',
											'type' => 'radio',
											'value' => array(
												'linear' => array( esc_html__( 'Linear', 'metamax' ),  true, 'e:linear_settings;d:radial_settings' ),
												'radial' =>array( esc_html__( 'Radial', 'metamax' ), false,  'd:linear_settings;e:radial_settings' ),
											),
										),
										'linear_settings' => array(
											'title' => esc_html__( 'Linear settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'angle' => array(
													'type' => 'number',
													'title' => esc_html__( 'Angle', 'metamax' ),
													'value' => '45',
												),
											)
										),
										'radial_settings' => array(
											'title' => esc_html__( 'Radial settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'shape_settings' => array(
													'title' => esc_html__( 'Shape', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'simple' => array( esc_html__( 'Simple', 'metamax' ),  true, 'e:shape;d:size;d:size_keyword;' ),
														'extended' =>array( esc_html__( 'Extended', 'metamax' ), false, 'd:shape;e:size;e:size_keyword;' ),
													),
												),
												'shape' => array(
													'title' => esc_html__( 'Gradient type', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'ellipse' => array( esc_html__( 'Ellipse', 'metamax' ),  true ),
														'circle' =>array( esc_html__( 'Circle', 'metamax' ), false ),
													),
												),
												'size_keyword' => array(
													'type' => 'select',
													'title' => esc_html__( 'Size keyword', 'metamax' ),
													'addrowclasses' => 'disable',
													'source' => array(
														'closest-side' => array(esc_html__( 'Closest side', 'metamax' ), false),
														'farthest-side' => array(esc_html__( 'Farthest side', 'metamax' ), false),
														'closest-corner' => array(esc_html__( 'Closest corner', 'metamax' ), false),
														'farthest-corner' => array(esc_html__( 'Farthest corner', 'metamax' ), true),
													),
												),
												'size' => array(
													'type' => 'text',
													'addrowclasses' => 'disable',
													'title' => esc_html__( 'Size', 'metamax' ),
													'atts' => 'placeholder="'.esc_attr__( 'Two space separated percent values, for example (60% 55%)', 'metamax' ).'"',
												),
											)
										)

									),
								),
								'use_pattern' => array(
									'title' => esc_html__( 'Use pattern image', 'metamax' ),
									'type' => 'checkbox',
									'addrowclasses' => 'grid-col-12 checkbox',
									'atts' => 'data-options="e:pattern_image"',
								),
								'pattern_image' => array(
									'title' => esc_html__( 'Pattern image', 'metamax' ),
									'addrowclasses' => 'grid-col-12 disable box',
									'url-atts' => 'readonly',
									'type' => 'media',
								),
							),
						),// end of video-section
						'static_img_section' => array(
							'title' => esc_html__( 'Static image Slider Setting', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'groups',
							'layout' => array(
								'home_header_image_options' => array(
									'title' => esc_html__( 'Static image', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'layout' => array(
										'is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution image', 'metamax' ),
											'type' => 'checkbox',
											'addrowclasses' => 'checkbox',
										),
									),
								),
								'set_static_image_height' => array(
									'title' => esc_html__( 'Set Image height', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:static_image_height;"',
								),
								'static_image_height' => array(
									'title' => esc_html__( 'Static Image Height', 'metamax' ),
									'addrowclasses' => 'grid-col-12 disable box',
									'type' => 'number',
									'default' => '600',
								),
								'static_customize_colors' => array(
									'title' => esc_html__( 'Customize colors', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_color_overlay_type;e:img_header_overlay_color;e:img_header_color_overlay_opacity;"',
								),
								'img_header_color_overlay_type'	=> array(
									'title'		=> esc_html__( 'Color overlay type', 'metamax' ),
									'type'	=> 'select',
									'addrowclasses' => 'grid-col-12 box disable',
									'source'	=> array(
										'color' => array( esc_html__( 'Color', 'metamax' ),  true, 'e:img_header_overlay_color;d:img_header_gradient_settings;' ),
										'gradient' => array( esc_html__( 'Gradient', 'metamax' ), false, 'd:img_header_overlay_color;e:img_header_gradient_settings;' )
									),
								),
								'img_header_overlay_color'	=> array(
									'title'	=> esc_html__( 'Overlay color', 'metamax' ),
									'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
									'value' => METAMAX_FIRST_COLOR,
									'addrowclasses' => 'box disable',
									'type'	=> 'text',
								),
								'img_header_gradient_settings' => array(
									'title' => esc_html__( 'Gradient Settings', 'metamax' ),
									'type' => 'fields',
									'addrowclasses' => 'disable box groups',
									'layout' => array(
										'first_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'From', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'second_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'To', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'first_color_opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'From (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'second_color_opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'To (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'type' => array(
											'title' => esc_html__( 'Gradient type', 'metamax' ),
											'type' => 'radio',
											'value' => array(
												'linear' => array( esc_html__( 'Linear', 'metamax' ),  true, 'e:img_header_gradient_linear_settings;d:img_header_gradient_radial_settings' ),
												'radial' =>array( esc_html__( 'Radial', 'metamax' ), false,  'd:img_header_gradient_linear_settings;e:img_header_gradient_radial_settings' ),
											),
										),
										'linear_settings' => array(
											'title' => esc_html__( 'Linear settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'angle' => array(
													'type' => 'number',
													'title' => esc_html__( 'Angle', 'metamax' ),
													'value' => '45',
												),
											)
										),
										'radial_settings' => array(
											'title' => esc_html__( 'Radial settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'shape_settings' => array(
													'title' => esc_html__( 'Shape', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'simple' => array( esc_html__( 'Simple', 'metamax' ),  true, 'e:img_header_gradient_shape;d:img_header_gradient_size;d:img_header_gradient_size_keyword;' ),
														'extended' =>array( esc_html__( 'Extended', 'metamax' ), false, 'd:img_header_gradient_shape;e:img_header_gradient_size;e:img_header_gradient_size_keyword;' ),
													),
												),
												'shape' => array(
													'title' => esc_html__( 'Gradient type', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'ellipse' => array( esc_html__( 'Ellipse', 'metamax' ),  true ),
														'circle' =>array( esc_html__( 'Circle', 'metamax' ), false ),
													),
												),
												'img_header_gradient_size_keyword' => array(
													'type' => 'select',
													'title' => esc_html__( 'Size keyword', 'metamax' ),
													'addrowclasses' => 'disable',
													'source' => array(
														'closest-side' => array(esc_html__( 'Closest side', 'metamax' ), false),
														'farthest-side' => array(esc_html__( 'Farthest side', 'metamax' ), false),
														'closest-corner' => array(esc_html__( 'Closest corner', 'metamax' ), false),
														'farthest-corner' => array(esc_html__( 'Farthest corner', 'metamax' ), true),
													),
												),
												'img_header_gradient_size' => array(
													'type' => 'text',
													'addrowclasses' => 'disable',
													'title' => esc_html__( 'Size', 'metamax' ),
													'atts' => 'placeholder="'.esc_attr__( 'Two space separated percent values, for example (60% 55%)', 'metamax' ).'"',
												),
											)
										)
									)
								),
								'img_header_color_overlay_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Opacity', 'metamax' ),
									'addrowclasses' => 'box disable',
									'placeholder' => esc_attr__( 'In percents', 'metamax' ),
									'value' => '40'
								),
								'img_header_use_pattern' => array(
									'title' => esc_html__( 'Add pattern', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_pattern_image;"',
								),
								'img_header_pattern_image' => array(
									'title' => esc_html__( 'Pattern image', 'metamax' ),
									'type' => 'media',
									'addrowclasses' => 'grid-col-12 disable box',
									'url-atts' => 'readonly',
								),
								'img_header_parallaxify' => array(
									'title' => esc_html__( 'Parallaxify image', 'metamax' ),
									'addrowclasses' => 'grid-col-12 checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_parallax_options;"',
								),
								'img_header_parallax_options' => array(
									'title' => esc_html__( 'Parallax options', 'metamax' ),
									'type' => 'fields',
									'addrowclasses' => 'disable box groups',
									'layout' => array(
										'img_header_scalar-x' => array(
											'type' => 'number',
											'title' => esc_html__( 'x-axis parallax intensity', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '2'
										),
										'img_header_scalar-y' => array(
											'type' => 'number',
											'title' => esc_html__( 'y-axis parallax intensity', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '2'
										),
										'img_header_limit-x' => array(
											'type' => 'number',
											'title' => esc_html__( 'Maximum x-axis shift', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '15'
										),
										'img_header_limit-y' => array(
											'type' => 'number',
											'title' => esc_html__( 'Maximum y-axis shift', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '15'
										),
									),
								),
							),
						),// end of static img slider-section
						'home_sidebars' => array(
							'title' => esc_html__( 'Home Page Sidebar Layout', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'box inside-box groups',
							'layout' => array(
								'layout' => array(
									'title' => esc_html__('Sidebar Position', 'metamax' ),
									'type' => 'radio',
									'addrowclasses' => 'grid-col-12',
									'subtype' => 'images',
									'value' => array(
										'left' => 	array( esc_html__('Left', 'metamax' ), false, 'e:sb1;d:sb2',	'/img/left.png' ),
										'right' => 	array( esc_html__('Right', 'metamax' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
										'both' => 	array( esc_html__('Double', 'metamax' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
										'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:sb1;d:sb2', '/img/none.png' )
									),
								),
								'sb1' => array(
									'title' => esc_html__('Select a sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'grid-col-12 disable box clear',
									'source' => 'sidebars',
								),
								'sb2' => array(
									'title' => esc_html__('Select right sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'grid-col-12 disable box',
									'source' => 'sidebars',
								),
							),
						),
					)
				),
				'layout_options_page' => array(
					'type' => 'tab',
					'icon' => array( 'fas', 'fa-book' ),
					'title' => esc_html__( 'Page', 'metamax' ),
					'layout' => array(
						'page_sidebars' => array(
							'title' => esc_html__( 'Page Sidebar Layout', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'box inside-box groups',
							'layout' => array(
								'layout' => array(
									'type' => 'radio',
									'subtype' => 'images',
									'value' => array(
										'left' => 	array( esc_html__('Left', 'metamax' ), false, 'e:sb1;d:sb2', '/img/left.png' ),
										'right' => 	array( esc_html__('Right', 'metamax' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
										'both' => 	array( esc_html__('Double', 'metamax' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
										'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:sb1;d:sb2', '/img/none.png' )
									),
								),
								'sb1' => array(
									'title' => esc_html__('Select a sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'disable box',
									'source' => 'sidebars',
								),
								'sb2' => array(
									'title' => esc_html__('Select right sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'disable box',
									'source' => 'sidebars',
								),
							),
						),
						'boxed' => array(
							'title' => esc_html__( 'Enable Boxed Layout', 'metamax' ),
							'addrowclasses' => 'checkbox alt',
							'type' => 'checkbox',
							'atts' => 'data-options="e:boxed_background"',
						),
						'boxed_background' => array(
							'title' => esc_html__( 'Background Settings', 'metamax' ),
							'type' => 'info',
							'icon' => array('fa', 'calendar-plus-o'),
							'addrowclasses'	=> 'disable',
							'value' => '<a href="'.get_admin_url(null, 'customize.php?autofocus[control]=background_image').'" target="_blank">'.esc_html__('Click this link to customize your background settings','metamax').'</a>',
						),
					)
				),
				'layout_options_blog' => array(
					'type' => 'tab',
					'icon' => array( 'fas', 'fa-book' ),
					'title' => esc_html__( 'Blog', 'metamax' ),
					'layout' => array(
						'blog_sidebars' => array(
							'title' => esc_html__( 'Blog Sidebars Settings', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'box inside-box groups grid-col-12',
							'layout' => array(
								'layout' => array(
									'title' => esc_html__('Sidebar Position', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'left' => 	array( esc_html__('Left', 'metamax' ), false, 'e:sb1;d:sb2',	'/img/left.png' ),
										'right' => 	array( esc_html__('Right', 'metamax' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
										'both' => 	array( esc_html__('Double', 'metamax' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
										'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:sb1;d:sb2', '/img/none.png' )
									),
								),
								'sb1' => array(
									'title' => esc_html__('Select a sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'disable grid-col-4',
									'source' => 'sidebars',
								),
								'sb2' => array(
									'title' => esc_html__('Select right sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'disable grid-col-4',
									'source' => 'sidebars',
								),
							),
						),					
						'blog_button_name' => array(
							'title' => esc_html__( 'Button Name', 'metamax' ),
							'type' => 'text',
							'value' => 'Read More',
							'addrowclasses' => 'grid-col-6',	
						),
						'def_blog_chars_count' => array(
							'title' => esc_html__( 'Text Length', 'metamax' ),
							'type' => 'text',
							'addrowclasses' => 'grid-col-6',
						),						
						'def_blogtype' => array(
							'title'		=> esc_html__( 'Blog Layout', 'metamax' ),
							'desc'		=> esc_html__( 'Default Blog Layout', 'metamax' ),
							'type'		=> 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'grid-col-6',
							'value' => array(
								'1' => array( esc_html__('Large', 'metamax' ), true, '', '/img/large.png'),
								'medium' => array( esc_html__('Medium', 'metamax' ), false, '', '/img/medium.png'),
								'small' => array( esc_html__('Small', 'metamax' ), false, '', '/img/small.png'),
								'2' => array( esc_html__('2 Cols', 'metamax' ), false, '', '/img/pinterest_2_columns.png'),
								'3' => array( esc_html__('3 Cols', 'metamax' ), false, '', '/img/pinterest_3_columns.png'),
								'4' => array( esc_html__('4 Cols', 'metamax' ), false, '', '/img/pinterest_4_columns.png'),
							),
						),
						'blog_slug' => array(
							'title' => esc_html__( 'Rename Blog', 'metamax' ),
							'addrowclasses' => 'requirement grid-col-6',
							'type' 	=> 'text',
							'value'	=> 'Blog'
						),
						'def_post_hide_meta_related_items'	=> array(
							'title'		=> esc_html__( 'Hide meta (Related Items)', 'metamax' ),
							'type'		=> 'select',
							'atts'		=> 'multiple',
							'addrowclasses' => 'grid-col-12',
							'source'		=> array(
								'' 				=> array( esc_html__( 'None', 'metamax' ), false),
								'title' 		=> array( esc_html__( 'Title', 'metamax' ), false),
								'cats' 			=> array( esc_html__( 'Categories', 'metamax' ), false),
								'tags' 			=> array( esc_html__( 'Tags', 'metamax' ), true),
								'author' 		=> array( esc_html__( 'Author', 'metamax' ), false),
								'likes' 		=> array( esc_html__( 'Likes', 'metamax' ), false),
								'date' 			=> array( esc_html__( 'Date', 'metamax' ), false),
								'comments' 		=> array( esc_html__( 'Comments', 'metamax' ), true),
								'read_more' 	=> array( esc_html__( 'Read More', 'metamax' ), false),
								'social' 		=> array( esc_html__( 'Social Icons', 'metamax' ), true),
								'excerpt' 		=> array( esc_html__( 'Excerpt', 'metamax' ), false),
							)
						),				
					)
				),
				'layout_options_portfolio' => array(
					'type' => 'tab',
					'icon' => array( 'fas', 'fa-book' ),
					'title' => esc_html__( 'Portfolio', 'metamax' ),
					'layout' => array(
						'def_layout_portfolio' => array(
							'title'		=> esc_html__( 'Portfolio Layout', 'metamax' ),
							'type'		=> 'radio',
							'subtype' => 'images',
							'tooltip' => array(
								'title' => esc_html__( 'Portfolio Layout', 'metamax' ),
								'content' => esc_html__( 'This option is applied to portfolio archive pages only', 'metamax' ),
							),
							'value' => array(
								'1' => array( esc_html__('Large', 'metamax' ), false, '', '/img/large.png'),
								'2' => array( esc_html__('2 Cols', 'metamax' ), false, '', '/img/pinterest_2_columns.png'),
								'3' => array( esc_html__('3 Cols', 'metamax' ), false, '', '/img/pinterest_3_columns.png'),
								'4' => array( esc_html__('4 Cols', 'metamax' ), true, '', '/img/pinterest_4_columns.png'),
							),
						),
						'portfolio_mode' => array(
							'title' => esc_html__( 'Display as', 'metamax' ),
							'type' => 'select',
							'source' => array(
								'grid' => array('Grid', true), // Title, isselected, data-options
								'grid_with_filter' => array( esc_html__( 'Grid with filter', 'metamax' ), false ),
                                'filter_with_ajax' => array( esc_html__( 'Grid with filter(Ajax)', 'metamax' ), false ),
								'carousel' => array( esc_html__( 'Carousel', 'metamax'), false )
							),
						),
						'def_cws_portfolio_data_to_show'	=> array(
							'title'		=> esc_html__( 'Show Meta Data', 'metamax' ),
							'type'		=> 'select',
							'atts'		=> 'multiple',
							'source'		=> array(
									'title'		=> array( esc_html__( 'Title', 'metamax' ), true ),
									'excerpt'	=> array( esc_html__( 'Excerpt', 'metamax' ), true ),
									'cats'		=> array( esc_html__( 'Categories', 'metamax' ), false )
							)
						),
						'portfolio_pagination_style' => array(
							'title' => esc_html__( 'Pagination style', 'metamax' ),
							'type' => 'radio',
							'value' => array(
								'paged' => array( esc_html__('Paged', 'metamax'), true ),
								'load_more' => array( esc_html__('Load More', 'metamax'), false )
							),
						),
						'portfolio_slug' => array(
							'title' => esc_html__( 'Portfolio slug', 'metamax' ),
							'type' => 'text',
							'value' => 'portfolio',
						),
					)
				),
				'layout_options_staff' => array(
					'type' => 'tab',
					'icon' => array( 'fas', 'fa-book' ),
					'title' => esc_html__( 'Staff', 'metamax' ),
					'layout' => array(
						'def_cws_staff_layout' => array(
							'title'		=> esc_html__( 'Staff Layout', 'metamax' ),
							'type'		=> 'radio',
							'subtype' => 'images',
							'tooltip' => array(
								'title' => esc_html__( 'Staff Layout', 'metamax' ),
								'content' => esc_html__( 'This option is applied to Staff archive pages only', 'metamax' ),
							),
							'value' => array(
								'1' => array( esc_html__('Large', 'metamax' ), false, '', '/img/large.png'),
								'2' => array( esc_html__('2 Cols', 'metamax' ), false, '', '/img/pinterest_2_columns.png'),
								'3' => array( esc_html__('3 Cols', 'metamax' ), false, '', '/img/pinterest_3_columns.png'),
								'4' => array( esc_html__('4 Cols', 'metamax' ), true, '', '/img/pinterest_4_columns.png'),
							),
						),
						'staff_sidebars' => array(
							'title' => esc_html__( 'Staff Sidebars Settings', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'box inside-box groups',
							'layout' => array(
								'layout' => array(
									'title' => esc_html__( 'Sidebar Position', 'metamax' ),
									'type' => 'radio',
									'subtype' => 'images',
									'addrowclasses' => 'grid-col-4',
									'value' => array(
										'left' => 	array( esc_html__('Left', 'metamax' ), false, 'e:sb1;d:sb2', '/img/left.png' ),
										'right' => 	array( esc_html__('Right', 'metamax' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
										'both' => 	array( esc_html__('Double', 'metamax' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
										'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:sb1;d:sb2', '/img/none.png' )
									),
								),
								'sb1' => array(
									'title' => esc_html__('Select a sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'disable grid-col-4',
									'source' => 'sidebars',
								),
								'sb2' => array(
									'title' => esc_html__('Select right sidebar', 'metamax' ),
									'type' => 'select',
									'addrowclasses' => 'disable grid-col-4',
									'source' => 'sidebars',
								),
							),
						),
						'def_cws_staff_data_to_hide' => array(
							'title'		=> esc_html__( 'Hide Meta Data', 'metamax' ),
							'type'		=> 'select',
							'atts'		=> 'multiple',
							'source'		=> array(
								'deps'			=> array( esc_html__( 'Departments', 'metamax' ), true ),
								'poss'			=> array( esc_html__( 'Positions', 'metamax' ), false ),
								'excerpt'		=> array( esc_html__( 'Excerpt', 'metamax' ), true ),
								'experience'	=> array( esc_html__( 'Experience', 'metamax' ), true ),
								'email'			=> array( esc_html__( 'Email', 'metamax' ), true ),
								'tel'			=> array( esc_html__( 'Tel', 'metamax' ), true ),
								'biography'		=> array( esc_html__( 'Biography', 'metamax' ), true ),
								'link_button'	 => array( esc_html__( 'Link Button', 'metamax' ), true ),
								'socials'		=> array( esc_html__( 'Social Links', 'metamax' ), false )
							)
						),
						'staff_slug' => array(
							'title' => esc_html__( 'Staff slug', 'metamax' ),
							'type' => 'text',
							'value' => 'staff',
						),

					)
				),
				'layout_options_sidebar_generator' => array(
					'type' => 'tab',
					'customizer' 	=> array( 'show' => false ),
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Sidebars', 'metamax' ),
					'layout' => array(
						'sidebars' => array(
							'type' => 'group',
							'addrowclasses' => 'group single_field requirement',
							'title' => esc_html__('Sidebar generator', 'metamax' ),
							'button_title' => esc_html__('Add new sidebar', 'metamax' ),
							'value' => array(
									array('title' => esc_html__('Footer', 'metamax')),
									array('title' => esc_html__('Blog Right', 'metamax')),
									array('title' => esc_html__('Blog Left', 'metamax')),
									array('title' => esc_html__('Page Right', 'metamax')),
									array('title' => esc_html__('Page Left', 'metamax')),
									array('title' => esc_html__('Side Panel', 'metamax')),
									array('title' => esc_html__('WooCommerce', 'metamax')),
							),
							'layout' => array(
								'title' => array(
									'type' => 'text',
									'value' => 'New Sidebar',
									'atts' => 'data-role="title"',
									'verification' => array (
										'length' => array( array('!0'), esc_html__('Title should not be empty', 'metamax' )),
									),
									'title' => esc_html__('Sidebar', 'metamax' ),
								)
							)
						),
						'sticky_sidebars' => array(
							'title' => esc_html__( 'Sticky sidebars', 'metamax' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						)
					)
				),
			)
		),	// end of sections
		'typography_options' => array(
			'type' => 'section',
			'title' => esc_html__('Typography', 'metamax' ),
			'icon' => array('fa', 'font'),
			'layout' => array(
				'menu_font_options' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Menu', 'metamax' ),
					'layout' => array(
						'menu-font' => array(
							'title' => esc_html__('Menu Font', 'metamax' ),
							'type' => 'font',
							'font-color' => true,
							'font-size' => true,
							'font-sub' => true,
							'line-height' => true,
							'value' => array(
								'font-size' => '17px',
								'line-height' => 'initial',
								'color' => '#0a0202',
								'font-family' => 'Rubik',
								'font-weight' => array( 'regular'),
								'font-sub' => array('latin'),
							)
						)
					)
				),
				'header_font_options' => array(
					'type' => 'tab',
					'icon' => array('fa', 'font'),
					'title' => esc_html__( 'Header', 'metamax' ),
					'layout' => array(
						'header-font' => array(
							'title' => esc_html__('Header\'s Font', 'metamax' ),
							'type' => 'font',
							'font-color' => true,
							'font-size' => true,
							'font-sub' => true,
							'line-height' => true,
							'value' => array(
								'font-size' => '60px',
								'line-height' => 'initial',
								'color' => '#1f5abc',
								'font-family' => 'Nunito',
								'font-weight' => array( 'regular', 'italic', '600', '600italic', '700', '700italic', '800',
                                    '800italic',
                                    '900', '900italic' ),
								'font-sub' => array('latin'),
							),
						)
					)
				),
				'body_font_options' => array(
					'type' => 'tab',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Body', 'metamax' ),
					'layout' => array(
						'body-font' => array(
							'title' => esc_html__('Body Font', 'metamax' ),
							'type' => 'font',
							'font-color' => true,
							'font-size' => true,
							'font-sub' => true,
							'line-height' => true,
							'value' => array(
								'font-size' => '16px',
								'line-height' => '1.5em',
								'color' => '#142b5f',
								'font-family' => 'Rubik',
								'font-weight' => array( 'regular', 'italic', '300', '300italic', '500', '500italic', '700', '700italic' ),
								'font-sub' => array('latin'),
							)
						)
					)
				),

			)
		), // end of sections
		'help_options' => array(
			'type' => 'section',
			'title' => esc_html__('Maintenance & Help', 'metamax' ),
			'icon' => array('fa', 'life-ring'),
			'layout' => array(
				'maintenance' => array(
					'type' => 'tab',
					'init' => 'open',
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Maintenance', 'metamax' ),
					'layout' => array(
						'show_loader' => array(
							'title' => esc_html__( 'ShowLoader', 'metamax' ),
							'addrowclasses' => 'grid-col-12 checkbox alt',
							'type' => 'checkbox',
							'atts' => 'checked data-options="e:loader_logo;e:overlay_loader_color"',
						),
						'loader_logo' => array(
							'title' => esc_html__( 'Loader logo (Square)', 'metamax' ),
							'type' => 'media',
							'url-atts' => 'readonly',
							'addrowclasses' => 'grid-col-12 disable',
							'layout' => array(
								'logo_is_high_dpi' => array(
									'title' => esc_html__( 'High-Resolution logo', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
								),
							),
						),
						'overlay_loader_color' => array(
							'title' => esc_html__( 'Loader Color', 'metamax' ),
							'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
							'value' => METAMAX_FIRST_COLOR,
							'addrowclasses' => 'disable grid-col-12',
							'type' => 'text',
						),
						'breadcrumbs' => array(
							'title' => esc_html__( 'Show breadcrumbs', 'metamax' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),
						'blog_author' => array(
							'title' => esc_html__( 'Show post author', 'metamax' ),
							'addrowclasses' => 'checkbox alt',
							'atts' => 'checked',
							'type' => 'checkbox',
						),
						'_theme_purchase_code' => array(
							'title' => esc_html__( 'Theme purchase code', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Item Purchase Code', 'metamax' ),
								'content' => wp_kses(__( 'Fill in this field with your Item Purchase Code in order to get the demo content and further theme updates.<br/> Please note, this code is applied to the theme only, it will not register Revolution Slider or any other plugins.', 'metamax' ), array(
								    'br' => array()
                                )),
							),
							'type' 	=> 'text',
							'value'	=> '',
							'customizer' 	=> array( 'show' => true )
						),
					)
				),
		    'animation' => array(
			     'type' => 'tab',
			     'icon' => array('fa', 'arrow-circle-o-up'),
			     'title' => esc_html__( 'Animation', 'metamax' ),
			     'layout' => array(
					'animation_curve_menu'	=> array(
						'title'	=> esc_html__( 'Animation (Menu Anchors)', 'metamax' ),
						'type'	=> 'select',
						'addrowclasses' => 'grid-col-4',
						'source'	=> array(
							'linear' => array( esc_html__( '1. linear', 'metamax' ), false ),
							'swing' => array( esc_html__( '2. swing', 'metamax' ), false ),
							'easeInQuad' => array( esc_html__( '3. easeInQuad', 'metamax' ), false ),
							'easeOutQuad' => array( esc_html__( '4. easeOutQuad', 'metamax' ), false ),
							'easeInOutQuad' => array( esc_html__( '5. easeInOutQuad', 'metamax' ), false ),
							'easeInCubic' => array( esc_html__( '6. easeInCubic', 'metamax' ), false ),
							'easeOutCubic' => array( esc_html__( '7. easeOutCubic', 'metamax' ), true ),
							'easeInOutCubic' => array( esc_html__( '8. easeInOutCubic', 'metamax' ), false ),
							'easeInQuart' => array( esc_html__( '9. easeInQuart', 'metamax' ), false ),
							'easeOutQuart' => array( esc_html__( '10. easeOutQuart', 'metamax' ), false ),
							'easeInOutQuart' => array( esc_html__( '11. easeInOutQuart', 'metamax' ), false ),
							'easeInQuint' => array( esc_html__( '12. easeInQuint', 'metamax' ), false ),
							'easeOutQuint' => array( esc_html__( '13. easeOutQuint', 'metamax' ), false ),
							'easeInOutQuint' => array( esc_html__( '14. easeInOutQuint', 'metamax' ), false ),
							'easeInSine' => array( esc_html__( '15. easeInSine', 'metamax' ), false ),
							'easeOutSine' => array( esc_html__( '16. easeOutSine', 'metamax' ), false ),
							'easeInOutSine' => array( esc_html__( '17. easeInOutSine', 'metamax' ), false ),
							'easeInExpo' => array( esc_html__( '18. easeInExpo', 'metamax' ), false ),
							'easeOutExpo' => array( esc_html__( '19. easeOutExpo', 'metamax' ), false ),
							'easeInOutExpo' => array( esc_html__( '20. easeInOutExpo', 'metamax' ), false ),
							'easeInCirc' => array( esc_html__( '21. easeInCirc', 'metamax' ), false ),
							'easeOutCirc' => array( esc_html__( '22. easeOutCirc', 'metamax' ), false ),
							'easeInOutCirc' => array( esc_html__( '23. easeInOutCirc', 'metamax' ), false ),
							'easeInElastic' => array( esc_html__( '24. easeInElastic', 'metamax' ), false ),
							'easeOutElastic' => array( esc_html__( '25. easeOutElastic', 'metamax' ), false ),
							'easeInOutElastic' => array( esc_html__( '26. easeInOutElastic', 'metamax' ), false ),
							'easeInBack' => array( esc_html__( '27. easeInBack', 'metamax' ), false ),
							'easeOutBack' => array( esc_html__( '28. easeOutBack', 'metamax' ), false ),
							'easeInOutBack' => array( esc_html__( '29. easeInOutBack', 'metamax' ), false ),
							'easeInBounce' => array( esc_html__( '30. easeInBounce', 'metamax' ), false ),
							'easeOutBounce' => array( esc_html__( '31. easeOutBounce', 'metamax' ), false ),
							'easeInOutBounce' => array( esc_html__( '32. easeInOutBounce', 'metamax' ), false ),
						),
					),
					'animation_curve_scrolltop'	=> array(
						'title'	=> esc_html__( 'Animation (ScrollTop)', 'metamax' ),
						'type'	=> 'select',
						'addrowclasses' => 'grid-col-4',
						'source'	=> array(
							'linear' => array( esc_html__( '1. linear', 'metamax' ), false ),
							'swing' => array( esc_html__( '2. swing', 'metamax' ), false ),
							'easeInQuad' => array( esc_html__( '3. easeInQuad', 'metamax' ), false ),
							'easeOutQuad' => array( esc_html__( '4. easeOutQuad', 'metamax' ), false ),
							'easeInOutQuad' => array( esc_html__( '5. easeInOutQuad', 'metamax' ), true ),
							'easeInCubic' => array( esc_html__( '6. easeInCubic', 'metamax' ), false ),
							'easeOutCubic' => array( esc_html__( '7. easeOutCubic', 'metamax' ), false ),
							'easeInOutCubic' => array( esc_html__( '8. easeInOutCubic', 'metamax' ), false ),
							'easeInQuart' => array( esc_html__( '9. easeInQuart', 'metamax' ), false ),
							'easeOutQuart' => array( esc_html__( '10. easeOutQuart', 'metamax' ), false ),
							'easeInOutQuart' => array( esc_html__( '11. easeInOutQuart', 'metamax' ), false ),
							'easeInQuint' => array( esc_html__( '12. easeInQuint', 'metamax' ), false ),
							'easeOutQuint' => array( esc_html__( '13. easeOutQuint', 'metamax' ), false ),
							'easeInOutQuint' => array( esc_html__( '14. easeInOutQuint', 'metamax' ), false ),
							'easeInSine' => array( esc_html__( '15. easeInSine', 'metamax' ), false ),
							'easeOutSine' => array( esc_html__( '16. easeOutSine', 'metamax' ), false ),
							'easeInOutSine' => array( esc_html__( '17. easeInOutSine', 'metamax' ), false ),
							'easeInExpo' => array( esc_html__( '18. easeInExpo', 'metamax' ), false ),
							'easeOutExpo' => array( esc_html__( '19. easeOutExpo', 'metamax' ), false ),
							'easeInOutExpo' => array( esc_html__( '20. easeInOutExpo', 'metamax' ), false ),
							'easeInCirc' => array( esc_html__( '21. easeInCirc', 'metamax' ), false ),
							'easeOutCirc' => array( esc_html__( '22. easeOutCirc', 'metamax' ), false ),
							'easeInOutCirc' => array( esc_html__( '23. easeInOutCirc', 'metamax' ), false ),
							'easeInElastic' => array( esc_html__( '24. easeInElastic', 'metamax' ), false ),
							'easeOutElastic' => array( esc_html__( '25. easeOutElastic', 'metamax' ), false ),
							'easeInOutElastic' => array( esc_html__( '26. easeInOutElastic', 'metamax' ), false ),
							'easeInBack' => array( esc_html__( '27. easeInBack', 'metamax' ), false ),
							'easeOutBack' => array( esc_html__( '28. easeOutBack', 'metamax' ), false ),
							'easeInOutBack' => array( esc_html__( '29. easeInOutBack', 'metamax' ), false ),
							'easeInBounce' => array( esc_html__( '30. easeInBounce', 'metamax' ), false ),
							'easeOutBounce' => array( esc_html__( '31. easeOutBounce', 'metamax' ), false ),
							'easeInOutBounce' => array( esc_html__( '32. easeInOutBounce', 'metamax' ), false ),
						),
					),
					'animation_curve_speed' => array(
						'type' => 'number',
						'title' => esc_html__( 'Animation Speed', 'metamax' ),
						'placeholder' => esc_html__( 'In milliseconds', 'metamax' ),
						'value' => '300',
						'addrowclasses' => 'grid-col-4',
					),
					'curves' => array(
						'type' => 'info',
						'addrowclasses' => 'grid-col-12',
						'value' => '<img src="'. get_template_directory_uri() . '/img/easing.png" />',
						'title' => esc_html__('Easing curves', 'metamax' )
					),
			    )
		    ),
				'help' => array(
					'type' => 'tab',
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Help', 'metamax' ),
					'layout' => array(
						'help' => array(
								 'title' 			=> esc_html__( 'Help', 'metamax' ),
								 'type' 			=> 'info',
								 'subtype'		=> 'custom',
								 'value' 			=> '<a class="cwsfw_info_button" href="http://metamax.cwsthemes.com/manual" target="_blank"><i class="fa fa-life-ring"></i>&nbsp;&nbsp;' . esc_html__( 'Online Tutorial', 'metamax' ) . '</a>&nbsp;&nbsp;<a class="cwsfw_info_button" href="https://www.youtube.com/user/cwsvideotuts/playlists" target="_blank"><i class="fa fa-video-camera"></i>&nbsp;&nbsp;' . esc_html__( 'Video Tutorial', 'metamax' ) . '</a>',
							),
					)
				),
				'crop' => array(
					'type' => 'tab',
					'icon' => array('fa', 'calendar-plus-o'),
					'title' => esc_html__( 'Crop Images', 'metamax' ),
					'layout' => array(
						'crop_x' => array(
							'title' => esc_html__( 'Crop X', 'metamax' ),
							'type' => 'radio',
							'addrowclasses' => 'grid-col-3',
							'value' => array(
								'left' => array( esc_html__( 'Left', 'metamax' ),  false, '' ),
								'center' => array( esc_html__( 'Center', 'metamax' ),  true, '' ),
								'right' => array( esc_html__( 'Right', 'metamax' ),  false, '' ),
							),
						),
						'crop_y' => array(
							'title' => esc_html__( 'Crop Y', 'metamax' ),
							'type' => 'radio',
							'addrowclasses' => 'grid-col-3',
							'value' => array(
								'top' => array( esc_html__( 'Top', 'metamax' ),  false, '' ),
								'center' => array( esc_html__( 'Center', 'metamax' ),  true, '' ),
								'bottom' => array( esc_html__( 'Bottom', 'metamax' ),  false, '' ),
							),
						),

					)
				),
			)
		),
		
		'social_options' => array(
			'type' => 'section',
			'title' => esc_html__('Social Networks', 'metamax' ),
			'icon' => array('fa', 'share-alt'),
			'layout' => array(
				'social_cont'	=> array(
					'type' => 'tab',
					'init'	=> 'open',
					'icon' => array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Social Networks', 'metamax' ),
					'layout' => array(
                        'tw-username'	=> array(
                            'title'		=> esc_html__( 'Twitter Username', 'metamax' ),
                            'type'	=> 'text',
                            'addrowclasses' => 'grid-col-12 box',
                            'value' => '',
                        ),

						'social' => array(
							'type' => 'fields',
							'addrowclasses' => 'inside-box groups grid-col-12 box',
							'layout' => array(
								'location'	=> array(
									'title'		=> esc_html__( 'Social Icons Location', 'metamax' ),
									'type'	=> 'select',
									'atts' => 'multiple',
									'addrowclasses' => 'grid-col-12 box',
									'source'	=> array(
										'top_bar' => array( esc_html__( 'Top Bar', 'metamax' ), false, ''),
										'menu' => array( esc_html__( 'Menu', 'metamax' ), false, ''),
										'copyrights' => array( esc_html__( 'Copyrights area', 'metamax' ), false, ''),
										'side_panel' => array( esc_html__( 'Side panel', 'metamax' ), false, ''),
									),
								),
								'icons' => array(
									'type' => 'group',
									'addrowclasses' => 'group sortable grid-col-12 box',
									'title' => esc_html__('Social Networks', 'metamax' ),
									'button_title' => esc_html__('Add new social network', 'metamax' ),
									'button_icon' => 'fa fa-plus',
									'layout' => array(
										'title' => array(
											'type' => 'text',
											'atts' => 'data-role="title"',
											'addrowclasses' => 'grid-col-4',
											'title' => esc_html__('Social account title', 'metamax' ),
										),
										'icon' => array(
											'type' => 'select',
											'addrowclasses' => 'fai grid-col-4',
											'source' => 'fa',
											'title' => esc_html__('Icon for this social contact', 'metamax' )
										),										
										'url' => array(
											'type' => 'text',
											'addrowclasses' => 'grid-col-4',
											'title' => esc_html__('Url to your account', 'metamax' ),
										),
										'color'	=> array(
											'title'	=> esc_html__( 'Icon color', 'metamax' ),
											'addrowclasses' => 'grid-col-3',
											'atts' => 'data-default-color="#bbd0ff"',
											'value' => '#bbd0ff',
											'type'	=> 'text',
										),
										'bg_color'	=> array(
											'title'	=> esc_html__( 'Background color', 'metamax' ),
											'addrowclasses' => 'grid-col-3',
											'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
											'value' => METAMAX_FIRST_COLOR,
											'type'	=> 'text',
										),			
										'hover_color'	=> array(
											'title'	=> esc_html__( 'Icon color (Hover)', 'metamax' ),
											'addrowclasses' => 'grid-col-3',
											'atts' => 'data-default-color="#ffffff"',
											'value' => '#ffffff',											
											'type'	=> 'text',
										),
										'hover_bg_color'	=> array(
											'title'	=> esc_html__( 'Background color (Hover)', 'metamax' ),
											'addrowclasses' => 'grid-col-3',
											'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
											'value' => METAMAX_FIRST_COLOR,
											'type'	=> 'text',
										),
									)
								),
							),
						),

					)
				),
			)
		), // end of sections
	);

	//Show field if WPML plugin active
	if ( class_exists('SitePress') )  {
		$settings['general_setting']['layout']['menu_cont']['layout']['menu_box']['layout']['language_bar'] = array(
			'title' => esc_html__( 'Add Language Bar to menu', 'metamax' ),
			'addrowclasses' => 'checkbox grid-col-6',
			'type' => 'checkbox',
		);
	}

	//Show TAB if WooCommerce plugin active
	if ( class_exists( 'woocommerce' ) )  {
		$settings['woo_options'] = array(
			'type'		=> 'section',
			'title'		=> esc_html__( 'WooCommerce', 'metamax' ),
			'icon'		=> array('fa', 'shopping-cart'),
			'layout'	=> array(
				'woo_options' => array(
					'type' 	=> 'tab',
					'init'	=> 'open',
					'icon' 	=> array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Woocommerce', 'metamax' ),
					'layout' => array(
						'woo_cart_enable'	=> array(
							'title'			=> esc_html__( 'Show WooCommerce Cart', 'metamax' ),
							'type'			=> 'checkbox',
							'addrowclasses'	=> 'checkbox alt grid-col-12',
							'atts' => 'data-options="e:woo_cart_place;"',
						),
						'woo_cart_place' => array(
							'title' => esc_html__( 'WooCommerce Cart position', 'metamax' ),
							'type' => 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'disable grid-col-12',
							'value' => array(
								'top' =>array( esc_html__( 'TopBar', 'metamax' ), false, '', '/img/woo-cart-top-right.png' ),
								'left' =>array( esc_html__( 'Menu (Left)', 'metamax' ), false, '', '/img/woo-cart-menu-left.png' ),
								'right' =>array( esc_html__( 'Menu (Right)', 'metamax' ), true, '', '/img/woo-cart-menu-right.png' ),
							),
						),
						'woo_sb_layout' => array(
							'title' => esc_html__('Sidebar Position', 'metamax' ),
							'type' => 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'grid-col-12',
							'value' => array(
								'left' => 	array( esc_html__('Left', 'metamax' ), false, 'e:woo_sidebar;',	'/img/left.png' ),
								'right' => 	array( esc_html__('Right', 'metamax' ), true, 'e:woo_sidebar;', '/img/right.png' ),
								'none' => 	array( esc_html__('None', 'metamax' ), false, 'd:woo_sidebar;', '/img/none.png' )
							),
						),
						'woo_sidebar' => array(
							'title' => esc_html__('Select a sidebar', 'metamax' ),
							'type' => 'select',
							'addrowclasses' => 'disable grid-col-12',
							'source' => 'sidebars',
                            'value' => 'woocommerce'
						),	
						'woo_sb_layout_single' => array(
							'title' => esc_html__('Sidebar Position Single', 'metamax' ),
							'type' => 'radio',
							'subtype' => 'images',
							'addrowclasses' => 'grid-col-12',
							'value' => array(
								'left' => 	array( esc_html__('Left', 'metamax' ), false, 'e:woo_sidebar_single;',	'/img/left.png' ),
								'right' => 	array( esc_html__('Right', 'metamax' ), false, 'e:woo_sidebar_single;', '/img/right.png' ),
								'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:woo_sidebar_single;', '/img/none.png' )
							),
						),					
						'woo_sidebar_single' => array(
							'title' => esc_html__('Select a Single sidebar', 'metamax' ),
							'type' => 'select',
							'addrowclasses' => 'disable grid-col-12',
							'source' => 'sidebars',
						),
						'woo_columns' => array(
							'type' => 'select',
							'title' => esc_html__( 'Columns layout', 'metamax' ),
							'addrowclasses' => 'grid-col-6',
							'source' => array(
								'2' => array('Two Columns',false, ''),
								'3' => array('Three Columns',true, ''),
								'4' => array('Four Columns',false, '')
							),
						),
						'woo_num_products'	=> array(
							'title'			=> esc_html__( 'Products per page', 'metamax' ),
							'type'			=> 'number',
							'addrowclasses' => 'grid-col-6',
							'value'			=> '9'
						),
						'woo_related_columns' => array(
							'type' => 'select',
							'title' => esc_html__( 'Columns layout (Related)', 'metamax' ),
							'addrowclasses' => 'grid-col-6',
							'source' => array(
								'2' => array('Two Columns',false, ''),
								'3' => array('Three Columns',false, ''),
								'4' => array('Four Columns',true, '')
							),
						),						
						'woo_related_num_products'	=> array(
							'title'			=> esc_html__( 'Products per page (Related)', 'metamax' ),
							'type'			=> 'number',
							'addrowclasses' => 'grid-col-6',
							'value'			=> '4'
						),
						'shop-slider-type' => array(
							'title' => esc_html__('Slider', 'metamax' ),
							'type' => 'radio',
							'addrowclasses' => 'grid-col-12',
							'value' => array(
								'none' => 	array( esc_html__('None', 'metamax' ), true, 'd:shop-header-slider-options;d:shopslidersection-start;d:static_img_section' ),
								'img-slider'=>	array( esc_html__('Image Slider', 'metamax' ), false, 'e:shop-header-slider-options;d:shopslidersection-start;d:static_img_section' ),
								'video-slider' => 	array( esc_html__('Video Slider', 'metamax' ), false, 'd:shop-header-slider-options;e:shopslidersection-start;d:static_img_section' ),
								'stat-img-slider' => 	array( esc_html__('Static image', 'metamax' ), false, 'd:shop-header-slider-options;d:shopslidersection-start;e:static_img_section' ),
							),
						),
						'shop-header-slider-options' => array(
							'title' => esc_html__( 'Slider shortcode', 'metamax' ),
							'addrowclasses' => 'disable grid-col-12',
							'type' => 'text',
							'value' => '[rev_slider shoppage]',
						),
						'shopslidersection-start' => array(
							'title' => esc_html__( 'Video Slider Setting', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'disable groups',
							'layout' => array(
								'slider_switch' => array(
									'title' => esc_html__( 'Slider', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:slider_shortcode;"',
								),
								'slider_shortcode' => array(
									'title' => esc_html__( 'Slider shortcode', 'metamax' ),
									'addrowclasses' => 'disable box',
									'type' => 'text',
								),
								'set_video_header_height' => array(
									'title' => esc_html__( 'Set Video height', 'metamax' ),
									'type' => 'checkbox',
									'addrowclasses' => 'checkbox',
									'atts' => 'data-options="e:video_header_height"',
								),
								'video_header_height' => array(
									'title' => esc_html__( 'Video height', 'metamax' ),
									'addrowclasses' => 'disable box',
									'type' => 'number',
									'value' => '600',
								),
								'video_type' => array(
									'title' => esc_html__('Video type', 'metamax' ),
									'type' => 'radio',
									'value' => array(
										'self_hosted' => 	array( esc_html__('Self-hosted', 'metamax' ), true, 'e:sh_source;d:youtube_source;d:vimeo_source' ),
										'youtube'=>	array( esc_html__('Youtube clip', 'metamax' ), false, 'd:sh_source;e:youtube_source;d:vimeo_source' ),
										'vimeo' => 	array( esc_html__('Vimeo clip', 'metamax' ), false, 'd:sh_source;d:youtube_source;e:vimeo_source' ),
									),
								),
								'sh_source' => array(
									'title' => esc_html__( 'Add video', 'metamax' ),
									'addrowclasses' => 'box',
									'url-atts' => 'readonly',
									'type' => 'media',
								),
								'youtube_source' => array(
									'title' => esc_html__( 'Youtube video code', 'metamax' ),
									'addrowclasses' => 'disable box',
									'type' => 'text',
								),
								'vimeo_source' => array(
									'title' => esc_html__( 'Vimeo embed url', 'metamax' ),
									'addrowclasses' => 'disable box',
									'type' => 'text',
								),
								'color_overlay_type' => array(
									'title' => esc_html__( 'Overlay type', 'metamax' ),
									'type' => 'select',
									'source' => array(
										'none' => array( esc_html__( 'None', 'metamax' ), 	true, 'd:overlay_color;d:slider_gradient_settings;d:color_overlay_opacity;'),
										'color' => array( esc_html__( 'Color', 'metamax' ), 	false, 'e:overlay_color;d:slider_gradient_settings;e:color_overlay_opacity;'),
										'gradient' =>array( esc_html__( 'Gradient', 'metamax' ), false, 'd:overlay_color;e:slider_gradient_settings;e:color_overlay_opacity;'),
									),
								),
								'overlay_color' => array(
									'title' => esc_html__( 'Overlay Color', 'metamax' ),
									'atts' => 'data-default-color=""',
									'addrowclasses' => 'box',
									'type' => 'text',
								),
								'color_overlay_opacity' => array(
									'type' => 'number',
									'addrowclasses' => 'box',
									'title' => esc_html__( 'Opacity', 'metamax' ),
									'placeholder' => esc_attr__( 'In percents', 'metamax' ),
									'value' => '40'
								),
								'slider_gradient_settings' => array(
									'title' => esc_html__( 'Gradient settings', 'metamax' ),
									'type' => 'fields',
									'addrowclasses' => 'disable box groups',
									'layout' => array(
										'first_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'From', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'second_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'To', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'first_color_opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'From (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'second_color_opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'To (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'type' => array(
											'title' => esc_html__( 'Gradient type', 'metamax' ),
											'type' => 'radio',
											'value' => array(
												'linear' => array( esc_html__( 'Linear', 'metamax' ),  true, 'e:linear_settings;d:radial_settings' ),
												'radial' =>array( esc_html__( 'Radial', 'metamax' ), false,  'd:linear_settings;e:radial_settings' ),
											),
										),
										'linear_settings' => array(
											'title' => esc_html__( 'Linear settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'angle' => array(
													'type' => 'number',
													'title' => esc_html__( 'Angle', 'metamax' ),
													'value' => '45',
												),
											)
										),
										'radial_settings' => array(
											'title' => esc_html__( 'Radial settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'shape_settings' => array(
													'title' => esc_html__( 'Shape', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'simple' => array( esc_html__( 'Simple', 'metamax' ),  true, 'e:shape;d:size;d:size_keyword;' ),
														'extended' =>array( esc_html__( 'Extended', 'metamax' ), false, 'd:shape;e:size;e:size_keyword;' ),
													),
												),
												'shape' => array(
													'title' => esc_html__( 'Gradient type', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'ellipse' => array( esc_html__( 'Ellipse', 'metamax' ),  true ),
														'circle' =>array( esc_html__( 'Circle', 'metamax' ), false ),
													),
												),
												'size_keyword' => array(
													'type' => 'select',
													'title' => esc_html__( 'Size keyword', 'metamax' ),
													'addrowclasses' => 'disable',
													'source' => array(
														'closest-side' => array(esc_html__( 'Closest side', 'metamax' ), false),
														'farthest-side' => array(esc_html__( 'Farthest side', 'metamax' ), false),
														'closest-corner' => array(esc_html__( 'Closest corner', 'metamax' ), false),
														'farthest-corner' => array(esc_html__( 'Farthest corner', 'metamax' ), true),
													),
												),
												'size' => array(
													'type' => 'text',
													'addrowclasses' => 'disable',
													'title' => esc_html__( 'Size', 'metamax' ),
													'atts' => 'placeholder="'.esc_attr__( 'Two space separated percent values, for example (60% 55%)', 'metamax' ).'"',
												),
											)
										)

									),
								),
								'use_pattern' => array(
									'title' => esc_html__( 'Use pattern image', 'metamax' ),
									'type' => 'checkbox',
									'addrowclasses' => 'checkbox',
									'atts' => 'data-options="e:pattern_image"',
								),
								'pattern_image' => array(
									'title' => esc_html__( 'Pattern image', 'metamax' ),
									'addrowclasses' => 'disable box',
									'url-atts' => 'readonly',
									'type' => 'media',
								),
							),
						),// end of video-section
						'static_img_section' => array(
							'title' => esc_html__( 'Static image Slider Setting', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'groups',
							'layout' => array(
								'shop_header_image_options' => array(
									'title' => esc_html__( 'Static image', 'metamax' ),
									'type' => 'media',
									'url-atts' => 'readonly',
									'layout' => array(
										'is_high_dpi' => array(
											'title' => esc_html__( 'High-Resolution image', 'metamax' ),
											'type' => 'checkbox',
											'addrowclasses' => 'checkbox',
										),
									),
								),
								'set_static_image_height' => array(
									'title' => esc_html__( 'Set Image height', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:static_image_height;"',
								),
								'static_image_height' => array(
									'title' => esc_html__( 'Static Image Height', 'metamax' ),
									'addrowclasses' => 'disable box',
									'type' => 'number',
									'default' => '600',
								),
								'static_customize_colors' => array(
									'title' => esc_html__( 'Customize colors', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_color_overlay_type;e:img_header_overlay_color;e:img_header_color_overlay_opacity;"',
								),
								'img_header_color_overlay_type'	=> array(
									'title'		=> esc_html__( 'Color overlay type', 'metamax' ),
									'type'	=> 'select',
									'addrowclasses' => 'box disable',
									'source'	=> array(
										'color' => array( esc_html__( 'Color', 'metamax' ),  true, 'e:img_header_overlay_color;d:img_header_gradient_settings;' ),
										'gradient' => array( esc_html__( 'Gradient', 'metamax' ), false, 'd:img_header_overlay_color;e:img_header_gradient_settings;' )
									),
								),
								'img_header_overlay_color'	=> array(
									'title'	=> esc_html__( 'Overlay color', 'metamax' ),
									'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
									'value' => METAMAX_FIRST_COLOR,
									'addrowclasses' => 'box disable',
									'type'	=> 'text',
								),
								'img_header_gradient_settings' => array(
									'title' => esc_html__( 'Gradient Settings', 'metamax' ),
									'type' => 'fields',
									'addrowclasses' => 'disable box groups',
									'layout' => array(
										'first_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'From', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'second_color' => array(
											'type' => 'text',
											'title' => esc_html__( 'To', 'metamax' ),
											'atts' => 'data-default-color=""',
										),
										'first_color_opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'From (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'second_color_opacity' => array(
											'type' => 'number',
											'title' => esc_html__( 'To (Opacity %)', 'metamax' ),
											'value' => '100',
										),
										'type' => array(
											'title' => esc_html__( 'Gradient type', 'metamax' ),
											'type' => 'radio',
											'value' => array(
												'linear' => array( esc_html__( 'Linear', 'metamax' ),  true, 'e:img_header_gradient_linear_settings;d:img_header_gradient_radial_settings' ),
												'radial' =>array( esc_html__( 'Radial', 'metamax' ), false,  'd:img_header_gradient_linear_settings;e:img_header_gradient_radial_settings' ),
											),
										),
										'linear_settings' => array(
											'title' => esc_html__( 'Linear settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'angle' => array(
													'type' => 'number',
													'title' => esc_html__( 'Angle', 'metamax' ),
													'value' => '45',
												),
											)
										),
										'radial_settings' => array(
											'title' => esc_html__( 'Radial settings', 'metamax'  ),
											'type' => 'fields',
											'addrowclasses' => 'disable',
											'layout' => array(
												'shape_settings' => array(
													'title' => esc_html__( 'Shape', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'simple' => array( esc_html__( 'Simple', 'metamax' ),  true, 'e:img_header_gradient_shape;d:img_header_gradient_size;d:img_header_gradient_size_keyword;' ),
														'extended' =>array( esc_html__( 'Extended', 'metamax' ), false, 'd:img_header_gradient_shape;e:img_header_gradient_size;e:img_header_gradient_size_keyword;' ),
													),
												),
												'shape' => array(
													'title' => esc_html__( 'Gradient type', 'metamax' ),
													'type' => 'radio',
													'value' => array(
														'ellipse' => array( esc_html__( 'Ellipse', 'metamax' ),  true ),
														'circle' =>array( esc_html__( 'Circle', 'metamax' ), false ),
													),
												),
												'img_header_gradient_size_keyword' => array(
													'type' => 'select',
													'title' => esc_html__( 'Size keyword', 'metamax' ),
													'addrowclasses' => 'disable',
													'source' => array(
														'closest-side' => array(esc_html__( 'Closest side', 'metamax' ), false),
														'farthest-side' => array(esc_html__( 'Farthest side', 'metamax' ), false),
														'closest-corner' => array(esc_html__( 'Closest corner', 'metamax' ), false),
														'farthest-corner' => array(esc_html__( 'Farthest corner', 'metamax' ), true),
													),
												),
												'img_header_gradient_size' => array(
													'type' => 'text',
													'addrowclasses' => 'disable',
													'title' => esc_html__( 'Size', 'metamax' ),
													'atts' => 'placeholder="'.esc_attr__( 'Two space separated percent values, for example (60% 55%)', 'metamax' ).'"',
												),
											)
										)
									)
								),
								'img_header_color_overlay_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'Opacity', 'metamax' ),
									'addrowclasses' => 'box disable',
									'placeholder' => esc_attr__( 'In percents', 'metamax' ),
									'value' => '40'
								),
								'img_header_use_pattern' => array(
									'title' => esc_html__( 'Add pattern', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_pattern_image;"',
								),
								'img_header_pattern_image' => array(
									'title' => esc_html__( 'Pattern image', 'metamax' ),
									'type' => 'media',
									'addrowclasses' => 'disable box',
									'url-atts' => 'readonly',
								),
								'img_header_parallaxify' => array(
									'title' => esc_html__( 'Parallaxify image', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
									'atts' => 'data-options="e:img_header_parallax_options;"',
								),
								'img_header_parallax_options' => array(
									'title' => esc_html__( 'Parallax options', 'metamax' ),
									'type' => 'fields',
									'addrowclasses' => 'disable box groups',
									'layout' => array(
										'img_header_scalar-x' => array(
											'type' => 'number',
											'title' => esc_html__( 'x-axis parallax intensity', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '2'
										),
										'img_header_scalar-y' => array(
											'type' => 'number',
											'title' => esc_html__( 'y-axis parallax intensity', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '2'
										),
										'img_header_limit-x' => array(
											'type' => 'number',
											'title' => esc_html__( 'Maximum x-axis shift', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '15'
										),
										'img_header_limit-y' => array(
											'type' => 'number',
											'title' => esc_html__( 'Maximum y-axis shift', 'metamax' ),
											'placeholder' => esc_attr__( 'Integer', 'metamax' ),
											'value' => '15'
										),
									),
								),
							),
						),// end of static img slider-section
                        'woo_single_button_color' => array(
                            'title' => esc_html__( 'Single Product page Add-to-Cart button text color', 'metamax' ),
                            'type' 			=> 'text',
                            'addrowclasses' => 'grid-col-4',
                            'atts' 			=> 'data-default-color="#9d5f36"',
                            'value'			=> '#9d5f36'
                        ),
                        'woo_single_button_bg' => array(
                            'title' => esc_html__( 'Single Product page Add-to-Cart button background color', 'metamax' ),
                            'type' 			=> 'text',
                            'addrowclasses' => 'grid-col-4',
                            'atts' 			=> 'data-default-color="#ffe27a"',
                            'value'			=> '#ffe27a'
                        ),
                        'woo_single_button_bd' => array(
                            'title' => esc_html__( 'Single Product page Add-to-Cart button border color', 'metamax' ),
                            'type' 			=> 'text',
                            'addrowclasses' => 'grid-col-4',
                            'atts' 			=> 'data-default-color="#ffe27a"',
                            'value'			=> '#ffe27a'
                        ),


                        'woo_single_button_color_hover' => array(
                            'title' => esc_html__( 'Single Product page Add-to-Cart button text color (on Hover)', 'metamax' ),
                            'type' 			=> 'text',
                            'addrowclasses' => 'grid-col-4',
                            'atts' 			=> 'data-default-color="#9d5f36"',
                            'value'			=> '#9d5f36'
                        ),
                        'woo_single_button_bg_hover' => array(
                            'title' => esc_html__( 'Single Product page Add-to-Cart button background color (on Hover)', 'metamax' ),
                            'type' 			=> 'text',
                            'addrowclasses' => 'grid-col-4',
                            'atts' 			=> 'data-default-color="#ffffff"',
                            'value'			=> '#ffffff'
                        ),
                        'woo_single_button_bd_hover' => array(
                            'title' => esc_html__( 'Single Product page Add-to-Cart button border color (on Hover)', 'metamax' ),
                            'type' 			=> 'text',
                            'addrowclasses' => 'grid-col-4',
                            'atts' 			=> 'data-default-color="#ffe27a"',
                            'value'			=> '#ffe27a'
                        ),
					)
				),
				'woo_menu_options' => array(
					'type' 	=> 'tab',
					'icon' 	=> array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Menu', 'metamax' ),
					'layout' => array(
						'woo_customize_menu'	=> array(
							'title'	=> esc_html__( 'Customize WooCommerce Menu', 'metamax' ),
							'type'	=> 'checkbox',
							'addrowclasses' => 'checkbox alt grid-col-12',
							'atts' => 'data-options="e:show_menu_bg_color;e:woo_menu_font_color;e:woo_menu_font_hover_color;e:woo_menu_border_color;e:woo_header_covers_slider"',		
						),							
						'show_menu_bg_color' => array(
							'title' => esc_html__( 'Add Background Color', 'metamax' ),
							'addrowclasses' => 'checkbox disable',
							'type' => 'checkbox',
							'atts' => 'data-options="e:woo_menu_opacity;e:woo_menu_bg_color"',
						),
						'woo_menu_opacity' => array(
							'title' 		=> esc_html__( 'Opacity', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Menu Opacity', 'metamax' ),
								'content' => esc_html__( 'This option will apply a transparent header when set to 0. Options available from 0 to 100', 'metamax' ),
							),								
							'type' 			=> 'number',
							'addrowclasses' => 'grid-col-6 disable',
							'atts' 			=> " min='0' max='100'",
							'value'			=> '0'
						),
						'woo_menu_bg_color' => array(
							'title' 		=> esc_html__( 'Background Color', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Background Color', 'metamax' ),
								'content' => esc_html__( 'Change the background color of the menu and logo area.', 'metamax' ),
							),							
							'type' 			=> 'text',
							'addrowclasses' => 'grid-col-6 disable',
							'atts' 			=> 'data-default-color="#ffffff"',
							'value'			=> '#ffffff'
						),
						'woo_menu_font_color' => array(
							'title' 		=> esc_html__( 'Override Font Color', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Override Font Color', 'metamax' ),
								'content' => wp_kses(__( 'This color is applied to the main menu only, sub-menu items will use the color which is set in Typography section.<br /> This option is very useful when menu and logo covers title area or slider.', 'metamax' ), array(
								    'br' => array()
                                )),
							),							
							'type' 			=> 'text',
							'addrowclasses' => 'grid-col-6 disable',
							'atts' 			=> 'data-default-color="#ffffff;"',
							'value'			=> '#ffffff'
						),
						'woo_menu_font_hover_color' => array(
							'title' 		=> esc_html__( 'Override Font Color on Hover', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Override Font Color on Hover', 'metamax' ),
								'content' => wp_kses(__( 'This color is applied to the main menu on mouse hover only, sub-menu items will use the color which is set in Typography section.<br /> This option is very useful when menu and logo covers title area or slider.', 'metamax' ), array(
								    'br' => array()
                                )),
							),							
							'type' 			=> 'text',
							'addrowclasses' => 'grid-col-6 disable',
							'atts' 			=> 'data-default-color="#ffffff;"',
							'value'			=> '#ffffff'
						),			
						'woo_menu_border_color' => array(
							'title' 		=> esc_html__( 'Override Border Color', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Override Border Color', 'metamax' ),
								'content' => wp_kses(__( 'This color is applied to the main menu only, sub-menu items will use the color which is set in Typography section.<br /> This option is very useful when menu and logo covers title area or slider.', 'metamax' ), array(
								    'br' => array()
                                )),
							),							
							'type' 			=> 'text',
							'addrowclasses' => 'grid-col-12 disable',
							'atts' 			=> 'data-default-color="#fff;"',
							'value'			=> '#fff'
						),
						'woo_header_covers_slider' => array(
							'title' => esc_html__( 'Header Hover Slider', 'metamax' ),
							'tooltip' => array(
								'title' => esc_html__( 'Menu Overlays Slider', 'metamax' ),
								'content' => wp_kses(__( 'This option will force the menu and logo sections to overlay the title area. <br> It is useful when using transparent menu.', 'metamax' ), array(
								    'br' => array()
                                )),
							),							
							'type' => 'checkbox',
							'addrowclasses' => 'checkbox grid-col-12 disable'
						),		
						'woo_customize_logotype'	=> array(
							'title'	=> esc_html__( 'Customize WooCommerce Logotype', 'metamax' ),
							'type'	=> 'checkbox',
							'addrowclasses' => 'checkbox alt grid-col-12',
							'atts' => 'data-options="e:logo_woo"',		
						),
						'logo_woo' => array(
							'title' => esc_html__( 'Logotype Woocommerce', 'metamax' ),
							'type' => 'media',
							'url-atts' => 'readonly',
							'addrowclasses' => 'grid-col-12 disable',
							'layout' => array(
								'is_high_dpi' => array(
									'title' => esc_html__( 'High-Resolution logo', 'metamax' ),
									'addrowclasses' => 'checkbox',
									'type' => 'checkbox',
								),
							),
						),
					)
				),
				'woo_title_options' => array(
					'type' 	=> 'tab',
					'icon' 	=> array('fa', 'arrow-circle-o-up'),
					'title' => esc_html__( 'Title Area', 'metamax' ),
					'layout' => array(
						'woo_customize_title'	=> array(
							'title'	=> esc_html__( 'Customize WooCommerce Title Area', 'metamax' ),
							'type'	=> 'checkbox',
							'atts' => 'data-options="e:woo_hide_title"',
							'addrowclasses' => 'checkbox alt grid-col-12'		
						),	
						'woo_hide_title'	=> array(
							'title'	=> esc_html__( 'Switch on/off the title area', 'metamax' ),
							'type'	=> 'checkbox',
							'atts' => 'data-options="e:woo_page_title_spacings;e:woo_default_header_image;e:woo_header_font_color;e:woo_header_helper_font_color;e:woo_header_helper_hover_font_color;e:woo_color_overlay_type;"',
							'addrowclasses' => 'checkbox alt grid-col-12 disable'		
						),
                        'woo_subtitle_content' => array(
                            'title' => esc_html__( 'Subtitle Content', 'metamax' ),
                            'addrowclasses' => 'grid-col-12 full_row',
                            'type' => 'textarea',
                            'atts' => 'rows="2"',
                        ),
						'woo_page_title_spacings' => array(
							'title' => esc_html__( 'Add Spacings (px)', 'metamax' ),
							'type' => 'margins',
							'value' => array(
								'top' => array('placeholder' => esc_attr__( 'Top', 'metamax' ), 'value' => '37'),
								'left' => array('placeholder' => esc_attr__( 'left', 'metamax' ), 'value' => '0'),
								'right' => array('placeholder' => esc_attr__( 'Right', 'metamax' ), 'value' => '0'),
								'bottom' => array('placeholder' => esc_attr__( 'Bottom', 'metamax' ), 'value' => '36'),
							),
							'addrowclasses' => 'grid-col-6 disable'
						),
						'woo_default_header_image'	=> array(
							'title'	=> esc_html__( 'Add Background Image', 'metamax' ),
							'addrowclasses' => 'grid-col-6 disable',
							'type'	=> 'media'
						),
                        'woo_header_font_color' => array(
                            'title'	=> esc_html__( 'Override Title Color', 'metamax' ),
                            'atts' => 'data-default-color="#ffe27a"',
                            'value' => '#ffe27a',
                            'addrowclasses' => 'disable grid-col-6',
                            'type'	=> 'text',
                        ),
                        'woo_header_helper_font_color' => array(
                            'title'	=> esc_html__( 'Subtitle content/Breadcrumbs Font Color', 'metamax' ),
                            'atts' => 'data-default-color="#ffffff"',
                            'value' => '#ffffff',
                            'addrowclasses' => 'disable grid-col-6',
                            'type'	=> 'text',
                        ),
                        'woo_header_helper_hover_font_color' => array(
                            'title'	=> esc_html__( 'Breadcrumbs hover Font Color', 'metamax' ),
                            'atts' => 'data-default-color="#ffe27a"',
                            'value' => '#ffe27a',
                            'addrowclasses' => 'disable grid-col-6',
                            'type'	=> 'text',
                        ),
						'woo_color_overlay_type'	=> array(
							'title'		=> esc_html__( 'Color Overlay', 'metamax' ),
							'addrowclasses' => 'grid-col-12 disable',
							'type'	=> 'select',
							'source'	=> array(
								'none' => array( esc_html__( 'None', 'metamax' ),  true, 'd:woo_color_overlay_opacity;d:woo_overlay_color;d:woo_gradient_settings;' ),
								'color' => array( esc_html__( 'Color', 'metamax' ),  false, 'e:woo_color_overlay_opacity;e:woo_overlay_color;d:woo_gradient_settings;' ),
								'gradient' => array( esc_html__( 'Gradient', 'metamax' ), false, 'e:woo_color_overlay_opacity;d:woo_overlay_color;e:woo_gradient_settings;' )
							),
						),
						'woo_color_overlay_opacity' => array(
							'type' => 'number',
							'addrowclasses' => 'disable grid-col-12',
							'title' => esc_html__( 'Opacity', 'metamax' ),
							'placeholder' => esc_attr__( 'In percents', 'metamax' ),
							'value' => '40'
						),
						'woo_overlay_color'	=> array(
							'title'	=> esc_html__( 'Overlay color', 'metamax' ),
							'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
							'addrowclasses' => 'disable grid-col-12',
							'value' => METAMAX_FIRST_COLOR,
							'type'	=> 'text'
						),
						'woo_gradient_settings' => array(
							'title' => esc_html__( 'Gradient Settings', 'metamax' ),
							'type' => 'fields',
							'addrowclasses' => 'disable box inside-box groups grid-col-12',
							'layout' => array(
								'first_color' => array(
									'type' => 'text',
									'title' => esc_html__( 'From', 'metamax' ),
									'atts' => 'data-default-color=""',
								),
								'second_color' => array(
									'type' => 'text',
									'title' => esc_html__( 'To', 'metamax' ),
									'atts' => 'data-default-color=""',
								),
								'first_color_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'From (Opacity %)', 'metamax' ),
									'value' => '100',
								),
								'second_color_opacity' => array(
									'type' => 'number',
									'title' => esc_html__( 'To (Opacity %)', 'metamax' ),
									'value' => '100',
								),
								'type' => array(
									'title' => esc_html__( 'Gradient type', 'metamax' ),
									'type' => 'radio',
									'value' => array(
										'linear' => array( esc_html__( 'Linear', 'metamax' ),  true, 'e:linear_settings;d:radial_settings' ),
										'radial' =>array( esc_html__( 'Radial', 'metamax' ), false,  'd:linear_settings;e:radial_settings' ),
										),
								),
								'linear_settings' => array(
									'title' => esc_html__( 'Linear settings', 'metamax'  ),
									'type' => 'fields',
									'addrowclasses' => 'disable',
									'layout' => array(
										'angle' => array(
											'type' => 'number',
											'title' => esc_html__( 'Angle', 'metamax' ),
											'value' => '45',
										),
									)
								),
								'radial_settings' => array(
									'title' => esc_html__( 'Radial settings', 'metamax'  ),
									'type' => 'fields',
									'addrowclasses' => 'disable',
									'layout' => array(
										'shape_settings' => array(
											'title' => esc_html__( 'Shape', 'metamax' ),
											'type' => 'radio',
											'value' => array(
												'simple' => array( esc_html__( 'Simple', 'metamax' ),  true, 'e:shape;d:size;d:size_keyword;' ),
												'extended' =>array( esc_html__( 'Extended', 'metamax' ), false, 'd:shape;e:size;e:size_keyword;' ),
											),
										),
										'shape' => array(
											'title' => esc_html__( 'Gradient type', 'metamax' ),
											'type' => 'radio',
											'value' => array(
												'ellipse' => array( esc_html__( 'Ellipse', 'metamax' ),  true ),
												'circle' =>array( esc_html__( 'Circle', 'metamax' ), false ),
											),
										),
										'size_keyword' => array(
											'type' => 'select',
											'title' => esc_html__( 'Size keyword', 'metamax' ),
											'addrowclasses' => 'disable',
											'source' => array(
												'closest-side' => array(esc_html__( 'Closest side', 'metamax' ), false),
												'farthest-side' => array(esc_html__( 'Farthest side', 'metamax' ), false),
												'closest-corner' => array(esc_html__( 'Closest corner', 'metamax' ), false),
												'farthest-corner' => array(esc_html__( 'Farthest corner', 'metamax' ), true),
											),
										),
										'size' => array(
											'type' => 'text',
											'addrowclasses' => 'disable',
											'title' => esc_html__( 'Size', 'metamax' ),
											'atts' => 'placeholder="'.esc_attr__( 'Two space separated percent values, for example (60% 55%)', 'metamax' ).'"',
										),
									)
								),
							)
						),
					)
				)
			)
		);
	}

	if (function_exists('cws_core_build_settings')) {
		cws_core_build_settings($settings, $g_components);
	}
	return $settings;
}

/*
	here local or overrided components can be added/changed
*/
function cwsfw_get_local_components() {
	return array();
}
?>