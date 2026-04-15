<?php
defined('ABSPATH') || exit;

/**
 * footer.php
 *
 * Jede der 3 Footer-Spalten ist unabhängig steuerbar:
 *
 *   WUS_FOOTER_COL1 / WUS_FOOTER_COL2 / WUS_FOOTER_COL3
 *   - 'auto'    (default) → Widgets wenn Sidebar befüllt, sonst HTML-Fallback
 *   - 'widgets'           → immer Widgets (auch wenn leer)
 *   - 'html'              → immer HTML-Fallback (Widgets ignoriert)
 *
 * HTML-Fallback pro Spalte direkt unten im Template anpassen.
 */

/**
 * Hilfsfunktion: soll eine Spalte Widgets rendern?
 */
function wus_footer_use_widgets(string $mode, string $sidebar_id): bool
{
	if ($mode === 'widgets') {
		return true;
	}
	if ($mode === 'html') {
		return false;
	}
	// auto
	return is_active_sidebar($sidebar_id);
}

$col1_mode = defined('WUS_FOOTER_COL1') ? (string) WUS_FOOTER_COL1 : 'auto';
$col2_mode = defined('WUS_FOOTER_COL2') ? (string) WUS_FOOTER_COL2 : 'auto';
$col3_mode = defined('WUS_FOOTER_COL3') ? (string) WUS_FOOTER_COL3 : 'auto';

$col1_widgets = wus_footer_use_widgets($col1_mode, 'wus-footer-1');
$col2_widgets = wus_footer_use_widgets($col2_mode, 'wus-footer-2');
$col3_widgets = wus_footer_use_widgets($col3_mode, 'wus-footer-3');

$year      = esc_html((string) current_time('Y'));
$site_name = get_bloginfo('name');
$site_name = $site_name ? esc_html($site_name) : esc_html__('Website', 'webundso');
?>

<footer class="footer uk-margin-medium-top">
	<div class="uk-container">
		<div class="uk-text-left uk-child-width-1-3@s" uk-grid>

			<!-- Spalte 1 -->
			<div class="uk-panel">
				<?php if ($col1_widgets) : ?>
					<?php dynamic_sidebar('wus-footer-1'); ?>
				<?php else : ?>
					<!-- HTML-Fallback Spalte 1 — projektspezifisch anpassen -->
					<h3><?php echo esc_html__('Footer Menu', 'webundso'); ?></h3>
					<?php if (has_nav_menu('footer-nav')) wus_footernav('footer-nav'); ?>
				<?php endif; ?>
			</div>

			<!-- Spalte 2 -->
			<div class="uk-panel">
				<?php if ($col2_widgets) : ?>
					<?php dynamic_sidebar('wus-footer-2'); ?>
				<?php else : ?>
					<!-- HTML-Fallback Spalte 2 — projektspezifisch anpassen -->
					<h3><?php echo esc_html__('Spalte 2', 'webundso'); ?></h3>
					<p><?php echo esc_html__('Inhalt folgt.', 'webundso'); ?></p>
				<?php endif; ?>
			</div>

			<!-- Spalte 3 -->
			<div class="uk-panel">
				<?php if ($col3_widgets) : ?>
					<?php dynamic_sidebar('wus-footer-3'); ?>
				<?php else : ?>
					<!-- HTML-Fallback Spalte 3 — projektspezifisch anpassen -->
					<h3><?php echo esc_html__('Spalte 3', 'webundso'); ?></h3>
					<p><?php echo esc_html__('Inhalt folgt.', 'webundso'); ?></p>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<div class="copy uk-container uk-margin-medium-top">
		&copy; <?php echo $year; ?> <?php echo $site_name; ?>
	</div>
</footer>

<a href="#top" class="footer-to-top" uk-scroll aria-label="<?php echo esc_attr__('Nach oben', 'webundso'); ?>">
	<span uk-icon="icon: chevron-up; ratio: 2" aria-hidden="true"></span>
	<span class="screen-reader-text"><?php echo esc_html__('Nach oben', 'webundso'); ?></span>
</a>

<?php wp_footer(); ?>
</body>
</html>
