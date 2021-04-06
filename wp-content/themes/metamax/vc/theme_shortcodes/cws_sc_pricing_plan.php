<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

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
            "type"				=> "checkbox",
            "param_name"		=> "add_border",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "value"				=> array( esc_html__( 'Add Right Border', 'metamax' ) => true ),
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Border Color', 'metamax' ),
            "param_name"		=> "border_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "dependency"		=> array(
                "element"	=> "add_border",
                "not_empty"	=> true
            ),
            "value"				=> "#dedede"
        ),
        array(
            "type"				=> "attach_image",
            "heading"			=> esc_html__( 'Highlighted Item Background Image', 'metamax' ),
            "param_name"		=> "bg_image",
            "dependency"		=> array(
                "element"	=> "highlighted",
                "not_empty"	=> true
            ),
            "group"				=> esc_html__( "Styling", 'metamax' )
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Accent Color Light', 'metamax' ),
            "param_name"		=> "accent_color_light",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "value"				=> "#ffe27a"
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Accent Color Dark', 'metamax' ),
            "param_name"		=> "accent_color_dark",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "value"				=> "#9d5f36"
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
                "admin_label"		=> true,
                "heading"			=> esc_html__( 'Title', 'metamax' ),
                "param_name"		=> "title",
                "value"				=> "Basic"
            ),
        ),
        $icon_params,
        array(
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Price', 'metamax' ),
                "param_name"		=> "price",
                "edit_field_class" 	=> "vc_col-xs-3",
                "value"				=> ""
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Price Сurrency', 'metamax' ),
                "param_name"		=> "currency",
                "edit_field_class" 	=> "vc_col-xs-3",
                "value"				=> "$"
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Price Description', 'metamax' ),
                "param_name"		=> "price_desc",
                "edit_field_class" 	=> "vc_col-xs-3",
                "value"				=> "/mo"
            ),
            array(
                "type"			    => "checkbox",
                "param_name"	    => "add_button",
                "value"			    => array( esc_html__( 'Add Button', 'metamax' ) => true )
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Button Title', 'metamax' ),
                "param_name"		=> "button_title",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> "Buy Now",
                "dependency"	    => array(
                    "element"	    => "add_button",
                    "not_empty"	    => true
                ),
            ),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Button Link', 'metamax' ),
                "param_name"		=> "button_url",
                "edit_field_class" 	=> "vc_col-xs-6",
                "value"				=> "#",
                "dependency"	    => array(
                    "element"	    => "add_button",
                    "not_empty"	    => true
                ),
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "button_new_tab",
                "dependency"	=> array(
                    "element"	=> "add_button",
                    "not_empty"	=> true
                ),
                "value"			=> array( esc_html__( 'Open Link in New Tab', 'metamax' ) => true )
            ),
            array(
                'type' => 'param_group',
                'heading' => esc_html__( 'Values', 'metamax' ),
                'param_name' => 'values',
                'value' => urlencode( json_encode( array(
                    array(
                        'text' 	=> '',
                    ),
                ) ) ),
                'params' => array(
                    array(
                        "type"			=> "textarea",
                        "heading"		=> esc_html__( 'Text Information Row', 'metamax' ),
                        "param_name"	=> "text",
                    ),
                ),
            ),
            array(
                "type"				=> "checkbox",
                "param_name"		=> "highlighted",
                "value"				=> array( esc_html__( 'Highlight this item', 'metamax' ) => true ),
            ),
            array(
                "type"				=> "textarea_html",
                "heading"			=> esc_html__( 'Additional Text', 'metamax' ),
                "param_name"		=> "content",
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
		"name"				=> esc_html__( 'CWS Pricing Plan', 'metamax' ),
		"base"				=> "cws_sc_pricing_plan",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Pricing_Plan extends WPBakeryShortCode {
	    }
	}
?>