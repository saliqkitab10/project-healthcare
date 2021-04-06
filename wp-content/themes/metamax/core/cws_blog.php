<?php

function cws_sc_blog($p){
	return function_exists( "cws_blog_output" ) ? cws_blog_output( $p ) : "";
}

//Get defaults values for Blog
function cws_blog_defaults( $extra_vars = array() ){
	global $cws_theme_funcs;

	if( $cws_theme_funcs ){
		$first_color = esc_attr( $cws_theme_funcs->cws_get_meta_option( 'theme_first_color' ) );
		$second_color = esc_attr( $cws_theme_funcs->cws_get_meta_option( 'theme_second_color' ) );
        $body_text_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );
	} else {
		$first_color = "#0c51ac";
		$second_color = "#fece42";
        $body_text_color = "#142b5f";
	}

	$def_blogtype = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option( "def_blogtype" ) : 'def';
	$btn_text = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option('blog_button_name') : 'Read More';

	$defaults = array(
		'orderby'								=> 'date',
		'order'									=> 'DESC',
		'display_style'							=> 'grid',
		'layout'								=> $def_blogtype,
		'content_align'							=> 'top',
		'checkerboard_spacings'					=> '',
		'isotope'								=> false,
        'thumbnail_size'                        => 'full',
		'carousel_direction'					=> 'horizontal',
		'carousel_navigation'					=> false,
		'carousel_pagination'					=> true,
		'carousel_autoheight'					=> true,

		'carousel_infinite'					    => false,
		'carousel_autoplay'					    => false,
		'autoplay_speed'					    => '3000',
		'pause_on_hover'					    => false,

		'post_hide_meta_override'				=> false,
		'post_hide_meta'						=> array(),
		'total_items_count'						=> '',
		'items_pp'								=> esc_html( get_option( 'posts_per_page' ) ),
		'items_pc'								=> '3',
		'chars_count'							=> '200',
		'pagination_grid'						=> 'standard',
		'more_btn_text'							=> $btn_text,
		//Styling
		'custom_styles'							=> '',
		'customize_colors'						=> false,
		'icons_color'							=> $first_color,
		'meta_color'							=> $body_text_color,
		'meta_hover_color'						=> $first_color,
		'text_color'							=> $body_text_color,
		'title_color'							=> $body_text_color,
		'title_hover_color'						=> $second_color,
		'button_color'							=> $body_text_color,
		'button_hover_color'					=> $second_color,
		//Anouther params
		'tax'									=> '',
		'titles'								=> '',
		'terms'									=> '',
		'addl_query_args'						=> array(),
		'full_width'							=> '',
		'el_class'								=> '',
		'related_items'							=> '',
	);

	if (!empty($extra_vars)){
		$defaults = array_merge($defaults, $extra_vars);
	}

	return $defaults;
}

function cws_meta_default() {
	return array(
		'post_title_box_image' => array(
			'src' => '',
			'id' => '',
		),
		'gallery_type' => 'slider',
		'gallery' => '',
		'video' => '',
		'audio' => '',
		'link' => '',
		'link_title' => '',
		'quote_text' => '',
		'quote_author' => '',
		'post_sidebars' => array(
			'layout' => 'none',
		),
		'author_info' => '1',
		'show_featured' => '1',
		'show_related' => '1',
		'rpo' => array(
			'title' => esc_html__('Related items', 'metamax'),
			'text_length' => '90',
			'cols' => '3',
			'items_show' => '3',
			'posts_hide' => array(
				'none',
				'tags',
				'author',
				'likes',
				'comments',
				'read_more',
				'social',
				'excerpt',
			),
		),
		'post_cust_color' => '0',
		'custom_title_spacings' => '0',
	);
}

//Fill atts from function to function
function cws_blog_fill_atts( $atts = array() ){
	global $cws_theme_funcs;

	extract( $atts );

	$post_id = get_the_id();
	$post_meta = get_post_meta( $post_id, 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();

	$total_items_count = !empty( $total_items_count ) ? (int)$total_items_count : PHP_INT_MAX;

	$def_post_layout = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option( 'def_blogtype' ) : 'def';
	$def_post_layout = isset( $def_post_layout ) ? $def_post_layout : "";
	$layout = ( empty( $layout ) || $layout === "def" ) ? $def_post_layout : $layout; 
	$cws_row_atts = isset($GLOBALS['cws_row_atts']) && !empty($GLOBALS['cws_row_atts']) ? $GLOBALS['cws_row_atts'] : "";

	$sb = $cws_theme_funcs ? $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() ) : '';
	$sb_layout = isset( $sb['layout_class'] ) ? $sb['layout_class'] : '';	

	$post_hide_meta_override = !empty( $post_hide_meta_override ) ? true : false;
	$post_hide_meta = is_string($post_hide_meta) ? explode( ",", $post_hide_meta ) : $post_hide_meta;
	$post_def_hide_meta = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option( 'def_post_hide_meta' ) : array();
	$post_def_hide_meta  = is_array( $post_def_hide_meta ) ? $post_def_hide_meta : array();
	$post_hide_meta = $post_hide_meta_override ? $post_hide_meta : $post_def_hide_meta;

	if ($related_items){
		$def_post_hide_meta_related_items = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option( 'def_post_hide_meta_related_items' ) : array();
		if (!empty($def_post_hide_meta_related_items)){
			$post_hide_meta = $def_post_hide_meta_related_items;
		}	
	}

	//Set GLOBALS vars
	$GLOBALS['cws_vc_shortcode_posts_grid_atts'] = array(
		'post_id'				=> $post_id,
		'post_meta'				=> $post_meta,
		'layout'				=> $layout,
		'sb_layout'				=> $sb_layout,
        'thumbnail_size'        => $thumbnail_size,
		'post_hide_meta'		=> $post_hide_meta,
		'chars_count'			=> $chars_count,
		'related_items'			=> $related_items,
		'more_btn_text'			=> $more_btn_text,
		'orderby'				=> $orderby,
		'order'					=> $order,
		'total_items_count'		=> $total_items_count
	);

	return array(
		'layout'				=> $layout,
		'sb_layout'				=> $sb_layout,
        'thumbnail_size'        => $thumbnail_size,
		'post_hide_meta'		=> $post_hide_meta,
		'related_items'			=> $related_items,
		'more_btn_text'			=> $more_btn_text,
		'orderby'				=> $orderby,
		'order'					=> $order,
		'total_items_count'		=> $total_items_count
	);
}

