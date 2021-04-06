<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> GET ICON CONFIG <----- */
	$icon_params = cws_ext_icon_vc_sc_config_params ();

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
			"description"		=> esc_html__( 'Default desktop paddings is 65/15/65/15', 'metamax' ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Spacing between info', 'metamax' ),
			"param_name"		=> "spacing",
			"responsive"		=> 'all',
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> "100",
			"description"		=> esc_html__( 'In pixels', 'metamax' ),
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "custom_color",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Custom Colors', 'metamax' ) => true ),
			"std"			=> "1",
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Color', 'metamax' ),
			"param_name"		=> "icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#208de2"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Background', 'metamax' ),
			"param_name"		=> "icon_background",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#fff"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#fff"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Active Title Color', 'metamax' ),
			"param_name"		=> "active_title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#000"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Divider Color', 'metamax' ),
			"param_name"		=> "divider_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#208de2"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Text Color', 'metamax' ),
			"param_name"		=> "text_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#474747"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Button Title', 'metamax' ),
			"param_name"		=> "button_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#fff"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Button Background', 'metamax' ),
			"param_name"		=> "button_bg_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#208de2"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Hover Button Title', 'metamax' ),
			"param_name"		=> "hover_button_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#208de2"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Hover Button Background', 'metamax' ),
			"param_name"		=> "hover_button_bg_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#fff"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Shadow Color', 'metamax' ),
			"param_name"		=> "shadow_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"value"				=> "rgba(12,81,172,0.35)"
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
				"heading"		=> esc_html__( 'Icon Type', 'metamax' ),
				"param_name"	=> "type",
				"value"			=> array(
					esc_html__( 'Simple', 'metamax' ) 		=> 'simple',
					esc_html__( 'Bordered', 'metamax' ) 	=> 'bordered'
				),
			),
			array(
				"type"				=> "textarea",
				"admin_label"		=> true,
				"heading"			=> esc_html__( 'Title', 'metamax' ),
				"param_name"		=> "title",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "Enter title here..."
			),
			array(
				"type"				=> "textarea",
				"heading"			=> esc_html__( 'Description', 'metamax' ),
				"param_name"		=> "description",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "Enter description here..."
			),
			array(
				"type"				=> "checkbox",
				"param_name"		=> "highlighted",
				"value"				=> array( esc_html__( 'Highlighted', 'metamax' ) => true ),
			),
			array(
				"type"				=> "checkbox",
				"param_name"		=> "add_button",
				"value"				=> array( esc_html__( 'Add Button', 'metamax' ) => true ),
				"std"				=> '1'
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Button Title', 'metamax' ),
				"param_name"		=> "button_title",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "Read More"
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Button URL', 'metamax' ),
				"param_name"		=> "button_url",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "#"
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
		$styles_mobile,
	));

	vc_map( array(
		"name"				=> esc_html__( 'CWS Benefits', 'metamax' ),
		"base"				=> "cws_sc_benefits",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Benefits extends WPBakeryShortCode {
	    }
	}
?>