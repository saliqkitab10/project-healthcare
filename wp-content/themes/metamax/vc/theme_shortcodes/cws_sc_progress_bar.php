<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	vc_map( array(
		"name"				=> esc_html__( 'CWS Progress Bar', 'metamax' ),
		"base"				=> "cws_sc_progress_bar",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> array(
			array(
				"type"			=> "textfield",
				"admin_label"	=> true,
				"heading"		=> esc_html__( 'Title', 'metamax' ),
				"param_name"	=> "title",
				"value"			=> "Work`s done"
			),
			array(
				"type"			=> "textfield",
				"heading"		=> esc_html__( 'Progress', 'metamax' ),
				"description"	=> esc_html__( 'In Percents', 'metamax' ),
				"param_name"	=> "progress",
				"value"			=> "65",
			),
			array(
				"type"			=> "checkbox",
				"param_name"	=> "use_custom_color",
				"value"			=> array( esc_html__( 'Use Custom Colors', 'metamax' ) => true )
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Title Color', 'metamax' ),
				"param_name"		=> "custom_title_color",
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "use_custom_color",
					"not_empty"	=> true
				),
				"value"				=> "#000"
			),
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Percents Color', 'metamax' ),
				"param_name"		=> "custom_percents_color",
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "use_custom_color",
					"not_empty"	=> true
				),
				"value"				=> "#000"
			),			
			array(
				"type"				=> "colorpicker",
				"heading"			=> esc_html__( 'Fill Color', 'metamax' ),
				"param_name"		=> "custom_fill_color",
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency"		=> array(
					"element"	=> "use_custom_color",
					"not_empty"	=> true
				),
				"value"				=> $theme_first_color
			),				
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Extra class name', 'metamax' ),
				"description"		=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
				"param_name"		=> "el_class",
				"value"				=> ""
			)
		)
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Progress_Bar extends WPBakeryShortCode {
	    }
	}
?>