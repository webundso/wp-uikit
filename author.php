<?php
defined('ABSPATH') || exit;

/**
 * author.php
 *
 * Hinweis:
 * - 301 = permanent → Suchmaschinen merken sich das
 * - wp_safe_redirect() schützt vor externen Redirect-Zielen
 */

// Fallback: Home-URL
$target = home_url('/');

// Optional: Filter, falls du pro Projekt etwas anderes willst
$target = apply_filters('wus_author_redirect_target', $target);

// Redirect ausführen
wp_safe_redirect($target, 301);
exit;
