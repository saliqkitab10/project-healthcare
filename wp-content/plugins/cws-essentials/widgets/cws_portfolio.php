<?php
	/**
	 * Latest Posts Widget Class
	 */
class CWS_Portfolio extends WP_Widget {

	function init_fields() {
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
			'layout' => array(
				'title' => esc_html__( 'Layout', 'cws-essentials' ),
				'type' => 'radio',
				'value' => array(
					'grid' => array( esc_html__( 'Grid', 'cws-essentials' ),  true, 'e:columns;e:spacings;' ),
					'carousel' =>array( esc_html__( 'Carousel', 'cws-essentials' ), false, 'd:columns;d:spacings;' ),
				),
			),
			'cats' => array(
				'type' => 'taxonomy',
				'title' => esc_html__( 'Categories', 'cws-essentials' ),
				'atts' => 'multiple',
				'taxonomy' => 'cws_portfolio_cat',
				'source' => array(),
			),
			'spacings' => array(
				'title' => esc_html__( 'Spacings (px)', 'cws-essentials' ),
				'type' => 'number',
                'addrowclasses' => 'disable',
				'value' => '7'
			),
			'columns' => array(
				'title' => esc_html__( 'Columns', 'cws-essentials' ),
				'type' => 'select',
                'addrowclasses' => 'disable',
				'source' => array(
					'1' => array('One Column', true, ''),
					'2' => array('Two Columns',false, ''),
					'3' => array('Three Columns',false, ''),
					'4' => array('Four Columns',false, '')
				),
			),
			'count' => array(
				'title' => esc_html__( 'Items Count', 'cws-essentials' ),
				'type' => 'number',
			),
		);
	}

	function __construct() {
        $widget_ops = array( 'classname' => 'widget-cws-portfolio', 'description' => esc_html__( 'Portfolio Items', 'cws-essentials' ) );
		parent::__construct( 'cws-portfolio-widget', esc_html__( 'CWS Portfolio', 'cws-essentials' ), $widget_ops );
	}

	function widget( $args, $instance ) {

		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'layout' => 'grid',
			'cats' => array(),
			'spacings' => '7',
			'columns' => '1',
			'count' => get_option( 'posts_per_page' ),
		), $instance));

		global $cws_theme_funcs;

		$carousel_mode = ($layout == 'carousel');
		$counter = 0;

		$use_blur = $cws_theme_funcs->cws_get_option( 'use_blur' );
		$use_blur = isset($use_blur) && !empty($use_blur) && ($use_blur == '1') ? true : false;

		$title = esc_html($title);
        $section_atts = '';

		$count = empty( $count ) ? get_option( 'posts_per_page' ) : $count;

		$query_args = array(
			'post_type' => 'cws_portfolio',
			'ignore_sticky_posts' => true,
			'post_status' => 'publish',
			'posts_per_page' => $count
		);

		$tax_query = array();
		if ( !empty( $cats ) ){
			$tax_query[] = array(
				'taxonomy' => 'cws_portfolio_cat',
				'field' => 'slug',
				'terms' => $cats
			);
		}

		if ( !empty( $tax_query ) ) $query_args['tax_query'] = $tax_query;

		$q = new WP_Query( $query_args );

		$several = $q->post_count > 1 ? true : false;
		$gallery_id = esc_attr(uniqid( 'cws-portfolio-gallery-' ));

		echo sprintf('%s',$before_widget);
			if ( !empty( $title ) ){
				echo sprintf("%s", $before_title)  . esc_html($title) . $after_title;
			}
			if ( $q->have_posts() ):

				if ( $carousel_mode ){
                    wp_enqueue_script ('slick-carousel');
                    $section_atts .= " data-columns='1'";
                    $section_atts .= " data-pagination='on'";
                    $section_atts .= " data-draggable='on'";
                    $section_atts .= " data-auto-height='on'";
				?>
					<div <?php post_class(array( 'cws-carousel-wrapper','portfolio-columns' )); echo ' ' . sprintf('%s', $section_atts); ?>>
                    <div class='cws-carousel'>
				<?php
				} else{ ?>
					<div <?php post_class(array( 'portfolio-item-thumbs','clearfix','portfolio-columns', "col-".$columns )); ?> style="margin:-<?php echo esc_attr($spacings); ?>px">
				<?php }

					while ( $q->have_posts() ):
						$q->the_post();
						if ( has_post_thumbnail() ){
							$dims = array();

							$img_url = esc_url(wp_get_attachment_url( get_post_thumbnail_id() ));

							$dims['width'] = 290;
							$dims['height'] = 290;
							$dims['crop'] = array(
								$cws_theme_funcs->cws_get_option( 'crop_x' ),
								$cws_theme_funcs->cws_get_option( 'crop_y' )
							);

							$thumb_obj = cws_get_img( get_post_thumbnail_id(), $dims, true );
							$thumb_url = esc_url($thumb_obj[0]);
							$retina_thumb_url = esc_url($thumb_obj[3]);

								if ( $carousel_mode && $counter <= 0 ){
									echo "<div class='item'>";
								}

								echo "<div class='portfolio-item-thumb'" . (!$carousel_mode ? " style='padding:"
                            .$spacings."px;'" : "" ) . ">";
									echo "<div class='pic'>";
										echo "<a href='$img_url' class='fancy'" . ( $several ? " data-fancybox-group='".esc_attr($gallery_id)."'" : "" ) . ">";
											if ( isset($thumb_obj[3]) ){
												echo "<img src='$thumb_url' data-at2x='$retina_thumb_url' alt />";
											}
											else{
												echo "<img src='$thumb_url' data-no-retina alt />";
											}
											if ( $use_blur ){
												echo "<img src='$thumb_url' class='blured-img' alt />";
											}
											echo "<div class='hover-effect'></div>";
										echo "</a>";
									echo "</div>";
								echo "</div>";

							if ( $carousel_mode ){
								if ( $counter >= 0 || (int)$q->current_post >= (int)$q->post_count-1 )
								{
									echo "</div>";
									$counter = 0;
								}
								else
								{
									$counter ++;
								}
							}
						}
					endwhile;
					wp_reset_postdata();
                if ( $carousel_mode ){
                    echo "</div>";
                }
				echo "</div>";
			else:
				echo do_shortcode( "[cws_sc_msg_box description='" . esc_html__( "There are no posts matching the query", "cws-essentials" ) . "' title=''][/cws_sc_msg_box]" );
			endif;
		echo sprintf('%s',$after_widget);
	}

	function update( $new_instance, $old_instance ) {
		$instance = (array)$new_instance;
		foreach ($new_instance as $key => $v) {
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