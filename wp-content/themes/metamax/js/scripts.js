"use strict";

function cws_uniq_id ( prefix ){
	var prefix = prefix != undefined && typeof prefix == 'string' ? prefix : "";
	var d = new Date();
	var t = d.getTime();
	var unique = Math.random() * t;
	var unique_id = prefix + unique;
	return unique_id;
}
function cws_has_class ( el, cls_name ){
	var re = new RegExp( "(^|\\s)" + cls_name + "(\\s|$)", 'g' );
	return re.test( el.className );
}
function cws_add_class ( el, cls_name ){
	if(!el){
		return false;
	}
	el.className =  el.className.length ? el.className + " " + cls_name : cls_name;		
}
function cws_remove_class ( el, cls_name ){
	var re = new RegExp( "\\s?" + cls_name, "g" );
	el.className = el.className.replace( re, '' );
}

/*--------- CWS_RESPONSIVE_SCRIPTS ----------*/
function is_mobile_device(){
	if ( navigator.userAgent.match( /(Android|iPhone|iPod|iPad|Phone|DROID|webOS|BlackBerry|Windows Phone|ZuneWP7|IEMobile|Tablet|Kindle|Playbook|Nexus|Xoom|SM-N900T|GT-N7100|SAMSUNG-SGH-I717|SM-T330NU)/ ) ) {
		return true;
	} else {
		return false;
	}
}
function cws_is_tablet_viewport () {
	if ( window.innerWidth > 767 && window.innerWidth < 1367 && is_mobile_device() ){
		return true;
	} else {
		return false;
	}		
}
function is_mobile () {
	if ( window.innerWidth < 768 ){
		return true;
	} else {
		return false;
	}		
}
function not_desktop(){
	if( (window.innerWidth < 1367 && is_mobile_device()) || window.innerWidth < 1200 ){
		return true;
	} else {
		return false;
	}
}
function cws_resize_controller() {
	var cwsBody = jQuery('body');
	if( is_mobile_device() && cws_is_tablet_viewport()){
		cwsBody.removeClass('cws_mobile');
		cwsBody.addClass('cws_tablet');
	} else if ( is_mobile_device() && is_mobile() ){
		cwsBody.removeClass('cws_tablet');
		cwsBody.addClass('cws_mobile');
	} else {
		cwsBody.removeClass('cws_tablet');
		cwsBody.removeClass('cws_mobile');
	}
}
function cws_resize() {
	cws_resize_controller();
	window.addEventListener( "resize", function (){
		cws_resize_controller();
	}, false );
}
function cws_is_mobile () {
	var device = is_mobile_device();
	var viewport = not_desktop();
	return device || viewport;
}
/*\--------- CWS_RESPONSIVE_SCRIPTS ----------\*/

function cws_merge_trees ( arr1, arr2 ){
	if ( typeof arr1 != 'object' || typeof arr2 != 'object' ){
		return false;
	}
	return cws_merge_trees_walker ( arr1, arr2 );
}
function cws_merge_trees_walker ( arr1, arr2 ){
	if ( typeof arr1 != 'object' || typeof arr2 != 'object' ){
		return false;
	}
	var keys1 = Object.keys( arr1 ); /* ! not working with null value */
	var keys2 = Object.keys( arr2 );
	var r = {};
	var i;
	for ( i = 0; i < keys2.length; i++ ){
		if ( typeof arr2[keys2[i]] == 'object' ){
			if ( Array.isArray( arr2[keys2[i]] ) ){
				if ( keys1.indexOf( keys2[i] ) === -1 ){
					r[keys2[i]] = arr2[keys2[i]];
				}
				else{
					r[keys2[i]] = arr1[keys2[i]];
				}				
			}
			else{
				if ( typeof arr1[keys2[i]] == 'object' ){
					r[keys2[i]] = cws_merge_trees_walker( arr1[keys2[i]], arr2[keys2[i]] );
				}
				else{
					r[keys2[i]] = cws_merge_trees_walker( {}, arr2[keys2[i]] );
				}
			}
		}
		else{
			if ( keys1.indexOf( keys2[i] ) === -1 ){
				r[keys2[i]] = arr2[keys2[i]];
			}
			else{
				r[keys2[i]] = arr1[keys2[i]];
			}
		}
	}
	return r;
}

function cws_get_flowed_previous ( el ){
	var prev = el.previousSibling;
	var is_prev_flowed;
	if ( !prev ) return false;
	is_prev_flowed = cws_is_element_flowed( prev );
	if ( !is_prev_flowed ){
		return cws_get_flowed_previous( prev );
	}
	else{
		return prev;
	}
}
function cws_is_element_flowed ( el ){
	var el_styles;
	if ( el.nodeName === "#text" ){
		return false;
	}
	el_styles = getComputedStyle( el );
	if ( el_styles.display === "none" || ["fixed","absolute"].indexOf( el_styles.position ) != -1 ){
		return false;
	}else{
		return true;
	}
}

function cws_empty_p_filter_callback (){
	var el = this;
	if ( el.tagName === "P" && !el.innerHTML.length ){
		return false;
	}
	else{
		return true;
	}	
}
function cws_br_filter_callback (){
	var el = this;
	if ( el.tagName === "BR" ){
		return false;
	}
	else{
		return true;
	}	
}

function cws_getRandomInt ( min, max ){
	var min = min !== undefined ? min : 0;
	var max = max !== undefined ? max : 1000000;
	return Math.floor( Math.random() * (max - min + 1) ) + min;
}

// Converts from degrees to radians.
function cws_math_radians (degrees){
	return degrees * Math.PI / 180;
};

// Converts from radians to degrees.
function cws_math_degrees (radians){
	return radians * 180 / Math.PI;
};


/**********************************
************ CWS HOOKS ************
**********************************/
function cws_hooks_init (){
	window.cws_hooks = {}
}
function cws_add_action ( tag, callback ){
	if ( typeof tag !== "string" || !tag.length ){
		return false;
	}
	if ( typeof callback !== "function" ){
		return false;
	}
	var hooks 	= window.cws_hooks;
	var hook;
	if ( hooks[tag] === 'object' ){
		hook = hooks[tag];
	}
	else{
		hooks[tag] = hook = new cws_hook ( tag );
	}
	hook.addAction( callback );
}
function cws_do_action ( tag, args ){
	var args 		= Array.isArray( args ) ? args : new Array ();
	var hooks 		= window.cws_hooks;
	var hook 		= hooks[tag]; 
	var hook_exists = typeof hook === 'object';
	if ( hook_exists ){
		hook.run( args );
	}
	return false;
}
function cws_hook ( tag ){
	this.tag = tag;
	this.actions = {};
	this.genActionID 	= function (){
		return cws_uniq_id( "cws_action_" );
	}
	this.addAction 		= function ( callback ){
		var actionID 			= this.genActionID();
		var action 				= new cws_action( this, actionID, callback )
		this.actions[actionID] 	= action;
	}
	this.run 			= function ( args ){
		var actionID, action;
		for ( actionID in this.actions ){
			action = this.actions[actionID];
			action.do( args );
		}
	}
}
function cws_action ( hook, actionID, callback ){
	this.hook 		= hook;
	this.id 		= actionID;
	this.callback 	= callback;
	this.do 		= function ( args ){
		this.callback.apply( this, args );
	}
}
/**********************************
************ \CWS HOOKS ***********
**********************************/


/**********************************
*********** CWS LOADER ************
**********************************/
(function ($){

	var loader;
	$.fn.start_cws_loader = start_cws_loader;
	$.fn.stop_cws_loader = stop_cws_loader;

	jQuery(document).ready(function (){
		cws_page_loader_controller ();
	});

	function cws_page_loader_controller (){
		var cws_page_loader, interval, timeLaps ;
		cws_page_loader = $( "#cws-page-loader" );
		timeLaps = 0;
		interval = setInterval( function (){
			var page_loaded = cws_check_if_page_loaded ();	
			timeLaps ++;
			if ( page_loaded || timeLaps == 12) {
				clearInterval ( interval );
				cws_page_loader.stop_cws_loader ();
			}
		}, 10);
	}
	function cws_check_if_page_loaded (){
		var keys, key, i, r;
		if ( window.cws_modules_state == undefined ) return false;
		r = true;
		keys = Object.keys( window.cws_modules_state );
		for ( i = 0; i < keys.length; i++ ){
			key = keys[i];
			if ( !window.cws_modules_state[key] ){
				r = false;
				break;
			}
		}
		return r;
	}
	function start_cws_loader (){
		var loader_obj, loader_container, indicators;
		loader = jQuery( this );
		if ( !loader.length ) return;
		loader_container = loader[0].parentNode;
		if ( loader_container != null ){
			loader_container.style.opacity = 1;
			setTimeout( function (){
				loader_container.style.display = "block";
			}, 10);
		}
	}
	function stop_cws_loader (){
		var loader_obj, loader_container, indicators;
		loader = jQuery( this );
		if ( !loader.length ) return;
		loader_container = loader[0].parentNode;
		if ( loader_container != null ){
			loader_container.style.opacity = 0;
			setTimeout( function (){
				loader_container.style.display = "none";
				jQuery( ".cws-textmodule-icon-wrapper.add-animation-icon" ).cws_services_icon();
			}, 200);
		}
	}
	function updateCirclePos(){
		var circle=$obj.data("circle");
		TweenMax.set($obj,{
			x:Math.cos(circle.angle)*circle.radius,
			y:Math.sin(circle.angle)*circle.radius,
		})
		requestAnimationFrame(updateCirclePos);
	}
	function setupCircle($obj){
		if(typeof($obj.data("circle"))=="undefined"){
			$obj.data("circle",{radius:0,angle:0});

			updateCirclePos();
		}
	}
	function startCircleAnim($obj,radius,delay,startDuration,loopDuration){
		setupCircle($obj);
		$obj.data("circle").radius=0;
		$obj.data("circle").angle=0;
		TweenMax.to($obj.data("circle"),startDuration,{
			delay:delay,
			radius:radius,
			ease:Quad.easeInOut
		});
		TweenMax.to($obj.data("circle"),loopDuration,{
			delay:delay,
			angle:Math.PI*2,
			ease:Linear.easeNone,
			repeat:-1
		});
	}
	function stopCircleAnim($obj,duration){
		TweenMax.to($obj.data("circle"),duration,{
			radius:0,
			ease:Quad.easeInOut,
			onComplete:function(){
				TweenMax.killTweensOf($obj.data("circle"));
			}
		});
	}

}(jQuery));

/**********************************
*********** \CWS LOADER ***********
**********************************/


/**********************************
********** CWS PARALLAX ***********
**********************************/

