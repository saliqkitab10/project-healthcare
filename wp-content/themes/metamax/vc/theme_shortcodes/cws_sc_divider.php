<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	/* -----> GET ICON CONFIG <----- */
	$icon_params = cws_ext_icon_vc_sc_config_params("separate_type", false, array( "iconic" ) );

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
			"param_name"		=> "custom_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> 'all',
			"dependency"		=> array(
				"element"	=> "type",
				"value"		=> "advanced",
				"resize"	=> false
			),
			"value"				=> array( esc_html__( 'Custom Sizes', 'metamax' ) => true ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon size / Image width', 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"param_name"		=> "icon_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
			"value"				=> "21"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon / Image Spacings', 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"param_name"		=> "icon_spacings",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
			"value"				=> "35"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Divider Color', 'metamax' ),
			"param_name"		=> "divider_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $theme_first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Color', 'metamax' ),
			"param_name"		=> "icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $theme_first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Inner Divider Color', 'metamax' ),
			"param_name"		=> "inner_divider_color",
			"dependency"		=> array(
				"element"	=> "type",
				"value"		=> "double"
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $theme_first_color
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
				"heading"		=> esc_html__( 'Type', 'metamax' ),
				"param_name"	=> "type",
				"value"			=> array(
					esc_html__( 'Simple', 'metamax' ) 	=> 'simple',
					esc_html__( 'Double', 'metamax' ) 	=> 'double',
					esc_html__( 'Dashed', 'metamax' ) 	=> 'dashed',
					esc_html__( 'Advanced', 'metamax' )	=> 'advanced',
				),
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Divider Height', 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-6",
				"param_name"		=> "height",
				"description"		=> esc_html__( 'In pixels', 'metamax' ),
				"value"				=> "1"
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Inner Divider Width', 'metamax' ),
				"edit_field_class" 	=> "vc_col-xs-6",
				"param_name"		=> "width",
				"description"		=> esc_html__( 'In percents', 'metamax' ),
				"dependency"		=> array(
					"element"	=> "type",
					"value"		=> "double"
				),
				"description"		=> esc_html__( 'Input value with unit', 'metamax' ),
				"value"				=> "15%"
			),
			array(
				"type"			=> "dropdown",
				"heading"		=> esc_html__( 'Separate Type', 'metamax' ),
				"param_name"	=> "separate_type",
				"dependency"	=> array(
					"element"	=> "type",
					"value"		=> "advanced"
				),
				"value"			=> array(
					esc_html__( 'Icon', 'metamax' )		=> 'iconic',
					esc_html__( 'Image', 'metamax' )	=> 'image',
				) 
			),
			array(
				"type"			=> "attach_image",
				"heading"		=> esc_html__( 'Image', 'metamax' ),
				"param_name"	=> "divider_image",
				"dependency"	=> array(
					"element"	=> "separate_type",
					"value"		=> "image"
				),
			),
		),
		$icon_params,
		array(
			array(
				"type"			=> "dropdown",
				"heading"		=> esc_html__( 'Icon Size', 'metamax' ),
				"param_name"	=> "size",
				"dependency"	=> array(
					"element"	=> "separate_type",
					"value"		=> "iconic"
				),
				"value"			=> array(
					esc_html__( 'Mini', 'metamax' ) 		=> '1x',
					esc_html__( 'Small', 'metamax' ) 		=> '2x',
					esc_html__( 'Medium', 'metamax' ) 		=> '3x',
					esc_html__( 'Large', 'metamax' )		=> '4x',
					esc_html__( 'Extra Large', 'metamax' )	=> '5x',
				),
				"std"			=> "3x"
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
		"name"				=> esc_html__( 'CWS Divider', 'metamax' ),
		"base"				=> "cws_sc_divider",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Divider extends WPBakeryShortCode {
	    }
	}
?>