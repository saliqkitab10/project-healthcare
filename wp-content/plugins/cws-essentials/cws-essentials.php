<?php
/*
Plugin Name: CWS Essentials
Plugin URI:  http://cwsthemes.com
Description: Internal use for CWSThemes themes only.
Text Domain: cws-essentials
Version: 1.0.4
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Author:      CWSThemes
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

//Check if plugin active
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if (!defined('CWS_SHORTCODES_PLUGIN_NAME'))
	define('CWS_SHORTCODES_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('CWS_SHORTCODES_PLUGIN_DIR'))
	define('CWS_SHORTCODES_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . CWS_SHORTCODES_PLUGIN_NAME);


if (!defined('CWS_SHORTCODES_PLUGIN_URL'))
	define('CWS_SHORTCODES_PLUGIN_URL', WP_PLUGIN_URL . '/' . CWS_SHORTCODES_PLUGIN_NAME);

require_once( CWS_SHORTCODES_PLUGIN_DIR . '/cws-metaboxes.php' );
require_once( CWS_SHORTCODES_PLUGIN_DIR . '/cws_thumb.php');

//Get custom post types slugs
function cws_get_slug($slug) {
	$new_slug = '';
	global $cws_theme_funcs;
	if(!empty($cws_theme_funcs)){
		$new_slug = $cws_theme_funcs->cws_get_option($slug.'_slug');
	}
	$new_slug = !empty( $new_slug ) ? $new_slug : $slug;

	return sanitize_title($new_slug);
}

//Regenerate permalinks, if slug (blog / portfolio / staff / testimonials) changed
add_action( "init", "cws_rewrite_slug", 11 );

function cws_rewrite_slug() {
	$cws_rewrite_slug = get_option('cws_rewrite_slug');
	if ($cws_rewrite_slug){ 
		flush_rewrite_rules();
		update_option('cws_rewrite_slug', false);
	}
}

// Attach Theme Widgets
add_action( "widgets_init", "cws_init_widgets" );
function cws_init_widgets(){
	if( !class_exists('Metamax_Widgets') ){
		require_once WP_PLUGIN_DIR . '/cws-essentials/cws-widgets.php';
	}

	$widgets =  array(
		'CWS_Text',
		'CWS_Latest_Posts',
		'CWS_Portfolio',
		'CWS_Twitter',
		'CWS_Contact',
		'CWS_About',
		'CWS_Categories',
		'CWS_Gallery',
		'CWS_Banner',
	);

	$cws_widgets_reg = new Metamax_Widgets($widgets);
}

//Add responsive suffix
function add_responsive_suffix($variables) {
	foreach ($variables as $key => $value) {

		if( $key == 'all' ){
			$inner_array = $value;

			foreach ($inner_array as $inner_key => $inner_value) {
				$inner_array[$inner_key.'_landscape'] = $inner_value;
				$inner_array[$inner_key.'_portrait'] = $inner_value;
				$inner_array[$inner_key.'_mobile'] = $inner_value;	
			}

			$variables['all'] = $inner_array;
		} else if( $key == 'landscape' ){
			$inner_array = $value;

			foreach ($inner_array as $inner_key => $inner_value) {
				$inner_array[$inner_key.'_landscape'] = $inner_value;
			}

			$variables['landscape'] = $inner_array;
		} else if( $key == 'portrait' ){
			$inner_array = $value;

			foreach ($inner_array as $inner_key => $inner_value) {
				$inner_array[$inner_key.'_portrait'] = $inner_value;
			}

			$variables['portrait'] = $inner_array;
		} else if( $key == 'mobile' ){
			$inner_array = $value;

			foreach ($inner_array as $inner_key => $inner_value) {
				$inner_array[$inner_key.'_mobile'] = $inner_value;
			}

			$variables['mobile'] = $inner_array;
		}

	}

	!isset($variables['all']) ? $variables['all'] = array() : '';
	!isset($variables['landscape']) ? $variables['landscape'] = array() : '';
	!isset($variables['portrait']) ? $variables['portrait'] = array() : '';
	!isset($variables['mobile']) ? $variables['mobile'] = array() : '';

	$out = array_merge($variables['all'], $variables['landscape'], $variables['portrait'], $variables['mobile']);

	return $out;
}

//Get VC responsive classes
function vc_responsive_styles($array){
	$desktop = $landscape = $portrait = $mobile = "";

	if( gettype($array) == 'array' ){
		foreach ($array as $key => $value) {
			if( $key == 'custom_styles' ){
				$desktop = $value;
			} else if( $key == 'custom_styles_landscape' ){
				$landscape = $value;
			} else if( $key == 'custom_styles_portrait' ){
				$portrait = $value;
			} else if( $key == 'custom_styles_mobile' ){
				$mobile = $value;
			}
		}
	}

	return array($desktop, $landscape, $portrait, $mobile);
}

//Add background properties to responsive vars
function add_bg_properties( $array ){

	if( array_key_exists('all', $array) ){
		foreach ($array as $key => $value) {
			if( isset($key) && ($key) == 'all' ){
				$value['bg_position'] = 'top';
				$value['bg_size'] = 'auto';
				$value['bg_repeat'] = 'no-repeat';
				$value['custom_bg_position'] = '';
				$value['custom_bg_size'] = '';
				$value['bg_display'] = '';

				$array[$key] = $value;
			}
		}
	} else {
		$array['all'] = array(
			'bg_position' => 'top',
			'bg_size' => 'auto',
			'bg_repeat' => 'no-repeat',
			'custom_bg_position' => '',
			'custom_bg_size' => '',
			'bg_display' => '',
		);
	}

	return $array;
}

//Repeater roadmap item
function print_roadmap_item($label, $title, $description, $end_point, $icon_lib, $icon, $item_color){
	$out = $result = $styles = '';
    $id = uniqid( "cws_roadmap-item-" );

    if ( !empty($item_color) ) {
        $styles .= "
			.".$id.".roadmap-item .roadmap-label:before,
			.".$id.".roadmap-item .roadmap-icon-wrapper:before
			{
				color: ".esc_attr($item_color).";
			}
			.".$id.".roadmap-item:after,
			.".$id.".roadmap-item .roadmap-label:after,
			.".$id.".roadmap-item .roadmap-icon-wrapper:after
			{
				background-color: ".esc_attr($item_color).";
			}
		";
    }

    if ( !empty($styles) ){
        Cws_shortcode_css()->enqueue_cws_css($styles);
    }

    if( !empty($icon_lib) ){
        $result .= "<div class='roadmap-icon-wrapper'>";
        if( !empty($icon) ){
            $result .= "<span class='icon'>";
                $result .= "<i class='cws_vc_shortcode_icon_3x " . $icon . "'></i>";
            $result .= "</span>";
        }
        $result .= "</div>";
    }

	if( !empty($label) || !empty($title) || !empty($description) || !empty($result) ){
		$out .= "<div class='roadmap-item ".( !empty($end_point) ? 'end-point' : '' ).( !empty($id) ? " " . esc_attr($id) : "" ) . "'>";
            $out .= "<div class='roadmap-item-inner'>";

                if ( !empty($result) ){
                    $out .= $result;
                }

                $out .= "<div class='roadmap-label'><span>".esc_attr($label)."</span></div>";

                if( !empty($title) || !empty($description) ){
                    $out .= "<div class='roadmap-item-info'>";
                        if( !empty($title) ){
                            $out .= "<h5 class='roadmap-title'>".esc_attr($title)."</h5>";
                        }
                        if( !empty($description) ){
                            $out .= "<p class='roadmap_desc'>".esc_attr($description)."</p>";
                        }
                    $out .= "</div>";
                }
            $out .= "</div>";
		$out .= "</div>";
	}

	return $out;
}

//Add thumbnail image to posts
function add_post_thumb_name ($columns) {
	$columns = array_slice($columns, 0, 1, true) +
				array('post_thumbnail' => esc_html__('Thumbnails', 'cws-essentials')) +
				array_slice($columns, 1, NULL, true);
	return $columns;
}
add_filter('manage_post_posts_columns', 'add_post_thumb_name');

//Add thumbnail image to posts
function add_post_thumb ($column, $id) {
	if ('post_thumbnail' === $column) {
		echo the_post_thumbnail('thumbnail');
	}
}
add_action('manage_post_posts_custom_column', 'add_post_thumb', 5, 2);

/*Term images*/
// add_action( 'edit_term', 'cws_show_extra_term_fields' );
add_action( 'admin_init', 'cws_taxonomy_image_init');

/*Users extra profile fields*/
add_action( 'show_user_profile', 'cws_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'cws_show_extra_profile_fields' );
add_action( 'personal_options_update', 'cws_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'cws_save_extra_profile_fields' );

function cws_taxonomy_image_init(){
    $taxonomies = get_taxonomies();

    add_filter('manage_edit-category_columns', 'cws_category_columns');
    add_filter('manage_category_custom_column', 'cws_category_columns_fields', 10, 3);

    foreach ((array) $taxonomies as $taxonomy) {
    	if ($taxonomy == 'category'){
        	cws_add_custom_column_fields($taxonomy);
    	}
    }
}

/**
 * Load plugin textdomain.
 */
function cws_load_textdomain() {
  load_plugin_textdomain( 'cws-essentials', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'plugins_loaded', 'cws_load_textdomain' );

function cws_add_custom_column_fields($taxonomy){
    add_action($taxonomy."_add_form_fields", 'cws_show_extra_term_fields');
    add_action($taxonomy."_edit_form_fields", 'cws_show_extra_term_fields');

	add_action("created_".$taxonomy, 'cws_save_extra_term_fields' );
	add_action("edited_".$taxonomy, 'cws_save_extra_term_fields' );

    //Add custom columns
    add_filter("manage_edit-".$taxonomy."_columns", 'cws_category_columns');
    add_filter("manage_".$taxonomy."_custom_column", 'cws_category_columns_fields', 10, 3);
}

function cws_category_columns($columns){
    $columns['image'] = esc_html__( 'Image', 'cws-essentials' );
    return $columns;
}

function cws_category_columns_fields($deprecated, $column_name, $term_id){
	$term_image = get_term_meta( $term_id, 'cws_mb_term' );
    if (!empty($term_image)){
		echo "<img class='term_table_img' src='".$term_image[0]['image']['src']."' alt=''>";
    }
}

// Extra term fields
function cws_show_extra_term_fields( $term_id ) {
	global $pagenow;

	$mb_attr = array(	
		'image' => array(
			'title' => esc_html__( 'Image', 'cws-essentials' ),
			'subtitle' => esc_html__( 'Upload your photo here.', 'cws-essentials' ),
			'addrowclasses' => 'hide_label wide_picture box grid-col-12',
			'type' => 'media',
		),
	);

	if ( in_array($pagenow,array('edit-tags.php','term.php')) ){
		echo '<h3>Additional categories information (CWS Themes)</h3>';
		echo '<div id="cws-post-metabox-id-1">';
			echo '<div class="inside" data-w="0">';
			wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );
			if (gettype ($term_id) == 'object'){
				$cws_stored_meta = get_term_meta( $term_id->term_id, 'cws_mb_term' );
			}
			if (function_exists('cws_core_cwsfw_fillMbAttributes') ) {
				if (!empty($cws_stored_meta[0])) {
					cws_core_cwsfw_fillMbAttributes($cws_stored_meta[0], $mb_attr);
				}
				echo cws_core_cwsfw_print_layout($mb_attr, 'cws_mb_');
			}
			echo "</div>";
		echo "</div>";
	}
}

function cws_save_extra_term_fields( $term_id/*, $tt_id*/) {
	$save_array = array();

	foreach($_POST as $key => $value) {
		if (0 === strpos($key, 'cws_mb_')) {
			if ('on' === $value) {
				$value = '1';
			}
			if (is_array($value)) {
				foreach ($value as $k => $val) {
					if (is_array($val)) {
						$save_array[substr($key, 7)][$k] = $val;
					} else {
						$save_array[substr($key, 7)][$k] = esc_html($val);
					}
				}
			} else {
				$save_array[substr($key, 7)] = esc_html($value);
			}
		}
	}
	if (!empty($save_array)) {
		update_term_meta( $term_id, 'cws_mb_term', $save_array );
	}
	return;
}

// Extra user fields
function cws_show_extra_profile_fields( $user ) {
	$mb_attr = array(
		'position' => array(
			'type' => 'text',
			'title' => esc_html__('Position', 'cws-essentials' ),
			'addrowclasses' => 'box grid-col-12',
		),		
		'avatar' => array(
			'title' => esc_html__( 'Avatar', 'cws-essentials' ),
			'subtitle' => esc_html__( 'Upload your photo here.', 'cws-essentials' ),
			'addrowclasses' => 'hide_label wide_picture box grid-col-12',
			'type' => 'media',
		),
		'social_group' => array(
			'type' => 'group',
			'addrowclasses' => 'group expander sortable box grid-col-12',
			'title' => esc_html__('Social networks', 'cws-essentials' ),
			'button_title' => esc_html__('Add new social network', 'cws-essentials' ),
			'button_icon' => 'fas fa-plus',
			'button_class' => 'button button-primary',
			'layout' => array(
				'title' => array(
					'type' => 'text',
					'atts' => 'data-role="title"',
					'addrowclasses' => 'grid-col-2',
					'title' => esc_html__('Social account title', 'cws-essentials' ),
				),
				'icon' => array(
					'type' => 'select',
					'addrowclasses' => 'fai grid-col-3',
					'source' => 'fa',
					'title' => esc_html__('Icon for this social contact', 'cws-essentials' )
				),
				'color'	=> array(
					'title'	=> esc_html__( 'Icon color', 'cws-essentials' ),
					'atts' => 'data-default-color="#595959"',
					'addrowclasses' => 'grid-col-3',
					'value' => '#595959',
					'type'	=> 'text',
				),										
				'url' => array(
					'type' => 'text',
					'addrowclasses' => 'grid-col-3',
					'title' => esc_html__('Url to your account', 'cws-essentials' ),
				)
			),
		),
		'author_url' => array(
			'type' => 'text',
			'title' => esc_html__('Author page URL', 'cws-essentials' ),
			'addrowclasses' => 'box grid-col-12',
		),			
	);

	echo '<h3>Additional profile information (CWS Themes)</h3>';
	echo '<div id="cws-post-metabox-id-1" class="postbox">';
		echo '<div class="inside" data-w="0">';
		wp_nonce_field( 'cws_mb_nonce', 'mb_nonce' );
		$cws_stored_meta = get_user_meta( $user->ID, 'cws_mb_user' );
		/*if (function_exists('cws_core_cwsfw_fillMbAttributes') ) {
			if (!empty($cws_stored_meta[0])) {
				cws_core_cwsfw_fillMbAttributes($cws_stored_meta[0], $mb_attr);
			}
			echo cws_core_cwsfw_print_layout($mb_attr, 'cws_mb_');
		}*/

		if (function_exists('cws_core_build_layout') ) {
			echo cws_core_build_layout((!empty($cws_stored_meta) ? $cws_stored_meta[0] : ''), $mb_attr, 'cws_mb_');
		}

		echo "</div>";
	echo "</div>";
}

function cws_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) return false;

	$save_array = array();

	foreach($_POST as $key => $value) {
		if (0 === strpos($key, 'cws_mb_')) {
			if ('on' === $value) {
				$value = '1';
			}
			if (is_array($value)) {
				foreach ($value as $k => $val) {
					if (is_array($val)) {
						$save_array[substr($key, 7)][$k] = $val;
					} else {
						$save_array[substr($key, 7)][$k] = esc_html($val);
					}
				}
			} else {
				$save_array[substr($key, 7)] = esc_html($value);
			}
		}
	}
	if (!empty($save_array)) {
		update_user_meta( $user_id, 'cws_mb_user', $save_array );

	}
}

/*------------------------------------
-------------- PORTFOLIO -------------
------------------------------------*/
$theme = wp_get_theme();
if ($theme->get( 'Template' )) {
	if ( ! defined( 'THEME_SLUG' ) ) {
  		define('THEME_SLUG', $theme->get( 'Template' ));
  	}
} else {
	if ( ! defined( 'THEME_SLUG' ) ) {
  		define('THEME_SLUG', $theme->get( 'TextDomain' ));
  	}
}

add_action( "init", "register_cws_portfolio_cat", 1 );
add_action( "init", "register_cws_portfolio", 2 );

function register_cws_portfolio_cat(){
	$rewrite_slug = cws_get_slug('portfolio');

	register_taxonomy( 'cws_portfolio_cat', 'cws_portfolio', array(
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $rewrite_slug . '_cat' ),
        'show_in_rest' => true
		));
}

