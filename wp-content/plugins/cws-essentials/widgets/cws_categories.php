<?php
	/**
	 * CWS Banner Widget Class
	 */
class CWS_Categories extends WP_Widget {
	public $fields = array();
	public function init_fields() {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget title', 'cws-essentials' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
				'value' => '',
			),
			'cats' => array(
				'title' => esc_html__( 'Categories to show', 'cws-essentials' ),
				'type' => 'taxonomy',
				'taxonomy' => 'category',
				'atts' => 'multiple',
				'source' => array(),
			),
		);
	}
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cws-categories', 'description' => esc_html__( 'Add information about yourself', 'cws-essentials' ) );
		parent::__construct( 'cws-categories', esc_html__( 'CWS Categories', 'cws-essentials' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		extract( shortcode_atts( array(
			'title' => '',
			'cats' => array(),
		), $instance));
		global $cws_theme_funcs;
		$title = esc_html($title);
 		$out = '';

 		$thumbnail_dims['width'] = 737;

		echo sprintf('%s',$before_widget);
			if (!empty( $title )){
				echo sprintf("%s", $before_title) . esc_html($title) . $after_title;
			}


			echo "<div class='cws-categories-widget'>";


				foreach ($cats as $id => $slug) {

			 		$term = get_term_by('slug', $slug, 'category');
			 		$term_name = $term->name;
					$term_id = $term->term_id;
					$link = get_category_link($term_id);
					$term_image = get_term_meta( $term_id, 'cws_mb_term' );
					$dummy_image = get_template_directory_uri() . "/img/img_placeholder.png";
					$is_dummy = true;

					ob_start();
						if( !empty($term_image[0]['image']['src']) && filter_var($term_image[0]['image']['src'], FILTER_VALIDATE_URL) ){
							$is_dummy = false;
							$dims['crop'] = true;
							$img_obj = cws_get_img( $term_image[0]['image']['src'], $thumbnail_dims , true );
						}
						?>
							<article <?php post_class(array( 'item','categories-grid')); ?>>
								<a class='category-block' href="<?php echo esc_url($link);?>">
									<img src='<?php echo esc_url( !$is_dummy ? $img_obj[0] : $dummy_image ); ?>' alt=''>
									<span class='category-label'><?php echo sprintf("%s", $term_name); ?></span>
								</a>
							</article>

						<?php
					$out .= ob_get_clean();
			 	}


				echo sprintf("%s", $out);

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