<?php
/**
 * The Header for our theme
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php
		global $cws_theme_funcs;
		global $metamax_theme_standard;
		if ($cws_theme_funcs){
			$cws_theme_funcs->cws_header_meta();
		} else {
			$metamax_theme_standard->cws_header_meta();
		}
		wp_head();
	?>
</head>

<body <?php body_class(); ?>>
<?php
if ($cws_theme_funcs){
    $cws_theme_funcs->cws_side_panel();
}
?>
<div class="cws-blank-preloader active"></div>
<!-- body cont -->
<div class='body-cont'>
	<?php
		if( $cws_theme_funcs ){
			echo sprintf("%s", $cws_theme_funcs->cws_page_loader()) ;
			echo sprintf("%s", $cws_theme_funcs->cws_page_header()) ;
		} else { ?>
			<div class="header-wrapper-container">
				<div class="header_cont">
					<header class="site-header logo-left menu-right desktop-menu-default logo-in-menu">
						<!--header-->
						<div class="header-container">
                            <div class="header-overlay"></div>
							<!--header-container-->
							<div class="header-zone">
								<!--header-zone-->
								<div class="menu-box">
									<div class="container">
										<div class="header-nav-part">
											<div class="menu-overlay"></div>
											<nav class="main-nav-container">
												<div class="logo-mobile-wrapper">
													<a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">
														<h3><?php bloginfo('name'); ?></h3>
													</a>
												</div>
												<div class="menu-left-icons">
                                                    <div class="mobile-menu-hamburger left custom-anim">
                                                        <span class="hamburger-icon"></span>
                                                    </div>
                                                </div>
												<div class="menu-box-wrapper">
													<div class="menu-logo-part">
														<a href="<?php echo esc_url( home_url('/') ); ?>" class="logo">
															<div class="logo-default-wrapper logo-wrapper">
																<h3><?php bloginfo('name'); ?></h3>
															</div>
														</a>
														<a href="<?php echo esc_url( home_url('/') ); ?>" class="logo logo-nav">
															<div class="logo-nav-wrapper logo-wrapper">
																<h3><?php bloginfo('name'); ?></h3>
															</div>
														</a>
													</div>
													<?php
														ob_start();
															$menu_locations = get_nav_menu_locations();

															if( isset($menu_locations['header-menu']) && !empty($menu_locations['header-menu']) ){
																wp_nav_menu(array(
																	'theme_location'	=> 'header-menu',
																	'menu_class'		=> 'main-menu',
																	'items_wrap'		=> '<div class="no-split-menu"><ul id="%1$s" class="%2$s">%3$s</ul></div>',
																	'container'			=> false,
																	'after'      		=> '<i class="button-open"></i>',
																));
															}
														$menu = ob_get_clean();

														if( !empty($menu) ){
															echo sprintf("%s", $menu);
														}
													?>
												</div>
												<div class="menu-right-icons">
													<div class="search-icon"></div>
												</div>
											</nav>
										</div>
									</div>
								</div>

								<?php
									$metamax_theme_standard->cws_site_header();
								?>
							</div>	
						</div>
					</header>
					<div class="site-search-wrapper">
						<span class="close-search"></span>
						<?php get_search_form(); ?>
					</div>
				</div>
			</div>
	<?php 
		}
		if ($cws_theme_funcs) {
			echo (isset($cws_theme_funcs->cws_get_meta_option('boxed')['header_layout']) && $cws_theme_funcs->cws_get_meta_option('boxed')['header_layout'] == '1') ? '</div>' : '';	
		}
	?>

	<div id="cws-main" class="site-main">
		<div class="aside-widgets-overlay"></div>