function register_cws_portfolio (){
	$rewrite_slug = cws_get_slug('portfolio');

	$labels = array(
		'name' => esc_html__( 'Portfolio', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Portfolio Item', 'cws-essentials' ),
		'menu_name' => esc_html__( 'Portfolio', 'cws-essentials' ),
		'add_new' => esc_html__( 'Add New', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add New Portfolio Item', 'cws-essentials' ),
		'edit_item' => esc_html__('Edit Portfolio Item', 'cws-essentials' ),
		'new_item' => esc_html__( 'New Portfolio Item', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Portfolio Item', 'cws-essentials' ),
		'search_items' => esc_html__( 'Search Portfolio Item', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Portfolio Items found', 'cws-essentials' ),
		'not_found_in_trash' => esc_html__( 'No Portfolio Items found in Trash', 'cws-essentials' ),
		'parent_item_colon' => '',
		);

	register_post_type( 'cws_portfolio', array(
		'label' => esc_html__( 'Portfolio items', 'cws-essentials' ),
		'labels' => $labels,
		'public' => true,
		'rewrite' => array( 'slug' => $rewrite_slug ),
		'capability_type' => 'post',
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'page-attributes',
			'thumbnail'
			),
		'menu_position' => 23,
		'menu_icon' => 'dashicons-format-gallery',
		'taxonomies' => array( 'cws_portfolio_cat' ),
		'has_archive' => true,
		'show_in_rest' => true
	));
}

//Add thumbnail image to portfolio posts
function add_cws_portfolio_thumb_name ($columns) {
	$columns = array_slice($columns, 0, 1, true) +
				array('cws_portfolio_thumbnail' => esc_html__('Thumbnails', 'cws-essentials')) +
				array_slice($columns, 1, NULL, true);
	return $columns;
}
add_filter('manage_cws_portfolio_posts_columns', 'add_cws_portfolio_thumb_name');

function add_cws_portfolio_thumb ($column, $id) {
	if ('cws_portfolio_thumbnail' === $column) {
		echo the_post_thumbnail('thumbnail');
	}
}
add_action('manage_cws_portfolio_posts_custom_column', 'add_cws_portfolio_thumb', 5, 2);
//Add thumbnail image to portfolio posts

/*------------------------------------
---------------- STAFF ---------------
------------------------------------*/

add_action( "init", "register_cws_staff_department", 3 );
add_action( "init", "register_cws_staff_position", 4 );
add_action( "init", "register_cws_staff", 5 );

function register_cws_staff (){
	$rewrite_slug = cws_get_slug('staff');

	$labels = array(
		'name' => esc_html__( 'Staff', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Staff Item', 'cws-essentials' ),
		'menu_name' => esc_html__( 'Our team', 'cws-essentials' ),
		'all_items' => esc_html__( 'All', 'cws-essentials' ),
		'add_new' => esc_html__( 'Add New', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add New Staff Item', 'cws-essentials' ),
		'edit_item' => esc_html__('Edit Staff Item\'s Info', 'cws-essentials' ),
		'new_item' => esc_html__( 'New Staff Item', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Staff Item\'s Info', 'cws-essentials' ),
		'search_items' => esc_html__( 'Find Staff Item', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Staff Items Found', 'cws-essentials' ),
		'not_found_in_trash' => esc_html__( 'No Staff Items Found in Trash', 'cws-essentials' ),
		'parent_item_colon' => '',
		);

	register_post_type( 'cws_staff', array(
		'label' => esc_html__( 'Staff Items', 'cws-essentials' ),
		'labels' => $labels,
		'public' => true,
		'rewrite' => array( 'slug' => $rewrite_slug ),
		'capability_type' => 'post',
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'page-attributes',
			'thumbnail'
			),
		'menu_position' => 24,
		'menu_icon' => 'dashicons-groups',
		'taxonomies' => array( 'cws_staff_member_position' ),
		'has_archive' => true,
		'show_in_rest' => true
	));
}

function register_cws_staff_department(){
	$rewrite_slug = cws_get_slug('staff');

	$labels = array(
		'name' => esc_html__( 'Departments', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Staff Department', 'cws-essentials' ),
		'all_items' => esc_html__( 'All Staff Departments', 'cws-essentials' ),
		'edit_item' => esc_html__( 'Edit Staff Department', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Staff Department', 'cws-essentials' ),
		'update_item' => esc_html__( 'Update Staff Department', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add Staff Department', 'cws-essentials' ),
		'new_item_name' => esc_html__( 'New Staff Department', 'cws-essentials' ),
		'parent_item' => esc_html__( 'Parent Staff Department', 'cws-essentials' ),
		'parent_item_colon' => esc_html__( 'Parent Staff Department:', 'cws-essentials' ),
		'search_items' => esc_html__( 'Search Staff Departments', 'cws-essentials' ),
		'popular_items' => esc_html__( 'Popular Staff Departments', 'cws-essentials' ),
		'separate_items_width_commas' => esc_html__( 'Separate with commas', 'cws-essentials' ),
		'add_or_remove_items' => esc_html__( 'Add or Remove Staff Departments', 'cws-essentials' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used Staff Departments', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Staff Departments Found', 'cws-essentials' )
	);
	register_taxonomy( 'cws_staff_member_department', 'cws_staff', array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $rewrite_slug . '_cat' ),
        'show_in_rest' => true
	));
}

function register_cws_staff_position(){
	$rewrite_slug = cws_get_slug('staff');

	$labels = array(
		'name' => esc_html__( 'Positions', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Staff Position', 'cws-essentials' ),
		'all_items' => esc_html__( 'All Staff Positions', 'cws-essentials' ),
		'edit_item' => esc_html__( 'Edit Staff Position', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Staff Position', 'cws-essentials' ),
		'update_item' => esc_html__( 'Update Staff Position', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add Staff Position', 'cws-essentials' ),
		'new_item_name' => esc_html__( 'New Staff Position', 'cws-essentials' ),
		'search_items' => esc_html__( 'Search Staff Positions', 'cws-essentials' ),
		'popular_items' => esc_html__( 'Popular Staff Positions', 'cws-essentials' ),
		'separate_items_width_commas' => esc_html__( 'Separate with commas', 'cws-essentials' ),
		'add_or_remove_items' => esc_html__( 'Add or Remove Staff Positions', 'cws-essentials' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used Staff Positions', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Staff Member positions found', 'cws-essentials' )
	);
	register_taxonomy( 'cws_staff_member_position', 'cws_staff', array(
		'labels' => $labels,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $rewrite_slug . '_tag' ),
		'show_tagcloud' => false,
        'show_in_rest' => true
	));
}
// =====================================================================================================================================================

/* Testimonials */
// Uncomment this line to activate Testimonials
// add_action( "init", "register_cws_testimonial_department", 6 );
// add_action( "init", "register_cws_testimonial_position", 7 );
// add_action( "init", "register_cws_testimonials", 8 );

//Categories
function register_cws_testimonial_department(){
	$rewrite_slug = cws_get_slug('testimonials');
	
	$labels = array(
		'name' => esc_html__( 'Departments', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Department', 'cws-essentials' ),
		'all_items' => esc_html__( 'All Departments', 'cws-essentials' ),
		'edit_item' => esc_html__( 'Edit Department', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Department', 'cws-essentials' ),
		'update_item' => esc_html__( 'Update Department', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add Department', 'cws-essentials' ),
		'new_item_name' => esc_html__( 'New Department', 'cws-essentials' ),
		'parent_item' => esc_html__( 'Parent Department', 'cws-essentials' ),
		'parent_item_colon' => esc_html__( 'Parent Department:', 'cws-essentials' ),
		'search_items' => esc_html__( 'Search Departments', 'cws-essentials' ),
		'popular_items' => esc_html__( 'Popular Departments', 'cws-essentials' ),
		'separate_items_width_commas' => esc_html__( 'Separate with commas', 'cws-essentials' ),
		'add_or_remove_items' => esc_html__( 'Add or Remove Departments', 'cws-essentials' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used Departments', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Departments found', 'cws-essentials' )
	);

	register_taxonomy( 'cws_testimonial_department', 'cws_testimonial', array(
		'labels' => $labels,
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $rewrite_slug . '_cat' )
	));
}

//Tags
function register_cws_testimonial_position(){
	$rewrite_slug = cws_get_slug('testimonials');

	$labels = array(
		'name' => esc_html__( 'Positions', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Position', 'cws-essentials' ),
		'all_items' => esc_html__( 'All Positions', 'cws-essentials' ),
		'edit_item' => esc_html__( 'Edit Position', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Position', 'cws-essentials' ),
		'update_item' => esc_html__( 'Update Position', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add Position', 'cws-essentials' ),
		'new_item_name' => esc_html__( 'New Position', 'cws-essentials' ),
		'search_items' => esc_html__( 'Search Positions', 'cws-essentials' ),
		'popular_items' => esc_html__( 'Popular Positions', 'cws-essentials' ),
		'separate_items_width_commas' => esc_html__( 'Separate with commas', 'cws-essentials' ),
		'add_or_remove_items' => esc_html__( 'Add or Remove Positions', 'cws-essentials' ),
		'choose_from_most_used' => esc_html__( 'Choose from the most used  Positions', 'cws-essentials' ),
		'not_found' => esc_html__( 'No  Positions found', 'cws-essentials' )
	);

	register_taxonomy( 'cws_testimonial_position', 'cws_testimonial', array(
		'labels' => $labels,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $rewrite_slug . '_tag' ),
		'show_tagcloud' => false
	));
}


function register_cws_testimonials (){
	$rewrite_slug = cws_get_slug('testimonials');

	$labels = array(
		'name' => esc_html__( 'Testimonials', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Testimonial', 'cws-essentials' ),
		'menu_name' => esc_html__( 'Testimonials', 'cws-essentials' ),
		'all_items' => esc_html__( 'All', 'cws-essentials' ),
		'add_new' => esc_html__( 'Add New', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add New', 'cws-essentials' ),
		'edit_item' => esc_html__('Edit Testimonial', 'cws-essentials' ),
		'new_item' => esc_html__( 'New Testimonial', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Testimonial', 'cws-essentials' ),
		'search_items' => esc_html__( 'Search Testimonials', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Testimonials Items Found', 'cws-essentials' ),
		'not_found_in_trash' => esc_html__( 'No Testimonials Items Found in Trash', 'cws-essentials' ),
		'parent_item_colon' => '',
		);

	register_post_type( 'cws_testimonial', array(
		'label' => esc_html__( 'Testimonials', 'cws-essentials' ),
		'labels' => $labels,
		'public' => true,
		'rewrite' => array( 'slug' => $rewrite_slug ),
		'capability_type' => 'post',
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'page-attributes', // Sortable column "Order"
			'thumbnail'
			),
		'menu_position' => 22,
		'menu_icon' => 'dashicons-format-quote',
		'taxonomies' => array( 'cws_testimonial_department' ),
		'has_archive' => true
	));
}


function add_order_column( $columns ) {
  $columns['menu_order'] = "Order";
  return $columns;
}
add_action('manage_edit-cws_staff_columns', 'add_order_column');
add_action('manage_edit-cws_portfolio_columns', 'add_order_column');
add_action('manage_edit-cws_testimonial_columns', 'add_order_column');

/**
* show custom order column values
*/
function show_order_column($name){
  global $post;
  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo $order;
      break;
   default:
      break;
   }
}

//Add thumbnail image to staff posts
function add_cws_staff_thumb_name ($columns) {
	$columns = array_slice($columns, 0, 1, true) +
				array('cws_staff_thumbnail' => esc_html__('Thumbnails', 'cws-essentials')) +
				array_slice($columns, 1, NULL, true);
	return $columns;
}
add_filter('manage_cws_staff_posts_columns', 'add_cws_staff_thumb_name');

function add_cws_staff_thumb ($column, $id) {
	if ('cws_staff_thumbnail' === $column) {
		echo the_post_thumbnail('thumbnail');
	}
}
add_action('manage_cws_staff_posts_custom_column', 'add_cws_staff_thumb', 5, 2);
//Add thumbnail image to staff posts

add_action('manage_cws_staff_posts_custom_column','show_order_column');
add_action('manage_cws_portfolio_posts_custom_column','show_order_column');
add_action('manage_cws_testimonial_posts_custom_column','show_order_column');

/**
* make column sortable
*/
function order_column_register_sortable( $columns ){
	$new_columns = array(
		"menu_order" 	=> "menu_order",
		"date"			=> "date",
		"title"			=> "title"
	);
	return $new_columns;
}
add_filter('manage_edit-cws_staff_sortable_columns','order_column_register_sortable');
add_filter('manage_edit-cws_portfolio_sortable_columns','order_column_register_sortable');
add_filter('manage_edit-cws_testimonial_sortable_columns','order_column_register_sortable');

// Uncomment this line to activate Classes
// add_action( "init", "register_cws_classes", 9 );
// add_action( "init", "register_cws_classes_cat", 10 );

function register_cws_classes_cat(){
	$rewrite_slug = cws_get_slug('classes');

	register_taxonomy( 'cws_classes_cat', 'cws_classes', array(
		'hierarchical' => true,
		'show_admin_column' => true,
		'rewrite' => array( 'slug' => $rewrite_slug . '_cat' )
		));
}

function register_cws_classes (){
	$rewrite_slug = cws_get_slug('classes');

	$labels = array(
		'name' => esc_html__( 'Classes', 'cws-essentials' ),
		'singular_name' => esc_html__( 'Classes', 'cws-essentials' ),
		'menu_name' => esc_html__( 'Our Classes', 'cws-essentials' ),
		'all_items' => esc_html__( 'All', 'cws-essentials' ),
		'add_new' => esc_html__( 'Add New', 'cws-essentials' ),
		'add_new_item' => esc_html__( 'Add New Class', 'cws-essentials' ),
		'edit_item' => esc_html__('Edit Class', 'cws-essentials' ),
		'new_item' => esc_html__( 'New Class', 'cws-essentials' ),
		'view_item' => esc_html__( 'View Class', 'cws-essentials' ),
		'search_items' => esc_html__( 'Find Classes', 'cws-essentials' ),
		'not_found' => esc_html__( 'No Classess found', 'cws-essentials' ),
		'not_found_in_trash' => esc_html__( 'No Classess found in Trash', 'cws-essentials' ),
		'parent_item_colon' => '',
		);

	register_post_type( 'cws_classes', array(
		'label' => esc_html__( 'Classes', 'cws-essentials' ),
		'labels' => $labels,
		'public' => true,
		'rewrite' => array( 'slug' => $rewrite_slug ),
		'capability_type' => 'post',
		'supports' => array(
			'title',
			'editor',
			'excerpt',
			'page-attributes',
			'thumbnail'
			),
		'menu_position' => 24,
		'menu_icon' => 'dashicons-clipboard',
		'taxonomies' => array( 'cws_classes_member_position' ),
		'has_archive' => true
	));
}

// =====================================================================================================================================================



function add_order_column_classes( $columns ) {
  $columns['menu_order'] = "Order";
  return $columns;
}
add_action('manage_edit-cws_classes_columns', 'add_order_column_classes');

/**
* show custom order column values
*/
function show_order_column_classes($name){
  global $post;
  switch ($name) {
    case 'menu_order':
      $order = $post->menu_order;
      echo $order;
      break;
   default:
      break;
   }
}
add_action('manage_cws_classes_posts_custom_column','show_order_column_classes');

/**
* make column sortable
*/
function order_column_register_sortable_classes( $columns ){
	$new_columns = array(
		"menu_order" 	=> "menu_order",
		"date"			=> "date",
		"title"			=> "title"
	);
	return $new_columns;
}
add_filter('manage_edit-cws_classes_sortable_columns','order_column_register_sortable_classes');

if(!function_exists('cws_Hex2RGBA')){
	function cws_Hex2RGBA( $color, $opacity ) {
		$output = '';
		if (!empty($color)){
			//Sanitize $color if "#" is provided 
			if (substr($color, 0, 4) === 'rgba') {
				if(!empty($opacity)){
					$rgba_o = str_replace("rgba(", "", $color);
					$rgba_o = explode(",", $rgba_o);
					return "rgba(".$rgba_o[0].",".$rgba_o[1].",".$rgba_o[2].", ".$opacity.")";
				}
				return $color;
			}

		    if ($color[0] == '#' ) {
		    	$color = substr( $color, 1 );
		    }
		    //Check if color has 6 or 3 characters and get values
		    if (strlen($color) == 6) {
		            $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		    } elseif ( strlen( $color ) == 3 ) {
		            $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		    } else {
		            return $default;
		    }

		    //Convert hexadec to rgb
		    $rgb =  array_map('hexdec', $hex);

		    //Check if opacity is set(rgba or rgb)
		    if($opacity){
		    	if(abs($opacity) > 1)
		    		$opacity = 1.0;
		    	$output = 'rgba('.implode(",",$rgb).','.$opacity.')';
		    } else {
		    	$output = 'rgb('.implode(",",$rgb).')';
		    }

		    //Return rgb(a) color string
		    return $output;
		}

	}	
}


/****************** POSTS GRID AJAX *******************/

function cws_vc_shortcode_posts_grid_dynamic_pagination (){
	extract( wp_parse_args( $_POST['data'], array(
		'section_id'				=> '',
		'post_type' 				=> '',
		'cws_portfolio_data_to_show'=> '',
		'cws_staff_data_to_hide'	=> array(),
		'cws_testimonial_data_to_hide'	=> array(),
		'change_title'              => '',
		'title_btn'					=> '',
		'massonry'					=> '',
		'meta_position'				=> 'above_title',
		'layout'					=> '1',
		'sb_layout'					=> '',
		'total_items_count'			=> get_option( 'posts_per_page' ),
		'items_pp'					=> get_option( 'posts_per_page' ),
		'page'						=> '1',
		'tax'						=> '',
		'terms'						=> array(),
		'filter'					=> 'false',
		'current_filter_val'		=> '',
		'req_page_url'				=> '',
		'crop_featured'				=> '',
		'crop_images'				=> '',
		'pagination_grid'			=> '',
		'full_width'				=> '',
		'addl_query_args'			=> array(),
		'post_hide_meta_override'	=> '',
		'uniq_cat'					=> '',
		'post_hide_meta'			=> '',						
		'info_align'				=> '',
		'aligning'					=> '',
		'display_style'				=> '',
		'portfolio_style'			=> '',
		'info_pos'					=> '',
		'masonry'					=> '',
		'anim_style'				=> '',
		'item_shadow'				=> '',
		'en_hover_color'			=> '',
		'en_cat_color'				=> '',
		'hover_color'				=> '',
		'title_color'				=> '',
		'cat_color'					=> '',
		'appear_style'				=> '',
		'more_btn_text'				=> '',
		'link_show'					=> '',
		'isotope_line_count'		=> '',
		'isotope_col_count'			=> '',
		'chars_count'				=> '',
		'add_divider'				=> '',
		'filter_vals'				=> '',
		'hover_bg_color'			=> '',
		'proc_atts'					=> '',
		'bg_hover_color'			=> '',
        'orderby'                   => '',
        'order'                     => '',
	)));

	$req_page = $page;
	if ( !empty( $req_page_url ) ){
		$match = preg_match( "#paged?(=|/)(\d+)#", $req_page_url, $matches );
		$req_page = $match ? $matches[2] : '1';								// if page parameter absent show first page
	};

	$not_in = ( 1 == $req_page ) ? array() : get_option( 'sticky_posts' );
	$query_args = array('post_type'			=> array( $post_type ),
						'post_status'		=> 'publish',
						'post__not_in'		=> $not_in
						);
	$query_args['posts_per_page']		= $items_pp;
	$query_args['paged']				= $paged = $req_page;
	$old_terms = $terms;
	if ( $filter == 'true' && $current_filter_val != '_all_' && !empty( $current_filter_val ) ){
		$terms = array( $current_filter_val );

		if($post_type == 'cws_portfolio' && $display_style == 'filter' && !empty($old_terms)){
			$terms = $old_terms;
		}	
	}
	if ( !empty( $terms ) ){
		$query_args['tax_query'] = array(
			array(
				'taxonomy'		=> $tax,
				'field'			=> 'slug',
				'terms'			=> $terms
			)
		);
	}

	//if ( in_array( $post_type, array( "cws_portfolio", "cws_staff", "cws_testimonial", "tribe_events", "cws_classes") ) ){
		$query_args['orderby'] 	= $orderby;
		$query_args['order']	= $order;
	//}
	$query_args = array_merge( $query_args, $addl_query_args );
	$q = new WP_Query( $query_args );
	$found_posts = $q->found_posts;
	$max_paged = $found_posts > $total_items_count ? ceil( $total_items_count / $items_pp ) : ceil( $found_posts / $items_pp );
	$GLOBALS['cws_vc_shortcode_posts_grid_atts'] = array(
		'post_type'					=> $post_type,
		'meta_position'				=> $meta_position,
		'layout'					=> $layout,
		'massonry'					=> $massonry,
		'sb_layout'					=> $sb_layout,
		'post_hide_meta'			=> $post_hide_meta,
		'uniq_cat'					=> $uniq_cat,
		'cws_portfolio_data_to_show'=> $cws_portfolio_data_to_show,
		'cws_staff_data_to_hide'	=> $cws_staff_data_to_hide,
		'cws_testimonial_data_to_hide'	=> $cws_testimonial_data_to_hide,
		'change_title'              => $change_title,
		'title_btn'					=> $title_btn,
		'crop_featured'				=> $crop_featured,
		'crop_images'				=> $crop_images,
		'total_items_count'			=> $total_items_count,
		'full_width'				=> $full_width,
		'pagination_grid'			=> $pagination_grid,
		'post_hide_meta_override'	=> $post_hide_meta_override,
		'info_align'				=> $info_align,
		'aligning'					=> $aligning,
		'display_style'				=> $display_style,
		'portfolio_style'			=> $portfolio_style,
		'info_pos'					=> $info_pos,
		'masonry'					=> $masonry,
		'anim_style'				=> $anim_style,
		'item_shadow'				=> $item_shadow,
		'en_hover_color'			=> $en_hover_color,
		'en_cat_color'				=> $en_cat_color,
		'hover_color'				=> $hover_color,
		'title_color'				=> $title_color,
		'cat_color'					=> $cat_color,
		'appear_style'				=> $appear_style,
		'more_btn_text'				=> $more_btn_text,
		'link_show'					=> $link_show,
		'isotope_line_count'		=> $isotope_line_count,
		'isotope_col_count'			=> $isotope_col_count,
		'chars_count'				=> $chars_count,
		'add_divider'				=> $add_divider,
		'tax'						=> $tax,
		'filter_vals'				=> $filter_vals,
		'hover_bg_color'			=> $hover_bg_color,
		'proc_atts'					=> $proc_atts,
		'bg_hover_color'			=> $bg_hover_color,
        'orderby'                   => $orderby,
        'order'                     => $order,

	);

	if ($post_type == 'post'){
		cws_blog_posts($q);
	} elseif ( function_exists( "cws_vc_shortcode_{$post_type}_posts_grid_posts" ) ){
		call_user_func_array( "cws_vc_shortcode_{$post_type}_posts_grid_posts", array( $q ) );
	}
	
	if ( $pagination_grid == 'load_more' ){
		echo cws_load_more ();
	}
	elseif($pagination_grid == 'standard_with_ajax'){
		echo cws_pagination($paged, $max_paged, true);
	}
	else{
		echo cws_pagination($paged, $max_paged, false);
	}
	unset ( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] );
	echo "<input type='hidden' id='{$section_id}_dynamic_pagination_page_number' name='{$section_id}_dynamic_pagination_page_number' class='cws_vc_shortcode_posts_grid_dynamic_pagination_page_number' value='$req_page' />";
	wp_die();
}
add_action( 'wp_ajax_cws_vc_shortcode_posts_grid_dynamic_pagination', 'cws_vc_shortcode_posts_grid_dynamic_pagination' );
add_action( 'wp_ajax_nopriv_cws_vc_shortcode_posts_grid_dynamic_pagination', 'cws_vc_shortcode_posts_grid_dynamic_pagination' );

function cws_vc_shortcode_posts_grid_dynamic_filter (){
	extract( wp_parse_args( $_POST['data'], array(
		'section_id'				=> '',
		'post_type' 				=> '',
		'post_hide_meta'			=> array(),
		'uniq_cat'					=> '',
		'massonry'					=> '',
		'cws_portfolio_data_to_show'=> '',
		'cws_classes_data_to_show'	=> '',
		'cws_staff_data_to_hide'	=> array(),
		'cws_testimonial_data_to_hide'	=> array(),
		'layout'					=> '1',
		'sb_layout'					=> '',
		'total_items_count'			=> get_option( 'posts_per_page' ),
		'items_pp'					=> get_option( 'posts_per_page' ),
		'page'						=> '1',
		'tax'						=> '',
		'terms'						=> array(),
		'filter'					=> 'false',
		'current_filter_val'		=> '',
		'crop_images' 				=> '',
		'pagination_grid'			=> '',
		'full_width'				=> '',
		'customize_colors'			=> '',
		'custom_color'				=> '',
		'font_color'				=> '',
		'bg_color'					=> '',
		'addl_query_args'			=> array(),
		'info_align'				=> '',
		'aligning'					=> '',
		'display_style'				=> '',
		'portfolio_style'			=> '',
		'info_pos'					=> '',
		'masonry'					=> '',
		'anim_style'				=> '',
		'item_shadow'				=> '',
		'en_hover_color'			=> '',
		'en_cat_color'				=> '',
		'hover_color'				=> '',
		'title_color'				=> '',
		'cat_color'					=> '',
		'appear_style'				=> '',
		'link_show'					=> '',
		'isotope_line_count'		=> '',
		'isotope_col_count'			=> '',
		'chars_count'				=> '',
		'add_divider'				=> '',
		'filter_vals'				=> '',
		'hover_bg_color'			=> '',
		'proc_atts'					=> '',
		'bg_hover_color'			=> '',
		'custom_title_color'		=> '',
		'cws_gradient_color_from'   => '',
		'cws_gradient_color_to'     => '',
        'orderby'                   => '',
        'order'                     => '',


	)));
	$not_in = ( 1 == $req_page ) ? array() : get_option( 'sticky_posts' );
	$query_args = array('post_type'			=> array( $post_type ),
						'post_status'		=> 'publish',
						'post__not_in'		=> $not_in
						);
	$query_args['posts_per_page']		= $items_pp;
	$query_args['paged']		= $page;
	if ( $current_filter_val != '_all_' && !empty( $current_filter_val ) ){
		$terms = array( $current_filter_val );
	}
	if ( !empty( $terms ) ){
		$query_args['tax_query'] = array(
			array(
				'taxonomy'		=> $tax,
				'field'			=> 'slug',
				'terms'			=> $terms
			)
		);
	}

//	if ( in_array( $post_type, array( "cws_portfolio", "cws_staff", "cws_testimonial", "tribe_events", "cws_classes" ) ) ){
		$query_args['orderby'] 	= $orderby;
		$query_args['order']	= $order;
//	}
	$query_args = array_merge( $query_args, $addl_query_args );
	$q = new WP_Query( $query_args );
	$found_posts = $q->found_posts;
	$max_paged = $found_posts > $total_items_count ? ceil( $total_items_count / $items_pp ) : ceil( $found_posts / $items_pp );
	$is_pagination = $max_paged > 1;
	$GLOBALS['cws_vc_shortcode_posts_grid_atts'] = array(
		'post_type'						=> $post_type,
		'layout'						=> $layout,
		'customize_colors'				=> $customize_colors,
		'custom_color'					=> $custom_color,
		'font_color'					=> $font_color,
		'bg_color'						=> $bg_color,
		'sb_layout'						=> $sb_layout,
		'massonry'						=> $massonry,
		'post_hide_meta'				=> $post_hide_meta,
		'uniq_cat'						=> $uniq_cat,
		'cws_portfolio_data_to_show'	=> $cws_portfolio_data_to_show,
		'cws_classes_data_to_show'		=> $cws_classes_data_to_show,
		'cws_staff_data_to_hide'		=> $cws_staff_data_to_hide,
		'cws_testimonial_data_to_hide'	=> $cws_testimonial_data_to_hide,
		'crop_images'					=> $crop_images,
		'total_items_count'				=> $total_items_count,
		'pagination_grid'				=> $pagination_grid,
		'full_width'					=> $full_width,
		'info_align'					=> $info_align,
		'aligning'						=> $aligning,
		'display_style'					=> $display_style,
		'portfolio_style'				=> $portfolio_style,
		'info_pos'						=> $info_pos,
		'masonry'						=> $masonry,
		'anim_style'					=> $anim_style,
		'item_shadow'					=> $item_shadow,
		'en_hover_color'				=> $en_hover_color,
		'en_cat_color'					=> $en_cat_color,
		'hover_color'					=> $hover_color,
		'title_color'					=> $title_color,
		'cat_color'						=> $cat_color,
		'appear_style'					=> $appear_style,
		'link_show'						=> $link_show,
		'isotope_line_count'			=> $isotope_line_count,
		'isotope_col_count'				=> $isotope_col_count,
		'chars_count'					=> $chars_count,
		'add_divider'					=> $add_divider,
		'filter_vals'					=> $filter_vals,
		'tax'							=> $tax,
		'hover_bg_color'				=> $hover_bg_color,
		'proc_atts'						=> $proc_atts,
		'bg_hover_color'				=> $bg_hover_color,
		'custom_title_color'			=> $custom_title_color,
		'cws_gradient_color_from'   	=> $cws_gradient_color_from,
		'cws_gradient_color_to'     	=> $cws_gradient_color_to,
        'orderby'                       => $orderby,
        'order'                         => $order,
	);

	if ($post_type == 'post'){
		cws_blog_posts($q);
	} elseif ( function_exists( "cws_vc_shortcode_{$post_type}_posts_grid_posts" ) ){
		call_user_func_array( "cws_vc_shortcode_{$post_type}_posts_grid_posts", array( $q ) );
	}	
	
	if ( $is_pagination ){
		if ( $pagination_grid == 'load_more' ){
			echo cws_load_more ();
		}
		else{
			echo cws_pagination ( $page, $max_paged,true );
		}
	}
	unset ( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] );
	wp_die();
}
add_action( 'wp_ajax_cws_vc_shortcode_posts_grid_dynamic_filter', 'cws_vc_shortcode_posts_grid_dynamic_filter' );
add_action( 'wp_ajax_nopriv_cws_vc_shortcode_posts_grid_dynamic_filter', 'cws_vc_shortcode_posts_grid_dynamic_filter' );

/****************** \POSTS GRID AJAX ******************/

function cws_portfolio_single(){
	$data = isset( $_POST['data'] ) ? $_POST['data'] : array();
	extract( shortcode_atts( array(
			'initial_id' => '',
			'requested_id' => ''
		), $data));
	if ( empty( $initial_id ) || empty( $requested_id ) ) die();

	$pid = $requested_id;
	$post_meta = get_post_meta( $pid, 'cws_mb_post' );
	$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
	$full_width = isset( $post_meta['full_width'] ) ? $post_meta['full_width'] : "";	
	
	ob_start();
		cws_vc_shortcode_cws_portfolio_single_post_post_media ($requested_id);
	$media = ob_get_clean();
	if ( !empty($full_width) ) {
		echo "<div class='cws_ajax_response_media_full'>";
			echo "<div class='cws_ajax_media'>";
				echo $media;
			echo "</div>";
		echo "</div>";
	}

	echo "<div class='cws_ajax_response'>";
		$pid = $requested_id;
		echo "<article id='cws-portfolio-post-{$pid}' class='cws-portfolio-post post-single item clearfix'>";
		if ( empty($full_width) ) {
			ob_start();
			cws_vc_shortcode_cws_portfolio_single_post_post_media ($requested_id);
			$media = ob_get_clean();
			$floated_media = isset( $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] ) ? $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] : false;
			unset( $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] );
			if ( $floated_media ){
				echo "<div class='floated_media cws-portfolio-floated-media single_post_floated_media'>";
				echo "<div class='floated_media_wrapper cws-portfolio-floated-media-wrapper single_post_floated_media_wrapper'>";
				echo $media;
				echo "</div>";
				echo "</div>";						
			}
			else{
				echo $media;
			}
		}
		ob_start();
		cws_vc_shortcode_cws_portfolio_single_post_title ( $pid );
		cws_vc_shortcode_cws_portfolio_single_post_content ($pid);
		$content_terms = ob_get_clean();
		if ( !empty( $content_terms ) ){
			if ( $floated_media ){
				echo "<div class='clearfix'>";
				echo $content_terms;
				echo "</div>";
			}
			else{
				echo $content_terms;
			}
		}
		echo "</article>";
	echo "</div>";
	die();
}
add_action( "wp_ajax_cws_portfolio_single", "cws_portfolio_single" );
add_action( "wp_ajax_nopriv_cws_portfolio_single", "cws_portfolio_single" );

function cws_classes_single(){
	$data = isset( $_POST['data'] ) ? $_POST['data'] : array();
	extract( shortcode_atts( array(
			'initial_id' => '',
			'requested_id' => ''
		), $data));
	if ( empty( $initial_id ) || empty( $requested_id ) ) die();

	echo "<div class='cws_ajax_response'>";
		$pid = $requested_id;
		$post_meta = get_post_meta( $pid, 'cws_mb_post' );
		$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
		$price = isset( $post_meta['price'] ) ? $post_meta['price'] : '';
		$date_events = isset( $post_meta['date_events'] ) ? $post_meta['date_events'] : '';
		$destinations = isset( $post_meta['destinations'] ) ? $post_meta['destinations'] : '';
		$time_events = isset( $post_meta['time_events'] ) ? $post_meta['time_events'] : '';
		if(!empty($price)){
			preg_match('/(.*[^0-9])(\d+)([\.,]\d+)/', $price, $matches);
			list(, $currency, $price, $pfraction) = $matches;
		}
		echo "<article id='cws_classes_post_{$pid}' class='cws_classes_post post-single item clearfix'>";
		ob_start();
		cws_vc_shortcode_cws_classes_single_post_post_media ($requested_id);
		$media = ob_get_clean();
		$floated_media = isset( $GLOBALS['cws_vc_shortcode_cws_classes_single_post_floated_media'] ) ? $GLOBALS['cws_vc_shortcode_cws_classes_single_post_floated_media'] : false;
		unset( $GLOBALS['cws_vc_shortcode_cws_classes_single_post_floated_media'] );
		if ( $floated_media ){
			echo "<div class='floated_media cws_classes_floated_media single_post_floated_media'>";
			echo "<div class='floated_media_wrapper cws_classes_floated_media_wrapper single_post_floated_media_wrapper'>";
			echo $media;
			echo "</div>";
			echo "</div>";						
		}
		else{
			echo $media;
		}

		ob_start();
		echo "<div class='wrap_title'>";
		echo "<div class='title_single_classes'>";
		cws_vc_shortcode_title($pid);
		echo "</div>";
		if(!empty($price)){
			echo "<div class='price_single_classes'>";
			echo "<span class='currency_price'>";
				echo esc_html($currency);
			echo "</span>";
			echo "<span class='price'>";
				echo esc_html($price);
			echo "</span>";
			echo "<span class='pfraction'>";
				echo esc_html($pfraction);
			echo "</span>";
			echo "</div>";						
		}
		echo "</div>";
		if(!empty($date_events)){
			echo "<div class='date_ev_single_classes'>";
			echo esc_html($date_events);
			echo "</div>";								
		}
		if(!empty($time_events) || !empty($destinations)){
			echo "<div class='wrap_desc_info'>";
			if(!empty($time_events)){
				echo "<div class='time_ev_single_classes'>";
					echo esc_html($time_events);
				echo "</div>";								
			}
			if(!empty($destinations)){
				echo "<div class='destinations_single_classes'>";
					echo esc_html($destinations);
				echo "</div>";	
			}
			echo "</div>";
		}

		cws_vc_shortcode_cws_classes_single_post_content ($pid);
		cws_vc_shortcode_cws_classes_teacher ($pid);
		$content_terms = ob_get_clean();
		if ( !empty( $content_terms ) ){
			if ( $floated_media ){
				echo "<div class='clearfix'>";
				echo $content_terms;
				echo "</div>";
			}
			else{
				echo $content_terms;
			}
		}
		echo "</article>";
	echo "</div>";
	die();
}
add_action( "wp_ajax_cws_classes_single", "cws_classes_single" );
add_action( "wp_ajax_nopriv_cws_classes_single", "cws_classes_single" );

function cws_vc_shortcode_single_portfolio_ajax_load () {
	$query_args = array('post_type'			=> 'cws_portfolio',
						'p' 				=> $_POST['post_id']
						);
	$post_query = new WP_Query( $query_args );
	while( $post_query->have_posts() ) : $post_query->the_post();

		$sb = cws_vc_shortcode_render_sidebars( get_queried_object_id() );
		$fixed_header = cws_get_meta_option( 'fixed_header' );
		$class = $sb['layout_class'].' '. $sb['sb_class'];
		$sb['sb_class'] = apply_filters('cws_print_single_class', $class);

		$p_id = get_queried_object_id ();
		$post_meta = get_post_meta( get_the_ID(), 'cws_mb_post' );
		$post_meta = isset( $post_meta[0] ) ? $post_meta[0] : array();
		$def_row_fw_atts = array(
						'full_width'				=> false,
					);
		$shot = isset( $GLOBALS['cws_row_atts'] ) ? $GLOBALS['cws_row_atts'] : $def_row_fw_atts;
		extract($shot);
		extract( wp_parse_args( $post_meta, array(
			'show_related' 		=> false,
			'rpo_title'			=> '',
			'rpo_cols'			=> '4',
			'carousel'			=> false,
			'img_size'			=> '1',
			'rpo_items_count'	=> get_option( 'posts_per_page' ),
		)));
		if ($full_width == 'stretch_row_content' || $full_width == 'stretch_row_content_no_spaces') {
			$full_width = true;
		}else{
			$full_width = '';
		} 
		$ajax_width = 1920;
		$show_related = isset( $post_meta['show_related'] ) ? $post_meta['show_related'] : false;
		$rpo_title = isset( $post_meta['rpo_title'] ) ? esc_html( $post_meta['rpo_title'] ) : "";
		$rpo_items_count = isset( $post_meta['rpo_items_count'] ) ? esc_textarea( $post_meta['rpo_items_count'] ) : esc_textarea( get_option( "posts_per_page" ) );
		$rpo_cols = isset( $post_meta['rpo_cols'] ) ? esc_textarea( $post_meta['rpo_cols'] ) : 4;
		$title = get_the_title();
		ob_start();
		cws_vc_shortcode_cws_portfolio_single_post_post_media ();
		$media = ob_get_clean();
		$floated_media = isset( $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] ) ? $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] : false;
		unset( $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] );
		if ( $img_size == 2 ) {
			echo $media;
		}

		echo (isset($sb['content']) ? $sb['content'] : '');
		$GLOBALS['cws_vc_shortcode_single_ajax_atts'] = array(
			'sb_layout'						=> $sb_layout_class,
			'display_style'					=> 'filter',
		);
		$pid = get_the_id();
		echo "<div id='cws-portfolio-post-{$pid}' class='cws-portfolio-post post-single clearfix'>";
			ob_start();
			cws_vc_shortcode_cws_portfolio_single_post_post_media (false,$ajax_width);
			$media = ob_get_clean();
			$floated_media = isset( $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] ) ? $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] : false;
			unset( $GLOBALS['cws_vc_shortcode_cws_portfolio_single_post_floated_media'] );
			if ( $img_size == 1 ) {
				if ( $floated_media ){
					echo "<div class='floated-media cws-portfolio-floated-media single-post-floated-media'>";
						echo "<div class='floated-media-wrapper cws-portfolio-floated-media-wrapper single-post-floated-media-wrapper'>";
							echo $media;
						echo "</div>";
					echo "</div>";						
				}
				else{
					echo $media;
				}
			}
			ob_start();
			cws_vc_shortcode_cws_portfolio_single_post_terms ();
			cws_vc_shortcode_cws_portfolio_single_post_content ();
			$content_terms = ob_get_clean();
			echo "<div class='container'>";
				if ( !empty( $content_terms ) ){
					if ( $floated_media && $img_size == 1 ){
						echo "<div class='clearfix floated_media_content cws-portfolio-single-content'>";
							echo $content_terms;
						echo "</div>";
					}
					else{
						echo "<div class='cws-portfolio-single-content'>";
							echo $content_terms;
						echo "</div>";
					}
				}

				if ( wp_get_referer() )
				{
					$previous = wp_get_referer();
					echo "<div class='back-link-case'><a href='$previous'><i class='flaticon-left-arrow'></i>" .
                        esc_html__('Back to Projects' , 'cws-essentials') . "</a></div>";
				}
			echo "</div>";
			global $cws_theme_funcs;
			cws_page_links();
		echo "</div>";
		wp_reset_postdata();
		unset( $GLOBALS['cws_vc_shortcode_single_post_atts'] );
		echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : '';
		if ( $show_related ){
			$terms = wp_get_post_terms( $p_id, 'cws_portfolio_cat' );
			$term_slugs = array();
			for ( $i=0; $i < count( $terms ); $i++ ){
				$term = $terms[$i];
				$term_slug = $term->slug;
				array_push( $term_slugs, $term_slug );
			}
			$term_slugs = implode( ",", $term_slugs );
			if ( !empty( $term_slugs ) ){
				$rp_args = array(
					'title'							=> $rpo_title,
					'post_type'						=> 'cws_portfolio',
					'total_items_count'				=> $rpo_items_count,
					'display_style'					=> 'carousel',
					'cws_portfolio_layout_override'	=> true,
					'cws_portfolio_layout'			=> $rpo_cols,
					'tax'							=> 'cws_portfolio_cat',
					'terms'							=> $term_slugs,
					'addl_query_args'				=> array(
						'post__not_in'					=> array( $p_id ),
					),
				);
				$related_projects = cws_vc_shortcode_posts_grid( $rp_args );
				if ( !empty( $related_projects ) ){
					echo "<hr />";
					echo $related_projects;
				}
			}
		}
	endwhile;
	exit;
}
add_action ( 'wp_ajax_cws_vc_shortcode_single_portfolio_ajax_load', 'cws_vc_shortcode_single_portfolio_ajax_load' );
add_action ( 'wp_ajax_nopriv_cws_vc_shortcode_single_portfolio_ajax_load', 'cws_vc_shortcode_single_portfolio_ajax_load' );

add_action ( 'wp_ajax_cws_vc_shortcode_page_load', 'cws_vc_shortcode_page_load' );
add_action ( 'wp_ajax_nopriv_cws_vc_shortcode_page_load', 'cws_vc_shortcode_page_load' );

function cws_vc_shortcode_page_load(){
	$data = isset( $_POST['data'] ) ? $_POST['data'] : array();
	echo "<div class='cws_ajax_response'>";

	$sb = cws_vc_shortcode_render_sidebars( get_queried_object_id() );
	$fixed_header = cws_get_meta_option( 'fixed_header' );
	$class = $sb['layout_class'].' '. $sb['sb_class'];
	$sb['sb_class'] = apply_filters('cws_print_single_class', $class);

	echo '<div class="'.(isset($sb['sb_class']) ? $sb['sb_class'] : '').'">';
		echo (isset($sb['content']) ? $sb['content'] : ''); 
		echo '<main'.($fixed_header == '1' ? ' class="header_shadow"' : '').' >';
			echo apply_filters('the_content', get_post_field('post_content', 40));

				$is_blog = cws_get_meta_option( 'is_blog' ) == '1';
				if ( $is_blog ) get_template_part( 'content', 'blog' );
				comments_template();
		
		echo '</main>';
		echo (isset($sb['content']) && !empty($sb['content']) ) ? '</div>' : '';
	echo '</div>';
	echo "</div>";
	die();
}

if(!function_exists('essentials_register_scripts')){
	function essentials_register_scripts (){
		$js_path = plugin_dir_url( __FILE__ ) . 'assets/js/';

		$common_scripts = array(
			'jquery-shortcode-velocity'  => array('velocity.min.js', false),
			'jquery-shortcode-velocity-ui' => array('velocity.ui.min.js', false)
		);

		foreach ($common_scripts as $alias => $value) {

			list($path, $enqueue) = $value;
			if ($path) {
				$path = (0 === strrpos($path, 'http')) ? $path : $js_path . $path;
			}

			if ($enqueue){
				wp_enqueue_script( $alias, $path, array( 'jquery' ), '', true );
			} else {
				wp_register_script( $alias, $path, array( 'jquery' ), '', true );
			}

		}

		wp_localize_script('jquery-ajax-shortcode', 'cws_vc_sh', array(
			'ajax_nonce' => wp_create_nonce('cws_vc_sh_nonce'),
		));

        wp_enqueue_script( 'jquery-ajax-shortcode', plugin_dir_url( __FILE__ ) . 'assets/js/ajax_plugin.js', array( 'cws-scripts' ), '', true );

		wp_register_style ( 'cws_front_css',  plugin_dir_url( __FILE__ ) . '/assets/css/main.css' );   
		wp_enqueue_style ( 'cws_front_css' );  
	} 
}
add_action( 'wp_enqueue_scripts', 'essentials_register_scripts' );

if(!function_exists('cws_vc_shortcode_render_sidebars')){
	function cws_vc_shortcode_render_sidebars($pid) {
		// !!! this must be in superclass
		$out = '';
		$sb = cws_vc_shortcode_get_sidebars( $pid );

		$layout_class = $sb && $sb['layout_class'] != 'none' ? $sb['layout_class'].'_sidebar' : '';
		$sb1_class = $sb && $sb['layout'] == 'right' ? 'sb-right' : 'sb-left';
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
}

if(!function_exists('cws_vc_shortcode_get_option')){
	function cws_vc_shortcode_get_option($name){
		$ret = null;
		if (is_customize_preview()) {
			global $cwsfw_settings;
			if (isset($cwsfw_settings[$name])) {
				$ret = $cwsfw_settings[$name];
				if (is_array($ret)) {
					$theme_options = get_option( THEME_SLUG );
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
		$theme_options = get_option( THEME_SLUG );
		$ret = isset($theme_options[$name]) ? $theme_options[$name] : null;
		$ret = stripslashes_deep( $ret );
		return $ret;
	}
}

if(!function_exists('cws_vc_shortcode_get_post_term_links_str')){
	function cws_vc_shortcode_get_post_term_links_str ( $tax = "", $delim = "" ){
		$pid = get_the_id();
		$terms_arr = wp_get_post_terms( $pid, $tax );
		$terms = "";
		if ( is_wp_error( $terms_arr ) ){
			return $terms;
		}
		for( $i = 0; $i < count( $terms_arr ); $i++ ){
			$term_obj	= $terms_arr[$i];
			$term_slug	= $term_obj->slug;
			$term_name	= esc_html( $term_obj->name );
			$term_link	= esc_url( get_term_link( $term_slug, $tax ) );
			$terms		.= "<a href='$term_link'>$term_name</a>" . ( $i < ( count( $terms_arr ) - 1 ) ? $delim : "" );
		}
		return $terms;
	}
}

if(!function_exists('cws_print_img_html')){
	function cws_print_img_html($img, $img_args, &$img_height = null) {
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

		if ( $is_high_dpi ) {
			if ( empty($img_args['width']) && empty($img_args['height']) ) {
				if (isset($img['width']) && isset($img['height'])) {
					$img_args = array(
						'width' => floor( (int) $img['width'] / 2 ),
						'height' => floor( (int) $img['height'] / 2 ),
						'crop' => true,
						);
				}
			}

			$thumb_obj = cws_get_img( isset($img['id']) ? $img['id'] : $img['src'], $img_args );
			if ($thumb_obj) {
				$img_h = !empty($img_args["height"]) ? $img_args["height"] : '';
				$thumb_path_hdpi = !empty($thumb_obj[3]) ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
				$src = $thumb_path_hdpi;
			}
		} else {
			$img_h = $img['height'];
			$src = " src='".esc_url( $img['src'] )."' data-no-retina";
		}
		if ($img_height) {
			$img_height = $img_h;
		}
		return $src;
	}
}

if(!function_exists('cws_get_meta_option')){
	function cws_get_meta_option($name = '', $check_first_key = false) {
		global $cws_theme_funcs;
		$value = '';
		if(!empty($cws_theme_funcs)){
			$value = isset($cws_theme_funcs::$options[$name]) ? $cws_theme_funcs::$options[$name] : null;
			while (is_string($value) && '{' === substr($value, 0, 1)) {
				$g_name = substr($value, 1, -1);
				$value = isset($cws_theme_funcs::$options[$g_name]) ? $cws_theme_funcs::$options[$g_name] : null;
			}
			if ($check_first_key && is_array($value)) {
				// it's better to set $check_first_key specifically when there's a chance
				// like in case of sidebars processing
				// check if need to replace value with theme option array
				reset($value);
				$first_key = key($value);
				$val = $value[$first_key];
				if (is_string($val) && '{' === substr($val, 0, 1)) {
					$g_name = substr($val, 1, -1);
					$value = isset($cws_theme_funcs::$options[$g_name]) ? $cws_theme_funcs::$options[$g_name] : null;
				}
			}
		}

		return $value;
				
	}
}

if(!function_exists('cws_vc_shortcode_get_sidebars')){
	function cws_vc_shortcode_get_sidebars( $p_id = null ) { /*!*/
		$page_type = 'page';
		$sb = null;
		$post_type = get_post_type($p_id);
		if ($p_id && !is_home() ) {
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
		} else if (is_home()) {
			/* default home page have no ID */
			$page_type = 'home';
		}

		if (!$sb) {
			$sb = cws_get_meta_option("{$page_type}_sidebars", true);
		}

		if ($sb){
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

	}
}

require_once( CWS_SHORTCODES_PLUGIN_DIR . '/templates-modules/cws_sc_portfolio.php' );
require_once( CWS_SHORTCODES_PLUGIN_DIR . '/templates-modules/cws_sc_staff.php' );

// Uncomment this line to activate Testimonials
// require_once( CWS_SHORTCODES_PLUGIN_DIR . '/templates-modules/cws_sc_testimonials.php' );

// Uncomment this line to activate Classes
// require_once( CWS_SHORTCODES_PLUGIN_DIR . '/templates-modules/cws_sc_classes.php' );

require_once( CWS_SHORTCODES_PLUGIN_DIR . '/templates-modules/cws_sc_events.php' );

add_action('wp_ajax_cws_vc_shortcode_tribe_events_posts_grid', 'cws_vc_shortcode_tribe_events_posts_grid');
add_action( 'wp_ajax_nopriv_cws_vc_shortcode_tribe_events_posts_grid', 'cws_vc_shortcode_tribe_events_posts_grid' );

function cws_vc_shortcode_msg_box ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;
	
	$defaults = array(
		/* -----> GENERAL TAB <----- */
		"icon_lib"					=> "FontAwesome",
		"type"						=> "info",
		"title"						=> esc_html__("Enter title here...", 'cws-essentials'),
		"description"				=> esc_html__("Enter description here...", 'cws-essentials'),
		"closable"					=> true,
		"el_class"					=> '',
		/* -----> STYLING TAB <----- */
		"custom_color"				=> false,
		"bg_color"				    => "#d2eaff",
		"icon_color"				=> "#ffffff",
		"icon_bg_color"				=> "#5cade5",
		"text_color"				=> "#1c8ad5",
		"hide_icon"					=> false,
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> ""
		)
	);
	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $result = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
	$icon = esc_attr( $icon );
	$id = uniqid( "cws_msg_box_" );

	/* -----> Extra icons <----- */
	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
		vc_icon_element_fonts_enqueue( $icon_lib );
	}

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $custom_color ){
        if( !empty($bg_color) ){
            $styles .= "
				#".$id."{
					background-color: ".esc_attr($bg_color).";
				}
			";
        }
        if( !empty($icon_bg_color) ){
            $styles .= "
				#".$id." .icon{
					background-color: ".esc_attr($icon_bg_color).";
				}
			";
        }
		if( !empty($icon_color) ){
			$styles .= "
				#".$id." .icon i:not(.svg){
					color: ".esc_attr($icon_color).";
				}
				#".$id." .icon i.svg{
					fill: ".esc_attr($icon_color).";
				}
			";
		}
		if( !empty($text_color) ){
			$styles .= "
				#".$id." .cws-msg-box-info,
				#".$id." .close-btn{
					color: ".esc_attr($text_color).";
				}
			";
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( !empty($vc_landscape_styles) ){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
				#".$id."{
					".$vc_landscape_styles."
				}
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( !empty($vc_portrait_styles) ){
		$styles .= "
			@media screen and (max-width: 991px){
				#".$id."{
					".$vc_portrait_styles."
				}
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( !empty($vc_mobile_styles) ){
		$styles .= "
			@media screen and (max-width: 767px){
				#".$id."{
					".$vc_mobile_styles."
				}
			}
		";
	}
	/* -----> End of mobile styles <----- */

	/* -----> Getting Icon <----- */
	if( !empty($icon_lib) ){
		if( $icon_lib == 'cws_svg' ){
			$icon = "icon_".$icon_lib;
			$svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
			$upload_dir = wp_upload_dir();
			$this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';	

			$result .= '<span class="icon">';
				$result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
					$result .= file_get_contents($this_folder . $svg_icon['name']);
				$result .= "</i>";
			$result .= '</span>';
		} else {
			if( !empty($icon) ){
				$result .= "<span class='icon'>";
					$result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "$icon") ."'></i>";
				$result .= "</span>";
			}
		}
	}

	$module_classes = ' type-'.$type;
	$module_classes .= ( $hide_icon ? ' hide-icon' : '' );
	$module_classes .= ( !empty($el_class) ? ' '.esc_attr($el_class) : '' );

	/* -----> Icon module output <----- */
	$out .= "<div id='".$id."' class='cws-msg-box-module".$module_classes."'>";
	
		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}
		
		$out .= $result;
		$out .= "<div class='cws-msg-box-info'>";
			if( !empty($title) ){
				$out .= "<div class='cws-msg-box-title'>".esc_attr($title)."</div>";
			}
			if( !empty($description) ){
				$out .= "<div class='cws-msg-box-desc'>".esc_attr($description)."</div>";
			}
		$out .= "</div>";

		if( $closable ){
			$out .= "<div class='close-btn'></div>";
		}

	$out .= "</div>";

	return $out;
}
add_shortcode( 'cws_sc_msg_box', 'cws_vc_shortcode_msg_box' );

