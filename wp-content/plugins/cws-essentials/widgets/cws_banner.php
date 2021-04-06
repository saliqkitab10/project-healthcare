<?php
	/**
	 * CWS Banner Widget Class
	 */
class CWS_Banner extends WP_Widget {
	public $fields = array();
	public function init_fields() {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget title', 'cws-essentials' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
				'value' => '',
			),
            'alignment' => array(
                'type' => 'select',
                'title' => esc_html__( 'Banner Alignment', 'cws-essentials' ),
                'source' => array(
                    'left' => array(esc_html__( 'Left', 'cws-essentials' ), true),
                    'center' => array(esc_html__( 'Center', 'cws-essentials' ), false),
                    'right' => array(esc_html__( 'Right', 'cws-essentials' ), false),
                )
            ),
			'img_bg' => array(
				'title' => esc_html__( 'Banner Background Image', 'cws-essentials' ),
				'addrowclasses' => 'wide_picture',
				'type' => 'media',
			),
			'banner_title' => array(
				'title' => esc_html__( 'Banner title', 'cws-essentials' ),
				'type' => 'textarea',
				'atts' => 'rows="2" placeholder="'.esc_attr__('Enter title', 'cws-essentials').'"',
				'value' => '',
			),
			'title_desc' => array(
				'title' => esc_html__( 'Title Description', 'cws-essentials' ),
				'type' => 'textarea',
				'atts' => 'rows="4" placeholder="'.esc_attr__('Enter title description', 'cws-essentials').'"',
				'value' => '',
			),
			'title_color' => array(
				'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
				'title'     => esc_html__( 'Title color', 'cws-essentials' ),
				'atts' => 'data-default-color="#ffffff"',
			),
            'text_color' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Text color', 'cws-essentials' ),
                'atts' => 'data-default-color="#ffffff"',
            ),
            'add_divider' => array(
                'title' => esc_html__( 'Add Title Divider', 'cws-essentials' ),
                'type' => 'checkbox',
                'addrowclasses' => 'checkbox',
                'atts' => 'data-options="e:divider_color;"',
            ),
            'divider_color' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-12',
                'title'     => esc_html__( 'Divider color', 'cws-essentials' ),
                'atts' => 'data-default-color="#ffffff"',
            ),

			'add_overlay' => array(
				'title' => esc_html__( 'Add Overlay', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'data-options="e:over_color;e:over_opacity;"',
			),
			'over_color' => array(
				'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
				'title'     => esc_html__( 'Overlay color', 'cws-essentials' ),
				'atts' => 'data-default-color="#ffffff"',
			),
			'over_opacity' => array(
				'title' => esc_html__( 'Overlay Opacity', 'cws-essentials' ),
				'type' => 'number',
                'addrowclasses' => 'grid-col-6',
				'value' => '50',
			),

			'add_button' => array(
				'title' => esc_html__( 'Add Button', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox clear',
				'atts' => 'data-options="e:button_title;e:button_url;e:new_tab;e:button_color;e:button_bg_color;e:button_bd_color;e:button_color_hover;e:button_bg_color_hover;e:button_bd_color_hover;"',
			),
			'button_title' => array(
				'title' => esc_html__( 'Button Title', 'cws-essentials' ),
				'type' => 'text',
				'value' => '',
			),
			'button_url' => array(
				'title' => esc_html__( 'Button Url', 'cws-essentials' ),
				'type' => 'text',
				'value' => '',
			),
            'button_color' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Button title color', 'cws-essentials' ),
                'atts' => 'data-default-color="#ffffff"',
            ),
            'button_color_hover' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Button title color (Hover)', 'cws-essentials' ),
                'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
            ),

            'button_bg_color' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Button background color', 'cws-essentials' ),
                'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
            ),
            'button_bg_color_hover' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Button background color (Hover)', 'cws-essentials' ),
                'atts' => 'data-default-color="#ffffff"',
            ),

            'button_bd_color' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Button border color', 'cws-essentials' ),
                'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
            ),
            'button_bd_color_hover' => array(
                'type'      => 'text',
                'addrowclasses' => 'grid-col-6',
                'title'     => esc_html__( 'Button border color (Hover)', 'cws-essentials' ),
                'atts' => 'data-default-color="' . METAMAX_FIRST_COLOR . '"',
            ),

			'new_tab' => array(
				'title' => esc_html__( 'Open in New Tab', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
			),
		);
	}
	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cws-banner', 'description' => esc_html__( 'Add information about yourself', 'cws-essentials' ) );
		parent::__construct( 'cws-banner', esc_html__( 'CWS Banner', 'cws-essentials' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

        global $cws_theme_funcs;

		extract( shortcode_atts( array(
			'title' => '',
			'alignment' => 'left',
			'img_bg' => '',
			'banner_title' => '',
			'title_desc' => '',
			'title_color' => '#ffffff',
			'text_color' => '#ffffff',
			'add_divider' => false,
			'divider_color' => '#ffffff',
			'add_overlay' => false,
			'over_color' => '#ffffff',
			'over_opacity' => '50',
			'add_button' => false,
			'button_title' => '',
			'button_url' => '',
            'button_color' => '#ffffff',
            'button_bg_color' => METAMAX_FIRST_COLOR,
            'button_bd_color' => METAMAX_FIRST_COLOR,
            'button_color_hover' => METAMAX_FIRST_COLOR,
            'button_bg_color_hover' => '#ffffff',
            'button_bd_color_hover' => METAMAX_FIRST_COLOR,
			'new_tab' => false,
		), $instance));

		$title = esc_html($title);
		$img_bg_id = !empty($img_bg) ? $img_bg['id'] : '';
		$thumb_obj = cws_get_img( $img_bg_id, array( 'width' => 330, 'height' => 330, 'crop' => true ), false );
		$thumb_url = esc_url($thumb_obj[0]);
		$retina_thumb_url = esc_url($thumb_obj[3]);
		$over_opacity = (int)$over_opacity / 100;

        $module_id = uniqid( "cws-banner-widget-" );
        $styles = '';

        if ( !empty($thumb_url) || !empty($alignment) ) {
            $styles .= "
                #{$module_id} {"
                . ( !empty($thumb_url) ? "background-image: url(" . esc_url($thumb_url) . ");" : "" )
                . ( !empty($alignment) ? "text-align: " . esc_attr($alignment) . ";" : "" )
				. "} 
            ";
        }
        if ( !empty($add_overlay) && ( !empty($over_color) || !empty($over_opacity) ) ) {
            $styles .= "
                #{$module_id} .banner-wrapper-overlay {"
                . ( !empty($over_color) ? "background-color: " . esc_attr($over_color) . ";" : "" )
                . ( !empty($over_opacity) ? "opacity: " . esc_attr($over_opacity) . ";" : "" )
                . "} 
            ";
        }
        if ( !empty($title_color) ) {
            $styles .= "
                #{$module_id} .banner-title {
		   			color: " . esc_attr($title_color) . ";
				} 
            ";
        }
        if ( !empty($text_color) ) {
            $styles .= "
                #{$module_id} .banner-desc {
		   			color: " . esc_attr($text_color) . ";
				} 
            ";
        }
        if ( !empty($add_divider) && !empty($divider_color) ) {
            $styles .= "
                #{$module_id} .banner-divider {
		   			background-color: " . esc_attr($divider_color) . ";
				} 
            ";
        }
        if ( !empty($add_button) ) {
            if ( !empty($button_color) || !empty($button_bg_color) || !empty($button_bd_color) ) {
                $styles .= "
                    #{$module_id} .button {"
                         . ( !empty($button_color) ? "color: " . esc_attr($button_color) . ";" : "" )
                         . ( !empty($button_bg_color) ? "background-color: " . esc_attr($button_bg_color) . ";" : "" )
                         . ( !empty($button_bd_color) ? "border-color: " . esc_attr($button_bd_color) . ";" : "" )
                    . "}
                ";
            }
            if ( !empty($button_color_hover) || !empty($button_bg_color_hover) || !empty($button_bd_color_hover) ) {
                $styles .= "
                    #{$module_id} .button:hover {"
                    . ( !empty($button_color_hover) ? "color: " . esc_attr($button_color_hover) . ";" : "" )
                    . ( !empty($button_bg_color_hover) ? "background-color: " . esc_attr($button_bg_color_hover) . ";" : "" )
                    . ( !empty($button_bd_color_hover) ? "border-color: " . esc_attr($button_bd_color_hover) . ";" : "" )
                    . "}
                ";
            }
        }

		echo sprintf('%s',$before_widget);
			if (!empty( $title )){
				echo sprintf("%s", $before_title) . esc_html($title) . $after_title;
			}

			echo "<div id='".$module_id."' class='cws-widget-banner'>";
                if ( !empty( $styles ) ){
                    Cws_shortcode_css()->enqueue_cws_css($styles);
                }
				echo "<div class='banner-wrapper" . (!empty($alignment) ? " a-" . esc_attr($alignment) : "") . "'>";
                    echo "<div class='banner-wrapper-overlay'></div>";
					echo "<div class='banner-content'>";
						echo !empty( $banner_title ) ? "<h3 class='banner-title'>$banner_title</h3>" : "";
						echo !empty( $add_divider ) ? "<div class='banner-divider'></div>" : "";
						echo !empty( $title_desc ) ? "<div class='banner-desc'>$title_desc</div>" : "";
					echo "</div>";
					if( !empty($button_title) ){
						echo "<div class='banner-button'>";
							echo "<a class='button' href='".(!empty($button_url) ? $button_url : '#')."' ".(!empty($new_tab) ? 'target="_blank"' : '')." >".$button_title."</a>";
						echo "</div>";
					}
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