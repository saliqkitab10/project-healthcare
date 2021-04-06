<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );
    $body_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

	/* -----> STYLING GROUP TITLES <----- */
	$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
	$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
	$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

	/* -----> GET ICON CONFIG <----- */
	$icon_params = cws_ext_icon_vc_sc_config_params ("icon_type", false, array( "iconic" ));

	/* -----> STYLING TAB PROPERTIES <----- */
	$styles = array(
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> 'all'
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_position",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"value"			=> array( esc_html__( 'Customize Elements Position', 'metamax' ) => true ),
            "dependency"		=> array(
                "element"	=> "service_type",
                "value"	    => "default"
            ),
		),
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Module Alignment', 'metamax' ),
			"param_name"		=> "module_align",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_position",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'Left', 'metamax' ) 		=> 'left',
				esc_html__( 'Center', 'metamax' ) 		=> 'center',
				esc_html__( 'Right', 'metamax' ) 		=> 'right',
			)
		),
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Icon Position', 'metamax' ),
			"param_name"		=> "icon_pos",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_position",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'Left', 'metamax' ) 		=> 'left',
				esc_html__( 'Top', 'metamax' ) 			=> 'top',
				esc_html__( 'Right', 'metamax' ) 		=> 'right',
				esc_html__( 'Corner', 'metamax' ) 		=> 'corner',
			),
			"std"				=> "top"
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_size",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"value"			=> array( esc_html__( 'Customize Sizes', 'metamax' ) => true ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Size', 'metamax' ),
			"param_name"		=> "icon_size",
			"responsive"		=> "all",
			"edit_field_class" 	=> "vc_col-xs-6",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"				=> "51"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Spacing to Border', 'metamax' ),
			"param_name"		=> "icon_spacing",
			"responsive"		=> "all",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"				=> "0"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Title Size', 'metamax' ),
			"param_name"		=> "title_size",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "20"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Description Size', 'metamax' ),
			"param_name"		=> "desc_size",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "16"
		),
        array(
            "type"				=> "textfield",
            "heading"			=> esc_html__( 'Icon Paddings', 'metamax' ),
            "param_name"		=> "icon_paddings",
            "responsive"		=> "all",
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_size",
                "not_empty"	=> true
            ),
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "value"				=> ""
        ),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Title Paddings', 'metamax' ),
			"param_name"		=> "title_paddings",
			"responsive"		=> "all",
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> "0px 0px 0px 0px"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Description Paddings', 'metamax' ),
			"param_name"		=> "desc_paddings",
			"edit_field_class" 	=> "vc_col-xs-6",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> "0px 0px 0px 0px"
		),
        array(
            "type"				=> "textfield",
            "heading"			=> esc_html__( 'Button Paddings', 'metamax' ),
            "param_name"		=> "button_paddings",
            "edit_field_class" 	=> "vc_col-xs-6",
            "responsive"		=> "all",
            "dependency"		=> array(
                "element"	=> "customize_size",
                "not_empty"	=> true
            ),
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "value"				=> "20px 0px 0px 0px"
        ),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_color",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Customize Colors', 'metamax' ) => true ),
			"std"			=> "1"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Color', 'metamax' ),
			"param_name"		=> "icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"value"				=> $theme_first_color
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Color on Hover', 'metamax' ),
            "param_name"		=> "icon_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Background Color', 'metamax' ),
            "param_name"		=> "icon_bg_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ''
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Background Color on Hover', 'metamax' ),
            "param_name"		=> "icon_bg_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ''
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Border Color', 'metamax' ),
            "param_name"		=> "icon_bd_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ''
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Border Color on Hover', 'metamax' ),
            "param_name"		=> "icon_bd_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ''
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"value"				=> $body_color
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Title Color on Hover', 'metamax' ),
            "param_name"		=> "title_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Description Color', 'metamax' ),
			"param_name"		=> "desc_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"value"				=> $body_color
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Description Color on Hover', 'metamax' ),
            "param_name"		=> "desc_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Background Color on Hover', 'metamax' ),
			"param_name"		=> "bg_color_hover",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"value"				=> ""
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Shadow Color', 'metamax' ),
			"param_name"		=> "shadow_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"value"				=> ""
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Divider Color', 'metamax' ),
			"param_name"		=> "divider_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"value"				=> "#ffe27a"
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Divider Color on Hover', 'metamax' ),
            "param_name"		=> "divider_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),

        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Color', 'metamax' ),
            "param_name"		=> "button_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> "#fff"
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Color on Hover', 'metamax' ),
            "param_name"		=> "button_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Background Color', 'metamax' ),
            "param_name"		=> "button_bg_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Background Color on Hover', 'metamax' ),
            "param_name"		=> "button_bg_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> "#fff"
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Border Color', 'metamax' ),
            "param_name"		=> "button_bd_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Border Color on Hover', 'metamax' ),
            "param_name"		=> "button_bd_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "value"				=> $theme_first_color
        ),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "disable_shadow",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Disable Shadow on Hover', 'metamax' ) => true ),
			"std"			=> '1'
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "hide_divider",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"value"			=> array( esc_html__( 'Hide Divider', 'metamax' ) => true ),
		),
	);

	/* -----> RESPONSIVE STYLING TABS PROPERTIES <----- */
	$styles_landscape = $styles_portrait = $styles_mobile = $styles;

	$styles_landscape = $cws_theme_funcs->cws_responsive_styles($styles_landscape, 'landscape', $landscape_group);
	$styles_portrait = $cws_theme_funcs->cws_responsive_styles($styles_portrait, 'portrait', $portrait_group);
	$styles_mobile = $cws_theme_funcs->cws_responsive_styles($styles_mobile, 'mobile', $mobile_group);

	$params = cws_ext_merge_arrs( array(
		/* -----> GENERAL TAB <----- */
        array(
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Service Type', 'metamax' ),
                "param_name"	=> "service_type",
                "value"			=> array(
                    esc_html__( 'Default', 'metamax' )		=> 'default',
                    esc_html__( 'Card', 'metamax' )	        => 'card',
                    esc_html__( 'List', 'metamax' )	        => 'list',
                    esc_html__( 'Gallery', 'metamax' )	    => 'gallery',
                )
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "first_child",
                "dependency"	=> array(
                    "element"	=> "service_type",
                    "value"	    => "gallery"
                ),
                "value"			=> array( esc_html__( 'It is first element in row', 'metamax' ) => true ),
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "last_child",
                "dependency"	=> array(
                    "element"	=> "service_type",
                    "value"	    => "gallery"
                ),
                "value"			=> array( esc_html__( 'It is last element in row', 'metamax' ) => true ),
            ),
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Icon Type', 'metamax' ),
                "param_name"	=> "icon_type",
                "value"			=> array(
                    esc_html__( 'Icon', 'metamax' )		=> 'iconic',
                    esc_html__( 'Image', 'metamax' )	=> 'image',
                )
            ),
            array(
                "type"			=> "attach_image",
                "heading"		=> esc_html__( 'Image', 'metamax' ),
                "param_name"	=> "plan_img",
                "dependency"	=> array(
                    "element"	=> "icon_type",
                    "value"		=> array( "image" )
                ),
            ),
        ),
		$icon_params,
		array(
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Icon Shape', 'metamax' ),
                "param_name"	=> "icon_shape",
                "value"			=> array(
                    esc_html__( 'Hexagon', 'metamax' )	=> 'hexagon',
                    esc_html__( 'Rounded', 'metamax' )	=> 'rounded',
                    esc_html__( 'Circle', 'metamax' )	=> 'circle',
                ),
                "dependency"	=> array(
                    "element"	=> "icon_type",
                    "value"		=> array( "icon" )
                ),
            ),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Divider Type', 'metamax' ),
				"param_name"		=> "divider",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> array(
					esc_html__( 'None', 'metamax' ) 		=> 'none',
					esc_html__( 'Right', 'metamax' ) 		=> 'right',
					esc_html__( 'Under Title', 'metamax' ) 	=> 'bottom',
					esc_html__( 'Full Width', 'metamax' ) 	=> 'full',
				)
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Link', 'metamax' ),
				"param_name"		=> "link",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> ""
			),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Divider Width', 'metamax' ),
                "param_name"		=> "divider_width",
                "edit_field_class" 	=> "vc_col-xs-6",
                "dependency"		=> array(
                    "element"	=> "divider",
                    "value"		=> "bottom"
                ),
                "value"				=> "21px"
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Side Divider Height', 'metamax' ),
                "param_name"		=> "divider_height_side",
                "edit_field_class" 	=> "vc_col-xs-6",
                "description"		=> esc_html__( 'In percents', 'metamax' ),
                "dependency"		=> array(
                    "element"	=> "divider",
                    "value"		=> "right"
                ),
                "value"				=> "80%"
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "new_tab",
                "dependency"	=> array(
                    "element"	=> "link",
                    "not_empty"	=> true
                ),
                "value"			=> array( esc_html__( 'Open in new tab', 'metamax' ) => true ),
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "add_button",
                "value"			=> array( esc_html__( 'Add extra button', 'metamax' ) => true ),
                "dependency"	=> array(
                    "element"	=> "link",
                    "is_empty"	=> true
                ),
            ),
            array(
                "type"			=> "textfield",
                "heading"		=> esc_html__( 'Button link', 'metamax' ),
                "param_name"	=> "extra_button_url",
                "dependency"	=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
            ),
            array(
                "type"			=> "textfield",
                "heading"		=> esc_html__( 'Button title', 'metamax' ),
                "param_name"	=> "extra_button_title",
                "dependency"	=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
            ),
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Button Type', 'metamax' ),
                "param_name"		=> "extra_button_style",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> array(
                    esc_html__( 'Simple', 'metamax' ) 		=> 'simple',
                    esc_html__( 'Standard', 'metamax' ) 	=> 'standard',
                    esc_html__( 'Hover', 'metamax' ) 	    => 'hover',
                    esc_html__( 'Arrow', 'metamax' ) 	    => 'arrow',
                ),
                "dependency"	=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
            ),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Title', 'metamax' ),
				"param_name"		=> "title",
				"value"				=> "Enter title here...",
                "admin_label"	=> true
			),
			array(
				"type"				=> "textarea",
				"heading"			=> esc_html__( 'Description', 'metamax' ),
				"param_name"		=> "description",
				"value"				=> "Enter description here..."
			),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "add_counter",
                "value"			=> array( esc_html__( 'Add counter', 'metamax' ) => true ),
                "dependency"		=> array(
                    "element"	=> "service_type",
                    "value"	    => array('card', 'gallery'),
                ),
            ),
            array(
                "type"			=> "textfield",
                "heading"		=> esc_html__( 'Counter Value', 'metamax' ),
                "param_name"	=> "counter_value",
                "value"         => '01',
                "dependency"	=> array(
                    "element"	=> "add_counter",
                    "not_empty" => true
                ),
            ),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Extra class name', 'metamax' ),
				"description"		=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
				"param_name"		=> "el_class",
				"value"				=> ""
			),
		),
		/* -----> STYLING TAB <----- */
		$styles,
		/* -----> TABLET LANDSCAPE TAB <----- */
		$styles_landscape,
		/* -----> TABLET PORTRAIT TAB <----- */
		$styles_portrait,
		/* -----> MOBILE TAB <----- */
		$styles_mobile
	));

	// Map Shortcode in Visual Composer
	vc_map( array(
		"name"				=> esc_html__( 'CWS Services', 'metamax' ),
		"base"				=> "cws_sc_services",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Services extends WPBakeryShortCode {
	    }
	}
?>