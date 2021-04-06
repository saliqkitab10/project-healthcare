<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Metamax
 * @since Metamax 1.0
 */
	get_header();	

	global $cws_theme_funcs;
	global $metamax_theme_standard;
	if ($cws_theme_funcs){
		$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
		$fixed_header = $cws_theme_funcs->cws_get_meta_option( 'fixed_header' );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);
	} else {
		$sb = $metamax_theme_standard->cws_render_default_sidebars('blog','double','both');
	}
	?>
	<div class="<?php echo (isset($sb['sb_class']) ? $sb['sb_class'] : 'page-content'); ?>">
		<?php
			echo (isset($sb['content']) && !empty($sb['content'])) ? $sb['content'] : '<div class="container">';
		?>
		<?php if(isset($sb['content']) && !empty($sb['content'])){ 
			echo '<i class="sidebar-tablet-trigger"></i>';
		} ?>
		<main>
			<?php get_template_part( 'content', 'blog' ); ?>
		</main>
		<?php 
			echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : '</div>';
		?>
	</div>

<?php
get_footer();
