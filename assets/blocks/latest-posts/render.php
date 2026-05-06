<?php
/**
 * ACF Block: Letzte Beiträge
 * Pfad: assets/blocks/latest-posts/render.php
 *
 * ACF Felder (Feldgruppe «Block: Letzte Beiträge», Regel: Block == acf/latest-posts):
 *
 *   posts_count  | Zahl         | Default 6     | Anzahl Beiträge
 *   categories   | Taxonomie    | Default leer  | Kategorie-Filter (mehrfach, category)
 *   show_more    | Wahr/Falsch  | Default 1     | «Ältere Beiträge laden»-Button anzeigen
 */

defined('ABSPATH') || exit;

// ── Felder ─────────────────────────────────────────────────────────────────
$posts_count = max(1, (int) (get_field('posts_count') ?: 6));
$categories  = get_field('categories'); // Term-Objekte, Term-IDs oder false
$show_more   = get_field('show_more');
$show_more   = ($show_more === '' || $show_more === null) ? true : (bool) $show_more;

// ── Kategorie Term-IDs normalisieren ───────────────────────────────────────
$cat_ids = [];
if (!empty($categories)) {
    $list = is_array($categories) ? $categories : [$categories];
    foreach ($list as $term) {
        $cat_ids[] = is_object($term) ? (int) $term->term_id : (int) $term;
    }
    $cat_ids = array_filter($cat_ids);
}

// ── WP_Query ───────────────────────────────────────────────────────────────
$args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => $posts_count,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => false, // brauchen max_num_pages für Show-More
];

if (!empty($cat_ids)) {
    $args['tax_query'] = [[
        'taxonomy' => 'category',
        'field'    => 'term_id',
        'terms'    => $cat_ids,
        'operator' => 'IN',
    ]];
}

$query = new WP_Query($args);
?>

<div class="wus-block-latest-posts">

    <?php if ($query->have_posts()) : ?>

        <div id="wus-blog-posts" class="uk-child-width-1-2@s uk-child-width-1-3@m uk-grid-match uk-grid" uk-grid>

          <?php while ($query->have_posts()) : $query->the_post(); ?>
              <div>
                  <?php get_template_part('assets/blocks/latest-posts/post-card'); ?>
              </div>
          <?php endwhile; wp_reset_postdata(); ?>

        </div><!-- .uk-grid -->

        <?php if ($show_more) :
            $max_pages = $query->max_num_pages;

            if ($max_pages > 1) {
                add_action('wp_footer', [WUS_Assets::class, 'enqueue_load_more'], 5);
            }
        ?>
            <div class="wus-block-latest-posts__more uk-text-center uk-margin-top"
                 <?php echo ($max_pages <= 1) ? 'hidden' : ''; ?>>
                <button
                    id="wus-load-more"
                    class="uk-button uk-button-primary"
                    data-max-pages="<?php echo esc_attr($max_pages); ?>"
                    data-per-page="<?php echo esc_attr($posts_count); ?>"
                    data-categories="<?php echo esc_attr(implode(',', $cat_ids)); ?>"
                    data-context="block"
                    data-label="<?php esc_attr_e('Ältere Beiträge laden', 'webundso'); ?>">
                    <?php esc_html_e('Ältere Beiträge laden', 'webundso'); ?>
                </button>
            </div>
        <?php endif; ?>

    <?php else : ?>

        <p class="uk-text-muted">
            <?php esc_html_e('Keine Beiträge gefunden.', 'webundso'); ?>
        </p>

    <?php endif; ?>

</div><!-- .wus-block-latest-posts -->
