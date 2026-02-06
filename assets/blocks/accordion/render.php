<?php
defined('ABSPATH') || exit;

/**
 * Accordion Block
 */

$items = get_field('items');
if (!$items) return;

$anchor = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '"' : '';
$class  = 'wus-block-accordion';

if (!empty($block['className'])) {
	$class .= ' ' . esc_attr($block['className']);
}
?>

<section <?= $anchor ?> class="<?= $class ?>">
	<div class="uk-container">
		<ul uk-accordion>
			<?php foreach ($items as $item): ?>
				<li>
					<a class="uk-accordion-title" href="#">
						<?= esc_html($item['title']); ?>
					</a>
					<div class="uk-accordion-content">
						<?= apply_filters('the_content', $item['content']); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