add_shortcode( 'cws_sc_portfolio_posts_grid', 'cws_vc_shortcode_cws_portfolio_posts_grid' );

// Uncomment this line to activate Classes
// add_shortcode( 'cws_sc_classes_posts_grid', 'cws_vc_shortcode_cws_classes_posts_grid' );

// Uncomment this line to activate Testimonials
// add_shortcode( 'cws_sc_testimonial_posts', 'cws_vc_shortcode_cws_testimonial_posts_grid' );

add_shortcode( 'cws_sc_staff_posts_grid', 'cws_vc_shortcode_cws_staff_posts_grid' );

add_shortcode( 'cws_sc_events_posts_grid', 'cws_vc_shortcode_tribe_events_posts_grid' );

function cws_vc_shortcode_sc_vc_blog ( $atts = array(), $content = "" ){
	$post_type = "post";

	$defaults = cws_blog_defaults();
	$proc_atts = shortcode_atts( $defaults, $atts );

	extract( $proc_atts );

	$out = "";
	$tax = isset( $atts[$post_type . '_tax'] ) ? $atts[$post_type . '_tax'] : '';
	$terms = isset( $atts["{$post_type}_{$tax}_terms"] ) ? $atts["{$post_type}_{$tax}_terms"] : "";

	$proc_atts = array_merge( $proc_atts, array(
		'post_hide_meta_override'				=> $post_hide_meta_override,
		'post_hide_meta'						=> $post_hide_meta,
		'tax'									=> $tax,
		'terms'									=> $terms
	));

	$out .= function_exists( "cws_sc_blog" ) ? cws_sc_blog( $proc_atts ) : "";

	return $out;
}
add_shortcode( 'cws_sc_vc_blog', 'cws_vc_shortcode_sc_vc_blog' );
add_shortcode( 'cws_sc_blog', 'cws_sc_blog' );


function cws_vc_shortcode_carousel ( $atts, $content ){
	global $cws_theme_funcs;

	$first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'columns'					=> '1',
		'slides_to_scroll'			=> '1',
		'pagination'				=> true,
		'navigation'				=> false,
		'nav_position'              => false,
		'auto_height'				=> true,
		'draggable'					=> true,
		'infinite'					=> false,
		'autoplay'					=> false,
		'autoplay_speed'			=> '3000',
		'pause_on_hover'			=> false,
		'vertical'					=> false,
		'vertical_swipe'			=> false,
		'el_class'					=> '',
		/* -----> STYLING TAB <----- */
		'custom_colors'				=> true,
		'nav_color'					=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($second_color).', .49)',
		'nav_hover_color'			=> '#fff',
		'nav_bg'				    => '#fff',
		'nav_hover_bg'				=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($second_color).', .49)',
		'nav_bd'				    => 'rgba('.$cws_theme_funcs->cws_Hex2RGB($second_color).', .21)',
		'dots_color'				=> '#D5D5D5',
		'dots_active_color'			=> $first_color,
		'dots_active_border'		=> $second_color
	);

	$responsive_vars = array(
		'all' => array(
			'custom_styles'	=> ''
		),
	);

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$id = uniqid( "cws-carousel-" );
	$section_atts = " data-columns='".$columns."'";
	$section_atts .= " data-slides-to-scroll='".$slides_to_scroll."'";
	$section_atts .= " data-pagination='".( $pagination ? 'on' : 'off' )."'";
	$section_atts .= " data-navigation='".( $navigation ? 'on' : 'off' )."'";
	$section_atts .= " data-auto-height='".( $auto_height ? 'on' : 'off' )."'";
	$section_atts .= " data-draggable='".( $draggable ? 'on' : 'off' )."'";
	$section_atts .= " data-infinite='".( $infinite ? 'on' : 'off' )."'";
	$section_atts .= " data-autoplay='".( $autoplay ? 'on' : 'off' )."'";
	$section_atts .= " data-autoplay-speed='".$autoplay_speed."'";
	$section_atts .= " data-pause-on-hover='".( $pause_on_hover ? 'on' : 'off' )."'";
	$section_atts .= " data-vertical='".( $vertical ? 'on' : 'off' )."'";
	$section_atts .= " data-vertical-swipe='".( $vertical_swipe ? 'on' : 'off' )."'";
    $section_atts .= " data-mobile-landscape='".( $columns != '1' ? '2' : '1' )."'";
    if ($columns == '2') {
        $section_atts .= " data-tablet-portrait='2'";
    } else if ($columns == '3' || $columns == '4') {
        $section_atts .= " data-tablet-portrait='3'";
    }

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $custom_colors ){
		if( !empty($nav_color) || !empty($nav_bd) ){
			$styles .= "
				#".$id." .cws-carousel .slick-arrow{
					".(!empty($nav_color) ? "color: ".esc_attr($nav_color).";" : "")."
					".(!empty($nav_bd) ? "background-color: ".esc_attr($nav_bd).";" : "")."
				}
			";
		}
        if( !empty($nav_bg) ){
            $styles .= "
				#".$id." .cws-carousel .slick-arrow:after{
					background-color: " . esc_attr($nav_bg) . ";
				}
			";
        }

		if( !empty($dots_color) ){
			$styles .= "
				#".$id." .slick-dots li button:before{
					background-color: ".esc_attr($dots_color).";
				}
			";
		}
		if( !empty($dots_active_color) ){
			$styles .= "
				#".$id." .slick-dots li.slick-active button:before{
					background-color: ".esc_attr($dots_active_color).";
				}
			";
		}
		if( !empty($dots_active_border) ){
			$styles .= "
				#".$id." .slick-dots li.slick-active button:after{
					border-color: ".esc_attr($dots_active_border).";
				}
			";
		}
        if( !empty($nav_hover_color) || !empty($nav_hover_bg) ){
            $styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

            $styles .= "
				#".$id." .cws-carousel .slick-arrow:hover{
					".(!empty($nav_hover_color) ? "color: ".esc_attr($nav_hover_color).";" : "")."
					".(!empty($nav_hover_bg) ? "background-color: ".esc_attr($nav_hover_bg).";" : "")."
				}
			";

            $styles .= "
                }
            ";
        }
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( !empty($vc_landscape_styles) ){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
				#".$id."{
					".$vc_landscape_styles."
				}
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( !empty($vc_portrait_styles) ){
		$styles .= "
			@media screen and (max-width: 991px){
				#".$id."{
					".$vc_portrait_styles."
				}
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( !empty($vc_mobile_styles) ){
		$styles .= "
			@media screen and (max-width: 767px){
				#".$id."{
					".$vc_mobile_styles."
				}
			}
		";
	}
	/* -----> End of mobile styles <----- */ 

	/* -----> Carousel module output <----- */
	if ( !empty( $content ) ){
		$out .= "<div id='".$id."' class='cws-carousel-wrapper" . ($nav_position == 'inside' ? ' inner-arrows ' : ' outer-arrows ') .esc_attr($el_class)."'". ( !empty($section_atts) ?
                $section_atts : "" ) .">";
			if( !empty($styles) ){
				Cws_shortcode_css()->enqueue_cws_css($styles);
			}

			$shortcode = do_shortcode($content);
			$count = 1;

			if( preg_match_all('/woocommerce/', $shortcode) != 0 ){
				$shortcode = preg_replace('/products/', 'products cws-carousel', $shortcode, $count);
				$out .= $shortcode;
			} else {
				$out .= "<div class='cws-carousel cws-wrapper'>";
					$out .= $shortcode;
				$out .= "</div>";
			}


		$out .= "</div>";
	}
	wp_enqueue_script( 'slick-carousel' );

	return $out;
}
add_shortcode( 'cws_sc_carousel', 'cws_vc_shortcode_carousel' );

function cws_vc_shortcode_sc_call_to_action ( $atts = array(), $content = "" ){
    global $cws_theme_funcs;

    $theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
    $theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

    $defaults = array(
        /* -----> GENERAL TAB <----- */
        "icon_lib"					=> "FontAwesome",
        "title"                     => esc_html__("Enter title here...", "metamax"),
        "description"               => esc_html__("Enter description here...", "metamax"),
        "add_divider"               => true,
        "divider_pos"               => "beside",
        "add_button"                => true,
        "button_url"               => "#link",
        "button_title"              => esc_html__("Read More", "metamax"),
        "button_pos"                => "beside",
        "el_class"					=> "",
        /* -----> STYLING TAB <----- */
        "customize_position"        => false,
        "aligning"                  => "left",
        "customize_color"           => false,
        "icon_color"                => $theme_first_color,
        "icon_bd_color"             => "",
        "icon_bg_color"             => $theme_second_color,
        "title_color"               => $theme_first_color,
        "description_color"         => $theme_first_color,
        "divider_color"             => $theme_second_color,
        "button_color"              => "#ffffff",
        "button_color_hover"        => $theme_first_color,
        "button_bd_color"           => $theme_first_color,
        "button_bd_color_hover"     => $theme_first_color,
        "button_bg_color"           => $theme_first_color,
        "button_bg_color_hover"     => "#ffffff",

        "icon_pos"                  => "beside",
        "vert_align"                => "middle",
    );

    $responsive_vars = array(
        "all" => array(
            "custom_styles"		    => "",

            "customize_size"		=> false,
            "icon_size"			    => "40",
            "icon_spacing"		    => "20",
            "title_size"		    => "25",
            "desc_size"		        => "16",
            "icon_paddings"		    => "0px 38px 0px 0px",
            "button_paddings"		=> "0px 0px 0px 80px",
        ),
    );
    $responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

    $responsive_defaults = add_responsive_suffix($responsive_vars);
    $defaults = array_merge($defaults, $responsive_defaults);

    $proc_atts = shortcode_atts( $defaults, $atts );
    extract( $proc_atts );

    /* -----> Variables declaration <----- */
    $out = $result = $styles = $classes = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
    $icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
    $icon = esc_attr( $icon );
    $id = uniqid( "cws-cte-" );

    /* -----> Extra icons <----- */
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
        vc_icon_element_fonts_enqueue( $icon_lib );
    }

    /* -----> Visual Composer Responsive styles <----- */
    list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

    preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles);
    $vc_desktop_styles = implode($vc_desktop_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
    $vc_landscape_styles = implode($vc_landscape_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
    $vc_portrait_styles = implode($vc_portrait_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
    $vc_mobile_styles = implode($vc_mobile_styles);

    /* -----> Customize default styles <----- */
    if( !empty($vc_desktop_styles) ){
        $styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
    }

    if( $customize_size ){
        if( !empty($icon_size) ){
            $styles .= "
				#".$id." .cte-icon,
				#".$id." .cte-icon i:not(.svg):before{
					font-size: ".(int)esc_attr($icon_size)."px;
					line-height: ".(int)esc_attr($icon_size)."px;
				}
			";
        }
        if( !empty($icon_spacing) ){
            $styles .= "
				#".$id." .cte-icon{
					padding: ".(int)esc_attr($icon_spacing)."px;
				}
			";
        }
        if ( !empty($title_size) ) {
            $styles .= "
                #".$id." .cte-title {
                    font-size: ".(int)esc_attr($title_size)."px;
                }
            ";
        }
        if ( !empty($desc_size) ) {
            $styles .= "
                #".$id." .cte-title {
                    font-size: ".(int)esc_attr($desc_size)."px;
                }
            ";
        }
        if ( !empty($icon_paddings) ) {
            $styles .= "
                #".$id." .cte-icon-wrapper {
                    margin: ".esc_attr($icon_paddings).";
                }
            ";
        }
        if ( !empty($button_paddings) ) {
            $styles .= "
                #".$id." .cte-button-wrapper {
                    margin: ".esc_attr($button_paddings).";
                }
            ";
        }
    }
    if( $customize_color ){
        if( !empty($icon_color) ){
            $styles .= "
				#".$id." .cte-icon i{
					color: ".esc_attr($icon_color).";
				}
			";
            $styles .= "
				#".$id." .cte-icon i.svg{
					fill: ".esc_attr($icon_color).";
				}
			";
        }
        if( !empty($icon_bd_color) || !empty($icon_bg_color) ){
            $styles .= "
				#".$id." .cte-icon{
					".( !empty($icon_bd_color) ? "border-color: ".esc_attr($icon_bd_color).";" : "")."
					".( !empty($icon_bg_color) ? "background-color: ".esc_attr($icon_bg_color).";" : "")."
				}
			";
        }
        if( !empty($title_color) ){
            $styles .= "
				#".$id." .cte-title{
					color: ".esc_attr($title_color).";
				}
			";
        }
        if( !empty($description_color) ){
            $styles .= "
				#".$id." .cte-description{
					color: ".esc_attr($description_color).";
				}
			";
        }
        if( !empty($divider_color) ){
            $styles .= "
				#".$id." .cte-divider{
					background-color: ".esc_attr($divider_color).";
				}
			";
        }
        if( !empty($button_color) || !empty($button_bd_color) || !empty($button_bg_color) ){
            $styles .= "
				#".$id." .cws-custom-button{
					".( !empty($button_color) ? "color: ".esc_attr($button_color).";" : "" )."
					".( !empty($button_bd_color) ? "border-color: ".esc_attr($button_bd_color).";" : "" )."
					".( !empty($button_bg_color) ? "background-color: ".esc_attr($button_bg_color).";" : "" )."
				}
			";
        }

        if ( empty($button_color_hover) || !empty($button_bd_color_hover) || !empty($button_bg_color_hover) ) {
            $styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

            $styles .= "
				#".$id." .cws-custom-button:hover{
					".( !empty($button_color_hover) ? "color: ".esc_attr($button_color_hover).";" : "" )."
					".( !empty($button_bd_color_hover) ? "border-color: ".esc_attr($button_bd_color_hover).";" : "" )."
					".( !empty($button_bg_color_hover) ? "background-color: ".esc_attr($button_bg_color_hover).";" : "" )."
				}
			";

            $styles .="
				}
			";
        }
    }
    /* -----> End of default styles <----- */

    /* -----> Customize landscape styles <----- */
    if(
        !empty($vc_landscape_styles) ||
        $customize_size_landscape
    ){
        $styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

        if( !empty($vc_landscape_styles) ){
            $styles .= "
				#".$id."{
					".$vc_landscape_styles."
				}
			";
        }
        if($customize_size_landscape){
            if( !empty($icon_size_landscape) ){
                $styles .= "
                    #".$id." .cte-icon,
                    #".$id." .cte-icon i:not(.svg):before{
                        font-size: ".(int)esc_attr($icon_size_landscape)."px;
                        line-height: ".(int)esc_attr($icon_size_landscape)."px;
                    }
                ";
            }
            if( !empty($icon_spacing_landscape) ){
                $styles .= "
                    #".$id." .cte-icon{
                        padding: ".(int)esc_attr($icon_spacing_landscape)."px;
                    }
                ";
            }
            if ( !empty($title_size_landscape) ) {
                $styles .= "
                    #".$id." .cte-title {
                        font-size: ".(int)esc_attr($title_size_landscape)."px;
                    }
                ";
            }
            if ( !empty($desc_size_landscape) ) {
                $styles .= "
                    #".$id." .cte-title {
                        font-size: ".(int)esc_attr($desc_size_landscape)."px;
                    }
                ";
            }
            if ( !empty($icon_paddings_landscape) ) {
                $styles .= "
                    #".$id." .cte-icon-wrapper {
                        margin: ".esc_attr($icon_paddings_landscape).";
                    }
                ";
            }
            if ( !empty($button_paddings_landscape) ) {
                $styles .= "
                    #".$id." .cte-button-wrapper {
                        margin: ".esc_attr($button_paddings_landscape).";
                    }
                ";
            }
        }

        $styles .= "
			}
		";
    }
    /* -----> End of landscape styles <----- */

    /* -----> Customize portrait styles <----- */
    if(
        !empty($vc_portrait_styles) ||
        $customize_size_portrait
    ){
        $styles .= "
			@media screen and (max-width: 991px){
		";

        if( !empty($vc_portrait_styles) ){
            $styles .= "
					#".$id."{
						".$vc_portrait_styles."
					}
				";
        }
        if($customize_size_portrait){
            if( !empty($icon_size_portrait) ){
                $styles .= "
                    #".$id." .cte-icon,
                    #".$id." .cte-icon i:not(.svg):before{
                        font-size: ".(int)esc_attr($icon_size_portrait)."px;
                        line-height: ".(int)esc_attr($icon_size_portrait)."px;
                    }
                ";
            }
            if( !empty($icon_spacing_portrait) ){
                $styles .= "
                    #".$id." .cte-icon{
                        padding: ".(int)esc_attr($icon_spacing_portrait)."px;
                    }
                ";
            }
            if ( !empty($title_size_portrait) ) {
                $styles .= "
                    #".$id." .cte-title {
                        font-size: ".(int)esc_attr($title_size_portrait)."px;
                    }
                ";
            }
            if ( !empty($desc_size_portrait) ) {
                $styles .= "
                    #".$id." .cte-title {
                        font-size: ".(int)esc_attr($desc_size_portrait)."px;
                    }
                ";
            }
            if ( !empty($icon_paddings_portrait) ) {
                $styles .= "
                    #".$id." .cte-icon-wrapper {
                        margin: ".esc_attr($icon_paddings_portrait).";
                    }
                ";
            }
            if ( !empty($button_paddings_portrait) ) {
                $styles .= "
                    #".$id." .cte-button-wrapper {
                        margin: ".esc_attr($button_paddings_portrait).";
                    }
                ";
            }
        }

        $styles .= "
			}
		";
    }
    /* -----> End of portrait styles <----- */

    /* -----> Customize mobile styles <----- */
    if(
        !empty($vc_mobile_styles) ||
        $customize_size_mobile
    ){
        $styles .= "
			@media screen and (max-width: 767px){
		";

        if( !empty($vc_mobile_styles) ){
            $styles .= "
					#".$id."{
						".$vc_mobile_styles."
					}
				";
        }
        if($customize_size_mobile){
            if( !empty($icon_size_mobile) ){
                $styles .= "
                    #".$id." .cte-icon,
                    #".$id." .cte-icon i:not(.svg):before{
                        font-size: ".(int)esc_attr($icon_size_mobile)."px;
                        line-height: ".(int)esc_attr($icon_size_mobile)."px;
                    }
                ";
            }
            if( !empty($icon_spacing_mobile) ){
                $styles .= "
                    #".$id." .cte-icon{
                        padding: ".(int)esc_attr($icon_spacing_mobile)."px;
                    }
                ";
            }
            if ( !empty($title_size_mobile) ) {
                $styles .= "
                    #".$id." .cte-title {
                        font-size: ".(int)esc_attr($title_size_mobile)."px;
                    }
                ";
            }
            if ( !empty($desc_size_mobile) ) {
                $styles .= "
                    #".$id." .cte-title {
                        font-size: ".(int)esc_attr($desc_size_mobile)."px;
                    }
                ";
            }
            if ( !empty($icon_paddings_mobile) ) {
                $styles .= "
                    #".$id." .cte-icon-wrapper {
                        margin: ".esc_attr($icon_paddings_mobile).";
                    }
                ";
            }
            if ( !empty($button_paddings_mobile) ) {
                $styles .= "
                    #".$id." .cte-button-wrapper {
                        margin: ".esc_attr($button_paddings_mobile).";
                    }
                ";
            }
        }

        $styles .= "
			}
		";
    }
    /* -----> End of mobile styles <----- */

    /* -----> Getting Icon <----- */
    if( !empty($icon_lib) ){
        if( $icon_lib == 'cws_svg' ){
            $icon = "icon_".$icon_lib;
            $svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
            $upload_dir = wp_upload_dir();
            $this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';

            $result .= '<span class="cte-icon">';
                $result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
                    $result .= file_get_contents($this_folder . $svg_icon['name']);
                $result .= "</i>";
            $result .= '</span>';
        } else {
            if( !empty($icon) ){
                $result .= "<span class='cte-icon'>";
                    $result .= "<i class='". ( !empty($size) ? "cws-icon-".esc_attr($size).esc_attr($icon) : esc_attr
                        ($icon) ) ."'></i>";
                $result .= "</span>";
            }
        }
    }

    /* -----> Module classes <----- */
    if ( !empty($vert_align) ) {
        $classes .= ' vertical-align-'.esc_attr($vert_align);
    }
    if ( !empty($aligning) ) {
        $classes .= ' align-'.esc_attr($aligning);
    }
    if ( !empty($icon_pos) ) {
        $classes .= ' icon-position-'.esc_attr($icon_pos);
    }
    if ( $add_divider && !empty($divider_pos) ) {
        $classes .= ' divider-position-'.esc_attr($divider_pos);
    }
    if ( $add_button && !empty($button_title) && !empty($button_pos) ) {
        $classes .= ' button-position-'.esc_attr($button_pos);
    }

    /* -----> Icon module output <----- */
    $out .= '<div class="cws-cte-wrapper'.esc_attr($classes).'" id="'.esc_attr($id).'">';
        if ( !empty($styles) ){
            Cws_shortcode_css()->enqueue_cws_css($styles);
        }
        $out .= '<div class="cws-cte">';
        if ( !empty($title) || !empty($description) || !empty($result) ) {
            $out .= '<div class="cte-content-wrapper">';
            if ( !empty($result) ) {
                $out .= '<div class="cte-icon-wrapper">';
                    $out .= $result;
                $out .= '</div>';
            }
            if ( !empty($title) || !empty($description) ) {
                $out .= '<div class="cte-info-wrapper">';
                if ( !empty($title) ) {
                    $out .= '<div class="cte-title">';
                        $out .= wp_kses($title, array(
                            'br'     => array(),
                            'mark'   => array(),
                            'a'      => array(
                                'href'   => array(),
                                'target' => array(),
                            ),
                            'em'     => array(),
                            'strong' => array(),
                            'b'      => array(),
                        ));
                    $out .= '</div>';
                }
                if ( $add_divider && $divider_pos == 'under' ) {
                    $out .= '<div class="cte-divider"></div>';
                }
                if ( !empty($description) ) {
                    $out .= '<div class="cte-description">';
                        $out .= wp_kses($description, array(
                            'br'     => array(),
                            'mark'   => array(),
                            'a'      => array(
                                'href'   => array(),
                                'target' => array(),
                            ),
                            'em'     => array(),
                            'strong' => array(),
                            'b'      => array(),
                        ));
                    $out .= '</div>';
                }
                $out .= '</div>';
            }
            $out .= '</div>';
        }
        if ( $add_divider && $divider_pos == 'beside' ) {
            $out .= '<div class="cte-divider"></div>';
        }
        if ( $add_button && !empty($button_title) ) {
            $out .= '<div class="cte-button-wrapper">';
                $out .= '<a href="'.(!empty($button_url) ? esc_url($button_url) : '#').'" class="cws-custom-button regular icon-position-right icon-face-flaticon-028-arrow-metamax">';
                    $out .= '<span class="button-icon">';
                        $out .= '<i class="cws_vc_shortcode_icon_3x flaticon-028-arrow-metamax"></i>';
                    $out .= '</span>';
                    $out .= esc_html($button_title);
                    $out .= '<span class="button-icon">';
                        $out .= '<i class="cws_vc_shortcode_icon_3x flaticon-028-arrow-metamax"></i>';
                    $out .= '</span>';
                $out .= '</a>';
            $out .= '</div>';
        }
        $out .= '</div>';
    $out .= '</div>';

    return $out;
}
add_shortcode( 'cws_sc_call_to_action', 'cws_vc_shortcode_sc_call_to_action' );

