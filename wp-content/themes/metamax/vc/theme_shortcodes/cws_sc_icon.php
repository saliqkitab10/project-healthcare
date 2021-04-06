<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

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
			"type"				=> "checkbox",
			"param_name"		=> "custom_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"value"				=> array( esc_html__( 'Custom Sizes', 'metamax' ) => true ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Size', 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"param_name"		=> "icon_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
			"value"				=> "64"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Spacing to Border', 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"param_name"		=> "icon_spacing",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
			"value"				=> "48"
		),
        array(
            "type"				=> "textfield",
            "heading"			=> esc_html__( 'Border width', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-4",
            "param_name"		=> "border_width",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "responsive"		=> "all",
            "dependency"		=> array(
                "element"	=> "custom_size",
                "not_empty"	=> true
            ),
            "description"		=> esc_html__( 'In pixels', 'metamax' ),
            "value"				=> "4"
        ),
		array(
			"type"				=> "checkbox",
			"param_name"		=> "custom_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> array( esc_html__( 'Custom Colors', 'metamax' ) => true ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon', 'metamax' ),
			"param_name"		=> "icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $theme_first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Hover', 'metamax' ),
			"param_name"		=> "icon_color_hover",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> ""
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Border', 'metamax' ),
			"param_name"		=> "icon_bd_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> ""
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Border Hover', 'metamax' ),
			"param_name"		=> "icon_bd_color_hover",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
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
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> ""
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Background Hover', 'metamax' ),
            "param_name"		=> "fancybox ",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> ""
        ),
	);

	/* -----> RESPONSIVE STYLING TABS PROPERTIES <----- */
	$styles_landscape = $styles_portrait = $styles_mobile = $styles;

	$styles_landscape =  $cws_theme_funcs->cws_responsive_styles($styles_landscape, 'landscape', $landscape_group);
	$styles_portrait =  $cws_theme_funcs->cws_responsive_styles($styles_portrait, 'portrait', $portrait_group);
	$styles_mobile =  $cws_theme_funcs->cws_responsive_styles($styles_mobile, 'mobile', $mobile_group);

	$params = cws_ext_merge_arrs( array(
		/* -----> GENERAL TAB <----- */
		$icon_params,
		array(
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Icon Shape', 'metamax' ),
                "param_name"	=> "icon_shape",
                "value"			=> array(
                    esc_html__( 'Rounded', 'metamax' )		=> 'rounded',
                    esc_html__( 'Hexagon', 'metamax' )	    => 'hexagon',
                    esc_html__( 'Circle', 'metamax' )	    => 'circle',
                )
            ),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Link', 'metamax' ),
				"param_name"		=> "url",
			),
			array(
				"type"				=> "checkbox",
				"param_name"		=> "new_tab",
				"dependency"		=> array(
					"element"	=> "url",
					"not_empty"	=> true
				),
				"value"				=> array( esc_html__( 'Open in New Tab', 'metamax' ) => true )
			),
            array(
				"type"				=> "checkbox",
				"param_name"		=> "pop_up",
				"dependency"		=> array(
					"element"	=> "url",
					"not_empty"	=> true
				),
				"value"				=> array( esc_html__( 'Open in Pop-Up Window', 'metamax' ) => true )
			),
            array(
                "type"				=> "checkbox",
                "param_name"		=> "add_shadow",
                "value"				=> array( esc_html__( 'Add shadow', 'metamax' ) => true ),
                "std"               => "0",
            ),
            array(
                "type"				=> "checkbox",
                "param_name"		=> "add_animation",
                "value"				=> array( esc_html__( 'Add animation', 'metamax' ) => true ),
                "std"               => "0",
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
		"name"				=> esc_html__( 'CWS Icon', 'metamax' ),
		"base"				=> "cws_sc_icon",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Icon extends WPBakeryShortCode {
	    }
	}
?>