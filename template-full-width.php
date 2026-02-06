<?php
/**
 * Template Name: Full width (2 Spalten)
 * Filename: template-full-width.php
 * Description: Content links, Sidebar rechts (Widgets).
 */

defined('ABSPATH') || exit;

get_header();

$sidebar_id  = function_exists('wus_get_sidebar_id') ? wus_get_sidebar_id() : 'wus-sidebar-pages';
$has_sidebar = is_active_sidebar($sidebar_id);
?>

<main id="content" class="content" role="main">
	<div class="uk-container">

		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<header class="page-header">
						<h1 class="page-title"><?php echo esc_html(get_the_title()); ?></h1>
					</header>

					<div class="uk-grid-large" uk-grid>

						<div class="<?php echo $has_sidebar ? 'uk-width-expand@m' : 'uk-width-1-1'; ?>">
							<div class="page-content">
								<?php
								the_content();

								wp_link_pages([
									'before' => '<nav class="page-links">',
									'after'  => '</nav>',
								]);
								?>
							</div>
						</div>

						<?php if ($has_sidebar): ?>
							<aside class="uk-width-1-3@m" aria-label="<?php echo esc_attr__('Sidebar', 'webundso'); ?>">
								<?php dynamic_sidebar($sidebar_id); ?>
							</aside>
						<?php endif; ?>


					</div>

				</article>

			<?php endwhile; ?>
		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
