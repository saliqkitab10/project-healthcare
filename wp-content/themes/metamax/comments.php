<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}


function cws_comment_form( $args = array(), $post_id = null ) {
	if ( null === $post_id )
		$post_id = get_the_ID();

	// Exit the function when comments for the post are closed.
	if ( ! comments_open( $post_id ) ) {
		/**
		 * Fires after the comment form if comments are closed.
		 *
		 * @since 3.0.0
		 */
		do_action( 'comment_form_comments_closed' );

		return;
	}

	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';

	$args = wp_parse_args( $args );
	if ( ! isset( $args['format'] ) )
		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';

	$req      = get_option( 'require_name_email' );
	$html_req = ( $req ? " required" : '' );
	$html5    = 'html5' === $args['format'];
	$fields   =  array(
		'author'  => '<p class="comment-form-author">' . '<input id="author" name="author" type="text" value="' .
            esc_attr( $commenter['comment_author'] ) . '" size="30" placeholder="'.esc_attr__( 'Your Name*', 'metamax' ).'" maxlength="245" ' . $html_req . ' /></p>',
		'email'   => '<p class="comment-form-email"><input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30" maxlength="100" placeholder="'.esc_attr__( 'Your Email*', 'metamax' ).'" aria-describedby="email-notes"' . $html_req . ' /></p>',
	);

	/**
	 * Filters the default comment form fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $fields The default comment fields.
	 */
	$fields = apply_filters( 'comment_form_default_fields', $fields );
	$defaults = array(
		'fields'               => $fields,
		'comment_field'        => '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="4" maxlength="65525" placeholder="'.esc_attr__( 'Comment', 'metamax' ).'"></textarea></p>',
		/** This filter is documented in wp-includes/link-template.php */
		'must_log_in'          => '<p class="must-log-in">' . sprintf(
		                              /* translators: %s: login URL */
		                              esc_html__('You must be', 'metamax').' <a href="%s">'.esc_html__("logged in", "metamax").'</a> '.esc_html__("to post a comment.", "metamax").'',
		                              wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ), $post_id ) )
		                          ) . '</p>',
		/** This filter is documented in wp-includes/link-template.php */
		'logged_in_as'         => '<p class="logged-in-as">' . sprintf(
		                              /* translators: 1: edit user link, 2: accessibility text, 3: user name, 4: logout URL */
		                              esc_html__("Logged in as", "metamax").'<a href="%1$s" aria-label="%2$s"> %3$s</a>. <a href="%4$s">'.esc_html__("Log out?", "metamax").'</a>',
		                              get_edit_user_link(),
		                              /* translators: %s: user name */
		                              sprintf( esc_attr__( 'Logged in as %s. Edit your profile.', 'metamax' ), $user_identity ),
		                              $user_identity,
		                              wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ), $post_id ) )
		                          ) . '</p>',
		'comment_notes_before' => '<p>' . esc_html__('Your email address will not be published. Required fields are marked *', 'metamax') . '</p>',
		'comment_notes_after'  => '',
		'action'               => site_url( '/wp-comments-post.php' ),
		'id_form'              => 'commentform',
		'id_submit'            => 'submit',
		'class_form'           => 'comment-form',
		'class_submit'         => 'submit',
		'name_submit'          => 'submit',
		'title_reply'          => esc_html__( 'Leave A Comment', 'metamax' ),
		'title_reply_to'       => esc_html__( 'Leave a Reply to %s', 'metamax' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title h4">',
		'title_reply_after'    => '</h3>',
		'cancel_reply_before'  => ' <small>',
		'cancel_reply_after'   => '</small>',
		'cancel_reply_link'    => esc_html__( '(Cancel reply)', 'metamax' ),
		'label_submit'         => esc_html__( 'Post Comment', 'metamax' ),
		'submit_button'        => '<input name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />',
		'submit_field'         => '<p class="form-submit">%1$s %2$s</p>',
		'format'               => 'xhtml',
	);

	/**
	 * Filters the comment form default arguments.
	 *
	 * Use {@see 'comment_form_default_fields'} to filter the comment fields.
	 *
	 * @since 3.0.0
	 *
	 * @param array $defaults The default comment form arguments.
	 */
	$args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );

	// Ensure that the filtered args contain all required default values.
	$args = array_merge( $defaults, $args );

	/**
	 * Fires before the comment form.
	 *
	 * @since 3.0.0
	 */
	do_action( 'comment_form_before' );
	?>
	<div id="respond" class="comment-respond">
		<?php
		echo sprintf('%s', $args['title_reply_before']);

		comment_form_title( $args['title_reply'], $args['title_reply_to'] );

		echo sprintf('%s', $args['cancel_reply_before']);

		cancel_comment_reply_link( $args['cancel_reply_link'] );

		echo sprintf('%s', $args['cancel_reply_after']);

		echo sprintf('%s', $args['title_reply_after']);

		if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) :
			echo sprintf('%s', $args['must_log_in']);
			/**
			 * Fires after the HTML-formatted 'must log in after' message in the comment form.
			 *
			 * @since 3.0.0
			 */
			do_action( 'comment_form_must_log_in_after' );
		else : ?>
			<form action="<?php echo esc_url( $args['action'] ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>" class="<?php echo esc_attr( $args['class_form'] ); ?>">
				<?php
				/**
				 * Fires at the top of the comment form, inside the form tag.
				 *
				 * @since 3.0.0
				 */
				do_action( 'comment_form_top' );

				if ( is_user_logged_in() ) :
					/**
					 * Filters the 'logged in' message for the comment form for display.
					 *
					 * @since 3.0.0
					 *
					 * @param string $args_logged_in The logged-in-as HTML-formatted message.
					 * @param array  $commenter      An array containing the comment author's
					 *                               username, email, and URL.
					 * @param string $user_identity  If the commenter is a registered user,
					 *                               the display name, blank otherwise.
					 */
					echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity );

					/**
					 * Fires after the is_user_logged_in() check in the comment form.
					 *
					 * @since 3.0.0
					 *
					 * @param array  $commenter     An array containing the comment author's
					 *                              username, email, and URL.
					 * @param string $user_identity If the commenter is a registered user,
					 *                              the display name, blank otherwise.
					 */
					do_action( 'comment_form_logged_in_after', $commenter, $user_identity );

				else :

					echo sprintf('%s', $args['comment_notes_before']);

				endif;

				// Prepare an array of all fields, including the textarea
				$comment_fields = (array) $args['fields'] + array( 'comment' => $args['comment_field'] );

				/**
				 * Filters the comment form fields, including the textarea.
				 *
				 * @since 4.4.0
				 *
				 * @param array $comment_fields The comment fields.
				 */
				$comment_fields = apply_filters( 'comment_form_fields', $comment_fields );

				// Get an array of field names, excluding the textarea
				$comment_field_keys = array_diff( array_keys( $comment_fields ), array( 'comment' ) );

				// Get the first and the last field name, excluding the textarea
				$first_field = reset( $comment_field_keys );
				$last_field  = end( $comment_field_keys );

				foreach ( $comment_fields as $name => $field ) {

					if ( 'comment' === $name ) {

						/**
						 * Filters the content of the comment textarea field for display.
						 *
						 * @since 3.0.0
						 *
						 * @param string $args_comment_field The content of the comment textarea field.
						 */
						echo apply_filters( 'comment_form_field_comment', $field );

						echo sprintf('%s', $args['comment_notes_after']);

					} elseif ( ! is_user_logged_in() ) {

						if ( $first_field === $name ) {
							/**
							 * Fires before the comment fields in the comment form, excluding the textarea.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_before_fields' );
						}

						/**
						 * Filters a comment form field for display.
						 *
						 * The dynamic portion of the filter hook, `$name`, refers to the name
						 * of the comment form field. Such as 'author', 'email', or 'url'.
						 *
						 * @since 3.0.0
						 *
						 * @param string $field The HTML-formatted output of the comment form field.
						 */
						echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";

						if ( $last_field === $name ) {
							/**
							 * Fires after the comment fields in the comment form, excluding the textarea.
							 *
							 * @since 3.0.0
							 */
							do_action( 'comment_form_after_fields' );
						}
					}
				}

				$submit_button = sprintf(
					$args['submit_button'],
					esc_attr( $args['name_submit'] ),
					esc_attr( $args['id_submit'] ),
					esc_attr( $args['class_submit'] ),
					esc_attr( $args['label_submit'] )
				);

				/**
				 * Filters the submit button for the comment form to display.
				 *
				 * @since 4.2.0
				 *
				 * @param string $submit_button HTML markup for the submit button.
				 * @param array  $args          Arguments passed to `comment_form()`.
				 */
				$submit_button = apply_filters( 'comment_form_submit_button', $submit_button, $args );

				$submit_field = sprintf(
					$args['submit_field'],
					$submit_button,
					get_comment_id_fields( $post_id )
				);

				/**
				 * Filters the submit field for the comment form to display.
				 *
				 * The submit field includes the submit button, hidden fields for the
				 * comment form, and any wrapper markup.
				 *
				 * @since 4.2.0
				 *
				 * @param string $submit_field HTML markup for the submit field.
				 * @param array  $args         Arguments passed to comment_form().
				 */
				echo apply_filters( 'comment_form_submit_field', $submit_field, $args );

				/**
				 * Fires at the bottom of the comment form, inside the closing </form> tag.
				 *
				 * @since 1.5.0
				 *
				 * @param int $post_id The post ID.
				 */
				do_action( 'comment_form', $post_id );
				?>
			</form>
		<?php endif; ?>
	</div><!-- #respond -->
	<?php

	/**
	 * Fires after the comment form.
	 *
	 * @since 3.0.0
	 */
	do_action( 'comment_form_after' );
}