(function ( $ ){
  
	$.fn.cws_prlx = cws_prlx;

	window.addEventListener( 'scroll', function (){
		if ( window.cws_prlx != undefined && !window.cws_prlx.disabled ){
			window.cws_prlx.translate_layers();
		}
	}, false );

	window.addEventListener( 'resize', function (){
		var i, section_id, section_params, layer_id;
		if ( window.cws_prlx != undefined ){
			if ( window.cws_prlx.servant.is_mobile() ){
				if ( !window.cws_prlx.disabled ){
					for ( layer_id in window.cws_prlx.layers ){
						window.cws_prlx.layers[layer_id].el.removeAttribute( 'style' );
					}
					window.cws_prlx.disabled = true;          
				}
			}
			else{
				if ( window.cws_prlx.disabled ){
					window.cws_prlx.disabled = false;
				}
				for ( section_id in window.cws_prlx.sections ){
					section_params = window.cws_prlx.sections[section_id];
					if ( section_params.height != section_params.el.offsetHeight ){
						window.cws_prlx.prepare_section_data( section_id );
					}
				}
			}
		}
	}, false );

	function cws_prlx ( args ){
		var factory, sects;
		sects = $( this );
		if ( !sects.length ) return;
		factory = new cws_prlx_factory( args );
		window.cws_prlx = window.cws_prlx != undefined ? window.cws_prlx : new cws_prlx_builder ();
		sects.each( function (){
			var sect = $( this );
			var sect_id = factory.add_section( sect );
			if ( sect_id ) window.cws_prlx.prepare_section_data( sect_id );
		});
	}

	function cws_prlx_factory ( args ){
		var args = args != undefined ? args : {};
		args.def_speed = args.def_speed != undefined && !isNaN( parseInt( args.def_speed ) ) && parseInt( args.def_speed > 0 ) && parseInt( args.def_speed <= 100 ) ? args.def_speed : 50;
		args.layer_sel = args.layer_sel != undefined && typeof args.layer_sel == "string" && args.layer_sel.length ? args.layer_sel : ".cws_prlx_layer";  
		this.args = args;
		this.add_section = cws_prlx_add_section;
		this.add_layer = cws_prlx_add_layer; 
		this.remove_layer = cws_prlx_remove_layer;   
	}

	function cws_prlx_builder (){
		this.servant = new cws_servant ();
		this.sections = {};
		this.layers = {}; 
		this.calc_layer_speed = cws_prlx_calc_layer_speed;
		this.prepare_section_data = cws_prlx_prepare_section_data;
		this.prepare_layer_data = cws_prlx_prepare_layer_data;
		this.translate_layers = cws_prlx_translate_layers;
		this.translate_layer = cws_prlx_translate_layer;
		this.conditions = {};
		this.conditions.layer_loaded = cws_prlx_layer_loaded_condition;
		this.disabled = false;
	}

	function cws_prlx_add_section ( section_obj ){
		var factory, section, section_id, layers, layer, i;
		factory = this;
		section = section_obj[0];
		layers = $( factory.args.layer_sel, section_obj );
		if ( !layers.length ) return false;
		section_id = window.cws_prlx.servant.uniq_id( 'cws_prlx_section_' );
		section.id = section_id;

		window.cws_prlx.sections[section_id] = {
			'el' : section,
			'height' : null,
			'layer_sel' : factory.args.layer_sel
		}

		if ( /cws_Yt_video_bg/.test( section.className ) ){  /* for youtube video background */ 
			section.addEventListener( "DOMNodeRemoved", function ( e ){
				var el = e.srcElement ? e.srcElement : e.target;
				if ( $( el ).is( factory.args.layer_sel ) ){
					factory.remove_layer( el.id );
				}
			}, false );
			section.addEventListener( "DOMNodeInserted", function ( e ){
				var el = e.srcElement ? e.srcElement : e.target;
				if ( $( el ).is( factory.args.layer_sel ) ){
					factory.add_layer( el, section_id );
				}
			}, false );
		}

		section.addEventListener( "DOMNodeRemoved", function ( e ){ /* for dynamically removed content */
			window.cws_prlx.prepare_section_data( section_id );
		},false );
		section.addEventListener( "DOMNodeInserted", function ( e ){ /* for dynamically added content */
			window.cws_prlx.prepare_section_data( section_id );
		},false );

		for ( i = 0; i < layers.length; i++ ){
			layer = layers[i];
			factory.add_layer( layer, section_id )
		}

		return section_id;

	}

	function cws_prlx_add_layer ( layer, section_id ){
		var factory, layer_rel_speed, layer_params;
		factory = this;
		layer.id = !layer.id.length ? window.cws_prlx.servant.uniq_id( 'cws_prlx_layer_' ) : layer.id;
		layer_rel_speed = $( layer ).data( 'scroll-speed' );
		layer_rel_speed = layer_rel_speed != undefined ? layer_rel_speed : factory.args.def_speed;
		layer_params = {
			'el' : layer,
			'section_id' : section_id,
			'height' : null,
			'loaded' : false,
			'rel_speed' : layer_rel_speed,
			'speed' : null
		}
		window.cws_prlx.layers[layer.id] = layer_params;
		return layer.id;
	}

	function cws_prlx_remove_layer ( layer_id ){
		var layers;
		layers = window.cws_prlx.layers;
		if ( layers[layer_id] != undefined ){
			delete layers[layer_id];
		}
	}

	function cws_prlx_prepare_section_data ( section_id ){
		var section, section_params, layer_sel, layers, layer, layer_id, i, section_obj;
		if ( !Object.keys( window.cws_prlx.sections ).length || window.cws_prlx.sections[section_id] == undefined ) return false;
		section_params = window.cws_prlx.sections[section_id];
		section = section_params.el;
		section_params.height = section.offsetHeight;
		section_obj = $( section );
		layers = $( section_params.layer_sel, section_obj );
		for ( i=0; i<layers.length; i++ ){
			layer = layers[i];
			layer_id = layer.id;
			if ( layer_id ) window.cws_prlx.prepare_layer_data( layer_id, section_id );
		}
	}

	function cws_prlx_prepare_layer_data ( layer_id, section_id ){
		window.cws_prlx.servant.wait_for( 'layer_loaded', [ layer_id ], function ( layer_id ){
			var layer_params, layer;
			layer_params = window.cws_prlx.layers[layer_id];
			layer = layer_params.el;
			layer_params.height = layer.offsetHeight;
			window.cws_prlx.calc_layer_speed( layer_id );
			window.cws_prlx.translate_layer( layer_id );
			layer_params.loaded = true;
		}, [ layer_id ]);    
	}

	function cws_prlx_translate_layers (){
		var layers, layer_ids, layer_id, i;
		if ( window.cws_prlx == undefined ) return;
		layers = window.cws_prlx.layers;
		layer_ids = Object.keys( layers );
		for ( i = 0; i < layer_ids.length; i++ ){
			layer_id = layer_ids[i];
			window.cws_prlx.translate_layer( layer_id );
		}
	}

	function cws_prlx_translate_layer ( layer_id ){
		var layer_params, section, layer, layer_translation, style_adjs;
		if ( window.cws_prlx == undefined || window.cws_prlx.layers[layer_id] == undefined ) return false;
		layer_params = window.cws_prlx.layers[layer_id];
		if ( layer_params.speed == null ) return false;
		if ( layer_params.section_id == undefined || window.cws_prlx.sections[layer_params.section_id] == undefined ) return false;
		section = window.cws_prlx.sections[layer_params.section_id].el;
		if ( window.cws_prlx.servant.is_visible( section ) ) {
			layer = layer_params.el;

			layer_translation = ( section.getBoundingClientRect().top - window.innerHeight ) * layer_params.rel_speed;
			style_adjs = {
				"WebkitTransform" : "translate(0%," + layer_translation + "px)",
				"MozTransform" : "translate(0%," + layer_translation + "px)",
				"msTransform" : "translate(0%," + layer_translation + "px)",
				"OTransform" : "translate(0%," + layer_translation + "px)",
				"transform" : "translate(0%," + layer_translation + "px)"
			}

			for (var key in style_adjs ){
				layer.style[key] = style_adjs[key];
			}
		}
	}

	function cws_servant (){
		this.uniq_id = cws_uniq_id;
		this.wait_for = cws_wait_for;
		this.is_visible = cws_is_visible;
		this.is_mobile = cws_is_mobile;
	}

	function cws_uniq_id ( prefix ){
		var d, t, n, id;
		var prefix = prefix != undefined ? prefix : "";
		d = new Date();
		t = d.getTime();
		n = parseInt( Math.random() * t );
		id = prefix + n;
		return id;
	}

	function cws_wait_for ( condition, condition_args, callback, callback_args ){
		var match = false;
		var condition_args = condition_args != undefined && typeof condition_args == 'object' ? condition_args : new Array();
		var callback_args = callback_args != undefined && typeof callback_args == 'object' ? callback_args : new Array();
		if ( condition == undefined || typeof condition != 'string' || callback == undefined || typeof callback != 'function' ) return match;
		match = window.cws_prlx.conditions[condition].apply( window, condition_args );
		if ( match == true ){
			callback.apply( window, callback_args );
			return true;
		}
		else if ( match == false ){
			setTimeout( function (){
				cws_wait_for ( condition, condition_args, callback, callback_args );
			}, 10);
		}
		else{
			return false;
		}
	}

	function cws_is_visible ( el ){
		var window_top, window_height, window_bottom, el_top, el_height, el_bottom, r;
		window_top = window.pageYOffset;
		window_height = window.innerHeight;
		window_bottom = window_top + window_height;
		el_top = $( el ).offset().top;
		el_height = el.offsetHeight;
		el_bottom = el_top + el_height;
		r = ( el_top > window_top && el_top < window_bottom ) || ( el_top < window_top && el_bottom > window_bottom ) || ( el_bottom > window_top && el_bottom < window_bottom ) ? true : false;
		return r;
	}

	function cws_prlx_layer_loaded_condition ( layer_id ){
		var layer, r;
		r = false;
		if ( layer_id == undefined || typeof layer_id != 'string' ) return r;
		if ( window.cws_prlx.layers[layer_id] == undefined ) return r;
		layer = window.cws_prlx.layers[layer_id].el;
		switch ( layer.tagName ){
			case "IMG":
			if ( layer.complete == undefined ){
			}
			else{
				if ( !layer.complete ){
					return r;
				}
			}
			break;  
			case "DIV":  /* for youtube video background */
			if ( /^video-/.test( layer.id ) ){
				return r;
			}
			break;      
		}
		return true;
	}

	function cws_prlx_calc_layer_speed ( layer_id ){
		var layer_params, layer, section_id, section_params, window_height;
		layer_params = window.cws_prlx.layers[layer_id];
		layer = layer_params.el;
		section_id = layer_params.section_id;
		section_params = window.cws_prlx.sections[section_id];
		window_height = window.innerHeight;
		layer_params.speed = ( ( layer_params.height - section_params.height ) / ( window_height + section_params.height ) ) * ( layer_params.rel_speed / 100 );
	}

}(jQuery));

/**********************************
********** \CWS PARALLAX **********
**********************************/

/* cws rtl */
var directRTL;
var wait_load_portfolio = false;
if (jQuery("html").attr('dir') == 'rtl') {
	directRTL = 'rtl';
} else {
	directRTL = '';
};

/* cws megamenu */
if ( window.cws_megamenu != undefined ){

	var menu = document.querySelectorAll( ".main-nav-container .main-menu" );
	for (var i = 0; i <= menu.length; i++) {
		window.cws_megamenu_main = new cws_megamenu( menu[i], {
			'fw_sel'							: '.container',
			'bottom_level_sub_menu_width_adj'	: 2
		});
	}
	
	window.cws_megamenu_sticky = new cws_megamenu( document.querySelector( "#sticky_menu" ), {
		'fw_sel'							: '.wide-container',
		'bottom_level_sub_menu_width_adj'	: 2
	});	

}

/**********************************
********** SCRIPTS INIT ***********
**********************************/
cws_modules_state_init();
is_visible_init();
cws_milestone_init();
cws_progress_bar_init();
cws_widget_divider_init();
setTimeout(cws_widget_services_init,0);

jQuery(document).ready(function (){	

	window.cws_modules_state.sync = true;
	wp_standard_processing();

	/***** Header scripts init *****/
	cws_center_menu_init();
	cws_top_panel_search();
	cws_adaptive_top_bar_init();
	cws_top_social_init();
	cws_page_header_video_init();
	cws_sidebar_bottom_info();
	cws_revslider_class_add();
	cws_blog_full_width_layout();
	cws_fullwidth_background_row();
	cws_header_bg_init();
	cws_header_imgs_cover_init();
	cws_header_parallax_init();
	cws_scroll_parallax_init();
    /****\ Header scripts init \****/
	
	/***** Other libraries init  *****/
	vimeo_init();
	cws_self_hosted_video();
	wow_init();
	cws_fancybox_init();
	isotope_init();
	onYouTubePlayerAPIReady();
	/****\ Other libraries init \****/

	/***** Pages filter/pagination/carousel init  *****/
	if( jQuery('body').hasClass('customize-support') ){
		setTimeout(function() {
			cws_carousel(".cws-carousel-wrapper:not(.inner_slick)");
			cws_carousel(".cws-carousel-wrapper.inner_slick");
		}, 1);
	} else {
		cws_carousel(".cws-carousel-wrapper:not(.inner_slick)");
		cws_carousel(".cws-carousel-wrapper.inner_slick");
	}
	cws_portfolio_pagination_init();
	cws_portfolio_filter_init();
	cws_ourteam_pagination_init();
	cws_ourteam_filter_init();
	widget_archives_hierarchy_init();
	/****\ Pages filter/pagination/carousel init  \****/

	/***** CWS Features init  *****/
	cws_resize();
	cws_fs_video_bg_init();
	boxed_var_init();
	load_more_init();
	cws_parallax_init();
	cws_prlx_init_waiter();
	cws_message_box_init();
	jQuery( ".cws-milestone-module" ).cws_milestone();
	jQuery( ".cws-progress-bar-module" ).cws_progress_bar();
	cws_sticky_sidebars_init();
	single_sticky_content();
	smooth_comment_link();
	cws_widgets_on_tablet();
	cws_footer_on_bottom();
	scroll_to_top();
	navigation_hold_post();
	cws_roadmap_before_end_point();
	cws_roadmap_animation();
	cws_benefits_init();
	cws_countdown();
    cws_vc_pie_chart_layout();
    cws_advanced_form_focus();
    cws_service_card_activation();
    cws_service_gallery_activation();
    cws_testimonials_card_activation();
    cws_submenu_location();
	/****\ CWS Features init  \****/

	/***** CWS Fixes init  *****/
	cws_first_place_col();
	staff_advanced_hover();
	cws_custom_inputs();
	cws_fix_styles_init();
	metamax_services_hover();
	metamax_portfolio_tablet_hover();
    metamax_product_tablet_hover();
	Video_resizer();
	vc_accordion_fix();
	embed_videos_height();
	cws_megamenu_active();
	/****\ CWS Fixes init \****/

});

window.addEventListener( "load", function() {

	/***** CWS Fixes init  *****/
	cws_revslider_pause_init();
	/****\ CWS Fixes init  \****/

	/***** Header scripts init *****/
	cws_simple_sticky_menu();
	cws_smart_sticky_menu();
	mainSearchForm();
	cws_toggle_topbar();
	cws_toggle_menu();
	cws_close_menu();
	cws_click_menu_item();
	cws_side_panel_init();
	pmc_plugin_styling();
	/****\ Header scripts init \****/

	setTimeout(function() {
        cws_particles_init();
		cws_blank_preloader();
	}, 500);
	cws_infinite_autoplay();
}, false);

jQuery(window).resize( function (){
	cws_full_width_row();
	cws_fullwidth_background_row();
	cws_slider_video_height (jQuery( ".fs-video-slider" ));
	cws_slider_video_height (jQuery( ".fs-img-header" ));

	vimeo_init();
	cws_self_hosted_video ();
	Video_resizer ();

	cws_go_to_page_init();
	cws_toggle_menu();
	cws_click_menu_item();
	cws_widgets_on_tablet();
	cws_footer_on_bottom();
    cws_vc_pie_chart_layout();
    cws_submenu_location();
} );
/**********************************
********** /SCRIPTS INIT **********
**********************************/

/**********************************
******* SCRIPTS DECLARATION *******
**********************************/
function cws_modules_state_init (){
	window.cws_modules_state = {
		"sync" : false,		
	}
}

function cws_unique_id ( prefix ){
	var prefix = prefix != undefined && typeof prefix == 'string' ? prefix : "";
	var d = new Date();
	var t = d.getTime();
	var unique = Math.random() * t;	
	var unique_id = prefix + unique;
	return unique_id;	
}

function wp_standard_processing (){
	var galls;
	jQuery( "img[class*='wp-image-']" ).each( function (){
		var canvas_id;
		var el = jQuery( this );
		var parent = el.parent( "a" );
		var align_class_matches = /align\w+/.exec( el.attr( "class" ) );
		var align_class = align_class_matches != null && align_class_matches[0] != undefined ? align_class_matches[0] : "";
		var added_class = "cws_img_frame";
		if ( align_class.length ){
			if ( parent.length ){
				el.removeClass( align_class );
			}
			added_class += " " + align_class;
		}
		if ( parent.length ){
			parent.addClass( added_class );
			parent.children().wrapAll( "<div class='cws_blur_wrapper' />" );
		}
	});
	galls = jQuery( ".gallery[class*='galleryid-']" );
	if ( galls.length ){
		galls.each( function (){
			var gall = jQuery( this );
			var gall_id = cws_unique_id ( "wp_gallery_" );
			jQuery( "a", gall ).attr( "data-fancybox-group", gall_id );
		});
	}

	//Check if function exist
	if (typeof fancybox === 'function') {
		jQuery( ".gallery-icon a[href*='.jpg'], .gallery-icon a[href*='.jpeg'], .gallery-icon a[href*='.png'], .gallery-icon a[href*='.gif'], .cws_img_frame[href*='.jpg'], .cws_img_frame[href*='.jpeg'], .cws_img_frame[href*='.png'], .cws_img_frame[href*='.gif']" ).fancybox();
	}
}