function cws_blog_output ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$defaults = cws_blog_defaults();
	$atts = shortcode_atts( $defaults, $atts );
	extract( $atts );

	/* -----> Variables declaration <----- */
	$out = $module_classes = $carousel_atts = $styles = "";
	global $display_post;
	$post_type = "post";
	$section_id = uniqid( 'blog-' );

	/* -----> Variable processing <----- */
	$display_post = isset($display_style) && !empty($display_style) ? $display_style : "";
	$items_pp = !empty( $items_pp ) ? (int)$items_pp : esc_html( get_option( 'posts_per_page' ) );
	$items_pc = !empty( $items_pc ) ? (int)$items_pc : esc_html( get_option( 'posts_per_page' ) );
	$paged = get_query_var( 'paged' );
	$home_paged = get_query_var('page');

	if( isset($home_paged) && !empty($home_paged) ){
		$paged = empty( $home_paged ) ? 1 : get_query_var('page');
	} else{
		$paged = empty( $paged ) ? 1 : $paged;
	}
	$titles = !empty($titles) ? explode( ',', $titles ) : null;
	if ( $tax == 'title' && !empty( $titles ) ) {
		$items_pp = count( $titles );
		$items_pc = count( $titles );
	}

	$fill_atts = cws_blog_fill_atts($atts);
	extract( $fill_atts );

	/* -----> Get Blog Taxonomy Properties <-----*/
	$terms = explode( ",", $terms );	
	$terms_temp = array();
	foreach ( $terms as $term ) {
		if ( !empty( $term ) ){
			array_push( $terms_temp, $term );
		}
	}
	$terms = $terms_temp;
	$all_terms = array();
	$all_terms_temp = !empty( $tax ) ? get_terms( $tax ) : array();
	$all_terms_temp = !is_wp_error( $all_terms_temp ) ? $all_terms_temp : array();

	foreach ( $all_terms_temp as $term ){
		array_push( $all_terms, $term->slug );
	}

	$terms = !empty( $terms ) ? $terms : array();
	$not_in = (1 == $paged) ? array() : get_option( 'sticky_posts' );

	$query_args = array('post_type'			=> array( $post_type ),
		'post_status'		=> 'publish',
		'post__not_in'		=> $not_in
	);
	if ( in_array( $display_style, array( 'grid' ) ) ){
		$query_args['posts_per_page']		= $items_pp;
		$query_args['paged']		        = $paged;
        $query_args['posts_per_column']		= -1;
	}else{
		$query_args['nopaging']				= true;
		$query_args['posts_per_page']		= -1;
		$query_args['posts_per_column']		= $items_pc;
	}

	if ( !empty( $terms ) ){
		$query_args['tax_query'] = array(
			array(
				'taxonomy'		=> $tax,
				'field'			=> 'slug',
				'terms'			=> $terms
			)
		);
	}

	if (!empty($titles)) {
		$query_args['post__in'] = $titles;
	}

	$query_args['orderby'] 	= $orderby;
	$query_args['order']	= $order;

	$query_args = array_merge( $query_args, $addl_query_args );
	$q = new WP_Query( $query_args );

	$found_posts = $q->found_posts;

	$requested_posts = $found_posts > $total_items_count ? $total_items_count : $found_posts;
	if ($carousel_direction == 'vertical') {
        $max_paged = $found_posts > $total_items_count ? ceil( $total_items_count / $items_pc ) : ceil( $found_posts / $items_pc );
    } else {
        $max_paged = $found_posts > $total_items_count ? ceil( $total_items_count / $items_pp ) : ceil( $found_posts / $items_pp );
    }

	/* -----> Pagination & Scripts init <-----*/
	$cols = in_array( $layout, array( 'medium', 'small', 'checkerboard' ) ) ? 1 : (int)$layout;
	$is_carousel = $requested_posts > $cols && $display_style == 'carousel';

	$use_pagination = in_array( $display_style, array( 'grid' ) ) && $max_paged > 1;
	
	wp_enqueue_script( 'slick-carousel' );
	wp_enqueue_script( 'isotope' );
	wp_enqueue_script( 'imagesloaded' );
	wp_enqueue_script( 'fancybox' );

	/* -----> Module Classes <-----*/
	if( $layout == 'checkerboard' ){
		$module_classes .= ' content-'.$content_align;
	}
	if( !empty($checkerboard_spacings) ){
		$module_classes .= ' has-spacings';
	}

	if( $is_carousel ){
		$carousel_atts .= ' data-draggable="on"';

		if( $layout == '2' ){
			$carousel_atts .= ' data-columns="2"';
		} else if( $layout == '3' ){
			$carousel_atts .= ' data-columns="3"';
		} else if( $layout == '4' ){
			$carousel_atts .= ' data-columns="4"';
        } else if( $carousel_direction == 'vertical' ){
            $carousel_atts .= ' data-columns="' . $items_pc . '"';
		} else {
			$carousel_atts .= ' data-columns="1"';
		}

		if ( $carousel_direction == 'vertical' ){
            $carousel_atts .= ' data-vertical="on"';
            $carousel_atts .= ' data-vertical-swipe="on"';
            $carousel_atts .= ' data-mobile-landscape="1"';
            $carousel_atts .= ' data-tablet-portrait="1"';
        }

		if( $carousel_navigation ){
			$carousel_atts .= ' data-navigation="on"'; 
		}
		if( $carousel_pagination ){
			$carousel_atts .= ' data-pagination="on"'; 
		}
		if( $related_items && $atts['layout'] != '1' ){
			$carousel_atts .= " data-tablet-portrait='2'";
		}
		if( $carousel_direction != 'vertical' && ( $atts['layout'] == '2' || $atts['layout'] == '3' || $atts['layout'] == '4') ){
			$carousel_atts .= " data-mobile-landscape='2'";
		}
		if( $carousel_autoheight ){
			$carousel_atts .= ' data-auto-height="on"';
		}

        if( $carousel_infinite ){
            $carousel_atts .= ' data-infinite="on"';
        }
        if( $carousel_autoplay ){
            $carousel_atts .= ' data-autoplay="on"';
        }
        if( $carousel_autoplay && !empty($autoplay_speed) ){
            $carousel_atts .= ' data-autoplay-speed="'.esc_attr($autoplay_speed).'"';
        }
        if( $carousel_autoplay && $pause_on_hover ){
            $carousel_atts .= ' data-pause-on-hover="on"';
        }
	}

	/* -----> Print styles <----- */
	if( !empty($custom_styles) ){

		preg_match("/(?<=\{).+?(?=\})/", $custom_styles, $vc_custom_styles); 
		$vc_custom_styles = implode($vc_custom_styles);

		$styles .= "
			#".$section_id."{
				".esc_attr($vc_custom_styles)."
			}
		";
	}
	if( !empty($customize_colors) ){
		if( !empty($icons_color) ){
			$styles .= "
				#".$section_id." .nav-post-links *:before,
				#".$section_id." .nav-post-links div a,
				#".$section_id." .title-box:not(.customized) .subtitle-content,
				#".$section_id." .title-box:not(.customized) .bread-crumbs *,
				#".$section_id." .post-meta-wrapper > * a:not(.read-more):before,
				#".$section_id." .item.format-quote .post-format-quote:before,
				#".$section_id." .item.format-link .post-format-link:before{
					color: ".esc_attr($icons_color).";
				}
			";
		}
		if( !empty($meta_color) ){
			$styles .= "
				#".$section_id." .post-meta-wrapper > * a:not(.read-more){
					color: ".esc_attr($meta_color).";
				}
			";
		}
		if( !empty($meta_hover_color) ){
			$styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					#".$section_id." .post-meta-wrapper > * a:not(.read-more):hover{
						color: ".esc_attr($meta_hover_color).";
					}
				}
			";
		}
		if( !empty($text_color) ){
			$styles .= "
				#".$section_id." .post-content{
					color: ".esc_attr($text_color).";
				}
			";
		}
		if( !empty($title_color) ){
			$styles .= "
				#".$section_id." .item .post-wrapper .post-info .post-title a{
					color: ".esc_attr($title_color).";
				}
			";
		}
		if( !empty($title_hover_color) ){
			$styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					#".$section_id." .item .post-wrapper .post-info .post-title a:hover,
					#".$section_id." .item.format-link .post-format-link:hover{
						color: ".esc_attr($title_hover_color).";
					}
				}
			";
		}
		if( !empty($button_color) ){
			$styles .= "
				#".$section_id." .item .post-wrapper .post-info .read-more{
					color: ".esc_attr($button_color).";
				}
			";
		}
		if( !empty($button_hover_color) ){
			$styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					#".$section_id." .item .post-wrapper .post-info .read-more:hover{
						color: ".esc_attr($button_hover_color).";
					}
				}
			";
		}
	}
	if( !empty($checkerboard_spacings) ){
		$styles .= "
			#".$section_id.".layout-checkerboard .item{
				margin-bottom: ".(int)esc_attr($checkerboard_spacings)."px;
			}
		";
	}

	if ( !empty($styles) ){
		Cws_shortcode_css()->enqueue_cws_css($styles);
	}

	ob_start ();
	/* -----> Staff module output <----- */
	echo "<section id='".$section_id."' class='clearfix news posts-grid layout-". $layout . $module_classes . esc_attr
        ($el_class) ."'>";

		echo "<div ".( $is_carousel ? "class='cws-vc-shortcode-wrapper cws-carousel-wrapper'".$carousel_atts : "class='cws-vc-shortcode-wrapper'").">";

			echo "<div class='cws-vc-shortcode-grid grid layout-". $layout . ( $isotope ? " isotope" : "" ) . (
			    $is_carousel ? " cws-carousel" : "" ) ."'>";
				cws_blog_posts($q);
				unset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] );
				unset( $GLOBALS['display_post'] );
			echo "</div>";

			if ( $use_pagination && $cws_theme_funcs){
				cws_loader_html();
			}
		echo "</div>";

		if ($cws_theme_funcs){
			/* -----> Ajax Posts Load <-----*/
			if ( !is_single() && $use_pagination){
				if ( $pagination_grid == 'load_more' ){
					echo cws_load_more ();
				} else if( $pagination_grid == 'standard_with_ajax' ){
					echo cws_pagination($paged, $max_paged, true);
				} else{
					echo cws_pagination($paged, $max_paged, false);
				}

				$ajax_data['section_id']						= $section_id;
				$ajax_data['post_type']							= 'post';
				$ajax_data['post_hide_meta']					= $post_hide_meta;
				$ajax_data['thumbnail_size']					= $thumbnail_size;
				$ajax_data['layout']							= $layout;
				$ajax_data['sb_layout']							= $sb_layout;
				$ajax_data['total_items_count']					= $total_items_count;
				$ajax_data['items_pp']							= $items_pp;
				$ajax_data['items_pc']							= $items_pc;
				$ajax_data['page']								= $paged;
				$ajax_data['max_paged']							= $max_paged;
				$ajax_data['tax']								= $tax;
				$ajax_data['terms']								= $terms;
				$ajax_data['current_filter_val']				= '_all_';
				$ajax_data['addl_query_args']					= $addl_query_args;
				$ajax_data['pagination_grid']					= $pagination_grid;
				$ajax_data['related_items']						= $related_items;
				$ajax_data['more_btn_text']						= $more_btn_text;
				$ajax_data['chars_count']						= $chars_count;
				$ajax_data['orderby']						    = $orderby;
				$ajax_data['order']						        = $order;
				$ajax_data_str = json_encode( $ajax_data );
				echo "<form id='{$section_id}_data' class='ajax-data-form cws-blog-ajax-data-form posts-grid-ajax-data-form'>";
					echo "<input type='hidden' id='{$section_id}-ajax-data' class='ajax-data cws-blog-ajax-data posts-grid-ajax-data' name='{$section_id}-ajax-data' value='$ajax_data_str' />";
				echo "</form>";
			}
		} else {
			echo cws_pagination($paged, $max_paged, false);
		}

	echo "</section>";
	$out = ob_get_clean();

	return $out;
}

