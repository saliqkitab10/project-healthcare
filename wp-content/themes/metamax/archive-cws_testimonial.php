<?php
	get_header ();

	$p_id = get_queried_object_id();
	global $cws_theme_funcs;
	if ($cws_theme_funcs){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$fixed_header = $cws_theme_funcs->cws_get_meta_option( 'fixed_header' );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);
	}
	$posts_grid_atts = array(
			'post_type'						=> 'cws_portfolio',
			'total_items_count'				=> PHP_INT_MAX
		);
	if ( is_date() ){
		$year = $cws_theme_funcs->cws_get_date_part( 'y' );
		$month = $cws_theme_funcs->cws_get_date_part( 'm' );
		$day = $cws_theme_funcs->cws_get_date_part( 'd' );
		if ( !empty( $year ) ){
			$posts_grid_atts['addl_query_args']['year'] = $year;
		}
		if ( !empty( $month ) ){
			$posts_grid_atts['addl_query_args']['monthnum'] = $month;
		}
		if ( !empty( $day ) ){
			$posts_grid_atts['addl_query_args']['day'] = $day;
		}
	}

?>
<div class="<?php echo (isset($sb['sb_class']) ? $sb['sb_class'] : 'page-content'); ?>">
	<?php
		echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '';
 
	?>
	<main id='page-content'>
		<div class="grid-row">
			<?php
				$posts_grid = cws_vc_shortcode_cws_testimonial_posts_grid( $posts_grid_atts );
				echo sprintf("%s", $posts_grid );
			?>
		</div>
	</main>
	<?php echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : ''; ?>
</div>

<?php

get_footer ();
?>