/***** Header scripts declaration *****/
function cws_center_menu_init(){
	if (!is_mobile() && !is_mobile_device() && jQuery('header').hasClass('menu-center') ){
		var icons_block_width = [];
		var max_block_width;

		var headerLogoWidth = jQuery('header').find('.header-logo-part').width(),
			menuIconLeft = jQuery('.menu-left-icons .menu-icons-wrapper'),
			menuIconRight = jQuery('.menu-right-icons .menu-icons-wrapper');

		jQuery.each([menuIconLeft,menuIconRight], function(i, el) {
			icons_block_width.push(jQuery(el).width());
		});

		max_block_width = Math.max.apply(null, icons_block_width);

		if( jQuery('header').hasClass('logo-left') ){
			menuIconLeft.css('min-width', max_block_width);
			menuIconRight.css('min-width', max_block_width+headerLogoWidth);
		}

		if( jQuery('header').hasClass('logo-center') ){
			menuIconLeft.css('min-width', max_block_width);
			menuIconRight.css('min-width', max_block_width);
		}	

		if( jQuery('header').hasClass('logo-right') ){
			menuIconLeft.css('min-width', max_block_width+headerLogoWidth);
			menuIconRight.css('min-width', max_block_width);
		}		

	}
}

function cws_top_panel_search (){
	//Top bar search
	jQuery(".top-bar-search .search-icon").on('click', function(){
		var el = jQuery(this);
		el.closest('.top-bar-search').find('.row-text-search .search-field').val('');
		if(!not_desktop()){
			el.closest('.top-bar-search').find('.row-text-search .search-field').focus();
		}
		el.closest('.top-bar-search').toggleClass( "show-search" );
	});

	//Clear text (ESC)
	jQuery(".top-bar-search .row-text-search .search-field").keydown(function(event) {
		if (event.keyCode == 27){
			jQuery(this).val('');
		}
	});
}

function cws_adaptive_top_bar(first_init, top_bar_window_resolution, top_bar_left_items, top_bar_right_items){
	if( jQuery(window).width() < 768 && (localStorage.getItem('top_bar_check') != 'mobile' || first_init)){
		//Set mobile top-bar
		jQuery('.top-bar-wrapper .container').append(top_bar_left_items);
		jQuery('.top-bar-wrapper .container').append(top_bar_right_items);

		//Сheck if the script was started
		localStorage.setItem('top_bar_check', 'mobile');
	} else if( jQuery(window).width() > 767 && localStorage.getItem('top_bar_check') != 'desktop' ) {
		//Remove mobile top-bar
		jQuery(top_bar_left_items).remove();
		jQuery(top_bar_right_items).remove();

		//Set desktop top-bar
		jQuery('.top-bar-wrapper .top-bar-icons.left-icons').append(top_bar_left_items);
		jQuery('.top-bar-wrapper .top-bar-icons.right-icons').append(top_bar_right_items);

		//Сheck if the script was started
		localStorage.setItem('top_bar_check', 'desktop');

		//Reinit JS
		cws_top_panel_search();
		cws_top_social_init();
		cws_side_panel_toggle();
	}
}

function cws_adaptive_top_bar_init(){
	//Definite variables
	var top_bar_left_items = jQuery('.top-bar-wrapper .top-bar-icons.left-icons > *');
	var top_bar_right_items = jQuery('.top-bar-wrapper .top-bar-icons.right-icons > *');
	var first_init = false;

	if( jQuery(window).width() < 768 ){
		var top_bar_window_resolution = localStorage.setItem('top_bar_check', 'mobile');
		first_init = true;
	} else {
		var top_bar_window_resolution = localStorage.setItem('top_bar_check', 'desktop');
	}

	cws_adaptive_top_bar(first_init, top_bar_window_resolution, top_bar_left_items, top_bar_right_items);

	jQuery(window).resize( function (){
		cws_adaptive_top_bar(false, top_bar_window_resolution, top_bar_left_items, top_bar_right_items);
	});
}

function cws_top_social_init (){
	jQuery('.social-links-wrapper.toogle-of').on('click', function() {
		jQuery(this).find('.cws-social-links').toggleClass('active');
		jQuery(this).find('.social-btn-open-icon').toggleClass('active');
		jQuery(this).find('.social-btn-open').toggleClass('active');
	});
}

function cws_set_header_video_wrapper_height (){
	var containers = document.getElementsByClassName( 'page-header-video-wrapper' );
	for ( var i=0; i<containers.length; i++ ){
		cws_set_window_height( containers[i] );
	}			
}

function cws_page_header_video_init (){
	cws_set_header_video_wrapper_height();
	window.addEventListener( 'resize', cws_set_header_video_wrapper_height, false )
}

function cws_side_panel_toggle(){
	jQuery(".side-panel-trigger").on( 'click', function(){
		jQuery("body").toggleClass("side-panel-show");
		return false;
	});
}

function cws_side_panel_init () {
	if( jQuery('.side-panel-container').hasClass('appear-pull') ){
		jQuery('body').addClass('side-panel-pull');
	}

	cws_side_panel_toggle();

	jQuery(".side-panel-overlay, .close-side-panel").on( 'click', function(){
		jQuery("body").removeClass("side-panel-show");
		jQuery("body").removeClass('oveflow-hidden');
		jQuery(".page-content aside").removeClass('active');
	});
}

function pmc_plugin_styling(){
	jQuery('.tradingview-widget-copyright').remove();
}

function cws_sidebar_bottom_info(){
    var sideBarBottomHeight = jQuery('.side-panel-bottom').height();
    jQuery('.side-panel').css('padding-bottom', sideBarBottomHeight + 80);
}

function cws_blank_preloader(){
	jQuery('.cws-blank-preloader').removeClass('active');
}

function cws_particles_init(){
    var particlesID = '';
    var particlesColor = '#fff';
    var particlesSpeed = 2;
    var particlesSaturation = 200;
    var particlesSize = 10;
    var particlesCount = 8;
    var particlesHide = 767;

    var particlesImage1 = '';
    var particlesImage2 = '';
    var particlesImage3 = '';
    var particlesImage4 = '';

    jQuery('.particles-js').each(function(i, el) {

        particlesID = jQuery(el).attr('id');

        if( jQuery(el).data('hide') != undefined ){
            particlesHide = jQuery(el).data('hide');
        }

        if( jQuery(window).width() > particlesHide ){

            /* -----> Grab data attributes <----- */
            if( jQuery(el).data('color') != undefined ){
                particlesColor = jQuery(el).data('color');
            }
            if( jQuery(el).data('saturation') != undefined ){
                particlesSaturation = jQuery(el).data('saturation');
            }
            if( jQuery(el).data('speed') != undefined ){
                particlesSpeed = jQuery(el).data('speed');
            }
            if( jQuery(el).data('size') != undefined ){
                particlesSize = jQuery(el).data('size');
            }
            if( jQuery(el).data('count') != undefined ){
                particlesCount = jQuery(el).data('count');
            }
            if( jQuery(el).data('image') != undefined ){
                particlesImage1 = jQuery(el).data('image') + "/img/particles/particle-1.svg";
                particlesImage2 = jQuery(el).data('image') + "/img/particles/particle-2.svg";
                particlesImage3 = jQuery(el).data('image') + "/img/particles/particle-3.svg";
                particlesImage4 = jQuery(el).data('image') + "/img/particles/particle-4.svg";
                // particlesImage5 = jQuery(el).data('image') + "/img/particles/particle-yellow-2.svg";
            }

            /* -----> Particles Init <----- */
            var test = particlesJS(particlesID,
                {
                    "particles": {
                        "number": {
                            "value": particlesCount,
                            "density": {
                                "enable": false,
                                "value_area": particlesSaturation
                            }
                        },
                        "color": {
                            "value": particlesColor
                        },
                        "shape": {
                            "type": ["image", "image2", "image3", "image4"],
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            },
                            "polygon": {
                                "nb_sides": 5
                            },
							"image": {
                                "src": particlesImage1,
                                "width": 83,
                                "height": 83
                            },
							"image2": {
                                "src": particlesImage2,
                                "width": 83,
                                "height": 83
                            },
                            "image3": {
                                "src": particlesImage3,
                                "width": 83,
                                "height": 83
                            },
							"image4": {
                                "src": particlesImage4,
                                "width": 83,
                                "height": 83
                            }
                        },
                        "opacity": {
                            "value": 1,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 0.2,
                                "opacity_min": 0.5,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": particlesSize,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 1,
                                "size_min": particlesSize * 0.7,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": false,
                            "distance": 150,
                            "color": "#000000",
                            "opacity": 1,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": particlesSpeed,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "bounce",
                            "attract": {
                                "enable": false,
                                "rotateX": 0,
                                "rotateY": 0
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": false,
                                "mode": "repulse"
                            },
                            "onclick": {
                                "enable": false,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 100,
                                "line_linked": {
                                    "opacity": .7
                                }
                            },
                            "bubble": {
                                "distance": 250,
                                "size": particlesSize*1.5,
                                "duration": 2,
                                "opacity": 1,
                                "speed": 1.5
                            },
                            "repulse": {
                                "distance": 100
                            },
                            "push": {
                                "particles_nb": 1
                            },
                            "remove": {
                                "particles_nb": 2
                            }
                        }
                    },
                    "retina_detect": true,
                }
            );

        }

    });
}

function cws_revslider_class_add (){
	if (jQuery('.rev_slider_wrapper.fullwidthbanner-container').length) {
		jQuery('.rev_slider_wrapper.fullwidthbanner-container').next().addClass('benefits-after-slider');
		if (jQuery('.rev_slider_wrapper.fullwidthbanner-container').length && jQuery('.site-main main .benefits_cont:first-child').length) {
			if (jQuery('.site-main main .benefits_cont:first-child').css("margin-top").replace("px", "") < -90) {
				jQuery('.site-main main .benefits_cont:first-child').addClass('responsive-minus-margin');
			}
		}
	};
}

function cws_blog_full_width_layout() {
	function cws_blog_full_width_controller(){
		var div = jQuery('.posts-grid.posts_grid_fw_img');
		jQuery(div).each(function(){
			div = jQuery(this);
			if (!div.hasClass('posts_grid_carousel')) {
				var doc_w = jQuery(window).width();
				var div_w = jQuery('.page-content main .grid-row').width();
				var marg = ( doc_w - div_w ) / 2;

				div.each(function() {
					jQuery(this).css({
						'margin-left' : '-'+(marg-15)+'px',
						'margin-right' : '-'+(marg-15)+'px'
					})
				});
				div.find('article.posts_grid_post').each(function() {
					jQuery(this).css({
						'padding-left' : marg+'px',
						'padding-right' : marg+'px'
					})
				})
			}
		});
	}
	cws_blog_full_width_controller();

	jQuery(window).resize( function(){
		cws_blog_full_width_controller();
	});
}

function cws_fix_vc_full_width_row(){
    if( jQuery('html').attr('dir') == 'rtl' ){        
        var $elements = jQuery('.cws-stretch-row[data-vc-full-width="true"]');
        jQuery($elements).each(function( i, el ){
        	jQuery(el).css('right', '-'+jQuery(el).css('left')).css('left', '');	
        });
    }
}

// Fixes rows in RTL
jQuery(document).on('vc-full-width-row', function () {
    cws_fix_vc_full_width_row();
});

// Run one time because it was not firing in Mac/Firefox and Windows/Edge some times
cws_fix_vc_full_width_row();

var IE_fix_section = '.cws-vc-shortcode-grid.layout-2';

function cws_full_width_row(IE_fix_section){
	var section = jQuery(section);
    var $elements = jQuery(section).find('[data-cws-full-width="true"]');
    $elements.after('<div class="cws_row-full-width"></div>');
    jQuery.each($elements, function(key, item) {
        var $el = jQuery(this);
        var test = $el.attr("data-cws-full-width-init");
        $el.addClass("vc_hidden");
        var $el_full = $el.next(".cws_row-full-width");
        if ($el_full.length || ($el_full = $el.parent().next(".cws_row-full-width")),
        $el_full.length) {
            var el_margin_left = parseInt($el.css("margin-left"), 10), 
        		el_margin_right = parseInt($el.css("margin-right"), 10), 
        		offset = 0 - $el_full.offset().left - el_margin_left, 
        		width = jQuery(window).width(), 
        		cws_styles = '', 
        		top = $el.css('top');

            cws_styles += "position: absolute;";
            cws_styles += "left: "+offset+"px !important;";
            cws_styles += "box-sizing: border-box;";
            cws_styles += "width: "+width+ "px;";
            cws_styles += "top: "+top+ ";";

            if (!$el.data("vcStretchContent")) {
                var padding = -1 * offset;
                0 > padding && (padding = 0);
                var paddingRight = width - padding - $el_full.width() + el_margin_left + el_margin_right;
                0 > paddingRight && (paddingRight = 0);
                cws_styles += "padding-left:"+ padding + "px;";
                cws_styles += "padding-right:"+ paddingRight + "px;";
            }

            $el.css("cssText", cws_styles);

            $el.attr("data-cws-full-width-init", "true"),
            $el.removeClass("vc_hidden");
        }
    });
}

