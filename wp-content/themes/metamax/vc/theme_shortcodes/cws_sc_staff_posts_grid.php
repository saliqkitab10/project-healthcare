<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

	/* -----> STYLING TAB PROPERTIES <----- */
	$styles = array(
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles",
			"group"			=> esc_html__( "Styling", 'metamax' )
		),
        array(
            'type'				=> 'checkbox',
            'param_name'		=> 'customize_colors',
            'value'				=> array(
                esc_html__( 'Customize colors', 'metamax' ) => true
            ),
            "group"			=> esc_html__( "Styling", 'metamax' )
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#000",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Text Color', 'metamax' ),
			"param_name"		=> "text_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#000",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Info Background', 'metamax' ),
			"param_name"		=> "info_background",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> "#fff",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Links Color', 'metamax' ),
			"param_name"		=> "links_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $first_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Links Hover Color', 'metamax' ),
			"param_name"		=> "links_hover_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $second_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Social Icon Color', 'metamax' ),
			"param_name"		=> "social_icon_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> $first_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Social Background Color', 'metamax' ),
            "param_name"		=> "social_bg_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> "",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Social Icon Hover Color', 'metamax' ),
			"param_name"		=> "social_icon_hover_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> "#fff",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Social Background Hover Color', 'metamax' ),
            "param_name"		=> "social_bg_hover_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-6",
            "value"				=> $first_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Button Color', 'metamax' ),
			"param_name"		=> "button_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> '#fff',
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Background Color', 'metamax' ),
            "param_name"		=> "button_bg_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-4",
            "value"				=> $first_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Border Color', 'metamax' ),
            "param_name"		=> "button_bd_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-4",
            "value"				=> $first_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
        ),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Button Hover Color', 'metamax' ),
			"param_name"		=> "button_hover_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"value"				=> $second_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
		),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Background Hover Color', 'metamax' ),
            "param_name"		=> "button_bg_hover_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-4",
            "value"				=> "#fff",
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
        ),
        array(
            "type"				=> "colorpicker",
            "heading"			=> esc_html__( 'Button Border Hover Color', 'metamax' ),
            "param_name"		=> "button_bd_hover_color",
            "group"				=> esc_html__( "Styling", 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-4",
            "value"				=> $first_color,
            "dependency"		=> array(
                "element"	=> "customize_colors",
                "not_empty"	=> true
            ),
        ),
	);

	/* -----> GET TAXONOMIES <----- */
	$taxonomies = array();
	$taxes = get_object_taxonomies ( 'cws_staff', 'object' );
	$avail_taxes = array(
		esc_html__( 'None', 'metamax' )	=> ''
	);
	foreach ( $taxes as $tax => $tax_obj ){
		$tax_name = isset( $tax_obj->labels->name ) && !empty( $tax_obj->labels->name ) ? $tax_obj->labels->name : $tax;
		$avail_taxes[$tax_name] = $tax;
	}
	array_push( $taxonomies, array(
		"type"			=> "dropdown",
		"heading"		=> esc_html__( 'Filter by', 'metamax' ),
		"param_name"	=> "tax",
		"value"			=> $avail_taxes
	));
	foreach ( $avail_taxes as $tax_name => $tax ) {
		$terms = get_terms( $tax );
		$avail_terms = array(
			''				=> ''
		);
		if ( !is_a( $terms, 'WP_Error' ) ){
			foreach ( $terms as $term ) {
				$avail_terms[$term->name] = $term->slug;
			}
		}
		array_push( $taxonomies, array(
			"type"			=> "cws_dropdown",
			"multiple"		=> "true",
			"heading"		=> $tax_name,
			"param_name"	=> "{$tax}_terms",
			"dependency"	=> array(
				"element"	=> "tax",
				"value"		=> $tax
			),
			"value"			=> $avail_terms
		));				
	}

	$params = cws_ext_merge_arrs( array(
		/* -----> GENERAL TAB <----- */
		array(
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Layout', 'metamax' ),
				"param_name"		=> "view_layout",
				"edit_field_class" 	=> "vc_col-xs-4",
				"value"				=> array(
					esc_html__( 'Grid', 'metamax' ) 	=> 'grid',
					esc_html__( 'Carousel', 'metamax' ) => 'carousel'
				),
				"std"				=> 'grid',
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Pagination type', 'metamax' ),
				"param_name"		=> "pagination_type",
				"edit_field_class" 	=> "vc_col-xs-4",
				'dependency'		=> array(
					'element'	=> 'view_layout',
					'value'		=> array('grid', 'list')
				),
				"value"				=> array(
					esc_html__( 'Load More', 'metamax' ) 	=> 'load_more',
					esc_html__( 'Pagination', 'metamax' ) 	=> 'pagination'
				)
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Columns', 'metamax' ),
				"param_name"		=> "layout",
				"edit_field_class" 	=> "vc_col-xs-8",
				"dependency"		=> array(
					"element" 	=> "view_layout",
					"value" 	=> array('grid', 'carousel')
				),
				"value"				=> array(
					esc_html__( 'Default', 'metamax' ) => 'def',
					esc_html__( 'One Column', 'metamax' ) => '1',
					esc_html__( 'Two Columns', 'metamax' ) => '2',
					esc_html__( 'Three Columns', 'metamax' ) => '3',
					esc_html__( 'Four Columns', 'metamax' ) => '4',
				)
			),

            array(
                "type"			=> "checkbox",
                "param_name"	=> "carousel_infinite",
                "edit_field_class" 	=> "vc_col-xs-4",
                'dependency'		=> array(
                    'element'	=> 'view_layout',
                    'value'		=> 'carousel'
                ),
                "value"			=> array( esc_html__( 'Infinite Loop', 'metamax' ) => true )
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "carousel_autoplay",
                "edit_field_class" 	=> "vc_col-xs-4",
                'dependency'		=> array(
                    'element'	=> 'view_layout',
                    'value'		=> 'carousel'
                ),
                "value"			=> array( esc_html__( 'Autoplay', 'metamax' ) => true )
            ),
            array(
                "type"			=> "textfield",
                "heading"		=> esc_html__( 'Autoplay Speed', 'metamax' ),
                "dependency"	=> array(
                    "element"	=> "carousel_autoplay",
                    "not_empty"	=> true
                ),
                "param_name"	=> "autoplay_speed",
                "value"			=> "3000"
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "pause_on_hover",
                "dependency"	=> array(
                    "element"	=> "carousel_autoplay",
                    "not_empty"	=> true
                ),
                "value"			=> array( esc_html__( 'Pause on Hover', 'metamax' ) => true )
            ),
		),
		$taxonomies,
		array(
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Visible Characters', 'metamax' ),
				"param_name"		=> "chars_count",
				"edit_field_class" 	=> "vc_col-xs-4",
				"value"				=> '90'
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Items to display', 'metamax' ),
				"param_name"		=> "total_items_count",
				"edit_field_class" 	=> "vc_col-xs-4",
				"value"				=> esc_html( get_option('posts_per_page') ),
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Items per Page', 'metamax' ),
				"param_name"		=> "items_pp",
				"edit_field_class" 	=> "vc_col-xs-4",
				"value"				=> esc_html( get_option('posts_per_page') ),
			),
            array(
                "type"			    => "dropdown",
                "heading"		    => esc_html__( 'Thumbnail size', 'metamax' ),
                "param_name"	    => "thumbnail_size",
                "value"             => array(
                    esc_html__( 'Full', 'metamax' )      => 'full',
                    esc_html__( 'Large', 'metamax' )     => 'large',
                    esc_html__( 'Medium', 'metamax' )    => 'medium',
                    esc_html__( 'Thumbnail', 'metamax' ) => 'thumbnail',
                ),
            ),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'hide_data_override',
				'value'				=> array(
					esc_html__( 'Hide Meta Data', 'metamax' ) => true
				)
			),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'disable_single',
				"edit_field_class" 	=> "vc_col-xs-4",
				'value'				=> array(
					esc_html__( 'Disable single page', 'metamax' ) => true
				)
			),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'disable_hover',
				"edit_field_class" 	=> "vc_col-xs-4",
				'value'				=> array(
					esc_html__( 'Disable Hover', 'metamax' ) => true
				)
			),
			array(
				'type'				=> 'cws_dropdown',
				'multiple'			=> 'true',
				'heading'			=> esc_html__( 'Hide', 'metamax' ),
				'param_name'		=> 'data_to_hide',
				'dependency'		=> array(
					'element'			=> 'hide_data_override',
					'not_empty'			=> true
				),
				'value'				=> array(
					esc_html__( 'None', 'metamax' )			=> array(),
					esc_html__( 'Departments', 'metamax' ) 	=> 'deps',
					esc_html__( 'Positions', 'metamax' )	=> 'poss',
					esc_html__( 'Excerpt', 'metamax' )		=> 'excerpt',
					esc_html__( 'Social Links', 'metamax' )	=> 'socials',
					esc_html__( 'Link Button', 'metamax' )	=> 'link_button',
					esc_html__( 'Email', 'metamax' )		=> 'email',
					esc_html__( 'Tel', 'metamax' )			=> 'tel',
					esc_html__( 'Experience', 'metamax' )	=> 'experience',
					esc_html__( 'Biography', 'metamax' )	=> 'biography',
				)
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
		$styles
	));

	// Map Shortcode in Visual Composer
	vc_map( array(
		"name"				=> esc_html__( 'CWS Staff', 'metamax' ),
		"base"				=> "cws_sc_staff_posts_grid",
		'category'			=> "By CWS",
		"weight"			=> 80,
		"icon"     			=> "cws_icon",		
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Staff_Posts_Grid extends WPBakeryShortCode {
	    }
	}
?>