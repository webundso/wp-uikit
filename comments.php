<?php
/**
 * Displays current comments and comment form. Works with includes/comments.php.
 *
 * For more info: https://developer.wordpress.org/themes/template-files-section/partial-and-miscellaneous-template-files/comments/
 */ 

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( // WPCS: XSS OK.
					esc_html( _nx( 'Ein Kommentar zu &ldquo;%2$s&rdquo;', '%1$s Komnmentare zu &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'webundso_wp' ) ),
					number_format_i18n( get_comments_number() ),
					'<span>' . get_the_title() . '</span>'
				);
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'webundso_wp' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'webundso_wp' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'webundso_wp' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-above -->
		<?php endif; // Check for comment navigation. ?>

		<ol class="commentlist">
			<?php wp_list_comments('type=comment&callback=wus_comments'); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
		<nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'webundso_wp' ); ?></h2>
			<div class="nav-links">

				<div class="nav-previous"><?php previous_comments_link( esc_html__( 'Older Comments', 'webundso_wp' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( esc_html__( 'Newer Comments', 'webundso_wp' ) ); ?></div>

			</div><!-- .nav-links -->
		</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>

	<?php endif; // Check for have_comments(). ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Kommentarfunktion ist geschlossen.', 'webundso_wp' ); ?></p>
	<?php endif; ?>

	<?php comment_form(array('class_submit'=>'button')); ?>

</div><!-- #comments -->