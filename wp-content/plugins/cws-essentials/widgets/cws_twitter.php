<?php
	/**
	 * CWS Twitter Widget Class
	 */
class CWS_Twitter extends WP_Widget {
	function init_fields () {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget Title', 'cws-essentials' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
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
			'items' => array(
				'title' => esc_html__( 'Tweets to extract', 'cws-essentials' ),
				'type' => 'number',
				'value' => get_option( 'posts_per_page' )
			),
			'visible' => array(
				'title' => esc_html__( 'Tweets to show', 'cws-essentials' ),
				'type' => 'number',
				'value' => get_option( 'posts_per_page' )
			),
			'showdate' => array(
				'title' => esc_html__( 'Show date', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
			)
		);
	}

	function __construct(){
		$widget_ops = array('classname' => 'widget-cws-twitter', 'description' => esc_html__( 'CWS Twitter Widget', 'cws-essentials' ) );
		parent::__construct('cws-twitter', esc_html__('CWS Twitter', 'cws-essentials' ), $widget_ops);
	}

	function widget ( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
		), $instance));

		echo sprintf("%s", $before_widget);
			if ( !empty( $title ) ){
				echo sprintf("%s", $before_title). esc_html($title) . $after_title;
			}

			$twitter_args = array(
				'in_widget' => true
			);
			if ( isset( $instance['items'] ) ) $twitter_args['items'] = $instance['items'];
			if ( isset( $instance['visible'] ) ) $twitter_args['visible'] = $instance['visible'];
			if ( isset( $instance['showdate'] ) ) $twitter_args['showdate'] = $instance['showdate'];

			echo cws_twitter_renderer( $twitter_args );
		echo sprintf("%s", $after_widget);
	}

	function form ( $instance ){
		if (function_exists('getTweets')) {
			$this->init_fields();
			if (function_exists('cws_core_build_layout') ) {
				echo cws_core_build_layout($instance, $this->fields, 'widget-' . $this->id_base . '[' . $this->number . '][');
			}
		} else {
			echo 'You need to install and activate <b>oAuth Twitter Feed for Developers</b> plugin';
		}
	}
}
?>