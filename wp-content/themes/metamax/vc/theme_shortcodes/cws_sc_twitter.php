<?php
	$params = array(
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Post Count', 'metamax' ),
			"param_name"	=> "number",
			"value"			=> "4"
		),
		array(
			"type"			=> "textfield",
			"heading"		=> esc_html__( 'Posts per slide', 'metamax' ),
			"param_name"	=> "visible_number",
			"value"			=> "2"
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Extra class name', 'metamax' ),
			"description"		=> esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'metamax' ),
			"param_name"		=> "el_class",
			"value"				=> ""
		)
	);

	vc_map( array(
		"name"				=> esc_html__( 'CWS Twitter', 'metamax' ),
		"base"				=> "cws_sc_twitter",
		'category'			=> "By CWS",
		"icon"     			=> "cws_icon",
		"weight"			=> 80,
		"params"			=> $params
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Twitter extends WPBakeryShortCode {
	    }
	}
?>