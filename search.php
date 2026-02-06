<?php
defined('ABSPATH') || exit;

/**
 * search.php
 *
 * Zweck:
 * - Suchresultate anzeigen (WP Search).
 *
 * Eigenschaften:
 * - Überschrift mit Suchbegriff (escaped)
 * - Nutzt Template Part für einzelne Treffer (parts/loop-archive.php)
 * - Pagination integriert (optional: wus_page_navi() fallback)
 * - UIkit Layout
 *
 * Security:
 * - Suchbegriff kommt aus User Input -> immer esc_html/esc_attr
 * - Textdomain konsistent: webundso
 */

get_header();

$query = get_search_query();
?>

<main id="content" class="content" role="main">
	<div class="uk-container">

		<header class="uk-margin-medium-bottom">
			<h1 class="archive-title uk-margin-small-bottom">
				<?php echo esc_html__('Suchergebnisse für:', 'webundso'); ?>
				<span class="uk-text-bold"><?php echo esc_html($query); ?></span>
			</h1>
		</header>

		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>
				<?php get_template_part('parts/loop', 'archive'); ?>
			<?php endwhile; ?>

			<nav class="uk-margin-large-top" aria-label="<?php echo esc_attr__('Beiträge Navigation', 'webundso'); ?>">
				<?php
				// Optional: eigene Pagination, sonst WP core
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

			<section class="uk-margin-large-top">
				<h2 class="uk-h4"><?php echo esc_html__('Neue Suche', 'webundso'); ?></h2>
				<?php get_search_form(); ?>
			</section>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
