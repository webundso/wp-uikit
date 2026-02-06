<?php
defined('ABSPATH') || exit;

/**
 * parts/loop-blog-grid.php
 *
 *
 * UIkit:
 * - uk-card Layout
 * - Thumbnail als Cover via uk-img (lazy) + uk-background-cover
 *

 *
 * Content-Policy:
 * - In Listen/Grids: the_excerpt() statt the_content()
 */

$post_id   = get_the_ID();
$permalink = get_permalink($post_id);
$title     = get_the_title($post_id);

$thumb_url = get_the_post_thumbnail_url($post_id, 'large');
$has_thumb = !empty($thumb_url);
?>

<div>
	<article id="post-<?php echo esc_attr((string) $post_id); ?>" <?php post_class('uk-card uk-card-default'); ?>>

		<?php if ($has_thumb): ?>
			<a class="uk-card-media-top uk-display-block" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($title); ?>">
				<div
					class="uk-background-cover uk-height-medium"
					data-src="<?php echo esc_url($thumb_url); ?>"
					uk-img
				></div>
			</a>
		<?php endif; ?>

		<div class="uk-card-body">

			<h3 class="uk-card-title uk-margin-small-bottom">
				<a href="<?php echo esc_url($permalink); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php echo esc_html($title); ?>
				</a>
			</h3>

			<div class="uk-text-small uk-margin-small-bottom">
				<time datetime="<?php echo esc_attr(get_the_date('c', $post_id)); ?>">
					<?php echo esc_html(get_the_date('j. F Y', $post_id)); ?>
				</time>
			</div>

			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div>

			<p class="uk-margin-small-top">
				<a class="uk-button uk-button-text" href="<?php echo esc_url($permalink); ?>">
					<?php echo esc_html__('Weiter lesen', 'webundso'); ?>
				</a>
			</p>

		</div>

	</article>
</div>
