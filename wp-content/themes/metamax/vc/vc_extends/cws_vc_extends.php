<?php
function cws_ext_merge_arrs ( $arrs = array() ){
	$r = array();
	for ( $i = 0; $i < count( $arrs ); $i++ ){
		$r = array_merge( $r, $arrs[$i] );
	}
	return $r;
}

function cws_ext_post_terms_str ( $pid = "", $tax = "", $delim = ", " ){
	$terms_str = "";
	$terms_arr = wp_get_post_terms( $pid, $tax, array( "fields" => "names" ) );
	if ( is_wp_error( $terms_arr ) ){
		return $terms_str;
	}
	else{
		$terms_str .= implode( $delim, $terms_arr );
	}
	return $terms_str;
}

/**/
/* Composer Background-Properties Group */
/**/
function cws_structure_background_props($layout){
	/* -----> STYLING GROUP TITLES <----- */
	$group_name = esc_html__('Design Options', 'metamax');
	$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
	$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
	$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

	/*-----> Desktop Background Properties <-----*/
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "bg_position",
			"group"				=> $group_name,
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'Center Top', 'metamax' ) 		=> 'top',
				esc_html__( 'Center Center', 'metamax' ) 	=> 'center',
				esc_html__( 'Center Bottom', 'metamax' ) 	=> 'bottom',
				esc_html__( 'Custom', 'metamax' ) 			=> 'custom',
			),
			"std"				=> "center"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "bg_size",
			"group"				=> $group_name,
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'Auto', 'metamax' ) 		=> 'auto',
				esc_html__( 'Cover', 'metamax' ) 		=> 'cover',
				esc_html__( 'Contain', 'metamax' ) 		=> 'contain',
				esc_html__( 'Custom', 'metamax' ) 		=> 'custom',
			),
			"std"				=> "cover"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Repeat', 'metamax' ),
			"param_name"		=> "bg_repeat",
			"group"				=> $group_name,
			"edit_field_class" 	=> "vc_col-xs-6",
			"value"				=> array(
				esc_html__( 'No Repeat', 'metamax' ) 	=> 'no-repeat',
				esc_html__( 'Repeat', 'metamax' ) 		=> 'repeat',
				esc_html__( 'Repeat Y', 'metamax' ) 	=> 'repeat-y',
				esc_html__( 'Repeat X', 'metamax' ) 	=> 'repeat-x',
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type" 				=> "dropdown",
			"heading" 			=> esc_html__("Background Attachment", 'metamax'),
			"param_name" 		=> "bg_attachment",
			"group"				=> $group_name,
			"edit_field_class" 	=> "vc_col-xs-6",
			"value" 			=> array(
				esc_html__("Scroll", 'metamax') => "scroll",
				esc_html__("Fixed", 'metamax') => "fixed",
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "custom_bg_position",
			"group"				=> $group_name,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "bg_position",
				"value"		=> "custom"
			),
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "custom_bg_size",
			"group"				=> $group_name,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "bg_size",
				"value"		=> "custom"
			),
			"value"				=> ""
		)
	);

	/*-----> Landscape Background Properties <-----*/
	vc_add_param(
		$layout,
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles_landscape",
			"group"			=> $landscape_group, 
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_bg_landscape",
			"group"			=> $landscape_group,
			"value"			=> array( esc_html__( 'Customize Background', 'metamax' ) => true )
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "bg_position_landscape",
			"group"				=> $landscape_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_landscape",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'Center Top', 'metamax' ) 		=> 'top',
				esc_html__( 'Center Center', 'metamax' ) 	=> 'center',
				esc_html__( 'Center Bottom', 'metamax' ) 	=> 'bottom',
				esc_html__( 'Custom', 'metamax' ) 			=> 'custom',
			),
			"std"				=> "center"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "bg_size_landscape",
			"group"				=> $landscape_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_landscape",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'Auto', 'metamax' ) 		=> 'auto',
				esc_html__( 'Cover', 'metamax' ) 		=> 'cover',
				esc_html__( 'Contain', 'metamax' ) 		=> 'contain',
				esc_html__( 'Custom', 'metamax' ) 		=> 'custom',
			),
			"std"				=> "cover"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Repeat', 'metamax' ),
			"param_name"		=> "bg_repeat_landscape",
			"group"				=> $landscape_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_landscape",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'No Repeat', 'metamax' ) 	=> 'no-repeat',
				esc_html__( 'Repeat', 'metamax' ) 		=> 'repeat',
				esc_html__( 'Repeat Y', 'metamax' ) 	=> 'repeat-y',
				esc_html__( 'Repeat X', 'metamax' ) 	=> 'repeat-x',
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type" 				=> "dropdown",
			"heading" 			=> esc_html__("Background Attachment", 'metamax'),
			"param_name" 		=> "bg_attachment_landscape",
			"group"				=> $landscape_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_landscape",
				"not_empty"	=> true
			),
			"value" 			=> array(
				esc_html__("Scroll", 'metamax') => "scroll",
				esc_html__("Fixed", 'metamax') => "fixed",
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "custom_bg_position_landscape",
			"group"				=> $landscape_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "bg_position_landscape",
				"value"		=> "custom"
			),
            "value"         => ""
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "custom_bg_size_landscape",
			"group"				=> $landscape_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "bg_size_landscape",
				"value"		=> "custom"
			),
			"value"				=> ""
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"			=> "checkbox",
			"param_name"	=> "hide_bg_landscape",
			"group"			=> $landscape_group,
			"value"			=> array( esc_html__( 'Hide Background', 'metamax' ) => true )
		)
	);

	/*-----> Portrait Background Properties <-----*/
	vc_add_param(
		$layout,
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles_portrait",
			"group"			=> $portrait_group
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_bg_portrait",
			"group"			=> $portrait_group,
			"value"			=> array( esc_html__( 'Customize Background', 'metamax' ) => true )
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "bg_position_portrait",
			"group"				=> $portrait_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_portrait",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'Center Top', 'metamax' ) 		=> 'top',
				esc_html__( 'Center Center', 'metamax' ) 	=> 'center',
				esc_html__( 'Center Bottom', 'metamax' ) 	=> 'bottom',
				esc_html__( 'Custom', 'metamax' ) 			=> 'custom',
			),
			"std"				=> "center"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "bg_size_portrait",
			"group"				=> $portrait_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_portrait",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'Auto', 'metamax' ) 		=> 'auto',
				esc_html__( 'Cover', 'metamax' ) 		=> 'cover',
				esc_html__( 'Contain', 'metamax' ) 		=> 'contain',
				esc_html__( 'Custom', 'metamax' ) 		=> 'custom',
			),
			"std"				=> "cover"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Repeat', 'metamax' ),
			"param_name"		=> "bg_repeat_portrait",
			"group"				=> $portrait_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_portrait",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'No Repeat', 'metamax' ) 	=> 'no-repeat',
				esc_html__( 'Repeat', 'metamax' ) 		=> 'repeat',
				esc_html__( 'Repeat Y', 'metamax' ) 	=> 'repeat-y',
				esc_html__( 'Repeat X', 'metamax' ) 	=> 'repeat-x',
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type" 				=> "dropdown",
			"heading" 			=> esc_html__("Background Attachment", 'metamax'),
			"param_name" 		=> "bg_attachment_portrait",
			"group"				=> $portrait_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_portrait",
				"not_empty"	=> true
			),
			"value" 			=> array(
				esc_html__("Scroll", 'metamax') => "scroll",
				esc_html__("Fixed", 'metamax') => "fixed",
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "custom_bg_position_portrait",
			"group"				=> $portrait_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "bg_position_portrait",
				"value"		=> "custom"
			),
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "custom_bg_size_portrait",
			"group"				=> $portrait_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "bg_size_portrait",
				"value"		=> "custom"
			),
			"value"				=> ""
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"			=> "checkbox",
			"param_name"	=> "hide_bg_portrait",
			"group"			=> $portrait_group,
			"value"			=> array( esc_html__( 'Hide Background', 'metamax' ) => true )
		)
	);

	/*-----> Mobile Background Properties <-----*/
	vc_add_param(
		$layout,
		array(
			"type"			=> "css_editor",
			"param_name"	=> "custom_styles_mobile",
			"group"			=> $mobile_group
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"			=> "checkbox",
			"param_name"	=> "customize_bg_mobile",
			"group"			=> $mobile_group,
			"value"			=> array( esc_html__( 'Customize Background', 'metamax' ) => true )
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "bg_position_mobile",
			"group"				=> $mobile_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_mobile",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'Center Top', 'metamax' ) 		=> 'top',
				esc_html__( 'Center Center', 'metamax' ) 	=> 'center',
				esc_html__( 'Center Bottom', 'metamax' ) 	=> 'bottom',
				esc_html__( 'Custom', 'metamax' ) 			=> 'custom',
			),
			"std"				=> "center"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "bg_size_mobile",
			"group"				=> $mobile_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_mobile",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'Auto', 'metamax' ) 		=> 'auto',
				esc_html__( 'Cover', 'metamax' ) 		=> 'cover',
				esc_html__( 'Contain', 'metamax' ) 		=> 'contain',
				esc_html__( 'Custom', 'metamax' ) 		=> 'custom',
			),
			"std"				=> "cover"
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Repeat', 'metamax' ),
			"param_name"		=> "bg_repeat_mobile",
			"group"				=> $mobile_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_mobile",
				"not_empty"	=> true
			),
			"value"				=> array(
				esc_html__( 'No Repeat', 'metamax' ) 	=> 'no-repeat',
				esc_html__( 'Repeat', 'metamax' ) 		=> 'repeat',
				esc_html__( 'Repeat Y', 'metamax' ) 	=> 'repeat-y',
				esc_html__( 'Repeat X', 'metamax' ) 	=> 'repeat-x',
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type" 				=> "dropdown",
			"heading" 			=> esc_html__("Background Attachment", 'metamax'),
			"param_name" 		=> "bg_attachment_mobile",
			"group"				=> $mobile_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "customize_bg_mobile",
				"not_empty"	=> true
			),
			"value" 			=> array(
				esc_html__("Scroll", 'metamax') => "scroll",
				esc_html__("Fixed", 'metamax') => "fixed",
			)
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "custom_bg_position_mobile",
			"group"				=> $mobile_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"	=> array(
				"element"	=> "bg_position_mobile",
				"value"		=> "custom"
			),
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "custom_bg_size_mobile",
			"group"				=> $mobile_group,
			"edit_field_class" 	=> "vc_col-xs-6",
			"dependency"		=> array(
				"element"	=> "bg_size_mobile",
				"value"		=> "custom"
			),
			"value"				=> ""
		)
	);
	vc_add_param(
		$layout,
		array(
			"type"			=> "checkbox",
			"param_name"	=> "hide_bg_mobile",
			"group"			=> $mobile_group,
			"value"			=> array( esc_html__( 'Hide Background', 'metamax' ) => true )
		)
	);
}
function cws_module_background_props(){
	$background_properties = array(
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "bg_position",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"responsive"		=> "all",
			"value"				=> array(
				esc_html__( 'Center Top', 'metamax' ) 		=> 'top',
				esc_html__( 'Center Center', 'metamax' ) 	=> 'center',
				esc_html__( 'Center Bottom', 'metamax' ) 	=> 'bottom',
				esc_html__( 'Custom', 'metamax' ) 			=> 'custom',
			)
		),
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "bg_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"responsive"		=> "all",
			"value"				=> array(
				esc_html__( 'Auto', 'metamax' ) 		=> 'auto',
				esc_html__( 'Cover', 'metamax' ) 		=> 'cover',
				esc_html__( 'Contain', 'metamax' ) 		=> 'contain',
				esc_html__( 'Custom', 'metamax' ) 		=> 'custom',
			)
		),
		array(
			"type"				=> "dropdown",
			"heading"			=> esc_html__( 'Background Repeat', 'metamax' ),
			"param_name"		=> "bg_repeat",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-4",
			"responsive"		=> "all",
			"value"				=> array(
				esc_html__( 'No Repeat', 'metamax' ) 	=> 'no-repeat',
				esc_html__( 'Repeat Y', 'metamax' ) 	=> 'repeat-y',
				esc_html__( 'Repeat X', 'metamax' ) 	=> 'repeat-x',
			)
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Position', 'metamax' ),
			"param_name"		=> "custom_bg_position",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
            "responsive"		=> "all",
			"dependency"	=> array(
				"element"	=> "bg_position",
				"value"		=> "custom"
			),
		),
		array(
			"type"				=> "textfield",
			"heading"			=> esc_html__( 'Background Size', 'metamax' ),
			"param_name"		=> "custom_bg_size",
			"group"				=> esc_html__( "Styling", 'metamax' ),
			"edit_field_class" 	=> "vc_col-xs-6",
            "responsive"		=> "all",
			"dependency"		=> array(
				"element"	=> "bg_size",
				"value"		=> "custom"
			),
			"value"				=> ""
		),
		array(
			"type"			=> "checkbox",
			"param_name"	=> "bg_display",
			"group"			=> esc_html__( "Styling", 'metamax' ),
			"responsive"	=> "all",
			"value"			=> array( esc_html__( 'Hide Background on this resolution', 'metamax' ) => true )
		),
	);

	return $background_properties;
}
/**/
/* \Composer Background-Properties Group */
/**/

