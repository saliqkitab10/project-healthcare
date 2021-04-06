<?php

// CWS Theme Metamax Standard Settings
//=========================================== DEFAULT METAMAX FUNCS ===========================================
class Metamax_Funcs_default{
	const THEME_BEFORE_CE_TITLE = '<div class="ce-title">';
	const THEME_AFTER_CE_TITLE = '</div>';
	const THEME_V_SEP = '<span class="v_sep"></span>';
	protected static $cws_theme_config;


	public function __construct(){
		require_once get_template_directory() . '/core/plugins.php';
		require_once(get_template_directory() . '/core/breadcrumbs.php');
		if (!is_admin()){
			add_action( 'wp_enqueue_scripts', array($this, 'cws_register_default') );
		}
		
		add_filter('embed_oembed_html', array($this, 'cws_oembed_wrapper'),10,3);
		$this->cws_assign_constants();
		add_action('after_setup_theme', array($this, 'cws_after_setup_theme') );

		define('CWS_WOO_ACTIVE', class_exists( 'woocommerce' ));

		if (CWS_WOO_ACTIVE) {
			add_action( 'wp_ajax_woocommerce_remove_from_cart',array( $this, 'cws_woo_ajax_remove_from_cart' ),1000 );
			add_action( 'wp_ajax_nopriv_woocommerce_remove_from_cart', array( $this, 'cws_woo_ajax_remove_from_cart' ),1000 );

			require_once( get_template_directory() . '/woocommerce/wooinit.php' ); // WooCommerce Shop ini file

			add_filter( 'woocommerce_add_to_cart_fragments', array($this, 'cws_woo_header_add_to_cart_fragment') );
			
			add_filter( 'woocommerce_output_related_products_args', array($this, 'cws_woo_related_products_args') );
			add_action( 'after_setup_theme', array($this, 'cws_theme_woo_setup') );
			add_filter( 'loop_shop_per_page', array( $this, 'cws_loop_products_per_page' ));	
		}
		add_action('widgets_init', array($this, 'cws_widgets_init') );
		add_filter('body_class', array($this, 'cws_layout_class') );
		add_filter('comment_form_defaults', array($this, 'cws_comment_form_defaults') );
		add_filter('get_search_form', array($this, 'cws_custom_search'));
        add_filter('wp_list_categories', array($this, 'cws_custom_categories_postcount_filter'));
        add_filter('get_archives_link', array($this, 'cws_custom_archive_postcount_filter'));

		//Filter all widgets output
		add_filter('dynamic_sidebar_params', array( $this, 'cws_filter_dynamic_sidebar_params' ), 9 );
		add_filter('widget_output', array($this, 'cws_filter_widgets'),10,4);
		//Filter all widgets output		
	}

	/* THE HEADER META */
	public function cws_header_meta() {
			?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php
	}
	/* END THE HEADER META */

	public function cws_custom_search ( $form ) {
		$form = "
		<form method='get' class='search-form' action=' ". home_url( '/' ) ." ' >
			<div class='search-wrapper'>
				<label><span class='screen-reader-text'>".esc_html__( 'Search for:', 'metamax' )."</span></label>
				<input type='text' placeholder='".esc_attr__( 'Search', 'metamax' )."' class='search-field' value='".
            esc_attr(apply_filters('the_search_query', get_search_query())) ."' name='s'/>
				<button type='submit' class='search-submit'>".esc_html__( 'Search', 'metamax' )."</button>
			</div>
		</form>";

		return $form;
	}

	public function cws_layout_class ($classes=array()) {
		array_push( $classes, 'cws-default' );
		return $classes;
	}

	public function cws_comment_form_defaults( $defaults ){
		$defaults['title_reply'] = esc_html__('Write a Comment', 'metamax' );
		$defaults['title_reply_before'] = '<h3 id="reply-title" class="h3 comment-reply-title ce-title">';
		$defaults['title_reply_after'] = '</h3>';
	  return $defaults;
	}	

