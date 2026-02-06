<?php
defined('ABSPATH') || exit;

/**
 * single.php
 *
 * Zweck:
 * - Detailansicht eines einzelnen Blog-Posts (single post).
 *
 * Eigenschaften:
 * - UIkit Layout mit optionaler Sidebar (Blog-Sidebar)
 * - Saubere Meta-Ausgabe (Autor/Datum/Kategorien)
 * - Optionales Featured Image (ohne inline-style XSS-Falle)
 * - Pagination für multipage Posts (<!--nextpage-->)
 * - Tags-Ausgabe
 * - Comments nur, wenn erlaubt/aktiv (Theme-Policy respektiert WUS_DISABLE_COMMENTS)
 *
 * Abhängigkeiten:
 * - sidebar.php (dynamisch)
 * - WUS_Content::get_sidebar_id()
 */

get_header();

$sidebar_id  = class_exists('WUS_Content') ? WUS_Content::get_sidebar_id() : 'wus-sidebar-blog';
$has_sidebar = is_active_sidebar($sidebar_id);
?>

<main id="content" class="content" role="main">
	<div class="uk-container">

		<div class="uk-grid-large" uk-grid>

			<section class="<?php echo $has_sidebar ? 'uk-width-expand@m' : 'uk-width-1-1'; ?>">

				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>

						<?php
						$post_id   = get_the_ID();
						$title     = get_the_title($post_id);
						$thumb_url = get_the_post_thumbnail_url($post_id, 'full');
						?>

						<article id="post-<?php echo esc_attr((string) $post_id); ?>" <?php post_class('uk-article'); ?>>

							<!-- Titel -->
							<h1 class="uk-article-title">
								<?php echo esc_html($title); ?>
							</h1>

							<!-- Meta -->
							<p class="uk-article-meta">
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

							<!-- Featured Image -->
							<?php if (!empty($thumb_url)) : ?>
								<figure class="uk-margin-medium">
									<img
										src="<?php echo esc_url($thumb_url); ?>"
										alt="<?php echo esc_attr($title); ?>"
										loading="lazy"
									>
								</figure>
							<?php endif; ?>

							<!-- Content -->
							<div class="entry-content">
								<?php the_content(); ?>
							</div>

							<!-- Footer: Multipage + Tags -->
							<footer class="article-footer uk-margin-large-top">

								<?php
								wp_link_pages([
									'before' => '<nav class="page-links" aria-label="' . esc_attr__('Seiten', 'webundso') . '"><span class="uk-text-bold">' . esc_html__('Seiten:', 'webundso') . '</span> ',
									'after'  => '</nav>',
								]);

								$tags = get_the_tag_list(
									'<span class="tags-title">' . esc_html__('Tags:', 'webundso') . '</span> ',
									', ',
									''
								);

								if ($tags) {
									echo '<p class="tags uk-text-small">' . wp_kses_post($tags) . '</p>';
								}
								?>
								<?php
								// Related Posts
								if (class_exists('WUS_Content')) {
								//	WUS_Content::related_posts(3);
								}
								?>
							</footer>

							<!-- Comments -->
							<?php
							$comments_allowed = !(defined('WUS_DISABLE_COMMENTS') && WUS_DISABLE_COMMENTS);

							if ($comments_allowed && (comments_open() || get_comments_number())) {
								comments_template();
							}
							?>

						</article>

					<?php endwhile; ?>
				<?php else : ?>

					<?php get_template_part('parts/content', 'missing'); ?>

				<?php endif; ?>

			</section>

			<?php if ($has_sidebar) : ?>
				<aside class="uk-width-1-4@m" aria-label="<?php echo esc_attr__('Sidebar', 'webundso'); ?>">
					<?php get_sidebar(); ?>
				</aside>
			<?php endif; ?>

		</div>

	</div>
</main>

<?php get_footer(); ?>