/**/
/* Composer Icon Params Group */
/**/
function cws_ext_icon_vc_sc_config_params ( $dep_el = "", $dep_val = false, $value_el = false ){
	global $cws_theme_funcs;
	$libs_param = array(
		'type' => 'dropdown',
		'heading' => esc_html__( 'Icon library', 'metamax' ),
		'value' => array(
            esc_html__( 'Font Awesome', 'metamax' ) => 'fontawesome',
            esc_html__( 'Open Iconic', 'metamax' ) => 'openiconic',
            esc_html__( 'Typicons', 'metamax' ) => 'typicons',
            esc_html__( 'Entypo', 'metamax' ) => 'entypo',
            esc_html__( 'Linecons', 'metamax' ) => 'linecons',
            esc_html__( 'Mono Social', 'metamax' ) => 'monosocial',
		),
		'param_name' => 'icon_lib',
		'description' => esc_html__( 'Select icon library.', 'metamax' ),
	);
	if ( !empty( $dep_el ) ){
		$libs_param['dependency'] = array(
			"element"	=> $dep_el
		);
		if ( is_bool( $dep_val ) ){
			$libs_param['dependency']['not_empty'] = $dep_val;
		}
		else{
			$libs_param['dependency']['value'] = $dep_val;
		}
		if(!empty($value_el)){
			$libs_param['dependency']['value'] = $value_el;
		}
	}
	$iconpickers = array(
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_fontawesome',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true,
				// default true, display an "EMPTY" icon?
				'iconsPerPage' => 4000,
				// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'fontawesome',
			),
			'description' => esc_html__( 'Select icon from library.', 'metamax' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_openiconic',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true, // default true, display an "EMPTY" icon?
				'type' => 'openiconic',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'openiconic',
			),
			'description' => esc_html__( 'Select icon from library.', 'metamax' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_typicons',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true, // default true, display an "EMPTY" icon?
				'type' => 'typicons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'typicons',
			),
			'description' => esc_html__( 'Select icon from library.', 'metamax' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_entypo',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true, // default true, display an "EMPTY" icon?
				'type' => 'entypo',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'entypo',
			),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_linecons',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true, // default true, display an "EMPTY" icon?
				'type' => 'linecons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'linecons',
			),
			'description' => esc_html__( 'Select icon from library.', 'metamax' ),
		),
		array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_monosocial',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true, // default true, display an "EMPTY" icon?
				'type' => 'monosocial',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'monosocial',
			),
			'description' => esc_html__( 'Select icon from library.', 'metamax' ),
		)
	);

	$fi_icons = cws_get_all_flaticon_icons();
	$fi_firsticon = "";
	$fi_exists = is_array( $fi_icons ) && !empty( $fi_icons );
	$fi_lib_key = esc_html__( 'CWS Flaticons', 'metamax' );
	if ( $fi_exists ){
		$fi_firsticon = $fi_icons[0];
		$libs_param['value'][$fi_lib_key] = 'cws_flaticons';
		array_push( $iconpickers, array(
			'type' => 'iconpicker',
			'heading' => esc_html__( 'Icon', 'metamax' ),
			'param_name' => 'icon_cws_flaticons',
			'value' => '', // default value to backend editor admin_label
			'settings' => array(
				'emptyIcon' => true, // default true, display an "EMPTY" icon?
				'type' => 'cws_flaticons',
				'iconsPerPage' => 4000, // default 100, how many icons per/page to display
			),
			'dependency' => array(
				'element' => 'icon_lib',
				'value' => 'cws_flaticons',
			),
			'description' => esc_html__( 'Select icon from library.', 'metamax' ),
		));
	}

	$svg_lib_key = esc_html__( 'CWS SVG', 'metamax' );
	$libs_param['value'][$svg_lib_key] = 'cws_svg';
	array_push( $iconpickers, array(
		"type"			=> "cws_svg",
		"heading"		=> esc_html__( 'SVG Icon', 'metamax' ),
		"param_name"	=> "icon_cws_svg",
		'dependency' => array(
			'element' => 'icon_lib',
			'value' => 'cws_svg',
		),
		'description' => esc_html__( 'Select icon from library.', 'metamax' ),
	));
	
	$params = array_merge( array( $libs_param ), $iconpickers );
	return $params;
}