function cws_fullwidth_background_row(){
	var main_width = jQuery('main').width();
	var row_bg_ofs, column_first_ofs, column_last_ofs;
	jQuery('.row_bg.fullwidth_background_bg').each(function(){

		row_bg_ofs = jQuery(this).offset();

		column_first_ofs = jQuery(this).find('.grid-col:first-child .cols-wrapper').offset();
		column_last_ofs = jQuery(this).find('.grid-col:last-child .cols-wrapper').offset();

		jQuery(this).find('.grid-col:first-child > .cols-wrapper > .row_bg_layer').css({'left':''+( row_bg_ofs.left - column_first_ofs.left )+'px','width':'auto','right':'0'});
		jQuery(this).find('.grid-col:first-child > .cols-wrapper > .row_bg_img_wrapper').css({'left':''+( row_bg_ofs.left - column_first_ofs.left )+'px','width':'auto','right':'0'});

		jQuery(this).find('.grid-col:last-child > .cols-wrapper > .row_bg_layer').css({'left':'0px','width':'auto','right':'-'+(jQuery(this).outerWidth() + row_bg_ofs.left - column_last_ofs.left - jQuery(this).find('.grid-col:last-child .cols-wrapper').outerWidth())+'px'});
		jQuery(this).find('.grid-col:last-child > .cols-wrapper > .row_bg_img_wrapper').css({'left':'0px','width':'auto','right':'-'+(jQuery(this).outerWidth() + row_bg_ofs.left - column_last_ofs.left - jQuery(this).find('.grid-col:last-child .cols-wrapper').outerWidth())+'px'});
		
	});
}

function cws_header_bg_controller ( bg_section ){
	var benefits_area = jQuery( ".benefits_area" ).eq( 0 );
	var page_content_section = jQuery( ".page-content" ).eq( 0 );
	var top_curtain_hidden_class = "hidden";
	var top_panel = jQuery( "#site_top_panel" );
	var top_curtain = jQuery( "#top_panel_curtain" );
	var consider_top_panel = top_panel.length && top_curtain.length && top_curtain.hasClass( top_curtain_hidden_class );
		if ( benefits_area.length ){
			if ( consider_top_panel ){
				bg_section.css( {
					'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + top_panel.outerHeight() + "px",
					'margin-top' : "-" + ( bg_section.parent().offset().top + top_panel.outerHeight() ) + "px"
				});
			}
			else{
				bg_section.css( {
					'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + "px",
					'margin-top' : "-" + bg_section.parent().offset().top + "px"
				});
			}
			bg_section.addClass( 'height_assigned' );
		}
		else if ( page_content_section.length ){
			if ( page_content_section.hasClass( "single-sidebar" ) || page_content_section.hasClass( "double-sidebar" ) ){
				if ( consider_top_panel ){
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + bg_section.parent().offset().top + top_panel.outerHeight() + "px",
						'margin-top' : "-" + ( bg_section.parent().offset().top + top_panel.outerHeight() ) + "px"
					});
				}
				else{
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + bg_section.parent().offset().top + "px",
						'margin-top' : "-" + bg_section.parent().offset().top + "px"
					});
				}
				bg_section.addClass( 'height_assigned' );				
			}
			else{
				if ( consider_top_panel ){
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + top_panel.outerHeight() + "px",
						'margin-top' : "-" + ( bg_section.parent().offset().top + top_panel.outerHeight() ) + "px"
					});
				}
				else{
					bg_section.css({
						'height' : bg_section.parent().outerHeight() + 200 + bg_section.parent().offset().top + "px",
						'margin-top' : "-" + bg_section.parent().offset().top + "px"
					});
				}
				bg_section.addClass( 'height_assigned' );				
			}
		}
}

function cws_header_bg_init(){
	var bg_sections = jQuery('.header-bg-img, .cws-parallax-scene-container');
	bg_sections.each( function (){
		var bg_section = jQuery( this );
		cws_header_bg_controller( bg_section );
	});
	window.addEventListener( 'resize', function (){
		var bg_sections = jQuery('.header-bg-img, .cws-parallax-scene-container');
		bg_sections.each( function (){
			var bg_section = jQuery( this );
			cws_header_bg_controller( bg_section );
		});
	}, false );
}

function cws_header_imgs_cover_controller (){
	var prlx_sections, prlx_section, section_imgs, section_img, i, j;
	var prlx_sections = jQuery( '.cws-parallax-scene-container > .cws-parallax-scene, .header-bg-img >' +
		' .cws-parallax-section');
	for ( i = 0; i < prlx_sections.length; i++ ){
		prlx_section = prlx_sections[i];
		section_imgs = jQuery( "img", jQuery( prlx_section ) );
		for ( j = 0; j < section_imgs.length; j++ ){
			section_img = section_imgs[j];
			cws_cover_image( section_img, prlx_section );
		}
	}
}

function cws_cover_image ( img, section ){
	var section_w, section_h, img_nat_w, img_nat_h, img_ar, img_w, img_h, canvas;
	if ( img == undefined || section == undefined ) return;
	section_w = section.offsetWidth;
	section_h = section.offsetHeight;	
	img_nat_w = img.naturalWidth;
	img_nat_h = img.naturalHeight;
	img_ar = img_nat_w / img_nat_h;
	if ( img_ar > 1 ){
		img_h = section_h;
		img_w = section_h * img_ar;
	}
	else{
		img_w = section_w;
		img_h = section_w / img_ar;
	}
	img.width = img_w;
	img.height = img_h;
}

function cws_header_imgs_cover_init (){
	cws_header_imgs_cover_controller ();
	window.addEventListener( "resize", cws_header_imgs_cover_controller, false );
}

function cws_wait_for_header_bg_height_assigned ( callback ){
	var header_bg_sections = jQuery( '.header-bg-img, .cws-parallax-scene-container' );
	if ( callback == undefined || typeof callback != 'function' ) return;
	cws_header_bg_height_assigned_waiter ( header_bg_sections, callback );
}

function cws_header_bg_height_assigned_waiter ( els, callback ){
	var i;
	for ( i = 0; i < els.length; i++ ){
		if ( jQuery( els[i] ).hasClass( 'height_assigned' ) ){
			els.splice( i, 1 );
		}
	}
	if ( els.length ){
		setTimeout( function (){
			cws_header_bg_height_assigned_waiter ( els, callback );
		}, 10 );
	}
	else{
		callback ();
		return true;
	}
}

function cws_header_parallax_init (){
	var scenes = jQuery( ".cws-parallax-section, .cws-parallax-scene" );
	if (typeof Parallax === 'function') {
		scenes.each( function (){
			var scene = this;
			var prlx_scene = new Parallax ( scene );
		});
	}
}

function cws_scroll_parallax_init (){
	var scroll = 0;
	var window_width = jQuery(window).width();
	var background_size_width;

	jQuery(window).scroll(function() {
		scroll = jQuery(window).scrollTop();
		window_width = jQuery(window).width();
	});


	if(jQuery('.title.has_fixed_background').length){

		var background_size_width = parseInt(jQuery('.title.has_fixed_background').css('background-size').match(/\d+/));
		var title_holder_height = jQuery('.title.has_fixed_background').height();

		if (jQuery('.bg-page-header').hasClass('hide_header')){
			var top = jQuery('.bg-page-header').data('top');
			var bottom = jQuery('.bg-page-header').data('bottom');
			title_holder_height = top+bottom+88;
		}

		var title_rate = (title_holder_height / 10000) * 7;
		var title_distance = scroll - jQuery('.title.has_fixed_background').offset().top;
		var title_bpos = -(title_distance * title_rate);
		jQuery('.title.has_fixed_background').css({'background-position': 'center 0px' });
		if(jQuery('.title.has_fixed_background').hasClass('zoom_out')){
			jQuery('.title.has_fixed_background').css({'background-size': background_size_width-scroll + 'px auto'});
		}
	}

	jQuery(window).on('scroll', function() {

		if(jQuery('.title.has_fixed_background').length){
			var title_distance = scroll - jQuery('.title.has_fixed_background').offset().top;
			var title_bpos = -(title_distance * title_rate);
			jQuery('.title.has_fixed_background').css({'background-position': 'center ' + title_bpos + 'px' });
			if(jQuery('.title.has_fixed_background').hasClass('zoom_out') && (background_size_width-scroll > window_width)){
				jQuery('.title.has_fixed_background').css({'background-size': background_size_width-scroll + 'px auto'});
			}
		}
	});
}

function cws_toggle_topbar(){
	jQuery('.topbar-trigger').off();
	jQuery('.topbar-trigger').on('click', function() {
		jQuery('.top-bar-wrapper').toggleClass('active');
		jQuery('.top-bar-wrapper .top-bar-inner-wrapper').slideToggle();

		setTimeout(function() {
			cws_infinite_autoplay();
		}, 401);
	});
}

function cws_toggle_menu(){

	// -----> Different mneu hamburger animation
	if ( cws_detect_browser() == 'Safari' ) {
		jQuery('.mobile-menu-hamburger').addClass('iOS-anim');
	} else {
		jQuery('.mobile-menu-hamburger').addClass('custom-anim');
	}

	// -----> Toggle menu
	if( jQuery('.site-header').hasClass('active-sandwich-menu') && !not_desktop() ){
		jQuery('.mobile-menu-hamburger').on('click', function() {
			jQuery('.mobile-menu-hamburger').toggleClass('active');
			jQuery('.main-nav-container').toggleClass('active');
		});
	} else if( not_desktop() ){
		jQuery('.mobile-menu-hamburger').on('click', function() {

			// -----> Close top-bar if is open
			if( is_mobile() && jQuery('.top-bar-wrapper').hasClass('active') ){
				jQuery('.top-bar-wrapper .top-bar-inner-wrapper').hide();
				jQuery('.top-bar-wrapper').removeClass('active');
			}

			jQuery(this).addClass('active');
			jQuery('body').addClass('menu-visible');
			jQuery('.top-bar-wrapper .topbar-trigger').addClass('hidden');
			jQuery('.menu-overlay').addClass('active');
			jQuery('.menu-box-wrapper').addClass('active');
		});
	}
}

function cws_close_menu(){
	jQuery('.menu-overlay').on('click', function() {
		jQuery(this).removeClass('active');
		jQuery('.menu-box-wrapper').removeClass('active');
		jQuery('.top-bar-wrapper .topbar-trigger').removeClass('hidden');
		jQuery('.mobile-menu-hamburger').removeClass('active');
		jQuery('body').removeClass('menu-visible');
	});
}

function cws_click_menu_item(){
	var initCounter = 0;

	if( not_desktop() ){
		if( initCounter == 0 ){
			var menuItem = jQuery('.menu-box-wrapper .main-menu .menu-item > a');
			var menuItemTrigger = jQuery('.menu-box-wrapper .main-menu .menu-item > .button-open');
			var currentParents;

			//Disable 'cws_go_to_page_init' script & multi init on resize
			menuItem.off();
			menuItemTrigger.off();

			menuItem.on('click', function(e) {

				if( jQuery(this).parent().hasClass('menu-item-has-children') || jQuery(this).parent().hasClass('menu-item-object-megamenu_item') ){

					if( !jQuery(this).parent().hasClass('active') ){
						//Close all anouther sub-menu`s
						currentParents = jQuery(this).parents('.menu-item');
						menuItem.parent().not(currentParents).removeClass('active');
						menuItem.parent().not(currentParents).find('.sub-menu').slideUp(300);

						jQuery(this).parent().addClass('active');
						jQuery(this).parent().children('.sub-menu').slideDown(300);

						e.preventDefault();
						return false;
					}
				}

			});

			menuItemTrigger.on('click', function() {

				if( jQuery(this).parent().hasClass('active') ){

					jQuery(this).next().slideUp(300);
					jQuery(this).parent().removeClass('active');

				} else {
					//Close all anouther sub-menu`s
					currentParents = jQuery(this).parents('.menu-item');
					menuItemTrigger.parent().not(currentParents).removeClass('active');
					menuItemTrigger.parent().not(currentParents).find('.sub-menu').slideUp(300);

					jQuery(this).next().slideDown(300);
					jQuery(this).parent().addClass('active');

				}

			});
		}
		initCounter ++;
	} else {
		initCounter = 0;
	}
}

function cws_simple_sticky_menu(){
	if( jQuery('.site-header').hasClass('sticky_simple') && !not_desktop() ){

		//Definition variables
		var menuBox = jQuery('.menu-box');
		var stickyMenu = menuBox.clone();
		var adminBar = jQuery('#wpadminbar').height();
		var menuOffset = parseInt(menuBox.offset().top);
		var currentScroll = jQuery(window).scrollTop();
		var menuBoxHeight = menuBox.outerHeight();
		var displaySticky = 0;
		var startPosition = 0;

		//Show menu on page load
		stickyMenu.addClass('sticky-menu-box');
		stickyMenu.appendTo('.header-zone');

		//Get Sticky menu height
		var stickyBoxHeight = stickyMenu.outerHeight();

		//Sticky menu declaration after getting height
		stickyMenu.css('transform', 'translateY(-'+ (stickyBoxHeight - adminBar) + 'px)');

		//Get condition for start position
		if( stickyBoxHeight <= menuBoxHeight ){
			startPosition = menuBoxHeight - stickyBoxHeight;
		}

		jQuery(window).on('scroll', function() {
			var currentScroll = jQuery(this).scrollTop();
			
			//Main script
			if( jQuery(this).scrollTop() > menuOffset + startPosition ){
				jQuery('.sticky-enable').addClass('sticky-active');
				jQuery('.sticky-enable .menu-box').css('transform', 'translateY(0px)');
				stickyMenu.show();
			}
			//Stop on the original position
			else if( currentScroll <= menuOffset ){
				jQuery('.sticky-enable').removeClass('sticky-active');
				stickyMenu.hide();
				displaySticky = 0;
			}
			
		});

		if(currentScroll > menuOffset){
			jQuery('.sticky-enable .menu-box').css('transform', 'translateY(0px)');
			jQuery('.sticky-enable').addClass('sticky-active');
		} else if(currentScroll <= menuOffset && currentScroll != 0){
			jQuery('.sticky-enable .menu-box').css('transform', 'translateY('+ (menuOffset - currentScroll - 1) +'px)');
		}
	}
}

