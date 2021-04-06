<?php
function cws_vc_shortcode_cws_staff_posts_grid ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );
	
	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'view_layout'			=> 'grid',
        'carousel_infinite'		=> false,
        'carousel_autoplay'	    => false,
        'autoplay_speed'		=> '3000',
        'pause_on_hover'		=> false,
		'layout'				=> 'def',
		'pagination_type'		=> 'load_more',
		'tax'					=> '',
		'chars_count'			=> '90',
		'total_items_count'		=> esc_html( get_option('posts_per_page') ),
		'items_pp'				=> esc_html( get_option('posts_per_page') ),
		'thumbnail_size'        => 'full',
		'hide_data_override'	=> false,
		'disable_single'		=> false,
		'disable_hover'			=> false,
		'data_to_hide'			=> '',
		'el_class'				=> '',
		'paged'					=> '1',
		'addl_query_args'		=> array(),

		/* -----> STYLING TAB <----- */
		'custom_styles'			=> '',
		'customize_colors'      => false,
		'preview_bg'			=> $first_color,
		'preview_title_color'	=> $second_color,
		'preview_pos_color'		=> '#fff',
		'title_color'			=> '#000',
		'text_color'			=> '#000',
		'info_background'		=> '#fff',
		'links_color'			=> $first_color,
		'links_hover_color'		=> $second_color,
		'social_icon_color'			=> $first_color,
		'social_bg_color'			=> '',
		'social_icon_hover_color'	=> '#fff',
		'social_bg_hover_color'	    => $first_color,
		'button_color'			    => '#fff',
		'button_bg_color'			=> $first_color,
		'button_bd_color'			=> $first_color,
		'button_hover_color'	    => $second_color,
		'button_bg_hover_color'	    => '#fff',
		'button_bd_hover_color'	    => $first_color,

	);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $carousel_atts = $module_classes = $grid_classes = "";
	$ajax_data = array();
	$id = uniqid( 'cws-staff-' );
	$pid = get_the_id();

	/* -----> Variable processing <----- */
	$terms = isset( $atts[ $tax . "_terms" ] ) ? $atts[ $tax . "_terms" ] : "";
	$total_items_count = !empty( $total_items_count ) ? (int)$total_items_count : PHP_INT_MAX;
	$items_pp = !empty($items_pp) ? (int)$items_pp : esc_html( get_option('posts_per_page') );
	$paged = (int)$paged;

	$def_layout = function_exists('cws_vc_shortcode_get_option') ? cws_vc_shortcode_get_option( 'def_cws_staff_layout' ) : "";
	$def_layout = isset($def_layout) ? $def_layout : "1";

	$layout = ( empty( $layout ) || $layout === "def" ) ? $def_layout : $layout;	

	/* -----> Hide Meta-Data <----- */
	$hide_data_override = (bool)$hide_data_override;
	$data_to_hide = explode( ",", $data_to_hide );

	$def_data_to_hide = function_exists('cws_vc_shortcode_get_option') ? cws_vc_shortcode_get_option( 'def_cws_staff_data_to_hide' ) : "";
	$def_data_to_hide  = isset( $def_data_to_hide ) ? $def_data_to_hide : array();

	$data_to_hide = $hide_data_override ? $data_to_hide : $def_data_to_hide;

	/* -----> Side-bars init <----- */
	$sb = function_exists('cws_vc_shortcode_get_sidebars') ? cws_vc_shortcode_get_sidebars($pid) : "";
	$sb_layout = isset( $sb['layout_class'] ) ? $sb['layout_class'] : '';

	/* -----> Get Staff Taxonomy Properties <-----*/
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
	$terms = !empty( $terms ) ? $terms : $all_terms;
	$not_in = (1 == $paged) ? array() : get_option( 'sticky_posts' );

	$query_args = array(
		'post_type'			=> 'cws_staff',
		'post_status'		=> 'publish',
		'post__not_in'		=> $not_in
	);

	if ( $view_layout != 'carousel' ){
		$query_args['posts_per_page']	= $items_pp;
		$query_args['paged']			= $paged;
	} else {
		$query_args['nopaging']			= true;
		$query_args['posts_per_page']	= -1;
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

	$post_meta = get_post_meta( $pid, 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();

	$query_args['orderby'] 	= "menu_order date title";
	$query_args['order']	= "ASC";

	$query_args = array_merge( $query_args, $addl_query_args );
	$q = new WP_Query( $query_args );

	$found_posts = $q->found_posts;

	$requested_posts = $found_posts > $total_items_count ? $total_items_count : $found_posts;
	$max_paged = $found_posts > $total_items_count ? ceil( $total_items_count / $items_pp ) : ceil( $found_posts / $items_pp );

	/* -----> Pagination & Scripts init <-----*/
	$is_carousel = $view_layout == 'carousel';

	$use_pagination = $view_layout != 'carousel' && $max_paged > 1;
	$dynamic_content = $use_pagination;

	wp_enqueue_script( 'fancybox' );
	if( $is_carousel ){
		wp_enqueue_script( 'slick-carousel' );

		$carousel_atts .= ' data-columns="'.$layout.'"';
		$carousel_atts .= ' data-mobile-landscape="2"';
		$carousel_atts .= ' data-auto-height="on"';
		$carousel_atts .= ' data-draggable="on"';
		$carousel_atts .= ' data-pagination="on"';

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
	} else if( $dynamic_content ){
		wp_enqueue_script( 'isotope' );
	}

	if ( $dynamic_content ){
		wp_enqueue_script( 'imagesloaded' ); // for dynamically loaded gallery posts
	}

	/* -----> Customize default styles <----- */
	if( !empty($custom_styles) ){
		preg_match("/(?<=\{).+?(?=\})/", $custom_styles, $vc_custom_styles); 
		$vc_custom_styles = implode($vc_custom_styles);

		$styles .= "
			#".$id."{
				".esc_attr($vc_custom_styles).";
			}
		";
	}
	if ( $customize_colors ) {
        if (!empty($preview_bg)) {
            $styles .= "
                #" . $id . " .staff-item-inner:before{
                    background-color: " . esc_attr($preview_bg) . ";
                }
            ";
        }
        if (!empty($preview_title_color)) {
            $styles .= "
                #" . $id . " .staff-item-inner .advanced_info .item-title a{
                    color: " . esc_attr($preview_title_color) . ";
                }
            ";
        }
        if (!empty($preview_pos_color)) {
            $styles .= "
                #" . $id . " .staff-item-inner .advanced_info .position-list a{
                    color: " . esc_attr($preview_pos_color) . ";
                }
            ";
        }
        if (!empty($title_color)) {
            $styles .= "
                #" . $id . " .cws-staff-post-info .item-title a{
                    color: " . esc_attr($title_color) . ";
                }
            ";
        }
        if (!empty($text_color)) {
            $styles .= "
                #" . $id . " .cws-staff-post-info{
                    color: " . esc_attr($text_color) . ";
                }
            ";
        }
        if (!empty($info_background)) {
            $styles .= "
                #" . $id . ".style_advanced .cws-vc-shortcode-grid .staff-item-wrapper .staff-item-inner .cws-staff-post-info{
                    background-color: " . esc_attr($info_background) . ";
                }
            ";
        }
        if (!empty($links_color)) {
            $styles .= "
                #" . $id . " .cws-staff-post-info a:not(.cws-custom-button){
                    color: " . esc_attr($links_color) . ";
                }
            ";
        }
        if ( !empty($social_icon_color) ) {
            $styles .= "
                #" . $id . " .cws-social-links a:before{
                    color: " . esc_attr($social_icon_color) . ";
                }
            ";
        }
        $styles .= "
            #" . $id . " .cws-social-links a:after{
                color: " . ( !empty($social_bg_color) ? esc_attr($social_bg_color) : 'transparent' ) . ";
            }
        ";
        if (!empty($button_color) || !empty($button_bg_color) || !empty($button_bd_color)) {
            $styles .= "
                #" . $id . " .cws-staff-post-info .cws-custom-button{
                    ".(!empty($button_bg_color) ? "background-color: " . esc_attr($button_bg_color) . ";" : "") . "
                    ".(!empty($button_bd_color) ? "border-color: " . esc_attr($button_bd_color) . ";" : "") . "
                    ".(!empty($button_color) ? "color: " . esc_attr($button_color) . ";" : "") . "
                }
            ";
        }
    }

	if( $customize_colors && (
		!empty($preview_bg) || 
		!empty($links_hover_color) || 
		!empty($social_icon_hover_color) ||
		!empty($social_bg_hover_color) ||
		!empty($button_hover_color) ||
		!empty($button_bg_hover_color) ||
		!empty($button_bd_hover_color)
    ) ) {
		$styles .= "
			@media 
				screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
				screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
				screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
				screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
				screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
			{
		";
			if( !empty($preview_bg) ){
				$styles .= "
					#".$id.".style_advanced .staff-item-inner:hover .cws-staff-post-info{
						-webkit-box-shadow: 0 15px 60px 0 ".esc_attr($preview_bg).";
					   	   -moz-box-shadow: 0 15px 60px 0 ".esc_attr($preview_bg).";
								box-shadow: 0 15px 60px 0 ".esc_attr($preview_bg).";
					}
				";
			}
			if( !empty($links_hover_color) ){
				$styles .= "
					#".$id." .cws-staff-post-info a:hover{
						color: ".esc_attr($links_hover_color).";
					}
				";
			}
            if( !empty($social_icon_hover_color) ){
                $styles .= "
                    #".$id." .cws-social-links a:hover:before{
                        color: ".esc_attr($social_icon_hover_color).";
                    }
                ";
            }
            if( !empty($social_bg_hover_color) ){
                $styles .= "
                    #".$id." .cws-social-links a:hover:after{
                        color: ".esc_attr($social_bg_hover_color).";
                    }
                ";
            }
			if( !empty($button_hover_color) || !empty($button_bg_hover_color) || !empty($button_bd_hover_color) ){
				$styles .= "
					#".$id." .cws-staff-post-info .cws-custom-button:hover{
						".(!empty($button_bg_hover_color) ? "background-color: " . esc_attr($button_bg_hover_color) . ";" : "") . "
						".(!empty($button_bd_hover_color) ? "border-color: " . esc_attr($button_bd_hover_color) . ";" : "") . "
						".(!empty($button_hover_color) ? "color: " . esc_attr($button_hover_color) . ";" : "") . "
					}
				";
			}

		$styles .= "
			}
		";
	}
	/* -----> End of default styles <----- */
	$styles = json_encode($styles);


	$module_classes .= " view-".$view_layout;
	if( $disable_hover ){
		$module_classes .= " disable-hover";
	}
	if( !empty($el_class) ){
		$module_classes .= " ".esc_attr($el_class);
	}
	if( $view_layout == 'grid' ){
		$module_classes .= " columns-".$layout;
		$grid_classes .= ' isotope';
	} else if( $view_layout == 'carousel' ){
		$grid_classes .= ' cws-carousel';
	}

	ob_start();
	/* -----> Staff module output <----- */
	echo "<div id='$id' class='staff-module-wrapper posts-grid render_styles". $module_classes ."' data-style='"
        .esc_attr($styles)."'>";

		echo "<div class='cws-vc-shortcode-wrapper".( $view_layout == 'carousel' ? ' cws-carousel-wrapper' : '' )."'"
            .( $view_layout == 'carousel' ? $carousel_atts : '' ).">";
			echo "<div class='cws-vc-shortcode-grid".$grid_classes."'>";

				$GLOBALS['cws_vc_shortcode_posts_grid_atts'] = array(
					'post_type'						=> 'cws_staff',
					'chars_count'					=> $chars_count,
					'layout'						=> $layout,
					'sb_layout'						=> $sb_layout,
					'cws_staff_data_to_hide'		=> $data_to_hide,
					'total_items_count'				=> $total_items_count,
					'proc_atts'						=> $proc_atts,
				);

				if ( function_exists( "cws_vc_shortcode_cws_staff_posts_grid_posts" ) ){
					call_user_func_array( "cws_vc_shortcode_cws_staff_posts_grid_posts", array( $q ) );
				}

				unset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] );


			echo "</div>";
			if ( $dynamic_content ){
				cws_loader_html();
			}
		echo "</div>";

		if ( $use_pagination ){
			if ( $pagination_type == 'load_more' ){
				cws_load_more ();
			} else {
				cws_pagination ( $paged, $max_paged );
			}
		}

		/* -----> Ajax Posts Load <-----*/
		if ( $dynamic_content ){
			$ajax_data['id']								= $id;
			$ajax_data['post_type']							= 'cws_staff';
			$ajax_data['cws_staff_data_to_hide']			= $data_to_hide;
			$ajax_data['layout']							= $layout;
			$ajax_data['sb_layout']							= $sb_layout;
			$ajax_data['total_items_count']					= $total_items_count;
			$ajax_data['items_pp']							= $items_pp;
			$ajax_data['page']								= $paged;
			$ajax_data['max_paged']							= $max_paged;
			$ajax_data['tax']								= $tax;
			$ajax_data['terms']								= $terms;
			$ajax_data['chars_count']						= $chars_count;
			$ajax_data['current_filter_val']				= '_all_';
			$ajax_data['addl_query_args']					= $addl_query_args;
			$ajax_data['proc_atts']							= $proc_atts;

			$ajax_data_str = json_encode( $ajax_data );


			echo "<form id='{$id}-data' class='ajax-data-form cws-staff-ajax-data-form posts-grid-ajax-data-form'>";
				echo "<input type='hidden' id='{$id}-ajax-data' class='ajax-data cws-staff-ajax-data posts-grid-ajax-data' name='{$id}-ajax-data' value='$ajax_data_str' />";
			echo "</form>";
		}

	echo "</div>";
	$out = ob_get_clean();

	return $out;
}

