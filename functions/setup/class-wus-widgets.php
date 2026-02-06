<?php
defined('ABSPATH') || exit;

/**
 * WUS_Widgets
 * Theme Widget Areas (Sidebars).
 *
 * Ziel:
 * - Sidebars muessen IMMER registriert werden (Frontend + Backend), unabhängig vom Login.
 * - Trennung: Admin-UX bleibt in WUS_Admin, Widget-Infrastruktur hier.
 *
 * Enthält:
 * - Pages Sidebar (wus-sidebar-pages)
 * - Blog Sidebar (wus-sidebar-blog)
 * - Footer Spalten 1–3 (wus-footer-1..3)
 */
class WUS_Widgets
{
  /**
   * Boot: registriert die Sidebars über widgets_init.
   *
   * Wirkung:
   * - macht die Widget Areas im Backend sichtbar
   * - sorgt dafür, dass dynamic_sidebar()/is_active_sidebar() im Frontend funktionieren
   */
  public static function init(): void
  {
    add_action('widgets_init', [__CLASS__, 'register_sidebars']);
  }

  /**
   * Registriert alle Widget Areas.
   *
   * Wirkung:
   * - definiert Markup für Widgets + Titles (UIkit-friendly spacing)
   * - IDs sind stabil, damit Templates/Logic sich darauf verlassen können
   */
  public static function register_sidebars(): void
  {
    $common = [
      'before_widget' => '<section id="%1$s" class="widget %2$s uk-margin-small">',
      'after_widget'  => '</section>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    ];

    // Sidebar: Pages
    register_sidebar(array_merge($common, [
      'name'        => __('Sidebar: Seiten', 'webundso'),
      'id'          => 'wus-sidebar-pages',
      'description' => __('Standard Sidebar für normale Seiten.', 'webundso'),
    ]));

    // Sidebar: Blog
    register_sidebar(array_merge($common, [
      'name'        => __('Sidebar: Blog', 'webundso'),
      'id'          => 'wus-sidebar-blog',
      'description' => __('Sidebar für Blog/Beiträge/Archive.', 'webundso'),
    ]));

    // Footer 1–3
    register_sidebar(array_merge($common, [
      'name'        => __('Footer Spalte 1', 'webundso'),
      'id'          => 'wus-footer-1',
      'description' => __('Widgets für Footer Spalte 1.', 'webundso'),
    ]));

    register_sidebar(array_merge($common, [
      'name'        => __('Footer Spalte 2', 'webundso'),
      'id'          => 'wus-footer-2',
      'description' => __('Widgets für Footer Spalte 2.', 'webundso'),
    ]));

    register_sidebar(array_merge($common, [
      'name'        => __('Footer Spalte 3', 'webundso'),
      'id'          => 'wus-footer-3',
      'description' => __('Widgets für Footer Spalte 3.', 'webundso'),
    ]));
  }
}
