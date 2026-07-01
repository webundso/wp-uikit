<?php
defined('ABSPATH') || exit;

/**
 * menus.php
 * UIkit Menu Integration:
 * - Menu Locations
 * - Output Helpers (Topnav / Offcanvas / Footer)
 * - Security: noopener/noreferrer bei target=_blank
 * - uk-active auf current + ancestor
 * - Walkers:
 *   - WUS_UikitWalker (Topnav Dropdown)
 *   - WUS_UikitWalkerAkk (Offcanvas Accordion-ish)
 *   - WUS_Megamenu_Walker (Topnav Mega Dropdown)
 */

class WUS_Menus
{
    /**
     * Boot: registriert Menus + Filter.
     */
    public static function init(): void
    {
        add_action('after_setup_theme', [__CLASS__, 'register_menus']);
        add_filter('nav_menu_css_class', [__CLASS__, 'active_nav_class'], 10, 2);
        add_filter('nav_menu_link_attributes', [__CLASS__, 'secure_blank_targets'], 10, 3);
    }

    /**
     * Menu-Positionen registrieren.
     */
    public static function register_menus(): void
    {
        register_nav_menus([
            'main-nav'      => __('Hauptmenu', 'webundso'),
            'offcanvas-nav' => __('Off-Canvas Menu', 'webundso'),
            'footer-nav'    => __('Footer Menu', 'webundso'),
        ]);
    }

    /**
     * Top Navigation (UIkit Navbar).
     * $walker_mode:
     * - 'dropdown' (default)
     * - 'mega'
     */
  public static function topnav(string $menu_location, ?string $walker_mode = null): void
  {
      if (!has_nav_menu($menu_location)) {
          return;
      }
  
      // Default Mode aus Config, aber per Parameter überschreibbar
      $mode = $walker_mode ?: (defined('WUS_TOPNAV_MODE') ? (string) WUS_TOPNAV_MODE : 'dropdown');
  
      $walker = null;
  
      if ($mode === 'mega' && class_exists('WUS_Megamenu_Walker')) {
          $walker = new WUS_Megamenu_Walker();
      } elseif (class_exists('WUS_UikitWalker')) {
          $walker = new WUS_UikitWalker();
      }
  
      wp_nav_menu([
          'container'      => false,
          'theme_location' => $menu_location,
          'menu_id'        => $menu_location,
          'menu_class'     => 'uk-navbar-nav',
          'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
          'depth'          => 3,
          'fallback_cb'    => false,
          'walker'         => $walker,
          'show_dropdown_icon' => true,
      ]);
  }


    /**
     * Offcanvas Navigation (mobil).
     */
    public static function offcanvas_nav(string $menu_location): void
    {
        if (!has_nav_menu($menu_location)) {
            return;
        }

        wp_nav_menu([
            'container'      => false,
            'theme_location' => $menu_location,
            'menu_id'        => $menu_location,
            'menu_class'     => 'uk-nav uk-nav-default uk-accordion',
            'depth'          => 4,
            'fallback_cb'    => false,
            'walker'         => class_exists('WUS_UikitWalkerAkk') ? new WUS_UikitWalkerAkk() : null,
            'show_dropdown_icon' => true,
        ]);
    }

    /**
     * Footer Navigation.
     */
    public static function footernav(string $menu_location): void
    {
        if (!has_nav_menu($menu_location)) {
            return;
        }

        wp_nav_menu([
            'container'      => false,
            'theme_location' => $menu_location,
            'menu_id'        => $menu_location,
            'menu_class'     => '',
            'items_wrap'     => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
            'depth'          => 1,
            'fallback_cb'    => false,
        ]);
    }

    /**
     * UIkit Active State:
     * - uk-active für current item + ancestor
     */
    public static function active_nav_class(array $classes, $item): array
    {
        if (!empty($item->current) || !empty($item->current_item_ancestor)) {
            $classes[] = 'uk-active';
        }
        return $classes;
    }