function cws_vc_shortcode_cws_staff_posts_grid_posts ( $q = null ){
	if ( !isset( $q ) ) return;

	$def_grid_atts = array(
		'layout'						=> '2',
		'cws_staff_data_to_hide'		=> array(),
		'total_items_count'				=> PHP_INT_MAX
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$paged = $q->query_vars['paged'];
	if ( $paged == 0 && $total_items_count < $q->post_count ){
		$post_count = $total_items_count;
	} else {
		$ppp = $q->query_vars['posts_per_page'];
		$posts_left = $total_items_count - ( $paged - 1 ) * $ppp;
		$post_count = $posts_left < $ppp ? $posts_left : $q->post_count;
	}

	if ( $q->have_posts() ):
		ob_start();
		while( $q->have_posts() && $q->current_post < $post_count - 1 ):
			$q->the_post();
			cws_vc_shortcode_cws_staff_posts_grid_post ();
		endwhile;
		wp_reset_postdata();
		ob_end_flush();
	endif;		
}

function cws_vc_shortcode_cws_staff_posts_grid_post (){
	$def_grid_atts = array(
		'cws_staff_data_to_hide'	=> array(),
	);
	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	$cws_staff_data_to_hide = $cws_staff_data_to_hide ? $cws_staff_data_to_hide : array();

	/* -----> Variables declaration <----- */
	$pid = get_the_id();
	$item_id = uniqid( "cws_staff_post_" );


	/* -----> Get Post Meta <-----*/
	$post_meta = get_post_meta( $pid, 'cws_mb_post' );

	if( isset($post_meta[0]) ){
		$post_meta = $post_meta[0];

		if( isset($post_meta['experience']) ){
			$experience = esc_html($post_meta['experience']);
		}
		if( isset($post_meta['email']) ){
			$email = esc_html($post_meta['email']);
		}
		if( isset($post_meta['tel']) ){
			$tel = esc_html($post_meta['tel']);
		}
		if( isset($post_meta['biography']) ){
			$biography = esc_html($post_meta['biography']);
		}
	}

	if( !empty(get_the_post_thumbnail_url($pid)) ){
		$thumbnail = get_the_post_thumbnail_url($pid);
	} else {
		$thumbnail = '';
	}

	echo "<article id='$item_id' class='item staff-item-wrapper'>";
		echo "<div class='staff-item-inner'>";

			cws_vc_shortcode_cws_staff_posts_grid_post_media();

			echo "<div class='cws-staff-post-info'>";
				cws_vc_shortcode_cws_staff_posts_grid_post_title();

				if( !in_array('deps', $cws_staff_data_to_hide) ){
					$deps = cws_vc_shortcode_get_post_term_links_str( 'cws_staff_member_department', ', ' );
					if( !empty($deps) ){
						echo "<div class='department-list'>";
							echo $deps;
						echo "</div>";	
					}
				}
				if( !in_array('poss', $cws_staff_data_to_hide) ) {
					$poss = cws_vc_shortcode_get_post_term_links_str( 'cws_staff_member_position', ', ' );
					if ( !empty( $poss ) ){
						echo "<div class='position-list'>";
							echo $poss;
						echo "</div>";	
					}
				}
				if( !in_array('experience', $cws_staff_data_to_hide) && !empty($experience)){
					echo "<div class='experience'>";
						echo "<span class='label'>".esc_html__('Experience', 'cws-essentials').": </span>";
						echo "<span>".$experience."</span>";
					echo "</div>";
				}
				if( !in_array('email', $cws_staff_data_to_hide) && !empty($email)){
					echo "<div class='email'>";
						echo "<span class='label'>".esc_html__('Email', 'cws-essentials').": </span>";
						echo "<a href='mailto:".$email."'>".$email."</a>";
					echo "</div>";
				}
				if( !in_array('tel', $cws_staff_data_to_hide) && !empty($tel)){
					echo "<div class='tel'>";
						echo "<span class='label'>".esc_html__('Tel', 'cws-essentials').": </span>";
						echo "<a href='tel:".$tel."'>".$tel."</a>";
					echo "</div>";
				}
				if(!in_array('biography', $cws_staff_data_to_hide) && !empty($biography)){
					echo "<div class='biography'>";
							echo "<span class='label'>".esc_html__('Biography', 'cws-essentials').": </span>";
							echo "<p>".$biography."</p>";
						echo "</div>";
				}
				if( !in_array('excerpt', $cws_staff_data_to_hide) ){
					cws_vc_shortcode_cws_staff_posts_grid_post_content ();
				}
				if( !in_array('socials', $cws_staff_data_to_hide) ) {
					cws_vc_shortcode_cws_staff_posts_grid_social_links ();
				}
				if( !in_array('link_button', $cws_staff_data_to_hide) ){
					echo "<a href='".get_the_permalink($pid)."' class='cws-custom-button small'>View Profile</a>";
				}
			echo "</div>";

		echo "</div>";
	echo "</article>";
}

function cws_vc_shortcode_cws_staff_posts_grid_post_media (){
	$def_grid_atts = array(
		'proc_atts' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	/* -----> Variables declaration <----- */
	$pid = get_the_id();
	$permalink = get_the_permalink( $pid );
    $disable_single = isset($proc_atts['disable_single']) ? $proc_atts['disable_single'] : false;
    $disable_hover = isset($proc_atts['disable_hover']) ? $proc_atts['disable_hover'] : false;

	/* -----> Get Post Thumbnail <----- */
	$thumbnail_id = get_post_thumbnail_id($pid);

	$thumb_title = get_post($thumbnail_id)->post_title;
	$thumb_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
	$thumb_alt = !empty($thumb_alt) ? $thumb_alt : $thumb_title;

	$thumb_size = ( isset($grid_atts['proc_atts']['thumbnail_size']) ? $grid_atts['proc_atts']['thumbnail_size'] : 'full' );

	$img_src = wp_get_attachment_image_url( $thumbnail_id, $thumb_size );
	$img_srcset = wp_get_attachment_image_srcset( $thumbnail_id, $thumb_size );
	$img_sizes = wp_get_attachment_image_sizes($thumbnail_id, $thumb_size);

	if( isset($disable_single) && $disable_single == '1' ){
		$start_tag = "<div";
		$end_tag = "</div>";
	} else {
		$start_tag = "<a href='".esc_url($permalink)."'";
		$end_tag = "</a>";
	}

	if ( !empty( $img_src ) ){
		echo "<div class='post-media'>";
		    if (!$disable_hover) {
		        echo "<div class='post-media-hover'></div>";
            }
			echo $start_tag." class='link-author'>";
				echo "<img src='".esc_url($img_src)."' srcset='".esc_attr($img_srcset)."' sizes='".esc_attr($img_sizes)."' alt='".esc_attr($thumb_alt)."'>";
			echo $end_tag;
		echo "</div>";
	}	
}

function cws_vc_shortcode_cws_staff_posts_grid_post_title (){
	$def_grid_atts = array(
		'proc_atts' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;
	extract( $grid_atts );

	/* -----> Variables declaration <----- */
	$pid 		= get_the_id();
	$title 		= get_the_title();
	$permalink 	= get_the_permalink( $pid );

	if( isset($proc_atts['disable_single']) && $proc_atts['disable_single'] == '1' ){
		$start_tag = "<div";
		$end_tag = "</div>";
	} else {
		$start_tag = "<a href='".esc_url($permalink)."'";
		$end_tag = "</a>";
	}

	if ( !empty( $title ) ){
		echo "<h3 class='item-title'>";
			echo (!is_single() ? $start_tag.">" : "");
				echo $title;
			echo (!is_single() ? $end_tag : "");
		echo "</h3>";
	} 
}

function cws_vc_shortcode_cws_staff_posts_grid_post_content (){
	if( class_exists('WPBMap') ){
		WPBMap::addAllMappedShortcodes();
	}

	/* -----> Variables declaration <----- */
	$pid = get_the_id();
	$post = get_post( $pid );

	$def_grid_atts = array(
		'cws_staff_data_to_hide' => array(),
	);

	$grid_atts = isset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] ) ? $GLOBALS['cws_vc_shortcode_posts_grid_atts'] : $def_grid_atts;	
	extract( $grid_atts );
	$data_to_hide = $cws_staff_data_to_hide ? $cws_staff_data_to_hide : array();

	$out = "";
	if ( !in_array( 'excerpt', $data_to_hide ) ){
		$out = !empty( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;

		$out = wptexturize( $out );
		if( !empty($chars_count) ){
			$out = substr( $out, 0, (int)$chars_count );
			$out = trim( preg_replace( "/[\s]{2,}/", " ", strip_shortcodes( strip_tags($out) ) ) );
		}

		if( !empty($out) ){
			echo "<div class='item_content'>";
				echo $out;
			echo "</div>";
		}
	}	
}

function cws_vc_shortcode_cws_staff_posts_grid_social_links (){
	$pid = get_the_id();
	$post_meta = get_post_meta( $pid, 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$social_group = isset( $post_meta['social_group'] ) ? $post_meta['social_group']: array();
	$icons = "";

	foreach ( $social_group as $social ) {
		$title = isset( $social['title'] ) ? $social['title'] : "";
		$icon = isset( $social['icon'] ) ? $social['icon'] : "";
		$url = isset( $social['url'] ) ? $social['url'] : "";
		if ( !empty( $icon ) && !empty( $url ) ){
			$icons .= "<a href='$url' target='_blank'" . ( !empty($title) ? " title='$title'" : "" ) . " class='cws-social-link hexagon ".$icon."'></a>";
		}
	}
	if ( !empty( $icons ) ){
		echo "<div class='cws-social-links shape-hexagon'>";
			echo $icons;	
		echo "</div>";
	}
}

?>