function cws_vc_shortcode_sc_icon ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
        "icon_shape"                => "rounded",
		"icon_lib"					=> "FontAwesome",
		"url"						=> "",
		"pop_up"                    => false,
		"new_tab"					=> false,
		"add_shadow"                => false,
		"add_animation"             => false,
		"el_class"					=> "",
		/* -----> STYLING TAB <----- */
		"custom_color"				=> false,
		"icon_color"				=> $theme_first_color,
		"icon_color_hover"			=> "",
		"icon_bd_color"			=> "",
		"icon_bd_color_hover"	=> "",
        "icon_bg_color"             => "",
        "icon_bg_color_hover"       => "",
 	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> "",
			"customize_align"	=> false,
			"aligning"			=> "left",
			"custom_size"		=> "",
			"icon_size"			=> "64",
			"icon_spacing"		=> "48",
            "border_width"      => "4",
		),
	);
	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $result = $styles = $module_classes = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
	$icon = esc_attr( $icon );
	$id = uniqid( "cws-icon-" );

	/* -----> Extra icons <----- */
	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
		vc_icon_element_fonts_enqueue( $icon_lib );
	}

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( $customize_align ){
		$styles .= "
			#".$id."{
				text-align: ".$aligning.";
			}
		";
	}
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $custom_size ){
		if( !empty($icon_size) ){
			$styles .= "
				#".$id." .icon,
				#".$id." .icon i:not(.svg):before{
					font-size: ".(int)esc_attr($icon_size)."px;
					line-height: ".(int)esc_attr($icon_size)."px;
				}
			";

            if (empty($icon_spacing)) {
                $icon_spacing = "0";
            }
            if (empty($border_width)) {
                $border_width = "4";
            }
            $styles .= "
                #" . $id . " .icon-icon-wrapper{
                    padding: " . (int)esc_attr($icon_spacing) . "px;
                }
                #" . $id . " .icon-icon-wrapper:before{
                    " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2) + ((int)esc_attr($border_width) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2) + ((int)esc_attr($border_width) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2) + ($border_width * 2)) . "px;" : "")."
                }
                #" . $id . " .icon-icon-wrapper:after{
                    " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2)) . "px;" : "")."
                }
            ";
		}
	}
	if( $custom_color ) {
        if (!empty($icon_color)) {
            $styles .= "
				#" . $id . " .icon i:not(.svg){
					color: " . esc_attr($icon_color) . ";
				}
			";
            $styles .= "
				#" . $id . " .icon i.svg{
					fill: " . esc_attr($icon_color) . ";
				}
			";
        }

        if (!empty($icon_bg_color)) {
            $styles .= "
				#" . $id . " .icon-icon-wrapper:after{
					" . ($icon_shape == "hexagon" ? "color: " . esc_attr($icon_bg_color) : "background-color: " . esc_attr($icon_bg_color)) . " !important;
				}
			";
        }

        if (!empty($icon_bd_color)) {
            if ($icon_shape == 'hexagon') {
                $styles .= "
                    #" . $id . " .icon-icon-wrapper:before{
                        color: " . esc_attr($icon_bd_color) . " !important;
                    }
                ";
            } else {
                $styles .= "
                    #" . $id . " .icon-icon-wrapper:before{
                        background-color: " . esc_attr($icon_bd_color) . " !important;
                    }
                ";
            }
        }

        if (
            !empty($icon_color_hover) ||
            !empty($icon_bg_color_hover) ||
            !empty($icon_bd_color_hover)
        ) {
            $styles .= "

				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

            if (!empty($icon_color_hover)) {
                $styles .= "
				    #" . $id . " .cws-icon:hover .icon i:not(.svg){
					    color: " . esc_attr($icon_color_hover) . ";
				    }
			    ";
                $styles .= "
				    #" . $id . " .cws-icon:hover .icon i.svg{
					    fill: " . esc_attr($icon_color_hover) . ";
				    }
			    ";
            }

            if (!empty($icon_bg_color_hover)) {
                $styles .= "
				    #" . $id . ":hover .icon-icon-wrapper:after{
					    " . ($icon_shape == "hexagon" ? "color: " . esc_attr($icon_bg_color_hover) : "background-color: " . esc_attr($icon_bg_color_hover)) . " !important;
				    }
			    ";
            }

            if (!empty($icon_bd_color_hover)) {
                if ($icon_shape == 'hexagon') {
                    $styles .= "
                        #" . $id . ":hover .icon-icon-wrapper:before{
                            color: " . esc_attr($icon_bd_color_hover) . " !important;
                        }
                    ";
                } else {
                    $styles .= "
                        #" . $id . ":hover .icon-icon-wrapper:before{
                            background-color: " . esc_attr($icon_bd_color_hover) . " !important;
                        }
                    ";
                }
            }

            $styles .= "
            }
            ";
        }
    }
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$customize_align_landscape || 
		$custom_size_landscape
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					#".$id."{
						".$vc_landscape_styles."
					}
				";
			}
			if( !empty($customize_align_landscape) ){
				$styles .= "
					#".$id."{
						text-align: ".$aligning_landscape.";
					}
				";
			}
			if($custom_size_landscape){
				if( !empty($icon_size_landscape) ){
					$styles .= "
						#".$id.".type-simple .icon,
						#".$id.".type-simple .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_landscape)."px;
							line-height: ".(int)esc_attr($icon_size_landscape)."px;
						}
						#".$id.".type-bordered .icon,
						#".$id.".type-bordered .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_landscape)."px;
							line-height: ".((int)esc_attr($icon_size_landscape) - 2)."px;
						}
					";

                    if (empty($icon_spacing_landscape)) {
                        $icon_spacing_landscape = $icon_spacing;
                    }
                    if (empty($border_width_landscape)) {
                        $border_width_landscape = $border_width;
                    }
                    $styles .= "
                #" . $id . " .icon-icon-wrapper{
                    padding: " . (int)esc_attr($icon_spacing_landscape) . "px;
                }
                #" . $id . " .icon-icon-wrapper:before{
                    " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2) + ((int)esc_attr($border_width_landscape) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2) + ((int)esc_attr($border_width_landscape) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2) + ((int)esc_attr($border_width_landscape) * 2)) . "px;" : "")."
                }
                #" . $id . " .icon-icon-wrapper:after{
                    " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2)) . "px;" : "")."
                    " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2)) . "px;" : "")."
                }
            ";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$customize_align_portrait || 
		$custom_size_portrait
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					#".$id."{
						".$vc_portrait_styles."
					}
				";
			}
			if( !empty($customize_align_portrait) ){
				$styles .= "
					#".$id."{
						text-align: ".$aligning_portrait.";
					}
				";
			}
			if($custom_size_portrait){
				if( !empty($icon_size_portrait) ){
					$styles .= "
						#".$id.".type-simple .icon,
						#".$id.".type-simple .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_portrait)."px;
							line-height: ".(int)esc_attr($icon_size_portrait)."px;
						}
						#".$id.".type-bordered .icon,
						#".$id.".type-bordered .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_portrait)."px;
							line-height: ".((int)esc_attr($icon_size_portrait) - 2)."px;
						}
					";

                    if (empty($icon_spacing_portrait)) {
                        $icon_spacing_portrait = $icon_spacing;
                    }
                    if (empty($border_width_portrait)) {
                        $border_width_portrait = $border_width;
                    }
                    $styles .= "
                        #" . $id . " .icon-icon-wrapper{
                            padding: " . (int)esc_attr($icon_spacing_portrait) . "px;
                        }
                        #" . $id . " .icon-icon-wrapper:before{
                            " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2) + ((int)esc_attr($border_width_portrait) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2) + ((int)esc_attr($border_width_portrait) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2) + ((int)esc_attr($border_width_portrait) * 2)) . "px;" : "")."
                        }
                        #" . $id . " .icon-icon-wrapper:after{
                            " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2)) . "px;" : "")."
                        }
                    ";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$customize_align_mobile || 
		$custom_size_mobile
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					#".$id."{
						".$vc_mobile_styles."
					}
				";
			}
			if( !empty($customize_align_mobile) ){
				$styles .= "
					#".$id."{
						text-align: ".$aligning_mobile.";
					}
				";
			}
			if($custom_size_mobile){
				if( !empty($icon_size_mobile) ){
					$styles .= "
						#".$id.".type-simple .icon,
						#".$id.".type-simple .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_mobile)."px;
							line-height: ".(int)esc_attr($icon_size_mobile)."px;
						}
						#".$id.".type-bordered .icon,
						#".$id.".type-bordered .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_mobile)."px;
							line-height: ".((int)esc_attr($icon_size_mobile) - 2)."px;
						}
					";

                    if (empty($icon_spacing_mobile)) {
                        $icon_spacing_mobile = $icon_spacing;
                    }
                    if (empty($icon_spacing_mobile)) {
                        $border_width_mobile = $icon_spacing;
                    }
                    $styles .= "
                        #" . $id . " .icon-icon-wrapper{
                            padding: " . (int)esc_attr($icon_spacing_mobile) . "px;
                        }
                        #" . $id . " .icon-icon-wrapper:before{
                            " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2) + ((int)esc_attr($border_width_mobile) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2) + ((int)esc_attr($border_width_mobile) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2) + ((int)esc_attr($border_width_mobile) * 2)) . "px;" : "")."
                        }
                        #" . $id . " .icon-icon-wrapper:after{
                            " . ($icon_shape == "hexagon" ? "font-size: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "width: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2)) . "px;" : "")."
                            " . ($icon_shape != "hexagon" ? "height: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2)) . "px;" : "")."
                        }
                    ";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */

	/* -----> Getting Icon <----- */
	if( !empty($icon_lib) ){
        $result .= "<div class='icon-icon-wrapper'>";
	    if( $icon_lib == 'cws_svg' ){
			$icon = "icon_".$icon_lib;
			$svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
			$upload_dir = wp_upload_dir();
			$this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';	

			$result .= '<span class="icon">';
				$result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
					$result .= file_get_contents($this_folder . $svg_icon['name']);
				$result .= "</i>";
			$result .= '</span>';
		} else {
			if( !empty($icon) ){
				$result .= "<span class='icon'>";
					$result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon") ."'></i>";
				$result .= "</span>";
			}
		}
        $result .= "</div>";
	}

	if( $icon_shape ){
	    $module_classes .= " icon-shape-".esc_attr($icon_shape);
	}
	if( $add_shadow ){
	    $module_classes .= " with-shadow";
	}
	if( $add_animation ){
	    $module_classes .= " with-animation";
	}

	/* -----> Icon module output <----- */
	if( !empty($url) ){
		$start_tag = "<a href='".esc_url($url)."'".($new_tab ? ' target="_blank"' : '')." ";
		$end_tag = "</a>";
        wp_enqueue_script('fancybox');
        wp_enqueue_style('fancybox');
	} else {
		$start_tag = "<div ";
		$end_tag = "</div>";
	}

	$out .= "<div id='".$id."' class='cws-icon-wrapper".esc_attr($module_classes).esc_attr($el_class)."'>";
	
		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		if ( $pop_up ) {
            $out .= $start_tag."class='cws-icon fancy fancybox.iframe' data-fancybox-group='icon_iframe_".esc_attr($id)."'>";
		} else {
            $out .= $start_tag."class='cws-icon'>";
        }
			$out .= $result;
		$out .= $end_tag;

	$out .= "</div>";
	
	return $out;
}
add_shortcode( 'cws_sc_icon', 'cws_vc_shortcode_sc_icon' );

function cws_vc_shortcode_sc_image ( $atts = array(), $content = "" ){
    $defaults = array(
        /* -----> GENERAL TAB <----- */
        "image"							=> "",
        "image_active"					=> "",
        "thumbnail_size"                => "full",
        "el_class"						=> "",
        /* -----> STYLING TAB <----- */
        "bg_hover"						=> "no-hover",
    );

    $responsive_vars = array(
        "all" => array(
            "custom_styles"		=> "",
            "customize_align"	=> false,
            "alignment"			=> "center",
        ),
    );
    $responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

    $responsive_defaults = add_responsive_suffix($responsive_vars);
    $defaults = array_merge($defaults, $responsive_defaults);

    $proc_atts = shortcode_atts( $defaults, $atts );
    extract( $proc_atts );

    /* -----> Variables declaration <----- */
    $out = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
    $id = uniqid( "cws-image-" );
    $image_alt = get_post_meta($image, '_wp_attachment_image_alt', true);
    $image_active_alt = get_post_meta($image_active, '_wp_attachment_image_alt', true);

    $img_src = !empty($image) ? wp_get_attachment_image_url( $image, $thumbnail_size ) : '';
    $img_srcset = !empty($image) ? wp_get_attachment_image_srcset( $image, $thumbnail_size ) : '';
    $img_sizes = !empty($image) ? wp_get_attachment_image_sizes($image, $thumbnail_size) : '';

    $img_active_src = !empty($image_active) ? wp_get_attachment_image_url( $image_active, $thumbnail_size ) : '';
    $img_active_srcset = !empty($image_active) ? wp_get_attachment_image_srcset( $image_active, $thumbnail_size ) : '';
    $img_active_sizes = !empty($image_active) ? wp_get_attachment_image_sizes($image_active, $thumbnail_size) : '';

    /* -----> Visual Composer Responsive styles <----- */
    list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

    preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles);
    $vc_desktop_styles = implode($vc_desktop_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
    $vc_landscape_styles = implode($vc_landscape_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
    $vc_portrait_styles = implode($vc_portrait_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
    $vc_mobile_styles = implode($vc_mobile_styles);

    /* -----> Customize default styles <----- */
    if( !empty($vc_desktop_styles) ){
        $styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
    }
    if( $customize_align ){
        $styles .= "
			#".$id."{
				text-align: ".esc_attr($alignment).";
			}
		";
    }
    /* -----> End of default styles <----- */

    /* -----> Customize landscape styles <----- */
    if(
        !empty($vc_landscape_styles) ||
        $customize_align_landscape
    ){
        $styles .= "
			@media 
				screen and (max-width: 1199px),
				screen and (max-width: 1366px) and (any-hover: none)
			{
		";

        if( !empty($vc_landscape_styles) ){
            $styles .= "
					#".$id."{
						".$vc_landscape_styles.";
					}
				";
        }
        if( $customize_align_landscape ){
            $styles .= "
					#".$id."{
						text-align: ".esc_attr($alignment_landscape).";
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of landscape styles <----- */

    /* -----> Customize portrait styles <----- */
    if(
        !empty($vc_portrait_styles) ||
        $customize_align_portrait
    ){
        $styles .= "
			@media screen and (max-width: 991px){
		";

        if( !empty($vc_portrait_styles) ){
            $styles .= "
					#".$id."{
						".$vc_portrait_styles.";
					}
				";
        }
        if( $customize_align_portrait ){
            $styles .= "
					#".$id."{
						text-align: ".esc_attr($alignment_portrait).";
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of portrait styles <----- */

    /* -----> Customize mobile styles <----- */
    if(
        !empty($vc_mobile_styles) ||
        $customize_align_mobile
    ){
        $styles .= "
			@media screen and (max-width: 767px){
		";

        if( !empty($vc_mobile_styles) ){
            $styles .= "
					#".$id."{
						".$vc_mobile_styles.";
					}
				";
        }
        if( $customize_align_mobile ){
            $styles .= "
					#".$id."{
						text-align: ".esc_attr($alignment_mobile).";
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of mobile styles <----- */

    if ( !empty($styles) ){
        Cws_shortcode_css()->enqueue_cws_css($styles);
    }

    $module_classes = " effect-".esc_attr($bg_hover);

    if( !empty($el_class) ){
        $module_classes .= " ".esc_attr($el_class);
    }

    /* -----> Image module output <----- */
    if( !empty($image) ){

        $out .= "<div id='".$id."' class='cws-image-module".$module_classes."'>";

            $out .= '<div class="main-image">';
                $out .= '<img src="'.$img_src.'" srcset="'.esc_attr($img_srcset).'" sizes="'.esc_attr($img_sizes).'" alt="'.esc_attr($image_alt).'" class="main-image-main" />';
                if ( !empty($image_active) && $bg_hover == 'roll-down' ) {
                    $out .= '<img src="'.$img_active_src.'" srcset="'.esc_attr($img_active_srcset).'" sizes="'.esc_attr($img_active_sizes) .'" alt="'.esc_attr($image_active_alt).'" class="main-image-hover" />';
                }
            $out .= '</div>';

        $out .= "</div>";
    }

    return $out;
}
add_shortcode( 'cws_sc_image', 'cws_vc_shortcode_sc_image' );

function cws_vc_shortcode_sc_button ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		"title"							=> esc_html__("Click Me!", "cws-essentials"),
		"url"							=> "#",
		"size"							=> "regular",
        "icon_lib"					    => "FontAwesome",
        "icon_pos"					    => "right",
		"new_tab"						=> true,
		"el_class"						=> "",
		/* -----> STYLING TAB <----- */
		"custom_colors"					=> true,
		"btn_font_color"				=> "#fff",
		"btn_font_color_hover"			=> "#fff",
		"btn_background_color"			=> 'rgba('.$cws_theme_funcs->cws_Hex2RGB($theme_first_color).', .9)',
		"btn_background_color_hover"	=> $theme_first_color,
		"btn_border_color"				=> 'transparent',
		"btn_border_color_hover"		=> 'transparent',
        "btn_icon_color"				=> '#fff',
        "btn_icon_color_hover"		    => '#fff',
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> "",
			"customize_align"	=> false,
			"aligning"			=> "left",
			"custom_size"		=> false,
			"title_size"		=> "18px"
		),
	);

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $classes = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = $result = "";
	$id_wrapper = uniqid( "cws_button_wrapper_" );
	$id = uniqid( "cws_button_" );
    $icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
    $icon = esc_attr( $icon );

    /* -----> Extra icons <----- */
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
        vc_icon_element_fonts_enqueue( $icon_lib );
    }

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);


	/* -----> Customize default styles <----- */
	if( $customize_align ){
		$styles .= "
			#".$id_wrapper."{
				text-align: ".$aligning.";
			}
		";
	}
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $custom_size ){
		if( !empty($title_size) ){
			$styles .= "
				#".$id."{
					font-size: ".(int)esc_attr($title_size)."px;	
				}
			";
		}
	}
	if( $custom_colors ){
		if( !empty($btn_font_color) ){
			$styles .= "
				#".$id."{
					color: ".esc_attr($btn_font_color).";	
				}
			";
		}
        if( !empty($btn_icon_color) ){
            $styles .= "
				#".$id." .button-icon{
					color: ".esc_attr($btn_icon_color).";
				}
				#".$id." .svg{
					fill: ".esc_attr($btn_icon_color).";
				}
			";
        }
		if( !empty($btn_background_color) ){
			$styles .= "
				#".$id."{
					background-color: ".esc_attr($btn_background_color).";	
				}
			";
		}
		if( !empty($btn_border_color) ){
			$styles .= "
				#".$id."{
					border-color: ".esc_attr($btn_border_color).";	
				}
			";
		}
		if(
			!empty($btn_font_color_hover) ||
			!empty($btn_background_color_hover) ||
			!empty($btn_border_color_hover) ||
            !empty($btn_icon_color_hover)
		) {
			$styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

				if( !empty($btn_font_color_hover) ){
					$styles .= "
						#".$id.":hover{
							color: ".esc_attr($btn_font_color_hover).";	
						}
					";
				}
                if( !empty($btn_icon_color_hover) ){
                    $styles .= "
                        #".$id.":hover .button-icon{
                            color: ".esc_attr($btn_icon_color_hover).";
                        }
                        #".$id.":hover .svg{
                            fill: ".esc_attr($btn_icon_color_hover).";
                        }
                    ";
                }
				if( !empty($btn_background_color_hover) ){
					$styles .= "
						#".$id.":hover{
							background-color: ".esc_attr($btn_background_color_hover)." !important;
						}
					";
				}
				if( !empty($btn_border_color_hover) ){
					$styles .= "
						#".$id.":hover{
							border-color: ".esc_attr($btn_border_color_hover)." !important;
						}
					";
				}

			$styles .="
				}
			";
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$customize_align_landscape || 
		$custom_size_landscape 
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					#".$id."{
						".$vc_landscape_styles."
					}
				";
			}
			if( $customize_align_landscape ){
				$styles .= "
					#".$id_wrapper."{
						text-align: ".$aligning_landscape.";
					}
				";
			}
			if( $custom_size_landscape ){
				if( !empty($title_size_landscape) ){
					$styles .= "
						#".$id."{
							font-size: ".(int)esc_attr($title_size_landscape)."px;	
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$customize_align_portrait || 
		$custom_size_portrait 
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					#".$id."{
						".$vc_portrait_styles."
					}
				";
			}
			if( $customize_align_portrait ){
				$styles .= "
					#".$id_wrapper."{
						text-align: ".$aligning_portrait.";
					}
				";
			}
			if( $custom_size_portrait ){
				if( !empty($title_size_portrait) ){
					$styles .= "
						#".$id."{
							font-size: ".(int)esc_attr($title_size_portrait)."px;	
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$customize_align_mobile || 
		$custom_size_mobile 
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					#".$id."{
						".$vc_mobile_styles."
					}
				";
			}
			if( $customize_align_mobile ){
				$styles .= "
					#".$id_wrapper."{
						text-align: ".$aligning_mobile.";
					}
				";
			}
			if( $custom_size_mobile ){
				if( !empty($title_size_mobile) ){
					$styles .= "
						#".$id."{
							font-size: ".(int)esc_attr($title_size_mobile)."px;	
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */

    /* -----> Getting Icon <----- */
    if( !empty($icon_lib) ){
        if( $icon_lib == 'cws_svg' ){
            $icon = "icon_".$icon_lib;
            $svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
            $upload_dir = wp_upload_dir();
            $this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';

            $result .= '<span class="button-icon">';
            $result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
            $result .= file_get_contents($this_folder . $svg_icon['name']);
            $result .= "</i>";
            $result .= '</span>';
        } else {
            if( !empty($icon) ){
                $result .= "<span class='button-icon'>";
                $result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon")
                    ."'></i>";
                $result .= "</span>";
            }
        }
    }

	/* -----> Getting Button classes <----- */
    $classes .= ( $size ? ' '.esc_attr($size) : '');
    $classes .= ( $icon_pos && !empty($icon) ? ' icon-position-'.esc_attr($icon_pos) : '');
    if ( !empty($icon) ) {
        $icon = str_replace(' ', '_', $icon);
    }
    $classes .= ( $icon ? ' icon-face-'.esc_attr($icon) : '');


	/* -----> Button module output <----- */
	if( !empty($title) ){

		if( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		$out .= "<div id='".$id_wrapper."' class='cws_button_wrapper ".esc_attr($el_class)."'>";
			$out .= "<a id='".$id."' class='cws-custom-button".$classes."' href='".(!empty($url) ? $url : '#')."' ".($new_tab ? 'target="_blank"' : '').">";
                $out .= $result;
				$out .= esc_html($title);
                $out .= $result;
			$out .= "</a>";
		$out .= "</div>";
	}

	return $out;
}
add_shortcode( 'cws_sc_button', 'cws_vc_shortcode_sc_button' );

function cws_vc_shortcode_sc_dropcap ( $atts = array(), $content = "" ){
    extract( shortcode_atts( array(
        "dropcap_style"				=> "square",
        "dropcap_size"				=> "50",
        "dropcap_border"			=> "",
        "dropcap_fill"				=> "",
    ), $atts));
    $out = "";
    $size = "";
    $dropcap_style 			= esc_attr( $dropcap_style );
    $dropcap_size   		= esc_attr( $dropcap_size );
    $dropcap_border			= (bool)$dropcap_border;
    $dropcap_fill			= (bool)$dropcap_fill;

    $size .= "
        font-size: " . $dropcap_size . "px;
        line-height: " . $dropcap_size * 1.5 . "px;
        width: " . $dropcap_size * 1.5 . "px;
    ";

    //echo sprintf("%s", $style);
    return "<span class='dropcap" . ($dropcap_style ? ' ' . $dropcap_style : '') . ($dropcap_border ? ' dropcap-border' : '') . ($dropcap_fill ? ' dropcap-fill' : '') . "'" . (isset($dropcap_size) ? ' style="' . esc_attr($size) . '"' : '') . ">$content</span>";
}
add_shortcode( 'cws_sc_dropcap', 'cws_vc_shortcode_sc_dropcap' );

function cws_vc_shortcode_sc_mark ( $atts = array(), $content = "" ){
	$theme_color = esc_attr( cws_vc_shortcode_get_option( 'theme_color' ) );
	extract( shortcode_atts( array(
		'font_color'	=> '#fff',
		'bg_color'		=> $theme_color
	), $atts));
	return "<mark style='color: $font_color;background-color: $bg_color;'>$content</mark>";
}
add_shortcode( 'cws_sc_mark', 'cws_vc_shortcode_sc_mark' );

function cws_vc_shortcode_sc_embed ( $atts, $content ) {
	extract( shortcode_atts( array(
		'url' => '',
		'width' => '',
		'height' => ''
	), $atts));
	$url = esc_url( $url );
	return !empty( $url ) ? apply_filters( "the_content", "[embed" . ( !empty( $width ) && is_numeric( $width ) ? " width='$width'" : "" ) . ( !empty( $height ) && is_numeric( $height ) ? " height='$height'" : "" ) . "]" . $url . "[/embed]" ) : "";
}
add_shortcode( 'cws_sc_embed', 'cws_vc_shortcode_sc_embed' );

function cws_vc_shortcode_sc_progress_bar ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	extract( shortcode_atts( array(
		/* -----> GENERAL TAB <----- */
		'title'						=> 'Work`s done',
		'progress'					=> '65',
		'use_custom_color'			=> '',
		'custom_title_color' 		=> '#000',
		'custom_percents_color' 	=> '#000',
		'custom_fill_color'			=> $theme_first_color,
		'el_class'					=> ''
	), $atts));

	/* -----> Variables declaration <----- */
	$out = $styles = "";
	$id = uniqid( "cws-progress-bar-" );

	/* -----> Customize default styles <----- */
	if( $use_custom_color ){
		if( !empty($custom_title_color) ){
			$styles .= "
				#".$id." .progress-bar-title{
					color: ".esc_attr($custom_title_color).";
				}
			";
		}
		if( !empty($custom_percents_color) ){
			$styles .= "
				#".$id." .progress-bar-percents{
					color: ".esc_attr($custom_percents_color).";
				}
			";
		}
		if( !empty($custom_fill_color) ){
			$styles .= "
				#".$id." .progress-bar-wrapper{
					background-color: rgba(" . $cws_theme_funcs->cws_Hex2RGB($custom_fill_color) . ", .2);
				}
				#".$id." .progress-bar{
				    background-color: ".esc_attr($custom_fill_color).";
				}
			";
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Progress Bar module output <----- */
	$out .= "<div id='".$id."' class='cws-progress-bar-module ".( !empty($el_class) ? ''.$el_class : '' )."'>";

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		if( !empty($progress) ){
			$out .= "<div class='progress-bar-info-wrap'>";
		}
		if( !empty($title) ){
				$out .= "<p class='progress-bar-title'>".esc_html($title)."</p>";
		}
		if( !empty($progress) ){
				$out .= "<p class='progress-bar-percents'>".(int)esc_html($progress)."%</p>";
			$out .= "</div>";

			$out .= "<div class='progress-bar-wrapper'>";
				$out .= "<div class='progress-bar' style='width: 0%;' data-value='".(int)esc_attr($progress)."'></div>";
			$out .= "</div>";
		}
	$out .= "</div>";

	return $out;
}
add_shortcode( 'cws_sc_progress_bar', 'cws_vc_shortcode_sc_progress_bar' );

function cws_vc_shortcode_sc_milestone ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		"icon_lib"					=> "FontAwesome",
		"title"						=> "Enter title here...",
		"description"				=> "Enter description here...",
		"number"					=> "99",
		"superscript"				=> "+",
		"speed"						=> "2000",
		"add_divider"				=> false,
		"hide_divider_hover"		=> true,
		"divider_position"          => "under-title",
		"el_class"					=> "",
		/* -----> STYLING TAB <----- */
        "info_pos"				    => "middle",
		"custom_color"				=> true,
		"icon_color"				=> $theme_first_color,
		"icon_color_hover"			=> "",
		"number_color"				=> $theme_first_color,
		"number_color_hover"		=> "",
		"title_color"				=> "#000",
		"title_color_hover"			=> "",
		"description_color"			=> "#000",
		"description_color_hover"	=> "",
		"divider_color"	            => "",
		"bg_color_hover"	        => "",
 	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"			=> "",
			"customize_position"	=> false,
			"module_align"			=> "center",
			"custom_size"			=> false,
			"icon_size"				=> "60px",
			"title_size"			=> "14px",
			"description_size"		=> "14px",
			"number_size"			=> "45px",
			"superscript_size"		=> "30px",
            "title_paddings"		=> "30px 0px 0px 0px",
		),
	);

	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $result = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
	$icon = esc_attr( $icon );
	$id = uniqid( "cws-milestone-" );

	wp_enqueue_script( 'odometer' );

	/* -----> Extra icons <----- */
	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
		vc_icon_element_fonts_enqueue( $icon_lib );
	}

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles.";
			}
		";
	}
	if( $customize_position ){
		$styles .= "
			#".$id."{
				text-align: ".$module_align.";
			}
		";
	}
	if( $custom_size ){
		if( !empty($icon_size) ){
			$styles .= "
				#".$id." .icon,
				#".$id." .icon i:before{
					font-size: ".(int)esc_attr($icon_size)."px;
					line-height: ".(int)esc_attr($icon_size)."px;
				}
			";
		}
		if( !empty($title_size) || !empty($title_paddings) ){
			$styles .= "
				#".$id." .cws-milestone-title{
					".(!empty($title_size)?"font-size: ".(int)esc_attr($title_size)."px;":"")."
					".(!empty($title_paddings)?"margin: ".esc_attr($title_paddings)." !important;":"")."
				}
			";
		}
		if( !empty($description_size) ){
			$styles .= "
				#".$id." .cws-milestone-desc{
					font-size: ".(int)esc_attr($description_size)."px;
				}
			";
		}
		if( !empty($number_size) ){
			$styles .= "
				#".$id." .cws-milestone-number{
					font-size: ".(int)esc_attr($number_size)."px;
				}
			";
		}
        if( !empty($superscript_size) ){
            $styles .= "
				#".$id." .cws-milestone-number-wrapper .cws-milestone-sup{
					font-size: ".(int)esc_attr($superscript_size)."px;
				}
			";
        }
	}
	if( $custom_color ){
		if( !empty($icon_color) ){
			$styles .= "
				#".$id." .icon i:not(.svg){
					color: ".esc_attr($icon_color).";
				}
				#".$id." .icon i.svg{
					fill: ".esc_attr($icon_color).";
				}
			";
		}
		if( !empty($number_color) ){
			$styles .= "
				#".$id." .cws-milestone-number,
				#".$id." .cws-milestone-number-wrapper .cws-milestone-sup{
					color: ".esc_attr($number_color).";
				}
			";
		}
		if( !empty($title_color) ){
			$styles .= "
				#".$id." .cws-milestone-title{
					color: ".esc_attr($title_color).";
				}
			";
		}
		if( !empty($description_color) ){
			$styles .= "
				#".$id." .cws-milestone-desc{
					color: ".esc_attr($description_color).";
				}
			";
		}
        if( !empty($divider_color) ){
            $styles .= "
				#".$id." .cws_milestone_divider{
					background-color: ".esc_attr($divider_color).";
				}
			";
        }

        if( !empty($bg_color_hover) || !empty($icon_color_hover) || !empty($number_color_hover) || !empty($title_color_hover) || !empty($description_color_hover) ) {
            $styles .= "

				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

            if( !empty($icon_color_hover) ){
                $styles .= "
				#".$id.":hover .icon i:not(.svg){
					color: ".esc_attr($icon_color_hover).";
				}
				#".$id.":hover .icon i.svg{
					fill: ".esc_attr($icon_color_hover).";
				}
			";
            }
            if( !empty($number_color_hover) ){
                $styles .= "
				#".$id.":hover .cws-milestone-number,
				#".$id.":hover .cws-milestone-number-wrapper .cws-milestone-sup{
					color: ".esc_attr($number_color_hover).";
				}
			";
            }
            if( !empty($title_color_hover) ){
                $styles .= "
				#".$id.":hover .cws-milestone-title{
					color: ".esc_attr($title_color_hover).";
				}
			";
            }
            if( !empty($description_color_hover) ){
                $styles .= "
				#".$id.":hover .cws-milestone-desc{
					color: ".esc_attr($description_color_hover).";
				}
			";
            }
            if( !empty($bg_color_hover) ){
                $styles .= "
				#".$id.":hover{
					background-color: ".esc_attr($bg_color_hover).";
				}
			";
            }

        }
        if( !empty($bg_color_hover) || !empty($icon_color_hover) || !empty($number_color_hover) || !empty($title_color_hover) || !empty($description_color_hover) ) {
            $styles .= "
                }
			";
        }
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$customize_position_landscape || 
		$custom_size_landscape
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					#".$id."{
						".$vc_landscape_styles.";
					}
				";
			}
			if( $customize_position_landscape ){
				$styles .= "
					#".$id."{
						text-align: ".$module_align_landscape.";
					}
				";
			}
			if( $custom_size_landscape ){
				if( !empty($icon_size_landscape) ){
					$styles .= "
						#".$id." .icon,
						#".$id." .icon i:before{
							font-size: ".(int)esc_attr($icon_size_landscape)."px;
							line-height: ".(int)esc_attr($icon_size_landscape)."px;
						}
					";
				}
                if( !empty($title_size_landscape) || !empty($title_paddings_landscape) ){
                    $styles .= "
                        #".$id." .cws-milestone-title{
                            ".(!empty($title_size_landscape)?"font-size: ".(int)esc_attr($title_size_landscape)."px;":"")."
                            ".(!empty($title_paddings_landscape)?"margin: ".esc_attr($title_paddings_landscape)." !important;":"")."
                        }
                    ";
                }
				if( !empty($description_size_landscape) ){
					$styles .= "
						#".$id." .cws-milestone-desc{
							font-size: ".(int)esc_attr($description_size_landscape)."px;
						}
					";
				}
				if( !empty($number_size_landscape) ){
					$styles .= "
						#".$id." .cws-milestone-number{
							font-size: ".(int)esc_attr($number_size_landscape)."px;
						}
					";
				}
                if( !empty($superscript_size_landscape) ){
                    $styles .= "
						#".$id." .cws-milestone-number-wrapper .cws-milestone-sup{
							font-size: ".(int)esc_attr($superscript_size_landscape)."px;
						}
					";
                }
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$customize_position_portrait || 
		$custom_size_portrait
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					#".$id."{
						".$vc_portrait_styles.";
					}
				";
			}
			if( $customize_position_portrait ){
				$styles .= "
					#".$id."{
						text-align: ".$module_align_portrait.";
					}
				";
			}
			if( $custom_size_portrait ) {
                if (!empty($icon_size_portrait)) {
                    $styles .= "
						#" . $id . " .icon,
						#" . $id . " .icon i:before{
							font-size: " . (int)esc_attr($icon_size_portrait) . "px;
							line-height: " . (int)esc_attr($icon_size_portrait) . "px;
						}
					";
                }
                if( !empty($title_size_portrait) || !empty($title_paddings_portrait) ){
                    $styles .= "
                        #" . $id . " .cws-milestone-title {
                            " . (!empty($title_size_portrait) ? "font-size: " . (int)esc_attr($title_size_portrait) . "px;" : "") . "
                            " . (!empty($title_paddings_portrait) ? "margin: " . esc_attr($title_paddings_portrait) . " !important;" : "") . "
                        }
                    ";
                }

				if( !empty($description_size_portrait) ){
					$styles .= "
						#".$id." .cws-milestone-desc{
							font-size: ".(int)esc_attr($description_size_portrait)."px;
						}
					";
				}
				if( !empty($number_size_portrait) ){
					$styles .= "
						#".$id." .cws-milestone-number{
							font-size: ".(int)esc_attr($number_size_portrait)."px;
						}
					";
				}
				if( !empty($superscript_size_portrait) ){
					$styles .= "
						#".$id." .cws-milestone-number-wrapper .cws-milestone-sup{
							font-size: ".(int)esc_attr($superscript_size_portrait)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$customize_position_mobile || 
		$custom_size_mobile
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					#".$id."{
						".$vc_mobile_styles.";
					}
				";
			}
			if( $customize_position_mobile ){
				$styles .= "
					#".$id."{
						text-align: ".$module_align_mobile.";
					}
				";
			}
			if( $custom_size_mobile ){
				if( !empty($icon_size_mobile) ){
					$styles .= "
						#".$id." .icon,
						#".$id." .icon i:before{
							font-size: ".(int)esc_attr($icon_size_mobile)."px;
							line-height: ".(int)esc_attr($icon_size_mobile)."px;
						}
					";
				}
                if( !empty($title_size_mobile) || !empty($title_paddings_mobile) ){
                    $styles .= "
                        #" . $id . " .cws-milestone-title {
                            " . (!empty($title_size_mobile) ? "font-size: " . (int)esc_attr($title_size_mobile) . "px;" : "") . "
                            " . (!empty($title_paddings_mobile) ? "margin: " . esc_attr($title_paddings_mobile) . " !important;" : "") . "
                        }
                    ";
                }
				if( !empty($description_size_mobile) ){
					$styles .= "
						#".$id." .cws-milestone-desc{
							font-size: ".(int)esc_attr($description_size_mobile)."px;
						}
					";
				}
				if( !empty($number_size_mobile) ){
					$styles .= "
						#".$id." .cws-milestone-number{
							font-size: ".(int)esc_attr($number_size_mobile)."px;
						}
					";
				}
				if( !empty($superscript_size_mobile) ){
					$styles .= "
						#".$id." .cws-milestone-number-wrapper .cws-milestone-sup{
							font-size: ".(int)esc_attr($superscript_size_mobile)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */

	/* -----> Getting Icon <----- */
	if( !empty($icon_lib) ){
		if( $icon_lib == 'cws_svg' ){
			$icon = "icon_".$icon_lib;
			$svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
			$upload_dir = wp_upload_dir();
			$this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';	

			$result .= '<span class="icon">';
				$result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
					$result .= file_get_contents($this_folder . $svg_icon['name']);
				$result .= "</i>";
			$result .= '</span>';
		} else {
			if( !empty($icon) ){
				$result .= "<span class='icon'>";
					$result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon") ."'></i>";
				$result .= "</span>";
			}
		}
	}

    /* -----> Getting Classes <----- */
    $classes = '';
    if( !empty($add_divider) ) {
        $classes .= " divider-" . esc_attr($divider_position);
    }
    if( !empty($add_divider) && !empty($hide_divider_hover) ) {
        $classes .= " hide-divider-hover";
    }

	/* -----> Milestone module output <----- */
	$out .= "<div id='".$id."' class='cws-milestone-module". (!empty($classes) ? $classes : '') .( !empty($el_class) ? ' '.esc_attr($el_class) : '' ) ."'>";

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		$out .= "<div class='cws-milestone-main'>";
			$out .= "<div class='cws-milestone-wrapper'>";

			    if ( (!empty($title) || !empty($description)) && !empty($customize_position) && $info_pos == 'top' ) {
			        $out .= "<div class='cws-milestone-info'>";
			        if( !empty($title) ){
			            $out .= "<div class='cws-milestone-title'>".esc_html($title)."</div>";
			        }
			        if( !empty($description) ){
			            $out .= "<div class='cws-milestone-desc'>".esc_html($description)."</div>";
			        }
			        $out .= "</div>";
                }



				if( !empty($result) ){
					$out .= "<div class='cws-milestone-icon-wrapper'>";
						$out .= $result;
					$out .= "</div>";
				}

                if ( (!empty($title) || !empty($description)) && !empty($customize_position) && $info_pos == 'middle' ) {
                    $out .= "<div class='cws-milestone-info'>";
                    if( !empty($title) ){
                        $out .= "<div class='cws-milestone-title'>".esc_html($title)."</div>";
                    }
                    if( !empty($description) ){
                        $out .= "<div class='cws-milestone-desc'>".esc_html($description)."</div>";
                    }
                    $out .= "</div>";
                }


				if( !empty($number) ){
                    $out .= "<div class='cws-milestone-number-wrapper'>";
                        $out .= "<div class='cws-milestone-number' ".( !empty($speed) && is_numeric($speed) ? 'data-speed="'.esc_attr($speed).'"' : '' ).">";
                            $out .= $number;
                        $out .= "</div>";
                        $out .= (!empty($superscript) ? '<div class="cws-milestone-sup">' . esc_html($superscript) . '</div>' : '');
                    $out .= "</div>";
				}

                if ( (!empty($title) || !empty($description)) && (!empty($customize_position) && $info_pos == 'bottom' || empty($customize_position)) ) {
                    $out .= "<div class='cws-milestone-info'>";
                    if( !empty($title) ){
                        $out .= "<div class='cws-milestone-title'>".esc_html($title)."</div>";
                    }
                    if( !empty($description) ){
                        $out .= "<div class='cws-milestone-desc'>".esc_html($description)."</div>";
                    }
                    $out .= "</div>";
                }



			$out .= "</div>";
		$out .= "</div>";

	$out .= "</div>";

	return $out;
}
add_shortcode( 'cws_sc_milestone', 'cws_vc_shortcode_sc_milestone' );

