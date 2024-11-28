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
      'menu_class'          => '',
      'items_wrap'          => '<ul id="%1$s" class="%2$s" uk-nav>%3$s</ul>',
      'theme_location'      => $menu_location,
      'depth'               => 3,
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


class UikitWalkerAkk extends \Walker_Nav_Menu {

  function start_el( &$output, $item, $depth = 0, $args=[], $id = 0 ) {
    // Fügt .uk-active Class auf Current Menu Item ein.
    if( in_array('current-menu-item', $item->classes) ) {
      $item->classes[] = 'uk-active ';
    }
    if($args->walker->has_children){
       $item->classes[] = 'uk-parent ';
    }
    // Fügt das Top Level Menu Item ein.
    $output .= "<li id='menu-id-". $item->ID ."' class='" .  implode( ' ', $item->classes ) . "'>";
    // Prüfe ob das Menu item ein Platzhalter Link ist.
    if ( $item->url && '#' !== $item->url ) {
      // Das Menu Item ist ein echter Link, behalte die Standard Ausgabe bei.
      $output .= '<a href="' . $item->url . '">';
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
    //  $output .= '<div class="uk-navbar-dropdown">';
    }
    // $depth 1 = Das erste Submenu
    if ( 1 === $depth ) {
      // Ersetze das ul.submenu mit neuen Klassen 'uk-nav uk-navbar-dropdown-nav'
      $output = preg_replace('<ul class="sub-menu">', 'ul class="uk-nav-sub" uk-nav="collapsible: true" ', $output, 1);
    }   
  }
  function end_lvl( &$output, $depth = 0, $args = [] ) {
    // Schließt <ul class="sub-menu uk-nav uk-navbar-dropdown-nav">
    $output .= '</ul>';
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