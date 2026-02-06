<?php
defined('ABSPATH') || exit;

/**
 * comments.php
 *
 * Zweck:
 * - Zeigt vorhandene Kommentare und das Kommentar-Formular.
 * - Wird über comments_template() geladen (z.B. in single.php).
 *
 * Eigenschaften:
 * - Respektiert Passwortschutz (post_password_required)
 * - Titel mit Pluralisierung (Singular/Plural) via _nx()
 * - Optionales Paging (Older/Newer Comments)
 * - Listet Kommentare über Callback (wus_comments)
 * - Kommentarformular mit UIkit/Theme-Button-Klasse
 *
 */

if (post_password_required()) {
	return;
}

$comments_count = get_comments_number();
?>

<div id="comments" class="comments-area">

	<?php if (have_comments()) : ?>

		<h2 class="comments-title uk-h4 uk-margin-medium-bottom">
			<?php
			// Titel: "Ein Kommentar zu «Titel»" / "X Kommentare zu «Titel»"
			printf(
				esc_html(
					_nx(
						'Ein Kommentar zu „%2$s“',
						'%1$s Kommentare zu „%2$s“',
						$comments_count,
						'comments title',
						'webundso'
					)
				),
				number_format_i18n($comments_count),
				// Titel als reiner Text, nicht als HTML (sicherer)
				wp_strip_all_tags(get_the_title())
			);
			?>
		</h2>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<nav class="comment-navigation uk-margin-medium-bottom" aria-label="<?php echo esc_attr__('Kommentar Navigation', 'webundso'); ?>">
				<div class="nav-links uk-flex uk-flex-between">
					<div class="nav-previous">
						<?php previous_comments_link(esc_html__('Ältere Kommentare', 'webundso')); ?>
					</div>
					<div class="nav-next">
						<?php next_comments_link(esc_html__('Neuere Kommentare', 'webundso')); ?>
					</div>
				</div>
			</nav>
		<?php endif; ?>

		<ol class="commentlist uk-comment-list">
			<?php
			/**
			 * Kommentare ausgeben.
			 * Callback muss existieren, sonst WP default markup.
			 */
			wp_list_comments([
				'style'    => 'ol',
				'type'     => 'comment',
				'callback' => function_exists('wus_comments') ? 'wus_comments' : null,
			]);
			?>
		</ol>

		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<nav class="comment-navigation uk-margin-medium-top" aria-label="<?php echo esc_attr__('Kommentar Navigation', 'webundso'); ?>">
				<div class="nav-links uk-flex uk-flex-between">
					<div class="nav-previous">
						<?php previous_comments_link(esc_html__('Ältere Kommentare', 'webundso')); ?>
					</div>
					<div class="nav-next">
						<?php next_comments_link(esc_html__('Neuere Kommentare', 'webundso')); ?>
					</div>
				</div>
			</nav>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	/**
	 * Hinweis, wenn Kommentare geschlossen sind, aber es existieren bereits Kommentare.
	 */
	if (!comments_open() && $comments_count !== 0 && post_type_supports(get_post_type(), 'comments')) :
	?>
		<p class="no-comments uk-text-muted">
			<?php echo esc_html__('Kommentarfunktion ist geschlossen.', 'webundso'); ?>
		</p>
	<?php endif; ?>

	<?php
	/**
	 * Kommentarformular:
	 * - class_submit: damit du es in CSS/UIkit ansprechen kannst
	 * - title_reply: i18n
	 *
	 * Hinweis UIkit:
	 * - Wenn du UIkit Buttons willst: setz class_submit => 'uk-button uk-button-default'
	 *   und style den Rest via comment_form_defaults Filter.
	 */
	comment_form([
		'class_submit' => 'uk-button uk-button-default',
		'title_reply'  => esc_html__('Kommentar schreiben', 'webundso'),
	]);
	?>

</div>
