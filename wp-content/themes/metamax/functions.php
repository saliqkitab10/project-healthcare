<?php

# CONSTANTS
define('METAMAX_URI', get_template_directory_uri());
define('METAMAX_THEME_DIR', get_template_directory());
defined('METAMAX_FIRST_COLOR') or define('METAMAX_FIRST_COLOR', '#1f5abc');
defined('METAMAX_SECOND_COLOR') or define('METAMAX_SECOND_COLOR', '#40a6ff');
defined('METAMAX_THIRD_COLOR') or define('METAMAX_THIRD_COLOR', '#1a397f');

# \CONSTANTS

# TEXT DOMAIN
load_theme_textdomain( 'metamax' , get_template_directory() .'/languages' );
# \TEXT DOMAIN

global $cws_theme_funcs;
global $metamax_theme_standard;
//Check if plugin active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once(get_template_directory() . '/core/cws_blog.php');
if (function_exists('cws_core_cwsfw_startFramework') && get_option('metamax')){
	$cws_theme_funcs = new Metamax_Funcs();
} else {
	require_once(get_template_directory() . '/core/cws_default.php');
	$metamax_theme_standard = new Metamax_Funcs_default();
}

// CWS PB settings
class Metamax_Funcs {
	protected static $height_to_width_ratio = 0.78;

	protected static $cws_theme_config;

	public static $options;

	protected static $flags = 0;

	public $templates;

	protected static $to_exists = false;

	protected static $blog_thumb_dims = array(
		'large' => array(
			'none' => array(1170, 659),
			'left' => array(870, 490),
			'right' => array(870, 490),
			'both' => array(570, 321),
			),
		'medium' => array(
			'none' => array(570, 321),
			'left' => array(570, 321),
			'right' => array(570, 321),
			'both' => array(570, 321),
			),
		'small' => array(
			'none' => array(370, 208),
			'left' => array(370, 208),
			'right' => array(370, 208),
			'both' => array(370, 208),
			),
		'checkerboard' => array(
			'none' => array(585, 208),
			'left' => array(415, 245),
			'right' => array(415, 245),
			'both' => array(570, 321),
			),
		'1' => array(
			'none' => array(1170, 659),
			'left' => array(870, 490),
			'right' => array(870, 490),
			'both' => array(570, 321),
			),
		'2' => array(
			'none' => array(570, 321),
			'left' => array(420, 237),
			'right' => array(420, 237),
			'both' => array(270, 152),
			),
		'3' => array(
			'none' => array(370, 208),
			'left' => array(270, 152),
			'right' => array(270, 152),
			'both' => array(270, 152),
			),
		'4' => array(
			'none' => array(270, 152),
			'left' => array(270, 152),
			'right' => array(270, 152),
			'both' => array(270, 152),
			),
	);

	const THEME_BEFORE_CE_TITLE = '<div class="ce-title">';
	const THEME_AFTER_CE_TITLE = '</div>';
	const THEME_V_SEP = '<span class="v_sep"></span>';

