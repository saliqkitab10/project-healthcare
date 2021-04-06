<?php
	/**
	 * CWS Contact Widget Class
	 */

class CWS_Contact extends WP_Widget {
	public $fields = array();
	public function init_fields() {
		$this->fields = array(
			'title' => array(
				'title' => esc_html__( 'Widget title', 'cws-essentials' ),
				'atts' => 'id="widget-title"',
				'type' => 'text',
				'value' => '',
			),

			'description_part' => array(
				'title' => esc_html__('Logotype', 'cws-essentials' ),
				'type' => 'checkbox',
				'addrowclasses' => 'checkbox',
				'atts' => 'data-options="e:logo_description;e:text_description;"',
			),
				'logo_description' => array(
					'title' => esc_html__( 'Logo', 'cws-essentials' ),
					'type' => 'media',
					'addrowclasses' => 'disable box',
					'layout' => array(
						'logo_is_high_dpi' => array(
							'title' => esc_html__( 'High-Resolution logo', 'cws-essentials' ),
							'addrowclasses' => 'checkbox',
							'type' => 'checkbox',
						),
					),
				),
				'information_part' => array(
					'title' => esc_html__('Contact info', 'cws-essentials' ),
					'type' => 'checkbox',
					'addrowclasses' => 'checkbox',
					'atts' => 'data-options="e:information_group;"',
				),
				'information_group' => array(
					'type' => 'group',
					'addrowclasses' => 'group expander sortable disable box',
					'title' => esc_html__('Information', 'cws-essentials' ),
					'button_title' => esc_html__('Add new field', 'cws-essentials' ),
					'layout' => array(
						'title' => array(
							'type' => 'text',
							'atts' => 'data-role="title"',
							'title' => esc_html__('Description', 'cws-essentials' ),
						),
						'icon' => array(
							'type' => 'select',
							'addrowclasses' => 'fai',
							'source' => 'fa',
							'title' => esc_html__('Icon', 'cws-essentials' )
						),
					),
				),
				'social_part' => array(
					'title' => esc_html__('Social links', 'cws-essentials' ),
					'type' => 'checkbox',
					'addrowclasses' => 'checkbox',
					'atts' => 'data-options="e:icons_count;e:social_icons_shape_type;e:social_icons_size;e:social_icons_alignment;e:social_icons_color;e:icon_bg_color;e:social_icons_color_hover;e:icon_hover_bg_color;"',
				),
				'icons_count' => array(
					'title' => esc_html__( 'Icons to show', 'cws-essentials' ),
					'type' => 'number',
					'addrowclasses' => 'disable box',
					'value' => '5'
				),
				'social_icons_shape_type' => array(
					'title' => esc_html__( 'Shape', 'cws-essentials' ),
					'type' => 'radio',
					'addrowclasses' => 'disable box',
					'value' => array(
						'none' => array( esc_html__( 'None', 'cws-essentials' ), false),
						'hexagon' => array( esc_html__( 'Hexagon', 'cws-essentials' ), true),
						'rounded' => array( esc_html__( 'Rounded', 'cws-essentials' ), false),
						'circle' =>array( esc_html__( 'Circle', 'cws-essentials' ), false),
					),
				),
				'social_icons_color' => array(
					'title'	=> esc_html__( 'Icon color', 'cws-essentials' ),
					'addrowclasses' => 'grid-col-6',
					'atts' => 'data-default-color="#222222"',
					'value' => '#222222',
					'type'	=> 'text',
				),
				'icon_bg_color'	=> array(
					'title'	=> esc_html__( 'Background color', 'cws-essentials' ),
					'addrowclasses' => 'grid-col-6',
					'atts' => 'data-default-color="#7A7A7A"',
					'value' => '#7A7A7A',
					'type'	=> 'text',
				),			
				'social_icons_color_hover' => array(
					'title'	=> esc_html__( 'Icon color (Hover)', 'cws-essentials' ),
					'addrowclasses' => 'grid-col-6',
					'atts' => 'data-default-color="' . METAMAX_SECOND_COLOR . '"',
					'value' => METAMAX_SECOND_COLOR,
					'type'	=> 'text',
				),
				'icon_hover_bg_color'	=> array(
					'title'	=> esc_html__( 'Background color (Hover)', 'cws-essentials' ),
					'addrowclasses' => 'grid-col-6',
					'atts' => 'data-default-color="#7A7A7A"',
					'value' => '#7A7A7A',
					'type'	=> 'text',
				),
				'social_icons_size' => array(
					'type' => 'text',
					'title' => esc_html__( 'Size', 'cws-essentials' ),
					'addrowclasses' => 'disable box',
                    'value' => '38'
				),
				'social_icons_alignment' => array(
					'type' => 'select',
					'title' => esc_html__( 'Alignment', 'cws-essentials' ),
					'addrowclasses' => 'disable box',
					'source' => array(
						'left' => array( esc_html__( 'Left', 'cws-essentials' ), false),
						'center' => array( esc_html__( 'Center', 'cws-essentials' ), true),
						'right' => array( esc_html__( 'Right', 'cws-essentials' ), false),
					)
				),
		);
	}