function cws_ext_icon_vc_sc_config_params_multiple ( $num = '1' ){
    global $cws_theme_funcs;
    $libs_param = array(
        'type' => 'dropdown',
        'heading' => esc_html__( 'Icon library-'.$num, 'metamax' ),
        'value' => array(
            esc_html__( 'Font Awesome', 'metamax' ) => 'fontawesome',
            esc_html__( 'Open Iconic', 'metamax' ) => 'openiconic',
            esc_html__( 'Typicons', 'metamax' ) => 'typicons',
            esc_html__( 'Entypo', 'metamax' ) => 'entypo',
            esc_html__( 'Linecons', 'metamax' ) => 'linecons',
            esc_html__( 'Mono Social', 'metamax' ) => 'monosocial',
        ),
        'param_name' => 'icon_lib-'.$num,
        'description' => esc_html__( 'Select icon library.', 'metamax' ),
        "edit_field_class" 	=> "vc_col-xs-4",
    );
    $iconpickers = array(
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_fontawesome-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true,
                // default true, display an "EMPTY" icon?
                'iconsPerPage' => 4000,
                // default 100, how many icons per/page to display, we use (big number) to display all icons in single page
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'fontawesome',
            ),
            'description' => esc_html__( 'Select icon from library.', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-8",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_openiconic-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'openiconic',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'openiconic',
            ),
            'description' => esc_html__( 'Select icon from library.', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-8",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_typicons-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'typicons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'typicons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-8",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_entypo-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'entypo',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'entypo',
            ),
            "edit_field_class" 	=> "vc_col-xs-8",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_linecons-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'linecons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'linecons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-8",
        ),
        array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_monosocial-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'monosocial',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'monosocial',
            ),
            'description' => esc_html__( 'Select icon from library.', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-8",
        )
    );

    $fi_icons = cws_get_all_flaticon_icons();
    $fi_firsticon = "";
    $fi_exists = is_array( $fi_icons ) && !empty( $fi_icons );
    $fi_lib_key = esc_html__( 'CWS Flaticons', 'metamax' );
    if ( $fi_exists ){
        $fi_firsticon = $fi_icons[0];
        $libs_param['value'][$fi_lib_key] = 'cws_flaticons';
        array_push( $iconpickers, array(
            'type' => 'iconpicker',
            'heading' => esc_html__( 'Icon-'.$num, 'metamax' ),
            'param_name' => 'icon_cws_flaticons-'.$num,
            'value' => '', // default value to backend editor admin_label
            'settings' => array(
                'emptyIcon' => true, // default true, display an "EMPTY" icon?
                'type' => 'cws_flaticons',
                'iconsPerPage' => 4000, // default 100, how many icons per/page to display
            ),
            'dependency' => array(
                'element' => 'icon_lib-'.$num,
                'value' => 'cws_flaticons',
            ),
            'description' => esc_html__( 'Select icon from library.', 'metamax' ),
            "edit_field_class" 	=> "vc_col-xs-8",
        ));
    }