	public function __construct() {
		$this->header = array(
			'drop_zone_start' => '',
			'drop_zone_end' => '</div><!-- /header-zone -->',
			'before_header' => '',
			'top_bar_box' => '',
			'logo_box' => '',
			'menu_box' => '',
			'title_box' => '',
			'after_header' => '',
		);

		// Check if JS_Composer is active
		if (class_exists('Vc_Manager')) {
			$vc_man = Vc_Manager::getInstance();
			$vc_man->disableUpdater(true);
			if (!isset($_COOKIE['vchideactivationmsg_vc11'])) {
				setcookie('vchideactivationmsg_vc11', WPB_VC_VERSION);
			}
		}
		if (class_exists('Vc_Manager')){
			require_once( get_template_directory() . '/vc/cws_vc_config.php' ); // JS_Composer Theme config file
		}

		global $wpdb;
		$to_len = (int)$wpdb->get_var( sprintf('SELECT LENGTH(option_value) FROM '.$wpdb->prefix.'options WHERE option_name = "%s"', 'metamax') );
		self::$to_exists = $to_len > 0;

		$this->cws_assign_constants();
		$this->cws_init();
		$this->cws_customizer_init();
		
	}
	private function cws_read_options() {
		global $wp_query;
		$pid = get_the_id();

		$theme_options = get_option('metamax');
		if (empty($theme_options)) return;

		$besides_ooptions = is_search();
		if ($pid && ! $besides_ooptions) {
			$meta = $this->cws_get_post_meta($pid);
			if (!empty($meta)) {
				$meta = $meta[0];
				foreach ($theme_options as $key => $value) {
					if (!isset($meta[$key])) {
						$meta[$key] = $value;
					}
				}
			} else {
				$meta = $theme_options;
			}
			self::$options = $meta;
		} else {
			self::$options = $theme_options;
		}
		
	}
	private function cws_assign_constants() {
		self::$cws_theme_config = array(
			'actions' => array(
				'cws_is_flaticon' => '',
				'cws_is_cwsfi' => '',
				),
			'gfonts' => array('body', 'menu', 'header'), // body-font etc from theme options
			'def_char_number' => 155, // cws_blog_get_chars_count
			'char_counts' => array( // keys are columns
				array(
					'double' => 130,
					'single' => 200,
					'' 		 => 300
				), // empty dummy array
				array(
					'double' => 130,
					'single' => 200,
					'' 		 => 300,
				),
				array(
					'double' => 120,
					'single' => 140,
					''		 => 150,
				),
				array(
					'double' => 60,
					'single' => 80,
					''		 => 90,
				),
				array(
					'double' => 50,
					'single' => 70,
					''		 => 100,
				),
			),
			'strings' => array(
				'home' => esc_html__( 'Home','metamax'), // text for the 'Home' link
				'category' => esc_html__( 'Category "%s"','metamax' ), // text for a category page
				'search' => esc_html__( 'Search for ','metamax' ).(isset($_GET['s']) ? $_GET['s'] : ""), // text for a search results page
				'taxonomy' => esc_html__( 'Archive by %s "%s"', 'metamax'),
				'tag'	=> esc_html__( 'Posts Tagged "%s"','metamax' ), // text for a tag page
				'author' => esc_html__( 'Articles Posted by %s','metamax' ), // text for an author page
				'404' => esc_html__( 'Error 404','metamax' ),
				'cart' => esc_html__( 'Cart','metamax' ),
				'checkout' => esc_html__( 'Checkout','metamax' ),
			),
			'alt_breadcrumbs' => array('yoast_breadcrumb' => array( '<nav class="bread-crumbs">', '</nav>', false)), // alternative breadcrumbs function and its arguments
			'post-formats' => array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ),
			'nav-menus' => array(
				'header-menu' => esc_html__( 'Navigation Menu','metamax' ),
				'sidebar-menu' => esc_html__( 'SidePanel Menu', 'metamax'),
				'topbar-menu' => esc_html__( 'TopBar Menu', 'metamax' ),
				'copyrights-menu' => esc_html__( 'Copyrights Menu', 'metamax' )
			),
			'category_colors' => array('567dbe', 'be5656', 'be9656', '62be56', 'be56b1', '56bebd'),
			'admin_pages' => array('widgets.php', 'edit-tags.php', 'edit.php', 'term.php', 'user-edit.php', 'profile.php', 'nav-menus.php'), // pages cwsfw should be initialized on
		);
	}

	public function cws_get_theme_config($name) {
		if (isset(self::$cws_theme_config[$name])) {
			return self::$cws_theme_config[$name];
		}
		return null;
	}

	public function cws_customizer_init() {
		if ( is_customize_preview() ) {
			if ( isset( $_POST['wp_customize'] ) && $_POST['wp_customize'] == "on" ) {
				if (strlen($_POST['customized']) > 10) {
					global $cwsfw_settings;
					global $cwsfw_mb_settings;
					$post_values = json_decode( stripslashes_deep( $_POST['customized'] ), true );
					if (isset($post_values['cwsfw_settings'])) {
						$new_options = $post_values['cwsfw_settings'];
						$current_options = get_option('metamax');
						foreach ($new_options as $key => $value) {

							if (is_array($value)){
								if (!isset($current_options[$key])) {
									$current_options[$key] = array();
								}
								$value = array_merge($current_options[$key], $value );
							}
							$cwsfw_settings[$key] = $value;
						}
					}
					if (isset($post_values['cwsfw_mb_settings'])) {
						$cwsfw_mb_settings = $post_values['cwsfw_mb_settings'];
						$this->cws_meta_vars();
					}
				}
			}
		}
	}
	/* Woo Related functions */
	public function cws_getWooMiniCart() {
		ob_start();
		if ( class_exists( 'woocommerce' ) ) {	woocommerce_mini_cart(); }
		return ob_get_clean();
	}

	public function cws_getWooMiniIcon() {
		ob_start();
		if ( class_exists( 'woocommerce' ) ) { ?>
			<a class="woo-icon" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart','metamax' ); ?>"><i class='woo-mini-count flaticon-shopcart-icon'><?php echo ((WC()->cart->cart_contents_count > 0) ?  '<span>' . esc_html( WC()->cart->cart_contents_count ) .'</span>' : '') ?></i></a>
		<?php
		}
		return ob_get_clean();
	}

	/* /Woo Related functions */

	/* Some useful functions */
	public function cws_get_option($name) {
		// !!! this must be in superclass
		$ret = null;
		if (is_customize_preview()) {
			global $cwsfw_settings;
			if (isset($cwsfw_settings[$name])) {
				$ret = $cwsfw_settings[$name];
				if (is_array($ret)) {
				$theme_options = get_option( 'metamax' );
					if (isset($theme_options[$name])) {
						$to = $theme_options[$name];
							foreach ($ret as $key => $value) {
								$to[$key] = $value;
							}
						$ret = $to;
					}
				}
				return $ret;
			}
		}
		$theme_options = get_option( 'metamax' );
		$ret = isset($theme_options[$name]) ? $theme_options[$name] : null;
		$ret = stripslashes_deep( $ret );
		return $ret;
	}

	public function cws_get_meta_option($name = '', $check_first_key = false) {
		$value = isset(self::$options[$name]) ? self::$options[$name] : null;
		while (is_string($value) && '{' === substr($value, 0, 1)) {
			$g_name = substr($value, 1, -1);
			$value = isset(self::$options[$g_name]) ? self::$options[$g_name] : null;
		}
		if ($check_first_key && is_array($value) && !empty($value)) {
			// it's better to set $check_first_key specifically when there's a chance
			// like in case of sidebars processing
			// check if need to replace value with theme option array
			reset($value);
			$first_key = key($value);
			$val = $value[$first_key];
			if (is_string($val) && '{' === substr($val, 0, 1)) {
				$g_name = substr($val, 1, -1);
				$value = isset(self::$options[$g_name]) ? self::$options[$g_name] : null;
			}
		}
		return $value;
	}

	public function cws_get_post_meta($pid, $key = 'cws_mb_post') {
		$ret = get_post_meta($pid, $key);
		if (!empty($ret[0])) {
			$ret = $ret[0];
		}
		if (is_customize_preview()) {
			global $cwsfw_settings;
			global $cwsfw_mb_settings;
			if(!empty($cwsfw_settings)){
				$ret = array_merge($ret, $cwsfw_settings);
			}
			if (!empty($cwsfw_mb_settings) && !empty($ret)) {
				$ret = array_merge($ret, $cwsfw_mb_settings);
			} else if (!empty($cwsfw_mb_settings) && empty($ret)) {
				$ret = $cwsfw_mb_settings;
			}
		}

		$ret = array($ret);

		return $ret;
	}

	// !!! this must be in superclass
	public function cws_echo_ne($condition, $str, $str2 = '') {	echo !empty($condition) ? $str : $str2; }

	// !!! this must be in superclass
	public function cws_echo_if($condition, $str, $str2 = '') {if($condition){echo sprintf("%s", $str);}else{echo sprintf("%s", $str2);}
	}

	public function cws_print_if($condition, $str, $str2 = '') { return $condition ? $str : $str2; }

	public function cws_print_ne($condition, $str, $str2 = '') { return !empty($condition) ? $str : $str2; }

	public function cws_print_search_form($message_title = '', $message = '') {
		ob_start();
			if( !empty($message_title) ) {
				echo "<h3>{$message_title}</h3><p>{$message}</p>";
			}
			get_search_form();
		$sc_content = ob_get_clean();
		return $sc_content;
	}

	public function cws_clean_search_form() {
		ob_start();

		get_search_form();

		$output = ob_get_clean();

		return $output;
	}

	/* END of Some useful functions */

	public function cws_render_sidebars($pid) {
		// !!! this must be in superclass
		$out = '';
		$sb = $this->cws_get_sidebars( $pid );

		$layout_class = $sb && !empty($sb['layout_class']) && $sb['layout_class'] != 'none' ? $sb['layout_class'].'-sidebar' : '';
		$sb1_class = $sb && isset($sb['layout']) && $sb['layout'] == 'right' ? 'sb-right' : 'sb-left';

		$sbl = $sb['sbl'];
		if ( $sbl ){
			$out .= '<div class="container">';
			if ( !empty($sb['sb1']) ) {
				$out .= sprintf('<aside class="%s">', sanitize_html_class($sb1_class));
				ob_start();
				dynamic_sidebar( $sb['sb1'] );
				$out .= ob_get_clean();
				$out .= '</aside>';
			}
			if ( !empty($sb['sb2']) ){
				$out .= '<aside class="sb-right">';
				ob_start();
				dynamic_sidebar( $sb['sb2'] );
				$out .= ob_get_clean();
				$out .= '</aside>';
			}
		}
		return array(
			'layout_class' => $layout_class,
			'sb_class' => $sb1_class,
			'content' => $out,
		);
	}

	private function cws_is_woo() {
		global $woocommerce;

		return !empty( $woocommerce ) ? is_woocommerce() || is_product_tag() || is_product_category() || is_account_page() || is_cart() || is_checkout() : false;
	}

	public function cws_get_sidebars( $p_id = null ) { /*!*/
		$page_type = 'page';
		$sb = null;

		if( $p_id ){
			$post_type = get_post_type($p_id);
		} else if( is_archive() ) {
			$post_type = get_post_type();
		}

		if( ($p_id && !is_home()) || is_archive() ) {
			switch ($post_type) {
				case 'page':
					$page_type = 'page';
					break;
				case 'post':
				case 'attachment':
					$page_type = 'post';
					break;
				case 'cws_portfolio':
					$page_type = 'portfolio';
					break;
				case 'cws_staff':
					$page_type = 'staff';
					break;
			}
		}


		if (is_front_page()) {
			//Is is_home() works if selected "latest posts" in Reading
			/* default home page have no ID */
			$page_type = 'home';
		}
		if (is_category() && is_archive()) {
			$page_type = 'blog';
		}

		if (!$sb) {
			$sb = $this->cws_get_meta_option("{$page_type}_sidebars", true);
		}

		$ret = $sb;
		$sb_enabled = isset($sb['layout']) && $sb['layout'] != 'none';
		$sbl = 0;
		if ($sb_enabled) {
			$sbl = (int)!empty($sb['sb1']) | ((int)!empty($sb['sb2'])*2);
		}
		$class = '';
		switch ($sbl) {
			case 1:
			case 2:
				$class = 'single';
				break;
			case 3:
				$class = 'double';
				break;
		}

		$ret['layout_class'] = $class;
		$ret['sbl'] = $sbl;
		return $ret;
	}

	public function cws_enqueue_script(){
	    wp_enqueue_script('slick-carousel', get_template_directory_uri() .'/js/slick.min.js', array(), '1.0', 'footer');
		wp_register_script('pie-chart', get_template_directory_uri() .'/js/jquery.pie_chart.js', array(), '1.0', 'footer');
		wp_register_script('particles', get_template_directory_uri() .'/js/particles.min.js', array(), '1.0', 'footer');
		wp_register_script('infinite-carousel', get_template_directory_uri() .'/js/jquery.simplemarquee.js', array(), '1.0', 'footer');
		wp_register_script('flipclock', get_template_directory_uri() .'/js/flipclock.min.js', array(), '1.0', 'footer');
		wp_register_script('isotope', get_template_directory_uri() .'/js/isotope.pkgd.min.js', array(), '1.0', 'footer');
		wp_register_script('odometer', get_template_directory_uri() .'/js/odometer.js', array(), '1.0', 'footer');
		wp_register_script('wow', get_template_directory_uri() .'/js/wow.min.js', array(), '1.0', 'footer');
		wp_register_script('parallax', get_template_directory_uri() .'/js/parallax.js', array(), '1.0', 'footer');
		wp_register_script('vimeo', get_template_directory_uri() .'/js/jquery.vimeo.api.min.js', array(), '1.0', 'footer');
		wp_register_script('skrollr', get_template_directory_uri() .'/js/skrollr.min.js', array(), '1.0', 'footer');
		wp_register_script('modernizr', get_template_directory_uri() .'/js/modernizr.js', array(), '1.0', 'footer');
		wp_register_script('yt-player-api', 'https://www.youtube.com/player_api', array(), '1.0', 'footer');

		wp_register_script('fancybox', get_template_directory_uri() .'/js/jquery.fancybox.js', array(), '1.0', 'footer');
		wp_register_script('select2-init', get_template_directory_uri() .'/js/select2.min.js', array(), '1.0', 'footer');
		wp_enqueue_script('cws-scripts', get_template_directory_uri() .'/js/scripts.js', array(), '1.0', 'footer');
		if ($this->cws_get_option('sticky_sidebars') == '1') {
		    wp_enqueue_script('fixed-sidebars', get_template_directory_uri() .'/js/sticky_sidebar.js', array(), '1.0', 'footer');
		}
		wp_register_script('tweenmax', get_template_directory_uri() .'/js/tweenmax.min.js', array(), '1.0', 'footer');
		wp_enqueue_script('jquery-easing', get_template_directory_uri() .'/js/jquery.easing.1.3.min.js', array(), '1.0', 'footer');

		wp_enqueue_style( 'ws-render-fonts-urls', $this->cws_render_fonts_url() );
		if ( is_singular() && comments_open()  && (get_option('thread_comments') == '1') ) {
	        wp_enqueue_script( 'comment-reply' );
	    }
	}

	public function cws_enqueue_styles(){
	    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/fonts/font-awesome/font-awesome.css', array(), '1.0');
		wp_enqueue_style('cwsfi', null, array(), '1.0');
		wp_enqueue_style('fancybox', get_template_directory_uri() . '/css/jquery.fancybox.css', array(), '1.0');
		wp_enqueue_style('select2-init', get_template_directory_uri() . '/css/select2.css', array(), '1.0');
		wp_enqueue_style('animate', get_template_directory_uri() . '/css/animate.css', array(), '1.0');
		$this->cws_theme_enqueue_styles();
		$this->cws_add_style();
	}

	private function cws_render_fonts_url() {
		$url = $query_args = '';
		$gfonts = self::$cws_theme_config['gfonts'];
		$fonts_opts = array();
		foreach ($gfonts as $value) {
			$font_value = $this->cws_get_option( $value.'-font' );
			if (isset($font_value)){
				$fonts_opts[] = $font_value;
			}
		}

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
		return $url;
	}

	public function cws_wp_title_filter ( $title_text ) {
		$site_name = get_bloginfo( 'name' );
		return is_home() ? $site_name . " | " . get_bloginfo( 'description' ) : $site_name;
	}

	public function cws_custom_nav_menu_item_title ( $title, $item, $args, $depth ) {
		$title =
			(!empty($item->icon) ? "<i class='".esc_attr($item->icon)."'></i> " : '') . //Custom menu fields (icon)
			$title .
			($item->tag && !empty($item->tag_text)  ? "<span class='tag_label' style='color:".esc_attr($item->tag_font_color).";background-color:".esc_attr($item->tag_bg_color).";'>".esc_html($item->tag_text)."</span> " : ''); //Custom menu fields (label)

		return $title;
	}

	# UPDATE THEME
	public function cws_check_for_update($transient) {
		if (empty($transient->checked)) { return $transient; }

		$theme_pc = trim($this->cws_get_option('_theme_purchase_code'));
		if (empty($theme_pc)) {
			add_action( 'admin_notices', array($this, 'cws_an_purchase_code') );
		}

		$result = wp_remote_get('http://up.cwsthemes.com/products-updater.php?pc=' . $theme_pc . '&tname=' . 'metamax');
		if (!is_wp_error( $result ) ) {
			if (200 == $result['response']['code'] && 0 != strlen($result['body']) ) {
				$resp = json_decode($result['body'], true);
				$h = isset( $resp['h'] ) ? (float) $resp['h'] : 0;
				$theme = wp_get_theme(get_template());
				if (isset($resp['new_version']) && version_compare( $theme->get('Version'), $resp['new_version'], '<' ) ) {
					$transient->response['metamax'] = $resp;
				}
				// request and save plugins info
				$opt_res = wp_remote_get('http://up.cwsthemes.com/plugins/update.php', array( 'timeout' => 1));
				update_option('cws_plugin_ver', array('data' => $opt_res['body'], 'lasttime' => date('U')));
				// end of request and save plugins info
			} else{
				unset($transient->response['metamax']);
			}
		}
		return $transient;
	}

	// an stands for admin notice
	public function cws_an_purchase_code() {
		$cws_theme = wp_get_theme();
		echo "<div class='update-nag'>" . $cws_theme->get('Name') . esc_html__(' theme notice: Please insert your Item Purchase Code in Theme Options to get the latest theme updates!', 'metamax') .'</div>';
	}
	# \UPDATE THEME

	private function cws_init() {
		global $wp_filesystem;
		if(empty( $wp_filesystem )) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		require_once get_template_directory() . '/core/plugins.php';

		// metaboxes
		include_once(get_template_directory() . '/core/breadcrumbs.php');
		if (function_exists('cws_core_cwsfw_fillMbAttributes')) {
			load_template( trailingslashit( get_template_directory() ) . '/core/scg.php');
			new Metamax_SCG();
		}

		set_transient('update_themes', 48*3600);

		add_action('after_setup_theme', array($this, 'cws_after_setup_theme') );
		add_action( 'init', array($this, 'cws_add_excerpts_to_pages') );

		// Disable vc animate.css styles
		add_action('wp_enqueue_scripts','cws_vc_animations_disable');

		add_filter('nav_menu_item_title', array($this, 'cws_custom_nav_menu_item_title'), 10, 4 ); //Custom menu fields
		add_filter('wp_title', array($this, 'cws_wp_title_filter') );
		add_filter('pre_set_site_transient_update_themes', array($this, 'cws_check_for_update') );
		add_action('admin_enqueue_scripts', array($this, 'cws_admin_init' ) );
		add_filter('the_content', array($this, 'cws_fix_shortcodes_autop') );

		add_filter( 'comment_form_defaults', array($this, 'cws_comment_form_defaults') );

		add_action('wp_enqueue_scripts', array($this, 'cws_enqueue_script') );

		add_action('wp_enqueue_scripts', array($this, 'cws_enqueue_styles') );

		add_action('wp_enqueue_scripts', array($this, 'cws_enqueue_theme_stylesheet'), 999 );
		add_action('widgets_init', array($this, 'cws_widgets_init') );
		add_filter('body_class', array($this, 'cws_layout_class') );

		add_action('menu_font_hook', array($this, 'cws_menu_font_action') );
		add_action('header_font_hook', array($this, 'cws_header_font_action') );
		add_action('body_font_hook', array($this, 'cws_body_font_action') );

		add_action('theme_color_hook', array($this, 'cws_theme_color_action'), 1);
		add_action('theme_color_hook', array($this, 'cws_theme_rgba_color'), 1);

		//Custom block styles
		add_action('theme_color_hook', array($this, 'cws_custom_header_styles_action'), 2);
		add_action('theme_color_hook', array($this, 'cws_custom_logo_box_styles_action'), 3);
		add_action('theme_color_hook', array($this, 'cws_custom_menu_box_styles_action'), 4);
		add_action('theme_color_hook', array($this, 'cws_custom_sticky_menu_styles_action'), 5);
		add_action('theme_color_hook', array($this, 'cws_custom_page_title_styles_action'), 6);
		add_action('theme_color_hook', array($this, 'cws_custom_top_bar_styles_action'), 7);
		add_action('theme_color_hook', array($this, 'cws_custom_side_panel_styles_action'), 8);
		add_action('theme_color_hook', array($this, 'cws_custom_footer_styles_action'), 9);
		add_action('theme_color_hook', array($this, 'cws_custom_styles_action'), 10);
		add_action('theme_color_hook', array($this, 'cws_custom_boxed_layout_styles_action'), 11);
		//Custom block styles

		add_action('theme_gradient_hook', array($this, 'cws_theme_gradient_action') );
		add_filter('body_class', array($this, 'cws_gradients_body_class') );
		add_filter('cws_dbl_to_sngl_quotes', array($this, 'cws_dbl_to_sngl_quotes') );

		add_action('wp_enqueue_scripts', array($this, 'cws_js_vars_init') );
		add_action('wp', array($this, 'cws_meta_vars') );
		add_action('template_redirect', array($this, 'cws_ajax_redirect') );
		add_filter('excerpt_length', array($this, 'cws_custom_excerpt_length'), 999 );
		add_action('wp_enqueue_scripts', array($this, 'cws_ajaxurl') );
		add_filter('embed_oembed_html', array($this, 'cws_oembed_wrapper'),10,3);
		add_filter('body_class', array($this, 'cws_loading_body_class') );
		add_filter('body_class', array($this, 'cws_boxed_body') );
		add_filter('wp_list_categories', array($this, 'cws_custom_categories_postcount_filter'));

		//Filter all widgets output
		add_filter('dynamic_sidebar_params', array( $this, 'cws_filter_dynamic_sidebar_params' ), 9 );
		add_filter('widget_output', array($this, 'cws_filter_widgets'),10,4);
		//Filter all widgets output

		add_filter('post_gallery', array($this, 'cws_custom_gallery'), 10, 2);
		add_filter('get_search_form', array($this, 'cws_custom_search'));

		// Add custom menu fields to menu
		add_filter('wp_setup_nav_menu_item', array($this, 'cws_add_custom_nav_fields'));
		// Save menu custom fields
		add_filter('wp_update_nav_menu_item', array($this, 'cws_update_custom_nav_fields'), 10, 3);
		// Edit menu walker
		add_filter('wp_edit_nav_menu_walker', array($this, 'cws_edit_walker'), 10, 2);

		// Add inline style
		add_filter('cws_print_single_class', array($this, 'cws_print_single_class'));

		/* tinymce related */
		add_filter( 'tiny_mce_before_init', array($this, 'cws_tiny_mce_before_init') );
		add_filter( 'mce_buttons_2', array($this, 'cws_mce_buttons_2') );
		/* /tinymce related */

		// comments
		add_filter('preprocess_comment', array($this, 'cws_comment_post'), '', 1);
		add_filter( 'comment_form_fields',array( $this, 'cws_move_comment_field_to_bottom' ) );

		// Add Svg support
		add_filter('upload_mimes', array($this, 'cws_mime_types'));

		// Check if WPML is active
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( function_exists('wpml_init_language_switcher') ) {
			define('CWS_WPML_ACTIVE', true);
			$GLOBALS['wpml_settings'] = get_option('icl_sitepress_settings');
			global $icl_language_switcher;
		} else {
			define('CWS_WPML_ACTIVE', false);
		}

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
	}

	public function cws_loop_products_per_page() {
		return (int) $this->cws_get_option( 'woo_num_products' );
	}

	public function cws_theme_woo_setup(){
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
	}

	public function cws_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	public function cws_fix_shortcodes_autop($content){
		$array = array (
			'<p>[' => '[',
			']</p>' => ']',
			']<br />' => ']'
		);

		$content = strtr($content, $array);
		return $content;
	}

	public function cws_add_excerpts_to_pages(){
		add_post_type_support( 'page', 'excerpt' );
	}

	public function cws_comment_form_defaults( $defaults ){
		$defaults['title_reply'] = esc_html__('Write a Comment', 'metamax' );
		$defaults['title_reply_before'] = '<h3 id="reply-title" class="h3 comment-reply-title ce-title">';
		$defaults['title_reply_after'] = '</h3>';
	  return $defaults;
	}

	public function cws_mce_buttons_2( $buttons ) {
		array_unshift( $buttons, 'styleselect' );
		return $buttons;
	}

	public function cws_blog_get_chars_count( $cols = 0, $p_id = null ) {
		$number = self::$cws_theme_config['def_char_number'];
		$p_id = $p_id ? $p_id : get_queried_object_id();
		$sb = $this->cws_get_sidebars( $p_id );
		$sb_layout = isset( $sb['sb_layout_class'] ) ? $sb['sb_layout_class'] : '';
		$anums = self::$cws_theme_config['char_counts'];
		if ( $cols < count($anums) ) {
			$number = $anums[$cols][$sb_layout];
		}
		return $number;
	}

	public function cws_tiny_mce_before_init( $settings ) {
		$font_array = $this->cws_get_option( 'header-font' );

		$settings['theme_advanced_blockformats'] = 'p,h1,h2,h3,h4';

		$style_formats = array(
		array( 'title' => esc_attr__('Title', 'metamax'), 'block' => 'div', 'classes' => 'ce-title' ),
		array( 'title' => esc_attr__('Divider left', 'metamax'), 'block' => 'div', 'classes' => 'ce-title ce-title-div-left' ),
		array( 'title' => esc_attr__('Divider right', 'metamax'), 'block' => 'div', 'classes' => 'ce-title ce-title-div-right' ),
		array( 'title' => esc_attr__('Font-size', 'metamax'), 'items' => array(
		    array( 'title' => esc_attr__('55px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '55px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('50px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '50px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('45px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '45px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('40px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '40px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('30px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '30px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('26px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '26px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('22px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '22px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('18px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '26px' , 'line-height' => 'initial') ),
			array( 'title' => esc_attr__('16px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '16px' , 'line-height' => '1.5em') ),
			array( 'title' => esc_attr__('14px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,ul,li', 'styles' => array( 'font-size' => '14px' , 'line-height' => '1.5em') ),
			)
		),
		array( 'title' => esc_attr__('margin-top', 'metamax'), 'items' => array(
			array( 'title' => esc_attr__('0px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '0' ) ),
			array( 'title' => esc_attr__('10px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '10px' ) ),
			array( 'title' => esc_attr__('15px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '15px' ) ),
			array( 'title' => esc_attr__('20px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '20px' ) ),
			array( 'title' => esc_attr__('25px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '25px' ) ),
			array( 'title' => esc_attr__('30px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '30px' ) ),
			array( 'title' => esc_attr__('40px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '40px' ) ),
			array( 'title' => esc_attr__('50px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '50px' ) ),
			array( 'title' => esc_attr__('60px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-top' => '60px' ) ),
			)
		),
		array( 'title' => esc_attr__('margin-bottom', 'metamax'), 'items' => array(
			array( 'title' => esc_attr__('0px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '0px' ) ),
			array( 'title' => esc_attr__('10px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '10px' ) ),
			array( 'title' => esc_attr__('15px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '15px' ) ),
			array( 'title' => esc_attr__('18px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '18px' ) ),
			array( 'title' => esc_attr__('20px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '20px' ) ),
			array( 'title' => esc_attr__('25px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '25px' ) ),
			array( 'title' => esc_attr__('30px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '30px' ) ),
			array( 'title' => esc_attr__('40px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '40px' ) ),
			array( 'title' => esc_attr__('50px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '50px' ) ),
			array( 'title' => esc_attr__('60px', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6,p,span,i,b,strong,em,div', 'styles' => array( 'margin-bottom' => '60px' ) ),
			)
		),
		array( 'title' => esc_attr__('SVG Divider', 'metamax'), 'selector' => 'h1,h2,h3,h4,h5,h6', 'classes' => 'div-title' ),
		array( 'title' => esc_attr__('Floated Blockqoute', 'metamax'), 'selector' => 'blockquote', 'classes' => 'floated' ),
		array( 'title' => esc_attr__('Borderless image', 'metamax'), 'selector' => 'img', 'classes' => 'noborder' ),
		array( 'title' => esc_attr__('Animation On Hover', 'metamax'), 'items' => array(
			array( 'title' => esc_attr__('To top', 'metamax'), 'selector' => 'a,img', 'classes' => 'shadow-image top' ),
			array( 'title' => esc_attr__('To bottom', 'metamax'), 'selector' => 'a,img', 'classes' => 'shadow-image bottom' ),
			array( 'title' => esc_attr__('Change color', 'metamax'), 'selector' => 'a,img', 'classes' => 'with-filter' ),
			array( 'title' => esc_attr__('White Background', 'metamax'), 'selector' => 'a,img', 'block' => 'div', 'classes' => 'img-with-bg' ),
			)
		),
		array( 'title' => esc_attr__('Image with Shadow', 'metamax'), 'selector' => 'img', 'classes' => 'image-with-shadow' ),
		array( 'title' => esc_attr__('Border Radius Image', 'metamax'), 'items' => array(
			array( 'title' => esc_attr__('1px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '1px' )),
			array( 'title' => esc_attr__('2px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '2px' )),
			array( 'title' => esc_attr__('3px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '3px' )),
			array( 'title' => esc_attr__('4px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '4px' )),
			array( 'title' => esc_attr__('5px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '5px' )),
			array( 'title' => esc_attr__('6px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '6px' )),
			array( 'title' => esc_attr__('7px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '7px' )),
			array( 'title' => esc_attr__('8px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '8px' )),
			array( 'title' => esc_attr__('9px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '9px' )),
			array( 'title' => esc_attr__('10px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '10px' )),
			array( 'title' => esc_attr__('11px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '11px' )),
			array( 'title' => esc_attr__('12px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '12px' )),
			array( 'title' => esc_attr__('13px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '13px' )),
			array( 'title' => esc_attr__('14px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '14px' )),
			array( 'title' => esc_attr__('15px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '15px' )),
			array( 'title' => esc_attr__('16px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '16px' )),
			array( 'title' => esc_attr__('17px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '17px' )),
			array( 'title' => esc_attr__('18px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '18px' )),
			array( 'title' => esc_attr__('19px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '19px' )),
			array( 'title' => esc_attr__('20px', 'metamax'), 'selector' => 'img', 'styles' => array( 'border-radius' => '20px' )),
		))
		);
		// Before 3.1 you needed a special trick to send this array to the configuration.
		// See this post history for previous versions.
		$settings['style_formats'] = str_replace( '"', "'", json_encode( $style_formats ) );

		return $settings;
	}

	public function cws_print_single_class($class) {
		$class .= ' page-content';
		$footer = $this->cws_get_meta_option('footer');
		$wide_featured = $this->cws_get_meta_option('wide_featured');

		$class .= isset($footer['fixed']) && $footer['fixed'] == '1' ? ' fixed' : '';
		$class .= isset($wide_featured) && $wide_featured == '1' ? ' wide-featured' : '';
		return $class;
	}

	public function cws_print_metas() {
		$this->cws_echo_if( has_category(), '<div class="post-categories">' . get_the_category_list (
		        $this::THEME_V_SEP ) . '</div>');
		$this->cws_echo_if( has_tag(), '<div class="post-tags">' . get_the_tag_list (null, $this::THEME_V_SEP, null )
		 . '</div>');
	}

	public function cws_get_page_meta_var ( $keys ) {
		$p_meta = array();
		if ( isset( $GLOBALS['metamax' . '_page_meta'] ) && !empty($keys) ) {
			$p_meta = $GLOBALS['metamax' . '_page_meta'];
			if ( is_string( $keys ) ) {
				if ( isset( $p_meta[$keys] ) ) {
					return $p_meta[$keys];
				}
			} else if ( is_array( $keys ) ) {
				for ( $i=0; $i < count($keys); $i++ ) {
					if ( isset( $p_meta[$keys[$i]] ) ) {
						if ( $i < count($keys) - 1 ) {
							if ( is_array( $p_meta[$keys[$i]] ) ) {
								$p_meta = $p_meta[$keys[$i]];
							}	else {
								return false;
							}
						}	else {
							return $p_meta[$keys[$i]];
						}
					}	else {
						return false;
					}
				}
			}
		}
		return false;
	}

	public function cws_set_page_meta_var($keys, $value = '') {
		$p_meta = array();
		if (isset($GLOBALS['metamax' . '_page_meta']) && !empty($keys) ) {
			$p_meta = &$GLOBALS['metamax' . '_page_meta'];

			if ( is_string( $keys ) ) {
				if ( isset($p_meta[$keys]) ) {
					$p_meta[$keys] = $value;
					return true;
				}
			} else if ( is_array( $keys ) && !empty( $keys ) ) {
				for ( $i=0; $i < count($keys); $i++ ) {
					if ( isset( $p_meta[$keys[$i]] ) ) {
						if ( $i < count($keys) - 1 ) {
							if ( is_array( $p_meta[$keys[$i]] ) ) {
								$p_meta = &$p_meta[$keys[$i]];
							} else {
								return false;
							}
						}	else {
							$p_meta[$keys[$i]] = $value;
							return true;
						}
					}	else {
						return false;
					}
				}
			}
		}
		return false;
	}

	/* HEDER LOADER */
	public function cws_page_loader() {
		$cws_enable_page_loader = $this->cws_get_meta_option( 'show_loader' );
		if (!empty($cws_enable_page_loader)) {

			$loader_logo = $this->cws_get_option( 'loader_logo' );

			echo '<div id="cws-page-loader-container" class="cws-loader-container">';
			    echo '<div id="cws-page-loader" class="cws-loader">';
			        echo '<div class="inner"></div>';

                    if ( isset( $loader_logo['src'] ) && ( ! empty( $loader_logo['src'] ) ) ) {
                        $loader_logo_height = '';
                        $bfi_args = array(
                                'width' => '50',
                                'height' => '50'
                        );
                        $file_parts = pathinfo($loader_logo['src']);
                        if ( $file_parts['extension'] == 'svg' ){
                            $loader_logo['svg'] = $this->cws_print_svg_html($loader_logo, $bfi_args, $loader_logo_height);
                            echo sprintf("%s", $loader_logo['svg']);
                        } else {
                            $loader_logo_src = $this->cws_print_img_html($loader_logo, $bfi_args, $loader_logo_height);
                            echo ( (!empty($loader_logo_src)) ? "<img class='loader-logo' $loader_logo_src />" : '');
                        }
                    }

			    echo '</div>';
			echo '</div>';
		}
	}
	/* END HEDER LOADER */

	/* THE HEADER META */
	public function cws_header_meta() {
			?>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php
		$this->cws_read_options();
	}
	/* END THE HEADER META */

	/* THEME HEADER */
	public function cws_page_header() {
		$pid = get_the_id();

		$font_color_style = $animate_title = '';
		$title_box_parallaxify = $bg_header_scalar_x = $bg_header_scalar_y = $bg_header_limit_x = $bg_header_limit_y = $title_box_pattern_image = '';
		$title_box_overlay = $title_box_overlay_gradient = $parallax_opt_arr = array();
		$title_box_parallaxify_atts = $title_box_parallaxify_layer_atts = '';

		$title_box_spacings = array();
		$title_box_font_color = '';

		$post_meta = get_post_meta($pid, 'cws_mb_post');
		if( !empty($post_meta) ){
			$post_meta = $post_meta[0];
		}

		//Get metaboxes from page
		$header = $this->cws_get_meta_option('header');
		extract($header, EXTR_PREFIX_ALL, 'header');

		$sticky_menu = $this->cws_get_meta_option( 'sticky_menu' );
		extract($sticky_menu, EXTR_PREFIX_ALL, 'sticky_menu');

		$title_box = $this->cws_get_meta_option( 'title_box' );
		extract($title_box, EXTR_PREFIX_ALL, 'title_box');

		$custom_header_bg_color = false;
		$post_type = get_post_type();

		$img_section_atts = $img_section_styles = '';
		$img_section_atts .= ' class="header_bg_img"';

		$header_outside_slider = $this->cws_get_meta_option('header')['outside_slider'] == '1' && !is_single() && !is_archive() || $this->cws_get_option( 'shop-slider-type' ) != 'none' && $this->cws_is_woo();

		$show_header_shop_slider = $this->cws_get_option( 'shop-slider-type' ) != 'none' && $this->cws_is_woo() && is_shop();
		if($show_header_shop_slider){
			if($this->cws_get_option('woo_header_covers_slider')){
				$header_outside_slider = true;
			}else{
				$header_outside_slider = false;
			}
		}

		$show_page_title = true;

		$top_panel_content = $this->cws_render_top_bar($pid);

		$this->header['top_bar_box'] = $top_panel_content;

		$title_box_customize = isset($title_box_customize) && $title_box_customize == '1';
		$title_box_no_title = isset($title_box_no_title) ? $title_box_no_title : '';
		$woo_customize_title = $this->cws_get_option('woo_customize_title');

		if($this->cws_is_woo() && !empty($woo_customize_title)){
			$woo_header_font_color = $this->cws_get_option('woo_header_font_color');
			$woo_header_helper_font_color = $this->cws_get_option('woo_header_helper_font_color');
			$woo_header_helper_hover_font_color = $this->cws_get_option('woo_header_helper_hover_font_color');
			$title_box_font_color = !empty($woo_header_font_color) ? $woo_header_font_color : $title_box_font_color;
			$title_box_helper_font_color = !empty($woo_header_helper_font_color) ? $woo_header_helper_font_color : $title_box_helper_font_color;
			$title_box_helper_hover_font_color = !empty($woo_header_helper_hover_font_color) ? $woo_header_helper_hover_font_color : $title_box_helper_hover_font_color;
		}

		if($this->cws_is_woo() && !empty($woo_customize_title)){
			$title_box_spacings = $this->cws_get_option('woo_page_title_spacings');
		}

		$page_title_content = $this->cws_build_page_title($title_box_no_title, $title_box_spacings);

		ob_start();
		echo "<!-- title-box -->";

			if ($title_box_customize) {
				extract($title_box_overlay, EXTR_PREFIX_ALL, 'title_box_overlay');
			}

			$slider_isset = $bg_header_feature = $posts_styles = $archives_styles = false;
			$bg_header_url = $bg_header_html = $title_style = '';

			if( $title_box_customize ){
				$title_style = ' customized';

				if( is_single() && !$title_box_show_on_posts ){
					$title_style = '';
				}
				if( is_archive() && !$title_box_show_on_archives ){
					$title_style = '';
				}
			}

			if (
				(
					($title_box_customize || isset($meta_title_area) || isset( $bg_header_url ) ) &&
					!(get_post_type() == 'cws_staff')
					// Uncomment this line if you need to exclude portfolio post
				)
			|| ($this->cws_is_woo())
				){

				if ( ($title_box_customize && ( (isset($title_box_overlay_type) && $title_box_overlay_type != 'none') || $title_box_use_pattern == '1') ))
				{
					if (
						( $title_box_use_pattern && !empty( $title_box_pattern_image ) && isset( $title_box_pattern_image['image']['src'] ) && !empty( $title_box_pattern_image['image']['src'] )  )
						|| ( isset($title_box_overlay_type) && $title_box_overlay_type == 'color' && !empty( $title_box_overlay_color ) )
						|| ( isset($title_box_overlay_type) && $title_box_overlay_type == 'gradient' )
					) {
						$bg_header_html .= "<div class='bg-layer'></div>";
					}
				}
			}

				if ( !empty( $bg_header_url ) && !is_front_page() ) {
					$header_bg_atts = '';
					foreach ( $title_box_spacings as $key => $value ) {
						switch ($key) {
							case 'top':
							case 'bottom':
							if ( !empty( $value ) ) {
								$header_bg_atts .= " data-$key='".esc_attr($value)."'";
							}
							break;
						}
					}

					echo "<div class='title-box bg-page-header with-image".$title_style."' ".$header_bg_atts.">";
						if ($page_title_content) {
							echo sprintf("%s", $page_title_content);
						}
						echo sprintf("%s", $bg_header_html);

					echo '</div>';
				}

				if ( (empty( $bg_header_url ) && ($title_box_customize )) || (empty( $bg_header_url ) && !empty($page_title_content) ) ) {
					echo "<div class='title-box bg-page-header".$title_style.(isset($title_box_border['line']) &&
					$title_box_border['line'] == '1' ? ' border_line' : '')."'".(!empty($font_color) ? ' style="color:'.esc_attr($font_color).';"' : '').(!empty($custom_header_bg_spacings["top"]) ? " data-top='".esc_attr($custom_header_bg_spacings['top'])."'" : '').(!empty($custom_header_bg_spacings["bottom"]) ? " data-bottom='".esc_attr($custom_header_bg_spacings['bottom'])."'" : '').">";
						if ($page_title_content) {
							echo sprintf("%s", $page_title_content);
						}
						echo sprintf("%s", $bg_header_html);
					echo '</div>';
				}
		echo "<!-- /title-box -->";

		$page_header_content = ob_get_clean();


		if( isset($title_box_enable) && $title_box_enable == '1' && !is_front_page() ){
			$this->header['title_box'] = $page_header_content;
		} else {
			$this->header['title_box'] = '';
		}

		ob_start();
			$is_revslider_active = function_exists('set_revslider_as_theme');
			$cws_revslider_content = '';
			$slider_type = "none";

			if ( is_front_page() ){
				$slider_type = $this->cws_get_option( 'home-slider-type' );
				switch( $slider_type ){
					case 'img-slider':
						$slider_settings = $this->cws_get_meta_option( 'slider_override' );

						$slider_shortcode = $this->cws_get_option( 'home-header-slider-options' );
						if ( is_page() && $slider_settings['is_override'] == '1' ){
							$slider_shortcode = $slider_settings['slider_shortcode'];
						}
						if ( $slider_settings['is_override'] == '1' ){
							$slider_is_wide = $slider_settings['is_wide'];
						} else {
							$slider_is_wide = '';
						}

						$slider_plugin_exist = true;

						$slider_shortcode = wp_specialchars_decode($slider_shortcode, ENT_QUOTES);
						$shortcode_output = do_shortcode($slider_shortcode);

						$slider_plugin_exist = !($shortcode_output === $slider_shortcode); //Check shortcode & output the same

						if ($slider_is_wide !== '1') {
							$shortcode_output = "<div class='slider-bg'><div class='container'>{$shortcode_output}</div></div>";
						}

						if ($slider_plugin_exist){
							echo sprintf("%s", $shortcode_output);
						}

						$slider_error = strpos($shortcode_output, 'Revolution Slider Error') ? true : false;

						$show_page_title = !empty( $slider_shortcode ) ? false : $show_page_title;
						break;
					case 'video-slider':
						$video_slider_settings = $this->cws_get_option('slidersection-start');

						$slider_shortcode = $video_slider_settings[ 'slider_shortcode' ];
						$slider_switch = $video_slider_settings[ 'slider_switch' ];
						$video_type = $video_slider_settings[ 'video_type' ];
						$set_video_header_height = $video_slider_settings[ 'set_video_header_height' ];
						$video_header_height = $video_slider_settings[ 'video_header_height' ];
						$sh_source = $video_slider_settings[ 'sh_source' ];
						$youtube_source = $video_slider_settings[ 'youtube_source' ];
						$vimeo_source = $video_slider_settings[ 'vimeo_source' ];
						$color_overlay_type = $video_slider_settings[ 'color_overlay_type' ];
						$overlay_color = $video_slider_settings[ 'overlay_color' ];
						$color_overlay_opacity = $video_slider_settings[ 'color_overlay_opacity' ];
						$use_pattern = $video_slider_settings[ 'use_pattern' ];
						$pattern_image = $video_slider_settings[ 'pattern_image' ];

						$video_header_height = $set_video_header_height == "1" ? $video_header_height : false;
						$gradient_video_set = $video_slider_settings["slider_gradient_settings"];
						$gradient_settings = $this->cws_render_gradient($gradient_video_set);

						$sh_source = isset( $sh_source['src'] ) && !empty( $sh_source['src'] ) ? $sh_source['src'] : '';
						$color_overlay_opacity = (int)$color_overlay_opacity / 100;
						$has_video_src = false;
						$header_video_atts = '';
						$header_video_class = "fs_video_bg";
						$header_video_styles = '';
						$header_video_html = '';
						$uniqid = uniqid( 'video-' );
						$uniqid_esc = esc_attr( $uniqid );
						switch ( $video_type ){
							case 'self_hosted':
								if ( !empty( $sh_source ) ){
									$has_video_src = true;
									$header_video_class .= " cws_self_hosted_video";
									$header_video_html .= "<video class='self_hosted_video' src='".esc_url($sh_source)."' autoplay='autoplay' loop='loop' muted='muted'></video>";
								}
								break;
							case 'youtube':
								if ( !empty( $youtube_source ) ){
									wp_enqueue_script ('cws-YT-bg');
									$has_video_src = true;
									$header_video_class .= " cws_Yt_video_bg loading";
									$header_video_atts .= " data-video-source='".esc_url($youtube_source)."' data-video-id='".esc_attr($uniqid)."'";
									$header_video_html .= "<div id='".esc_attr($uniqid_esc)."'></div>";
								}
								break;
							case 'vimeo':
								if ( !empty( $vimeo_source ) ){
									wp_enqueue_script ('vimeo');
									wp_enqueue_script ('cws-self&vimeo-bg');
									$has_video_src = true;
									$header_video_class .= " cws_Vimeo_video_bg";
									$header_video_atts .= " data-video-source='".esc_url($vimeo_source)."' data-video-id='".esc_attr($uniqid)."'";
									$header_video_html .= "<iframe id='".esc_attr($uniqid_esc)."' src='" . esc_url($vimeo_source) . "?api=1&player_id=".esc_attr($uniqid)."' frameborder='0'></iframe>";
								}
								break;
						}
						if ( $has_video_src ){
							if ( $use_pattern && !empty( $pattern_image ) && isset( $pattern_image['url'] ) && !empty( $pattern_image['url'] ) ){
								$pattern_img_src = $pattern_image['url'];
								$header_video_html .= "<div class='bg-layer' style='background-image:url(" . esc_url
								($pattern_img_src) . ")'></div>";
							}
							if ( $color_overlay_type == 'color' && !empty( $overlay_color ) ){
								$header_video_html .= "<div class='bg-layer' style='background-color:" . esc_attr
								($overlay_color) . ";" . ( !empty( $color_overlay_opacity ) ? "opacity:".esc_attr($color_overlay_opacity).";" : '' ) . "'></div>";
							}
							else if ( $color_overlay_type == 'gradient' ){
								$gradient_rules = $this->cws_print_gradient( $gradient_settings );
								$header_video_html .= "<div class='bg-layer' style='".esc_attr($gradient_rules)."" .
								( !empty( $color_overlay_opacity ) ? "opacity:".esc_attr($color_overlay_opacity).";" : '' ) . "'></div>";
							}
						}

						$header_video_atts .= !empty( $header_video_class ) ? " class='" . trim( $header_video_class ) . "'" : '';
						$header_video_atts .= !empty( $header_video_styles ) ? " style='". esc_attr($header_video_styles) ."'" : '';


						if ( !empty( $slider_shortcode ) && $has_video_src && $slider_switch == 1 ){
							echo "<div class='fs-video-slider'>";
								echo  do_shortcode( $slider_shortcode );
								echo '<div ' . $header_video_atts . '>';
								echo sprintf("%s", $header_video_html);
								echo '</div>';
								echo '</div>';
						} elseif ( $has_video_src && $slider_switch == 0 ) {
							$header_video_fs_view = $video_header_height == false ? 'header-video-fs-view' : '';
							$video_height_coef = $video_header_height == false ? '' : " data-wrapper-height='".esc_attr(960 / $video_header_height)."'";
							$video_header_height = $video_header_height == false ? '' : "style='height:" . esc_attr($video_header_height) ."px'";
							echo "<div class='fs-video-slider ". sanitize_html_class( $header_video_fs_view ) ."' " .
							 $video_header_height . " ". $video_height_coef .">";
							echo '<div ' . $header_video_atts . '>';
							echo sprintf("%s", $header_video_html);
							echo '</div>';
							echo '</div>';
						} elseif ( ! empty( $slider_shortcode ) && $slider_switch == 1 && ! $has_video_src ) {
							echo  do_shortcode( $slider_shortcode );
						} else {
							if ( $has_video_src ){
								echo "<div class='fs-video-slider'></div>";
							}
						}

						break;
					case 'stat-img-slider':
						$static_img_section = $this->cws_get_option('static_img_section');
						$set_img_header_height = $static_img_section['set_static_image_height'];
						$img_header_height = $static_img_section[ 'static_image_height' ];

						$color_overlay_type = '';
						$overlay_color = '';
						$color_overlay_opacity = '';
						$gradient_settings = array();

						if ($static_img_section[ 'static_customize_colors' ] == "1"){
							$color_overlay_type = $static_img_section[ 'img_header_color_overlay_type' ];
							$overlay_color = $static_img_section[ 'img_header_overlay_color' ];
							$color_overlay_opacity = $static_img_section[ 'img_header_color_overlay_opacity' ];
							$color_overlay_opacity = (int)$color_overlay_opacity / 100;
							$gradient_settings = $this->cws_render_gradient( $static_img_section["img_header_gradient_settings"] );
						}

						$use_pattern = $static_img_section[ 'img_header_use_pattern' ];
						$pattern_image = $static_img_section[ 'img_header_pattern_image' ];

						$img_header_height = $set_img_header_height == "1" ? $img_header_height : false;

						$parallax_header_opt = $static_img_section['img_header_parallax_options'];

						$img_header_parallaxify = $static_img_section["img_header_parallaxify"];

						if ($img_header_parallaxify == '1'){
							$img_header_scalar_x = $parallax_header_opt["img_header_scalar_x"];
							$img_header_scalar_y = $parallax_header_opt["img_header_scalar_y"];
							$img_header_limit_x = $parallax_header_opt["img_header_limit_x"];
							$img_header_limit_y = $parallax_header_opt["img_header_limit_y"];

							$img_header_parallaxify_atts = ' data-scalar-x="'.esc_attr($img_header_scalar_x).'" data-scalar-y="'.esc_attr($img_header_scalar_y).'" data-limit-y="'.esc_attr($img_header_limit_y).'" data-limit-x="'.esc_attr($img_header_limit_x).'"';
							$img_header_parallaxify_layer_atts = 'position: absolute; z-index: 1; left: -'.esc_attr($img_header_limit_y).'px; right: -'.esc_attr($img_header_limit_y).'px; top: -'.esc_attr($img_header_limit_x).'px; bottom: -'.esc_attr($img_header_limit_x).'px;';
						}

						$image_options = $static_img_section["home_header_image_options"];

						$default_img = false;
						$override_img = false;
						$img_url = '';

						$header_img_html = '';

						if ( isset( $image_options['src'] ) ){
							if ( $use_pattern && !empty( $pattern_image ) && isset( $pattern_image['src'] ) && !empty( $pattern_image['src'] ) ){
								$pattern_img_src = $pattern_image['src'];
								$header_img_html .= "<div class='bg-layer' style='background-image:url(" . esc_url
								($pattern_img_src) . ");".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
							}
							if ( $color_overlay_type == 'color' && !empty( $overlay_color ) ){
								$header_img_html .= "<div class='bg-layer' style='background-color:" . esc_attr
								($overlay_color) . ";" . ( !empty( $color_overlay_opacity ) ? "opacity:".esc_attr($color_overlay_opacity).";" : '' ) . ";".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
							}
							else if ( $color_overlay_type == 'gradient' && !empty( $gradient_settings ) ){
								$gradient_rules = $this->cws_print_gradient( $gradient_settings );
								$header_img_html .= "<div class='bg-layer' style='$gradient_rules" . ( !empty(
								        $color_overlay_opacity ) ? "opacity:".esc_attr($color_overlay_opacity).";" : '' ) . ";".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
							}
						}

						if ( isset( $image_options['src'] ) ) {
							$header_img_fs_view = $img_header_height== false ? 'header-video-fs-view' : '';
							$header_img_height_coef = $img_header_height == false ? '' : " data-wrapper-height='".esc_attr(960 / $img_header_height)."'";
							$img_header_height = $img_header_height == false ? '' : "style='height:" . esc_attr($img_header_height) ."px'";

							echo "<div class='fs-img-header " . sanitize_html_class( $header_img_fs_view ) ."' " .
							$img_header_height . " ". $header_img_height_coef .">";
								if ($img_header_parallaxify) {
									echo '<div class="cws-parallax-section" '.$img_header_parallaxify_atts.'>';
								}
								wp_enqueue_script ('parallax');
									if ($img_header_parallaxify) {
										echo '<div class="layer" data-depth="1.00">';
									}
									echo sprintf("%s", $header_img_html);
									echo "<div class='stat-img-cont' style='". ($img_header_parallaxify ?
									$img_header_parallaxify_layer_atts : '') ."background-image: url(".esc_url($image_options['src']).");background-size: cover;background-position: center center;'></div>";
									if ($img_header_parallaxify) {
										echo '</div>';
									}
								if ($img_header_parallaxify) {
									echo '</div>';
								}
							echo '</div>';

						}
					break;
					default:
				}
				if( $slider_type != 'none' ){
					$slider_isset = true;
				}
			} else if ( is_page() || $this->cws_is_woo() && is_shop() ){
				if($this->cws_is_woo() && is_shop()){
					$slider_type = $this->cws_get_option( 'shop-slider-type' );
					switch( $slider_type ){
						case 'img-slider':
							$slider_isset = true;

							$slider_settings = $this->cws_get_meta_option( 'slider_override' );
							$slider_shortcode = wp_specialchars_decode($this->cws_get_option( 'shop-header-slider-options' ));

							if ( is_page() && $slider_settings['is_override'] == '1' ){
								$slider_shortcode = wp_specialchars_decode($slider_settings['slider_shortcode']);
							}
							$slider_options = isset($slider_options) ? $slider_options : '';
							$slider_options = wp_specialchars_decode($slider_options, ENT_QUOTES);
							$slider_output = do_shortcode( $slider_shortcode );

							if ($slider_settings['is_wide'] !== '1'){
								$slider_output = "<div class='slider-bg'><div class='container'>{$slider_output}</div></div>";
							}

							echo sprintf("%s", $slider_output);

							$slider_error = strpos($slider_output, 'Revolution Slider Error') ? true : false;

							$show_page_title = !empty( $slider_options ) ? false : $show_page_title;
							break;
						case 'video-slider':
							$slider_isset = true;

							$video_slider_settings = $this->cws_get_option('shopslidersection-start');

							$slider_shortcode = isset($video_slider_settings[ 'slider_shortcode' ]) ? $video_slider_settings[ 'slider_shortcode' ] : "";
							$slider_switch = $video_slider_settings[ 'slider_switch' ];
							$video_type = $video_slider_settings[ 'video_type' ];
							$set_video_header_height = $video_slider_settings[ 'set_video_header_height' ];
							$video_header_height = $video_slider_settings[ 'video_header_height' ];
							$sh_source = isset($video_slider_settings[ 'sh_source' ]) ? $video_slider_settings[ 'sh_source' ] : "";
							$youtube_source = $video_slider_settings[ 'youtube_source' ];
							$vimeo_source = isset($video_slider_settings[ 'vimeo_source' ]) ? $video_slider_settings[ 'vimeo_source' ] : "";
							$color_overlay_type = $video_slider_settings[ 'color_overlay_type' ];
							$overlay_color = isset($video_slider_settings[ 'overlay_color' ]) ? $video_slider_settings[ 'overlay_color' ] : "";
							$color_overlay_opacity = isset($video_slider_settings[ 'color_overlay_opacity' ]) ? $video_slider_settings[ 'color_overlay_opacity' ] : "";
							$use_pattern = isset($video_slider_settings[ 'use_pattern' ]) ? $video_slider_settings[ 'use_pattern' ] : "";
							$pattern_image = isset($video_slider_settings[ 'pattern_image' ]) ? $video_slider_settings[ 'pattern_image' ] : "";

							$video_header_height = $set_video_header_height == "1" ? $video_header_height : false;
							$gradient_video_set = isset($video_slider_settings["slider_gradient_settings"]) ? $video_slider_settings["slider_gradient_settings"] : "";
							$gradient_settings = $this->cws_render_gradient($gradient_video_set);

							$sh_source = isset( $sh_source['src'] ) && !empty( $sh_source['src'] ) ? $sh_source['src'] : '';
							$color_overlay_opacity = (int)$color_overlay_opacity / 100;
							$has_video_src = false;
							$header_video_atts = '';
							$header_video_class = "fs_video_bg";
							$header_video_styles = '';
							$header_video_html = '';
							$uniqid = uniqid( 'video-' );
							$uniqid_esc = esc_attr( $uniqid );
							switch ( $video_type ){
								case 'self_hosted':
									if ( !empty( $sh_source ) ){
										$has_video_src = true;
										$header_video_class .= " cws_self_hosted_video";
										$header_video_html .= "<video class='self_hosted_video' src='$sh_source' autoplay='autoplay' loop='loop' muted='muted'></video>";
									}
									break;
								case 'youtube':
									if ( !empty( $youtube_source ) ){
										$has_video_src = true;
										$header_video_class .= " cws_Yt_video_bg loading";
										$header_video_atts .= " data-video-source='$youtube_source' data-video-id='$uniqid'";
										$header_video_html .= "<div id='$uniqid_esc'></div>";
									}
									break;
								case 'vimeo':
									if ( !empty( $vimeo_source ) ){
										wp_enqueue_script ('vimeo');
										$has_video_src = true;
										$header_video_class .= " cws_Vimeo_video_bg";
										$header_video_atts .= " data-video-source='$vimeo_source' data-video-id='$uniqid'";
										$header_video_html .= "<iframe id='$uniqid_esc' src='" . $vimeo_source . "?api=1&player_id=$uniqid' frameborder='0'></iframe>";
									}
									break;
							}
							if ( $has_video_src ){
								if ( $use_pattern && !empty( $pattern_image ) && isset( $pattern_image['url'] ) && !empty( $pattern_image['url'] ) ){
									$pattern_img_src = $pattern_image['url'];
									$header_video_html .= "<div class='bg-layer' style='background-image:url(" .
									$pattern_img_src . ")'></div>";
								}
								if ( $color_overlay_type == 'color' && !empty( $overlay_color ) ){
									$header_video_html .= "<div class='bg-layer' style='background-color:" .
									$overlay_color . ";" . ( !empty( $color_overlay_opacity ) ? "opacity:$color_overlay_opacity;" : '' ) . "'></div>";
								}
								else if ( $color_overlay_type == 'gradient' ){
									$gradient_rules = $this->cws_print_gradient( array( 'settings' => $gradient_settings ) );
									$header_video_html .= "<div class='bg-layer' style='$gradient_rules" . ( !empty(
									        $color_overlay_opacity ) ? "opacity:$color_overlay_opacity;" : '' ) . "'></div>";
								}
							}

							$header_video_atts .= !empty( $header_video_class ) ? " class='" . trim( $header_video_class ) . "'" : '';
							$header_video_atts .= !empty( $header_video_styles ) ? " style='". esc_attr($header_video_styles) ."'" : '';


							if ( !empty( $slider_shortcode ) && $has_video_src && $slider_switch == 1 ){
								echo "<div class='fs_video_slider'>";
								if ( $is_revslider_active ) {
									echo  do_shortcode( $slider_shortcode );
								} else {
									echo do_shortcode( "[cws_sc_msg_box type='warning' is_closable='1' description='Install and activate Slider Revolution plugin' title=''][/cws_sc_msg_box]" );
								}
									echo '<div ' . $header_video_atts . '>';
									echo sprintf("%s", $header_video_html);
									echo '</div>';
									echo '</div>';
							} elseif ( $has_video_src && $slider_switch == 0 ) {
								$header_video_fs_view = $video_header_height == false ? 'header-video-fs-view' : '';
								$video_height_coef = $video_header_height == false ? '' : " data-wrapper-height='".(960 / $video_header_height)."'";
								$video_header_height = $video_header_height == false ? '' : "style='height:" . $video_header_height ."px'";
								echo "<div class='fs-video-slider ". sanitize_html_class( $header_video_fs_view ) ."' " . $video_header_height . " ". $video_height_coef .">";
								echo '<div ' . $header_video_atts . '>';
								echo sprintf("%s", $header_video_html);
								echo '</div>';
								echo '</div>';
							}elseif ( ! empty( $slider_shortcode ) && $slider_switch == 1 && ! $has_video_src ) {
								if ( $is_revslider_active ) {
									echo  do_shortcode( $slider_shortcode );
								} else {
									echo do_shortcode( "[cws_sc_msg_box type='warning' is_closable='1' description='Install and activate Slider Revolution plugin' title=''][/cws_sc_msg_box]" );
								}
							}else{
								if ( $has_video_src ){
									echo "<div class='fs-video-slider'></div>";
								}
							}

							break;
						case 'stat-img-slider':
							$slider_isset = true;

							$static_img_section = $this->cws_get_option('static_img_section');
							$set_img_header_height = $static_img_section['set_static_image_height'];
							$img_header_height = $static_img_section[ 'static_image_height' ];


							$color_overlay_type = '';
							$overlay_color = '';
							$color_overlay_opacity = '';
							$gradient_settings = array();

							if ($static_img_section[ 'static_customize_colors' ] == "1"){
								$color_overlay_type = $static_img_section[ 'img_header_color_overlay_type' ];
								$overlay_color = $static_img_section[ 'img_header_overlay_color' ];
								$color_overlay_opacity = $static_img_section[ 'img_header_color_overlay_opacity' ];
								$color_overlay_opacity = (int)$color_overlay_opacity / 100;
								$gradient_settings = $this->cws_render_gradient( $static_img_section["img_header_gradient_settings"] );
							}

							$use_pattern = $static_img_section[ 'img_header_use_pattern' ];
							$pattern_image = $static_img_section[ 'img_header_pattern_image' ];

							$img_header_height = $set_img_header_height == "1" ? $img_header_height : false;

							$parallax_header_opt = $static_img_section['img_header_parallax_options'];

							$img_header_parallaxify = $static_img_section["img_header_parallaxify"];

							if ($img_header_parallaxify == '1'){
								$img_header_scalar_x = $parallax_header_opt["img_header_scalar_x"];
								$img_header_scalar_y = $parallax_header_opt["img_header_scalar_y"];
								$img_header_limit_x = $parallax_header_opt["img_header_limit_x"];
								$img_header_limit_y = $parallax_header_opt["img_header_limit_y"];

								$img_header_parallaxify_atts = ' data-scalar-x="'.$img_header_scalar_x.'" data-scalar-y="'.$img_header_scalar_y.'" data-limit-y="'.$img_header_limit_y.'" data-limit-x="'.$img_header_limit_x.'"';
								$img_header_parallaxify_layer_atts = 'position: absolute; z-index: 1; left: -'.$img_header_limit_y.'px; right: -'.$img_header_limit_y.'px; top: -'.$img_header_limit_x.'px; bottom: -'.$img_header_limit_x.'px;';
							}

							$image_options = $static_img_section["shop_header_image_options"];

							$default_img = false;
							$override_img = false;
							$img_url = '';

							$header_img_html = '';

							if ( isset( $image_options['src'] ) ){
								if ( $use_pattern && !empty( $pattern_image ) && isset( $pattern_image['src'] ) && !empty( $pattern_image['src'] ) ){
									$pattern_img_src = esc_url($pattern_image['src']);
									$header_img_html .= "<div class='bg-layer' style='background-image:url(" .
									$pattern_img_src . ");".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
								}
								if ( $color_overlay_type == 'color' && !empty( $overlay_color ) ){
									$header_img_html .= "<div class='bg-layer' style='background-color:" . esc_attr
									($overlay_color) . ";" . ( !empty( $color_overlay_opacity ) ? "opacity:".esc_attr($color_overlay_opacity).";" : '' ) . ";".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
								}
								else if ( $color_overlay_type == 'gradient' && !empty( $gradient_settings ) ){
									$gradient_rules = $this->cws_print_gradient( array( 'settings' => $gradient_settings ) );
									$header_img_html .= "<div class='bg-layer' style='$gradient_rules" . ( !empty(
									        $color_overlay_opacity ) ? "opacity:".esc_attr($color_overlay_opacity).";" : '' ) . ";".($img_header_parallaxify ? $img_header_parallaxify_layer_atts : '')."'></div>";
								}
							}

							if ( isset( $image_options['src'] ) ) {
								$header_img_fs_view = $img_header_height== false ? 'header-video-fs-view' : '';
								$header_img_height_coef = $img_header_height == false ? '' : " data-wrapper-height='".(960 / $img_header_height)."'";
								$img_header_height = $img_header_height == false ? '' : "style='height:" . esc_attr($img_header_height) ."px'";

								echo "<div class='fs-img-header " . sanitize_html_class( $header_img_fs_view ) ."' "
								. $img_header_height . " ". $header_img_height_coef .">";
								if($img_header_parallaxify){
									echo '<div class="cws-parallax-section" '.$img_header_parallaxify_atts.'>';
								}
									wp_enqueue_script ('parallax');
									if($img_header_parallaxify){
										echo '<div class="layer" data-depth="1.00">';
									}
										echo sprintf("%s", $header_img_html);
										echo "<div class='stat-img-cont' style='". ($img_header_parallaxify ?
										$img_header_parallaxify_layer_atts : '') ."background-image: url(".esc_url($image_options['src']).");background-size: cover;background-position: center center;'></div>";
										if($img_header_parallaxify){
											echo '</div></div>';
										}
								echo '</div>';

							}
						break;
						default:
					}
				} else {
					$slider_settings = $this->cws_get_meta_option( 'slider_override' );
					if ( isset($slider_settings['is_override']) && $slider_settings['is_override'] == '1' ){
						$slider_plugin_exist = true;

						$slider_shortcode = wp_specialchars_decode($slider_settings['slider_shortcode'], ENT_QUOTES);
						$shortcode_output = do_shortcode($slider_shortcode);

						$slider_plugin_exist = !($shortcode_output === $slider_shortcode); //Check shortcode & output the same

						if ($slider_settings['is_wide'] !== '1') {
							$shortcode_output = "<div class='slider-bg'><div class='container'>{$shortcode_output}</div></div>";
						}

						if ($slider_plugin_exist){
							echo sprintf("%s", $shortcode_output);
							$slider_isset = true;
						}

						$slider_error = strpos($shortcode_output, 'Revolution Slider Error') ? true : false;
					}
				}
			}
			else if ( is_single() ){}
			else if ( is_archive() ){}
		$slider_content = ob_get_clean();

		$args = array(
			'slider_content' => $slider_content,
			'slider_isset' => $slider_isset
		);

		ob_start();
			$this->cws_header_menu_and_logo($args);
		$header_content = ob_get_clean();

		wp_add_inline_script('img_loaded', 'window.header_after_slider=false;');

		//Get metaboxes from page
		//Header (General)
		$header = $this->cws_get_meta_option('header');
		extract($header, EXTR_PREFIX_ALL, 'header');

		ob_start();
                if ( isset($header_customize) && $header_customize == '1' && $header_overlay['type'] != 'none' ){
					echo "<div class='header-overlay'></div>";
				}
			?>

			<div class="header-zone"><!-- header-zone -->
				<?php


		$header_zone = ob_get_clean();
		$this->header['drop_zone_start'] = $header_zone;

		$header_outside_slider = $this->cws_get_meta_option('header')['outside_slider'] == '1';

		$header_wrapper_classes = '';
		if( ($header_outside_slider && $slider_isset && !$slider_error) || isset($header_outside_content) && $header_outside_content ){
			$header_wrapper_classes .= ' header-outside-slider';
		}

		//Render Header from parts
		ob_start();

		echo '<div class="megamenu_width"><div class="container"></div><div class="container wide-container"></div></div>';
		echo '<div class="header-wrapper-container'.$header_wrapper_classes.'">';
			echo sprintf("%s", $this->header['before_header']);
			if ( isset( $header_order ) ){
				foreach ($header_order as $key => $value){
					if (( $slider_isset && $value['val'] == 'title_box' )  ) continue;
					$header_val = $this->header[$value['val']];
					echo sprintf("%s", $header_val);
				}
			}
			echo sprintf("%s", $this->header['after_header']);
		echo '</div>';

		$header = ob_get_clean();
		echo sprintf("%s", $header);


		if (isset($slider_error) && $slider_error){
			$slider_content = "<div class='rev_slider_error'><div class='message'>Revolution Slider Error: Slider $slider_shortcode not found.</div></div>";
		}

		echo (!empty($slider_content) ? $slider_content : '');
	}

	public function cws_build_page_title($no_title, $title_box_spacings) {

		$post_type = get_post_type();

		$page_title_section_atts = '';
		$page_title_section_class = 'page-title';
		$page_title_section_class .= $no_title == '1' ? ' no-title' : '';
		$page_title_section_class .= $title_box_spacings ? (!empty($title_box_spacings['top']) || !empty($title_box_spacings['bottom'])) ? ' custom_spacing' : '' : '';
		$page_title_section_atts = !empty( $page_title_section_class ) ? " class='".esc_attr($page_title_section_class)."'" : '';
		$page_title_section_atts .= !empty( $page_title_section_styles ) ? " style='".esc_attr($page_title_section_styles)."'" : '';

		$page_title_container_styles = '';


		$woo_subtitle = $this->cws_get_option( 'woo_subtitle_content');
		if ($this->cws_is_woo() && is_shop() && !empty($woo_subtitle) ) {
		    $subtitle_content = !empty($woo_subtitle) ? $this->cws_get_meta_option('woo_subtitle_content') : '';
		} else {
		    $subtitle_content = !empty($this->cws_get_meta_option('title_box')['subtitle_content']) ? $this->cws_get_meta_option('title_box')['subtitle_content'] : '';
		}

		foreach ( $title_box_spacings as $key => $value ) {
			if ( !empty( $value ) ) {
				$page_title_container_styles .= "padding-".esc_attr($key).":".esc_attr($value)."px;";
				$page_title_section_atts .= " data-init-".esc_attr($key)."='".esc_attr($value)."'";
			}
		}

		$post_id = get_the_id();
		$post_meta = get_post_meta( $post_id, 'cws_mb_post' );
		$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
		$apply_color = isset($post_meta['apply_color']) && !empty($post_meta['apply_color']) ? $post_meta['apply_color'] : "";

		$post_title_color = isset($post_meta['post_title_color']) && !empty($post_meta['post_title_color']) ? $post_meta['post_title_color'] : "";
		$post_background_color = isset($post_meta['post_background_color']) && !empty($post_meta['post_background_color']) ? $post_meta['post_background_color'] : '';

		$page_title_container_styles = $this->cws_print_ne($page_title_container_styles, ' style="' . esc_attr($page_title_container_styles) . '"');
		$show_breadcrumbs = $this->cws_get_option( 'breadcrumbs' ) == '1';
		$page_title = $this->cws_get_page_title($post_type);

		$breadcrumbs = '';
		if ( $show_breadcrumbs ){
			$alt_bc = self::$cws_theme_config['alt_breadcrumbs'];
			reset($alt_bc);
			$key = key($alt_bc);
			if (!empty($alt_bc) && function_exists($key)) {
				$breadcrumbs = call_user_func_array($key, $alt_bc[$key]);
			} else {
				ob_start();
				metamax_dimox_breadcrumbs();
				$breadcrumbs = ob_get_clean();
			}
		}

		$title_box = $this->cws_get_meta_option('title_box');
		$title_box_settings = isset($title_box['hide_divider']) ? $title_box['hide_divider'] : "";

		$woo_customize_title = $this->cws_get_option('woo_customize_title');
		if($this->cws_is_woo() && !empty($woo_customize_title)){
			$title_box_settings = $this->cws_get_option('woo_hide_divider');
		}

		$logo_exists = false;

		$out = '';

		$header_center = ' header_center';
		$out .= '<section' . $page_title_section_atts . '>';
		$out .= '<div class="container' . $header_center . '"';
		$out .= $page_title_container_styles . '>';
		$out .= '<div class="title"><h1>'.wp_kses( $page_title, array(
			"b"			=> array(),
			"strong"	=> array(),
			"mark"		=> array(),
			"br"		=> array(),
			"em"		=> array(),
			"sup"		=> array(),
			"sub"		=> array()
		)).'</h1></div>';
		if( is_page() ){
		    $out .= '<div class="subtitle-content"><p>'.$subtitle_content.'</p></div>';
		}

		if(is_page() && !empty($post->post_excerpt)){
			$out .= "<div class='page-excerpt'>".$post->post_excerpt."</div>";
		}
		$out .= $breadcrumbs . '</div></section>';

		return $out;
	}

	public function cws_get_page_title($post_type) {
		$page_title = '';
		if ( is_404() ) {
			$page_title = self::$cws_theme_config['strings']['404'];
		} else if ( is_search() ) {
			$page_title = self::$cws_theme_config['strings']['search'];
		} else if ( is_front_page() ) {
			$page_title = self::$cws_theme_config['strings']['home'];
		} else if ( is_category() ) {
			$cat = get_category( get_query_var( 'cat' ) );
			$cat_name = isset( $cat->name ) ? $cat->name : '';
			$page_title = sprintf( self::$cws_theme_config['strings']['category'], $cat_name );
		} else if ( is_tag() ) {
			$page_title = sprintf( self::$cws_theme_config['strings']['tag'], single_tag_title( '', false ) );
		} elseif ( is_day() ) {
			$page_title = get_the_time( get_option('date_format') );
		} elseif ( is_month() ) {
			$page_title = get_the_time( 'F, Y' );
		} elseif ( is_year() ) {
			$page_title = get_the_time( 'Y' );
		} elseif ( has_post_format() && !is_singular() ) {
			$page_title = get_post_format_string( get_post_format() );
		} else if ( is_tax( array( 'cws_portfolio_cat', 'cws_staff_member_department', 'cws_staff_member_position' ) ) ) {
			$tax_slug = get_query_var( 'taxonomy' );
			$term_slug = get_query_var( $tax_slug );
			$tax_obj = get_taxonomy( $tax_slug );
			$term_obj = get_term_by( 'slug', $term_slug, $tax_slug );

			$singular_tax_label = isset( $tax_obj->labels ) && isset( $tax_obj->labels->singular_name ) ? $tax_obj->labels->singular_name : '';
			$term_name = isset( $term_obj->name ) ? $term_obj->name : '';
			$page_title = $singular_tax_label . ' ' . $term_name ;
		} elseif ( function_exists ( 'is_shop' ) && is_shop() ) {
  			$page_title = woocommerce_page_title(false);
		} elseif ( is_archive() ) {
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_name = isset( $post_type_obj->label ) ? $post_type_obj->label : '';
			$page_title = $post_type_name ;
		} else if ( $this->cws_is_woo() ) {
			if(is_cart()){
				$page_title = self::$cws_theme_config['strings']['cart'];
			}elseif(is_checkout()){
				$page_title = self::$cws_theme_config['strings']['checkout'];
			}else{
				$page_title = get_the_title();
			}
		} else if (substr($post_type, 0, 4) === 'cws_' ) {
			$slug_option = substr($post_type, 4) . '_slug'; // if post_type is cws_portfolio, this will turn it into portfolio_slug option name
			$portfolio_slug = $this->cws_get_option( $slug_option );
			$post_type_obj = get_post_type_object( $post_type );
			$post_type_name = $post_type_obj->labels->menu_name;
			$page_title = !empty($post_type_name) ? $post_type_name : $portfolio_slug;
		} else {
			$blog_title = $this->cws_get_option('blog_title');
			$page_title = (!is_page() && !empty($blog_title)) ? $blog_title : get_the_title();
		}
		return $page_title;
	}

	/* Social Links */
	public function cws_render_social_links($social_location = '', $social_place = 'left') {
		$out = '';
		$social = $this->cws_get_option('social'); //Call from ThemeOptions
		$location = isset($social['location']) ? $social['location'] : '';
		$icons = isset($social['icons']) ? $social['icons'] : '';
		$el_atts = '';

		if ((!empty($icons) && !empty($location) && in_array($social_location, $location))) {
			$menu_box_search_place = $this->cws_get_meta_option('menu_box')['search_place'];
			$side_panel_place = $this->cws_get_meta_option('side_panel')['place'];
			$links = null;

			$styles = '';
			foreach ( $icons as $icon ) {
				$icon_id = uniqid( "cws_social_icon_" );
				$title = esc_attr($icon['title']);
				$url = !empty($icon['url']) ? $icon['url'] : '#';
				$links .= "<a id='".esc_attr($icon_id)."' href='".esc_url($url)."' class='cws-social-link ".esc_attr($icon['icon'])."' title='".esc_attr($title)."' target='_blank'></a>";
				$styles .= "
					#top_social_links_wrapper .cws-social-links #".esc_attr($icon_id)."{
						color: ".esc_attr($icon['color']).";
						background-color : ".esc_attr($icon['bg_color']).";
					}

					#top_social_links_wrapper .cws-social-links #".esc_attr($icon_id).":hover{
						color: ".esc_attr($icon['hover_color']).";
						background-color : ".esc_attr($icon['hover_bg_color']).";
					}					
				";
			}
			Cws_shortcode_css()->enqueue_cws_css($styles);

			if ($links) {
				$social_class = ($menu_box_search_place == 'top' || $this->cws_get_option('woo_cart_place') == 'top' || $side_panel_place == 'topbar_right') ? 'social-divider' : '';
				$out = "<div ".( !empty( $el_atts ) ? $el_atts : '' )." class='cws-social-links ".esc_attr($social_class)."'>{$links}</div>";
			}
		}
		return $out;
	}
	/* \Social Links */

	//public function

	public function cws_header_menu_and_logo ($args = array() ) {
		extract($args);

		//Get metaboxes from page
		$top_bar_box = $this->cws_get_meta_option( 'top_bar_box' );
		extract($top_bar_box, EXTR_PREFIX_ALL, 'top_bar_box');

		$menu_box = $this->cws_get_meta_option( 'menu_box' );
		extract($menu_box, EXTR_PREFIX_ALL, 'menu_box');

		$sticky_menu = $this->cws_get_meta_option( 'sticky_menu' );
		extract($sticky_menu, EXTR_PREFIX_ALL, 'sticky_menu');

		$logo_box = $this->cws_get_meta_option( 'logo_box' );
		extract($logo_box, EXTR_PREFIX_ALL, 'logo_box');

		$side_panel = $this->cws_get_meta_option( 'side_panel' );
		extract($side_panel, EXTR_PREFIX_ALL, 'side_panel');

		$mobile_menu_box = $this->cws_get_meta_option( 'mobile_menu_box' );
		extract($mobile_menu_box, EXTR_PREFIX_ALL, 'mobile_menu_box');

		$woo_mini_cart = $this->cws_getWooMiniCart();
		$woo_mini_icon = $this->cws_getWooMiniIcon();

		/*** Logo Position ***/
		$header_class = 'site-header';
		$header_class .= $sticky_menu_enable ? ' sticky-enable sticky_'.$sticky_menu_mode : '';
		$header_class .= $sticky_menu_enable && $sticky_menu_shadow ? ' sticky-shadow' : '';

		$header_class .= !empty( $logo_box_position ) ? ' logo-'.esc_attr($logo_box_position) : '';
		$header_class .= !empty( $menu_box_position ) ? ' menu-'.esc_attr($menu_box_position) : '';
		$header_class .= !empty( $menu_box_menu_mode ) ? ' desktop-menu-'.esc_attr($menu_box_menu_mode) : '';
		$header_class .= !empty( $menu_box_sandwich ) ? ' active-sandwich-menu' : '';
		$header_class .= !empty( $logo_box_in_menu ) && $logo_box_in_menu == '1' ? ' logo-in-menu' : ' logo-not-in-menu';

		$social = $this->cws_get_option('social'); //Call from ThemeOptions
		$location = isset($social['location']) ? $social['location'] : '';
		$top_bar_box = $this->cws_get_meta_option( 'top_bar_box' );
		$social_place = $top_bar_box['social_place'];
		if (is_array($location)){
			$header_class .= in_array('top_bar', $location) || in_array('menu', $location) ? ' social-'. esc_attr($social_place) : '';
		} elseif (is_string($location)) {
			$header_class .= $location == 'top_bar' || $location == 'menu' ? ' social-'. esc_attr($social_place) : '';
		}
		/***** \Logo Position *****/

		/***** Menu Position *****/
		global $current_user;
		$menu_locations = get_nav_menu_locations();

		$show_wpml_menu = CWS_WPML_ACTIVE;
		/***** \Menu Position *****/

		$a_logos = array(); // array of main logo, mobile and sticky
		$bfi_args = $bfi_args_sticky = $bfi_args_mobile = $bfi_args_nav = array();
		if ($logo_box_enable) {
			ob_start();
				/***** Logo Settings *****/
				// TODO: need to add some filter to get proper logo in case there are more than one option
				$woo_customize_logotype = $this->cws_get_option('woo_customize_logotype');
				if($this->cws_is_woo() && !empty($woo_customize_logotype)){
					$logo_box_default = 'logo_woo';
				}

				$logo_class = '';
				$logo_lr_spacing = $logo_tb_spacing = $main_logo_height = '';

				if ($logo_box_default !== 'custom') {
					$logo = isset($this->cws_get_option( 'logo_box' )[$logo_box_default]) ? $this->cws_get_option( 'logo_box' )[$logo_box_default] : ''; //Call from ThemeOptions
				} else {
					$logo = isset($this->cws_get_meta_option( 'logo_box' )['custom']) ? $this->cws_get_meta_option( 'logo_box' )['custom'] : '';
				}

				$logo_woo = $this->cws_get_option( 'logo_woo' );
				if ($this->cws_get_option( 'woo_customize_logotype' ) == '1' && $this->cws_is_woo() ) {
					$logo = isset($logo_woo) ? $logo_woo : ''; //Call from ThemeOptions -> WooCommerce
				}

				$logo_box_mobile = isset($this->cws_get_meta_option( 'mobile_menu_box' )['mobile']) ?
				$this->cws_get_meta_option( 'mobile_menu_box' )['mobile'] : ''; //Call from ThemeOptions
				$logo_box_sticky = isset($this->cws_get_meta_option( 'sticky_menu' )['sticky']) ?
				$this->cws_get_meta_option(
				        'sticky_menu' )['sticky'] : ''; //Call from ThemeOptions
				$mobile_logo_nav = isset($this->cws_get_option( 'mobile_menu_box' )['navigation']) ? $this->cws_get_option( 'mobile_menu_box' )['navigation'] : ''; //Call from ThemeOptions


				$logo_box_dimensions_sticky = isset($this->cws_get_option( 'sticky_menu' )['dimensions_sticky']) ? $this->cws_get_option( 'sticky_menu' )['dimensions_sticky'] : ''; //Call from ThemeOptions
				$logo_box_dimensions_mobile = isset($this->cws_get_meta_option( 'mobile_menu_box' )['dimensions_mobile'])
				? $this->cws_get_meta_option( 'mobile_menu_box' )['dimensions_mobile'] : ''; //Call from ThemeOptions
				$mobile_logo_nav_dimensions = isset($this->cws_get_option( 'mobile_menu_box' )['dimensions_navigation']) ? $this->cws_get_option( 'mobile_menu_box' )['dimensions_navigation'] : ''; //Call from ThemeOptions

				$logo_exists = false;
				if ( !empty( $logo['src'] ) ) {
					$logo_exists = true;

					if ( !empty($logo_box_dimensions) && is_array( $logo_box_dimensions ) ) {
						foreach ( $logo_box_dimensions as $key => $value ) {
							if ( ! empty( $value ) ) {
								$bfi_args[ $key ] = $value;
							}
						}
					}
					if ( !empty($logo_box_dimensions_sticky) && is_array( $logo_box_dimensions_sticky ) ) {
						foreach ( $logo_box_dimensions_sticky as $key => $value ) {
							if ( ! empty( $value ) ) {
								$bfi_args_sticky[ $key ] = $value;
							}
						}
					}
					if ( !empty($logo_box_dimensions_mobile) && is_array( $logo_box_dimensions_mobile ) ) {
						foreach ( $logo_box_dimensions_mobile as $key => $value ) {
							if ( ! empty( $value ) ) {
								$bfi_args_mobile[ $key ] = $value;
							}
						}
					}
					if ( !empty($mobile_logo_nav_dimensions) && is_array( $mobile_logo_nav_dimensions ) ) {
						foreach ( $mobile_logo_nav_dimensions as $key => $value ) {
							if ( ! empty( $value ) ) {
								$bfi_args_nav[ $key ] = $value;
							}
						}
					}

					if(!empty($logo['src'])){
						$file_parts = pathinfo($logo['src']);

						if($file_parts['extension'] == 'svg'){
							$a_logos['logo']['svg'] = $this->cws_print_svg_html($logo, $bfi_args, $main_logo_height);
						}else{
							$a_logos['logo']['img'] = $this->cws_print_img_html($logo, $bfi_args, $main_logo_height);
						}
					}

					$logo_lr_spacing = $logo_tb_spacing = '';
					if ( is_array( $logo_box_margin ) ) {
						$logo_lr_spacing = $this->cws_print_css_keys($logo_box_margin, 'margin-', 'px');
						$logo_tb_spacing = $this->cws_print_css_keys($logo_box_margin, 'padding-', 'px');
					}

					if (!empty($main_logo_height)) {
						$logo_lr_spacing .= "height:{$main_logo_height}px;";
						$main_logo_height = " style='height:{$main_logo_height}px;'";
						$a_logos['logo_h'] = $main_logo_height;
					}
				}

				/***** \Logo Settings *****/
				$logo_sticky_src = array();
				if ( !empty($logo_box_sticky['src']) ) {
					$file_parts_sticky = pathinfo($logo_box_sticky['src']);

					if($file_parts_sticky['extension'] == 'svg'){
						$logo_sticky_src['svg'] = $this->cws_print_svg_html($logo_box_sticky, $bfi_args);
					}else{
						$logo_sticky_src['img'] = $this->cws_print_img_html($logo_box_sticky['id'], (!empty($bfi_args_sticky) ? $bfi_args_sticky : null));
					}
					$logo_class .= ' custom_sticky_logo';
				}

				$logo_mobile_src = array();
				if ( !empty( $logo_box_mobile['src']) ) {
					$file_parts_mobile = pathinfo($logo_box_mobile['src']);

					if($file_parts_mobile['extension'] == 'svg'){
						$logo_mobile_src['svg'] = $this->cws_print_svg_html($logo_box_mobile, $bfi_args);
					}else{
						$logo_mobile_src['img'] = $this->cws_print_img_html($logo_box_mobile['id'], (!empty($bfi_args_mobile) ? $bfi_args_mobile : null));
					}
					$logo_class .= ' custom_mobile_logo';
				}

				$logo_mobile_nav_src = array();
				if ( !empty( $mobile_logo_nav['src']) ) {
					$file_parts_mobile_nav = pathinfo($mobile_logo_nav['src']);

					if($file_parts_mobile_nav['extension'] == 'svg'){
						$logo_mobile_nav_src['svg'] = $this->cws_print_svg_html($mobile_logo_nav, $bfi_args);
					}else{
						$logo_mobile_nav_src['img'] = $this->cws_print_img_html($mobile_logo_nav['id'], (!empty($bfi_args_nav) ? $bfi_args_nav : null));
					}
					$logo_class .= ' custom_nav_logo';
				}

				$a_logos['mobile'] = $logo_mobile_src;
				$a_logos['sticky'] = $logo_sticky_src;
				$a_logos['navigation'] = $logo_mobile_nav_src;

				//Logo box
				$esc_blog_name = esc_html(get_bloginfo('name'));

				$logo_box_class = 'logo-box';
				$logo_box_class .= ' header-logo-part';

				if ( (isset($logo_box_in_menu) && $logo_box_in_menu != '1') ) : ?>
				<!-- logo_box -->
					<div class="<?php echo esc_attr($logo_box_class); ?>">

						<div class="container<?php if( $logo_box_in_menu != '1' && $logo_box_wide == '1' ){ echo ' wide-container'; } ?>">
							<?php

							if (isset($logo_box_in_menu) && $logo_box_in_menu != '1'){
								if ($logo_exists){
									$printed_logo = $this->cws_print_logo_block($sticky_menu_enable, $logo_lr_spacing, $a_logos, $esc_blog_name);
									echo sprintf('%s', $print_logo);
								} else {
								?>
									<h1 class='header-site-title'><?php echo esc_html($esc_blog_name); ?></h1>
								<?php
								}
							}

							?>
						</div>
					</div>
				<?php endif; ?>
				<!-- /logo-box -->
			<?php
			$logo_box = ob_get_clean();
		}

		$this->header['logo_box'] = $logo_box;

		ob_start();

			echo "<div class='header_cont'>"; ?>
				<header <?php echo !empty($header_class) ? "class='".esc_attr($header_class)."'" : ''; ?>><!-- header -->
					<div class="header-container"><!-- header-container -->
						<?php
							$before_header_content = ob_get_clean();
							$this->header['before_header'] = $before_header_content;

							$menu_box_content = '';

							if ($menu_box_enable){

							ob_start();

							$menu_style = '';
							$menu_class = 'menu-box';
							$menu_class .= (isset($menu_box_border['line']) && $menu_box_border['line'] == '1' ? ' border_line' : '');
							$menu_attr = " class='".esc_attr($menu_class)."' ".(!empty($menu_style) ? 'style="'.$menu_style.'"' : '').'';
						?>

						<!-- menu-box -->
						<div<?php echo (!empty( $menu_attr )) ? $menu_attr : ''; ?>>
							<div class="container<?php if( $menu_box_wide == '1' ){ echo ' wide-container'; } ?>">
								<?php

									$custom_menu = isset($menu_box_override_menu) && $menu_box_override_menu == '1' ? $menu_box_custom_menu : '';

									if ( !empty($menu_locations['header-menu']) || !empty($custom_menu) ) {
								?>

									<div class="header-nav-part">
										<div class="menu-overlay"></div>
										<nav class="main-nav-container">
											<?php

												/*NEW MENU*/

												// ------> Mobile logo
												echo '<div class="logo-mobile-wrapper">';
													$printed_logo_mobile = $this->cws_print_logo_mobile($a_logos);
													echo sprintf('%s', $printed_logo_mobile);
												echo '</div>';

												// ------> Menu left
												echo '<div class="menu-left-icons">';
													// ------> Side panel
													if ($side_panel_place == 'menu_left' && $side_panel_enable){
														echo "<div class='side-panel-icon-wrapper'>";
															echo "<a href='#javascript' class='side-panel-trigger ".esc_attr($side_panel_place)."'></a>";
														echo "</div>";
													}

													// ------> Menu hamburger
													if ($menu_box_mobile_place == "left"){
														echo "<div class='mobile-menu-hamburger left'>";
															echo "<span class='hamburger-icon'></span>";
														echo "</div>";
													}

													// ------> Woo mini-cart
													if ( (class_exists('woocommerce') && $this->cws_get_option('woo_cart_place') == 'left') ){
														echo "<div class='mini-cart'>";
															echo sprintf('%s', $woo_mini_icon);
															echo sprintf('%s', $woo_mini_cart);
														echo "</div>";
													}

													// ------> Search
													if($menu_box_search_place == 'left'){
														echo "<div class='search-icon'></div>";
													}
												echo '</div>';

												// ------> Menu center
												ob_start();

													echo "<div class='menu-box-wrapper'>";

														if (($logo_box_position == "left" || $logo_box_position == "right") && $logo_box_enable && (isset($logo_box_in_menu) && $logo_box_in_menu == '1')) :
														?>
															<div class="menu-logo-part">
																<?php if ($logo_exists) {
																	$printed_logo_block = $this->cws_print_logo_block($sticky_menu_enable, $logo_lr_spacing, $a_logos, $esc_blog_name);
																	echo sprintf('%s', $printed_logo_block);

																	if( !empty($mobile_logo_nav['src']) ){
																		$printed_mobile_nav = $this->cws_pring_logo_nav($a_logos);
																		echo sprintf('%s', $printed_mobile_nav);
																	}

																}else{ ?>
																	<h1 class='header-site-title'><?php bloginfo( 'name' ) ?></h1>
																<?php } ?>
															</div>

														<?php
														endif;

														wp_nav_menu( array(
															'menu_id'  => 'main_menu',
															'theme_location' => (!empty($custom_menu) ? '' : 'header-menu'),
															'menu' => (!empty($custom_menu) ? $custom_menu : ''),
															'menu_class' => 'main-menu',
															'items_wrap'      => '<div class="'.( $logo_box_position == 'center' && $logo_box_in_menu == '1' && $logo_box_enable ? 'menu-left-part' : 'no-split-menu' ).'"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
															'container' => false,
															'walker' => new Metamax_Walker_Nav_Menu($this)
														) );
													echo "</div>";

												$menu = ob_get_clean();
												printf('%s', $menu);

												// ------> Menu right
												echo '<div class="menu-right-icons">';
													// ------> Search
													if ($menu_box_search_place == 'right'){
														echo "<div class='search-icon'></div>";
													}

													// ------> Woo mini-cart
													if ( (class_exists('woocommerce') && $this->cws_get_option('woo_cart_place') == 'right') ){
														echo "<div class='mini-cart'>";
															echo sprintf('%s', $woo_mini_icon);
															echo sprintf('%s', $woo_mini_cart);
														echo "</div>";
													}

													// ------> Menu hamburger
													if ($menu_box_mobile_place == "right"){
														echo "<div class='mobile-menu-hamburger right'>";
															echo "<span class='hamburger-icon'></span>";
														echo "</div>";
													}

													// ------> Side panel
													if ($side_panel_place == 'menu_right' && $side_panel_enable){
														echo "<div class='side-panel-icon-wrapper'>";
															echo "<a href='#javascript' class='side-panel-trigger ".esc_attr($side_panel_place)."'></a>";
														echo "</div>";
													}
												echo '</div>';

												/*\NEW MENU*/
											?>

										</nav>
									</div>

								<?php
									}
								?>
							</div>
						</div>

						<?php
							$menu_box_content = ob_get_clean();
						}

						$this->header['menu_box'] = $menu_box_content;

						ob_start();
						?>

					</div><!-- header-container -->
				</header><!-- header -->
		<?php

				echo "<div class='site-search-wrapper'>";
					echo "<span class='close-search'></span>";
					get_search_form();
				echo '</div>';

			echo '</div>';

		$after_header_content = ob_get_clean();

		$this->header['after_header'] = $after_header_content;
	}

	public function cws_print_logo_block($sticky_menu_enable, $logo_lr_spacing, $lg, $blog_name) {
		extract(shortcode_atts( array(
			'logo' => '',
			'sticky' => '',
			'logo_h' => '',
		), $lg));

		$spacing = !empty( $logo_lr_spacing ) ? " style='{$logo_lr_spacing}'" : '';
		$out = sprintf( '<a%s class="logo" href="%s">', $spacing, esc_url(home_url('/')) );

		if(!empty($logo)){
			$out .= "<div class='logo-default-wrapper logo-wrapper'>";
			foreach ($logo as $key => $value) {
		    	switch ($key){
			        case 'img' :
			        	$out .= $this->cws_print_if( !empty($logo[$key]), "<img ".$logo[$key]." ".$logo_h." class='logo-default' />");
			            break ;
			        case 'svg' :
			            $out .= $logo[$key];
			            break ;
		    	}
		 	}
		 	$out .= "</div>";
		}

		if(!empty($sticky) && is_array($sticky)){

			$out .= "<div class='logo-sticky-wrapper logo-wrapper'>";
			foreach ($sticky as $key => $value) {
		    	switch ($key){
			        case 'img' :
			            $out .= $this->cws_print_if( !empty($sticky[$key]), "<img ".$sticky[$key]." class='logo-sticky' />");
			            break ;
			        case 'svg' :
			        	$out .= "<span class='logo-sticky cws-svg-sticky'>";
			            $out .= $sticky[$key];
			            $out .= "</span>";
			            break ;
		    	}
		 	}
		 	$out .= "</div>";
		}

		$out .= '</a>';
		return $out;
	}

	public function cws_print_logo_mobile($lg) {
		extract(shortcode_atts( array(
			'mobile' => '',
		), $lg));

		$out = sprintf( '<a class="logo" href="%s">', esc_url(home_url('/')) );

		if(!empty($mobile) && is_array($mobile)){
		 	foreach ($mobile as $key => $value) {
		    	switch ($key){
			        case 'img' :
			            $out .= $this->cws_print_if( !empty($mobile[$key]), "<img ".$mobile[$key]." class='logo-mobile' />");
			            break ;
			        case 'svg' :
			        	$out .= "<span class='logo-mobile cws-svg-mobile'>";
			            $out .= $mobile[$key];
			            $out .= "</span>";
			            break ;
		    	}
		 	}
		}

		$out .= '</a>';
		return $out;
	}

	public function cws_pring_logo_nav($lg) {
		extract(shortcode_atts( array(
			'navigation' => '',
		), $lg));

		$out = sprintf( '<a class="logo logo-nav" href="%s">', esc_url(home_url('/')) );

		if(!empty($navigation) && is_array($navigation)){

			$out .= "<div class='logo-nav-wrapper logo-wrapper'>";
		 	foreach ($navigation as $key => $value) {
		    	switch ($key){
			        case 'img' :
			            $out .= $this->cws_print_if( !empty($navigation[$key]), "<img ".$navigation[$key]." class='logo-navigation' />");
			            break ;
			        case 'svg' :
			        	$out .= "<span class='navigation cws-svg-navigation'>";
			            $out .= $navigation[$key];
			            $out .= "</span>";
			            break ;
		    	}
		 	}
		 	$out .= "</div>";
		}

		$out .= '</a>';
		return $out;
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

	public function cws_get_date_part ( $part = '' ){
		$part_val = '';
		$p_id = get_queried_object_id();
		$perm_struct = get_option( 'permalink_structure' );
		$use_perms = !empty( $perm_struct );
		$merge_date = get_query_var( 'm' );
		$match = preg_match( '#(\d{4})?(\d{1,2})?(\d{1,2})?#', $merge_date, $matches );
		switch ( $part ){
			case 'y':
				$part_val = $use_perms ? get_query_var( 'year' ) : ( isset( $matches[1] ) ? $matches[1] : '' );
				break;
			case 'm':
				$part_val = $use_perms ? get_query_var( 'monthnum' ) : ( isset( $matches[2] ) ? $matches[2] : '' );
				break;
			case 'd':
				$part_val = $use_perms ? get_query_var( 'day' ) : ( isset( $matches[3] ) ? $matches[3] : '' );
				break;
		}
		return $part_val;
	}

	public function cws_render_top_bar($pid) {
		//Get metaboxes from page
		$top_bar_box = $this->cws_get_meta_option( 'top_bar_box' );
		extract($top_bar_box, EXTR_PREFIX_ALL, 'top_bar_box');

		$logo_box = $this->cws_get_meta_option( 'logo_box' );
		extract($logo_box, EXTR_PREFIX_ALL, 'logo_box');

		$menu_box = $this->cws_get_meta_option( 'menu_box' );
		extract($menu_box, EXTR_PREFIX_ALL, 'menu_box');

		$side_panel = $this->cws_get_meta_option( 'side_panel' );
		extract($side_panel, EXTR_PREFIX_ALL, 'side_panel');

		ob_start();

			/*NEW TOP BAR*/
			if($top_bar_box_enable){

				// ------> Register variables
				$social_links  			= '';
				$woo_mini_cart 			= '';
				$woo_mini_icon			= '';
				$show_wpml_header 		= CWS_WPML_ACTIVE;
				$is_woo_active 			= class_exists('woocommerce');
				$top_bar_box_text 		= isset($top_bar_box_text) ? stripslashes($top_bar_box_text) : '';
				$social_links 			= $this->cws_render_social_links('top_bar', $top_bar_box_social_place);
				if($is_woo_active){
					$woo_mini_cart 		= $this->cws_getWooMiniCart();
					$woo_mini_icon 		= $this->cws_getWooMiniIcon();
				}

				// ------> Render Top-Bar
				if( !empty($social_links) || !empty($top_bar_box_text) || $top_bar_box_language_bar || ($is_woo_active && $this->cws_get_option('woo_cart_place') == 'top') || !empty($top_bar_box_content_items) ){
					echo "<div class='top-bar-wrapper'>";
						echo "<div class='top-bar-inner-wrapper'>";
							echo "<div class='container" . ( $top_bar_box_wide ? ' wide-container ' : '' ) . "'>";
								echo "<div class='top-bar-icons left-icons'>";
									// ------> Side panel
									if ($side_panel_place == 'topbar_left' && $side_panel_enable){
										echo "<div class='side-panel-icon-wrapper'>";
											echo "<a href='#' class='side-panel-trigger ".esc_attr($side_panel_place)."'></a>";
										echo "</div>";
									}
									// ------> Top bar links
									if (!empty($top_bar_box_content_items) ){
										echo "<div class='top-bar-links-wrapper'>";
											foreach ($top_bar_box_content_items as $key => $value) {
												if( !empty($value['icon']) ){
													$top_bar_box_row_icon = "<i class='".esc_attr($value['icon'])."'></i>";
												}
												if ( !empty($value['url']) ){
													if ( $value['link_type'] != 'link' && !empty($value['link_type']) ){
														echo "<a class='top-bar-box-text' href='".esc_attr($value['link_type']).esc_attr($value['url'])."'>".$top_bar_box_row_icon."<span>".esc_html($value['title'])."</span></a>";
													} elseif ( $value['link_type'] == 'link' ){
														echo "<a class='top-bar-box-text' href='".esc_attr($value['url'])."'>".$top_bar_box_row_icon."<span>".esc_html($value['title'])."</span></a>";
													}
												} else {
													echo "<div class='top-bar-box-text'>".$top_bar_box_row_icon.esc_html($value['title'])."</div>";
												}
											}
										echo "</div>";
									}
									// ------> Language switcher
									if ( $show_wpml_header && $top_bar_box_language_bar && $top_bar_box_language_bar_position == 'left') {
										echo "<div class='lang_bar'>";
											do_action( 'icl_language_selector' );
										echo "</div>";
									}
									// ------> Social links
									if ( $top_bar_box_social_place == 'left' && !empty($social_links) ){
										echo "<div class='social-links-wrapper" . ( $top_bar_box_toggle_share ? ' toogle-of' : ' toogle-off' ) . "'>";
											if( $top_bar_box_toggle_share ){
												echo "<i class='social-btn-open-icon'></i><span class='social-btn-open'>Social</span>";
											}
											echo sprintf("%s", $social_links );
										echo "</div>";
									}
								echo "</div>";

								echo "<div class='top-bar-icons right-icons'>";
								    // ------> Top bar content
									if( !empty($top_bar_box_text) ){
										echo "<div class='top-bar-content'>";
											if( !empty($top_bar_box_text) ){
												echo "<div class='top_bar_text'>";
													echo sprintf('%s', $top_bar_box_text);
												echo "</div>";
											}

										echo "</div>";
									}
									// ------> Social links
									if ( $top_bar_box_social_place == 'right' && !empty($social_links) ){
										echo "<div class='social-links-wrapper" . ( $top_bar_box_toggle_share ? ' toogle-of' : ' toogle-off' ) . "'>";
											echo sprintf("%s", $social_links );
											if( $top_bar_box_toggle_share ){
												echo "<i class='social-btn-open-icon'></i><span class='social-btn-open'>Social</span>";
											}
										echo "</div>";
									}
									// ------> Language switcher
									if ( $show_wpml_header && $top_bar_box_language_bar && $top_bar_box_language_bar_position == 'right') {
										echo "<div class='lang_bar'>";
											do_action( 'icl_language_selector' );
										echo "</div>";
									}
									// ------> Woo mini-cart
									if ( ($is_woo_active && $this->cws_get_option('woo_cart_place') == 'top') ){
										echo "<div class='mini-cart'>";
											echo sprintf('%s', $woo_mini_icon);
											echo sprintf('%s', $woo_mini_cart);
										echo "</div>";
									}
									// ------> Search
									if($menu_box_search_place == 'top'){
										echo "<div class='top-bar-search'>";
											echo "<div class='row-text-search'>";
												get_search_form();
											echo '</div>';
											echo "<div class='search-icon'></div>";
										echo '</div>';
									}
									// ------> Side panel
									if ($side_panel_place == 'topbar_right' && $side_panel_enable){
										echo "<div class='side-panel-icon-wrapper'>";
											echo "<a href='#' class='side-panel-trigger ".esc_attr($side_panel_place)."'></a>";
										echo "</div>";
									}
								echo "</div>";
							echo "</div>";
						echo "</div>";

						// ------> Topbar trigger
						echo "<div class='topbar-trigger'></div>";
					echo "</div>";
				}
			}
			/*\\NEW TOP BAR*/

		return ob_get_clean();
	}
	/* /THEME HEADER */

	// Add menu custom fields
	public function cws_add_custom_nav_fields( $menu_item ) {
		$cws_mb_post = get_post_meta( $menu_item->ID, 'cws_mb_post', true );
		if (isset($cws_mb_post['cws_menu'])) {
			foreach ($cws_mb_post['cws_menu'] as $key => $value) {
				$menu_item->$key = $value;
			}
		}
		return $menu_item;
	}

	// Save menu custom fields
	public function cws_update_custom_nav_fields( $menu_id, $menu_post_id, $args ) {
		if(isset($_POST['cws_menu_options'])) {
			parse_str(urldecode($_POST['cws_menu_options']), $parse_array);
			$save_array = array();
			foreach ($parse_array as $k => $value) {
				list($key, $id) = explode('-', $k);
				if ($id == $menu_post_id) {
					$save_array[$key] = $value;
				}
			}
			update_post_meta( $menu_post_id, 'cws_mb_post', array('cws_menu' => $save_array));
		}
	}

	// Edit menu custom fields
	public function cws_edit_walker( $walker,$menu_id ) {
		return 'Walker_Nav_Menu_Edit_Custom';
	}

	// Add inline style
	public function cws_add_style() {
		$out = $this->cws_theme_header_process_fonts();
		$out .= $this->cws_theme_header_process_colors();
		$out .= $this->cws_theme_loader();

//		 $this->cws_generate_default_css($out); //Uncomment this line if you need regenerate default style sheet
		wp_add_inline_style('cws-main-inline-styles', $out);
	}

	// Add responsive style
	public function cws_responsive_styles($styles, $resolution, $group) {
		foreach ($styles as $key => $value) {

			if( isset($value['param_name']) ){
				$value['param_name'] .= '_'.$resolution;
			}
			if( isset($value['dependency']) && isset($value['dependency']['element']) ){
				if( !isset($value['dependency']['resize']) ){
					$value['dependency']['element'] .= '_'.$resolution;
				}
			}
			if( isset($value['group']) ){
				$value['group'] = $group;
			}
			if( isset($value['responsive']) && ($value['responsive'] == $resolution || $value['responsive'] == 'all') ){
				$new_styles[] = $value;
			}
		}

		return $new_styles;
	}

	//Generate default css
	public function cws_generate_default_css($style) {
		global $wp_filesystem;

		$wp_filesystem->put_contents(
		get_template_directory(). '/css/default.css',
			$style,
			FS_CHMOD_FILE
		);
	}

	public function cws_Hex2RGBA( $hex, $opacity = '1' ) {
		$hex = str_replace('#', '', $hex);
		$color = '';

		if(strlen($hex) == 3) {
			$color = hexdec(substr($hex, 0, 1 )) . ',';
			$color .= hexdec(substr($hex, 1, 1 )) . ',';
			$color .= hexdec(substr($hex, 2, 1 )) . ',';
		}
		else if(strlen($hex) == 6) {
			$color = hexdec(substr($hex, 0, 2 )) . ',';
			$color .= hexdec(substr($hex, 2, 2 )) . ',';
			$color .= hexdec(substr($hex, 4, 2 )) . ',';
		}
		$color .= $opacity;
		return "rgba($color)";
	}

	public function cws_theme_youtube_api_init (){
		wp_add_inline_script('yt-player-api', '
			var tag = document.createElement("script");
			tag.src = "https://www.youtube.com/player_api";
			var firstScriptTag = document.getElementsByTagName("script")[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		');
	}

	public function cws_dbl_to_sngl_quotes ( $content ) {
		return preg_replace( "|\"|", "'", $content );
	}

	public function cws_loading_body_class ( $classes ) {
		$classes[] = '';
		$classes[] .= 'metamax-new-layout';

		return $classes;
	}

	public function cws_boxed_body ( $classes ) {
		$classes[] = '';

		$boxed = $this->cws_get_meta_option("boxed");

		if( (isset($boxed) && $boxed == '1') || (isset($boxed['layout']) && $boxed['layout'] == '1') ){
			$classes[] .= 'is-boxed';
		}

		return $classes;
	}

	public function cws_custom_search ( $form ) {
		$form = "
		<form method='get' class='search-form' action=' ".home_url( '/' )." ' >
			<div class='search-wrapper'>
				<label><span class='screen-reader-text'>".esc_html__( 'Search for:', 'metamax' )."</span></label>
				<input type='text' placeholder='".esc_attr__( 'Search', 'metamax' )."' class='search-field' value='".
				esc_attr(apply_filters('the_search_query', get_search_query())) ."' name='s'/>
				<button type='submit' class='search-submit'>".esc_html__( 'Search', 'metamax' )."</button>
			</div>
		</form>";

		return $form;
	}

	// Custom filter function to modify default gallery shortcode output
	public function cws_custom_gallery( $output, $attr ) {

		// Initialize
		global $post, $wp_locale;

		// Gallery instance counter
		static $instance = 0;
		$instance++;

		// Validate the author's orderby attribute
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( ! $attr['orderby'] ) unset( $attr['orderby'] );
		}

		// Get attributes from shortcode
		extract( shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'div',
			'icontag'    => 'div',
			'captiontag' => 'div',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => ''
		), $attr ) );

		wp_enqueue_script( 'fancybox' );

		// Initialize
		$id = intval( $id );
		$attachments = array();
		if ( $order == 'RAND' ) $orderby = 'none';

		if ( ! empty( $include ) ) {

			// Include attribute is present
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$_attachments = get_posts( array( 'include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );

			// Setup attachments array
			foreach ( $_attachments as $key => $val ) {
				$attachments[ $val->ID ] = $_attachments[ $key ];
			}

		} else if ( ! empty( $exclude ) ) {

			// Exclude attribute is present
			$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );

			// Setup attachments array
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		} else {
			// Setup attachments array
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby ) );
		}

		if ( empty( $attachments ) ) return '';

		// Filter gallery differently for feeds
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) $output .= wp_get_attachment_link( $att_id, $size, true ) . "\n";
			return $output;
		}

		// Filter tags and attributes
		$itemtag = tag_escape( $itemtag );
		$captiontag = tag_escape( $captiontag );
		$columns = intval( $columns );
		$itemwidth = $columns > 0 ? round(100 / $columns, 2) : 100;
		$float = is_rtl() ? 'right' : 'left';
		$selector = "gallery-{$instance}";

		// Filter gallery CSS
		$output = apply_filters( 'gallery_style', "
			<!-- see gallery_shortcode() in wp-includes/media.php -->
			<div id='$selector' class='gallery galleryid-{$id}'>"
		);

		// Iterate through the attachments in this gallery instance
		$i = 0;
		foreach ( $attachments as $id => $attachment ) {

			// Attachment link
			$link = isset( $attr['link'] ) && 'file' == $attr['link'] ? wp_get_attachment_link( $id, $size, false, false ) : wp_get_attachment_link( $id, $size, true, false );

			if ( isset($attr['link']) && $attr['link'] == 'none') {
				$link = preg_replace("/<a[^>]*>([^|]*)<\/a>/", "<div>$1</div>", $link);
			}

			// Start itemtag
			$output .= "<{$itemtag} class='gallery-item' style='float: {$float}; width: {$itemwidth}%;'>";

			// icontag
			$output .= "
			<{$icontag} class='gallery-icon'>
				$link
			</{$icontag}>";

			if ( $captiontag && trim( $attachment->post_excerpt ) ) {

				// captiontag
				$output .= "
				<{$captiontag} class='gallery-caption'>
					" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";

			}

			// End itemtag
			$output .= "</{$itemtag}>";

			// Line breaks by columns set
			if($columns > 0 && ++$i % $columns == 0) $output .= '<br>';

		}

		// End gallery output
		$output .= "
			<br>
		</div>\n";

		return $output;
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
			$widget_output = preg_replace('|<\/a>.*\(|', '<span class="post-count">', $widget_output);
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

	public function cws_oembed_wrapper( $html, $url, $args ) {
		return !empty( $html ) ? "<div class='cws-oembed-wrapper'>$html</div>" : '';
	}

	public function cws_custom_excerpt_length( $length ) {
		return 1400;
	}

	public function cws_ajaxurl() {
		wp_localize_script('cws-scripts', 'ajaxurl', array(
			'templateDir' => esc_url( get_template_directory_uri() ),
			'url' => admin_url( 'admin-ajax.php' ),
		));
	}

	public function cws_ajax_redirect() {
		$ajax = isset( $_POST['ajax'] ) ? (bool)$_POST['ajax'] : false;
		if ( $ajax ) {
			$template = isset( $_POST['template'] ) ? $_POST['template'] : '';
			if ( !empty( $template ) ) {
				if ( strpos( $template, '-' ) ) {
					$template_parts = explode( '-', $template );
					if ( count( $template_parts ) == 2 ) {
						get_template_part( $template_parts[0], $template_parts[1] );
					}
					else {
						return;
					}
				}	else {
					get_template_part( $template );
				}
				exit();
			}
		}
		return;
	}

	public function cws_meta_vars() {}

	public function cws_render_gradient ($arrs) {
		$gradient = array(
			'first_color' => (!empty($arrs[ 'first_color' ]) ? $arrs[ 'first_color' ] : ''),
			'second_color' => (!empty($arrs[ 'second_color' ]) ? $arrs[ 'second_color' ] : ''),
			'first_color_opacity' => (!empty($arrs[ 'first_color_opacity' ]) ? $arrs[ 'first_color_opacity' ] : ''),
			'second_color_opacity' => (!empty($arrs[ 'second_color_opacity' ]) ? $arrs[ 'second_color_opacity' ] : ''),
			'type' => (!empty($arrs[ 'type' ]) ? $arrs[ 'type' ] : ''),
			'linear_settings' => (!empty($arrs[ 'linear_settings' ]) ? $arrs[ 'linear_settings' ] : ''),
			'radial_settings' => array(
				'shape_settings' => (!empty($arrs['radial_settings']['shape_settings']) ? $arrs['radial_settings']['shape_settings'] : ''),
				'shape' => (!empty($arrs['radial_settings']['shape']) ? $arrs['radial_settings']['shape'] : ''),
				'size_keyword' => (!empty($arrs['radial_settings']['size_keyword']) ? $arrs['radial_settings']['size_keyword'] : ''),
				'size' => (!empty($arrs['radial_settings']['size']) ? $arrs['radial_settings']['size'] : ''),
			)
		);
		return $gradient;
	}

	public function cws_get_grid_shortcodes() {
		return array( 'cws-row', 'col', 'cws-widget' );
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

	public function cws_post_format_mark() {
		global $post;
		$out = '';
		if ( isset( $post ) ) {
			$pf = get_post_format();
			$post_format_icons = array(
				'aside'     => 'fas fa-bullseye',
				'gallery'   => 'fas fa-bullseye',
				'link'      => 'fas fa-link',
				'image'     => 'far fa-image',
				'quote'     => 'fas fa-quote-left',
				'status'    => 'fas fa-flag',
				'video'     => 'fas fa-video',
				'audio'     => 'fas fa-music',
				'chat'      => 'fab fa-weixin',
			);
			$icon = '';
			if (isset($post_format_icons[$pf])) {
				$icon = $post_format_icons[$pf];
			}
			$out = "<i class='$icon'></i> $pf";
		}
		return $out;
	}

	public function cws_strip_grid_shortcodes($text) {
		$shortcodes = function_exists('metamax_get_grid_shortcodes') ? metamax_get_grid_shortcodes () : "";
		$find = array();
		if(!empty($shortcodes)){
			foreach ( $shortcodes as $shortcode ) {
				$shortcode = preg_replace( "|-|", "\-", $shortcode );
				$op_tag = "|\[.*" . $shortcode . ".*\]|";
				$cl_tag = "|\[/.*" . $shortcode . ".*\]|";
				array_push( $find, $op_tag, $cl_tag );
			}
		}

		$text = preg_replace( $find, '', $text );
		return $text;
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
		$args['posts_per_page'] = $this->cws_get_option( 'woo-resent-num-products' ); // 4 related products
		$args['columns'] = 3; // arranged in 2 columns
		return $args;
	}

	/************** JAVASCRIPT VARIABLES INIT **************/
	public function cws_js_vars_init() {
		//Get metaboxes from page
		$sticky_menu = $this->cws_get_meta_option( 'sticky_menu' );
		extract($sticky_menu, EXTR_PREFIX_ALL, 'sticky_menu');

		$sticky_menu_enable = $sticky_menu_enable == '1' ? 'true' : 'false';

		$is_user_logged = is_user_logged_in();
		$logged_var = $is_user_logged ? 'true' : 'false';

		$sticky_sidebars = $this->cws_get_option('sticky_sidebars') == '1' ? 'true' : 'false';
		$page_loader = $this->cws_get_option('show_loader') == '1' ? 'true' : 'false';
		$animation_curve_menu = $this->cws_get_option('animation_curve_menu');
		$animation_curve_scrolltop = $this->cws_get_option('animation_curve_scrolltop');
		$animation_curve_speed = $this->cws_get_option('animation_curve_speed');

		//Don't forget boolean value without ''
		wp_add_inline_script('cws-scripts', '
			var is_user_logged = '.esc_js($logged_var).','.
			'sticky_menu_enable = '.esc_js($sticky_menu_enable).','.
			'sticky_menu_mode = "'.esc_js($sticky_menu_mode).'",'.
			'sticky_sidebars = '.esc_js($sticky_sidebars).','.
			'page_loader = '.esc_js($page_loader).','.
			'animation_curve_menu = "'.esc_js($animation_curve_menu).'",'.
			'animation_curve_scrolltop = "'.esc_js($animation_curve_scrolltop).'",'.
			'animation_curve_speed = '.esc_js($animation_curve_speed).';'
		);
	}
	/************** \JAVASCRIPT VARIABLES INIT **************/

	/******************** TYPOGRAPHY ********************/
	// MENU FONT HOOK
	private function cws_print_font_css($font_array) {
		$out = '';
		foreach ($font_array as $style=>$v) {
			if ($style != 'font-weight' && $style != 'font-sub' && $style != 'font-type') {
				$out .= !empty($v) ? $style .':'.$v.';' : '';
			}
		}
		return $out;
	}

	private function cws_print_menu_font() {
		ob_start();
		do_action( 'menu_font_hook' );
		return ob_get_clean();
	}

	public function cws_menu_font_action() {
		$out = '';
		$font_array = $this->cws_get_meta_option('menu-font');

		$slider_settings = $this->cws_get_meta_option( 'slider_override' );

		// -----> Styles from Theme Options -> Typography -> Menu
		if (isset($font_array)) {
			$out .= '
			.main-nav-container .menu-item a,
			.main-nav-container .menu-item .cws_megamenu_item_title
			{'
				. esc_attr($this->cws_print_font_css($font_array)) . ';
			}';

			$out .= '
			.menu-box .container .header-nav-part .main-nav-container .menu-box-wrapper .main-menu .menu-item .sub-menu .cws_megamenu_item .vc_row .cws-column-wrapper .widgettitle
			{
			    font-family: '.esc_attr($font_array["font-family"]).';
			}';

			$out .= '
			.main-nav-container .search-icon,
			.main-nav-container .mini-cart a,
			.main-nav-container .side-panel-trigger
			{
				color : '. esc_attr($font_array["color"]) . ';
			}

			@media screen and (max-width: 1366px) and (any-hover: none), screen and (max-width: 1199px){
				.menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item a,
				.menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item .widgettitle{
					color : '. esc_attr($font_array["color"]) . ';
				}
			}

			';

			$out .='
			.main-nav-container .hamburger-icon,
			.main-nav-container .hamburger-icon:before,
			.main-nav-container .hamburger-icon:after
			{
				background-color : ' . esc_attr($font_array["color"]) . ';
			}';
		}

		$menu_box_font_color = $this->cws_get_meta_option('menu_box')['font_color'];
		$menu_box_font_color_hover = $this->cws_get_meta_option('menu_box')['font_color_hover'];
		$menu_box_highlight_color = $this->cws_get_meta_option('menu_box')['highlight_color'];
		$menu_box_submenu_font_color = $this->cws_get_meta_option('menu_box')['submenu_font_color'];
		$menu_box_submenu_font_color_hover = $this->cws_get_meta_option('menu_box')['submenu_font_color_hover'];
		$menu_box_submenu_bg_color = $this->cws_get_meta_option('menu_box')['submenu_bg_color'];
		$menu_box_submenu_bg_color_hover = $this->cws_get_meta_option('menu_box')['submenu_bg_color_hover'];
		$p_type = get_post_type();

		// -----> Styles from Theme Options / Metaboxes -> Header -> Menu
		$out .= '
		.header-zone .main-nav-container .main-menu > .menu-item > a,
		.header-zone .main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title,
		.header-zone .main-nav-container .search-icon,
		.header-zone .main-nav-container .mini-cart a,
		.header-zone .main-nav-container .side-panel-trigger
		{
			color : '. esc_attr($menu_box_font_color) . ';
		}';

		$out .= '
        .header-zone .main-nav-container .main-menu > .menu-item.current_page_ancestor > a,
        .header-zone .main-nav-container .main-menu > .menu-item.current_page_ancestor > span
        {
            color : '. esc_attr($menu_box_font_color_hover) . ';
        }';

		$out .= '
		.header-zone .main-nav-container .hamburger-icon,
		.header-zone .main-nav-container .hamburger-icon:before,
		.header-zone .main-nav-container .hamburger-icon:after
		{
			background-color : '. esc_attr($menu_box_font_color) . ';
		}';

		if (!empty($menu_box_highlight_color)) {
            $out .= '
            .header-zone .main-nav-container .main-menu > .menu-item.current_page_ancestor > a:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current_page_ancestor > span:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current-menu-ancestor > a:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current-menu-ancestor > span:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current-menu-parent > a:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current-menu-parent > span:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current-menu-item > a:after,
            .header-zone .main-nav-container .main-menu > .menu-item.current-menu-item > span:after
            {
                background-color: '. esc_attr($menu_box_highlight_color) . ';
            }';
        }
        if (!empty($menu_box_submenu_font_color)) {
            $out .= '
            .header-zone .main-nav-container .sub-menu .widgettitle,
            .header-zone .main-nav-container .sub-menu .menu-item > a,
            .header-zone .main-nav-container .sub-menu .menu-item > a,
            .header-zone .main-nav-container .sub-menu .menu-item > .button-open
            {
                color: '. esc_attr($menu_box_submenu_font_color) . ';
            }
            .header-zone .main-nav-container .sub-menu .widgettitle 
            {
                border-color: '. $this->cws_Hex2RGBA( esc_attr($menu_box_submenu_font_color), 0.2 ) . ';
            }
            ';
        }
        if (!empty($menu_box_submenu_bg_color)) {
            $out .= '
            .header-zone .main-nav-container .sub-menu .menu-item,
            .header-zone .main-nav-container .sub-menu
            {
                background-color: '. esc_attr($menu_box_submenu_bg_color) . ';
            }';
        }
        if (!empty($menu_box_submenu_font_color_hover)) {
            $out .= '
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-item:before,
            .header-zone .main-nav-container .sub-menu .menu-item.current_page_ancestor:before
            {
                background-color: '. esc_attr($menu_box_submenu_font_color_hover) . ';
            }';
        }
        if (!empty($menu_box_submenu_font_color_hover)) {
            $out .= '
            .header-zone .main-nav-container .sub-menu .menu-item.current_page_ancestor > a,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-ancestor > a,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-parent > a,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-item > a,
            .header-zone .main-nav-container .sub-menu .menu-item.current_page_ancestor > span,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-ancestor > span,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-parent > span,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-item > span, 
            .header-zone .main-nav-container .sub-menu .menu-item.current_page_ancestor > .button-open,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-ancestor > .button-open,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-parent > .button-open,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-item > .button-open,
            .header-zone .mini-cart .woo-icon i span
            {
                color: '. esc_attr($menu_box_submenu_font_color_hover) . ';
            }
            
            .header-zone .main-nav-container .sub-menu .menu-item.current_page_ancestor:before,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-ancestor:before,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-parent:before,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-item:before
            {
                background-color: '. esc_attr($menu_box_submenu_font_color_hover) . ';
            }';
        }
        if (!empty($menu_box_submenu_bg_color_hover)) {
            $out .= '
            .header-zone .main-nav-container .sub-menu .menu-item.current_page_ancestor,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-ancestor,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-parent,
            .header-zone .main-nav-container .sub-menu .menu-item.current-menu-item,
            .header-zone .mini-cart .woo-icon i span
            {
                background-color: '. esc_attr($menu_box_submenu_bg_color_hover) . ';
            }';
        }

		$out .= '
			@media 
				screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
				screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
				screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
				screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
				screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
			{';


			$out .=	'
			    .header-zone .main-nav-container .main-menu > .menu-item > a:hover,
				.header-zone .main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title:hover,
				.header-zone .main-nav-container .search-icon:hover,
				.header-zone .main-nav-container .mini-cart a:hover,
				.header-zone .main-nav-container .side-panel-trigger:hover{
					color : '. esc_attr($menu_box_font_color_hover) . ';
				}
				
				.header-zone .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon,
				.header-zone .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:before,
				.header-zone .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:after{
					background-color : '. esc_attr($menu_box_font_color_hover) . ';
				}';

			if (!empty($menu_box_submenu_font_color_hover)) {
                $out .= '
                .header-zone .main-nav-container .sub-menu .menu-item:hover > a,
                .header-zone .main-nav-container .sub-menu .menu-item:hover > span,
                .header-zone .main-nav-container .sub-menu .menu-item:hover > .button-open
                {
                    color: '. esc_attr($menu_box_submenu_font_color_hover) . ';
                }
                .header-zone .main-nav-container .sub-menu .menu-item:hover:before
                {
                    background-color: '. esc_attr($menu_box_submenu_font_color_hover) . ';
                }';
            }

            if (!empty($menu_box_submenu_bg_color_hover)) {
                $out .= '
                .header-zone .main-nav-container .sub-menu .menu-item:hover
                {
                    background-color: '. esc_attr($menu_box_submenu_bg_color_hover) . ';
                }';
            }


		$out .= '}';

        $mobile_menu_box_font_color = $this->cws_get_option('mobile_menu_box')['font_color'];
		$mobile_menu_box_font_color_active = $this->cws_get_option('mobile_menu_box')['font_color_active'];
		$mobile_menu_box_bg_color = $this->cws_get_option('mobile_menu_box')['bg_color'];
		$mobile_menu_box_divider_color = $this->cws_get_option('mobile_menu_box')['divider_color'];

		$out .= '
			@media 
				screen and (max-width: 1199px)
			{';

		if (!empty($mobile_menu_box_bg_color)) {
		    $out .= '
		        .menu-box .container .header-nav-part .main-nav-container .menu-box-wrapper
		        {
		            background-color: '.esc_attr($mobile_menu_box_bg_color).';
		        }
		    ';
		}
		if (!empty($mobile_menu_box_divider_color)) {
		    $out .= '
		        .menu-box .container .header-nav-part .main-nav-container .menu-box-wrapper .main-menu .menu-item:not(:first-child),
		        .menu-box .container .header-nav-part .main-nav-container .menu-box-wrapper .main-menu .menu-item:not(:first-child)
		        {
		            border-top-color: '.esc_attr($mobile_menu_box_divider_color).' !important;
		        }
		        .menu-box .container .header-nav-part .main-nav-container .menu-box-wrapper .menu-logo-part:before
		        {
		            background-color: '.esc_attr($mobile_menu_box_divider_color).' !important;
		        }
		    ';
		}
		if (!empty($mobile_menu_box_font_color)) {
		    $out .= '
		        .menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item a, 
		        .menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item .widgettitle,
		        .menu-box .container .header-nav-part .main-nav-container .menu-box-wrapper .main-menu .menu-item .button-open
		        {
		            color: '.esc_attr($mobile_menu_box_font_color).';
		        }
		    ';
		}
		if (!empty($mobile_menu_box_font_color_active)) {
		    $out .= '
		        .menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item.current-menu-item > .button-open, 
		        .menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item.current-menu-item > a, 
		        .menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item.current-menu-ancestor > .button-open, 
		        .menu-box .main-nav-container .menu-box-wrapper .main-menu .menu-item.current-menu-ancestor > a, 
		        .header-zone .main-nav-container .main-menu > .menu-item.current_page_ancestor > a, 
		        .header-zone .main-nav-container .main-menu > .menu-item.current_page_ancestor > span, 
		        .header-zone .main-nav-container .main-menu > .menu-item.current-menu-item > a, 
		        .header-zone .main-nav-container .main-menu > .menu-item.current-menu-item > span
		        {
		            color: '.esc_attr($mobile_menu_box_font_color_active).';
		        }
		    ';
		}

		$out .= '}';


		// -----> Styles from Theme Options -> Header -> Header
		if ($this->cws_get_meta_option( 'header' )['customize'] == '1' ) {
			$menu_box_font_color = $this->cws_get_meta_option('header')['override_menu_color'];
			$menu_box_font_color_hover = $this->cws_get_meta_option('header')['override_menu_color_hover'];

			$out .= '
			.main-nav-container .main-menu > .menu-item > a,
			.main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title,
		    .main-nav-container .search-icon,
			.main-nav-container .mini-cart a,
			.main-nav-container .side-panel-trigger,
			.main-nav-container .logo h3
			{
				color : '. esc_attr($menu_box_font_color) . ';
			}';

			$out .= '
			.header-zone .main-nav-container .hamburger-icon,
			.header-zone .main-nav-container .hamburger-icon:before,
			.header-zone .main-nav-container .hamburger-icon:after
			{
				background-color : '. esc_attr($menu_box_font_color) . ';
			}';

			$out .= '
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					.main-nav-container .main-menu > .menu-item > a:hover,
					.main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title:hover,
					.main-nav-container .search-icon:hover,
					.main-nav-container .mini-cart a:hover,
					.main-nav-container .side-panel-trigger:hover{
						color : '. esc_attr($menu_box_font_color_hover) . ';
					}

					.header-zone .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon,
					.header-zone .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:before,
					.header-zone .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:after{
						background-color : '. esc_attr($menu_box_font_color_hover) . ';
					}
				}
			';
		}


		// -----> Styles from Theme Options -> WooCommerce -> Menu
		if ($this->cws_get_option( 'woo_customize_menu' ) == '1' && $this->cws_is_woo() ) {
			$menu_box_font_color = $this->cws_get_option('woo_menu_font_color');
			$menu_box_font_color_hover = $this->cws_get_option('woo_menu_font_hover_color');
			$menu_border_color = $this->cws_get_option('woo_menu_border_color');

			$out .= '
			.woocommerce .main-nav-container .main-menu > .menu-item > a,
			.woocommerce .main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title,
			.woocommerce .main-nav-container .search-icon,
			.woocommerce .main-nav-container .mini-cart a,
			.woocommerce .main-nav-container .side-panel-trigger
			{
				color : '. esc_attr($menu_box_font_color) . ';
			}';

			$out .= '
			.woocommerce .main-nav-container .main-menu > .menu-item.current_page_ancestor > a,
			.woocommerce .main-nav-container .main-menu > .menu-item.current_page_ancestor > span,	
			{
				color : '. esc_attr($menu_box_font_color_hover) . ';
			}';

			$out .= '
			.woocommerce .main-nav-container .hamburger-icon,
			.woocommerce .main-nav-container .hamburger-icon:before,
			.woocommerce .main-nav-container .hamburger-icon:after
			{
				background : '. esc_attr($menu_box_font_color) . ';
			}';

			$out .= '
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					.woocommerce .main-nav-container .main-menu > .menu-item > a:hover,
					.woocommerce .main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title:hover,
					.woocommerce .main-nav-container .search-icon:hover,
					.woocommerce .main-nav-container .mini-cart a:hover,
					.woocommerce .main-nav-container .side-panel-trigger:hover{
						color : '. esc_attr($menu_box_font_color_hover) . ';
					}

					.woocommerce .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon,
					.woocommerce .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:before,
					.woocommerce .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:after{
						background : '. esc_attr($menu_box_font_color_hover) . ';
					}
				}
			';

		}
		echo preg_replace('/\s+/',' ', $out);
	}

	// \MENU FONT HOOK

	// HEADER FONT HOOK

	private function cws_print_header_font () {
		ob_start();
		do_action( 'header_font_hook' );
		return ob_get_clean();
	}

	public function cws_header_font_action () {
		$out = '';
		$font_array = $this->cws_get_option('header-font');

		if (isset($font_array)) {
			$out .= "
				body,
				.cws-msg-box-module .cws-msg-box-info .cws-msg-box-title,
				.vc_pie_chart .wpb_wrapper .vc_pie_chart_value,
				.cws-testimonial-module .testimonial-author-name,
				.cws-widget .widget-title,
				.post-info-footer .post-meta-item,
				.post-format-quote .quote-text,
				.post-format-link .link-text,
				.nav-post .nav-post-title,
				.pricing-price-wrapper,
				.cws-roadmap-module .roadmap-label,
				.cws-textmodule-subtitle,
				.cws-milestone-number-wrapper,
				.woocommerce-tabs .comment-reply-title,
				.cws-service-module.service-type-gallery .service-info-wrapper .service-counter,
				.cws-cte-wrapper .cte-title,
				.comment-author
				{
					font-family: ". esc_attr($font_array['font-family']) .";
				}
			";

			$out .= "
				.ce-title,
				.widgettitle,
				.wpforms-title
				{ 
					font-family: ". esc_attr($font_array['font-family']) .";
					color: ". esc_attr($font_array['color']) .";
				}
			";

			$out .= '
				.widget-title
				{
					color:' . esc_attr($font_array['color']) . ';
				}
			';

			$out .=  "
				h1,h2, h3, h4, h5, h6
				{
					font-family: ". esc_attr($font_array['font-family']) .";
					color: ". esc_attr($font_array['color']) .";
				}
			";

		}
		echo preg_replace('/\s+/',' ', $out);
	}

	// \HEADER FONT HOOK

	// BODY FONT HOOK

	private function cws_print_body_font () {
		ob_start();
		do_action( 'body_font_hook' );
		return ob_get_clean();
	}

	public function cws_body_font_action () {
		$out = '';
		$font_array = $this->cws_get_option('body-font');
		if (isset($font_array)) {
			$out .= '
				body
				{
					'. esc_attr($this->cws_print_font_css($font_array)) . '
				}
			';
			$out .= '
				.cws-widget:not(.widget_icl_lang_sel_widget) ul li > a,
				.widget_recent_comments a,
				.widget-cws-recent-entries .post-item .post-title a,
				.news.posts-grid .item .post-wrapper .post-info .post-title,
				.news.posts-grid .item .post-wrapper .post-info .post-title a,
				.news.posts-grid .item .post-wrapper .post-info .read-more-wrapper .read-more,
				.news.posts-grid .item .post-wrapper .post-author .post-author-name a,
				.post-format-quote .quote-text,
				.post-format-link .link-text,
				.product .woo-product-post-title,
				.product .woo-product-post-title a,
				.product-category a .woocommerce-loop-category__title,
				.product .product_meta a,
				.header-container .woo-mini-cart .cart_list .mini_cart_item a:not(.remove),
				.woocommerce .product .woocommerce-tabs .tabs li a,
				.cws-portfolio-nav .filter-item .cws-portfolio-nav-item,
				.portfolio-module-wrapper .item .under-image-portfolio .post-title,
				.portfolio-module-wrapper .item .under-image-portfolio .post-title a,
				.single-cws_portfolio .grid-row.related-portfolio .widgettitle,
				.wp-block-latest-comments .wp-block-latest-comments__comment-date,
				.wp-block-latest-comments a,
				.wp-block-latest-posts a,
				.wp-block-latest-posts .wp-block-latest-posts__post-date,
				.wp-block-rss .wp-block-rss__item .wp-block-rss__item-title a,
				.wp-block-rss .wp-block-rss__item .wp-block-rss__item-publish-date,
				.wp-block-rss .wp-block-rss__item .wp-block-rss__item-author,
				.wp-block-rss .wp-block-rss__item .wp-block-rss__item-excerpt,
				.woocommerce-account .woocommerce .woocommerce-MyAccount-navigation > ul > li > a
				{
					color:' . esc_attr($font_array['color']) . ';
				}
			';
			$out .= '
				body textarea
				{
					line-height:' . esc_attr($font_array['line-height']) . ';
				}
			';
			$out .= '
				abbr
				{
					border-bottom-color:' . esc_attr($font_array['color']) . ';
				}
			';
			$out .= '
			    .vc_toggle .vc_toggle_title > h4,
			    .vc_tta-accordion .vc_tta-panel h4.vc_tta-panel-title,
			    .comments-count,
			    h5.roadmap-title,
			    .page-footer .container .footer-container .cws-widget .widget-title,
			    .portfolio-module-wrapper .item .under-image-portfolio .post-title,
			    .logo h3,
			    .single-post .post-option h6
			    {
			        font-family:' . esc_attr($font_array['font-family']) . ';
			    }
			';
			$out .= '
			    .post-meta-item:before
			    {
			        background-color:' . esc_attr($font_array['color']) . ';
			    }
			';

			$fs_match = preg_match( '#(\d+)(.*)#', $font_array['font-size'], $fs_matches );
			$lh_match = preg_match( '#(\d+)(.*)#', $font_array['line-height'], $lh_matches );

			if ( $fs_match && $lh_match ) {
				$fs_number = (int)$fs_matches[1];
				$fs_units = $fs_matches[2];
				$lh_number = (int)$lh_matches[1];
				$lh_units = $lh_matches[2];
				$out .= "
					.dropcap
					{
						font-size:" . esc_attr($fs_number * 2 . $fs_units).";
						line-height:" . esc_attr($lh_number * 2 . $lh_units).";
						width:" . esc_attr($lh_number * 2 . $lh_units).";
					}
				";
			}
		}

		echo preg_replace('/\s+/',' ', $out);
	}

	public function cws_process_fonts() {
		$out = $this->cws_print_menu_font();
		$out .= $this->cws_print_header_font();
		$out .= $this->cws_print_body_font();
		return $out;
	}

	//****************************** CWS PRINT FUNCTIONS ******************************
	public function cws_print_gradient( $settings, $selectors = '',  $use_extra_rules = false) {
		global $cws_theme_funcs;

		extract( shortcode_atts( array(
			'c1' => METAMAX_FIRST_COLOR,
			'c2' => METAMAX_SECOND_COLOR,
			'op1' => '100',
			'op2' => '100',
			'type' => 'linear',
			'linear' => array(),
			'radial' => array(),
			'custom_css' => '',
		), $settings));

		if (!empty($custom_css)) return $custom_css;

		$c1 = $this->cws_Hex2RGBA($c1,(int)$op1/100);
		$c2 = $this->cws_Hex2RGBA($c2,(int)$op2/100);

		$out = '';
		$rules = '';
		switch ($type) {
			case 'linear':
				$angle = isset($linear['angle']) ? $linear['angle'] : 0;
				$rules .= "background:-webkit-linear-gradient(".esc_attr($angle)."deg, ".esc_attr($c1).", ".esc_attr($c2).");";
				$rules .= "background:-o-linear-gradient(".esc_attr($angle)."deg, ".esc_attr($c1).", ".esc_attr($c2).");";
				$rules .= "background:-moz-linear-gradient(".esc_attr($angle)."deg, ".esc_attr($c1).", ".esc_attr($c2).");";
				$rules .= "background:linear-gradient(".esc_attr($angle)."deg, ".esc_attr($c1).", ".esc_attr($c2).");";
				break;
			case 'radial':
				extract( shortcode_atts( array(
					'shape_type' => 'simple',
					'shape' => 'ellipse',
					'keyword' => 'farthest-corner',
					'size' => ''
				), $radial));

				switch ($shape_type) {
					case 'simple':
						$rules .= "background:-webkit-radial-gradient(".esc_attr($shape)." ".esc_attr($c1).", ".esc_attr($c2).");";
						$rules .= "background:-o-radial-gradient(".esc_attr($shape)." ".esc_attr($c1).", ".esc_attr($c2).");";
						$rules .= "background:-moz-radial-gradient(".esc_attr($shape)." ".esc_attr($c1).", ".esc_attr($c2).");";
						$rules .= "background:radial-gradient(".esc_attr($shape)." ".esc_attr($c1).", ".esc_attr($c2).");";
						break;
					case 'exteneded':
						$rules .= "background:-webkit-radial-gradient( ".esc_attr($size)." ".esc_attr($size_keyword)." ".esc_attr($c1).", ".esc_attr($c2).");";
						$rules .= "background:-o-radial-gradient( ".esc_attr($size)." ".esc_attr($size_keyword)." ".esc_attr($c1).", ".esc_attr($c2).");";
						$rules .= "background:-moz-radial-gradient( ".esc_attr($size)." ".esc_attr($size_keyword)." ".esc_attr($c1).", ".esc_attr($c2).");";
						$rules .= "background:radial-gradient(".esc_attr($size_keyword)." at ".esc_attr($size)." ".esc_attr($c1).", ".esc_attr($c2).");";
						break;
			}
				break;
		}

		if ( !empty( $rules ) ) {
			$printf_rules = !empty($selectors) ? '%s{%s}' : '%s%s';
			$out .= sprintf($printf_rules, $selectors, $rules);
			if ( $use_extra_rules ) {
				$border_extra_rules = 'border-color:transparent;-moz-background-clip:border;-webkit-background-clip: border;background-clip:border-box;-moz-background-origin:border;-webkit-background-origin:border;background-origin:border-box;background-repeat:no-repeat;';
				$transition_extra_rules = '-webkit-transition-property:background,color,border-color,opacity;-webkit-transition-duration:0s,0s,0s,0.6s;-o-transition-property:background,color,border-color,opacity;-o-transition-duration:0s,0s,0s,0.6s;-moz-transition-property:background,color,border-color,opacity;-moz-transition-duration:0s,0s,0s,0.6s;transition-property:background,color,border-color,opacity;transition-duration:0s,0s,0s,0.6s;';
				$out .= sprintf($printf_rules, $selectors, $border_extra_rules);
				$out .= sprintf($printf_rules, $selectors, 'color: #fff !important;');
				$selectors_wth_pseudo = str_replace( ':hover', '', $selectors );
				$out .= sprintf($printf_rules, $selectors_wth_pseudo, $transition_extra_rules);
			}
		}
		return $out;
	}

	public function cws_print_paddings($paddings = array()) {
		if ($paddings && is_array($paddings)) {
			$out = '';
			foreach ( $paddings as $key => $value ){
				if ( !empty( $value ) || $value == '0' ){
					$out .= "padding-".esc_attr($key). ": " . esc_attr($value) . "px;";
				}
			}
		}
		return $out;
	}

	public function cws_print_margins($margins = array()) {
		if ($margins && is_array($margins)) {
			$out = '';
			foreach ( $margins as $key => $value ){
				if ( !empty( $value ) || $value == '0' ){
					$out .= "margin-".esc_attr($key). ": " . esc_attr($value) . "px;";
				}
			}
		}
		return $out;
	}

	public function cws_print_background($props = null) {
		if ($props && is_array($props)) {
			$out = '';
			foreach ($props as $key => $value) {
				if ('image' === $key) {
					$out .= 'background-'.esc_attr($key).':';
					$out .= sprintf('url(%s);', esc_url($value['src']));
				}
			}
			$out .=  $this->cws_print_css_keys($props, 'background-');
		}
		return $out;
	}

	public function cws_print_border($box) {
		$out = '';
		if ( !empty($box) && !empty($box['border_box']) ){
			$width_type_color = sprintf('%spx %s %s', $box['width'], $box['type'], $box['color']);
			foreach ($box['border_box'] as $key => $value) {
				$out .= "border-".esc_attr($value).":".esc_attr($width_type_color).";";
			}
		}
		return $out;
	}

	public function cws_print_overlay($o){
		$bg_styles = $type = '';
		if(!empty($o)){
			extract($o);
		}

		switch ($type) {
			case 'gradient':
				$bg_styles = $this->cws_print_gradient($gradient);
				$bg_styles .= "opacity:".(esc_attr($opacity) / 100).";";
				break;
			case 'color':
				$bg_styles = $this->cws_print_rgba('background-color', $color, $opacity);
				break;
		}
		return $bg_styles;
	}

	public function cws_print_rgba($prefix, $color, $op = 100){
		return sprintf('%s:rgba(%s,%s);', $prefix, $this->cws_Hex2RGB($color), (int)$op/100);
	}

	// prints associative array keys with prefix and value
	public function cws_print_keys($a, $prefix = '') {
		$out = '';
		if (is_array($a)) {
			foreach ($a as $key => $value) {
				if (!is_array($key) && !empty($value)) {
					$out .= $prefix . $key . '="' . $value . '" ';
				}
			}
		}
		return trim($out);
	}

	public function cws_print_css_keys($a, $prefix = '', $suffix = '') {
		$out = '';
		if (is_array($a)) {
			foreach ($a as $key => $value) {
				if (!is_array($a[$key]) && !empty($value)) {
					if ('position' === $key) {
						$out .= $prefix . $key . ':' . $this->cws_print9positions($value) . $suffix . ';';
					} else {
						$out .= $prefix . $key . ':' . $value . $suffix . ';';
					}
				}
			}
		}
		return trim($out);
	}

	public function cws_print_parallaxify_atts($opts, &$atts, &$layer_atts) {
		if (!empty($opts)) {
			$atts .= $this->cws_print_keys($opts, 'data-');
			$layer_atts .= 'position:absolute;z-index:1;left:-'.esc_attr($opts['limit-y']).'px;right:-'.esc_attr($opts['limit-y']).'px;top:-'.esc_attr($opts['limit-x']).'px;bottom:-'.esc_attr($opts['limit-x']).'px;';
		}
	}

	public function cws_print_border_box($box) {
		$out = $type_color = '';
		if ( !empty($box) && !empty($box['border']) ){
			if(isset($box['border_type']) && isset($box['border_color'])){
				$type_color = sprintf('%s %s', $box['border_type'], $box['border_color']);
			}

			if(isset($box['border']) && is_array($box['border'])){
				foreach ($box['border'] as $key => $value) {
					$out .= "border-{$value}:1px {$type_color};";
				}
			}
		}
		return $out;
	}

	public function cws_print_img_html($img, $img_args, &$img_height = null) {
		$src = '';
		$img_h = 0;

		if ($img && !is_array($img) ) {
			$attach = wp_get_attachment_image_src( $img, 'full' );
			if ($attach) {
				list($src, $width, $height) = $attach;
				$img = array('src'=> $src, 'width' => $width, 'height' => $height, 'is_high_dpi' => '1', 'id' => $img);
			} else {
				return $src;
			}
		} else if ($img && !isset($img['is_high_dpi'] ) ) {
			$img['is_high_dpi'] = '1';
		} else if (empty($img['width']) && empty($img['height'])) {
			$attach = wp_get_attachment_image_src( $img['id'], 'full' );
			if ($attach) {
				list($src, $width, $height) = $attach;
				$img['width'] = $width;
				$img['height'] = $height;
			}
		}

		$is_high_dpi = (isset($img['is_high_dpi']) && $img['is_high_dpi'] == '1');

		$img_source = isset($img['id']) ? $img['id'] : $img['src'];

		if ( $is_high_dpi ) {
			if ( empty($img_args['width']) && empty($img_args['height']) ) {
				if (isset($img['width']) && isset($img['height'])) {

					$img_args = array(
						'width' => floor( (int) $img['width'] / 2 ),
						'height' => floor( (int) $img['height'] / 2 ),
						'crop' => true
					);
				}
			}

                if (function_exists('cws_get_img')) {
                    $thumb_obj = cws_get_img( $img_source, $img_args );
                } else {
                    $thumb_obj = array(
                            0 => wp_get_attachment_image_url($img_source, array($img_args['width'], $img_args['height'])),
                            1 => '',
                            2 => '',
                            3 => wp_get_attachment_image_url($img_source, array($img_args['width']*2, $img_args['height']*2)),
                    );
                }

			if ($thumb_obj) {
				$img_h = !empty($img_args["height"]) ? $img_args["height"] : '';
				$thumb_path_hdpi = !empty($thumb_obj[3]) ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
				$img_alt = get_post_meta($img_source, '_wp_attachment_image_alt', true);
				$src = $thumb_path_hdpi ." alt='" . (!empty($img_alt) ? $img_alt : get_the_title($img_source)) . "'";
			}
		} else {
			if ( empty($img_args['width']) && empty($img_args['height']) ) {
				if (isset($img['width']) && isset($img['height'])) {
					$img_args = array(
						'width' => floor( (int) $img['width'] ),
						'height' => floor( (int) $img['height'] ),
						'crop' => true
					);
				}
			}
                if (function_exists('cws_get_img')) {
                    $thumb_obj = cws_get_img( $img_source, $img_args );
                } else {
                    $thumb_obj = array(
                            0 => wp_get_attachment_image_url($img_source, array($img_args['width'], $img_args['height'])),
                            1 => '',
                            2 => '',
                            3 => wp_get_attachment_image_url($img_source, array($img_args['width']*2, $img_args['height']*2)),
                    );
                }

			if ($thumb_obj) {
				$img_h = !empty($img_args["height"]) ? $img_args["height"] : '';
				$thumb_path_hdpi = !empty($thumb_obj[3]) ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
				$img_alt = get_post_meta($img_source, '_wp_attachment_image_alt', true);
				$src = $thumb_path_hdpi ." alt='" . (!empty($img_alt) ? $img_alt : get_the_title($img_source)) . "'";
			}
		}

		if ($img_height) {
			$img_height = $img_h;
		}
		return $src;
	}

	public function cws_print_svg_html($img, $img_args, &$img_height = null) {

		$svg = '';
		if ( !empty($img_args['width']) && !empty($img_args['height']) ) {
			$svg .= "<span class='cws_logotype_svg' style='width:{$img_args['width']}px;height:{$img_args['height']}px'>";
		}
		if(!empty($img['src'])){
			global $wp_filesystem;
			WP_Filesystem();
			$upload_dir = wp_upload_dir();
			$file_parts = pathinfo($img['src']);
			$dir = str_replace($upload_dir['baseurl'], "", $file_parts['dirname']);
			$dir = $wp_filesystem->find_folder($upload_dir['basedir'] . $dir);
		    $file = trailingslashit($dir) . $file_parts['basename'];
		    if($wp_filesystem->exists($file)){
		    	$svg .= $wp_filesystem->get_contents($file);
		    }
		}

		if ( !empty($img_args['width']) && !empty($img_args['height']) ) {
			$svg .= "</span>";
		}
		return $svg;
	}

	public function cws_print9positions($pos){
		$bg_pos = '';
		for ($i=0; $i<2;$i++) {
			$c = $pos[$i];
			switch ($c) {
				case 'l':
					$bg_pos .= ' left';
					break;
				case 'r':
					$bg_pos .= ' right';
					break;
				case 'c':
					$bg_pos .= ' center';
					break;
				case 'b':
					$bg_pos .= ' bottom';
					break;
				case 't':
					$bg_pos .= ' top';
					break;
			}
		}
		return trim($bg_pos);
	}
	//****************************** //CWS PRINT FUNCTIONS ******************************

	public function cws_loader() {
		$cws_loader = $this->cws_get_option('overlay_loader_color');
		if(!empty($cws_loader)){
			$out = '#cws-page-loader .inner:before{
				    background-image: -webkit-linear-gradient(top, '.$cws_loader.', '.$cws_loader.');
	    			background-image: -moz-linear-gradient(top, '.$cws_loader.', '.$cws_loader.');
	   				background-image: linear-gradient(to bottom, '.$cws_loader.', '.$cws_loader.');
			}';
			$out .= '#cws-page-loader .inner:after{
				    background-image: -webkit-linear-gradient(top, #ffffff, '.$cws_loader.');
	    			background-image: -moz-linear-gradient(top, #ffffff, '.$cws_loader.');
	   				background-image: linear-gradient(to bottom, #ffffff, '.$cws_loader.');
			}';
			return $out;
		}
	}

	/* THEME FOOTER */
	public function cws_page_footer (){
		//Get metaboxes from page
		$footer = $this->cws_get_meta_option( 'footer' );
		extract($footer, EXTR_PREFIX_ALL, 'footer');

		$instagram_feed_content = '';

		if ($footer_instagram_feed == '1') {
			$instagram_feed_content = do_shortcode(wp_specialchars_decode($footer_instagram_feed_shortcode, ENT_QUOTES));
		}

		$footer_class = 'page-footer';
		$footer_class .= (isset($footer_border['line']) && $footer_border['line'] == '1' ? ' border_line' : '');
		$footer_class .= empty( $footer_sidebar ) || !is_active_sidebar( $footer_sidebar ) ? ' empty_footer' : '';
		$footer_class .= $footer_instagram_feed == '1' ? (($footer_instagram_feed_full_width == '1' ) ? ' instagram_feed instagram_feed_full_width' : ' instagram_feed') : '';



			echo "<footer class='".esc_attr($footer_class)."'>";
				echo "<div class='bg-layer'></div>";

				if ( isset($footer_icon_enable) && !empty($footer_icon_enable) ) {
				    echo "<div class='footer-icon'><div class='footer-icon-arrow'></div></div>";
				}

				if ( !empty($footer_pattern_image['image']['src']) ) {
					echo "<div class='footer-pattern'></div>";
				}

				if ($footer_instagram_feed == '1' && $footer_instagram_feed_full_width == '1') echo sprintf("%s", $instagram_feed_content);
				echo "<div class='container".($footer_wide == '1' ? ' wide-container' : '')."'>";


                    if ( !empty($footer_logo_enable) || !empty( $footer_footer_info_text ) ) {
                        echo '<div class="footer-info">';
                        $logo_cont = '';
                        $logo = (isset($footer_logo) && !empty($footer_logo) ? $footer_logo : '');

                        if ( !empty($logo['src']) ) {
                            $bfi_args = array();
                            if ( isset($footer_dimensions) && is_array( $footer_dimensions ) ) {
                                foreach ( $footer_dimensions as $key => $value ) {
                                    if ( ! empty( $value ) ) {
                                        $bfi_args[ $key ] = $value;
                                        $bfi_args['crop'] = false;
                                    }
                                }
                            }
                            $img_result = '';
                            $file_parts = pathinfo($logo['src']);
                            if($file_parts['extension'] == 'svg'){
                                $img_result .= '<span class="footer-logo cws-svg-desktop">';
                                $img_result .= $this->cws_print_svg_html($logo, $bfi_args, $main_logo_height);
                                $img_result .= '</span>';
                            } else {
                                $logo_src = $this->cws_print_img_html($logo, $bfi_args, $main_logo_height);
                                $img_result .= '<img '. $logo_src . ' class="footer-logo" />';
                            }


                            $rety = home_url('/');
                            $logo_cont .= '<a class="logo" href="'.$rety.'">';
                            $logo_cont .= $img_result;
                            $logo_cont .= '</a>';
                        }

                        if ( !empty($footer_logo_enable) ) {
                            echo '<div class="footer-logo">';
                                echo sprintf("%s", $logo_cont);
                            echo '</div>';
                        }
                        if ( !empty( $footer_footer_info_text ) ) {
                            echo '<div class="footer-info-text">';
                                echo sprintf("%s", $footer_footer_info_text);
                            echo '</div>';
                        }
                        echo '</div>';

                    }

					echo "<div class='footer-container col-".esc_attr($footer_layout)."'>";
						if ($footer_instagram_feed == '1' && $footer_instagram_feed_full_width == '0') echo sprintf("%s", $instagram_feed_content);

						$GLOBALS['footer_is_rendered'] = true;
						if ( !empty( $footer_sidebar ) && is_active_sidebar( $footer_sidebar ) ) {
							dynamic_sidebar( $footer_sidebar );
						}
						unset( $GLOBALS['footer_is_rendered'] );
					echo '</div>';

                    $show_wpml_footer = CWS_WPML_ACTIVE;

                    $flags = '';
                    if ( function_exists('wpml_init_language_switcher') ) {
                        global $wpml_language_switcher;
                        $slot = $wpml_language_switcher->get_slot( 'statics', 'footer' );
                        $template = $slot->get_model();
                        $flags = $slot->is_enabled();
				    }

				    if ( !empty( $show_wpml_footer ) ) {
                        $class_wpml = '';

                        if(isset($template['template']) && !empty($template['template'])){
                            if($template['template'] == 'wpml-legacy-vertical-list'){
                                $class_wpml = 'wpml_language_switch lang_bar '. esc_attr($template['template']);
                            }
                            else{
                                $class_wpml = 'wpml_language_switch horizontal_bar '.esc_attr($template['template']);
                            }
                        } else{
                            $class_wpml = 'lang_bar';
                        }
                        ob_start();
                            do_action( 'wpml_footer_language_selector');
                        $wpml_footer_result = ob_get_clean();

                        if ( $show_wpml_footer && !empty($flags) && !empty($wpml_footer_result) ) {
                            echo '<div class="'. esc_attr($class_wpml).'">';
                                echo sprintf("%s", $wpml_footer_result);
                            echo '</div>';
						}
				    }
				echo '</div>';

				$social_links = '';
				$social_links .= $this->cws_render_social_links('copyrights');

				$footer_info_cont = cws_footer_info_box();
				if ( !empty($social_links) || !empty($footer_info_cont) ) {
				    echo '<div class="container">';
				    if ( !empty($footer_info_cont) ) {
				        echo sprintf('%s', $footer_info_cont);
				    };
				    if ( !empty($social_links) ) {
				        echo sprintf('%s', $social_links);
				    }
				    echo '</div>';
				}

				$footer_copyrights_text = stripslashes($footer_copyrights_text);
				ob_start();

				if ( !empty( $footer_copyrights_text ) ) {
					echo "<div class='copyrights'>$footer_copyrights_text</div>";
				}

				$copyrights_content = ob_get_clean();

				$copyrights_area_class = 'copyrights-area';


				if ( !empty( $copyrights_content ) ) {
					echo "<div class='".esc_attr($copyrights_area_class)."'>";
						echo "<div class='container ".($footer_wide == '1' ? ' wide-container' : '')."'>";
							echo "<div class='copyrights-container' style='text-align: ".esc_attr($footer_alignment).";'>";
								echo sprintf("%s", $copyrights_content);
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}

			echo "</footer>";

	}
	/* END THEME FOOTER */

	/******************** \TYPOGRAPHY ********************/

	public function cws_layout_class ($classes=array()) {
		$mobile_menu_box = $this->cws_get_meta_option( 'mobile_menu_box' );

		array_push( $classes, 'wide' );

		return $classes;
	}

	public function cws_widgets_init() {
		$sidebars = $this->cws_get_option('sidebars');
		$sidebars = isset($sidebars) ? $sidebars :
		array(
			array('title' => 'Footer'),
			array('title' => 'Blog Right'),
			array('title' => 'Page Right'),
		); //Check default

		if (!empty($sidebars) && function_exists('register_sidebars')) {
			foreach ($sidebars as $sb) {
				if ($sb && !empty($sb['title'])) {
					register_sidebar( array(
						'name' => $sb['title'],
						'id' => strtolower(preg_replace("/[^a-z0-9\-]+/i", "_", esc_attr($sb['title']) )),
						//Uncomment this line to add divider to widgets
						'before_widget' => '<div class="cws-widget %2$s">',
						'after_widget' => '</div>',
						'before_title' => '<div class="widget-title"><div class="inherit-wt">',
						'after_title' => '</div></div>',
					));
				}
			}
		}
	}

	public function cws_theme_reset_styles() {
		wp_enqueue_style('reset', METAMAX_URI . '/css/reset.css');
	}

	public function cws_theme_enqueue_styles() {
		if(is_admin() && !is_shortcode_preview()) {
			return;
		}
		wp_register_style('cws-main-inline-styles', false );
		wp_enqueue_style( 'cws-main-inline-styles' );

		wp_enqueue_style( 'fancybox', METAMAX_URI . '/css/jquery.fancybox.css');
		wp_enqueue_style( 'select2-init', METAMAX_URI . '/css/select2.css');
		wp_enqueue_style( 'animate', METAMAX_URI . '/css/animate.css');

		$cwsfi = get_option('cwsfi');
		if (!empty($cwsfi) && isset($cwsfi['css'])) {
			wp_enqueue_style( 'cwsfi-style', $cwsfi['css']);
		} else {
			wp_enqueue_style( 'cws-flaticon', METAMAX_URI . '/fonts/flaticon/flaticon.css' );
		};

		wp_enqueue_style( 'font-awesome', METAMAX_URI . '/fonts/font-awesome/font-awesome.css' );
		wp_enqueue_style( 'cws-iconpack', METAMAX_URI . '/fonts/cws-iconpack/flaticon.css' );

		$is_custom_color = $this->cws_get_option('is-custom-color');
		if ($is_custom_color != '1') {
			$style = $this->cws_get_option('stylesheet');
			if (!empty($style)) {
				wp_enqueue_style( 'style-color', METAMAX_URI . '/css/' . $style . '.css' );
			}
		}
		wp_enqueue_style( 'cws-main', METAMAX_URI . '/css/main.css' );
	}

	public function cws_enqueue_theme_stylesheet () {
		wp_enqueue_style( 'style', get_stylesheet_uri() );
	}

	public function cws_admin_init( $hook ) {
		$this->cws_read_options();
		wp_enqueue_style('admin-style', METAMAX_URI . '/core/css/mb-post-styles.css' );
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('custom-admin', METAMAX_URI . '/core/js/custom-admin.js', array( 'jquery' ) );

		$cwsfi = get_option('cwsfi');
		if (!empty($cwsfi) && isset($cwsfi['css'])) {
			wp_enqueue_style( 'cwsfi-style', $cwsfi['css']);
		}else{
			wp_enqueue_style( 'flaticon', METAMAX_URI . '/fonts/flaticon/flaticon.css' );
		};

		if (('toplevel_page_Metamax' == $hook) || ('toplevel_page_MetamaxChildTheme' == $hook)) {
			wp_enqueue_style( 'cws-redux-style' , METAMAX_URI . '/core/css/cws-redux-style.css' );
		}
	}

	public function cws_register_fonts () {
		metamax_render_fonts_url ();
		$gf_url = esc_url( metamax_render_fonts_url () );
		wp_enqueue_style( 'google-fonts', $gf_url );
	}

	public function cws_after_setup_theme() {
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support(' widgets ');
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );
		add_theme_support( 'post-formats', self::$cws_theme_config['post-formats'] );

		// Add Gutenberg Compatibility
		add_theme_support( 'align-wide' );

		$nav_menus = self::$cws_theme_config['nav-menus'];
		register_nav_menus($nav_menus);

		add_theme_support( 'woocommerce' );

		add_theme_support( 'custom-background', array(
			'wp-head-callback' => 'cws_custom_background',
			'default-color'    => '616262',
			'default-repeat'         => 'no-repeat',
			'default-position-x'     => 'center',
			'default-position-y'     => 'top',
			'default-size'           => 'contain',
			'default-attachment'     => 'scroll',
		));

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

	// THEME COLOR HOOK

	private function cws_print_theme_color() {
		ob_start();
		do_action( 'theme_color_hook' );
		return ob_get_clean();
	}

	public function cws_theme_color_action() {
		$out = '';

		$first_color = $this->cws_get_meta_option('theme_first_color');
		$second_color = $this->cws_get_meta_option('theme_second_color');
		$third_color = $this->cws_get_meta_option('theme_third_color');

		global $wp_filesystem;
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$file = get_template_directory() . '/css/theme-color.css';
		if ( $wp_filesystem->exists($file) ) {
			$file = $wp_filesystem->get_contents( $file );
				$colors = array();
				$colors[0] = '|#theme-color-1#|';
				$colors[1] = '|#theme-color-2#|';
				$colors[2] = '|#theme-color-3#|';
				$replace = array();
				$replace[0] = $this->cws_Hex2RGB($first_color);
				$replace[1] = $this->cws_Hex2RGB($second_color);
				$replace[2] = $this->cws_Hex2RGB($third_color);
				$new_css = preg_replace($colors, $replace, $file);
				$out .= $new_css;
		}

		$result = preg_replace('/\s+/',' ', $out);
		echo sprintf("%s", $result);
	}

	public function cws_theme_rgba_color () {
		$out = '';
		$font_array = $this->cws_get_option('theme_first_color');
		$rgb_color = $this->cws_Hex2RGB( $font_array );
		$rgb_color = esc_attr($rgb_color);

		echo preg_replace('/\s+/',' ', $out);
	}

	//****************************** CWS CUSTOM STYLES FUNCTIONS ******************************
	public function cws_custom_header_styles_action () {
		//Get metaboxes from page
		$header_styles = '';
		$start = $title_inside = false;
		$header = $this->cws_get_meta_option( 'header' );
		extract($header, EXTR_PREFIX_ALL, 'header');

		$post_meta = get_post_meta(get_the_id(), 'cws_mb_post');
		if( !empty($post_meta) ){
			$post_meta = $post_meta[0];
		}

		if( isset($header_spacings) ){
			foreach ( $header_spacings as $key => $value ){
				if ( !empty( $value ) || $value == '0' ){
					$header_styles .= "padding-".esc_attr($key) . ": " . esc_attr($value) . "px;";
				}
			}
		}

		// Check if title inside header zone
		if ( isset($header['order']) ) {
            foreach ($header['order'] as $key => $value) {
                if( $value['val'] == 'drop_zone_start' ){
                    $start = true;
                } else if( $value['val'] == 'drop_zone_end' ){
                    $start = false;
                }

                if( $start ){
                    if( $value['val'] == 'title_box' ){
                        $title_inside = true;
                    }
                }
            }
        }

		if( !empty($post_meta['post_title_box_image']['src']) && $title_inside ){
			$header_background_image['image']['src'] = $post_meta['post_title_box_image']['src'];
		}
		if( isset($header_background_image['image']['src']) && !empty($header_background_image['image']['src']) ){
			$header_styles .= $this->cws_print_background($header_background_image);
		}

		$out = '';
		if( isset($header_order) ) {
			$count = count($header_order);
			foreach ($header_order as $key => $value){
				if ( !empty($value) && !in_array( $value['val'], array('drop_zone_start', 'drop_zone_end', 'before_header', 'after_header') ) ){
					$z_index = $count-$key;
					$out .= esc_attr(".header_wrapper .".esc_attr($value['val'])."{z-index:".esc_attr($z_index).";} ");
				}
			}
		}

		if( isset($header_background_image['image']['src']) && !empty($header_background_image['image']['src']) && $header_customize == '1' ){
			$out .= "
				.header-container{
					".$header_styles.";
				}
			";
		}

		if( $header_customize == '1' && isset($header_overlay) && $header_overlay['type'] != 'none' ){
			$out .= "
				.header-overlay{
					".esc_attr($this->cws_print_overlay($header_overlay))."
				}
			";
		}

		echo sprintf('%s', $out);
	}

	public function cws_custom_logo_box_styles_action(){
		//Get metaboxes from page
		$logo_box = $this->cws_get_meta_option( 'logo_box' );	
		extract($logo_box, EXTR_PREFIX_ALL, 'logo_box');

		$out = '';

		if( isset($logo_box_overlay) ){
			$out .= "
				.header-cont .logo-box{
					".esc_attr($this->cws_print_overlay($logo_box_overlay))."
				}
			";
		}
		if( isset($logo_box_border) ){
			$out .= "
				.header-cont .logo-box{
					".esc_attr( $this->cws_print_border($logo_box_border))."
				}
			";
		}

		echo sprintf('%s', $out);
	}

	public function cws_custom_menu_box_styles_action () {
		//Get metaboxes from page
		$menu_box = $this->cws_get_meta_option( 'menu_box' );
		extract($menu_box, EXTR_PREFIX_ALL, 'menu_box');

		//Substitution of variables if WooCommerce menu customized
		if( $this->cws_get_option( 'woo_customize_menu' ) == '1' && $this->cws_is_woo() && $this->cws_get_option('show_menu_bg_color') ){
			if( !empty($this->cws_get_option('woo_menu_bg_color')) ){
				$menu_box_background_color = $this->cws_get_option('woo_menu_bg_color');
			}
			if( !empty($this->cws_get_option('woo_menu_opacity')) ){
				$menu_box_background_opacity = $this->cws_get_option('woo_menu_opacity');
			}
		}

		//Accept filters
		if( empty($menu_box_background_opacity) && $menu_box_background_opacity != '0' ){
			$menu_box_background_opacity = 0;
		} else {
			$menu_box_background_opacity = esc_attr($menu_box_background_opacity);
		}
		if( !empty($menu_box_background_color) && $menu_box_background_color != 'transparent' ){
			$menu_box_background_color = esc_attr($menu_box_background_color);
			$menu_box_background = 'background-color: rgba('. $this->cws_Hex2RGB( $menu_box_background_color ). ',' .esc_attr($menu_box_background_opacity / 100) . ');';
		}

		//Custom ThemeOptions Styles
		$out = '';

		if( !empty($menu_box_margin) ){
			$out .= "
				.header_cont .menu-box{
					".$this->cws_print_css_keys($menu_box_margin, 'padding-', 'px')."
				}
			";
		}
		if( isset($menu_box_border) ){
			$out .= "
				.header_cont .menu-box{
					".$this->cws_print_border($menu_box_border)."
				}
			";
		}
		if( isset($menu_box_background) ){
			$out .= "
				.header_cont .menu-box{
					".$menu_box_background.";
				}
			";
		}

		//WooCommerce ThemeOptions Styles
		if( $this->cws_get_option( 'woo_customize_menu' ) == '1' && $this->cws_is_woo() ){

			if( !empty($this->cws_get_option('woo_menu_border_color')) ){
				$out .= "
					.header_cont .menu-box{
						border-color: ".$this->cws_get_option('woo_menu_border_color').";
					}
				";
			}

		}

		echo sprintf('%s', $out);
	}

	public function cws_custom_sticky_menu_styles_action () {

		//Get metaboxes from page
		$sticky_menu = $this->cws_get_meta_option( 'sticky_menu' );
		extract($sticky_menu, EXTR_PREFIX_ALL, 'sticky_menu');
		
		//Accept filters
		if( empty($sticky_menu_background_opacity) ){
			$sticky_menu_background_opacity = 100;
		} else {
			$sticky_menu_background_opacity = esc_attr($sticky_menu_background_opacity);
		}
		if( !empty($sticky_menu_background_color) && $sticky_menu_background_color != 'transparent' ){
			$sticky_menu_background_color = esc_attr($sticky_menu_background_color);
			$sticky_menu_style = 'background-color: rgba('. $this->cws_Hex2RGB( $sticky_menu_background_color ). ',' .esc_attr($sticky_menu_background_opacity / 100) . ');';
		}
		if( !empty($sticky_menu_font_color) ){
			$sticky_menu_font_color = esc_attr($sticky_menu_font_color);
		}

		//Custom ThemeOptions Styles
		$out = '';

		if( $sticky_menu_enable ){
			if( !empty($sticky_menu_margin_sticky) ){
				$out .= "
					.header_cont .sticky-menu-box{
						".$this->cws_print_css_keys($sticky_menu_margin_sticky, 'padding-', 'px')."
					}
				";
			}
			if( !empty($sticky_menu_border) ){
				$out .= "
					.sticky-enable.sticky-active .header-container .menu-box{
						".$this->cws_print_border($sticky_menu_border).";
					}
				";
			}
			if( isset($sticky_menu_style) ){
				$out .= "
					.sticky-enable.sticky-active .header-container .menu-box{
						".$sticky_menu_style.";
					}
				";
			}
			if( !empty($sticky_menu_font_color) ){
				$out .= "
					.sticky-enable.sticky-active .main-nav-container .main-menu > .menu-item > a,
					.sticky-enable.sticky-active .main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title,
					.sticky-enable.sticky-active .main-nav-container .search-icon,
					.sticky-enable.sticky-active .main-nav-container .mini-cart a,
					.sticky-enable.sticky-active .main-nav-container .side-panel-trigger
					{
						color : ". $sticky_menu_font_color . ";
					}
				";

				$out .= "
					.sticky-enable.sticky-active .main-nav-container .hamburger-icon,
					.sticky-enable.sticky-active .main-nav-container .hamburger-icon:before,
					.sticky-enable.sticky-active .main-nav-container .hamburger-icon:after
					{
						background-color : ". $sticky_menu_font_color . ";
					}
				";
			}

			if( !empty($sticky_menu_font_color_hover) ){
				$out .= '
					.sticky-enable.sticky-active .main-nav-container .main-menu > .menu-item.current_page_ancestor > a,
					.sticky-enable.sticky-active .main-nav-container .main-menu > .menu-item.current_page_ancestor > span{
						color : '. esc_attr($sticky_menu_font_color_hover) . ';
					}
				';

				$out .= '
					@media 
						screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
						screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
						screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
						screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
						screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
					{
						.sticky-enable.sticky-active .main-nav-container .main-menu > .menu-item > a:hover,
						.sticky-enable.sticky-active .main-nav-container .main-menu > .menu-item > .cws_megamenu_item_title:hover,
						.sticky-enable.sticky-active .main-nav-container .search-icon:hover,
						.sticky-enable.sticky-active .main-nav-container .mini-cart a:hover,
						.sticky-enable.sticky-active .main-nav-container .side-panel-trigger:hover{
							color : '. esc_attr($sticky_menu_font_color_hover) . ';
						}
						
						.sticky-enable.sticky-active .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon,
						.sticky-enable.sticky-active .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:before,
						.sticky-enable.sticky-active .main-nav-container .mobile-menu-hamburger:hover .hamburger-icon:after{
							background-color : '. esc_attr($sticky_menu_font_color_hover) . ';
						}
					}
				';
			}
		}

		echo sprintf('%s', $out);
	}

	public function cws_custom_page_title_styles_action () {
		//Get metaboxes from page
		$header = $this->cws_get_meta_option('header');
		extract($header, EXTR_PREFIX_ALL, 'header');

		$title_box = $this->cws_get_meta_option( 'title_box' );
		extract($title_box, EXTR_PREFIX_ALL, 'title_box');

		$post_meta = get_post_meta(get_the_id(), 'cws_mb_post');
		if( !empty($post_meta) ){
			$post_meta = $post_meta[0];
		}

		$out = '';
		$start = $title_inside = false;

		// Check if title inside header zone
		if ( isset($header['order']) ) {
            foreach ($header['order'] as $key => $value) {
                if( $value['val'] == 'drop_zone_start' ){
                    $start = true;
                } else if( $value['val'] == 'drop_zone_end' ){
                    $start = false;
                }

                if( $start ){
                    if( $value['val'] == 'title_box' ){
                        $title_inside = true;
                    }
                }
            }
        }

		if( $title_box_customize ){

			if( isset($title_box_border) ){
				$out .= "
					.title-box{
						".esc_attr($this->cws_print_border($title_box_border))."
					}
				";
			}
			if( isset($title_box_overlay) ){
				$out .= "
					.title-box:before{
						".esc_attr($this->cws_print_overlay($title_box_overlay))."
					}
				";
			}
			if( isset($title_box_page_title_size) && !empty($title_box_page_title_size) ){
				$out .= "
					.title-box .title h1{
						font-size: ".(int)esc_attr($title_box_page_title_size)."px;
					}
				";
			}
			if( !empty($title_box_helper_font_color) ){
				$out .= "
					.title-box .subtitle-content,
					.title-box .bread-crumbs a,
					.title-box .bread-crumbs
					{
						color: ".esc_attr($title_box_helper_font_color).";
					}
				";
				$out .= "
					.title-box .bread-crumbs .delimiter:before{
						color: rgba(".$this->cws_Hex2RGB($title_box_helper_font_color).", .5);
					}
				";
			}
			if( !empty($title_box_helper_hover_font_color) ){
			    $out .= "
					.title-box .bread-crumbs .current
					{
					    color: ".esc_attr($title_box_helper_hover_font_color).";
					}
			    ";
				$out .= "
					@media 
						screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
						screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
						screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
						screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
						screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
					{
						.title-box .bread-crumbs a:hover{
							color: ".esc_attr($title_box_helper_hover_font_color).";
						}
					}
				";
			}
			if( $title_box_use_pattern == '1' && isset($title_box_pattern_image['image']['src']) && !empty($title_box_pattern_image['image']['src']) ){
				$out .= "
					.title-box .bg-layer{
						".esc_attr($this->cws_print_background($title_box_pattern_image))."
					}
				";
			}
			if( !empty($post_meta['post_title_box_image']['src']) && !$title_inside ){
				$title_box_background_image['image']['src'] = $post_meta['post_title_box_image']['src'];
			}
			if( !empty($title_box_background_image['image']['src']) ){
				$out .= "
					.title-box{
						".esc_attr($this->cws_print_background($title_box_background_image))."
					}
				";
			}
			if( !empty($title_box_font_color) ){
				$out .= "
					.title-box .title h1{
						color: ".esc_attr($title_box_font_color).";
					}
				";
			}

		}

		echo sprintf('%s', $out);
	}

	public function cws_custom_top_bar_styles_action () {
		//Get metaboxes from page
		$top_bar_box = $this->cws_get_meta_option( 'top_bar_box' );
		extract($top_bar_box, EXTR_PREFIX_ALL, 'top_bar_box');

		$top_bar_box_style = '';

		if( empty($top_bar_box_background_opacity) ){
			$top_bar_box_background_opacity = 100;
		} else {
			$top_bar_box_background_opacity = esc_attr($top_bar_box_background_opacity);
		}

		if( !empty($top_bar_box_background_color) && $top_bar_box_background_color != 'transparent' ){
			$top_bar_box_background_color = esc_attr($top_bar_box_background_color);
			$top_bar_box_style .= 'background-color: rgba('. $this->cws_Hex2RGB( $top_bar_box_background_color ). ',' .($top_bar_box_background_opacity / 100) . ');';
		}

		if( isset($top_bar_box_border) ){
			$top_bar_box_style .= $this->cws_print_border($top_bar_box_border);
		}

		if ( isset($top_bar_box_spacings) ){
			$top_bar_box_padding = '';
			foreach ( $top_bar_box_spacings as $key => $value ){
				if ( !empty( $value ) || $value == '0' ){
					$top_bar_box_padding .= "padding-".esc_attr($key). ": " . esc_attr($value) . "px;";
				}
			}
		}

		$out = '';

		// -----> Top-Bar TAB Settings in ThemeOptions/Metaboxes
		if( isset($top_bar_box_padding) ){
			$out .= "
				.top-bar-icons{
					".$top_bar_box_padding."
				}
			";
		}

		if( !empty($top_bar_box_style) ){
			$out .= "
				.top-bar-wrapper .topbar-trigger:after,
				.top-bar-wrapper{
					".$top_bar_box_style."
				}
			";
		}

		if( !empty($top_bar_box_background_color) ){
			$out .= "
				.top-bar-wrapper .topbar-trigger:before{
					border-color: rgba(". $this->cws_Hex2RGB( $top_bar_box_background_color ). "," .($top_bar_box_background_opacity / 100) . ") transparent transparent transparent;
				}
			";
			$out .= "
				.header-container .top-bar-wrapper .container .top-bar-search.show-search .row-text-search:before,
				.header-container .top-bar-wrapper .container .top-bar-icons.right-icons .social-links-wrapper.toogle-of .cws-social-links:after{
					background: -webkit-linear-gradient(to left, ".$top_bar_box_background_color." 85%, rgba(".$this->cws_Hex2RGB( $top_bar_box_background_color ).", 0.1));
					background: -o-linear-gradient(to left, ".$top_bar_box_background_color." 85%, rgba(".$this->cws_Hex2RGB( $top_bar_box_background_color ).", 0.1));
					background: linear-gradient(to left, ".$top_bar_box_background_color." 85%, rgba(".$this->cws_Hex2RGB( $top_bar_box_background_color ).", 0.1));
				}
			";
			$out .= "
				.header-container .top-bar-wrapper .container .top-bar-icons.left-icons .social-links-wrapper.toogle-of .cws-social-links:after{
					background: -webkit-linear-gradient(to right, ".$top_bar_box_background_color." 85%, rgba(".$this->cws_Hex2RGB( $top_bar_box_background_color ).", 0.1));
					background: -o-linear-gradient(to right, ".$top_bar_box_background_color." 85%, rgba(".$this->cws_Hex2RGB( $top_bar_box_background_color ).", 0.1));
					background: linear-gradient(to right, ".$top_bar_box_background_color." 85%, rgba(".$this->cws_Hex2RGB( $top_bar_box_background_color ).", 0.1));
				}
			";
		}

		if( !empty($top_bar_box_font_color) ){
			$out .= "
				.top-bar-content,
				.top-bar-wrapper .container a,
				.top-bar-wrapper .container .search-icon,
				.top-bar-wrapper .container .top-bar-icons .top-bar-links-wrapper .top-bar-box-text i,
				.top-bar-wrapper .container .social-links-wrapper .social-btn-open,
				.top-bar-wrapper .container .social-links-wrapper .social-btn-open-icon,
				.top-bar-wrapper .ticker-item-price{
					color: ".$top_bar_box_font_color.";
				}
			";
		}

		if( !empty($top_bar_box_hover_font_color) ){
			$out .= "
				.top-bar-wrapper .top-bar-search.show-search .search-icon,
				.top-bar-wrapper .container .social-links-wrapper .social-btn-open.active,
				.top-bar-wrapper .ticker-item-name{
					color: ".$top_bar_box_hover_font_color.";
				}

				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					.top-bar-wrapper .container a:hover,
					.top-bar-wrapper .container .top-bar-icons .search-icon:hover,
					.top-bar-wrapper .container .social-links-wrapper:hover .social-btn-open{
						color: ".$top_bar_box_hover_font_color.";
					}
				}
			";
		}


		// -----> Header TAB Settings in ThemeOptions/Metaboxes (override)
		if( $this->cws_get_meta_option('header')['customize'] == '1' ){
			$top_bar_override_font_color = $this->cws_get_meta_option('header')['override_topbar_color'];
			$top_bar_override_font_color_hover = $this->cws_get_meta_option('header')['override_topbar_color_hover'];

			if( !empty($top_bar_override_font_color) ){
				$out .= "
					.header-zone .top-bar-content,
					.header-zone .top-bar-wrapper .container a,
					.header-zone .top-bar-wrapper .container .search-icon,
					.header-zone .top-bar-wrapper .container .top-bar-icons .top-bar-links-wrapper .top-bar-box-text i,
					.header-zone .top-bar-wrapper .container .social-links-wrapper .social-btn-open,
					.header-zone .top-bar-wrapper .container .social-links-wrapper .social-btn-open-icon,
					.header-zone .top-bar-wrapper .container .top-bar-content .top_bar_shortcode_wrapper .ccpw-price-label ul li .coin-container .price{
						color: ".$top_bar_override_font_color.";
					}
				";
			}

			if( !empty($top_bar_override_font_color_hover) ){
				$out .= "
					.header-zone .top-bar-wrapper .top-bar-search.show-search .search-icon,
					.header-zone .top-bar-wrapper .container .social-links-wrapper .social-btn-open.active,
					.header-zone .top-bar-wrapper .container .top-bar-content .top_bar_shortcode_wrapper .ccpw-price-label ul li .coin-container .name{
						color: ".$top_bar_override_font_color_hover.";
					}

					@media 
						screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
						screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
						screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
						screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
						screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
					{
						.header-zone .top-bar-wrapper .container a:hover,
						.header-zone .top-bar-wrapper .container .top-bar-icons .search-icon:hover,
						.header-zone .top-bar-wrapper .container .social-links-wrapper:hover .social-btn-open{
							color: ".$top_bar_override_font_color_hover.";
						}
					}
				";
			}
		}

		echo sprintf('%s', $out);
	}			

	public function cws_custom_side_panel_styles_action(){

		//Get metaboxes from page
		$side_panel = $this->cws_get_meta_option( 'side_panel' );
		extract($side_panel, EXTR_PREFIX_ALL, 'side_panel');

		$out = '';

		if( $side_panel_enable ){

			if( isset($side_panel_background_image['image']['src']) && !empty($side_panel_background_image['image']['src']) ){
				$out .= "
					aside.side-panel{
						".$this->cws_print_background($side_panel_background_image)."
					}
				";
			}

			if( isset($side_panel_overlay) && $side_panel_overlay['type'] != 'none' ){
				$out .= "
					aside.side-panel:before{
						".esc_attr($this->cws_print_overlay($side_panel_overlay))."
					}
				";
			}

			if( !empty($side_panel_fixed_bg) ){
				$out .= "
					.side-panel-container .side-panel-bottom{
						background-color: ".$side_panel_fixed_bg.";
					}
					.side-panel-container .side-panel-bottom:after{
						background: -webkit-linear-gradient(to top, ".$side_panel_fixed_bg.", transparent 65%);
    					background: linear-gradient(to top, ".$side_panel_fixed_bg.", transparent 65%);
					}
				";
			}

			if( !empty($side_panel_font_color) ){
				$side_panel_font_color = esc_attr($side_panel_font_color);
			}

			if( !empty($side_panel_font_color_hover) ){
				$side_panel_font_color_hover = esc_attr($side_panel_font_color_hover);
			}

			if( !empty($side_panel_font_color) ){
				$out .= "
					aside.side-panel,
					aside.side-panel .button,
					aside.side-panel .cws-widget .widget-title,
					aside.side-panel .cws-widget ul > li a,
					aside.side-panel .cws-widget ul > li a:hover,
					aside.side-panel .cws-widget .menu .menu-item:hover>.opener,
					aside.side-panel .cws-widget .menu .menu-item.current-menu-ancestor>.opener,
					aside.side-panel .cws-widget .menu .menu-item.current-menu-item>.opener,
					aside.side-panel .menu .menu-item:hover > a,
					aside.side-panel .widget_nav_menu .menu li.menu-item-object-megamenu_item .widgettitle,
					aside.side-panel .menu .menu-item.current-menu-ancestor>a,
					aside.side-panel .menu .menu-item.current-menu-item>a,
					aside.side-panel .cws-widget .ourteam_item_title a,
					aside.side-panel .cws-widget .ourteam_item_position a,
					aside.side-panel .widget_woocommerce_product_tag_cloud .tagcloud a,
					aside.side-panel .widget_tag_cloud .tagcloud a,
					aside.side-panel .widget-cws-about .user-name,
					aside.side-panel .gallery-caption,
					aside.side-panel .custom-html-widget > *:not(a),
					aside.side-panel .widget-cws-text .text > *:not(a),
					aside.side-panel .widget-cws-text .cws-custom-button,
					aside.side-panel .widget-cws-twitter .cws-tweet:before,
					.side-panel-container .side-panel-bottom p,
					.side-panel-container .side-panel-bottom i,
					.side-panel-container .side-panel-bottom .cws-social-links,
					.side-panel-bottom span,
					aside.side-panel .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-title a,
					aside.side-panel .cws-widget .parent_archive .widget_archive_opener:before,
					aside.side-panel .widget_recent_comments #recentcomments .recentcomments a,
					aside.side-panel .cws-widget .menu-item-has-children .opener:before{
						color: ".$side_panel_font_color.";
					}

					.side-panel-container .side-panel-bottom .cws-social-links .cws-social-link{
						color: ".$side_panel_font_color."!important;
					}

					aside.side-panel .widget_woocommerce_product_search form .search-field,
					aside.side-panel .widget_search form .search-field{
						border-color: ".$side_panel_font_color.";
					}

					aside.side-panel .cws-widget ul > li:before,
					aside.side-panel .owl-controls .owl-pagination .owl-page span:before,
					aside.side-panel .owl-controls .owl-pagination .owl-page.active span:before,
					aside.side-panel .owl-pagination .owl-page.active:before{
						background-color: ".$side_panel_font_color.";
					}
				";
			}

			if( !empty($side_panel_font_color_hover) ){
				$color = $this->cws_Hex2RGB($side_panel_font_color_hover);

				$out .= "
					aside.side-panel a,
					aside.side-panel .star-rating span,
					aside.side-panel .custom-html-widget a,
					aside.side-panel .widget-cws-text .text a,
					aside.side-panel .widget-cws-contact .cws-textwidget-content .information-group i,
					aside.side-panel .widget-cws-contact .cws-textwidget-content .information-group a,
					aside.side-panel .cws-widget .cws-textwidget-content .user-position,
					aside.side-panel .widget-cws-recent-entries ul li .post-date,
					.side-panel-bottom span:hover,
					aside.side-panel .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-date{
						color: ".$side_panel_font_color_hover.";
					}
				";

				$out .= "
					@media 
						screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
						screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
						screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
						screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
						screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
					{
						aside.side-panel .widget-cws-categories .item .category-block .category-label:hover,
						aside.side-panel .widget_recent_comments #recentcomments .recentcomments a:hover,
						aside.side-panel .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-title a:hover,
						aside.side-panel .widget_recent_entries ul li a:hover,
						aside.side-panel .button:hover{
							color: ".$side_panel_font_color_hover.";
						}

						.side-panel-container .side-panel-bottom .cws-social-links .cws-social-link:hover{
							color: ".$side_panel_font_color_hover." !important;
						}
					}
				";

				$out .= "
					aside.side-panel .button,
					aside.side-panel .widget-cws-banner .banner-desc:before,
					aside.side-panel .widget_recent_entries ul li .post-date:before,
					aside.side-panel .widget-cws-about .cws-textwidget-content .about-me .user-description:before,
					aside.side-panel .widget-cws-text .cws-custom-button,
					aside.side-panel .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-date:before,
					aside.side-panel .cws-widget .calendar_wrap #wp-calendar tr td,
					aside.side-panel .cws-widget .calendar_wrap #wp-calendar tr th,
					aside.side-panel .cws-widget .calendar_wrap #wp-calendar caption{
						background-color: ".$side_panel_font_color_hover.";
					}
				";

				$out .= "
					aside.side-panel .button,
					aside.side-panel .widget-cws-text .cws-custom-button,
					aside.side-panel .owl-controls .owl-pagination .owl-page.active span:after,
					aside.side-panel .cws-widget ul > li > a{
						border-color: ".$side_panel_font_color_hover.";
					}
				";

				$out .= "
					aside.side-panel .widget-cws-twitter .cws-tweet:before,
					aside.side-panel .cws-widget .tagcloud a:before,
					aside.side-panel .cws-widget .widget-title .inherit-wt:before{
						background: -webkit-linear-gradient(135deg, rgba(".$color.", .7), rgba(".$color.",.9));
						background: -o-linear-gradient(135deg, rgba(".$color.", .7), rgba(".$color.",.9));
						background: linear-gradient(135deg, rgba(".$color.", .7), rgba(".$color.",.9));
					}
				";

				$out .= "
					aside.side-panel .widget-cws-twitter .cws-tweet:before{
						-webkit-box-shadow: 3px 3px 10px 0px rgba(".$color.",.2);
						   -moz-box-shadow: 3px 3px 10px 0px rgba(".$color.",.2);
								box-shadow: 3px 3px 10px 0px rgba(".$color.",.2);
					}
				";
			}

		}

		echo sprintf('%s', $out);
	}	

	public function cws_custom_footer_styles_action() {
		//Get metaboxes from page
		$footer = $this->cws_get_meta_option( 'footer' );
		extract($footer, EXTR_PREFIX_ALL, 'footer');

		$out = '';
		$footer_style = '';
		$footer_layer_style = '';
		$footer_pattern_style = '';

		if( isset($footer_border) ){
			$footer_style .= $this ->cws_print_border($footer_border);
		}
		if( isset($footer_spacings) ){
			$footer_style .= $this->cws_print_paddings($footer_spacings);
		}
		if( isset($footer_background_image['image']['src']) && !empty($footer_background_image['image']['src']) ){
			$footer_style .= $this->cws_print_background($footer_background_image);
		}
		if( isset($footer_overlay) && !empty($footer_overlay) ){
			$footer_layer_style .= $this->cws_print_overlay($footer_overlay);
		}
		if (isset($footer_pattern_image['image']['src']) && !empty($footer_pattern_image['image']['src'])){
			$footer_pattern_style .= $this->cws_print_background($footer_pattern_image);
		}

		/* -----> CSS <----- */
		if( !empty($footer_title_color) ){
			$out .= "
				.footer-container .cws-widget .widget-title,
				.footer-content-wrapper .footer-text .footer-text-title,
				.footer-subscribe-form .footer-subscribe-form-input input:focus
				{
					color: ".esc_attr($footer_title_color).";
				}
			";
		}
		if( !empty($footer_font_color) ){
			$out .= "
				.footer-container,
				.footer-container .button,
				.footer-container .cws-widget ul > li a,
				.footer-info-text,
				.page-footer .container .cws-social-links .cws-social-link:before,
				.footer-container .cws-widget .menu .menu-item:hover>.opener,
				.footer-container .cws-widget .menu .menu-item.current-menu-ancestor>.opener,
				.footer-container .cws-widget .menu .menu-item.current-menu-item>.opener,
				.footer-container .menu .menu-item.current-menu-ancestor>a,
				.footer-container .menu .menu-item.current-menu-item>a,
				.footer-container .cws-widget .ourteam_item_title a,
				.footer-container .cws-widget .ourteam_item_position a,
				.footer-container .widget_woocommerce_product_tag_cloud .tagcloud a,
				.footer-container .widget_tag_cloud .tagcloud a,
				.footer-container .widget-cws-about .user-name,
				.footer-container .gallery-caption,
				.footer-container .custom-html-widget > *:not(a),
				.footer-container .widget-cws-text .text > *:not(a),
				.footer-container .widget-cws-text .cws-custom-button,
				.footer-container .widget-cws-twitter .cws-tweet:before,
				.footer-container .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-title a,
				.footer-container .cws-widget .parent_archive .widget_archive_opener:before,
				.footer-container .widget_recent_comments #recentcomments .recentcomments a,
				.footer-container .cws-widget .menu-item-has-children .opener:before,
				.footer-subscribe-form .footer-subscribe-form-input input::placeholder,
				.footer-subscribe-form .footer-subscribe-form-input input
				{
					color: ".$footer_font_color.";
				}

				.footer-container .widget_woocommerce_product_search form .search-field,
				.footer-container .widget_search form .search-field{
					border-color: ".$footer_font_color.";
				}

				.footer-container .cws-widget ul > li:before,
				.footer-container .owl-controls .owl-pagination .owl-page span:before,
				.footer-container .owl-controls .owl-pagination .owl-page.active span:before,
				.footer-container .owl-pagination .owl-page.active:before{
					background-color: ".$footer_font_color.";
				}
			";
		}
		if( !empty($footer_font_color_hover) ){
			$color = $this->cws_Hex2RGB($footer_font_color_hover);

			$out .= "
				.footer-container a,
				.footer-container .star-rating span,
				.footer-container .custom-html-widget a,
				.footer-container .widget-cws-text .text a,
				.footer-container .widget-cws-contact .cws-textwidget-content .information-group i,
				.footer-container .widget-cws-contact .cws-textwidget-content .information-group a,
				.footer-container .cws-widget .cws-textwidget-content .user-position,
				.footer-container .widget_recent_entries ul li .post-date{
					color: ".$footer_font_color_hover.";
				}
			";

			$out .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
					.footer-container .widget-cws-categories .item .category-block .category-label:hover,
					.footer-container .widget_recent_comments #recentcomments .recentcomments a:hover,
					.footer-container .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-title a:hover,
					.footer-container .widget_recent_entries ul li a:hover,
					.footer-container .widget_nav_menu ul > li:hover > a,
					.page-footer .container .cws-social-links .cws-social-link:hover:before,
					.footer-subscribe-form .footer-subscribe-form-button:hover:before,
					.footer-container .button:hover
					{
						color: ".$footer_font_color_hover.";
					}
				}
			";

			$out .= "
				.footer-container .button,
				.footer-container .widget-cws-banner .banner-desc:before,
				.footer-container .widget_recent_entries ul li .post-date:before,
				.footer-container .widget-cws-about .cws-textwidget-content .about-me .user-description:before,
				.footer-container .widget-cws-text .cws-custom-button,
				.footer-container .widget-cws-recent-entries .post-item .post-preview .post-info-wrap .post-date:before,
				.footer-container .widget_nav_menu ul > li > a:before,
				.footer-container .cws-widget .calendar_wrap #wp-calendar tr td,
				.footer-container .cws-widget .calendar_wrap #wp-calendar tr th,
				.footer-container .cws-widget .calendar_wrap #wp-calendar caption
				{
					background-color: ".$footer_font_color_hover.";
				}
			";

			$out .= "
				.footer-container .button,
				.footer-container .widget-cws-text .cws-custom-button,
				.footer-container .owl-controls .owl-pagination .owl-page.active span:after,
				.footer-container .cws-widget ul > li > a
				{
					border-color: ".$footer_font_color_hover.";
				}
			";

			$out .= "
				form .footer-subscribe-form .footer-subscribe-form-input input
				{
					border-color: ".$this->cws_Hex2RGBA($footer_font_color, 0.2).";
				}
				.footer-subscribe-form .footer-subscribe-form-button:before
				{
				    color: ".$this->cws_Hex2RGBA($footer_font_color, 1).";
				}
				.page-footer .container .cws-social-links .cws-social-link:after
				{
				    color: ".$this->cws_Hex2RGBA($footer_font_color, 0.1).";
				}
				.footer-text .footer-text-icon
				{
				    color: ".$this->cws_Hex2RGBA($footer_font_color, 1).";
				}
				
			";

			$out .= "
				.footer-container .widget-cws-twitter .cws-tweet:before,
				.footer-container .cws-widget .tagcloud a:before{
					background: -webkit-linear-gradient(135deg, rgba(".$color.", .7), rgba(".$color.",.9));
					background: -o-linear-gradient(135deg, rgba(".$color.", .7), rgba(".$color.",.9));
					background: linear-gradient(135deg, rgba(".$color.", .7), rgba(".$color.",.9));
				}
			";

			$out .= "
				.footer-container .widget-cws-twitter .cws-tweet:before{
					-webkit-box-shadow: 3px 3px 10px 0px rgba(".$color.",.2);
					   -moz-box-shadow: 3px 3px 10px 0px rgba(".$color.",.2);
							box-shadow: 3px 3px 10px 0px rgba(".$color.",.2);
				}
			";
		}
		if( !empty($footer_pattern_style) ){
			$out .= "
				.page-footer .footer-pattern{
					".esc_attr($footer_pattern_style)."
				}
			";
		}
		if( !empty($footer_style) ){
			$out .= "
				.page-footer{
					".esc_attr($footer_style)."
				}
			";
		}
		if( !empty($footer_layer_style) ){
			$out .= "
				.page-footer .bg-layer{
					".esc_attr($footer_layer_style)."
				}
			";

		}
		if( isset($footer['overlay']['color']) && !empty($footer['overlay']['color']) ){
		    $out .= "
				.page-footer .footer-icon:before{
					color: ".esc_attr($footer['overlay']['color'])." !important;
				}
			";
		}
		if( !empty($footer_icon_bg_color) ){
		    $out .= "
				.page-footer .footer-icon:after{
					color: ".esc_attr($footer_icon_bg_color)." !important;
				}
			";
		}
		if( !empty($footer_icon_color) ){
		    $out .= "
				.page-footer .footer-icon-arrow:before{
					color: ".esc_attr($footer_icon_color)." !important;
				}
			";
		}
		if( !empty($footer_copyrights_background_color) ){
			$out .= "
				.copyrights-area{
					background-color: ".esc_attr($footer_copyrights_background_color).";
				}	
			";
		}
		if( !empty($footer_copyrights_font_color) ){
			$out .= "
				.copyrights-area{
					color: ".esc_attr($footer_copyrights_font_color).";
				}	
			";
		}
		if( !empty($footer_copyrights_hover_color) ){
			$out .= "
				
			";
		}

		echo sprintf('%s', $out);
	}

	public function cws_custom_styles_action(){
		$page_spacing = $this->cws_get_meta_option('page_spacing');
		$page_content_style = '';
		if ( isset($page_spacing) ){
			foreach ( $page_spacing as $key => $value ){
				if ( !empty( $value ) || $value == '0' ){
					$page_content_style .= "padding-".esc_attr($key). ": " . esc_attr($value) . "px;";
				}
			}

			ob_start();
			?>
				#cws-main .page-content{
					<?php echo esc_attr($page_content_style) ?>
				}

			<?php
			echo ob_get_clean();
		}

		$woo_single_button_color = $this->cws_get_option('woo_single_button_color');
		$woo_single_button_bg = $this->cws_get_option('woo_single_button_bg');
		$woo_single_button_bd = $this->cws_get_option('woo_single_button_bd');
		$woo_single_button_color_hover = $this->cws_get_option('woo_single_button_color_hover');
		$woo_single_button_bg_hover = $this->cws_get_option('woo_single_button_bg_hover');
		$woo_single_button_bd_hover = $this->cws_get_option('woo_single_button_bd_hover');

		if ( !empty($woo_single_button_color) || !empty($woo_single_button_bg) || !empty($woo_single_button_bd) || !empty($woo_single_button_color_hover) || !empty($woo_single_button_bg_hover) || !empty($woo_single_button_bd_hover) ) {
		    ob_start();
			?>
				.woocommerce .product .summary .cart .single_add_to_cart_button{
					background-color: <?php echo esc_attr($woo_single_button_bg) ?>;
					border-color: <?php echo esc_attr($woo_single_button_bd) ?>;
					color: <?php echo esc_attr($woo_single_button_color) ?>;
				}
                .woocommerce .product .summary .cart .single_add_to_cart_button:hover,
                .woocommerce .product .summary .cart .single_add_to_cart_button:focus,
                .woocommerce .product .summary .cart .single_add_to_cart_button:active{
					background-color: <?php echo esc_attr($woo_single_button_bg_hover) ?>;
					border-color: <?php echo esc_attr($woo_single_button_bd_hover) ?>;
					color: <?php echo esc_attr($woo_single_button_color_hover) ?>;
				}
			<?php
			echo ob_get_clean();
		}
	}

	public function cws_custom_boxed_layout_styles_action() {
		$out = '';
		$boxed_overlay = '';
		$boxed_background_image = '';

		//Get metaboxes from page
		$boxed = $this->cws_get_meta_option( 'boxed' );

		if ( isset($boxed['layout']) ){
			extract($boxed, EXTR_PREFIX_ALL, 'boxed');

			if ( !empty($boxed_background_image['image']['src']) ){
				$bg = $this->cws_print_css_keys($boxed_background_image, 'background-');
				$bg .= 'background-image:url('.esc_url($boxed_background_image['image']['src']).');';

				$out .= "
					html body.is-boxed{
						".esc_attr($bg)."
					}
				";
			}
			if( $boxed_overlay != 'none' ){
				$boxed_extra = $this->cws_print_overlay($boxed_overlay);
				
				$out .= "
					html body.is-boxed:before{
						".esc_attr($boxed_extra)."
					}
				";	
			}
		}

		echo sprintf('%s', $out);
	}	

	//****************************** //CWS CUSTOM STYLES FUNCTIONS ******************************

	private function cws_print_theme_gradient () {
		ob_start();
		do_action( 'theme_gradient_hook' );
		return ob_get_clean();
	}

	public function cws_theme_gradient_action () {
		$out = '';
		$use_gradients = $this->cws_get_option('use_gradients');
		if ( $use_gradients ) {
			$gradient_settings = $this->cws_get_option( 'gradient_settings' );
			require_once( get_template_directory() . "/css/gradient_selectors.php" );
			if ( function_exists( "get_gradient_selectors" ) ) {
				$gradient_selectors = get_gradient_selectors();
				$out .= $this->cws_print_gradient( array(
					'settings' => $gradient_settings,
					'selectors' => $gradient_selectors,
					'use_extra_rules' => true
				));
			}
		}
		echo preg_replace('/\s+/',' ', $out);
	}

	public function cws_gradients_body_class ( $classes ) {
		$use_gradients = $this->cws_get_option('use_gradients');
		if ( $use_gradients ) {
			$classes[] = "cws-gradients";
		}
		return $classes;
	}

	public function cws_process_colors() {
		$out = $this->cws_print_theme_color();
		return $out;
	}

	public function cws_Hex2RGB($hex) {
		$hex = str_replace('#', '', $hex);
		$color = '';

		if(strlen($hex) == 3) {
			$color = hexdec(mb_substr($hex, 0, 1)) . ',';
			$color .= hexdec(mb_substr($hex, 1, 1)) . ',';
			$color .= hexdec(mb_substr($hex, 2, 1));
		}
		else if(strlen($hex) == 6) {
			$color = hexdec(mb_substr($hex, 0, 2)) . ',';
			$color .= hexdec(mb_substr($hex, 2, 2)) . ',';
			$color .= hexdec(mb_substr($hex, 4, 2));
		}
		return $color;
	}

	// \  COLOR HOOK

	public function cws_theme_header_process_fonts (){
		return $this->cws_process_fonts();
	}

	public function cws_theme_header_process_colors (){
		return $this->cws_process_colors();
	}

	public function cws_theme_loader (){
		return $this->cws_loader();
	}	
	/* END THE HEADER META */

	/* Comments */
	public function cws_comment_nav() {
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
		?>
		<div class="comments-nav carousel_nav_panel clearfix">
			<?php
			    echo '<div class="prev-section">';
				if ( $prev_link = get_previous_comments_link( "<span class='prev'></span><span>" . esc_html__( 'Older Comments', 'metamax' ) . "</span>" ) ) {
					printf( '%s', $prev_link );
				}
				echo '</div>';
                echo '<div class="next-section">';
				if ( $next_link = get_next_comments_link( "<span>" . esc_html__( 'Newer Comments', 'metamax' ) . "</span><span class='next'></span>" ) ) {
					printf( '%s', $next_link );
				}
				echo '</div>';
			?>
		</div><!-- .comment-navigation -->
		<?php
		}
	}

	public function cws_comment_post( $incoming_comment ) {
		$comment = strip_tags($incoming_comment['comment_content']);
		$comment = esc_html($comment);
		$incoming_comment['comment_content'] = $comment;
		return $incoming_comment;
	}
	/* /Comments */

	/* SIDE PANEL */
	public function cws_side_panel() {
		//Get metaboxes from page
		$side_panel = $this->cws_get_meta_option( 'side_panel' );
		extract($side_panel, EXTR_PREFIX_ALL, 'side_panel');

		$side_panel_class = 'side-panel-container';
		$side_panel_class .= ' panel-' . (!empty($side_panel_position) ? esc_attr($side_panel_position) : 'left');
		$side_panel_class .= ' appear-' . (!empty($side_panel_appear) ? esc_attr($side_panel_appear) : 'fade');

		if( $side_panel_enable ){

			$logo_src = '';
			if( !empty($side_panel_logo['src']) ){
				$logo_hw = $side_panel_logo_dimensions;

				$bfi_args = array();
				if( is_array($logo_hw) ){
					foreach( $logo_hw as $key => $value ){
						if( !empty($value) ){
							$bfi_args[$key] = $value;
							$bfi_args['crop'] = false;
						}
					}
				}

				$main_logo_height = '';
				$logo_src = $this->cws_print_img_html($side_panel_logo, $bfi_args, $main_logo_height);
			}

			echo '<div class="side-panel-overlay '.esc_attr($side_panel_appear).'"></div>';
			echo '<div class="'.$side_panel_class.'">';
				echo '<div class="side-panel-bg"></div>';
				echo '<aside class="side-panel '.(isset($side_panel_bottom_bar['info_icons']) ? ' bottom_bar' : '').'">';
					echo '<div class="side-panel-logo-wrapper logo-'.(!empty($side_panel_logo_position) ? esc_attr
					($side_panel_logo_position) : 'left').'">';
						echo '<img '.$logo_src . get_post_meta( $side_panel_logo["id"], '_wp_attachment_image_alt', true).' />';
					echo '</div>';
					if( !empty($side_panel_sidebar) && is_active_sidebar($side_panel_sidebar) ){
						dynamic_sidebar($side_panel_sidebar);
					}
				echo "</aside>";

				if( isset($side_panel_bottom_bar) || $side_panel_add_social ){
					echo '<div class="side-panel-bottom">';
						if( isset($side_panel_bottom_bar['info_icons']) ){
							echo '<div class="info-icons-rows">';
								foreach ($side_panel_bottom_bar['info_icons'] as $key => $value) {

									if( !empty($value['url']) ){

										$value['link_type'] != 'link' ? $type = $value['link_type'] : $type = '';

										$start_tag = '<a href="'.$type.$value['url'].'"';
										$end_tag = '</a>';
									} else {
										$start_tag = '<p';
										$end_tag = '</p>';
									}

									echo sprintf('%s', $start_tag.'>');
										echo '<i class="'.esc_attr($value["icon"]).'"></i>';
										echo '<span>'.esc_html($value['title']).'</span>';
									echo sprintf('%s', $end_tag);
								}
							echo '</div>'; 
						}	

						if( $side_panel_add_social ){
							$social_links = $this->cws_render_social_links('side_panel');
							if( !empty($social_links) ){
								echo sprintf('%s', $social_links);
							}
						}
					echo '</div>';	
				}
			echo '</div>';
		}
	}
	/* SIDE PANEL */

	public function cws_widget_title_icon_rendering( $args = array() ) {
		extract( shortcode_atts(
			array(
				'icon_type' => '',
				'icon_fa' => '',
				'icon_img' => array(),
				'icon_color' => '#fff',
				'icon_bg_type' => 'color',
				'icon_bgcolor' => METAMAX_FIRST_COLOR,
				'gradient_first_color' => METAMAX_FIRST_COLOR,
				'gradient_second_color' => '#0eecbd',
				'gradient_type' => '',
				'gradient_linear_angle' => '',
				'gradient_radial_shape' => '',
				'gradient_radial_type' => '',
				'gradient_radial_size_key' => '',
				'gradient_radial_size' => '',
				), $args));

		$r = $icon_styles = '';
		if ( $icon_type == 'fa' && !empty( $icon_fa ) ) {
			switch ($icon_bg_type) {
				case 'none':
					$icon_styles .= "border-width: 1px; border-style: solid;";
					break;
				case 'color':
					$icon_styles .= "background-color:$icon_bgcolor;";
					break;
				case 'gradient':
					$gradient_settings = $this->cws_extract_array_prefix($args, 'gradient');
					$gradient_settings_arr = array(
						'first_color' => $gradient_settings["first_color"],
						'second_color' => $gradient_settings["second_color"],
						'type' => $gradient_settings["type"],
						'linear_settings' => array(
							'angle' => $gradient_settings["linear_angle"],
						),
						'radial_settings' => array(
							'shape_settings' => $gradient_settings["radial_shape"],
							'shape' => $gradient_settings["radial_type"],
							'size_keyword' => $gradient_settings["radial_size_key"],
							'size' => $gradient_settings["radial_size"],
						),
					);

					$gradient_settings = isset( $gradient_settings_arr ) ? $gradient_settings_arr : new stdClass();
					$settings = new stdClass();

					foreach ($gradient_settings_arr as $key => $value) {
						$settings->$key = $value;
					}

					$icon_styles .= esc_attr( $this->cws_print_gradient( array( 'settings' => $settings ) ) );
					break;
			}

			$icon_styles .= "color:$icon_color;";
			$r .= "<i class='$icon_fa' style='$icon_styles'></i>";
		}	else if ( $icon_type == 'img' && !empty( $icon_img['src'] ) ) {

			$font = $this->cws_get_meta_option( 'body-font' );
			$font_size = isset( $font['font_size'] ) ? preg_replace( 'px', '', $font['font_size'] ) : '15';
			$thumb_size = (int)round( (float)$font_size * 2 );

			$g_img = $this->cws_print_img_html(array('src' => $icon_img['src']), array( 'width' => $thumb_size, 'height' => $thumb_size ));
			$this->cws_echo_ne($g_img, "<img{$g_img}/>");

		}
		return $r;
	}

	private function cws_extract_array_prefix($arr, $prefix) {
		$ret = array();
		$pref_len = strlen($prefix);
		foreach ($arr as $key => $value) {
			if (0 === strpos($key, $prefix . '_') ) {
				$ret[mb_substr($key, $pref_len+1)] = $value;
			}
		}
		return $ret;
	}

	public function cws_move_comment_field_to_bottom( $fields ) {
		$comment_field = $fields['comment'];
		unset( $fields['comment'] );
		$fields['comment'] = $comment_field;

		if ( has_action( 'set_comment_cookies', 'wp_set_comment_cookies' ) && get_option( 'show_comments_cookies_opt_in' ) ) {
            if ( isset( $fields ) && isset( $fields['cookies'] ) ) {
                $cookies_field = $fields['cookies'];
                unset( $fields['cookies'] );
                $fields['cookies'] = $cookies_field;
            }
        }

		return $fields;
	}
}
/* end of Theme's Class */

//Functions
add_action( 'init', 'cws_setup_admin_styles' );
function cws_setup_admin_styles() {
	// Admin`s gutenberg styles
	wp_enqueue_style( 'admin-style', get_template_directory_uri() . '/core/css/gutenberg.css', array(), '1.0.0', 'all' );
}

function cws_logo_extra_info() {
	global $cws_theme_funcs;

	$logo_box = $cws_theme_funcs->cws_get_meta_option( 'logo_box' );
	extract($logo_box, EXTR_PREFIX_ALL, 'logo_box');

	$extra_info = '';

	if( !empty($logo_box_extra_title) || !empty($logo_box_extra_link) ){
		$extra_info .= '<div class="logo-extra-info">';
			if( !empty($logo_box_extra_title) ){
				$extra_info .= '<p>'.wp_kses( $logo_box_extra_title, array(
					'a' 	=> array(
						'href'	=> array(),
						'title' => array()
					),
					'span' 	=> array(),
					'p' 	=> array(),
					'i' 	=> array(
						'class'	=> array()
					),
					'br' 	=> array(),
					'center' 	=> array(),
					'em' 	=> array(),
					'strong'=> array(),
				)).'</p>';
			}
			if( !empty($logo_box_extra_link) ){
				$extra_info .= '<i class="'.esc_attr($logo_box_extra_icon).'"></i>';
				if( $logo_box_link_type != 'link' ){
					$extra_info .= '<a href="'.esc_attr($logo_box_link_type).esc_attr($logo_box_extra_link).'">'.esc_html($logo_box_extra_link).'</a>';
				} else {
					$extra_info .= '<a href="'.esc_attr($logo_box_extra_link).'">'.esc_html($logo_box_extra_link).'</a>';
				}
			}
		$extra_info .= '</div>';
	}

	return $extra_info;
}

function cws_vc_animations_disable() {
	wp_dequeue_style('animate-style');
	wp_deregister_style('animate-style');
}

function cws_custom_background() {
    ob_start();

    _custom_background_cb(); // Default handler

    $style = ob_get_clean();

    echo sprintf("%s", $style);
}

if(!function_exists('cws_pagination')){
	function cws_page_links(){
	    global $more;
	    $more = 1;
		$args = array(
			'before'		   => '',
			'after'			=> '',
			'link_before'	  => '<span>',
			'link_after'	   => '</span>',
			'next_or_number'   => 'number',
			'nextpagelink'	 =>  esc_html__("Next Page",'metamax'),
			'previouspagelink' => esc_html__("Previous Page",'metamax'),
			'pagelink'		 => '%',
			'echo'			 => 0
		);
		$pagination = wp_link_pages( $args );
		echo !empty( $pagination ) ? "<div class='pagination'><div class='page-links'>$pagination</div></div>" : '';
	}
}

if(!function_exists('cws_pagination')){
	function cws_pagination ( $paged=1, $max_paged=1, $dynamic = true ){
		$is_rtl = is_rtl();

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
		$classes = '';
		$classes .= $dynamic ? ' dynamic' : '';
		?>

		<div class="pagination<?php echo sprintf("%s", $classes); ?>">
			<div class='page-links'>
			<?php
			$pagination_args = array( 'base' => $pagenum_link,
				'format' => $format,
				'current' => $paged,
				'total' => $max_paged,
				"prev_text" => "<i class='" . ( $is_rtl ? "rtl" : "" ) . "'></i>",
				"next_text" => "<i class='" . ( $is_rtl ? "rtl" : "" ) . "'></i>",
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
}

if(!function_exists('cws_loader_html')){
	function cws_loader_html ( $args = array() ){
		extract( wp_parse_args( $args, array(
			'holder_id'		=> '',
			'holder_class' 	=> '',
			'loader_id'		=> '',
			'loader_class'	=> ''
		)));
		$holder_class 	.= " cws-loader-holder";
		$loader_class 	.= " cws_loader";
		$holder_id		= esc_attr( $holder_id );
		$holder_class 	= esc_attr( trim( $holder_class ) );
		$loader_id		= esc_attr( $loader_id );
		$loader_class 	= esc_attr( trim( $loader_class ) );
		echo "<div " . ( !empty( $holder_id ) ? " id='$holder_id'" : "" ) . " class='$holder_class'>";
			echo "<div " . ( !empty( $loader_id ) ? " id='$loader_id'" : "" ) . " class='$loader_class'>";
				?>
				<svg width='104px' height='104px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-default"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(0 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(30 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.08333333333333333s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(60 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.16666666666666666s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(90 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.25s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(120 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.3333333333333333s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(150 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.4166666666666667s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(180 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.5s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(210 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.5833333333333334s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(240 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.6666666666666666s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(270 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.75s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(300 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.8333333333333334s' repeatCount='indefinite'/></rect><rect  x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(330 50 50) translate(0 -30)'>  <animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.9166666666666666s' repeatCount='indefinite'/></rect></svg>
				<?php
			echo "</div>";
		echo "</div>";
	}	
}

function cws_footer_info_box() {
	global $cws_theme_funcs;

	$footer = $cws_theme_funcs->cws_get_meta_option( 'footer' );
	extract($footer, EXTR_PREFIX_ALL, 'footer');

	$footer_info = '';

	if ( !empty($footer_content_items) ){
	    $footer_info .= "<div class='footer-content-wrapper'>";

	    foreach ($footer_content_items as $key => $value) {

	        if( !empty($value['icon']) ){
	            $footer_row_icon = "<i class='".esc_attr($value['icon'])."'></i>";
	        } else {
	        	$footer_row_icon = "";
	        }

	        if ( !empty($value['url']) ){

	                $footer_info .= "<a class='footer-text' href='".esc_attr($value['url'])."'>";
                        $footer_info .= "<span class='footer-text-icon'>";
                            $footer_info .= $footer_row_icon;
                        $footer_info .= "</span>";
                        $footer_info .= "<span class='footer-text-content'>";
                            $footer_info .= "<span class='footer-text-title'>";
                                $footer_info .= esc_html($value['title']);
                            $footer_info .= "</span>";
                        $footer_info .= "</span>";
	                $footer_info .= "</a>";
	        } else {
	            $footer_info .= "<div class='footer-text'>";
                    $footer_info .= "<span class='footer-text-icon'>";
                        $footer_info .= $footer_row_icon;
                    $footer_info .= "</span>";
                    $footer_info .= "<span class='footer-text-content'>";
                        $footer_info .= "<span class='footer-text-title'>";
                            $footer_info .= esc_html($value['title']);
                        $footer_info .= "</span>";
                    $footer_info .= "</span>";
	            $footer_info .= "</div>";
	        }
	    }
	    $footer_info .= "</div>";
	}

	return $footer_info;
}

//Add inline styles to enqueue
if(!function_exists('Cws_shortcode_css')){
    function Cws_shortcode_css() {
        return Cws_shortcode_css::instance();
    }
}

if ( !class_exists( "Cws_shortcode_css" ) ){
    class Cws_shortcode_css{
        public $settings;
        protected static $instance = null;

        public static function instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }    
        public function enqueue_cws_css( $style ) {
            if(!empty($style)){
                wp_register_style( 'cws-footer', false);
                wp_enqueue_style( 'cws-footer' );
                wp_add_inline_style( 'cws-footer', $style );
            }
        }
    }
}
//Add inline styles to enqueue

/* FA ICONS */
function cws_get_all_fa_icons() {
	$meta = get_option('cws_fa');
	if (!empty($meta) || (time() - $meta['t']) > 3600*7 ) {
		global $wp_filesystem;
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$file = get_template_directory() . '/fonts/font-awesome/font-awesome.css';
		$fa_content = '';
		if ( $wp_filesystem && $wp_filesystem->exists($file) ) {
			$fa_content = $wp_filesystem->get_contents($file);
			if ( preg_match_all( "/fa-((\w+|-?)+):before/", $fa_content, $matches, PREG_PATTERN_ORDER ) ) {
				return $matches[1];
			}
		}
	} else {
		return $meta['fa'];
	}
}
/* \FA ICONS */

/* FL ICONS */
function cws_get_all_flaticon_icons() {
	$cwsfi = get_option('cwsfi');
	if (!empty($cwsfi) && isset($cwsfi['entries'])) {
		return $cwsfi['entries'];
	} else {
		global $wp_filesystem;
		if( empty( $wp_filesystem ) ) {
			require_once( ABSPATH .'/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		$file = get_template_directory() . '/fonts/flaticon/flaticon.css';
		$fi_content = '';
		$out = '';
		if ( $wp_filesystem && $wp_filesystem->exists($file) ) {
			$fi_content = $wp_filesystem->get_contents($file);
			if ( preg_match_all( "/flaticon-((\w+|-?)+):before/", $fi_content, $matches, PREG_PATTERN_ORDER ) ){
				return $matches[1];
			}
		}
	}
}
/* \FL ICONS */

/********************************** !!! **********************************/

function cws_twitter_renderer ( $atts, $content = '' ) {
	global $cws_theme_funcs;

	extract( shortcode_atts( array(
		'in_widget' => false,
		'title' => '',
		'centertitle' => '0',
		'items' => get_option( 'posts_per_page' ),
		'visible' => get_option( 'posts_per_page' ),
		'showdate' => '0'
	), $atts));
	
	$out = '';
	$tw_username = trim($cws_theme_funcs->cws_get_option( 'tw-username' )) ? trim($cws_theme_funcs->cws_get_option( 'tw-username' )) : 'Creative_WS';

	if ( !is_numeric( $items ) || !is_numeric( $visible ) ) return $out;
	$tweets = cws_getTweets( (int)$items );

	if ( is_string( $tweets ) ) {
		$out .= do_shortcode( "[cws_sc_msg_box title='" . esc_attr__( 'Twitter responds:', 'metamax' ) . "' description='$tweets' is_closable='1'][/cws_sc_msg_box]" );
	} else if ( is_array( $tweets ) && isset($tweets['error']) ){
		echo esc_html($tweets['error']);
	} else if ( is_array( $tweets ) ) {
		$use_carousel = count( $tweets ) > $visible;
		$section_class = "cws-tweets";
		$section_class .= $use_carousel ? " cws-carousel-wrapper" : '';

		$section_atts = " data-columns='".$visible."'";
		$section_atts .= " data-pagination='on'";
		$section_atts .= " data-auto-height='on'";
		$section_atts .= " data-draggable='on'";

		//Reg-exp for links
		$find_links = "/(https?:\/\/|ftp:\/\/|www\.)((?![.,?!;:()]*(\s|$))[^\s]){2,}/"; 

		if ( $use_carousel && !$in_widget ) {
			$out .= "<div class='tweets_carousel_header'>";
				$out .= "<a href='http://twitter.com/".esc_attr($tw_username)."' class='follow_us fab fa-twitter' target='_blank'></a>";
			$out .= "</div>";
		}
		$out .= "<div class='".esc_attr($section_class)."'".$section_atts.">";
			$out .= "<div class='cws-carousel cws-wrapper'>";
				$carousel_item_closed = false;

				for ( $i=0; $i<count( $tweets ); $i++ ) {
					$tweet = $tweets[$i];

					if ( $use_carousel && ( $i == 0 || $carousel_item_closed ) ) {
						wp_enqueue_script('slick-carousel');
						$out .= "<div class='item'>";
						$carousel_item_closed = false;
					}

					//Remove image links from text
					$tweet_text = preg_replace($find_links, '', $tweet['text']);
					$tweet_entitties = isset( $tweet['entities'] ) ? $tweet['entities'] : array();
					$tweet_urls = isset( $tweet_entitties['urls'] ) && is_array( $tweet_entitties['urls'] ) ? $tweet_entitties['urls'] : array();

					foreach ( $tweet_urls as $tweet_url ) {
						$display_url = isset( $tweet_url['display_url'] ) ? $tweet_url['display_url'] : '';
						$received_url = isset( $tweet_url['url'] ) ? $tweet_url['url'] : '';
						$tweet_text  .= "<a href='".esc_url($received_url)."'>".esc_html($display_url)."</a>";
					}

					$item_content = '';
					$item_content .= !empty( $tw_username ) ? "<div class='tweet-author'>@" . esc_html($tw_username) . "</div>" : "";
					$item_content .= !empty( $tweet_text ) ? "<div class='tweet-content'>$tweet_text</div>" : '';
					if ( $showdate ) {
						$tweet_date = isset( $tweet['created_at'] ) ? $tweet['created_at'] : '';
						$tweet_date_formatted = cws_time_elapsed_string( date( "U", strtotime( $tweet_date ) ) );
						$item_content .= "<div class='tweet-date'>$tweet_date_formatted</div>";
					}

					$out .= !empty( $item_content ) ? "<div class='cws-tweet'>$item_content</div>" : '';
					$temp1 = ( $i + 1 ) / (int)$visible;
					if ( $use_carousel && ( $temp1 - floor( $temp1 ) == 0 || $i == count( $tweets ) - 1 ) ) {
						$out .= "</div>";
						$carousel_item_closed = true;
					}
				}

			$out .= "</div>";
		$out .= "</div>";
	}
	return $out;
}

function cws_time_elapsed_string($ptime){
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 365 * 24 * 60 * 60  =>  'year',
                 30 * 24 * 60 * 60  =>  'month',
                      24 * 60 * 60  =>  'day',
                           60 * 60  =>  'hour',
                                60  =>  'minute',
                                 1  =>  'second'
                );
    $a_plural = array( 'year'   => 'years',
                       'month'  => 'months',
                       'day'    => 'days',
                       'hour'   => 'hours',
                       'minute' => 'minutes',
                       'second' => 'seconds'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
        }
    }
}

function cws_getTweets( $count = 20 ) {
	$res = null;
	global $cws_theme_funcs;

	if ( '0' != $cws_theme_funcs->cws_get_option( 'turn-twitter' ) ) {
		$twitt_name = trim($cws_theme_funcs->cws_get_option( 'tw-username' )) ? trim($cws_theme_funcs->cws_get_option( 'tw-username' )) : 'Creative_WS';
		if (function_exists('getTweets')) {
			$res = getTweets($twitt_name, $count);
		}
	}

	return $res;
}

if( !isset($content_width) ){
	$content_width = 1170;
}

if(!function_exists('cws_load_more')){
	function cws_load_more ( $paged = 1, $max_paged = PHP_INT_MAX ){
	?>	
		<div class="cws-custom-button-wrapper load-more">
			<a class="cws-custom-button small cws-load-more" href="#"><?php esc_html_e( "Load More", 'metamax' ); ?></a>
		</div>
	<?php
	}
}

function cws_portfolio_loader(){
	ob_start();
	?>
		<div class='portfolio_loader_wraper'>
			<div class='portfolio_loader_container'>
				<svg width='104px' height='104px' xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" class="uil-default"><rect x="0" y="0" width="100" height="100" fill="none" class="bk"></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(0 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(30 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.083s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(60 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.1667s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(90 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.25s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(120 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.33s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(150 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.4166s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(180 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.5s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(210 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.5833s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(240 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.67s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(270 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.75s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(300 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.83s' repeatCount='indefinite'/></rect><rect x='46.5' y='40' width='7' height='20' rx='5' ry='5' fill='#000000' transform='rotate(330 50 50) translate(0 -30)'><animate attributeName='opacity' from='1' to='0' dur='1s' begin='0.9167s' repeatCount='indefinite'/></rect></svg>
			</div>
		</div>
	<?php
	echo ob_get_clean();
}

/****************** WALKER CUSTOM MENU *********************/
class Walker_Nav_Menu_Edit_Custom extends Walker_Nav_Menu  {
	/**
	 * @see Walker_Nav_Menu::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see Walker_Nav_Menu::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param object $args
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $_wp_nav_menu_max_depth;

		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;

		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		ob_start();
		$item_id = esc_attr( $item->ID );
		$removed_args = array(
			'action',
			'customlink-tab',
			'edit-menu-item',
			'menu-item',
			'page-tab',
			'_wpnonce',
		);

		$original_title = '';
		if ( 'taxonomy' == $item->type ) {
			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
			if ( is_wp_error( $original_title ) )
				$original_title = false;
		} elseif ( 'post_type' == $item->type ) {
			$original_object = get_post( $item->object_id );
			$original_title = $original_object->post_title;
		}

		$classes = array(
			'menu-item menu-item-depth-' . $depth,
			'menu-item-' . esc_attr( $item->object ),
			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
		);

		$title = $item->title;

		if ( ! empty( $item->_invalid ) ) {
			$classes[] = 'menu-item-invalid';
			/* translators: %s: title of menu item which is invalid */
			$title = sprintf( __( '%s (Invalid)', 'metamax' ), $item->title );
		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
			$classes[] = 'pending';
			/* translators: %s: title of menu item in draft status */
			$title = sprintf( __('%s (Pending)', 'metamax'), $item->title );
		}

		$title = empty( $item->label ) ? $title : $item->label;

		?>
		<li id="menu-item-<?php echo esc_attr($item_id); ?>" class="<?php echo implode(' ', $classes ); ?>">
			<dl class="menu-item-bar">
				<dt class="menu-item-handle">
					<span class="item-title"><?php echo esc_html( $title ); ?></span>
					<span class="item-controls">
						<span class="spinner"></span>
						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
						<span class="item-order hide-if-js">
							<a href="<?php
								echo esc_url(wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-up-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								));
							?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'metamax'); ?>">&#8593;</abbr></a>
							|
							<a href="<?php
								echo esc_url(wp_nonce_url(
									add_query_arg(
										array(
											'action' => 'move-down-menu-item',
											'menu-item' => $item_id,
										),
										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
									),
									'move-menu_item'
								));
							?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'metamax'); ?>">&#8595;</abbr></a>
						</span>
						<a class="item-edit" id="edit-<?php echo esc_attr($item_id); ?>" title="<?php esc_attr_e('Edit Menu Item', 'metamax'); ?>" href="<?php
							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : esc_url(add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) ));
						?>"><?php _e( 'Edit Menu Item', 'metamax' ); ?></a>
					</span>
				</dt>
			</dl>

			<div class="menu-item-settings wp-clearfix" id="menu-item-settings-<?php echo esc_attr($item_id); ?>">
				<?php if( 'custom' == $item->type ) : ?>
					<p class="field-url description description-wide">
						<label for="edit-menu-item-url-<?php echo esc_attr($item_id); ?>">
							<?php _e( 'URL', 'metamax' ); ?><br />
							<input type="text" id="edit-menu-item-url-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
						</label>
					</p>
				<?php endif; ?>
				<p class="description description-thin">
					<label for="edit-menu-item-title-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Navigation Label', 'metamax' ); ?><br />
						<input type="text" id="edit-menu-item-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
					</label>
				</p>
				<p class="description description-thin">
					<label for="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Title Attribute', 'metamax' ); ?><br />
						<input type="text" id="edit-menu-item-attr-title-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
					</label>
				</p>
				<p class="field-link-target description">
					<label for="edit-menu-item-target-<?php echo esc_attr($item_id); ?>">
						<input type="checkbox" id="edit-menu-item-target-<?php echo esc_attr($item_id); ?>" value="_blank" name="menu-item-target[<?php echo esc_attr($item_id); ?>]"<?php checked( $item->target, '_blank' ); ?> />
						<?php _e( 'Open link in a new window/tab', 'metamax' ); ?>
					</label>
				</p>
				<p class="field-css-classes description description-thin">
					<label for="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'CSS Classes (optional)', 'metamax' ); ?><br />
						<input type="text" id="edit-menu-item-classes-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
					</label>
				</p>
				<p class="field-xfn description description-thin">
					<label for="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Link Relationship (XFN)', 'metamax' ); ?><br />
						<input type="text" id="edit-menu-item-xfn-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
					</label>
				</p>
				<p class="field-description description description-wide">
					<label for="edit-menu-item-description-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Description', 'metamax' ); ?><br />
						<textarea id="edit-menu-item-description-<?php echo esc_attr($item_id); ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo esc_attr($item_id); ?>]"><?php echo esc_html( $item->description ); ?></textarea>
						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'metamax'); ?></span>
					</label>
				</p>

				<?php
				/* New fields insertion starts here */
				?>

				<p class="field-custom description description-thin description-thin-custom">
					<label for="edit-menu-item-align-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Text alignment', 'metamax' ); ?><br />
						<select class="widefat" id="edit-menu-item-align<?php echo esc_attr($item_id); ?>" data-item-option data-name="align-<?php echo esc_attr($item_id); ?>">
							<option value="left" <?php if($item->align == "left"){echo 'selected="selected"';} ?>>Left</option>
							<option value="center" <?php if($item->align == "center"){echo 'selected="selected"';} ?>>Center</option>
							<option value="right" <?php if($item->align == "right"){echo 'selected="selected"';} ?>>Right</option>
						</select>
					</label>
				</p>

				<?php
				$icon_data_attr = 'icon-'. esc_attr($item_id);

				$icons = cws_get_all_fa_icons();
				$isIcons = !empty($icons);

				$ficons = cws_get_all_flaticon_icons();
				$isFlatIcons = !empty($ficons);

				$output_icons = '<option value=""></option>';
				?>

				<p class="field-custom description description-thin description-thin-custom">
					<label for="edit-menu-item-icon-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Icon', 'metamax' ); ?><br />
						<select class="widefat icons-select" id="edit-menu-item-icon<?php echo esc_attr($item_id); ?>" data-item-option data-name="<?php echo esc_attr($icon_data_attr); ?>">
							<?php
							if ($isIcons){
								$output_icons .= '<optgroup label="Font Awesome">';
								foreach ($icons as $icon) {
									$selected = ($item->icon === 'fa fa-' . $icon) ? ' selected' : '';
									$output_icons .= '<option value="fa fa-' . esc_attr($icon) . '" '.esc_attr($selected).'>' . esc_attr($icon) . '</option>';
								}
								$output_icons .= '</optgroup>';
							}

							if ($isFlatIcons){
								$output_icons .= '<optgroup label="Flaticon">';
								foreach ($ficons as $icon) {
									$selected = ($item->icon === 'flaticon-' . $icon) ? ' selected' : '';
									$output_icons .= '<option value="flaticon-' . esc_attr($icon) . '" '.esc_attr($selected).'>' . esc_attr($icon) . '</option>';
								}
								$output_icons .= '</optgroup>';
							}

							printf('%s', $output_icons);
							?>
						</select>
						<br/><?php _e( 'Select icon from list', 'metamax' ); ?>
					</label>
				</p>

				<p class="field-custom description description-thin">
					<?php
					$value = $item->hide;
					if($value != "") $value = "checked";
					?>
					<label for="edit-menu-item-hide-<?php echo esc_attr($item_id); ?>">
						<input type="checkbox" id="edit-menu-item-hide-<?php echo esc_attr($item_id); ?>" class="code edit-menu-item-custom" data-item-option data-name="hide-<?php echo esc_attr($item_id); ?>" value="hide" <?php echo esc_attr($value); ?> />
						<?php _e( "Don't show in menu", 'metamax' ); ?>
					</label>
				</p>

				<p class="field-custom description description-thin description-thin-custom">
					<?php
					$value = $item->tag;
					if($value != "") $value = "checked";
					?>
					<label for="edit-menu-item-tag-<?php echo esc_attr($item_id); ?>">
						<input type="checkbox" id="edit-menu-item-tag-<?php echo esc_attr($item_id); ?>" class="code edit-menu-item-custom" data-item-option data-name="tag-<?php echo esc_attr($item_id); ?>" value="tag" <?php echo esc_attr($value); ?> />
						<?php _e( "Show tag label", 'metamax' ); ?>
					</label>
				</p>				

				<p class="field-custom description description-wide description-wide-custom">
					<label for="edit-menu-item-tag_text-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Tag text', 'metamax' ); ?><br />
						<input type="text" id="edit-menu-item-tag_text-<?php echo esc_attr($item_id); ?>" class="widefat code edit-menu-item-tag_text" data-item-option data-name="tag_text-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $item->tag_text ); ?>" />
					</label>
				</p>

				<p class="field-custom description description-thin description-thin-custom">
					<label for="edit-menu-item-tag_font_color-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Tag color', 'metamax' ); ?><br />
						<input type="text" data-default-color="<?php echo esc_attr(METAMAX_FIRST_COLOR);?>" id="edit-menu-item-tag_font_color-<?php echo esc_attr($item_id); ?>" class="color_picker widefat code edit-menu-item-tag_font_color" data-item-option data-name="tag_font_color-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $item->tag_font_color ); ?>" />
					</label>
				</p>

				<p class="field-custom description description-thin description-thin-custom">
					<label for="edit-menu-item-tag_bg_color-<?php echo esc_attr($item_id); ?>">
						<?php _e( 'Tag background', 'metamax' ); ?><br />
						<input type="text" data-default-color="#ffffff" id="edit-menu-item-tag_bg_color-<?php echo esc_attr($item_id); ?>" class="color_picker widefat code edit-menu-item-tag_bg_color" data-item-option data-name="tag_bg_color-<?php echo esc_attr($item_id); ?>" value="<?php echo esc_attr( $item->tag_bg_color ); ?>" />
					</label>
				</p>								

				<?php
				/* New fields insertion ends here */
				?>
				<div class="menu-item-actions description-wide submitbox">
					<?php if( 'custom' != $item->type && $original_title !== false ) : ?>
						<p class="link-to-original">
							<?php printf( __('Original: %s', 'metamax'), '<a href="' . esc_url( $item->url ) . '">' .
							esc_html( $original_title ) . '</a>' ); ?>
						</p>
					<?php endif; ?>
					<a class="item-delete submitdelete deletion" id="delete-<?php echo esc_attr($item_id); ?>" href="<?php
					echo esc_url(wp_nonce_url(
						add_query_arg(
							array(
								'action' => 'delete-menu-item',
								'menu-item' => $item_id,
							),
							remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
						),
						'delete-menu_item_' . esc_attr($item_id)
					)); ?>"><?php _e('Remove', 'metamax'); ?></a> <span class="meta-sep"> | </span> <a class="item-cancel submitcancel" id="cancel-<?php echo esc_attr($item_id); ?>" href="<?php echo esc_url( add_query_arg( array('edit-menu-item' => $item_id, 'cancel' => time()), remove_query_arg( $removed_args, admin_url( 'nav-menus.php' ) ) ) );
						?>#menu-item-settings-<?php echo esc_attr($item_id); ?>"><?php _e('Cancel', 'metamax'); ?></a>
				</div>

				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr($item_id); ?>" />
				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo esc_attr($item_id); ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
			</div><!-- .menu-item-settings-->
			<ul class="menu-item-transport"></ul>
		<?php

		$output .= ob_get_clean();

		}
}

/****************** WALKER *********************/
class Metamax_Walker_Nav_Menu extends Walker {
	private $elements;
	private $elements_counter = 0;
	private $cws_theme_funcs;

	function __construct($a) {
		$this->cws_theme_funcs = $a;
	}

	function walk ($items, $depth, ...$args) {
		$this->elements = $this->get_number_of_root_elements($items);
		return parent::walk($items, $depth);
	}

	/**
	 * @see Walker::$tree_type
	 * @since 3.0.0
	 * @var string
	 */
	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );

	/**
	 * @see Walker::$db_fields
	 * @since 3.0.0
	 * @todo Decouple this.
	 * @var array
	 */
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">";
		$output .= "\n";
	}
	/**
	 * @see Walker::end_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */

	function logo_ini( $indent, $item ) {
		$logo_box = $this->cws_theme_funcs->cws_get_meta_option( 'logo_box' );
		extract($logo_box, EXTR_PREFIX_ALL, 'logo_box');

		$logo_cont = '';

		$logo_lr_spacing = $logo_tb_spacing = $main_logo_height = '';


		if ($logo_box_default !== 'custom') {
			$logo = isset($this->cws_theme_funcs->cws_get_option( 'logo_box' )[$logo_box_default]) ? $this->cws_theme_funcs->cws_get_option( 'logo_box' )[$logo_box_default] : ''; //Call from ThemeOptions
		} else {
			$logo = isset($this->cws_theme_funcs->cws_get_meta_option( 'logo_box' )['custom']) ? $this->cws_theme_funcs->cws_get_meta_option( 'logo_box' )['custom'] : '';
		}
		$logo_box_sticky = isset($this->cws_theme_funcs->cws_get_meta_option( 'sticky_menu' )['sticky']) ?
		$this->cws_theme_funcs->cws_get_meta_option( 'sticky_menu' )['sticky'] : ''; //Call from ThemeOptions
		$logo_box_mobile = isset($this->cws_theme_funcs->cws_get_meta_option( 'mobile_menu_box' )['mobile']) ?
		$this->cws_theme_funcs->cws_get_option( 'mobile_menu_box' )['mobile'] : ''; //Call from ThemeOptions
		$mobile_logo_nav = isset($this->cws_theme_funcs->cws_get_option( 'mobile_menu_box' )['navigation']) ? $this->cws_theme_funcs->cws_get_option( 'mobile_menu_box' )['navigation'] : ''; //Call from ThemeOptions

		$logo_box_dimensions_sticky = isset($this->cws_theme_funcs->cws_get_option( 'sticky_menu' )['dimensions_sticky']) ? $this->cws_theme_funcs->cws_get_option( 'sticky_menu' )['dimensions_sticky'] : ''; //Call from ThemeOptions
		$logo_box_dimensions_mobile = isset($this->cws_theme_funcs->cws_get_meta_option( 'mobile_menu_box' )['dimensions_mobile']) ? $this->cws_theme_funcs->cws_get_option( 'mobile_menu_box' )['dimensions_mobile'] : ''; //Call from ThemeOptions
		$mobile_logo_nav_dimensions = isset($this->cws_theme_funcs->cws_get_option( 'mobile_menu_box' )['dimensions_navigation']) ? $this->cws_theme_funcs->cws_get_option( 'mobile_menu_box' )['dimensions_navigation'] : ''; //Call from ThemeOptions

		if ( $logo_box_position == 'center' && $logo_box_in_menu == '1' && $logo_box_enable ) {
			if ( !empty($logo['src']) ) {			
				$bfi_args = $bfi_args_sticky = $bfi_args_mobile = array();
				if ( is_array( $logo_box_dimensions ) ) {
					foreach ( $logo_box_dimensions as $key => $value ) {
						if ( ! empty( $value ) ) {
							$bfi_args[ $key ] = $value;
							$bfi_args['crop'] = true;
						}
					}
				}
				if ( is_array( $logo_box_dimensions_sticky ) ) {
					foreach ( $logo_box_dimensions_sticky as $key => $value ) {
						if ( ! empty( $value ) ) {
							$bfi_args_sticky[ $key ] = $value;
							$bfi_args_sticky['crop'] = true;
						}
					}
				}				
				if ( is_array( $logo_box_dimensions_mobile ) ) {
					foreach ( $logo_box_dimensions_mobile as $key => $value ) {
						if ( ! empty( $value ) ) {
							$bfi_args_mobile[ $key ] = $value;
							$bfi_args_mobile['crop'] = true;
						}
					}
				}
				if ( is_array( $mobile_logo_nav_dimensions ) ) {
					foreach ( $mobile_logo_nav_dimensions as $key => $value ) {
						if ( ! empty( $value ) ) {
							$bfi_args_nav[ $key ] = $value;
							$bfi_args_nav['crop'] = true;
						}
					}
				}

				$logo_lr_spacing = $logo_tb_spacing = '';
				if ( is_array( $logo_box_margin ) ) {
					$logo_lr_spacing = $this->cws_theme_funcs->cws_print_css_keys($logo_box_margin, 'margin-', 'px');
					$logo_tb_spacing = $this->cws_theme_funcs->cws_print_css_keys($logo_box_margin, 'padding-', 'px');
				}

				$img_mrg = ! empty( $logo_lr_spacing ) ? "style='".esc_attr( $logo_lr_spacing )."'" : '';

				$logo_src = $this->cws_theme_funcs->cws_print_img_html($logo, $bfi_args, $main_logo_height);


				if(!empty($logo['src'])){
					$logo_default = '<img '. $logo_src .' '. $img_mrg .' class="logo-desktop" />';
				}

				$logo_sticky = $this->cws_theme_funcs->cws_get_meta_option('sticky_menu')['sticky'];
				if ( isset($logo_sticky) && !empty( $logo_sticky['src'] ) ) {
					$logo_sticky_src = $this->cws_theme_funcs->cws_print_img_html($logo_sticky['id'], (!empty($bfi_args_sticky) ? $bfi_args_sticky : null));
				}

				$logo_mobile = $this->cws_theme_funcs->cws_get_meta_option('mobile_menu_box')['mobile'];
				if (isset($logo_mobile) && !empty($logo_mobile['src'])) {
					$logo_mobile_src = $this->cws_theme_funcs->cws_print_img_html($logo_mobile['id'], (!empty($bfi_args_mobile) ? $bfi_args_mobile : null));
				}

				$logo_nav = $this->cws_theme_funcs->cws_get_option('mobile_menu_box')['navigation'];
				if (isset($logo_nav) && !empty($logo_nav['src'])) {
					$logo_nav_src = $this->cws_theme_funcs->cws_print_img_html($logo_nav['id'], (!empty($bfi_args_nav) ? $bfi_args_nav : null));
				}

				$rety = home_url('/');

				$logo_cont = '
				</ul></div>
					<div class="menu-center-part menu-logo-part">
						<a class="logo" href="'.esc_url($rety).'">';

						if( !empty($logo['src']) ){
							$file_parts = pathinfo($logo['src']);

							$logo_cont .= "<div class='logo-default-wrapper logo-wrapper'>";
							if( $file_parts['extension'] == 'svg' ){
								$logo_cont .= $this->cws_theme_funcs->cws_print_svg_html($logo, $bfi_args, $main_logo_height);
							} else {
								$logo_cont .= $logo_default;
							}
							$logo_cont .= "</div>";
						}

						if( !empty($logo_sticky_src) ){
							$file_parts_sticky = pathinfo($logo['src']);

							$logo_cont .= "<div class='logo-sticky-wrapper logo-wrapper'>";
							if( $file_parts_sticky['extension'] != 'svg' ){
								$logo_cont .= ($logo_sticky_src ?  '<img '.$logo_sticky_src." class='logo-sticky' />"
								 : '');
							} else {
								$logo_cont .= $this->cws_theme_funcs->cws_print_svg_html($logo_sticky, $bfi_args);
							}
							$logo_cont .= "</div>";
						}

						if( !empty($logo_nav_src) ){
							$file_parts_nav = pathinfo($logo['src']);

							$logo_cont .= "<div class='logo-navigation-wrapper logo-wrapper'>";
							if( $file_parts_nav['extension'] != 'svg' ){
								$logo_cont .= ($logo_nav_src ?  '<img '.$logo_nav_src." class='logo-navigation' />" :
								 '');
							} else {
								$logo_cont .= $this->cws_theme_funcs->cws_print_svg_html($logo_nav, $bfi_args);
							}
							$logo_cont .= "</div>";
						}

						$logo_cont .= '
						</a>
					</div>
				<div class="menu-right-part"><ul class="main-menu">';
			} else {
				$logo_cont = '
				</ul></div>
					<div class="menu-center-part menu-logo-part">
						<h1 class="header-site-title">'.esc_html(get_bloginfo( 'name' )).'</h1>
					</div>
				<div class="menu-right-part"><ul class="main-menu">';
			}
		}
		return $logo_cont;
	}

	function site_name_ini( $indent, $item ) {
		$logo_box_position = $this->cws_theme_funcs->cws_get_meta_option( 'logo_box' )['position'];
		if ( $indent == 0 && $logo_box_position == 'center' ) {
			ob_start();
		?>
				</ul>
			</div>
			<div class="menu-center-part menu-logo-part site-name" <?php echo isset( $logo_box_position ) && !empty(
			        $logo_box_position ) && $logo_box_position == 'center' && ! empty( $logo_tb_spacing ) ? " style='".esc_attr($logo_tb_spacing)."'" : ''; ?>>
				<a <?php echo ( ! empty( $logo_lr_spacing ) ? " style='".esc_attr($logo_lr_spacing)."'" : '') ?> class="logo" href="<?php echo esc_url( home_url('/') ); ?>" >
					<h1 class='header-site-title'><?php bloginfo( 'name' ); ?></h1>
				</a>
			</div>
			<div class="menu-right-part">
				<ul class="main-menu">
		<?php
			$site_name_cont = ob_get_clean();
		} else {
			$site_name_cont = '';
		}

		return $site_name_cont;
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . sanitize_html_class( $item->ID );

		//Custom menu fields
		if ($item->align != 'left' && isset($item->align)){
			array_push($classes,'link_align_'.$item->align);
		}

		if ($item->hide == 'hide'){
			array_push($classes,'hide_link');
		}	
		//Custom menu fields	

		if ($item->menu_item_parent=="0") {
			$this->elements_counter += 1;
			if ($this->elements_counter>$this->elements/2){
				array_push($classes,'sub-align-right');
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . $class_names  . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. sanitize_html_class( $item->ID ), $item, $args );
		$id = $id ? ' id="' . $id . '"' : '';

		// logo in cont init;
		if ( $item->menu_item_parent == '0' && $this->elements_counter == floor(($this->elements / 2)+1) ) {
			$logo_container = $this->logo_ini( $indent, $item );
		} else {
			$logo_container = '';
		}

		$output .= $indent . (!empty($search_and_woo_icon_start) ? $search_and_woo_icon_start : '' ) . $logo_container . '<li' . $id . $value . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )	 ? $item->target	 : '';
		$atts['rel']	= ! empty( $item->xfn )		? $item->xfn		: '';
		$atts['href']   = ! empty( $item->url )		? $item->url		: '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = !empty($args->before) ? $args->before : '';
		$item_output .= '<a'. $attributes .'>';

		$item_output .= ( !empty($args->link_before) ? $args->link_before : "" ) .
			apply_filters( 'nav_menu_item_title', $item->title, $item, $args, $depth ) .
			(is_rtl() ? '&#x200E;' : '') . ( !empty($args->link_after ) ? $args->link_after : "" );

		$item_output .= '</a>';

		if (is_array($item->classes)){
			if ( in_array( 'menu-item-has-children', $item->classes ) ){
				$item_output .= "<i class='button-open'></i>";
			}
		}

		$item_output .= ( !empty($args->after) ? $args->after : '' );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * @see Walker::end_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Page data object. Not used.
	 * @param int $depth Depth of page. Not Used.
	 */

	function end_el( &$output, $item, $depth = 0, $args = array() ) {

		$output .= "</li>\n";
	}
}

/****************** TOPBAR WALKER *********************/
class Metamax_Walker_Nav_Topbar_Menu extends Walker {
	private $elements;
	private $elements_counter = 0;
	private $cws_theme_funcs;

	function __construct($a) {
		$this->cws_theme_funcs = $a;
	}

	function walk ($items, $depth, ...$args) {
		$this->elements = $this->get_number_of_root_elements($items);
		return parent::walk($items, $depth);
	}

	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">";
		$output .= "\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . sanitize_html_class( $item->ID );

		if ($item->menu_item_parent=="0") {
			$this->elements_counter += 1;
			if ($this->elements_counter>$this->elements/2){
				array_push($classes,'right');
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . $class_names  . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'top-bar-menu-item-'. sanitize_html_class( $item->ID ), $item, $args );
		$id = $id ? ' id="' . $id . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )	 ? $item->target	 : '';
		$atts['rel']	= ! empty( $item->xfn )		? $item->xfn		: '';
		$atts['href']   = ! empty( $item->url )		? $item->url		: '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = !empty($args->before) ? $args->before : '';
		$item_output .= '<a'. $attributes .'>';

		$item_output .= ( !empty($args->link_before) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . (is_rtl() ? '&#x200E;' : '') . ( !empty($args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';

		$item_output .= ( !empty($args->after) ? $args->after : '' );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}

/****************** COPYRIGHTS WALKER *********************/
class Metamax_Walker_Nav_Copyright_Menu extends Walker {
	private $elements;
	private $elements_counter = 0;
	private $cws_theme_funcs;

	function __construct($a) {
		$this->cws_theme_funcs = $a;
	}

	function walk ($items, $depth, ...$args) {
		$this->elements = $this->get_number_of_root_elements($items);
		return parent::walk($items, $depth);
	}

	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"sub-menu\">";
		$output .= "\n";
	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . sanitize_html_class( $item->ID );

		if ($item->menu_item_parent=="0") {
			$this->elements_counter += 1;
			if ($this->elements_counter>$this->elements/2){
				array_push($classes,'right');
			}
		}

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr($class_names)  . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'top-bar-menu-item-'. sanitize_html_class( $item->ID ), $item, $args );
		$id = $id ? ' id="' . $id . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$atts = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target )	 ? $item->target	 : '';
		$atts['rel']	= ! empty( $item->xfn )		? $item->xfn		: '';
		$atts['href']   = ! empty( $item->url )		? $item->url		: '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output = !empty($args->before) ? $args->before : '';
		$item_output .= '<a'. $attributes .'>';

		$item_output .= ( !empty($args->link_before) ? $args->link_before : '' ) . apply_filters( 'the_title', $item->title, $item->ID ) . (is_rtl() ? '&#x200E;' : '') . ( !empty($args->link_after ) ? $args->link_after : '' );
		$item_output .= '</a>';

		$item_output .= ( !empty($args->after) ? $args->after : '' );

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		$output .= "</li>\n";
	}
}

/* Comments */
class METAMAX_Walker_Comment extends Walker_Comment {
	// init classwide variables
	var $tree_type = 'comment';
	var $db_fields = array( 'parent' => 'comment_parent', 'id' => 'comment_ID' );
	function __construct() { ?>
		<div class="comment-list">
	<?php }

	/** START_LVL
	 * Starts the list before the CHILD elements are added. Unlike most of the walkers,
	 * the start_lvl function means the start of a nested comment. It applies to the first
	 * new level under the comments that are not replies. Also, it appear that, by default,
	 * WordPress just echos the walk instead of passing it to &$output properly. Go figure.  */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		<div class="comments-children">
	<?php }

	/** END_LVL
	 * Ends the children list of after the elements are added. */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$GLOBALS['comment_depth'] = $depth + 1; ?>
		</div><!-- /.children -->

	<?php }

	/** START_EL */
	function start_el( &$output, $comment, $depth = 0, $args = array(), $id = 0 ) {
		$depth++;
		global $cws_theme_funcs;
		$GLOBALS['comment_depth'] = $depth;
		$GLOBALS['comment'] = $comment;
		$parent_class = ( empty( $args['has_children'] ) ? '' : 'parent' );
		$old_version = 0;
		?>

		<div <?php comment_class( $parent_class ); ?> id="comment-<?php comment_ID() ?>">
			<div id="comment-body-<?php comment_ID() ?>" class="comment-body">
				<div class="comment-info-section">
					<div class="comment-info">

                            <?php
                                if( $args['avatar_size'] != 0 ){
                                    echo get_avatar( $comment, $args['avatar_size'] );
                                }
                            ?>

						<?php $reply_args = array(
							'reply_text'    => esc_html__( 'Reply', 'metamax' ),
							'depth'         => $depth,
							'max_depth'     => $args['max_depth'],
							'add_below'     => 'comment-body'
						); ?>

						<div class="comment-meta comment-meta-data">
							<div class="comment-top-info">
							    <cite class="comment-author"><?php echo get_comment_author_link(); ?></cite>
								<?php
								echo "<span class='date'>";
								    echo sprintf( __( '%s ago', 'metamax' ), human_time_diff( get_comment_time( 'U' ),
								    current_time( 'timestamp' ) ) );
								echo "</span>";
								?>
					        </div>
					        <div class="comments-buttons">
								<?php
									echo "<div class='button-content reply'>";
									comment_reply_link( array_merge( $args, $reply_args ) );
									echo '</div>';

									edit_comment_link( esc_html__('(Edit)', 'metamax') );
								?>
							</div>
						</div><!-- /.comment-meta -->

					    <div id="comment-content-<?php comment_ID(); ?>" class="comment-content">
						<?php
						if( !$comment->comment_approved ) { ?>
						    <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'metamax'); ?></em>
						<?php
						} else {
						    if ($comment->comment_type != 'pingback')
						    {
						        comment_text();
						    }
						} ?>

					    </div><!-- /.comment-content -->

					</div>

				</div>
			</div><!-- /.comment-body -->

	<?php }

	function end_el(&$output, $comment, $depth = 0, $args = array() ) { ?>

		</div><!-- /#comment-' . get_comment_ID() . ' -->

	<?php }

	/** DESTRUCTOR
	 * I just using this since we needed to use the constructor to reach the top
	 * of the comments list, just seems to balance out :) */
	function __destruct() { ?>

	</div><!-- /#comment-list -->

	<?php }
}
/* \Comments */

?>