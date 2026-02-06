<?php
defined('ABSPATH') || exit;

/**
 * parts/content-missing.php
 *
 * Zweck:
 * - Fallback-Inhalt, wenn keine Posts/Resultate gefunden wurden.
 * - Wird in index.php, archive.php, search.php etc. via get_template_part() eingebunden.
 */

$is_search = is_search();
?>

<div class="post-not-found">

	<?php if ($is_search) : ?>

		<div class="uk-alert uk-alert-warning uk-padding-small" role="alert" aria-live="polite">
			<h2 class="uk-h4 uk-margin-small-bottom">
				<?php echo esc_html__('Keine Ergebnisse gefunden', 'webundso'); ?>
			</h2>

			<p class="uk-margin-remove-top">
				<?php echo esc_html__('Möglicherweise ist der Begriff falsch geschrieben oder nicht vorhanden. Bitte prüfe deine Eingabe und versuche es erneut.', 'webundso'); ?>
			</p>
		</div>

		<section class="uk-section uk-padding-remove-top">
			<h2 class="uk-h4"><?php echo esc_html__('Suche', 'webundso'); ?></h2>
			<?php get_search_form(); ?>
		</section>

	<?php else : ?>

		<div class="uk-alert uk-alert-warning uk-padding-small" role="alert" aria-live="polite">
			<h2 class="uk-h4 uk-margin-small-bottom">
				<?php echo esc_html__('Inhalt nicht gefunden', 'webundso'); ?>
			</h2>

			<p class="uk-margin-remove-top">
				<?php echo esc_html__('Leider wurde kein Beitrag gefunden.', 'webundso'); ?>
			</p>
		</div>

		<section class="uk-section uk-padding-remove-top">
			<h2 class="uk-h4"><?php echo esc_html__('Suche', 'webundso'); ?></h2>
			<?php get_search_form(); ?>
		</section>

	<?php endif; ?>

</div>
