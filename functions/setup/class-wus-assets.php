<?php
defined('ABSPATH') || exit;

/**
 * WUS_Assets
 * Frontend Assets:
 * - UIkit (lokal, mit Version)
 * - Projekt JS (assets/js/scripts.js) mit Cache-Busting via filemtime
 * - Projekt CSS (assets/styles/site.css) mit Cache-Busting via filemtime
 *
 * Schalter:
 * - WUS_UIKIT_VERSION (string): UIkit Version (z.B. 3.17.11)
 * - WUS_SCRIPT_DEBUG (bool): lädt unminified UIkit Files, falls vorhanden
 */

class WUS_Assets
{
		/**
		 * Boot: hängt Assets an wp_enqueue_scripts (Priority 20).
		 */
		public static function init(): void
		{
				add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue'], 20);
		}

		/**
		 * Enqueue aller Frontend Assets.
		 */
		public static function enqueue(): void
		{
				self::enqueue_uikit();
				self::enqueue_uikit_css();
				self::enqueue_project_assets();
		}

		/**
		 * Load More Script: enqueued on-demand von ACF Block (latest-posts).
		 * Wird via wp_footer Hook aufgerufen, sobald Block vorhanden ist.
		 */
		public static function enqueue_load_more(): void
		{
				$ajax_rel = '/assets/js/wus-ajax.js';
				$ajax_abs = get_stylesheet_directory() . $ajax_rel;
				$ajax_uri = get_stylesheet_directory_uri() . $ajax_rel;

				if (!file_exists($ajax_abs)) {
						return;
				}

				wp_enqueue_script(
						'wus-ajax',
						$ajax_uri,
						['jquery', 'uikit'],
						filemtime($ajax_abs),
						true
				);

				wp_localize_script('wus-ajax', 'wusAjax', [
						'ajaxUrl' => admin_url('admin-ajax.php'),
						'nonce'   => wp_create_nonce('wus_load_more'),
						'loading' => esc_html__('Wird geladen…', 'webundso'),
						'noMore'  => esc_html__('Keine weiteren Beiträge', 'webundso'),
				]);
		}

		/**
		 * UIkit Enqueue (lokale Library im Theme).
		 * Effekt: UI-Komponenten + Icons verfügbar im Frontend.
		 */
		private static function enqueue_uikit(): void
		{
				$uikit_version = defined('WUS_UIKIT_VERSION') ? (string) WUS_UIKIT_VERSION : null;

				$debug = defined('WUS_SCRIPT_DEBUG') && WUS_SCRIPT_DEBUG;
				$suffix = $debug ? '' : '.min';

				// UIkit liegt bei dir im Theme: /uikit/dist/js/...
				$base_uri = get_template_directory_uri() . '/uikit/dist/js/';
				$base_dir = get_template_directory() . '/uikit/dist/js/';

				$uikit_file       = "uikit{$suffix}.js";
				$uikit_icons_file = "uikit-icons{$suffix}.js";

				// Fail-soft: nur registrieren, wenn File existiert
				if (!file_exists($base_dir . $uikit_file)) {
						// Optional debug log
						// if (defined('WP_DEBUG') && WP_DEBUG) error_log('WUS: UIkit missing: ' . $base_dir . $uikit_file);
						return;
				}

				wp_register_script(
						'uikit',
						$base_uri . $uikit_file,
						[],
						$uikit_version,
						true
				);

				if (file_exists($base_dir . $uikit_icons_file)) {
						wp_register_script(
								'uikit-icons',
								$base_uri . $uikit_icons_file,
								['uikit'],
								$uikit_version,
								true
						);
				}

				wp_enqueue_script('uikit');

				// Icons nur enqueuen, wenn registriert
				if (wp_script_is('uikit-icons', 'registered')) {
						wp_enqueue_script('uikit-icons');
				}
		}

		/**
		 * UIkit CSS Enqueue (vorkompilierte Distribution, /uikit/dist/css/).
		 * Effekt: Basis-Styles vor site.css geladen, damit Theme-Overrides greifen.
		 */
		private static function enqueue_uikit_css(): void
		{
				$uikit_version = defined('WUS_UIKIT_VERSION') ? (string) WUS_UIKIT_VERSION : null;

				$debug = defined('WUS_SCRIPT_DEBUG') && WUS_SCRIPT_DEBUG;
				$suffix = $debug ? '' : '.min';

				$base_uri = get_template_directory_uri() . '/uikit/dist/css/';
				$base_dir = get_template_directory() . '/uikit/dist/css/';

				$uikit_css_file = "uikit{$suffix}.css";

				if (!file_exists($base_dir . $uikit_css_file)) {
						return;
				}

				wp_register_style(
						'uikit',
						$base_uri . $uikit_css_file,
						[],
						$uikit_version
				);

				wp_enqueue_style('uikit');
		}

		/**
		 * Projekt-Assets (Child Theme / Stylesheet).
		 * Effekt: site.css + scripts.js geladen, mit Cache-Busting über filemtime.
		 */
		private static function enqueue_project_assets(): void
		{
				// ---------------------------------------------------------------------
				// JS
				// ---------------------------------------------------------------------
				$js_rel = '/assets/js/scripts.js';
				$js_abs = get_stylesheet_directory() . $js_rel;
				$js_uri = get_stylesheet_directory_uri() . $js_rel;

				if (file_exists($js_abs)) {
						$js_ver = filemtime($js_abs);

						// Wenn dein scripts.js jQuery braucht: dependency drin lassen.
						// Wenn nicht: ['jquery'] rausnehmen.
						wp_enqueue_script(
								'wus-script',
								$js_uri,
								['jquery'],
								$js_ver,
								true
						);

						// jQuery nur ziehen, wenn du es wirklich brauchst.
						// wp_enqueue_script('jquery');
				}

				// ---------------------------------------------------------------------
				// AJAX Load More (nur auf Blog/Archive-Seiten)
				// ---------------------------------------------------------------------
				if (
						(defined('WUS_BLOG_LOAD_MORE') && WUS_BLOG_LOAD_MORE) &&
						(is_home() || is_archive())
				) {
						$ajax_rel = '/assets/js/wus-ajax.js';
						$ajax_abs = get_stylesheet_directory() . $ajax_rel;
						$ajax_uri = get_stylesheet_directory_uri() . $ajax_rel;

						if (file_exists($ajax_abs)) {
								wp_enqueue_script(
										'wus-ajax',
										$ajax_uri,
										['jquery', 'uikit'],
										filemtime($ajax_abs),
										true
								);

								wp_localize_script('wus-ajax', 'wusAjax', [
										'ajaxUrl' => admin_url('admin-ajax.php'),
										'nonce'   => wp_create_nonce('wus_load_more'),
										'loading' => esc_html__('Wird geladen…', 'webundso'),
										'noMore'  => esc_html__('Keine weiteren Beiträge', 'webundso'),
								]);
						}
				}

				// ---------------------------------------------------------------------
				// CSS
				// ---------------------------------------------------------------------
				$css_rel = '/assets/styles/site.css';
				$css_abs = get_stylesheet_directory() . $css_rel;
				$css_uri = get_stylesheet_directory_uri() . $css_rel;

				if (file_exists($css_abs)) {
						$css_ver = filemtime($css_abs);

						wp_enqueue_style(
								'wus-site',
								$css_uri,
								wp_style_is('uikit', 'registered') ? ['uikit'] : [],
								$css_ver,
								'all'
						);
				}
		}
}
