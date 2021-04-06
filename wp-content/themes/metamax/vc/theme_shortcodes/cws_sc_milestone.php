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
			"param_name"	=> "customize_position",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"value"			=> array( esc_html__( 'Customize Elements Position', 'metamax' ) => true ),
		),
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Module Alignment', 'metamax' ),
			"param_name"		=> "module_align",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_position",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'Left', 'metamax' ) 		=> 'left',
				esc_html__( 'Center', 'metamax' ) 		=> 'center',
				esc_html__( 'Right', 'metamax' ) 		=> 'right',
			),
			"std"				=> "center"
		),
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Title & Description Position', 'metamax' ),
			"param_name"		=> "info_pos",
			"responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "customize_position",
				"not_empty"	=> true
			),
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'Top', 'metamax' ) 		    => 'top',
				esc_html__( 'Middle', 'metamax' ) 		=> 'middle',
				esc_html__( 'Bottom', 'metamax' ) 		=> 'bottom',
			),
			"std"				=> "middle"
		),

		array(
			"type"			=> "checkbox",
			"param_name"	=> "custom_size",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"value"			=> array( esc_html__( 'Customize Sizes', 'metamax' ) => true ),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Icon Size', 'metamax' ),
			"param_name"		=> "icon_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"value"				=> "60px"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Title Size', 'metamax' ),
			"param_name"		=> "title_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"value"				=> "14px"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Description Size', 'metamax' ),
			"param_name"		=> "description_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"value"				=> "14px"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Number Size', 'metamax' ),
			"param_name"		=> "number_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"responsive"		=> "all",
			"edit_field_class" 	=> "vc_col-xs-3",
			"dependency"		=> array(
				"element"	=> "custom_size",
				"not_empty"	=> true
			),
			"value"				=> "45px"
		),
        array(
            "type"				=> "textfield",
            "heading"			=> esc_html__( 'Number Superscript Text Size', 'metamax' ),
            "param_name"		=> "superscript_size",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "responsive"		=> "all",
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_size",
                "not_empty"	=> true
            ),
            "value"				=> "30px"
        ),
        array(
            "type"				=> "textfield",
            "heading"			=> esc_html__( 'Title Paddings', 'metamax' ),
            "param_name"		=> "title_paddings",
            "responsive"		=> "all",
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_size",
                "not_empty"	=> true
            ),
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "value"				=> "30px 0px 0px 0px"
        ),

		array(
			"type"			=> "checkbox",
			"param_name"	=> "custom_color",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Customize Colors', 'metamax' ) => true ),
			"std"			=> '1'
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icon Color', 'metamax' ),
			"param_name"		=> "icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"value"				=> $theme_first_color
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Icon Color on Hover', 'metamax' ),
            "param_name"		=> "icon_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Number Color', 'metamax' ),
			"param_name"		=> "number_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"value"				=> $theme_first_color
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Number Color on Hover', 'metamax' ),
            "param_name"		=> "number_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"value"				=> "#000"
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Title Color on Hover', 'metamax' ),
            "param_name"		=> "title_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Description Color', 'metamax' ),
			"param_name"		=> "description_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"value"				=> "#000"
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Description Color on Hover', 'metamax' ),
            "param_name"		=> "description_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "value"				=> ""
        ),

        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Divider Color', 'metamax' ),
            "param_name"		=> "divider_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
            "value"				=> $theme_second_color
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Background Color on Hover', 'metamax' ),
            "param_name"		=> "bg_color_hover",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "dependency"		=> array(
                "element"	=> "custom_color",
                "not_empty"	=> true
            ),
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
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Title', 'metamax' ),
				"param_name"		=> "title",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "Enter title here..."
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Description', 'metamax' ),
				"param_name"		=> "description",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "Enter description here..."
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Count To (number)', 'metamax' ),
				"param_name"		=> "number",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "99"
			),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Number superscript text', 'metamax' ),
                "param_name"		=> "superscript",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> "+"
            ),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Count Speed', 'metamax' ),
				"param_name"		=> "speed",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> "2000"
			),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "add_divider",
                "responsive"	=> "all",
                "value"			=> array( esc_html__( 'Add Divider', 'metamax' ) => true ),
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "hide_divider_hover",
                "responsive"	=> "all",
                "value"			=> array( esc_html__( 'Hide Divider on Hover', 'metamax' ) => true ),
                "dependency"		=> array(
                    "element"	    => "add_divider",
                    "not_empty"		=> true
                ),
                "std"               => true
            ),
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Divider Position', 'metamax' ),
                "param_name"		=> "divider_position",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> array(
                    esc_html__( 'Under Icon', 'metamax' ) 	    => 'under-icon',
                    esc_html__( 'Under Title', 'metamax' ) 	    => 'under-title',
                    esc_html__( 'Under Counter', 'metamax' ) 	=> 'under-counter',
                ),
                "dependency"		=> array(
                    "element"	    => "add_divider",
                    "not_empty"		=> true
                ),
                "std"               => "under_title"
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
		"name"				=> esc_html__( 'CWS Milestone', 'metamax' ),
		"base"				=> "cws_sc_milestone",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Milestone extends WPBakeryShortCode {
	    }
	}
?>