function cws_smart_sticky_menu(){
	if( jQuery('.site-header').hasClass('sticky_smart') && !not_desktop() && jQuery('.menu-box').length != 0 ){
		//Definition variables
		var menuBox = jQuery('.menu-box');
		var stickyMenu = menuBox.clone();
		var adminBar = jQuery('#wpadminbar').height();
		var menuOffset = parseInt(menuBox.offset().top);
		var menuBoxHeight = menuBox.outerHeight();
		var currentScroll = jQuery(window).scrollTop();
		var tempScroll = jQuery(window).scrollTop();
		var minOffset = menuOffset + (menuBoxHeight * 3); //Min top offset, when should show the menu.
		var defaultSlideUp = true;
		var smoothSlideUp = false;
		var smoothSlideDown = false;
		var scrollMarkerUp = 0;
		var scrollMarkerDown = 0;
		var menuCurrentTransform = 0;
		var translateY_down = 0;
		var displaySticky = 0;
		var defaultCondition = false;

		//Sticky menu declaration 
		stickyMenu.addClass('sticky-menu-box');
		stickyMenu.appendTo('.header-zone');

		//Get Sticky menu height
		var stickyBoxHeight = stickyMenu.outerHeight();

		//Sticky menu declaration after getting height
		stickyMenu.css('transform', 'translateY(-'+ (stickyBoxHeight - adminBar) + 'px)');


		//Get condition for default position
		if( stickyBoxHeight <= menuBoxHeight ){
			defaultCondition = false;
		} else if( stickyBoxHeight > menuBoxHeight ) {
			defaultCondition = true;
		}

		//Main script
		jQuery(window).on('scroll', function() {
			currentScroll = jQuery(this).scrollTop();

			//Sticky trigger
			if( currentScroll > minOffset ){

				jQuery('.sticky-enable').addClass('sticky-active');

				//When scrolling down ----> HIDE MENU SCRIPTS
				if( tempScroll < currentScroll ){
					//Hide menu
					if( currentScroll > (menuOffset + menuBoxHeight) ){
						//Taking the scroll position
						if(smoothSlideUp == false){
							scrollMarkerDown = currentScroll;
							menuCurrentTransform = parseInt(stickyMenu.css('transform').split(',')[5]);
							smoothSlideUp = true;
						}
						var translateY_up = menuCurrentTransform - (currentScroll - scrollMarkerDown);
						stickyMenu.css('transform', 'translateY('+ parseInt(translateY_up) +'px)');
					}
					smoothSlideDown = false;
				}

				//When scrolling top ----> SHOW MENU SCRIPTS
				else if( tempScroll > currentScroll ){
					//Taking the scroll position
					if(smoothSlideDown == false){
						scrollMarkerUp = currentScroll;
						menuCurrentTransform = parseInt(stickyMenu.css('transform').split(',')[5]);
						smoothSlideDown = true;
					}
					//Smooth slide down if menu-box is visible now
					if( Math.abs(menuCurrentTransform) < stickyBoxHeight ){
						//Smooth slide down
						if( (scrollMarkerUp - currentScroll) <= (stickyBoxHeight - (stickyBoxHeight - Math.abs(menuCurrentTransform)) ) ){

							translateY_down = Math.abs(menuCurrentTransform) - (scrollMarkerUp - currentScroll);
							stickyMenu.css('transform', 'translateY(-'+parseInt(translateY_down)+'px)');
						}
					}
					//Smooth slide down if menu-box is not visible now
					else {
						if( displaySticky == 0 ){
							stickyMenu.show();
							displaySticky = 1;
						}
						//Smooth slide down
						if( (scrollMarkerUp - currentScroll) < stickyBoxHeight ){
							translateY_down = stickyBoxHeight - (scrollMarkerUp - currentScroll);
							stickyMenu.css('transform', 'translateY(-'+ parseInt(translateY_down) +'px)');
						}
						//Show menu box (Sharp movement of the mouse wheel)
						else {
							stickyMenu.css('transform', 'translateY(0px)');
						}
					}

					smoothSlideUp = false;
				}
			}
			//Stop on the original position ( Sticky < Menu Box )
			else if( !defaultCondition && currentScroll <= menuOffset + (menuBoxHeight - stickyBoxHeight) ){
				jQuery('.sticky-enable').removeClass('sticky-active');
				//Hide sticky right in center of default menu box
				if( currentScroll <= menuOffset + ((menuBoxHeight - stickyBoxHeight) / 2) ){
					stickyMenu.hide();
					displaySticky = 0;
				}
			}
			//Stop on the original position ( Sticky > Menu Box )
			else if( defaultCondition && currentScroll <= menuOffset ){
				jQuery('.sticky-enable').removeClass('sticky-active');
				stickyMenu.hide();
				displaySticky = 0;
			}

			tempScroll = currentScroll;
		});

	}
}

function cws_vc_responsive(){
	if( !not_desktop() ){
		jQuery('div[class*="desktop_vc_custom"]');
	}
}

function mainSearchForm(){
	jQuery('.menu-box .search-icon').on('click', function() {
		jQuery('.site-search-wrapper').addClass('active');
		jQuery('.site-search-wrapper').find('.search-field').focus();
	});

	jQuery('.site-search-wrapper .close-search').on('click', function(){
		jQuery('.site-search-wrapper').removeClass('active');
	});
}
/****\ Header scripts declaration \****/


/***** Other libraries declaration  *****/
function vimeo_init() {
	var element;
	var vimeoId;
	var chek;
	jQuery(".cws_Vimeo_video_bg").each(function(){
		element = jQuery(this);
		var el_width;
		var el_height;
		vimeoId = jQuery(".cws_Vimeo_video_bg").attr('data-video-id');

		jQuery("#"+vimeoId).vimeo("play");
			jQuery("#"+vimeoId).vimeo("setVolume", 0);
			jQuery("#"+vimeoId).vimeo("setLoop", true);
			el_width = element[0].offsetWidth;

		if (element[0].offsetHeight<((el_width/16)*9)) {
			el_height = (element[0].offsetWidth/16)*9;
		}else{
			el_height = element[0].offsetHeight;
			el_width = (el_height/9)*16;
		}
		jQuery("#"+vimeoId)[0].style.width = el_width+'px';
		jQuery("#"+vimeoId)[0].style.height = el_height+'px';
		setInterval(check_on_page, 1000);
	})

	function check_on_page (){
		if (document.getElementsByTagName('html')[0].hasAttribute('data-focus-chek')) {		
			if (chek < 1) {
				chek++
				jQuery("#"+vimeoId).vimeo("play");
			}else{
				chek = 1
			}									
		}else{
			jQuery("#"+vimeoId).vimeo("pause");
			chek = 0;
		}
	}	
}

function cws_self_hosted_video (){
	var element,el_width,video
	jQuery('.cws_self_hosted_video').each(function(){
		element = jQuery(this)
		video = element.find('video')
		el_width = element[0].offsetWidth;

		if (element[0].offsetHeight<((el_width/16)*9)) {
			el_height = (element[0].offsetWidth/16)*9;
		}else{
			el_height = element[0].offsetHeight;
			el_width = (el_height/9)*16;
		}
		video[0].style.width = el_width+'px';
		video[0].style.height = el_height+'px';
	})	
}

function wow_init (){
	if( typeof WOW === 'function' ){
		new WOW().init();	
	}
}

function cws_fancybox_init(){
	//Check if function exist
	if( typeof fancybox === 'function' ){
		jQuery(".fancy").fancybox();
	}
}

function isotope_init (){		
	jQuery(".news.news-pinterest .isotope").each(function(item, value){	
		jQuery(this).isotope({
			itemSelector: ".item"
		});					
	});	
	jQuery(".blog_gallery_grid.isotope").each(function(item, value){	
		jQuery(this).isotope({
			// percentPosition: true,
			itemSelector: ".pic"
		});					
	});
}

/* -----> Youtube Video <----- */
var i,
	currTime,
	duration,
	video_source,
	video_id,
	el_height,
	element,
	el_width,
	el_quality,
	player;

	element = document.getElementsByClassName("cws_Yt_video_bg"); 
	
function onYouTubePlayerAPIReady() {
	if(typeof element === 'undefined') 
		return; 
	for (var i = element.length - 1; i >= 0; i--) {
		video_source = element[i].getAttribute("data-video-source");
		video_id = element[i].getAttribute("data-video-id");
		el_width = element[i].offsetWidth;

		

		if (element[i].offsetHeight<((el_width/16)*9)) {
			el_height = (element[i].offsetWidth/16)*9;
		}else{
			el_height = element[i].offsetHeight;
			el_width = (el_height/9)*16;
		}
		if (el_width > 1920){
			el_quality = 'highres'; 
		}
		if (el_width < 1920){
			el_quality = 'hd1080'; 
		}
		if (el_width < 1280) {
			el_quality = 'hd720'; 
		}
		if (el_width < 853) {
			el_quality = 'large';
		}
		if (el_width < 640) {
			el_quality = 'medium';
		};
		rev (video_id,video_source,el_width,el_height);
		
	};
}
function rev (video_id,video_source,el_width,el_height){
	window.setTimeout(function() {
		if (!YT.loaded) {
			console.log('not loaded yet');
			window.setTimeout(arguments.callee, 50)
		} else {
			var curplayer = video_control(video_id,video_source,el_width,el_height);		
		}
	}, 50);
}

var chek = 0;
var YouTube;

function video_control (uniqid,video_source,el_width,el_height) {
	var interval;

	player = new YT.Player(uniqid, {
			height: el_height,
			width: el_width,
			videoId: video_source,
			playerVars: {
				'autoplay' : 1,
				'rel' : 0,
				'showinfo' : 0,
				'showsearch' : 0,
				'controls' : 0,
				'loop' : 1,
				'enablejsapi' : 1,
				'theme' : 'dark',
				'modestbranding' : 0,
				'wmode' : 'transparent',
			},
			events: {
				'onReady': onPlayerReady,
				'onStateChange': onPlayerStateChange
			}
		}
	);
}

window.addEventListener('focus', function() {
	checkPlayer();
	return true;
});
function onPlayerReady(event){
	YouTube = event.target;
	YouTube.mute();
	YouTube.setPlaybackQuality(el_quality);	    
}
function onPlayerStateChange(event) {	
	YouTube.playVideo();
}
function seekTo(event) {
	player.seekTo(0);									
}
function checkPlayer() {	
	if (undefined !== player && undefined !== player.getCurrentTime) {
		currTime = player.getCurrentTime(); //get video position	
		duration = player.getDuration(); //get video duration
		(currTime > (duration - 0.8)) ? seekTo(event) : '';		
	};		
					
}
function chek_on_page (){
	if (document.getElementsByTagName('html')[0].hasAttribute('data-focus-chek')) {		
		if (chek < 1 && undefined !== player.playVideo) {
			chek++
			player.playVideo();
		}else{
			chek = 1
		}									
	}else if (undefined !== player.pauseVideo) {
		player.pauseVideo();
		chek = 0;
	}
}
/*\ ----> Youtube Video <---- \*/
/****\ Other libraries declaration  \****/


/***** Pages filter/pagination/carousel declaration  *****/
function cws_carousel($class, area){
	jQuery( $class, area ).each( function() {

		var this_is = jQuery(this);

		/* -----> Getting carousel attributes <-----*/	
		var slidesToShow = this_is.data('columns');
		var slidesToScroll = this_is.data('slides-to-scroll');
		var infinite = this_is.data('infinite') == 'on';
		var pagination = this_is.data('pagination') == 'on';
		var navigation = this_is.data('navigation') == 'on';
		var autoHeight = this_is.data('auto-height') == 'on';
		var draggable = this_is.data('draggable') == 'on';
		var autoplay = this_is.data('autoplay') == 'on';
		var autoplaySpeed = this_is.data('autoplay-speed');
		var pauseOnHover = this_is.data('pause-on-hover') == 'on';
		var vertical = this_is.data('vertical') == 'on';
		var verticalSwipe = this_is.data('vertical-swipe') == 'on';
		var tabletPortrait = this_is.data('tablet-portrait');
		var landscapeMobile = this_is.data('mobile-landscape');
		var centerMode = this_is.data('center-mode') == 'on';
		var carousel = this_is.children('.cws-carousel');
		if( carousel.length == 0 ){
			carousel = this_is.find('.products.cws-carousel'); //Need for woocommerce shortcodes
		}
		var responsive = 'centerMode';
		var rtl = jQuery('body').hasClass('rtl');
		console.log();

		/* -----> Collect attributes in aruments object <-----*/	
		var args = {
			slidesToShow: slidesToShow,
			slidesToScroll: slidesToScroll,
			infinite: infinite,
			dots: pagination,
			arrows: navigation,
			adaptiveHeight: autoHeight,
			draggable: draggable,
			autoplay: autoplay,	
			autoplaySpeed: autoplaySpeed,
			pauseOnHover: pauseOnHover, 
			vertical: vertical,
			verticalSwiping: verticalSwipe,
			margin: 20,
            centerMode: centerMode,
			centerPadding: '0px',
			rtl: rtl
		};


		/* -----> Responsive rules <----- */
		if( typeof tabletPortrait !== 'undefined' && typeof landscapeMobile !== "undefined" ){
			responsive = {
				responsive: [
					{
						breakpoint: 992,
						settings: {
							slidesToShow: tabletPortrait,
                            arrows: navigation,
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: landscapeMobile,
                            arrows: false,
                            vertical: vertical,
                            verticalSwiping: verticalSwipe
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							dots: true,
							arrows: false,
                            vertical: false,
                            verticalSwiping: false
						}
					},
				]
			}
		} else if( typeof tabletPortrait !== 'undefined' ){
			responsive = {
				responsive: [
					{
						breakpoint: 992,
						settings: {
							slidesToShow: tabletPortrait,
                            arrows: navigation,
                            vertical: vertical,
                            verticalSwiping: verticalSwipe
						}
					},
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
                            arrows: false,
                            vertical: vertical,
                            verticalSwiping: verticalSwipe
						}
					},
					{
						breakpoint: 480,
						settings: {
                            arrows: false,
                            vertical: false,
                            verticalSwiping: false,
                            dots: true,
						}
					}
				]
			}
		} else if( landscapeMobile !== "undefined" ){
			responsive = {
				responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            arrows: navigation,
                            vertical: vertical,
                            verticalSwiping: verticalSwipe
                        }
                    },
					{
						breakpoint: 768,
						settings: {
							slidesToShow: landscapeMobile,
                            arrows: false,
                            vertical: vertical,
                            verticalSwiping: verticalSwipe
						}
					},
					{
						breakpoint: 480,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
                            arrows: false,
                            vertical: false,
                            verticalSwiping: false,
                            dots: true,
						}
					}
				]
			}
		} else {
			responsive = {
				responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            arrows: navigation,
                        }
                    },
					{
						breakpoint: 768,
						settings: {
							slidesToShow: 1,
							slidesToScroll: 1,
							dots: true,
							arrows: false,
						}
					}
				]
			}
		}
		
		args = jQuery.extend({}, args, responsive);

		/* -----> Carousel init <-----*/	
		carousel.slick(args);
		
	});
}