function cws_blog_special_post_formats (){
	return array( "status" );
}

function cws_blog_post_format (){
	global $post;
	if ( isset( $post ) ){
		$pf = get_post_format ();

		$out = "$pf";
		return $out;
	}
	else{
		return "";
	}
}

function cws_blog_posts ( $q = null ){
	if ( !isset( $q ) ) return;
	//Blog attributes
	$def_grid_atts = array(
		'layout'				=> '1',
		'thumbnail_size'        => 'full',
		'post_hide_meta'		=> array(),
		'total_items_count'		=> PHP_INT_MAX
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$paged = $q->query_vars['paged'];
	if ( $paged == 0 && $total_items_count < $q->post_count ){
		$post_count = $total_items_count;
	}else{
		$ppp = $q->query_vars['posts_per_page'];
		$posts_left = $total_items_count - ( $paged - 1 ) * $ppp;
		$post_count = $posts_left < $ppp ? $posts_left : $q->post_count;
	}

	if ( $q->have_posts() ):
		ob_start();
		while( $q->have_posts() && $q->current_post < $post_count - 1 ):
			$q->the_post();
			cws_blog_article();
		endwhile;
		wp_reset_postdata();
		ob_end_flush();
	endif;				
}

function cws_blog_styles ($uniq_pid){
	global $cws_theme_funcs;

	$def_grid_atts = array(
		'related_items' => false
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	/* -----> Variables declaration <----- */
	$post_id = get_the_id();
	$page_title_container_styles = '';

	/* -----> Style variables from post metaboxes <----- */
	$post_meta = get_post_meta( $post_id, 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$image_from_post = isset($post_meta['post_title_box_image']) ? $post_meta['post_title_box_image'] : '';
	$post_custom_color = isset($post_meta['post_custom_color']) ? $post_meta['post_custom_color'] : '';
	$post_title_color = isset($post_meta['post_title_color']) ? $post_meta['post_title_color'] : '';
	$post_breadcrumbs_color = isset($post_meta['post_breadcrumbs_color']) ? $post_meta['post_breadcrumbs_color'] : '';
	$post_breadcrumbs_hover_color = isset($post_meta['post_breadcrumbs_hover_color']) ? $post_meta['post_breadcrumbs_hover_color'] : '';
	$page_title_spacings = isset($post_meta['page_title_spacings']) ? $post_meta['page_title_spacings'] : array();

	ob_start();
	if( isset($post_meta['custom_title_spacings']) && !empty($page_title_spacings) ){
		foreach ( $page_title_spacings as $key => $value ) {
			if ( !empty($value) ) {
				$page_title_container_styles .= "padding-".esc_attr($key).":".(int)esc_attr($value)."px !important;";
			}
		}
		echo "
			.single-post .title-box .page-title .container{
				".$page_title_container_styles.";
			}
		";
	}

	if ( $post_custom_color ) {
		if( !empty($post_title_color) ){
			echo "
				.single-post .title-box .title h1{
					color: ".esc_attr($post_title_color).";
				}
			";
		}
		if( !empty($post_breadcrumbs_color) ){
			$rgb = $cws_theme_funcs->cws_Hex2RGB($post_breadcrumbs_color);

			echo "
				.single-post .title-box .subtitle-content,
				.single-post .title-box .bread-crumbs *{
					color: ".esc_attr($post_breadcrumbs_color).";
				}
				.title-box .bread-crumbs .delimiter:before{
					background: -webkit-linear-gradient(135deg, rgba(".esc_attr($rgb).", .5), rgba(".esc_attr($rgb).", .7));
				    background: -o-linear-gradient(135deg, rgba(".esc_attr($rgb).", .5), rgba(".esc_attr($rgb).", .7));
				    background: linear-gradient(135deg, rgba(".esc_attr($rgb).", .5), rgba(".esc_attr($rgb).", .7));
				}
			";
		}
		if( !empty($post_breadcrumbs_hover_color) ){
			echo "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					.single-post .title-box .bread-crumbs a:hover{
						color: ".esc_attr($post_breadcrumbs_hover_color).";
					}
				}
			";
		}
	}

	/* \styles */
	$styles = ob_get_clean();

	if ( !empty($styles) ){
		Cws_shortcode_css()->enqueue_cws_css($styles);
	}	
}

function cws_blog_article (){
	global $cws_theme_funcs;

	//Blog attributes
	$def_grid_atts = array(
		'layout'				=> '1',
		'thumbnail_size'        => 'full',
		'post_hide_meta'		=> array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;

	extract($grid_atts);

	/* -----> Variables declaration <----- */
	$post_format = get_post_format();
	$uniq_pid = uniqid( "blog-post-" );

	cws_blog_styles($uniq_pid);

	/* -----> Blog article output <-----*/
	echo "<article id='$uniq_pid' ";
		post_class( array('item', 'post-item', (is_sticky(get_the_id()) ? 'sticky-post' : ''), ( $related_items ? 'related-item' : '') ) );
	echo ">";
		echo "<div class='post-wrapper'>";
            if ($post_format != 'link' && $post_format != 'quote') {
                cws_blog_media($uniq_pid);
            }

			echo "<div class='post-info'>";

			if ($related_items) {

                echo '<div class="post-info-header">';
                    echo cws_post_categories();
                    if ($post_format != 'link' && $post_format != 'quote' && $post_format != 'aside' && $post_format != 'status') {
                        cws_blog_title();
                    }
                echo '</div>';
                cws_link_quote();
                if ($post_format != 'quote' && $post_format != 'link') {
                    cws_blog_content();
                }
                echo cws_blog_btn_more();
                echo "<div class='post-info-footer'>";
                    echo "<div class='post-meta-wrapper'>";
                        echo cws_blog_meta_author();
                        echo cws_blog_date();
                        echo cws_blog_meta_comments();
                        echo cws_blog_meta_likes();
                        echo cws_post_tags();
                    echo "</div>";
                echo "</div>";

            } else {

			    if ($layout == '2' || $layout == '3' || $layout == '4'  ) {

                    echo '<div class="post-info-header">';
                        echo cws_post_categories();
                    echo '</div>';
                    if ($post_format != 'link' && $post_format != 'quote' && $post_format != 'aside' && $post_format != 'status') {
                        cws_blog_title();
                    }
                    cws_link_quote();
                    if ($post_format != 'quote' && $post_format != 'link') {
                        cws_blog_content();
                    }
                    echo "<div class='post-meta-wrapper'>";
                        echo cws_blog_meta_comments();
                        echo cws_blog_meta_likes();
                        echo cws_post_tags();
                    echo "</div>";
                    echo cws_blog_btn_more();
                    echo "<div class='post-info-footer'>";
                        echo cws_blog_meta_author();
                        echo cws_blog_date_special();
                    echo "</div>";

                } else if ($layout == 'special') {

                    echo '<div class="post-info-header">';
                        echo '<div class="post-info-content">';
                            if ($post_format != 'link' && $post_format != 'quote' && $post_format != 'aside' && $post_format != 'status') {
                                cws_blog_title();
                            }
                            cws_link_quote();
                            if ($post_format != 'quote' && $post_format != 'link') {
                                cws_blog_content();
                            }
                        echo '</div>';
                        echo cws_blog_date_special();
                    echo '</div>';

                    echo "<div class='post-info-footer'>";
                        echo "<div class='post-meta-wrapper'>";
                            echo cws_blog_meta_author();
                            echo cws_blog_meta_comments();
                            echo cws_blog_meta_likes();
                            echo cws_post_tags();
                        echo "</div>";
                        echo cws_blog_btn_more();
                    echo "</div>";

                } else if ($layout == 'list') {

                    $footer = cws_blog_meta_author() . cws_blog_date() . cws_blog_meta_comments() . cws_post_tags();
                    echo cws_post_categories();

                    if ($post_format != 'link' && $post_format != 'quote' && $post_format != 'aside' && $post_format != 'status') {
                        cws_blog_title();
                    }
                    cws_link_quote();
                    if ($post_format != 'quote' && $post_format != 'link') {
                        echo '<div class="post-info-content">';
                        cws_blog_content();
                        echo cws_blog_btn_more();
                        echo '</div>';
                    }
                    if (!empty($footer)) {
                        echo "<div class='post-info-footer'>";
                        echo "<div class='post-meta-wrapper'>";
                        echo cws_blog_meta_author();
                        echo cws_blog_date();
                        echo cws_blog_meta_comments();
                        echo cws_blog_meta_likes();
                        echo cws_post_tags();
                        echo "</div>";
                        echo "</div>";
                    }

                } else {

			        $header = cws_post_categories() . cws_blog_meta_likes();
			        $footer = cws_blog_meta_author() . cws_blog_date() . cws_blog_meta_comments() . cws_post_tags() . cws_blog_btn_more();
                    if ( !empty($header) ) {
                        echo '<div class="post-info-header">';
                            echo cws_post_categories();
                            echo '<div class="post-info-header-divider"></div>';
                            echo cws_blog_meta_likes();
                        echo '</div>';
                    }

                    if ($post_format != 'link' && $post_format != 'quote' && $post_format != 'aside' && $post_format != 'status') {
                        cws_blog_title();
                    }
                    cws_link_quote();
                    if ($post_format != 'quote' && $post_format != 'link') {
                        cws_blog_content();
                    }
                    if (!empty($footer)) {
                        echo "<div class='post-info-footer'>";
                            echo "<div class='post-meta-wrapper'>";
                                echo "<div class='post-meta'>";
                                    echo cws_blog_meta_author();
                                    if ($layout == 'timeline') {
                                        echo cws_blog_date_timeline();
                                    } else {
                                        echo cws_blog_date();
                                    }
                                    echo cws_blog_meta_comments();
                                    echo cws_post_tags();
                                echo "</div>";
                                echo '<div class="post-info-footer-divider"></div>';
                            echo "</div>";

                            echo cws_blog_btn_more();
                        echo "</div>";
                    }
                }
            };
			echo "</div>";

		echo "</div>";	
	echo "</article>";
}

function cws_blog_date (){
	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$out = '';
	$year = get_the_time('Y');
	$month = get_the_time('m');

	$date_link = get_month_link($year, $month);
	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	if ( !in_array( 'date', $post_hide_meta ) ){
		$date = get_the_time( get_option("date_format") );
		if ( !empty( $date ) ){
			$out .= "<div class='post-meta-item post-date'>";
				$out .= "<a href='" . esc_url($date_link) . "'>";
					$out .= $date;
				$out .= "</a>";
			$out .= "</div>";
		}
	}
	
	return $out;
}

function cws_blog_date_timeline (){
    $def_grid_atts = array(
        'post_hide_meta' => array(),
    );

    $grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
    extract( $grid_atts );

    $out = '';
    $year = get_the_time('Y');
    $post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

    if ( !in_array( 'date', $post_hide_meta ) ){
        if ( !empty( $year ) ){
            $out .= "<div class='post-meta-item post-date'>";
                $out .= esc_html($year);
            $out .= "</div>";
        }
    }

    return $out;
}

function cws_blog_date_special (){
    $def_grid_atts = array(
        'post_hide_meta' => array(),
    );

    $grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
    extract( $grid_atts );

    $out = '';

    $s_day = get_the_time('d');
    $s_month = get_the_time('M');

    $post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

    if ( !in_array( 'date', $post_hide_meta ) ){
        $date = '<span class="day">' . esc_html($s_day) . '</span><span class="month">' . esc_html($s_month) . '</span>';
        if ( !empty( $date ) ){
            $out .= "<div class='post-date-special'>";
                $out .= $date;
            $out .= "</div>";
        }
    }

    return $out;
}

function cws_blog_title (){
	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	/* -----> Variables declaration <----- */
	$pid = get_the_id();
	$is_single = is_single( $pid );
	$title = get_the_title();
	$permalink = get_the_permalink();

	$title_part = "<h3 class='post-title'><a href='".esc_url($permalink)."'>". esc_html($title) ."</a></h3>";

	if( !in_array('title', $post_hide_meta) && !empty($title) ){
		echo sprintf('%s', $title_part);
	}
}

function cws_link_quote (){
	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	/* -----> Variables declaration <----- */
	$post_format = get_post_format();
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();

	switch ($post_format) {
		case 'link':
			$link = isset( $post_meta['link'] ) ? esc_url( $post_meta['link'] ) : "";
			$link_title = isset( $post_meta['link_title'] ) ? esc_html( $post_meta['link_title'] ) : "";

			if( !empty( $link ) ) {
				echo "<span class='post-format-link'>";
				    echo "<a class='link-text' href='".esc_url($link)."'>".$link_title."</a>";
				echo "</span>";
			}

			break;
		case 'quote':
			$quote = isset( $post_meta['quote_text'] ) ? esc_html($post_meta['quote_text']) : '';
			$author_name = isset( $post_meta['quote_author'] ) ? esc_html($post_meta['quote_author']) : '';	

			if( !empty($quote) || !empty($author_name) ){
				echo "<div class='post-format-quote'>";
					if( !empty($quote) ){
						echo "<p class='quote-text'>".$quote."</p>";
					}
					if( !empty($author_name) ){
						echo "<span class='quote-author-name'>".$author_name."</span>";
					}
				echo "</div>";
			}

			break;
		default:
			break;
	}
}

function cws_blog_media (){
	global $cws_theme_funcs;

	$def_grid_atts = array(
		'layout'				=> '1',
		'thumbnail_size'        => 'full',
		'post_hide_meta'		=> array(),
		'sb_layout'				=> '',
		'full_width'			=> '',
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );
	
	/* -----> Variables declaration <----- */
	$pid = get_the_id();
	$buf1 = $classes = "";
	$is_single = is_single( $pid );
	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();
	$post_format = get_post_format();
	$media_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$media_meta = isset( $media_meta[0] ) ? $media_meta[0] : array();
	$thumb_size = ( $thumbnail_size ? $thumbnail_size : 'full' ) ;

	/* -----> Get Post Default Thumbnail <----- */
	$thumbnail_props = has_post_thumbnail( ) ? wp_get_attachment_image_src(get_post_thumbnail_id( ), $thumb_size) : array();
	$thumbnail = !empty( $thumbnail_props ) ? $thumbnail_props[0] : '';
	$real_thumbnail_dims = array();
	if ( !empty( $thumbnail_props ) && isset( $thumbnail_props[1] ) ) $real_thumbnail_dims['width'] = $thumbnail_props[1];
	if ( !empty( $thumbnail_props ) && isset( $thumbnail_props[2] ) ) $real_thumbnail_dims['height'] = $thumbnail_props[2];

	/* -----> Get Media Content frim different post formats <----- */
	ob_start();
	switch ($post_format) {
		case 'video':
			$video = isset($media_meta[$post_format]) ? $media_meta[$post_format] : "";
			if ( !empty( $video ) ) {
				echo "<div class='video'>" . apply_filters('the_content',"[embed]". $video ."[/embed]") . "</div>";
			}
			break;
		case 'audio':
			$audio = isset($media_meta[$post_format]) ? esc_attr( $media_meta[$post_format]) : "";
			$is_soundcloud = is_int( strpos( (string) $audio, 'https://soundcloud' ) );
			if ( !empty( $thumbnail ) && !$is_soundcloud ){
				/* -----> Get Post Thumbnail <----- */
				$thumbnail_id = get_post_thumbnail_id($pid);

				$thumb_title = get_post($thumbnail_id)->post_title;
				$thumb_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
				$thumb_alt = !empty($thumb_alt) ? $thumb_alt : $thumb_title;

				$img_src = wp_get_attachment_image_url( $thumbnail_id, $thumb_size );
				$img_srcset = wp_get_attachment_image_srcset( $thumbnail_id, $thumb_size );
				$img_sizes = wp_get_attachment_image_sizes($thumbnail_id, $thumb_size);

				echo "<a href='".get_permalink()."' class='pic'>";
					echo "<img src='".esc_url($img_src)."' srcset='".esc_attr($img_srcset)."' sizes='".esc_attr($img_sizes)."' alt='".esc_attr($thumb_alt)."'";
				echo "</a>";
				if ( empty( $audio ) ){
					echo "<div class='hover-effect'></div>";
					echo "<a class='fancy post-media-link post-post-media-link ".($is_single ? 'post-single-post-media-link' : 'posts-grid-post-media-link')."' href='".esc_url($thumbnail)."'></a>";
				}					
			}
			if ( !empty( $audio ) ){
				echo "<div class='audio" . ( $is_soundcloud ? " soundcloud" : "" ) . "'>";
					echo apply_filters( 'the_content', $audio );
				echo "</div>";
			}
			break;
		case 'gallery':
			$gallery_id = uniqid('single-gallery-');
			$gallery_type = isset( $media_meta['gallery_type'] ) ? $media_meta['gallery_type'] : "";
			$grid_cols = isset( $media_meta['grid_cols'] ) ? $media_meta['grid_cols'] : "1";
			$custom_grid = isset( $media_meta['custom_grid'] ) ? $media_meta['custom_grid'] : "";
			$gallery = isset( $media_meta[$post_format] ) ? $media_meta[$post_format] : "";
			wp_enqueue_script( 'slick-carousel' );
			wp_enqueue_script( 'fancybox' );

			if( !empty($gallery) ){
				$match = preg_match_all("/\d+/", $gallery, $images);
				preg_match("/link=&quot;(.*?)&quot;/", $gallery, $link_type);

				if( $match ){
					$images = $images[0];
					$images_src = array();

					foreach( $images as $image ){
						$images_props = array();

						$image_src = wp_get_attachment_image_src($image, $thumb_size);
						$image_srcset = wp_get_attachment_image_srcset($image, $thumb_size);
						$image_sizes = wp_get_attachment_image_sizes($image, $thumb_size);

						$image_link = wp_get_attachment_image_url($image, $thumb_size);
						$image_page = get_permalink($image);

						$image_post = get_post($image);
						if( isset($image_post) ){
							$image_title = get_post($image)->post_title;
							$image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
							$image_alt = !empty($image_alt) ? $image_alt : $image_title;
						}

						if( $image_src ){
							$images_props = array(
								'src'		=> $image_src[0],
								'srcset'	=> $image_srcset,
								'sizes'		=> $image_sizes,
								'alt'		=> $image_alt,
								'page'		=> $image_page
							);

							array_push( $images_src, $images_props );
						}
					}
				}
			}

			if( is_single() && $gallery_type == 'new_grid' && !empty($gallery) && !$related_items ){
				if ($match){
					$counter = 1;

					foreach ($images_src as $image) {

						if( $counter == 1 ){
							echo "<div class='gallery-custom-grid ".$custom_grid."'>";
						}

							if( !empty($link_type) ){
								if( $link_type[1] == 'file' ){
									echo "<a href='".esc_url($image['src'])."' class='fancy pic' data-fancybox-group='".$gallery_id."' style='background-image: url(".esc_url($image['src']).")'></a>";
								} else if( $link_type[1] == 'none' ){
									echo "<div class='pic' style='background-image: url(".esc_url($image['src']).")'></div>";
								}
							} else {
								echo "<a href='".esc_url($image['page'])."' class='pic' style='background-image: url(".esc_url($image['src']).")'></a>";
							}

							$counter ++;

						if(
							( $custom_grid == 'var_3' && $counter == 4 ) || 
							( ($custom_grid == 'var_5' || $custom_grid == 'var_6') && $counter == 6 ) || 
							( ($custom_grid == 'var_1' || $custom_grid == 'var_2' || $custom_grid == 'var_4' || $custom_grid == 'var_7') && $counter == 7 )
						){
							echo "</div>";
							$counter = 1;
						}

					}
				}
			} else if( is_single() && $gallery_type == 'grid' && !empty($gallery) && !$related_items ){
				if( $match ){
					echo "<div class='gallery-grid cols-".$grid_cols."'>";

						if( !empty($link_type) ){
							if( $link_type[1] == 'file' ){
								foreach ( $images_src as $image ) {
									echo "<a href='".esc_url($image['src'])."' class='fancy pic' data-fancybox-group='".$gallery_id."'>";
										echo "<img src='".esc_url($image['src'])."' srcset='".esc_attr($image['srcset'])."' sizes='".esc_attr($image['sizes'])."' alt='".esc_attr($image['alt'])."'/>";
									echo "</a>";
								}
							} else if( $link_type[1] == 'none' ){
								foreach ( $images_src as $image ) {
									echo "<div class='pic'>";
										echo "<img src='".esc_url($image['src'])."' srcset='".esc_attr($image['srcset'])."' sizes='".esc_attr($image['sizes'])."' alt='".esc_attr($image['alt'])."'/>";
									echo "</div>";
								}
							}
						} else {
							foreach ( $images_src as $image ) {
								echo "<a href='".esc_url($image['page'])."' class='pic'>";
									echo "<img src='".esc_url($image['src'])."' srcset='".esc_attr($image['srcset'])."' sizes='".esc_attr($image['sizes'])."' alt='".esc_attr($image['alt'])."'/>";
								echo "</a>";
							}
						}

					echo "</div>";
				}
			} else if( (!is_single() || $gallery_type == 'slider' || $related_items) && !empty($gallery) ) {
				$carousel_atts = "";

				if( $match ){
					$carousel = count($images_src) > 1 ? true : false;
					$carousel_atts .= " data-navigation='on'";
					$carousel_atts .= " data-pagination='off'";
					if( is_single() && !$related_items ){
						$carousel_atts .= " data-draggable='on'";
					} else {
						$carousel_atts .= " data-draggable='off'";
					}

					if( $carousel ){
						echo "<div class='cws-carousel-wrapper inner_slick'".$carousel_atts.">";
							echo "<div class='cws-carousel'>";
					}

						foreach ( $images_src as $image ) {
							echo "<a href='".get_permalink()."' class='pic'>";
								echo "<img src='".esc_url($image['src'])."' srcset='".esc_attr($image['srcset'])."' sizes='".esc_attr($image['sizes'])."' alt='".esc_attr($image['alt'])."'/>";
							echo "</a>";
						}

					if( $carousel ){
							echo "</div>";
						echo "</div>";
					}
				}
			}
			break;
	}
	$buf1 = ob_get_contents();

	/* -----> Get Media Content <----- */
	if ( empty( $buf1 ) && !empty( $thumbnail ) ) {
		/* -----> Get Post Thumbnail <----- */
		$thumbnail_id = get_post_thumbnail_id($pid);

		$thumb_title = get_post($thumbnail_id)->post_title;
		$thumb_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
		$thumb_alt = !empty($thumb_alt) ? $thumb_alt : $thumb_title;

		$img_src = wp_get_attachment_image_url( $thumbnail_id, $thumb_size );
		$img_srcset = wp_get_attachment_image_srcset( $thumbnail_id, $thumb_size );

        if ( $layout == '2' || $layout == 'checkerboard' || $layout == 'medium' || $layout == 'timeline' ) {
            $img_sizes = '(max-width: 767px) 100vw, 50vw';
        } elseif ( $layout == '3' || $layout == 'small' ) {
            $img_sizes = '(max-width: 767px) 100vw, (max-width: 991px) 50vw, 33vw';
        } elseif ( $layout == '4' ) {
            $img_sizes = '(max-width: 767px) 100vw, (max-width: 991px) 50vw, (max-width: 1366) 33vw, 25vw';
        } else {
            $img_sizes = '100vw';
        }

		echo "<a href='".get_permalink()."' class='pic'>";
			echo "<img src='".esc_url($img_src)."' srcset='".esc_attr($img_srcset)."' sizes='".esc_attr($img_sizes)."' alt='".esc_attr($thumb_alt)."'/>";
		echo "</a>";
	}
	$media_content = ob_get_clean();

	$classes .= "post-media";
	if( $is_single ){
		$classes .= " post-single-media";
	} else {
		$classes .= " post-grid-media";
	}

	if ( !empty( $media_content ) ){
		echo "<div class='".$classes."'>";
			echo sprintf("%s", $media_content);
            if ($layout == 'special' && !$related_items) {
                echo cws_post_categories();
            }
		echo "</div>";			
	}
}

function cws_blog_content(){
	if(class_exists('WPBMap')){
		WPBMap::addAllMappedShortcodes();
	}

	global $cws_theme_funcs;
	global $post, $more, $cws_theme_funcs;

	$def_grid_atts = array(
		'post_hide_meta'		=> array(),
		'chars_count'			=> '',
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );

	/* -----> Variables declaration <----- */
	$content = $proc_content = $excerpt = $proc_excerpt = "";
	$id = get_the_ID();
	$permalink = get_the_permalink( $id );
	$more = 0;
	$is_rtl = is_rtl();

	if( isset($chars_count) && !empty($chars_count) && !is_archive() ){
		$chars_count = (int)$chars_count; //Chars count from vc_module
	} else {
		if( $cws_theme_funcs ){
			$chars_count = $cws_theme_funcs->cws_get_option('def_blog_chars_count'); //Chars count from theme options
		} else {
			$chars_count = 200;
		}
	}

	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();
	$content = $post->post_content;
	$excerpt = $post->post_excerpt;

	/* -----> Related items on single page <----- */
	if( is_single() ) {
		if( !empty($chars_count) && $grid_atts['related_items'] ){
			$proc_content = $content;
			$proc_content = trim( preg_replace( '/[\s]{2,}/u', ' ', strip_shortcodes( strip_tags( $proc_content ) ) ) );
			$proc_content = mb_substr( $proc_content, 0, $chars_count );
		} else {
			$proc_content = $content;
		}
	} else {
		if ( !empty( $excerpt ) ){
			$proc_content = get_the_excerpt();
		} else if ( strpos( (string) $content, '<!--more-->' ) ){
			$proc_content = get_the_content( "" );
		} else if ( !empty( $content ) && !empty($chars_count) ){
			$proc_content = get_the_content("");
			$proc_content = trim( preg_replace( '/[\s]{2,}/u', ' ', strip_shortcodes( strip_tags( $proc_content ) ) ) );
			$proc_content = mb_substr( $proc_content, 0, $chars_count );
		} else {
			if( 
				$grid_atts['layout'] == '2' || 
				$grid_atts['layout'] == '3' || 
				$grid_atts['layout'] == '4' || 
				$grid_atts['layout'] == 'checkerboard' || 
				$grid_atts['layout'] == 'special'
			){
				$proc_content = get_the_content("");
				$proc_content = trim( preg_replace( '/[\s]{2,}/u', ' ', strip_shortcodes( strip_tags( $proc_content ) ) ) );
			} else {
				$proc_content = get_the_content( "[...]" );
			}
		}
	}


	/* -----> Post output <----- */
	if( !is_single() ){
		if(!in_array( 'excerpt', $post_hide_meta )){
			echo "<div class='post-content'>";
				if ($cws_theme_funcs){
					echo apply_filters( 'the_content', $proc_content );
				} else {
					the_content();				
				}
			echo "</div>";
		}
	} else {
		if(!in_array( 'excerpt', $post_hide_meta )){
			echo "<div class='post-content'>";
				if( $related_items ){
					echo apply_filters( 'the_content', $proc_content );
				} else {
                    $more = 1;
					the_content();
				}
			echo "</div>";
		}
	}
}

function cws_blog_btn_more(){
	global $post;
	global $more;
	global $cws_theme_funcs;

	$btn_text = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option('blog_button_name') : 'Read More';

	$def_grid_atts = array(
		'post_hide_meta'		=> array(),
		'chars_count'			=> '',
		'more_btn_text'			=> $btn_text,
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );

	/* -----> Variables declaration <----- */
	$more = 0;
	$id = get_the_ID();
	$permalink = get_the_permalink( $id );

	$out = '';

	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();	

	if( isset($chars_count) && !empty($chars_count) && !is_archive() ){
		$chars_count = (int)$chars_count; //Chars count from vc_module
	} else {
		if( $cws_theme_funcs ){
			$chars_count = $cws_theme_funcs->cws_get_option('def_blog_chars_count'); //Chars count from theme options
		} else {
			$chars_count = 200;
		}
	}

	$content = $proc_content = $excerpt = $proc_excerpt = "";
	$content = $post->post_content;
	$excerpt = $post->post_excerpt;
	$read_more_exists = false;

	if ( !empty( $excerpt ) ){
		$read_more_exists = !empty( $content );
	} else if ( strpos( (string) $content, '<!--more-->' ) ){
		$read_more_exists = true;
	} else if ( !empty( $content ) && !empty( $chars_count ) ){
		$proc_content = get_the_content( "" );
		$proc_content = trim( preg_replace( '/[\s]{2,}/u', ' ', strip_shortcodes( strip_tags( $proc_content ) ) ) );
		$chars_count = (int)$chars_count;
		$proc_content = mb_substr( $proc_content, 0, $chars_count );
		$read_more_exists = strlen( $proc_content ) < strlen( $content );
	}

	if ( $read_more_exists && !in_array( 'read_more', $post_hide_meta ) ){
        $out .= "<div class='read-more-wrapper'>";
            $out .= "<a href='".esc_url($permalink)."' class='read-more'>$more_btn_text</a>";
        $out .= "</div>";
	}

	return $out;
}

function cws_blog_meta_author(){
	global $cws_theme_funcs;

	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);
	
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	/* -----> Variables declaration <----- */
	$out = '';
	$post_id = get_the_id();
	$post_meta = get_post_meta( $post_id, 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();

	$author_info = isset($post_meta['author_info']) ? $post_meta['author_info'] : '';
	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();
	$show_author = $cws_theme_funcs ? $cws_theme_funcs->cws_get_option( "blog_author" ) : true;

	if( $author_info ){
		$show_author = true;
	}

	if ( !in_array( 'author', $post_hide_meta ) && $show_author){
		$author = get_the_author();

		if ( !empty($author) ){
			ob_start();
				the_author_posts_link();
			$author_link = ob_get_clean();

			$out .= "<div class='post-meta-item post-author'>";
                $out .= "<span class='post-author-avatar'>";
                    $out .= "<a href='" . get_author_posts_url(get_the_author_meta('ID')) . "'>" . get_avatar
                        (get_the_author_meta('ID'), 35) . "</a>";
                $out .= "</span>";
                $out .= "<span class='post-author-name'>" . sprintf('%s', $author_link) . "</span>";
			$out .= "</div>";
		}
	}
	return $out;
}

function cws_blog_meta_likes(){
	global $cws_theme_funcs;

	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	$like_func = function_exists("cws_vc_shortcode_get_simple_likes_button") ? cws_vc_shortcode_get_simple_likes_button( get_the_ID() ) : "";
	$out = '';
	if ( !in_array( 'likes', $post_hide_meta ) ){
		if( $cws_theme_funcs ){
			$out .= "<div class='post-meta-item post-likes'>".$like_func."</div>";
		}
	}
	return $out;
}

function cws_blog_meta_comments(){
	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$pid = get_the_id();
	$permalink = get_the_permalink( $pid );	
	$permalink .= "#comments";

	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	$out = '';
	if ( !in_array( 'comments', $post_hide_meta ) ){
		$comments_n = get_comments_number();
		if ( (int)$comments_n > 0 ) {
			if( $comments_n == '1' ){
				$comment_text = esc_html__( 'Comment', 'metamax' );
			} else {
				$comment_text = esc_html__( 'Comments', 'metamax' );
			}

			$out .= "<span class='post-meta-item post-comments'>";
				$out .= "<a href='".esc_url($permalink)."'>";
					$out .= $comments_n;
					$out .= "<span> " . esc_html($comment_text) . "</span>";
				$out .= "</a>";
			$out .= "</span>";
		}
	}
	return $out;
}

function cws_post_categories(){
	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$out = '';
	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	if ( !in_array( 'cats', $post_hide_meta ) ){
		if ( has_category() ) {
			$out .= "<div class='post-meta-item post-category'>";
				$cats = "";
				if ( has_category() ) {
					ob_start();
					if( is_single() && !$related_items ){
						the_category ( ", " );
					} else {
						the_category ( ", " );
					}
					$cats .= ob_get_clean();
				}
				if ( !empty( $cats ) ){
					$out .= $cats;
				}
			$out .= "</div>";
		}
	}

	return $out;
}

function cws_post_tags(){
	$def_grid_atts = array(
		'post_hide_meta' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$out = '';
	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();

	if ( !in_array( 'tags', $post_hide_meta ) ){
		if ( has_tag() ) {
			$out .= "<div class='post-meta-item post-tags'>";
				$tags = "";
				if ( has_tag() ) {
					ob_start();
					the_tags ( "", ", ", "" );
					$tags .= ob_get_clean();
				}
				if ( !empty( $tags ) ){
					$out .= $tags;
				}
			$out .= "</div>";
		}
	}

	return $out;
}

function cws_single_post_output ( $atts = array() ) {
	extract( $atts );

	/* -----> Variables declaration <----- */
	$fill_atts = cws_blog_fill_atts($atts);
	$pid = get_the_id();
	$post_hide_meta = isset($post_hide_meta) && !empty($post_hide_meta) ? $post_hide_meta : array();
	$uniq_pid = uniqid( "single-post-" );
	$post_format = get_post_format();

	$media_meta = get_post_meta( $pid, 'cws_mb_post' );
	$media_meta = isset( $media_meta[0] ) ? $media_meta[0] : cws_meta_default();
	
	/* -----> Single blog output <----- */
	cws_blog_styles($uniq_pid);
	echo "<article id='$uniq_pid'";
		post_class( array( 'item', 'single-post' ) );
	echo ">";

		echo "<div class='post-wrapper'>";
			if( $media_meta['show_featured'] ){
				cws_blog_media();
			}

			if( $post_format == 'link' ){
                $url = (!empty($media_meta['link']) ? $media_meta['link'] : '#');
                $title = $media_meta['link_title'];
                if ( !empty($title) ) {
                    echo "<div class='post-option format-link'>";
                        echo "<a target='_blank' href='" . esc_url($url) . "'>" . esc_html($title) . "</a>";
                    echo "</div>";
                }
			}
            if( $post_format == 'quote' ){
                $text = $media_meta['quote_text'];
                $author = $media_meta['quote_author'];
                if ( !empty($text) || !empty($author) ) {
                    echo "<div class='post-option format-quote'>";
                        echo "<h6>" . esc_html($text) . "</h6>";
                        if (!empty($author)) {
                            echo "<span>" . $author . "</span>";
                        }
                    echo "</div>";
                }
            }

			echo "<div class='post-info'>";
				cws_blog_content();
			echo "</div>";

			cws_page_links();

		echo "</div>";

	echo "</article>";
}

?>