<?php

function cws_vc_shortcode_cws_portfolio_posts_grid ( $atts = array(), $content = "" ){
	$out = "";
	$first_color = function_exists("cws_vc_shortcode_get_option") ? esc_attr( cws_vc_shortcode_get_option( 'theme-first-color' ) ) : "";
	$defaults = array(
		'title'									=> '',
		'title_align'							=> 'left',
		'info_pos'								=> 'inside_img',
		'total_items_count'						=> get_option( 'posts_per_page' ),
		'layout'								=> 'def', 
		'crop_images'							=> false,
		'masonry'								=> false,
		'cws_portfolio_show_data_override'		=> false,
		'full_width'							=> false,
		'item_shadow'							=> false,
		'en_isotope'							=> false,
        'carousel_infinite'					    => false,
        'carousel_autoplay'					    => false,
        'autoplay_speed'					    => '3000',
        'pause_on_hover'					    => false,
		'info_align'							=> 'center',
		'cws_portfolio_data_to_show'			=> '',
		'chars_count'							=> '',
		'customize_carousel'					=> '',
		'pagination_carousel'					=> '',
		'navigation_carousel'					=> '',
		'display_style'							=> 'grid',
		'link_show'								=> 'area_link',
		'select_filter'							=> '',
		'carousel_pagination'					=> '',
		'items_pp'								=>  esc_html( get_option( 'posts_per_page' ) ),
		'paged'									=> 1,
		'tax'									=> '',
		'titles'								=> '',
		'terms'									=> '',
		'pagination_grid'						=> 'standard_with_ajax',
		'pagination_grid_text'					=> '',
		'addl_query_args'						=> array(),
		'call_from'								=> '',
		'el_class'								=> ''
	);
	$proc_atts = shortcode_atts( $defaults, $atts );
	$shot = isset( $GLOBALS['cws_row_atts'] ) ? $GLOBALS['cws_row_atts'] : '';
	extract( $proc_atts );
	if(!empty($shot)){
		extract($shot);
	}
	global $cws_theme_funcs;
	$terms = isset( $atts[ $tax . "_terms" ] ) ? $atts[ $tax . "_terms" ] : "";
	$terms = isset( $atts[ 'terms' ] ) && !empty($atts[ 'terms' ]) ? $atts[ 'terms' ] : $terms;

	$titles = !empty($titles) ? explode( ',', $titles ) : null;
	if ( $tax == 'title' && !empty( $titles ) ) {
		$items_pp = count( $titles );
	}
	$display_style = esc_html($display_style);

	$pid = get_the_id();
	$p_meta = get_post_meta( $pid, 'cws_mb_post' );
	$p_meta = isset( $p_meta[0] ) ? $p_meta[0] : array();
	$section_id = uniqid( 'cws-portfolio-posts-grid-' );
	$ajax_data = array();
	$total_items_count = !empty( $total_items_count ) ? (int)$total_items_count : PHP_INT_MAX;
	$items_pp = !empty( $items_pp ) ? (int)$items_pp : esc_html( get_option( 'posts_per_page' ) );
	$paged = (int)$paged;
	$select_filter = (bool)$select_filter;
	$crop_images = (bool)$crop_images;
	$masonry = (bool)$masonry;

	$carousel_pagination = $carousel_pagination == '1' ? true : false;
	$def_layout = cws_vc_shortcode_get_option( 'def_layout_portfolio' );
	$def_layout = isset( $def_layout ) ? $def_layout : "";
	$layout = ( empty( $layout ) || $layout === "def" ) ? $def_layout : $layout; 
	$cws_portfolio_show_data_override = !empty( $cws_portfolio_show_data_override ) ? true : false;
	$cws_portfolio_data_to_show = explode( ",", $cws_portfolio_data_to_show );
	$cws_portfolio_def_data_to_show = cws_vc_shortcode_get_option( 'def_cws_portfolio_data_to_show' );
	$cws_portfolio_def_data_to_show  = isset( $cws_portfolio_def_data_to_show ) ? $cws_portfolio_def_data_to_show : array();
	if(is_string($cws_portfolio_def_data_to_show) && !empty($cws_portfolio_def_data_to_show)){
		$cws_portfolio_def_data_to_show = explode(",", $cws_portfolio_def_data_to_show);
	}
	$cws_portfolio_data_to_show = $cws_portfolio_show_data_override ? $cws_portfolio_data_to_show : $cws_portfolio_def_data_to_show;
	$link_show = explode( ",", $link_show );

	$el_class = esc_attr( $el_class );
	$sb =  function_exists('cws_vc_shortcode_get_sidebars') ? cws_vc_shortcode_get_sidebars() : "";
	$sb_layout = isset( $sb['layout_class'] ) ? $sb['layout_class'] : '';	
	$terms = explode( ",", $terms );	

	$terms_temp = array();
	foreach ( $terms as $term ) {
		if(strrpos($term, ') ')){
			$term = str_replace(substr($term, 0, strrpos($term, ') ') + 2), "", $term); 
		}
		
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
	$terms = !empty( $terms ) ? $terms : $all_terms;
	$not_in = (1 == $paged) ? array() : get_option( 'sticky_posts' );
	$query_args = array('post_type'			=> 'cws_portfolio',
						'post_status'		=> 'publish',
						'post__not_in'		=> $not_in
						);
	if ( in_array( $display_style, array( 'grid', 'filter', 'filter_with_ajax' ) ) ){
		$query_args['posts_per_page']		= $items_pp;
		$query_args['paged']		= $paged;
	}
	else{
		$query_args['nopaging']				= true;
		$query_args['posts_per_page']		= -1;
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
	$query_args['orderby'] 	= "menu_order date title";
	$query_args['order']	= "ASC";
	$query_args = array_merge( $query_args, $addl_query_args );
	$q = new WP_Query( $query_args );
	$found_posts = $q->found_posts;
	$requested_posts = $found_posts > $total_items_count ? $total_items_count : $found_posts;
	$max_paged = $found_posts > $total_items_count ? ceil( $total_items_count / $items_pp ) : ceil( $found_posts / $items_pp );
	$cols = in_array( $layout, array( 'medium', 'small' ) ) ? 1 : (int)$layout;

	$is_carousel = $display_style == 'carousel' && $requested_posts > $cols;
	
	wp_enqueue_script( 'fancybox' );

	$is_filter = in_array( $display_style, array( 'filter', 'filter_with_ajax' ) ) && !empty( $terms ) ? true : false;
	
	$filter_vals = array();
	$isotope_init = ($display_style != 'carousel' && $en_isotope) || in_array( $display_style, array( 'filter', 'filter_with_ajax' ) ) || ($crop_images || $masonry) ? " isotope_init" : "";

	$use_pagination = in_array( $display_style, array( 'grid', 'filter', 'filter_with_ajax' ) ) && $max_paged > 1;

	$pagination_grid = isset($pagination_grid) && !empty($pagination_grid) ? $pagination_grid : '';
	$dynamic_content = $is_filter || $use_pagination;
	if ( $is_carousel ){
		wp_enqueue_script( 'slick-carousel' );
	}
	else if ( in_array( $layout, array( "2", "3", "4", "5" ) ) || $dynamic_content ){
		wp_enqueue_script( 'isotope' );
	}
	if ( $dynamic_content ){
		wp_enqueue_script( 'slick-carousel' ); // for dynamically loaded gallery posts		
	}
	wp_enqueue_script( 'imagesloaded' );
	if ($full_width == 'stretch_row_content' || $full_width == 'stretch_row_content_no_spaces') {
		$full_width = true;
	} else{
		$full_width = '';
	}
	$post_url = esc_url(get_the_permalink());
	$thumbnail_props = has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id( ),'full') : array();
	$thumbnail = !empty( $thumbnail_props ) ? $thumbnail_props[0] : '';
	$real_thumbnail_dims = array();
	if ( !empty( $thumbnail_props ) && isset( $thumbnail_props[1] ) ) $real_thumbnail_dims['width'] = $thumbnail_props[1];
	if ( !empty(  $thumbnail_props ) && isset( $thumbnail_props[2] ) ) $real_thumbnail_dims['height'] = $thumbnail_props[2];
	$isotope = array('crop_images' => $crop_images , 'masonry' => $masonry , 'columns' => $layout );

	if ($isotope['masonry']){
		$thumbnail_dims = cws_vc_shortcode_get_cws_portfolio_thumbnail_dims( false, $real_thumbnail_dims );
		extract(cws_portfolio_masonry(true, $thumbnail_dims, $p_meta, $isotope));
	}
	$isotope_line_count = !empty($isotope_line_count) ? $isotope_line_count : '';
	$isotope_col_count = !empty($isotope_col_count) ? $isotope_col_count : '';

	ob_start ();

	$classes = '';
	$classes .= $carousel_pagination ? " carousel_pagination" : "";
	$classes .= $carousel_autoplay ? " carousel_auto" : "";

	$classes_add = '';
	$classes_add .= $cws_portfolio_show_data_override && !empty($cws_portfolio_data_to_show) ? " meta-show" : "";
	$classes_add .= $select_filter ? " select-filter" : " simple-filter";
	$classes_add .= $full_width ? " full-width-style" : "";
	$classes_add .= " align-".$info_align;

	$en_isotope = !empty($en_isotope) ? $en_isotope : '';
	if ($crop_images == '1' && $masonry == '1') {
		$isotope_style = " isotope masonry crop";
	} else if ($crop_images == false && $masonry == '1') {
		$isotope_style = " isotope masonry";
	} else if ($dynamic_content) {
		$isotope_style = " isotope";
	} else if ($en_isotope) {
		$isotope_style = " isotope";
	} else {
		$isotope_style = "";
	}
	$layout_cl = "posts-grid-{$layout}";
	if(in_array( $display_style, array( 'filter' ) )){
		$layout_cl .= " standard_filter";
	}

	$customize_carousel = (bool)$customize_carousel;
	$extra_style = '.grid-row:not(.related-portfolio) ';
	ob_start();	
	if(!empty($customize_carousel) && !empty($pagination_carousel)){
		echo "
		".$extra_style."#{$section_id} .owl-pagination .owl-page.active:before{
			background-color: $pagination_carousel;
		}";	
		echo "
		".$extra_style."#{$section_id} .owl-pagination .owl-page,
		".$extra_style."#{$section_id} .owl-pagination .owl-page.active{
			-webkit-box-shadow: 0px 0px 0px 3px $pagination_carousel;
		    -moz-box-shadow: 0px 0px 0px 3px $pagination_carousel;
		    box-shadow: 0px 0px 0px 3px $pagination_carousel;
		}";	
	}
	$styles = ob_get_clean();
	$styles = json_encode($styles);

	if ( $is_carousel ){
		$classes_add .= isset($auto_play_carousel) && !empty($auto_play_carousel) ? ' auto_play_owl' : "";
		$classes_add .= isset($navigation_carousel) && !empty($navigation_carousel) ? ' navigation_owl' : "";
		$classes_add .= isset($pagination_carousel) && !empty($pagination_carousel) ? ' pagination_owl' : "";
	}

	echo "<section id='$section_id' class='posts-grid cws-portfolio-posts-grid cws-portfolio-posts-grid{$isotope_init} $layout_cl posts-grid-{$display_style}" . ( $dynamic_content ? " dynamic_content" : "" ) . ( !empty( $el_class ) ? " $el_class" : "" ) . $classes_add . " render_styles ' data-style='".esc_attr($styles)."' data-col='".esc_attr($layout)."'>";
		if ( $is_filter && count( $terms ) > 1 ){

			foreach ( $terms as $term ) {
				if ( empty( $term ) ) continue;
				$term_obj = get_term_by( 'slug', $term, $tax );
				if ( empty( $term_obj ) ) continue;

				$term_name = $term_obj->name;
				$filter_vals[$term_obj->slug] = $term_name;
			}
			if ( $filter_vals > 1 ){
				wp_enqueue_script( 'tweenmax' );
				echo "<div class='nav cws-portfolio-nav posts_grid_nav'>";
					echo "<div class='filter-list'>";
						echo "<div class='filter-item active all'>";
							echo "<a href class='nav_item cws-portfolio-nav-item posts_grid_nav_item' data-nav-val='_all_'>";
								echo "<span class='title_nav_portfolio'><span class='txt_title'>" . esc_html__( 'All', 'cws-essentials' ) . "</span></span>";
							echo "</a>";
						echo "</div>";

						foreach ( $filter_vals as $term_slug => $term_name ){
							echo "<div class='filter-item'>";
								echo "<a href class='nav_item cws-portfolio-nav-item posts_grid_nav_item' data-nav-filter='." . esc_html( $term_slug ) . "' data-nav-val='" . esc_html( $term_slug ) . "'>";
									echo "<span class='title_nav_portfolio'><span class='txt_title'>" . esc_html( $term_name ) . "</span></span>";
								echo "</a>";
							echo "</div>";
						}

					echo "</div>";
				echo "</div>";
			}
			
		} else{
			echo !empty( $title ) ? "<h2 class='widgettitle'>" . esc_html( $title ) . "</h2>" : "";
		}

		$carousel_atts = '';

		if( $is_carousel ){
			$carousel_atts .= ' data-columns="'.$layout.'"';
			$carousel_atts .= ' data-draggable="on"';
			$carousel_atts .= ' data-pagination="on"';

            $carousel_atts .= ( isset($carousel_infinite) && !empty($carousel_infinite) ? ' data-infinite="on"' : '');
            $carousel_atts .= ( isset($carousel_autoplay) && !empty($carousel_autoplay) ? ' data-autoplay="on"' : '');
            $carousel_atts .= ( $carousel_autoplay && !empty($autoplay_speed) ? ' data-autoplay-speed="'.esc_attr($autoplay_speed).'"' : '');
            $carousel_atts .= ( $carousel_autoplay && $pause_on_hover ? ' data-pause-on-hover="on"' : '');
		}

		echo "<div class='portfolio-module-wrapper cws-vc-shortcode-wrapper".( $masonry ? " layout-masonry" :
                (!$is_carousel && $en_isotope ? " layout-isotope" : "") ) . ( $is_carousel ? ' cws-carousel-wrapper' : '' ) ."' ".$carousel_atts.">";
			echo "<div class='" . ( $is_carousel ? "cws-carousel" : "cws-vc-shortcode-grid" . ( ( in_array( $layout,
                            array( "2", "3", "4", "5" ) ) || $dynamic_content ) ? $isotope_style : "" ) ) . $classes .  "'>";
				if (!$is_carousel) {
					echo "<div class='grid-sizer'></div>";
				}
				$GLOBALS['cws_vc_shortcode_posts_grid_atts'] = array(
					'post_type'						=> 'cws_portfolio',
					'layout'						=> $layout,
					'info_align'					=> $info_align,
					'display_style'					=> $display_style,
					'sb_layout'						=> $sb_layout,
					'cws_portfolio_data_to_show'	=> $cws_portfolio_data_to_show,
					'total_items_count'				=> $total_items_count,
					'info_pos'						=> $info_pos,
					'crop_images'					=> $crop_images,
					'masonry'						=> $masonry,
					'full_width'					=> $full_width,
					'item_shadow'					=> $item_shadow,
					'link_show'						=> $link_show,
					'isotope_line_count'			=> $isotope_line_count,
					'isotope_col_count'				=> $isotope_col_count,
					'chars_count'					=> $chars_count,
					'pagination_grid'				=> $pagination_grid,
					'filter_vals'					=> $filter_vals,
					'call_from'						=> $call_from,
					'tax'							=> $tax,
					'pagination_grid_text'			=> $pagination_grid_text,
					);
				if ( function_exists( "cws_vc_shortcode_cws_portfolio_posts_grid_posts" ) ){
					call_user_func_array( "cws_vc_shortcode_cws_portfolio_posts_grid_posts", array( $q ) );
				}
				unset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] );
			echo "</div>";
			if ( $dynamic_content ){
				cws_loader_html();
			}
		echo "</div>";
		if ( $use_pagination ){
			if ( $pagination_grid == 'load_more' ){
				cws_load_more ($pagination_grid_text);
			}
			else{
				cws_pagination ( $paged, $max_paged, true );
			}
		}
		if ( $dynamic_content ){
			$ajax_data['section_id']						= $section_id;
			$ajax_data['post_type']							= 'cws_portfolio';
			$ajax_data['cws_portfolio_data_to_show']		= $cws_portfolio_data_to_show;
			$ajax_data['link_show']							= $link_show;
			$ajax_data['layout']							= $layout;
			$ajax_data['display_style']						= $display_style;
			$ajax_data['sb_layout']							= $sb_layout;
			$ajax_data['total_items_count']					= $total_items_count;
			$ajax_data['full_width']						= $full_width;
			$ajax_data['items_pp']							= $items_pp;
			$ajax_data['page']								= $paged;
			$ajax_data['pagination_grid_text']				= $pagination_grid_text;
			$ajax_data['max_paged']							= $max_paged;
			$ajax_data['tax']								= $tax;
			$ajax_data['terms']								= $terms;
			$ajax_data['filter']							= $is_filter;
			$ajax_data['current_filter_val']				= '_all_';
			$ajax_data['addl_query_args']					= $addl_query_args;
			$ajax_data['info_pos']							= $info_pos;
			$ajax_data['crop_images']						= $crop_images;
			$ajax_data['masonry']							= $masonry;
			$ajax_data['item_shadow']						= $item_shadow;
			$ajax_data['isotope_line_count']				= $isotope_line_count;
			$ajax_data['isotope_col_count']					= $isotope_col_count;
			$ajax_data['chars_count']						= $chars_count;
			$ajax_data['info_align']						= $info_align;
			$ajax_data['pagination_grid']					= $pagination_grid;
			$ajax_data['filter_vals']					    = $filter_vals;
			$ajax_data_str = json_encode( $ajax_data );
			echo "<form id='{$section_id}_data' class='posts-grid-data ajax-data-form cws-portfolio-ajax-data-form posts-grid-ajax-data-form'>";
				echo "<input type='hidden' id='{$section_id}-ajax-data' class='ajax-data cws-portfolio-ajax-data posts-grid-ajax-data' name='{$section_id}-ajax-data' value='$ajax_data_str' />";
			echo "</form>";
		}
	echo "</section>";
	$out = ob_get_clean();
	return $out;
}