function cws_vc_shortcode_sc_services ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
    $body_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
        "service_type"              => "default",
		"first_child"               => false,
		"last_child"                => false,
		"icon_type"                 => "iconic",
		"plan_img"                  => "",
		"icon_lib"					=> "FontAwesome",
		"icon_shape"				=> "hexagon",
		"divider"					=> "none",
		"link"						=> "",
		"divider_width"			    => "21px",
		"divider_height_side"		=> "80%",
		"new_tab"					=> false,
		"add_button"                => false,
		"extra_button_url"          => "",
		"extra_button_title"        => "",
		"extra_button_style"        => "simple",
		"title"						=> "Enter title here...",
        "description"				=> "Enter description here...",
		"add_counter"               => false,
		"counter_value"             => "01",
		"el_class"					=> "",
		/* -----> STYLING TAB <----- */
		"customize_color"			=> true,
		"icon_color"				=> $theme_first_color,
		"icon_color_hover"			=> "",
		"icon_bg_color"				=> "",
		"icon_bg_color_hover"		=> "",
		"icon_bd_color"				=> "",
		"icon_bd_color_hover"		=> "",
		"title_color"				=> $body_color,
		"title_color_hover"			=> "",
		"desc_color"				=> $body_color,
		"desc_color_hover"			=> "",
		"bg_color_hover"			=> "",
		"shadow_color"				=> "",
		"divider_color"				=> "#ffe27a",
		"divider_color_hover"		=> "",
		"button_color"				=> "#ffffff",
		"button_color_hover"		=> $theme_first_color,
		"button_bg_color"			=> $theme_first_color,
		"button_bg_color_hover"		=> "#ffffff",
		"button_bd_color"			=> $theme_first_color,
		"button_bd_color_hover"		=> $theme_first_color,
		"disable_shadow"			=> true,
	);
	$responsive_vars = array(
		/* -----> RESPONSIVE TABS <----- */
 		"all" => array(
 			"custom_styles"			=> "",
 			"customize_position"	=> false,
 			"module_align"			=> "left",
 			"icon_pos"				=> "top",
 			"customize_size"		=> false,
 			"icon_size"				=> "51",
 			"icon_spacing"			=> "0",
 			"title_size"			=> "20",
 			"desc_size"				=> "16",
 			"icon_paddings"		    => "",
 			"title_paddings"		=> "0px 0px 0px 0px",
 			"desc_paddings"			=> "0px 0px 0px 0px",
 			"button_paddings"		=> "20px 0px 0px 0px",
 			"hide_divider"			=> false,
 		),
	);

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $result = $image_result = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
	$icon = esc_attr( $icon );
	$id = uniqid( "cws-service-" );

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles);
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			.".$id."{
				".$vc_desktop_styles.";
			}
		";
	}
	if( !empty($divider_width) && $divider == 'bottom' ){
		$styles .= "
			.".$id." .service-info-wrapper .service-divider{
				width: ".esc_attr($divider_width).";
			}
		";
	}
	if( !empty($divider_height_side) && $divider == 'right' ){
		$styles .= "
			.".$id." .side-divider{
				top: ".((100 - (int)esc_attr($divider_height_side)) / 2)."%;
				height: ".(int)esc_attr($divider_height_side)."%;
			}
		";
	}
	if( $customize_position ){
		$styles .= "
			.".$id."{
				text-align: ".$module_align.";
			}
		";
		if( $icon_pos != "top" && $module_align == "center" ){
			$styles .= "
				.".$id."{
					-webkit-justify-content: center !important;
					-moz-justify-content: center !important;
					-ms-justify-content: center !important;
					justify-content: center !important;
				}
			";
		} else if( $icon_pos == "top" && $module_align == "center" ){
			$styles .= "
				.".$id."{
					-webkit-align-items: center !important;
					-moz-align-items: center !important;
					-ms-align-items: center !important;
					align-items: center !important;
				}
			";
		}
	}
	if( $customize_size ){
		if( !empty($icon_size) ){
			$styles .= "
				.".$id." .icon,
				.".$id." .icon i:before{
					font-size: ".(int)esc_attr($icon_size)."px !important;
					line-height: ".(int)esc_attr($icon_size)."px !important;
				}
			";
		}

		if ($icon_pos == 'corner') {
            if( !empty($icon_size) ) {
                $styles .= "
                    ." . $id . " .service-icon-wrapper:before{
                        font-size: " . ((int)esc_attr($icon_size)*5.16  + 4) . "px;
                    }
                    ." . $id . " .service-icon-wrapper:after{
                        font-size: " . ((int)esc_attr($icon_size)*5.16  - 1) . "px;
                    }
                ";
            }
		} else {
		    if( !empty($icon_spacing) && !empty($icon_size) ) {
		        $styles .= "
                    ." . $id . " .service-icon-wrapper{
                        padding: " . (int)esc_attr($icon_spacing) . "px;
                    }
                    ." . $id . " .service-icon-wrapper:before{
                        font-size: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2) + 4) . "px;
                    }
                    ." . $id . " .service-icon-wrapper:after{
                        font-size: " . ((int)esc_attr($icon_size) + ((int)esc_attr($icon_spacing) * 2) - 1) . "px;
                    }
                ";
		    }
		}
		if( !empty($title_size) ){
			$styles .= "
				.".$id." .service-title{
					font-size: ".(int)esc_attr($title_size)."px !important;
				}
			";
		}
		if( !empty($desc_size) ){
			$styles .= "
				.".$id." .service-description{
					font-size: ".(int)esc_attr($desc_size)."px !important;
				}
			";
		}
		if( !empty($icon_paddings) ){
			$styles .= "
				.".$id." .service-icon-wrapper{
					margin: ".esc_attr($icon_paddings)." !important;
				}
			";
		}
        if( !empty($title_paddings) ){
            $styles .= "
				.".$id." .service-title{
					margin: ".esc_attr($title_paddings)." !important;
				}
			";
        }
		if( !empty($desc_paddings) ){
			$styles .= "
				.".$id." .service-description{
					margin: ".esc_attr($desc_paddings)." !important;
				}
			";
		}
        if( !empty($button_paddings) ){
            $styles .= "
				.".$id." .service-button-wrapper{
					margin: ".esc_attr($button_paddings)." !important;
				}
			";
        }
	}
	if( $customize_color ){
		if( !empty($icon_color) ){
			$styles .= "
				.".$id." .icon i:not(.svg){
					color: ".esc_attr($icon_color).";
				}
				.".$id." .icon i.svg{
					fill: ".esc_attr($icon_color).";
				}
			";
		}
        if( !empty($icon_bg_color) ){
            $styles .= "
				.".$id." .service-icon-wrapper:after{
				    ".($icon_shape == "hexagon" ? "color: ".esc_attr($icon_bg_color) : "background-color: " .esc_attr($icon_bg_color))." !important;
				}
			";
        }
        if( !empty($icon_bd_color) ){
            if ($icon_shape=='hexagon') {
                $styles .= "
                    .".$id." .service-icon-wrapper:before{
                        color: ".esc_attr($icon_bd_color)." !important;
                    }
                ";
            } else {
                $styles .= "
                    .".$id." .service-icon-wrapper:after{
                        border-color: ".esc_attr($icon_bd_color)." !important;
                    }
                ";
            }
        }

		if( !empty($divider_color) ){
			$styles .= "
				.".$id." .side-divider{
					background-color: ".esc_attr($divider_color).";
				}
				.".$id." .service-divider{
					background-color: ".esc_attr($divider_color).";
				}
			";
		}
		if( !empty($title_color) ){
			$styles .= "
				.".$id." .service-title{
					color: ".esc_attr($title_color).";
				}
			";
		}
		if( !empty($desc_color) ){
			$styles .= "
				.".$id." .service-description{
					color: ".esc_attr($desc_color).";
				}
			";
		}


        if( !empty($button_color) || !empty($button_bg_color) || !empty($button_bd_color) ){
            $styles .= "
				.".$id." .service-info-wrapper .service-button-wrapper .service-button{
					".(!empty($button_color) ? "color: " .esc_attr($button_color).";" : "")."
					".(!empty($button_bg_color) ? "background-color: " .esc_attr($button_bg_color).";" : "")."
					".(!empty($button_bd_color) ? "border-color: " .esc_attr($button_bd_color).";" : "")."
				}
			";
        }





        if( !empty($bg_color_hover) ){
            $styles .= "
                    .".$id.".hovered,
                    .".$id.".service-type-card.hovered .service-info-wrapper{
						background-color: ".esc_attr($bg_color_hover)." !important;
					}
			    ";
        }

        if( !empty($icon_color_hover) ){
            $styles .= "
                    .".$id.".hovered .icon i:not(.svg){
                        color: ".esc_attr($icon_color_hover).";
                    }
                    .".$id.".hovered .icon i.svg{
                        fill: ".esc_attr($icon_color_hover).";
                    }
			    ";
        }

        if( !empty($icon_bg_color_hover) ){
            $styles .= "
                    .".$id.".hovered .service-icon-wrapper:after{
				        ".($icon_shape == "hexagon" ? "color: ".esc_attr($icon_bg_color_hover) : "background-color: ".esc_attr($icon_bg_color_hover))." !important;
                    }
                ";
        }

        if( !empty($icon_bd_color_hover) ){
            if ($icon_shape=='hexagon') {
                $styles .= "
                        .".$id.".hovered .service-icon-wrapper:before{
                            color: ".esc_attr($icon_bd_color_hover)." !important;
                        }
                    ";
            } else {
                $styles .= "
                        .".$id.".hovered .service-icon-wrapper:after{
                            border-color: ".esc_attr($icon_bd_color_hover)." !important;
                        }
                    ";
            }
        }

        if( !empty($title_color_hover) ){
            $styles .= "
				.".$id.".hovered .service-title{
					color: ".esc_attr($title_color_hover).";
				}
			";
        }
        if( !empty($desc_color_hover) ){
            $styles .= "
				.".$id.".hovered .service-description{
					color: ".esc_attr($desc_color_hover).";
				}
			";
        }
        if( !empty($divider_color_hover) ){
            $styles .= "
				.".$id.".hovered .side-divider{
					background-color: ".esc_attr($divider_color_hover).";
				}
				.".$id.".hovered .service-divider{
					background-color: ".esc_attr($divider_color_hover).";
				}
			";
        }

        if( !empty($shadow_color) ){
            $styles .= "
                    .".$id.":not(.disable-shadow).hovered{
						-webkit-box-shadow: 0 20px 60px 0 ".esc_attr($shadow_color)." !important;
						   -moz-box-shadow: 0 20px 60px 0 ".esc_attr($shadow_color)." !important;
								box-shadow: 0 20px 60px 0 ".esc_attr($shadow_color)." !important;
					}
			    ";
        }

        if( !empty($button_color_hover) || !empty($button_bg_color_hover) || !empty($button_bd_color_hover) ){
            if ($service_type == 'gallery') {
                $styles .= "
                        .".$id." .service-info-wrapper .service-button-wrapper .service-button:hover,
                        .".$id." .service-info-wrapper .service-button-wrapper .service-button.hovered{
                            ".(!empty($button_color_hover) ? "color: " .esc_attr($button_color_hover).";" : "")."
                            ".(!empty($button_bg_color_hover) ? "background-color: " .esc_attr($button_bg_color_hover).";" : "")."
                            ".(!empty($button_bd_color_hover) ? "border-color: " .esc_attr($button_bd_color_hover).";" : "")."
                        }
                    ";
            } else {
                $styles .= "
                        .".$id.".hovered .service-info-wrapper .service-button-wrapper .service-button{
                            ".(!empty($button_color_hover) ? "color: " .esc_attr($button_color_hover).";" : "")."
                            ".(!empty($button_bg_color_hover) ? "background-color: " .esc_attr($button_bg_color_hover).";" : "")."
                            ".(!empty($button_bd_color_hover) ? "border-color: " .esc_attr($button_bd_color_hover).";" : "")."
                        }
                        .".$id.".hovered .service-info-wrapper .service-button-wrapper .service-button:after{
                            ".(!empty($button_bg_color_hover) ? "background-color: " .esc_attr($button_bg_color_hover).";" : "")."
                        }
                    ";
            }
        }





		if( !empty($bg_color_hover) || !empty($shadow_color) || !empty($icon_color_hover) ||
            !empty($icon_bg_color_hover) || !empty($icon_bd_color_hover) || !empty($button_color) ||
            !empty($button_bg_color) || !empty($button_bd_color) || !empty($title_color_hover) ||
            !empty($descr_color_hover) ){
			$styles .= "

				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

            if( !empty($bg_color_hover) ){
                $styles .= "
                    .".$id.":hover,
                    .".$id.".service-type-card:hover .service-info-wrapper
                    {
						background-color: ".esc_attr($bg_color_hover)." !important;
					}
			    ";
            }

            if( !empty($icon_color_hover) ){
                $styles .= "
                    .".$id.":hover .icon i:not(.svg)
                    {
                        color: ".esc_attr($icon_color_hover).";
                    }
                    .".$id.":hover .icon i.svg
                    {
                        fill: ".esc_attr($icon_color_hover).";
                    }
			    ";
            }

            if( !empty($icon_bg_color_hover) ){
                $styles .= "
                    .".$id.":hover .service-icon-wrapper:after
                    {
				        ".($icon_shape == "hexagon" ? "color: ".esc_attr($icon_bg_color_hover) : "background-color: ".esc_attr($icon_bg_color_hover))." !important;
                    }
                ";
            }

            if( !empty($icon_bd_color_hover) ){
                if ($icon_shape=='hexagon') {
                    $styles .= "
                        .".$id.":hover .service-icon-wrapper:before
                        {
                            color: ".esc_attr($icon_bd_color_hover)." !important;
                        }
                    ";
                } else {
                    $styles .= "
                        .".$id.":hover .service-icon-wrapper:after
                        {
                            border-color: ".esc_attr($icon_bd_color_hover)." !important;
                        }
                    ";
                }
            }

            if( !empty($title_color_hover) ){
                $styles .= "
				.".$id.":hover .service-title
				{
					color: ".esc_attr($title_color_hover).";
				}
			";
            }
            if( !empty($desc_color_hover) ){
                $styles .= "
				.".$id.":hover .service-description
				{
					color: ".esc_attr($desc_color_hover).";
				}
			";
            }
            if( !empty($divider_color_hover) ){
                $styles .= "
				.".$id.":hover .side-divider
				{
					background-color: ".esc_attr($divider_color_hover).";
				}
				.".$id.":hover .service-divider
				{
					background-color: ".esc_attr($divider_color_hover).";
				}
			";
            }

            if( !empty($shadow_color) ){
                $styles .= "
                    .".$id.":not(.disable-shadow):hover
                    {
						-webkit-box-shadow: 0 20px 60px 0 ".esc_attr($shadow_color)." !important;
						   -moz-box-shadow: 0 20px 60px 0 ".esc_attr($shadow_color)." !important;
								box-shadow: 0 20px 60px 0 ".esc_attr($shadow_color)." !important;
					}
			    ";
            }

            if( !empty($button_color_hover) || !empty($button_bg_color_hover) || !empty($button_bd_color_hover) ){
                if ($service_type == 'gallery') {
                    $styles .= "
                        .".$id." .service-info-wrapper .service-button-wrapper .service-button:hover
                        {
                            ".(!empty($button_color_hover) ? "color: " .esc_attr($button_color_hover).";" : "")."
                            ".(!empty($button_bg_color_hover) ? "background-color: " .esc_attr($button_bg_color_hover).";" : "")."
                            ".(!empty($button_bd_color_hover) ? "border-color: " .esc_attr($button_bd_color_hover).";" : "")."
                        }
                    ";
                } else {
                    $styles .= "
                        .".$id.":hover .service-info-wrapper .service-button-wrapper .service-button
                        {
                            ".(!empty($button_color_hover) ? "color: " .esc_attr($button_color_hover).";" : "")."
                            ".(!empty($button_bg_color_hover) ? "background-color: " .esc_attr($button_bg_color_hover).";" : "")."
                            ".(!empty($button_bd_color_hover) ? "border-color: " .esc_attr($button_bd_color_hover).";" : "")."
                        }
                    ";
                }
            }

            $styles .= "
            }
            ";
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$customize_position_landscape || 
		$customize_size
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					.".$id."{
						".$vc_landscape_styles.";
					}
				";
			}
			if( $customize_position_landscape ){
				$styles .= "
					.".$id."{
						text-align: ".$module_align_landscape.";
					}
				";
				if( $icon_pos_landscape != "top" && $module_align_landscape == "center" ){
					$styles .= "
						.".$id."{
							-webkit-justify-content: center !important;
							-moz-justify-content: center !important;
							-ms-justify-content: center !important;
							justify-content: center !important;
						}
					";
				} else if( $icon_pos_landscape == "top" && $module_align_landscape == "center" ){
					$styles .= "
						.".$id."{
							-webkit-align-items: center !important;
							-moz-align-items: center !important;
							-ms-align-items: center !important;
							align-items: center !important;
						}
					";
				}
			}
			if( $customize_size_landscape ){
                if( !empty($icon_size_landscape) ){
                    $styles .= "
                        .".$id." .icon,
                        .".$id." .icon i:before{
                            font-size: ".(int)esc_attr($icon_size_landscape)."px !important;
                            line-height: ".(int)esc_attr($icon_size_landscape)."px !important;
                        }
                    ";
                }

                if ($icon_pos_landscape == 'corner') {
                    if( !empty($icon_size_landscape) ) {
                        $styles .= "
                            ." . $id . " .service-icon-wrapper:before{
                                font-size: " . ((int)esc_attr($icon_size_landscape)*5.16  + 4) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:after{
                                font-size: " . ((int)esc_attr($icon_size_landscape)*5.16  - 1) . "px;
                            }
                        ";
                    }
                } else {
                    if( !empty($icon_spacing_landscape) && !empty($icon_size_landscape) ) {
                        $styles .= "
                            ." . $id . " .service-icon-wrapper{
                                padding: " . (int)esc_attr($icon_spacing_landscape) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:before{
                                font-size: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2) + 4) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:after{
                                font-size: " . ((int)esc_attr($icon_size_landscape) + ((int)esc_attr($icon_spacing_landscape) * 2) - 1) . "px;
                            }
                        ";
                    }
                }
				if( !empty($title_size_landscape) ){
					$styles .= "
						.".$id." .service-title{
							font-size: ".(int)esc_attr($title_size_landscape)."px !important;
						}
					";
				}
				if( !empty($desc_size_landscape) ){
					$styles .= "
						.".$id." .service-description{
							font-size: ".(int)esc_attr($desc_size_landscape)."px !important;
						}
					";
				}
				if( !empty($icon_paddings_landscape) ){
					$styles .= "
						.".$id." .service-icon-wrapper{
							margin: ".esc_attr($icon_paddings_landscape)." !important;
						}
					";
				}
				if( !empty($title_paddings_landscape) ){
					$styles .= "
						.".$id." .service-title{
							margin: ".esc_attr($title_paddings_landscape)." !important;
						}
					";
				}
				if( !empty($desc_paddings_landscape) ){
					$styles .= "
						.".$id." .service-description{
							margin: ".esc_attr($desc_paddings_landscape)." !important;
						}
					";
				}
                if( !empty($button_paddings_landscape) ){
                    $styles .= "
						.".$id." .service-button-wrapper{
							margin: ".esc_attr($button_paddings_landscape)." !important;
						}
					";
                }
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$customize_position_portrait || 
		$customize_size
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					.".$id."{
						".$vc_portrait_styles.";
					}
				";
			}
			if( $customize_position_portrait ){
				$styles .= "
					.".$id."{
						text-align: ".$module_align_portrait.";
					}
				";
				if( $icon_pos_portrait != "top" && $module_align_portrait == "center" ){
					$styles .= "
						.".$id."{
							-webkit-justify-content: center !important;
							-moz-justify-content: center !important;
							-ms-justify-content: center !important;
							justify-content: center !important;
						}
					";
				} else if( $icon_pos_portrait == "top" && $module_align_portrait == "center" ){
					$styles .= "
						.".$id."{
							-webkit-align-items: center !important;
							-moz-align-items: center !important;
							-ms-align-items: center !important;
							align-items: center !important;
						}
					";
				}
			}
			if( $customize_size_portrait ){
                if( !empty($icon_size_portrait) ){
                    $styles .= "
                        .".$id." .icon,
                        .".$id." .icon i:before{
                            font-size: ".(int)esc_attr($icon_size_portrait)."px !important;
                            line-height: ".(int)esc_attr($icon_size_portrait)."px !important;
                        }
                    ";
                }

                if ($icon_pos_portrait == 'corner') {
                    if( !empty($icon_size_portrait) ) {
                        $styles .= "
                            ." . $id . " .service-icon-wrapper:before{
                                font-size: " . ((int)esc_attr($icon_size_portrait)*5.16  + 4) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:after{
                                font-size: " . ((int)esc_attr($icon_size_portrait)*5.16  - 1) . "px;
                            }
                        ";
                    }
                } else {
                    if( !empty($icon_spacing_portrait) && !empty($icon_size_portrait) ) {
                        $styles .= "
                            ." . $id . " .service-icon-wrapper{
                                padding: " . (int)esc_attr($icon_spacing_portrait) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:before{
                                font-size: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2) + 4) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:after{
                                font-size: " . ((int)esc_attr($icon_size_portrait) + ((int)esc_attr($icon_spacing_portrait) * 2) - 1) . "px;
                            }
                        ";
                    }
                }
				if( !empty($title_size_portrait) ){
					$styles .= "
						.".$id." .service-title{
							font-size: ".(int)esc_attr($title_size_portrait)."px !important;
						}
					";
				}
				if( !empty($desc_size_portrait) ){
					$styles .= "
						.".$id." .service-description{
							font-size: ".(int)esc_attr($desc_size_portrait)."px !important;
						}
					";
				}
				if( !empty($icon_paddings_portrait) ){
					$styles .= "
						.".$id." .service-icon-wrapper{
							margin: ".esc_attr($icon_paddings_portrait)." !important;
						}
					";
				}
				if( !empty($title_paddings_portrait) ){
					$styles .= "
						.".$id." .service-title{
							margin: ".esc_attr($title_paddings_portrait)." !important;
						}
					";
				}
				if( !empty($desc_paddings_portrait) ){
					$styles .= "
						.".$id." .service-description{
							margin: ".esc_attr($desc_paddings_portrait)." !important;
						}
					";
				}
                if( !empty($button_paddings_portrait) ){
                    $styles .= "
						.".$id." .service-button-wrapper{
							margin: ".esc_attr($button_paddings_portrait)." !important;
						}
					";
                }
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$customize_position_mobile || 
		$customize_size
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

            if( !empty($bg_color_hover) && $service_type == 'gallery' ){
                $styles .= "
                        .".$id." .service-info-wrapper {
                            background-color: ".esc_attr($bg_color_hover)." !important;
                        }
                    ";
            }

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					.".$id."{
						".$vc_mobile_styles.";
					}
				";
			}
			if( $customize_position_mobile ){
				$styles .= "
					.".$id."{
						text-align: ".$module_align_mobile.";
					}
				";
				if( $icon_pos_mobile != "top" && $module_align_mobile == "center" ){
					$styles .= "
						.".$id."{
							-webkit-justify-content: center !important;
							-moz-justify-content: center !important;
							-ms-justify-content: center !important;
							justify-content: center !important;
						}
					";
				} else if( $icon_pos_mobile == "top" && $module_align_mobile == "center" ){
					$styles .= "
						.".$id."{
							-webkit-align-items: center !important;
							-moz-align-items: center !important;
							-ms-align-items: center !important;
							align-items: center !important;
						}
					";
				}
			}
			if( $customize_size_mobile ){
                if( !empty($icon_size_mobile) ){
                    $styles .= "
                        .".$id." .icon,
                        .".$id." .icon i:before{
                            font-size: ".(int)esc_attr($icon_size_mobile)."px !important;
                            line-height: ".(int)esc_attr($icon_size_mobile)."px !important;
                        }
                    ";
                }

                if ($icon_pos_mobile == 'corner') {
                    if( !empty($icon_size_mobile) ) {
                        $styles .= "
                            ." . $id . " .service-icon-wrapper:before{
                                font-size: " . ((int)esc_attr($icon_size_mobile)*5.16  + 4) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:after{
                                font-size: " . ((int)esc_attr($icon_size_mobile)*5.16  - 1) . "px;
                            }
                        ";
                    }
                } else {
                    if( !empty($icon_spacing_mobile) && !empty($icon_size_mobile) ) {
                        $styles .= "
                            ." . $id . " .service-icon-wrapper{
                                padding: " . (int)esc_attr($icon_spacing_mobile) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:before{
                                font-size: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2) + 4) . "px;
                            }
                            ." . $id . " .service-icon-wrapper:after{
                                font-size: " . ((int)esc_attr($icon_size_mobile) + ((int)esc_attr($icon_spacing_mobile) * 2) - 1) . "px;
                            }
                        ";
                    }
                }
				if( !empty($title_size_mobile) ){
					$styles .= "
						.".$id." .service-title{
							font-size: ".(int)esc_attr($title_size_mobile)."px !important;
						}
					";
				}
				if( !empty($desc_size_mobile) ){
					$styles .= "
						.".$id." .service-description{
							font-size: ".(int)esc_attr($desc_size_mobile)."px !important;
						}
					";
				}
				if( !empty($icon_paddings_mobile) ){
					$styles .= "
						.".$id." .service-icon-wrapper{
							margin: ".esc_attr($icon_paddings_mobile)." !important;
						}
					";
				}
				if( !empty($title_paddings_mobile) ){
					$styles .= "
						.".$id." .service-title{
							margin: ".esc_attr($title_paddings_mobile)." !important;
						}
					";
				}
				if( !empty($desc_paddings_mobile) ){
					$styles .= "
						.".$id." .service-description{
							margin: ".esc_attr($desc_paddings_mobile)." !important;
						}
					";
				}
                if( !empty($button_paddings_mobile) ){
                    $styles .= "
						.".$id." .service-button-wrapper{
							margin: ".esc_attr($button_paddings_mobile)." !important;
						}
					";
                }
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */

    if (
        !empty($bg_color_hover) && $service_type == 'card'
    ){
        $styles .= "
			@media screen and (max-width: 479px){
		";

        $styles .= "
            .".$id.".service-type-card.slick-active .service-info-wrapper{
                background-color: ".esc_attr($bg_color_hover)." !important;
            }
        ";

        $styles .= "
			}
		";
    }

	/* -----> Getting Icon <----- */
	if( !empty($icon_lib) ){
        $result .= "<div class='service-icon-wrapper'>";
            $result .= "<span class='service-media-element'></span>";
            if( $icon_lib == 'cws_svg' ){
                $icon = "icon_".$icon_lib;
                $svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
                $upload_dir = wp_upload_dir();
                $this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';

                $result .= '<span class="icon">';
                    $result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
                        $result .= file_get_contents($this_folder . $svg_icon['name']);
                    $result .= "</i>";
                $result .= '</span>';
            } else {
                if( !empty($icon) ){
                    $result .= "<span class='icon'>";
                        $result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon") ."'></i>";
                    $result .= "</span>";
                }
            }
        $result .= "</div>";
	}

    /* -----> Getting Image <----- */
    if(!empty($plan_img)){
        $plan_img_data = wp_get_attachment_image_src( $plan_img, 'full' );
        if ( is_array( $plan_img_data ) ){
            $plan_img_src = !empty( $plan_img_data ) ? $plan_img_data[0] : '';
            if ($service_type == 'gallery') {
                $src_img = cws_print_img_html(array('src' => $plan_img_src), array( 'height' => 1000, 'width' => 1000, 'crop' => true ) );
            } else {
                $src_img = cws_print_img_html(array('src' => $plan_img_src), array( 'height' => 840, 'width' => 1110, 'crop' => true ) );
            }

            $image_result .=  "<span class='service-image-wrapper'>";
                $image_result .= "<span class='service-media-element'></span>";
                $image_result .=  "<img " . $src_img . " alt='' />";
            $image_result .=  "</span>";
        }
    }

	$module_classes = '';
	if( $service_type ){
		$module_classes .= " service-type-".esc_attr($service_type);
	}
	if ( $service_type == 'gallery' ) {
	    if ( !empty($first_child) ) {
            $module_classes .= " first-child";
        }
        if ( !empty($last_child) ) {
            $module_classes .= " last-child";
        }
    }
	if( $divider != 'none' ){
		$module_classes .= " divider-".$divider;
	}
	if( $disable_shadow ){
		$module_classes .= " disable-shadow";
	}
	if( $customize_position ){
		$module_classes .= " icon-".$icon_pos;
	}
	if( $customize_position_landscape ){
		$module_classes .= " landscape-icon-".$icon_pos_landscape;
	}
	if( $customize_position_portrait ){
		$module_classes .= " portrait-icon-".$icon_pos_portrait;
	}
	if( $customize_position_mobile ){
		$module_classes .= " mobile-icon-".$icon_pos_mobile;
	}
	if( $hide_divider ){
		$module_classes .= " hide-divider";
	}
	if( $hide_divider_landscape ){
		$module_classes .= " landscape-hide-divider";
	}
	if( $hide_divider_portrait ){
		$module_classes .= " portrait-hide-divider";
	}
	if( $hide_divider_mobile ){
		$module_classes .= " mobile-hide-divider";
	}
    if( $icon_shape ){
        $module_classes .= " icon-shape-".esc_attr($icon_shape);
    }
	if( !empty($el_class) ){
		$module_classes .= " ".esc_attr($el_class);
	}

	if( !empty($link) ){
		$start_tag = "<a href='".esc_url($link)."' ".( $new_tab ? 'target="_blank"' : '' )."";
		$end_tag = '</a>';
	} else {
		$start_tag = "<div";
		$end_tag = "</div>";
	}

	/* -----> Services module output <----- */
	$out .= $start_tag." class='".$id." cws-service-module".$module_classes."'>"; //ID in class, coz slick-slider rewrite ID.

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		if( $divider == 'right' ){
			$out .= "<span class='side-divider'></span>";
		}

		if ($icon_type == 'iconic') {
            if( !empty($result) ){
                $out .= $result;
            }
        } else if ($icon_type == 'image') {
            if( !empty($image_result) ){
                $out .= $image_result;
            }
        }

		if( !empty($title) || !empty($description) ){
			$out .= "<div class='service-info-wrapper'>";
                $out .= "<div class='service-info-inner'>";
                    if( !empty($add_counter) && !empty($counter_value) ){
                        $out .= "<div class='service-counter'>";
                        $out .= esc_html($counter_value);
                        $out .= "</div>";
                    }
                    if( !empty($title) ){
                        $out .= "<h6 class='service-title'>".esc_html($title)."</h6>";
                    }
                    if( $divider == 'bottom' || $divider == 'full' ){
                        $out .= "<div class='service-divider'></div>";
                    }
                    if( !empty($description) ){
                        $description = wp_kses( $description, array(
                            "b"			=> array(),
                            "strong"	=> array(),
                            "mark"		=> array(),
                            "br"		=> array()
                        ));
                        $out .= "<div class='service-description'>".$description."</div>";
                    }
                    if( !empty($add_button) ){
                        $out .= "<div class='service-button-wrapper'>";
                        if( !empty($extra_button_url) || $extra_button_title ){
                            $out .= "<a href='".esc_attr($extra_button_url)."' class='service-button ".esc_attr($extra_button_style)."'>".esc_html($extra_button_title)."</a>";
                        }
                        $out .= "</div>";
                    }
                $out .= "</div>";
			$out .= "</div>";
		}

	$out .= $end_tag;

	return $out;
}
add_shortcode( 'cws_sc_services', 'cws_vc_shortcode_sc_services' );

