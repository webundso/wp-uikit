<?php
defined('ABSPATH') || exit;

/**
 * WUS_Admin
 * Admin-/Backend-UX Tweaks:
 * - Standard Widgets reduzieren
 * - Dashboard aufraeumen + Support Widget
 * - Admin Menu Tweaks (z.B. “Alle Einstellungen”)
 * - Admin Bar aufraeumen + “Howdy” ersetzen
 * - Admin Footer Branding
 *
 * Schalter:
 * - WUS_DISABLE_ADMIN_BAR (bool): blendet Admin-Bar für Nicht-Admins aus (Admins behalten sie).
 */

class WUS_Admin
{
		/**
		 * Boot: registriert alle Admin-Hooks.
		 * Wichtig: laeuft nur im Backend oder wenn ein User eingeloggt ist (Admin Bar).
		 */
		public static function init(): void
		{
				// Im Frontend ohne Login ist das alles nutzlos -> keine Hooks registrieren.
				if (!is_admin() && !is_user_logged_in()) {
						return;
				}

				// Widgets
				add_action('widgets_init', [__CLASS__, 'disable_standard_widgets']);

				// Dashboard
				add_action('wp_dashboard_setup', [__CLASS__, 'custom_dashboard_widgets']);

				// Admin Menu
				add_action('admin_menu', [__CLASS__, 'admin_menu_tweaks']);

				// Admin Bar (Backend + Frontend wenn eingeloggt)
				add_action('wp_before_admin_bar_render', [__CLASS__, 'admin_bar_cleanup']);
				add_action('admin_bar_menu', [__CLASS__, 'replace_howdy'], 25);

				// Footer Branding
				add_filter('admin_footer_text', [__CLASS__, 'custom_admin_footer']);
				

				/**
				 * Admin-Bar ausblenden:
				 * Wenn aktiv, bleibt sie für Admins sichtbar, für alle anderen aus.
				 */
				if (defined('WUS_DISABLE_ADMIN_BAR') && WUS_DISABLE_ADMIN_BAR) {
						add_filter('show_admin_bar', [__CLASS__, 'maybe_hide_admin_bar']);
				}
		}

		/**
		 * Admin Bar Sichtbarkeit steuern.
		 * Effekt: Admins sehen sie weiterhin, alle anderen nicht.
		 */
		public static function maybe_hide_admin_bar($show): bool
		{
				// $show respektieren wir nicht, weil wir eine klare Policy wollen.
				return current_user_can('manage_options');
		}

		/**
		 * Standard Widgets deaktivieren.
		 * Effekt: weniger “legacy” Widgets im Widgets-Screen.
		 */
		public static function disable_standard_widgets(): void
		{
				unregister_widget('WP_Widget_Categories');
				unregister_widget('WP_Widget_Recent_Posts');
				unregister_widget('WP_Widget_Recent_Comments');
				unregister_widget('WP_Widget_RSS');
				unregister_widget('WP_Widget_Meta');
				unregister_widget('WP_Widget_Search');
		}

		/**
		 * Entfernt Default Dashboard Widgets.
		 * Effekt: Dashboard bleibt clean und projektfokussiert.
		 */
		private static function disable_default_dashboard_widgets(): void
		{
				remove_meta_box('dashboard_right_now', 'dashboard', 'core');
				remove_meta_box('dashboard_recent_comments', 'dashboard', 'core');
				remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');
				remove_meta_box('dashboard_plugins', 'dashboard', 'core');
				remove_meta_box('dashboard_activity', 'dashboard', 'core');
				remove_meta_box('dashboard_quick_press', 'dashboard', 'core');
				remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');
				remove_meta_box('dashboard_primary', 'dashboard', 'core');
				remove_meta_box('dashboard_secondary', 'dashboard', 'core');

				// Plugin-spezifisch (falls vorhanden)
				remove_meta_box('yoast_db_widget', 'dashboard', 'normal');
		}

		/**
		 * Dashboard Widgets setzen.
		 * Effekt: Default Widgets raus, Support Widget rein.
		 */
		public static function custom_dashboard_widgets(): void
		{
				self::disable_default_dashboard_widgets();

				wp_add_dashboard_widget(
						'wus_support_widget',
						__('Support Webseite', 'webundso'),
						[__CLASS__, 'dashboard_help_widget']
				);
		}

		/**
		 * Inhalt Support Dashboard Widget.
		 * Effekt: Support-Mail prominent im Dashboard.
		 */
		public static function dashboard_help_widget(): void
		{
				$support_email = defined('WUS_BRAND_SUPPORT_EMAIL')
						? WUS_BRAND_SUPPORT_EMAIL
						: 'support@webundso.ch';

				printf(
						'<p>%s: <a href="mailto:%s">%s</a></p>',
						esc_html__('Support-Anfrage an webundso senden', 'webundso'),
						esc_attr($support_email),
						esc_html($support_email)
				);
		}

		/**
		 * Admin Menu Tweaks.
		 * Effekt: “Alle Einstellungen” für Administratoren sichtbar (High Risk).
		 */
		public static function admin_menu_tweaks(): void
		{
				add_options_page(
						__('Alle Einstellungen', 'webundso'),
						__('ALLE Einstellungen', 'webundso'),
						'manage_options',
						'options.php'
				);

				// Optional removals bleiben bewusst auskommentiert.
		}

		/**
		 * Admin Bar Cleanup.
		 * Effekt: WP Logo / About / Docs etc. entfernen.
		 */
		public static function admin_bar_cleanup(): void
		{
				global $wp_admin_bar;
				if (!$wp_admin_bar) {
						return;
				}

				$wp_admin_bar->remove_menu('wp-logo');
				$wp_admin_bar->remove_menu('about');
				$wp_admin_bar->remove_menu('wporg');
				$wp_admin_bar->remove_menu('documentation');
				$wp_admin_bar->remove_menu('support-forums');
				$wp_admin_bar->remove_menu('feedback');
		}

		/**
		 * Ersetzt “Howdy” im My-Account Menü.
		 * Effekt: neutraleres Wording (“Welcome, Name”).
		 */
		public static function replace_howdy($wp_admin_bar): void
		{
				if (!is_user_logged_in() || !is_object($wp_admin_bar)) {
						return;
				}

				$node = $wp_admin_bar->get_node('my-account');
				if (!$node) {
						return;
				}

				$user = wp_get_current_user();
				$title = sprintf(
						'%s %s',
						esc_html__('Welcome,', 'webundso'),
						esc_html($user->display_name)
				);

				$wp_admin_bar->add_node([
						'id'    => 'my-account',
						'title' => $title,
				]);
		}
		

		/**
		 * Admin Footer Branding.
		 */
		public static function custom_admin_footer(): string
		{
				$brand_name = defined('WUS_BRAND_NAME') ? WUS_BRAND_NAME : 'webundso GmbH';
				$brand_url  = defined('WUS_BRAND_URL') ? WUS_BRAND_URL : 'https://www.webundso.ch';

				return sprintf(
						'<span id="footer-thankyou">%s <a href="%s" target="_blank" rel="noopener noreferrer">%s</a></span>.',
						esc_html__('Erstellt von', 'webundso'),
						esc_url($brand_url),
						esc_html($brand_name)
				);
		}
				
}