function cws_vc_shortcode_cws_portfolio_posts_grid_posts ( $q = null ){
	if ( !isset( $q ) ) return;
	$def_grid_atts = array(
		'layout'						=> '1',
		'cws_portfolio_data_to_show'	=> array(),
		'total_items_count'				=> PHP_INT_MAX
	);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );
	$paged = $q->query_vars['paged'];
	if ( $paged == 0 && $total_items_count < $q->post_count ){
		$post_count = $total_items_count;
	}
	else{
		$ppp = $q->query_vars['posts_per_page'];
		$posts_left = $total_items_count - ( $paged - 1 ) * $ppp;
		$post_count = $posts_left < $ppp ? $posts_left : $q->post_count;
	}
	if ( $q->have_posts() ):
		ob_start();
		while( $q->have_posts() && $q->current_post < $post_count - 1 ):
			$q->the_post();
			cws_vc_shortcode_cws_portfolio_posts_grid_post ();
		endwhile;
		wp_reset_postdata();
		ob_end_flush();
	endif;				
}

function cws_vc_shortcode_get_cws_portfolio_thumbnail_dims ( $eq_thumb_height = false, $real_dims = array() ) {
	global $cws_theme_funcs;

	$def_grid_atts = array(
					'layout'				=> '1',
					'sb_layout'				=> '',
					'masonry'				=> '',
					'full_width'			=> false,
				);
	$def_single_atts = array(
					'sb_layout'				=> '',
				);
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;

	$single_atts = isset( $GLOBALS['cws_vc_shortcode_single_post_atts'] ) ? $GLOBALS['cws_vc_shortcode_single_post_atts'] : $def_single_atts;
	$ajax_single_atts = isset( $GLOBALS['cws_vc_shortcode_single_ajax_atts'] ) ? $GLOBALS['cws_vc_shortcode_single_ajax_atts'] : $def_grid_atts;

	$single = is_single();
	if ( $single ){
		extract( $single_atts );
		extract( $ajax_single_atts );
	}
	else{
		extract( $grid_atts );
	}
	$display_style = !empty($display_style) ? $display_style : '';
	$display_style = esc_attr( $display_style );
	$layout = !empty($layout) ? $layout : '1';
	$single_fw = isset( $post_meta['full_width'] ) ? $post_meta['full_width'] : false;
	$dims = array( 'width' => 0, 'height' => 0 );
	if ($single){
		if ( empty( $sb_layout ) ){
			if ( ( empty( $real_dims ) || ( isset( $real_dims['width'] ) ) ) ){
				if ($single_fw) {
					$dims['width'] = 1920;
				} else{
					$dims['width'] = 1170;
				}
			}
		}
		else if ( $sb_layout === "single" ){
			if ( ( empty( $real_dims ) || ( isset( $real_dims['width'] ) && $real_dims['width'] > 870 ) ) || $eq_thumb_height ){
				$dims['width'] = 870;
			}
		}
		else if ( $sb_layout === "double" ){
			if ( ( empty( $real_dims ) || ( isset( $real_dims['width'] ) && $real_dims['width'] > 570 ) ) || $eq_thumb_height ){
				$dims['width'] = 570;
			}
		}
	} else if ($full_width){
		switch ($layout){
			case "1":	
				$dims['width'] = 1920;
				if ( !isset( $real_dims['height'] ) ){
					$dims['height'] = 1080;
				}		
				break;
			case '2':
				$dims['width'] = 921;
				if ( !isset( $real_dims['height'] ) ){
					$dims['height'] = 208;
				}		
				break;
			case '3':
				$dims['width'] = 604;
				if ( !isset( $real_dims['height'] ) ){
					$dims['height'] = 208;
				}		
				break;
			case '4':
				$dims['width'] = 445;
				if ( !isset( $real_dims['height'] ) ){
					$dims['height'] = 152;
				}
				break;
			case '5':
				$dims['width'] = 351;
				if ( !isset( $real_dims['height'] ) ){
					$dims['height'] = 152;
				}
				break;
		}
	} else{
		switch ($layout){
			case "1":
				if ( empty( $sb_layout ) ){
					$dims['width'] = 1170;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 979;
					}	
				}
				else if ( $sb_layout === "single" ){
					$dims['width'] = 770;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 644;
					}		
				}
				else if ( $sb_layout === "double" ){
					$dims['width'] = 570;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 310;
					}	
				}
				break;
			case '2':
				if ( empty( $sb_layout ) ){
					$dims['width'] = 570;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 477;
					}	
				}
				else if ( $sb_layout === "single" ){
					$dims['width'] = 472;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 310;
					}	
				}
				else if ( $sb_layout === "double" ){
					$dims['width'] = 270;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 142;
					}		
				}
				break;
			case '3':
				if ( empty( $sb_layout ) ){
					$dims['width'] = 370;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 370;
					}	
				}
				else if ( $sb_layout === "single" ){
					$dims['width'] = 270;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 197;
					}	
				}
				else if ( $sb_layout === "double" ){
					$dims['width'] = 170;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 103;
					}		
				}			
				break;
			case '4':
				if ( empty( $sb_layout ) ){
					$dims['width'] = 270;
					if (!isset( $real_dims['height'] ) ){
						$dims['height'] = 226;
					}
				}
				else if ( $sb_layout === "single" ){
					$dims['width'] = 195;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 142;
					}		
				}
				else if ( $sb_layout === "double" ){
					$dims['width'] = 120;
					if (!isset( $real_dims['height'] ) ){
						$dims['height'] = 58;
					}	
				}			
				break;			
			case '5':
				if ( empty( $sb_layout ) ){
					$dims['width'] = 210;
					if (!isset( $real_dims['height'] ) ){
						$dims['height'] = 226;
					}
				}
				else if ( $sb_layout === "single" ){
					$dims['width'] = 150;
					if ( !isset( $real_dims['height'] ) ){
						$dims['height'] = 142;
					}		
				}
				else if ( $sb_layout === "double" ){
					$dims['width'] = 90;
					if (!isset( $real_dims['height'] ) ){
						$dims['height'] = 58;
					}	
				}			
				break;			
		}
	}

	return $dims;
}

