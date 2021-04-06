<?php
	if ( !class_exists( 'cws_ext_VC_Config' ) ){
		class cws_ext_VC_Config extends Metamax_Funcs{

			public function __construct ( $args = array() ){

				require_once(trailingslashit(get_template_directory()) . '/vc/vc_extends/cws_vc_extends.php');
				add_action( 'admin_init', array( $this, 'remove_meta_boxes' ) );
				add_action( 'admin_menu', array( $this, 'remove_grid_elements_menu' ) );
				add_action( 'vc_iconpicker-type-cws_flaticons', array( $this, 'add_cws_flaticons' ) );
				add_action( 'init', array( $this, 'remove_vc_elements' ) );
				
				add_action( 'init', array( $this, 'config' ) );
				if ( function_exists('cws_rewrite_slug') ){
					add_action( 'init', array( $this, 'extend_shortcodes' ) );
				}	
				add_action( 'init', array( $this, 'extend_params' ) );
				add_action( 'init', array( $this, 'modify_vc_elements' ) );
				add_action('admin_enqueue_scripts', array($this, 'cws_vc_init' ) );
			}
			public function add_cws_shortcode($name, $param1, $param2)  {
				$short = 'shortcode';
				call_user_func('vc_add_' . $short.$name, $param1, $param2);
			}			
			public function config (){
				vc_set_default_editor_post_types( array(
					'page',					
					'megamenu_item'
				)); 
			}
			public function get_defaults (){
				$this->args = wp_parse_args( $this->args, $this->defaults );
			}
			// Extend Composer with Theme Shortcodes
			public function extend_shortcodes (){
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_banners.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_vc_blog.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_button.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_carousel.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_call_to_action.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_categories.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_divider.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_embed.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_icon.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_image.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_milestone.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_msg_box.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_portfolio_posts_grid.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_pricing_plan.php' );	
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_progress_bar.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_services.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_roadmap.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_social_icons.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_staff_posts_grid.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_testimonial.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/theme_shortcodes/cws_sc_text.php' );
			}
			// Extend Composer with Custom Parametres
			public function extend_params (){
				require_once( trailingslashit( get_template_directory() ) . 'vc/params/cws_dropdown.php' );
				require_once( trailingslashit( get_template_directory() ) . 'vc/params/cws_svg.php' );
			}
			// Modify VC Elements
			public function modify_vc_elements (){
				if ( function_exists( 'vc_add'.'_shortcode_param' ) ) {
 					$this->add_cws_shortcode('_param' , 'cws_svg' , 'cws_vc_svg');
				}				
				vc_remove_param( 'vc_row', 'columns_placement' );
				vc_add_param('vc_row',array(
					"type" => "textfield",
					"heading" => esc_html__("Minimum Height", 'metamax'),
					"param_name"		=> "cws_row_min_height",
					"value" => '0px',
					"description"	=> esc_html__( 'Add a minimum height to the row so you can have a row without any content but still display it at a certain height. Such as a background with a video or image background but without any content', 'metamax' ),
				));	

				vc_remove_param( 'vc_tta_accordion', 'style' );
				vc_remove_param( 'vc_tta_accordion', 'shape' );
				vc_remove_param( 'vc_tta_accordion', 'color' );
				vc_remove_param( 'vc_tta_accordion', 'no_fill' );
				vc_remove_param( 'vc_tta_accordion', 'spacing' );
				vc_remove_param( 'vc_tta_accordion', 'gap' );

				vc_remove_param( 'vc_tta_tabs', 'style' );
				vc_remove_param( 'vc_tta_tabs', 'shape' );
				vc_remove_param( 'vc_tta_tabs', 'color' );
				vc_remove_param( 'vc_tta_tabs', 'no_fill_content_area' );
				vc_remove_param( 'vc_tta_tabs', 'spacing' );
				vc_remove_param( 'vc_tta_tabs', 'gap' );
				vc_remove_param( 'vc_tta_tabs', 'pagination_style' );
				vc_remove_param( 'vc_tta_tabs', 'pagination_color' );

				vc_remove_param( 'vc_toggle', 'style' );
				vc_remove_param( 'vc_toggle', 'color' );
				vc_remove_param( 'vc_toggle', 'size' );
				vc_remove_param( 'vc_toggle', 'use_custom_heading' );

				vc_remove_param( 'vc_images_carousel', 'partial_view' );	
			}
			// Remove VC Elements
			public function remove_vc_elements (){
				vc_remove_element( 'vc_separator' );
				vc_remove_element( 'vc_text_separator' );
				vc_remove_element( 'vc_message' );
				vc_remove_element( 'vc_gallery' );
				vc_remove_element( 'vc_tta_tour' );
				vc_remove_element( 'vc_tta_pageable' );
				vc_remove_element( 'vc_custom_heading' );
				vc_remove_element( 'vc_cta' );
				vc_remove_element( 'vc_posts_slider' );
				vc_remove_element( 'vc_progress_bar' );
				vc_remove_element( 'vc_basic_grid' );
				vc_remove_element( 'vc_media_grid' );
				vc_remove_element( 'vc_masonry_grid' );
				vc_remove_element( 'vc_masonry_media_grid' );
				vc_remove_element( 'vc_widget_sidebar' );
			}
			public function add_cws_flaticons ( $icons ){
				$icon_id = "";
				$fi_array = array();
				$fi_icons = cws_get_all_flaticon_icons();
				$fi_exists = is_array( $fi_icons ) && !empty( $fi_icons );				
				if ( !is_array( $fi_icons ) || empty( $fi_icons ) ){
					return $icons;
				}
				for ( $i = 0; $i < count( $fi_icons ); $i++ ){
					$icon_id = $fi_icons[$i];
					$icon_class = "flaticon-{$icon_id}";
					array_push( $fi_array, array( "$icon_class" => $icon_id ) );
				}
				$icons = array_merge( $icons, $fi_array );
				return $icons;
			}
			// Remove teaser metabox
			public function remove_meta_boxes() {
				remove_meta_box( 'vc_teaser', 'page', 		'side' );
				remove_meta_box( 'vc_teaser', 'post', 		'side' );
				remove_meta_box( 'vc_teaser', 'portfolio', 	'side' );
				remove_meta_box( 'vc_teaser', 'product', 	'side' );
			}
			// Remove 'Grid Elements' from Admin menu
			public function remove_grid_elements_menu(){
			  remove_menu_page( 'edit.php?post_type=vc_grid_item' );
			}
			public function cws_vc_init(){
				wp_enqueue_style( 'vc-css-styles', trailingslashit( get_template_directory_uri() ) . 'vc/vc_extends/css/cws_vc.css' );
			}
		}
	}
	/**/
	/* Config and enable extension */
	/**/
	$vc_config = new cws_ext_VC_Config ();
	/**/
	/* \Config and enable extension */


	if(!class_exists('VC_CWS_Background')){
		class VC_CWS_Background extends cws_ext_VC_Config{
			static public $row_atts = '';
			static public $column_atts = '';

			function __construct(){
				add_action('admin_init', array($this,'cws_extra_vc_params'));
			}

			/* -----> Start Customize VC_ROW <-----*/
			public static function cws_open_vc_shortcode($atts, $content){
				global $cws_theme_funcs;

				$first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

				extract( shortcode_atts( array(
					/* From cws_vc_extends.php -> cws_structure_background_props() */
					//Desktop
					"bg_position"					=> "center",
					"bg_size"						=> "cover",
					"bg_repeat"						=> "no-repeat",
					"bg_attachment"					=> "scroll",
					"custom_bg_position"			=> "",
					"custom_bg_size"				=> "",
					//Landscape
					"custom_styles_landscape" 		=> "",
					"customize_bg_landscape"		=> false,
					"bg_position_landscape"			=> "center",
					"bg_size_landscape"				=> "cover",
					"bg_repeat_landscape"			=> "no-repeat",
					"bg_attachment_landscape"		=> "scroll",
					"custom_bg_position_landscape"	=> "",
					"custom_bg_size_landscape"		=> "",
					"hide_bg_landscape" 			=> false,
					//Portrait
					"custom_styles_portrait" 		=> "",
					"customize_bg_portrait"			=> false,
					"bg_position_portrait"			=> "center",
					"bg_size_portrait"				=> "cover",
					"bg_repeat_portrait"			=> "no-repeat",
					"bg_attachment_portrait"		=> "scroll",
					"custom_bg_position_portrait"	=> "",
					"custom_bg_size_portrait"		=> "",
					"hide_bg_portrait" 				=> false,
					//Mobile
					"custom_styles_mobile" 			=> "",
					"customize_bg_mobile"			=> false,
					"bg_position_mobile"			=> "center",
					"bg_size_mobile"				=> "cover",
					"bg_repeat_mobile"				=> "no-repeat",
					"bg_attachment_mobile"			=> "scroll",
					"custom_bg_position_mobile"		=> "",
					"custom_bg_size_mobile"			=> "",
					"hide_bg_mobile" 				=> false,
					/*\ From cws_structure_background_props \*/

					/* Start Overlay Properties */
					"bg_cws_color" => "none",
					"cws_overlay_color" => $first_color,
					"cws_gradient_color_from" => "#000",
					"cws_gradient_color_to" => "#fff",
					"cws_gradient_opacity" => "50",
					"cws_gradient_type" => "linear",
					"cws_gradient_angle" => "45",
					"cws_gradient_shape_variant_type" => "simple",
					"cws_gradient_shape_type" => "ellipse",
					"cws_gradient_size_keyword_type" => "closest-side",
					"cws_gradient_size_type" => "60% 55%",
					/*\ End Overlay Properties \*/

					/* Start Extra Layer Properties */
					"add_layers" => false,
					"cws_layer_image" => "",
					"extra_layer_pos" => "left",
					"extra_layer_width" => "",
					"extra_layer_size" => "initial", //change
					"extra_layer_position" => "left top", //change
					"extra_layer_repeat" => "no-repeat",
					"extra_layer_bg" => "",
					"extra_layer_margin" => "0px 0px",
					"extra_layer_opacity" => "100",
					"hide_layer_landscape" => false,
					"hide_layer_portrait" => false,
					"hide_layer_mobile" => false,
					"z_index" => "",
					"shift" => "",
					/* End Extra Layer Properties */

                    /* Start Extra Layer Properties */
                    'particles' => false,
                    'particles_width' => '100%',
                    'particles_height' => '100%',
                    'particles_speed' => '2',
                    'particles_saturation' => '300',
                    'particles_left' => '',
                    'particles_top' => '',
                    'particles_size' => '40',
                    'particles_count' => '8',
                    'particles_start' => 'top-left',
                    'particles_hide' => '768',
                    'particles_color' => $first_color,
                    'add_waves' => false,
                    'wave_top_left' => false,
                    'wave_top_right' => false,
                    'wave_bottom_left' => false,
                    'wave_bottom_right' => false,
                    'add_canvas_background' => false,
                    'interactive_mouse' => true,
                    'dots_color' => $first_color,
                    /* End Extra Layer Properties */

				), $atts ) );

				/* -----> Variables declaration <----- */
				$out = $styles = $full_width = $extra_layer_styles = $particles_styles = $particles_wrap_styles = $particles_data = "";
				$id = uniqid( "cws_content_" );

				/* -----> Visual Composer Responsive styles <----- */
				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_landscape, $vc_landscape_styles); 
				$vc_landscape_styles = implode($vc_landscape_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_portrait, $vc_portrait_styles); 
				$vc_portrait_styles = implode($vc_portrait_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_mobile, $vc_mobile_styles); 
				$vc_mobile_styles = implode($vc_mobile_styles);

				/* -----> Customize default styles <----- */
				$styles .= "
					#".$id." > .vc_row{
						background-attachment: ".$bg_attachment." !important;
						background-repeat: ".$bg_repeat." !important;
					}
				";
				if( $bg_size == 'custom' && !empty($custom_bg_size) ){
					$styles .= "
						#".$id." > .vc_row{
							background-size: ".$custom_bg_size." !important;
						}
					";
				} else if( $bg_size == 'custom' && empty($custom_bg_size) ) {
					$styles .= "
						#".$id." > .vc_row{
							background-size: cover !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .vc_row{
							background-size: ".$bg_size." !important;
						}
					";
				}
				if( $bg_position == 'custom' && !empty($custom_bg_position) ){
					$styles .= "
						#".$id." > .vc_row{
							background-position: ".$custom_bg_position." !important;
						}
					";
				} else if( $bg_position == 'custom' && empty($custom_bg_position) ) {
					$styles .= "
						#".$id." > .vc_row{
							background-position: center center !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .vc_row{
							background-position: ".$bg_position." !important;
						}
					";
				}
				if( $bg_cws_color == 'color' && !empty($cws_overlay_color) ){
					$styles .= "
						#".$id." > .vc_row:before{
							background-color: ".$cws_overlay_color.";
						}
					";
				}
				if( $bg_cws_color == 'gradient' ){
					!empty($cws_gradient_color_from) ? $color1 = esc_attr($cws_gradient_color_from) : $color1 = '#000';
					!empty($cws_gradient_color_to) ? $color2 = esc_attr($cws_gradient_color_to) : $color2 = '#fff';
					!empty($cws_gradient_opacity) ? $opacity = (esc_attr($cws_gradient_opacity) / 100) : $opacity = 0;


					if( $cws_gradient_type == 'linear' ){
						!empty($cws_gradient_angle) ? $angle = (int)esc_attr($cws_gradient_angle) : $angle = 0;

						$styles .= "
							#".$id." > .vc_row:before{
								background: -webkit-linear-gradient(".$angle."deg, ".$color1.", ".$color2." );
								background: -moz-linear-gradient(".$angle."deg, ".$color1.", ".$color2." );
								background: -o-linear-gradient(".$angle."deg, ".$color1.", ".$color2." );
								background: linear-gradient(".$angle."deg, ".$color1.", ".$color2." );
								opacity: ".$opacity.";
							}
						";
					} else if( $cws_gradient_type == 'radial' ){
						!empty($cws_gradient_size_type) ? $size = esc_attr($cws_gradient_size_type) : $size = '0% 0%';

						if( $cws_gradient_shape_variant_type == 'simple' ){
							$styles .= "
								#".$id." > .vc_row:before{
									background: -webkit-radial-gradient(".$cws_gradient_shape_type.", ".$color1.", ".$color2." );
									background: -moz-radial-gradient(".$cws_gradient_shape_type.", ".$color1.", ".$color2." );
									background: -o-radial-gradient(".$cws_gradient_shape_type.", ".$color1.", ".$color2." );
									background: radial-gradient(".$cws_gradient_shape_type.", ".$color1.", ".$color2." );
									opacity: ".$opacity.";
								}
							";
						} else {
							$styles .= "
								#".$id." > .vc_row:before{
									background: -webkit-radial-gradient(".$size." ".$cws_gradient_size_keyword_type.", ".$color1." , ".$color2." );
									background: -moz-radial-gradient(".$size." ".$cws_gradient_size_keyword_type.", ".$color1." , ".$color2." );
									background: -o-radial-gradient(".$size." ".$cws_gradient_size_keyword_type.", ".$color1." , ".$color2." );
									background: radial-gradient(".$cws_gradient_size_keyword_type." at ".$size.", ".$color1." , ".$color2." );
									opacity: ".$opacity.";
								}
							";
						}
					}
				}
				if( !empty($z_index) ){
					$styles .= "
						#".$id." > .vc_row{
							position: relative;
							overflow: visible;
							z-index: ".(int)esc_attr($z_index).";
						}
					";
				}
				if( !empty($shift) ){
					$styles .= "
						#".$id."{
							position: relative;
							bottom: ".(int)esc_attr($shift)."px;
						}
					";
				}
				/* -----> End of default styles <----- */

				/* -----> Customize landscape styles <----- */
				if(
					!empty($custom_styles_landscape) || 
					$customize_bg_landscape || 
					$hide_bg_landscape || 
					$hide_layer_landscape 
				){
					$styles .= "
						@media 
							screen and (max-width: 1199px) /*Check, is device a tablet*/
						{
					";

						if( !empty($custom_styles_landscape) ){
							$styles .= "
								#".$id." > .vc_row{
									".$vc_landscape_styles."
								}
							";
						}
						if( $customize_bg_landscape ){
							$styles .= "
								#".$id." > .vc_row{
									background-attachment: ".$bg_attachment_landscape." !important;
									background-repeat: ".$bg_repeat_landscape." !important;
								}
							";
							if( $bg_size_landscape == 'custom' && !empty($custom_bg_size_landscape) ){
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$custom_bg_size_landscape." !important;
									}
								";
							} else if( $bg_size_landscape == 'custom' && empty($custom_bg_size_landscape) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$bg_size_landscape." !important;
									}
								";
							}
							if( $bg_position_landscape == 'custom' && !empty($custom_bg_position_landscape) ){
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$custom_bg_position_landscape." !important;
									}
								";
							} else if( $bg_position_landscape == 'custom' && empty($custom_bg_position_landscape) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$bg_position_landscape." !important;
									}
								";
							}
						}
						if( $hide_bg_landscape ){
							$styles .= "
								#".$id." > .vc_row{
									background-image: none !important;
								}
							";
						}
						if( $hide_layer_landscape ){
							$styles .= "
								#".$id." > .cws-layer{
									display: none;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of landscape styles <----- */

				/* -----> Customize portrait styles <----- */
				if(
					!empty($custom_styles_portrait) || 
					$customize_bg_portrait || 
					$hide_bg_portrait || 
					$hide_layer_portrait 
				){
					$styles .= "
						@media screen and (max-width: 991px){
					";

						if( !empty($custom_styles_portrait) ){
							$styles .= "
								#".$id." > .vc_row{
									".$vc_portrait_styles."
								}
							";
						}
						if( $customize_bg_portrait ){
							$styles .= "
								#".$id." > .vc_row{
									background-attachment: ".$bg_attachment_portrait." !important;
									background-repeat: ".$bg_repeat_portrait." !important;
								}
							";
							if( $bg_size_portrait == 'custom' && !empty($custom_bg_size_portrait) ){
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$custom_bg_size_portrait." !important;
									}
								";
							} else if( $bg_size_portrait == 'custom' && empty($custom_bg_size_portrait) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$bg_size_portrait." !important;
									}
								";
							}
							if( $bg_position_portrait == 'custom' && !empty($custom_bg_position_portrait) ){
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$custom_bg_position_portrait." !important;
									}
								";
							} else if( $bg_position_portrait == 'custom' && empty($custom_bg_position_portrait) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$bg_position_portrait." !important;
									}
								";
							}
						}
						if( $hide_bg_portrait ){
							$styles .= "
								#".$id." > .vc_row{
									background-image: none !important;
								}
							";
						}
						if( $hide_layer_portrait ){
							$styles .= "
								#".$id." > .cws-layer{
									display: none;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of portrait styles <----- */

				/* -----> Customize mobile styles <----- */
				if(
					!empty($custom_styles_mobile) || 
					$customize_bg_mobile || 
					$hide_bg_mobile || 
					$hide_layer_mobile 
				){
					$styles .= "
						@media screen and (max-width: 767px){
					";

						if( !empty($custom_styles_mobile) ){
							$styles .= "
								#".$id." > .vc_row{
									".$vc_mobile_styles."
								}
							";
						}
						if( $customize_bg_mobile ){
							$styles .= "
								#".$id." > .vc_row{
									background-attachment: ".$bg_attachment_mobile." !important;
									background-repeat: ".$bg_repeat_mobile." !important;
								}
							";
							if( $bg_size_mobile == 'custom' && !empty($custom_bg_size_mobile) ){
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$custom_bg_size_mobile." !important;
									}
								";
							} else if( $bg_size_mobile == 'custom' && empty($custom_bg_size_mobile) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$bg_size_mobile." !important;
									}
								";
							}
							if( $bg_position_mobile == 'custom' && !empty($custom_bg_position_mobile) ){
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$custom_bg_position_mobile." !important;
									}
								";
							} else if( $bg_position_mobile == 'custom' && empty($custom_bg_position_mobile) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$bg_position_mobile." !important;
									}
								";
							}
						}
						if( $hide_bg_mobile ){
							$styles .= "
								#".$id." > .vc_row{
									background-image: none !important;
								}
							";
						}
						if( $hide_layer_mobile ){
							$styles .= "
								#".$id." > .cws-layer{
									display: none;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of mobile styles <----- */


				/* -----> Custom VC_ROW output <----- */
				$out = '<!-- Start CWS Row -->';
				$out .= '<div id="'.$id.'" class="cws-content">';
					if( !empty($styles) ){
						Cws_shortcode_css()->enqueue_cws_css($styles);
					}

					/*-----> Get VC_ROW properties <-----*/
					$sc_obj = Vc_Shortcodes_Manager::getInstance()->getElementClass( 'vc_row' );
					$row_class_vc = vc_map_get_attributes( $sc_obj->getShortcode(), $atts );
					extract( $row_class_vc );

					$extra_layer_classes = "";
					$extra_layer_atts = "";

					if( !empty($full_width) ){
						$extra_layer_classes .= " cws-stretch-row";
						$extra_layer_atts .= " data-vc-full-width='true' data-vc-full-width-init='false'";
					}

					/*-----> Extra Layer Input <-----*/
					if( $add_layers ){
						if( !empty($cws_layer_image) ){
							$src = wp_get_attachment_image_src($cws_layer_image, 'full');
							$extra_layer_styles .= 'background-image:url("'.esc_attr($src[0]).'");';
						}

						$extra_layer_styles .= "
							".(!empty($extra_layer_pos) ? $extra_layer_pos.":0%;" : '')."
							".(!empty($extra_layer_width) ? " width:".(float)esc_attr($extra_layer_width)."% !important;" : '')."
							".(!empty($extra_layer_size) ? " background-size:".$extra_layer_size.";" : '')."
							".(!empty($extra_layer_position) ? " background-position:".$extra_layer_position.";" : '')."
							".(!empty($extra_layer_repeat) ? " background-repeat:".$extra_layer_repeat.";" : '')."
							".(!empty($extra_layer_bg) ? " background-color:".$extra_layer_bg.";" : '')."
							".(!empty($extra_layer_margin) ? " margin: ".esc_attr($extra_layer_margin).";" : 'cws_vc_config.php')."
							".(!empty($extra_layer_opacity) ? " opacity: ".( (int)esc_attr($extra_layer_opacity) / 100 ).";" : '')."
						";

						$out .= "<div class='cws-layer".$extra_layer_classes."'".$extra_layer_atts.">";
							$out .= "<div style='".esc_attr($extra_layer_styles)."'></div>";
						$out .= "</div>";

						if( !empty($full_width) ){
							$out .= "<div class='vc_row-full-width vc_clearfix'></div>";
						}
					}

                /*-----> Particles <-----*/
                if( $particles ){
                    wp_enqueue_script( 'particles' );

                    $particles_id = uniqid('particles-');

                    if( !empty($particles_color) ){
                        $particles_data .= " data-color='".esc_attr($particles_color)."'";
                    }

                    if( !empty($particles_saturation) ){
                        $particles_data .= " data-saturation='".(int)esc_attr($particles_saturation)."'";
                    }
                    if( !empty($particles_size) ){
                        $particles_data .= " data-size='".(float)esc_attr($particles_size)."'";
                    }
                    if( !empty($particles_count) ){
                        $particles_data .= " data-count='".(float)esc_attr($particles_count)."'";
                    }
                    if( !empty($particles_speed) ){
                        $particles_data .= " data-speed='".(float)esc_attr($particles_speed)."'";
                    }
                    if( !empty($particles_hide) ){
                        $particles_data .= " data-hide='".(int)esc_attr($particles_hide)."'";
                    }

                    $particles_data .= " data-image='".esc_url(get_template_directory_uri())."'";

                    if( !empty($particles_width) ){
                        $particles_styles .= "
								width: ".esc_attr($particles_width).";
							";
                    }
                    if( !empty($particles_height) ){
                        $particles_styles .= "
								height: ".esc_attr($particles_height).";
							";
                    }
                    if( !empty($particles_left) ){
                        $particles_styles .= "
								margin-left: ".esc_attr($particles_left).";
							";
                    }
                    if( !empty($particles_top) ){
                        $particles_wrap_styles .= "
								margin-top: ".esc_attr($particles_top).";
							";
                    }

                    $out .= "<div class='particles-wrapper' ". (!empty($particles_wrap_styles) ? 'style="'.$particles_wrap_styles.'"' : '') .">";
                    $out .= "<div id='".$particles_id."' class='particles-js ".$particles_start."' ".$particles_data." style='".$particles_styles."'></div>";
                    $out .= "</div>";

                    if( !empty($full_width) ){
                        $out .= "<div class='vc_row-full-width vc_clearfix'></div>";
                    }
                }

                /*-----> Waves <-----*/
                if ( $add_waves ) {
                    $out .= '<div class="bg-waves">';
                    if ( $wave_top_left ) {
                        $out .= '<div class="wave wave-top-left">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/left_top_02.png" alt="left_top_02">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/left_top_01.png" alt="left_top_01">';
                        $out .= '</div>';
                    }
                    if ( $wave_top_right ) {
                        $out .= '<div class="wave wave-top-right">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/right_top_02.png" alt="right_top_02">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/right_top_01.png" alt="right_top_01">';
                        $out .= '</div>';
                    }
                    if ( $wave_bottom_left ) {
                        $out .= '<div class="wave wave-bottom-left">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/left_bottom_02.png" alt="left_bottom_02">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/left_bottom_01.png" alt="left_bottom_01">';
                        $out .= '</div>';
                    }
                    if ( $wave_bottom_right ) {
                        $out .= '<div class="wave wave-bottom-right">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/right_bottom_02.png" alt="right_bottom_02">';
                            $out .= '<img class="layer" src="'.esc_url( get_template_directory_uri()).'/img/waves/right_bottom_01.png" alt="right_bottom_01">';
                        $out .= '</div>';
                    }
                    $out .= '</div>';
                }

				return $out;
			}
			public static function cws_close_vc_shortcode($atts, $content){
				$out = "</div>";
				$out .= '<!-- End CWS Row -->';

				return $out;
			}
			/*\ -----> End Customize VC_ROW <-----\*/


			/* -----> Start Customize VC_COLUMN <-----*/
			public static function cws_open_vc_shortcode_column($atts, $content){
				global $cws_theme_funcs;

				extract( shortcode_atts( array(
					/* From cws_vc_extends.php -> cws_structure_background_props() */
					//Desktop
					"bg_position"					=> "center",
					"bg_size"						=> "cover",
					"bg_repeat"						=> "no-repeat",
					"bg_attachment"					=> "scroll",
					"custom_bg_position"			=> "",
					"custom_bg_size"				=> "",
					//Landscape
					"custom_styles_landscape" 		=> "",
					"customize_bg_landscape"		=> false,
					"bg_position_landscape"			=> "center",
					"bg_size_landscape"				=> "cover",
					"bg_repeat_landscape"			=> "no-repeat",
					"bg_attachment_landscape"		=> "scroll",
					"custom_bg_position_landscape"	=> "",
					"custom_bg_size_landscape"		=> "",
					"hide_bg_landscape" 			=> false,
					//Portrait
					"custom_styles_portrait" 		=> "",
					"customize_bg_portrait"			=> false,
					"bg_position_portrait"			=> "center",
					"bg_size_portrait"				=> "cover",
					"bg_repeat_portrait"			=> "no-repeat",
					"bg_attachment_portrait"		=> "scroll",
					"custom_bg_position_portrait"	=> "",
					"custom_bg_size_portrait"		=> "",
					"hide_bg_portrait" 				=> false,
					//Mobile
					"custom_styles_mobile" 			=> "",
					"customize_bg_mobile"			=> false,
					"bg_position_mobile"			=> "center",
					"bg_size_mobile"				=> "cover",
					"bg_repeat_mobile"				=> "no-repeat",
					"bg_attachment_mobile"			=> "scroll",
					"custom_bg_position_mobile"		=> "",
					"custom_bg_size_mobile"			=> "",
					"hide_bg_mobile" 				=> false,
					/*\ From cws_structure_background_props \*/
					"place_ahead"					=> false,
				), $atts ) );

				/* -----> Variables declaration <----- */
				$out = $styles = $offset = $width = "";
				$id = uniqid( "cws-column-" );

				/* -----> Visual Composer Responsive styles <----- */
				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_landscape, $vc_landscape_styles); 
				$vc_landscape_styles = implode($vc_landscape_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_portrait, $vc_portrait_styles); 
				$vc_portrait_styles = implode($vc_portrait_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_mobile, $vc_mobile_styles); 
				$vc_mobile_styles = implode($vc_mobile_styles);

				/* -----> Customize default styles <----- */
				$styles .= "
					#".$id." > .wpb_column > .vc_column-inner{
						background-attachment: ".$bg_attachment." !important;
						background-repeat: ".$bg_repeat." !important;
					}
				";
				if( $bg_size == 'custom' && !empty($custom_bg_size) ){
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-size: ".$custom_bg_size." !important;
						}
					";
				} else if( $bg_size == 'custom' && empty($custom_bg_size) ) {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-size: cover !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-size: ".$bg_size." !important;
						}
					";
				}
				if( $bg_position == 'custom' && !empty($custom_bg_position) ){
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-position: ".$custom_bg_position." !important;
						}
					";
				} else if( $bg_position == 'custom' && empty($custom_bg_position) ) {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-position: center center !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-position: ".$bg_position." !important;
						}
					";
				}
				/* -----> End of default styles <----- */

				/* -----> Customize landscape styles <----- */
				if(
					!empty($custom_styles_landscape) || 
					$customize_bg_landscape || 
					$hide_bg_landscape 
				){
					$styles .= "
						@media 
							screen and (max-width: 1199px) /*Check, is device a tablet*/
						{
					";

						if( !empty($custom_styles_landscape) ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									".$vc_landscape_styles."
								}
							";
						}
						if( $customize_bg_landscape ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-attachment: ".$bg_attachment_landscape." !important;
									background-repeat: ".$bg_repeat_landscape." !important;
								}
							";
							if( $bg_size_landscape == 'custom' && !empty($custom_bg_size_landscape) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$custom_bg_size_landscape." !important;
									}
								";
							} else if( $bg_size_landscape == 'custom' && empty($custom_bg_size_landscape) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$bg_size_landscape." !important;
									}
								";
							}
							if( $bg_position_landscape == 'custom' && !empty($custom_bg_position_landscape) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$custom_bg_position_landscape." !important;
									}
								";
							} else if( $bg_position_landscape == 'custom' && empty($custom_bg_position_landscape) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$bg_position_landscape." !important;
									}
								";
							}
						}
						if( $hide_bg_landscape ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of landscape styles <----- */

				/* -----> Customize portrait styles <----- */
				if(
					!empty($custom_styles_portrait) || 
					$customize_bg_portrait || 
					$hide_bg_portrait 
				){
					$styles .= "
						@media screen and (max-width: 991px){
					";

						if( !empty($custom_styles_portrait) ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									".$vc_portrait_styles."
								}
							";
						}
						if( $customize_bg_portrait ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-attachment: ".$bg_attachment_portrait." !important;
									background-repeat: ".$bg_repeat_portrait." !important;
								}
							";
							if( $bg_size_portrait == 'custom' && !empty($custom_bg_size_portrait) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$custom_bg_size_portrait." !important;
									}
								";
							} else if( $bg_size_portrait == 'custom' && empty($custom_bg_size_portrait) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$bg_size_portrait." !important;
									}
								";
							}
							if( $bg_position_portrait == 'custom' && !empty($custom_bg_position_portrait) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$custom_bg_position_portrait." !important;
									}
								";
							} else if( $bg_position_portrait == 'custom' && empty($custom_bg_position_portrait) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$bg_position_portrait." !important;
									}
								";
							}
						}
						if( $hide_bg_portrait ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of portrait styles <----- */

				/* -----> Customize mobile styles <----- */
				if(
					!empty($custom_styles_mobile) || 
					$customize_bg_mobile || 
					$hide_bg_mobile || 
					$place_ahead 
				){
					$styles .= "
						@media screen and (max-width: 767px){
					";

						if( !empty($custom_styles_mobile) ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									".$vc_mobile_styles."
								}
							";
						}
						if( $customize_bg_mobile ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-attachment: ".$bg_attachment_mobile." !important;
									background-repeat: ".$bg_repeat_mobile." !important;
								}
							";
							if( $bg_size_mobile == 'custom' && !empty($custom_bg_size_mobile) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$custom_bg_size_mobile." !important;
									}
								";
							} else if( $bg_size_mobile == 'custom' && empty($custom_bg_size_mobile) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$bg_size_mobile." !important;
									}
								";
							}
							if( $bg_position_mobile == 'custom' && !empty($custom_bg_position_mobile) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$custom_bg_position_mobile." !important;
									}
								";
							} else if( $bg_position_mobile == 'custom' && empty($custom_bg_position_mobile) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$bg_position_mobile." !important;
									}
								";
							}
						}
						if( $hide_bg_mobile ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-image: none !important;
								}
							";
						}
						if( $place_ahead ){
							$styles .= "
								#".$id."{
									-ms-flex-order: -1;
									 -webkit-order: -1;
											 order: -1;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of mobile styles <----- */

				/*-----> Get VC_ROW properties <-----*/
				$sc_obj = Vc_Shortcodes_Manager::getInstance()->getElementClass('vc_column');
				$atts = vc_map_get_attributes( $sc_obj->getShortcode(), $atts );
				extract( $atts );

				$width = wpb_translateColumnWidthToSpan( $width );
				$width = vc_column_offset_class_merge( $offset, $width );

				/* -----> Custom VC_COLUMN output <----- */
				$out .= '<!-- Start CWS Column --> ';
				$out .= "<div id='".$id."' class='cws-column-wrapper ".$width."'>";
					if( !empty($styles) ){
						Cws_shortcode_css()->enqueue_cws_css($styles);
					}

				return $out;
			}
			public static function cws_close_vc_shortcode_column($atts, $content){	
				$out = "</div>";
				$out .= '<!-- End CWS Column --> ';
				return $out;
			}
			/*\ -----> End Customize VC_COLUMN <-----\*/


			/* -----> Start Customize VC_ROW_INNER <-----*/
			public static function cws_open_vc_shortcode_row_inner($atts, $content){
				global $cws_theme_funcs;

				extract( shortcode_atts( array(
					/* From cws_vc_extends.php -> cws_structure_background_props() */
					//Desktop
					"bg_position"					=> "center",
					"bg_size"						=> "cover",
					"bg_repeat"						=> "no-repeat",
					"bg_attachment"					=> "scroll",
					"custom_bg_position"			=> "",
					"custom_bg_size"				=> "",
					"add_shadow"					=> false,
					//Landscape
					"custom_styles_landscape" 		=> "",
					"customize_bg_landscape"		=> false,
					"bg_position_landscape"			=> "center",
					"bg_size_landscape"				=> "cover",
					"bg_repeat_landscape"			=> "no-repeat",
					"bg_attachment_landscape"		=> "scroll",
					"custom_bg_position_landscape"	=> "",
					"custom_bg_size_landscape"		=> "",
					"hide_bg_landscape" 			=> false,
					//Portrait
					"custom_styles_portrait" 		=> "",
					"customize_bg_portrait"			=> false,
					"bg_position_portrait"			=> "center",
					"bg_size_portrait"				=> "cover",
					"bg_repeat_portrait"			=> "no-repeat",
					"bg_attachment_portrait"		=> "scroll",
					"custom_bg_position_portrait"	=> "",
					"custom_bg_size_portrait"		=> "",
					"hide_bg_portrait" 				=> false,
					//Mobile
					"custom_styles_mobile" 			=> "",
					"customize_bg_mobile"			=> false,
					"bg_position_mobile"			=> "center",
					"bg_size_mobile"				=> "cover",
					"bg_repeat_mobile"				=> "no-repeat",
					"bg_attachment_mobile"			=> "scroll",
					"custom_bg_position_mobile"		=> "",
					"custom_bg_size_mobile"			=> "",
					"hide_bg_mobile" 				=> false,
					/*\ From cws_structure_background_props \*/
				), $atts ) );

				/* -----> Variables declaration <----- */
				$out = $styles = "";
				$id = uniqid( "cws_inner_row_" );

				/* -----> Visual Composer Responsive styles <----- */
				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_landscape, $vc_landscape_styles); 
				$vc_landscape_styles = implode($vc_landscape_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_portrait, $vc_portrait_styles); 
				$vc_portrait_styles = implode($vc_portrait_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_mobile, $vc_mobile_styles); 
				$vc_mobile_styles = implode($vc_mobile_styles);

				/* -----> Customize default styles <----- */
				$styles .= "
					#".$id." > .vc_row{
						background-attachment: ".$bg_attachment." !important;
						background-repeat: ".$bg_repeat." !important;
					}
				";
				if( $bg_size == 'custom' && !empty($custom_bg_size) ){
					$styles .= "
						#".$id." > .vc_row{
							background-size: ".$custom_bg_size." !important;
						}
					";
				} else if( $bg_size == 'custom' && empty($custom_bg_size) ) {
					$styles .= "
						#".$id." > .vc_row{
							background-size: cover !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .vc_row{
							background-size: ".$bg_size." !important;
						}
					";
				}
				if( $bg_position == 'custom' && !empty($custom_bg_position) ){
					$styles .= "
						#".$id." > .vc_row{
							background-position: ".$custom_bg_position." !important;
						}
					";
				} else if( $bg_position == 'custom' && empty($custom_bg_position) ) {
					$styles .= "
						#".$id." > .vc_row{
							background-position: center center !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .vc_row{
							background-position: ".$bg_position." !important;
						}
					";
				}
				/* -----> End of default styles <----- */

				/* -----> Customize landscape styles <----- */
				if(
					!empty($custom_styles_landscape) || 
					$customize_bg_landscape || 
					$hide_bg_landscape 
				){
					$styles .= "
						@media 
							screen and (max-width: 1199px) /*Check, is device a tablet*/
						{
					";

						if( !empty($custom_styles_landscape) ){
							$styles .= "
								#".$id." > .vc_row{
									".$vc_landscape_styles."
								}
							";
						}
						if( $customize_bg_landscape ){
							$styles .= "
								#".$id." > .vc_row{
									background-attachment: ".$bg_attachment_landscape." !important;
									background-repeat: ".$bg_repeat_landscape." !important;
								}
							";
							if( $bg_size_landscape == 'custom' && !empty($custom_bg_size_landscape) ){
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$custom_bg_size_landscape." !important;
									}
								";
							} else if( $bg_size_landscape == 'custom' && empty($custom_bg_size_landscape) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$bg_size_landscape." !important;
									}
								";
							}
							if( $bg_position_landscape == 'custom' && !empty($custom_bg_position_landscape) ){
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$custom_bg_position_landscape." !important;
									}
								";
							} else if( $bg_position_landscape == 'custom' && empty($custom_bg_position_landscape) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$bg_position_landscape." !important;
									}
								";
							}
						}
						if( $hide_bg_landscape ){
							$styles .= "
								#".$id." > .vc_row{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of landscape styles <----- */

				/* -----> Customize portrait styles <----- */
				if(
					!empty($custom_styles_portrait) || 
					$customize_bg_portrait || 
					$hide_bg_portrait 
				){
					$styles .= "
						@media screen and (max-width: 991px){
					";

						if( !empty($custom_styles_portrait) ){
							$styles .= "
								#".$id." > .vc_row{
									".$vc_portrait_styles."
								}
							";
						}
						if( $customize_bg_portrait ){
							$styles .= "
								#".$id." > .vc_row{
									background-attachment: ".$bg_attachment_portrait." !important;
									background-repeat: ".$bg_repeat_portrait." !important;
								}
							";
							if( $bg_size_portrait == 'custom' && !empty($custom_bg_size_portrait) ){
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$custom_bg_size_portrait." !important;
									}
								";
							} else if( $bg_size_portrait == 'custom' && empty($custom_bg_size_portrait) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$bg_size_portrait." !important;
									}
								";
							}
							if( $bg_position_portrait == 'custom' && !empty($custom_bg_position_portrait) ){
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$custom_bg_position_portrait." !important;
									}
								";
							} else if( $bg_position_portrait == 'custom' && empty($custom_bg_position_portrait) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$bg_position_portrait." !important;
									}
								";
							}
						}
						if( $hide_bg_portrait ){
							$styles .= "
								#".$id." > .vc_row{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of portrait styles <----- */

				/* -----> Customize mobile styles <----- */
				if(
					!empty($custom_styles_mobile) || 
					$customize_bg_mobile || 
					$hide_bg_mobile 
				){
					$styles .= "
						@media screen and (max-width: 767px){
					";

						if( !empty($custom_styles_mobile) ){
							$styles .= "
								#".$id." > .vc_row{
									".$vc_mobile_styles."
								}
							";
						}
						if( $customize_bg_mobile ){
							$styles .= "
								#".$id." > .vc_row{
									background-attachment: ".$bg_attachment_mobile." !important;
									background-repeat: ".$bg_repeat_mobile." !important;
								}
							";
							if( $bg_size_mobile == 'custom' && !empty($custom_bg_size_mobile) ){
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$custom_bg_size_mobile." !important;
									}
								";
							} else if( $bg_size_mobile == 'custom' && empty($custom_bg_size_mobile) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-size: ".$bg_size_mobile." !important;
									}
								";
							}
							if( $bg_position_mobile == 'custom' && !empty($custom_bg_position_mobile) ){
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$custom_bg_position_mobile." !important;
									}
								";
							} else if( $bg_position_mobile == 'custom' && empty($custom_bg_position_mobile) ) {
								$styles .= "
									#".$id." > .vc_row{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .vc_row{
										background-position: ".$bg_position_mobile." !important;
									}
								";
							}
						}
						if( $hide_bg_mobile ){
							$styles .= "
								#".$id." > .vc_row{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of mobile styles <----- */

				/* -----> Custom VC_ROW_INNER output <----- */
				$out = "<!-- Start CWS Inner Row --> ";
				$out .= "<div id='".$id."' class='cws-inner-row-wrapper".($add_shadow ? ' shadow' : '')."'>";
					if( !empty($styles) ){
						Cws_shortcode_css()->enqueue_cws_css($styles);
					}

				return $out;
			}
			public static function cws_close_vc_shortcode_row_inner($atts, $content){
				$out = "</div>";
				$out .= '<!-- End CWS Inner Row --> ';
				return $out;
			}
			/* -----> End Customize VC_ROW_INNER <-----*/


			/* -----> Start Customize VC_COLUMN_INNER <-----*/
			public static function cws_open_vc_shortcode_column_inner($atts, $content){
				global $cws_theme_funcs;

				extract( shortcode_atts( array(
					/* From cws_vc_extends.php -> cws_structure_background_props() */
					//Desktop
					"bg_position"					=> "center",
					"bg_size"						=> "cover",
					"bg_repeat"						=> "no-repeat",
					"bg_attachment"					=> "scroll",
					"custom_bg_position"			=> "",
					"custom_bg_size"				=> "",
					//Landscape
					"custom_styles_landscape" 		=> "",
					"customize_bg_landscape"		=> false,
					"bg_position_landscape"			=> "center",
					"bg_size_landscape"				=> "cover",
					"bg_repeat_landscape"			=> "no-repeat",
					"bg_attachment_landscape"		=> "scroll",
					"custom_bg_position_landscape"	=> "",
					"custom_bg_size_landscape"		=> "",
					"hide_bg_landscape" 			=> false,
					//Portrait
					"custom_styles_portrait" 		=> "",
					"customize_bg_portrait"			=> false,
					"bg_position_portrait"			=> "center",
					"bg_size_portrait"				=> "cover",
					"bg_repeat_portrait"			=> "no-repeat",
					"bg_attachment_portrait"		=> "scroll",
					"custom_bg_position_portrait"	=> "",
					"custom_bg_size_portrait"		=> "",
					"hide_bg_portrait" 				=> false,
					//Mobile
					"custom_styles_mobile" 			=> "",
					"customize_bg_mobile"			=> false,
					"bg_position_mobile"			=> "center",
					"bg_size_mobile"				=> "cover",
					"bg_repeat_mobile"				=> "no-repeat",
					"bg_attachment_mobile"			=> "scroll",
					"custom_bg_position_mobile"		=> "",
					"custom_bg_size_mobile"			=> "",
					"hide_bg_mobile" 				=> false,
					/*\ From cws_structure_background_props \*/
					"place_ahead"					=> false,
				), $atts ) );

				/* -----> Variables declaration <----- */
				$out = $styles = $offset = $width = "";
				$id = uniqid( "cws-column-" );

				/* -----> Visual Composer Responsive styles <----- */
				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_landscape, $vc_landscape_styles); 
				$vc_landscape_styles = implode($vc_landscape_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_portrait, $vc_portrait_styles); 
				$vc_portrait_styles = implode($vc_portrait_styles);

				preg_match("/(?<=\{).+?(?=\})/", $custom_styles_mobile, $vc_mobile_styles); 
				$vc_mobile_styles = implode($vc_mobile_styles);

				/* -----> Customize default styles <----- */
				$styles .= "
					#".$id." > .wpb_column > .vc_column-inner{
						background-attachment: ".$bg_attachment." !important;
						background-repeat: ".$bg_repeat." !important;
					}
				";
				if( $bg_size == 'custom' && !empty($custom_bg_size) ){
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-size: ".$custom_bg_size." !important;
						}
					";
				} else if( $bg_size == 'custom' && empty($custom_bg_size) ) {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-size: cover !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-size: ".$bg_size." !important;
						}
					";
				}
				if( $bg_position == 'custom' && !empty($custom_bg_position) ){
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-position: ".$custom_bg_position." !important;
						}
					";
				} else if( $bg_position == 'custom' && empty($custom_bg_position) ) {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-position: center center !important;
						}
					";
				} else {
					$styles .= "
						#".$id." > .wpb_column > .vc_column-inner{
							background-position: ".$bg_position." !important;
						}
					";
				}
				/* -----> End of default styles <----- */

				/* -----> Customize landscape styles <----- */
				if(
					!empty($custom_styles_landscape) || 
					$customize_bg_landscape || 
					$hide_bg_landscape 
				){
					$styles .= "
						@media 
							screen and (max-width: 1199px) /*Check, is device a tablet*/
						{
					";

						if( !empty($custom_styles_landscape) ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									".$vc_landscape_styles."
								}
							";
						}
						if( $customize_bg_landscape ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-attachment: ".$bg_attachment_landscape." !important;
									background-repeat: ".$bg_repeat_landscape." !important;
								}
							";
							if( $bg_size_landscape == 'custom' && !empty($custom_bg_size_landscape) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$custom_bg_size_landscape." !important;
									}
								";
							} else if( $bg_size_landscape == 'custom' && empty($custom_bg_size_landscape) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$bg_size_landscape." !important;
									}
								";
							}
							if( $bg_position_landscape == 'custom' && !empty($custom_bg_position_landscape) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$custom_bg_position_landscape." !important;
									}
								";
							} else if( $bg_position_landscape == 'custom' && empty($custom_bg_position_landscape) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$bg_position_landscape." !important;
									}
								";
							}
						}
						if( $hide_bg_landscape ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of landscape styles <----- */

				/* -----> Customize portrait styles <----- */
				if(
					!empty($custom_styles_portrait) || 
					$customize_bg_portrait || 
					$hide_bg_portrait 
				){
					$styles .= "
						@media screen and (max-width: 991px){
					";

						if( !empty($custom_styles_portrait) ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									".$vc_portrait_styles."
								}
							";
						}
						if( $customize_bg_portrait ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-attachment: ".$bg_attachment_portrait." !important;
									background-repeat: ".$bg_repeat_portrait." !important;
								}
							";
							if( $bg_size_portrait == 'custom' && !empty($custom_bg_size_portrait) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$custom_bg_size_portrait." !important;
									}
								";
							} else if( $bg_size_portrait == 'custom' && empty($custom_bg_size_portrait) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$bg_size_portrait." !important;
									}
								";
							}
							if( $bg_position_portrait == 'custom' && !empty($custom_bg_position_portrait) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$custom_bg_position_portrait." !important;
									}
								";
							} else if( $bg_position_portrait == 'custom' && empty($custom_bg_position_portrait) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$bg_position_portrait." !important;
									}
								";
							}
						}
						if( $hide_bg_portrait ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-image: none !important;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of portrait styles <----- */

				/* -----> Customize mobile styles <----- */
				if(
					!empty($custom_styles_mobile) || 
					$customize_bg_mobile || 
					$hide_bg_mobile || 
					$place_ahead 
				){
					$styles .= "
						@media screen and (max-width: 767px){
					";

						if( !empty($custom_styles_mobile) ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									".$vc_mobile_styles."
								}
							";
						}
						if( $customize_bg_mobile ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-attachment: ".$bg_attachment_mobile." !important;
									background-repeat: ".$bg_repeat_mobile." !important;
								}
							";
							if( $bg_size_mobile == 'custom' && !empty($custom_bg_size_mobile) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$custom_bg_size_mobile." !important;
									}
								";
							} else if( $bg_size_mobile == 'custom' && empty($custom_bg_size_mobile) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: cover !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-size: ".$bg_size_mobile." !important;
									}
								";
							}
							if( $bg_position_mobile == 'custom' && !empty($custom_bg_position_mobile) ){
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$custom_bg_position_mobile." !important;
									}
								";
							} else if( $bg_position_mobile == 'custom' && empty($custom_bg_position_mobile) ) {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: center center !important;
									}
								";
							} else {
								$styles .= "
									#".$id." > .wpb_column > .vc_column-inner{
										background-position: ".$bg_position_mobile." !important;
									}
								";
							}
						}
						if( $hide_bg_mobile ){
							$styles .= "
								#".$id." > .wpb_column > .vc_column-inner{
									background-image: none !important;
								}
							";
						}
						if( $place_ahead ){
							$styles .= "
								#".$id."{
									-ms-flex-order: -1;
									 -webkit-order: -1;
											 order: -1;
								}
							";
						}

					$styles .= "
						}
					";
				}
				/* -----> End of mobile styles <----- */

				/*-----> Get VC_ROW properties <-----*/
				$sc_obj = Vc_Shortcodes_Manager::getInstance()->getElementClass('vc_column');
				$atts = vc_map_get_attributes( $sc_obj->getShortcode(), $atts );
				extract( $atts );

				$width = wpb_translateColumnWidthToSpan( $width );
				$width = vc_column_offset_class_merge( $offset, $width );

				/* -----> Custom VC_COLUMN output <----- */
				$out .= '<!-- Start CWS Inner Column --> ';
				$out .= "<div id='".$id."' class='cws-column-wrapper ".$width."'>";
					if( !empty($styles) ){
						Cws_shortcode_css()->enqueue_cws_css($styles);
					}

				return $out;
			}
			public static function cws_close_vc_shortcode_column_inner($atts, $content){	
				$out = "</div>";
				$out .= '<!-- End CWS Inner Column --> ';
				return $out;
			}
			/* -----> End Customize VC_COLUMN_INNER <-----*/


			function cws_extra_vc_params(){
				global $cws_theme_funcs;

				/* -----> STYLING GROUP TITLES <----- */
				$group_name = esc_html__('Design Options', 'metamax');
				$landscape_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_landscape-tablets'></i>";
				$portrait_group = esc_html__('Tablet', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-tablets'></i>";
				$mobile_group = esc_html__('Mobile', 'metamax')."&nbsp;&nbsp;&nbsp;<i class='vc-composer-icon vc-c-icon-layout_portrait-smartphones'></i>";

				$first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

				if(function_exists('vc_add_param')){
					/*-----> Extra VC_Row Params <-----*/
					cws_structure_background_props('vc_row');
					//VC_Row Overlay Properties
					vc_add_param(
						'vc_row',
						array(
							"type" 			=> "dropdown",
							"heading" 		=> esc_html__("Overlay", 'metamax'),
							"param_name"	=> "bg_cws_color",
							"group" 		=> $group_name,
							"value" 		=> array(
								esc_html__("None", 'metamax') => "none",
								esc_html__("Color", 'metamax') => "color",
								esc_html__("Gradient", 'metamax') => "gradient",
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 			=> "colorpicker",
							"heading"		=> esc_html__( 'Color', 'metamax' ),
							"param_name" 	=> "cws_overlay_color",
							"group" 		=> $group_name,
							"dependency"	=> array(
								"element"	=> "bg_cws_color",
								'value' => 'color',
							),
							"value"			=> $first_color
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "colorpicker",
							"heading"			=> esc_html__( 'From', 'metamax' ),
							"param_name" 		=> "cws_gradient_color_from",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-4",
							"dependency"		=> array(
								"element"	=> "bg_cws_color",
								'value' 	=> 'gradient',
							),
							"value"				=> "#000"
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "colorpicker",
							"heading"			=> esc_html__( 'To', 'metamax' ),
							"param_name" 		=> "cws_gradient_color_to",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-4",
							"dependency"		=> array(
								"element"	=> "bg_cws_color",
								'value' 	=> 'gradient',
							),
							"value"			=> "#fff"
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type"				=> "textfield",
							"heading"			=> esc_html__( 'Opacity', 'metamax' ),
							"param_name"		=> "cws_gradient_opacity",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-4",
							"description"		=> esc_html__( '100 - visible, 0 - invisible', 'metamax' ),
							"dependency"		=> array(
								"element"	=> "bg_cws_color",
								'value' 	=> 'gradient',						
							),
							"value" 			=> '50',
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading" 			=> esc_html__("Type", 'metamax'),
							"param_name"		=> "cws_gradient_type",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"dependency"		=> array(
								"element"	=> "bg_cws_color",
								'value' 	=> 'gradient',
							),
							"value" 			=> array(
								esc_html__("Linear", 'metamax') => "linear",
								esc_html__("Radial", 'metamax') => "radial",
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type"				=> "textfield",
							"heading"			=> esc_html__( 'Angle', 'metamax' ),
							"param_name"		=> "cws_gradient_angle",
							"value" 			=> '45',
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"description"		=> esc_html__( 'Degrees: -360 to 360', 'metamax' ),
							"dependency"		=> array(
								"element"	=> "cws_gradient_type",
								'value' 	=> 'linear',						
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading" 			=> esc_html__("Shape variant", 'metamax'),
							"param_name"		=> "cws_gradient_shape_variant_type",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"dependency"		=> array(
								"element"	=> "cws_gradient_type",
								'value' 	=> 'radial',	
							),
							"value" 			=> array(
								esc_html__("Simple", 'metamax') => "simple",
								esc_html__("Extended", 'metamax') => "extended",
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading" 			=> esc_html__("Shape", 'metamax'),
							"param_name"		=> "cws_gradient_shape_type",
							"group" 			=> $group_name,
							"dependency"		=> array(
								"element"	=> "cws_gradient_shape_variant_type",
								'value' 	=> 'simple',	
							),
							"value" 			=> array(
								esc_html__("Ellipse", 'metamax') => "ellipse",
								esc_html__("Circle", 'metamax') => "circle",
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading"			=> esc_html__("Size keyword", 'metamax'),
							"param_name"		=> "cws_gradient_size_keyword_type",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"dependency"	=> array(
								"element"	=> "cws_gradient_shape_variant_type",
								'value' => 'extended',	
							),
							"value" => array(
								esc_html__("Closest side", 'metamax') => "closest-side",
								esc_html__("Farthest side", 'metamax') => "farthest-side",
								esc_html__("Closest corner", 'metamax') => "closest-corner",
								esc_html__("Farthest corner", 'metamax') => "farthest-corner",
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "textfield",
							"heading" 			=> esc_html__("Size", 'metamax'),
							"param_name"		=> "cws_gradient_size_type",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"description"		=> esc_html__( 'Two space separated percent values, for example (60% 55%)', 'metamax' ),
							"dependency"		=> array(
								"element"	=> "cws_gradient_shape_variant_type",
								'value' 	=> 'extended',	
							),
							"value" 			=> '60% 55%',
						)
					);

					//VC_Row Extra Layer Properties
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "checkbox",
							"param_name"		=> "add_layers",
							"group" 			=> $group_name,						
							"value"				=> array( esc_html__( 'Add Layer', 'metamax' ) => true )
						)
					);					
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "attach_image",
							"heading" 			=> esc_html__("Layer", 'metamax'),
							"param_name"		=> "cws_layer_image",
							"group" 			=> $group_name,
							"dependency"		=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							'type' 				=> 'dropdown',
							'heading' 			=> esc_html__( 'Layer position', 'metamax' ),
							'param_name' 		=> 'extra_layer_pos',
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							'dependency' 		=> array(
								'element' 	=> 'add_layers',
								'not_empty' => true,
							),
							'value' 			=> array(
                                esc_html__( 'Left', 'metamax' ) => 'left',
                                esc_html__( 'Right', 'metamax' ) => 'right',
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							'type' 				=> 'textfield',
							'heading' 			=> esc_html__( 'Layer width', 'metamax' ),
							'param_name' 		=> 'extra_layer_width',
							"group" 			=> $group_name,
							'description' 		=> esc_html__( 'In percents', 'metamax' ),
							"edit_field_class" 	=> "vc_col-xs-6",
							'dependency' 		=> array(
								'element' 	=> 'add_layers',
								'not_empty' => true,
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading" 			=> esc_html__("Layer Image Size", 'metamax'),
							"param_name" 		=> "extra_layer_size",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-4",
							"dependency"	=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value" => array(
								esc_html__("Initial", 'metamax') => "initial",
								esc_html__("Cover", 'metamax') => "cover",
								esc_html__("Contain", 'metamax') => "contain",
							),
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading" 			=> esc_html__("Layer Image Position", 'metamax'),
							"param_name" 		=> "extra_layer_position",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-4",
							"dependency"		=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value" 			=> array(
								esc_html__("Left Top", 'metamax') => "left top",
								esc_html__("Left Center", 'metamax') => "left center",
								esc_html__("Left Bottom", 'metamax') => "left bottom",
								esc_html__("Right Top", 'metamax') => "right top",
								esc_html__("Right Center", 'metamax') => "right center",
								esc_html__("Right Bottom", 'metamax') => "right bottom",
								esc_html__("Center Top", 'metamax') => "center top",
								esc_html__("Center Center", 'metamax') => "center center",
								esc_html__("Center Bottom", 'metamax') => "center bottom",
							),	
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "dropdown",
							"heading" 			=> esc_html__("Layer Image Position", 'metamax'),
							"param_name" 		=> "extra_layer_repeat",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-4",
							"dependency"		=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value" 			=> array(
								esc_html__("No Repeat", 'metamax') => "no-repeat",
								esc_html__("Repeat", 'metamax') => "repeat",
								esc_html__("Repeat X", 'metamax') => "repeat-x",
								esc_html__("Repeat Y", 'metamax') => "repeat-y",
							),	
						)
					);			
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "colorpicker",
							"heading" 			=> esc_html__("Layer Background Color", 'metamax'),
							"param_name" 		=> "extra_layer_bg",
							"group" 			=> $group_name,
							"dependency"		=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value"				=> '',
						)
					);		
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "textfield",
							"heading" 			=> esc_html__("Layer Margin", 'metamax'),
							"param_name" 		=> "extra_layer_margin",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"description"		=> esc_html__( '1, 2( top/bottom, left/right ) or 4, space separated, values with units', 'metamax' ),
							"dependency"		=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value" => "0px 0px",
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "textfield",
							"heading" 			=> esc_html__("Layer Opacity", 'metamax'),
							"param_name" 		=> "extra_layer_opacity",
							"group" 			=> $group_name,
							"edit_field_class" 	=> "vc_col-xs-6",
							"description"		=> esc_html__( '100 = Visible, 0 = Transparent', 'metamax' ),
							"dependency"		=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value" => "100",
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type"			=> "checkbox",
							"param_name"	=> "hide_layer_landscape",
							"group"			=> $landscape_group,
							"dependency"	=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value"			=> array( esc_html__( 'Hide Layer', 'metamax' ) => true )
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type"			=> "checkbox",
							"param_name"	=> "hide_layer_portrait",
							"group"			=> $portrait_group,
							"dependency"	=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value"			=> array( esc_html__( 'Hide Layer', 'metamax' ) => true )
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type"			=> "checkbox",
							"param_name"	=> "hide_layer_mobile",
							"group"			=> $mobile_group,
							"dependency"	=> array(
								"element"	=> "add_layers",
								"not_empty"	=> true
							),
							"value"			=> array( esc_html__( 'Hide Layer', 'metamax' ) => true )
						)
					);
                    vc_add_param(
                        'vc_row',
                        array(
                            "type"			=> "checkbox",
                            "param_name"	=> "particles",
                            "group" 		=> $group_name,
                            "value"			=> array( esc_html__( 'Add particles', 'metamax' ) => true )
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Width (with unit)", 'metamax'),
                            "param_name" 		=> "particles_width",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "100%",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Height (with unit)", 'metamax'),
                            "param_name" 		=> "particles_height",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "100%",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Speed (0 to 10)", 'metamax'),
                            "param_name" 		=> "particles_speed",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "2",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Saturation (10 to 10 000)", 'metamax'),
                            "param_name" 		=> "particles_saturation",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "300",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Left Offset (with unit)", 'metamax'),
                            "param_name" 		=> "particles_left",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Top Offset (with unit)", 'metamax'),
                            "param_name" 		=> "particles_top",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Size (1 to 50)", 'metamax'),
                            "param_name" 		=> "particles_size",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "40",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Particles Count (1 to 500)", 'metamax'),
                            "param_name" 		=> "particles_count",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "8",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "dropdown",
                            "heading" 			=> esc_html__("Particles Start Pos", 'metamax'),
                            "param_name" 		=> "particles_start",
                            "edit_field_class" 	=> "vc_col-xs-4",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 		=> array(
                                esc_html__("Top Left", 'metamax') => "top-left",
                                esc_html__("Top Center", 'metamax') => "top-center",
                                esc_html__("Top Right", 'metamax') => "top-right",
                                esc_html__("Right Center", 'metamax') => "right-center",
                                esc_html__("Bottom Right", 'metamax') => "bottom-right",
                                esc_html__("Bottom Center", 'metamax') => "bottom-center",
                                esc_html__("Bottom Left", 'metamax') => "bottom-left",
                                esc_html__("Left Center", 'metamax') => "left-center",
                            ),
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "textfield",
                            "heading" 			=> esc_html__("Hide Particles on Choosen Resolution", 'metamax'),
                            "param_name" 		=> "particles_hide",
                            "edit_field_class" 	=> "vc_col-xs-5",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> "768",
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type" 				=> "colorpicker",
                            "heading" 			=> esc_html__("Particles Color", 'metamax'),
                            "param_name" 		=> "particles_color",
                            "edit_field_class" 	=> "vc_col-xs-3",
                            "dependency"	=> array(
                                "element"	=> "particles",
                                "not_empty"	=> true
                            ),
                            "group" 			=> $group_name,
                            "value" 			=> $first_color,
                        )
                    );



                    vc_add_param(
                        'vc_row',
                        array(
                            "type"			=> "checkbox",
                            "param_name"	=> "add_waves",
                            "group"			=> $group_name,
                            "edit_field_class" 	=> "vc_col-xs-12",
                            "value"			=> array( esc_html__( 'Add animated waves', 'metamax' ) => true )
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type"			=> "checkbox",
                            "param_name"	=> "wave_top_left",
                            "group"			=> $group_name,
                            "edit_field_class" 	=> "vc_col-xs-6",
                            "dependency"	=> array(
                                "element"	=> "add_waves",
                                "not_empty"	=> true
                            ),
                            "value"			=> array( esc_html__( 'Top left corner', 'metamax' ) => true )
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type"			=> "checkbox",
                            "param_name"	=> "wave_top_right",
                            "group"			=> $group_name,
                            "edit_field_class" 	=> "vc_col-xs-6",
                            "dependency"	=> array(
                                "element"	=> "add_waves",
                                "not_empty"	=> true
                            ),
                            "value"			=> array( esc_html__( 'Top right corner', 'metamax' ) => true )
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type"			=> "checkbox",
                            "param_name"	=> "wave_bottom_left",
                            "group"			=> $group_name,
                            "edit_field_class" 	=> "vc_col-xs-6",
                            "dependency"	=> array(
                                "element"	=> "add_waves",
                                "not_empty"	=> true
                            ),
                            "value"			=> array( esc_html__( 'Bottom left corner', 'metamax' ) => true )
                        )
                    );
                    vc_add_param(
                        'vc_row',
                        array(
                            "type"			=> "checkbox",
                            "param_name"	=> "wave_bottom_right",
                            "group"			=> $group_name,
                            "edit_field_class" 	=> "vc_col-xs-6",
                            "dependency"	=> array(
                                "element"	=> "add_waves",
                                "not_empty"	=> true
                            ),
                            "value"			=> array( esc_html__( 'Bottom right corner', 'metamax' ) => true )
                        )
                    );


					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "textfield",
							"heading" 			=> esc_html__("Row Z-Index", 'metamax'),
							"param_name" 		=> "z_index",
							"edit_field_class" 	=> "vc_col-xs-6",
							"group" 			=> $group_name,
							"value" 			=> "",
						)
					);
					vc_add_param(
						'vc_row',
						array(
							"type" 				=> "textfield",
							"heading" 			=> esc_html__("Row Bottom Shift", 'metamax'),
							"param_name" 		=> "shift",
							"edit_field_class" 	=> "vc_col-xs-6",
							"group" 			=> $group_name,
							"value" 			=> "",
						)
					);

					/*-----> Extra VC_Column Params <-----*/
					cws_structure_background_props('vc_column');
					vc_add_param(
						'vc_column',
						array(
							"type" 			=> "checkbox",
							"param_name" 	=> "place_ahead",
							"group" 		=> $mobile_group,
							"description"	=> esc_html__( 'If this column have`t content, use "padding-top" or "padding-bottom" properties for set the column height', 'metamax' ),
							"value"			=> array( esc_html__( 'Put this column on first place', 'metamax' ) => true )
						)
					);

					/*-----> Extra VC_Inner-Row Params <-----*/
					cws_structure_background_props('vc_row_inner');
					vc_add_param(
						'vc_row_inner',
						array(
							"type" 			=> "checkbox",
							"param_name" 	=> "add_shadow",
							"group" 		=> esc_html__('Design Options', 'metamax'),
							"value"			=> array( esc_html__( 'Add Shadow', 'metamax' ) => true )
						)
					);

					/*-----> Extra VC_Inner-Column Params <-----*/
					cws_structure_background_props('vc_column_inner');
					vc_add_param(
						'vc_column_inner',
						array(
							"type" 			=> "checkbox",
							"param_name" 	=> "place_ahead",
							"group" 		=> $mobile_group,
							"description"	=> esc_html__( 'If this column have`t content, use "padding-top" or "padding-bottom" properties for set the column height', 'metamax' ),
							"value"			=> array( esc_html__( 'Put this column on first place', 'metamax' ) => true )
						)
					);
				}
			} 
		}
		new VC_CWS_Background;
	}

	// VC_ROW hook
	if ( !function_exists( 'vc_theme_before_vc_row' ) ) {
		function vc_theme_before_vc_row($atts, $content = null) {
			$GLOBALS['cws_row_atts'] = $atts;
			return VC_CWS_Background::cws_open_vc_shortcode($atts, $content);
		}
	}
	if ( !function_exists( 'vc_theme_after_vc_row' ) ) {
		function vc_theme_after_vc_row($atts, $content = null) {
			unset($GLOBALS['cws_row_atts']);
			return VC_CWS_Background::cws_close_vc_shortcode($atts, $content);
		}
	}

	// VC_COLUMN hook
	if ( !function_exists( 'vc_theme_before_vc_column' ) ) {
		function vc_theme_before_vc_column($atts, $content = null) {
			new VC_CWS_Background();
			return VC_CWS_Background::cws_open_vc_shortcode_column($atts, $content);
		}
	}
	if ( !function_exists( 'vc_theme_after_vc_column' ) ) {
		function vc_theme_after_vc_column($atts, $content = null) {
			new VC_CWS_Background();
			return VC_CWS_Background::cws_close_vc_shortcode_column($atts, $content);
		}
	}

	// VC_ROW_INNER hook
	if ( !function_exists( 'vc_theme_before_vc_row_inner' ) ){
		function vc_theme_before_vc_row_inner($atts, $content = null) {
			new VC_CWS_Background();
			return VC_CWS_Background::cws_open_vc_shortcode_row_inner($atts, $content);
		}
	}
	if ( !function_exists( 'vc_theme_after_vc_row_inner' ) ){
		function vc_theme_after_vc_row_inner($atts, $content = null) {
			new VC_CWS_Background();
			return VC_CWS_Background::cws_close_vc_shortcode_row_inner($atts, $content);
		}
	}

	// VC_COLUMN_INNER hook
	if ( !function_exists( 'vc_theme_before_vc_column_inner' ) ) {
		function vc_theme_before_vc_column_inner($atts, $content = null) {
			new VC_CWS_Background();
			return VC_CWS_Background::cws_open_vc_shortcode_column_inner($atts, $content);
		}
	}
	if ( !function_exists( 'vc_theme_after_vc_column_inner' ) ) {
		function vc_theme_after_vc_column_inner($atts, $content = null) {
			new VC_CWS_Background();
			return VC_CWS_Background::cws_close_vc_shortcode_column_inner($atts, $content);
		}
	}
	
?>