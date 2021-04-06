<?php
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	/* -----> STYLING GROUP TITLES <----- */
	$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
	$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
	$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

    /* -----> GET ICON CONFIG <----- */
    $icon_params = cws_ext_icon_vc_sc_config_params();

	/* -----> STYLING TAB PROPERTIES <----- */
	$background_properties = cws_module_background_props();

	$styles = cws_ext_merge_arrs( array(
		array(
			array(
				"type"			=> "css_editor",
				"param_name"	=> "custom_styles",
				"group"			=> esc_html__( "Styling", 'metamax' ),
				"responsive"	=> 'all'
			)
		),
		$background_properties,
		array(
			array(
				"type"			=> "checkbox",
				"param_name"	=> "bg_overlay",
				"group"			=> esc_html__( "Styling", 'metamax' ),
				"value"			=> array( esc_html__( 'Use Background Color as Overlay', 'metamax' ) => true ),
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "customize_align",
				"group"			=> esc_html__( "Styling", 'metamax' ),
				"responsive"	=> "all",
				"value"			=> array( esc_html__( 'Customize Alignment', 'metamax' ) => true ),
			),
			array(
				"type"			=> "dropdown",
				"heading"		=> esc_html__( 'Module Aligning', 'metamax' ),
				"param_name"	=> "aligning",
				"group"			=> esc_html__( "Styling", 'metamax' ),
				"responsive"	=> "all",
				"dependency"		=> array(
					"element"	=> "customize_align",
					"not_empty"	=> true
				),
				"value"			=> array(
					esc_html__( 'Left', 'metamax' ) 	=> 'left',
					esc_html__( 'Center', 'metamax' ) 	=> 'center',
					esc_html__( 'Right', 'metamax' ) 	=> 'right',
				)
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
				"heading"			=> esc_html__( 'Title Size', 'metamax' ),
				"param_name"		=> "title_size",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-6",
				"responsive"		=> "all",
				"dependency"		=> array(
					"element"	=> "customize_size",
					"not_empty"	=> true
				),
				"value"				=> "30px"
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Button Size', 'metamax' ),
				"param_name"		=> "button_size",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-6",
				"dependency"		=> array(
					"element"	=> "customize_size",
					"not_empty"	=> true
				),
				"value"				=> array(
					esc_html__( 'Small', 'metamax' ) 	=> 'small',
					esc_html__( 'Regular', 'metamax' ) 	=> 'regular',
					esc_html__( 'Large', 'metamax' ) 	=> 'large',
				)
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "customize_colors",
				"group"			=> esc_html__( "Styling", 'metamax' ),
				"value"			=> array( esc_html__( 'Customize Colors', 'metamax' ) => true ),
				"std"			=> '1'
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Title Color', 'metamax' ),
				"param_name"		=> "title_color",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> "#fff"
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Divider Color', 'metamax' ),
				"param_name"		=> "divider_color",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> $theme_first_color
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Description Color', 'metamax' ),
				"param_name"		=> "description_color",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> "rgba(255,255,255, .6)"
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Button Title', 'metamax' ),
				"param_name"		=> "btn_font_color",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> "#fff"
			),
            array(
                "type"				=> "colorpicker",
                "heading"			=> esc_html__( 'Button Icon', 'metamax' ),
                "param_name"		=> "btn_icon_color",
                "group"				=> esc_html__( "Styling", 'metamax' ),
                "edit_field_class" 	=> "vc_col-xs-4",
                "dependency"		=> array(
                    "element"	=> "customize_colors",
                    "not_empty"	=> true
                ),
                "value"				=> "#fff"
            ),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Button Background', 'metamax' ),
				"param_name"		=> "btn_background_color",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> $theme_first_color
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Button Border', 'metamax' ),
				"param_name"		=> "btn_border_color",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> $theme_first_color
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Button Title Hover', 'metamax' ),
				"param_name"		=> "btn_font_color_hover",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> $theme_first_color
			),
            array(
                "type"				=> "colorpicker",
                "heading"			=> esc_html__( 'Button Icon Hover', 'metamax' ),
                "param_name"		=> "btn_icon_color_hover",
                "group"				=> esc_html__( "Styling", 'metamax' ),
                "edit_field_class" 	=> "vc_col-xs-4",
                "dependency"		=> array(
                    "element"	=> "customize_colors",
                    "not_empty"	=> true
                ),
                "value"				=> $theme_first_color
            ),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Button Background Hover', 'metamax' ),
				"param_name"		=> "btn_background_color_hover",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> "#fff"
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Button Border Hover', 'metamax' ),
				"param_name"		=> "btn_border_color_hover",
				"group"				=> esc_html__( "Styling", 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "customize_colors",
					"not_empty"	=> true
				),
				"value"				=> $theme_first_color
			),
		)
	));

	/* -----> RESPONSIVE STYLING TABS PROPERTIES <----- */
	$styles_landscape = $styles_portrait = $styles_mobile = $styles;

	$styles_landscape =  $cws_theme_funcs->cws_responsive_styles($styles_landscape, 'landscape', $landscape_group);
	$styles_portrait =  $cws_theme_funcs->cws_responsive_styles($styles_portrait, 'portrait', $portrait_group);
	$styles_mobile =  $cws_theme_funcs->cws_responsive_styles($styles_mobile, 'mobile', $mobile_group);

	$params = cws_ext_merge_arrs( array(
		/* -----> GENERAL TAB <----- */
        array(
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Banner style', 'metamax' ),
                "param_name"		=> "banner_style",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> array(
                    esc_html__( 'Style 1', 'metamax' ) 	=> 'style-1',
                    esc_html__( 'Style 2', 'metamax' )	=> 'style-2',
                )
            ),
            array(
                "type"			=> "textarea",
                "admin_label"	=> true,
                "heading"		=> esc_html__( 'Title', 'metamax' ),
                "param_name"	=> "title",
                "value"			=> "Enter title here"
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Description', 'metamax' ),
                "param_name"		=> "description",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> "Enter description here"
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Banner Url', 'metamax' ),
                "param_name"		=> "banner_url",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> "#"
            ),
            array(
                "type"				=> "checkbox",
                "param_name"		=> "add_divider",
                "edit_field_class" 	=> "vc_col-xs-4",
                "value"				=> array( esc_html__( 'Add Divider', 'metamax' ) => true ),
                "std"				=> '1'
            ),
            array(
                "type"				=> "checkbox",
                "param_name"		=> "new_tab",
                "edit_field_class" 	=> "vc_col-xs-4",
                "value"				=> array( esc_html__( 'Open Link in New Tab', 'metamax' ) => true ),
                "std"				=> '1'
            ),
            array(
                "type"				=> "checkbox",
                "param_name"		=> "add_button",
                "edit_field_class" 	=> "vc_col-xs-4",
                "value"				=> array( esc_html__( 'Add Button', 'metamax' ) => true )
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Button Title', 'metamax' ),
                "param_name"		=> "button_title",
                "edit_field_class" 	=> "vc_col-xs-6",
                "dependency"		=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
                "value"				=> "Click Me!"
            ),
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Button Position', 'metamax' ),
                "param_name"		=> "button_position",
                "edit_field_class" 	=> "vc_col-xs-6",
                "dependency"		=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
                "value"				=> array(
                    esc_html__( 'Default', 'metamax' ) 	=> 'default',
                    esc_html__( 'Floated', 'metamax' )	=> 'floated',
                )
            ),
        ),
        $icon_params,
		array(
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Icon Position', 'metamax' ),
                "param_name"	=> "icon_pos",
                "value"			=> array(
                    esc_html__( 'Right', 'metamax' )	=> 'right',
                    esc_html__( 'Left', 'metamax' )		=> 'left'
                )
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

	/* -----> MODULE DACLARATION <----- */
	vc_map( array(
		"name"				=> esc_html__( 'CWS Banner', 'metamax' ),
		"base"				=> "cws_sc_banners",
		"category"			=> "By CWS",
		"icon" 				=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Banners extends WPBakeryShortCode {
	    }
	}
?>