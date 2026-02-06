<?php
defined('ABSPATH') || exit;

$mode = defined('WUS_FOOTER_MODE') ? (string) WUS_FOOTER_MODE : 'auto';

$widgets_active =
		is_active_sidebar('wus-footer-1') ||
		is_active_sidebar('wus-footer-2') ||
		is_active_sidebar('wus-footer-3');

$use_widgets = false;

if ($mode === 'widgets') {
		$use_widgets = true;
} elseif ($mode === 'classic') {
		$use_widgets = false;
} else {
		// auto
		$use_widgets = $widgets_active;
}

$year = esc_html((string) current_time('Y'));
$site_name = get_bloginfo('name');
$site_name = $site_name ? esc_html($site_name) : esc_html__('Website', 'webundso');
?>

<footer class="footer uk-margin-medium-top">
	<div class="uk-container">
		<div class="uk-text-left uk-child-width-1-3@s" uk-grid>

			<?php if ($use_widgets): ?>

			<div class="uk-panel">
				<?php if (is_active_sidebar('wus-footer-1')) dynamic_sidebar('wus-footer-1'); ?>
			</div>

			<div class="uk-panel">
				<?php if (is_active_sidebar('wus-footer-2')) dynamic_sidebar('wus-footer-2'); ?>
			</div>

			<div class="uk-panel">
				<?php if (is_active_sidebar('wus-footer-3')) dynamic_sidebar('wus-footer-3'); ?>
			</div>

			<?php else: ?>

			<div class="uk-panel">
				<h3><?php echo esc_html__('Footer Menu', 'webundso'); ?></h3>
				<?php if (has_nav_menu('footer-nav')) wus_footernav('footer-nav'); ?>
			</div>

			<div class="uk-panel">
				<h3><?php echo esc_html__('Spalte 2', 'webundso'); ?></h3>
				<p><?php echo esc_html__('Inhalt folgt.', 'webundso'); ?></p>
			</div>

			<div class="uk-panel">
				<h3><?php echo esc_html__('Spalte 3', 'webundso'); ?></h3>
				<p><?php echo esc_html__('Inhalt folgt.', 'webundso'); ?></p>
			</div>

			<?php endif; ?>

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