<?php
    // Map Shortcode in Visual Composer
    global $cws_theme_funcs;

    /* -----> THEME OPTIONS PROPERTIES <----- */
    $theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

    /* -----> STYLING GROUP TITLES <----- */
    $landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
    $portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
    $mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

    /* -----> STYLING TAB PROPERTIES <----- */
    $styles = cws_ext_merge_arrs( array(
        array(
            array(
                "type"			=> "css_editor",
                "param_name"	=> "custom_styles",
                "group"			=> esc_html__( "Styling", 'metamax' ),
                "responsive"	=> 'all'
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "customize_align",
                "group"			=> esc_html__( "Styling", "metamax" ),
                "responsive"	=> "all",
                "value"			=> array( esc_html__( 'Customize Alignment', 'metamax' ) => true ),
            ),
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Alignment', 'metamax' ),
                "group"			=> esc_html__( "Styling", 'metamax' ),
                "param_name"	=> "alignment",
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
                "std"			=> 'center',
            ),
        )
    ));

    /* -----> RESPONSIVE STYLING TABS PROPERTIES <----- */
    $styles_landscape = $styles_portrait = $styles_mobile = $styles;

    $styles_landscape =  $cws_theme_funcs->cws_responsive_styles($styles_landscape, 'landscape', $landscape_group);
    $styles_portrait =  $cws_theme_funcs->cws_responsive_styles($styles_portrait, 'portrait', $portrait_group);
    $styles_mobile =  $cws_theme_funcs->cws_responsive_styles($styles_mobile, 'mobile', $mobile_group);

    $params = cws_ext_merge_arrs( array(
        /* -----> GENERAL TAB <----- */
        array(
            array(
                "type"				=> "attach_image",
                "heading"			=> esc_html__( 'Image', 'metamax' ),
                "param_name"		=> "image",
                "edit_field_class" 	=> "vc_col-xs-4",
            ),
            array(
                "type"				=> "attach_image",
                "heading"			=> esc_html__( 'Active Image', 'metamax' ),
                "param_name"		=> "image_active",
                "dependency"		=> array(
                    "element"	=> "bg_hover",
                    "value"	    => "roll-down"
                ),
                "edit_field_class" 	=> "vc_col-xs-4",
            ),
            array(
                "type"			=> "dropdown",
                "heading"		=> esc_html__( 'Thumbnail size', 'metamax' ),
                "param_name"	=> "thumbnail_size",
                "value"         => array(
                    esc_html__( 'Full', 'metamax' )      => 'full',
                    esc_html__( 'Large', 'metamax' )     => 'large',
                    esc_html__( 'Medium', 'metamax' )    => 'medium',
                    esc_html__( 'Thumbnail', 'metamax' ) => 'thumbnail',
                ),
                "edit_field_class" 	=> "vc_col-xs-4",
            ),
            array(
                "type"				=> "dropdown",
                "heading"			=> esc_html__( 'Image Hover Effect', 'metamax' ),
                "param_name"		=> "bg_hover",
                "value"				=> array(
                    esc_html__( 'No Hover', 'metamax' )		=> 'no-hover',
                    esc_html__( 'Roll Down', 'metamax' )	=> 'roll-down',
                ),
                "std"				=> 'no-hover',
                "admin_label"		=> true
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

    /* -----> MODULE DECLARATION <----- */
    vc_map( array(
        "name"				=> esc_html__( 'CWS Image', 'metamax' ),
        "base"				=> "cws_sc_image",
        "category"			=> "By CWS",
        "icon" 				=> "cws_icon",
        "weight"			=> 80,
        "params"			=> $params
    ));

    if ( class_exists( 'WPBakeryShortCode' ) ) {
        class WPBakeryShortCode_CWS_Sc_Image extends WPBakeryShortCode {
        }
    }
?>