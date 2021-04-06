<?php
	get_header ();

	global $cws_theme_funcs;
	global $metamax_theme_standard;

	$pid = get_the_id();
	if ($cws_theme_funcs){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$class = $sb['layout_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);

		$meta = $cws_theme_funcs->cws_get_post_meta( $pid );
		if(isset($meta[0])){
			$meta = $meta[0];
		} 
		extract( shortcode_atts( array(
			'enable_lightbox' => '0',
			'show_related' => '0',
			'show_featured' => '',
			'full_width_featured' => '',
		), $meta) );


		$fw_featured = $show_featured == '1' && $full_width_featured == '1';	
	}

	$page_class = '';
	$page_class .= (isset($sb) ? $sb['sb_class'] : ' page-content');
	$page_class .= isset($fw_featured) && $fw_featured ? ' full-width-featured' : '';

?>
<div class="<?php echo sprintf("%s", $page_class); ?>">
	<?php
	
	if ($cws_theme_funcs){	

		$GLOBALS['cws_vc_shortcode_single_post_atts'] = array(
			'sb_layout'	=> $sb['layout_class'],
		);	

		echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '';

		$related_projects_title = isset( $meta['rpo']['title'] ) ? $meta['rpo']['title'] : '';
		$related_projects_category = isset( $meta['rpo']['category'] ) ? $meta['rpo']['category'] : '';
		$related_projects_text_length = isset( $meta['rpo']['text_length'] ) ? $meta['rpo']['text_length'] : '90';
		$related_projects_cols = isset( $meta['rpo']['cols'] ) ? (int) $meta['rpo']['cols'] : '4';
		$related_projects_count = isset( $meta['rpo']['items_show'] ) ? (int) $meta['rpo']['items_show'] : '4';
		$posts_hide = isset( $meta['rpo']['posts_hide'] ) ? $meta['rpo']['posts_hide'] : '';

		$related_projects_category = !empty($related_projects_category) ? implode(',', $related_projects_category) : '';
		$posts_hide = !empty($posts_hide) ? implode(',', $posts_hide) : '';
	}

	$query_args = array(
		'post_type' => 'post',
		'ignore_sticky_posts' => true,
		'post_status' => 'publish',
	);
	$query_args["post__not_in"] = array( $pid );
	if ($cws_theme_funcs){
		if ( $related_projects_count ){
			$query_args['posts_per_page'] = $related_projects_count;
		}
	}
	$q = new WP_Query( $query_args );
	$related_posts = $q->posts;
	$has_related = false;
	if ( count( $related_posts ) > 0 ) {
		$has_related = true;
	}

	if ($cws_theme_funcs){
		$show_related_items = $show_related == 1 && $has_related;
	}

	$section_class = "news single";

	$query = cws_blog_defaults();

	$query['layout'] = '1';
	$query['post_hide_meta_override'] = true;
	$query['post_hide_meta'] = array('title');

	?>

	<main>
		<?php if(isset($sb['content']) && !empty($sb['content'])){ 
			echo '<i class="sidebar-tablet-trigger"></i>';
		} ?>
		<div class="grid-row clearfix">
			<section class="<?php echo esc_attr($section_class) ?>">
				<div class="cws-wrapper">
					<div class="grid">
						<?php
							while ( have_posts() ):
								the_post();
								cws_single_post_output($query);
							endwhile;
							wp_reset_postdata();

                            echo '<div class="single-post-meta">';
                                echo cws_blog_meta_author();
                                echo cws_blog_meta_likes();
                                echo cws_post_categories();
                            echo '</div>';
						?>
					</div>
				</div>
			</section>
        </div>

        <!-- Post navigation -->
        <div class="grid-row single-post-links">
            <div class="nav-post-links clearfix">

                <?php
                $prev_post = get_previous_post();
                if ( !empty($prev_post) ) {
                    $prev_id = get_previous_post()->ID;
                    $prev_title = get_previous_post()->post_title;
                    if (empty($prev_title)) {
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
                    if (empty($next_title)) {
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
                                echo sprintf( '%s', $next_img );
                            echo '</span>';
                        echo '</a>';
                    }
                echo '</div>';
                ?>

            </div>
        </div>

		<?php 
			if ( $cws_theme_funcs && $show_related_items ){
				$crop_image = $cws_theme_funcs -> cws_get_option('crop_related_items');
				$crop_image = isset($crop_image) ? (bool)$crop_image : '';

				$mode = $q->post_count > $related_projects_cols ? '1' : '0';
				
				$terms = wp_get_post_terms( get_queried_object_id(), 'category' );
				$term_slugs = array();
				for ( $i=0; $i < count( $terms ); $i++ ){
					$term = $terms[$i];
					$term_slug = $term->slug;
					array_push( $term_slugs, $term_slug );
				}
				$term_slugs = implode( ",", $term_slugs );
				$p_id = get_queried_object_id();
				$sc_atts = array(
					'title' 						=> $related_projects_title,
					'tax' 							=> 'category',
					'related_items' 				=> true,
  					'terms' 						=> !empty($related_projects_category) ? $related_projects_category : $term_slugs,
  					'total_items_count'				=> $related_projects_count,
  					'display_style'					=> ($related_projects_count > $related_projects_cols ? 'carousel' : 'grid'),
  					'layout'						=> $related_projects_cols,
  					'chars_count' 					=> $related_projects_text_length,
  					'post_hide_meta_override'		=> true,
  					'navigation_carousel'			=> true,
  					'crop_featured'					=> $crop_image,
  					'meta_position'					=> 'bottom',
  					'post_hide_meta'				=> $posts_hide,
  					'addl_query_args'				=> array(
					'post__not_in'					=> array( $p_id )
					),
				);

				$related_projects_section = function_exists('cws_blog_output') ? cws_blog_output( $sc_atts ) : "";

				if( !empty($related_projects_section) ){
					echo "<div class='grid-row single-related'>";
						echo "<h3 class='related-item ce-title'>".esc_html($related_projects_title)."</h3>";
						echo sprintf('%s', $related_projects_section);
					echo "</div>";
				}
				
				unset( $GLOBALS['cws_vc_shortcode_single_post_atts'] );
			}
		?>

	    </main>
        <?php echo (isset($sb) && !empty($sb['content']) ) ? '</div>' : ''; ?>
        <div class="container">
            <?php
            comments_template();

            if (is_singular() && comments_open() && get_option( 'thread_comments' ) == '1'){
                wp_enqueue_script('comment-reply');
            }
            ?>
        </div>
    </div>

<?php

get_footer ();
?>