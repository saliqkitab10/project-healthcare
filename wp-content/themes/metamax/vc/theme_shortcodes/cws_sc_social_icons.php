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
    $styles = array(
        array(
            "type"          => "css_editor",
            "param_name"    => "custom_styles",
            "group"         => esc_html__( "Styling", 'metamax' ),
            "responsive"    => 'all',
        ),
        array(
            "type"          => "checkbox",
            "param_name"    => "customize_align",
            "group"         => esc_html__( "Styling", 'metamax' ),
            "responsive"    => "all",
            "value"         => array( esc_html__( 'Customize Alignment', 'metamax' ) => true ),
        ),
        array(
            "type"          => "dropdown",
            "heading"       => esc_html__( 'Module Aligning', 'metamax' ),
            "param_name"    => "aligning",
            "group"         => esc_html__( "Styling", 'metamax' ),
            "responsive"    => "all",
            "dependency"    => array(
                "element"   => "customize_align",
                "not_empty" => true
            ),
            "value"         => array(
                esc_html__( 'Left', 'metamax' )     => 'left',
                esc_html__( 'Center', 'metamax' )   => 'center',
                esc_html__( 'Right', 'metamax' )    => 'right',
            )
        ),
        array(
            "type"              => "checkbox",
            "param_name"        => "custom_size",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "responsive"        => 'all',
            "value"             => array( esc_html__( 'Custom Sizes', 'metamax' ) => true ),
        ),
        array(
            "type"              => "textfield",
            "heading"           => esc_html__( 'Icon Size', 'metamax' ),
            "param_name"        => "custom_icon_size",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "responsive"        => "all",
            "dependency"        => array(
                "element"   => "custom_size",
                "not_empty" => true
            ),
            "description"       => esc_html__( 'In pixels', 'metamax' ),
            "value"             => "38"
        ),
        array(
            "type"              => "checkbox",
            "param_name"        => "custom_color",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "value"             => array( esc_html__( 'Custom Colors', 'metamax' ) => true ),
            "std"               => '1'
        ),
        array(
            "type"              => "colorpicker",
            "heading"           => esc_html__( 'Icon', 'metamax' ),
            "param_name"        => "icon_color",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "dependency"        => array(
                "element"   => "custom_color",
                "not_empty" => true
            ),
            "edit_field_class"  => "vc_col-xs-6",
            "value"             => $theme_first_color
        ),
        array(
            "type"              => "colorpicker",
            "heading"           => esc_html__( 'Icon Hover', 'metamax' ),
            "param_name"        => "icon_color_hover",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "dependency"        => array(
                "element"   => "custom_color",
                "not_empty" => true
            ),
            "edit_field_class"  => "vc_col-xs-6",
            "value"             => "#fff"
        ),
        array(
            "type"              => "colorpicker",
            "heading"           => esc_html__( 'Icon Background', 'metamax' ),
            "param_name"        => "icon_bg_color",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "dependency"        => array(
                "element"   => "custom_color",
                "not_empty" => true
            ),
            "edit_field_class"  => "vc_col-xs-6",
            "value"             => "#fff"
        ),
        array(
            "type"              => "colorpicker",
            "heading"           => esc_html__( 'Icon Background Hover', 'metamax' ),
            "param_name"        => "icon_bg_color_hover",
            "group"             => esc_html__( "Styling", 'metamax' ),
            "dependency"        => array(
                "element"   => "custom_color",
                "not_empty" => true
            ),
            "edit_field_class"  => "vc_col-xs-6",
            "value"             => $theme_first_color
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
                'type' => 'param_group',
                'heading' => esc_html__( 'Values', 'metamax' ),
                'param_name' => 'values',
                'description' => esc_html__( 'Enter values for graph - value, title and color.', 'metamax' ),
                'value' => urlencode( json_encode( array(
                    array(
                        'link' => 'https://www.facebook.com/',
                        'icon' => 'fab fa-facebook',
                        'title' => esc_html__( 'Facebook', 'metamax' ),
                        'new_tab' => true,
                    ),
                    array(
                        'link' => 'https://twitter.com/',
                        'icon' => 'fab fa-twitter',
                        'title' => esc_html__( 'Twitter', 'metamax' ),
                        'new_tab' => true,
                    ),
                    array(
                        'link' => 'https://www.instagram.com/',
                        'icon' => 'fab fa-instagram',
                        'title' => esc_html__( 'Instagram', 'metamax' ),
                        'new_tab' => true,
                    ),
                ) ) ),
                'params' => array(
                    array(
                        'type' => 'iconpicker',
                        'heading' => esc_html__( 'Icon', 'metamax' ),
                        'param_name' => 'icon',
                        'value' => 'fas fa-adjust', // default value to backend editor admin_label
                        'settings' => array(
                            'emptyIcon' => true,
                            // default true, display an "EMPTY" icon?
                            'iconsPerPage' => 200,
                            // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
                        ),
                        'description' => esc_html__( 'Select icon from library.', 'metamax' ),
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__( 'Link', 'metamax' ),
                        'param_name' => 'link',
                        'admin_label' => true,
                    ),
                    array(
                        'type' => 'textfield',
                        'heading' => esc_html__( 'Title', 'metamax' ),
                        'param_name' => 'title',
                        'admin_label' => true,
                    ),
                    array(
                        "type"          => "checkbox",
                        "param_name"    => "new_tab",
                        "std"           => true,
                        "value"         => array( esc_html__( 'Open in New Tab', 'metamax' ) => true )
                    ),
                ),
            ),
            array(
                "type"              => "dropdown",
                "heading"           => esc_html__( 'Shape', 'metamax' ),
                "param_name"        => "icon_shape",
                "edit_field_class"  => "vc_col-xs-6",
                "value"             => array(
                    esc_html__( 'None', 'metamax' )     => 'none',
                    esc_html__( 'Rounded', 'metamax' )  => 'rounded',
                    esc_html__( 'Circle', 'metamax' )    => 'circle',
                    esc_html__( 'Hexagon', 'metamax' )  => 'hexagon',
                ),
            ),
            array(
                "type"              => "textfield",
                "heading"           => esc_html__( 'Extra class name', 'metamax' ),
                "description"       => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
                "param_name"        => "el_class",
                "value"             => ""
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
        "name"              => esc_html__( 'CWS Social Icons', 'metamax' ),
        "base"              => "cws_sc_social_icons",
        'category'          => "By CWS",
        "weight"            => 80,
        'icon'              => 'cws_icon',
        "params"            => $params
    ));

    if ( class_exists( 'WPBakeryShortCode' ) ) {
        class WPBakeryShortCode_CWS_Sc_Social_Icons extends WPBakeryShortCode {
        }
    }

?>