<?php
	global $cws_theme_funcs;
	$first_color = esc_attr( $cws_theme_funcs->cws_get_option( 'theme-first-color' ) );
	// Map Shortcode in Visual Composer
	vc_map( array(
		"name"				=> esc_html__( 'CWS Tips', 'metamax' ),
		"base"				=> "cws_sc_tips",
		'category'			=> "By CWS",
                "icon"                          => "cws_icon",
		"weight"			=> 80,
        'description' => esc_html__( 'Image Tips with tooltip', 'metamax' ),
        "params" => array(
        	array(
        		"type" => "attach_image",
        		"heading" => esc_html__("Image", "metamax"),
        		"param_name" => "image",
        		"value" => "",
        		"description" => esc_html__("Select image from media library.", "metamax")
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Resize image to this width", "metamax"),
        		"param_name" => "width",
        		"value" => "",
        		"description" => esc_html__("You can resize image to this width, or keep it to blank to use the original image.", "metamax")
        		),
        	array(
        		"type" => "textarea_html",
        		"holder" => "div",
        		"heading" => esc_html__("Tooltip content, divide each one with [cwstips][/cwstips], please edit in text mode:", "metamax"),
        		"param_name" => "content",
        		"value" => wp_kses(__("[cwstips]
        			You have to wrap each tooltip block in <strong>cwstips</strong>.
        			[/cwstips]
        			[cwstips]
        			Hello tooltip 2, you can customize the icon color, link, arrow position, tooltip content etc in the backend.
        			[/cwstips]
        			[cwstips]
        			Hello tooltip 3
        			[/cwstips]
        			", "metamax"), array(
        			    "strong" => array()
                    )
                ),
                "description" => esc_html__("Enter content for each block here. Divide each with [cwstips].", "metamax") ),
        	array(
        		"type" => "dropdown",
        		"heading" => esc_html__("Display which tooltip by default?", "metamax"),
        		"param_name" => "isdisplayall",
        		'value' => array(esc_html__("Display all of them when loaded", "metamax") => "on", esc_html__("Display a specified one (customize it below:)", "metamax") => "specify", esc_html__("Hide them all when loaded", "metamax") => "off"),
        		'std' => 'off',
        		"description" => esc_html__('Default all the tooltips are hidden. Though you can choose to open all of them or a single one when page is loaded.', 'metamax')
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Display this tooltip when page loaded:", "metamax"),
        		"param_name" => "displayednum",
        		"value" => "1",
        		"dependency" => Array('element' => "isdisplayall", 'value' => array('specify')),
        		"description" => wp_kses(__("You can specify to display which tooltip in current image. Default is <strong>1</strong>, which stand for the number 1 tooltip will be opened when page is loaded.", "metamax"), array(
        		    "strong" => array()
                ))
        		),
        	array(
        		"type" => "dropdown",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Display the tips with?", "metamax"),
        		"param_name" => "icontype",
        		"value" => array(esc_html__("single dot", "metamax") => "dot", esc_html__("number", "metamax") => "number", esc_html__("Font Awesome icon", "metamax") => "icon"),
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Numbers start from", "metamax"),
        		"param_name" => "startnumber",
        		"value" => "1",
        		"dependency" => Array('element' => "icontype", 'value' => array('number')),
        		"description" => esc_html__("Default is start from 1, you can specify other value here, like 4.", "metamax")
        		),
        	array(
        		"type" => "exploded_textarea",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Font Awesome icon for each tips:", 'metamax'),
        		"param_name" => "fonticon",
        		"value" => "fa-hand-point-right,fa-image,fa-coffee,fa-comment",
        		"dependency" => Array('element' => "icontype", 'value' => array('icon')),
        		"description" => wp_kses(__("Put the <a href='http://fontawesome.github.io/Font-Awesome/icons/' target='_blank'>Font Awesome icon</a> here, divide with linebreak (Enter).", 'metamax'), array(
        		    "a" => array(
        		        "href" => array(),
                        "target" => array()
                    )
                ))
        		),
        	array(
        		"type" => "exploded_textarea",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Each tips icon's position", 'metamax'),
        		"param_name" => "position",
        		"value" => "25%|30%,35%|20%,45%|60%,75%|20%",
        		"description" => wp_kses(__("Position of each icon in <strong>top|left</strong> format. Please update via dragging the tips icon in the Visual Composer Frontend editor. See a <a href='http://youtu.be/9j1XhIQw9JE' target='_blank'>Youtube video demo</a>.", 'metamax'), array(
        		    "strong" => array(),
                    "a" => array(
                        "href" => array(),
                        "target" => array()
                    )
                ))
        		),
        	array(
        		"type" => "colorpicker",
        		"holder" => "div",
        		"class" => "",
        		"heading" => esc_html__("Global tips icon color", 'metamax'),
        		"param_name" => "iconbackground",
        		"value" => 'rgba(0,0,0,0.8)',
        		"description" => esc_html__("Global color for the tips icon. Or you can specify different color for each icon below.", 'metamax')
        		),
        	array(
        		"type" => "colorpicker",
        		"holder" => "div",
        		"class" => "",
        		"heading" => esc_html__("Hotspot circle dot (or Font Awesome icon) color", 'metamax'),
        		"param_name" => "circlecolor",
        		"value" => '#FFFFFF',
        		"description" => esc_html__("Color for the tips circle dot. Default is white.", 'metamax')
        		),
        	array(
        		"type" => "exploded_textarea",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Each tips icon's color", 'metamax'),
        		"param_name" => "color",
        		"value" => "",
        		"description" => esc_html__("Color for each icon, you can use the value like #663399 or the name of the color like blue here. Divide each with linebreaks (Enter).", 'metamax')
        		),
        	array(
        		"type" => "dropdown",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Display pulse animation for the tips icon?", "metamax"),
        		"param_name" => "ispulse",
        		"value" => array(esc_html__("Yes", "metamax") => "yes", esc_html__("No", "metamax") => "no")
        		),
        	array(
        		"type" => "dropdown",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Select pulse border color", "metamax"),
        		"param_name" => "pulsecolor",
        		"value" => array(esc_html__("Default", "metamax") => "pulse-white", esc_html__("gray", "metamax") => "pulse-gray", esc_html__("red", "metamax") => "pulse-red", esc_html__("green", "metamax") => "pulse-green", esc_html__("yellow", "metamax") => "pulse-yellow", esc_html__("blue", "metamax") => "pulse-blue", esc_html__("purple", "metamax") => "pulse-purple"),
        		"dependency" => array('element' => "ispulse", 'value' => array('yes')),
        		"std" => "pulse-white",
        		"description" => esc_html__("You can select the pulse border color here, default is white.", "metamax")
        		),
        	array(
        		"type" => "exploded_textarea",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Tooltip arrow position for each tips", 'metamax'),
        		"param_name" => "arrowposition",
        		"value" => "",
        		"description" => wp_kses(__("The arrow position for each tooltip, default is top. The available options are: <strong>top, right, bottom, left, top-right, top-left, bottom-right, bottom-left</strong>. Divide each with linebreaks (Enter)", 'metamax'), array(
        		    "strong" => array()
                ))
        		),

        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Hotspot icon opacity", "metamax"),
        		"param_name" => "opacity",
        		"value" => "1",
        		"description" => esc_html__("The opacity of each icon, default is 1", "metamax")
        		),
        	array(
        		"type" => "dropdown",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Tooltip style", "metamax"),
        		"param_name" => "tooltipstyle",
        		"value" => array(esc_html__("shadow", "metamax") => "shadow", esc_html__("light", "metamax") => "light", esc_html__("noir", "metamax") => "noir", esc_html__("punk", "metamax") => "punk")
        		),
        	array(
        		"type" => "dropdown",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Tooltip trigger when user", "metamax"),
        		"param_name" => "trigger",
        		"value" => array(esc_html__("hover", "metamax") => "hover", esc_html__("click", "metamax") => "click"),
        		"description" => esc_html__("Select how to trigger the tooltip.", "metamax")
        		),
        	array(
        		"type" => "dropdown",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Tooltip animation", "metamax"),
        		"param_name" => "tooltipanimation",
        		"value" => array(esc_html__("grow", "metamax") => "grow", esc_html__("fade", "metamax") => "fade", esc_html__("swing", "metamax") => "swing", esc_html__("slide", "metamax") => "slide", esc_html__("fall", "metamax") => "fall"),
        		"description" => esc_html__("Choose the animation for the tooltip.", "metamax")
        		),
        	array(
        		"type" => "exploded_textarea",
        		"holder" => "",
        		"class" => "metamax",
        		"heading" => esc_html__("Link for each tips icon", 'metamax'),
        		"param_name" => "links",
        		"value" => "",
        		"description" => esc_html__("Specify link for each icon, divide each with linebreaks (Enter).", 'metamax')
        		),
        	array(
        		"type" => "dropdown",
        		"heading" => esc_html__("How to open the link for the icon?", "metamax"),
        		"param_name" => "custom_links_target",
        		"description" => esc_html__('Select how to open the links', 'metamax'),
        		'value' => array(esc_html__("Same window", "metamax") => "_self", esc_html__("New window", "metamax") => "_blank")
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("maxWidth of the tooltip", "metamax"),
        		"param_name" => "maxwidth",
        		"value" => "240",
        		"description" => esc_html__("maxWidth for the tooltip, 0 is auto width, you can specify a value here, default is 240.", "metamax")
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Container width", "metamax"),
        		"param_name" => "containerwidth",
        		"value" => "",
        		"description" => esc_html__("You can specify the container width here, default is 100%. You can try other value like 80%, it will be align center automatically.", "metamax")
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Margin offset", "metamax"),
        		"param_name" => "marginoffset",
        		"value" => "",
        		"description" => wp_kses(__("The margin offset for the tips icon in small screen. For example <strong>-6px 0 0 -6px</strong> will move the icons upper left for 6px offset in small screen. Leave here to be blank if you do not want it.", "metamax"), array(
        		    "strong" => array()
                ))
        		),
        	array(
        		"type" => "textfield",
        		"heading" => esc_html__("Extra class name for the container", "metamax"),
        		"param_name" => "extra_class",
        		"description" => esc_html__("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", "metamax")
        		)

        	)
	));

	if ( class_exists( 'WPBakeryShortCode' ) ) {
	    class WPBakeryShortCode_CWS_Sc_Tips extends WPBakeryShortCode {
	    }
	}
?>