<?php
	get_header ();
	if(class_exists('WPBMap')){
		WPBMap::addAllMappedShortcodes();
	}
	global $cws_theme_funcs;
	if(!empty($cws_theme_funcs)){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);

	}

	$taxonomy = get_query_var( 'taxonomy' );
	$term_slug = get_query_var( $taxonomy );

?>
<div class="<?php echo (isset($sb) ? $sb['sb_class'] : 'page-content'); ?>">
	<?php
		echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '';

	?>
	<main>
		<div class="grid-row">
			<?php
				switch( $taxonomy ) {
				case "cws_portfolio_cat":
						echo cws_vc_shortcode_cws_portfolio_posts_grid( array(
							'columns' => $cws_theme_funcs->cws_get_option( "def_layout_portfolio" ),
							'tax'							=> $taxonomy,
							$taxonomy . '_terms'			=> $term_slug,
							'crop_images' => '1',
							'display_style' => $cws_theme_funcs->cws_get_option( "portfolio_mode" ),
							'pagination_grid' => $cws_theme_funcs->cws_get_option( "portfolio_pagination_style" ),
							)
						);
						break;
						case "cws_staff_member_department":
						echo cws_vc_shortcode_cws_staff_posts_grid( array(
							'mode' => $cws_theme_funcs->cws_get_option( "staff_mode" ),
							'tax'							=> $taxonomy,
							$taxonomy . '_terms'			=> $term_slug,
							)
						);
						break;
					case "cws_staff_member_position":
						echo cws_vc_shortcode_cws_staff_posts_grid( array(
							'mode' => $cws_theme_funcs->cws_get_option( "staff_mode" ),
							'tax'							=> $taxonomy,
							$taxonomy . '_terms'			=> $term_slug,
							)
						);
						break;
					case "cws_testimonials_department":
						echo cws_vc_shortcode_cws_testimonials_posts_grid( array(
							'columns' => $cws_theme_funcs->cws_get_option( "def_layout_testimonials" ),
							'tax'							=> $taxonomy,
							$taxonomy . '_terms'			=> $term_slug,
							)
						);
						break;
					case "cws_testimonials_position":
						echo cws_vc_shortcode_cws_testimonials_posts_grid( array(
							'columns' => $cws_theme_funcs->cws_get_option( "def_layout_testimonials" ),
							'tax'							=> $taxonomy,
							$taxonomy . '_terms'			=> $term_slug,
							)
						);
						break;				
				}
			?>
		</div>
	</main>
	<?php echo (isset($sb['content']) && !empty($sb['content'])) ? "</div>" : ''; ?>
</div>

<?php

get_footer ();
?>