	//Allows to filter the output of any WordPress widget
	public function cws_filter_dynamic_sidebar_params( $sidebar_params ) {
		if ( is_admin() ) {
			return $sidebar_params;
		}
		global $wp_registered_widgets;
		$current_widget_id = $sidebar_params[0]['widget_id'];
		$wp_registered_widgets[ $current_widget_id ]['original_callback'] = $wp_registered_widgets[ $current_widget_id ]['callback'];
		$wp_registered_widgets[ $current_widget_id ]['callback'] = array( $this, 'cws_display_widget' );
		return $sidebar_params;
	}

	public function cws_display_widget() {
		global $wp_registered_widgets;
		$original_callback_params = func_get_args();
		$widget_id         = $original_callback_params[0]['widget_id'];
		$original_callback = $wp_registered_widgets[ $widget_id ]['original_callback'];
		$wp_registered_widgets[ $widget_id ]['callback'] = $original_callback;
		$widget_id_base = $original_callback[0]->id_base;
		$sidebar_id     = $original_callback_params[0]['id'];
		if ( is_callable( $original_callback ) ) {
			ob_start();
			call_user_func_array( $original_callback, $original_callback_params );
			$widget_output = ob_get_clean();
			/**
			 * Filter the widget's output.
			 *
			 * @param string $widget_output  The widget's output.
			 * @param string $widget_id_base The widget's base ID.
			 * @param string $widget_id      The widget's full ID.
			 * @param string $sidebar_id     The current sidebar ID.
			 */
			echo apply_filters( 'widget_output', $widget_output, $widget_id_base, $widget_id, $sidebar_id );
		}
	}

    public function cws_filter_widgets( $widget_output, $widget_type, $widget_id, $sidebar_id ) {
        if ($widget_type == 'archives' || $widget_type == 'categories'){
            $widget_output = preg_replace('|<\/a>\s*\(|', '<span class="post-count">', $widget_output);
            $widget_output = preg_replace('|(\d)\)|', "$1</span></a>", $widget_output);
        }
//		$widget_output = preg_replace('|cws-widget|', 'cws-widget widget-'.esc_attr($widget_type), $widget_output);
        return $widget_output;
    }
    // --//Allows to filter the output of any WordPress widget

    public function cws_custom_categories_postcount_filter ($count) {
        $count = str_replace("</a> (", "<span class='post-count'>", $count);
        $count = str_replace("</a> <span class=\"count\">(", "<span class='post-count'>", $count);
        $count = str_replace(")", "</span></a>", $count);

        return $count;
    }
    public function cws_custom_archive_postcount_filter ($count) {
        $count = str_replace('</a>&nbsp;(', ' <span class="post-count">', $count);
        $count = str_replace(')', '</span></a>', $count);
        return $count;
    }
	// --//Allows to filter the output of any WordPress widget	

	public function cws_oembed_wrapper( $html, $url, $args ) {
		return !empty( $html ) ? "<div class='cws-oembed-wrapper'>$html</div>" : '';
	}
	
