<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$first_color 	= esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$second_color 	= esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

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
            "type"          => "dropdown",
            "heading"       => esc_html__( 'Module Aligning', 'metamax' ),
            "param_name"    => "aligning",
            "group"         => esc_html__( "Styling", 'metamax' ),
            "value"         => array(
                esc_html__( 'Left', 'metamax' )     => 'left',
                esc_html__( 'Center', 'metamax' )   => 'center',
                esc_html__( 'Right', 'metamax' )    => 'right',
            )
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
			"heading"			=> esc_html__( 'Author Name', 'metamax' ),
			"param_name"		=> "name_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#000"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Author position', 'metamax' ),
			"param_name"		=> "pos_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> $first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Quote', 'metamax' ),
			"param_name"		=> "quote_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#474747"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Dots Color', 'metamax' ),
			"param_name"		=> "dots_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#D5D5D5"
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Dots Active Color', 'metamax' ),
			"param_name"		=> "dots_active_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> $first_color
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Dots Active Border', 'metamax' ),
			"param_name"		=> "dots_active_border",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"dependency"		=> array(
				"element"	=> "custom_color",
				"not_empty"	=> true
			),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> $second_color
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
				"heading"		=> esc_html__( 'Testimonials Style', 'metamax' ),
				"param_name"	=> "testimonials_style",
				"value"			=> array(
					esc_html__( 'Style 1', 'metamax' )		=> 'style-1',
					esc_html__( 'Style 2', 'metamax' )		=> 'style-2',
					esc_html__( 'Style 3', 'metamax' )		=> 'style-3',
				),
			),
			array(
                'type' => 'param_group',
                'heading' => esc_html__( 'Values', 'metamax' ),
                'param_name' => 'values',
                'description' => esc_html__( 'Enter values for graph - thumbnail, quote, author name and author position.', 'metamax' ),
                'value' => urlencode( json_encode( array(
                    array(
                        'thumbnail' 		    => '',
                        'thumbnail_size'        => 'full',
                        'quote' 			    => '',
                        'author_name' 		    => 'John Doe',
                        'author_pos' 		    => '',
                        'testimonial_rating' 	=> '',
                    ),
                    array(
                        'thumbnail' 		    => '',
                        'thumbnail_size'        => 'full',
                        'quote' 			    => '',
                        'author_name' 		    => 'Jane Doe',
                        'author_pos' 		    => '',
                        'testimonial_rating' 	=> '',
                    ),
                    array(
                        'thumbnail' 		    => '',
                        'thumbnail_size'        => 'full',
                        'quote' 			    => '',
                        'author_name' 		    => 'John Doe',
                        'author_pos' 		    => '',
                        'testimonial_rating' 	=> '',
                    ),
                ) ) ),
                'params' => array(
		            array(
						"type"			    => "attach_image",
						"heading"		    => esc_html__( 'Thumbnail', 'metamax' ),
						"param_name"	    => "thumbnail",
                        "edit_field_class" 	=> "vc_col-xs-6",
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
                        "edit_field_class" 	=> "vc_col-xs-6",
                    ),
					array(
						"type"			=> "textarea",
						"heading"		=> esc_html__( 'Quote', 'metamax' ),
						"param_name"	=> "quote",
					),
					array(
						"type"			=> "textfield",
						"heading"		=> esc_html__( 'Author Name', 'metamax' ),
						"param_name"	=> "author_name",
                        'admin_label' 	=> true,
					),
					array(
						"type"			=> "textfield",
						"heading"		=> esc_html__( 'Author Position', 'metamax' ),
						"param_name"	=> "author_pos",
                        'admin_label' 	=> true,
					),
                    array(
                        "type"			=> "checkbox",
                        "param_name"	=> "show_rating",
                        "value"         => array( esc_html__( 'Show rating', 'metamax' ) => true ),
                    ),
                    array(
                        "type"			=> "dropdown",
                        "heading"		=> esc_html__( 'Testimonial Rating', 'metamax' ),
                        "param_name"	=> "testimonial_rating",
                        "value"         => array(
                            esc_html__( 'Very bad', 'metamax' )     => '0',
                            esc_html__( 'Bad', 'metamax' )          => '1',
                            esc_html__( 'Poor', 'metamax' )         => '2',
                            esc_html__( 'Fair', 'metamax' )         => '3',
                            esc_html__( 'Good', 'metamax' )         => '4',
                            esc_html__( 'Excellent', 'metamax' )    => '5',
                        ),
                        "dependency"	=> array(
                            "element"	=> "show_rating",
                            "not_empty"	=> true
                        ),
                    ),
                ),
            ),
			array(
                "type"          => "dropdown",
                "heading"       => esc_html__( 'Testimonials Grid', 'metamax' ),
                "param_name"    => "item_grid",
                "dependency"	=> array(
					"element"	=> "testimonials_style",
					"value"		=> "style-1"
				),
                "value"         => array(
                    esc_html__( 'One Column', 'metamax' )    => '1',
                    esc_html__( 'Two Columns', 'metamax' )   => '2',
                    esc_html__( 'Three Columns', 'metamax' ) => '3',
                ),              
            ),
            array(
                "type"          => "checkbox",
                "param_name"    => "use_carousel",
                "value"         => array( esc_html__( 'Use Carousel', 'metamax' ) => true ),
                "std"           => "1",
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "carousel_infinite",
                'dependency'		=> array(
                    'element'	=> 'use_carousel',
                    'not_empty'		=> true
                ),
                "value"			=> array( esc_html__( 'Infinite Loop', 'metamax' ) => true )
            ),
            array(
                "type"          => "checkbox",
                "param_name"    => "autoplay",
                "value"         => array( esc_html__( 'Autoplay', 'metamax' ) => true ),
                "dependency"	=> array(
					"element"	=> "use_carousel",
					"not_empty"	=> true
				),
            ),
			array(
				"type"			=> "textfield",
				"heading"		=> esc_html__( 'Autoplay Speed', 'metamax' ),
				"param_name"	=> "autoplay_speed",
                "dependency"	=> array(
					"element"	=> "autoplay",
					"not_empty"	=> true
				),
				"value" 		=> "3000"
			),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "pause_on_hover",
                "dependency"	=> array(
                    "element"	=> "autoplay",
                    "not_empty"	=> true
                ),
                "value"			=> array( esc_html__( 'Pause on Hover', 'metamax' ) => true )
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
		"name"				=> esc_html__( 'Custom Testimonials', 'metamax' ),
		"base"				=> "cws_sc_vc_testimonial",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Testimonial extends WPBakeryShortCode {
	    }
	}
?>