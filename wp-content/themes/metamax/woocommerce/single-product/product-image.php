<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product, $post;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$thumbnail_size    = apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' );
$post_thumbnail_id = $product->get_image_id();
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, $thumbnail_size );
$placeholder       = $post_thumbnail_id ? 'with-images' : 'without-images';
$gallery_init = $product->get_gallery_image_ids();
$thumb_dims = '';
$retina_thumb = '';

//CWS ADDON
if ( $post_thumbnail_id ) {
	$thumb_dims = get_option( 'shop_single_image_size' );

	$image = "";
    if (function_exists('cws_get_img')) {
        $thumb_obj = cws_get_img( get_post_thumbnail_id(), $thumb_dims );
    } else {
        $thumb_obj = array(
            0 => wp_get_attachment_image_url(get_post_thumbnail_id(), $thumb_dims),
            1 => '',
            2 => '',
            3 => '',
        );
    }
	$thumb_url = isset( $thumb_obj[0] ) ? esc_url( $thumb_obj[0] ) : "";
	$retina_thumb = isset( $thumb_obj[3] ) ? $thumb_obj[3] : false;
}

//-----CWS ADDON

$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
	'woocommerce-product-gallery',
	'woocommerce-product-gallery--' . $placeholder,
	'woocommerce-product-gallery--' . (!empty($gallery_init) ? 'init' : 'no_gallery'),
	'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
	'woocommerce-product-gallery--columns-' . absint( $columns ),
	'images',
	'post_media',
	'woo-product-post-media',
	'post-single-post-media',
	($retina_thumb ? ' img_retina' : '')
) );	

$attributes = array(
	'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
	'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
	'data-src'                => $full_size_image[0],
	'data-large_image'        => $full_size_image[0],
	'data-large_image_width'  => $full_size_image[1],
	'data-large_image_height' => $full_size_image[2],
);

$html = '';
?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">

	<?php if ( $retina_thumb) { ?>
		<div class="post-media-wrapper woocommerce-product-gallery__wrapper woo-product-post-media-wrapper
		post-single-post-media-wrapper">
	<?php } else { ?>
		<figure class="woocommerce-product-gallery__wrapper">
	<?php }

			if ( $post_thumbnail_id ) {
				//CWS ADDON
				$image_link    = wp_get_attachment_url( get_post_thumbnail_id() );
				if ( $retina_thumb ) {
					$html  = '<div data-thumb="' . get_the_post_thumbnail_url( $post->ID, 'shop_thumbnail' ) . '" class="pic woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= apply_filters( "woocommerce_single_product_image_html", "<img src='".esc_url($thumb_url)."' data-at2x='".esc_url($retina_thumb)."' alt" );
					foreach ( $attributes as $name => $value ) {
						$html .= " $name=" . '"' . $value . '"';
					}
					$html .= "/>";
				}
				else{
					$html  = wc_get_gallery_image_html( $post_thumbnail_id, true );
				}
				//-----CWS ADDON
				if ( $retina_thumb) {
					$html .= '</a></div>';
				}
			} else {
				$html  = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src('woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'metamax' ) );
				$html .= '</div>';
			}
			if(!empty($html)){
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );
			}
			
		do_action( 'woocommerce_product_thumbnails' );
		
	if ( $retina_thumb) { ?>
		</div>
	<?php } else { ?>
		</figure>
	<?php } ?>

</div>