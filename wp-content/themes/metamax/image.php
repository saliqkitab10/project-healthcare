<?php
	get_header ();

	$p_id = get_queried_object_id();

	global $cws_theme_funcs;
	if(!empty($cws_theme_funcs)){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);

	}else{
		global $metamax_theme_standard;
	}

?>
<div class="<?php echo (isset($sb) ? $sb['sb_class'] : 'page-content'); ?>">
	<?php 
		echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '';
	?>
	<main>
		<?php if(isset($sb['content']) && !empty($sb['content'])){ 
			echo '<i class="sidebar-tablet-trigger"></i>';
		} ?>
		<div class="grid-row clearfix">
			<section class="news posts-grid layout-1">
				<div class="cws-vc-shortcode-wrapper">
					<div class="cws-vc-shortcode-grid grid layout-1">
						<?php
							while ( have_posts() ) :
								the_post();
						?>
						<article class="item post-item">
							<div class="post-wrapper">
								<div class="post-media">
									<?php
										$image = get_the_id();

										echo "<div class='pic'>";
											$image_src = wp_get_attachment_image_src($image, 'full');
											$image_srcset = wp_get_attachment_image_srcset($image, 'full');
											$image_link = wp_get_attachment_image_url($image, 'full');
											$image_sizes = wp_get_attachment_image_sizes($image, 'full');

											$image_title = get_post($image)->post_title;
											$image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
											$image_alt = !empty($image_alt) ? $image_alt : $image_title;

											echo "<img src='".esc_url($image_src[0])."' srcset='".esc_attr($image_srcset)."' sizes='".esc_attr($image_sizes)."' alt='".esc_attr($image_alt)."'/>";

										echo "</div>";
									?>
								</div>

								<div class="post-info">
								    <div class="post-info-footer">
                                        <div class="post-meta-wrapper">
                                            <?php
                                                /* -----> Author <----- */
                                                $author = get_the_author();
                                                if ( !empty($author) ){
                                                    ob_start();
                                                    the_author_posts_link();
                                                    $author_link = ob_get_clean();
                                                    echo "<div class='post-meta-item post-author'>";
                                                        echo "<span class='post-author-avatar'>";
                                                            echo "<a href='" . get_author_posts_url(get_the_author_meta('ID')) ."'>" . get_avatar(get_the_author_meta('ID'), 35) . "</a>";
                                                        echo "</span>";
                                                        echo "<span class='post-author-name'>" . sprintf('%s', $author_link). "</span>";
                                                    echo "</div>";
                                                }

                                                /* -----> Date <----- */
                                                $year = get_the_time('Y');
                                                $month = get_the_time('m');
                                                $date_link = get_month_link($year, $month);
                                                $date = get_the_time( get_option("date_format") );
                                                if ( !empty( $date ) ){
                                                    echo "<div class='post-meta-item post-date'>";
                                                        echo "<a href='".$date_link."'>";
                                                            echo sprintf('%s', $date);
                                                        echo "</a>";
                                                    echo "</div>";
                                                }

                                                /* -----> Comments <----- */
                                                $permalink = get_the_permalink();
                                                $permalink .= "#comments";
                                                $comments_n = get_comments_number();
                                                if ( (int)$comments_n > 0 ) {
                                                    if( $comments_n == '1' ){
                                                        $comment_text = esc_html__( 'Comment', 'metamax' );
                                                    } else {
                                                        $comment_text = esc_html__( 'Comments', 'metamax' );
                                                    }
                                                    echo "<span class='post-meta-item post-comments'>";
                                                        echo "<a href='".esc_url($permalink)."'>";
                                                            echo sprintf('%s', $comments_n);
                                                            echo "<span> ".esc_html($comment_text)."</span>";
                                                        echo "</a>";
                                                    echo "</span>";
                                                }
                                            ?>
                                        </div>
                                        <?php
                                            $content = get_the_content();

                                            if( !empty($content) ){
                                                echo '<div class="post-content">';
                                                    echo apply_filters( 'the_content', $content );
                                                echo '</div>';
                                            }
                                        ?>
								    </div>
								</div>
							</div>
						</article>


						<?php
							endwhile;
							wp_reset_postdata();
						?>
					</div>
				</div>
			</section>
		</div>

		<?php comments_template(); ?>

	</main>
	<?php echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : ''; ?>
</div>

<?php

get_footer ();
?>