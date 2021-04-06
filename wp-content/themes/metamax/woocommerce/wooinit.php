<?php

class Metamax_WooExt{

	public $def_args;
	public $args;
	public $def_img_sizes;
	public $widget_args;

	public function __construct ( $args = array() ){
		$this->args = wp_parse_args( $args, $this->def_args );
		add_theme_support( 'woocommerce' );	// Declare Woo Support
		add_action( 'activate_woocommerce/woocommerce.php', array( $this, 'on_woo_activation' ), 10 );
		if ( class_exists( 'woocommerce' ) ) {
			$this->def_args = array(
				'shop_catalog_image_size' 		=> array(),
				'shop_single_image_size'		=> array(),
				'shop_thumbnail_image_size'		=> array(),
				'shop_thumbnail_image_spacings'	=> array(),
				'shop_single_image_spacings'	=> array()
			);
			add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );
			add_action( 'woocommerce_init', array( $this, 'woo_init' ) );
			add_filter( 'woocommerce_enqueue_styles', '__return_false' );
			add_filter( 'woocommerce_show_page_title', '__return_false' );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_script' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ), 11 );
			add_action( 'body_font_hook',  array( $this, 'body_font_styles' ) );
			add_action( 'header_font_hook',  array( $this, 'header_font_styles' ) );
			if ( class_exists('WC_List_Grid') ) {
				$this->gridlist_init();
			}
		}	
	}
	public function on_woo_activation (){
		/* set product images dimensions */
		update_option( 'shop_catalog_image_size', $this->args['shop_catalog_image_size'] ); 
		update_option( 'shop_single_image_size', $this->args['shop_single_image_size'] ); 
		update_option( 'shop_thumbnail_image_size', $this->args['shop_thumbnail_image_size'] ); 
		/* set product images dimensions */
	}
	public function after_switch_theme (){
		/* set product images dimensions */
		update_option( 'shop_catalog_image_size', $this->args['shop_catalog_image_size'] ); 
		update_option( 'shop_single_image_size', $this->args['shop_single_image_size'] ); 
		update_option( 'shop_thumbnail_image_size', $this->args['shop_thumbnail_image_size'] ); 
		/* set product images dimensions */
	}
	public function woo_init (){
		/* loop */
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10, 0 ); 
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5, 0 ); 
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5, 0 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10, 0 );

		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'after_shop_loop_item_price_wrapper_open' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'after_shop_loop_item_price_wrapper_close' ), 20 );

		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'after_shop_loop_item_wrapper_open' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'after_shop_loop_item_wrapper_close' ), 20 );
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'shop_loop_item_content_wrapper_open' ), 1 );
		add_action( 'woocommerce_before_subcategory', array( $this, 'shop_loop_item_content_wrapper_open' ), 1 );
		add_action( 'woocommerce_shop_loop_item_categories', array( $this, 'shop_loop_item_info_wrapper_open' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'shop_loop_item_info_wrapper_close' ), 25 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'shop_loop_item_content_wrapper_close' ), 28 );
		add_action( 'woocommerce_after_subcategory', array( $this, 'shop_loop_item_content_wrapper_close' ), 28 );
		add_filter( 'woocommerce_layered_nav_link', array( $this, 'custom_woocommerce_layered_nav_get_color_filter' ), 30, 1 );
		add_filter( 'woocommerce_layered_nav_count', array( $this, 'custom_woocommerce_layered_nav_set_color_filter' ), 30, 1 );
		add_filter( 'loop_shop_per_page', array( $this, 'loop_products_per_page' ), 20 );
        add_filter( 'woocommerce_sale_flash', array( $this, 'custom_replace_sale_text' ) );
		/* \loop */

		add_filter( 'loop_shop_columns' , array( $this, 'cws_loop_shop_column' ), 10 );

		/* single */
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10, 0 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_divider_before_upsells' ), 14 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_divider_before_related' ), 19 );
		add_action( 'woocommerce_cart_collaterals', array( $this, 'divider' ), 1 );
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );


        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
        remove_action( 'woocommerce_review_before', 'woocommerce_review_display_gravatar', 10 );

        add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 1 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_header_start' ), 3 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_rating' ), 5 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_sale_banner' ), 13 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_header_finish' ), 15 );

        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_categories' ), 16 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_price' ), 17 );

        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_footer_start' ), 30 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_tags' ), 32 );
        add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 34 );
        add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_info_footer_finish' ), 38 );

        add_action( 'woocommerce_product_reviews_tab_title', array( $this, 'single_product_reviews_tab_counter' ) );
        add_action( 'woocommerce_review_before', array( $this, 'single_product_review_display_gravatar' ), 10 );
        add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_page_navigation' ), 18 );

        add_filter( 'woocommerce_product_review_comment_form_args', array( $this, 'custom_wc_review_form_defaults' ) );
		/* single */

		/* widgets */
		add_action( 'woocommerce_before_mini_cart', array( $this, 'minicart_wrapper_open' ) );
		add_action( 'woocommerce_after_mini_cart', array( $this, 'minicart_wrapper_close' ) );
		add_action( 'wp_ajax_woocommerce_remove_from_cart', array( $this, 'ajax_remove_from_cart' ), 1000 );
		add_action( 'wp_ajax_nopriv_woocommerce_remove_from_cart', array( $this, 'ajax_remove_from_cart' ), 1000 );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'header_add_to_cart_fragment' ) );
		add_filter( 'woocommerce_get_price_html', array( $this, 'product_widget_price_update' ), 100, 2 );
		/* \widgets */
		$this->set_img_dims();
		add_filter( 'woocommerce_breadcrumb_defaults', array( $this, 'change_breadcrumb_delimiter' ) );
	}

	public function gridlist_init (){
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
		add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 40 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'remove_excess_gridlist_actions' ), 40 );	
		add_action( 'wp', array( $this, 'remove_excess_gridlist_actions' ), 30 );
	}

	public function set_img_dims (){
		global $pagenow;
	 	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
			return;
		}
		if ( isset( $this->args['shop_catalog_image_size'] ) && !empty( $this->args['shop_catalog_image_size'] ) ){
			update_option( 'shop_catalog_image_size', $this->args['shop_catalog_image_size'] );		
		}
		if ( isset( $this->args['shop_single_image_size'] ) && !empty( $this->args['shop_single_image_size'] ) ){
			update_option( 'shop_single_image_size', $this->args['shop_single_image_size'] );		
		}
		if ( isset( $this->args['shop_thumbnail_image_size'] ) && !empty( $this->args['shop_thumbnail_image_size'] ) ){
			update_option( 'shop_thumbnail_image_size', $this->args['shop_thumbnail_image_size'] );		
		}
	}

	public function divider (){
		echo "<hr />";
	}

	public function custom_woocommerce_layered_nav_get_color_filter ($link) {
		preg_match('/[^=]\w+$/', $link, $match_color);
		$this->widget_args['color_link'] = $match_color[0];
		return $link;
	}

	public function cws_loop_shop_column(){
		global $cws_theme_funcs;
		if ($cws_theme_funcs){
			$woo_columns = $cws_theme_funcs->cws_get_option('woo_columns');			
		} else {
			$woo_columns = 3;
		}
		return (int)$woo_columns;
	}

	public function custom_woocommerce_layered_nav_set_color_filter ($span) {
		$span = str_replace('<span class="count">', '<span class="color-box" style="background:'.esc_attr($this->widget_args['color_link']).';"></span><span class="count">', $span);
		return $span;
	}

	static function get_wc_placeholder_img_src (){
		$image_link = wc_placeholder_img_src();
		$has_ext = preg_match( "#\.[^(\.)]*$#", $image_link, $matches );
		if ( $has_ext ){
			$ext = $has_ext ? $matches[0] : "";
			$wc_placeholder_img_name = "wc_placeholder_img";
			$wp_upload_dir = wp_upload_dir();
			$wp_upload_base_dir = isset( $wp_upload_dir['basedir'] ) ? $wp_upload_dir['basedir'] : "";
			$woo_upload_dir = trailingslashit( $wp_upload_base_dir ) . "woocommerce_uploads";
			$wc_placeholder_img_src = trailingslashit( $woo_upload_dir ) . "{$wc_placeholder_img_name}{$ext}";
			if ( !file_exists( $wc_placeholder_img_src ) ){
				$image_editor = wp_get_image_editor( $image_link );
				if ( ! is_wp_error( $image_editor ) ) {
					$image_editor->save( $wc_placeholder_img_src );
					return $wc_placeholder_img_src;
				}
			}
			else{
				return $wc_placeholder_img_src;
			}
		}
		return false;
	}

	public function change_breadcrumb_delimiter( $defaults ) {
		$defaults['delimiter'] = ' >> ';
		return $defaults;
	}

	/**/
	/* STYLES */
	/**/

	public function enqueue_style() {
			$is_rtl = is_rtl();
			if ( class_exists('WC_List_Grid') ) {		
				wp_register_style( 'woocommerce-gridlist', METAMAX_URI . '/woocommerce/css/woocommerce_gridlist.css',
                    array( 'grid-list-layout', 'grid-list-button' ) );
				wp_enqueue_style( 'woocommerce-gridlist' );
			}
			if ( $is_rtl ){
				wp_register_style( 'woocommerce-rtl', METAMAX_URI . '/woocommerce/css/woocommerce-rtl.css');
				if ( class_exists( 'woocommerce' ) ) {
					wp_enqueue_style( 'woocommerce-rtl' );
				}
				if ( class_exists('WC_List_Grid') ) {		
					wp_register_style( 'woocommerce-gridlist-rtl', METAMAX_URI . '/woocommerce/css/woocommerce_gridlist-rtl.css', array( 'grid-list-layout', 'grid-list-button', 'woocommerce_gridlist' ) );
					wp_enqueue_style( 'woocommerce-gridlist-rtl' );
				}				
			}
			$this->custom_styles();
	}
	public function custom_styles(){
		$product_thumb_dims = get_option( 'shop_single_image_size' );
		$product_thumb_width = isset( $product_thumb_dims['width'] ) ? $product_thumb_dims['width'] : $this->args['shop_single_image_size']['width'];

		ob_start();
		echo "
			.woo-product-post-media.post-single-post-media > .post-media-wrapper{
				width: {$product_thumb_width}px;
			}
		";

		if ( isset( $this->args['shop_thumbnail_image_spacings'] ) && !empty( $this->args['shop_thumbnail_image_spacings'] ) ){
			echo ".woo_product_post_thumbnail.post-single-post-thumbnail{";
			foreach ( $this->args['shop_thumbnail_image_spacings'] as $key => $value) {
				echo "padding-{$key}: {$value}px;";		
			}
			echo "}";
			echo ".woo-product-post-media.post-single-post-media .thumbnails{";
			foreach ( $this->args['shop_thumbnail_image_spacings'] as $key => $value) {
				echo "margin-{$key}: -{$value}px;";		
			}
			echo "}";
		}
		if ( isset( $this->args['shop_single_image_spacings'] ) && !empty( $this->args['shop_single_image_spacings'] ) ){
			echo ".woo-product-post-media-wrapper.post-single-post-media-wrapper > .pic:not(:only-child){";
			foreach ( $this->args['shop_single_image_spacings'] as $key => $value) {
				echo "margin-{$key}: {$value}px;";		
			}
			echo "}";
		}
		$custom_styles = ob_get_clean();
		if ( !empty( $custom_styles ) ){
			wp_add_inline_style( 'woocommerce', $custom_styles );
		}	
	}
	public function body_font_styles (){
		global $cws_theme_funcs;
		if($cws_theme_funcs){
			$font_options = $cws_theme_funcs->cws_get_option('body-font');
		}else{
			$font_options = array(
			    'font-family' => 'Lato',
			    'font-weight' => array('regular','italic','700','700italic'),
			    'font-sub' => array('latin'),
			    'font-type' => '',
			    'color' => '#747474',
			    'font-size' => '16px',
			    'line-height' => '35px',
			);
		}
		

		$font_family = $font_options['font-family'];
		$font_size = $font_options['font-size'];
		$line_height = $font_options['line-height'];
		$font_color = $font_options['color'];
		
		if ( class_exists( 'woocommerce' ) ) {
			ob_start();
			if ( !empty( $font_size ) ){
				echo "
				.widget .woocommerce-product-search .screen-reader-text:before,
				.woocommerce .cart_totals h2,
				.woocommerce-checkout h3
				{
					font-size: $font_size;
				}";
			}
			if ( !empty( $font_color ) ){
				echo "
				#top_panel_woo_minicart,
				.woocommerce .cart_totals h2,
				.woocommerce-checkout h3
				{
				color: $font_color;
				}";
			}
			if ( !empty( $font_family ) ){
				echo "
				.tipr_content,
				.woocommerce .cart_totals h2,
				.woocommerce-checkout h3
				{
					font-family: $font_family;
				}";
			}

			if ( !empty( $line_height ) ){
				echo "
				.woocommerce .cart_totals h2,
				.woocommerce-checkout h3
				{
					line-height: $line_height;
				}
				";
			}			

			$styles = ob_get_clean();
			echo sprintf("%s", $styles);
		}
	}
	public function header_font_styles (){
		global $cws_theme_funcs;
		if($cws_theme_funcs){
			$font_options = $cws_theme_funcs->cws_get_option( 'header-font' );
		}else{
			$font_options = array(
    			'font-family' => 'Montserrat',
			    'font-weight' => array('300','regular','500','600','700'),
			    'font-sub' => array('latin'),
			    'font-type' => '',
			    'color' => '#000000',
			    'font-size' => '27px',
			    'line-height' => '36px',
			);	
		}
		
		$font_family = $font_options['font-family'];
		$font_size = $font_options['font-size'];
		$line_height = $font_options['line-height'];
		$font_color = $font_options['color'];

		if ( class_exists( 'woocommerce' ) ) {
			ob_start();
			if ( !empty( $font_size ) ){
				echo "";
			}
			if ( !empty( $font_family ) ){
				echo "
				ul.products.list li.product .woo-product-post-title.posts_grid_post_title
				{
				font-family: $font_family;
				}";
			}
			$styles = ob_get_clean();
			echo sprintf("%s", $styles);
		}
	}
	/**/
	/* \STYLES */
	/**/

	/**/
	/* SCRIPTS */
	/**/
	public function enqueue_script() {
		wp_register_script( 'metamax-woo', METAMAX_URI . '/woocommerce/js/woocommerce.js', array(), '', 'footer' );
		if ( class_exists( 'woocommerce' ) ) {
			wp_enqueue_script( 'metamax-woo' );
		}
	}
	/**/
	/* SCRIPTS */
	/**/

	/**/
	/* LOOP */
	/**/
	public function loop_products_per_page() {
		global $cws_theme_funcs;
		if ($cws_theme_funcs){
			return (int) $cws_theme_funcs->cws_get_option( 'woo_num_products' );
		} else {
			return 10;
		}		
	}
    public function custom_replace_sale_text( $html ) {
        return str_replace( esc_html__( 'Sale!', 'metamax' ), esc_html__( 'Sale', 'metamax' ), $html );
    }
	public function after_shop_loop_item_price_wrapper_open (){
		echo "<div class='woo-product-post-price-wrapper'>";
	}

	public function after_shop_loop_item_price_wrapper_close (){
		echo "</div>";
	}
	public function after_shop_loop_item_wrapper_open (){
		echo "<div class='button_wrapper metamax_after_shop_loop_item_wrapper clearfix'>";
	}
	public function after_shop_loop_item_wrapper_close (){
		echo "</div>";
	}
	public function shop_loop_item_content_wrapper_open (){
		echo "<div class='metamax-shop-loop-item-content-wrapper'>";
	}
	public function shop_loop_item_content_wrapper_close (){
		echo "</div>";
	}
    public function shop_loop_item_info_wrapper_open (){
        echo "<div class='woo-product-post-info'>";
    }
    public function shop_loop_item_info_wrapper_close (){
        echo "</div>";
    }
	public function remove_excess_gridlist_actions (){
		$actions = array(
			'woocommerce_after_shop_loop_item'	=> array( 'gridlist_buttonwrap_open', 'gridlist_buttonwrap_close', 'gridlist_hr' )
		);
		global $wp_filter;
		foreach ( $actions as $hook => $functions ) {
			if ( array_key_exists( $hook, $wp_filter ) ){
				$reg_functions = &$wp_filter[$hook];
				foreach ( $reg_functions as $reg_id => $reg_atts ){
					foreach ( $reg_atts as $reg_method_id => $reg_method_atts) {
						$reg_method = $reg_method_atts['function'];
						$reg_method_name = "";
						if ( is_array( $reg_method ) && isset( $reg_method[1] ) ){
							$reg_method_name = $reg_method[1];
						}else{
							$reg_method_name = $reg_method;
						}
						if ( in_array( $reg_method_name, $functions ) ){
							// unset( $wp_filter[$hook][$reg_id][$reg_method_id] );
							if ( empty( $wp_filter[$hook][$reg_id] ) ) unset( $wp_filter[$hook][$reg_id] );
							break 1;
						}
					}
				}
			}
		}
	}
	/**/
	/* \LOOP */
	/**/

	/**/
	/* SINGLE */
	/**/
	public function single_product_divider_before_upsells (){
		global $product;
		$posts_per_page = get_option( 'posts_per_page' );
		$upsells = $product->get_upsell_ids( $posts_per_page );
		echo sizeof( $upsells ) ? "<hr />" : "";
	}
	public function single_product_divider_before_related (){
		global $product;
		if ( !isset( $product ) ) return false;
		$posts_per_page = get_option( 'posts_per_page' );
		if(function_exists('wc_get_related_products')){
			$related = wc_get_related_products( $posts_per_page );
		}else{
			$related = $product->get_related( $posts_per_page );
		}
		
		echo sizeof( $related ) ? "<hr />" : "";
	}
	public function related_products_args( $args ) {
		global $product;
		global $cws_theme_funcs;
		if($cws_theme_funcs){
			$ppp = $cws_theme_funcs->cws_get_option( 'woo_related_num_products' );
			$columns = $cws_theme_funcs->cws_get_option( 'woo_related_columns' );
		}else{
			$ppp = 10;
			$columns = 3;
		}

		$args['posts_per_page'] = $ppp;
		$args['columns'] = $columns;
		return $args;
	}

	public function single_product_info_header_start () {
	    echo '<div class="single-product-header">';
    }
    public function single_product_info_header_finish () {
        echo '</div>';
    }

    public function single_product_info_sale_banner () {

        echo '<div class="single-product-sale-banner-wrapper">';
        ob_start();
            woocommerce_show_product_loop_sale_flash();
        $sale = ob_get_clean();

        if ( !empty($sale) ) {
            echo "<div class='woo-banner-wrapper'>";
                echo "<div class='woo-banner sale-bunner'>";
                    echo "<div class='woo-banner-text'>";
                        echo sprintf("%s", $sale);
                    echo "</div>";
                echo "</div>";
            echo "</div>";
        }

        echo "</div>";
    }

    public function single_product_info_price () {
        global $product;

        echo "<div class='single-product-price'>";
        echo sprintf( '%s', $product->get_price_html() );
        echo "</div>";
    }

    public function single_product_info_rating () {
        global $product;

        if ( ! wc_review_ratings_enabled() ) {
            return;
        }

        $rating_count = $product->get_rating_count();
        $average      = $product->get_average_rating();

        if ( $rating_count > 0 ) {

            echo '<div class="woocommerce-product-rating">';
                echo wc_get_rating_html( $average, $rating_count );
            echo '</div>';

        }
    }

    public function single_product_info_categories () {
        global $product;

        echo '<div class="single-product-categories">';
            do_action( 'woocommerce_product_meta_start' );
            echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( '', '', count( $product->get_category_ids() ), 'metamax' ) . ' ', '</span>' );
            do_action( 'woocommerce_product_meta_end' );
        echo '</div>';
    }

    public function single_product_info_footer_start () {
        echo '<div class="single-product-footer">';
    }
    public function single_product_info_footer_finish () {
        echo '</div>';
    }

    public function single_product_info_tags () {
        global $product;

        do_action( 'woocommerce_product_meta_start' );
        echo wc_get_product_tag_list( $product->get_id(), '', '<div class="single-product-tags"><span class="tagged-as">' . _n( '', '', count( $product->get_tag_ids() ), 'metamax' ) . ' ', '</span></div>' );
        do_action( 'woocommerce_product_meta_end' );
    }

    public function single_product_page_navigation () {
	    ?>
        <!-- Product navigation -->
        <div class="single-product-links">
            <div class="nav-post-links clearfix">

                <?php
                $prev_post = get_previous_post();
                if ( !empty($prev_post) ) {
                    $prev_id = get_previous_post()->ID;
                    $prev_title = get_previous_post()->post_title;
                    if( !empty($prev_title) ){
                        $row_length = iconv_strlen($prev_title);
                        $prev_title = mb_substr( $prev_title, 0, 32 );
                        if( $row_length > 32 ){
                            $prev_title .= "...";
                        }
                    } else {
                        $prev_title = 'Prev Product (no title)';
                    }

                    $prev_link = get_permalink($prev_id);
                    $prev_img = get_the_post_thumbnail($prev_id, 'medium');
                    $prev_cats = strip_tags( wc_get_product_category_list( $prev_id, ', ', ' ', ' ' ) );
                }

                $next_post = get_next_post();
                if ( !empty($next_post) ) {
                    $next_id = get_next_post()->ID;
                    $next_title = get_next_post()->post_title;
                    if( !empty($next_title) ){
                        $row_length = iconv_strlen($next_title);
                        $next_title = mb_substr( $next_title, 0, 32 );
                        if( $row_length > 32 ){
                            $next_title .= "...";
                        }
                    } else {
                        $next_title = 'Next Product (no title)';
                    }
                    $next_link = get_permalink($next_id);
                    $next_img = get_the_post_thumbnail($next_id, 'medium');
                    $next_cats = strip_tags( wc_get_product_category_list( $next_id, ', ', ' ', ' ' ) );
                }

                echo '<div class="current-post"></div>';
                echo '<div class="prev-post nav-post">';
                    if ( !empty($prev_post) ) {
                        echo '<a href="' . $prev_link . '" class="nav-post-link">';
                            echo '<span class="nav-post-text">' . esc_html__('Prev Product', 'metamax') . '</span>';
                            echo '<span class="nav-post-thumb">';
                                echo sprintf( '%s', $prev_img );
                            echo '</span>';
                            echo '<span class="nav-post-info">';
                                echo '<span class="nav-post-title">' . $prev_title . '</span>';
                                echo '<span class="nav-post-categories">' . $prev_cats . '</span>';
                            echo '</span>';
                        echo '</a>';
                    }
                echo '</div>';
                echo '<div class="next-post nav-post">';
                    if ( !empty($next_post) ) {
                        echo '<a href="' . $next_link . '" class="nav-post-link">';
                            echo '<span class="nav-post-text">' . esc_html__('Next Product', 'metamax') . '</span>';
                            echo '<span class="nav-post-info">';
                                echo '<span class="nav-post-title">' . $next_title . '</span>';
                                echo '<span class="nav-post-categories">' . $next_cats . '</span>';
                            echo '</span>';
                            echo '<span class="nav-post-thumb">';
                                echo sprintf( '%s', $next_img );
                            echo '</span>';
                        echo '</a>';
                    }
                echo '</div>';
                ?>

            </div>
        </div>

        <?php
    }

    public function single_product_reviews_tab_counter ($content) {
        $content = str_replace('(', '<span class="review-counter">', $content);
        $content = str_replace(')', '</span>', $content);
	    return $content;
    }

    public function single_product_review_display_gravatar ($comment) {
        echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '70' ), '' );
    }

    function custom_wc_review_form_defaults($defaults) {
        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $html_req = ( $req ? " required='required'" : '' );

        $defaults['fields']['author'] = '<p class="comment-form-author">' .
            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245" placeholder="' . esc_attr__('Name *', 'metamax') . '"' . $html_req . ' /></p>';
        $defaults['fields']['email'] = '<p class="comment-form-email">' .
            '<input id="email" name="email" type="text" value="'
            . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes" placeholder="' . esc_attr__('Email *', 'metamax') . '"' . $html_req  . ' /></p>';

        $defaults['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . esc_html__( 'Your rating:', 'metamax' ) . '</label><select name="rating" id="rating" required>
						<option value="">' . esc_html__( 'Rate&hellip;', 'metamax' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'metamax' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'metamax' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'metamax' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'metamax' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'metamax' ) . '</option>
					</select></p><p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="1" aria-required="true" placeholder="' . esc_attr__('Your review', 'metamax') . '"></textarea></p>';

        return $defaults;
    }
	/**/
	/* \SINGLE */
	/**/

	/**/
	/* WIDGETS */
	/**/
	public function ajax_remove_from_cart() {
		global $woocommerce;

		$woocommerce->cart->set_quantity( $_POST['remove_item'], 0 );

		$ver = explode( '.', WC_VERSION );

		if ( $ver[1] == 1 && $ver[2] >= 2 ) :
			$wc_ajax = new WC_AJAX();
			$wc_ajax->get_refreshed_fragments();
		else :
			woocommerce_get_refreshed_fragments();
		endif;

		die();
	}
	public function header_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
			?>
				<span class='woo_mini_count'><?php echo ( (WC()->cart->cart_contents_count > 0) ? esc_html( WC()->cart->cart_contents_count ) : '' ) ?></span>
			<?php
			$fragments['.woo_mini_count'] = ob_get_clean();

			ob_start();
			woocommerce_mini_cart();
			$fragments['.cws_woo_minicart_wrapper'] = ob_get_clean();

			return $fragments;
	}
	public function minicart_wrapper_open (){
		echo "<div class='woo-mini-cart'>";
	}
	public function minicart_wrapper_close (){
		echo "</div>";
	}

    public function product_widget_price_update( $price, $product ) {
        return '<span class="cws-price">' . sprintf('%s', $price) . '</span>';
    }
	/**/
	/* \WIDGETS */
	/**/
}