	// Check if WooCommerce is active
	public function cws_woo_ajax_remove_from_cart() {
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

	public function cws_woo_header_add_to_cart_fragment( $fragments ) {
		ob_start();
		?>
			<i class='woo-mini-count flaticon-shopcart-icon-metamax'><?php echo ((WC()->cart->cart_contents_count >
                    0) ?  '<span>' . WC()->cart->cart_contents_count .'</span>' : '') ?></i>
		<?php
		$fragments['.woo-mini-count'] = ob_get_clean();

		ob_start();
		woocommerce_mini_cart();
		$fragments['div.woo-mini-cart'] = ob_get_clean();
		return $fragments;
	}
	public function cws_woo_related_products_args( $args ) {
		$args['posts_per_page'] = 4; // 4 related products
		$args['columns'] = 3; // arranged in 2 columns
		return $args;
	}

	public function cws_get_date_parts () {
		$part_val = array();
		$perm_struct = get_option( 'permalink_structure' );
		if (!empty( $perm_struct )) {
			$part_val['addl_query_args'] = array(
				'year' => get_query_var( 'year' ),
				'monthnum' => get_query_var( 'monthnum' ),
				'day' => get_query_var( 'day' ),
			);
		} else {
			$merge_date = get_query_var( 'm' );
			$match = preg_match( '#(\d{4})?(\d{1,2})?(\d{1,2})?#', $merge_date, $matches );
			$part_val['addl_query_args'] = array(
				'year' => isset( $matches[1] ) ? $matches[1] : '',
				'monthnum' => isset( $matches[2] ) ? $matches[2] : '',
				'day' => isset( $matches[3] ) ? $matches[3] : '',
			);
		}
		return $part_val;
	}

	public function cws_pagination ( $paged=1, $max_paged=1, $style = 'paged', $pagination_text = 'Load More') {
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts	= explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$permalink_structure = get_option('permalink_structure');

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = $permalink_structure ? trailingslashit( $pagenum_link ) . '%_%' : trailingslashit( $pagenum_link ) . '?%_%';
		$pagenum_link = add_query_arg( $query_args, $pagenum_link );

		$format  = $permalink_structure && preg_match( '#^/*index.php#', $permalink_structure ) && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $permalink_structure ? user_trailingslashit( 'page/%#%', 'paged' ) : 'paged=%#%';
		?>
		<div class='pagination <?php if($style == 'load_more'){ echo "pagination-load-more";} ?> separated'>
			<div class='page-links'>
			<?php
			$pagination_args = array( 'base' => $pagenum_link,
				'format' => $format,
				'current' => $paged,
				'total' => $max_paged,
				"prev_text" => "",
				"next_text" => ($style == 'paged' ? "" : $pagination_text),
				"link_before" => '',
				"link_after" => '',
				"before" => '',
				"after" => '',
				"mid_size" => 2,
			);

			$pagination = paginate_links($pagination_args);
			echo sprintf("%s", $pagination);
			?>
			</div>
		</div>
		<?php
	}

	function cws_msg_box ( $atts, $content ) {
		extract( shortcode_atts( array(
			'type'					=> '',
			'title'					=> '',
			'text'					=> '',
			'is_closable'			=> '',
			'customize'				=> '',
			'icon_lib'				=> '',
			'custom_fill_color'		=> '#e6eaed',
			'custom_font_color'		=> "#707273",
			'el_class'				=> ''
		), $atts));
		$out = "";
		$type 			= esc_html( $type );
		$is_closable 	= (bool)$is_closable;
		$customize 		= (bool)$customize;
		$icon_lib 		= esc_attr( $icon_lib );
		$icon 			= function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
		$el_class 		= esc_attr( $el_class );
		$content 		= !empty( $text ) ? $text : $content;
		$section_id 	= uniqid( "cws_vc_shortcode_msg_box_" );
		ob_start();
		if ( $customize ){
			echo !empty( $custom_fill_color ) ? "background-color: $custom_fill_color;" : "";
			echo !empty( $custom_font_color ) ? "color: $custom_font_color;" : "";
		}
		$section_styles = ob_get_clean();
		$icon_class = "msg_icon";
		if ( $customize && !empty( $icon ) ){
			if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
				vc_icon_element_fonts_enqueue( $icon_lib );
			}
			$icon_class .= " $icon custom";
		}
		if ( !empty( $title ) || !empty( $content ) ){
			$out .= "<div id='$section_id' class='cws-msg-box-module cws_vc_shortcode_module" . ( !empty( $type ) ? "type-$type" : "" ) . ( $is_closable ? " closable" : "" ) . ( !empty( $el_class ) ? " $el_class" : "" ) . "'" . ( !empty( $section_styles ) ? " style='$section_styles'" : "" ) . ">";
				$out .= "<div class='icon-part'>";
					$out .= "<i class='$icon_class'></i>";
				$out .= "</div>";
				$out .= "<div class='content-part'>";
					$out .= !empty( $title ) ? "<div class='title'>$title</div>" : "";
					$out .= !empty( $content ) ? "<p>$content</p>" : "";
				$out .= "</div>";
				$out .= $is_closable ? "<a class='close-button'></a>" : "";
			$out .= "</div>";
		}
		return $out;
	}
	public function cws_print_search_form($message_title = '', $message = '') {
		ob_start();
			if( !empty($message_title) ) {
				echo '<div class="cws-msg-box-module type-info add-shadow">';
					echo '<div class="cws-msg-box-info">';
						echo '<div class="cws-msg-box-title">'.$message_title.'</div>';
						echo '<div class="cws-msg-box-desc">'.$message.'</div>';
					echo '</div>';
					echo '<div class="close-btn"></div>';
				echo '</div>';
			}
			echo "<div class='search-wrapper'>";
				get_search_form();
			echo "</div>";
		$sc_content = ob_get_clean();
		return $sc_content;
	}

