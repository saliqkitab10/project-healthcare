<?php
	global $cws_theme_funcs;
	global $metamax_theme_standard;
	if ($cws_theme_funcs){

		if (is_page()){
			$blogtype = $cws_theme_funcs->cws_get_meta_option( "blogtype" );
		} elseif (is_front_page() || is_category() || is_tag() || is_archive()) {
			$blogtype = $cws_theme_funcs->cws_get_option( "def_blogtype" );
		}
	} else {
		$blogtype = 'large';
	}
	$taxonomy = "category";
	$terms  = array();

	if ( is_page() ) {
		if($cws_theme_funcs){
			$cats = $cws_theme_funcs->cws_get_meta_option( 'category' );
			$terms = !empty($cats) ? explode(',', $cats) : array();
		}
	}	else if ( is_category() ) {
		$term_id = get_query_var( 'cat' );
		$term = get_term_by( 'id', $term_id, 'category' );
		$term_slug = $term->slug;
		$terms = array( $term_slug );
	} else if ( is_tag() ) {
		$taxonomy = 'post_tag';
		$term_slug = get_query_var( 'tag' );
		$terms = array( $term_slug );
	}
	$terms = implode( ",", $terms);

	$post_type_array = array("post");
	$posts_per_page = (int)get_option('posts_per_page');
	$ajax = isset( $_POST['ajax'] ) ? (bool)$_POST['ajax'] : false;
	$paged_var = get_query_var( 'paged' );
	$paged = $ajax && isset( $_POST['paged'] ) ? $_POST['paged'] : ( $paged_var ? $paged_var : 1 );
	$args = array(
		'post_type' => $post_type_array,
		'post_status' => 'publish',
		'tax' => $taxonomy,
		'terms' => $terms,
		'pagination_grid' => 'standard',
	);

	if ( is_date() ) {
		if($cws_theme_funcs){
			$args = array_merge( $args, $cws_theme_funcs->cws_get_date_parts() );
		} else{
			$args = array_merge( $args, $metamax_theme_standard->cws_get_date_parts() );
		}
	}

	$query = new WP_Query( $args );
	$max_paged = ceil( $query->found_posts / $posts_per_page );

	$blogtype = sanitize_html_class( $blogtype );

	$news_class = !empty( $blogtype ) ? ( preg_match( '#^\d+$#', $blogtype ) ? "news-pinterest" : "news-$blogtype" ) : "news-medium";
	$grid_class = $news_class == "news-pinterest" ? "grid-$blogtype isotope" : "";

	if ($news_class == "news-pinterest") {
		wp_enqueue_script ('isotope');
	}

	if ( !$ajax ): // not ajax request

		?>
		<div class="grid-row">
			<?php
				endif;							
					echo cws_blog_output($args);
				if ( !$ajax ): // not ajax request
				if ( $news_class == "news-pinterest" && $paged < $max_paged ) {
					$template = 'content-blog';
				}
			?>
		</div>
		<?php

	endif;
?>