//    $svg_lib_key = esc_html__( 'CWS SVG', 'metamax' );
//    $libs_param['value'][$svg_lib_key] = 'cws_svg';
//    array_push( $iconpickers, array(
//        "type"			=> "cws_svg",
//        "heading"		=> esc_html__( 'SVG Icon-'.$num, 'metamax' ),
//        "param_name"	=> "icon_cws_svg-".$num,
//        'dependency' => array(
//            'element' => 'icon_lib-'.$num,
//            'value' => 'cws_svg',
//        ),
//        'description' => esc_html__( 'Select icon from library.', 'metamax' ),
//        "edit_field_class" 	=> "vc_col-xs-9",
//    ));

    $params = array_merge( array( $libs_param ), $iconpickers );
    return $params;
}
/**/
/* \Composer Icon Params Group */
/**/

/**/
/* Get Selected Icons from Composer Attributes */
/**/
function cws_ext_vc_sc_get_icon ( $atts ){
	$defaults = array(
		'icon_lib' 				=> 'fontawesome',
		'icon_fontawesome'		=> '',
		'icon_openiconic'		=> '',
		'icon_typicons'			=> '',
		'icon_entypo'			=> '',
		'icon_linecons'			=> '',
		'icon_monosocial'		=> '',
		'icon_cws_flaticons'	=> '',
		'icon_cws_svg'		=> '',
	);
	$proc_atts 	= wp_parse_args( $atts, $defaults );
	$lib 		= $proc_atts['icon_lib'];
	$icon_key 	= "icon_$lib";
	$icon 		= isset( $atts[$icon_key] ) ? $atts[$icon_key] : "";
	return $icon;
}

