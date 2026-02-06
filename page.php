<?php
/**
 * page.php
 * Standard Page Template (Full Width).
 */

defined('ABSPATH') || exit;

get_header();

$show_title = defined('WUS_PAGE_SHOW_TITLE') ? (bool) WUS_PAGE_SHOW_TITLE : true;

// Per Seite überschreiben (wenn Meta vorhanden)
$hide_title = get_post_meta(get_the_ID(), 'hide_page_title', true);

if ($hide_title !== '') {
	$show_title = !$hide_title;
}


?>

<main id="content" class="content" role="main">
	<div class="uk-container">

		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php if ($show_title): ?>
				<header class="page-header">
					<h1 class="page-title">
						<?php echo esc_html(get_the_title()); ?>
					</h1>
				</header>
			<?php endif; ?>


			<div class="page-content">
				<?php
						the_content();

						// Pagination für gesplittete Seiten (<!--nextpage-->)
						wp_link_pages([
							'before' => '<nav class="page-links">',
							'after'  => '</nav>',
						]);
						?>
			</div>

		</article>

		<?php endwhile; ?>
		<?php endif; ?>

	</div>
</main>
<?php get_footer(); ?>
