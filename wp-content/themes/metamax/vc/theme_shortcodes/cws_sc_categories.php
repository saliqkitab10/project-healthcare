<?php
	// Map Shortcode in Visual Composer
	global $cws_theme_funcs;

	$params = array(
		array(
			"type"			=> "dropdown",
			"heading"		=> esc_html__( 'Columns', 'metamax' ),
			"param_name"	=> "columns",
			"value"			=> array(
				esc_html__( 'One', 'metamax' )		=> '1',
				esc_html__( 'Two', 'metamax' )		=> '2',
				esc_html__( 'Three', 'metamax' )	=> '3',
				esc_html__( 'Four', 'metamax' )		=> '4',
			),
			"std"			=> '3'
		),
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Categories to Show (number)', 'metamax' ),
			"param_name"	=> "count",
			"value"			=> "3"
		),
		array(
            "type"          => "checkbox",
            "param_name"    => "square",
            "std"           => true,
            "value"         => array( esc_html__( 'Square Images', 'metamax' ) => true )
        ),
		array(
            "type"          => "checkbox",
            "param_name"    => "use_carousel",
            "value"         => array( esc_html__( 'Use Carousel', 'metamax' ) => true )
        ),


        array(
			"type"			=> "checkbox",
			"param_name"	=> "pagination",
			"value"			=> array( esc_html__( 'Add Pagination Dots', 'metamax' ) => true ),
			 "dependency"	=> array(
				"element"	=> "use_carousel",
				"not_empty"	=> true
			),
			"std"			=> '1'
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "navigation",
			"value"			=> array( esc_html__( 'Add Navigation Arrows', 'metamax' ) => true ),
			 "dependency"	=> array(
				"element"	=> "use_carousel",
				"not_empty"	=> true
			)
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "auto_height",
			"value"			=> array( esc_html__( 'Auto Height', 'metamax' ) => true ),
			 "dependency"	=> array(
				"element"	=> "use_carousel",
				"not_empty"	=> true
			),
			"std"			=> '1'
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "infinite",
			"value"			=> array( esc_html__( 'Infinite Loop', 'metamax' ) => true ),
			 "dependency"	=> array(
				"element"	=> "use_carousel",
				"not_empty"	=> true
			)
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "autoplay",
			"value"			=> array( esc_html__( 'Autoplay', 'metamax' ) => true ),
			 "dependency"	=> array(
				"element"	=> "use_carousel",
				"not_empty"	=> true
			)
		),
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Autoplay Speed', 'metamax' ),
			"dependency"	=> array(
				"element"	=> "autoplay",
				"not_empty"	=> true
			),
			"param_name"	=> "autoplay_speed",
			"value"			=> "3000",
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
	);

	$terms = get_terms( 'category' );
	$avail_terms = array(
		esc_html__( 'None', 'metamax' )	=> ''
	);
	if ( !is_a( $terms, 'WP_Error' ) ){
		foreach ( $terms as $term ) {
			$avail_terms[$term->name] = $term->slug;
		}
	}

	array_push( $params, array(
		"type"			=> "cws_dropdown",
		"multiple"		=> "true",
		"heading"		=> esc_html__( 'Filter by Categories', 'metamax' ),
		"param_name"	=> "cat_terms",
		"value"			=> $avail_terms
	));
	$params2 = array(					
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Extra class name', 'metamax' ),
			"description"		=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
			"param_name"		=> "el_class",
			"value"				=> ""
		)
	);
	$params = array_merge($params, $params2);

	vc_map( array(
		"name"				=> esc_html__( 'CWS Categories', 'metamax' ),
		"base"				=> "cws_sc_categories",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,	
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Categories extends WPBakeryShortCode {
	    }
	}
?>