function cws_vc_shortcode_get_cws_portfolio_chars_count ( $cols = null ){
	$number = 155;
	switch ( $cols ){
		case '1':
			$number = 300;
			break;
		case '2':
			$number = 130;
			break;
		case '3':
			$number = 70;
			break;
		case '4':
			$number = 55;
			break;
	}
	return $number;
}

function cws_vc_shortcode_cws_portfolio_posts_grid_post (){
	$def_grid_atts = array(
					'layout'						=> '1',
					'cws_portfolio_data_to_show'	=> array(),
					'display_style'					=> '',
					'info_pos'						=> 'inside_img',
					'item_shadow'					=> false,
				);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$p_type = isset( $post_meta['p_type'] ) ? $post_meta['p_type'] : '';
	$video_type = isset( $post_meta['video_type'] ) ? $post_meta['video_type'] : '';	
	$slider_type = isset( $post_meta['slider_type'] ) ? $post_meta['slider_type'] : '';	
	$popup = isset( $post_meta['video_type']['popup'] ) ? $post_meta['video_type']['popup'] : '';
	$popup = $popup == 'true' ? true : false;
	$enable_hover = isset( $post_meta['enable_hover'] ) ? $post_meta['enable_hover'] : false;
	$display_style = esc_attr( $display_style );
	$info_pos = !empty($info_pos) ? $info_pos : 'inside_img';
	$item_shadow = !empty($item_shadow) ? $item_shadow : false;

	$video_on = false;
	$slider_on = false;

	$pid = get_the_id();
// $call_from == 'related'
	if ( empty( $cws_portfolio_data_to_show ) || empty( $cws_portfolio_data_to_show[0] ) ){
		if ( has_post_thumbnail( $pid ) ){
			cws_vc_shortcode_cws_portfolio_posts_grid_post_media ();
			echo "<div class='cws-portfolio-content-wrap'>";
				cws_vc_shortcode_cws_portfolio_posts_grid_post_hover ();
			echo "</div>";
			echo "</div>";
			echo "</article>";	
		}
	}else{
		cws_vc_shortcode_cws_portfolio_posts_grid_post_media (); 
		if ($info_pos == 'inside_img'){
			if (!$video_on && $enable_hover == 1 && !$slider_on) {
				echo "<div class='cws-portfolio-content-wrap'>";
				cws_vc_shortcode_cws_portfolio_posts_grid_post_hover ();
				echo "<div class='desc-img'>";
				cws_vc_shortcode_cws_portfolio_posts_grid_post_title ();
				cws_vc_shortcode_cws_portfolio_posts_grid_post_terms ();
				cws_vc_shortcode_cws_portfolio_posts_grid_post_content ();
				echo "</div>";
				echo "</div>";
				
			}
		} else {
			echo "<div class='under-image-portfolio'>";
				cws_vc_shortcode_cws_portfolio_posts_grid_post_title ();
				echo "<div class='divider'></div>";
				cws_vc_shortcode_cws_portfolio_posts_grid_post_terms ();
				cws_vc_shortcode_cws_portfolio_posts_grid_post_content ();
			echo "</div>";
		}
		echo "</div>";
		if ($item_shadow == 1 && $enable_hover == 1) {
			echo "<div class='item-shadow-box'></div>";
		}
		echo "</article>";
	}
}

