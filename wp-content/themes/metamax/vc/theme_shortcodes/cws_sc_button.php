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
    $icon_params = cws_ext_icon_vc_sc_config_params();

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
			"heading"		=> esc_html__( 'Aligning', 'metamax' ),
			"param_name"	=> "aligning",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"dependency"		=> array(
				"element"	=> "customize_align",
				"not_empty"	=> true
			),
			"value"			=> array(
				esc_html__( 'Left', 'metamax' )		=> 'left',
				esc_html__( 'Center', 'metamax' )	=> 'center',
				esc_html__( 'Right', 'metamax' )	=> 'right',
			)
		),
		array(
			"type"				=> "checkbox",
			"param_name"		=> "custom_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"value"				=> array( esc_html__( 'Custom Title Size', 'metamax' ) => true )
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Title Size', 'metamax' ),
			"param_name"		=> "title_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"value"				=> "18px"
		),
		array(
			"type"				=> "checkbox",
			"param_name"		=> "custom_colors",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> array( esc_html__( 'Custom Colors', 'metamax' ) => true )
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "btn_font_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#fff"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Hover Color', 'metamax' ),
			"param_name"		=> "btn_font_color_hover",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"value"				=> '#fff'
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Icon', 'metamax' ),
            "param_name"		=> "btn_icon_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_colors",
                "not_empty"	=> true
            ),
            "value"				=> "#fff"
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Icon Hover', 'metamax' ),
            "param_name"		=> "btn_icon_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_colors",
                "not_empty"	=> true
            ),
            "value"				=> '#fff'
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Background Color', 'metamax' ),
			"param_name"		=> "btn_background_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"value"				=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($theme_first_color).', .9)'
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Background Hover Color', 'metamax' ),
			"param_name"		=> "btn_background_color_hover",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"value"				=> $theme_first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Border Color', 'metamax' ),
			"param_name"		=> "btn_border_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"value"				=> 'transparent'
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Border Hover Color', 'metamax' ),
			"param_name"		=> "btn_border_color_hover",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"value"				=> 'transparent'
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
                "heading"		=> esc_html__( 'Icon Position', 'metamax' ),
                "param_name"	=> "icon_pos",
                "value"			=> array(
                    esc_html__( 'Right', 'metamax' )	=> 'right',
                    esc_html__( 'Left', 'metamax' )		=> 'left'
                )
            ),
			array(
				"type"				=> "textfield",
				"admin_label"		=> true,
				"heading"			=> esc_html__( 'Title', 'metamax' ),
				"param_name"		=> "title",
				"value"				=> "Click Me!"
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Link', 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-6",
				"param_name"		=> "url",
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Size', 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-6",
				"param_name"		=> "size",
				"description"		=> esc_html__( 'For custom size change paddings in css editor', 'metamax' ),
				"value"				=> array(
					esc_html__( 'Regular', 'metamax' )		=> 'regular',
					esc_html__( 'Small', 'metamax' )		=> 'small',
					esc_html__( 'Large', 'metamax' )		=> 'large',
				),
			),
			array(
				"type"				=> "checkbox",
				"param_name"		=> "new_tab",
				"value"				=> array( esc_html__( 'Open in New Tab', 'metamax' ) => true ),
				"std"				=> "1"
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
	vc_map( array(
		"name"				=> esc_html__( 'CWS Button', 'metamax' ),
		"base"				=> "cws_sc_button",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Button extends WPBakeryShortCode {
	    }
	}
?>