<?php
/**
 * Template Name: Redirect to Child
 * Filename: template-redirect-to-child-page.php
 * Description: Redirects to first published subpage (by menu_order ASC).
 */

defined('ABSPATH') || exit;

global $post;

if (!($post instanceof WP_Post)) {
  // Wenn kein Post-Kontext da ist, nichts machen.
  wp_safe_redirect(home_url('/'), 302);
  exit;
}

$parent_id = (int) $post->ID;

/**
 * WPML (optional):
 * Falls WPML aktiv ist, Parent auf die aktuelle Sprache mappen.
 */
if (function_exists('icl_object_id') && defined('ICL_LANGUAGE_CODE')) {
  $type = get_post_type($parent_id);
  $mapped = icl_object_id($parent_id, $type, true, ICL_LANGUAGE_CODE);
  if (!empty($mapped)) {
    $parent_id = (int) $mapped;
  }
}

/**
 * Erstes Kind holen:
 * - nur published pages
 * - sortiert nach menu_order, dann title als tie-breaker
 * - nur IDs für Performance
 */
$children = get_pages([
  'post_type'    => 'page',
  'post_status'  => 'publish',
  'parent'       => $parent_id,
  'sort_column'  => 'menu_order,post_title',
  'sort_order'   => 'ASC',
  'number'       => 1,
]);

if (!empty($children) && $children[0] instanceof WP_Post) {
  $child_id = (int) $children[0]->ID;
  $url = get_permalink($child_id);

  if ($url) {
    wp_safe_redirect($url, 301);
    exit;
  }
}

/**
 * Fallback:
 * Wenn kein Kind existiert (oder keine URL), zur Parent-Page selbst oder zur Startseite.
 * Du kannst hier auch auf 302 umstellen, wenn du SEO-Risiko vermeiden willst.
 */
$url = get_permalink($parent_id);
wp_safe_redirect($url ?: home_url('/'), 302);
exit;
