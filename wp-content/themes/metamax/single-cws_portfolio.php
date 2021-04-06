<?php
	get_header();

	$p_id = get_queried_object_id();

	global $cws_theme_funcs;
	if ($cws_theme_funcs){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$fixed_header = $cws_theme_funcs->cws_get_meta_option( 'fixed_header' );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);
	}
	$page_classes = "";
	$page_classes .= !empty( $sb['layout_class'] ) ? ' '.$sb['layout_class']."_sidebar" : "";
	$page_classes = trim( $page_classes );
	
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	extract( wp_parse_args( $post_meta, array(
		'show_related' 		=> false,
		'rpo_title'			=> '',
		'rpo_cols'			=> '4',
		'carousel'			=> false,
		'full_width'		=> false,
		'show_featured'	=> '',
		'wide_featured'	=> '',
		'rpo_items_count'	=> get_option( 'posts_per_page' ),
	)));

	$full_width = isset( $post_meta['full_width'] ) ? $post_meta['full_width'] : false;
	$full_width = (bool)$full_width;
	$show_related = isset( $post_meta['show_related'] ) ? $post_meta['show_related'] : false;
	$rpo_title = isset( $post_meta['rpo_title'] ) ? esc_html( $post_meta['rpo_title'] ) : "";
	$rpo_items_count = isset( $post_meta['rpo_items_count'] ) ? esc_textarea( $post_meta['rpo_items_count'] ) : esc_textarea( get_option( "posts_per_page" ) );
	$rpo_cols = isset( $post_meta['rpo_cols'] ) ? esc_textarea( $post_meta['rpo_cols'] ) : 4;
	$title = get_the_title();
	$decr_pos = isset( $post_meta['decr_pos'] ) ? $post_meta['decr_pos'] : '';
	$p_type = isset( $post_meta['p_type'] ) ? $post_meta['p_type'] : '';
	$gall_type = isset( $post_meta['gall_type'] ) ? $post_meta['gall_type'] : '';
	$slider_type = isset( $post_meta['slider_type'] ) ? $post_meta['slider_type'] : '';
	$rev_slider_type = isset( $post_meta['rev_slider_type'] ) ? $post_meta['rev_slider_type'] : '';
	$video_type = isset( $post_meta['video_type'] ) ? $post_meta['video_type'] : '';
	$full_width = isset( $post_meta['full_width'] ) ? $post_meta['full_width'] : false;	
	$decr_pos = isset( $post_meta['decr_pos'] ) ? $post_meta['decr_pos'] : '';
	$cont_width = isset( $post_meta['cont_width'] ) ? $post_meta['cont_width'] : '';
	$categories = isset( $post_meta['rpo_categories'] ) ? $post_meta['rpo_categories'] : '';
	if(is_array($categories)){
		if(empty($categories[0])){
			unset($categories[0]);
		}
	}

	wp_enqueue_script( 'owl-carousel' );

	$sb['sb_class'] .= $full_width ? ' full-width' : '';
	$classes = !empty($p_type) ? $p_type : '';
	$classes .= !empty($decr_pos) ? ' ' . $decr_pos : '';
	$classes .= !empty($decr_pos) && $decr_pos !== 'bot' && !$full_width ? ' flex-col' : '';
	$classes .= $decr_pos == 'left' || $decr_pos == 'left_s' ? ' reverse' : '';
	$sticky = $decr_pos == 'left_s' || $decr_pos == 'right_s' ? 'sticky-cont' : '';
	switch ($cont_width) {
		case '25':
			$media_width = '75';
			break;
		case '33':
			$media_width = '66';
			break;
		case '50':
			$media_width = '50';
			break;
		case '66':
			$media_width = '33';
			break;
	}

?>

