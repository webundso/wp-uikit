<?php
defined('ABSPATH') || exit;

/**
 * parts/loop-blog.php
 *
 * Zweck:
 * - Ausgabe eines einzelnen Posts in Listen-Ansicht (Blog-Index/Archive).
 *
 * UIkit:
 * - uk-section: optische Trennung je Post
 * - uk-grid: 2-Spalten Layout (Bild links, Inhalt rechts)
 * - uk-background-cover + uk-img: Thumbnail als Cover-Background
 *
 *
 * Hinweis zur Content-Ausgabe:
 * - Für Listen-Views ist the_excerpt() in der Regel sinnvoller als the_content()
 *   (sonst renderst du volle Seiteninhalte inkl. Blocks, Buttons, etc.)
 */

$post_id    = get_the_ID();
$permalink  = get_permalink($post_id);
$title      = get_the_title($post_id);

$thumb_url  = get_the_post_thumbnail_url($post_id, 'large');
$has_thumb  = !empty($thumb_url);
?>

<article id="post-<?php echo esc_attr((string) $post_id); ?>" <?php post_class('uk-section'); ?>>
	<div class="uk-container">

		<!-- Titel + Link -->
		<h2 class="uk-margin-small-bottom">
			<a href="<?php echo esc_url($permalink); ?>" title="<?php the_title_attribute(); ?>">
				<?php echo esc_html($title); ?>
			</a>
		</h2>

		<div class="uk-grid" uk-grid>

			<!-- Thumbnail-Spalte -->
			<div class="uk-width-1-4@m">
				<?php if ($has_thumb): ?>
					<a class="uk-display-block" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>">
						<div
							class="uk-background-cover uk-height-medium"
							data-src="<?php echo esc_url($thumb_url); ?>"
							uk-img
						></div>
					</a>
				<?php else: ?>
					<!-- Optional: Platzhalter wenn kein Bild -->
					<div class="uk-height-medium uk-background-muted"></div>
				<?php endif; ?>
			</div>

			<!-- Content-Spalte -->
			<div class="uk-width-expand@m">

				<!-- Meta / Byline -->
				<p class="byline uk-text-small uk-margin-small-bottom">
					<?php echo esc_html__('Erstellt von', 'webundso'); ?>
					<?php the_author_posts_link(); ?>
					<?php echo esc_html__('am', 'webundso'); ?>
					<time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
						<?php echo esc_html(get_the_date('j. F Y', $post_id)); ?>
					</time>
					<span class="uk-margin-small-left">
						<?php the_category(', '); ?>
					</span>
				</p>

				<!-- Inhalt: Excerpt statt Full Content -->
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div>

				<!-- Read more -->
				<p class="uk-margin-small-top">
					<a class="uk-button uk-button-default" href="<?php echo esc_url($permalink); ?>">
						<?php echo esc_html__('Weiter lesen', 'webundso'); ?>
					</a>
				</p>

				<!-- Tags -->
				<footer class="article-footer uk-margin-small-top">
					<?php
					$tags = get_the_tag_list(
						'<span class="tags-title">' . esc_html__('Tags:', 'webundso') . '</span> ',
						', ',
						''
					);

					if ($tags) {
						echo '<p class="tags uk-text-small">' . wp_kses_post($tags) . '</p>';
					}
					?>
				</footer>

			</div>

		</div>

	</div>
</article>