	function __construct() {
		$widget_ops = array( 'classname' => 'widget-cws-contact', 'description' => esc_html__( 'Add description, information, social links about site', 'cws-essentials' ) );
		parent::__construct( 'cws-contact', esc_html__( 'CWS Contact info', 'cws-essentials' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		global $cws_theme_funcs;
		$second_color = $cws_theme_funcs->cws_get_option( 'theme-second-color' );

		extract( shortcode_atts( array(
			'title' => '',
			'information_part' => '',
			'description_part' => '',
			'social_part' => '',
			'information_group' => '',
			'logo_description' => '',
			'text_description' => '',
			'icons_count' => '5',
			'social_icons_shape_type' => '',
			'social_icons_color' => '#222222',
			'icon_bg_color'	=> '#7A7A7A',
			'social_icons_color_hover' => $second_color,
			'icon_hover_bg_color'	=> '#7A7A7A',
			'social_icons_size' => '38',
			'social_icons_alignment' => '',
		), $instance));
		
		$social = $cws_theme_funcs->cws_get_option( 'social' )['icons'];

		$title = wp_kses( $title, array(
			"a"			=> array(
				'href'		=> true,
				'target'	=> true,
				'class'		=> true,
			)
		));
		$social_icons_color = esc_attr($social_icons_color);
		$icon_bg_color = esc_attr($icon_bg_color);
		$social_icons_color_hover = esc_attr($social_icons_color_hover);
		$icon_hover_bg_color = esc_attr($icon_hover_bg_color);

		$module_id = uniqid( "cws_contact_info_widget_" );
		$styles = '';

		if ( $social_part ){
            if(!empty($social_icons_size)){
                $styles .= "
                    #{$module_id} .cws-social-link{
                        font-size: ".(int)esc_attr($social_icons_size)."px;
                    }
                    #{$module_id} .cws-social-link:before{
                        font-size: ".(int)esc_attr($social_icons_size)."px;
                    }
                ";
            }
			if(!empty($social_icons_color)){
				$styles .= "
                    #{$module_id} .cws-social-link:before{
                        color: ".esc_attr($social_icons_color).";
                    }
				";
			}
			if(!empty($icon_bg_color)){
                if ($social_icons_shape_type == 'hexagon') {
                    $styles .= "
                        #{$module_id} .cws-social-link:after{
                            color: ".esc_attr($icon_bg_color).";
                        }
                    ";
                } else {
                    $styles .= "
                        #{$module_id} .cws-social-link{
                            background-color: ".esc_attr($icon_bg_color).";
                        }
                    ";
                }

			}
			if(!empty($social_icons_color_hover) || !empty($icon_hover_bg_color)) {
                $styles .= "
					@media 
						screen and (min-width: 1367px), /*Disable this styles for iPad Pro 1024-1366*/
						screen and (min-width: 1200px) and (any-hover: hover), /*Check, is device a desktop (Not working on IE & FireFox)*/
						screen and (min-width: 1200px) and (min--moz-device-pixel-ratio:0), /*Check, is device a desktop with firefox*/
						screen and (min-width: 1200px) and (-ms-high-contrast: none), /*Check, is device a desktop with IE 10 or above*/
						screen and (min-width: 1200px) and (-ms-high-contrast: active) /*Check, is device a desktop with IE 10 or above*/
					    {
					";

                    if (!empty($social_icons_color_hover)) {
                        $styles .= "
                            #{$module_id} .cws-social-link:hover:before{
                                color: ".esc_attr($social_icons_color_hover).";
                            }
                        ";
                    }
                    if (!empty($icon_hover_bg_color)) {
                        if ($social_icons_shape_type == 'hexagon') {
                            $styles .= "
                                #{$module_id} .cws-social-link:hover:after{
                                    color: ".esc_attr($icon_hover_bg_color).";
                                }
                            ";
                        } else {
                            $styles .= "
                                #{$module_id} .cws-social-link:hover{
                                    background-color: ".esc_attr($icon_hover_bg_color).";
                                }
                            ";
                        }
                    }

					$styles .= "
					    }
					";
			}
		}

		if (isset($logo_description['src']) && !empty($logo_description['src'])) {

			$logo = $logo_description;
			$logo_is_high_dpi = $logo['logo_is_high_dpi'];
			$logo_image_src = wp_get_attachment_image_src($logo['id'], 'full');
			$logo_height = $logo_image_src[2];
			$logo_width = $logo_image_src[1];

			if ( $logo_is_high_dpi ) {
				$thumb_obj = cws_get_img( $logo['id'],array( 'width' => floor( (int) $logo_width / 2 ), 'crop' => false ),false );
				$thumb_path_hdpi = !empty($thumb_obj[3]) ? " src='". esc_url( $thumb_obj[0] ) ."' data-at2x='" . esc_attr( $thumb_obj[3] ) ."'" : " src='". esc_url( $thumb_obj[0] ) . "' data-no-retina";
				$logo_src = $thumb_path_hdpi;
			} else {
				$logo_src = " src='" . esc_url( $logo['src'] ) . "' data-no-retina";
			}

		}

	echo sprintf('%s',$before_widget);

		if (!empty( $title )){
			echo sprintf("%s", $before_title) . $title . $after_title;
		}

		echo "<div id='".$module_id."' class='cws-textwidget-content'>";
			if ( !empty( $styles ) ){
				Cws_shortcode_css()->enqueue_cws_css($styles);
			}
			if ($description_part == '1') {
				ob_start();
				?>
					<div class="text_description">
						<?php

						if (!empty($logo_description['src'])) { ?>

							<div class="logo-description">
								<?php if(!empty($logo_src)){ ?>
									<img <?php echo sprintf('%s', $logo_src); ?> alt />
								<?php } else { ?>
									<h1 class='header_site_title'><?php echo esc_html(get_bloginfo( 'name' )); ?></h1>
								<?php } ?>
	                    	</div>

						<?php } ?>
						<?php echo (!empty( $text_description ) ? '<p>'.esc_html($text_description).'</p>': ''); ?>
					</div>
				<?php
				echo ob_get_clean();
			}

			if ($information_part == '1') {
				if (!empty( $information_group )){
				ob_start();
				?>
					<div class="information-group">
						<?php foreach ($information_group as $key => $value) { ?>
							<div class="information-unit">
								<?php if($value['icon'] != ''){ ?>
									<i class="<?php echo esc_attr($value['icon']) ?>"></i>
								<?php } ?>
								<?php 
									echo wp_kses( $value['title'], array(
										"a"			=> array(
											'href'		=> true,
											'target'	=> true,
											'class'		=> true,
										)
									));
								?>
							</div>
						<?php } ?>
					</div>
				<?php
				echo ob_get_clean();
				} else {
					echo "<div class='cws-textwidget-content'>No info.</div>";
				}
			}

			if ($social_part == '1') {

				if(!empty( $social )) {
					$i = 0;
					$icons_count = intval($icons_count);
					ob_start();
					?>
						<div class="cws-social-links shape-<?php echo esc_attr($social_icons_shape_type); ?>
						position-<?php echo esc_attr($social_icons_alignment); ?>" style="text-align: <?php echo esc_attr($social_icons_alignment); ?>;">
							<?php foreach ($social as $key => $value) {
								if($i < $icons_count && !empty($value['icon']) ){

								$icon_id = uniqid( "cws_social_icon_" );				
								?>
								<a id="<?php echo esc_attr($icon_id) ?>" href="<?php echo esc_url($value['url']) ?>" class="cws-social-link <?php echo esc_attr($social_icons_shape_type) .' '.esc_attr($value['icon']) ?>" title="<?php echo esc_attr($value['title']) ?>" target="_blank"></a>
							<?php


							} $i++;
						} 

						?>
						</div>
					<?php
					echo ob_get_clean();
				} else {
					echo "<div class='cws-textwidget-content'>No Social Icons found.</div>";
				}
			}
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