/******************** TESTIMONIAL ********************/

function cws_vc_shortcode_testimonial_renderer( $atts ) {
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'testimonials_style'	=> 'style-1',
		'values'				=> '',
		'item_grid'				=> '1',
		'use_carousel'			=> true,
		'carousel_infinite'     => false,
		'autoplay'				=> false,
		'autoplay_speed'		=> '3000',
		'pause_on_hover'        => false,
		'el_class'				=> '',
		/* -----> STYLING TAB <----- */
		'aligning'				=> 'left',
		'custom_color'			=> true,
		'name_color'			=> '#000',
		'pos_color'				=> $theme_first_color,
		'quote_color'			=> '#474747',
		'dots_color'			=> '#D5D5D5',
		'dots_active_color'		=> $theme_first_color,
		'dots_active_border'	=> $theme_second_color,
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> "",
		),
	);
	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$id = uniqid( "cws-testimonial-" );

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($custom_styles) ){
        if( !empty($vc_desktop_styles) ){
            $styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
        }
	}
	if( $custom_color ){
		if( !empty($name_color) ){
			$styles .= "
				#".$id." .testimonial-author-name{
					color: ".esc_attr($name_color).";
				}
				#".$id." .testimonial_divider:before{
					background-color: ".esc_attr($name_color).";
				}
			";
		}
		if( !empty($pos_color) ){
			$styles .= "
				#".$id." .testimonial-author-pos{
					color: ".esc_attr($pos_color).";
				}
			";
		}
		if( !empty($quote_color) ){
			$styles .= "
				#".$id." .testimonial-quote{
					color: ".esc_attr($quote_color).";
				}
			";
		}
		if( !empty($dots_color) ){
			$styles .= "
				#".$id." .slick-dots li button:before{
					background-color: ".esc_attr($dots_color).";
				}
			";
		}
		if( !empty($dots_active_color) ){
			$styles .= "
				#".$id." .slick-dots li.slick-active button:before{
					background-color: ".esc_attr($dots_active_color).";
				}
			";
		}
		if( !empty($dots_active_border) ){
			$styles .= "
				#".$id." .slick-dots li.slick-active button:after{
					border-color: ".esc_attr($dots_active_border).";
				}
			";
		}
	}
	/* -----> End of default styles <----- */

    /* -----> Customize landscape styles <----- */
    if( !empty($vc_landscape_styles) ){
        $styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

        if( !empty($vc_landscape_styles) ){
            $styles .= "
					#".$id."{
						".$vc_landscape_styles.";
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of landscape styles <----- */

    /* -----> Customize portrait styles <----- */
    if( !empty($vc_portrait_styles) ){
        $styles .= "
			@media screen and (max-width: 991px){
		";

        if( !empty($vc_portrait_styles) ){
            $styles .= "
					#".$id."{
						".$vc_portrait_styles.";
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of portrait styles <----- */

    /* -----> Customize mobile styles <----- */
    if( !empty($vc_mobile_styles) ){
        $styles .= "
			@media screen and (max-width: 767px){
		";

        if( !empty($vc_mobile_styles) ){
            $styles .= "
					#".$id."{
						".$vc_mobile_styles.";
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of mobile styles <----- */

	/* -----> Parse values array <----- */
	$values = (array)vc_param_group_parse_atts($values);

	$testimonials_data = array();
	foreach ( $values as $data ) {
		$new_data = $data;

        if( isset($data['thumbnail_size']) && !empty($data['thumbnail_size']) ){
            $new_data['thumbnail_size'] = esc_attr($data['thumbnail_size']);
        } else {
            $new_data['thumbnail_size'] = 'full';
        }
		if( isset($data['thumbnail']) && !empty($data['thumbnail']) ){
			$img = wp_get_attachment_image_src( $data['thumbnail'], $new_data['thumbnail_size'] );
			$new_data['thumbnail'] = $img[0];
		}
		if( isset($data['quote']) && !empty($data['quote']) ){
			$new_data['quote'] = esc_html($data['quote']);
		}
		if( isset($data['author_name']) && !empty($data['author_name']) ){
			$new_data['author_name'] = esc_html($data['author_name']);
		}
		if( isset($data['author_pos']) && !empty($data['author_pos']) ){
			$new_data['author_pos'] = esc_html($data['author_pos']);
		}
		$new_data['show_rating'] = (isset($data['show_rating']) && !empty($data['show_rating'])) ? true : false;
		
		if( isset($data['testimonial_rating']) && !empty($data['testimonial_rating']) ){
            $new_data['testimonial_rating'] = esc_html($data['testimonial_rating']);
		}


		$testimonials_data[] = $new_data;
	}

	$module_attr = '';
	$module_classes = ' align-'.$aligning;
	if( $use_carousel ){
		wp_enqueue_script( 'slick-carousel' );

		$module_classes .= ' cws-carousel-wrapper';

		$module_attr = " data-columns='".$item_grid."'";
		$module_attr .= " data-pagination='on'";
        $module_attr .= " data-navigation='".( $testimonials_style != 'style-1' ? 'on' : 'off' )."'";
		$module_attr .= " data-auto-height='on'";
		$module_attr .= " data-draggable='on'";
		$module_attr .= " data-autoplay='".( $autoplay ? 'on' : 'off' )."'";
		$module_attr .= " data-autoplay-speed='".esc_attr($autoplay_speed)."'";
//		$module_attr .= " data-pause-on-hover='on'";
		$module_attr .= " data-mobile-landscape='1'";
		$module_attr .= " data-tablet-portrait='".( $testimonials_style == 'style-1' && $item_grid != '1' ? '2' : '1' )."'";
		if ( ((int)$item_grid == 3 || (int)$item_grid == 5) && $testimonials_style == 'style-1' ) {
            $module_attr .= " data-center-mode='on'";
            $module_attr .= " data-infinite='on'";
        } else {
            if( $carousel_infinite ){
                $module_attr .= ' data-infinite="on"';
            }
        }
        if( $autoplay && $pause_on_hover ){
            $module_attr .= ' data-pause-on-hover="on"';
        }
	}
	if( $item_grid != '1' && !$use_carousel ){
		$module_classes .= ' columns-'.$item_grid;
	}
	if( !empty($el_class) ){
		$module_classes .= " ".esc_attr($el_class);
	}

	/* -----> Testimonial module output <----- */
	$out .= "<div id='".$id."' class='cws-testimonial-module ".$testimonials_style . $module_classes."' "
        .$module_attr.">";

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		$out .= "<div class='cws-testimonial-list".( $use_carousel ? ' cws-carousel' : '' )."'>";
			foreach ($testimonials_data as $testimonial) {
				$out .= "<div class='cws-testimonial-item'>";
					if(
                        !empty($testimonial['thumbnail']) ||
						!empty($testimonial['quote']) || 
						!empty($testimonial['author_name']) || 
						!empty($testimonial['author_pos']) 
					){
						$out .= "<div class='testimonial-info-wrapper'>";

						    if ($testimonials_style == 'style-3') {
						        $out .= '<div class="testimonial-header">';
                            }

                                if( !empty($testimonial['thumbnail']) ){
                                    $out .= "<div class='testimonial-img'>";
                                        $out .= "<img src=".$testimonial['thumbnail']." alt='testimonial' />";
                                    $out .= "</div>";
                                }
                                if ($testimonials_style == 'style-3') {
                                    $out .= '<div class="testimonial-info">';
                                }
                                    if( !empty($testimonial['author_name']) ){
                                        $out .= "<div class='testimonial-author-name'>".$testimonial['author_name']."</div>";
                                    }
                                    if( !empty($testimonial['author_pos']) ){
                                        $out .= "<div class='testimonial-author-pos'>".$testimonial['author_pos']."</div>";
                                    }
                                if ($testimonials_style == 'style-3') {
                                    $out .= '</div>';
                                }
                            if ($testimonials_style == 'style-3') {
                                $out .= '</div>';
                            }
							if( !empty($testimonial['quote']) ){
								$out .= "<div class='testimonial-quote'>".$testimonial['quote']."</div>";
							}
                            if ( !empty($testimonial['testimonial_rating']) && $testimonial['show_rating'] ) {
                                $out .= "<div class='testimonial-rating'>";
                                switch ( $testimonial['testimonial_rating'] ) {
                                    case 0:
                                        $out .= "<i class='fas fa-star default'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i>";
                                        break;
                                    case 1:
                                        $out .= "<i class='fas fa-star active'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i>";
                                        break;
                                    case 2:
                                        $out .= "<i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i>";
                                        break;
                                    case 3:
                                        $out .= "<i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star default'></i><i class='fas fa-star default'></i>";
                                        break;
                                    case 4:
                                        $out .= "<i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star default'></i>";
                                        break;
                                    case 5:
                                        $out .= "<i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star active'></i><i class='fas fa-star active'></i>";
                                        break;
                                    default:
                                        $out .= "";
                                }
                                $out .= "</div>";
                            }
						$out .= "</div>";
					}
				$out .= "</div>";
			}
		$out .= "</div>";


	$out .= "</div>";

	return $out;
}

function cws_vc_shortcode_quote_renderer( $atts ) {
	extract( shortcode_atts( array(
		'thumbnail'		=> null,
		'quote'			=> '',
		'author_name'	=> '',
		'author_status'	=> ''
	), $atts));
	$quote        	= esc_html( $quote );
	$author_name 	= esc_html( $author_name );
	$author_status	= esc_html( $author_status );
	ob_start();
	$author_section = $quote_section = '';


	if(!empty($thumbnail)){
		$thumbnail = has_post_thumbnail( ) ? wp_get_attachment_image_src( get_post_thumbnail_id( ),'full' ) : '';
		$thumbnail = $thumbnail[0];
	}


	$quote_section_class = "quote";
	$quote_section_atts = '';
	$quote_section_atts .= !empty( $quote_section_class ) ? " class='" . trim( $quote_section_class ) . "'" : '';

	if ( !empty( $quote ) ){
		$quote_section .= "<div" . ( !empty( $quote_section_atts ) ? $quote_section_atts : "" ) . ">";

			$quote_section .= "<div class='content-quote'>$quote</div>";			
			if ( !empty( $author_name ) || !empty( $author_status ) ){
				if(!empty($author_name)){
					$arr = explode(' ',trim($author_name));
					$arr_all = str_replace($arr[0], "", $author_name);
				}
				$quote_section .= "<div class='author_info_box-quote'>";
					
					$quote_section .= !empty( $author_status ) ? "<span class='author_status author_info'>" . esc_html( $author_status ) . "</span>" : "";
					$quote_section .= !empty( $author_name ) ? "<p class='author_name author_info'> - "."<span>".$arr[0]."</span>". esc_html( $arr_all ) . "</p>" : "";
				$quote_section .= "</div>";
			}
			$quote_section .= "<div class='quote_bg_c'></div>";
		$quote_section .= "</div>";
		if(!empty($thumbnail)){
		$quote_section .= '<div class="quote_bg" style="background-image: url('.esc_attr($thumbnail).');background-position: center center;"></div>';
		}

	}

	if(!empty($url)){
		$quote_section .= "<div class='link-testimonials'>";
		$quote_section .= "<a class='testimonial-button' href='".$url."'>".esc_html__('Read more', 'cws-essentials')."</a>";
		$quote_section .= "</div>";
	}

	?>
	<div class="cws_vc_shortcode_module clearfix <?php echo $thumbnail ? '' : 'without_image'; echo !empty( $el_class ) ? " $el_class" : ""; ?>">
		<?php
		if ( !empty( $thumbnail ) ) {
			echo $author_section . $quote_section;
		}
		else{
			echo $quote_section;
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}

/******************** \TESTIMONIAL ********************/


function cws_vc_shortcode_sc_vc_testimonial ( $atts = array(), $content = "" ){
	$atts['thumbnail'] = isset( $atts['thumbnail'] ) && !empty( $atts['thumbnail'] ) ? wp_get_attachment_url( $atts['thumbnail'] ) : "";
	return  function_exists( 'cws_vc_shortcode_testimonial_renderer' ) ? cws_vc_shortcode_testimonial_renderer( $atts, $content ) : '';
}
add_shortcode( 'cws_sc_vc_testimonial', 'cws_vc_shortcode_sc_vc_testimonial' );
function cws_vc_shortcode_sc_testimonial ( $atts = array(), $content = "" ){
	if ( !empty( $atts['thumbnail'] ) ){
		$thumbnail_data = json_decode( $atts['thumbnail'], true );
		$atts['thumbnail'] = ( isset( $thumbnail_data['@'] ) && isset( $thumbnail_data['@']['src'] ) ) ? $thumbnail_data['@']['src'] : "";
	}
	return function_exists( 'cws_vc_shortcode_testimonial_renderer' ) ? cws_vc_shortcode_testimonial_renderer( $atts, $content ) : '';
}
add_shortcode( 'cws_sc_testimonial', 'cws_vc_shortcode_sc_testimonial' );

function cws_vc_shortcode_sc_pricing_plan ( $atts = array(), $content = "" ){
	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'title'						=> 'Basic',
		'price'						=> '',
		'currency'					=> '$',
		'price_desc'				=> '/mo',
        'icon_lib'					=> 'fontawesome',
        'add_button'				=> false,
		'button_title'				=> 'Buy Now',
		'button_url'				=> '#',
        'button_new_tab'			=> false,
        'values'					=> '',
		'highlighted'				=> false,
		'el_class'					=> '',
		/* -----> STYLING TAB <----- */
		'add_border'				=> false,
		'border_color'				=> '#dedede',
		'bg_image'					=> '',
        'accent_color_light'        => '#ffe27a',
        'accent_color_dark'         => '#9d5f36'
 	);

    $responsive_vars = array(
        "all" => array()
    );

    $responsive_defaults = add_responsive_suffix($responsive_vars);
    $defaults = array_merge($defaults, $responsive_defaults);

    $proc_atts = shortcode_atts( $defaults, $atts );
    extract( $proc_atts );

    /* -----> Extra icons <----- */
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
        vc_icon_element_fonts_enqueue( $icon_lib );
    }

	/* -----> Variables declaration <----- */
    $out = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
 	$id = uniqid( "cws-pricing-plan-" );
    $icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
    $icon = esc_attr( $icon );
    $content = apply_filters( "the_content", $content );

    /* -----> Visual Composer Responsive styles <----- */
    list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

    preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles);
    $vc_desktop_styles = implode($vc_desktop_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
    $vc_landscape_styles = implode($vc_landscape_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
    $vc_portrait_styles = implode($vc_portrait_styles);

    preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
    $vc_mobile_styles = implode($vc_mobile_styles);

	$module_classes = '';
	$module_classes .= ( $highlighted ? ' highlighted' : '' );
	$module_classes .= ( $add_border ? ' bordered' : '' );
	$module_classes .= ( !empty($el_class) ? ' '.esc_attr($el_class) : '' );

    /* -----> Customize default styles <----- */
    if( !empty($accent_color_light) ){
        $styles .= "
            #".$id.".highlighted .pricing-icon,
            #".$id.".highlighted .pricing-row-info:before,
            #".$id.".highlighted .pricing-additional-text a
            {
               color: ".esc_attr($accent_color_light).";
            }
            #".$id.".highlighted .pricing-plan-buttons .more-button
            {
               background-color: ".esc_attr($accent_color_light).";
               border-color: ".esc_attr($accent_color_light).";
            }
        ";
    }
    if( !empty($accent_color_dark) ){
        $styles .= "
            #".$id.".highlighted .pricing-plan-buttons .more-button
            {
               color: ".esc_attr($accent_color_dark).";
            }
        ";
    }

    if( !empty($vc_desktop_styles) ){
        $styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
    }
    if( $add_border ){
        if( !empty($border_color) ){
            $styles .= "
				#".$id.":after{
					background-color: ".esc_attr($border_color).";
				}
			";
        }
    }
    if( !empty($bg_image) ){
        $img = wp_get_attachment_image_src( $bg_image, 'full' );

        $styles .= "
			#".$id."{
				background-image: url(".esc_attr($img[0]).");
			}
		";
    }
    /* -----> End of default styles <----- */

    /* -----> Customize landscape styles <----- */
    if(
        !empty($vc_landscape_styles)
    ){
        $styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

        if( !empty($vc_landscape_styles) ){
            $styles .= "
					#".$id."{
						".$vc_landscape_styles."
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of landscape styles <----- */

    /* -----> Customize portrait styles <----- */
    if(
        !empty($vc_portrait_styles)
    ){
        $styles .= "
			@media screen and (max-width: 991px){
		";

        if( !empty($vc_portrait_styles) ){
            $styles .= "
					#".$id."{
						".$vc_portrait_styles."
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of portrait styles <----- */

    /* -----> Customize mobile styles <----- */
    if(
        !empty($vc_mobile_styles)
    ){
        $styles .= "
			@media screen and (max-width: 767px){
		";

        if( !empty($vc_mobile_styles) ){
            $styles .= "
					#".$id."{
						".$vc_mobile_styles."
					}
				";
        }

        $styles .= "
			}
		";
    }
    /* -----> End of mobile styles <----- */

    /* -----> Getting Icon <----- */
    if( !empty($icon_lib) ){
        if( $icon_lib == 'cws_svg' ){
            $icon = "icon_".$icon_lib;
            $svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
            $upload_dir = wp_upload_dir();
            $this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';

            $result = '<span class="pricing-icon">';
            $result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
            $result .= file_get_contents($this_folder . $svg_icon['name']);
            $result .= "</i>";
            $result .= '</span>';
        } else {
            if( !empty($icon) ){
                $result = "<span class='pricing-icon'>";
                $result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon")
                    ."'></i>";
                $result .= "</span>";
            }
        }
    }

    /* -----> Pricing Plan content output <----- */
    $values = (array) vc_param_group_parse_atts( $values );
    $item_data = array();
    foreach ( $values as $data ) {
        $new_data = $data;
        $new_data['text'] = isset( $data['text'] ) ? $data['text'] : '';
        $item_data[] = $new_data;
    }

	/* -----> Pricing Plan module output <----- */
	if( !empty($title) || !empty($price) ){

		if( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		$out .= "<div id='".$id."' class='cws-pricing-module".$module_classes."'>";

		    if( !empty($result) || !empty($title) ){
		        $out .= "<div class='pricing-header'>";
		            $out .= "<div class='pricing-title-wrapper'>";
		            if ( !empty( $result ) ){
		                $out .= $result;
		            }
		            if( !empty($title) ){
		                $out .= "<span class='pricing-title'>" . esc_html($title) . "</span>";
		            }
		            $out .= "</div>";
		        $out .= "</div>";
		    }

            $out .= "<div class='pricing-wrapper'>";

                if( !empty($currency) || !empty($price) || !empty($price_desc) ){
                    $out .= '<div class="pricing-price-wrapper">';
                        if( !empty($currency) ){
                            $out .= "<sup>".esc_html($currency)."</sup>";
                        }
                        if( !empty($price) ){
                            $out .= "<span class='pricing-price'>".esc_html($price)."</span>";
                        }
                        if( !empty($price_desc) ){
                            $out .= "<span class='pricing-price-desc'>".esc_html($price_desc)."</span>";
                        }
                    $out .= "</div>";
                }

                if( !empty($item_data) ){
                    $out .= "<div class='pricing-content'>";
                    foreach ( $item_data as $item_d ) {

                        if (!empty($item_d['text'])) {
                            $out .= "<div class='pricing-row-info'>".$item_d['text']."</div>";
                        }
                    }
                    $out .= "</div>";
                }

                if( $add_button && !empty($button_title) ){
                    $out .= "<div class='pricing-plan-buttons'>";
                        $out .= "<a href='" . (!empty($button_url) ? esc_url($button_url) : '#') . "' " . ($button_new_tab ? 'target="_blank"' : '' ) . " class='more-button'>" . (!empty($button_title) ? $button_title : '') . "</a>";
                    $out .= "</div>";
                }

            $out .= "</div>";

            if( !empty($content) ){
                $out .= "<div class='pricing-additional-text'>";
                $out .= $content;
                $out .= "</div>";
            }

		$out .= "</div>";
	}

	return $out;
}
add_shortcode( 'cws_sc_pricing_plan', 'cws_vc_shortcode_sc_pricing_plan' );

function cws_vc_shortcode_sc_banners ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
        "banner_style"                  => "style-1",
		"title"							=> "Enter title here",
		"description"					=> "Enter description here",
		"banner_url"					=> "#",
		"add_divider"					=> true,
		"new_tab"						=> true,
		"add_button"					=> false,
		"button_title"					=> "Click Me!",
		"button_position"				=> "default",
        "button_size"		            => "small",
        "icon_lib"					    => "FontAwesome",
        "icon_pos"					    => "right",
		"el_class"						=> "",
		/* -----> STYLING TAB <----- */
		"bg_overlay"					=> false,
		"customize_colors"				=> true,
		"title_color"					=> "#fff",
		"divider_color"					=> $theme_first_color,
		"description_color"				=> "rgba(255,255,255, .6)",
		"btn_font_color"				=> "#fff",
		"btn_background_color"			=> $theme_first_color,
		"btn_border_color"				=> $theme_first_color,
		"btn_font_color_hover"			=> $theme_first_color,
		"btn_background_color_hover"	=> "#fff",
		"btn_border_color_hover"		=> $theme_first_color,
        "btn_icon_color"				=> '#fff',
        "btn_icon_color_hover"		    => $theme_first_color,
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> "",
			"customize_align"	=> false,
			"aligning"			=> "left",
			"customize_size"	=> "",
			"title_size"		=> "30px",
		),
	);
	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class =
        "";
	$id = uniqid( "cws-banner-" );
    $icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
    $icon = esc_attr( $icon );
	$title = wp_kses( $title, array(
		"b"			=> array(),
		"strong"	=> array(),
		"mark"		=> array(),
		"br"		=> array()
	));
	$description = wp_kses( $description, array(
		"b"			=> array(),
		"strong"	=> array(),
		"mark"		=> array(),
		"br"		=> array()
	));

    /* -----> Extra icons <----- */
    if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
        vc_icon_element_fonts_enqueue( $icon_lib );
    }
	
	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);
	$vc_desktop_styles .= "
		background-position: ".(!empty($custom_bg_position) ? $custom_bg_position : $bg_position )." !important;
		background-size: ".(!empty($custom_bg_size) ? $custom_bg_size : $bg_size )." !important;
		background-repeat: ".$bg_repeat." !important;
		". ($bg_display == '1' ? 'background-image: none !important;' : '') ."
	";

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);
	$vc_landscape_styles .= "
		background-position: ".(!empty($custom_bg_position_landscape) ? $custom_bg_position_landscape : $bg_position_landscape )." !important;
		background-size: ".(!empty($custom_bg_size_landscape) ? $custom_bg_size_landscape : $bg_size_landscape )." !important;
		background-repeat: ".$bg_repeat_landscape." !important;
		". ($bg_display_landscape == '1' ? 'background-image: none !important;' : '') ."
	";

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);
	$vc_portrait_styles .= "
		background-position: ".(!empty($custom_bg_position_portrait) ? $custom_bg_position_portrait : $bg_position_portrait )." !important;
		background-size: ".(!empty($custom_bg_size_portrait) ? $custom_bg_size_portrait : $bg_size_portrait )." !important;
		background-repeat: ".$bg_repeat_portrait." !important;
		". ($bg_display_portrait == '1' ? 'background-image: none !important;' : '') ."
	";

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);
	$vc_mobile_styles .= "
		background-position: ".(!empty($custom_bg_position_mobile) ? $custom_bg_position_mobile : $bg_position_mobile
        )." !important;
		background-size: ".(!empty($custom_bg_size_mobile) ? $custom_bg_size_mobile : $bg_size_mobile )." !important;
		background-repeat: ".$bg_repeat_mobile.";
		". ($bg_display_mobile == '1' ? 'background-image: none !important;' : '') ."
	";

	/* -----> Customize default styles <----- */
	if( $customize_align ){
		$styles .= "
			#".$id."{
				text-align: ".$aligning.";
			}
		";
	}
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $customize_size ){
		if( !empty($title_size) ){
			$styles .= "
				#".$id." h5.banner-title{
					font-size: ".(int)esc_attr($title_size)."px !important;	
				}
			";
		}
	}
	if( $customize_colors ){
		if( !empty($title_color) ){
			$styles .= "
				#".$id." h5.banner-title{
					color: ".esc_attr($title_color).";	
				}
			";
		}
		if( !empty($divider_color) ){
			$styles .= "
				#".$id." .banner-divider{
					background-color: ".esc_attr($divider_color).";	
				}
			";
		}
		if( !empty($description_color) ){
			$styles .= "
				#".$id." .banner-description{
					color: ".esc_attr($description_color).";	
				}
			";
		}
		if( !empty($btn_font_color) ){
			$styles .= "
				#".$id." .banner-button-wrapper a{
					color: ".esc_attr($btn_font_color).";	
				}
			";
		}
        if( !empty($btn_icon_color) ){
            $styles .= "
				#".$id." .banner-button-wrapper a .button-icon{
					color: ".esc_attr($btn_icon_color).";
				}
				#".$id." .banner-button-wrapper a .svg{
					fill: ".esc_attr($btn_icon_color).";
				}
			";
        }
		if( !empty($btn_background_color) ){
			$styles .= "
				#".$id." .banner-button-wrapper a{
					background-color: ".esc_attr($btn_background_color).";	
				}
			";
		}
		if( !empty($btn_border_color) ){
			$styles .= "
				#".$id." .banner-button-wrapper a{
					border-color: ".esc_attr($btn_border_color).";	
				}
			";
		}
		if(
			!empty($btn_font_color_hover) ||
			!empty($btn_background_color_hover) ||
			!empty($btn_border_color_hover) ||
            !empty($btn_icon_color_hover)
		) {
			$styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

				if( !empty($btn_font_color_hover) ){
					$styles .= "
						#".$id." .banner-button-wrapper a:hover{
							color: ".esc_attr($btn_font_color_hover).";	
						}
					";
				}
                if( !empty($btn_icon_color_hover) ){
                    $styles .= "
                            #".$id." .banner-button-wrapper a:hover .button-icon{
                                color: ".esc_attr($btn_icon_color_hover).";
                            }
                            #".$id." .banner-button-wrapper a:hover .svg{
                                fill: ".esc_attr($btn_icon_color_hover).";
                            }
                        ";
                }
				if( !empty($btn_background_color_hover) ){
					$styles .= "
						#".$id." .banner-button-wrapper a:hover{
							background-color: ".esc_attr($btn_background_color_hover).";	
						}
					";
				}
				if( !empty($btn_border_color_hover) ){
					$styles .= "
						#".$id." .banner-button-wrapper a:hover{
							border-color: ".esc_attr($btn_border_color_hover).";	
						}
					";
				}

			$styles .="
				}
			";
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$customize_align_landscape || 
		$customize_size_landscape 
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					#".$id."{
						".$vc_landscape_styles.";
					}
				";
			}
			if( $customize_align_landscape ){
				$styles .= "
					#".$id."{
						text-align: ".$aligning_landscape.";
					}
				";
			}
			if( $customize_size_landscape ){
				if( $title_size_landscape ){
					$styles .= "
						#".$id." h5.banner-title{
							font-size: ".(int)esc_attr($title_size_landscape)."px !important;	
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$customize_align_portrait || 
		$customize_size_portrait 
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					#".$id."{
						".$vc_portrait_styles.";
					}
				";
			}
			if( $customize_align_portrait ){
				$styles .= "
					#".$id."{
						text-align: ".$aligning_portrait.";
					}
				";
			}
			if( $customize_size_portrait ){
				if( $title_size_portrait ){
					$styles .= "
						#".$id." h5.banner-title{
							font-size: ".(int)esc_attr($title_size_portrait)."px !important;	
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$customize_align_mobile || 
		$customize_size_mobile 
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					#".$id."{
						".$vc_mobile_styles.";
					}
				";
			}
			if( $customize_align_mobile ){
				$styles .= "
					#".$id."{
						text-align: ".$aligning_mobile.";
					}
				";
			}
			if( $customize_size_mobile ){
				if( $title_size_mobile ){
					$styles .= "
						#".$id." h5.banner-title{
							font-size: ".(int)esc_attr($title_size_mobile)."px !important;	
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */

	$module_classes = "";
	$button_class = "";

	if( isset($button_position) ){
		$module_classes .= " button-".$button_position."";
	}
	if( $bg_overlay ){
		$module_classes .= " color-overlay";
	}
	if( !empty($el_class) ){
		$module_classes .= " ".esc_attr($el_class);
	}
	$module_classes .= ' ' . esc_attr($banner_style);

    /* -----> Getting Icon <----- */
    $result = '';
    if( !empty($icon_lib) ){
        if( $icon_lib == 'cws_svg' ){
            $icon = "icon_".$icon_lib;
            $svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
            $upload_dir = wp_upload_dir();
            $this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';

            $result .= '<span class="button-icon">';
            $result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
            $result .= file_get_contents($this_folder . $svg_icon['name']);
            $result .= "</i>";
            $result .= '</span>';
        } else {
            if( !empty($icon) ){
                $result .= "<span class='button-icon'>";
                $result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon")
                    ."'></i>";
                $result .= "</span>";
            }
        }
    }

    if( isset($button_size) ){
        $button_class .= " ".$button_size;
    }
    if ( !empty($icon) ) {
        $icon = str_replace(' ', '_', $icon);
        $button_class .= " icon-position-".esc_attr($icon_pos);
        $button_class .= " icon-face-".esc_attr($icon);
    }

	/* -----> Banner module output <----- */
	if( !empty($title) || !empty($description) || !empty($banner_url) ){

		if( !empty($banner_url) && !$add_button ){
			$start_tag = "<a href='".esc_url($banner_url)."'".($new_tab ? " target='_blank'" : '');
			$end_tag = "</a>";
		} else {
			$start_tag = "<div";
			$end_tag = "</div>";
		}

		$out .= $start_tag." id='".$id."' class='cws-banner-module".$module_classes."'>";

			if( !empty($styles) ){
				Cws_shortcode_css()->enqueue_cws_css($styles);
			}

			$out .= ($banner_style == 'style-1' ? "<div class='banner-info-wrapper'>" : "");
			$out .= ($banner_style == 'style-2' ? "<div class='banner-title-wrapper'>" : "");
				if( !empty($title) ){
					$out .= "<h5 class='banner-title'>".$title."</h5>";
				}
				if( $add_divider ){
					$out .= "<span class='banner-divider'></span>";
				}
            $out .= ($banner_style == 'style-2' ? "</div>" : "");
            $out .= ($banner_style == 'style-2' ? "<div class='banner-content-wrapper'>" : "");
				if( !empty($description) ){
					$out .= "<div class='banner-description'>".$description."</div>";
				}
            $out .= ($banner_style == 'style-1' ? "</div>" : "");

			if( $add_button && !empty($button_title) && !empty($banner_url) ){
				$out .= "<div class='banner-button-wrapper'>";
					$out .= "<a class='cws-custom-button".$button_class."' href='".esc_url($banner_url)."'". ($new_tab ? " target='_blank'" : '').">";
                        if ( !empty( $result ) ){
                            $out .= $result;
                        }
					    $out .= esc_html($button_title);
                        if ( !empty( $result ) ){
                            $out .= $result;
                        }
					$out .= "</a>";
				$out .= "</div>";
			}
            $out .= ($banner_style == 'style-2' ? "</div>" : "");

		$out .= $end_tag;
	}

	return $out;
}
add_shortcode( 'cws_sc_banners', 'cws_vc_shortcode_sc_banners' );

function cws_vc_shortcode_benefits ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;
	
	$defaults = array(
		/* -----> GENERAL TAB <----- */
		"icon_lib"					=> "FontAwesome",
		"type"						=> "simple",
		"title"						=> "Enter title here...",
		"description"				=> "Enter description here...",
		"add_button"				=> true,
		"button_title"				=> "Read More",
		"button_url"				=> "#",
		"highlighted"				=> false,
		"el_class"					=> "",
		/* -----> STYLING TAB <----- */
		"custom_color"				=> true,
		"icon_color"				=> "#208de2",
		"icon_background"			=> "#fff",
		"title_color"				=> "#fff",
		"active_title_color"		=> "#000",
		"divider_color"				=> "#208de2",
		"text_color"				=> "#474747",
		"button_color"				=> "#fff",
		"button_bg_color"			=> "#208de2",
		"hover_button_color"		=> "#208de2",
		"hover_button_bg_color"		=> "#fff",
		"shadow_color"				=> "rgba(12,81,172,0.35)",
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> "",
			"spacing"			=> "100"
		)
	);
	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $result = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
	$icon = esc_attr( $icon );
	$id = uniqid( "cws_benefits_" );

	$title = wp_kses( $title, array(
		"b"			=> array(),
		"strong"	=> array(),
		"mark"		=> array(),
		"br"		=> array()
	));
	$description = wp_kses( $description, array(
		"b"			=> array(),
		"strong"	=> array(),
		"mark"		=> array(),
		"br"		=> array()
	));

	/* -----> Extra icons <----- */
	if ( function_exists( 'vc_icon_element_fonts_enqueue' ) ){
		vc_icon_element_fonts_enqueue( $icon_lib );
	}

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( !empty($spacing) ){
		$styles .= "
			#".$id." > *:not(.visible-title){
				padding: ".((int)esc_attr($spacing) / 2)."px 0px;
			}
		";
	}
	if( $custom_color ){
		if( !empty($icon_color) ){
			$styles .= "
				#".$id." .benefits-icon .icon{
					color: ".esc_attr($icon_color).";
				}
				#".$id." .svg-border path{
					stroke: ".esc_attr($icon_color)." !important;
				}
			";
		}
		if( !empty($icon_background) ){
			$styles .= "
				#".$id." .benefits-icon .icon:before{
					background-color: ".esc_attr($icon_background).";
				}
			";
		}
		if( !empty($title_color) ){
			$styles .= "
				#".$id." .visible-title{
					color: ".esc_attr($title_color).";
				}
			";
		}
		if( !empty($active_title_color) ){
			$styles .= "
				#".$id." .benefits-title{
					color: ".esc_attr($active_title_color).";
				}
			";
		}
		if( !empty($divider_color) ){
			$styles .= "
				#".$id." .benefits-info .benefits-description:before{
					background-color: ".esc_attr($divider_color).";
				}
			";
		}
		if( !empty($text_color) ){
			$styles .= "
				#".$id." .benefits-info .benefits-description{
					color: ".esc_attr($text_color).";
				}
			";
		}
		if( !empty($button_color) ){
			$styles .= "
				#".$id." .cws-custom-button{
					color: ".esc_attr($button_color).";
				}
			";
		}
		if( !empty($button_bg_color) ){
			$styles .= "
				#".$id." .cws-custom-button{
					background-color: ".esc_attr($button_bg_color).";
					border-color: ".esc_attr($button_bg_color).";
				}
			";
		}
		if( !empty($hover_button_color) ){
			$styles .= "
				#".$id." .cws-custom-button:hover{
					color: ".esc_attr($hover_button_color).";
				}
			";
		}
		if( !empty($hover_button_bg_color) ){
			$styles .= "
				#".$id." .cws-custom-button:hover{
					background-color: ".esc_attr($hover_button_bg_color).";
				}
			";
		}
		if( !empty($shadow_color) ){
			$styles .= "
				#".$id.":before{
					-webkit-box-shadow: 0px 5px 40px 10px ".esc_attr($shadow_color).";
				    -moz-box-shadow: 0px 5px 40px 10px ".esc_attr($shadow_color).";
				    box-shadow: 0px 5px 40px 10px ".esc_attr($shadow_color).";
				}
				#".$id.":not(.hidden) .benefits-icon:not(.type-bordered) .icon:before{
					-webkit-box-shadow: 0px 5px 40px 10px ".esc_attr($shadow_color).";
				    -moz-box-shadow: 0px 5px 40px 10px ".esc_attr($shadow_color).";
				    box-shadow: 0px 5px 40px 10px ".esc_attr($shadow_color).";
				}
			";
		}
	}
	/* -----> End of default styles <----- */
	if( !empty($spacing) ){
		$styles .= "
			#".$id." > *:not(.visible-title){
				padding: ".((int)esc_attr($spacing) / 2)."px 0px;
			}
		";
	}
	/* -----> Customize landscape styles <----- */
	if( !empty($vc_landscape_styles) ||
		!empty($spacing_landscape)
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{";

				if( !empty($vc_landscape_styles) ){
					$styles .= "
						#".$id."{
							".$vc_landscape_styles."
						}
					";
				}
				if( !empty($spacing_landscape) ){
					$styles .= "
						#".$id." > *:not(.visible-title){
							padding: ".((int)esc_attr($spacing_landscape) / 2)."px 0px;
						}
					";
				}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( !empty($vc_portrait_styles) ){
		$styles .= "
			@media screen and (max-width: 991px){
				#".$id."{
					".$vc_portrait_styles."
				}
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( !empty($vc_mobile_styles) ){
		$styles .= "
			@media screen and (max-width: 767px){
				#".$id."{
					".$vc_mobile_styles."
				}
			}
		";
	}
	/* -----> End of mobile styles <----- */

	/* -----> Getting Icon <----- */
	if( !empty($icon_lib) ){
		if( $icon_lib == 'cws_svg' ){
			$icon = "icon_".$icon_lib;
			$svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
			$upload_dir = wp_upload_dir();
			$this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';	

			$result .= '<span class="icon">';
				$result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
					$result .= file_get_contents($this_folder . $svg_icon['name']);
				$result .= "</i>";
			$result .= '</span>';
		} else {
			if( !empty($icon) ){
				$result .= "<span class='icon'>";
					$result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon") ."'></i>";
				$result .= "</span>";
			}
		}
	}

	/* -----> Icon module output <----- */
	$out .= "<div id='".$id."' class='cws-benefits-module ".( $highlighted ? '' : 'hidden' )." ".esc_attr($el_class)."'>";
	
		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}
		$out .= "<div class='benefits-icon type-".$type."'>";
			if( $type == 'bordered' ){
				$out .= '
					<svg class="svg-border" width="100" height="100">
						<path d="M10.61,78.54a48.73,48.73,0,0,1,68.1-67.66" style="fill:none; stroke:#000; stroke-miterlimit:10; stroke-width:3px"/>
						<path d="M89.41,21.62A48.73,48.73,0,0,1,22,89.81" style="fill:none; stroke:#000; stroke-miterlimit:10; stroke-width:3px"/>
					</svg>
				';
			}
			$out .= $result;
		$out .= "</div>";

		if( !empty($title) ){
			$out .= "<h5 class='visible-title'>".$title."</h5>";
		}

		$out .= "<div class='benefits-info'>";
			if( !empty($title) ){
				$out .= "<h5 class='benefits-title'>".$title."</h5>";
			}
			if( !empty($description) ){
				$out .= "<div class='benefits-description'>".$description."</div>";
			}
		$out .= "</div>";

		$out .= "<div class='benefits-button'>";
			if( $add_button ){
				$out .= "<a href='".( !empty($button_url) ? esc_url($button_url) : '#' )."' class='cws-custom-button regular'>".esc_html($button_title)."</a>";
			}
		$out .= "</div>";

	$out .= "</div>";

	return $out;
}
add_shortcode( 'cws_sc_benefits', 'cws_vc_shortcode_benefits' );

