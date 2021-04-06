"use strict";
( function ($){
	cws_hooks_init ();
	cws_resize ();
	jQuery(window).scroll(function (){
		animation_init();
	});

	window.addEventListener( "load", function (){

		cws_vc_shortcode_isotope_init_plugin();
		cws_vc_shortcode_hoverdir();
		cws_vc_shortcode_fancybox_init();
		cws_vc_shortcode_posts_grid_sections_dynamic_content_init ();
		cws_vc_portfolio_footer_height();
		cws_vc_shortcode_render_styles();
		cws_vc_shortcode_portfolio_fw();
		animation_init();
		

	}, false );

	jQuery(document).ready(function (){	
		cws_load_events();
	});
	
	function cws_vc_shortcode_fancybox_init ( area ){
		var area = area == undefined ? document : area;
		if ( typeof $.fn.fancybox == 'function' ){
			$(".fancy").fancybox();
			$("a.cws_img_frame[href*='.jpg'], a.cws_img_frame[href*='.jpeg'], a.cws_img_frame[href*='.png'], a.cws_img_frame[href*='.gif'],.gallery-icon a[href*='.jpg'],.gallery-icon a[href*='.jpeg'],.gallery-icon a[href*='.png'],.gallery-icon a[href*='.gif']").fancybox();
		}
	}

	function cws_vc_shortcode_posts_grid_sections_dynamic_content_init (){
		var i, section;
		var sections = document.getElementsByClassName( 'posts-grid' );
		for ( i = 0; i < sections.length; i++ ){
			section = sections[i];
			cws_vc_shortcode_posts_grid_section_dynamic_content_init ( section );
		}
	}

	function cws_vc_shortcode_posts_grid_section_dynamic_content_init ( section ){
		var i, section_id, grid, loader, form, data_field, paged_field, filter_field, data, request_data, response_data, response_data_str, pagination, page_links, page_link, /*filter*/nav_items, load_more;
		var load_more_pg, load_more_pg_old;
		
		if ( section == undefined ) return;
		grid = section.getElementsByClassName( 'cws-vc-shortcode-grid' );
		if ( !grid.length ) return;
		grid = grid[0];
		loader = section.getElementsByClassName( 'cws-loader-holder' );
		loader = loader.length ? loader[0] : null;
		form = section.getElementsByClassName( 'posts-grid-ajax-data-form' );
		if ( !form.length ) return;
		form = form[0];
		data_field = form.getElementsByClassName( 'posts-grid-ajax-data' );
		if ( !data_field.length ) return;
		data_field = data_field[0];
		data = data_field.value;
		data = JSON.parse( data );

		section_id = data['section_id'];
		request_data = response_data = data;

    	cws_vc_shortcode_posts_grid_dynamic_pagination({
    		'section'		: section,
    		'section_id'	: section_id,
    		'grid'			: grid,
    		'loader'		: loader,
    		'form'			: form,
    		'data_field'	: data_field,
    		'paged_field'	: paged_field,
    		'filter_field'	: filter_field,
    		'data'			: data
		});

		nav_items 			= $( '.posts_grid_nav .nav_item', section );

		if ( nav_items.length ){
			nav_items.on( 'click', function ( e ){
				e.preventDefault();
				e.stopPropagation();
				var filter = $(this);
				var filterPos = filter.offset();
				var filter_val = filter.data("nav-val");
				var filter_nav = filter.data("nav-filter");
				request_data['current_filter_val'] = filter_val;
				request_data['page'] = 1;
				filter.closest('.filter-item').trigger('click');

				if( jQuery(window).width() < 767 ){
					console.log(jQuery(this));
					if( jQuery(this).closest('.filter-item').hasClass('active') ){

						jQuery(this).closest('.filter-list').addClass('active');

						e.preventDefault();
						return false;
					} else {
						jQuery(this).closest('.filter-list').removeClass('active');
					}
				}
				
				filter.closest('.posts-grid').find('.filter-item').removeClass( "active" );
				filter.closest('.filter-item').addClass( "active" );


				if(filter.closest('.posts-grid').hasClass('standard_filter')){
					var $grid = filter.closest('.posts-grid').find('.isotope');
  					$grid.isotope({ filter: filter_nav, animationEngine: 'jquery' });
  				}else{
					if ( loader != null ){
						if ( !cws_has_class( loader, "filter-action" ) ){
							cws_add_class( loader, "filter-action" );
						}
						if ( !cws_has_class( loader, "active" ) ){
							cws_add_class( loader, "active" );
						}
					}		
					filterPos.left = filterPos.left + filter.width() / 2 - 20;
					jQuery(loader).find("svg").offset(filterPos);
					jQuery(filter).find('.txt_title').css({'opacity' : 0});
					$.post( ajaxurl, {
						'action'		: 'cws_vc_shortcode_posts_grid_dynamic_filter',
						'data'			: request_data
					}, function ( response, status ){
						var response_container, old_items, new_items, pagination, new_pagination, img_loader;
						response_container = document.createElement( "div" );
						response_container.innerHTML = response;
						new_items = $( ".item", response_container );
						new_items.addClass( "hidden" );
						jQuery(filter).find('.txt_title').css({'opacity' : 1});
						new_pagination = response_container.getElementsByClassName( 'pagination dynamic' );
						new_pagination = new_pagination.length ? new_pagination[0] : null;					

						load_more_pg = response_container.getElementsByClassName( 'cws-load-more' );
						load_more_pg = load_more_pg.length ? load_more_pg[0] : null;

						old_items = $( ".item", grid );
						pagination = section.getElementsByClassName( 'pagination dynamic' );
						pagination = pagination.length ? pagination[0] : null;						

						
						load_more_pg_old = section.getElementsByClassName( 'cws-load-more' );
						load_more_pg_old = load_more_pg_old.length ? load_more_pg_old[0] : null;	


						$( grid ).isotope( 'remove', old_items );
						$( grid ).append( new_items );
						img_loader = imagesLoaded ( grid );
						img_loader.on( "always", function (){
							cws_carousel(".cws-carousel-wrapper.inner_slick", grid);
                            embed_videos_height();
							cws_vc_shortcode_fancybox_init ( grid );
							cws_vc_shortcode_hoverdir();

							$(".cws-vc-shortcode-wrapper.layout-masonry .isotope").each(function(item, value){
								isotope_masonry_resize(value);
								jQuery( window ).resize(function() {
									isotope_masonry_resize(value);
								});	
							});
							jQuery(".cws-vc-shortcode-wrapper:not(.layout-masonry) .isotope,.news.news-pinterest" +
								" .isotope").each(function(item, value){
								jQuery(this).isotope({
									itemSelector: ".item"
								});					
							});
							new_items.removeClass( "hidden" );
							if($(grid).data('isotope') != undefined){
								$( grid ).isotope( 'appended', new_items );
								$( grid ).isotope( 'layout' );	
							}else{
								$( grid ).append(new_items );
							}
								

						    if (Retina.isRetina()) {
					        	jQuery(window.retina.root).trigger( "load" );
						    }
						    if ( pagination != null || load_more_pg_old != null ){
							    cws_add_class ( pagination || load_more_pg_old, "hiding animated fadeOut" );
							    setTimeout( function (){
							    	(pagination || load_more_pg_old).parentNode.removeChild( pagination ||  load_more_pg_old);
							    	if ( new_pagination != null || load_more_pg != null ){
								    	cws_add_class( new_pagination || load_more_pg, "animated fadeIn" );
								    	section.insertBefore( new_pagination || load_more_pg, form );
								    	if(new_pagination){
									    	cws_vc_shortcode_posts_grid_dynamic_pagination({
										    		'section'		: section,
										    		'section_id'	: section_id,
										    		'grid'			: grid,
										    		'loader'		: loader,
										    		'form'			: form,
										    		'data_field'	: data_field,
										    		'paged_field'	: paged_field,
										    		'filter_field'	: filter_field,
										    		'data'			: data
									    		});								    		
								    	}
								    	if(load_more_pg){
								    		cws_vc_shortcode_posts_grid_dynamic_loadmore({
										    		'section'		: section,
										    		'section_id'	: section_id,
										    		'grid'			: grid,
										    		'loader'		: loader,
										    		'form'			: form,
										    		'data_field'	: data_field,
										    		'paged_field'	: paged_field,
										    		'filter_field'	: filter_field,
										    		'data'			: data
									    		});		
								    	}
					    		
							    	}						    	
							    }, 300);
						    }
						    else{
						    	if ( new_pagination != null || load_more_pg != null ){
							    	cws_add_class( new_pagination || load_more_pg, "animated fadeIn" );
							    	section.insertBefore( new_pagination || load_more_pg, form );
							    	if(new_pagination){
								    	cws_vc_shortcode_posts_grid_dynamic_pagination({
									    		'section'		: section,
									    		'section_id'	: section_id,
									    		'grid'			: grid,
									    		'loader'		: loader,
									    		'form'			: form,
									    		'data_field'	: data_field,
									    		'paged_field'	: paged_field,
									    		'filter_field'	: filter_field,
									    		'data'			: data
								    	});							    		
							    	}
							    	if(load_more_pg){
							    		cws_vc_shortcode_posts_grid_dynamic_loadmore({
									    		'section'		: section,
									    		'section_id'	: section_id,
									    		'grid'			: grid,
									    		'loader'		: loader,
									    		'form'			: form,
									    		'data_field'	: data_field,
									    		'paged_field'	: paged_field,
									    		'filter_field'	: filter_field,
									    		'data'			: data
								    	});		
							    	}

								}					    					    	
						    }
							response_data['current_filter_val']	= filter_val;
							response_data['page']		= 1;
							response_data_str = JSON.stringify( response_data );
							data_field.value = response_data_str;
							cws_do_action( "cws_dynamic_content", new Array({
								"section" : section
							}));
							if ( loader != null ){
								if ( cws_has_class( loader, "filter-action" ) ){
									cws_remove_class( loader, "filter-action" );
								}
								if ( cws_has_class( loader, "active" ) ){
									cws_remove_class( loader, "active" );
								}
							}
						});
					});
				}
				
			})
		}
 		
		load_more = section.getElementsByClassName( 'cws-load-more' );
		if ( load_more.length ){
			load_more = load_more[0];
			load_more.addEventListener( 'click', function ( e ){
				var page, next_page, max_paged;
				e.preventDefault();
				if ( !cws_has_class( loader, "load-more-action" ) ){
					cws_add_class( loader, "load-more-action" );
				}
				if ( !cws_has_class( loader, "active" ) ){
					cws_add_class( loader, "active" );
				}
				page = data['page'];
				max_paged = data['max_paged'];
				next_page = page + 1;
				request_data['page'] = next_page;
				if ( next_page >= max_paged ){
					cws_add_class( load_more, 'animated' );
				}
				var btnLoadMorePos = jQuery(this).offset();
				btnLoadMorePos.left = btnLoadMorePos.left + jQuery(this).outerWidth() / 2 - 20;
				btnLoadMorePos.top = btnLoadMorePos.top + 10;
				jQuery(loader).find("svg").offset(btnLoadMorePos);

				$.post( ajaxurl, {
					'action'		: 'cws_vc_shortcode_posts_grid_dynamic_pagination',
					'data'			: request_data
				}, function ( response, status ){
					if ( next_page >= max_paged ){
						load_more.parentNode.removeChild( load_more );
					}
					var response_container, new_items, img_loader;
					response_container = document.createElement( "div" );
					response_container.innerHTML = response;
					new_items = $( ".item", response_container );
					new_items.addClass( "hidden" );

					$( grid ).append( new_items );
					img_loader = imagesLoaded ( grid );
					img_loader.on( "always", function (){
						cws_carousel(".cws-carousel-wrapper.inner_slick", new_items);
                        embed_videos_height();
						cws_vc_shortcode_fancybox_init ( grid );
						cws_vc_shortcode_hoverdir();
						new_items.removeClass( "hidden" );

						$(".cws-vc-shortcode-wrapper.layout-masonry .isotope").each(function(item, value){
							isotope_masonry_resize(value);
							jQuery( window ).resize(function() {
								isotope_masonry_resize(value);
							});	
						});

						$(section).find('.cws-vc-shortcode-wrapper').not('.layout-masonry').find('.isotope').isotope({
							itemSelector: ".item"
						});				

						if( $(grid).data('isotope') != undefined ){
							$( grid ).isotope( 'appended', new_items );
							$( grid ).isotope( 'layout' );	
						}else{
							$( grid ).append(new_items );
						}

						setTimeout(function() {	
							if($(section).find('.cws-vc-shortcode-grid').hasClass('layout-1')){
								cws_full_width_row(section);
							}
						},1);

						if (Retina.isRetina()) {
							jQuery(window.retina.root).trigger( "load" );
						}

						response_data['page'] = next_page;
						response_data_str = JSON.stringify( response_data );
						data_field.value = response_data_str;

						cws_do_action( "cws_dynamic_content", new Array({
							"section" : section
						}));

						if ( loader != null ){
							if ( cws_has_class( loader, "load-more-action" ) ){
								cws_remove_class( loader, "load-more-action" );
							}
							if ( cws_has_class( loader, "active" ) ){
								cws_remove_class( loader, "active" );
							}
						}
					});
				});
			}, false );
		}			
	}

	function cws_vc_shortcode_posts_grid_dynamic_loadmore ( args ){
		var i, section,load_more, section_id, section_offset, grid, loader, form, data_field, paged_field, filter_field, data, request_data, response_data, pagination, page_links, page_link, response_data_str;

		section = args['section'];
		section_id = args['section_id'];
		grid = args['grid'];
		loader = args['loader'];
		form = args['form'];
		data_field = args['data_field'];
		paged_field	= args['paged_field'];
		filter_field = args['filter_field'];
		data = request_data = response_data = args['data'];
		section_offset = $( section ).offset().top;

		pagination = section.getElementsByClassName( 'pagination dynamic' );
		load_more = section.getElementsByClassName( 'cws-load-more' );
		if ( !load_more.length ) return;
		
		if ( load_more.length ){
			load_more = load_more[0];
			load_more.addEventListener( 'click', function ( e ){
				var page, next_page, max_paged;
				e.preventDefault();
				if ( !cws_has_class( loader, "load-more-action" ) ){
					cws_add_class( loader, "load-more-action" );
				}
				if ( !cws_has_class( loader, "active" ) ){
					cws_add_class( loader, "active" );
				}
				var btnLoadMorePos = jQuery(this).offset();
				btnLoadMorePos.left = btnLoadMorePos.left + jQuery(this).outerWidth() / 2 - 20;
				btnLoadMorePos.top = btnLoadMorePos.top + 10;
				jQuery(loader).find("svg").offset(btnLoadMorePos);
				page = data['page'];
				max_paged = data['max_paged'];
				next_page = page + 1;
				request_data['page'] = next_page;
				if ( next_page >= max_paged ){
					cws_add_class( load_more, 'hiding animated fadeOut' );
					setTimeout( function (){
						load_more.parentNode.removeChild( load_more );
					}, 300);
				}
				$.post( ajaxurl, {
					'action'		: 'cws_vc_shortcode_posts_grid_dynamic_pagination',
					'data'			: request_data
				}, function ( response, status ){
					var response_container, new_items, img_loader;
					response_container = document.createElement( "div" );
					response_container.innerHTML = response;
					new_items = $( ".item", response_container );
					new_items.addClass( "hidden" );
					$( grid ).append( new_items );
					img_loader = imagesLoaded ( grid );
					img_loader.on( "always", function (){
						cws_carousel(".cws-carousel-wrapper.inner_slick", new_items);
                        embed_videos_height();
						cws_vc_shortcode_fancybox_init ( grid );
						cws_vc_shortcode_hoverdir();
						// jQuery( ".working_day_classes" ).cws_tabs ();
						new_items.removeClass( "hidden" );
						$(".cws-vc-shortcode-wrapper.layout-masonry .isotope").each(function(item, value){
							isotope_masonry_resize(value);
							jQuery( window ).resize(function() {
								isotope_masonry_resize(value);
							});	
						});
						jQuery(".cws-vc-shortcode-wrapper:not(.layout-masonry) .isotope,.news.news-pinterest .isotope").each(function(item, value){
							jQuery(this).isotope({
								itemSelector: ".item",
							});					
						});
                        if( $(grid).data('isotope') != undefined ) {
                            $(grid).isotope('appended', new_items);
                            $(grid).isotope('layout');
                        } else {
                            $( grid ).append( new_items );
						}
						if (Retina.isRetina()) {
							jQuery(window.retina.root).trigger( "load" );
						}
						response_data['page'] = next_page;
						response_data_str = JSON.stringify( response_data );
						data_field.value = response_data_str;					
						cws_do_action( "cws_dynamic_content", new Array({
							"section" : section
						}));						
						if ( loader != null ){
							if ( cws_has_class( loader, "load-more-action" ) ){
								cws_remove_class( loader, "load-more-action" );
							}
							if ( cws_has_class( loader, "active" ) ){
								cws_remove_class( loader, "active" );
							}
						}
					});
				});
			}, false );
		}	
	}

	function cws_vc_shortcode_posts_grid_dynamic_pagination ( args ){
		var i, section,load_more, section_id, section_offset, grid, loader, form, data_field, paged_field, filter_field, data, request_data, response_data, pagination, page_links, page_link;

		section = args['section'];
		section_id = args['section_id'];
		grid = args['grid'];
		loader = args['loader'];
		form = args['form'];
		data_field = args['data_field'];
		paged_field	= args['paged_field'];
		filter_field = args['filter_field'];
		data = request_data = response_data = args['data'];
		section_offset = $( section ).offset().top;

		pagination = section.getElementsByClassName( 'pagination dynamic' );
		load_more = section.getElementsByClassName( 'cws-load-more' );
		
		if ( !pagination.length ) return;
		pagination = pagination[0];
		page_links = pagination.getElementsByTagName( 'a' );
		for ( i = 0; i < page_links.length; i++ ){
			page_link = page_links[i];
			page_link.addEventListener( 'click', function ( e ){
				e.preventDefault();
				var el = e.srcElement ? e.srcElement : e.target;
				if ( loader != null ){
					if ( !cws_has_class( loader, "pagination-action" ) ){
						cws_add_class( loader, "pagination-action" );
					}
					if ( !cws_has_class( loader, "active" ) ){
						cws_add_class( loader, "active" );
					}
				}
				var btnLoadMorePos = jQuery(this).offset();
				btnLoadMorePos.left = btnLoadMorePos.left + jQuery(this).outerWidth() / 2 - 20;
				jQuery(loader).find("svg").offset(btnLoadMorePos);

				request_data['req_page_url'] = jQuery(el).is('.page-numbers') ? el.href : jQuery(el).parent()[0].href;

				$.post( ajaxurl, {
					'action'		: 'cws_vc_shortcode_posts_grid_dynamic_pagination',
					'data'			: request_data
				}, function ( response, status ){
					var section_offset_top, response_container, page_number_field, old_items, new_items, pagination, old_page_links, new_pagination, new_page_links, img_loader, page_number, response_data_str;
					response_container = document.createElement( "div" );
					response_container = $(response_container).append(response);

					new_items = $( ".item", response_container );
					new_items.addClass( "hidden" );

					new_pagination = response_container;
					new_pagination = new_pagination.length ? new_pagination[0] : null;
					new_page_links = new_pagination != null ? $( '.page-links', new_pagination ) : [];
					new_page_links = new_page_links.length ? new_page_links[0] : null;

					page_number_field = $( '.cws_vc_shortcode_posts_grid_dynamic_pagination_page_number', new_pagination  );
					page_number_field = page_number_field.length ? page_number_field[0] : null;
					page_number = page_number_field != null ? page_number_field.value : "";

					section_offset_top = $( section ).offset().top;
					old_items = $( ".item", grid );
					pagination = section.getElementsByClassName( 'pagination dynamic' );
					pagination = pagination.length ? pagination[0] : null;

					old_page_links = pagination != null ? pagination.getElementsByClassName( 'page-links' ) : [];
					old_page_links = old_page_links.length ? old_page_links[0] : null;

					//$( grid ).isotope( 'remove', old_items );
                    if( $(grid).data('isotope') != undefined ){
                        $( grid ).isotope( 'remove', old_items );
                        $( grid ).isotope( 'layout' );
                    }else{
                        old_items.remove();
                    }
					
					if ( window.scrollY > section_offset_top ){
						jQuery( 'html, body' ).stop().animate({
							scrollTop : section_offset_top
						}, 300);
					}
					$( grid ).append( new_items );
					img_loader = imagesLoaded ( grid );
					img_loader.on( "always", function (){
                        cws_carousel(".cws-carousel-wrapper.inner_slick", new_items);
                        embed_videos_height();
                        cws_vc_shortcode_fancybox_init ( grid );
                        cws_vc_shortcode_hoverdir();
                        // jQuery( ".working_day_classes" ).cws_tabs ();
						new_items.removeClass( "hidden" );
                        $(".cws-vc-shortcode-wrapper.layout-masonry .isotope").each(function(item, value){
                            isotope_masonry_resize(value);
                            jQuery( window ).resize(function() {
                                isotope_masonry_resize(value);
                            });
                        });
                        jQuery(".cws-vc-shortcode-wrapper:not(.layout-masonry) .isotope,.news.news-pinterest .isotope").each(function(item, value){
                            jQuery(this).isotope({
                                itemSelector: ".item",
                            });
                        });

                        if( $(grid).data('isotope') != undefined ) {

                            $(grid).isotope('insert', new_items);
                            $(grid).isotope('layout');
                        } else {
                            $( grid ).append( new_items );
						}

					    if (Retina.isRetina()) {
				        	jQuery(window.retina.root).trigger( "load" );
					    }

					    cws_add_class ( old_page_links, "hiding animated fadeOut" );

					    setTimeout( function (){
					    	pagination.removeChild ( old_page_links );
					    	cws_add_class( new_page_links, "animated fadeIn" );
					    	if(new_page_links){
					    		pagination.appendChild ( new_page_links );
					    	}
					    	
					    	cws_vc_shortcode_posts_grid_dynamic_pagination({
						    	'section'		: section,
						    	'section_id'	: section_id,
						    	'grid'			: grid,
						    	'loader'		: loader,
						    	'form'			: form,
						    	'data_field'	: data_field,
						    	'paged_field'	: paged_field,
						    	'filter_field'	: filter_field,
						    	'data'			: data
					    	});
					    }, 300);

						response_data['page'] = page_number.length ? page_number : 1;
						response_data_str = JSON.stringify( response_data );
						data_field.value = response_data_str;
						cws_do_action( "cws_dynamic_content", new Array({
							"section" : section
						}));
						if ( loader != null ){
							if ( cws_has_class( loader, "pagination-action" ) ){
								cws_remove_class( loader, "pagination-action" );
							}
							if ( cws_has_class( loader, "active" ) ){
								cws_remove_class( loader, "active" );
							}
						}
						if ( window.scrollY > section_offset ){
							jQuery( 'html, body' ).stop().animate({
								scrollTop : section_offset
							}, 300);
						}
					});
				});
			});
		}
	}

	function cws_vc_shortcode_isotope_init_plugin (){
		jQuery(".cws-vc-shortcode-wrapper.layout-masonry .isotope").each(function(item, value){
			isotope_masonry_resize(value);

			jQuery( window ).resize(function() {
				isotope_masonry_resize(value);
			});

			if ( typeof jQuery.fn.isotope == 'function' && jQuery(window).width() > 767){
				jQuery(this).isotope({
					itemSelector: ".item",
					percentPosition: true,
					layoutMode: 'masonry',
					masonry: {
						columnWidth: '.grid-sizer',
					}	
				});		
			}
		});

		if ( typeof jQuery.fn.isotope == 'function' ){
			setTimeout(function(){
				jQuery(".cws-vc-shortcode-wrapper:not(.layout-masonry) .isotope,.news.news-pinterest .isotope,.tribe_events_posts_grid .isotope .cws-event-list").each(function(item, value){
					jQuery(this).isotope({
						itemSelector: ".item"
					});
				});
				cws_full_width_row();
			},0);
		}
	}

	function isotope_masonry_resize(container){
		var container, size, th, col_count, line_count, width;
		container = jQuery(container);
		size = container.find('.grid-sizer');
		width = container.width();
		var grid_num = container.parents('.posts-grid').attr('data-col');
		var col_width = width/grid_num;

		container.find('.item').each(function() {
			th = jQuery(this);
			col_count = th.data('masonry-col'); 
			line_count = th.data('masonry-line'); 
			var col = Math.ceil(col_width*col_count);
			if(container.hasClass('crop')){
				var line = Math.ceil(col_width*line_count);
			} else {
				var paddings = 30 * grid_num;

				var single_col_width = ((width - paddings) / grid_num);
				var single_col_height = ((single_col_width / 16) * 9) + 30;

				var line = single_col_height * line_count;
			}


			if( line / 2 > col_width ){
				line = Math.ceil( line + 0.5);
			}

			//If not mobile viewport
			if (jQuery(document).width() >= 768) {
				th.css('width', col-30 + 'px');
				th.css('height', line + 'px');
			} else {
			    th.css('width', '100%');
			    th.css('height','auto');				
			}
		});
	}

	function cws_vc_shortcode_hoverdir(){

		jQuery(".portfolio-item-post.hoverdir").each(function() {
			jQuery(this).hoverdir({hoverElem: '.cws-portfolio-content-wrap'})
		})
		jQuery(".portfolio-item-post.hover3d").hover3d({
			selector: ".item_content",
			shine: !1,
			perspective: 1e3,
			invert: !0
		})
	}

	(function (factory) {
		if (typeof define === 'function' && define.amd) {
			define(['jquery'], factory);
		} else if (typeof exports !== 'undefined') {
			module.exports = factory(require('jquery'));
		} else {
			factory(jQuery);
		}
	})(function ($) {
		function Hoverdir(element, options) {
			this.$el = $(element);
			this.options = $.extend(true, {}, this.defaults, options);
			this.isVisible = false;
			this.$hoverElem = this.$el.find(this.options.hoverElem);
			this.transitionProp = 'all ' + this.options.speed + 'ms ' + this.options.easing;
			this.support = this._supportsTransitions();
			this._loadEvents();
		}
		Hoverdir.prototype = {
			defaults: {
				speed: 300,
				easing: 'ease',
				hoverDelay: 0,
				inverse: false,
				hoverElem: 'div'
			},
			constructor: Hoverdir,
			_supportsTransitions: function () {
				if (typeof Modernizr !== 'undefined') {
					return Modernizr.csstransitions;
				} else {
					var b = document.body || document.documentElement,
						s = b.style,
						p = 'transition';
					if (typeof s[p] === 'string') {
						return true;
					}
					var v = ['Moz', 'webkit', 'Webkit', 'Khtml', 'O', 'ms'];
					p = p.charAt(0).toUpperCase() + p.substr(1);

					for (var i = 0; i < v.length; i++) {
						if (typeof s[v[i] + p] === 'string') {
							return true;
						}
					}

					return false;
				}
			},
			_loadEvents: function () {
				this.$el.on('mouseenter.hoverdir mouseleave.hoverdir', $.proxy(function (event) {
					this.direction = this._getDir({x: event.pageX, y: event.pageY});
					if (event.type === 'mouseenter') {
						this._showHover();
					}
					else {
						this._hideHover();
					}
				}, this));
			},
			_showHover: function () {
				var styleCSS = this._getStyle(this.direction);
				if (this.support) {
					this.$hoverElem.css('transition', '');
				}
				this.$hoverElem.hide().css(styleCSS.from);
				clearTimeout(this.tmhover);

				this.tmhover = setTimeout($.proxy(function () {
					this.$hoverElem.show(0, $.proxy(function () {
						if (this.support) {
							this.$hoverElem.css('transition', this.transitionProp);
						}
						this._applyAnimation(styleCSS.to);

					}, this));
				}, this), this.options.hoverDelay);

				this.isVisible = true;
			},
			_hideHover: function () {
				var styleCSS = this._getStyle(this.direction);
				if (this.support) {
					this.$hoverElem.css('transition', this.transitionProp);
				}
				clearTimeout(this.tmhover);
				this._applyAnimation(styleCSS.from);
				this.isVisible = false;
			},
			_getDir: function (coordinates) {
				var w = this.$el.width(),
					h = this.$el.height(),
					x = (coordinates.x - this.$el.offset().left - (w / 2)) * (w > h ? (h / w) : 1),
					y = (coordinates.y - this.$el.offset().top - (h / 2)) * (h > w ? (w / h) : 1),
					direction = Math.round((((Math.atan2(y, x) * (180 / Math.PI)) + 180) / 90) + 3) % 4;
				return direction;
			},
			_getStyle: function (direction) {
				var fromStyle, toStyle,
					slideFromTop = {'left': '0', 'top': '-100%'},
					slideFromBottom = {'left': '0', 'top': '100%'},
					slideFromLeft = {'left': '-100%', 'top': '0'},
					slideFromRight = {'left': '100%', 'top': '0'},
					slideTop = {'top': '0'},
					slideLeft = {'left': '0'};
				switch (direction) {
					case 0:
					case 'top':
						fromStyle = !this.options.inverse ? slideFromTop : slideFromBottom;
						toStyle = slideTop;
						break;
					case 1:
					case 'right':
						fromStyle = !this.options.inverse ? slideFromRight : slideFromLeft;
						toStyle = slideLeft;
						break;
					case 2:
					case 'bottom':
						fromStyle = !this.options.inverse ? slideFromBottom : slideFromTop;
						toStyle = slideTop;
						break;
					case 3:
					case 'left':
						fromStyle = !this.options.inverse ? slideFromLeft : slideFromRight;
						toStyle = slideLeft;
						break;
				}

				return {from: fromStyle, to: toStyle};
			},
			_applyAnimation: function (styleCSS) {
				$.fn.applyStyle = this.support ? $.fn.css : $.fn.animate;
				this.$hoverElem.stop().applyStyle(styleCSS, $.extend(true, [], {duration: this.options.speed}));
			},
			show: function (direction) {
				this.$el.off('mouseenter.hoverdir mouseleave.hoverdir');
				if (!this.isVisible) {
					this.direction = direction || 'top';
					this._showHover();
				}
			},
			hide: function (direction) {
				this.rebuild();
				if (this.isVisible) {
					this.direction = direction || 'bottom';
					this._hideHover();
				}
			},
			setOptions: function (options) {
				this.options = $.extend(true, {}, this.defaults, this.options, options);
			},
			destroy: function () {
				this.$el.off('mouseenter.hoverdir mouseleave.hoverdir');
				this.$el.data('hoverdir', null);
			},
			rebuild: function (options) {
				if (typeof options === 'object') {
					this.setOptions(options);
				}
				this._loadEvents();
			}
		};
		$.fn.hoverdir = function (option, parameter) {
			return this.each(function () {
				var data = $(this).data('hoverdir');
				var options = typeof option === 'object' && option;

				// Initialize hoverdir.
				if (!data) {
					data = new Hoverdir(this, options);
					$(this).data('hoverdir', data);
				}
				if (typeof option === 'string') {
					data[option](parameter);

					if (option === 'destroy') {
						$(this).data('hoverdir', false);
					}
				}
			});
		};
		$.fn.hoverdir.Constructor = Hoverdir;
	});

	(function (factory) {
		if (typeof define === 'function' && define.amd) {
			define(['jquery'], factory);
		} else if (typeof exports !== 'undefined') {
			module.exports = factory(require('jquery'));
		} else {
			factory(jQuery);
		}
	})(function($){
		$.fn.hover3d = function(options){
			var settings = $.extend({
				selector      : null,
				perspective   : 1000,
				sensitivity   : 20,
				invert        : false,
				shine         : false,
				hoverInClass  : "hover-in",
				hoverOutClass : "hover-out",
				hoverClass    : "hover-3d"
			}, options);
			return this.each(function(){
				var $this = $(this),
					$card = $this.find(settings.selector),
					currentX = 0,
					currentY = 0;
				if( settings.shine ){
					$card.append('<div class="shine"></div>');
				}
				var $shine = $(this).find(".shine");
				$this.css({
					perspective: settings.perspective+"px",
					transformStyle: "preserve-3d"
				});
				$card.css({
					perspective: settings.perspective+"px",
					transformStyle: "preserve-3d",
				});
				$shine.css({
					position  : "absolute",
					top       : 0,
					left      : 0,
					bottom    : 0,
					right     : 0,
					transform : 'translateZ(1px)',
					"z-index" : 9
				});
				function enter(event){
					$card.addClass(settings.hoverInClass+" "+settings.hoverClass);
					currentX = currentY = 0;
					setTimeout(function(){
						$card.removeClass(settings.hoverInClass);
					}, 1000);
				}
				function move(event){
					var w      = $card.innerWidth(),
						h      = $card.innerHeight(),
						currentX = Math.round(event.pageX - $card.offset().left),
						currentY = Math.round(event.pageY - $card.offset().top),
						ax 	   = settings.invert ?  ( w / 2 - currentX)/settings.sensitivity : -( w / 2 - currentX)/settings.sensitivity,
						ay     = settings.invert ? -( h / 2 - currentY)/settings.sensitivity :  ( h / 2 - currentY)/settings.sensitivity,
						dx     = currentX - w / 2,
						dy     = currentY - h / 2,
						theta  = Math.atan2(dy, dx),
						angle  = theta * 180 / Math.PI - 90;
					if (angle < 0) {
						angle  = angle + 360;
					}
					$card.css({
						perspective    : settings.perspective+"px",
						transformStyle : "preserve-3d",
						transform      : "rotateY("+ax+"deg) rotateX("+ay+"deg)"
					});
					$shine.css('background', 'linear-gradient(' + angle + 'deg, rgba(255,255,255,' + event.offsetY / h * .5 + ') 0%,rgba(255,255,255,0) 80%)');
				}
				function leave(){
					$card.addClass(settings.hoverOutClass+" "+settings.hoverClass);
					$card.css({
						perspective    : settings.perspective+"px",
						transformStyle : "preserve-3d",
						transform      : "rotateX(0) rotateY(0)"
					});
					setTimeout( function(){
						$card.removeClass(settings.hoverOutClass+" "+settings.hoverClass);
						currentX = currentY = 0;
					}, 1000 );
				}
				$this.on( "mouseenter", function(){
					return enter();
				});
				$this.on( "mousemove", function(event){
					return move(event);
				});
				$this.on( "mouseleave", function(){
					return leave();
				});
			});
		};
	})

	function animation_init() {
		var el = jQuery('.posts-grid.appear_style article.item');
		if($.Velocity){
			$.Velocity.RegisterEffect.packagedEffects = {
				"callout.bounce": {
					defaultDuration: 550,
					calls: [[{
						translateY: -30
					}, .25], [{
						translateY: 0
					}, .125], [{
						translateY: -15
					}, .125], [{
						translateY: 0
					}, .25]]
				},
				"callout.shake": {
					defaultDuration: 800,
					calls: [[{
						translateX: -11
					}, .125], [{
						translateX: 11
					}, .125], [{
						translateX: -11
					}, .125], [{
						translateX: 11
					}, .125], [{
						translateX: -11
					}, .125], [{
						translateX: 11
					}, .125], [{
						translateX: -11
					}, .125], [{
						translateX: 0
					}, .125]]
				},
				"callout.flash": {
					defaultDuration: 1100,
					calls: [[{
						opacity: [0, "easeInOutQuad", 1]
					}, .25], [{
						opacity: [1, "easeInOutQuad"]
					}, .25], [{
						opacity: [0, "easeInOutQuad"]
					}, .25], [{
						opacity: [1, "easeInOutQuad"]
					}, .25]]
				},
				"callout.pulse": {
					defaultDuration: 825,
					calls: [[{
						scaleX: 1.1,
						scaleY: 1.1
					}, .5, {
						easing: "easeInExpo"
					}], [{
						scaleX: 1,
						scaleY: 1
					}, .5]]
				},
				"callout.swing": {
					defaultDuration: 950,
					calls: [[{
						rotateZ: 15
					}, .2], [{
						rotateZ: -10
					}, .2], [{
						rotateZ: 5
					}, .2], [{
						rotateZ: -5
					}, .2], [{
						rotateZ: 0
					}, .2]]
				},
				"callout.tada": {
					defaultDuration: 1e3,
					calls: [[{
						scaleX: .9,
						scaleY: .9,
						rotateZ: -3
					}, .1], [{
						scaleX: 1.1,
						scaleY: 1.1,
						rotateZ: 3
					}, .1], [{
						scaleX: 1.1,
						scaleY: 1.1,
						rotateZ: -3
					}, .1], ["reverse", .125], ["reverse", .125], ["reverse", .125], ["reverse", .125], ["reverse", .125], [{
						scaleX: 1,
						scaleY: 1,
						rotateZ: 0
					}, .2]]
				},
				"transition.fadeIn": {
					defaultDuration: 600,
					calls: [[{
						opacity: [1, 0]
					}]]
				},
				"transition.flipXIn": {
					defaultDuration: 600,
					calls: [[{
						opacity: [1, 0],
						transformPerspective: [800, 800],
						rotateY: [0, -30]
					}]],
					reset: {
						transformPerspective: 0
					}
				},
				"transition.flipYIn": {
					defaultDuration: 600,
					calls: [[{
						opacity: [1, 0],
						transformPerspective: [1500, 1500],
						rotateX: [0, -30]
					}]],
					reset: {
						transformPerspective: 0
					}
				},
				"transition.shrinkIn": {
					defaultDuration: 600,
					calls: [[{
						opacity: [1, 0],
						transformOriginX: ["50%", "50%"],
						transformOriginY: ["50%", "50%"],
						scaleX: [1, 1.15],
						scaleY: [1, 1.15],
						translateZ: 0
					}]]
				},
				"transition.expandIn": {
					defaultDuration: 600,
					calls: [[{
						opacity: [1, 0],
						transformOriginX: ["50%", "50%"],
						transformOriginY: ["50%", "50%"],
						scaleX: [1, .9],
						scaleY: [1, .9],
						translateZ: 0
					}]]
				},
				"transition.grow": {
					defaultDuration: 600,
					calls: [[{
						opacity: [1, 0],
						transformOriginX: ["50%", "50%"],
						transformOriginY: ["50%", "50%"],
						scaleX: [1, .2],
						scaleY: [1, .2],
						translateZ: 0
					}]]
				},
				"transition.slideUpBigIn": {
					defaultDuration: 850,
					calls: [[{
						opacity: [1, 0],
						translateY: [0, 75],
						translateZ: 0
					}]]
				},
				"transition.slideDownBigIn": {
					defaultDuration: 850,
					calls: [[{
						opacity: [1, 0],
						translateY: [0, -75],
						translateZ: 0
					}]]
				},
				"transition.slideLeftBigIn": {
					defaultDuration: 800,
					calls: [[{
						opacity: [1, 0],
						translateX: [0, -75],
						translateZ: 0
					}]]
				},
				"transition.slideRightBigIn": {
					defaultDuration: 800,
					calls: [[{
						opacity: [1, 0],
						translateX: [0, 75],
						translateZ: 0
					}]]
				},
				"transition.perspectiveUpIn": {
					defaultDuration: 800,
					calls: [[{
						opacity: [1, 0],
						transformPerspective: [3e3, 3e3],
						transformOriginX: [0, 0],
						transformOriginY: ["100%", "100%"],
						rotateX: [0, -70]
					}]],
					reset: {
						transformPerspective: 0,
						transformOriginX: "50%",
						transformOriginY: "50%"
					}
				},
				"transition.perspectiveDownIn": {
					defaultDuration: 800,
					calls: [[{
						opacity: [1, 0],
						transformPerspective: [3e3, 3e3],
						transformOriginX: [0, 0],
						transformOriginY: [0, 0],
						rotateX: [0, 70]
					}]],
					reset: {
						transformPerspective: 0,
						transformOriginX: "50%",
						transformOriginY: "50%"
					}
				},
				"transition.perspectiveLeftIn": {
					defaultDuration: 800,
					calls: [[{
						opacity: [1, 0],
						transformPerspective: [2e3, 2e3],
						transformOriginX: [0, 0],
						transformOriginY: [0, 0],
						rotateY: [0, -70]
					}]],
					reset: {
						transformPerspective: 0,
						transformOriginX: "50%",
						transformOriginY: "50%"
					}
				},
				"transition.perspectiveRightIn": {
					defaultDuration: 800,
					calls: [[{
						opacity: [1, 0],
						transformPerspective: [2e3, 2e3],
						transformOriginX: ["100%", "100%"],
						transformOriginY: [0, 0],
						rotateY: [0, 70]
					}]],
					reset: {
						transformPerspective: 0,
						transformOriginX: "50%",
						transformOriginY: "50%"
					}
				}
			};
			for (var k in $.Velocity.RegisterEffect.packagedEffects)
				$.Velocity.RegisterEffect(k, $.Velocity.RegisterEffect.packagedEffects[k]);
			el.each(function() {
				if (jQuery(this).is_visible() && !jQuery(this).hasClass('anim_done')){
					jQuery(this).addClass('anim_done');
					var anim = jQuery(this).data('item-anim');
					jQuery(this).velocity(anim);
				}	
			});			
		}
	}

	function cws_vc_portfolio_footer_height(){
		var fh, fh2, footer;
		footer = jQuery('#footer');
		fh = footer.outerHeight();
		fh2 = fh * 1.5
		if (!jQuery('section.posts-grid').hasClass('posts_grid_showcase')) {
			if ( ( jQuery(window).width()>992) && footer.hasClass('footer-fixed')){
				footer.addClass('fixed');
				jQuery('body').css('margin-bottom',' ' + fh + 'px');
			} else{
				jQuery('body').css('margin-bottom','0px');
				footer.removeClass('fixed');
			}
			if ( ( jQuery(window).height()<fh2) ){
				jQuery('body').css('margin-bottom','0px');
				footer.removeClass('fixed');
			}
		}
	};

	function cws_vc_shortcode_render_styles(){
		var css = '';
		var head = document.head || document.getElementsByTagName('head')[0];
		var style = document.createElement('style');  
			jQuery('.render_styles').each(function(index, el) {
				var data = '';
				var data = JSON.parse(jQuery(el).data('style'));
				jQuery(el).removeAttr('data-style');
				css += data;
			});

		style.type = 'text/css';
		if (style.styleSheet){
			style.styleSheet.cssText = css;
		} else {
			style.appendChild(document.createTextNode(css));
		}
		head.appendChild(style);
	}

	function cws_vc_shortcode_portfolio_fw() {
		var doc_w, marg, div, div_w, div2, div3, fw_item, fw_item2, img_h, scroll_top_el;
		if (jQuery('.posts-grid').hasClass('posts_grid_showcase')) {
			jQuery("body").addClass('portfolio_fw');
		}
		div = jQuery('.posts-grid.posts_grid_showcase');
		div2 = jQuery('.single-cws-portfolio #page');
		div3 = jQuery('.single-post #page');
		fw_item = div2.find('.post-media');
		fw_item2 = div3.find('.post-media');
		doc_w = jQuery(document).width();
		div_w = jQuery('#page-content').width();
		marg = ( doc_w - div_w ) / 2;
		if (div2.hasClass('full-width')) {
			fw_item.css({'height' : '100%'})
			setTimeout(function(){
				fw_item.css({'opacity' : '1'})
			}, 700);
			if (fw_item.length) {
				scroll_top_el = fw_item.offset().top;
			}
			jQuery("body.single-cws-portfolio").animate({"scrollTop":scroll_top_el}, 1000);
		}
		if (div3.hasClass('full-width')) {
			fw_item2.css({'height' : '100%'})
			setTimeout(function(){
				fw_item2.css({'opacity' : '1'})
			}, 700);
			if (fw_item2.length) {
				scroll_top_el = fw_item2.offset().top;
			}
			jQuery("body.single-post").animate({"scrollTop":scroll_top_el}, 1000);
		}	
	}
	/* portfolio ajax */

	/* classes ajax */
	function cws_load_events(){
		var i, section, loader;
		var sections = document.getElementsByClassName( 'cws-wrapper-events' );
		for ( i = 0; i < sections.length; i++ ){
			section = sections[i];		
			
			loader = section.getElementsByClassName( 'cws-loader-holder' );
			loader = loader.length ? loader[0] : null;		

			if ( loader != null ){
				if ( !cws_has_class( loader, "active" ) ){
					cws_add_class( loader, "active" );
				}
			}
			
			if(window.cws_vc_sh_atts){
				jQuery.ajax({
					type : "post", 
					async: true,
					dataType : "text",
					url : ajaxurl.url,
					data : {
						action: "cws_vc_shortcode_tribe_events_posts_grid",		
						nonce: cws_vc_sh.ajax_nonce,
						data: JSON.parse(cws_vc_sh_atts.cws_events) 
					},
					error: function(resp) {
						console.log(resp);
					},
					success: function(resp) {
						if ( loader != null ){
							if ( cws_has_class( loader, "active" ) ){
								cws_remove_class( loader, "active" );
							}
						}
						if (resp.length) {
							var o_resp = JSON.parse(resp);
							jQuery(section).append(o_resp.result);	
							setTimeout(cws_vc_shortcode_isotope_init_plugin, 500);
						}
					},
				});			
			}
		}
	}

	window.addEventListener( 'resize', function (){
		cws_vc_shortcode_portfolio_fw();
	}, false )

}(jQuery));
