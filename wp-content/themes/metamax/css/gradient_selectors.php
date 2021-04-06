<?php
	function get_gradient_selectors (){
		$selectors = "
			.main-nav-container .menu-item:hover,
			.main-nav-container .menu-item.current-menu-ancestor,
			.main-nav-container .menu-item.current-menu-item,
			.main-nav-container .sub-menu .menu-item:hover,
			.main-nav-container .sub-menu .menu-item.current-menu-ancestor,
			.main-nav-container .sub-menu .menu-item.current-menu-item,
			.site-header.with_background .main-nav-container .menu-item:hover,
			.site-header.with_background .main-nav-container .menu-item.current-menu-ancestor,
			.site-header.with_background .main-nav-container .menu-item.current-menu-item,
			.site-header.with_background .main-nav-container .sub-menu .menu-item:hover,
			.site-header.with_background .main-nav-container .sub-menu .menu-item.current-menu-ancestor,
			.site-header.with_background .main-nav-container .sub-menu .menu-item.current-menu-item,
			.news .post-info-box .date,
			.pic .hover-effect,
			.news .more-link:hover,
			.pagination .page-links .page-numbers.current,
			.pagination .page-links > span:not([class]),
			input[type='submit'],
			.cws-widget #wp-calendar tbody td#today:before,
			.cws-widget .tagcloud a:hover,
			.ce_accordion:not(.third_style) .accordion_content,
			.ce_accordion.second_style .accordion_section.active,
			.ce_toggle .accordion_title .accordion_icon,
			.ce_tabs .tab.active,
			.pricing_table_column .title_section,
			.comments-area .comment-list .comment-reply-link,
			.cws_milestone.alt,
			.cws_progress_bar .progress,
			.dropcap,
			.tp-caption.metamax-main-slider-layer a:before,
			#site_top_panel .cws-social-links .cws-social-link:hover,
			#site_top_panel #top_social_links_wrapper .cws-social-links.expanded .cws-social-link:hover,
			.copyrights-area .cws-social-links .cws-social-link:hover,
			.ourteam_item_wrapper .social_links a:hover,
			.cws_ourteam.single .social_links a:hover,
			.banner-404:before,
			.cws_img_frame:before,
			.gallery-icon a:before,
			.cws-tweet:before,
			.tweets_carousel_header .follow_us
		";
		$selectors = trim( $selectors );
		return $selectors;
	}
?>