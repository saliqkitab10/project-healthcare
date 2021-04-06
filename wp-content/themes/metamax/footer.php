<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Metamax
 * @since Metamax 1.0
 */
?>

	</div><!-- #main -->

	<?php
    global $cws_theme_funcs;
		if ($cws_theme_funcs){
			$printed_footer = $cws_theme_funcs->cws_page_footer();
			echo sprintf("%s", $printed_footer);
		} else {
		?>
        <footer class="page-footer">
            <div class="bg-layer"></div>
            <div class="footer-icon">
                <div class="footer-icon-arrow"></div>
            </div>
            <div class="copyrights-area">
                <div class="container">
                    <div class="copyrights-container">
                        <div class="copyrights">
                            <?php esc_html_e('Copyright ', 'metamax'); ?> <?php echo date("Y");?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
		<?php
		}
	?>
	</div>
<!-- end body cont -->
<?php
wp_footer();
?>
</body>
</html>