global $cws_theme_funcs;
ob_start();
	
	if ( have_comments() ) {
			$comments_number = number_format_i18n( get_comments_number() );
			$comment_text = ($comments_number == '1') ? esc_html__( 'Comment', 'metamax') : esc_html__('Comments', 'metamax');
			echo "<h3 class='comments-title'> " . $comment_text . " <span class='comments-count'>$comments_number</span>" . "</h3>";

			wp_list_comments( array(
				'walker' => new METAMAX_Walker_Comment(),
				'avatar_size' => 70,
			) );

			if ($cws_theme_funcs){
				$cws_theme_funcs->cws_comment_nav();
			} else {

				if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
				?>
				<div class="comments-nav carousel_nav_panel clearfix">
					<?php
                        echo '<div class="prev-section">';
						if ( $prev_link = get_previous_comments_link( "<span class='prev'></span><span>" . esc_html__( 'Older Comments', 'metamax' ) . "</span>" ) ) {
							printf( '%s', $prev_link );
						}
                        echo '</div>';
                        echo '<div class="next-section">';
						if ( $next_link = get_next_comments_link( "<span>" . esc_html__( 'Newer Comments', 'metamax' ) . "</span><span class='next'></span>" ) ) {
							printf( '%s', $next_link );
						}
                        echo '</div>';
					?>
				</div><!-- .comment-navigation -->
				<?php
				}

			}

	} // have_comments()

	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) {
		echo apply_filters( 'the_content', "<div class='cws-msg-box-module cws_vc_shortcode_module type-info'><div class='icon_part'><i class='msg_icon'></i></div><div class='content_part'>" . esc_html__( 'Comments are closed.', 'metamax' ) . "</div></div>" );
	}

	ob_start();
	cws_comment_form();
	$comment_form = ob_get_clean();
	echo trim( $comment_form );


$comments_section_content = ob_get_clean();
echo !empty( $comments_section_content ) ? "<div class='grid-row single-comments'><div class='grid-col grid-col-12'><div class='cols-wrapper'><div id='comments' 
class='comments-area'>$comments_section_content</div></div></div></div>" : "";




?>