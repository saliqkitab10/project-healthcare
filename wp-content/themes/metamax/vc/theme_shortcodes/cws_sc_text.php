<?php
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );
	$header_color = esc_attr( $cws_theme_funcs->cws_get_option('header-font')['color'] );
	$body_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

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
			"responsive"	=> 'all',
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
			"heading"		=> esc_html__( 'Text Alignment', 'metamax' ),
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"param_name"	=> "module_alignment",
			"responsive"	=> "all",
			"dependency"		=> array(
				"element"	=> "customize_align",
				"not_empty"	=> true
			),
			"value"			=> array(
				esc_html__( "Left", 'metamax' ) => 'left',
				esc_html__( "Center", 'metamax' ) => 'center',
				esc_html__( "Right", 'metamax' ) => 'right',
			),
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_size",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> 'all',
			"value"			=> array( esc_html__( 'Customize Sizes', 'metamax' ) => true ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Title Size', 'metamax' ),
			"param_name"		=> "title_size",
			"edit_field_class" 	=> "vc_col-xs-4",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"				=> "60px",
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Subtitle Size', 'metamax' ),
			"param_name"		=> "subtitle_size",
			"edit_field_class" 	=> "vc_col-xs-4",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"				=> "18px",
		),
        array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Description Size', 'metamax' ),
			"param_name"		=> "description_size",
			"edit_field_class" 	=> "vc_col-xs-4",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"				=> "16px",
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Divider Width', 'metamax' ),
			"param_name"		=> "divider_size",
			"edit_field_class" 	=> "vc_col-xs-4",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"dependency"		=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"				=> "69px",
		),	
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Title Margins', 'metamax' ),
			"param_name"	=> "title_margins",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> 'all',
			"description"	=> esc_html__( '1, 2( top/bottom, left/right ) or 4, space separated, values with units', 'metamax' ),
			"dependency"	=> array(
				"element"	=> "customize_size",
				"not_empty"	=> true
			),
			"value"			=> "0px 0px 0px 0px",
		),	
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_colors",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Customize Colors', 'metamax' ) => true ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "custom_title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $header_color,
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Title Marking Text Color', 'metamax' ),
            "param_name"		=> "custom_title_mark",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-3",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
            "value"				=> "",
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Subtitle Color', 'metamax' ),
			"param_name"		=> "custom_subtitle_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $theme_first_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Divider Color', 'metamax' ),
			"param_name"		=> "custom_divider_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $theme_second_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Font Color', 'metamax' ),
			"param_name"		=> "custom_font_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $body_color,
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
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Subtitle', 'metamax' ),
				"param_name"		=> "subtitle",
				"value"				=> "Enter subtitle here",
				"admin_label"		=> true,
			),
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Subtitle Position', 'metamax' ),
                "param_name"	=> "subtitle_position",
                "responsive"	=> "all",
                "value"			=> array(
                    esc_html__( "Above", 'metamax' ) => 'above',
                    esc_html__( "Beside", 'metamax' ) => 'beside',
                ),
                "std"           => 'beside'
            ),
			array(
				"type"				=> "textarea",
				"heading"			=> esc_html__( 'Title', 'metamax' ),
				"param_name"		=> "title",
				"value"				=> "Enter title here",
				"edit_field_class" 	=> "vc_col-xs-6",
				"admin_label"		=> true,
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Title HTML Tag', 'metamax' ),
				"param_name"		=> "title_tag",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> array(
					esc_html__( "Default - (H2)", 'metamax' ) 	=> 'h2',
					esc_html__( "H1", 'metamax' ) 				=> 'h1',
					esc_html__( "H2", 'metamax' ) 				=> 'h2',
					esc_html__( "H3", 'metamax' ) 				=> 'h3',
					esc_html__( "H4", 'metamax' ) 				=> 'h4',
					esc_html__( "H5", 'metamax' ) 				=> 'h5',
					esc_html__( "H6", 'metamax' ) 				=> 'h6',
				),
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "add_divider",
				"std"			=> "1",
				"value"			=> array( esc_html__( 'Add Divider', 'metamax' ) => true ),
			),
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Divider Position', 'metamax' ),
                "param_name"	=> "divider_position",
                "responsive"	=> "all",
                "value"			=> array(
                    esc_html__( "Under Title", 'metamax' ) => 'under',
                    esc_html__( "Beside Title", 'metamax' ) => 'beside',
                ),
                "std"           => 'under',
                "dependency"		=> array(
                    "element"	=> "add_divider",
                    "not_empty"	=> true
                ),
            ),
			array(
				"type"			=> "textarea_html",
				"heading"		=> esc_html__( 'Text', 'metamax' ),
				"param_name"	=> "content",
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
		"name"				=> esc_html__( 'CWS Text', 'metamax' ),
		"base"				=> "cws_sc_text",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",		
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Text extends WPBakeryShortCode {
	    }
	}
?>