function getUrl() {
	$url  = isset($_SERVER["HTTPS"]) && @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] :  'https://'.$_SERVER["SERVER_NAME"];
	$url .= ( $_SERVER["SERVER_PORT"] != 80 ) ? ":".$_SERVER["SERVER_PORT"] : "";
	$url .= $_SERVER["REQUEST_URI"];
	return $url;
}

function cws_vc_shortcode_cws_portfolio_posts_grid_post_media (){
	$pid = get_the_id();
	$post_url = esc_url(get_the_permalink());
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$p_type = isset( $post_meta['p_type'] ) ? $post_meta['p_type'] : '';	
	$permalink = get_the_permalink( $pid );
	$def_grid_atts = array(
					'layout'						=> '1',
					'cws_portfolio_data_to_show'	=> array(),
					'display_style'					=> 'grid',
					'info_pos'						=> 'inside_img',
					'crop_images'					=> false,
					'masonry'						=> false,
				);	
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );
	$display_style = !empty($display_style) ? $display_style : "";
	$crop_images = !empty($crop_images) ? $crop_images : "";
	$masonry = !empty($masonry) ? $masonry : "";
	$display_style = esc_attr( $display_style );
	$info_pos = !empty($info_pos) ? $info_pos : 'inside_img';
	// $under_img = (bool)$under_img;
	$slider_type = isset( $post_meta['slider_type'] ) ? $post_meta['slider_type'] : '';
	$video_type = isset( $post_meta['video_type'] ) ? $post_meta['video_type'] : '';
	$enable_hover = isset( $post_meta['enable_hover'] ) ? $post_meta['enable_hover'] : false;
	$custom_url = isset( $post_meta['link_options_url'] ) ? $post_meta['link_options_url'] : "";
	$fancybox = isset( $post_meta['link_options_fancybox'] ) ? $post_meta['link_options_fancybox'] : false;
	$classes = "";
	$classes .= $info_pos == 'under_img' ? " under-img" : "";

	if( $info_pos == 'under_img' || ($info_pos == 'inside_img' && empty($cws_portfolio_data_to_show[0])) ){
		$classes .= ' clear-image';
	}

	$crop_images = $crop_images == 'true' ? true : "";
	$masonry = $masonry == 'true' ? true : false;
	$post_url = esc_url(get_the_permalink());
	$thumbnail_props = has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id( ),'full') : array();
	$thumbnail = !empty( $thumbnail_props ) ? $thumbnail_props[0] : '';
	$real_thumbnail_dims = array();
	if ( !empty( $thumbnail_props ) && isset( $thumbnail_props[1] ) ) $real_thumbnail_dims['width'] = $thumbnail_props[1];
	if ( !empty(  $thumbnail_props ) && isset( $thumbnail_props[2] ) ) $real_thumbnail_dims['height'] = $thumbnail_props[2];
	
	$thumbnail_dims = cws_vc_shortcode_get_cws_portfolio_thumbnail_dims( false, $real_thumbnail_dims );

	$isotope = array('crop_images' => $crop_images , 'masonry' => $masonry , 'sb_layout' => $sb_layout , 'columns' => $layout );
	if ($isotope['masonry']){
		extract(cws_portfolio_masonry($thumbnail, $thumbnail_dims, $post_meta, $isotope, $full_width));
	}
	$video_t = isset( $post_meta['video_type']['video_t'] ) ? $post_meta['video_type']['video_t'] : '';
	$video = isset( $post_meta['video_type'][$video_t . '_t']['url'] ) ? $post_meta['video_type'][$video_t . '_t']['url'] : '';
	$video_img = isset( $post_meta['video_type']['img'] ) ? $post_meta['video_type']['img'] : '';
	$popup_grid = isset( $post_meta['video_type']['popup_grid'] ) ? $post_meta['video_type']['popup_grid'] : '';
	$popup_grid = (bool)$popup_grid;
	if (!empty($thumbnail)) {
		if(!empty($crop_images)){
			$thumbnail_dims['crop'] = true;
		}
		$thumb_obj = cws_get_img( get_post_thumbnail_id( ), $thumbnail_dims, false );
	}
	$thumb_url = isset( $thumb_obj[0] ) ? esc_url($thumb_obj[0]) : "";
	$retina_thumb = isset( $thumb_obj[3] ) ? $thumb_obj[3] : false;

	$get_alt = get_post_meta(get_post_thumbnail_id( ), '_wp_attachment_image_alt', true); 
	$img_alt = " alt='" . (!empty($get_alt) ? $get_alt : get_the_title(get_post_thumbnail_id( ))) . "'";

	$link_fancy = "";
	$link_fancy .= $fancybox ? "class='fancy fa flaticon-magnifying-glass'" : "";
	$link_fancy .= $fancybox ? " href='$thumbnail'" : "";
	$popup = isset( $post_meta['video_type']['popup'] ) ? $post_meta['video_type']['popup'] : '';
	$popup = (bool)$popup;
	$video_on = false;
	$slider_on = false;
	$link_url = "";
	$link_url .= $custom_url ? $custom_url : $post_url;

	$page_url = getUrl();

	$name_slug = array();
	if(!empty($tax) && $tax !== 'title'){
		foreach (get_the_terms($pid, $tax) as $key => $value) {
			$name_slug[] = $value->slug ;
		}		
	}	

		$data_col = !empty($isotope_col_count) ? " data-masonry-col='$isotope_col_count'" : ""; 
		$data_line = !empty($isotope_line_count) ? " data-masonry-line='$isotope_line_count'" : ""; 
		
		$uniq_pid = uniqid( "post-post-" );
		echo "<article id='$uniq_pid'";
		echo " class='";
		if(!empty($name_slug)){
			foreach ($name_slug as $key => $value) { 
				echo $value. " tax-portfolio ";
			};	
		}else{
			echo "no-tax ";
		}
		echo "item portfolio-item-post portfolio-item-grid-post item clearfix" . $classes . "' $data_col $data_line>";
			echo "<div class='item-content'>";
				echo "<div class='post-media post-post-media post-posts-grid-post-media'>";

					echo "<div class='pic'>";
						if ( $retina_thumb ) {
							echo "<img src='".esc_url($thumb_url)."' data-at2x='".esc_url($retina_thumb)."' ".$img_alt." />";
						}
						else{
							echo "<img src='".esc_url($thumb_url)."' data-no-retina ".$img_alt." />";
						}
					echo "</div>";

					if ($info_pos == 'under_img' && $enable_hover == 1 && !$video_on) {
						echo "<div class='cws-portfolio-content-wrap'>";
							cws_vc_shortcode_cws_portfolio_posts_grid_post_hover ();
						echo "</div>";
					}
				echo "</div>";
}

