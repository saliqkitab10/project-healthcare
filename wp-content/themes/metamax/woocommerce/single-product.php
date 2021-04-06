<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version	 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) { exit; // Exit if accessed directly
}
get_header( 'shop' );

global $cws_theme_funcs;
ob_start();

/**
* woocommerce_sidebar hook.
*
* @hooked woocommerce_get_sidebar - 10
*/
do_action( 'woocommerce_sidebar' );
$woo_sidebar = ob_get_clean();
$woo_sb_position = '';
if($cws_theme_funcs){
	$woo_sb_position = $cws_theme_funcs->cws_get_option('woo_sb_layout_single');	
}

$class_container = 'page-content' . ( ! empty( $woo_sidebar ) && ($woo_sb_position != 'none') ? ' single-sidebar' : '' );

?>

	<div class="<?php echo esc_attr($class_container) ?>">

			<div class="container">

				<?php
					if( !empty($woo_sidebar) ){
						if( $woo_sb_position == 'left' ){
							echo "<aside class='sb-left'>". $woo_sidebar ."</aside>";
						} else if( $woo_sb_position == 'right' ){
							echo "<aside class='sb-right'>". $woo_sidebar ."</aside>";
						}
					}
				?>

				<main>
			<?php					
				/**
				 * woocommerce_before_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked woocommerce_breadcrumb - 20
				 */
			 do_action( 'woocommerce_before_main_content' );
			 ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'single-product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				do_action( 'woocommerce_after_main_content' );

					?>

				</main>
			</div>
	</div>

<?php
get_footer( 'shop' );
?>
