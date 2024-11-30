<?php
/*
=================================================================
Filename: menus.php
Description: Menu functions
Author: Noël Girstmair | webundso GmbH
Last changes: 20.6.2024
=================================================================
*/

#-----------------------------------------------------------------#
# Menu -- Register Menu Locations
# you can pass a menu location into one of the helper functions
#-----------------------------------------------------------------#

function wus_register_my_menus() {
  register_nav_menus(
    array(
      'main-nav'		=> __( 'Hauptmenu', 'webundso_wp' ),		
      'offcanvas-nav'	=> __( 'Off-Canvas Menu', 'webundso_wp' ),
      'footer-nav'	=> __( 'Footer Menu', 'webundso_wp' )		
    )
  );
 }
 add_action( 'init', 'wus_register_my_menus' );

#-----------------------------------------------------------------#
# Top-Menu
#-----------------------------------------------------------------#

function wus_topnav($menu_location) {
  if ( has_nav_menu( $menu_location ) ) {
    wp_nav_menu( array(
      'container'       => false,
      'menu_id'         => $menu_location,
      'menu_class'      => 'uk-nav-parent-icon',
      'items_wrap'      => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
      'theme_location'  => $menu_location,
      'depth'           => 3,
      'walker'          => new \UikitWalker(),
      'show_dropdown_icon'  =>  true
    ) );
  }
}

#-----------------------------------------------------------------#
# Offcanvas-Menu
#-----------------------------------------------------------------#

function wus_offcanvas_nav($menu_location) {
  if ( has_nav_menu( $menu_location ) ) {
    wp_nav_menu( array(
      'container'           => false,
      'menu_id'             => $menu_location,
      'menu_class'          => 'uk-nav uk-nav-default uk-accordion',
      'theme_location'      => $menu_location,
      'depth'               => 4,
      'walker'              => new \UikitWalkerAkk(),
      'show_dropdown_icon'  =>  true
    ) );
  }
}


#-----------------------------------------------------------------#
# Footer-Menu
#-----------------------------------------------------------------#

function wus_footernav($menu_location) {
  if ( has_nav_menu( $menu_location ) ) {
    wp_nav_menu( array(
      'container'       => false,
      'menu_id'         => $menu_location,
      'menu_class'      => '',
      'items_wrap'      => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
      'theme_location'  => $menu_location,
      'depth'           => 0
    ) );
  }
}

// mark the active menu item
function required_active_nav_class( $classes, $item ) {
  if ( $item->current == 1 || $item->current_item_ancestor == true ) {
    $classes[] = 'uk-active';
  }
  return $classes;
}
add_filter( 'nav_menu_css_class', 'required_active_nav_class', 10, 2 );


class UikitWalker extends \Walker_Nav_Menu {

  function start_el( &$output, $item, $depth = 0, $args=[], $id = 0 ) {
    // Fügt .uk-active Class auf Current Menu Item ein.
    if( in_array('current-menu-item', $item->classes) ) {
      $item->classes[] = 'uk-active ';
    }
    // Fügt das Top Level Menu Item ein.
    $output .= "<li id='menu-id-". $item->ID ."' class='" .  implode( ' ', $item->classes ) . "'>";
    // Prüfe ob das Menu item ein Platzhalter Link ist.
    if ( $item->url && '#' !== $item->url ) {
      // Das Menu Item ist ein echter Link, behalte die Standard Ausgabe bei.
     $attributes  = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
     $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target    ) . '"' : '';
     $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn       ) . '"' : '';
     $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url       ) . '"' : '';
     $output .= '<a ' . $attributes .' href="' . $item->url . '">';
    } else {
      // Das Menu Item ist ein "Platzhalter-Link", ändere <a> zu <span>
      $output .= '<span class="uk-navbar-item">';
    }
    // Fügt den Titel erneut in das Menu Item ein.
    $output .= $item->title;
    // Prüfe ob das Menu item ein Platzhalter Link ist.
    if ( $item->url && '#' !== $item->url ) {
      // Prüfe ob das Menu Item ein Submenu hat und ob wir ein Dropdown Icon anzeigen möchten.
      if ( $args->walker->has_children && $args->show_dropdown_icon ) {
        // Fügt ein UiKit3 Icon nach dem Menu Titel ein.
        $output .= '<span data-uk-icon="icon:chevron-down"></span>';
      }
      // Das Menu Item ist ein echter Link, schließe ein <a> Tag.
      $output .= '</a>';
    } else {
      // Prüfe ob das Menu Item ein Submenu hat und ob wir ein Dropdown Icon anzeigen möchten.
      if ( $args->walker->has_children && $args->show_dropdown_icon ) {
        // Fügt ein UiKit3 Icon nach dem Menu Titel ein.
        $output .= '<span data-uk-icon="icon:chevron-down"></span>';
      }
      // Das Menu Item ist ein "Platzhalter-Link", schließe ein <span> Tag.
      $output .= '</span>';
    }
    // Prüfe ob dieser Menüpunkt ein Submenu hat und füge ein div ein.
    if ( $args->walker->has_children ) {
      // Hier wird das .sub-menu noch mit dem UiKit3 benötigten Wrapper umzogen.
      $output .= '<div class="uk-navbar-dropdown">';
    }
    // $depth 1 = Das erste Submenu
    if ( 1 === $depth ) {
      // Ersetze das ul.submenu mit neuen Klassen 'uk-nav uk-navbar-dropdown-nav'
      $output = preg_replace('<ul class="sub-menu">', 'ul class="sub-menu uk-nav uk-navbar-dropdown-nav" ', $output, 1);
    }   
  }
  function end_lvl( &$output, $depth = 0, $args = [] ) {
    // Schließt <ul class="sub-menu uk-nav uk-navbar-dropdown-nav">
    $output .= '</ul>';
  }
  
}