	public function cws_styles_default() {
		$default_styles = '';
		ob_start();
		
		$default_styles .= ob_get_clean();

		wp_register_style( 'cws-default-inline-styles', false );
		wp_add_inline_style('cws-default-inline-styles', $default_styles);
		wp_enqueue_style( 'cws-default-inline-styles' );
	}
	public function cws_register_default() {
		//Defaults Google fonts
		$url = $query_args = '';

		$fonts_opts = array(
			array(
				'font-size' => '16px',
				'line-height' => '24px',
				'color' => '#142b5f',
				'font-family' => 'Rubik',
				'font-weight' => array( 'regular', 'italic', '300', '300italic', '500', '500italic', '700', '700italic' ),
				'font-sub' => array('latin'),
			),
			array(
				'font-size' => '60px',
				'line-height' => 'initial',
				'color' => '#1f5abc',
				'font-family' => 'Nunito',
				'font-weight' => array( 'regular', 'italic', '600', '600italic', '700', '700italic', '800', '800italic',
                    '900' ),
				'font-sub' => array('latin'),
			),
		);		

		if ( !empty( $fonts_opts ) ) {
			$fonts_urls = array( count( $fonts_opts ) );
			$subsets_arr = array();
			$base_url = "//fonts.googleapis.com/css";

			for ( $i = 0; $i < count( $fonts_opts ); $i++ ){
				$fonts_urls[$i] = $fonts_opts[$i]['font-family'];
				$fonts_urls[$i] .= !empty( $fonts_opts[$i]['font-weight'] ) ? ':' . implode( $fonts_opts[$i]['font-weight'], ',' ) : '';
				if(!empty($fonts_opts[$i]['font-sub'])){
					for ( $j = 0; $j < count( $fonts_opts[$i]['font-sub'] ); $j++ ){
						if ( !in_array( $fonts_opts[$i]['font-sub'][$j], $subsets_arr ) ){
							array_push( $subsets_arr, $fonts_opts[$i]['font-sub'][$j] );
						}
					}
				}
			}
			$query_args = array(
				'family'	=> urlencode( implode( $fonts_urls, '|' ) )
			);
			if ( !empty( $subsets_arr ) ) {
				$query_args['subset']	= urlencode( implode( $subsets_arr, ',' ) );
			}
			$url = add_query_arg( $query_args, $base_url );
		}
		wp_enqueue_style( 'cws-defauts-urls', $url );

		// Scripts
        wp_enqueue_script('jquery-easing', METAMAX_URI . '/js/jquery.easing.1.3.min.js', array('jquery'), '1.0', 'footer' );

		wp_enqueue_script('cws-scripts', METAMAX_URI . '/js/scripts.js', array(), '1.0', 'footer' );
		wp_enqueue_script('cws-default-scripts', METAMAX_URI . '/js/default.js', array(), '1.0', 'footer' );
		wp_enqueue_script('isotope', METAMAX_URI . '/js/isotope.pkgd.min.js', array(), '1.0', 'footer' );
		wp_enqueue_script('fancybox', METAMAX_URI . '/js/jquery.fancybox.js', array(), '1.0', 'footer' );
		wp_enqueue_script('select2-main', METAMAX_URI . '/js/select2.min.js', array(), '1.0', 'footer' );
		if ( is_singular() && comments_open() ) { 
	        wp_enqueue_script( 'comment-reply' ); 
	    }

		wp_add_inline_script('cws-scripts', '
			var sticky_menu_enable = false,'.
			'page_loader = false,'.
			'sticky_menu_enable = false,'.
			'sticky_menu_mode = false,'.
			'page_loader = false,'.
			'animation_curve_menu = "easeInOutQuad",'.
			'animation_curve_scrolltop = "easeInOutQuad",'.
			'animation_curve_speed = 400,'.
			'sticky_sidebars = false;'
		);

		// Style
		wp_enqueue_style( 'font-awesome', METAMAX_URI . '/fonts/font-awesome/font-awesome.css' );
		wp_enqueue_style( 'flaticon', METAMAX_URI . '/fonts/flaticon/flaticon.css' );
		wp_enqueue_style( 'cws-iconpack', METAMAX_URI . '/fonts/cws-iconpack/flaticon.css' );
		wp_enqueue_style( 'select2-main', METAMAX_URI . '/css/select2.css' );
		wp_enqueue_style( 'cws-main', METAMAX_URI . '/css/main.css' );
		wp_enqueue_style( 'fancybox', METAMAX_URI . '/css/jquery.fancybox.css' );
		wp_enqueue_style( 'cws-default-style', METAMAX_URI . '/css/default.css' );
		wp_enqueue_style( 'animate', METAMAX_URI . '/css/animate.css' );

		$this->cws_styles_default();
	}



