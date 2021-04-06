<?php
get_header();

	global $cws_theme_funcs;
	global $metamax_theme_standard;
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
<div class="<?php echo (isset($sb['sb_class']) ? $sb['sb_class'] : 'page-content'); ?>">
	<?php echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '<div class="container">'; ?>
	<?php if(isset($sb['content']) && !empty($sb['content'])){ 
			echo '<i class="sidebar-tablet-trigger"></i>';
		} ?>
	<main>
	<?php
		// check if the CWS Builder is used on the current page
		$post_content = $post->post_content;
		$cws_grid = preg_match( "#\[cws-row#", $post_content );

		// apply fixed page width if CWS Builder doesn't manage grid
		if (!$cws_grid) {
			echo("<div class='grid-row page-grid'>");
		}

		while ( have_posts() ) : the_post();
			the_content();
			cws_page_links();
		endwhile;

		// close CWS Builder grid
		if (!$cws_grid) {
			echo("</div>");
		}
	wp_reset_postdata();
		if ($cws_theme_funcs){
			$is_blog = $cws_theme_funcs->cws_get_meta_option( 'is_blog' ) == '1';
			if ( $is_blog ) get_template_part( 'content', 'blog' );
		}
		comments_template();
	?>
	</main>
	<?php echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : '</div>'; ?>
</div>
<?php get_footer (); ?>