function cws_vc_shortcode_cws_portfolio_posts_grid_post_hover ( $video_link = '', $links_html = '' ){
	$pid = get_the_id ();
	$def_grid_atts = array(
					'layout'						=> '1',
					'display_style'					=> '',
					'link_show'						=> array(),
				);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );
	$display_style = esc_attr( $display_style );
	$display_style = !empty($display_style) ? $display_style : "";
	$post_url = esc_url(get_the_permalink());
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$custom_url = isset( $post_meta['link_options_url'] ) ? $post_meta['link_options_url'] : "";
	$link_options_fancybox = isset( $post_meta['link_options_fancybox'] ) ? $post_meta['link_options_fancybox'] : false;
	$link_options_single = isset( $post_meta['link_options_single'] ) ? $post_meta['link_options_single'] : false;
	$enable_hover = isset( $post_meta['enable_hover'] ) ? $post_meta['enable_hover'] : false;
	$link_url = $custom_url ? $custom_url : $post_url;
	$thumbnail_props = has_post_thumbnail() ? wp_get_attachment_image_src(get_post_thumbnail_id( ),'full') : array();
	$thumbnail = !empty( $thumbnail_props ) ? $thumbnail_props[0] : '';
	$thumbnail = !empty($video_link) ? $video_link : $thumbnail;
	$link_show = !empty($link_show) ? $link_show : array();
	$single = in_array( 'single_link', $link_show );
	$popup = in_array( 'popup_link', $link_show );
	$area = in_array( 'area_link', $link_show );

	if( !$single ) {
		if( is_single() ){
			$single = $link_options_single == 1 ? true : false;
		}
	}
	if( !$popup ) {
		if( is_single() ){
			$popup = $link_options_fancybox == 1 ? true : false;
		}
	}


	echo "<div class='links-wrap'>";
	if ($single || $popup) {
	    if( $call_from == 'related' ){
	        echo "<a href='$link_url' class='links area'></a>";
	    }
	    if( !empty($links_html) ){
	        echo $links_html;
	    }
	    if( $popup && $link_options_fancybox == '1' && $call_from != 'related'){
	        echo "<a href='" . $thumbnail . "' class='plus-wrapper links fancy" . (!empty($video_link) ? ' fancybox.iframe' : '') . "'>";
	            echo "<span></span>";
	            echo "<span></span>";
	        echo "</a>";
	    }
	}
	if ( $area ){
	    echo "<a href='" . (!empty($video_link) ? $video_link : $link_url) . "' class='links area" . (!empty($video_link) ? ' fancy fancybox.iframe' : '') . "'></a>";
	}
	echo "</div>";
}

function cws_vc_shortcode_cws_portfolio_posts_grid_post_title (){
	$pid = get_the_id ();
	$def_grid_atts = array(
					'layout'						=> '1',
					'cws_portfolio_def_data_to_show'	=> array(),
					'display_style'					=> '',
				);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );
	$info_align = !empty($info_align) ? $info_align : '';
	$display_style = "";
	$display_style = esc_attr( $display_style );
	if ( in_array( 'title', $cws_portfolio_data_to_show ) ){
		$title = get_the_title();
		$permalink = get_the_permalink();
		echo !empty( $title ) ?	"<h3 class='cws-portfolio-post-title post-title'><a href='$permalink'>" . $title . "</a></h3>" : "";
	}	
}

function cws_vc_shortcode_cws_portfolio_posts_grid_post_content (){
	if(class_exists('WPBMap')){
		WPBMap::addAllMappedShortcodes();
	}
	$pid = get_the_id ();
	$post = get_post( $pid );
	$def_grid_atts = array(
					'layout'						=> '1',
					'cws_portfolio_data_to_show'	=> array(),
					'chars_count'					=> '',
				);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );
	$info_align = !empty($info_align) ? $info_align : '';
	$out = "";
	if ( in_array( 'excerpt', $cws_portfolio_data_to_show ) ){
		$chars_count = !empty($chars_count) ? $chars_count : cws_vc_shortcode_get_cws_portfolio_chars_count( $layout );
		$out = !empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
		$out = trim( preg_replace( "/[\s]{2,}/", " ", strip_shortcodes( strip_tags( $out ) ) ) );
		$out = wptexturize( $out );
		$out = substr( $out, 0, $chars_count );
		echo !empty( $out ) ? "<div class='cws-portfolio-posts-grid-post-content post-content'>$out</div>" : "";
	}
}