/**/
/* Config and enable extension */
/**/
$metamax_woo_args = array(
	'shop_catalog_image_size'		=> array(
		'width'	=> 1000,
		'height'=> 1000,
		'crop'	=> 1
	),
	'shop_single_image_size'		=> array(
		'width'	=> 600,
		'height'=> 600,
		'crop'	=> 1
	),
	'shop_thumbnail_image_size'		=> array(
		'width'	=> 116,
		'height'=> 116,
		'crop'	=> 1
	),
	'shop_thumbnail_image_spacings' => array(
		'left'	=> 6,
		'right'	=> 5,
		'top'	=> 11
	),
	'shop_single_image_spacings'	 => array(
		'bottom'=> 10
	),
);
global $metamax_woo_ext;
$metamax_woo_ext = new Metamax_WooExt ( $metamax_woo_args );

/**/
/* \Config and enable extension */
/**/

/**/
/* Overriden functions */
/**/
function woocommerce_template_loop_product_title(){
	$title = get_the_title();
	$permalink = get_the_permalink();
	echo !empty( $title ) ? "<h3 class='post-title woo-product-post-title posts_grid_post_title'><a href='$permalink'>$title</a></h3>" : "";
}
function woocommerce_template_loop_product_thumbnail (){
	global $product;
	$pid = get_the_id();
	$post_thumb_exists = has_post_thumbnail( $pid );
	$permalink = esc_url( get_the_permalink() );
	$img_url = "";
	if ( $post_thumb_exists ){
		$img_obj = wp_get_attachment_image_src( get_post_thumbnail_id( $pid ), 'full' );
		$img_url = isset( $img_obj[0] ) ? esc_url( $img_obj[0] ) : '';
	}
	else{
		$wc_placeholder_img_src = Metamax_WooExt::get_wc_placeholder_img_src();
		$img_url = $wc_placeholder_img_src ? $wc_placeholder_img_src : $img_url;
	}
	if ( empty( $img_url ) ) return false;
	$lightbox_en = get_option( 'woocommerce_enable_lightbox' ) == 'yes' ? true : false;	
	ob_start();
	if ( $lightbox_en ) {	
	echo "<div class='links'>";
		woocommerce_template_loop_add_to_cart();
		if($post_thumb_exists){
			echo "<a href='$img_url' data-mode='top' data-tip='Quick view' class='tip fancy fa flaticon-search-icon-metamax'></a>";
		}
		echo "<a href='$permalink' data-mode='top' data-tip='Product page' class='tip fancy fa flaticon-right-arrow-icon-metamax'></a>";
	echo "</div>";
	}
	$lightbox = ob_get_clean();	
	$thumb_dims = get_option( 'shop_catalog_image_size' );
    $retina_thumb_url = '';

    if (function_exists('cws_get_img')) {
        $thumb_obj = cws_get_img( get_post_thumbnail_id( $pid ), $thumb_dims );
    } else {
        $thumb_obj = array(
            0 => wp_get_attachment_image_url(get_post_thumbnail_id( $pid ), $thumb_dims),
            1 => '',
            2 => '',
            3 => '',
        );
    }



	$thumb_url = isset( $thumb_obj[0] ) ? esc_url( $thumb_obj[0] ) : "";
	$thumb_obj[0] = empty($thumb_obj[0]) ? woocommerce_placeholder_img_src() : $thumb_obj[0];

	ob_start();
	woocommerce_show_product_loop_sale_flash();
	$sale = ob_get_clean();
	$sale_banner = !empty( $sale ) ? "<div class='woo-banner-wrapper'><div class='woo-banner sale-bunner'><div class='woo-banner-text'>$sale</div></div></div>" : "";
	echo "<div class='post-media woo-product-post-media posts-grid-post-media'>";
		echo !empty( $sale_banner ) ? $sale_banner : "";
		echo "<div class='pic'>";
			echo "<a href='$permalink'>";
			$thumb_path_hdpi = !empty($thumb_obj[3]) ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
			echo "<img $thumb_path_hdpi alt />";
			echo "</a>";
			echo sprintf("%s", $lightbox);
		echo "</div>";

		echo "<div class='woo-products-media-side-links'>";
					do_action( 'woocommerce_after_shop_loop_item' );
		echo "</div>";
        if ( wc_review_ratings_enabled() ) {
            $rating_count = $product->get_rating_count();
            $average      = $product->get_average_rating();

            if ( $rating_count > 0 ) {

                echo '<div class="woocommerce-product-rating">';
                echo wc_get_rating_html( $average, $rating_count );
                echo '</div>';

            }
        }


	echo '</div>';
}
/**/
/* \Overriden functions */
/**/

// Reposition WooCommerce breadcrumb
function metamax_remove_woo_breadcrumb() {
	remove_action(
	'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
}
add_action(
	'woocommerce_before_main_content', 'metamax_remove_woo_breadcrumb'
);

?>
