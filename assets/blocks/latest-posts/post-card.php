<?php defined('ABSPATH') || exit; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('uk-card uk-card-default'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a class="uk-display-block uk-position-relative"
           href="<?php the_permalink(); ?>"
           tabindex="-1"
           aria-hidden="true">
            <div class="uk-background-cover post-card-image"
                 data-src="<?php echo esc_url(get_the_post_thumbnail_url(null, 'medium_large')); ?>"
                 uk-img>
                <?php $cats = get_the_category();
                if (!empty($cats)) : ?>
                    <span class="post-category-badge">
                        <?php echo esc_html(strtoupper($cats[0]->name)); ?>
                    </span>
                <?php endif; ?>
            </div>
        </a>
    <?php endif; ?>
</article>
