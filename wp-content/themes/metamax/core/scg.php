<?php
class Metamax_SCG {
	private $cws_tmce_sc_settings_config = array();

	public function __construct() {
		$this->init();
	}

	private function init() {
		add_filter( 'mce_buttons_3', array($this, 'mce_sc_buttons') );
		add_filter( 'mce_external_plugins', array($this, 'mce_sc_plugin') );
		add_action( 'wp_ajax_cws_ajax_sc_settings', array($this, 'ajax_sc_settings_callback') );
		add_action( 'admin_enqueue_scripts', array($this, 'scg_scripts_enqueue'), 11 );
		$body_font = cws_core_get_option( 'body-font' );
		$body_font_color = $body_font['color'];

		$this->cws_tmce_sc_settings_config = array(
			'embed' => array(
				'title' => esc_html__( 'Embed audio/video file', 'metamax' ),
				'icon' => 'dashicons dashicons-format-video',
				'fields' => array(
					'url' => array(
						'title' => esc_html__( 'Url', 'metamax' ),
						'desc' => esc_html__( 'Embed url', 'metamax' ),
						'type' => 'text',
					),
					'width' => array(
						'title' => esc_html__( 'Width', 'metamax' ),
						'desc' => esc_html__( 'Max width in pixels', 'metamax' ),
						'type' => 'number',
					),
					'height' => array(
						'title' => esc_html__( 'Height', 'metamax' ),
						'desc' => esc_html__( 'Max height in pixels', 'metamax' ),
						'type' => 'number',
					)
				)
			),
			'dropcap' => array(
				'title' => esc_html__( 'CWS Dropcap', 'metamax' ),
				'icon' => 'fas fa-font',
				'required' => 'single_char_selected',
				'paired' => true,
				'fields' => array(
					'dropcap_style' => array(
      					'title' => esc_html__( 'Letter Style', 'metamax' ),
      					'type' => 'select',
      					'source' => array(
      						'square' => array(esc_html__( 'Square', 'metamax' ), true),
      						'round' => array(esc_html__( 'Round', 'metamax' ), false),
       
      					),
      				),
					'dropcap_size' => array(
      					'title' => esc_html__( 'Dropcap size', 'metamax' ),
      					'desc' => esc_html__( 'in pixels', 'metamax' ),
      					'type' => 'number',
      					'value' => '50'
     				),  
     				'dropcap_border' => array(
      					'title' => esc_html__( 'Border', 'metamax' ),
      					'type' => 'checkbox',
     				), 
     				'dropcap_fill' => array(
      					'title' => esc_html__( 'Fill', 'metamax' ),
      					'type' => 'checkbox',
     				),       				
				)
			),
			'mark' => array(
				'title' => esc_html__( 'CWS Mark Selection', 'metamax' ),
				'icon' => 'fas fa-pencil-alt',
				'paired' => true,
				'required' => 'selection',
				'fields' => array(
					'font_color' => array(
						'title' => esc_html__( 'Font Color', 'metamax' ),
						'type' => 'text',
						'atts' => 'data-default-color="#fff"',
					),
					'bg_color' => array(
						'title' => esc_html__( 'Background Color', 'metamax' ),
						'type' => 'text',
						'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
					)
				)
			),
			'custom_list' => array(
				'title' => esc_html__( 'CWS List Selection', 'metamax' ),
				'icon' => 'fas fa-list-ul',
				'required' => 'list_selection',
				'fields' => array(
					'list_columns' => array(
						'title' => esc_html__( 'Columns', 'metamax' ),
						'type' => 'text',
						'addrowclasses' => 'fai',
					),
					'list_style' => array(
						'title' => esc_html__( 'List Style', 'metamax' ),
						'type' => 'select',
						'source' => array(
							'default_style' => array(esc_html__( 'Default', 'metamax' ), true, 'd:icon_list_bg_color;d:icon;'),
							'checkmarks_style' => array(esc_html__( 'Checkmarks', 'metamax' ), false, 'e:icon_list_bg_color;d:icon;'),
							'dash_style' => array(esc_html__( 'Dash', 'metamax' ), false, 'd:icon_list_bg_color;d:icon;'),
							'special_style' => array(esc_html__( 'Special', 'metamax' ), false, 'd:icon_list_bg_color;d:icon;'),
							'custom_icon_style' => array(esc_html__( 'Custom Icon', 'metamax' ), false, 'd:icon_list_bg_color;e:icon;'),
						),
					),
					'icon_list_color' => array(
						'title' => esc_html__( 'Icon color', 'metamax' ),
						'type' => 'text',
						'atts' => 'data-default-color="'.METAMAX_FIRST_COLOR.'"',
					),
                    'icon_list_bg_color' => array(
                        'title' => esc_html__( 'Icon background color', 'metamax' ),
                        'type' => 'text',
                        'addrowclasses' => 'disable',
                        'atts' => 'data-default-color=""',
                    ),
                    'icon' => array(
						'title' => esc_html__( 'Icon', 'metamax' ),
						'type' => 'select',
						'addrowclasses' => 'fai disable',
						'source' => 'fa',
					),
				)
			),
			'carousel' => array(
				'title' => esc_html__( 'CWS Shortcode Carousel', 'metamax' ),
				'icon' => 'fas fa-arrows-alt-h',
				'required' => 'sc_selection_or_nothing',
				'paired' => true,
				'def_content' => "<ul><li>" . esc_html__( 'Some content here', 'metamax' ) . "</li><li>" . esc_html__( 'Some content here', 'metamax' ) . "</li></ul>",
				'fields' => array(
					'title' => array(
						'title' => esc_html__( 'Carousel title', 'metamax' ),
						'type' => 'text',
					),
					'columns' => array(
						'title' => esc_html__( 'Columns', 'metamax' ),
						'type' => 'select',
						'source' => array(
							'1' => array(esc_html__( 'One', 'metamax' ), true),
							'2' => array(esc_html__( 'Two', 'metamax' ), false),
							'3' => array(esc_html__( 'Three', 'metamax' ), false),
							'4' => array(esc_html__( 'Four', 'metamax' ), false)
						),
					)
				)
			),
		);
	}

