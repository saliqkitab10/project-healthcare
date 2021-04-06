<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

	/* -----> STYLING GROUP TITLES <----- */
	$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
	$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
	$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

	/* -----> GET ICON CONFIG <----- */
	$icon_params = cws_ext_icon_vc_sc_config_params ();

	/* -----> STYLING TAB PROPERTIES <----- */
	$styles = array(
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> 'all'
		),
        array(
            "type"				=> "dropdown",
            "heading"			=> esc_html__( 'Module Vertical Alignment', 'metamax' ),
            "param_name"		=> "vert_align",
            "dependency"		=> array(
                "element"	=> "icon_pos",
                "value"	    => "beside"
            ),
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> array(
                esc_html__( 'Middle', 'metamax' ) 		=> 'middle',
                esc_html__( 'Top', 'metamax' ) 		    => 'top',
                esc_html__( 'Bottom', 'metamax' ) 		=> 'bottom',
            ),
            "std"				=> "middle"
        ),
        array(
            "type"			=> "checkbox",
            "param_name"	=> "customize_position",
            "group"			=> esc_html__( "Styling", 'metamax' ),
            "value"			=> array( esc_html__( 'Customize Module Aligning', 'metamax' ) => true ),
        ),
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Module Aligning', 'metamax' ),
			"param_name"	=> "aligning",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "customize_position",
				"not_empty"	=> true
			),
			"value"			=> array(
				esc_html__( 'Left', 'metamax' ) 	=> 'left',
				esc_html__( 'Center', 'metamax' ) 	=> 'center',
				esc_html__( 'Right', 'metamax' ) 	=> 'right',
			)
		),

		array(
			"type"				=> "checkbox",
			"param_name"		=> "customize_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"value"				=> array( esc_html__( 'Customize Sizes', 'metamax' ) => true ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Size', 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"param_name"		=> "icon_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
			"value"				=> "40"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Spacing to Border', 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"param_name"		=> "icon_spacing",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
			"value"				=> "20"
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
            "value"				=> "25"
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
            "value"				=> "0px 38px 0px 0px"
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
            "value"				=> "0px 0px 0px 80px"
        ),


		array(
			"type"				=> "checkbox",
			"param_name"		=> "customize_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> array( esc_html__( 'Customize Colors', 'metamax' ) => true ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon', 'metamax' ),
			"param_name"		=> "icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $theme_first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Border', 'metamax' ),
			"param_name"		=> "icon_bd_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> ""
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Background', 'metamax' ),
			"param_name"		=> "icon_bg_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "customize_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $theme_second_color
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Title Color', 'metamax' ),
            "param_name"		=> "title_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Description Color', 'metamax' ),
            "param_name"		=> "description_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Divider Color', 'metamax' ),
            "param_name"		=> "divider_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_second_color
        ),

        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Color', 'metamax' ),
            "param_name"		=> "button_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> "#ffffff"
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Color (Hover)', 'metamax' ),
            "param_name"		=> "button_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Border Color', 'metamax' ),
            "param_name"		=> "button_bd_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Border Color (Hover)', 'metamax' ),
            "param_name"		=> "button_bd_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Background Color', 'metamax' ),
            "param_name"		=> "button_bg_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $theme_first_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Background Color (Hover)', 'metamax' ),
            "param_name"		=> "button_bg_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "customize_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> "#ffffff"
        ),
	);

	/* -----> RESPONSIVE STYLING TABS PROPERTIES <----- */
	$styles_landscape = $styles_portrait = $styles_mobile = $styles;

	$styles_landscape =  $cws_theme_funcs->cws_responsive_styles($styles_landscape, 'landscape', $landscape_group);
	$styles_portrait =  $cws_theme_funcs->cws_responsive_styles($styles_portrait, 'portrait', $portrait_group);
	$styles_mobile =  $cws_theme_funcs->cws_responsive_styles($styles_mobile, 'mobile', $mobile_group);

	$params = cws_ext_merge_arrs( array(
		/* -----> GENERAL TAB <----- */
        array(
            array(
                "type"				=> "textarea",
                "heading"			=> esc_html__( 'Title', 'metamax' ),
                "param_name"		=> "title",
                "value"				=> esc_html__("Enter title here...", "metamax"),
                "admin_label"	    => true
            ),
            array(
                "type"				=> "textarea",
                "heading"			=> esc_html__( 'Description', 'metamax' ),
                "param_name"		=> "description",
                "value"				=> esc_html__("Enter description here...", "metamax")
            ),
        ),
		$icon_params,
		array(
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Icon Position', 'metamax' ),
                "param_name"		=> "icon_pos",
                "responsive"		=> "all",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> array(
                    esc_html__( 'Beside Content', 'metamax' ) 	=> 'beside',
                    esc_html__( 'Above Content', 'metamax' ) 	=> 'above',
                ),
                "std"				=> "beside"
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "add_divider",
                "value"			=> array( esc_html__( 'Add divider', 'metamax' ) => true ),
                "std"           => '1'
            ),
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Divider Type', 'metamax' ),
                "param_name"		=> "divider_pos",
                "responsive"		=> "all",
                "dependency"		=> array(
                    "element"	=> "add_divider",
                    "not_empty"	=> true
                ),
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> array(
                    esc_html__( 'Vertical', 'metamax' ) 	=> 'beside',
                    esc_html__( 'Under Title', 'metamax' ) 	=> 'under',
                ),
                "std"				=> "beside"
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "add_button",
                "value"			=> array( esc_html__( 'Add extra button', 'metamax' ) => true ),
                "std"           => '1'
            ),
            array(
                "type"			=> "textfield",
                "heading"		=> esc_html__( 'Button link', 'metamax' ),
                "param_name"	=> "button_url",
                "dependency"	=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
                "std"           => '#link'
            ),
            array(
                "type"			=> "textfield",
                "heading"		=> esc_html__( 'Button title', 'metamax' ),
                "param_name"	=> "button_title",
                "dependency"	=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
                "value"         => esc_html__("Read More", "metamax")
            ),
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Button Position', 'metamax' ),
                "param_name"		=> "button_pos",
                "responsive"		=> "all",
                "dependency"		=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> array(
                    esc_html__( 'Beside content', 'metamax' ) 	=> 'beside',
                    esc_html__( 'Under content', 'metamax' ) 	=> 'under',
                ),
                "std"				=> "beside"
            ),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Extra class name', 'metamax' ),
				"description"		=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
				"param_name"		=> "el_class",
				"value"				=> ""
			)
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
	
	vc_map( array(
		"name"				=> esc_html__( 'CWS Call-to-Action', 'metamax' ),
		"base"				=> "cws_sc_call_to_action",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Call_To_Action extends WPBakeryShortCode {
	    }
	}
?>