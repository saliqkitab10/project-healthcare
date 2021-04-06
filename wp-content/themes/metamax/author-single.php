<?php
	global $cws_theme_funcs;

	$pid = get_the_id();
	if ($cws_theme_funcs){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);

		$meta = $cws_theme_funcs->cws_get_post_meta( $pid );
		if(isset($meta[0])){
			$meta = $meta[0];
		} 
		extract( shortcode_atts( array(
				'enable_lightbox' => '0',
				'show_related' => '0',
				'author_info' => '1',
				'show_featured' => '',
			'full_width_featured' => '',
		), $meta) );
	}

	if ($cws_theme_funcs && function_exists('cws_rewrite_slug')){
		if ($author_info){
			$author_meta = get_user_meta($post->post_author, 'cws_mb_user' );

			$author_position = (!empty($author_meta[0]['position']) ? $author_meta[0]['position'] : '');
			$author_avatar = (!empty($author_meta[0]['avatar']['src']) ? $author_meta[0]['avatar']['src'] : '');
			$author_social = (!empty($author_meta[0]['social_group']) ? $author_meta[0]['social_group'] : '');
			$author_url = (!empty($author_meta[0]['author_url']) ? $author_meta[0]['author_url'] : '');
			$author_description = get_user_meta($post->post_author, 'description', true);

			$first_name = get_user_meta($post->post_author, 'first_name', true);
			$last_name = get_user_meta($post->post_author, 'last_name', true);

			if (!empty($first_name) || !empty($last_name)){
				$author_name = $first_name . (!empty($last_name) ? ' '.$last_name : '' );
			} else {
				$author_name = get_the_author();
			}

			if(!empty( $author_avatar )) {

				if (function_exists('cws_get_img')) {
                    $thumb_obj = cws_get_img( $author_avatar,array('width' => 90, 'height' => 90, 'crop' => true) ,false );
                } else {
                    $thumb_obj = array(
                            0 => wp_get_attachment_image_url($author_avatar, array(90, 90)),
                            1 => 90,
                            2 => 90,
                            3 => wp_get_attachment_image_url($author_avatar, array(180, 180)),
                    );
                }

				$thumb_url = isset( $thumb_obj[0] ) ? $thumb_obj[0] : "";
				$retina_thumb = isset( $thumb_obj[3] ) ? $thumb_obj[3] : false;
				
				?>
					<div class="author_info cws_content_top">
						<?php if(!empty( $author_avatar )) { ?>
						<div class='author_pic'>
							<?php

							$get_alt = get_post_meta($thumb_obj[1], '_wp_attachment_image_alt', true); 
							$img_alt = " alt='" . (!empty($get_alt) ? $get_alt : get_the_title($thumb_obj[1])) . "'";

							echo (!empty($author_url) ? "<a href='".esc_url($author_url)."'>" : "" );
							if ( $retina_thumb ) {
								echo "<img src='".esc_url($thumb_url)."' data-at2x='".esc_url($retina_thumb)."' ".$img_alt." />";
							}
							else{
								echo "<img src='".esc_url($thumb_url)."' data-no-retina ".$img_alt." />";
							}	
						echo (!empty($author_url) ? "</a>" :"" ) ?>
					</div>
					<?php } ?>
					<div class="author_description">
						<div class="cols-wrapper">
							<div class="widget_wrapper">
								<div class="ce clearfix">
									<div class="author_title">
										<?php echo !empty($author_position) ? " <h5 class='author_pos'>".esc_html($author_position)."</h5>" : ''; ?>
									</div>
									<div>
										<?php
										if(!empty( $author_social )) {
											?>
											<div class="cws-social-links">
												<?php foreach ($author_social as $key => $value) { ?>
												<a href="<?php echo esc_url($value['url']) ?>" class="cws-social-link" title="<?php echo esc_attr($value['title']) ?>" target="_blank">
													<i style="color:<?php echo esc_attr($value['color']); ?>;" class="cws_fa <?php echo esc_attr($value['icon']) ?> fa-2x simple_icon"></i>
												</a>
												<?php } ?>
											</div>
											<?php } ?>
										</div>									
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php
			}
		}
	}
?>