    /**
     * Security: target=_blank => rel noopener noreferrer erzwingen (Tabnabbing).
     */
    public static function secure_blank_targets(array $atts, $item, $args): array
    {
        if (!empty($atts['target']) && $atts['target'] === '_blank') {
            $rel = isset($atts['rel']) ? (string) $atts['rel'] : '';
            $rel_parts = preg_split('/\s+/', trim($rel)) ?: [];

            $rel_parts[] = 'noopener';
            $rel_parts[] = 'noreferrer';

            $rel_parts = array_values(array_unique(array_filter($rel_parts)));
            $atts['rel'] = implode(' ', $rel_parts);
        }

        return $atts;
    }
}


/* -----------------------------------------------------------------------------
 * Walker: Topnav Dropdown (UIkit Navbar Dropdown)
 * -------------------------------------------------------------------------- */

class WUS_UikitWalker extends Walker_Nav_Menu
{
    /**
     * Submenu öffnen.
     * UIkit: Dropdown Wrapper + UL mit uk-navbar-dropdown-nav auf depth 0.
     */
    public function start_lvl(&$output, $depth = 0, $args = null): void
    {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "\n{$indent}<div class=\"uk-navbar-dropdown\">\n";
            $output .= "{$indent}\t<ul class=\"uk-nav uk-navbar-dropdown-nav\">\n";
        } else {
            // Deep submenus: UIkit sub nav
            $output .= "\n{$indent}<ul class=\"uk-nav-sub\">\n";
        }
    }

    /**
     * Submenu schliessen.
     */
    public function end_lvl(&$output, $depth = 0, $args = null): void
    {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "{$indent}\t</ul>\n";
            $output .= "{$indent}</div>\n";
        } else {
            $output .= "{$indent}</ul>\n";
        }
    }

    /**
     * Element-Ausgabe.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void
    {
        $indent = $depth ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? [] : (array) $item->classes;

        // has_children korrekt (WP setzt das pro Item)
        $has_children = !empty($args->has_children);

        if ($has_children) {
            $classes[] = 'uk-parent';
        }

        if (in_array('current-menu-item', $classes, true)) {
            $classes[] = 'uk-active';
        }

        $class_names = implode(' ', array_filter($classes));
        $output .= "{$indent}<li id=\"menu-id-" . esc_attr($item->ID) . "\" class=\"" . esc_attr($class_names) . "\">";

        // Link vs Placeholder
        if (!empty($item->url) && $item->url !== '#') {
            $output .= $this->build_link($item, $args, $has_children, $depth);
        } else {
            $output .= $this->build_placeholder($item, $args, $has_children, $depth);
        }
    }

    /**
     * Element schliessen.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null): void
    {
        $output .= "</li>\n";
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    private function build_link($item, $args, bool $has_children, int $depth): string
    {
        $atts = [
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target) ? $item->target : '',
            'rel'    => !empty($item->xfn) ? $item->xfn : '',
            'href'   => !empty($item->url) ? $item->url : '',
        ];

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if ($value === '') {
                continue;
            }
            $value = ($attr === 'href') ? esc_url($value) : esc_attr($value);
            $attributes .= " {$attr}=\"{$value}\"";
        }

        $title = esc_html($item->title);

        $html  = "<a{$attributes}>";
        $html .= $title;

        if ($has_children && !empty($args->show_dropdown_icon) && $depth === 0) {
            $html .= ' <span uk-icon="icon: chevron-down"></span>';
        }

        $html .= '</a>';

        return $html;
    }

    private function build_placeholder($item, $args, bool $has_children, int $depth): string
    {
        $title = esc_html($item->title);

        // Für Navbar: uk-navbar-item ist passend
        $html  = '<span class="uk-navbar-item">';
        $html .= $title;

        if ($has_children && !empty($args->show_dropdown_icon) && $depth === 0) {
            $html .= ' <span uk-icon="icon: chevron-down"></span>';
        }

        $html .= '</span>';

        return $html;
    }
}


/* -----------------------------------------------------------------------------
 * Walker: Offcanvas (Accordion-ish mit Toggle Button)
 * -------------------------------------------------------------------------- */

