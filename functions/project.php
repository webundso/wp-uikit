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