	public function cws_after_setup_theme() {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(' widgets ');
		add_theme_support( 'title-tag' );

		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
		add_theme_support( 'post-formats', self::$cws_theme_config['post-formats'] );

		$nav_menus = self::$cws_theme_config['nav-menus'];
		if (function_exists('cws_core_cwsfw_startFramework')){
			register_nav_menus($nav_menus);
		} else {
			register_nav_menus( array('header-menu' => 'Navigation Menu'));
		}

		add_theme_support( 'woocommerce' );
		add_theme_support( 'custom-background', array('default-color' => '616262') );

		$user = wp_get_current_user();
		$user_nav_adv_options = get_user_option( 'managenav-menuscolumnshidden', get_current_user_id() );
		if ( is_array($user_nav_adv_options) ) {
			$css_key = array_search('css-classes', $user_nav_adv_options);
			if (false !== $css_key) {
				unset($user_nav_adv_options[$css_key]);
				update_user_option($user->ID, 'managenav-menuscolumnshidden', $user_nav_adv_options,	true);
			}
		}

		add_editor_style();
	}

	public function cws_render_default_sidebars($type = 'blog', $layout = 'double', $sidebar = 'both') {
		$out = '';
		$layout_class = '';
		$sb = array();

		if ($layout == 'single' && $sidebar == 'left'){
			$sb['sb1'] = $type.'_left';
		} elseif ($layout == 'single' && $sidebar == 'right') {
			$sb['sb2'] = $type.'_right';
		} elseif ($layout == 'double'  && $sidebar == 'both') {
			$sb['sb1'] = $type.'_left';
			$sb['sb2'] = $type.'_right';
		}

		$sb1_exist = !empty($sb['sb1']) && is_active_sidebar($sb['sb1']);
		$sb2_exist = !empty($sb['sb2']) && is_active_sidebar($sb['sb2']);
		$sb_double = $sb1_exist && $sb2_exist;

		$sb1_class = 'page-content sb_'.esc_attr($sidebar);

		$out .= '<div class="container">';
		if ( $sb1_exist ) {
			$out .= '<aside class="sb-left">';
			ob_start();
			dynamic_sidebar( $sb['sb1'] );
			$out .= ob_get_clean();
			$out .= '</aside>';
		}
		if ( $sb2_exist ){
			$out .= '<aside class="sb-right">';
			ob_start();
			dynamic_sidebar( $sb['sb2'] );
			$out .= ob_get_clean();
			$out .= '</aside>';
		}

		if ( $sb1_exist || $sb2_exist ){
			$layout_class = 'single-sidebar';
		} elseif ($layout == 'double' && $sb_double ) {
			$layout_class = 'double-sidebar';
		}

		return array(
			'layout_class' => $layout_class,
			'sb_class' => $sb1_class . ' ' . $layout_class,
			'content' => $out,
		);
	}
	
