<?php
  defined('ABSPATH') || exit;
  
  /**
  * project.php
  *
  * Projektspezifische Funktionen, Hooks und Anpassungen.
  * Diese Datei bleibt theme-unabhängig — hier kommt alles rein,
  * was nur für dieses Projekt gilt.
  *
  * Beispiele:
  * - Custom Post Types / Taxonomien
  * - Shortcodes
  * - AJAX-Handler (projektspezifisch)
  * - ACF-Felder / Optionsseiten
  * - Filter auf Content, Menus, Queries
  * - Projekt-spezifische Widget-Areas
  */
  
  // ============================================================
  // Custom Post Types
  // ============================================================
  
  // add_action('init', function (): void {
  // 	register_post_type('projekt', [
  // 		'label'  => 'Projekte',
  // 		'public' => true,
  // 		// ...
  // 	]);
  // });
  
  
  // ============================================================
  // Shortcodes
  // ============================================================
  
  // add_shortcode('beispiel', function (array $atts, ?string $content = null): string {
  // 	$atts = shortcode_atts(['farbe' => 'primary'], $atts);
  // 	return '<span class="uk-text-' . esc_attr($atts['farbe']) . '">' . esc_html((string) $content) . '</span>';
  // });
  
  
  // ============================================================
  // Hooks / Filter
  // ============================================================
  
  // add_filter('the_content', function (string $content): string {
  // 	return $content;
  // });

  // ============================================================
  // Gutenberg / Editor
  // ============================================================

  if (WUS_HIDE_GUTENBERG_CONTRAST_WARNING) {
  	add_action('admin_head', function (): void {
  		echo '<style>
  .components-notice[aria-label="Warning"][role="alert"] {
  	display: none !important;
  }
  		</style>';
  	});
  }

// ============================================================
// ACF WYSIWYG Toolbar: Zwei Spalten RTE
// ============================================================
    
  
//   add_filter( 'acf/fields/wysiwyg/toolbars', function( $toolbars ) {
//       $toolbars['Name Toolbar'] = array(
//           1 => array(
//               'formatselect',
//               'styleselect',
//               'bold',
//               'italic',
//               'bullist',
//               'outdent',
//               'indent',
//               'link',
//               'blockquote',
//           ),
//       );
//       return $toolbars;
//   } );
//   
//   /**
//    * TinyMCE: Button-Style und H2/H3 als einzige Formate
//    */
// add_filter( 'tiny_mce_before_init', function( $settings ) {
//      $style_formats = array(
//        array(
//          'title'   => 'Button',
//          'inline'  => 'a',
//          'classes' => 'uk-button uk-button-primary',
//          'wrapper' => false,
//        ),
//        array(
//          'title'   => 'Button Stern',
//          'inline'  => 'a',
//          'classes' => 'uk-button uk-button-primary star',
//          'wrapper' => false,
//        ),
//        array(
//          'title'   => 'Preis',
//          'inline'  => 'span',
//          'classes' => 'preis',
//          'wrapper' => false,
//        ),
//        array(
//          'title'    => 'Preisliste eingerückt',
//          'selector' => 'li',
//          'classes'  => 'wus-preisliste--indent',
//        ),
//      );
//    
//      $settings['style_formats'] = wp_json_encode( $style_formats );
//      $settings['block_formats'] = 'Paragraph=p;H2=h2;H3=h3';
//    
//      return $settings;
//    } );
//    