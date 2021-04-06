<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version 3.3.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}

$is_rtl = is_rtl();
?>
<nav class="pagination woocommerce-pagination">
	<div class='page-links'>
	<?php
		$prev_text = esc_html__( 'Prev' , 'metamax' );
		$next_text = esc_html__( 'Next' , 'metamax' );
		echo paginate_links( apply_filters( 'woocommerce_pagination_args', array( // WPCS: XSS ok.
			'base'		=> $base,
			'format'	=> $format,
			'add_args'	=> false,
			'current'	=> max( 1, $current ),
			'total'		=> $total,
			'prev_text'	=> "<i class='flaticon " . ( $is_rtl ? "flaticon-arrow-point-to-right" : 'flaticon-arrowhead-thin-outline-to-the-left' ) . "'></i>",
			'next_text'	=> "<i class='flaticon " . ( $is_rtl ? "flaticon-arrowhead-thin-outline-to-the-left" : 'flaticon-arrow-point-to-right' ) . "'></i>",
			'end_size'	=> 3,
			'mid_size'	=> 3,
		) ) );
	?>
	</div>
</nav>