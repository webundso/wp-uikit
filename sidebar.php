<?php
defined('ABSPATH') || exit;

/**
 * sidebar.php
 * Generic Sidebar Output
 * Die Sidebar-ID wird kontextabhängig via WUS_Content bestimmt.
 */

if (!class_exists('WUS_Content')) {
	return;
}

$sidebar_id = WUS_Content::get_sidebar_id();

if (!is_active_sidebar($sidebar_id)) {
	return;
}
?>

<aside class="sidebar" aria-label="<?php echo esc_attr__('Sidebar', 'webundso'); ?>">
	<?php dynamic_sidebar($sidebar_id); ?>
</aside>
