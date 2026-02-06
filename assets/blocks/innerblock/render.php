<?php
/**
 * ACF Block: Section (Seitenabschnitt)
 */

$layout = get_field('layout') ?: 'bleed'; // bleed|full
$pad    = get_field('pad') ?: 'l';        // none|s|m|l|xl

$classes = [
	'block-inner',
	'block-inner--' . $layout,
	'block-inner--pad-' . $pad,
];

$inner_class = 'block-inner__inner';
if ($layout === 'bleed') {
	$inner_class .= ' uk-container';
}
?>
<section <?php echo get_block_wrapper_attributes(['class' => implode(' ', $classes)]); ?>>
	<div class="<?php echo esc_attr($inner_class); ?>">
		<?php echo '<InnerBlocks />'; ?>
	</div>
</section>