function cws_vc_shortcode_sc_roadmap ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );
    $body_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'values'				=> '',
		'el_class'				=> '',
		/* -----> STYLING TAB <----- */
		'custom_color'			=> true,
		'timeline_color'		=> "#a7cbeb",
		'label_color'			=> "#fff",
        'icon_lib'				=> "FontAwesome",
		'item_color'			=> "#fff",
		'icon_color'			=> "#fff",
		'title_color'			=> $body_color,
		'text_color'			=> $body_color,
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> '',
		),
	);

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $extra_class = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$id = uniqid( "cws-roadmap-" );
	$counter = 0;
	$breakpoint_passed = false;

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $custom_color ){
		if( !empty($timeline_color) ){
			$styles .= "
                #".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row:before,
                #".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row:after,
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item:before,
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item.end-point:before,
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item .roadmap-item-inner:before
				{
					background-color: ".esc_attr($timeline_color).";
				}
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item.end-point:before
				{
					color: ".esc_attr($timeline_color).";
					fill: ".esc_attr($timeline_color).";
				}
			";
		}
		if( !empty($label_color) ){
			$styles .= "
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item .roadmap-label span
				{
					color: ".esc_attr($label_color).";
				}
			";
		}

		if( !empty($title_color	) ){
			$styles .= "
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item .roadmap-item-info .roadmap-title
				{
					color: ".esc_attr($title_color).";
				}
			";
		}

        if( !empty($icon_color) ){
            $styles .= "
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item .roadmap-icon-wrapper .icon i
				{
					color: ".esc_attr($icon_color).";
					fill: ".esc_attr($icon_color).";
				}
			";
        }

		if( !empty($text_color) ){
			$styles .= "
				#".$id.".cws-roadmap-module .roadmap-list-wrapper .roadmap-row .roadmap-item .roadmap-item-info .roadmap_desc
				{
					color: ".esc_attr($text_color).";
				}
			";
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Parse values array <----- */
	$values = (array)vc_param_group_parse_atts($values);

	/* -----> Icon module output <----- */
	$out .= '<div id="'.$id.'" class="cws-roadmap-module">';

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		$out .= '<div class="roadmap-list-wrapper">';

			foreach ($values as $key) {
				//Notices fix
				!isset($key['title-1']) ? $key['title-1'] = '' : '';
				!isset($key['title-2']) ? $key['title-2'] = '' : '';
				!isset($key['title-3']) ? $key['title-3'] = '' : '';
				!isset($key['title-4']) ? $key['title-4'] = '' : '';
				!isset($key['title-5']) ? $key['title-5'] = '' : '';

				!isset($key['description-1']) ? $key['description-1'] = '' : '';
				!isset($key['description-2']) ? $key['description-2'] = '' : '';
				!isset($key['description-3']) ? $key['description-3'] = '' : '';
				!isset($key['description-4']) ? $key['description-4'] = '' : '';
				!isset($key['description-5']) ? $key['description-5'] = '' : '';

				!isset($key['end_point-1']) ? $key['end_point-1'] = '' : '';
				!isset($key['end_point-2']) ? $key['end_point-2'] = '' : '';
				!isset($key['end_point-3']) ? $key['end_point-3'] = '' : '';
				!isset($key['end_point-4']) ? $key['end_point-4'] = '' : '';
				!isset($key['end_point-5']) ? $key['end_point-5'] = '' : '';

				!isset($key['icon_lib-1']) ? $key['icon_lib-1'] = '' : '';
				!isset($key['icon_lib-2']) ? $key['icon_lib-2'] = '' : '';
				!isset($key['icon_lib-3']) ? $key['icon_lib-3'] = '' : '';
				!isset($key['icon_lib-4']) ? $key['icon_lib-4'] = '' : '';
				!isset($key['icon_lib-5']) ? $key['icon_lib-5'] = '' : '';

                !isset($key['item_color-1']) ? $key['item_color-1'] = '' : '';
                !isset($key['item_color-2']) ? $key['item_color-2'] = '' : '';
                !isset($key['item_color-3']) ? $key['item_color-3'] = '' : '';
                !isset($key['item_color-4']) ? $key['item_color-4'] = '' : '';
                !isset($key['item_color-5']) ? $key['item_color-5'] = '' : '';


				//Extra classes
				if( !empty($key['end_point-1']) || !empty($key['end_point-2']) || !empty($key['end_point-3']) ||
                    !empty($key['end_point-4']) || !empty($key['end_point-5']) ){
					$extra_class .= " breakpoint";
				}

				if( $counter % 2 == 0 ){
					$extra_class .= " odd";
				} else {
					$extra_class .= " even";
				}

                $icon_1 = 'icon_' . $key['icon_lib-1'] . '-1';
                $icon_2 = 'icon_' . $key['icon_lib-2'] . '-2';
                $icon_3 = 'icon_' . $key['icon_lib-3'] . '-3';
                $icon_4 = 'icon_' . $key['icon_lib-4'] . '-4';
                $icon_5 = 'icon_' . $key['icon_lib-5'] . '-5';

				//Roadmap item output
				//if( isset($key['label-1']) || isset($key['label-2']) || isset($key['label-3']) || isset($key['label-4']) ){
					$out .= "<div class='roadmap-row".$extra_class."'>";

						if( !empty($key['label-1']) || !empty($key['title-1']) || !empty($key['description-1']) || (!empty($key['icon_lib-1']) && !empty($key[$icon_1])) ){
							$out .= print_roadmap_item($key['label-1'], $key['title-1'], $key['description-1'], $key['end_point-1'], $key['icon_lib-1'], $key[$icon_1], $key['item_color-1']);
						}
                        if( !empty($key['label-2']) || !empty($key['title-2']) || !empty($key['description-2']) || (!empty($key['icon_lib-2']) && isset($key[$icon_2])) ){
							$out .= print_roadmap_item($key['label-2'], $key['title-2'], $key['description-2'], $key['end_point-2'], $key['icon_lib-2'], $key[$icon_2], $key['item_color-2']);
						}
                        if( !empty($key['label-3']) || !empty($key['title-3']) || !empty($key['description-3']) || (!empty($key['icon_lib-3']) && !empty($key[$icon_3])) ){
							$out .= print_roadmap_item($key['label-3'], $key['title-3'], $key['description-3'], $key['end_point-3'], $key['icon_lib-3'], $key[$icon_3], $key['item_color-3']);
						}
                        if( !empty($key['label-4']) || !empty($key['title-4']) || !empty($key['description-4']) || (!empty($key['icon_lib-4']) && !empty($key[$icon_4])) ){
							$out .= print_roadmap_item($key['label-4'], $key['title-4'], $key['description-4'], $key['end_point-4'], $key['icon_lib-4'], $key[$icon_4], $key['item_color-4']);
						}
                        if( !empty($key['label-5']) || !empty($key['title-5']) || !empty($key['description-5']) || (!empty
                                ($key['icon_lib-5']) && !empty($key[$icon_5])) ){
                            $out .= print_roadmap_item($key['label-5'], $key['title-5'], $key['description-5'], $key['end_point-5'], $key['icon_lib-5'], $key[$icon_5], $key['item_color-5']);
                        }

					$out .= "</div>";
				//}

				$extra_class = '';
				$counter ++;
			}

		$out .= '</div>';

	$out .= '</div>';

	return $out;

}
add_shortcode( 'cws_sc_roadmap', 'cws_vc_shortcode_sc_roadmap' );

function metamax_sc_social_icons ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'values'				=> '',
		'icon_shape'			=> 'none',
		'el_class'				=> '',
		/* -----> STYLING TAB <----- */
		'custom_color'			=> true,
		'icon_color'			=> $theme_first_color,
		'icon_color_hover'		=> '#fff',
		'icon_bg_color'			=> '#fff',
		'icon_bg_color_hover'	=> $theme_first_color,
	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"		=> "",
			"customize_align"	=> false,
			"aligning"			=> "left",
			"custom_size"		=> "",
			"custom_icon_size"	=> "38",
		),
	);
	$responsive_vars = add_bg_properties($responsive_vars); //Add custom background properties to responsive vars array

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $result = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$id = uniqid( "cws-socail-icon-" );

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( $custom_size ){
		if( !empty($custom_icon_size) ){
			$styles .= "
				#".$id." .cws-social-link{
					font-size: ".(int)esc_attr($custom_icon_size)."px;
				}
				#".$id." .cws-social-link:before{
					font-size: ".(int)esc_attr($custom_icon_size)."px;
				}
			";
		}
	}
	if( $custom_color ){
		if( !empty($icon_color) ){
			$styles .= "
				#".$id." .cws-social-link:before{
					color: ".esc_attr($icon_color).";
				}
			";
		}
		if( !empty($icon_bg_color) ){
		    if ($icon_shape == 'hexagon') {
                $styles .= "
                    #".$id." .cws-social-link:after{
                        color: ".esc_attr($icon_bg_color).";
                    }
                ";
            } else {
                $styles .= "
                    #".$id." .cws-social-link{
                        background-color: ".esc_attr($icon_bg_color).";
                    }
                ";
            }
		}

        if(
            !empty($icon_color_hover) ||
            !empty($icon_bg_color_hover)
        ) {
            $styles .= "
				@media 
					screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
					screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
					screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
					screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
					screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
				{
			";

            if( !empty($icon_color_hover) ){
                $styles .= "
                    #".$id." .cws-social-link:hover:before{
                        color: ".esc_attr($icon_color_hover).";
                    }
                ";
            }
            if( !empty($icon_bg_color_hover) ){
                if ($icon_shape == 'hexagon') {
                    $styles .= "
                        #".$id." .cws-social-link:hover:after{
                            color: ".esc_attr($icon_bg_color_hover).";
                        }
                    ";
                } else {
                    $styles .= "
                        #".$id." .cws-social-link:hover{
                            background-color: ".esc_attr($icon_bg_color_hover).";
                        }
                    ";
                }
            }

            $styles .="
				}
			";
        }
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$custom_size_landscape
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					#".$id."{
						".$vc_landscape_styles."
					}
				";
			}
			if($custom_size_landscape){
				if( !empty($custom_icon_size_landscape) ){
					$styles .= "
						#".$id." .cws-social-link{
							height: ".((int)esc_attr($custom_icon_size_landscape) + 20)."px;
							width: ".((int)esc_attr($custom_icon_size_landscape) + 20)."px;
							line-height: ".((int)esc_attr($custom_icon_size_landscape) + 20)."px;
						}
						#".$id." .cws-social-link:before{
							font-size: ".(int)esc_attr($custom_icon_size_landscape)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$custom_size_portrait
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					#".$id."{
						".$vc_portrait_styles."
					}
				";
			}
			if($custom_size_portrait){
				if( !empty($custom_icon_size_portrait) ){
					$styles .= "
						#".$id." .cws-social-link{
							height: ".((int)esc_attr($custom_icon_size_portrait) + 20)."px;
							width: ".((int)esc_attr($custom_icon_size_portrait) + 20)."px;
							line-height: ".((int)esc_attr($custom_icon_size_portrait) + 20)."px;
						}
						#".$id." .cws-social-link:before{
							font-size: ".(int)esc_attr($custom_icon_size_portrait)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$custom_size_mobile
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					#".$id."{
						".$vc_mobile_styles."
					}
				";
			}
			if($custom_size_mobile){
				if( !empty($custom_icon_size_mobile) ){
					$styles .= "
						#".$id." .cws-social-link{
							height: ".((int)esc_attr($custom_icon_size_mobile) + 20)."px;
							width: ".((int)esc_attr($custom_icon_size_mobile) + 20)."px;
							line-height: ".((int)esc_attr($custom_icon_size_mobile) + 20)."px;
						}
						#".$id." .cws-social-link:before{
							font-size: ".(int)esc_attr($custom_icon_size_mobile)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */

	/* -----> Parse values array <----- */
	$values = (array)vc_param_group_parse_atts($values);

	$icon_data = array();
	foreach ( $values as $data ) {
		$new_data = $data;
		$new_data['icon'] = isset( $data['icon'] ) ? $data['icon'] : '';
		$new_data['link'] = isset( $data['link'] ) ? $data['link'] : '#';
		$new_data['title'] = isset( $data['title'] ) ? $data['title'] : '';
		$new_data['new_tab'] = isset( $data['new_tab'] ) ? $data['new_tab'] : '';

		$icon_data[] = $new_data;
	}

	$module_classes = '';
	if( $customize_align ){
		$module_classes .= ' position-'.$aligning;
	}
	if( $customize_align_landscape ){
		$module_classes .= ' landscape-position-'.$aligning_landscape;
	}
	if( $customize_align_portrait ){
		$module_classes .= ' portrait-position-'.$aligning_portrait;
	}
	if( $customize_align_mobile ){
		$module_classes .= ' mobile-position-'.$aligning_mobile;
	}
	if( !empty($el_class) ){
		$module_classes .= " ".esc_attr($el_class);
	}

	/* -----> Icon module output <----- */
	$out .= '<div id="'.$id.'" class="cws-social-links shape-'.$icon_shape.$module_classes.'">';

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		foreach ( $icon_data as $icon_d ) {
			!empty( $icon_d['title'] ) ? $title = esc_html($icon_d['title']) : $title = "";
			!empty($icon_d['link']) && (bool)$icon_d['new_tab'] ? $attr = "target='_blank'" : $attr = "";
			!empty( $icon_d['link'] ? $link = esc_url($icon_d['link']) : $link = '#' );
			!empty( $icon_d['icon'] ? $icon = esc_attr($icon_d['icon']) : $icon = '' );

			$out .= "<a href='".$link."' ".$attr." class='cws-social-link ". $icon_shape ." ". $icon ."'></a>";
		}

	$out .= '</div>';

	return $out;
}
add_shortcode( 'cws_sc_social_icons', 'metamax_sc_social_icons' );

function cws_vc_shortcode_sc_divider ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		"type"					=> "simple",
		"height"				=> "1",
		"width"					=> "15%",
		"icon_lib"				=> "FontAwesome",
		"separate_type"			=> "iconic",
		"size"					=> "3x",
		"divider_image"			=> "",
		"el_class"				=> "",
		/* -----> STYLING TAB <----- */
		"icon_color"			=> $theme_first_color,
		"divider_color"			=> $theme_first_color,
		"inner_divider_color"	=> $theme_first_color,
 	);

	$responsive_vars = array(
		"all" => array(
			"custom_styles"	=> "",
			"custom_size"	=> "",
			"icon_size"		=> "21",
			"icon_spacings"	=> "35"
		),
	);

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $result = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$icon = function_exists('cws_ext_vc_sc_get_icon') ? cws_ext_vc_sc_get_icon( $atts ) : "";
	$icon = esc_attr( $icon );
	$id = uniqid( "cws-divider-" );

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			#".$id."{
				".$vc_desktop_styles."
			}
		";
	}
	if( !empty($height) ){
		$styles .= "
			#".$id." .divider-left-part,
			#".$id." .divider-right-part,
			#".$id." .cws-divider{
				height: ".(int)esc_attr($height)."px;
			}
		";
	}
	if( !empty($width) ){
		$styles .= "
			#".$id." .cws-divider:before{
				width: ".(int)esc_attr($width)."%;
			}
		";
	}
	if( !empty($custom_size) ){
		if( !empty($icon_size) ){
			$styles .= "
				#".$id." .icon,
				#".$id." .icon i:not(.svg):before{
					font-size: ".(int)esc_attr($icon_size)."px;
					line-height: ".(int)esc_attr($icon_size)."px;
				}
				#".$id." .divider-img img{
					width: ".(int)esc_attr($icon_size)."px;
				}
			";
		}
		if( !empty($icon_spacings) ){
			$styles .= "
				#".$id." .icon{
					margin: 0 ".(int)esc_attr($icon_spacings)."px;
				}
				#".$id." .divider-img{
					margin: 0 ".(int)esc_attr($icon_spacings)."px;
				}
			";
		}
	}
	if( !empty($icon_color) ){
		$styles .= "
			#".$id." .icon{
				color: ".esc_attr($icon_color).";
			}
			#".$id." .icon i.svg{
				fill: ".esc_attr($icon_color).";
			}
		";
	}
	if( !empty($divider_color) ){
		$styles .= "
			#".$id." .divider-left-part,
			#".$id." .divider-right-part,
			#".$id." .cws-divider{
				background-color: ".esc_attr($divider_color).";
			}
		";
		if( $type == 'dashed' ){
			$styles .= "
				#".$id." .cws-divider{
					background-image: linear-gradient(90deg, ".esc_attr($divider_color).", ".esc_attr($divider_color)." 75%, transparent 75%, transparent 100%);
					background-size: 12px 1px;
				}
			";
		}
	}
	if( !empty($inner_divider_color) ){
		$styles .= "
			#".$id." .cws-divider:before{
				background-color: ".esc_attr($inner_divider_color).";
			}
		";
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$custom_size_landscape
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					#".$id."{
						".$vc_landscape_styles."
					}
				";
			}
			if( $custom_size_landscape ){
				if( !empty($icon_size_landscape) ){
					$styles .= "
						#".$id." .icon,
						#".$id." .icon i:not(.svg),
						#".$id." .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_landscape)."px;
							line-height: ".(int)esc_attr($icon_size_landscape)."px;
						}
						#".$id." .divider-img img{
							width: ".(int)esc_attr($icon_size_landscape)."px;
						}
					";
				}
				if( !empty($icon_spacings_landscape) ){
					$styles .= "
						#".$id." .icon{
							margin: 0 ".(int)esc_attr($icon_spacings_landscape)."px;
						}
						#".$id." .divider-img{
							margin: 0 ".(int)esc_attr($icon_spacings_landscape)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$custom_size_portrait
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					#".$id."{
						".$vc_portrait_styles."
					}
				";
			}
			if( $custom_size_portrait ){
				if( !empty($icon_size_portrait) ){
					$styles .= "
						#".$id." .icon,
						#".$id." .icon i:not(.svg),
						#".$id." .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_portrait)."px;
							line-height: ".(int)esc_attr($icon_size_portrait)."px;
						}
						#".$id." .divider-img img{
							width: ".(int)esc_attr($icon_size_portrait)."px;
						}
					";
				}
				if( !empty($icon_spacings_portrait) ){
					$styles .= "
						#".$id." .icon{
							margin: 0 ".(int)esc_attr($icon_spacings_portrait)."px;
						}
						#".$id." .divider-img{
							margin: 0 ".(int)esc_attr($icon_spacings_portrait)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$custom_size_mobile
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					#".$id."{
						".$vc_mobile_styles."
					}
				";
			}
			if( $custom_size_mobile ){
				if( !empty($icon_size_mobile) ){
					$styles .= "
						#".$id." .icon,
						#".$id." .icon i:not(.svg),
						#".$id." .icon i:not(.svg):before{
							font-size: ".(int)esc_attr($icon_size_mobile)."px;
							line-height: ".(int)esc_attr($icon_size_mobile)."px;
						}
						#".$id." .divider-img img{
							width: ".(int)esc_attr($icon_size_mobile)."px;
						}
					";
				}
				if( !empty($icon_spacings_mobile) ){
					$styles .= "
						#".$id." .icon{
							margin: 0 ".(int)esc_attr($icon_spacings_mobile)."px;
						}
						#".$id." .divider-img{
							margin: 0 ".(int)esc_attr($icon_spacings_mobile)."px;
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */ 

	/* -----> Getting Icon <----- */	
	if( !empty($divider_image) ){
		$image = wp_get_attachment_image( $divider_image, 'full' );
		$result .= "<div class='divider-img'>".$image."</div>";
	}
	if( !empty($icon_lib) ){
		if( $icon_lib == 'cws_svg' ){
			$icon = "icon_".$icon_lib;
			$svg_icon = json_decode(str_replace("``", "\"", $atts[$icon]), true);
			$upload_dir = wp_upload_dir();
			$this_folder = $upload_dir['basedir'] . '/cws-svgicons/' . md5($svg_icon['collection']) . '/';

			$result .= '<span class="icon">';
				$result .= "<i class='svg' style='width:".$svg_icon['width']."px; height:".$svg_icon['height']."px;'>";
					$result .= file_get_contents($this_folder . $svg_icon['name']);
				$result .= "</i>";
			$result .= '</span>';
		} else {
			if( !empty($icon) ){
				$result .= "<span class='icon'>";
					$result .= "<i class='". (!empty($size) ? "cws-icon-$size $icon" : "cws_vc_shortcode_icon_3x $icon") ."'></i>";
				$result .= "</span>";
			}
		}
	}

	/* -----> Divider module output <----- */
	$out .= "<div id='".$id."' class='cws-divider-wrapper type-".$type." ".esc_attr($el_class)."'>";

		if ( !empty($styles) ){
			Cws_shortcode_css()->enqueue_cws_css($styles);
		}

		if( $type == 'simple' || $type == 'double' || $type == 'dashed' ){
			$out .= "<div class='cws-divider'></div>";
		} else {
			$out .= "<div class='divider-left-part'></div>";
			$out .= $result;
			$out .= "<div class='divider-right-part'></div>";
		}

	$out .= "</div>";
	
	return $out;
}
add_shortcode( 'cws_sc_divider', 'cws_vc_shortcode_sc_divider' );

function cws_vc_shortcode_sc_spacing ( $atts = array(), $content = "" ){
	extract( shortcode_atts( array(
		'height' => '',
		'responsive_es' => '',
		'height_size_sm_desctop' => '',
		'height_tablet' => '',
		'height_mobile' => '',
 	), $atts));
 	$classes = '';
	if ($responsive_es == 'true') {
		$classes .= !empty($height_size_sm_desctop || $height_size_sm_desctop == '0') ? ' cws_spacing_size_sm_desctop-on' : '';
		$classes .= !empty($height_tablet || $height_tablet == '0') ? ' cws_spacing_tablet-on' : '';
		$classes .= !empty($height_mobile || $height_mobile == '0') ? ' cws_spacing_mobile-on' : '';
	}

	$out = '';
	if (!empty($height) || $height == '0') {
		$out .= '<div class="cws_spacing'.esc_attr($classes).'">';
		$out .= '<div class="cws_spacing cws_spacing_default" style="height:'.(int)$height.'px;"></div>';
		if ($responsive_es == 'true') {
			$out .= !empty($height_size_sm_desctop || $height_size_sm_desctop == '0') ? ' <div class="cws_spacing cws_spacing_size_sm_desctop" style="height:'.(int)$height_size_sm_desctop.'px;"></div>' : '';
			$out .= !empty($height_tablet || $height_tablet == '0') ? ' <div class="cws_spacing cws_spacing_tablet" style="height:'.(int)$height_tablet.'px;"></div>' : '';
			$out .= !empty($height_mobile || $height_mobile == '0') ? ' <div class="cws_spacing cws_spacing_mobile" style="height:'.(int)$height_mobile.'px;"></div>' : '';
		}
		$out .= '</div>';
	}	
	return $out;
}
add_shortcode( 'cws_sc_spacing', 'cws_vc_shortcode_sc_spacing' );

function cws_vc_shortcode_sc_categories ( $atts = array(), $content = "" ){
	extract( shortcode_atts( array(
		"columns"			=> "3",
		"count"				=> "3",
		"square"			=> false,
		"use_carousel"		=> false,
		"pagination"		=> true,
		"navigation"		=> false,
		"auto_height"		=> true,
		"infinite"			=> false,
		"autoplay"			=> false,
		"autoplay_speed"	=> "3000",
		"pause_on_hover"	=> false,
		"cat_terms" 		=> '',
		"el_class"			=> ""
 	), $atts));

	global $cws_theme_funcs;

 	$el_class = esc_attr( $el_class );
 	$out = $grid_class = "";
 	$counter = 0;
	
	$sb = $cws_theme_funcs->cws_render_sidebars( get_queried_object_id() );
	$sb_layout = isset( $sb['layout_class'] ) ? $sb['layout_class'] : '';
	$full_width = isset($GLOBALS['cws_row_atts']) && !empty($GLOBALS['cws_row_atts']) ? $GLOBALS['cws_row_atts'] : "";

	$GLOBALS['cws_vc_shortcode_posts_grid_atts'] = array(
		'layout'			=> $columns,
		'sb_layout'			=> $sb_layout,
		'crop_featured'		=> false,
		'full_width'		=> $full_width
	);

 	unset( $GLOBALS['cws_vc_shortcode_posts_grid_atts'] );
	if ($cat_terms == '') {
		$cat_terms = get_terms( 'category', array(
		    'fields' => 'id=>slug', //all, ids, names, id=>name, id=>slug
		));
	} else {
		$cat_terms = !empty($cat_terms) ? explode( ',', $cat_terms ) : null;
	}
	wp_enqueue_script( 'isotope' );
	if ($use_carousel) {
		wp_enqueue_script( 'slick-carousel' );
	}

	$section_id = uniqid( 'categories_grid_' );
	$section_class = 'cws-categories';
	if( $square ){
		$section_class .= " square_img";
	}
	if( !empty($el_class) ){
		$section_class .= " ".esc_attr($el_class);
	}
	$section_atts = '';

	if( $use_carousel ){
		$section_class .= ' cws-carousel-wrapper';

		$section_atts = " data-columns='".$columns."'";
		$section_atts .= " data-pagination='".( $pagination ? 'on' : 'off' )."'";
		$section_atts .= " data-navigation='".( $navigation ? 'on' : 'off' )."'";
		$section_atts .= " data-auto-height='".( $auto_height ? 'on' : 'off' )."'";
		$section_atts .= " data-draggable='on'";
		$section_atts .= " data-infinite='".( $infinite ? 'on' : 'off' )."'";
		$section_atts .= " data-autoplay='".( $autoplay ? 'on' : 'off' )."'";
		$section_atts .= " data-autoplay-speed='".$autoplay_speed."'";
		$section_atts .= " data-pause-on-hover='".( $pause_on_hover ? 'on' : 'off' )."'";
	} else {
		$section_class .= ' columns-'.$columns;
	}

	if( !empty($cat_terms) ){
		$out .= "<div id='".$section_id."' class='".$section_class."'".$section_atts.">";
		if( $use_carousel ){
			$out .= "<div class='cws-carousel'>";
		}
			foreach ($cat_terms as $id => $slug) {
		 		if ($counter >= $count) break;

		 		$term = get_term_by('slug', $slug, 'category');
				$term_id = $term->term_id;
		 		$term_name = $term->name;
				$link = get_category_link($term_id);
				$term_image = get_term_meta( $term_id, 'cws_mb_term' );
				if ((bool)$square) {
					if( $columns == '1' ){
						$dummy_image = get_template_directory_uri() . "/img/img_placeholder_crop_1170.png";
			 		} else if( $columns == '2' ) {
			 			$dummy_image = get_template_directory_uri() . "/img/img_placeholder_crop_585.png";
			 		} else {
			 			$dummy_image = get_template_directory_uri() . "/img/img_placeholder_crop_500.png";
			 		}
				} else {
					$dummy_image = get_template_directory_uri() . "/img/img_placeholder.png";
				}
				$is_dummy = true;

				ob_start();
					if ( !empty($term_image[0]['image']['src']) ){
						$is_dummy = false;
						$img_id = $term_image[0]['image']['id'];

						$img_title = get_post($img_id)->post_title;
						$img_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
						$img_alt = !empty($img_alt) ? $img_alt : $img_title;

						$img_src = wp_get_attachment_image_url( $img_id, 'full' );
						$img_srcset = wp_get_attachment_image_srcset( $img_id, 'full' );
						$img_sizes = wp_get_attachment_image_sizes($img_id, 'full');
					} ?>
						<a class='category-item' href="<?php echo esc_url($link);?>">
							<img src='<?php echo esc_url($img_src) ?>' srcset='<?php echo esc_attr($img_srcset) ?>' sizes='<?php echo esc_attr($img_sizes) ?>' alt='<?php echo esc_attr($img_alt) ?>'/>
							<span class='category-label'><?php echo sprintf("%s", $term_name); ?></span>
						</a>
					<?php
				$out .= ob_get_clean();
				$counter++;
		 	}
		if( $use_carousel ){
			$out .= "</div>";
		}
		$out .= "</div>";
	}

	return $out;
}
add_shortcode( 'cws_sc_categories', 'cws_vc_shortcode_sc_categories' );

