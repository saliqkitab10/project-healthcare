<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$first_color 	= esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$second_color 	= esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

	/* -----> STYLING GROUP TITLES <----- */
	$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
	$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
	$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

	/* -----> STYLING TAB PROPERTIES <----- */
	$styles = array(
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> 'all'
		),
		array(
			"type"				=> "checkbox",
			"param_name"		=> "custom_colors",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> array( esc_html__( 'Custom Colors', 'metamax' ) => true ),
			"std"				=> '1'
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Arrows Color', 'metamax' ),
			"param_name"		=> "nav_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($second_color).', .49)'
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Arrows Hover Color', 'metamax' ),
			"param_name"		=> "nav_hover_color",
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
            "heading"			=> esc_html__( 'Arrows Background', 'metamax' ),
            "param_name"		=> "nav_bg",
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
			"heading"			=> esc_html__( 'Arrows Hover Background', 'metamax' ),
			"param_name"		=> "nav_hover_bg",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($second_color).', .49)'
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Arrows Border', 'metamax' ),
            "param_name"		=> "nav_bd",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "custom_colors",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-12",
            "value"				=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($second_color).', .21)'
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Dots Color', 'metamax' ),
			"param_name"		=> "dots_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#D5D5D5"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Dots Active Color', 'metamax' ),
			"param_name"		=> "dots_active_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Dots Active Border', 'metamax' ),
			"param_name"		=> "dots_active_border",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_colors",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $second_color
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
				"type"			=> "dropdown",
				"heading"		=> esc_html__( 'Carousel Columns', 'metamax' ),
				"param_name"	=> "columns",
				"value"			=> array(
					esc_html__( "One", 'metamax' ) 		=> '1',
					esc_html__( "Two", 'metamax' )		=> '2',
					esc_html__( "Three", 'metamax' )	=> '3',
					esc_html__( "Four", 'metamax' )		=> '4'
				)		
			),
			array(
				"type"			=> "textfield",
				"heading"		=> esc_html__( 'Slides to scroll', 'metamax' ),
				"param_name"	=> "slides_to_scroll",
				"value"			=> "1"
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "pagination",
				"value"			=> array( esc_html__( 'Add Pagination Dots', 'metamax' ) => true ),
				"std"			=> '1'
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "navigation",
				"value"			=> array( esc_html__( 'Add Navigation Arrows', 'metamax' ) => true )
			),
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Navigation Arrows position', 'metamax' ),
                "param_name"	=> "nav_position",
                "dependency"	=> array(
                    "element"	=> "navigation",
                    "not_empty"	=> true
                ),
                "value"			=> array(
                    esc_html__( "Outside", 'metamax' ) 		=> 'outside',
                    esc_html__( "Inside", 'metamax' )		=> 'inside',
                )
            ),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "auto_height",
				"value"			=> array( esc_html__( 'Auto Height', 'metamax' ) => true ),
				"std"			=> '1'
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "draggable",
				"value"			=> array( esc_html__( 'Draggable', 'metamax' ) => true ),
				"std"			=> '1'
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "infinite",
				"value"			=> array( esc_html__( 'Infinite Loop', 'metamax' ) => true )
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "autoplay",
				"value"			=> array( esc_html__( 'Autoplay', 'metamax' ) => true )
			),
			array(
				"type"			=> "textfield",
				"heading"		=> esc_html__( 'Autoplay Speed', 'metamax' ),
				"dependency"	=> array(
					"element"	=> "autoplay",
					"not_empty"	=> true
				),
				"param_name"	=> "autoplay_speed",
				"value"			=> "3000"
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "pause_on_hover",
				"dependency"	=> array(
					"element"	=> "autoplay",
					"not_empty"	=> true
				),
				"value"			=> array( esc_html__( 'Pause on Hover', 'metamax' ) => true )
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "vertical",
				"value"			=> array( esc_html__( 'Vertical Direction', 'metamax' ) => true )
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "vertical_swipe",
				"value"			=> array( esc_html__( 'Vertical Swipe', 'metamax' ) => true )
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
		"name"				=> esc_html__( 'CWS Carousel', 'metamax' ),
		"base"				=> "cws_sc_carousel",
		'content_element' 	=> true,
		'as_parent'			=> array('only' => 'vc_column_text, cws_sc_text, cws_sc_services, cws_sc_widget_text, vc_single_image, cws_sc_tips'),
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		'js_view' 			=> 'VcColumnView',
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Carousel extends WPBakeryShortCodesContainer {
	    }
	}
?>