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
			"responsive"	=> 'all'
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "custom_color",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Custom Colors', 'metamax' ) => true ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Background Color', 'metamax' ),
			"param_name"		=> "bg_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#d2eaff"
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
            "edit_field_class" 	=> "vc_col-xs-4",
            "value"				=> "#fff"
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Background Color', 'metamax' ),
            "param_name"		=> "icon_bg_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "edit_field_class" 	=> "vc_col-xs-4",
            "value"				=> "#5cade5"
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
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#1c8ad5"
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
				"heading"		=> esc_html__( 'Type', 'metamax' ),
				"param_name"	=> "type",
				"value"			=> array(
					esc_html__( 'Informational', 'metamax' )	=> 'info',
					esc_html__( 'Warning', 'metamax' )			=> 'warn',
					esc_html__( 'Success', 'metamax' )			=> 'success',
					esc_html__( 'Error', 'metamax' )			=> 'error',
				)
			),
			array(
				"type"				=> "textfield",
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
				"param_name"		=> "closable",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> array( esc_html__( 'Closable', 'metamax' ) => true ),
				"std"				=> '1'
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
		array(
			array(
				"type"			=> "checkbox",
				"param_name"	=> "hide_icon",
				"group"			=> $mobile_group,
				"value"			=> array( esc_html__( 'Hide Icon', 'metamax' ) => true ),
			),
		)
	));

	vc_map( array(
		"name"				=> esc_html__( 'CWS Message Box', 'metamax' ),
		"base"				=> "cws_sc_msg_box",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Msg_Box extends WPBakeryShortCode {
	    }
	}
?>