class UikitWalkerAkk extends Walker_Nav_Menu {
  private $is_first_submenu = false;
  
  function start_lvl(&$output, $depth = 0, $args = []) {
    $indent = str_repeat("\t", $depth);
    $blu = ($depth === 0 && $this->is_first_submenu) ? '' : 'hidden'; // fügt attr. hidden ein beim 1. Punkt
    $output .= "\n$indent<ul class=\"uk-nav-sub  \" $blu>\n"; // Verstecke das Submenü initial
  }

  function end_lvl(&$output, $depth = 0, $args = []) {
    $indent = str_repeat("\t", $depth);
    $output .= "$indent</ul>\n";
  }

  function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
    $indent = ($depth) ? str_repeat("\t", $depth) : '';
    $classes = empty($item->classes) ? [] : (array) $item->classes;
    $classes[] = 'menu-item-' . $item->ID;

    if ($args->walker->has_children) {
      $classes[] = 'uk-parent'; // Füge die Klasse für Parent-Elemente hinzu
    }

    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
    $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

    $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
    $id = $id ? ' id="' . esc_attr($id) . '"' : '';

    $output .= $indent . '<li' . $id . $class_names .'>';

    $atts = [];
    $atts['title'] = !empty($item->attr_title) ? $item->attr_title : '';
    $atts['target'] = !empty($item->target) ? $item->target : '';
    $atts['rel'] = !empty($item->xfn) ? $item->xfn : '';
    $atts['href'] = !empty($item->url) ? $item->url : '';

    $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
    $attributes = '';
    foreach ($atts as $attr => $value) {
      if (!empty($value)) {
        $value = ($attr === 'href') ? esc_url($value) : esc_attr($value);
        $attributes .= ' ' . $attr . '="' . $value . '"';
      }
    }

    $title = apply_filters('the_title', $item->title, $item->ID);

    $item_output = $args->before;
    $item_output .= '<a' . $attributes . '>';
    $item_output .= $args->link_before . $title . $args->link_after;
    $item_output .= '</a>';

    // Menü-Toggle-Button für Parent-Elemente hinzufügen
    if ($args->walker->has_children) {
      $item_output .= '<button class="uk-accordion-toggle" uk-toggle="target: #menu-item-' . $item->ID . ' > .uk-nav-sub; animation: uk-animation-slide-top-small"><span uk-icon="icon: chevron-down"></span></button>';
    }

    $item_output .= $args->after;

    $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
  }

  function end_el(&$output, $item, $depth = 0, $args = []) {
    $output .= "</li>\n";
    if ($depth === 0 && $this->is_first_submenu) {
      $this->is_first_submenu = false; // Nur das erste Submenü soll offen sein
    }
  }
}

class Megamenu_Walker extends Walker_Nav_Menu {
  function start_lvl(&$output, $depth = 0, $args = array()) {
    if ($depth === 0) {
        $output .= '<div class="uk-navbar-dropdown" uk-drop="stretch: x;boundary: window; boundary-align: false; pos: bottom-justify; mode: hover; delay-hide: 0; duration: 500"><div class="uk-container"><div class="uk-grid-divider uk-navbar-dropdown-grid uk-child-width-expand@s" uk-grid>';
    } else {
        $output .= '<ul class="uk-nav uk-navbar-dropdown-nav sub-menu">';
    }
  }

  function end_lvl(&$output, $depth = 0, $args = array()) {
    if ($depth === 0) {
      $output .= '</div></div></div>';
    } else {
      $output .= '</ul>';
    }
  }

  function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
    if( in_array('current-menu-item', $item->classes) ) {
      $item->classes[] = 'uk-active ';
    }
    if ($depth === 0) {
      $output .= '<li id="menu-id-'. $item->ID .'" class="' .  implode( ' ', $item->classes ) . '">';
      $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
    } elseif ($depth === 1) {
      $output .= '<div><ul class="uk-nav">';
      $output .= '<li id="menu-id-'. $item->ID .'" class="' .  implode( ' ', $item->classes ) . '"><a href="' . esc_html($item->url) . '">' . esc_html($item->title) . '</a>';
    } elseif ($depth === 2) {
      $output .= '<li id="menu-id-'. $item->ID .'" class="' .  implode( ' ', $item->classes ) . '"><a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a></li>';
    }
  }

  function end_el(&$output, $item, $depth = 0, $args = array()) {
    if ($depth === 0) {
      $output .= '</li>';
    } elseif ($depth === 1) {
      $output .= '</li></ul></div>';
    }
  }
}