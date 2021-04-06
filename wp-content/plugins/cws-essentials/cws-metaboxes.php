<?php
defined('METAMAX_FIRST_COLOR') or define('METAMAX_FIRST_COLOR', '#1f5abc');
defined('METAMAX_SECOND_COLOR') or define('METAMAX_SECOND_COLOR', '#40a6ff');
defined('METAMAX_THIRD_COLOR') or define('METAMAX_THIRD_COLOR', '#1a397f');

if (!is_customize_preview()) {
	new Metamax_Metaboxes();
}

class Metamax_Metaboxes {
	public $mb_page_layout = array();
	public $mb_staff_layout = array();
	public $mb_portfolio_layout = array();
	public $mb_classes_layout = array();

	public static $instance;

	public function __construct($a = null) {
	
		$this->mb_page_layout = array(
			'tab0' => array(
				'type' => 'tab',
				'init' => 'open',
				'title' => esc_html__( 'General', 'cws-essentials' ),
				'layout' => array(
					'MB_general' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Customize', 'cws-essentials' ),
						'atts' => 'data-options="e:sb_layout;e:is_blog;e:page_sidebars;e:slider_override;e:page_spacing"',
						'addrowclasses' => 'checkbox alt box',
					),
					'page_sidebars' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups',
						'layout' => array(
							'layout' => array(
								'title' => esc_html__('Sidebar Position', 'cws-essentials' ),
								'type' => 'radio',
								'addrowclasses' => 'grid-col-12',
								'subtype' => 'images',
								'value' => array(
									'{page_sidebars}'=>	array( esc_html__('Default', 'cws-essentials' ), false, 'd:def--sidebar1;d:def--sidebar2', '/img/default.png' ),
									'left' => array( esc_html__('Left', 'cws-essentials' ), false, 'e:sb1;d:sb2',	'/img/left.png' ),
									'right' => array( esc_html__('Right', 'cws-essentials' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
									'both' => array( esc_html__('Double', 'cws-essentials' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
									'none' => array( esc_html__('None', 'cws-essentials' ), true, 'd:sb1;d:sb2', '/img/none.png' )
								),
							),
							'sb1' => array(
								'title' => esc_html__('Select a sidebar', 'cws-essentials' ),
								'type' => 'select',
								'addrowclasses' => 'disable box grid-col-6',
								'source' => 'sidebars',
							),
							'sb2' => array(
								'title' => esc_html__('Select right sidebar', 'cws-essentials' ),
								'type' => 'select',
								'addrowclasses' => 'disable box grid-col-6',
								'source' => 'sidebars',
							),
						),
					),
					'is_blog' => array(
						'type' => 'checkbox',
						'title' => esc_html__('Add Blog posts', 'cws-essentials' ),
						'atts' => 'data-options="e:blogtype;e:category"',
						'addrowclasses' => 'disable checkbox grid-col-12',
					),
					'blogtype' => array(
						'type' => 'radio',
						'subtype' => 'images',
						'title' => esc_html__('Blog Layout', 'cws-essentials' ),
						'addrowclasses' => 'disable grid-col-12',
						'value' => array(
							'default'=>	array( esc_html__('Default', 'cws-essentials' ), false, '', '/img/default.png' ),
							'large' => array( esc_html__('Large', 'cws-essentials' ), false, '', '/img/large.png' ),
							'medium' => array( esc_html__('Medium', 'cws-essentials' ), true, '', '/img/medium.png' ),
							'small' => array( esc_html__('Small', 'cws-essentials' ), false, '', '/img/small.png' ),
							'2' => array(  esc_html__('Two', 'cws-essentials' ), false, '', '/img/pinterest_2_columns.png'),
							'3' => array( esc_html__('Three', 'cws-essentials' ), false, '', '/img/pinterest_3_columns.png'),
							'4' => array( esc_html__('Four', 'cws-essentials' ), false, '', '/img/pinterest_4_columns.png'),
						),
					),
					'category' => array(
						'title' => esc_html__('Category', 'cws-essentials' ),
						'type' => 'taxonomy',
						'addrowclasses' => 'disable grid-col-12',
						'atts' => 'multiple',
						'taxonomy' => 'category',
						'source' => array(),
					),
					'page_spacing' => array(
						'title' => esc_html__( 'Page Spacings', 'cws-essentials' ),
						'type' => 'margins',
						'addrowclasses' => 'disable grid-col-12 two-inputs',
						'value' => array(
							'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '63'),
							'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '120'),
						),
					),
					'slider_override' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups',
						'layout' => array(
							'is_override' => array(
								'type' => 'checkbox',
								'title' => esc_html__( 'Add Image Slider', 'cws-essentials' ),
								'atts' => 'data-options="e:slider_shortcode;e:is_wide;"',
								'addrowclasses' => 'checkbox grid-col-12',
							),
							'slider_shortcode' => array(
								'addrowclasses' => 'disable box grid-col-12',
								'type' => 'text',
								'default' => ''
							),
							'is_wide' => array( // wide_slider
								'type' => 'checkbox',
								'title' => esc_html__( 'Full-Width Slider', 'cws-essentials' ),
								'atts' => 'checked',
								'addrowclasses' => 'disable checkbox box grid-col-12',
							),
						),
					),
				),
			),
			'tab1' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Styling', 'cws-essentials' ),
				'layout' => array(
					'MB_styling' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'type' => 'checkbox',
						'atts' => 'data-options="e:theme_first_color;e:theme_second_color;e:theme_third_color;"',
					),				
					'theme_first_color' => array(
						'title' => esc_html__( 'Main color', 'cws-essentials' ),
						'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
						'value' => METAMAX_FIRST_COLOR,
						'addrowclasses' => 'grid-col-2_5',
						'type' => 'text',
					),
					'theme_second_color' => array(
						'title' => esc_html__( 'Second color', 'cws-essentials' ),
						'atts' => 'data-default-color="' . METAMAX_SECOND_COLOR . '"',
						'value' => METAMAX_SECOND_COLOR,
						'addrowclasses' => 'grid-col-2_5',
						'type' => 'text',
					),
                    'theme_third_color' => array(
                        'title' => esc_html__( 'Third color', 'cws-essentials' ),
                        'atts' => 'data-default-color="' . METAMAX_THIRD_COLOR . '"',
                        'value' => METAMAX_THIRD_COLOR,
                        'addrowclasses' => 'grid-col-2_5',
                        'type' => 'text',
                    ),
				),
			),
			'tab2' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Header', 'cws-essentials' ),
				'layout' => array(
					'MB_header' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'type' => 'checkbox',
						'atts' => 'data-options="e:header;"',
					),

					'header' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'order' => array(
								'type' => 'group',
								'addrowclasses' => 'group sortable drop grid-col-12',
								'tooltip' => array(
									'title' => esc_html__( 'Header order', 'cws-essentials' ),
									'content' => esc_html__( 'Drag to reorder and customize your header.', 'cws-essentials' ),
								),
								'title' => esc_html__('Header order', 'cws-essentials' ),
								'value' => array(
									array('title' => 'Top Bar','val' => 'top_bar_box'),
									array('title' => 'Header Zone','val' => 'drop_zone_start'),
									array('title' => 'Logo','val' => 'logo_box'),
									array('title' => 'Menu','val' => 'menu_box'),
									array('title' => 'Header Zone','val' => 'drop_zone_end'),
									array('title' => 'Title area','val' => 'title_box'),
								),
								'layout' => array(
									'title' => array(
										'type' => 'text',
										'value' => '',
										'atts' => 'data-role="title"',
										'title' => esc_html__('Sidebar', 'cws-essentials' ),
									),
									'val' => array(
										'type' => 'text',
									)

								)
							),
							'customize' => array(
								'title' => esc_html__( 'Customize', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
								'atts' => 'data-options="e:background_image;e:overlay;e:spacings;e:override_topbar_color;e:override_topbar_color_hover;"',
							),
							'background_image' => array(
								'type' => 'fields',
								'addrowclasses' => 'box grid-col-12 inside-box groups',
								'layout' => '%image_layout%',
							),
							'overlay' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 disable box inside-box groups',
								'layout' => array(
									'type'	=> array(
										'title'		=> esc_html__( 'Add Color overlay', 'cws-essentials' ),
										'addrowclasses' => 'grid-col-4',
										'type'	=> 'select',
										'source'	=> array(
											'none' => array( esc_html__( 'None', 'cws-essentials' ),  true, 'd:opacity;d:color;d:gradient;' ),
											'color' => array( esc_html__( 'Color', 'cws-essentials' ),  false, 'e:opacity;e:color;d:gradient;' ),
											'gradient' => array( esc_html__('Gradient', 'cws-essentials' ), false, 'e:opacity;d:color;e:gradient;' )
										),
									),
									'color'	=> array(
										'title'	=> esc_html__( 'Overlay color', 'cws-essentials' ),
										'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
										'addrowclasses' => 'grid-col-4',
										'value' => METAMAX_FIRST_COLOR,
										'type'	=> 'text',
									),
									'opacity' => array(
										'type' => 'number',
										'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
										'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
										'value' => '40',
										'addrowclasses' => 'grid-col-4',
									),
									'gradient' => array(
										'title' => esc_html__( 'Gradient Settings', 'cws-essentials' ),
										'type' => 'fields',
										'addrowclasses' => 'grid-col-12 disable box inside-box groups',
										'layout' => '%gradient_layout%',
									),
								),
							),
							'override_topbar_color'	=> array(
								'title'	=> esc_html__( 'Override TopBar\'s Font Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#c5cfff"',
								'addrowclasses' => 'grid-col-6 disable',
								'value' => '#c5cfff',
								'type'	=> 'text',
							),
							'override_topbar_color_hover' => array(
								'title'	=> esc_html__( 'Override TopBar\'s Font Color on Hover', 'cws-essentials' ),
								'atts' => 'data-default-color="#feb556"',
								'addrowclasses' => 'grid-col-6 disable',
								'value' => '#feb556',
								'type'	=> 'text',
							),
							'spacings' => array(
								'title' => esc_html__( 'Add Spacings', 'cws-essentials' ),
								'type' => 'margins',
								'addrowclasses' => 'disable grid-col-4 two-inputs',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '10'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '10'),
								),
							),
							'outside_slider' => array(
								'title' => esc_html__( 'Header Overlays Slider', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
							),
							'outside_content' => array(
								'title' => esc_html__( 'Header Overlays Content', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
							),						
						),
					),

				),
			),
			'tab3' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Logo', 'cws-essentials' ),
				'layout' => array(
					'MB_logo' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'atts' => 'data-options="e:logo_box;"',
						'type' => 'checkbox',
					),

					'logo_box' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'enable' => array(
								'title' => esc_html__( 'Logo', 'cws-essentials' ),
								'addrowclasses' => 'checkbox alt grid-col-12',
								'type' => 'checkbox',
								'atts' => 'checked',
							),													
							'default' => array(
								'title'		=> esc_html__( 'Display Logo Variation', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-12',
								'type'	=> 'select',
								'source'	=> array(
									'dark' => array( esc_html__( 'Dark', 'cws-essentials' ),  false, 'd:custom;' ),
									'light' => array( esc_html__( 'Light', 'cws-essentials' ),  true, 'd:custom;' ),
									'custom' => array( esc_html__( 'Custom', 'cws-essentials' ),  false, 'e:custom;e:custom_mobile_logo;' ),
								),
							),
							'custom' => array(
								'title' => esc_html__( 'Custom Logo', 'cws-essentials' ),
								'type' => 'media',
								'url-atts' => 'readonly',
								'addrowclasses' => 'grid-col-12',
								'layout' => array(
									'is_high_dpi' => array(
										'title' => esc_html__( 'High-Resolution logo', 'cws-essentials' ),
										'addrowclasses' => 'checkbox grid-col-6',
										'type' => 'checkbox',
									),
									'custom_mobile_logo' => array(
										'title' => esc_html__('Override mobile logo', 'cws-essentials'),
										'addrowclasses' => 'checkbox grid-col-6',
										'type' => 'checkbox',
									),
								),
							),
							'dimensions' => array(
								'title' => esc_html__( 'Logo Dimensions', 'cws-essentials' ),
								'type' => 'dimensions',
								'addrowclasses' => 'grid-col-12',
								'value' => array(
									'width' => array('placeholder' => esc_html__( 'Width', 'cws-essentials' ), 'value' => ''),
									'height' => array('placeholder' => esc_html__( 'Height', 'cws-essentials' ), 'value' => ''),
									),
							),
							'margin' => array(
								'title' => esc_html__( 'Margins (px)', 'cws-essentials' ),
								'type' => 'margins',
								'addrowclasses' => 'grid-col-12',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '0'),
									'left' => array('placeholder' => esc_html__( 'left', 'cws-essentials' ), 'value' => '0'),
									'right' => array('placeholder' => esc_html__( 'Right', 'cws-essentials' ), 'value' => '0'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '0'),
								),
							),
							'position' => array(
								'title' => esc_html__( 'Position', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'grid-col-12',
								'value' => array(
									'left' => array( esc_html__('Left', 'cws-essentials'), true, 'd:site_name_in_menu;e:with_site_name;', '/img/align-left.png' ),
									'center' =>array( esc_html__('Center', 'cws-essentials'), false, 'e:site_name_in_menu;e:with_site_name;', '/img/align-center.png', ),
									'right' =>array( esc_html__('Right', 'cws-essentials'), false, 'd:site_name_in_menu;e:with_site_name;', '/img/align-right.png', ),
								),
							),								
							'in_menu' => array(
								'title' => esc_html__( 'Logo in menu box', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
								'atts' => 'checked data-options="d:wide;d:overlay;d:border;"',
							),		
							'wide' => array(
								'title' => esc_html__( 'Apply Full-Width Container', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
							),													
							'overlay' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 box inside-box groups',
								'layout' => array(
									'type'	=> array(
										'title'		=> esc_html__( 'Add Background Color Overlay to the Logo Area', 'cws-essentials' ),
										'addrowclasses' => 'grid-col-4',
										'type'	=> 'select',
										'source'	=> array(
											'none' => array( esc_html__( 'None', 'cws-essentials' ),  true, 'd:opacity;d:color;d:gradient;' ),
											'color' => array( esc_html__( 'Color', 'cws-essentials' ),  false, 'e:opacity;e:color;d:gradient;' ),
											'gradient' => array( esc_html__('Gradient', 'cws-essentials' ), false, 'e:opacity;d:color;e:gradient;' )
										),
									),
									'color'	=> array(
										'title'	=> esc_html__( 'Overlay color', 'cws-essentials' ),
										'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
										'addrowclasses' => 'grid-col-4',
										'value' => METAMAX_FIRST_COLOR,
										'type'	=> 'text',
									),
									'opacity' => array(
										'type' => 'number',
										'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
										'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
										'value' => '40',
										'addrowclasses' => 'grid-col-4',
									),
									'gradient' => array(
										'title' => esc_html__( 'Gradient Settings', 'cws-essentials' ),
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
						),
					),

				),
			),
			'tab4' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Menu', 'cws-essentials' ),
				'layout' => array(
					'MB_menu' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'type' => 'checkbox',
						'atts' => 'data-options="e:menu_box;"',
					),

					'menu_box' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'enable' => array(
								'title' => esc_html__( 'Menu', 'cws-essentials' ),
								'addrowclasses' => 'checkbox alt grid-col-12',
								'type' => 'checkbox',
								'atts' => 'checked',
							),								
							'position' => array(
								'title' => esc_html__( 'Menu Alignment', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'new_row grid-col-3',
								'value' => array(
									'left' => array( esc_html__( 'Left', 'cws-essentials' ), 	false, '', '/img/align-left.png' ),
									'center' =>array( esc_html__( 'Center', 'cws-essentials' ), false, '', '/img/align-center.png' ),
									'right' =>array( esc_html__( 'Right', 'cws-essentials' ), true, '', '/img/align-right.png' ),
								),
							),
							'search_place' => array(
								'title' => esc_html__( 'Search Icon Location', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'grid-col-3',
								'value' => array(
									'none' => array( esc_html__( 'None', 'cws-essentials' ), 	false, '', '/img/no_layout.png' ),
									'top' => array( esc_html__( 'Top', 'cws-essentials' ), 	false, '', '/img/search-social-right.png' ),
									'left' =>array( esc_html__( 'Left', 'cws-essentials' ), false, '', '/img/search-menu-left.png' ),
									'right' =>array( esc_html__( 'Right', 'cws-essentials' ), true, '', '/img/search-menu-right.png' ),
								),
							),						
							'mobile_place' => array(
								'title' => esc_html__( 'Mobile Menu Location', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'grid-col-3',
								'value' => array(
									'left' =>array( esc_html__( 'Left', 'cws-essentials' ), true, '', '/img/hamb-left.png' ),
									'right' =>array( esc_html__( 'Right', 'cws-essentials' ), false, '', '/img/hamb-right.png' ),
								),
							),
							'background_color' => array(
								'title' => esc_html__( 'Background color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'grid-col-6',
								'type' => 'text',
							),
							'background_opacity' => array(
								'type' => 'number',
								'title' => esc_html__( 'Opacity', 'cws-essentials' ),
								'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-6',
								'value' => '0'
							),
							'font_color' => array(
								'type' => 'text',
								'title' => esc_html__( 'Override Font color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'grid-col-6',
							),
							'font_color_hover' => array(
								'type' => 'text',
								'title' => esc_html__( 'Override Font hover color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'grid-col-6',
							),
                            'highlight_color' => array(
                                'type' => 'text',
                                'title' => esc_html__( 'Highlight color', 'cws-essentials' ),
                                'atts' => 'data-default-color="#ffe27a"',
                                'value' => '#ffe27a',
                                'addrowclasses' => 'grid-col-12',
                            ),

                            'submenu_font_color' => array(
                                'type' => 'text',
                                'title' => esc_html__( 'Submenu item Font color', 'cws-essentials' ),
                                'atts' => 'data-default-color="#ffffff"',
                                'value' => '#ffffff',
                                'addrowclasses' => 'grid-col-6',
                            ),
                            'submenu_font_color_hover' => array(
                                'type' => 'text',
                                'title' => esc_html__( 'Submenu item Font color on Hover', 'cws-essentials' ),
                                'atts' => 'data-default-color="#9c8635"',
                                'value' => '#9c8635',
                                'addrowclasses' => 'grid-col-6',
                            ),
                            'submenu_bg_color' => array(
                                'type' => 'text',
                                'title' => esc_html__( 'Submenu item Background color', 'cws-essentials' ),
                                'atts' => 'data-default-color="'.METAMAX_THIRD_COLOR.'"',
                                'value' => METAMAX_THIRD_COLOR,
                                'addrowclasses' => 'grid-col-6',
                            ),
                            'submenu_bg_color_hover' => array(
                                'type' => 'text',
                                'title' => esc_html__( 'Submenu item Background color on Hover', 'cws-essentials' ),
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
								'title' => esc_html__( 'Add Spacings', 'cws-essentials' ),
								'type' => 'margins',
								'addrowclasses' => 'grid-col-4 two-inputs',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '35'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '35'),
								),
							),
							'menu_mode'	=> array(
								'title'		=> esc_html__( 'Desktop Menu on Devices', 'cws-essentials' ),
								'type'	=> 'select',
								'addrowclasses' => 'grid-col-12',
								'source'	=> array(
									'default' => array( esc_html__( 'Default', 'cws-essentials' ),  true ),
									'portrait' => array( esc_html__( 'Portrait', 'cws-essentials' ), false ),
									'landdscape' => array( esc_html__( 'Landscape', 'cws-essentials' ), false ),
									'both' => array( esc_html__( 'Both', 'cws-essentials' ), false ),
								),
							),
							'sandwich' => array(
								'title' => esc_html__( 'Use mobile menu on desktop PCs', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-4',
								'type' => 'checkbox',
							),			
							'wide' => array(
								'title' => esc_html__( 'Apply Full-Width Menu', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-4',
								'type' => 'checkbox',
							),
							'override_menu' => array(
								'type' => 'checkbox',
								'title' => esc_html__( 'Use Custom Menu on this Page', 'cws-essentials' ),
								'atts' => 'data-options="e:custom_menu;"',
								'addrowclasses' => 'checkbox grid-col-6',
							),	
							'custom_menu' => array(
								'title' => esc_html__('Select a menu', 'cws-essentials' ),
								'addrowclasses' => 'disable grid-col-12',
								'type' => 'taxonomy',
								'source' => array(),
								'taxonomy' => 'nav_menu',
							),
						),
					),

				),
			),
			'tab5' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Mobile Menu', 'cws-essentials' ),
				'layout' => array(
					'MB_mobile_menu' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'type' => 'checkbox',
						'atts' => 'data-options="e:mobile_menu_box;"',
					),

					'mobile_menu_box' => array(
						'type' => 'fields',
						'addrowclasses' => 'inside-box groups grid-col-12 box',
						'layout' => array(
                            'mobile' => array(
                                'title' => esc_html__( 'Mobile Logo', 'metamax' ),
                                'type' => 'media',
                                'url-atts' => 'readonly',
                                'addrowclasses' => 'grid-col-6',
                                'layout' => array(
                                    'logo_mobile_is_high_dpi' => array(
                                        'title' => esc_html__( 'High-Resolution mobile logo', 'cws-essentials' ),
                                        'addrowclasses' => 'checkbox',
                                        'type' => 'checkbox',
                                    ),
                                ),
                            ),
                            'dimensions_mobile' => array(
                                'title' => esc_html__( 'Mobile Logo Dimensions', 'cws-essentials' ),
                                'type' => 'dimensions',
                                'addrowclasses' => 'grid-col-6',
                                'value' => array(
                                    'width' => array('placeholder' => esc_html__( 'Width', 'cws-essentials' ), 'value' => ''),
                                    'height' => array('placeholder' => esc_html__( 'Height', 'cws-essentials' ), 'value' => ''),
                                ),
                            ),
						),
					),

				),
			),
			'tab6' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Sticky', 'cws-essentials' ),
				'layout' => array(
					'MB_sticky_menu' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'type' => 'checkbox',
						'atts' => 'data-options="e:sticky_menu;"',
					),

					'sticky_menu' => array(
						'type' => 'fields',
						'addrowclasses' => 'inside-box groups grid-col-12 box',
						'layout' => array(
							'enable' => array(
								'title' => esc_html__( 'Sticky Menu', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12 alt',
								'type' => 'checkbox',
							),
                            'sticky' => array(
                                'title' => esc_html__( 'Sticky Logo', 'cws-essentials' ),
                                'type' => 'media',
                                'url-atts' => 'readonly',
                                'addrowclasses' => 'grid-col-6',
                                'layout' => array(
                                    'logo_sticky_is_high_dpi' => array(
                                        'title' => esc_html__( 'High-Resolution sticky logo', 'cws-essentials' ),
                                        'addrowclasses' => 'checkbox',
                                        'type' => 'checkbox',
                                    ),
                                ),
                            ),
							'mode'	=> array(
								'title'		=> esc_html__( 'Select a Sticky\'s Mode', 'cws-essentials' ),
								'type'	=> 'select',
								'addrowclasses' => 'grid-col-6',
								'source'	=> array(
									'smart' => array( esc_html__( 'Smart', 'cws-essentials' ),  true ),
									'simple' => array( esc_html__( 'Simple', 'cws-essentials' ), false ),
								),
							),
							'margin_sticky' => array(
								'title' => esc_html__( 'Sticky Menu Spacings', 'cws-essentials' ),
								'type' => 'margins',
								'tooltip' => array(
									'title' => esc_html__('Sticky menu spacings', 'cws-essentials'),
									'content' => esc_html__('These values should not exceed the menu spacings, which are set in Menu\'s section', 'cws-essentials'),
								),
								'addrowclasses' => 'grid-col-6 two-inputs',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '12'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '12'),
								),
							),			
							'background_color' => array(
								'title' => esc_html__( 'Background color', 'cws-essentials' ),
								'tooltip' => array(
									'title' => esc_html__( 'Background Color', 'cws-essentials' ),
									'content' => esc_html__( 'This color is applied to header section including top bar.', 'cws-essentials' ),
								),
								'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
								'value' => METAMAX_FIRST_COLOR,
								'addrowclasses' => 'grid-col-6',
								'type' => 'text',
							),
							'background_opacity' => array(
								'type' => 'number',
								'title' => esc_html__( 'Background Opacity', 'cws-essentials' ),
								'tooltip' => array(
									'title' => esc_html__( 'Background Opacity', 'cws-essentials' ),
									'content' => esc_html__( 'This option will apply the transparent header when set to "0".', 'cws-essentials' ),
								),
								'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-6',
								'value' => '100'
							),
							'font_color' => array(
								'title' => esc_html__( 'Override Font color', 'cws-essentials' ),
								'tooltip' => array(
									'title' => esc_html__( 'Override Font Color', 'cws-essentials' ),
									'content' => esc_html__( 'This color is applied to main menu items and menu icons, submenus will use the color which is set in Typography section.<br /> This option is very useful when transparent menu is set.', 'cws-essentials' ),
								),
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'grid-col-6',
								'type' => 'text',
							),
							'font_color_hover' => array(
								'title' => esc_html__( 'Override Font color on hover', 'cws-essentials' ),
								'tooltip' => array(
									'title' => esc_html__( 'Override Font Color on hover', 'cws-essentials' ),
									'content' => esc_html__( 'This color is applied to main menu items and menu icons on mouse hover, submenus will use the color which is set in Typography section.<br /> This option is very useful when transparent menu is set.', 'cws-essentials' ),
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
								'title' => esc_html__( 'Add Shadow', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
                                'atts' => 'checked',
							),
						),
					),

				),
			),
			'tab7' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Title', 'cws-essentials' ),
				'layout' => array(
					'MB_title_box' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'atts' => 'data-options="e:title_box;"',
						'type' => 'checkbox',
					),

					'title_box' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'enable' => array(
								'title' => esc_html__( 'Title Area', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12 alt',
								'atts' => 'checked',
								'type' => 'checkbox',
							),
							'no_title' => array(
								'title' => esc_html__( 'Hide Page Title', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
							),							
							'customize' => array(
								'title' => esc_html__( 'Customize', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
								'atts' => 'data-options="e:animate;e:slide_down;e:font_color;e:helper_font_color;e:helper_hover_font_color;e:background_image;e:overlay;e:use_pattern;e:use_blur;e:effect;e:spacings;e:title_height;"',
							),						
							'background_image' => array(
								'type' => 'fields',
								'addrowclasses' => 'disable box grid-col-12 inside-box groups',
								'layout' => '%image_layout%',
							),
							'subtitle_content' => array(
								'title' => esc_html__( 'Subtitle Content', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-12 full_row',
								'type' => 'textarea',
								'atts' => 'rows="2"',
							),
							'spacings' => array(
								'title' => esc_html__( 'Title Box Spacings', 'cws-essentials' ),
								'type' => 'margins',
								'addrowclasses' => 'disable two-inputs grid-col-6',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '37'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '36'),
								),
							),
							'page_title_size' => array(
								'type' => 'number',
								'title' => esc_html__( 'Page Title Size', 'cws-essentials' ),
								'value' => '60',
								'addrowclasses' => 'grid-col-6',
							),
							'font_color' => array(
								'type'	=> 'text',
								'title'	=> esc_html__( 'Override Title Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffe27a"',
								'value' => '#ffe27a',
								'addrowclasses' => 'disable grid-col-4',
							),
							'helper_font_color' => array(
								'type'	=> 'text',
								'title'	=> esc_html__( 'Subtitle content/Breadcrumbs Font Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'disable grid-col-4',
							),
							'helper_hover_font_color' => array(
								'type'	=> 'text',
								'title'	=> esc_html__( 'Breadcrumbs hover Font Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffe27a"',
								'value' => '#ffe27a',
								'addrowclasses' => 'disable grid-col-4',
							),								
							'overlay' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 disable box inside-box groups',
								'layout' => array(
									'type'	=> array(
										'title'		=> esc_html__( 'Color overlay', 'cws-essentials' ),
										'addrowclasses' => 'grid-col-4',
										'type'	=> 'select',
										'source'	=> array(
											'none' => array( esc_html__( 'None', 'cws-essentials' ),  true, 'd:opacity;d:color;d:gradient;' ),
											'color' => array( esc_html__( 'Color', 'cws-essentials' ),  false, 'e:opacity;e:color;d:gradient;' ),
											'gradient' => array( esc_html__('Gradient', 'cws-essentials' ), false, 'e:opacity;d:color;e:gradient;' )
										),
									),
									'color'	=> array(
										'title'	=> esc_html__( 'Color Overlay', 'cws-essentials' ),
										'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
										'addrowclasses' => 'grid-col-4',
										'value' => METAMAX_FIRST_COLOR,
										'type'	=> 'text',
									),
									'opacity' => array(
										'type' => 'number',
										'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
										'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
										'value' => '100',
										'addrowclasses' => 'grid-col-4',
									),
									'gradient' => array(
										'title' => esc_html__( 'Gradient Settings', 'cws-essentials' ),
										'type' => 'fields',
										'addrowclasses' => 'grid-col-12 disable box inside-box groups',
										'layout' => '%gradient_layout%',
									),
								),
							),
							'use_pattern' => array(
								'title' => esc_html__( 'Add pattern', 'cws-essentials' ),
								'addrowclasses' => 'disable checkbox grid-col-12',
								'type' => 'checkbox',
								'atts' => 'data-options="e:pattern_image;"',
							),
							'pattern_image' => array(
								'type' => 'fields',
								'title' => esc_html__( 'Pattern image', 'cws-essentials' ),
								'addrowclasses' => 'disable box grid-col-12 inside-box groups',
								'layout' => '%image_layout%',
							),
						),
					),

				),
			),
			'tab8' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Top bar', 'cws-essentials' ),
				'layout' => array(
					'MB_topbar' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'atts' => 'data-options="e:top_bar_box;"',
						'type' => 'checkbox',
					),

					'top_bar_box' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'enable' => array(
								'title' => esc_html__( 'Top Panel', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-12 checkbox alt',
								'type' => 'checkbox',
							),
							'wide' => array(
								'title' => esc_html__( 'Apply Full-Width Top Bar', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-12 checkbox',
								'type' => 'checkbox',
							),
							'language_bar' => array(
								'title' => esc_html__( 'Add Language Bar', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-12 checkbox',
								'atts' => 'data-options="e:language_bar_position;"',
								'type' => 'checkbox',
							),
							'language_bar_position' => array(
								'title' => esc_html__( 'Language Bar Alignment', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'disable grid-col-12',
								'value' => array(
									'left' => array( esc_html__( 'Left', 'cws-essentials' ), false, '', '/img/multilingual-left.png' ),
									'right' =>array( esc_html__( 'Right', 'cws-essentials' ), true, '', '/img/multilingual-right.png' ),
								),
							),
                            'text_position' => array(
                                'title' => esc_html__( 'Content text position', 'cws-essentials' ),
                                'type' => 'radio',
                                'subtype' => 'images',
                                'addrowclasses' => 'disable grid-col-12',
                                'value' => array(
                                    'left' => array( esc_html__( 'Left', 'cws-essentials' ), false, '', '/img/multilingual-left.png' ),
                                    'right' =>array( esc_html__( 'Right', 'cws-essentials' ), true, '', '/img/multilingual-right.png' ),
                                ),
                            ),
							'social_place' => array(
								'title' => esc_html__( 'Social Icons Alignment', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'grid-col-6',
								'value' => array(
									'left' =>array( esc_html__( 'Left', 'cws-essentials' ), false, '', '/img/social-left.png' ),
									'right' =>array( esc_html__( 'Right', 'cws-essentials' ), true, '', '/img/social-right.png' ),
								),
							),		
							'toggle_share' => array(
								'title' => esc_html__( 'Toggle Social Icons', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-12 checkbox',
								'type' => 'checkbox',
                                'atts' => 'checked',
							),
							'text' => array(
								'title' => esc_html__( 'Content', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-8 full_row',
								'tooltip' => array(
									'title' => esc_html__( 'Indent Adjusting', 'cws-essentials' ),
									'content' => esc_html__( 'Adjust Indents by multiple spaces.<br /> Line breaks are working too.', 'cws-essentials' ),
								),
								'type' => 'text'
							),
							'content_items' => array(
								'type' => 'group',
								'addrowclasses' => 'grid-col-12 group expander sortable box',
								'title' => esc_html__('Top Bar Info', 'cws-essentials' ),
								'button_title' => esc_html__('Add new info row', 'cws-essentials' ),
								'layout' => array(
									'icon' => array(
										'type' => 'select',
										'addrowclasses' => 'grid-col-3 fai',
										'source' => 'fa',
										'title' => esc_html__('Select the icon', 'cws-essentials' )
									),
									'title' => array(
										'type' => 'text',
										'atts' => 'data-role="title"',
										'addrowclasses' => 'grid-col-3',
										'title' => esc_html__('Write main info', 'cws-essentials' ),
									),
									'url' => array(
										'type' => 'text',
										'addrowclasses' => 'grid-col-3',
										'title' => esc_html__('Write URL', 'cws-essentials' ),
									),
									'link_type' => array(
										'type' => 'select',
										'addrowclasses' => 'grid-col-3 fai',
										'source' => array(
											'link' => array( esc_html__( 'Link', 'cws-essentials' ),  true, '' ),
											'mailto:' => array( esc_html__( 'Email', 'cws-essentials' ),  false, '' ),
											'skype:' => array( esc_html__( 'Skype', 'cws-essentials' ),  false, '' ),
											'tel:' => array( esc_html__( 'Phone', 'cws-essentials' ),  false, '' ),
										),
										'title' => esc_html__('Select link type', 'cws-essentials' )
									),
								),
							),
							'background_color' => array(
								'title' => esc_html__( 'Customize Background', 'cws-essentials' ),
								'atts' => 'data-default-color="#1b2e7d"',
								'value' => '#1b2e7d',
								'addrowclasses' => 'new_row grid-col-3',
								'type' => 'text',
							),
							'font_color' => array(
								'title' => esc_html__( 'Font Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#c5cfff"',
								'value' => '#c5cfff',
								'addrowclasses' => 'grid-col-3',
								'type' => 'text',
							),
							'hover_font_color' => array(
								'title' => esc_html__( 'Hover Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#feb556"',
								'value' => '#feb556',
								'addrowclasses' => 'grid-col-3',
								'type' => 'text',
							),							
							'background_opacity' => array(
								'type' => 'number',
								'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
								'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
								'value' => '100',
								'addrowclasses' => 'grid-col-3',
							),
							'border' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 box inside-box groups',
								'layout' => '%border_layout%',
							),
							'spacings' => array(
								'title' => esc_html__( 'Add Spacings (px)', 'cws-essentials' ),
								'type' => 'margins',
								'addrowclasses' => 'new_row grid-col-4 two-inputs',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '10'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '10'),
								),
							),	
						),
					),

				),
			),
			'tab9' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Footer', 'cws-essentials' ),
				'layout' => array(
					'MB_footer' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'atts' => 'data-options="e:footer;"',
						'addrowclasses' => 'checkbox alt box',
					),

					'footer' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'wide' => array(
								'title' => esc_html__( 'Apply Full-Width Footer', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12',
								'type' => 'checkbox',
							),
                            'logo_enable' => array(
                                'title' => esc_html__( 'Enable Footer Logo', 'cws-essentials' ),
                                'addrowclasses' => 'checkbox grid-col-12',
                                'type' => 'checkbox',
                                'atts' => 'checked data-options="e:logo;e:dimensions;"',
                            ),
                            'logo' => array(
                                'title' => esc_html__( 'Footer Logo', 'cws-essentials' ),
                                'type' => 'media',
                                'url-atts' => 'readonly',
                                'addrowclasses' => 'grid-col-6 disable',
                                'layout' => array(
                                    'is_high_dpi' => array(
                                        'title' => esc_html__( 'High-Resolution footer logo', 'cws-essentials' ),
                                        'addrowclasses' => 'checkbox',
                                        'type' => 'checkbox',
                                    ),
                                ),
                            ),
                            'dimensions' => array(
                                'title' => esc_html__( 'Dimensions', 'cws-essentials' ),
                                'type' => 'dimensions',
                                'addrowclasses' => 'grid-col-4 disable',
                                'value' => array(
                                    'width' => array('placeholder' => esc_attr__( 'Width', 'cws-essentials' ), 'value' => ''),
                                    'height' => array('placeholder' => esc_attr__( 'Height', 'cws-essentials' ), 'value' => ''),
                                ),
                            ),
                            'icon_enable' => array(
                                'title' => esc_html__( 'Enable Footer Icon', 'cws-essentials' ),
                                'addrowclasses' => 'checkbox grid-col-12',
                                'type' => 'checkbox',
                                'atts' => 'checked data-options="e:icon_color;e:icon_bg_color;"',
                            ),
                            'icon_color' => array(
                                'title' => esc_html__( 'Footer Icon Color', 'cws-essentials' ),
                                'atts' => 'data-default-color="#9d5f36"',
                                'value' => '#9d5f36',
                                'addrowclasses' => 'grid-col-6 disable',
                                'type' => 'text',
                            ),
                            'icon_bg_color' => array(
                                'title' => esc_html__( 'Footer Icon Background Color', 'cws-essentials' ),
                                'atts' => 'data-default-color="#ffe27a"',
                                'value' => '#ffe27a',
                                'addrowclasses' => 'grid-col-6 disable',
                                'type' => 'text',
                            ),
							'layout' => array(
								'type' => 'select',
								'title' => esc_html__( 'Select a layout', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-4',
								'source' => array(
									'1' => array( esc_html__( '1/1 Column', 'cws-essentials' ),  false ),
									'2' => array( esc_html__( '2/2 Column', 'cws-essentials' ), false ),
									'3' => array( esc_html__( '3/3 Column', 'cws-essentials' ), false ),
									'4' => array( esc_html__( '4/4 Column', 'cws-essentials' ), true ),
									'66-33' => array( esc_html__( '2/3 + 1/3 Column', 'cws-essentials' ), false ),
									'33-66' => array( esc_html__( '1/3 + 2/3 Column', 'cws-essentials' ), false ),
									'25-75' => array( esc_html__( '1/4 + 3/4 Column', 'cws-essentials' ), false ),
								),
							),
							'sidebar' => array(
								'title' 		=> esc_html__('Select Footer\'s Sidebar Area', 'cws-essentials' ),
								'type' 			=> 'select',
								'addrowclasses' => 'grid-col-4',
								'source' 		=> 'sidebars',
                                'value'         => 'footer',
							),
							'alignment' => array(
								'type' => 'select',
								'title' => esc_html__( 'Copyrights alignment', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-4',
								'source' => array(
									'left' => array( esc_html__( 'Left', 'cws-essentials' ),  true ),
									'center' => array( esc_html__( 'Center', 'cws-essentials' ), false ),
									'right' => array( esc_html__( 'Right', 'cws-essentials' ), false ),
								),
							),
							'copyrights_text' => array(
								'title' => esc_html__( 'Copyrights content', 'cws-essentials' ),
								'type' => 'textarea',
								'addrowclasses' => 'grid-col-12 full_row',
								'value' => esc_html__('Copyright © 2019. All Rights Reserved Metamax', 'cws-essentials'),
								'atts' => 'rows="6"',
							),
                            'footer_info_text' => array(
                                'title' => esc_html__( 'Footer info content', 'cws-essentials' ),
                                'type' => 'textarea',
                                'addrowclasses' => 'grid-col-12 full_row',
                                'value' => esc_html__("We’re on a mission to build a better future where technology creates good jobs for everyone.", "cws-essentials"),
                                'atts' => 'rows="6"',
                            ),
							'background_image' => array(
								'type' => 'fields',
								'addrowclasses' => 'box grid-col-12 inside-box groups',
								'layout' => '%image_layout%',
							),
							'title_color' => array(
								'title' => esc_html__( 'Widgets Title Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'value' => '#ffffff',
								'addrowclasses' => 'grid-col-4',
								'type' => 'text',
							),								
							'font_color' => array(
								'title' => esc_html__( 'Font Color', 'cws-essentials' ),
								'atts' => 'data-default-color="#bbd0ff"',
								'value' => '#bbd0ff',
								'addrowclasses' => 'grid-col-4',
								'type' => 'text',
							),
							'font_color_hover' => array(
								'title' => esc_html__( 'Font Color on Hover', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffe27a"',
								'value' => '#ffe27a',
								'addrowclasses' => 'grid-col-4',
								'type' => 'text',
							),
							'copyrights_background_color' => array(
								'title'	=> esc_html__( 'Background Color (Copyrights)', 'cws-essentials' ),
								'atts'	=> 'data-default-color="#0d2969"',
								'value' => '#0d2969',
								'addrowclasses' => 'grid-col-4',
								'type'	=> 'text'
							),
							'copyrights_font_color' => array(
								'title' => esc_html__( 'Font color (Copyrights)', 'cws-essentials' ),
								'atts' => 'data-default-color="#7a94cd"',
								'value' => '#7a94cd',
								'addrowclasses' => 'grid-col-4',
								'type' => 'text',
							),
							'copyrights_hover_color' => array(
								'title' => esc_html__( 'Hover color (Copyrights)', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffe27a"',
								'value' => '#ffe27a',
								'addrowclasses' => 'grid-col-4',
								'type' => 'text',
							),				
							'pattern_image' => array(
								'type' => 'fields',
								'title' => esc_html__( 'Pattern Image', 'cws-essentials' ),
								'addrowclasses' => 'box grid-col-12 inside-box groups',
								'layout' => '%image_layout%',
							),
							'overlay' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 box inside-box groups',
								'layout' => array(
									'type'	=> array(
										'title'		=> esc_html__( 'Color overlay', 'cws-essentials' ),
										'addrowclasses' => 'grid-col-4',
										'type'	=> 'select',
										'source'	=> array(
											'none' => array( esc_html__( 'None', 'cws-essentials' ),  true, 'd:opacity;d:color;d:gradient;' ),
											'color' => array( esc_html__( 'Color', 'cws-essentials' ),  false, 'e:opacity;e:color;d:gradient;' ),
											'gradient' => array( esc_html__('Gradient', 'cws-essentials' ), false, 'e:opacity;d:color;e:gradient;' )
										),
									),
									'color'	=> array(
										'title'	=> esc_html__( 'Color', 'cws-essentials' ),
										'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
										'addrowclasses' => 'grid-col-4',
										'value' => METAMAX_FIRST_COLOR,
										'type'	=> 'text',
										'customizer' => array( 'show' => true )
									),
									'opacity' => array(
										'type' => 'number',
										'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
										'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
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
								'addrowclasses' => 'grid-col-12 box inside-box groups',
								'layout' => '%border_layout%',
							),
                            'content_items' => array(
                                'type' => 'group',
                                'addrowclasses' => 'grid-col-12 group expander sortable box',
                                'title' => esc_html__('Top Bar Info', 'cws-essentials' ),
                                'button_title' => esc_html__('Add new info row', 'cws-essentials' ),
                                'layout' => array(
                                    'icon' => array(
                                        'type' => 'select',
                                        'addrowclasses' => 'grid-col-3 fai',
                                        'source' => 'fa',
                                        'title' => esc_html__('Select the icon', 'cws-essentials' )
                                    ),
                                    'title' => array(
                                        'type' => 'text',
                                        'atts' => 'data-role="title"',
                                        'addrowclasses' => 'grid-col-3',
                                        'title' => esc_html__('Write main info', 'cws-essentials' ),
                                    ),
                                    'url' => array(
                                        'type' => 'text',
                                        'addrowclasses' => 'grid-col-3',
                                        'title' => esc_html__('Write URL', 'cws-essentials' ),
                                    ),
                                    'link_type' => array(
                                        'type' => 'select',
                                        'addrowclasses' => 'grid-col-3 fai',
                                        'source' => array(
                                            'link' => array( esc_html__( 'Link', 'cws-essentials' ),  true, '' ),
                                            'mailto:' => array( esc_html__( 'Email', 'cws-essentials' ),  false, '' ),
                                            'skype:' => array( esc_html__( 'Skype', 'cws-essentials' ),  false, '' ),
                                            'tel:' => array( esc_html__( 'Phone', 'cws-essentials' ),  false, '' ),
                                        ),
                                        'title' => esc_html__('Select link type', 'cws-essentials' )
                                    ),
                                ),
                            ),
							'instagram_feed' => array(
								'title' => esc_html__( 'Add Instagram Feed', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-6',
								'type' => 'checkbox',
								'atts' => 'data-options="e:instagram_feed_shortcode;e:instagram_feed_full_width;"',
							),
							'instagram_feed_full_width' => array(
								'title' => esc_html__( 'Apply Full-Width Feed', 'cws-essentials' ),
								'addrowclasses' => 'disable checkbox grid-col-12',
								'type' => 'checkbox',
							),							
							'instagram_feed_shortcode' => array(
								'title' => esc_html__( 'Instagram Shortcode', 'cws-essentials' ),
								'addrowclasses' => 'disable grid-col-12 full_row',
								'type' => 'textarea',
								'atts' => 'rows="3"',
								'default' => '',
								'value' => '[instagram-feed cols=8 num=8 imagepadding=0 imagepaddingunit=px showheader=false showbutton=true showfollow=true]'
							),
							'spacings' => array(
								'title' => esc_html__( 'Add Spacings (px)', 'cws-essentials' ),
								'type' => 'margins',
								'addrowclasses' => 'new_row grid-col-12 two-inputs',
								'value' => array(
									'top' => array('placeholder' => esc_html__( 'Top', 'cws-essentials' ), 'value' => '70'),
									'bottom' => array('placeholder' => esc_html__( 'Bottom', 'cws-essentials' ), 'value' => '0'),
								),
							),	
						),
					),

				),
			),
			'tab10' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Layout', 'cws-essentials' ),
				'layout' => array(
					'MB_boxed' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'atts' => 'data-options="e:boxed;"'
					),

					'boxed' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'layout' => array(
								'type' => 'checkbox',
								'title' => esc_html__( 'Apply Page Boxed Layout', 'cws-essentials' ),
								'addrowclasses' => 'checkbox grid-col-12 box',
							),
							'background_image' => array(
								'type' => 'fields',
								'addrowclasses' => 'box inside-box groups grid-col-12',
								'layout' => '%image_layout%', 
							),
							'overlay' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 box inside-box groups',
								'layout' => array(
									'type'	=> array(
										'title'		=> esc_html__( 'Color overlay', 'cws-essentials' ),
										'addrowclasses' => 'grid-col-4',
										'type'	=> 'select',
										'source'	=> array(
											'none' => array( esc_html__( 'None', 'cws-essentials' ),  true, 'd:opacity;d:color;d:gradient;' ),
											'color' => array( esc_html__( 'Color', 'cws-essentials' ),  false, 'e:opacity;e:color;d:gradient;' ),
											'gradient' => array( esc_html__('Gradient', 'cws-essentials' ), false, 'e:opacity;d:color;e:gradient;' )
										),
									),
									'color'	=> array(
										'title'	=> esc_html__( 'Color', 'cws-essentials' ),
										'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
										'addrowclasses' => 'grid-col-4',
										'value' => METAMAX_FIRST_COLOR,
										'type'	=> 'text',
									),
									'opacity' => array(
										'type' => 'number',
										'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
										'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
										'value' => '40',
										'addrowclasses' => 'grid-col-4',
									),
									'gradient' => array(
										'title' => esc_html__( 'Gradient Settings', 'cws-essentials' ),
										'type' => 'fields',
										'addrowclasses' => 'grid-col-12 disable box inside-box groups',
										'layout' => '%gradient_layout%',
									),
								),
							),
						),
					),

				),
			),
			'tab11' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Sidebar', 'cws-essentials' ),
				'layout' => array(
					'MB_side_panel' => array(
						'title' => esc_html__( 'Customize', 'cws-essentials' ),
						'addrowclasses' => 'checkbox alt box',
						'atts' => 'data-options="e:side_panel;"',
						'type' => 'checkbox',
					),

					'side_panel' => array(
						'type' => 'fields',
						'addrowclasses' => 'disable inside-box groups grid-col-12 box',
						'layout' => array(
							'enable' => array(
								'title' => esc_html__( 'Side Panel', 'cws-essentials' ),
								'addrowclasses' => 'alt checkbox grid-col-12',
								'type' => 'checkbox',
							),	
							'place' => array(
								'title' => esc_html__( 'Menu Icon Location', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'addrowclasses' => 'grid-col-6',
								'value' => array(
									'topbar_left' =>array( esc_html__( 'TopBar (Left)', 'cws-essentials' ), false, '', '/img/top-hamb-left.png' ),
									'topbar_right' => array( esc_html__( 'TopBar (Right)', 'cws-essentials' ), 	false, '', '/img/top-hamb-right.png' ),
									'menu_left' =>array( esc_html__( 'Menu (Left)', 'cws-essentials' ), true, '', '/img/hamb-left.png' ),
									'menu_right' =>array( esc_html__( 'Menu (Right)', 'cws-essentials' ), false, '', '/img/hamb-right.png' ),
								),
							),
							'position' => array(
								'title' 			=> esc_html__('Side Panel Position', 'cws-essentials' ),
								'type' 				=> 'radio',
								'subtype' 			=> 'images',
								'addrowclasses' => 'grid-col-6',
								'value' 			=> array(
									'left' 				=> 	array( esc_html__('Left', 'cws-essentials' ), true, '',	'/img/left.png' ),
									'right' 			=> 	array( esc_html__('Right', 'cws-essentials' ), false, '', '/img/right.png' ),
								),
							),
							'sidebar' => array(
								'title' 		=> esc_html__('Select the Sidebar Area', 'cws-essentials' ),
								'type' 			=> 'select',
								'addrowclasses' => 'new_row grid-col-6',
								'source' 		=> 'sidebars',
								'value'         => 'side_panel',
							),
							'appear'	=> array(
								'title'		=> esc_html__( 'Animation Format', 'cws-essentials' ),
								'type'	=> 'select',
								'addrowclasses' => 'grid-col-6',
								'source'	=> array(
									'fade' => array( esc_html__( 'Fade & Slide', 'cws-essentials' ),  true ),
									'slide' => array( esc_html__( 'Slide', 'cws-essentials' ), false ),
									'pull' => array( esc_html__( 'Pull', 'cws-essentials' ), false ),
								),
							),
							'logo' => array(
								'title' => esc_html__( 'Logo', 'cws-essentials' ),
								'type' => 'media',
								'url-atts' => 'readonly',
								'addrowclasses' => 'grid-col-6',
								'layout' => array(
									'is_high_dpi' => array(
										'title' => esc_html__( 'High-Resolution logo', 'cws-essentials' ),
										'addrowclasses' => 'checkbox',
										'type' => 'checkbox',
									),
								),
							),
							'logo_position' => array(
								'title' => esc_html__( 'Logo position', 'cws-essentials' ),
								'addrowclasses' => 'grid-col-3',
								'type' => 'radio',
								'value' => array(
									'left' => array( esc_html__( 'Left', 'cws-essentials' ),  true, '' ),
									'center' =>array( esc_html__( 'Center', 'cws-essentials' ), false,  '' ),
									'right' =>array( esc_html__( 'Right', 'cws-essentials' ), false,  '' ),
								),
							),
							'logo_dimensions' => array(
								'title' => esc_html__( 'Logo Dimensions', 'cws-essentials' ),
								'type' => 'dimensions',
								'addrowclasses' => 'grid-col-3',
								'value' => array(
									'width' => array('placeholder' => esc_html__( 'Width', 'cws-essentials' ), 'value' => ''),
									'height' => array('placeholder' => esc_html__( 'Height', 'cws-essentials' ), 'value' => ''),
								),
							),							
							'background_image' => array(
								'title' => esc_html__( 'Background image', 'cws-essentials' ),
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 inside-box groups',
								'layout' => '%image_layout%',
							),
							'overlay' => array(
								'type' => 'fields',
								'addrowclasses' => 'grid-col-12 inside-box groups',
								'layout' => array(
									'type'	=> array(
										'title'		=> esc_html__( 'Add Color overlay', 'cws-essentials' ),
										'addrowclasses' => 'grid-col-4',
										'type'	=> 'select',
										'source'	=> array(
											'none' => array( esc_html__( 'None', 'cws-essentials' ),  false, 'd:opacity;d:color;d:gradient;' ),
											'color' => array( esc_html__( 'Color', 'cws-essentials' ),  true, 'e:opacity;e:color;d:gradient;' ),
											'gradient' => array( esc_html__('Gradient', 'cws-essentials' ), false, 'e:opacity;d:color;e:gradient;' )
										),
									),
									'color'	=> array(
										'title'	=> esc_html__( 'Color', 'cws-essentials' ),
										'atts' => 'data-default-color="#1b2e7d"',
										'addrowclasses' => 'grid-col-4',
										'value' => '#1b2e7d',
										'type'	=> 'text',
									),
									'opacity' => array(
										'type' => 'number',
										'title' => esc_html__( 'Opacity (%)', 'cws-essentials' ),
										'placeholder' => esc_html__( 'In percents', 'cws-essentials' ),
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
								'title'	=> esc_html__( 'Font color', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'addrowclasses' => 'grid-col-4',
								'value' => '#ffffff',
								'type'	=> 'text',
							),
							'font_color_hover'	=> array(
								'title'	=> esc_html__( 'Font color on Hover', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffe27a"',
								'addrowclasses' => 'grid-col-4',
								'value' => '#ffe27a',
								'type'	=> 'text',
							),
							'fixed_bg' => array(
								'title'	=> esc_html__( 'Fixed info Background', 'cws-essentials' ),
								'atts' => 'data-default-color="#ffffff"',
								'addrowclasses' => 'grid-col-4',
								'value' => '#ffffff',
								'type'	=> 'text',
							),
							'add_social' => array(
								'title' => esc_html__( 'Add social icon to Fixed Info Bar', 'cws-essentials' ),
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
										'title' => esc_html__('Fixed Information', 'cws-essentials' ),
										'button_title' => esc_html__('Add new information row', 'cws-essentials' ),
										'button_icon' => 'fas fa-plus',
										'layout' => array(
											'title' => array(
												'type' => 'text',
												'atts' => 'data-role="title"',
												'addrowclasses' => 'grid-col-3',
												'title' => esc_html__('Title', 'cws-essentials' ),
											),
											'url' => array(
												'type' => 'text',
												'addrowclasses' => 'grid-col-3',
												'title' => esc_html__('Link', 'cws-essentials' ),
											),
											'icon' => array(
												'type' => 'select',
												'addrowclasses' => 'fai grid-col-3',
												'source' => 'fa',
												'title' => esc_html__('Icon', 'cws-essentials' )
											),
											'link_type' => array(
												'type' => 'select',
												'addrowclasses' => 'grid-col-3',
												'source' => array(
													'link' => array( esc_html__( 'Link', 'cws-essentials' ),  true, '' ),
													'mailto:' => array( esc_html__( 'Email', 'cws-essentials' ),  false, '' ),
													'skype:' => array( esc_html__( 'Skype', 'cws-essentials' ),  false, '' ),
													'tel:' => array( esc_html__( 'Phone', 'cws-essentials' ),  false, '' ),
												),
												'title' => esc_html__('Select link type', 'cws-essentials' )
											),
										)
									),																
								),
							),
						),
					),

				)
			),
		);		

		$this->mb_staff_layout = array(
			'experience' => array(
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Experience', 'cws-essentials' ),			
			),			
			'email' => array(
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Email', 'cws-essentials' ),			
			),			
			'tel' => array(
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Tel', 'cws-essentials' ),			
			),			
			'biography' => array(
				'atts' 			=> 'rows="5"',
				'type' 			=> 'textarea',
				'title' 		=> esc_html__( 'Biography', 'cws-essentials' ),			
			),
			'social_group' => array(
				'type' => 'group',
				'addrowclasses' => 'group expander sortable box',
				'title' => esc_html__('Social networks', 'cws-essentials' ),
				'button_title' => esc_html__('Add new social network', 'cws-essentials' ),
				'layout' => array(
					'title' => array(
						'type' => 'text',
						'atts' => 'data-role="title"',
						'title' => esc_html__('Social account title', 'cws-essentials' ),
					),
					'icon' => array(
						'type' => 'select',
						'addrowclasses' => 'fai',
						'source' => 'fa',
						'title' => esc_html__('Select the icon for this social contact', 'cws-essentials' )
					),
					'url' => array(
						'type' => 'text',
						'title' => esc_html__('Url to your account', 'cws-essentials' ),
					),
				),
			),
		);

		$this->mb_portfolio_layout = array(
			'tab0' => array(
				'type' => 'tab',
				'init' => 'open grid-col-12',
				'title' => esc_html__( 'General', 'cws-essentials' ),
				'layout' => array(
					'portfolio_sidebars' => array(
						'title' => esc_html__( 'Page Sidebars Settings', 'cws-essentials' ),
						'type' => 'fields',
						'addrowclasses' => 'box inside-box groups',
						'layout' => array(
							'layout' => array(
								'title' => esc_html__('Sidebar Position', 'cws-essentials' ),
								'type' => 'radio',
								'subtype' => 'images',
								'value' => array(
									'{page_sidebars}'=>	array( esc_html__('Default', 'cws-essentials' ), true, 'd:def--sidebar1;d:def--sidebar2', '/img/default.png' ),
									'left' => 	array( esc_html__('Left', 'cws-essentials' ), false, 'e:sb1;d:sb2',	'/img/left.png' ),
									'right' => 	array( esc_html__('Right', 'cws-essentials' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
									'both' => 	array( esc_html__('Double', 'cws-essentials' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
									'none' => 	array( esc_html__('None', 'cws-essentials' ), false, 'd:sb1;d:sb2', '/img/none.png' )
								),
							),
							'sb1' => array(
								'title' => esc_html__('Select a sidebar', 'cws-essentials' ),
								'type' => 'select',
								'addrowclasses' => 'disable box',
								'source' => 'sidebars',
							),
							'sb2' => array(
								'title' => esc_html__('Select right sidebar', 'cws-essentials' ),
								'type' => 'select',
								'addrowclasses' => 'disable box',
								'source' => 'sidebars',
							),
						),
					),
					'full_width' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Full Width', 'cws-essentials' ),
						'atts' => 'data-options="d:decr_pos;"',
					),
					'decr_pos' => array(
						'type' => 'select',
						'title' => esc_html__( 'Project Description', 'cws-essentials' ),
						'source' => array(
							'bot' => array(esc_html__( 'Bottom', 'cws-essentials' ), true, 'd:cont_width;'),
							'left' => array(esc_html__( 'Left', 'cws-essentials' ), false, 'e:cont_width;'),
							'left_s' => array(esc_html__( 'Left + Sticky', 'cws-essentials' ), false, 'e:cont_width;'),
							'right' => array(esc_html__( 'Right', 'cws-essentials' ), false, 'e:cont_width;'),
							'right_s' => array(esc_html__( 'Right + Sticky', 'cws-essentials' ), false, 'e:cont_width;'),
						),
					),
					'cont_width' => array(
						'type' => 'select',
						'title' => esc_html__( 'Content Width', 'cws-essentials' ),
						'source' => array(
							'25' => array(esc_html__( '1/4', 'cws-essentials' ), false),
							'33' => array(esc_html__( '1/3', 'cws-essentials' ), true),
							'50' => array(esc_html__( '1/2', 'cws-essentials' ), false),
							'66' => array(esc_html__( '2/3', 'cws-essentials' ), false),
						),
					),
					'p_type' => array(
						'type' => 'select',
						'title' => esc_html__( 'Portfolio Single\'s Format', 'cws-essentials' ),
						'source' => array(
							'image' => array(esc_html__( 'Featured Image', 'cws-essentials' ), true, 'd:gall_type;d:video_type;d:slider_type;d:rev_slider_type;'),
							'gallery' => array(esc_html__( 'Gallery', 'cws-essentials' ), false, 'e:gall_type;d:video_type;d:slider_type;d:rev_slider_type;'),
							'slider' => array(esc_html__( 'Slider', 'cws-essentials' ), false, 'd:gall_type;d:video_type;e:slider_type;d:rev_slider_type;'),
							'rev_slider' => array(esc_html__( 'External Slider', 'cws-essentials' ), false, 'd:gall_type;d:video_type;d:slider_type;e:rev_slider_type;'),
							'video' => array(esc_html__( 'Video', 'cws-essentials' ), false, 'd:gall_type;e:video_type;d:slider_type;d:rev_slider_type;'),
							'none' => array(esc_html__( 'None', 'cws-essentials' ), false, 'd:gall_type;d:video_type;d:slider_type;d:rev_slider_type;'),
						),
					),
					'gall_type' => array(
						'type' => 'fields',
						'addrowclasses' => 'box inside-box groups',
						'layout' => array(
							'gall' => array(
								'title' => esc_html__( 'Add Media', 'cws-essentials' ),
								'type' => 'gallery'
							),
						),
					),
					'slider_type' => array(
						'type' => 'fields',
						'addrowclasses' => 'box inside-box groups',
						'layout' => array(
							'slider_gall' => array(
								'title' => esc_html__( 'Add Media', 'cws-essentials' ),
								'type' => 'gallery',
								'addrowclasses' => 'grid-col-3',
							),
						),
					),
					'rev_slider_type' => array(
						'type' => 'fields',
						'addrowclasses' => 'box inside-box groups',
						'layout' => array(
							'rev_url' => array(
								'title' => esc_html__( 'Add Shortcode', 'cws-essentials' ),
								'type' => 'text',
							),
						)
					),
					'video_type' => array(
						'type' => 'fields',
						'addrowclasses' => 'box inside-box groups',
						'layout' => array(
							'video_t' => array(
								'type' => 'select',
								'source' => array(
									'youtube' => array(esc_html__( 'YouTube', 'cws-essentials' ), true, 'e:youtube_t;d:vimeo_t;d:other_t;'),
									'vimeo' => array(esc_html__( 'Vimeo', 'cws-essentials' ), false, 'd:youtube_t;e:vimeo_t;d:other_t;'),
									'other' => array(esc_html__( 'Other', 'cws-essentials' ), false, 'd:youtube_t;d:vimeo_t;e:other_t;'),
								),
							),
							'youtube_t' => array(
								'type' => 'fields',
								'addrowclasses' => 'box inside-box groups grid-col-12',
								'layout' => array(
									'url' => array(
										'title' => esc_html__( 'Video ID', 'cws-essentials' ),
										'type' => 'text',
									),
								),
							),
							'vimeo_t' => array(
								'type' => 'fields',
								'addrowclasses' => 'box inside-box groups grid-col-12',
								'layout' => array(
									'url' => array(
										'title' => esc_html__( 'Video ID', 'cws-essentials' ),
										'type' => 'text',
									),
								),
							),
							'other_t' => array(
								'type' => 'fields',
								'addrowclasses' => 'box inside-box groups grid-col-12',
								'layout' => array(
									'url' => array(
										'title' => esc_html__( 'Video URL', 'cws-essentials' ),
										'type' => 'text',
									),
								),
							),
						)
					),
				),
			),
			'tab1' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Isotope Layout', 'cws-essentials' ),
				'layout' => array(
					'isotope_col_count' => array(
						'type' => 'select',
						'title' => esc_html__( 'Columns', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
						'source' => array(
							'1' => array(esc_html__( 'One', 'cws-essentials' ), true),
							'2' => array(esc_html__( 'Two', 'cws-essentials' ), false),
							'3' => array(esc_html__( 'Three', 'cws-essentials' ), false),
							'4' => array(esc_html__( 'Four', 'cws-essentials' ), false),
						),
					),
					'isotope_line_count' => array(
						'type' => 'select',
						'title' => esc_html__( 'Lines', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
						'source' => array(
							'1' => array(esc_html__( 'One', 'cws-essentials' ), true),
							'2' => array(esc_html__( 'Two', 'cws-essentials' ), false),
							'3' => array(esc_html__( 'Three', 'cws-essentials' ), false),
							'4' => array(esc_html__( 'Four', 'cws-essentials' ), false),
						),
					),
					'desc' => array(
						'type' => 'lable',
						'addrowclasses' => 'grid-col-12',
						'title' => esc_html__( 'This option is used in the Isotope Portfolio Layout only. The image will take the selected number of Columns/Lines and will be displayed accordingly.', 'cws-essentials' ),
					),
				),
			),
			'tab2' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Related Items', 'cws-essentials' ),
				'layout' => array(
					'carousel' => array(
						'title' => esc_html__( 'Display items carousel for this portfolio post', 'cws-essentials' ),
						'type' => 'checkbox',
						'atts' => 'checked',
						'addrowclasses' => 'checkbox grid-col-12',
					),
					'show_related' => array(
						'title' => esc_html__( 'Show related Items', 'cws-essentials' ),
						'type' => 'checkbox',
						'atts' => 'checked data-options="e:related_projects_options;e:rpo_title;e:rpo_cols;e:rpo_items_count;e:rpo_categories;"',
						'addrowclasses' => 'alt checkbox grid-col-12',
					),
					'rpo_title' => array(
						'type' => 'text',
						'title' => esc_html__( 'Title', 'cws-essentials' ),
						'value' => esc_html__( 'Related projects', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
					),
					'rpo_categories' => array(
						'title' => esc_html__( 'Categories', 'cws-essentials' ),
						'type' => 'taxonomy',
						'atts' => 'multiple',
						'addrowclasses' => 'grid-col-12',
						'taxonomy' => 'cws_portfolio_cat',
						'source' => array(),
					),
					'rpo_cols' => array(
						'type' => 'select',
						'title' => esc_html__( 'Columns', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
						'source' => array(
							'1' => array(esc_html__( 'One', 'cws-essentials' ), false),
							'2' => array(esc_html__( 'Two', 'cws-essentials' ), false),
							'3' => array(esc_html__( 'Three', 'cws-essentials' ), true),
							'4' => array(esc_html__( 'Four', 'cws-essentials' ), false),
							),
					),
					'rpo_items_count' => array(
						'type' => 'number',
						'title' => esc_html__( 'Number of Related Items', 'cws-essentials' ),
						'value' => '3',
						'addrowclasses' => 'grid-col-12',
					),
				),

			),
			'tab3' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Hover', 'cws-essentials' ),
				'layout' => array(
					'enable_hover' => array(
						'title' => esc_html__( 'Enable Hover', 'cws-essentials' ),
						'type' => 'checkbox',
						'atts' => 'checked data-options="e:link_options;e:link_options_single;e:link_options_fancybox"',
						'addrowclasses' => 'alt checkbox grid-col-12',
					),
					'link_options_fancybox' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Open in a popup', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
						'atts' => 'checked'
					),
					'link_options_single' => array(
						'type' => 'checkbox',
						'title' => esc_html__( 'Single Link', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
						'atts' => 'checked data-options="e:link_options_url;"',
					),
					'link_options_url' => array(
						'type' => 'text',
						'title' => esc_html__( 'Add Custom URL', 'cws-essentials' ),
						'addrowclasses' => 'grid-col-12',
						'default' => ''
					),
				),
			),	
		);

		$this->mb_classes_layout = array(
			'post_sidebars' => array(
				'title' => esc_html__( 'Page Sidebars Settings', 'cws-essentials' ),
				'type' => 'fields',
				'addrowclasses' => 'box inside-box groups',
				'layout' => array(
					'layout' => array(
						'title' => esc_html__('Sidebar Position', 'cws-essentials' ),
						'type' => 'radio',
						'subtype' => 'images',
						'value' => array(
							'{page_sidebars}'=>	array( esc_html__('Default', 'cws-essentials' ), true, 'd:def--sidebar1;d:def--sidebar2', '/img/default.png' ),
							'left' => 	array( esc_html__('Left', 'cws-essentials' ), false, 'e:sb1;d:sb2',	'/img/left.png' ),
							'right' => 	array( esc_html__('Right', 'cws-essentials' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
							'both' => 	array( esc_html__('Double', 'cws-essentials' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
							'none' => 	array( esc_html__('None', 'cws-essentials' ), false, 'd:sb1;d:sb2', '/img/none.png' )
						),
					),
					'sb1' => array(
						'title' => esc_html__('Select a sidebar', 'cws-essentials' ),
						'type' => 'select',
						'addrowclasses' => 'disable box',
						'source' => 'sidebars',
					),
					'sb2' => array(
						'title' => esc_html__('Select right sidebar', 'cws-essentials' ),
						'type' => 'select',
						'addrowclasses' => 'disable box',
						'source' => 'sidebars',
					),
				),
			),
			'link_to' 		=> array(
				'type' 		=> 'select',
				'title' 	=> esc_html__( 'Link Options', 'cws-essentials' ),
				'atts' => 'data-options="select:options"',
				'source' 	=> array(
					'none' 			=> array(esc_html__( 'None', 'cws-essentials' ), false, 'd:link_custom_url;'),
					'post' 			=> array(esc_html__( 'Post', 'cws-essentials' ), false, 'd:link_custom_url;'),
					'custom_url' 	=> array(esc_html__( 'Custom Url', 'cws-essentials' ), false, 'e:link_custom_url;' ),
				)
			),
			'link_custom_url' => array(
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Custom url', 'cws-essentials' ),
				'addrowclasses' => 'disable',				
			),	
			'add_btn' => array(
				'type' => 'checkbox',
				'title' => esc_html__('Add Button Link', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-options="e:title_btn;"',
			),
			'title_btn' => array(
				'type' 			=> 'text',
				'title' 		=> esc_html__( 'Title Link Button', 'cws-essentials' ),
				'addrowclasses' => 'disable',
			),
			'our_staff' => array(
				'title' => esc_html__( 'Teacher', 'cws-essentials' ),
				'type' => 'post_type',
				'atts' => 'multiple',
				'post_type' => 'cws_staff',
				'source' => array(),
			),
			'show_staff' => array(
				'type' => 'checkbox',
				'title' => esc_html__('Show Details Teacher Page', 'cws-essentials' ),
				'atts' => 'checked',
			),
			'price' => array(
				'type' => 'text',
				'addrowclasses' => 'box',
				'title' => esc_html__( 'Price', 'cws-essentials' ),
				),
			'date_events' => array(
				'type' => 'text',
				'addrowclasses' => 'box',
				'title' => esc_html__( 'Date Events', 'cws-essentials' ),
				),			
			'time_events' => array(
				'type' => 'text',
				'title' => esc_html__( 'Time Events', 'cws-essentials' ),
				),
			'destinations' => array(
				'type' => 'text',
				'addrowclasses' => 'box',
				'title' => esc_html__( 'Destination Events', 'cws-essentials' ),
				),
			'show_featured' => array(
				'title' => esc_html__( 'Show featured image on single', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'checked data-options="e:wide_featured;"',
			),
			'wide_featured' => array(
				'title' => esc_html__( 'Wide featured image', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'disable checkbox box',
			),
			'carousel' => array(
				'title' => esc_html__( 'Display items carousel for this post', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'checked',
			),
			'show_related' => array(
				'title' => esc_html__( 'Show related items', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'checked data-options="e:rpo_title;e:rpo_cols;e:rpo_items_count"',
			),
			'rpo_title' => array(
				'type' => 'text',
				'addrowclasses' => 'box',
				'title' => esc_html__( 'Title', 'cws-essentials' ),
				'value' => esc_html__( 'Related items', 'cws-essentials' )
				),
			'rpo_cols' => array(
				'type' => 'select',
				'title' => esc_html__( 'Columns', 'cws-essentials' ),
				'addrowclasses' => 'box',
				'source' => array(
					'1' => array(esc_html__( 'one', 'cws-essentials' ), false),
					'2' => array(esc_html__( 'two', 'cws-essentials' ), false),
					'3' => array(esc_html__( 'three', 'cws-essentials' ), true),
					'4' => array(esc_html__( 'four', 'cws-essentials' ), false),
					),
				),
			'rpo_categories' => array(
				'title' => esc_html__( 'Categories', 'cws-essentials' ),
				'type' => 'taxonomy',
				'atts' => 'multiple',
				'taxonomy' => 'cws_classes_cat',
				'source' => array(),
			),
			'rpo_items_count' => array(
				'type' => 'number',
				'title' => esc_html__( 'Number of items to show', 'cws-essentials' ),
				'addrowclasses' => 'box',
				'value' => '3'
			),
			'work_days_group' => array(
				'type' => 'group',
				'addrowclasses' => 'group sortable',
				'title' => esc_html__('Working Days', 'cws-essentials' ),
				'button_title' => esc_html__('Add New Working Days', 'cws-essentials' ),
				'button_icon' => 'fas fa-plus',
				'layout' => array(
					'title' => array(
						'type' => 'text',
						'atts' => 'data-role="title"',
						'title' => esc_html__('Working Days', 'cws-essentials' ),
						'value' => "Monday"
					),
					'from' => array(
						'type' => 'text',
						'title' => esc_html__( 'From', 'cws-essentials' ),
						'value' => ""
					),	
					'to' => array(
						'type' => 'text',
						'title' => esc_html__( 'To', 'cws-essentials' ),
						'value' => ""
					),		
				)
			),
		);

		if ( is_customize_preview() && $a) { 
			switch (get_post_type($a)) {
				case 'cws_portfolio':
				$this->mb_page_layout = $this->mb_portfolio_layout;
				break;				
				case 'cws_staff':
				$this->mb_page_layout = $this->mb_staff_layout;
				break;				
				case 'cws_classes':
				$this->mb_page_layout = $this->mb_classes_layout;
				break;
			}
		}

		self::$instance = $this;
		$this->init();
	}

	public static function get_instance() {
		return self::$instance;
	}

	private function init() {
		add_action( 'add_meta_boxes', array($this, 'post_addmb') );
		add_action( 'add_meta_boxes_cws_testimonials', array($this, 'testimonials_addmb') );
		add_action( 'add_meta_boxes_cws_portfolio', array($this, 'portfolio_addmb') );
		add_action( 'add_meta_boxes_cws_classes', array($this, 'classes_addmb') );
		add_action( 'add_meta_boxes_cws_staff', array($this, 'staff_addmb') );
		add_action( 'add_meta_boxes_megamenu_item', array($this, 'mgmenu_addmb') );

		add_action( 'save_post', array($this, 'cws_post_metabox_save'), 11, 2 );
	}

	public function testimonials_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Testimonials Options', array($this, 'mb_testimonials_callback'), 'cws_testimonials', 'normal', 'high' );
	}	

	public function classes_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Classes Options', array($this, 'mb_classes_callback'), 'cws_classes', 'normal', 'high' );
	}	

	public function mgmenu_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Megamenu Options', array($this, 'mb_mgmenu_callback'), 'megamenu_item', 'normal', 'high' );
	}

	public function portfolio_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Portfolio Options', array($this, 'mb_portfolio_callback'), 'cws_portfolio', 'normal', 'high' );
	}

	public function staff_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Staff Options', array($this, 'mb_staff_callback'), 'cws_staff', 'normal', 'high' );
	}

	public function post_addmb() {
		add_meta_box( 'cws-post-metabox-id-1', 'CWS Post Options', array($this, 'mb_post_callback'), 'post', 'normal', 'high' );
		add_meta_box( 'cws-post-metabox-id-2', 'Header Image', array($this, 'mb_post_side_callback'), 'post', 'side', 'low' );
		add_meta_box( 'cws-post-metabox-id-3', 'CWS Page Options', array($this, 'mb_page_callback'), 'page', 'normal', 'high' );
	}

	public function mb_staff_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = $this->mb_staff_layout;

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function mb_mgmenu_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = array(
			'fw_mgmenu' => array(
				'type' => 'checkbox',
				'title' => esc_html__('Disable Full Width Megamenu', 'cws-essentials' ),
				'atts' => 'data-options="e:mgmenu_width;e:mgmenu_pos"',
				'addrowclasses' => 'grid-col-12',
			),
			'mgmenu_width' => array(
				'title' 		=> esc_html__( 'Set Fixed Width (in px)', 'cws-essentials' ),
				'type' 			=> 'text',
				'addrowclasses' => 'disable grid-col-12',
				'value'			=> '1170',
			),
			'mgmenu_pos' => array(
				'title' 		=> esc_html__( 'Dropdown Position', 'cws-essentials' ),
				'type' 			=> 'select',
				'addrowclasses' => 'disable grid-col-12',
				'source' => array(
					'center' => array(esc_html__( 'Center', 'cws-essentials' ), true),
					'left' => array(esc_html__( 'Left', 'cws-essentials' ), false),
					'right' => array(esc_html__( 'Right', 'cws-essentials' ), false),
				),
			),
		);

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function mb_page_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = $this->mb_page_layout;

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function mb_testimonials_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = array(
			'carousel' => array(
				'title' => esc_html__( 'Display controls', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'checked',
			),
		);

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function mb_portfolio_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = $this->mb_portfolio_layout;

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function mb_classes_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = $this->mb_classes_layout;

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function mb_post_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr_all = array(
			'gallery' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Gallery', 'cws-essentials' ),
				'layout' => array(
					'gallery_type' => array(
						'type' => 'select',
						'title' => esc_html__( 'Gallery type', 'cws-essentials' ),
						'source' => array(
							'slider' => array(esc_html__( 'Slider', 'cws-essentials' ), true, 'd:grid_cols;d:custom_grid;'),
							'grid' => array(esc_html__( 'Grid', 'cws-essentials' ), false, 'e:grid_cols;d:custom_grid;'),
							'new_grid' => array(esc_html__( 'Custom Grid', 'cws-essentials' ), false, 'd:grid_cols;e:custom_grid;'),
						),
					),
					'grid_cols' => array(
						'type' => 'select',
						'title' => esc_html__( 'Grid columns', 'cws-essentials' ),
						'addrowclasses' => 'disable box',
						'source' => array(
							'1' => array(esc_html__( 'one', 'cws-essentials' ), false),
							'2' => array(esc_html__( 'two', 'cws-essentials' ), false),
							'3' => array(esc_html__( 'three', 'cws-essentials' ), false),
							'4' => array(esc_html__( 'four', 'cws-essentials' ), true),
						),
					),
					'custom_grid' => array(
						'title' => esc_html__('Choose Your Custom Grid', 'cws-essentials' ),
						'type' => 'radio',
						'addrowclasses' => 'disable box',
						'subtype' => 'images',
						'value' => array(
							'var_1' => array( '', true, '', '/img/1st.png' ),
							'var_2' => array( '', false, '', '/img/2nd.png' ),
							'var_3' => array( '', false, '', '/img/3rd.png' ),
							'var_4' => array( '', false, '', '/img/4th.png' ),
							'var_5' => array( '', false, '', '/img/5th.png' ),
							'var_6' => array( '', false, '', '/img/6th.png' ),
							'var_7' => array( '', false, '', '/img/7th.png' ),
						),
					),
					'gallery' => array(
						'type' => 'gallery'
					),
				)
			),
			'video' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Video', 'cws-essentials' ),
				'layout' => array(
					'video' => array(
						'title' => esc_html__( 'Direct URL path of a video file', 'cws-essentials' ),
						'type' => 'text'
					)
				)
			),
			'audio' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Audio', 'cws-essentials' ),
				'layout' => array(
					'audio' => array(
						'title' => esc_html__( 'A self-hosted or SoundClod audio file URL', 'cws-essentials' ),
						'subtitle' => esc_html__( 'Ex.: /wp-content/uploads/audio.mp3 or http://soundcloud.com/...', 'cws-essentials' ),
						'type' => 'text'
					)
				)
			),
			'link' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Url', 'cws-essentials' ),
				'layout' => array(
					'link' => array(
						'title' => esc_html__( 'URL', 'cws-essentials' ),
						'type' => 'text'
					),
					'link_title' => array(
						'title' => esc_html__( 'Title', 'cws-essentials' ),
						'type' => 'text'
					)
				)
			),
			'quote' => array(
				'type' => 'tab',
				'init' => 'closed',
				'title' => esc_html__( 'Quote', 'cws-essentials' ),
				'layout' => array(
					'quote_text' => array(
						'subtitle' => esc_html__( 'Enter the quote', 'cws-essentials' ),
						'atts' => 'rows="5"',
						'type' => 'textarea'
					),
					'quote_author' => array(
						'title' => esc_html__( 'Author', 'cws-essentials' ),
						'type' => 'text'
					),
				)
			),
			'post_sidebars' => array(
				'title' => esc_html__( 'Sidebars Settings', 'cws-essentials' ),
				'type' => 'fields',
				'addrowclasses' => 'box inside-box groups grid-col-12',
				'layout' => array(
					'layout' => array(
						'title' => esc_html__('Sidebar Position', 'cws-essentials' ),
						'type' => 'radio',
						'subtype' => 'images',
						'addrowclasses' => 'grid-col-12',
						'value' => array(
							'{blog_sidebars}'=>	array( esc_html__('Default', 'cws-essentials' ), true, 'd:sb1;d:sb2', '/img/default.png' ),
							'left' => 	array( esc_html__('Left', 'cws-essentials' ), false, 'e:sb1;d:sb2',	'/img/left.png' ),
							'right' => 	array( esc_html__('Right', 'cws-essentials' ), false, 'e:sb1;d:sb2', '/img/right.png' ),
							'both' => 	array( esc_html__('Double', 'cws-essentials' ), false, 'e:sb1;e:sb2', '/img/both.png' ),
							'none' => 	array( esc_html__('None', 'cws-essentials' ), false, 'd:sb1;d:sb2', '/img/none.png' )
						),
					),
					'sb1' => array(
						'title' => esc_html__('Select a sidebar', 'cws-essentials' ),
						'type' => 'select',
						'addrowclasses' => 'disable box grid-col-6',
						'source' => 'sidebars',
					),
					'sb2' => array(
						'title' => esc_html__('Select right sidebar', 'cws-essentials' ),
						'type' => 'select',
						'addrowclasses' => 'disable box grid-col-6',
						'source' => 'sidebars',
					),
				),
			),	
			'show_featured' => array(
				'title' => esc_html__( 'Show featured image on single post', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'checked data-options="e:full_width_featured;"',
			),
			'full_width_featured' => array(
				'type' => 'checkbox',
				'title' => esc_html__( 'Full-Width Featured Image', 'cws-essentials' ),
				'addrowclasses' => 'disable checkbox grid-col-12',
			),
			'show_related' => array(
				'title' => esc_html__( 'Show related items', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'checked data-options="e:rpo"',
			),
			'rpo' => array(
				'title' => esc_html__( 'Related items settings', 'cws-essentials' ),
				'type' => 'fields',
				'addrowclasses' => 'disable groups',
				'layout' => array(
					'title' => array(
						'type' => 'text',
						'addrowclasses' => 'box grid-col-12',
						'title' => esc_html__( 'Title', 'cws-essentials' ),
						'value' => esc_html__( 'Related items', 'cws-essentials' ),
					),
					'category' => array(
						'title' => esc_html__( 'Categories (Filter)', 'cws-essentials' ),
						'type' => 'taxonomy',
						'atts' => 'multiple',
						'taxonomy' => 'category',
						'addrowclasses' => 'box grid-col-12',
						'source' => array(),
					),
					'text_length' => array(
						'type' => 'number',
						'title' => esc_html__( 'Text length', 'cws-essentials' ),
						'addrowclasses' => 'box grid-col-12',
						'value' => '90'
					),
					'cols' => array(
						'type' => 'select',
						'title' => esc_html__( 'Columns', 'cws-essentials' ),
						'addrowclasses' => 'box grid-col-12',
						'source' => array(
							'1' => array(esc_html__( 'one', 'cws-essentials' ), false),
							'2' => array(esc_html__( 'two', 'cws-essentials' ), false),
							'3' => array(esc_html__( 'three', 'cws-essentials' ), true),
							'4' => array(esc_html__( 'four', 'cws-essentials' ), false),
							),
						),
					'items_show' => array(
						'type' => 'number',
						'title' => esc_html__( 'Number of items to show', 'cws-essentials' ),
						'addrowclasses' => 'box grid-col-12',
						'value' => '3'
					),
					'posts_hide' => array(
						'title' => esc_html__( 'Hide', 'cws-essentials' ),	
						'atts' => 'multiple',
						'type' => 'select',
						'addrowclasses' => 'box grid-col-12',
						'source' => array(
							'none' => array(esc_html__( 'None', 'cws-essentials' ), true),
							'cats' => array(esc_html__( 'Categories', 'cws-essentials' ), false),
							'tags' => array(esc_html__( 'Tags', 'cws-essentials' ), true),
							'author' => array(esc_html__( 'Author', 'cws-essentials' ), true),
							'likes' => array(esc_html__( 'Likes', 'cws-essentials' ), true),
							'date' => array(esc_html__( 'Date', 'cws-essentials' ), false),
							'comments' => array(esc_html__( 'Comments', 'cws-essentials' ), true),
							'read_more' => array(esc_html__( 'Read More', 'cws-essentials' ), true),
							'social' => array(esc_html__( 'Social Icons', 'cws-essentials' ), true),
							'excerpt' => array(esc_html__( 'Excerpt', 'cws-essentials' ), true),
							),
					),
				),
			),
			'post_custom_color' => array(
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox grid-col-12',
				'title' => esc_html__( 'Edit Colors', 'cws-essentials' ),
				'atts' => 'data-options="e:post_title_color;e:post_breadcrumbs_color;e:post_breadcrumbs_hover_color;"',
			),
			'post_title_color' => array(
				'title' 		=> esc_html__( 'Title Color', 'cws-essentials' ),
				'tooltip' => array(
					'title' => esc_html__( 'Override Title Color', 'cws-essentials' ),
					'content' => esc_html__( 'Override Title Color', 'cws-essentials' ),
				),
				'type' 			=> 'text',
				'addrowclasses' => 'disable grid-col-3',
				'atts' 			=> 'data-default-color="#ffe27a"',
				'value'			=> '#ffe27a'
			),
			'post_breadcrumbs_color' => array(
				'title' 		=> esc_html__( 'Breadcrumbs Color', 'cws-essentials' ),
				'type' 			=> 'text',
				'addrowclasses' => 'disable grid-col-3',
				'atts' 			=> 'data-default-color="#ffffff;"',
				'value'			=> '#ffffff'
			),
			'post_breadcrumbs_hover_color' => array(
				'title' 		=> esc_html__( 'Breadcrumbs Hover Color', 'cws-essentials' ),
				'type' 			=> 'text',
				'addrowclasses' => 'disable grid-col-3',
				'atts' 			=> 'data-default-color="#ffe27a;"',
				'value'			=> '#ffe27a'
			),
			'custom_title_spacings' => array(
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox grid-col-12',
				'title' => esc_html__( 'Add Title Spacings', 'cws-essentials' ),
				'atts' => 'data-options="e:page_title_spacings;"',
			),
			'page_title_spacings' => array(
				'type' => 'margins',
				'addrowclasses' => 'grid-col-4 two-inputs',
				'value' => array(
					'top' => array('placeholder' => esc_html__( 'Top (in px)', 'cws-essentials' ), 'value' => '37'),
					'bottom' => array('placeholder' => esc_html__( 'Bottom (in px)', 'cws-essentials' ), 'value' => '36'),
				),
			),
		);

		$this->cws_generate_metabox($post, $mb_attr_all);
	}

	public function mb_post_side_callback( $post ) {
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );

		$mb_attr = array(
			'post_title_box_image' => array(
				'title' => esc_html__( 'Image', 'cws-essentials' ),
				'addrowclasses' => 'hide_label',
				'type' => 'media'
			),
		);

		$this->cws_generate_metabox($post, $mb_attr);
	}

	public function cws_generate_metabox($post, $mb_attr){
		$cws_stored_meta = get_post_meta( $post->ID, 'cws_mb_post' );
		if (function_exists('cws_core_build_layout') ) {
			echo cws_core_build_layout((!empty($cws_stored_meta) ? $cws_stored_meta[0] : ''), $mb_attr, 'cws_mb_');
		}
	}

	public function cws_post_metabox_save( $post_id, $post ) {
		if ( in_array($post->post_type, array('post', 'page', 'cws_testimonials', 'cws_portfolio', 'cws_staff', 'cws_classes', 'megamenu_item')) ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;

			if ( !isset( $_POST['mb_nonce']) || !wp_verify_nonce($_POST['mb_nonce'], 'cws_mb_nonce') )
				return;

			if ( !current_user_can( 'edit_post', $post->ID ) )
				return;

			$save_array = array();

			foreach($_POST as $key => $value) {
				if (0 === strpos($key, 'cws_mb_')) {
					if ('on' === $value) {
						$value = '1';
					}
					if (is_array($value)) {
						foreach ($value as $k => $val) {
							if (is_array($val)) {
								$this->cws_remove_dummy($val);
								$save_array[substr($key, 7)][$k] = $val;
							} else {
								$save_array[substr($key, 7)][$k] = esc_html($val);
							}
						}
					} else {
						$save_array[substr($key, 7)] = esc_html($value);
					}
				}
			}
			if (!empty($save_array)) {
				update_post_meta($post_id, 'cws_mb_post', $save_array);
			}
		}
	}

	private function cws_remove_dummy(&$val) {
		if (is_array($val)) {
			if (!$this->cws_is_assoc($val)) {
				// check for dummy key and delete it if there are
				if ('!!!dummy!!!' === $val[0]) { array_shift($val); }
			} else {
				foreach ($val as $key => &$value) {
					$this->cws_remove_dummy($value);
				}
			}
		}
	}

	private function cws_is_assoc(array $array) {
		return count(array_filter(array_keys($array), 'is_string')) > 0;
	}
}
?>