function cws_render_builder_gradient_rules_hover( $options ) {
	extract(shortcode_atts(array(
		'cws_gradient_color_from' => "#000000",
		'cws_gradient_color_to' => '#0eecbd',
		'cws_gradient_type' => 'linear',
		'cws_gradient_angle' => '45',
		'cws_gradient_shape_variant_type' => 'simple',
		'cws_gradient_shape_type' => 'ellipse',
		'cws_gradient_size_keyword_type' => 'farthest-corner',
		'cws_gradient_size_type' => '',		
	), $options));

	$cws_gradient_color_from = isset($options['cws_bg_hover_gradient_color_from']) ? $options['cws_bg_hover_gradient_color_from'] : $cws_gradient_color_from;
	$cws_gradient_color_to = isset($options['cws_bg_hover_gradient_color_to']) ? $options['cws_bg_hover_gradient_color_to'] : $cws_gradient_color_to;
	$cws_gradient_type = isset($options['cws_bg_hover_gradient_type']) ? $options['cws_bg_hover_gradient_type'] : $cws_gradient_type;
	$cws_gradient_angle = isset($options['cws_bg_hover_gradient_angle']) ? $options['cws_bg_hover_gradient_angle'] : $cws_gradient_angle;
	
	$cws_gradient_shape_variant_type = isset($options['cws_bg_hover_gradient_shape_variant_type']) ? $options['cws_bg_hover_gradient_shape_variant_type'] : $cws_gradient_shape_variant_type;
	$cws_gradient_shape_type = isset($options['cws_bg_hover_gradient_shape_type']) ? $options['cws_bg_hover_gradient_shape_type'] : $cws_gradient_shape_type;
	$cws_gradient_size_keyword_type = isset($options['cws_bg_hover_gradient_size_keyword_type']) ? $options['cws_bg_hover_gradient_size_keyword_type'] : $cws_gradient_size_keyword_type;
	$cws_gradient_size_type = isset($options['cws_bg_hover_gradient_size_type']) ? $options['cws_bg_hover_gradient_size_type'] : $cws_gradient_size_type;
	
	$out = '';
	if ( $cws_gradient_type == 'linear' ) {
		$out .= "background: -webkit-linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
		$out .= "background: -o-linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
		$out .= "background: -moz-linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
		$out .= "background: linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
	}
	else if ( $cws_gradient_type == 'radial' ) {
		if ( $cws_gradient_shape_variant_type == 'simple' ) {
			$out .= "background: -webkit-radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -o-radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -moz-radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
		}
		else if ( $cws_gradient_shape_variant_type == 'extended' ) {
		
			$out .= "background: -webkit-radial-gradient(" . ( !empty( $cws_gradient_size_type ) ? " " . $cws_gradient_size_type . "," : "" ) . ( !empty( $cws_gradient_size_keyword_type ) ? " " . $cws_gradient_size_keyword_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -o-radial-gradient(" . ( !empty( $cws_gradient_size_type ) ? " " . $cws_gradient_size_type . "," : "" ) . ( !empty( $cws_gradient_size_keyword_type ) ? " " . $cws_gradient_size_keyword_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -moz-radial-gradient(" . ( !empty( $cws_gradient_size_type ) ? " " . $cws_gradient_size_type . "," : "" ) . ( !empty( $cws_gradient_size_keyword_type ) ? " " . $cws_gradient_size_keyword_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: radial-gradient(" . ( !empty( $cws_gradient_size_keyword_type ) && !empty( $cws_gradient_size_type ) ? " $cws_gradient_size_keyword_type at $cws_gradient_size_type" : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
		}
	}
	$out .= "border-color: transparent;-webkit-background-clip: border;-moz-background-clip: border;background-clip: border-box;-webkit-background-origin: border;-moz-background-origin: border;background-origin: border-box;";
	return preg_replace('/\s+/',' ', $out);
}

function cws_render_builder_gradient_rules( $options ) {
	extract(shortcode_atts(array(
		'cws_gradient_color_from' => "#000000",
		'cws_gradient_color_to' => '#0eecbd',
		'cws_gradient_type' => 'linear',
		'cws_gradient_angle' => '45',
		'cws_gradient_shape_variant_type' => 'simple',
		'cws_gradient_shape_type' => 'ellipse',
		'cws_gradient_size_keyword_type' => 'farthest-corner',
		'cws_gradient_size_type' => '',
	), $options));
	$out = '';
	if ( $cws_gradient_type == 'linear' ) {
		$out .= "background: -webkit-linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
		$out .= "background: -o-linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
		$out .= "background: -moz-linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
		$out .= "background: linear-gradient(" . $cws_gradient_angle . "deg, $cws_gradient_color_from, $cws_gradient_color_to);";
	}
	else if ( $cws_gradient_type == 'radial' ) {
		if ( $cws_gradient_shape_variant_type == 'simple' ) {
			$out .= "background: -webkit-radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -o-radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -moz-radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: radial-gradient(" . ( !empty( $cws_gradient_shape_type ) ? " " . $cws_gradient_shape_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
		}
		else if ( $cws_gradient_shape_variant_type == 'extended' ) {
		
			$out .= "background: -webkit-radial-gradient(" . ( !empty( $cws_gradient_size_type ) ? " " . $cws_gradient_size_type . "," : "" ) . ( !empty( $cws_gradient_size_keyword_type ) ? " " . $cws_gradient_size_keyword_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -o-radial-gradient(" . ( !empty( $cws_gradient_size_type ) ? " " . $cws_gradient_size_type . "," : "" ) . ( !empty( $cws_gradient_size_keyword_type ) ? " " . $cws_gradient_size_keyword_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: -moz-radial-gradient(" . ( !empty( $cws_gradient_size_type ) ? " " . $cws_gradient_size_type . "," : "" ) . ( !empty( $cws_gradient_size_keyword_type ) ? " " . $cws_gradient_size_keyword_type . "," : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
			$out .= "background: radial-gradient(" . ( !empty( $cws_gradient_size_keyword_type ) && !empty( $cws_gradient_size_type ) ? " $cws_gradient_size_keyword_type at $cws_gradient_size_type" : "" ) . " $cws_gradient_color_from, $cws_gradient_color_to);";
		}
	}
	$out .= "border-color: transparent;-webkit-background-clip: border;-moz-background-clip: border;background-clip: border-box;-webkit-background-origin: border;-moz-background-origin: border;background-origin: border-box;";
	return preg_replace('/\s+/',' ', $out);
}


?>