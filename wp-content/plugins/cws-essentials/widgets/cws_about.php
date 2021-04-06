<?php
	/**
	 * CWS About Widget Class
	 */

class CWS_About extends WP_Widget {
	public $fields = array();
	public function init_fields() {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget title', 'cws-essentials' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
				'value' => '',
			),
			'avatar' => array(
				'title' => esc_html__( 'Avatar', 'cws-essentials' ),
				'addrowclasses' => 'wide_picture',
				'type' => 'media',
			),
			'width' => array(
				'type' => 'number',
				'title' => esc_html__( 'Width (px)', 'cws-essentials' ),
				'value' => '270',
			),
			'height' => array(
				'type' => 'number',
				'title' => esc_html__( 'Height (px)', 'cws-essentials' ),
				'value' => '270',
			),
			'link' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Avatar link', 'cws-essentials' ),
				'atts' => 'placeholder="'.esc_attr__('http://', 'cws-essentials').'"',
			),
			'name' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Name', 'cws-essentials' ),
			),
			'position' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Position', 'cws-essentials' ),
			),
			'description' => array(
				'title' => esc_html__( 'About Me', 'cws-essentials' ),
				'type' => 'textarea',
				'atts' => 'rows="10" placeholder="'.esc_attr__('Enter information about self', 'cws-essentials').'"',
				'value' => '',
			),
			'signature' => array(
				'title' => esc_html__( 'Signature', 'cws-essentials' ),
				'addrowclasses' => 'wide_picture',
				'type' => 'media',
			),
		);
	}
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cws-about', 'description' => esc_html__( 'Add information about yourself', 'cws-essentials' ) );
		parent::__construct( 'cws-about', esc_html__( 'CWS About', 'cws-essentials' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'avatar' => '',
			'width' => '700',
			'height' => '700',
			'link' => '',
			'name' => '',
			'position' => '',
			'description' => '',
			'signature' => '',
		), $instance));
		global $cws_theme_funcs;

		$title = esc_html($title);

		$avatar_id = !empty($avatar) ? $avatar['id'] : '';

		if ( !empty($width) || !empty($height) ) {
			$thumb_obj = cws_get_img( $avatar_id, array( 'width' => $width, 'height' => $height, 'crop' => true ), false );
			$avatar_src = $thumb_obj[0];
		}
		$signature_id = !empty($signature) ? $signature['id'] : '';

		if (!empty($signature_id)) { 
			$thumb_title = get_post($signature['id'])->post_title;
			$thumb_alt = get_post_meta($signature['id'], '_wp_attachment_image_alt', true);
			$thumb_alt = !empty($thumb_alt) ? $thumb_alt : $thumb_title;

			$img_src = wp_get_attachment_image_url( $signature['id'], 'medium' );
			$img_srcset = wp_get_attachment_image_srcset( $signature['id'], 'medium' );
			$img_sizes = wp_get_attachment_image_sizes($signature['id'], 'medium');
		}

		echo sprintf('%s',$before_widget);
		if (!empty( $title )){
			echo sprintf("%s", $before_title);
			echo esc_html($title) . $after_title;
		}


		echo "<div class='cws-about-main-wrapper'>";
			if (!empty($avatar_src)) { ?>
				<div class="user-avatar">
				<?php if (!empty($link)) { ?> <a href="<?php echo esc_url($link) ?>"> <?php } ?>
					<img src="<?php echo esc_url($avatar_src) ?>" alt="<?php echo (!empty($name) ? esc_attr($name) : '') ?>" />
				<?php if (!empty($link)) { ?> </a> <?php } ?>
				</div>
			<?php }

			echo "<div class='cws-textwidget-content'>";
				ob_start();
				?>
					<div class="about-me">
						<?php echo (!empty($name) ? '<h4 class="user-name">'.$name.'</h4>' : '') ?>
						<?php echo (!empty($position) ? '<h5 class="user-position">'.$position.'</h5>' : '') ?>
						<?php echo (!empty($description) ? '<p class="user-description">'.esc_html($description).'</p>' : '') ?>
						<?php if (!empty($signature_id)) { ?>
							<div class="user-signature">
								<?php echo "<img src='".esc_url($img_src)."' srcset='".esc_attr($img_srcset)."' sizes='".esc_attr($img_sizes)."' alt='".esc_attr($thumb_alt)."'/>"; ?>
							</div>
						<?php } ?>
					</div>
				<?php
				echo ob_get_clean();
			echo "</div>";
		echo "</div>";
		
		echo sprintf('%s',$after_widget);	
	}

	function update( $new_instance, $old_instance ) {
		$instance = (array)$new_instance;
		foreach ($new_instance as $key => $v) {
			if ($v == 'on') {
				$v = '1';
			}
			switch ($this->fields[$key]['type']) {
				case 'text':
					$instance[$key] = strip_tags($v);
					break;
			}
		}
		return $instance;
	}

	function form( $instance ) {
		$this->init_fields();
		if (function_exists('cws_core_build_layout') ) {
			echo cws_core_build_layout($instance, $this->fields, 'widget-' . $this->id_base . '[' . $this->number . '][');
		}
	}
}
?>