function cws_portfolio_pagination_init (){
	jQuery( ".cws-portfolio .pagination" ).each( function (){
		var pagination = jQuery( this );
		cws_portfolio_pagination ( pagination );
	});

	jQuery('.cws-portfolio-fw .pagination').each( function (){
		var pagination = jQuery( this );
		cws_portfolio_pagination ( pagination , true );
	});
}

function cws_portfolio_pagination ( pagination , is_fw ){
	if ( pagination == undefined ) return;
	if (is_fw != undefined){ 
		is_fw == is_fw ;
	}else{
		is_fw == false ;
	}
	var old_page_links = pagination.find( ".page-links" );
	var items = old_page_links.find( ".page-numbers:not(.dots)" ).not( ".current" ); 
	if (is_fw) {
		var parent = pagination.closest( ".cws-portfolio-fw" );
	}else{
		var parent = pagination.closest( ".cws-portfolio" );
	}
	
	if (is_fw) {
		var grid = parent.find( ".grid_fw" );
	}else{
		var grid = parent.find( ".cws-portfolio-items" );
	}

	if (is_fw) {
		var ajax_data_input = parent.find( "input.cws-portfolio-fw-ajax-data" );
	}else{
		var ajax_data_input = parent.find( "input.cws-portfolio-ajax-data" );
	}

	items.each( function (){
		var item = jQuery( this );
		var url = item.attr( "href" );
		var ajax_data = JSON.parse( ajax_data_input.val() );
		var action_func;
		ajax_data['url'] = url;		
		if (is_fw) {
			action_func = 'cws_portfolio_fw_pagination';
		}else{
			action_func = 'cws_portfolio_pagination';
		}

		item.on( "click", function ( e ){
			e.preventDefault();
			if ( wait_load_portfolio ) return;
			wait_load_portfolio = true;
			if (is_fw) {
				pagination.closest('.cws-portfolio-fw').find('.portfolio-loader-wraper').show();
			}else{
				pagination.closest('.cws-portfolio').find('.portfolio-loader-wraper').show();
			}
			jQuery.post( ajaxurl, {
				"action" : action_func,
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var parent_offset = parent.offset().top;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_page_links = jQuery( ".pagination .page-links", jQuery( data ) );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				if (ajax_data['pagination_style'] != 'load_more') {
					grid.isotope( 'remove', old_items );
					if ( window.scrollY > parent_offset ){
						jQuery( 'html, body' ).stop().animate({
							scrollTop : parent_offset
						}, 300);
					}					
				}
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					if (is_fw) {
						pagination.closest('.cws-portfolio-fw').find('.portfolio-loader-wraper').hide();
					}else{
						pagination.closest('.cws-portfolio').find('.portfolio-loader-wraper').hide();
					}
					grid.isotope( 'layout' );
					old_page_links.fadeOut( function (){
						old_page_links.remove();
						wait_load_portfolio = false;
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							pagination.append( new_page_links );
							new_page_links.fadeIn();
							if (is_fw){
								cws_portfolio_pagination ( pagination , true );
							}else{
								cws_portfolio_pagination ( pagination );
							}
						}
						else{
							pagination.remove();
						}
					    if (Retina.isRetina()) {
				        	jQuery(window.retina.root).trigger( "load" );
					    }
						cws_fancybox_init ();
					});
				});
			});
		});
	});
}

function cws_portfolio_filter_init (){
	var els = jQuery( ".cws-portfolio .cws-portfolio-filter" );
	els.each( function (){
		var el = jQuery( this );
		var parent = el.closest( ".cws-portfolio" );
		var grid = parent.find( ".cws-portfolio-items" );
		var ajax_data_input = parent.find( "input.cws-portfolio-ajax-data" );
		var filter_el = el.children("a");
		filter_el.on( "click", function (e){

			e.preventDefault();
			jQuery( this ).addClass('active').siblings().removeClass('active');
			var val = jQuery( this ).attr('data-filter');
			var ajax_data = JSON.parse( ajax_data_input.val() );
			ajax_data["filter"] = val;
			var old_pagination = parent.find( ".pagination" );
			var old_page_links = jQuery( ".page-links", old_pagination );

			el.closest('.cws-portfolio-header').siblings( '.cws-wrapper' ).find('.portfolio-loader-wraper').show();
			jQuery.post( ajaxurl, {
				"action" : "cws_portfolio_filter",
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_pagination = jQuery( ".pagination", jQuery( data ) );
				var new_page_links = jQuery( ".page-links", new_pagination );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				grid.append( new_items );
				el.closest('.cws-portfolio-header').siblings( '.cws-wrapper' ).find('.portfolio-loader-wraper').hide();
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					ajax_data_input.attr( "value", JSON.stringify( ajax_data ) );
					if ( old_pagination.length ){
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							old_page_links.fadeOut( function (){
								old_page_links.remove();
								old_pagination.append( new_page_links );
								new_page_links.fadeIn();
								cws_portfolio_pagination ( old_pagination );
							});
						}
						else{
							old_pagination.fadeOut( function (){
								old_pagination.remove();
							});
						}
					}
					else{
						if ( new_page_links_exists ){
							new_pagination.css( "display", "none" );
							parent.append( new_pagination );
							new_pagination.fadeIn();
							cws_portfolio_pagination ( new_pagination );
						}
					}
				    if (Retina.isRetina()) {
			        	jQuery(window.retina.root).trigger( "load" );
				    }
					cws_fancybox_init ();
				});
			});
		});
	});
}

function cws_ourteam_pagination_init (){
	var els = jQuery( ".cws_ourteam .pagination" );
	els.each( function (){
		var pagination = jQuery( this );
		cws_ourteam_pagination( pagination );
	});	
}

function cws_ourteam_pagination ( pagination ){
	if ( pagination == undefined ) return;
	var old_page_links = pagination.find( ".page-links" );
	var items = old_page_links.find( ".page-numbers" ).not( ".current" );
	var parent = pagination.closest( ".cws_ourteam" );
	var grid = parent.find( ".cws_ourteam_items" );
	var ajax_data_input = parent.find( "input.cws-ourteam-ajax-data" );
	items.each( function (){
		var item = jQuery( this );
		var url = item.attr( "href" );
		var ajax_data = JSON.parse( ajax_data_input.val() );
		ajax_data['url'] = url;
		item.on( "click", function ( e ){
			e.preventDefault();
			jQuery.post( ajaxurl, {
				"action" : "cws_ourteam_pagination",
				"data" : ajax_data
			}, function ( data, status ){
				var img_loader;
				var parent_offset = parent.offset().top;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_page_links = jQuery( ".pagination .page-links", jQuery( data ) );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				if ( window.scrollY > parent_offset ){
					jQuery( 'html, body' ).stop().animate({
						scrollTop : parent_offset
					}, 300);
				}
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					old_page_links.fadeOut( function (){
						old_page_links.remove();
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							pagination.append( new_page_links );
							new_page_links.fadeIn();
							cws_ourteam_pagination ( pagination );
						}
						else{
							pagination.remove();
						}
					    if (Retina.isRetina()) {
				        	jQuery(window.retina.root).trigger( "load" );
					    }
						cws_fancybox_init ();
					});
				});

			});
		});
	});
}

function cws_ourteam_filter_init (){
	var els = jQuery( ".cws_ourteam select.cws_ourteam_filter" );
	els.each( function (){
		var el = jQuery( this );
		var parent = el.closest( ".cws_ourteam" );
		var grid = parent.find( ".cws_ourteam_items" );
		var ajax_data_input = parent.find( "input.cws-ourteam-ajax-data" );
		el.on( "change", function (){
			var val = el.val();
			var ajax_data = JSON.parse( ajax_data_input.val() );
			ajax_data["filter"] = val;
			var old_pagination = parent.find( ".pagination" );
			var old_page_links = jQuery( ".page-links", old_pagination );
			jQuery.post( ajaxurl, {
				"action" : "cws_ourteam_filter",
				"data" : ajax_data
			}, function ( data, status ){
				console.log(data);
				var img_loader;
				var old_items = jQuery( ".item", grid );
				var new_items = jQuery( ".item", jQuery( data ) );
				var new_pagination = jQuery( ".pagination", jQuery( data ) );
				var new_page_links = jQuery( ".page-links", new_pagination );
				var new_page_links_exists = Boolean( new_page_links.children().length );
				new_items.css( "display", "none" );
				grid.isotope( 'remove', old_items );
				grid.append( new_items );
				img_loader = imagesLoaded( grid );
				img_loader.on( "always", function (){
					grid.isotope( 'appended', new_items );
					grid.isotope( 'layout' );
					ajax_data_input.attr( "value", JSON.stringify( ajax_data ) );
					if ( old_pagination.length ){
						if ( new_page_links_exists ){
							new_page_links.css( "display", "none" );
							old_page_links.fadeOut( function (){
								old_page_links.remove();
								old_pagination.append( new_page_links );
								new_page_links.fadeIn();
								cws_ourteam_pagination ( old_pagination );
							});
						}
						else{
							old_pagination.fadeOut( function (){
								old_pagination.remove();
							});
						}
					}
					else{
						if ( new_page_links_exists ){
							new_pagination.css( "display", "none" );
							parent.append( new_pagination );
							new_pagination.fadeIn();
							cws_ourteam_pagination ( new_pagination );
						}
					}
				    if (Retina.isRetina()) {
			        	jQuery(window.retina.root).trigger( "load" );
				    }
					cws_fancybox_init ();
				});
			});
		});
	});
}

function widget_archives_hierarchy_init (){
	//widget_archives_hierarchy_controller ( ".cws-widget>ul li", "ul.children", "parent_archive", "widget_archive_opener" );
	widget_archives_hierarchy_controller ( ".cws-widget .menu li", "ul.sub-menu", "menu-item-has-children", "opener" );
}

function widget_archives_hierarchy_controller ( list_item_selector, sublist_item_selector, parent_class, opener_class ){
	jQuery( list_item_selector ).has( sublist_item_selector ).each( function (){
		jQuery( this ).addClass( parent_class );
		var sublist = jQuery( this ).children( sublist_item_selector ).first();
		var level_height = jQuery( this ).outerHeight() - sublist.outerHeight();
		jQuery(this).append( "<span class='flaticon flaticon-arrow-point-to-right " + opener_class + "'></span>" );
	});
	jQuery( list_item_selector + ">" + sublist_item_selector ).css( "display", "none" );

	jQuery( document ).on( "click", "." + opener_class, function (){
		var el = jQuery(this);
		var sublist = el.siblings( sublist_item_selector );

		if ( !sublist.length ) return;
		sublist = sublist.first();

		el.parent().toggleClass( "active" );
		sublist.slideToggle( 300 );
	});
}
/****\ Pages filter/pagination/carousel declaration  \****/


/***** CWS Features declaration  *****/

function cws_fs_video_bg_init (){
	var slider_wrappers, header_height_is_set;
	header_height_is_set = document.getElementsByClassName( 'header-video-fs-view' );


	if ( !header_height_is_set.length) return;
		cws_fs_video_slider_controller( header_height_is_set[0] );
	window.addEventListener( 'resize', function (){
		cws_fs_video_slider_controller( header_height_is_set[0] );
	});
}
function cws_fs_video_slider_controller ( el ){
	cws_set_window_width( el );
	cws_set_window_height( el );
}

function cws_slider_video_height (element){
	var height_coef = element.attr('data-wrapper-height')
	if (height_coef) {
		if (window.innerWidth<960) {
			element.height(window.innerWidth/height_coef)
		}else{
			element.height(960/height_coef)
		}
	}	
}

function boxed_var_init (){
	var body_el = document.body;
	var children = body_el.childNodes;
	var child_class = "";
	var match;
	window.boxed_layout = false;
	for ( var i=0; i<children.length; i++ ){
		child_class = children[i].className;
		if ( child_class != undefined ){
			match = /page_boxed/.test( child_class );
			if ( match ){
				window.boxed_layout = true;
				break;
			}
		}
	}
}

var wait_load_posts = false;

function reload_scripts(){
	wp_standard_processing();
	cws_fancybox_init();
}

function load_more_init (){
	jQuery( document ).on( "click", ".cws-load-more", function (e){
		e.preventDefault();
		if ( wait_load_posts ) return;
		var el = jQuery(this);
		var url = el.attr( "href" );
		var paged = parseInt( el.data( "paged" ) );
		var max_paged = parseInt( el.data( "max-paged" ) );
		var template = el.data( "template" );
		var item_cont = el.parent().siblings( ".grid" );
		var isotope = false;
		var args = { ajax : "true", paged : paged, template: template };

		if ( !item_cont.length ) return;
		el.closest('.cws-wrapper').find('.portfolio_loader_wraper').show();
		wait_load_posts = true;
		jQuery.post( url, args, function ( data ){
			var new_items = jQuery(data).filter( '.item' );
			if ( !new_items.length ) return;
			new_items.css( 'display' , 'none' );
			jQuery(item_cont).append( new_items );
			el.closest('.cws-wrapper').find('.portfolio_loader_wraper').hide();
			wait_load_posts = false;
			var img_loader = imagesLoaded( jQuery(item_cont) );
			img_loader.on ('always', function (){
				reload_scripts();
				new_items.css( 'display', 'block' );
				if ( jQuery(item_cont).isotope ){
					jQuery(item_cont).isotope( 'appended', new_items);
					jQuery(item_cont).isotope( 'layout' );
				}
			    if (Retina.isRetina()) {
		        	jQuery(window.retina.root).trigger( "load" );
			    }
			    if ( paged == max_paged ){
			    	el.fadeOut( { duration : 300, complete : function (){
			    		el.remove();
			    	}})
			    }
			    else{
			    	el.data( "paged", String( paged + 1 ) );
			    }
			});
		});
	});
}

