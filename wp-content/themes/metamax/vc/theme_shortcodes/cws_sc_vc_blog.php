<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	/* -----> THEME OPTIONS PROPERTIES <----- */
	$first_color = esc_attr( $cws_theme_funcs->cws_get_meta_option( 'theme_first_color' ) );
	$second_color = esc_attr( $cws_theme_funcs->cws_get_meta_option( 'theme_second_color' ) );
	$body_text_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

	/* -----> STYLING TAB PROPERTIES <----- */
	$styles = array(
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles",
			"group"			=> esc_html__( "Styling", 'metamax' )
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_colors",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"value"			=> array( esc_html__( 'Customize Colors', 'metamax' ) => true ),
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Icons Color', 'metamax' ),
			"param_name"		=> "icons_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $first_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Meta Color', 'metamax' ),
			"param_name"		=> "meta_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $body_text_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Meta Hover Color', 'metamax' ),
			"param_name"		=> "meta_hover_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $first_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Text Color', 'metamax' ),
			"param_name"		=> "text_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $body_text_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Color', 'metamax' ),
			"param_name"		=> "title_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $body_text_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Title Hover Color', 'metamax' ),
			"param_name"		=> "title_hover_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $second_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Button Color', 'metamax' ),
			"param_name"		=> "button_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $body_text_color,
		),
		array(
			"type"				=> "colorpicker",
			"heading"			=> esc_html__( 'Button Hover Color', 'metamax' ),
			"param_name"		=> "button_hover_color",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"dependency"		=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"				=> $second_color,
		),
	);

	/* -----> GET TAXONOMIES <----- */
	$post_type = "post";
	$taxonomies = array();

	$taxes = get_object_taxonomies ( $post_type, 'object' );
	$avail_taxes = array(
		esc_html__( 'None', 'metamax' )	=> '',
		esc_html__( 'Titles', 'metamax' )	=> 'title',
	);

	foreach ( $taxes as $tax => $tax_obj ){
		$tax_name = isset( $tax_obj->labels->name ) && !empty( $tax_obj->labels->name ) ? $tax_obj->labels->name : $tax;
		$avail_taxes[$tax_name] = $tax;
	}

	array_push( $taxonomies, array(
		"type"				=> "dropdown",
		"heading"			=> esc_html__( 'Filter by', 'metamax' ),
		"param_name"		=> "post_tax",
		"value"				=> $avail_taxes
	));

	foreach ( $avail_taxes as $tax_name => $tax ) {
		if ($tax == 'title'){
			global $wpdb;
    		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type LIKE %s and post_status = 'publish'", $post_type ) );
    		$titles_arr = array();
		    foreach( $results as $index => $post ) {
		    	$post_title = $post->post_title;
		        $titles_arr[$post_title] =  $post->ID;
		    }
			array_push( $taxonomies, array(
				"type"				=> "cws_dropdown",
				"multiple"			=> "true",
				"heading"			=> esc_html__( 'Titles', 'metamax' ),
				"param_name"		=> "titles",
				'edit_field_class'	=> 'inside-box vc_col-xs-12',
				"dependency"		=> array(
					"element"	=> "post_tax",
					"value"		=> 'title'
				),
				"value"				=> $titles_arr
			));		
		} else {
			$terms = get_terms( $tax );
			$avail_terms = array();
			if ( !is_a( $terms, 'WP_Error' ) ){
				foreach ( $terms as $term ) {
					$avail_terms[$term->name] = $term->slug;
				}
			}
			array_push( $taxonomies, array(
				"type"			=> "cws_dropdown",
				"multiple"		=> "true",
				"heading"		=> $tax_name,
				"param_name"	=> "{$post_type}_{$tax}_terms",
				"dependency"	=> array(
					"element"	=> "post_tax",
					"value"		=> $tax
					),
				"value"			=> $avail_terms
			));				
		}
	}

	$params = cws_ext_merge_arrs( array(
		/* -----> GENERAL TAB <----- */
		$taxonomies,
		array(
			array(
				'type'				=> 'dropdown',
				'heading'			=> esc_html__( 'Order by', 'metamax' ),
				'param_name'		=> 'orderby',
				"edit_field_class" 	=> "vc_col-xs-6",
				"dependency"		=> array(
					"element"	=> "post_tax",
					"value"		=> array( "title","category","post_tag","post_format", )
				),
				'value'				=> array(
					esc_html__( 'Date', 'metamax' ) => 'date',
					esc_html__( 'Title', 'metamax' ) => 'title',
				),
			),
			array(
				'type'				=> 'dropdown',
				'heading'			=> esc_html__( 'Order', 'metamax' ),
				'param_name'		=> 'order',
				"edit_field_class" 	=> "vc_col-xs-6",
				"dependency"		=> array(
					"element"	=> "post_tax",
					"value"		=> array( "title","category","post_tag","post_format", )
				),
				'value'				=> array(
					esc_html__( 'DESC', 'metamax' ) => 'DESC',
					esc_html__( 'ASC', 'metamax' ) => 'ASC',
				),
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Blog View', 'metamax' ),
				"param_name"		=> "display_style",
				"edit_field_class" 	=> "vc_col-xs-6",
				"value"				=> array(
					esc_html__( 'Grid', 'metamax' ) => 'grid',
					esc_html__( 'Carousel', 'metamax' ) => 'carousel'
				)
			),
			array(
				'type'				=> 'dropdown',
				'heading'			=> esc_html__( 'Layout', 'metamax' ),
				'param_name'		=> 'layout',
				"edit_field_class" 	=> "vc_col-xs-6",
				'value'				=> array(
					esc_html__( 'Default', 'metamax' )          => 'def',
					esc_html__( 'Large Image', 'metamax' )      => '1',
					esc_html__( 'Medium Image', 'metamax' )     => 'medium',
					esc_html__( 'Small Image', 'metamax' )      => 'small',
					esc_html__( 'Two Columns', 'metamax' )      => '2',
					esc_html__( 'Three Columns', 'metamax' )    => '3',
					esc_html__( 'Four Columns', 'metamax' )     => '4',
					esc_html__( 'Checkerboard', 'metamax' )     => 'checkerboard',
					esc_html__( 'Special', 'metamax' )          => 'special',
					esc_html__( 'List', 'metamax' )             => 'list',
					esc_html__( 'TimeLine', 'metamax' )         => 'timeline',
				)
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Content Alignment', 'metamax' ),
				"param_name"		=> "content_align",
				"edit_field_class" 	=> "vc_col-xs-6",
				"dependency" 		=> array(
					"element"	=> "layout",
					"value"		=> array( "checkerboard" )
				),			
				"value"				=> array(
					esc_html__( "Top", 'metamax' ) 		=> 'top',
					esc_html__( "Center", 'metamax' )	=> 'center',
					esc_html__( "Bottom", 'metamax' )	=> 'bottom'
				)		
			),
			array(
				'type'				=> 'textfield',
				"heading"			=> esc_html__( 'Items Spacing', 'metamax' ),
				'param_name'		=> 'checkerboard_spacings',
				"edit_field_class" 	=> "vc_col-xs-6",
				"dependency" 		=> array(
					"element"	=> "layout",
					"value"		=> array( "checkerboard" )
				),
				'value'				=> ''
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
                "edit_field_class" 	=> "vc_col-xs-6",
            ),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'isotope',
				'dependency'		=> array(
					'element'	=> 'display_style',
					'value'		=> 'grid'
				),
				'value'				=> array(
					esc_html__( 'Isotope', 'metamax' ) => true
				)
			),
//			array(
//				'type'				=> 'checkbox',
//				'param_name'		=> 'uniq_cat',
//				'dependency'		=> array(
//					'element'	=> 'layout',
//					'value'		=> array('2', '3', '4'),
//				),
//				'value'				=> array(
//					esc_html__( 'Highlighted category', 'metamax' ) => true
//				)
//			),
            array(
                'type'				=> 'cws_dropdown',
                'heading'		    => esc_html__( 'Carousel Direction', 'metamax' ),
                'param_name'		=> 'carousel_direction',
                'dependency'		=> array(
                    'element'	=> 'display_style',
                    'value'		=> 'carousel'
                ),
                'value'			=> array(
                    esc_html__( 'Horizontal', 'metamax' )	=> 'horizontal',
                    esc_html__( 'Vertical', 'metamax' )		=> 'vertical',
                )
            ),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'carousel_navigation',
				"edit_field_class" 	=> "vc_col-xs-4",
				'dependency'		=> array(
					'element'	=> 'display_style',
					'value'		=> 'carousel'
				),
				'value'				=> array(
					esc_html__( 'Carousel Nav', 'metamax' ) => true
				)
			),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'carousel_pagination',
				"edit_field_class" 	=> "vc_col-xs-4",
				'dependency'		=> array(
					'element'	=> 'display_style',
					'value'		=> 'carousel'
				),
				'value'				=> array(
					esc_html__( 'Carousel Dots', 'metamax' ) => true
				),
				'std'				=> '1'
			),
			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'carousel_autoheight',
				"edit_field_class" 	=> "vc_col-xs-4",
				'dependency'		=> array(
					'element'	=> 'display_style',
					'value'		=> 'carousel'
				),
				'value'				=> array(
					esc_html__( 'Auto Height', 'metamax' ) => true
				),
				'std'				=> '1'
			),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "carousel_infinite",
                "edit_field_class" 	=> "vc_col-xs-4",
                'dependency'		=> array(
                    'element'	=> 'display_style',
                    'value'		=> 'carousel'
                ),
                "value"			=> array( esc_html__( 'Infinite Loop', 'metamax' ) => true )
            ),
            array(
                "type"			=> "checkbox",
                "param_name"	=> "carousel_autoplay",
                "edit_field_class" 	=> "vc_col-xs-4",
                'dependency'		=> array(
                    'element'	=> 'display_style',
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

			array(
				'type'				=> 'checkbox',
				'param_name'		=> 'post_hide_meta_override',
				'value'				=> array(
					esc_html__( 'Hide Meta Data', 'metamax' ) => true
				)
			),
			array(
				'type'			=> 'cws_dropdown',
				'multiple'		=> "true",
				'heading'		=> esc_html__( 'Hide', 'metamax' ),
				'param_name'	=> 'post_hide_meta',
				'dependency'	=> array(
					'element'	=> 'post_hide_meta_override',
					'not_empty'	=> true
				),
				'value'			=> array(
					esc_html__( 'None', 'metamax' )			=> '',
					esc_html__( 'Title', 'metamax' )		=> 'title',
					esc_html__( 'Categories', 'metamax' )	=> 'cats',
					esc_html__( 'Tags', 'metamax' )			=> 'tags',
					esc_html__( 'Author', 'metamax' )		=> 'author',
					esc_html__( 'Likes', 'metamax' )		=> 'likes',
					esc_html__( 'Date', 'metamax' )			=> 'date',
					esc_html__( 'Comments', 'metamax' )		=> 'comments',
					esc_html__( 'Read More', 'metamax' )	=> 'read_more',
					esc_html__( 'Social Icons', 'metamax' )	=> 'social',
					esc_html__( 'Excerpt', 'metamax' )		=> 'excerpt',
				)
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Items to display', 'metamax' ),
				"param_name"		=> "total_items_count",
				"edit_field_class" 	=> "vc_col-xs-4",
				"value"				=> ''
			),
			array(
				"type"				=> "textfield",
				"heading"			=> esc_html__( 'Items per Page', 'metamax' ),
				"param_name"		=> "items_pp",
				"edit_field_class" 	=> "vc_col-xs-4",
				"dependency" 		=> array(
					"element"	=> "display_style",
					"value"		=> array("grid")
				),
				"value"				=> esc_html( get_option( 'posts_per_page' ) )
			),
            array(
                "type"				=> "textfield",
                "heading"			=> esc_html__( 'Items per Column', 'metamax' ),
                "param_name"		=> "items_pc",
                "edit_field_class" 	=> "vc_col-xs-4",
                "dependency" 		=> array(
                    "element"	=> "carousel_direction",
                    "value"		=> array("vertical")
                ),
                "value"				=> '3'
            ),
			array(
				'type'				=> 'textfield',
				'heading'			=> esc_html__( 'Content Character Limit', 'metamax' ),
				'param_name'		=> 'chars_count',
				"edit_field_class" 	=> "vc_col-xs-4",
				'value'				=> '200'
			),
			array(
				"type"				=> "dropdown",
				"heading"			=> esc_html__( 'Pagination', 'metamax' ),
				"param_name"		=> 'pagination_grid',
				"edit_field_class" 	=> "vc_col-xs-6",
				"dependency" 		=> array(
					"element"	=> "display_style",
					"value"		=> array( "grid" )
				),
				"value"				=> array(
					esc_html__( "Standard", 'metamax' ) 			=> 'standard',
					esc_html__( "Load More", 'metamax' )			=> 'load_more',
					esc_html__( "Standard With Ajax", 'metamax' )	=> 'standard_with_ajax',
				)		
			),
			array(
				"type"			=> "textfield",
				"heading"		=> esc_html__( 'More button caption', 'metamax' ),
				"param_name"	=> "more_btn_text",
				"value"			=> $cws_theme_funcs->cws_get_option('blog_button_name'),
			),
			array(
				"type"			=> "textfield",
				"heading"		=> esc_html__( 'Extra class name', 'metamax' ),
				"description"	=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
				"param_name"	=> "el_class",
				"value"			=> ""
			),
		),
		$styles,
	));

	vc_map( array(
		"name"				=> esc_html__( 'CWS Blog', 'metamax' ),
		"base"				=> "cws_sc_vc_blog",
		'category'			=> "By CWS",
		"weight"			=> 80,
		"icon"     			=> "cws_icon",		
		"params"			=> $params
	));

if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_CWS_Sc_Vc_Blog extends WPBakeryShortCode {
    }
}

?>