class WUS_UikitWalkerAkk extends Walker_Nav_Menu
 {
     /**
      * Submenu öffnen.
      * Default: hidden, wird via UIkit Toggle/JS geöffnet.
      */
     public function start_lvl(&$output, $depth = 0, $args = null): void
     {
         $indent = str_repeat("\t", $depth);
         $output .= "\n{$indent}<ul class=\"uk-nav-sub\" hidden>\n";
     }
     /**
      * Submenu schliessen.
      */
     public function end_lvl(&$output, $depth = 0, $args = null): void
     {
         $indent = str_repeat("\t", $depth);
         $output .= "{$indent}</ul>\n";
     }
     /**
      * Element-Ausgabe.
      */
     public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void
     {
         $indent = $depth ? str_repeat("\t", $depth) : '';
         $classes = empty($item->classes) ? [] : (array) $item->classes;
 
         // Robust: direkt aus den vorhandenen Item-Klassen ableiten,
         // statt auf $args->has_children zu vertrauen (unzuverlässig bei Custom-Walkern)
         $has_children = in_array('menu-item-has-children', $classes, true);
 
         $classes[] = 'menu-item-' . $item->ID;
         if ($has_children) {
             $classes[] = 'uk-parent';
         }
         $class_names = implode(' ', array_filter($classes));
         $item_id = 'menu-item-' . $item->ID;
         $output .= "{$indent}<li id=\"" . esc_attr($item_id) . "\" class=\"" . esc_attr($class_names) . "\">";
         // Link Attribute sauber über WP Filter (inkl. noopener via globalem Filter)
         $atts = [
             'title'  => !empty($item->attr_title) ? $item->attr_title : '',
             'target' => !empty($item->target) ? $item->target : '',
             'rel'    => !empty($item->xfn) ? $item->xfn : '',
             'href'   => !empty($item->url) ? $item->url : '',
         ];
         $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
         $attributes = '';
         foreach ($atts as $attr => $value) {
             if ($value === '') {
                 continue;
             }
             $value = ($attr === 'href') ? esc_url($value) : esc_attr($value);
             $attributes .= " {$attr}=\"{$value}\"";
         }
         $title = esc_html($item->title);
         $output .= "<a{$attributes}>{$title}</a>";
         // Toggle Button für Parents
         if ($has_children) {
             $sub_selector = '#' . $item_id . ' > .uk-nav-sub';
             $output .= '<button'
                 . ' type="button"'
                 . ' class="uk-accordion-toggle"'
                 . ' aria-controls="' . esc_attr($item_id . '-sub') . '"'
                 . ' aria-expanded="false"'
                 . ' uk-toggle="target: ' . esc_attr($sub_selector) . '; animation: uk-animation-slide-top-small">'
                 . '<span uk-icon="icon: chevron-down"></span>'
                 . '</button>';
         }
     }
     /**
      * Element schliessen.
      */
     public function end_el(&$output, $item, $depth = 0, $args = null): void
     {
         $output .= "</li>\n";
     }
     /**
      * Optional: Submenu UL bekommt eine ID für aria-controls.
      * (Walker_Nav_Menu gibt uns dafür keinen direkten Hook pro UL-ID; wenn du das exakt willst,
      * machst du es über start_lvl mit depth+parent id tracking. Für jetzt: ok.)
      */
 }


/* -----------------------------------------------------------------------------
 * Walker: Megamenu (UIkit Drop + Grid)
 * Struktur:
 * - depth 0: Navbar Item
 * - depth 0 submenu: Dropdown Wrapper + Grid
 * - depth 1: Column (Titel als Link), Kinder in UL
 * - depth 2: normale Links in Column
 * -------------------------------------------------------------------------- */

