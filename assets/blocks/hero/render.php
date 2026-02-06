<?php
defined('ABSPATH') || exit;

/**
 * Hero Block
 */

$title   = get_field('title');
$text    = get_field('text');
$image   = get_field('background_image');
$button  = get_field('button');

if (!$title && !$image) return;

$anchor = !empty($block['anchor']) ? 'id="' . esc_attr($block['anchor']) . '"' : '';
$class  = 'wus-block-hero';

if (!empty($block['className'])) {
	$class .= ' ' . esc_attr($block['className']);
}

$bg_style = '';
if ($image) {
	$bg_style = 'style="background-image:url(' . esc_url($image['url']) . ');"';
}
?>

<section <?= $anchor ?> class="<?= $class ?> uk-section uk-section-large uk-background-cover uk-light" <?= $bg_style ?>>
	<div class="uk-container">
		<div class="uk-grid uk-flex-middle" uk-grid>
			<div class="uk-width-1-1 uk-width-2-3@m">
				<?php if ($title): ?>
					<h1 class="uk-heading-large"><?= esc_html($title); ?></h1>
				<?php endif; ?>

				<?php if ($text): ?>
					<div class="uk-text-lead">
						<?= apply_filters('the_content', $text); ?>
					</div>
				<?php endif; ?>

				<?php if ($button): ?>
					<a
						href="<?= esc_url($button['url']); ?>"
						class="uk-button uk-button-primary uk-margin-top"
						target="<?= esc_attr($button['target'] ?: '_self'); ?>"
					>
						<?= esc_html($button['title']); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