function cws_vc_shortcode_cws_portfolio_posts_grid_post_terms (){
	$pid = get_the_id ();
	$def_grid_atts = array(
					'layout'						=> '1',
					'cws_portfolio_data_to_show'	=> array(),
					'display_style'					=> '',
				);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );	
	
	$display_style = !empty($display_style) ? $display_style : "";
	$info_align = !empty($info_align) ? $info_align : '';
	$display_style = esc_attr( $display_style );
	if ( in_array( 'cats', $cws_portfolio_data_to_show ) ){
		$p_category_terms = wp_get_post_terms( $pid, 'cws_portfolio_cat' );
		$p_cats = "";
		for ( $i=0; $i < count( $p_category_terms ); $i++ ){
			$p_category_term = $p_category_terms[$i];
			$p_cat_permalink = get_term_link( $p_category_term->term_id, 'cws_portfolio_cat' );
			$p_cat_name = $p_category_term->name;
			$p_cats .= "<a href='$p_cat_permalink'>$p_cat_name</a>";
			$p_cats .= $i < count( $p_category_terms ) - 1 ? esc_html__( ",&#x20;", 'cws-essentials' ) : "";
		}	
		echo !empty($p_cats) ? "<div class='cws-portfolio-post-title post-terms'>&#x20;{$p_cats}</div>" : "";
	}	
}

function cws_vc_shortcode_cws_portfolio_single_post_post_media ($pid_ajax = null , $ajax_width = ''){
	$post_url = esc_url(get_the_permalink($pid_ajax));
	$post_meta = get_post_meta( ($pid_ajax ? $pid_ajax : get_the_ID()), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$thumbnail_props = has_post_thumbnail( $pid_ajax ) ? wp_get_attachment_image_src(get_post_thumbnail_id( $pid_ajax ),'full') : array();
	$thumbnail = !empty( $thumbnail_props ) ? $thumbnail_props[0] : '';
	$real_thumbnail_dims = array();
	if ( !empty( $thumbnail_props ) && isset( $thumbnail_props[1] ) ) $real_thumbnail_dims['width'] = $thumbnail_props[1];
	if ( !empty(  $thumbnail_props ) && isset( $thumbnail_props[2] ) ) $real_thumbnail_dims['height'] = $thumbnail_props[2];
	$thumbnail_dims = cws_vc_shortcode_get_cws_portfolio_thumbnail_dims( false, $real_thumbnail_dims );
	$crop_thumb = isset( $thumbnail_dims['width'] ) && $thumbnail_dims['width'] > 0;

	if (!empty($ajax_width)) {
		$thumbnail_dims['width'] = $ajax_width;
		$thumbnail_dims['height'] = 0;
	}
	if (!empty($thumbnail)) {
		$thumb_obj = cws_get_img( get_post_thumbnail_id( $pid_ajax ), $thumbnail_dims, $thumbnail );
	}
	$get_alt = get_post_meta(get_post_thumbnail_id( ), '_wp_attachment_image_alt', true); 
	$img_alt = " alt='" . (!empty($get_alt) ? $get_alt : get_the_title(get_post_thumbnail_id( ))) . "'";
	$thumb_url = isset( $thumb_obj[0] ) ? esc_url($thumb_obj[0]) : "";
	$p_type = isset( $post_meta['p_type'] ) ? $post_meta['p_type'] : '';	
	$full_width = isset( $post_meta['full_width'] ) ? $post_meta['full_width'] : false;	
	$retina_thumb = isset( $thumb_obj[3] ) ? $thumb_obj[3] : false;

	$enable_hover = isset( $post_meta['enable_hover'] ) ? $post_meta['enable_hover'] : false;
	$custom_url = isset( $post_meta['link_options_url'] ) ? $post_meta['link_options_url'] : "";
	$fancybox = isset( $post_meta['link_options_fancybox'] ) ? $post_meta['link_options_fancybox'] : false;
	$video_t = isset( $post_meta['video_type']['video_t'] ) ? $post_meta['video_type']['video_t'] : '';
	$video = isset( $post_meta['video_type'][$video_t . '_t']['url'] ) ? $post_meta['video_type'][$video_t . '_t']['url'] : '';
	$video_img = isset( $post_meta['video_type']['img'] ) ? $post_meta['video_type']['img'] : '';
	$popup = isset( $post_meta['video_type']['popup'] ) ? $post_meta['video_type']['popup'] : '';
	if ( $fancybox ){
		wp_enqueue_script( 'fancybox' );
	}
	$popup = (bool)$popup;
	$link_atts = "";
	$link_url = $custom_url ? $custom_url : $thumbnail;
	$link_icon = $fancybox ? ( $custom_url ? 'magic' : 'plus' ) : 'share';
	$link_class = $fancybox ? "fancy fas fa-{$link_icon}" : "fas fa-{$link_icon}";
	$link_atts .= !empty( $link_class ) ? " class='$link_class'" : "";
	$link_atts .= !empty( $link_url ) ? " href='$link_url'" : "";
	$link_atts .= !$fancybox ? " target='_blank'" : "";
	$link_atts .= $fancybox && $custom_url ? " data-fancybox-type='iframe'" : "";
		echo "<div class='post-media post-post-media post-posts-grid-post-media'>";
				switch ($p_type) {
					case 'image':
						if ( !empty( $thumb_url ) ){
							echo "<div class='pic" . ( !$enable_hover || ( !$crop_thumb && !$custom_url ) ? " wth_hover" : "" ) . "'>";
							if ( $retina_thumb ) {
								echo "<img src='".esc_url($thumb_url)."' data-at2x='".esc_url($retina_thumb)."' ".$img_alt." />";
							}
							else{
								echo "<img src='".esc_url($thumb_url)."' data-no-retina ".$img_alt." />";
							}
							echo "</div>";
						}
						break;
					case 'slider':
						$slider_type = isset( $post_meta['slider_type'] ) ? $post_meta['slider_type'] : '';
						if ( !empty( $slider_type) ) {
							$match = preg_match_all("/\d+/",$slider_type['slider_gall'],$images);
							if ($match){
								$images = $images[0];
								$image_srcs = array();
								foreach ( $images as $image ) {
									$image_temp = array();
									$image_src = wp_get_attachment_image_src($image,'full');
									if ($image_src){
										$image_temp = array('url' => $image_src[0], 'id' => $image);
										array_push( $image_srcs, $image_temp );										
									}
								}
								$thumb_media = $some_media = count( $image_srcs ) > 0 ? true : false;
								$carousel = count($image_srcs) > 1 ? true : false;
								$gallery_id = uniqid( 'cws-gallery-' );

								if( $carousel ){
									wp_enqueue_script( 'slick-carousel' );

									echo "<div class='cws-carousel-wrapper' data-columns='1' data-draggable='on' data-pagination='on'>";
										echo "<div class='cws-carousel'>";

										foreach ( $image_srcs as $image_src ) {
											$img_obj = cws_get_img( $image_src['id'], $thumbnail_dims , false );
											$img_url = isset( $img_obj[0] ) ? esc_url( $img_obj[0] ) : "";
											$retina_thumb = isset( $thumb_obj[3] ) ? $thumb_obj[3] : false;

											if ( !empty($img_url) ) {
												echo "<div class='pic'>";
													if ( $retina_thumb ) {
														echo "<img src='".esc_url($img_url)."' data-at2x='".esc_url($retina_thumb)."' ".$img_alt." />";
													}
													else{
														echo "<img src='".esc_url($img_url)."' data-no-retina ".$img_alt." />";
													}
												echo "</div>";
											}
										}

										echo "</div>";
									echo "</div>";
								}
							}
						}
						break;
					case 'rev_slider':
						get_template_part( 'slider' );
						break;
					case 'gallery':
						service_filter_gallery();
						remove_all_filters('post_gallery', 10);
						echo do_shortcode($post_meta['gall_type']['gall']);
						remove_filter('post_gallery', 'single_gallery', 11, 3);
						break;
					case 'video':
						global $wp_filesystem;
						preg_match('@[^/]*$@', $video, $video_link);
						$clear_url = array("?", "&amp", "watchv=");
						$video_id = str_replace($clear_url, '', preg_replace('/[^?][a-z]*=\w+/', '', $video_link[0]));
						$video_url = str_replace('watch?v=', '', $video_link[0]);
						if ($video_t == 'youtube') {
							$thumbnail_img = "http://img.youtube.com/vi/".esc_attr($video_id)."/maxresdefault.jpg";
							$link = "http://www.youtube.com/embed/";
						} else if ($video_t == 'vimeo') {
							$json = json_decode($wp_filesystem->get_contents("https://vimeo.com/api/oembed.json?url=".$video));
							$vimeo_id = !empty($json->video_id) ? $json->video_id : '';
							$thumbnail_img = !empty($json->thumbnail_url) ? $json->thumbnail_url : '';
							$link = "https://player.vimeo.com/video/";
							$video_url = ($video_id != $vimeo_id ? str_replace($video_id, $vimeo_id, $video_url) : $video_url);
						}
						$embed_link = $link.esc_attr($video_url) . ($video_id != $video_url ? '&amp;' : '?') . "autoplay=1";
						$img_url = !empty($thumb_url) ? $thumb_url : $thumbnail_img;
						echo "<div class='video'>";
						if ($popup) {
							echo "<div class='cover_img'>";
								if ( $retina_thumb ) {
									echo "<img src='".esc_url($thumb_url)."' data-at2x='".esc_url($retina_thumb)."' ".$img_alt." />";
								}
								else{
									echo "<img src='".esc_url($thumb_url)."' data-no-retina ".$img_alt." />";
								}
								echo "<div class='cws-portfolio-content-wrap'>";
									echo "<div class='hover-effect'></div>";
									echo "<a href='$embed_link' class='links video far fa-play-circle fancy fancybox.iframe'></a>";
									echo "<a class='links dsdsd area fancy fancybox.iframe' href='$embed_link'></a>";
								echo "</div>";
							echo "</div>";
						} else {
							if(!empty($pid_ajax)){
								global $wp_embed; 
								$wp_embed->post_ID = $pid_ajax; 
								echo $wp_embed->run_shortcode( apply_filters('the_content',"[embed width='" . $thumbnail_dims['width'] . "']" .($video_t == 'youtube' ? 'https://youtu.be/'.$video_id : $video ) . "[/embed]") );
							}else{
								echo apply_filters('the_content',"[embed width='" . $thumbnail_dims['width'] . "']" .($video_t == 'youtube' ? 'https://youtu.be/'.$video_id : $video ) . "[/embed]");
							}
						}
						echo "</div>";
						break;
				}
		echo "</div>";
	$GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] = !empty( $thumb_url ) && !$crop_thumb;
}

