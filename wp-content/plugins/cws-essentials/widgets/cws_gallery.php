<?php
	/**
	 * CWS Gallery Widget Class
	 */

class CWS_Gallery extends WP_Widget {
	public $fields = array();
	public function init_fields() {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget title', 'cws-essentials' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
				'value' => '',
			),
			'gallery' => array(
				'title' => esc_html__( 'Gallery', 'cws-essentials' ),
				'type' => 'gallery'
			),
			'grid_col' => array(
				'type' => 'select',
				'title' => esc_html__( 'Gallery Columns', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'source' => array(
					'1' => array(esc_html__( '1 Column', 'cws-essentials' ), true),
					'2' => array(esc_html__( '2 Columns', 'cws-essentials' ), false),
					'3' => array(esc_html__( '3 Columns', 'cws-essentials' ), false),
					'4' => array(esc_html__( '4 Columns', 'cws-essentials' ), false),
				),
			),
			'add_carousel' => array(
				'title' => esc_html__( 'Add Carousel', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'data-options="d:grid_col"',
			),

			'gradient_first_color' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'From', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-default-color="'.METAMAX_FIRST_COLOR.'"',
			),
			'gradient_second_color' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'To', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'atts' => 'data-default-color="#0eecbd"',
			),
			'gradient_type' => array(
				'title' => esc_html__( 'Gradient type', 'cws-essentials' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'linear' => array( esc_html__( 'Linear', 'cws-essentials' ), 	true, 'e:gradient_linear_angle;d:gradient_radial_shape' ),
					'radial' =>array( esc_html__( 'Radial', 'cws-essentials' ), false,	'd:gradient_linear_angle;e:gradient_radial_shape' ),
				),
			),
			'gradient_linear_angle' => array(
				'type'      => 'number',
				'title'     => esc_html__( 'Angle', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'value' => '45',
			),
			'gradient_radial_shape' => array(
				'title' => esc_html__( 'Gradient type', 'cws-essentials' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'simple' => array( esc_html__( 'Simple', 'cws-essentials' ), 	true, 'e:gradient_radial_type;d:gradient_radial_size_key;d:gradient_radial_size' ),
					'extended' =>array( esc_html__( 'Extended', 'cws-essentials' ), false, 'd:gradient_radial_type;e:gradient_radial_size_key;e:gradient_radial_size' ),
				),
			),
			'gradient_radial_type' => array(
				'title' => esc_html__( 'Gradient type', 'cws-essentials' ),
				'type' => 'radio',
				'addrowclasses' => 'disable',
				'value' => array(
					'ellipse' => array( esc_html__( 'Ellipse', 'cws-essentials' ), 	true ),
					'circle' =>array( esc_html__( 'Cirle', 'cws-essentials' ), false ),
				),
			),
			'gradient_radial_size_key' => array(
				'type' => 'select',
				'title' => esc_html__( 'Size keyword', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'source' => array(
					'closest-side' => array(esc_html__( 'Closest side', 'cws-essentials' ), false),
					'farthest-side' => array(esc_html__( 'Farthest side', 'cws-essentials' ), false),
					'closest-corner' => array(esc_html__( 'Closest corner', 'cws-essentials' ), false),
					'farthest-corner' => array(esc_html__( 'Farthest corner', 'cws-essentials' ), true),
				),
			),
			'gradient_radial_size' => array(
				'type'      => 'text',
				'title'     => esc_html__( 'Size', 'cws-essentials' ),
				'addrowclasses' => 'disable',
				'atts' => 'placeholder="'.esc_attr__('Two space separated percent values, for example (60% 55%)', 'cws-essentials').'"',
			),

		);
	}
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cws-gallery', 'description' => esc_html__( 'Create gallery from media', 'cws-essentials' ) );
		parent::__construct( 'cws-gallery', esc_html__( 'CWS Gallery', 'cws-essentials' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'gallery' => '',
			'grid_col' => '1',
			'add_carousel' => false,
		), $instance));
		global $cws_theme_funcs;

		switch ($grid_col) {
			case '4':
				$col = 3;
				break;
			case '3':
				$col = 4;
				break;
			case '2':
				$col = 6;
				break;
			case '1':
				$col = 12;
				break;	
		}
		$title = esc_html($title);
		$match = preg_match_all("/\d+/",$gallery,$images);
		$section_atts = '';

		if ($match) {
			$images = $images[0];
			$image_srcs = array();
			foreach ( $images as $image ) {
				$image_src = wp_get_attachment_image_src($image,'full');
				if ( $image_src ){
					$image_url = $image_src[0];
					array_push( $image_srcs, $image_url );
				}
			}
			$dims['width'] = 737;
			$dims['height'] = 737;
			$dims['crop'] = true;

			echo sprintf('%s',$before_widget);

			if (!empty( $title )){
				echo sprintf("%s", $before_title) . esc_html($title) . $after_title;
			}

			if( $add_carousel ){ 
				wp_enqueue_script ('slick-carousel');
				$section_atts .= " data-columns='1'";
				$section_atts .= " data-pagination='on'";
				$section_atts .= " data-draggable='on'";
				$section_atts .= " data-auto-height='on'";
			}

			echo "<div class='cws-widget-gallery clearfix".((bool)$add_carousel ? ' widget-carousel' : '')."'>";
				if( $add_carousel ){
					echo "<div class='cws-carousel-wrapper'".$section_atts.">";
						echo "<div class='cws-carousel'>";
				}
					foreach ( $image_srcs as $image_src ) {
						$img_obj = cws_get_img( $image_src, $dims , true );
						$img_url = isset( $img_obj[0] ) ? esc_url( $img_obj[0] ) : "";
						$thumb_url_retina = isset( $img_obj[3] ) ? esc_url($img_obj[3]) : "";
						$thumb_url_retina = $thumb_url_retina == null ? "data-no-retina" : "data-at2x='$thumb_url_retina'";

						echo "<div class='pic".(!(bool)$add_carousel ? ' vc_col-sm-'.$col : '')."'>";
							echo "<img src='$img_url' $thumb_url_retina alt />";
						echo "</div>";
					}
				if( $add_carousel ){
						echo "</div>";
					echo "</div>";
				}
			echo "</div>";

			echo sprintf('%s',$after_widget);
		}
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