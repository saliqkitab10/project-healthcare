<?php
	global $cws_theme_funcs;
	$def_chars_count = $cws_theme_funcs->cws_get_option( 'def_blog_chars_count' );
	$def_chars_count = isset( $def_chars_count ) && is_numeric( $def_chars_count ) ? $def_chars_count : '';
	$params = array(
		array(
			"type"			=> "textfield",
			"admin_label"	=> true,
			"heading"		=> esc_html__( 'Title', 'metamax' ),
			"param_name"	=> "title",
			"value"			=> "" 
		),
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Title Alignment', 'metamax' ),
			"param_name"	=> "title_align",
			"value"			=> array(
				esc_html__( "Left", 'metamax' ) 	=> 'left',
				esc_html__( "Right", 'metamax' )	=> 'right',
				esc_html__( "Center", 'metamax' )	=> 'center'
			)		
		),
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Layout', 'metamax' ),
			"param_name"	=> "display_style",
			"value"			=> array(
								esc_html__( 'Grid', 'metamax' ) => 'grid',
								esc_html__( 'Grid with Filter', 'metamax' ) => 'filter',
								esc_html__( 'Carousel', 'metamax' ) => 'carousel'
							)
		),		
		array(
			'type'			=> 'dropdown',
			'heading'		=> esc_html__( 'Columns', 'metamax' ),
			'param_name'	=> 'layout',
			'save_always'	=> true,
			'value'			=> array(
				esc_html__( 'Default', 'metamax' ) => 'def',
				esc_html__( 'Large', 'metamax' ) => '1',
				esc_html__( 'Small', 'metamax' ) => 'small',
				esc_html__( 'Two Columns', 'metamax' ) => '2',
				esc_html__( 'Three Columns', 'metamax' ) => '3',
				esc_html__( 'Four Columns', 'metamax' ) => '4'
			)
		),
	);

	$taxes = get_object_taxonomies ( 'tribe_events', 'object' );
	$avail_taxes = array(
		esc_html__( 'None', 'metamax' )	=> ''
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
		$terms = get_terms( $tax );
		$avail_terms = array(
			''				=> ''
		);
		if ( !is_a( $terms, 'WP_Error' ) ){
			foreach ( $terms as $term ) {
				$avail_terms[$term->name] = $term->slug;
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
				"value"			=> $avail_terms
			));	
		}			
	}
	$params2 = array(
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_colors",
			"value"			=> array( esc_html__( 'Customize Colors', 'metamax' ) => true )
		),		
		array(
			"type"			=> "colorpicker",
			"heading"		=> esc_html__( 'Custom Color', 'metamax' ),
			"param_name"	=> "custom_color",
			"dependency"	=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"			=> METAMAX_FIRST_COLOR
		),				
		array(
			"type"			=> "colorpicker",
			"heading"		=> esc_html__( 'Background Color', 'metamax' ),
			"param_name"	=> "bg_color",
			"dependency"	=> array(
				"element"	=> "customize_colors",
				"not_empty"	=> true
			),
			"value"			=> "#fff"
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "add_shadow",
			"value"			=> array( esc_html__( 'Add Shadow', 'metamax' ) => true )
		),	
		array(
			'type'			=> 'checkbox',
			'param_name'	=> 'tribe_events_hide_meta_override',
			'value'			=> array(
				esc_html__( 'Hide Meta Data', 'metamax' ) => true
			)
		),
		array(
			'type'			=> 'cws_dropdown',
			'multiple'		=> "true",
			'heading'		=> esc_html__( 'Hide', 'metamax' ),
			'param_name'	=> 'tribe_events_hide_meta',
			'dependency'	=> array(
					'element'	=> 'tribe_events_hide_meta_override',
					'not_empty'	=> true
			),
			'value'			=> array(
				esc_html__( 'None', 'metamax' )			=> '',
				esc_html__( 'Title', 'metamax' )		=> 'title',
				esc_html__( 'Date', 'metamax' )	=> 'date',
				esc_html__( 'Excerpt', 'metamax' )	=> 'excerpt',
				esc_html__( 'Time Events', 'metamax' )		=> 'time_events',
				esc_html__( 'Venue Events', 'metamax' )		=> 'venue_events',
			)
		),	
		array(
			'type'			=> 'textfield',
			'heading'		=> esc_html__( 'Content Character Limit', 'metamax' ),
			'param_name'	=> 'chars_count',
			'dependency'	=> array(
					'element'	=> 'tribe_events_hide_meta_override',
					'not_empty'	=> true
			),
			'value'			=> 	$def_chars_count	
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
								"value"		=> array( "grid", "filter" )
							),
			"value"			=> esc_html( get_option( 'posts_per_page' ) )
		),	
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Pagination', 'metamax' ),
			"param_name"	=> "pagination_grid",
			"dependency" 	=> array(
								"element"	=> "display_style",
								"value"		=> array( "grid", "filter" )
							),
			"value"			=> array(
				esc_html__( "Standard", 'metamax' ) 	=> 'standard',
				esc_html__( "Standard With Ajax", 'metamax' ) 	=> 'standard_with_ajax',
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
		"name"				=> esc_html__( 'CWS Events Grid', 'metamax' ),
		"base"				=> "cws_sc_events_posts_grid",
		'category'			=> "By CWS",
		"weight"			=> 80,
		"icon"     			=> "cws_icon",
		"params"			=> $params
	));
	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Events_Posts_Grid extends WPBakeryShortCode {
	    }
	}
?>