<?php
	get_header ();
	global $cws_theme_funcs;
?>
<div class="page-content">
	<main>
		<div class="grid-row clearfix">
			<div class="grid-col grid-col-12">
				<div class="not-found">
					<div class="desc-404">
						<div class="msg-404">
							<?php
								esc_html_e( 'Sorry,', 'metamax' );
								echo "<br />";
								esc_html_e( "This page doesn't exist.", "metamax" );
							?>
						</div>
						<div class="link">
							<?php
								esc_html_e( 'Please, proceed to our ', 'metamax' );
								echo "<br />";
								echo "<a class='cws-custom-button regular' href='" . home_url('/') . "'>";
									esc_html_e( 'Home page', 'metamax' );
								echo "</a>";
							?>
						</div>
					</div>
					<div class="banner-404">
						<img src="<?php echo METAMAX_URI . "/img/404.png"; ?>" alt="<?php esc_attr_e('404', 'metamax'); ?>" />
					</div>
				</div>
			</div>
		</div>
	</main>
</div>

<?php
get_footer ();
?>