function cws_go_to_page_init(){
	if(!not_desktop()){
		var hashTagActive = false;

		jQuery('.menu-item a').on('click', function(event) {
			if(!jQuery(this).hasClass("fancy") && jQuery(this).attr("href") != "#" && jQuery(this).attr("target") != "_blank"){
				event.stopPropagation();
			    var anchor = jQuery(this).attr("href");
			    var link = anchor.replace('/#','#')
				var re = new RegExp( "^#.*$" );
				var matches = re.exec( link );

				if ((matches == null && jQuery(this).attr("href").indexOf("#") != -1) || (!!navigator.userAgent.match(/Version\/[\d\.]+.*Safari/))){
					return true;
				} else {
					event.preventDefault();
				}

			    if (hashTagActive) return;
			    hashTagActive = true;      
			    
			    if (jQuery(this).attr("href").indexOf("#") != -1 && matches !== null){

			        if (jQuery(link).length){
			                jQuery('html, body').animate({
			                scrollTop: jQuery(link).offset().top
			            }, animation_curve_speed, animation_curve_menu, function () {
			                hashTagActive = false;
			            });              
			        }

			    } else {
			        jQuery('body').fadeOut(1000, newpage(anchor)); 
			    }
			       
			}
		});

	   function newpage(e) {
	     window.location = e;
	   }
	}
}

function cws_parallax_init(){
	if (jQuery( ".cws_prlx_section" ).length) {
		jQuery( ".cws_prlx_section" ).cws_prlx();
	};
}

function cws_clone_obj ( src_obj ){
	var new_obj, keys, i, key, val;
	if ( src_obj == undefined || typeof src_obj != 'object' ) return false;
	new_obj = {};
	keys = Object.keys( src_obj );
	for ( i = 0; i < keys.length; i++ ){
		key = keys[i];
		val = src_obj[key];
		new_obj[key] = val;
	}
	return new_obj;
}

function cws_prlx_init_waiter (){
	var interval, layers, layer_ids, i, layer_id, layer_obj, layer_loaded;
	if ( window.cws_prlx == undefined ){
		return;
	}
	layers = cws_clone_obj( window.cws_prlx.layers );
	interval = setInterval( function (){
		layer_ids = Object.keys( layers );
		for ( i = 0; i < layer_ids.length; i++ ){
			layer_id = layer_ids[i];
			layer_obj = window.cws_prlx.layers[layer_id];
			layer_loaded = layer_obj.loaded;
			if ( layer_loaded ){
				delete layers[layer_id];
			}
		}
		if ( !Object.keys( layers ).length ){
			clearInterval ( interval );
		}
	}, 100);
}

function cws_message_box_init (){
	jQuery( document ).on( 'click', '.cws-msg-box-module .close-btn', function (){
		var cls_btn = jQuery(this);
		var el = cls_btn.closest( ".cws-msg-box-module" );
		el.fadeOut( function (){
			el.remove();
		});
	});
}

function cws_milestone_init (){
	jQuery.fn.cws_milestone = function (){
		jQuery(this).each( function (){		
			var el = jQuery(this);
			var number_container = el.find(".cws-milestone-number");
			var done = false;
			if (number_container.length){
				if ( !done ) done = milestone_controller (el, number_container);
				jQuery(window).scroll(function (){
					if ( !done ) done = milestone_controller (el, number_container);
				});
			}
		});
	}
}

function milestone_controller (el, number_container){
	var od, args;
	var speed = number_container.data( 'speed' );
	var number = number_container.text();
	if (el.is_visible()){
		args= {
			el: number_container[0],
			format: 'd',
		};
		if ( speed ) args['duration'] = speed;
		od = new Odometer( args );
		od.update( number );
		return true;
	}
	return false;
}

function get_digit (number, digit){
	var exp = Math.pow(10, digit);
	return Math.round(number/exp%1*10);
}

function cws_progress_bar_init (){
	jQuery.fn.cws_progress_bar = function (){
		jQuery(this).each( function (){
			var el = jQuery(this);
			var done = false;
			if (!done) done = progress_bar_controller(el);
			jQuery(window).scroll(function (){
				if (!done) done = progress_bar_controller(el);
			});
		});
	}
}

function progress_bar_controller (el){
	if (el.is_visible()){
		var progress = el.find(".progress-bar");
		var value = parseInt( progress.attr("data-value") );
		var width = parseInt(progress.css('width').replace(/%|(px)|(pt)/,""));
		var ind = el.find(".indicator");
		if ( width < value ){
			var progress_interval = setInterval( function(){
				width ++;
				progress.css("width", width+"%");
				ind.text(width+'%');
				if (width == value){
					clearInterval(progress_interval);
				}
			}, 5);
		}
		return true;
	}
	return false;
}

function cws_sticky_sidebars_init(){
	//Check if function exist
	if (typeof jQuery.fn.theiaStickySidebar === 'function') {
		if (sticky_sidebars == 1 && !is_mobile() ){
			jQuery('aside.sb-left, aside.sb-right').theiaStickySidebar({
			      additionalMarginTop: 60,
			      additionalMarginBottom: 60
			}); 		
		}
	}
}

function cws_set_window_width ( el ){
	var window_w;
	if ( el != undefined ){
		window_w = document.body.clientWidth;
		el.style.width = window_w + 'px';
	}
}
function cws_set_window_height ( el ){
	var window_h;
	if ( el != undefined ){
		window_h = window.innerHeight;
		el.style.height = window_h + 'px';
	}
}

function single_sticky_content() {
	var item = jQuery(".cws-portfolio-single-content.sticky-cont");
	var item_p = item.parent();
	if(typeof item_p.theiaStickySidebar != 'undefined'){
		item_p.theiaStickySidebar({
			additionalMarginTop: 80,
			additionalMarginBottom: 30
		}); 		
	}
}

function smooth_comment_link(){
	if( jQuery('body').hasClass('single-post') || jQuery('body').hasClass('attachment') ){
		jQuery('.post-comments a').on('click', function(e) {
			e.preventDefault();

			var href = jQuery(this).attr('href');
			var anchor = href.split('#')[1];

			jQuery('html, body').animate({
				scrollTop: jQuery('#'+anchor).offset().top
			}, 500)			
		});
	}
}

function cws_widgets_on_tablet(){
	if( cws_is_tablet_viewport() ){
		var body = jQuery('body');
		var widgetsTrigger = jQuery('.sidebar-tablet-trigger');
		var aside = jQuery('.page-content aside');
		var windowHeight = jQuery(window).height();

		var showTrigger = body.height() * 0.4;
		var widgetsPos = aside.attr('class');
				
		widgetsPos = 'trigger_'+widgetsPos;
		widgetsTrigger.addClass(widgetsPos);

		jQuery(window).on('scroll', function() {
			if( jQuery(this).scrollTop() > showTrigger ) {
				widgetsTrigger.addClass('active');
			} else {
				widgetsTrigger.removeClass('active');
			}
		});

		widgetsTrigger.on('click', function() {
			aside.addClass('active');
			body.addClass('oveflow-hidden');
			jQuery('.aside-widgets-overlay').addClass('active');
		});

		jQuery('.aside-widgets-overlay').on('click', function() {
			jQuery(this).removeClass('active');
			aside.removeClass('active');
			body.removeClass('oveflow-hidden');
		});
	}
}

function cws_footer_on_bottom(){
	if( jQuery(window).height() > jQuery('body').height() ){
		jQuery('.copyrights-area').addClass('bottom_fixed');
	} else {
		jQuery('.copyrights-area').removeClass('bottom_fixed');
	}
}

function scroll_to_top(){

	jQuery('.footer-icon').on('click', function() {
		jQuery('html, body').animate({
			scrollTop: 0
		}, 1000)
	});
}

function navigation_hold_post(){
	jQuery('.nav-post-links .nav-post').on('click', function() {
		jQuery(this).addClass('active');
	});
}

function cws_roadmap_before_end_point(){
	if( jQuery('body').hasClass('rtl') ){
		jQuery('.roadmap-row.breakpoint').find('.end-point').nextAll().addClass('before_end');
	} else {
		jQuery('.roadmap-row.breakpoint').find('.end-point').prevAll().addClass('before_end');
	}
}

function cws_roadmap_animation(){
	jQuery('.roadmap-row').each(function(i, el){
		//Animate icons when module is visible
		var module_offset = jQuery(el).offset().top;

		if( jQuery(window).scrollTop() + (jQuery(window).height() * 0.7) >= module_offset ){
			jQuery(el).addClass('active');
		} else {
			jQuery(window).on('scroll', function() {
				if( jQuery(window).scrollTop() + (jQuery(window).height() * 0.7) >= module_offset ){
					jQuery(el).addClass('active');
				}
			});
		}
	});
}

function cws_benefits_init(){
	if( !not_desktop() ){
		cws_benefits_animation('hover');
	} else {
		cws_benefits_animation('click');
	}

	cws_benefits_equal_height();
}
function cws_benefits_animation(trigger){
	jQuery('.cws-benefits-module').on(trigger, function() {
		jQuery(this).closest('.vc_row').find('.cws-benefits-module').addClass('hidden');
		jQuery(this).removeClass('hidden');
	});
}
function cws_benefits_equal_height(){
	jQuery('.cws-benefits-module').each(function(i, el){
		if( jQuery(el).closest('.vc_row').hasClass('vc_row-o-equal-height') ){
			jQuery(el).parent('.wpb_wrapper').css('height', '100%');
			jQuery(el).css('height', '100%');
		}
	});
}

function cws_infinite_autoplay(){
	if( jQuery('.top-bar-ticker').length != 0 ){
		jQuery('.top-bar-ticker').simplemarquee();
	}
}

function cws_countdown(){
	if( jQuery('.countdown_clock').length != 0 ){

		var clock;
		var futureDate;
		var diff;
		var currentDate = new Date();

		jQuery('.countdown_clock').each(function(i, el){
			futureDate = new Date(jQuery(el).data('time'));
			diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;

			clock = jQuery(el).FlipClock(diff, {
				clockFace: 'DailyCounter',
				countdown: true
			});
		});
	}
}

function cws_vc_pie_chart_layout(){
    jQuery('.vc_pie_chart').each( function() {
		var width = jQuery('.vc_pie_wrapper', this).width();
        var width_block = jQuery(this).width();
		var fsize_title = Math.round(width_block*0.1);
		var fsize_value = Math.round(width*0.152);
		var title_padding = Math.round(width*0.1826);
		if (fsize_title < 20) {
			fsize_title = 20;
		}
		if (title_padding > 100) {
            title_padding = 100;
		}
        jQuery('.wpb_pie_chart_heading', this).css({'font-size': fsize_title + 'px', 'padding-top': title_padding + 'px'});
        jQuery('.vc_pie_chart_value', this).css({'font-size': fsize_value + 'px'});
    });
}

function cws_advanced_form_focus() {
	jQuery('.form-advanced').each(function() {
		jQuery('.wpcf7-text', this).on('focus', function() {
			jQuery(this).parents('.form-field-wrapper').addClass('focused');
		});
        jQuery('.wpcf7-text', this).on('blur', function() {
            jQuery(this).parents('.form-field-wrapper').removeClass('focused');
        });
	});
}

function cws_service_card_activation() {
	jQuery('.service-type-card').each(function() {
		var row = jQuery(this).parents('.cws-content');
		jQuery('.service-type-card:first', row).addClass('hovered');
        jQuery('.service-type-card', row).on('mouseover', function() {
        	if (jQuery(this).hasClass('hovered')) {
        		return false;
			} else {
                jQuery('.service-type-card', row).removeClass('hovered');
                jQuery(this).addClass('hovered');
			}
		});
	});
}

function cws_service_gallery_activation() {
    jQuery('.service-type-gallery').each(function() {
        var row = jQuery(this).closest('.cws-content');
        jQuery('.service-type-gallery', row).eq(1).addClass('hovered');
        jQuery('.service-type-gallery', row).on('mouseover', function() {
            if (jQuery(this).hasClass('hovered')) {
                return false;
            } else {
                jQuery('.service-type-gallery', row).removeClass('hovered');
                jQuery(this).addClass('hovered');
            }
        });
    });
}

function cws_testimonials_card_activation() {
	jQuery('.cws-testimonial-module.style-1.cws-carousel-wrapper').each(function() {
        jQuery('.cws-testimonial-item').on('mouseover', function() {
        	if (jQuery(this).hasClass('slick-center')) {
        		return false;
			} else {
                jQuery(this).addClass('active').siblings('.slick-center').removeClass('slick-center').addClass('temp');
			}
		});
        jQuery('.cws-testimonial-item').on('mouseleave', function() {
            jQuery(this).removeClass('active').siblings('.temp').removeClass('temp').addClass('slick-center');
        });
	});
}

function cws_submenu_location() {
    jQuery(".header-nav-part .sub-menu .sub-menu").each(function() {
        if ( jQuery(window).width() >= 1200 ) {
            var menu_width = Math.trunc(parseInt(jQuery(this).outerWidth()));

            var parent_offset_left = Math.trunc(parseInt(jQuery(this).parent('li').offset().left));
            var parent_width = Math.trunc(parseInt(jQuery(this).parent('li').outerWidth()));

            var menu_offset_left = Math.trunc(parent_offset_left + parent_width);
            var menu_offset_right = Math.trunc(parseInt(jQuery(window).width() - menu_offset_left - menu_width));

            if (menu_offset_right < 15) {
                jQuery(this).css({
                    'left': 'auto',
                    'right': '100%'
                });
            } else {
                jQuery(this).css({
                    'left': '100%',
                    'right': 'auto'
                });
            }
        } else {
            jQuery(this).removeAttr('style');
        }
    });
}
/****\ CWS Features declaration  \****/

/***** CWS Fixes declaration  *****/

