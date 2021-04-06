<?php
	global $cws_theme_funcs;
	$first_color 			= $cws_theme_funcs->cws_get_option( 'theme-first-color' );
	$params = array(		
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Layout', 'metamax' ),
			"param_name"	=> "display_style",
			"value"			=> array(
								esc_html__( 'Grid', 'metamax' ) => 'grid',
								esc_html__( 'Grid with Filter', 'metamax' ) => 'filter',
								esc_html__( 'Grid with Filter(Ajax)', 'metamax' ) => 'filter_with_ajax',
								esc_html__( 'Carousel', 'metamax' ) => 'carousel',
							)
		),
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Columns', 'metamax' ),
			"param_name"	=> "layout",
			"value"			=> array(
				esc_html__( 'Default', 'metamax' ) => 'def',
				esc_html__( 'One Column', 'metamax' ) => '1',
				esc_html__( 'Two Columns', 'metamax' ) => '2',
				esc_html__( 'Three Columns', 'metamax' ) => '3',
				esc_html__( 'Four Columns', 'metamax' ) => '4'
			)
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "crop_images",
			"dependency" 	=> array(
				"element"	=> 'layout',
				"value"		=> array( "def","2","3","4" )
			),
			'value'			=> array(
				esc_html__( 'Crop images', 'metamax' ) => true
			)
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "masonry",
			'value'			=> array(
				esc_html__( 'Masonry', 'metamax' ) => true
			)
		),
	);
	$taxes = get_object_taxonomies ( 'cws_portfolio', 'object' );
	$avail_taxes = array(
		esc_html__( 'None', 'metamax' )	=> '',
		esc_html__( 'Titles', 'metamax' )	=> 'title',
	);
	foreach ( $taxes as $tax => $tax_obj ){
		$tax_name = isset( $tax_obj->labels->name ) && !empty( $tax_obj->labels->name ) ? $tax_obj->labels->name : $tax;
		$avail_taxes[$tax_name] = $tax;
	}
	array_push( $params, array(
		"type"				=> "dropdown",
		"heading"			=> esc_html__( 'Filter by', 'metamax' ),
		"param_name"		=> "tax",
		"value"				=> $avail_taxes
	));
	foreach ( $avail_taxes as $tax_name => $tax ) {
		if ($tax == 'title'){
			$custom_post_type = 'cws_portfolio';
			global $wpdb;
    		$results = $wpdb->get_results( $wpdb->prepare( "SELECT ID, post_title FROM {$wpdb->posts} WHERE post_type LIKE %s and post_status = 'publish'", $custom_post_type ) );
    		$titles_arr = array();
		    foreach( $results as $index => $post ) {
		    	$post_title = $post->post_title;
		        $titles_arr[$post_title] =  $post->ID;
		    }
			array_push( $params, array(
				"type"			=> "cws_dropdown",
				"multiple"		=> "true",
				"heading"		=> esc_html__( 'Titles', 'metamax' ),
				"param_name"	=> "titles",
				"dependency"	=> array(
									"element"	=> "tax",
									"value"		=> 'title'
								),
				"value"			=> $titles_arr
			));		
		} else {
			$terms = get_terms( $tax );
			$avail_terms =  array();
			$hierarchy = _get_term_hierarchy($tax);
			if ( !is_a( $terms, 'WP_Error' ) ){
				foreach($terms as $term) {
					if(isset($term)){
						if($term->parent) {
							continue;
						} 			
						$avail_terms[] = $term->name;  
						if(isset($hierarchy[$term->term_id])) {	
							$children = _get_term_children($term->term_id, $terms, $tax);										
							foreach($children as $child) {
								$child = get_term($child, $tax);
								$ancestors = get_ancestors( $child->term_id, $child->taxonomy );
								$depth = $ancestors = count($ancestors);
								if($child->count > 0){
									if($depth <= $ancestors){							
										$avail_terms[] =  str_repeat("-", $depth) . ' ('.$term->name.') '.$child->slug;
									}
								}
							}
						}					
					}				
				}

			}

			array_push( $params, array(
				"type"			=> "cws_dropdown",
				"multiple"		=> "true",
				"heading"		=> $tax_name,
				"param_name"	=> "{$tax}_terms",
				"dependency"	=> array(
									"element"	=> "tax",
									"value"		=> $tax
								),
				"value"			=> $avail_terms, 
			));	
		} 		
	}
	$params2 = array(
		array(
			"type"			=> "checkbox",
			"param_name"	=> "en_isotope",
			"dependency" 	=> array(
					"element"	=> 'display_style',
					"value"		=> 'grid'
				),
			'value'			=> array(
				esc_html__( 'Use Isotope', 'metamax' ) => true
			)
		),
//		array(
//			"type"			=> "checkbox",
//			"param_name"	=> "carousel_auto",
//			"dependency" 	=> array(
//								"element"	=> 'display_style',
//								"value"		=> array( "carousel" )
//							),
//			'value'			=> array(
//				esc_html__( 'AutoPlay Carousel', 'metamax' ) => true
//			)
//		),


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
			"type"			=> "checkbox",
			"param_name"	=> "carousel_pagination",
			"dependency" 	=> array(
								"element"	=> 'display_style',
								"value"		=> array( "carousel" )
							),
			'value'			=> array(
				esc_html__( 'Pagination', 'metamax' ) => true
			)
		),
		array(
			'type'				=> 'cws_dropdown',
			'multiple'			=> "true",
			'heading'			=> esc_html__( 'Show', 'metamax' ),
			'param_name'		=> 'link_show',
			'value'				=> array(
				esc_html__( 'Make Image Clickable', 'metamax' )		=> 'area_link',
				esc_html__( 'Show Image PoPup Icon', 'metamax' )	=> 'popup_link',
			)
		),
		array(
			'type'			=> 'dropdown',
			'heading'		=> esc_html__( 'Show Title and Description', 'metamax' ),
			'param_name'	=> 'info_pos',
			'description'	=> esc_html__( 'Choose "On Image Hover" if masonry is enabled', 'metamax' ),
			'value'			=> array(
				esc_html__( 'On Image Hover', 'metamax' ) => 'inside_img',
				esc_html__( 'Under Image', 'metamax' ) => 'under_img',
			),
		),	
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_carousel",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			'value'			=> array(
				esc_html__( 'Customize Carousel', 'metamax' ) => true
			),
			"dependency" 	=> array(
					"element"	=> 'display_style',
					"value"		=> "carousel"
				),
		),
		array(
			"type"			=> "colorpicker",
			"param_name"	=> "pagination_carousel",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"heading"		=> esc_html__( 'Pagination Color', 'metamax' ),
			"dependency"	=> array(
				"element"	=> "customize_carousel",
				"not_empty"	=> true
			),
			"value"			=> ""
		),		
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Info Alignment', 'metamax' ),
			"param_name"	=> "info_align",
			"value"			=> array(
					esc_html__( 'Center', 'metamax' ) 	=> 'center',
					esc_html__( 'Left', 'metamax' )	    => 'left',
					esc_html__( 'Right', 'metamax' ) 	=> 'right',
							),
		),
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Items to display', 'metamax' ),
			"param_name"	=> "total_items_count",
			"value"			=> esc_html( get_option( 'posts_per_page' ) )
		),
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Items per Page', 'metamax' ),
			"param_name"	=> "items_pp",
			"dependency" 	=> array(
								"element"	=> "display_style",
								"value"		=> array( "grid", "filter", "filter_with_ajax" )
							),
			"value"			=> esc_html( get_option( 'posts_per_page' ) )
		),
		array(
			'type'			=> 'checkbox',
			'param_name'	=> 'cws_portfolio_show_data_override',
			'value'			=> array(
				esc_html__( 'Show Meta Data', 'metamax' ) => true
			)
		),		
		array(
			'type'				=> 'cws_dropdown',
			'multiple'			=> "true",
			'param_name'		=> 'cws_portfolio_data_to_show',
			'dependency'		=> array(
				'element'			=> 'cws_portfolio_show_data_override',
				'not_empty'			=> true
			),
			'value'				=> array(
				esc_html__( 'None', 'metamax' )			=> '',
				esc_html__( 'Title', 'metamax' )		=> 'title',
				esc_html__( 'Excerpt', 'metamax' )		=> 'excerpt',
				esc_html__( 'Categories', 'metamax' )	=> 'cats'
			)
		),	
		array(
			'type'			=> 'textfield',
			'heading'		=> esc_html__( 'Content Character Limit', 'metamax' ),
			'param_name'	=> 'chars_count',
			'dependency'	=> array(
				'element'		=> 'cws_portfolio_show_data_override',
				'not_empty'		=> true
			),
			'value'			=> 	''	
		),
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Pagination', 'metamax' ),
			"param_name"	=> "pagination_grid",
			"dependency" 	=> array(
								"element"	=> "display_style",
								"value"		=> array( "grid", "filter", "filter_with_ajax" )
							),
			"value"			=> array(
				esc_html__( "Standard", 'metamax' ) 	=> 'standard_with_ajax',
				esc_html__( "Load More", 'metamax' )	=> 'load_more',
			)		
		),
			
	);
	$params = array_merge($params, $params2);
	array_push( $params, array(
		"type"				=> "textfield",
		"heading"			=> esc_html__( 'Extra class name', 'metamax' ),
		"description"		=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
		"param_name"		=> "el_class",
		"value"				=> ""
	));
	vc_map( array(
		"name"				=> esc_html__( 'CWS Portfolio', 'metamax' ),
		"base"				=> "cws_sc_portfolio_posts_grid",
		'category'			=> "By CWS",
		"weight"			=> 80,
		"icon"     			=> "cws_icon",		
		"params"			=> $params
	));
	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Portfolio_Posts_Grid extends WPBakeryShortCode {
	    }
	}
?>