function cws_vc_shortcode_sc_twitter ( $atts = array(), $content = "" ){
	extract( shortcode_atts( array(
		'number' 					=> '4',
		'visible_number' 			=> '2',
		"el_class"					=> ""
 	), $atts));

	/* -----> Variables declaration <----- */
	$out = $styles = $module_classes = $module_attr = "";
	$id = uniqid( "cws-twitter-" );

	$is_plugin_installed = function_exists( 'cws_getTweets' );
	$tweets = $is_plugin_installed ? cws_getTweets( $number ) : array();

	//Reg-exp for links
	$find_links = "/(https?:\/\/|ftp:\/\/|www\.)((?![.,?!;:()]*(\s|$))[^\s]){2,}/"; 

	$retrieved_tweets_number = count( $tweets );

	$is_carousel = $retrieved_tweets_number > $visible_number;

	if ( $is_carousel ){
		wp_enqueue_script( 'slick-carousel' );
		$module_classes .= " cws-carousel-wrapper";

		$module_attr .= " data-columns='".$visible_number."'";
		$module_attr .= " data-pagination='on'";
		$module_attr .= " data-auto-height='on'";
		$module_attr .= " data-draggable='on'";
	}
	if( !empty($el_class) ){
		$module_classes .= " ".esc_attr($el_class);
	}

	/* -----> Twitter module output <----- */
	if( !empty($tweets) ){
		if ( isset( $tweets['error'] ) && !empty( $tweets['error'] ) ){
			echo do_shortcode( "[cws_sc_msg_box title='" . esc_html__( 'Error', 'cws-essentials' ) . "' type='error' description='']" . esc_html( $tweets['error'] ) . "[/cws_sc_msg_box]" );
		} else {
			$out .= "<div id='".$id."' class='cws-twitter-module".$module_classes."' ".$module_attr.">";
				if( $is_carousel ){
					$out .= "<div class='cws-carousel'>";
				}

					foreach ($tweets as $tweet) {

						//Find twetter attached urls
						$tweet_entitties = isset( $tweet['entities'] ) ? $tweet['entities'] : array();
						$tweet_urls = isset( $tweet_entitties['urls'] ) && is_array( $tweet_entitties['urls'] ) ? $tweet_entitties['urls'] : array();

						//Remove image links from text
						$new_text = preg_replace($find_links, '', $tweet['text']);

						foreach ( $tweet_urls as $tweet_url ) {
							$display_url = isset( $tweet_url['display_url'] ) ? $tweet_url['display_url'] : '';
							$received_url = isset( $tweet_url['url'] ) ? $tweet_url['url'] : '';
							$new_text .= "<a href='".esc_url($received_url)."'>".esc_html($display_url)."</a>";
						}

						$out .= "<div class='cws-tweet'>";
							$out .= "<div class='text'>".$new_text."</div>";
							$out .= "<div class='date'>".esc_html( date( "Y-m-d H:i:s", strtotime($tweet['created_at']) ) )."</div>";
						$out .= "</div>";
					}

				if( $is_carousel ){
					$out .= "</div>";
				}
			$out .= "</div>";
		}
	} else {
		if ( !$is_plugin_installed ){
			echo do_shortcode( "[cws_sc_msg_box title='" . esc_html__( 'Plugin not installed', 'cws-essentials' ) . "' type='warn']" . esc_html__( 'Please install and activate required plugin ', 'cws-essentials' ) . "<a href='https://ru.wordpress.org/plugins/oauth-twitter-feed-for-developers/'>" . esc_html__( "oAuth Twitter Feed for Developers", 'cws-essentials' ) . "</a>[/cws_sc_msg_box]" );
		}
	}

	return $out;
}
add_shortcode( 'cws_sc_twitter', 'cws_vc_shortcode_sc_twitter' );


function cws_vc_shortcode_sc_text ( $atts = array(), $content = "" ){
	global $cws_theme_funcs;

	$theme_first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_first_color' ) );
	$theme_second_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme_second_color' ) );
	$header_color = esc_attr( $cws_theme_funcs->cws_get_option('header-font')['color'] );
	$body_color = esc_attr( $cws_theme_funcs->cws_get_option('body-font')['color'] );

	$defaults = array(
		/* -----> GENERAL TAB <----- */
		'subtitle'					=> 'Enter subtitle here',
		'title'						=> 'Enter title here',
		'subtitle_position'			=> 'beside',
		'title_tag'					=> 'h2',
		'add_divider'				=> true,
		'divider_position'          => 'under',
		'el_class'					=> '',
		/* -----> STYLING TAB <----- */
		'customize_colors'			=> false,
		'custom_title_color'		=> $header_color,
		'custom_title_mark'		    => '',
		'custom_subtitle_color'		=> $theme_first_color,
		'custom_divider_color'		=> $theme_second_color,
		'custom_font_color'			=> $body_color,
 	);
 	$responsive_vars = array(
 		/* -----> RESPONSIVE TABS <----- */
 		'all' => array(
 			'custom_styles'		=> '',
 			'customize_align'	=> false,
 			'module_alignment'	=> 'left',
 			'customize_size' 	=> false,
			'title_size' 		=> '60px',
			'subtitle_size' 	=> '18px',
			'description_size' 	=> '16px',
			'divider_size' 		=> '69px',
			'title_margins'		=> '0px 0px 0px 0px'
 		),
	);

	$responsive_defaults = add_responsive_suffix($responsive_vars);
	$defaults = array_merge($defaults, $responsive_defaults);

	$proc_atts = shortcode_atts( $defaults, $atts );
	extract( $proc_atts );

	/* -----> Variables declaration <----- */
	$out = $styles = $vc_desktop_class = $vc_landscape_class = $vc_portrait_class = $vc_mobile_class = "";
	$id = uniqid( "cws-textmodule-" );
	$title = wp_kses( $title, array(
		"b"			=> array(),
		"strong"	=> array(),
		"mark"		=> array(),
		"br"		=> array()
	));
	$content = apply_filters( "the_content", $content );

	/* -----> Visual Composer Responsive styles <----- */
	list( $vc_desktop_class, $vc_landscape_class, $vc_portrait_class, $vc_mobile_class ) = vc_responsive_styles($atts);

	preg_match("/(?<=\{).+?(?=\})/", $vc_desktop_class, $vc_desktop_styles); 
	$vc_desktop_styles = implode($vc_desktop_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_landscape_class, $vc_landscape_styles);
	$vc_landscape_styles = implode($vc_landscape_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_portrait_class, $vc_portrait_styles);
	$vc_portrait_styles = implode($vc_portrait_styles);

	preg_match("/(?<=\{).+?(?=\})/", $vc_mobile_class, $vc_mobile_styles);
	$vc_mobile_styles = implode($vc_mobile_styles);

	/* -----> Customize default styles <----- */
	if( !empty($vc_desktop_styles) ){
		$styles .= "
			.".$id."{
				".$vc_desktop_styles.";
			}
		";
	}
	if( $customize_align ){
		$styles .= "
			.".$id."{
				text-align: ".$module_alignment.";
			}
		";
	}
	if( $customize_colors ){
		if( !empty($custom_title_color) ){
			$styles .= "
				.".$id." .cws-textmodule-title{
					color: ".esc_attr($custom_title_color).";
				}
			";
		}
        if( !empty($custom_title_mark) ){
            $styles .= "
				.".$id." .cws-textmodule-title mark{
					color: ".esc_attr($custom_title_mark).";
				}
			";
        }
		if( !empty($custom_subtitle_color) ){
			$styles .= "
				.".$id." .cws-textmodule-subtitle{
					color: ".esc_attr($custom_subtitle_color).";
				}
			";
		}
		if( !empty($custom_divider_color) ){
			$styles .= "
				.".$id." .cws-textmodule-divider:before{
					background-color: ".esc_attr($custom_divider_color).";
				}
			";
		}
		if( !empty($custom_font_color) ){
			$styles .= "
				.".$id."{
					color: ".esc_attr($custom_font_color).";
				}
			";
		}
	}
	if( $customize_size ){
		if( !empty($title_size) ){
			$styles .= "
				.".$id." .cws-textmodule-title{
					font-size: ".(int)esc_attr($title_size)."px !important;
				}
			";
		}
		if( !empty($subtitle_size) ){
			$styles .= "
				.".$id." .cws-textmodule-subtitle{
					font-size: ".(int)esc_attr($subtitle_size)."px !important;
				}
			";
		}
        if( !empty($description_size) ){
            $styles .= "
				.".$id." .cws-textmodule-content-wrapper{
					font-size: ".(int)esc_attr($description_size)."px;
				}
			";
        }
		if( !empty($divider_size) ){
			$styles .= "
				.".$id." .cws-textmodule-divider:before{
					width: ".(int)esc_attr($divider_size)."px;
				}
			";
            if ( $divider_position == 'beside' ) {
                $styles .= "
                    .".$id." .cws-textmodule-divider.divider-position-beside {
                        margin-left: -".((int)esc_attr($divider_size)+18)."px;
                    }
			    ";
            }
		}
		if( !empty($title_margins) ){
		    if ($subtitle_position == 'beside') {
                $styles .= "
                    .".$id." .cws-textmodule-header{
                        margin: ".esc_attr($title_margins).";
                    }
			";
            } else {
                $styles .= "
                    .".$id." .cws-textmodule-title{
                        margin: ".esc_attr($title_margins).";
                    }
			";
            }
		}
	}
	/* -----> End of default styles <----- */

	/* -----> Customize landscape styles <----- */
	if( 
		!empty($vc_landscape_styles) || 
		$customize_align_landscape || 
		$customize_size_landscape 
	){
		$styles .= "
			@media 
				screen and (max-width: 1199px), /*Check, is device a tablet*/
				screen and (max-width: 1366px) and (any-hover: none) /*Enable this styles for iPad Pro 1024-1366*/
			{
		";

			if( !empty($vc_landscape_styles) ){
				$styles .= "
					.".$id."{
						".$vc_landscape_styles.";
					}
				";
			}
			if( $customize_align_landscape ){
				$styles .= "
					.".$id."{
						text-align: ".$module_alignment_landscape.";
					}
				";
			}
			if( $customize_size_landscape ){
				if( !empty($title_size_landscape) ){
					$styles .= "
						.".$id." .cws-textmodule-title{
							font-size: ".(int)esc_attr($title_size_landscape)."px !important;
						}
					";
				}
				if( !empty($subtitle_size_landscape) ){
					$styles .= "
						.".$id." .cws-textmodule-subtitle{
							font-size: ".(int)esc_attr($subtitle_size_landscape)."px !important;
						}
					";
				}
                if( !empty($description_size_landscape) ){
                    $styles .= "
						.".$id." .cws-textmodule-content-wrapper{
							font-size: ".(int)esc_attr($description_size_landscape)."px;
						}
					";
                }
				if( !empty($divider_size_landscape) ){
					$styles .= "
						.".$id." .cws-textmodule-divider:before{
							width: ".(int)esc_attr($divider_size_landscape)."px;
						}
					";
                    if ( $divider_position == 'beside' ) {
                        $styles .= "
                            .".$id." .cws-textmodule-divider.divider-position-beside {
                                margin-left: -".((int)esc_attr($divider_size_lanscape)+18)."px;
                            }
					    ";
                    }
				}
				if( !empty($title_margins_landscape) ){
					$styles .= "
						.".$id." .cws-textmodule-title{
							margin: ".esc_attr($title_margins_landscape).";
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of landscape styles <----- */

	/* -----> Customize portrait styles <----- */
	if( 
		!empty($vc_portrait_styles) || 
		$customize_align_portrait || 
		$customize_size_portrait 
	){
		$styles .= "
			@media screen and (max-width: 991px){
		";

			if( !empty($vc_portrait_styles) ){
				$styles .= "
					.".$id."{
						".$vc_portrait_styles.";
					}
				";
			}
			if( $customize_align_portrait ){
				$styles .= "
					.".$id."{
						text-align: ".$module_alignment_portrait.";
					}
				";
			}
			if( $customize_size_portrait ){
				if( !empty($title_size_portrait) ){
					$styles .= "
						.".$id." .cws-textmodule-title{
							font-size: ".(int)esc_attr($title_size_portrait)."px !important;
						}
					";
				}
				if( !empty($subtitle_size_portrait) ){
					$styles .= "
						.".$id." .cws-textmodule-subtitle{
							font-size: ".(int)esc_attr($subtitle_size_portrait)."px !important;
						}
					";
				}
				if( !empty($description_size_portrait) ){
					$styles .= "
						.".$id." .cws-textmodule-content-wrapper{
							font-size: ".(int)esc_attr($description_size_portrait)."px;
						}
					";
				}
				if( !empty($divider_size_portrait) ){
					$styles .= "
						.".$id." .cws-textmodule-divider:before{
							width: ".(int)esc_attr($divider_size_portrait)."px;
						}
					";
                    if ( $divider_position == 'beside' ) {
                        $styles .= "
                            .".$id." .cws-textmodule-divider.divider-position-beside {
                                margin-left: -".((int)esc_attr($divider_size_portrait)+18)."px;
                            }
					    ";
                    }
				}
				if( !empty($title_margins_portrait) ){
					$styles .= "
						.".$id." .cws-textmodule-title{
							margin: ".esc_attr($title_margins_portrait).";
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of portrait styles <----- */

	/* -----> Customize mobile styles <----- */
	if( 
		!empty($vc_mobile_styles) || 
		$customize_align_mobile || 
		$customize_size_mobile 
	){
		$styles .= "
			@media screen and (max-width: 767px){
		";

			if( !empty($vc_mobile_styles) ){
				$styles .= "
					.".$id."{
						".$vc_mobile_styles.";
					}
				";
			}
			if( $customize_align_mobile ){
				$styles .= "
					.".$id."{
						text-align: ".$module_alignment_mobile.";
					}
				";
			}
			if( $customize_size_mobile ){
				if( !empty($title_size_mobile) ){
					$styles .= "
						.".$id." .cws-textmodule-title{
							font-size: ".(int)esc_attr($title_size_mobile)."px !important;
						}
					";
				}
				if( !empty($subtitle_size_mobile) ){
					$styles .= "
						.".$id." .cws-textmodule-subtitle{
							font-size: ".(int)esc_attr($subtitle_size_mobile)."px !important;
						}
					";
				}
				if( !empty($description_size_mobile) ){
					$styles .= "
						.".$id." .cws-textmodule-content-wrapper{
							font-size: ".(int)esc_attr($description_size_mobile)."px;
						}
					";
				}
				if( !empty($divider_size_mobile) ){
					$styles .= "
						.".$id." .cws-textmodule-divider:before{
							width: ".(int)esc_attr($divider_size_mobile)."px;
						}
					";
					if ( $divider_position == 'beside' ) {
                        $styles .= "
                            .".$id." .cws-textmodule-divider.divider-position-beside {
                                margin-left: -".((int)esc_attr($divider_size_mobile)+18)."px;
                            }
					    ";
                    }
				}
				if( !empty($title_margins_mobile) ){
					$styles .= "
						.".$id." .cws-textmodule-title{
							margin: ".esc_attr($title_margins_mobile).";
						}
					";
				}
			}

		$styles .= "
			}
		";
	}
	/* -----> End of mobile styles <----- */
	

	/* -----> Text module output <----- */
	if ( !empty($title) || !empty($subtitle) || !empty($content) || !empty($icon) ){
		$out .= "<div class='".$id." cws-textmodule". ( !empty($el_class) ? " $el_class" : "" ) ."'>"; //ID in class,
        // coz slick-slider rewrite ID.

			if ( !empty($styles) ){
				Cws_shortcode_css()->enqueue_cws_css($styles);
			}
            if( !empty($subtitle) || !empty($title) ) {
                $out .= "<div class='cws-textmodule-header subtitle-" . esc_html($subtitle_position) . "'>";
                if (!empty($subtitle)) {
                    $out .= "<div class='cws-textmodule-subtitle'>" . esc_html($subtitle) . "</div>";
                }
                if (!empty($title)) {
                    $out .= "<" . $title_tag . " class='cws-textmodule-title'>" . ( $add_divider && $divider_position == "beside" ? "<span class='cws-textmodule-divider divider-position-beside'></span>" : "") . $title . "</" . $title_tag . ">";
                }
                $out .= "</div>";
            }
			if( $add_divider && $divider_position == 'under' ){
				$out .= "<div class='cws-textmodule-divider divider-position-under'></div>";
			}
			if( !empty($content) ){
				$out .= "<div class='cws-textmodule-content-wrapper'>";
					$out .= $content;
				$out .= "</div>";
			}

		$out .= "</div>";
	}

	return $out;
}
add_shortcode( 'cws_sc_text', 'cws_vc_shortcode_sc_text' );


function cws_vc_shortcode_sc_tips($atts, $content=null, $tag) {
	extract( shortcode_atts( array(
		'image' => '',
		'width' => '',
		'color' => '',
		'ispulse' => 'yes',
		'pulsecolor' => 'pulse-white',
		'icon' => '',
		'iconsize' => '',
		'tooltipstyle' => 'shadow',
		'iconbackground' => 'rgba(0,0,0,0.8)',
		'tooltipanimation' => 'grow',
		'circlecolor' => '#FFFFFF',
		'opacity' => '1',
		'arrowposition' => '',
		'trigger' => '',
		'links' => '',
		'maxwidth' => '240',
		'custom_links_target' => '',
		'position' => '25%|30%,35%|20%,45%|60%,75%|20%',
		'containerwidth' => '',
		'marginoffset' => '',
		'icontype' => 'dot',
		'fonticon' => '',
		'isdisplayall' => 'off',
		'displayednum' => '1',
		'startnumber' => '1',
		'extra_class' => ''
		), $atts ) );

	$image_full = wp_get_attachment_image_src($image, 'full');
	$position = explode(',', $position);
	$color = explode(',', $color);
	$arrowposition = explode(',', $arrowposition);
	$links = explode(',', $links);
	$fonticon = explode(',', $fonticon);
	$i = -1;
	$is_new_tag = false;
          $content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content
          if(strpos($content, '[/cwstips]')===false){
          	$content = str_replace('</div>', '', trim($content));
          	$contentarr = explode('<div class="tooltip-content">', trim($content));
          }else{
          	$content = str_replace('[/cwstips]', '', trim($content));
          	$contentarr = explode('[cwstips]', trim($content));
          	$is_new_tag = true;
          }
          $pulseborder = "";
          $ispulse = $ispulse == "yes" ? $pulsecolor : "";
          array_shift($contentarr);
          $output = $tooltipcontent = '';
          $output .= '<div style="width:'.$containerwidth.';" class="cwstooltip-wrapper '.$extra_class.'" data-opacity="'.$opacity.'" data-tooltipanimation="'.$tooltipanimation.'" data-tooltipstyle="'.$tooltipstyle.'" data-trigger="'.$trigger.'" data-maxwidth="'.$maxwidth.'" data-marginoffset="'.$marginoffset.'" data-isdisplayall="'.$isdisplayall.'" data-displayednum="'.$displayednum.'">';

          $image_temp = $imagethumb = "";
          $fullimage = $image_full[0];
          $imagethumb = $fullimage;
          $attachment = get_post($image);
          if($width!=""){
          	if(function_exists('wpb_resize')){
          		$image_temp = wpb_resize($image, null, $width, null);
          		$imagethumb = $image_temp['url'];
          		if($imagethumb=="") $imagethumb = $fullimage;
          	}
          }

          $output .= '<img src="'.$imagethumb.'" alt="'.get_post_meta($attachment->ID, '_wp_attachment_image_alt', true ).'" />';
          $output .= '<div class="cws-hotspots">';
          foreach ($contentarr as $key => $thecontent) {
          	$i++;
          	$tooltipcontent = '';
          	if(!isset($position[$i])) $position[$i] = '25%|25%';
          	if(!isset($fonticon[$i])) $fonticon[$i] = '';
          	$iconposition = explode('|', trim($position[$i]));
          	if(!isset($iconposition[0])) $iconposition[0] = '25%';
          	if(!isset($iconposition[1])) $iconposition[1] = '25%';
          	if(!isset($color[$i])) $color[$i] = '';
          	if(!isset($arrowposition[$i])) $arrowposition[$i] = 'top';
          	if(!isset($links[$i])) $links[$i] = '';
          	if($color[$i]!="") {
          		$iconcolor = $color[$i];
          	}else{
          		$iconcolor = $iconbackground;
          	}
          	$tooltipcontent = trim($thecontent); 
          	$tooltipcontent = preg_replace("/(^)?(<br\s*\/?>\s*)+$/", "", $tooltipcontent);
          	$tooltipcontent = preg_replace('/^(<br \/>)*/', "", $tooltipcontent);
          	$tooltipcontent = preg_replace('/^(<\/p>)*/', "", $tooltipcontent);
          	$output .= '<div class="hotspot-item '.$ispulse.' '.$pulseborder.'" style="top:'.$iconposition[0].';left:'.$iconposition[1].';" data-top="'.$iconposition[0].'" data-left="'.$iconposition[1].'">';
          	if($links[$i]!=""){
          		$output .= '<a href="'.$links[$i].'" class="cws-tooltip" style="background:'.$iconcolor.';" data-tooltip="'.htmlspecialchars($tooltipcontent).'" data-arrowposition="'.trim($arrowposition[$i]).'" target="'.$custom_links_target.'">';
          	}else{
          		$output .= '<a href="#" class="cws-tooltip" style="background:'.$iconcolor.';" data-tooltip="'.htmlspecialchars($tooltipcontent).'" data-arrowposition="'.trim($arrowposition[$i]).'">';
          	}
          	if($icontype=="number"){
          		if($startnumber!=1){
          			$output .= '<i>';
          			$output .= $startnumber+$i;
          			$output .= '</i>';
          		}else{
          			$output .= '<i>';
          			$output .= $i+1;
          			$output .= '</i>';
          		}
          	}else if($icontype=="icon"){
          		if($fonticon[$i]!=""){
          			$output .= '<i class="fa '.$fonticon[$i].'" style="color:'.$circlecolor.';"></i>';
          		}else{
          			$output .= '<span style="background:'.$circlecolor.';">';
          			$output .= '</span>';
          		}
          	}else{
          		$output .= '<span style="background:'.$circlecolor.';">';
          		$output .= '</span>';
          	}

          	$output .= '</a>';
          	$output .= '</div>';
          }
          $output .= '</div>';
          $output .= '</div>';

          return $output;
}

add_shortcode( 'cws_sc_tips', 'cws_vc_shortcode_sc_tips' );

/**
 * Processes like/unlike
 * @since    0.5
 */
add_action( 'wp_enqueue_scripts', 'cws_vc_shortcode_sl_enqueue_scripts' );
function cws_vc_shortcode_sl_enqueue_scripts() {
	wp_enqueue_script( 'simple-likes-public-js', CWS_SHORTCODES_PLUGIN_URL . '/assets/js/simple-likes-public.js', array( 'jquery' ), '0.5', true );
	wp_localize_script( 'simple-likes-public-js', 'simpleLikes', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'like' => esc_html__( 'Like', 'cws-essentials' ),
		'unlike' => esc_html__( 'Unlike', 'cws-essentials' )
		) ); 
}

add_action( 'wp_ajax_nopriv_cws_vc_shortcode_process_simple_like', 'cws_vc_shortcode_process_simple_like' );
add_action( 'wp_ajax_cws_vc_shortcode_process_simple_like', 'cws_vc_shortcode_process_simple_like' );
function cws_vc_shortcode_process_simple_like() {
	// Security
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
	if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( esc_html__( 'Not permitted', 'cws-essentials' ) );
	}
	// Test if javascript is disabled
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
	// Test if this is a comment
	$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
	// Base variables
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	$result = array();
	$post_users = NULL;
	$like_count = 0;
	// Get plugin options
	if ( $post_id != '' ) {
		$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( !cws_vc_shortcode_already_liked( $post_id, $is_comment ) ) { // Like the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = cws_vc_shortcode_post_user_likes( $user_id, $post_id, $is_comment );
				if ( $is_comment == 1 ) {
					// Update User & Comment
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					}
				} else {
					// Update User & Post
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_user_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = cws_vc_shortcode_sl_get_ip();
				$post_users = cws_vc_shortcode_post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ++$count;
			$response['status'] = "liked";
			$response['icon'] = cws_vc_shortcode_get_liked_icon();
		} else { // Unlike the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = cws_vc_shortcode_post_user_likes( $user_id, $post_id, $is_comment );
				// Update User
				if ( $is_comment == 1 ) {
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, "_comment_like_count", --$user_like_count );
					}
				} else {
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}
				}
				// Update Post
				if ( $post_users ) {	
					$uid_key = array_search( $user_id, $post_users );
					unset( $post_users[$uid_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = cws_vc_shortcode_sl_get_ip();
				$post_users = cws_vc_shortcode_post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					$uip_key = array_search( $user_ip, $post_users );
					unset( $post_users[$uip_key] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else { 
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
			$response['status'] = "unliked";
			$response['icon'] = cws_vc_shortcode_get_unliked_icon();
		}
		if ( $is_comment == 1 ) {
			update_comment_meta( $post_id, "_comment_like_count", $like_count );
			update_comment_meta( $post_id, "_comment_like_modified", date( 'Y-m-d H:i:s' ) );
		} else { 
			update_post_meta( $post_id, "_post_like_count", $like_count );
			update_post_meta( $post_id, "_post_like_modified", date( 'Y-m-d H:i:s' ) );
		}
		$response['count'] = get_like_count( $like_count );
		$response['testing'] = $is_comment;
		if ( $disabled == true ) {
			if ( $is_comment == 1 ) {
				wp_redirect( get_permalink( get_the_ID() ) );
				exit();
			} else {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			}
		} else {
			wp_send_json( $response );
		}
	}
}

function cws_vc_shortcode_already_liked( $post_id, $is_comment ) {
	$post_users = NULL;
	$user_id = NULL;
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
	} else { // user is anonymous
		$user_id = cws_vc_shortcode_sl_get_ip();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" ); 
		if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
			$post_users = $post_meta_users[0];
		}
	}
	if ( is_array( $post_users ) && in_array( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
}

function cws_vc_shortcode_get_simple_likes_button( $post_id, $is_comment = NULL ) {
	$is_comment = ( NULL == $is_comment ) ? 0 : 1;
	$output = '';
	$nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
	if ( $is_comment == 1 ) {
		$post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
		$comment_class = esc_attr( ' sl-comment' );
		$like_count = get_comment_meta( $post_id, "_comment_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	} else {
		$post_id_class = esc_attr( ' sl-button-' . $post_id );
		$comment_class = esc_attr( '' );
		$like_count = get_post_meta( $post_id, "_post_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	}
	$count = get_like_count( $like_count );
	$icon_empty = cws_vc_shortcode_get_unliked_icon();
	$icon_full = cws_vc_shortcode_get_liked_icon();
	// Loader
	$loader = '<span class="sl-loader"></span>';
	// Liked/Unliked Variables
	if ( cws_vc_shortcode_already_liked( $post_id, $is_comment ) ) {
		$class = esc_attr( ' liked' );
		$title = esc_html__( 'Unlike', 'cws-essentials' );
		$icon = $icon_full;
	} else {
		$class = '';
		$title = esc_html__( 'Like', 'cws-essentials' );
		$icon = $icon_empty;
	}
	$output = '<span class="sl-wrapper"><a href="' . admin_url( 'admin-ajax.php?action=cws_vc_shortcode_process_simple_like' . '&post_id=' . $post_id . '&nonce=' . $nonce . '&is_comment=' . $is_comment . '&disabled=true' ) . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
	return $output;
} 

add_shortcode( 'jmliker', 'cws_vc_shortcode_sl_shortcode' );
function cws_vc_shortcode_sl_shortcode() {
	return cws_vc_shortcode_get_simple_likes_button( get_the_ID(), 0 );
}

function cws_vc_shortcode_post_user_likes( $user_id, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_id, $post_users ) ) {
		$post_users['user-' . $user_id] = $user_id;
	}
	return $post_users;
}

function cws_vc_shortcode_post_ip_likes( $user_ip, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
	// Retrieve post information
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !in_array( $user_ip, $post_users ) ) {
		$post_users['ip-' . $user_ip] = $user_ip;
	}
	return $post_users;
}

function cws_vc_shortcode_sl_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
}

function cws_vc_shortcode_get_liked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="fas fa-heart"></i> */
	$icon = '<span class="sl-icon unliked"></span>';
	return $icon;
}

function cws_vc_shortcode_get_unliked_icon() {
	/* If already using Font Awesome with your theme, replace svg with: <i class="far fa-heart"></i> */
	$icon = '<span class="sl-icon liked"></span>';
	return $icon;
}

function cws_vc_shortcode_sl_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number/1000, $precision ).'K';
	} else if ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number/1000000, $precision ).'M';
	} else if ( $number >= 1000000000 ) {
		$formatted = number_format( $number/1000000000, $precision ).'B';
	} else {
		$formatted = $number; // Number is less than 1000
	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
}

function get_like_count( $like_count ) {
	$like_text = esc_html__( '0', 'cws-essentials' );
	if ( is_numeric( $like_count ) && $like_count > 0 ) { 
		$number = cws_vc_shortcode_sl_format_count( $like_count );
	} else {
		$number = $like_text;
	}
	/*
	*** Uncomment this to add "like" & "likes" word
	*
	if( $number == 1 ){
	 	$count = '<span class="sl-count">' . $number . ' '. esc_html__('like', 'cws-essentials'). '</span>';	
	} else {
	 	$count = '<span class="sl-count">' . $number . ' '. esc_html__('likes', 'cws-essentials'). '</span>';
	}
	*/
	$count = '<span class="sl-count">' . $number . '</span>';

	return $count;
}

// User Profile List
add_action( 'show_user_profile', 'cws_vc_shortcode_show_user_likes' );
add_action( 'edit_user_profile', 'cws_vc_shortcode_show_user_likes' );
function cws_vc_shortcode_show_user_likes( $user ) { ?>        
	<table class="form-table">
		<tr>
			<th><label for="user_likes"><?php esc_html_e( 'You Like:', 'cws-essentials' ); ?></label></th>
			<td>
				<?php
				$types = get_post_types( array( 'public' => true ) );
				$args = array(
					'numberposts' => -1,
					'post_type' => $types,
					'meta_query' => array (
						array (
							'key' => '_user_liked',
							'value' => $user->ID,
							'compare' => 'LIKE'
							)
						) );		
				$sep = '';
				$like_query = new WP_Query( $args );
				if ( $like_query->have_posts() ) : ?>
					<p>
						<?php while ( $like_query->have_posts() ) : $like_query->the_post(); 
						echo sprintf('%s', $sep); ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
						<?php
						$sep = ' &middot; ';
						endwhile; 
						?>
					</p>
				<?php else : ?>
				<p><?php esc_html_e( 'You do not like anything yet.', 'cws-essentials' ); ?></p>
				<?php 
				endif; 
				wp_reset_postdata(); 
				?>
			</td>
		</tr>
	</table>
<?php }

?>