	private function cws_assign_constants() {
		self::$cws_theme_config = array(

			'alt_breadcrumbs' => array('yoast_breadcrumb' => array( '<nav class="bread-crumbs">', '</nav>', false)), // alternative breadcrumbs function and its arguments
			'post-formats' => array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ),
			'nav-menus' => array(
				'header-menu' => esc_html__( 'Navigation Menu','metamax' ),
				'sidebar-menu' => esc_html__( 'SidePanel Menu', 'metamax'),
				'topbar-menu' => esc_html__( 'TopBar Menu', 'metamax' ),
				'copyrights-menu' => esc_html__( 'Copyrights Menu', 'metamax' )
			),
			'widgets' => array(
				'CWS_Text',
				'CWS_Latest_Posts',
				'CWS_Testimonials',
				'CWS_Portfolio',
				'CWS_Twitter',
				'CWS_Contact',
				'CWS_About',
				'CWS_Categories',
				'CWS_Gallery',
				'CWS_Banner',
			),		
			'sideBar' => array(
				'Blog Right',
			),
			'category_colors' => array('567dbe', 'be5656', 'be9656', '62be56', 'be56b1', '56bebd'),
			'admin_pages' => array('widgets.php', 'edit-tags.php', 'edit.php', 'term.php', 'user-edit.php', 'profile.php', 'nav-menus.php'), // pages cwsfw should be initialized on
		);
	} // self::$cws_theme_config

	public function cws_widgets_init() {
		if (function_exists('register_sidebars')) {
			foreach (self::$cws_theme_config['sideBar'] as $sb) {
				register_sidebar( array(
					'name'          => sprintf(__('%s','metamax' ), $sb ),
					'id' => strtolower(preg_replace("/[^a-z0-9\-]+/i", "_", $sb)),
					'description'   => 'CWS Sidebar Area',
					'class'         => '',
					'before_widget' => '<div class="cws-widget %2$s">',
					'after_widget'  => '</div>',
					'before_title' => '<div class="widget-title"><div class="inherit-wt">',
					'after_title' => '</div></div>',
				) );
			}
		}
	}
	
	public function cws_get_special_post_formats() {
		return array( 'aside' );
	}
	
	public function cws_is_special_post_format() {
		global $post;
		$sp_post_formats = $this->cws_get_special_post_formats();
		if ( isset($post) ) {
			return in_array( get_post_format(), $sp_post_formats );
		} else{
			return false;
		}
	}

	private function cws_is_woo() {
		global $woocommerce;
		
		return !empty( $woocommerce ) ? is_woocommerce() || is_product_tag() || is_product_category() || is_account_page() || is_cart() || is_checkout() : false;
	}

	public function cws_is_blog () {
		global  $post;
		$posttype = get_post_type($post );
		return ( ((is_archive()) || (is_author()) || (is_category()) || (is_home()) || (is_single()) || (is_tag())) && ( $posttype == 'post')  ) ? true : false ;
	}


	public function cws_site_header(){
		ob_start();
		$page_title_section_atts = "";
		$show_breadcrumbs = true;
		$page_title_section_class = "page-title default-page-title";
		$page_title_section_atts .= !empty( $page_title_section_class ) ? " class='$page_title_section_class'" : "";

		$text['home']	 = esc_html__( 'Home', 'metamax' ); // text for the 'Home' link
		$text['category'] = esc_html__( 'Category "%s"', 'metamax' ); // text for a category page
		$text['search']   = esc_html__( 'Search for "%s"', 'metamax' ); // text for a search results page
		$text['taxonomy'] = esc_html__( 'Archive by %s "%s"', 'metamax' );
		$text['tag']	  = esc_html__( 'Posts Tagged "%s"', 'metamax' ); // text for a tag page
		$text['author']   = esc_html__( 'Articles Posted by %s', 'metamax' ); // text for an author page
		$text['404']	  = esc_html__( 'Error 404', 'metamax' ); // text for the 404 page
		$text['cart']	  = esc_html__( 'Cart', 'metamax' ); // text for the cart page
		$text['checkout']	= esc_html__( 'Checkout', 'metamax' ); // text for the checkout page

		$page_title = "";

		if ( is_404() ) {
			$page_title = esc_html__( '404 Page', 'metamax' );
		}
		else if ( is_search() ) {
			$page_title = esc_html__( 'Search', 'metamax' );
		} else if ( is_front_page() ) {
			$page_title = esc_html__( 'Home', 'metamax' );
		} else if ( is_category() ) {
			$cat = get_category( get_query_var( 'cat' ) );
			$cat_name = isset( $cat->name ) ? $cat->name : '';
			$page_title = sprintf( $text['category'], $cat_name );
		} else if ( is_tag() ) {
			$page_title = sprintf( $text['tag'], single_tag_title( '', false ) );
		} else if ( is_day() ) {
			echo sprintf( $link, get_year_link( get_the_time( 'Y' ) ), get_the_time( 'Y' ) ) . " ";
			echo sprintf( $link, get_month_link( get_the_time( 'Y' ),get_the_time( 'm' ) ), get_the_time( 'F' ) ) . " ";
			$page_title = get_the_time( 'd' );

		} else if ( is_month() ) {
			$page_title = get_the_time( 'F' );

		} else if ( is_year() ) {
			$page_title = get_the_time( 'Y' );

		} else if ( has_post_format() && ! is_singular() ) {
			$page_title = get_post_format_string( get_post_format() );
		} else if ( is_tax( array( 'cws_portfolio_cat', 'cws_staff_member_department', 'cws_staff_member_position' ) ) ) {
			$tax_slug = get_query_var( 'taxonomy' );
			$term_slug = get_query_var( $tax_slug );
			$tax_obj = get_taxonomy( $tax_slug );
			$term_obj = get_term_by( 'slug', $term_slug, $tax_slug );

			$singular_tax_label = isset( $tax_obj->labels ) && isset( $tax_obj->labels->singular_name ) ? $tax_obj->labels->singular_name : '';
			$term_name = isset( $term_obj->name ) ? $term_obj->name : '';
			$page_title = $singular_tax_label . ' ' . $term_name ;
		} else if ( is_archive() ) {
			$post_type = get_post_type();
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_name = isset( $post_type_obj->label ) ? $post_type_obj->label : '';
			$page_title = $post_type_name ;
		} else if ( $this->cws_is_woo() ) {
			$page_title = woocommerce_page_title( false );
		} else if (get_post_type() == 'cws_portfolio') {
			$portfolio_slug = 'portfolio';
			$post_type = get_post_type();
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_name = isset( $post_type_obj->labels->menu_name ) ? $post_type_obj->labels->menu_name : '';
			$page_title = !empty($portfolio_slug) ? $portfolio_slug : $post_type_name ;
		}else if (get_post_type() == 'cws_staff') {
			$stuff_slug = 'staff';
			$post_type = get_post_type();
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_name = isset( $post_type_obj->labels->menu_name ) ? $post_type_obj->labels->menu_name : '';
			$page_title = !empty($stuff_slug) ? $stuff_slug : $post_type_name ;
		}else {
			$blog_title = $this->cws_is_blog() ? get_the_title() : "";
			$page_title = (!is_page() && !empty($blog_title)) ? $blog_title : get_the_title();
		}
		$breadcrumbs = "";
		if ( $show_breadcrumbs ){
			ob_start();
			metamax_dimox_breadcrumbs();
			$breadcrumbs = ob_get_clean();

			$breadcrumbs = html_entity_decode($breadcrumbs);
		}

		$page_title = wp_kses( $page_title, array(
			"b"			=> array(),
			"strong"	=> array(),
			"mark"		=> array(),
			"br"		=> array(),
			"em"		=> array(),
			"sup"		=> array(),
			"sub"		=> array()
		));

		if ( !empty( $page_title ) || (!empty( $breadcrumbs ) && $show_breadcrumbs ) ){
			echo "<div class='title-box bg-page-header'>";
                echo "<section" . ( !empty( $page_title_section_atts ) ? $page_title_section_atts : "" ) . " data-top='37' data-bottom='36'>";
                    echo "<div class='container header_center'>";
                        echo !empty( $page_title ) ? "<div class='title'><h1>$page_title</h1></div>" : "";
                        echo (!empty( $breadcrumbs ) && $show_breadcrumbs) ? $breadcrumbs : "";
                    echo "</div>";
                echo "</section>";
			echo "</div>";
		}

		$page_title_content = ob_get_clean();
		if($page_title_content){
			echo sprintf("%s", $page_title_content);
		}
	}
	public function cws_theme_woo_setup(){
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );		
			add_theme_support( 'wc-product-gallery-slider' );		
	}
	public function cws_loop_products_per_page() {
		return 10;
	}
}
//=========================================== /DEFAULT METAMAX FUNCS ===========================================