class WUS_Megamenu_Walker extends Walker_Nav_Menu
{
    /**
     * Submenu öffnen.
     */
    public function start_lvl(&$output, $depth = 0, $args = null): void
    {
        if ($depth === 0) {
            $output .= '<div class="uk-navbar-dropdown" '
                . 'uk-drop="stretch: x; pos: bottom-justify; mode: hover; delay-hide: 0; duration: 250">'
                . '<div class="uk-container">'
                . '<div class="uk-grid-divider uk-navbar-dropdown-grid uk-child-width-expand@s" uk-grid>';
            return;
        }

        // depth 1: Column list öffnen
        if ($depth === 1) {
            $output .= '<ul class="uk-nav uk-navbar-dropdown-nav">';
            return;
        }

        // depth 2+: normale sub lists
        $output .= '<ul class="uk-nav-sub">';
    }

    /**
     * Submenu schliessen.
     */
    public function end_lvl(&$output, $depth = 0, $args = null): void
    {
        if ($depth === 0) {
            $output .= '</div></div></div>';
            return;
        }

        $output .= '</ul>';
    }

    /**
     * Element-Ausgabe.
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0): void
    {
        $has_children = !empty($args->has_children);
        $classes = empty($item->classes) ? [] : (array) $item->classes;

        if ($has_children) {
            $classes[] = 'uk-parent';
        }

        if (in_array('current-menu-item', $classes, true)) {
            $classes[] = 'uk-active';
        }

        $class_names = esc_attr(implode(' ', array_filter($classes)));
        $item_id = 'menu-id-' . esc_attr($item->ID);

        // depth 0: normal navbar li + link/placeholder
        if ($depth === 0) {
            $output .= '<li id="' . $item_id . '" class="' . $class_names . '">';

            if (!empty($item->url) && $item->url !== '#') {
                $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
            } else {
                $output .= '<span class="uk-navbar-item">' . esc_html($item->title) . '</span>';
            }

            return;
        }

        // depth 1: Column wrapper + Column title (als Link)
        if ($depth === 1) {
            $output .= '<div class="wus-mega-col">';

            $title = esc_html($item->title);

            if (!empty($item->url) && $item->url !== '#') {
                $output .= '<a class="uk-nav-header" href="' . esc_url($item->url) . '">' . $title . '</a>';
            } else {
                $output .= '<div class="uk-nav-header">' . $title . '</div>';
            }

            return;
        }

        // depth 2+: normale LI in Column-UL
        $output .= '<li id="' . $item_id . '" class="' . $class_names . '">';

        if (!empty($item->url) && $item->url !== '#') {
            $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
        } else {
            $output .= '<span class="uk-nav-text">' . esc_html($item->title) . '</span>';
        }
    }

    /**
     * Element schliessen.
     */
    public function end_el(&$output, $item, $depth = 0, $args = null): void
    {
        if ($depth === 0) {
            $output .= '</li>';
            return;
        }

        if ($depth === 1) {
            $output .= '</div>';
            return;
        }

        if ($depth >= 2) {
            $output .= '</li>';
            return;
        }
    }
}


/* -----------------------------------------------------------------------------
 * Legacy Wrapper Functions (für bestehende Templates)
 * -------------------------------------------------------------------------- */

function wus_register_my_menus(): void
{
    WUS_Menus::register_menus();
}

function required_active_nav_class(array $classes, object $item): array
{
    return WUS_Menus::active_nav_class($classes, $item);
}

function wus_topnav(string $menu_location, ?string $mode = null): void
{
    WUS_Menus::topnav($menu_location, $mode);
}

function wus_offcanvas_nav(string $menu_location): void
{
    WUS_Menus::offcanvas_nav($menu_location);
}

function wus_footernav(string $menu_location): void
{
    WUS_Menus::footernav($menu_location);
}
