<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$first_color 	= esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$second_color 	= esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );
    $body_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

	/* -----> STYLING GROUP TITLES <----- */
	$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
	$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
	$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

    /* -----> GET ICON CONFIG <----- */
    $icon_params_1 = cws_ext_icon_vc_sc_config_params_multiple ('1');
    $icon_params_2 = cws_ext_icon_vc_sc_config_params_multiple ('2');
    $icon_params_3 = cws_ext_icon_vc_sc_config_params_multiple ('3');
    $icon_params_4 = cws_ext_icon_vc_sc_config_params_multiple ('4');
    $icon_params_5 = cws_ext_icon_vc_sc_config_params_multiple ('5');

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
			"param_name"		=> "custom_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"value"				=> array( esc_html__( 'Custom Colors', 'metamax' ) => true ),
			"std"				=> "1"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'TimeLine Color', 'metamax' ),
			"param_name"		=> "timeline_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-3",
			"value"				=> "#a7cbeb"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Label Color', 'metamax' ),
			"param_name"		=> "label_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-3",
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
			"edit_field_class" 	=> "vc_col-xs-3",
			"value"				=> $body_color
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
            "edit_field_class" 	=> "vc_col-xs-3",
            "value"				=> "#fff"
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
			"edit_field_class" 	=> "vc_col-xs-3",
			"value"				=> $body_color
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
                'type' 			=> 'param_group',
                'heading' 		=> esc_html__( 'Values', 'metamax' ),
                'param_name' 	=> 'values',
                'description' 	=> 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.',
                'params' => cws_ext_merge_arrs( array(

                    array(
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Label-1', 'metamax' ),
                            "param_name"		=> "label-1",
                            "edit_field_class" 	=> "vc_col-xs-3",
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Label-2', 'metamax' ),
                            "param_name"		=> "label-2",
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Label-3', 'metamax' ),
                            "param_name"		=> "label-3",
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Label-4', 'metamax' ),
                            "param_name"		=> "label-4",
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Label-5', 'metamax' ),
                            "param_name"		=> "label-5",
                            "edit_field_class" 	=> "vc_col-xs-3",
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Title-1', 'metamax' ),
                            "param_name"		=> "title-1",
                            "edit_field_class" 	=> "vc_col-xs-3",
                            'admin_label' 		=> true,
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Title-2', 'metamax' ),
                            "param_name"		=> "title-2",
                            "edit_field_class" 	=> "vc_col-xs-2",
                            'admin_label' 		=> true,
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Title-3', 'metamax' ),
                            "param_name"		=> "title-3",
                            "edit_field_class" 	=> "vc_col-xs-2",
                            'admin_label' 		=> true,
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Title-4', 'metamax' ),
                            "param_name"		=> "title-4",
                            "edit_field_class" 	=> "vc_col-xs-2",
                            'admin_label' 		=> true,
                        ),
                        array(
                            "type"				=> "textfield",
                            "heading"			=> esc_html__( 'Title-5', 'metamax' ),
                            "param_name"		=> "title-5",
                            "edit_field_class" 	=> "vc_col-xs-3",
                            'admin_label' 		=> true,
                        ),
                        array(
                            "type"				=> "textarea",
                            "heading"			=> esc_html__( 'Description-1', 'metamax' ),
                            "param_name"		=> "description-1",
                            "edit_field_class" 	=> "vc_col-xs-3",
                        ),
                        array(
                            "type"				=> "textarea",
                            "heading"			=> esc_html__( 'Description-2', 'metamax' ),
                            "param_name"		=> "description-2",
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "textarea",
                            "heading"			=> esc_html__( 'Description-3', 'metamax' ),
                            "param_name"		=> "description-3",
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "textarea",
                            "heading"			=> esc_html__( 'Description-4', 'metamax' ),
                            "param_name"		=> "description-4",
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "textarea",
                            "heading"			=> esc_html__( 'Description-5', 'metamax' ),
                            "param_name"		=> "description-5",
                            "edit_field_class" 	=> "vc_col-xs-3",
                        ),
                        array(
                            "type"				=> "checkbox",
                            "param_name"		=> "end_point-1",
                            "value"				=> array( esc_html__( 'End Point', 'metamax' ) => true ),
                            "description"		=> esc_html__( 'Should be only one end point in whole module', 'metamax' ),
                            "edit_field_class" 	=> "vc_col-xs-3",
                        ),
                        array(
                            "type"				=> "checkbox",
                            "param_name"		=> "end_point-2",
                            "value"				=> array( esc_html__( 'End Point', 'metamax' ) => true ),
                            "description"		=> esc_html__( 'Should be only one end point in whole module', 'metamax' ),
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "checkbox",
                            "param_name"		=> "end_point-3",
                            "value"				=> array( esc_html__( 'End Point', 'metamax' ) => true ),
                            "description"		=> esc_html__( 'Should be only one end point in whole module', 'metamax' ),
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "checkbox",
                            "param_name"		=> "end_point-4",
                            "value"				=> array( esc_html__( 'End Point', 'metamax' ) => true ),
                            "description"		=> esc_html__( 'Should be only one end point in whole module', 'metamax' ),
                            "edit_field_class" 	=> "vc_col-xs-2",
                        ),
                        array(
                            "type"				=> "checkbox",
                            "param_name"		=> "end_point-5",
                            "value"				=> array( esc_html__( 'End Point', 'metamax' ) => true ),
                            "description"		=> esc_html__( 'Should be only one end point in whole module', 'metamax' ),
                            "edit_field_class" 	=> "vc_col-xs-3",
                        ),
                    ),
                    $icon_params_1,
                    $icon_params_2,
                    $icon_params_3,
                    $icon_params_4,
                    $icon_params_5,
                    array(
                        array(
                            "type"				=> "colorpicker",
                            "heading"			=> esc_html__( 'Item Color-1', 'metamax' ),
                            "param_name"		=> "item_color-1",
                            "edit_field_class" 	=> "vc_col-xs-3",
                            "value"				=> $first_color
                        ),
                        array(
                            "type"				=> "colorpicker",
                            "heading"			=> esc_html__( 'Item Color-2', 'metamax' ),
                            "param_name"		=> "item_color-2",
                            "edit_field_class" 	=> "vc_col-xs-2",
                            "value"				=> $first_color
                        ),
                        array(
                            "type"				=> "colorpicker",
                            "heading"			=> esc_html__( 'Item Color-3', 'metamax' ),
                            "param_name"		=> "item_color-3",
                            "edit_field_class" 	=> "vc_col-xs-2",
                            "value"				=> $first_color
                        ),
                        array(
                            "type"				=> "colorpicker",
                            "heading"			=> esc_html__( 'Item Color-4', 'metamax' ),
                            "param_name"		=> "item_color-4",
                            "edit_field_class" 	=> "vc_col-xs-2",
                            "value"				=> $first_color
                        ),
                        array(
                            "type"				=> "colorpicker",
                            "heading"			=> esc_html__( 'Item Color-5', 'metamax' ),
                            "param_name"		=> "item_color-5",
                            "edit_field_class" 	=> "vc_col-xs-3",
                            "value"				=> $first_color
                        ),
                    ),
                ) ),
				'value' 		=> array()
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
		"name"				=> esc_html__( 'CWS Roadmap', 'metamax' ),
		"base"				=> "cws_sc_roadmap",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Roadmap extends WPBakeryShortCode {
	    }
	}
?>