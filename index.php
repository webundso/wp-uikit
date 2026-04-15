<?php
defined('ABSPATH') || exit;

/**
 * index.php
 *
 * Zweck:
 * - Standard-Fallback-Template von WordPress
 * - In diesem Setup primär als Blog-Übersicht / Post-Liste genutzt
 *
 * Eigenschaften:
 * - Unterstützt zwei Loop-Varianten (Grid / Liste)
 * - Sidebar wird dynamisch eingeblendet (Blog-Sidebar)
 * - UIkit Grid für Layout
 * - Pagination integriert
 *
 * Abhängigkeiten:
 * - WUS_Content::get_sidebar_id()
 * - parts/loop-blog.php
 * - parts/loop-blog-grid.php (rendert nur Items, Wrapper ist hier)
 */

get_header();

/**
 * Sidebar-Logik:
 * - Sidebar-ID wird zentral über WUS_Content bestimmt
 * - Sidebar wird nur gerendert, wenn sie aktiv ist
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

					<?php
				$max_pages   = $GLOBALS['wp_query']->max_num_pages;
				$load_more   = defined('WUS_BLOG_LOAD_MORE') ? (bool) WUS_BLOG_LOAD_MORE : true;
				$show_button = $load_more && ($max_pages > 1);
				?>

				<?php if ($is_grid) : ?>

						<div id="wus-blog-posts" class="uk-child-width-1-3@m uk-grid-match" uk-grid>
							<?php while (have_posts()) : the_post(); ?>
								<?php get_template_part('parts/loop', $loop_slug); ?>
							<?php endwhile; ?>
						</div>
					<?php else : ?>

						<div id="wus-blog-posts">
						<?php
						/**
						 * Listen-Variante:
						 * - Loop-Part rendert komplette List-Item Struktur
						 */
						while (have_posts()) :
							the_post();
							get_template_part('parts/loop', $loop_slug);
						endwhile;
						?>
						</div>

					<?php endif; ?>

					<?php if ($show_button) : ?>

						<!-- Load More Button -->
						<div class="uk-margin-large-top uk-text-center">
							<button
								id="wus-load-more"
								class="uk-button uk-button-default"
								data-max-pages="<?php echo esc_attr((string) $max_pages); ?>"
								data-label="<?php echo esc_attr__('Ältere Beiträge laden', 'webundso'); ?>"
							>
								<?php echo esc_html__('Ältere Beiträge laden', 'webundso'); ?>
							</button>
						</div>

					<?php else : ?>

						<!-- Pagination (Load More deaktiviert) -->
						<nav class="uk-margin-large-top" aria-label="<?php echo esc_attr__('Beiträge Navigation', 'webundso'); ?>">
							<?php
							the_posts_pagination([
								'mid_size'  => 2,
								'prev_text' => esc_html__('Zurück', 'webundso'),
								'next_text' => esc_html__('Weiter', 'webundso'),
							]);
							?>
						</nav>

					<?php endif; ?>

				<?php else : ?>

					<?php
					/**
					 * Fallback:
					 * - wird angezeigt, wenn keine Posts existieren
					 */
					get_template_part('parts/content', 'missing');
					?>

				<?php endif; ?>

			</section>

			<?php if ($has_sidebar) : ?>
				<!-- Sidebar: nur wenn aktiv -->
				<aside class="uk-width-1-4@m" aria-label="<?php echo esc_attr__('Sidebar', 'webundso'); ?>">
					<?php get_sidebar(); ?>
				</aside>
			<?php endif; ?>

		</div>

	</div>
</main>

<?php get_footer(); ?>
