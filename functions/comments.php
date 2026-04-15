<?php
/**
 * Comment Callbacks
 * In functions.php einfügen
 */

/**
 * Einzelner Kommentar – UIkit uk-comment Markup
 */
function webundso_comment_template( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		?>
		<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'uk-comment', $comment ); ?>>

				<article class="uk-comment <?php echo $comment->comment_approved === '0' ? 'uk-comment-primary' : ''; ?>">

						<header class="uk-comment-header uk-grid-medium uk-flex-middle" uk-grid>
								<div class="uk-width-auto">
										<?php echo get_avatar( $comment, $args['avatar_size'], '', '', [ 'class' => 'uk-border-circle' ] ); ?>
								</div>
								<div class="uk-width-expand">
										<h4 class="uk-comment-title uk-margin-remove">
												<?php comment_author_link( $comment ); ?>
										</h4>
										<p class="uk-comment-meta uk-margin-remove">
												<a href="<?php echo esc_url( get_comment_link( $comment ) ); ?>" class="uk-link-reset">
														<time datetime="<?php comment_date( 'c', $comment ); ?>">
																<?php comment_date( get_option( 'date_format' ), $comment ); ?>
														</time>
												</a>
												<?php edit_comment_link( esc_html__( 'Bearbeiten', 'webundso' ), ' &middot; ', '' ); ?>
										</p>
								</div>
						</header>

						<div class="uk-comment-body">

								<?php if ( $comment->comment_approved === '0' ) : ?>
										<p class="uk-text-small uk-text-muted">
												<?php esc_html_e( 'Dein Kommentar wartet auf Freischaltung.', 'webundso' ); ?>
										</p>
								<?php endif; ?>

								<?php comment_text( $comment ); ?>

								<?php
								comment_reply_link( array_merge( $args, [
										'add_below' => 'comment',
										'depth'     => $depth,
										'max_depth' => $args['max_depth'],
										'before'    => '<p class="uk-margin-small-top">',
										'after'     => '</p>',
										'reply_text'=> esc_html__( 'Antworten', 'webundso' ),
										'login_text'=> esc_html__( 'Einloggen zum Antworten', 'webundso' ),
								] ) );
								?>

						</div>

				</article>

		<?php // Kein schliessender Tag hier – wp_list_comments schliesst selbst
}

/**
 * Schliesst das li/div Element nach jedem Kommentar
 */
function webundso_comment_end( $comment, $args, $depth ) {
		$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
		echo '</' . $tag . '>';
}
