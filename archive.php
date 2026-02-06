<?php
defined('ABSPATH') || exit;

/**
 * archive.php
 *
 * Zweck:
 * - Archive-Template für Kategorien, Tags, Autoren, Datum, Custom Taxonomies, etc.
 *
 * Eigenschaften:
 * - Nutzt dieselbe Loop-Logik wie index.php (Grid / Liste via Schalter)
 * - Sidebar wird dynamisch eingeblendet (Blog-Sidebar)
 * - UIkit Grid für Layout
 * - Pagination integriert (WP core)
 *
 * Abhängigkeiten:
 * - WUS_Content::get_sidebar_id()
 * - parts/loop-blog.php
 * - parts/loop-blog-grid.php (rendert nur Items, Wrapper ist hier)
 *
 */

get_header();

/**
 * Sidebar-Logik:
 * - Archive ist immer "Blog-Kontext" => get_sidebar_id() liefert i.d.R. wus-sidebar-blog
 * - Sidebar wird nur gezeigt, wenn aktiv
 */
$sidebar_id = class_exists('WUS_Content')
	? WUS_Content::get_sidebar_id()
	: 'wus-sidebar-blog';

$has_sidebar = is_active_sidebar($sidebar_id);

/**
 * Blog-Loop-Variante:
 * - global konfigurierbar über WUS_BLOG_LOOP_VARIANT
 * - 'grid' (default) => parts/loop-blog-grid.php
 * - 'list'           => parts/loop-blog.php
 */
$variant = defined('WUS_BLOG_LOOP_VARIANT')
	? (string) WUS_BLOG_LOOP_VARIANT
	: 'grid';

$is_grid   = ($variant !== 'list');
$loop_slug = $is_grid ? 'blog-grid' : 'blog';
?>

<main id="content" class="content" role="main">
	<div class="uk-container">

		<div class="uk-grid-large" uk-grid>

			<section class="<?php echo $has_sidebar ? 'uk-width-expand@m' : 'uk-width-1-1'; ?>">

				<?php if (have_posts()) : ?>

					<!-- Archive Header (Titel + optional Beschreibung) -->
					<header class="uk-margin-medium-bottom">
						<h1 class="page-title uk-margin-small-bottom">
							<?php echo esc_html(get_the_archive_title()); ?>
						</h1>

						<?php
						$desc = get_the_archive_description();
						if (!empty($desc)) {
							// WP liefert hier HTML, daher kses
							echo '<div class="archive-description uk-text-muted">' . wp_kses_post($desc) . '</div>';
						}
						?>
					</header>

					<?php if ($is_grid) : ?>
						<!-- Grid Wrapper: liegt hier (nicht im loop-blog-grid.php) -->
						<div class="uk-child-width-1-3@m uk-grid-match" uk-grid>
							<?php while (have_posts()) : the_post(); ?>
								<?php get_template_part('parts/loop', $loop_slug); ?>
							<?php endwhile; ?>
						</div>
					<?php else : ?>

						<?php while (have_posts()) : the_post(); ?>
							<?php get_template_part('parts/loop', $loop_slug); ?>
						<?php endwhile; ?>

					<?php endif; ?>

					<!-- Pagination -->
					<nav class="uk-margin-large-top" aria-label="<?php echo esc_attr__('Beiträge Navigation', 'webundso'); ?>">
						<?php
						// Optional: wenn du deine eigene Pagination-Funktion behalten willst
						if (function_exists('wus_page_navi')) {
							wus_page_navi();
						} else {
							the_posts_pagination([
								'mid_size'  => 2,
								'prev_text' => esc_html__('Zurück', 'webundso'),
								'next_text' => esc_html__('Weiter', 'webundso'),
							]);
						}
						?>
					</nav>

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