function cws_revslider_pause_init (){
	var slider_els, slider_el, slider_id, id_parts, revapi_ind, revapi_id, i;
	var slider_els = document.getElementsByClassName( "rev_slider" );
	window.cws_revsliders = {};
	if ( !slider_els.length ) return;
	for ( i = 0; i < slider_els.length; i++ ){
		slider_el = slider_els[i];
		slider_id = slider_el.id;
		id_parts = /rev_slider_(\d+)(_\d+)?/.exec( slider_id );
		if ( id_parts == null ) continue;
		if ( id_parts[1] == undefined ) continue;
		revapi_ind = id_parts[1];
		revapi_id = "revapi" + revapi_ind;
		window.cws_revsliders[slider_id] = {
			'el' : slider_el,
			'api_id' : revapi_id,
			'stopped' : false
		}
		window[revapi_id].on( 'bind', 'revolution.slide.onloaded', function (){
			cws_revslider_scroll_controller ( slider_id );
		});
		window.addEventListener( 'scroll', function (){
			cws_revslider_scroll_controller ( slider_id );
		});	
	}	
}
function cws_revslider_scroll_controller ( slider_id ){
	var slider_obj, is_visible;
	if ( slider_id == undefined ) return;
	slider_obj = window.cws_revsliders[slider_id];
	is_visible = jQuery( slider_obj.el ).is_visible();
	if ( is_visible && slider_obj.stopped ){
		window[slider_obj.api_id].revresume();
		slider_obj.stopped = false;
	}
	else if ( !is_visible && !slider_obj.stopped ){
		window[slider_obj.api_id].revpause();	
		slider_obj.stopped = true;		
	}
}

function cws_first_place_col(){
	jQuery('.first_col_trigger').each(function(i, el) {
		jQuery(el).next().addClass('vc_inner_col-first-place');
	});

	jQuery('.vc_col-first-place').closest('.vc_row').addClass('custom_flex_row');
	jQuery('.vc_inner_col-first-place').closest('.vc_row').addClass('custom_inner_flex_row');
}

function staff_advanced_hover() {
	if( not_desktop() ){
		jQuery('.staff-module-wrapper.style_advanced .staff-item-inner').on('click', function(e) {
			jQuery(this).closest('.cws-vc-shortcode-grid').find('.staff-item-inner').removeClass('active');
			jQuery(this).addClass('active');

			e.preventDefault();
			return false;
		});
	}
}

function cws_custom_inputs() {
	jQuery('.cws-input-mail').closest('p').addClass('cws-input-mail');
	jQuery('.cws-submit-mail').closest('p').addClass('cws-submit-mail');

	jQuery('.big-input').closest('p').addClass('big-input');
	jQuery('.submit-inside').closest('p').addClass('submit-inside');
	jQuery('.second-color').closest('p').addClass('second-color');
	jQuery('.full-width').closest('p').addClass('full-width');
	jQuery('.width-50').closest('p').addClass('width-50');
	jQuery('.float-right').closest('p').addClass('float-right');

	jQuery('.cws-standart-form').closest('form').addClass('cws-standart-form');

	jQuery('.wpcf7-form-control-wrap').children().each(function(i, el) {
		if( el.tagName == 'SELECT' ){
			jQuery(el).parent().addClass('cws-custom-select');
		}
	});
}

function cws_fix_styles_init(){
	//Full width map fix
	jQuery('#wpgmza_map').closest('.cws-column').addClass('full_width_map');

	var browser = cws_detect_browser();
	jQuery('body').addClass('browser_'+browser);
}

function metamax_services_hover(){
	if( !not_desktop() ){
		jQuery('.cws-service-module.scale_on_hover').hover( function() {
			jQuery(this).closest('.vc_row').find('.cws-column-wrapper').css('z-index', '1');
			jQuery(this).closest('.cws-column-wrapper').css('z-index', '2');
		});
	}
}

function metamax_portfolio_tablet_hover(){
	if( not_desktop() ){
		jQuery('.portfolio-module-wrapper.cws-carousel-wrapper .item .item-content, .cws-vc-shortcode-grid .item .item-content').on('click', function(e) {
			if( !jQuery(this).hasClass('active') ){
				jQuery('.item .item-content').removeClass('active');
				jQuery(this).addClass('active');
				e.preventDefault();
				return false;
			}
		});
	}
}
function metamax_product_tablet_hover(){
    if( not_desktop() ){
        jQuery('.products .product .metamax-shop-loop-item-content-wrapper').on('click', function(e) {
            if( !jQuery(this).hasClass('active') ){
                jQuery('.products .product .metamax-shop-loop-item-content-wrapper').removeClass('active');
                jQuery(this).addClass('active');
                e.preventDefault();
                return false;
            }
        });
    }
}

function Video_resizer (){
	if (element.length) {
		for (var i = element.length - 1; i >= 0; i--) {
			video_source = element[i].getAttribute("data-video-source");
			video_id = element[i].getAttribute("data-video-id");
			el_width = element[i].offsetWidth;
		

			if (element[i].offsetHeight<((el_width/16)*9)) {
				el_height = (element[i].offsetWidth/16)*9;
			}else{
				el_height = element[i].offsetHeight;
				el_width = (el_height/9)*16;
			}
			var el_iframe = document.getElementById(element[i].getAttribute("data-video-id"));
			el_iframe.style.width = el_width+'px';
			el_iframe.style.height = el_height+'px';
		};
	};
}

function vc_accordion_fix(){
	jQuery('.vc_tta-panel-title').each(function(i, el){
		if( jQuery(el).hasClass('vc_tta-controls-icon-position-right') ){
			jQuery(el).closest('.vc_tta-panel').find('.vc_tta-panel-body').addClass('vc_tta-content-position-right');
		}
	});
}

function embed_videos_height(){
	jQuery('.cws-oembed-wrapper').each(function(i, el) {
		if( typeof jQuery(el).children('iframe').attr('src') !== 'undefined' && jQuery(el).children('iframe').attr('src') != '' ){
			jQuery(el).addClass('video-inside');
		}
	});
}

function cws_megamenu_active(){
	jQuery('.menu-item-object-megamenu_item').each(function(i, el) {
		if( jQuery(el).find('.current_page_item').length != 0 ){
			jQuery(el).addClass('current_page_ancestor');
		}
	});
}

/****\ CWS Fixes declaration  \****/

/**********************************
****** /SCRIPTS DECLARATION *******
**********************************/


/*******************************************
******** OTHER SCRIPTS DECLARATION *********
*******************************************/

function is_visible_init (){
	jQuery.fn.is_visible = function (){
		return ( jQuery(this).offset().top >= jQuery(window).scrollTop() ) && ( jQuery(this).offset().top <= jQuery(window).scrollTop() + jQuery(window).height() );
	}
}

function cws_is_rtl(){
	return jQuery("body").hasClass("rtl");
}

function cws_widget_divider_init (){
	jQuery.fn.cws_widget_divider = function (){
		jQuery(this).each( function (){
			var el = jQuery(this);
			var done = false;
			if (!done) done = cws_widget_divider_controller(el);
			jQuery(window).scroll(function (){
				if (!done) done = cws_widget_divider_controller(el);
			});
		});
	}
}

function cws_widget_divider_controller (el){
	if (el.is_visible()){
		jQuery(el).addClass('divider_init');
		return true;
	}
	return false;
}

function cws_widget_services_init (){
	jQuery.fn.cws_services_icon = function (){
		jQuery(this).each( function (){
			var el = jQuery(this);
			var done = false;
			if (!done) done = cws_icon_animation_controller(el);
			jQuery(window).scroll(function (){
				if (!done) done = cws_icon_animation_controller(el);
			});
		});
	}
}

function cws_icon_animation_controller (el){
	if (el.is_visible() && jQuery(el).hasClass('add_animation_icon') ){
		jQuery(el).addClass('icon_init');
		return true;
	}
	return false;
}

function hexdec(hex_string) {
	hex_string = (hex_string + '')
	.replace(/[^a-f0-9]/gi, '');
	return parseInt(hex_string, 16);
}

function cws_Hex2RGB(hex) {
	var hex = hex.replace("#", "");
	var color = '';
	if (hex.length == 3) {
		color = hexdec(hex.substr(0,1))+',';
		color = color + hexdec(hex.substr(1,1))+',';
		color = color + hexdec(hex.substr(2,1));
	}else if(hex.length == 6){
		color = hexdec(hex.substr(0,2))+',';
		color = color + hexdec(hex.substr(2,2))+',';
		color = color + hexdec(hex.substr(4,2));
	}
	return color;
}

function cws_detect_browser() { 
    if((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) != -1 ) 
    {
        return 'Opera';
    }
    else if(navigator.userAgent.indexOf("Chrome") != -1 )
    {
        return 'Chrome';
    }
    else if(navigator.userAgent.indexOf("Safari") != -1)
    {
        return 'Safari';
    }
    else if(navigator.userAgent.indexOf("Firefox") != -1 ) 
    {
        return 'Firefox';
    }
    else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) //IF IE > 10
    {
    	return 'IE';
    }  
    else 
    {
       return 'unknown';
    }
}

/*******************************************
******* /OTHER SCRIPTS DECLARATION *********
*******************************************/


/*************************************
************** RETINA ****************
*************************************/

var retina = {};
retina.root = (typeof exports === 'undefined' ? window : exports);
retina.config = {
        // An option to choose a suffix for 2x images
        retinaImageSuffix : '@2x',

        // Ensure Content-Type is an image before trying to load @2x image
        // https://github.com/imulus/retinajs/pull/45)
        check_mime_type: true,

        // Resize high-resolution images to original image's pixel dimensions
        // https://github.com/imulus/retinajs/issues/8
        force_original_dimensions: true
    };
retina.config.retinaImagePattern = new RegExp( retina.config.retinaImageSuffix + "." );

(function() {
    function Retina() {}

    window.retina.root.Retina = Retina;

    Retina.configure = function(options) {
        if (options === null) {
            options = {};
        }

        for (var prop in options) {
            if (options.hasOwnProperty(prop)) {
                window.retina.config[prop] = options[prop];
            }
        }
    };

    Retina.init = function(context) {
        if (context === null) {
            context = window.retina.root;
        }

        var existing_onload = context.onload || function(){};

        context.onload = function() {
            var images = document.getElementsByTagName('img'), retinaImages = [], i, image;
            for (i = 0; i < images.length; i += 1) {
                image = images[i];
                if ( !retina.config.retinaImagePattern.test(image.getAttribute("src")) ){
                    if (!!!image.getAttributeNode('data-no-retina')) {
                        retinaImages.push(new RetinaImage(image));
                    }
                }
            }
            existing_onload();
        };
    };

    Retina.isRetina = function(){
        var mediaQuery = '(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)';

        if (window.retina.root.devicePixelRatio > 1) {
            return true;
        }

        if (window.retina.root.matchMedia && window.retina.root.matchMedia(mediaQuery).matches) {
            return true;
        }

        return false;
    };


    var regexMatch = /\.\w+$/;
    function suffixReplace (match) {
        return window.retina.config.retinaImageSuffix + match;
    }

    function RetinaImagePath(path, at_2x_path) {
        this.path = path || '';
        if (typeof at_2x_path !== 'undefined' && at_2x_path !== null) {
            this.at_2x_path = at_2x_path;
            this.perform_check = false;
        } else {
            if (undefined !== document.createElement) {
                var locationObject = document.createElement('a');
                locationObject.href = this.path;
                locationObject.pathname = locationObject.pathname.replace(regexMatch, suffixReplace);
                this.at_2x_path = locationObject.href;
            } else {
                var parts = this.path.split('?');
                parts[0] = parts[0].replace(regexMatch, suffixReplace);
                this.at_2x_path = parts.join('?');
            }
            this.perform_check = true;
        }
    }

    window.retina.root.RetinaImagePath = RetinaImagePath;

    RetinaImagePath.confirmed_paths = [];

    RetinaImagePath.prototype.is_external = function() {
        return !!(this.path.match(/^https?\:/i) && !this.path.match('//' + document.domain) );
    };

    RetinaImagePath.prototype.check_2x_variant = function(callback) {
        var http, that = this;
        if (this.is_external()) {
            return callback(false);
        } else if (!this.perform_check && typeof this.at_2x_path !== 'undefined' && this.at_2x_path !== null) {
            return callback(true);
        } else if (this.at_2x_path in RetinaImagePath.confirmed_paths) {
            return callback(true);
        } else {
            return callback(false);
        }
    };


    function RetinaImage(el) {
        this.el = el;
        this.path = new RetinaImagePath(this.el.getAttribute('src'), this.el.getAttribute('data-at2x'));
        var that = this;
        this.path.check_2x_variant(function(hasVariant) {
            if (hasVariant) {
                that.swap();
            }
        });
    }

    window.retina.root.RetinaImage = RetinaImage;

    RetinaImage.prototype.swap = function(path) {
        if (typeof path === 'undefined') {
            path = this.path.at_2x_path;
        }

        var that = this;
        function load() {
            var width = that.el.offsetWidth;
            var height = that.el.offsetHeight;
            if ( !that.el.complete || !width || !height ) {
                setTimeout(load, 5);
            } else {
                if (window.retina.config.force_original_dimensions) {
                    that.el.setAttribute('width', width);
                    that.el.setAttribute('height', height);
                }

                that.el.setAttribute('src', path);
            }
        }
        load();
    };


    if (Retina.isRetina()) {
        Retina.init(window.retina.root);
    }
})();

/**************************************
************** \RETINA ****************
**************************************/


/*******************************************************
************** TIPR ****************
*******************************************************/
(function($){$.fn.tipr=function(options){var set=$.extend({'speed':200,'mode':'bottom'},options);return this.each(function(){var tipr_cont='.tipr_container_'+set.mode;$(this).hover( function()
{var d_m=set.mode;if($(this).attr('data-mode'))
{d_m=$(this).attr('data-mode')
tipr_cont='.tipr_container_'+d_m;}
var out='<div class="tipr_container_'+d_m+'"><div class="tipr_point_'+d_m+'"><div class="tipr_content">'+$(this).attr('data-tip')+'</div></div></div>';$(this).append(out);var w_t=$(tipr_cont).outerWidth();var w_e=$(this).width();var m_l=(w_e / 2)-(w_t / 2);$(tipr_cont).css('margin-left',m_l+'px');$(this).removeAttr('title alt');$(tipr_cont).fadeIn(set.speed);},function()
{$(tipr_cont).remove();});});};})(jQuery);
/*******************************************************
************** \TIPR ****************
*******************************************************/