function cws_vc_shortcode_cws_portfolio_single_post_title ($pid = null){
	$title = get_the_title($pid);
	echo !empty( $title ) ?	"<h3 class='cws-portfolio-post-title post-title'>" . $title . "</h3>" : "";
}

function cws_vc_shortcode_cws_portfolio_single_post_terms ($pid = null){
	$pid = !empty($pid) ? $pid : get_the_id ();
	$out = "";
	$p_category_terms = wp_get_post_terms( $pid, 'cws_portfolio_cat' );
	$p_cats = "";
	for ( $i=0; $i < count( $p_category_terms ); $i++ ){
		$p_category_term = $p_category_terms[$i];
		$p_cat_permalink = get_term_link( $p_category_term->term_id, 'cws_portfolio_cat' );
		$p_cat_name = $p_category_term->name;
		$p_cat_name = esc_html( $p_cat_name );
		$p_cats .= "<a href='$p_cat_permalink'>$p_cat_name</a>";
		$p_cats .= $i < count( $p_category_terms ) - 1 ? esc_html__( ",&#x20;", 'cws-essentials' ) : "";
	}
	if ( !empty( $p_cats ) ){
		echo "<div class='cws-portfolio-post-title post-terms'>";
			echo "{$p_cats}";
		echo "</div>";
	}
}

function cws_vc_shortcode_cws_portfolio_single_post_content ($pid = null){
	if(class_exists('WPBMap')){
		WPBMap::addAllMappedShortcodes();
	}

	$content =  apply_filters('the_content', get_post_field('post_content', $pid));
	if ( !empty( $content ) ){
		echo "<div class='post-content post_post_content post-single-post-content'>";
			echo $content;
		echo "</div>";
	}
}