<div class="<?php echo (isset($sb['sb_class']) ? $sb['sb_class'] : 'page-content'); ?>">
	<?php

	if ($show_featured == '1' && $wide_featured == '1'){
		$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $p_id ), 'full' );
		$thumb_path_hdpi = isset($featured_img[3]) ? " src='". esc_url($featured_img[0]) ."' data-at2x='" . esc_url($featured_img[3]) ."'" : " src='". esc_url($featured_img[0]) . "' data-no-retina";

		$get_alt = get_post_meta($p_id, '_wp_attachment_image_alt', true); 
		$img_alt = " alt='" . (!empty($get_alt) ? $get_alt : get_the_title($p_id)) . "'";

		echo "<div class='wide_featured_img'><img $thumb_path_hdpi ".$img_alt." /></div>";
	}
	echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '';

	$cats = get_the_terms( $p_id, 'cws_portfolio_cat' );
	$cats = $cats ? $cats : array(); 
	$cat_slugs = array();
	foreach( $cats as $cat ){
		$cat_slugs[] = $cat->slug;
	}
	$has_cats = !empty( $cat_slugs );

	$related_posts = array();
	$has_related = false;

	if ( $has_cats ){
		$query_args = array(
			'post_type' => 'cws_portfolio',
		);
		$query_args['tax_query'] = array( array(
			'taxonomy' => 'cws_portfolio_cat',
			'field' => 'slug',
			'terms' => $cat_slugs
		));
		$query_args["post__not_in"] = array( $p_id );
		if ( $rpo_items_count ){
			$query_args['posts_per_page'] = $rpo_items_count;
		}
		$q = new WP_Query( $query_args );
		$related_posts = $q->posts;
		if ( count( $related_posts ) > 0 ){
			$has_related = true;
		}
	}

	$use_related_carousel = $carousel == 1 && $has_related;
	$show_related_items  = $show_related == 1 && $has_related;

	$section_class = "cws-portfolio single clearfix";
	$section_class .= $use_related_carousel ? " related" : "";

	?>
	<main>
		<?php
		ob_start();
			cws_vc_shortcode_cws_portfolio_single_post_post_media ();
		$media = ob_get_clean();
		if ( $full_width ) {
			echo sprintf("%s", $media);
		}
		?>
		<div class="grid-row">
			<section class="<?php echo esc_attr($section_class) ?>">
				<div class="cws-wrapper cws-portfolio-items">
					<?php

					$GLOBALS['cws_vc_shortcode_single_post_atts'] = array(
						'sb_layout'						=> $sb['layout_class'],
						);
						while ( have_posts() ) : the_post();
							$pid = get_the_id();
							echo "<article id='cws-portfolio-post-{$pid}' class='cws-portfolio-post post-single item clearfix " . $classes . "'>";
								ob_start();
								cws_vc_shortcode_cws_portfolio_single_post_post_media ();
								$media = ob_get_clean();
								ob_start();
								echo "<div class='cws-portfolio-single-content {$sticky}'>";
									cws_vc_shortcode_cws_portfolio_single_post_title ();
									/*cws_vc_shortcode_cws_portfolio_single_post_terms ();*/
									cws_vc_shortcode_cws_portfolio_single_post_content ();
								echo "</div>";
								$content_terms = ob_get_clean();

								if (!$full_width) {
									if ($decr_pos == 'bot') {
										echo !empty($media) ? $media : '';
										echo !empty($content_terms) ? $content_terms : '';
									} else {
										echo "<div class='single-col single-col-" . (!empty($media_width) ? $media_width : '') . "'>";
											echo !empty($media) ? $media : '';
										echo "</div>";
										echo "<div class='single-col single-col-" . $cont_width . "'>";
											echo !empty($content_terms) ? $content_terms : '';
										echo "</div>";
									}
								} else {
									echo sprintf("%s", $content_terms);
								}

								cws_page_links();
							echo "</article>";
						endwhile;
						wp_reset_postdata();
						unset( $GLOBALS['cws_vc_shortcode_single_post_atts'] );
					?>
				</div>
				<?php

                    echo '<div class="single-post-meta">';
                        echo cws_blog_meta_author();
                        echo cws_blog_meta_likes();
                        echo cws_post_categories();
                    echo '</div>';

					if ( $use_related_carousel ){
						$related_ids = array();
						$previous = '';

						if ( wp_get_referer() )
						{
							$previous = wp_get_referer();
						}

						foreach ( $related_posts as $related_post ) {
							$related_ids[] = $related_post->ID;
						}
						array_unshift( $related_ids, $p_id );
						$ajax_data = array(
							'current' => $p_id,
							'initial' => $p_id,
							'related_ids' => $related_ids
						);
						echo "<input type='hidden' id='cws-portfolio-single-ajax-data' value='" . esc_attr
                            (json_encode( $ajax_data ) ) . "' />";
						?>

                        <!-- Post navigation -->
                        <div class="single-portfolio-links">
                            <div class="nav-post-links clearfix">

                                <?php
                                $prev_post = get_previous_post();
                                if ( !empty($prev_post) ) {
                                    $prev_id = get_previous_post()->ID;
                                    $prev_title = get_previous_post()->post_title;
                                    if( empty($prev_title) ){
                                        $prev_title = 'Prev Post (no title)';
                                    }

                                    $prev_link = get_permalink($prev_id);
                                    $prev_img = get_the_post_thumbnail($prev_id, 'thumbnail');
                                    $prev_date = get_the_date('M d, Y', $prev_id);
                                }

                                $next_post = get_next_post();
                                if ( !empty($next_post) ) {
                                    $next_id = get_next_post()->ID;
                                    $next_title = get_next_post()->post_title;
                                    if( empty($next_title) ){
                                        $next_title = 'Next Post (no title)';
                                    }
                                    $next_link = get_permalink($next_id);
                                    $next_img = get_the_post_thumbnail($next_id, 'thumbnail');
                                    $next_date = get_the_date('M d, Y', $next_id);
                                }

                                echo '<div class="current-post"></div>';

                                echo '<div class="prev-post nav-post">';
                                    if ( !empty($prev_post) ) {
                                        echo '<a href="' . $prev_link . '" class="nav-post-link">';
                                            echo '<span class="nav-post-text">' . esc_html__('Prev Post', 'metamax') . '</span>';
                                            echo '<span class="nav-post-thumb">';
                                                echo sprintf('%s', $prev_img);
                                            echo '</span>';
                                            echo '<span class="nav-post-info">';
                                                echo '<span class="nav-post-title">' . $prev_title . '</span>';
                                                echo '<span class="nav-post-date">' . $prev_date . '</span>';
                                            echo '</span>';
                                        echo '</a>';
                                    }
                                echo '</div>';
                                echo '<div class="next-post nav-post">';
                                    if ( !empty($next_post) ) {
                                        echo '<a href="' . $next_link . '" class="nav-post-link">';
                                            echo '<span class="nav-post-text">' . esc_html__('Next Post', 'metamax') . '</span>';
                                            echo '<span class="nav-post-info">';
                                                echo '<span class="nav-post-title">' . $next_title . '</span>';
                                                echo '<span class="nav-post-date">' . $next_date . '</span>';
                                            echo '</span>';
                                            echo '<span class="nav-post-thumb">';
                                                echo sprintf('%s', $next_img);
                                            echo '</span>';
                                        echo '</a>';
                                    }
                                echo '</div>';
                                ?>

                            </div>
                        </div>

						<?php
					}
				?>
			</section>
		</div>
		<div class="grid-row single-portfolio related-portfolio">
			<?php
				if ( $show_related ){
					$terms = wp_get_post_terms( $p_id, 'cws_portfolio_cat' );
					$term_slugs = array();
					for ( $i=0; $i < count( $terms ); $i++ ){
						$term = $terms[$i];
						$term_slug = $term->slug;
						array_push( $term_slugs, $term_slug );
					}
					$term_slugs = implode( ",", $term_slugs );
					if ( !empty( $term_slugs ) ){
						$rp_args = array(
							'title'							    => $rpo_title,
							'post_type'						    => 'cws_portfolio',
							'total_items_count'				    => $rpo_items_count,
							'display_style'					    => 'carousel',
							'cws_portfolio_show_data_override'	=> true,
							'cws_portfolio_data_to_show'        => 'title,excerpt',
							'en_hover_color'				    => true,
							'link_show'						    => 'popup_link',
							'layout'						    => $rpo_cols,
							'tax'							    => 'cws_portfolio_cat',
							'info_pos'						    => 'under_img',
							'info_align'						=> 'left',
							'hover_color'					    => 'rgba(255,255,255,0.9)',
							'navigation_carousel'			    => true,
							'call_from'						    => 'related',
							'terms'							    => !empty($categories) ? implode( ",", $categories ) : $term_slugs,
							'addl_query_args'				    => array(
								'post__not_in'					=> array( $p_id ),
							),
						);
						$related_projects = cws_vc_shortcode_cws_portfolio_posts_grid( $rp_args );
						if ( !empty( $related_projects ) ){
							echo sprintf("%s", $related_projects);
						}
					}
				}
			?>
		</div>
	</main>
	<?php echo isset($sb['content']) && !empty($sb['content']) ? '</div>' : ''; ?>
</div>

<?php
	get_footer();
?>