	public function scg_scripts_enqueue($a) {
		if( $a == 'post-new.php' || $a == 'post.php' ) {
			$prefix = 'cws_sc_';
			$data = array();
			foreach ( $this->cws_tmce_sc_settings_config as $sect_name => $section ) {
				array_push( $data, array(
					'sc_name' => $prefix . $sect_name,
					'title' => isset( $section['title'] ) ? $section['title'] : '',
					'icon' => isset( $section['icon'] ) ? $section['icon'] : '',
					'required' => isset( $section['required'] ) ? $section['required'] : '',
					'def_content' => isset( $section['def_content'] ) ? $section['def_content'] : '',
					'has_options' => isset( $section['fields'] ) && is_array( $section['fields'] ) && !empty( $section['fields'] )
				));
			}
			wp_localize_script('cwsfw-main-js', 'cws_sc_data', $data);
			wp_register_script( 'cws-redux-sc-settings', get_template_directory_uri() . '/core/js/cws_sc_settings_controller.js', array( 'jquery' ) );
			wp_enqueue_script( 'cws-redux-sc-settings' );
		}
	}

	public function mce_sc_buttons ( $buttons ) {
		$cws_sc_names = array_keys( $this->cws_tmce_sc_settings_config );
		$cws_sc_prefix = 'cws_sc_';
		foreach ($cws_sc_names as $key => $v) {
			$cws_sc_names[$key] = $cws_sc_prefix . $v;
		}
		$buttons = array_merge( $buttons, $cws_sc_names );
		return $buttons;
	}

	public function mce_sc_plugin ( $plugin_array ) {
		$plugin_array['cws_shortcodes'] = get_template_directory_uri() . '/core/js/cws_tmce.js';
		return $plugin_array;
	}

	public function ajax_sc_settings_callback () {
		$shortcode = trim( $_POST['shortcode'] );
		$prefix = 'cws_sc_';
		$selection = isset( $_POST['selection'] ) ? stripslashes( trim( $_POST['selection'] ) ) : '';
		$def_content = isset( $_POST['def_content'] ) ? trim( $_POST['def_content'] ) : '';
		$shortcode = substr($shortcode, 7);
		$paired = isset($this->cws_tmce_sc_settings_config[$shortcode]['paired']) && $this->cws_tmce_sc_settings_config[$shortcode]['paired']? '1' : '0';
		?>
		<div class="cws_sc_settings_container">
			<input type="hidden" name="cws_sc_name" id="cws_sc_name" value="<?php echo esc_attr($shortcode); ?>" />
			<input type="hidden" name="cws_sc_selection" id="cws_sc_selection" value="<?php echo apply_filters( 'cws_dbl_to_sngl_quotes', $selection); ?>" />
			<input type="hidden" name="cws_sc_def_content" id="cws_sc_def_content" value="<?php echo esc_attr($def_content); ?>" />
			<input type="hidden" name="cws_sc_prefix" id="cws_sc_prefix" value="<?php echo esc_attr($prefix); ?>" />
			<input type="hidden" name="cws_sc_paired" id="cws_sc_paired" value="<?php echo esc_attr($paired); ?>" />
	<?php
		$meta = array(
			array (
				'text' => $selection,
				)
			);
		$sc_fields = $this->cws_tmce_sc_settings_config[$shortcode]['fields'];

		if (function_exists('cws_core_cwsfw_fillMbAttributes')) {
			cws_core_cwsfw_fillMbAttributes( $meta, $sc_fields );
			echo cws_core_cwsfw_print_layout($sc_fields, 'cws_sc_');
		}

	?>
		<input type="submit" class="button button-primary button-large" id="cws_insert_button" value="<?php esc_attr_e('Insert Shortcode', 'metamax' ) ?>">
		</div>
	<?php
		wp_die();
	}
}
?>