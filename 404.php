<?php
defined('ABSPATH') || exit;

/**
 * 404.php
 *
 * Zweck:
 * - Wird angezeigt, wenn WordPress keine passende Seite/Route findet.
 *
 */

get_header();
?>

<main id="content" class="content" role="main">
	<div class="uk-container">

		<header class="uk-margin-medium-bottom">
			<h1 class="uk-heading-small">
				<?php echo esc_html__('404 – Seite nicht gefunden', 'webundso'); ?>
			</h1>
		</header>

		<section class="uk-alert uk-alert-warning" role="alert" aria-live="polite">
			<div class="uk-padding-small">
				<?php echo esc_html__('Die gewünschte Seite konnte nicht gefunden werden.', 'webundso'); ?>
			</div>
		</section>

		<section class="search uk-margin-large-top">
			<h2 class="uk-h4 uk-margin-small-bottom">
				<?php echo esc_html__('Suchen:', 'webundso'); ?>
			</h2>

			<div class="uk-width-1-2@m">
				<?php get_search_form(); ?>
			</div>
		</section>

		<section class="uk-margin-large-top">
			<p class="uk-text-muted">
				<?php echo esc_html__('Tipp: Prüfe die URL oder nutze die Navigation.', 'webundso'); ?>
			</p>
		</section>

	</div>
</main>

<?php get_footer(); ?>
