<?php
	$paged = !empty($_POST['paged']) ? (int)$_POST['paged'] : (!empty($_GET['paged']) ? (int)$_GET['paged'] : ( get_query_var("paged") ? get_query_var("paged") : 1 ) );
	$posts_per_page = (int)get_option('posts_per_page');
	$search_terms = get_query_var( 'search_terms' );

	get_header();
	global $cws_theme_funcs;
	global $metamax_theme_standard;

	$p_id = get_queried_object_id();
    $fixed_header = '';
    if ($cws_theme_funcs){
        $sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
        $fixed_header = $cws_theme_funcs->cws_get_meta_option( 'fixed_header' );
        $class = $sb['layout_class'].' '. $sb['sb_class'];
        $sb['sb_class'] = apply_filters('cws_print_single_class', $class);
    } else {
        $sb = $metamax_theme_standard->cws_render_default_sidebars('page','double','both');
    }
?>
<div class="page-content search-results <?php echo (isset($sb['sb_class']) ? $sb['sb_class'] : 'page-content'); ?>">
    <?php echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '<div class="container">'; ?>
    <?php if(isset($sb['content']) && !empty($sb['content'])){
        echo '<i class="sidebar-tablet-trigger"></i>';
    } ?>

	<main>
		<div class="grid-row clearfix">
			<?php
			global $wp_query;
			$total_post_count = $wp_query->found_posts;
			$max_paged = ceil( $total_post_count / $posts_per_page );
			if ( 0 === strlen($wp_query->query_vars['s'] ) ){
				$message_title = esc_html__( 'Empty search string', 'metamax' );
				$message = esc_html__( 'Please, enter some characters to search field', 'metamax' );
				if(!empty($cws_theme_funcs)){
					$printed_theme_funcs = $cws_theme_funcs->cws_print_search_form($message_title, $message);
					echo sprintf("%s", $printed_theme_funcs);
				}else{
					$printed_theme_spandard = $metamax_theme_standard->cws_print_search_form($message_title, $message);
					echo sprintf("%s", $printed_theme_spandard);
				}
				
			} else {

				if(have_posts()){
					?>
					<section class="news posts-grid layout-1 meta-above-title">
						<div class='cws-vc-shortcode-wrapper'>
							<div class="cws-vc-shortcode-grid grid layout-1">
							<?php
								$use_pagination = $max_paged > 1;
									while( have_posts() ) : the_post();
										$content = get_the_content();
										$content = preg_replace( '/\[.*?(\"title\":\"(.*?)\").*?\]/', '$2', $content );
										$content = preg_replace( '/\[.*?(|title=\"(.*?)\".*?)\]/', '$2', $content );
										$content = strip_tags( $content );
										$content = preg_replace( '|\s+|', ' ', $content );
										$title = get_the_title();

										$cont = '';
										$bFound = false;
										$contlen = strlen( $content );

										foreach ($search_terms as $term) {
											$pos = 0;
											$term_len = strlen($term);
											do {
												if ( $contlen <= $pos ) {
													break;
												}
												$pos = stripos( $content, $term, $pos );
												if ( $pos ) {
													$start = ($pos > 50) ? $pos - 50 : 0;
													$temp = substr( $content, $start, $term_len + 100 );
													$cont .= ! empty( $temp ) ? $temp . ' ... ' : '';
													$pos += $term_len + 50;
												}
											} while ($pos);
										}

										if( strlen($cont) > 0 ){
											$bFound = true;
										} else {
											$cont = mb_substr( $content, 0, $contlen < 100 ? $contlen : 100 );
											if ( $contlen > 100 ){
												$cont .= '...';
											}
											$bFound = true;
										}

										$pattern = "#\[[^\]]+\]#";
										$replace = "";
										$cont = preg_replace($pattern, $replace, $cont);
										$cont = preg_replace('/('.implode('|', $search_terms) .')/iu', '<mark>\0</mark>', $cont);
										$permalink = esc_url( get_the_permalink() );
										$button_word = esc_html__( 'Read More', 'metamax' );
										$title = get_the_title();
										$title = preg_replace( '/('.implode( '|', $search_terms ) .')/iu', '<mark>\0</mark>', $title );

										echo "<article class='item post-item'>";
											echo "<div class='post-wrapper'>";

												echo "<div class='post-info'>";

                                        /* -----> Title <----- */
                                        if( !empty($title) ){
                                            echo "<h3 class='post-title'>";
                                            echo "<a href='".$permalink."'>".$title."</a>";
                                            echo "</h3>";
                                        }

                                        /* -----> Content <----- */
                                        if( !empty($cont) ){
                                            echo "<div class='post-content'>";
                                            echo apply_filters( 'the_content', $cont );
                                            echo "</div>";
                                        }




                                        echo "<div class='post-info-footer'>";
                                            echo "<div class='post-meta-wrapper'>";
                                                echo "<div class='post-meta'>";
                                        /* -----> Author <----- */
                                        $author = get_the_author();
                                        ob_start();
                                        the_author_posts_link();
                                        $author_link = ob_get_clean();

                                        if( !empty($author) ){
                                            echo "<div class='post-meta-item post-author'>".esc_html__('by ', 'metamax').
                                            $author_link."</div>";
                                        }
                                        /*\ ----> Author <---- \*/

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
                                        /*\ -----> Date <----- \*/

                                        /* -----> Categories <----- */
                                        if ( has_category() ) {
                                            echo "<div class='post-meta-item post-category'>";
                                            $cats = "";
                                            if ( has_category() ) {
                                                ob_start();
                                                the_category ( ", " );
                                                $cats .= ob_get_clean();
                                            }
                                            if ( !empty( $cats ) ){
                                                echo sprintf("%s", $cats);
                                            }
                                            echo '</div>';
                                        }
                                        /*\ -----> Categories <----- \*/

                                        /* -----> Tags <----- */
                                        if ( has_tag() ) {
                                            echo "<div class='post-meta-item post-tags'>";
                                            $tags = "";
                                            if ( has_tag() ) {
                                                ob_start();
                                                the_tags ( "", ", ", "" );
                                                $tags .= ob_get_clean();
                                            }
                                            if ( !empty( $tags ) ){
                                                echo sprintf("%s", $tags);
                                            }
                                            echo '</div>';
                                        }
                                        /*\ -----> Tags <----- \*/

                                        echo "</div>";
                                        echo "<div class='post-info-footer-divider'></div>";
                                        echo "</div>";

                                    /* -----> Read More <----- */
                                    echo "<div class='read-more-wrapper'>";
                                        echo "<a href='".$permalink."' class='read-more'>".$button_word."</a>";
                                    echo "</div>";

                                        echo "</div>";






												echo "</div>";

											echo "</div>";
										echo "</article>";
									endwhile;
									wp_reset_postdata();
								?>
							</div>
						</div>
					</section>
					<?php
					if ( $use_pagination ) {
						cws_pagination($paged, $max_paged);
					}
				}
				else {
					$message_title = esc_html__( 'Nothing Found', 'metamax' );
					$message = esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'metamax' );
					
					if(!empty($cws_theme_funcs)){
						$printed_cws_theme_funcs = $cws_theme_funcs->cws_print_search_form($message_title, $message);
						echo sprintf("%s", $printed_cws_theme_funcs);
					}else{
						$printed_metamax_theme_standard = $metamax_theme_standard->cws_print_search_form($message_title, $message);
						echo sprintf("%s", $printed_metamax_theme_standard);
					}
				}
			}
			?>
		</div>
	</main>
    <?php echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : '</div>'; ?>
</div>

<?php

get_footer ();
?>