function cws_portfolio_masonry($featured_img_url = '', $dims_from_columns, $p_meta = '', $custom_layout_arr = false, $full_width = false){	
	$isotope_col_count = (isset($p_meta['isotope_col_count'])) ? intval($p_meta['isotope_col_count']) : 1;
	$isotope_line_count = (isset($p_meta['isotope_line_count'])) ? intval($p_meta['isotope_line_count']) : 1;

	$img_width = $dims_from_columns['width'];
	$img_height = $dims_from_columns['height'];

	$columns = intval($custom_layout_arr['columns']);
	if (!empty( $featured_img_url ) && $custom_layout_arr['crop_images'] == '1'){
		//Make square dimensions
		if ($img_width <= $img_height){
			$dims['width'] = $img_width;
			$dims['height'] = $img_width;
		} elseif ($img_height <= $img_width) {
			$dims['width'] = $img_height;
			$dims['height'] = $img_height;
		}
		$dims['crop'] = array(
			cws_vc_shortcode_get_option( "crop_x" ),
			cws_vc_shortcode_get_option( "crop_y" )
		);	
	} else {
		$dims['width'] = 0;
		$dims['height'] = 0;
	}

	if (!empty( $featured_img_url ) && $custom_layout_arr['crop_images'] == false && $custom_layout_arr['masonry'] == '1') {
		$img_width = $dims_from_columns['width'];
		$img_height = $dims_from_columns['height'];	
		$pd = 30;
		$col_paddings = ($pd * $isotope_col_count);
		$line_paddings = ($pd * $isotope_line_count);

		switch ($isotope_col_count) {
				case '1':
					$dims['width'] = $img_width * $isotope_col_count;
					break;
				case '2':
					if ( $columns < $isotope_col_count ) {
						$isotope_col_count = $columns;
					}
					$dims['width'] = ($img_width * $isotope_col_count) + $pd;
					break;
				case '3':
					$case = $isotope_col_count - 1;
					if ( $columns < $isotope_col_count ) {
						$isotope_col_count = $columns;
						$case = $isotope_col_count - 1;
					}
					$dims['width'] = ($img_width * $isotope_col_count) + $pd * $case;
					break;
				case '4':
					$case = $isotope_col_count - 1;
					if ( $columns < $isotope_col_count ) {
						$isotope_col_count = $columns;
						$case = $isotope_col_count - 1;
					}
					$dims['width'] = ($img_width * $isotope_col_count) + $pd * $case;
					break;
		}
		if($full_width){
				switch ($custom_layout_arr['columns']) {
					case '1':
						$img_width = 1053;
						break;
					case '2':
						$img_width = 518;
						break;
					case '3':
						$img_width = 340;
						break;
					case '4':
						$img_width = 250;
						break;
					case '5':
						$img_width = 197;
						break;
					
					default:
						break;
				}
		} else {
				switch ($custom_layout_arr['columns']) {
					case '1':
						if ( empty( $custom_layout_arr['sb_layout'] ) ){
							$img_width = 658;	
						}
						else if ( $custom_layout_arr['sb_layout'] === "double" ){
							$img_width = 320;
						}
						break;
					case '2':
						if ( empty( $custom_layout_arr['sb_layout'] ) ){
							$img_width = 320;	
						}
						else if ( $custom_layout_arr['sb_layout'] === "single" ){
							$img_width = 268;
						}
						else if ( $custom_layout_arr['sb_layout'] === "double" ){
							$img_width = 152;
						}
						break;
					case '3':
						if ( empty( $custom_layout_arr['sb_layout'] ) ){
							$img_width = 208;
						}
						else if ( $custom_layout_arr['sb_layout'] === "single" ){
							$img_width = 152;
						}
						else if ( $custom_layout_arr['sb_layout'] === "double" ){
							$img_width = 96;
						}
						break;
					case '4':
						if ( empty( $custom_layout_arr['sb_layout'] ) ){
							$img_width = 150;
						}
						else if ( $custom_layout_arr['sb_layout'] === "single" ){
							$img_width = 110;
						}
						else if ( $custom_layout_arr['sb_layout'] === "double" ){
							$img_width = 68;
						}
						break;
					case '5':
						$img_width = 118;
						if ( empty( $custom_layout_arr['sb_layout'] ) ){
							$img_width = 150;
						}
						else if ( $custom_layout_arr['sb_layout'] === "single" ){
							$img_width = 84;
						}
						else if ( $custom_layout_arr['sb_layout'] === "double" ){
							$img_width = 51;
						}
						break;
				}		
		}
		switch ($isotope_line_count) {
				case '1':
					$dims['height'] = $img_width * $isotope_line_count;
					break;
				case '2':
					if ( $columns < $isotope_line_count ) {
						$isotope_line_count = $columns;
					}
					$dims['height'] = ($img_width * $isotope_line_count) + $pd;
					break;
				case '3':
					$case = $isotope_line_count - 1;
					if ( $columns < $isotope_line_count ) {
						$isotope_line_count = $columns;
						$case = $isotope_line_count - 1;
					}
					$dims['height'] = ($img_width * $isotope_line_count) + $pd * $case;
					break;
				case '4':
					$case = $isotope_line_count - 1;
					if ( $columns < $isotope_line_count ) {
						$isotope_line_count = $columns;
						$case = $isotope_line_count - 1;
					}
					$dims['height'] = ($img_width * $isotope_line_count) + $pd * $case;
					break;
		}

		$dims['crop'] = array(
			cws_vc_shortcode_get_option( "crop_x" ),
			cws_vc_shortcode_get_option( "crop_y" )
		);
	}

	if (!empty( $featured_img_url ) && $custom_layout_arr['crop_images'] == '1' && $custom_layout_arr['masonry'] == '1') {
		$img_width = $dims_from_columns['width'];
		$img_height = $dims_from_columns['height'];	
		$pd = 30;
		$col_paddings = ($pd * $isotope_col_count);
		$line_paddings = ($pd * $isotope_line_count);
		
		switch ($isotope_col_count) {
				case '1':
					$dims['width'] = $img_width * $isotope_col_count;
					break;
				case '2':
					if ( $columns < $isotope_col_count ) {
						$isotope_col_count = $columns;
					}
					$dims['width'] = ($img_width * $isotope_col_count) + $pd;
					break;
				case '3':
					$case = $isotope_col_count - 1;
					if ( $columns < $isotope_col_count ) {
						$isotope_col_count = $columns;
						$case = $isotope_col_count - 1;
					}
					$dims['width'] = ($img_width * $isotope_col_count) + $pd * $case;
					break;
				case '4':
					$case = $isotope_col_count - 1;
					if ( $columns < $isotope_col_count ) {
						$isotope_col_count = $columns;
						$case = $isotope_col_count - 1;
					}
					$dims['width'] = ($img_width * $isotope_col_count) + $pd * $case;
					break;
		}
		switch ($isotope_line_count) {
				case '1':
					$dims['height'] = $img_width * $isotope_line_count;
					break;
				case '2':
					if ( $columns < $isotope_line_count ) {
						$isotope_line_count = $columns;
					}
					$dims['height'] = ($img_width * $isotope_line_count) + $pd;
					break;
				case '3':
					$case = $isotope_line_count - 1;
					if ( $columns < $isotope_line_count ) {
						$isotope_line_count = $columns;
						$case = $isotope_line_count - 1;
					}
					$dims['height'] = ($img_width * $isotope_line_count) + $pd * $case;
					break;
				case '4':
					$case = $isotope_line_count - 1;
					if ( $columns < $isotope_line_count ) {
						$isotope_line_count = $columns;
						$case = $isotope_line_count - 1;
					}
					$dims['height'] = ($img_width * $isotope_line_count) + $pd * $case;
					break;
		}

		$dims['crop'] = array(
			cws_vc_shortcode_get_option( "crop_x" ),
			cws_vc_shortcode_get_option( "crop_y" )
		);
	}
	return array(
		'thumbnail_dims' => $dims,
		'isotope_col_count' => $isotope_col_count,
		'isotope_line_count' => $isotope_line_count
	);
}

function service_filter_gallery(){
	return add_filter( 'post_gallery', 'single_gallery', 11, 3 );
}

function single_gallery( $output, $atts, $instance) {
	$pid = get_the_id();
	$p_meta = get_post_meta( $pid, 'cws_mb_post' );
	$p_meta = isset( $p_meta[0] ) ? $p_meta[0] : array();
	$is_full_width = $p_meta['full_width'];
	$resolution = $is_full_width ? 1920 : 1170;

	$atts = array_merge(array('columns' => 3), $atts);
	$columns = $atts['columns'];
	$columns = preg_replace( '/[^0-9,]+/', '', $columns );
	$columns = intval($columns);

	$crop_width = $resolution / $columns;
	$img_data = array('width' => $crop_width );

	$itemwidth = $columns > 0 ?  round((100 / $columns) , 2) : 100;
	$images = explode(',', $atts['ids']);
	$selector = "gallery-{$instance}";
	$gallery_id = uniqid( 'cws-portfolio-gallery-' );
	$gallery_style = "<style>
	    #{$selector} {
			margin: auto;
		}
	    #{$selector} .gallery-item {
		margin-top: 10px;
		text-align: center;
		width: {$itemwidth}%;
		}
		#{$selector} .gallery-caption {
		margin-left: 0;
		}
	</style>";

	$i = 0;
	$return = '';
	
	$return .= "<div id='$selector' class='single-gallery $selector'>";
	$return .= apply_filters( 'gallery_style', $gallery_style );
	foreach ($images as $key => $value) {
		if($key == 0){
			$value = str_replace("&quot;", "", $value); 
		}
		if (!empty($value)){
		$image_attributes = wp_get_attachment_image_src($value, 'full');
		$real_thumbnail_dims = array();
		if ( !empty( $image_attributes ) && isset( $image_attributes[1] ) ) $real_thumbnail_dims['width'] = $image_attributes[1];
		if ( !empty(  $image_attributes ) && isset( $image_attributes[2] ) ) $real_thumbnail_dims['height'] = $image_attributes[2];
			// $thumb_obj = cws_get_img($value, $real_thumbnail_dims, false);
			$thumb_obj = cws_get_img($value, $img_data, false);

			if ($thumb_obj){
				$thumb_path_hdpi = isset( $thumb_obj[3] ) ? " src='". esc_url($thumb_obj[0]) ."' data-at2x='" . esc_attr($thumb_obj[3]) ."'" : " src='". esc_url($thumb_obj[0]) . "' data-no-retina";
		$src = $thumb_path_hdpi;
		$return .= '
			<div class="gallery-item col-' . esc_attr($columns) . '">
				<a class="fancy" data-gallery="gallery" data-fancybox-group="'.esc_attr($gallery_id).'" href="'.esc_url($image_attributes[0]).'">
					<img '.$src.' alt="gallery">
				</a>
			</div>
		';
		$i++;
	}
		}
	}
	$return .= '</div>';
	return $return;
}

?>