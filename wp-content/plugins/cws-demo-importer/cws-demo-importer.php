<?php
/*
Plugin Name: CWS Demo Importer
Plugin URI: http://cwsthemes.com/
Description: Demo Importer for CWS Themes
Text Domain: cws_demo_imp
Version: 2.1.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'CWS_IMP_VERSION', '2.1.0' );

if (!defined('CWS_IMP_HOST'))
	define('CWS_IMP_HOST', 'http://up.cwsthemes.com/importer/');

if (!defined('CWS_IMP_PLUGIN_NAME'))
	define('CWS_IMP_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('CWS_IMP_PLUGIN_URL'))
	define('CWS_IMP_PLUGIN_URL', WP_PLUGIN_URL . '/' . CWS_IMP_PLUGIN_NAME);


add_action( 'admin_init', 'register_importers' );

add_filter( 'pre_set_site_transient_update_plugins', 'cws_check_for_update_imp' );
function cws_check_for_update_imp($transient) {
	if (empty($transient->checked))
		return $transient;
	$imp_path = CWS_IMP_PLUGIN_NAME . '/' . CWS_IMP_PLUGIN_NAME . '.php';

	$result = wp_remote_get(CWS_IMP_HOST . 'update.php');
	if ( isset($result->errors) ) {
		return $transient;
	} else {
		if (200 == $result['response']['code']) {
			$resp = json_decode($result['body']);
			if ( version_compare( CWS_IMP_VERSION, $resp->new_version, '<' ) ) {
				$transient->response[$imp_path] = $resp;
			}
		}
	}
	return $transient;
}

function cws_plugins_imp_api($res, $action = null, $args = null) {
	if ( ($action == 'plugin_information') && isset($args->slug) && ($args->slug == CWS_IMP_PLUGIN_NAME) ) {
		$result = wp_remote_get(CWS_IMP_HOST . 'update.php?info=1');
		if (200 == $result['response']['code']) {
			$res = json_decode($result['body'], true);
			$res = (object) array_map(__FUNCTION__, $res);
		}
	}
	return $res;
}

add_filter('plugins_api', 'cws_plugins_imp_api', 20, 3);

function register_importers() {
	register_importer( 'cws_demo_imp', esc_html__( 'CWS Demo Importer', 'cws_demo_imp' ), esc_html__( 'Import CWS theme\'s demo content.', 'cws_demo_imp'), 'cws_importer' );
}

add_action( 'admin_enqueue_scripts', 'cws_imp_enqueue', 11);

function cws_imp_enqueue($h) {
	if ('admin.php' === $h) {
		if (isset($_GET['import']) && 'cws_demo_imp' === $_GET['import'] && isset($_GET['step']) && '1' === $_GET['step']) {
			wp_enqueue_script( 'cws-imp-js',  plugin_dir_url( __FILE__ ) . 'assets/js/imp.js', '', CWS_IMP_VERSION );
			wp_enqueue_style( 'cws-imp-css',  plugin_dir_url( __FILE__ ) . 'assets/css/imp.css', '', CWS_IMP_VERSION );
		}
	}
}

function cws_importer() {
	require_once dirname( __FILE__ ) . '/importer.php';
	// Dispatch
	$importer = new WP_CWS_Demo_Import();
	$importer->dispatch();
}

add_action( 'wp_ajax_cws_imp_run', 'cws_imp_run' );

function cws_imp_run() {
	if ( wp_verify_nonce( $_REQUEST['nonce'], 'cws_imp_ajax') ) {
		$id = $_POST['id'];
		$options = isset($_POST['options']) ? $_POST['options'] : array();
		$upload_dir = wp_upload_dir();
		$xml_upload_dir = $upload_dir['basedir'] . '/cws_demo/';
		$demo_f = sprintf($xml_upload_dir. 'demo%02d.xml', $id);
		require_once dirname( __FILE__ ) . '/importer.php';
		$importer = new WP_CWS_Demo_Import();
		if (file_exists($demo_f)) {
			$importer->id = $demo_f;
			$messages = '';
			ob_start();
			$importer->import( $importer->id, $options, $id );
			$messages = ob_get_clean();
			if (!is_string($messages)) {
				$messages = '';
			}
			echo json_encode(array('id' => $id, 'messages' => $messages));
		} else {
			$importer->finalize();
			delete_option('cwsimp_temp');
		}
	}
	die();
}
?>