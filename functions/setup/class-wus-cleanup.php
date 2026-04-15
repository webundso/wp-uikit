<?php
defined('ABSPATH') || exit;

/**
 * WUS_Cleanup
 * Entfernt WP-Ballast und reduziert Angriffsfläche/Noise:
 * - Emojis raus
 * - Head aufräumen (Generator, Shortlink, Rel-Links, Feed-Links)
 * - wpautop optional global deaktivieren
 * - make_clickable in Comments deaktivieren
 * - Self-Pings entfernen
 * - Feeds optional deaktivieren (mit sauberer Fehlermeldung)
 *
 * Schalter:
 * - WUS_DISABLE_WPAUTOP (bool): wpautop global entfernen (default false, riskant)
 * - WUS_DISABLE_FEEDS  (bool): alle Feeds deaktivieren (default true)
 */

class WUS_Cleanup
{
		/**
		 * Boot: hängt Cleanup an init (Priority 20), damit andere Basics vorher laufen dürfen.
		 */
		public static function init(): void
		{
				add_action('init', [__CLASS__, 'hooks'], 20);
		}

		/**
		 * Registriert/entfernt Hooks gemäss Policy und Flags.
		 */
		public static function hooks(): void
		{
				// ---------------------------------------------------------------------
				// Emojis
				// Effekt: weniger Requests/Output, weniger Legacy-Kram.
				// ---------------------------------------------------------------------
				self::disable_emojis();

				// ---------------------------------------------------------------------
				// WP Head Cleanup
				// Effekt: entfernt unnötige Meta/Links im <head>.
				// ---------------------------------------------------------------------
				remove_action('wp_head', 'feed_links_extra', 3);
				remove_action('wp_head', 'feed_links', 2);

				remove_action('wp_head', 'rsd_link');
				remove_action('wp_head', 'wp_generator');
				remove_action('wp_head', 'wp_shortlink_wp_head');

				// Legacy/harmlos: remove_action ist safe, auch wenn Hooks fehlen.
				remove_action('wp_head', 'index_rel_link');
				remove_action('wp_head', 'parent_post_rel_link');
				remove_action('wp_head', 'start_post_rel_link', 10, 0);
				remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

				// Generator auch aus Headern entfernen
				add_filter('the_generator', '__return_empty_string');

				// ---------------------------------------------------------------------
				// wpautop (optional)
				// Effekt: entfernt automatische <p>/<br> Einfügungen (meist Content-Falle).
				// ---------------------------------------------------------------------
				if (defined('WUS_DISABLE_WPAUTOP') && WUS_DISABLE_WPAUTOP) {
						remove_filter('the_content', 'wpautop');
						remove_filter('the_excerpt', 'wpautop');
				}

				// ---------------------------------------------------------------------
				// Comments: Auto-Linking deaktivieren
				// Effekt: kein automatisches “make_clickable” in Kommentartexten.
				// ---------------------------------------------------------------------
				remove_filter('comment_text', 'make_clickable', 9);

				// ---------------------------------------------------------------------
				// Self-Pingbacks deaktivieren
				// Effekt: keine Pingbacks auf eigene URLs.
				// ---------------------------------------------------------------------
				add_action('pre_ping', [__CLASS__, 'self_ping']);

				// ---------------------------------------------------------------------
				// Feeds deaktivieren (optional)
				// Effekt: Feed-Endpunkte liefern Fehlerseite statt XML.
				// ---------------------------------------------------------------------
				if (defined('WUS_DISABLE_FEEDS') && WUS_DISABLE_FEEDS) {
						add_action('do_feed',      [__CLASS__, 'disable_feed'], 1);
						add_action('do_feed_rdf',  [__CLASS__, 'disable_feed'], 1);
						add_action('do_feed_atom', [__CLASS__, 'disable_feed'], 1);
						add_action('do_feed_rss',  [__CLASS__, 'disable_feed'], 1);
						add_action('do_feed_rss2', [__CLASS__, 'disable_feed'], 1);
				}

				// ---------------------------------------------------------------------
				// Openverse deaktivieren
				// Effekt: entfernt den Openverse-Tab im Medien-Dialog (externe Mediensuche).
				// ---------------------------------------------------------------------
				add_filter('media_view_settings', static function (array $settings): array {
						$settings['openverse'] = ['status' => false];
						return $settings;
				});
		}

		/**
		 * Entfernt Emoji-Scripts/Styles und den TinyMCE Plugin-Eintrag.
		 */
		private static function disable_emojis(): void
		{
				remove_action('admin_print_styles', 'print_emoji_styles');
				remove_action('wp_head', 'print_emoji_detection_script', 7);
				remove_action('admin_print_scripts', 'print_emoji_detection_script');
				remove_action('wp_print_styles', 'print_emoji_styles');

				remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
				remove_filter('the_content_feed', 'wp_staticize_emoji');
				remove_filter('comment_text_rss', 'wp_staticize_emoji');

				add_filter('tiny_mce_plugins', [__CLASS__, 'disable_emoji_tinymce']);
				add_filter('emoji_svg_url', '__return_false');
		}

		/**
		 * Entfernt "wpemoji" aus TinyMCE Plugins.
		 */
		public static function disable_emoji_tinymce($plugins): array
		{
				if (!is_array($plugins)) {
						return [];
				}

				return array_values(array_diff($plugins, ['wpemoji']));
		}

		/**
		 * Feed deaktivieren: liefert eine klare Message und beendet Request.
		 * Response: 410 Gone (bewusst abgeschaltet).
		 */
		public static function disable_feed(): void
		{
				$message = sprintf(
						__('No feed available, please visit our <a href="%s">homepage</a>!', 'webundso'),
						esc_url(home_url('/'))
				);

				wp_die(
						wp_kses_post($message),
						'',
						['response' => 410]
				);
		}

		/**
		 * Entfernt Links, die auf die eigene Domain zeigen (Self-Pings).
		 */
		public static function self_ping(array &$links): void
		{
				$home = home_url('/');

				foreach ($links as $i => $link) {
						if (strpos($link, $home) === 0) {
								unset($links[$i]);
						}
				}
		}
}
