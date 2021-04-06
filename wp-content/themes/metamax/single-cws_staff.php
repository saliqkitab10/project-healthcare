<?php
	get_header();

	global $cws_theme_funcs;

	/* -----> Variables declaration <----- */
	$p_id = get_queried_object_id();
	$deps = cws_vc_shortcode_get_post_term_links_str( 'cws_staff_member_department', ', ' );
	$poss = cws_vc_shortcode_get_post_term_links_str( 'cws_staff_member_position', ', ' );

	if( $cws_theme_funcs ){
		$sb = $cws_theme_funcs->cws_render_sidebars($p_id);
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);
	}

	/* -----> Get Post Meta <-----*/
	$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );

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

	?>
	<div class="<?php echo (isset($sb['sb_class']) ? $sb['sb_class'] : 'page-content'); ?>">
		<?php
		echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '';

		echo "<main id='page-content'>";

			echo "<div class='grid-row'>";
			$GLOBALS['cws_vc_shortcode_single_post_atts'] = array(
				'sb_layout'	=> $sb['layout_class'],
			);

			while ( have_posts() ) : the_post();
				echo "<article class='item cws-staff-single'>";
					echo "<div class='main-staff-info'>";

						echo "<div class='socials-wrapper'>";
							cws_vc_shortcode_cws_staff_posts_grid_social_links();
						echo "</div>";

						cws_vc_shortcode_cws_staff_posts_grid_post_media();

						echo "<div class='information-wrapper'>";
							cws_vc_shortcode_cws_staff_posts_grid_post_title();
							if( !empty($deps) ){
								echo "<div class='department-list'>";
									echo "<span class='label'>".esc_html__('Department', 'metamax').": </span>";
									echo sprintf('%s', $deps);
								echo "</div>";	
							}
							if ( !empty( $poss ) ){
								echo "<div class='position-list'>";
									echo "<span class='label'>".esc_html__('Position', 'metamax').": </span>";
									echo sprintf('%s', $poss);
								echo "</div>";	
							}
							if( !empty($experience) ){
								echo "<div class='experience'>";
									echo "<span class='label'>".esc_html__('Experience', 'metamax').": </span>";
									echo "<span>".$experience."</span>";
								echo "</div>";
							}
							if( !empty($email) ){
								echo "<div class='email'>";
									echo "<span class='label'>".esc_html__('Email', 'metamax').": </span>";
									echo "<a href='mailto:".$email."'>".$email."</a>";
								echo "</div>";
							}
							if( !empty($tel) ){
								echo "<div class='tel'>";
									echo "<span class='label'>".esc_html__('Tel', 'metamax').": </span>";
									echo "<a href='tel:".$tel."'>".$tel."</a>";
								echo "</div>";
							}
						echo "</div>";

					echo "</div>";

					if( !empty($biography) ){
						echo "<div class='biography'>";
							echo "<h5>".esc_html__('Biography', 'metamax').": </h5>";
							echo "<p>".$biography."</p>";
						echo "</div>";
					}

					ob_start();
						cws_vc_shortcode_cws_staff_posts_grid_post_content();
					$content = ob_get_clean();

					if( !empty($content) ){
						echo "<div class='personal_info'>";
							echo "<h5>".esc_html__('Personal Information', 'metamax').": </h5>";
							echo sprintf('%s', $content);
						echo "</div>";
					}

					cws_page_links();

				echo "</article>";

			endwhile;

			wp_reset_postdata();
			unset( $GLOBALS['cws_vc_shortcode_single_post_atts'] );

		echo "</div>";


		echo "</main>";
		echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : '';
		?>
	</div>

<?php
	get_footer();
?>
