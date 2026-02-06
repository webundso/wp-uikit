<?php
defined('ABSPATH') || exit;

/**
 * WUS_Content
 * Content-/Frontend-nahe Regeln und Helper.
 *
 * Enthält:
 * - Search: Post-Types für die Suche erweitern
 * - Feeds: Post-Types in Feed-Requests ergänzen
 * - Excerpt: Standard-Länge
 * - Uploads: optionale Mimes (VCF/SVG) mit Cap- und Sanitizer-Check
 * - Captions: saubereres Caption-Markup
 * - Comments: optional global deaktivieren + Attachments immer ohne Comments
 * - Helper: Sidebar-Auswahl, Current-URL, Excerpt by ID
 */
class WUS_Content
{
	/**
	 * Bootstrapping: registriert alle Hooks/Filter dieses Moduls.
	 *
	 * Wirkung:
	 * - aktiviert die in dieser Klasse definierten Anpassungen global
	 * - respektiert Feature-Toggles (z.B. WUS_DISABLE_COMMENTS, SVG Upload Switches)
	 */
	public static function init(): void
	{
		add_action('pre_get_posts', [__CLASS__, 'search_all']);
		add_filter('request', [__CLASS__, 'custom_feed_request']);

		add_filter('excerpt_length', [__CLASS__, 'excerpt_length']);
		add_filter('upload_mimes', [__CLASS__, 'custom_upload_mimes']);
		add_filter('img_caption_shortcode', [__CLASS__, 'cleaner_captions'], 10, 3);

		// Comments optional global disable
		if (defined('WUS_DISABLE_COMMENTS') && WUS_DISABLE_COMMENTS) {
			add_filter('comments_open', '__return_false', 20, 2);
			add_filter('pings_open', '__return_false', 20, 2);
			add_filter('comments_number', [__CLASS__, 'comments_number_zero'], 20, 2);

			// Attachments sicher ohne Comments (falls Plugins/Code das wieder aktiviert)
			add_filter('comments_open', [__CLASS__, 'filter_media_comment_status'], 10, 2);
		}
	}

	/**
	 * Sidebar-Auswahl je nach Kontext.
	 *
	 * Wirkung:
	 * - Blog-Kontext (Posts, Archive, Blog-Index) => Blog Sidebar
	 * - Default => Page Sidebar
	 *
	 * Templates fragen nur diese Methode ab, statt eigene Logik zu duplizieren.
	 */
	public static function get_sidebar_id(): string
	{
		// Blog-Kontext: Einzelbeitrag oder Blog-Listen/Archive
		if (
			is_singular('post') ||
			is_home() ||
			is_archive() ||
			is_category() ||
			is_tag() ||
			is_author() ||
			is_date()
		) {
			return 'wus-sidebar-blog';
		}

		// Default: normale Seiten
		return 'wus-sidebar-pages';
	}

	/**
	 * Search: erweitert die Suche auf mehrere Post Types.
	 *
	 * Wirkung:
	 * - betrifft nur die Main Query auf Frontend-Suchresultaten
	 * - setzt post_type auf WUS_SEARCH_POST_TYPES oder fallback ['post','page']
	 */
	public static function search_all($query): void
	{
		if (is_admin() || !$query->is_main_query() || !$query->is_search()) {
			return;
		}

		$post_types = defined('WUS_SEARCH_POST_TYPES') ? WUS_SEARCH_POST_TYPES : ['post', 'page'];
		$query->set('post_type', $post_types);
	}

	/**
	 * Feed Requests: ergänzt post_type, wenn ein Feed angefragt wird.
	 *
	 * Wirkung:
	 * - wenn ?feed=rss2 etc. ohne post_type kommt, setzen wir post_type
	 * - nutzt WUS_SEARCH_POST_TYPES oder fallback ['post']
	 */
	public static function custom_feed_request($vars): array
	{
		if (isset($vars['feed']) && !isset($vars['post_type'])) {
			$vars['post_type'] = defined('WUS_SEARCH_POST_TYPES') ? WUS_SEARCH_POST_TYPES : ['post'];
		}

		return $vars;
	}

	/**
	 * Excerpt Länge (Wörter).
	 *
	 * Wirkung:
	 * - steuert WordPress' the_excerpt() Standardlänge
	 * - Achtung: Viele Plugins/Themes überschreiben das ebenfalls
	 */
	public static function excerpt_length($length): int
	{
		return 80;
	}

	/**
	 * Upload Mimes: erlaubt optionale Filetypes.
	 *
	 * Wirkung:
	 * - VCF: erlaubt .vcf Uploads, wenn WUS_ALLOW_VCF_UPLOAD true
	 * - SVG: erlaubt .svg/.svgz nur wenn:
	 *   - WUS_ALLOW_SVG_UPLOAD true
	 *   - User eingeloggt + hat Capability WUS_SVG_ALLOWED_CAPABILITY (default manage_options)
	 *   - optional: Sanitizer vorhanden, wenn WUS_SVG_REQUIRE_SANITIZER true
	 */
	public static function custom_upload_mimes($existing_mimes = []): array
	{
		// VCF
		if (defined('WUS_ALLOW_VCF_UPLOAD') && WUS_ALLOW_VCF_UPLOAD) {
			$existing_mimes['vcf'] = 'text/vcard';
		}

		// SVG (riskant!)
		if (defined('WUS_ALLOW_SVG_UPLOAD') && WUS_ALLOW_SVG_UPLOAD) {
			$cap = defined('WUS_SVG_ALLOWED_CAPABILITY') ? WUS_SVG_ALLOWED_CAPABILITY : 'manage_options';

			// Nur erlauben, wenn User die Capability hat
			if (is_user_logged_in() && current_user_can($cap)) {

				// Optional: Sanitizer erzwingen
				if (defined('WUS_SVG_REQUIRE_SANITIZER') && WUS_SVG_REQUIRE_SANITIZER) {
					// Beispiel-Check: an dein Sanitizer-Setup anpassen
					$sanitizer_ok = function_exists('enshrined\svgSanitize\Sanitizer') || function_exists('svg_sanitizer_init');
					if (!$sanitizer_ok) {
						return $existing_mimes;
					}
				}

				$existing_mimes['svg']  = 'image/svg+xml';
				$existing_mimes['svgz'] = 'image/svg+xml';
			}
		}

		return $existing_mimes;
	}

	/**
	 * Comments: erzwingt 0 als Kommentar-Anzahl.
	 *
	 * Wirkung:
	 * - kosmetisch: zeigt im Theme/Backend keine Kommentar-Zahl an
	 * - aktiv nur, wenn WUS_DISABLE_COMMENTS true ist und init() den Filter gesetzt hat
	 */
	public static function comments_number_zero($count, $post_id): int
	{
		return 0;
	}

	/**
	 * Comments: Attachments sollen nie Kommentare erlauben.
	 *
	 * Wirkung:
	 * - falls Comments global deaktiviert sind, bleibt Attachment-Comment-Status sicher aus
	 */
	public static function filter_media_comment_status($open, $post_id): bool
	{
		$post = get_post($post_id);

		if ($post && $post->post_type === 'attachment') {
			return false;
		}

		return (bool) $open;
	}

	/**
	 * Cleaner Captions: ersetzt das Standard-Caption-Markup.
	 *
	 * Wirkung:
	 * - gibt <figure> / <figcaption> statt div/p Mischmasch aus
	 * - im Feed wird das originale Markup nicht angefasst
	 */
	public static function cleaner_captions($output, $attr, $content): string
	{
		if (is_feed()) {
			return $output;
		}

		$defaults = [
			'id'      => '',
			'align'   => 'alignnone',
			'width'   => '',
			'caption' => '',
		];

		$attr = shortcode_atts($defaults, $attr);

		if (1 > (int) $attr['width'] || empty($attr['caption'])) {
			return $content;
		}

		$attributes = (!empty($attr['id']) ? ' id="' . esc_attr($attr['id']) . '"' : '');
		$attributes .= ' class="wp-caption ' . esc_attr($attr['align']) . '"';

		$out  = '<figure' . $attributes . '>';
		$out .= do_shortcode($content);
		$out .= '<figcaption class="wp-caption-text"><p>' . esc_html($attr['caption']) . '</p></figcaption>';
		$out .= '</figure>';

		return $out;
	}

	/**
	 * Current URL Helper (secure-ish).
	 *
	 * Wirkung:
	 * - baut die aktuelle URL aus Host + Request URI
	 *
	 * Hinweis:
	 * - HTTP_HOST ist nicht 100% vertrauenswuerdig (Header kann manipuliert werden)
	 * - nutze das nur für interne Zwecke (z.B. Return-URL), nicht als Security-Entscheidungsgrundlage
	 */
	public static function get_current_url(): string
	{
		$protocol = is_ssl() ? 'https' : 'http';
		$host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
		$uri  = isset($_SERVER['REQUEST_URI']) ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'])) : '';

		return esc_url_raw($protocol . '://' . $host . $uri);
	}

	/**
	 * Excerpt by Post ID (Plaintext, gekürzt).
	 *
	 * Wirkung:
	 * - liest post_content, entfernt HTML + Shortcodes
	 * - schneidet nach $length Wörtern ab und hängt … an
	 * - gibt ein <p> zurück (fertiges HTML)
	 */
	public static function get_excerpt_by_id($post_id, $length = 35): string
	{
		$the_post = get_post($post_id);
		if (!$the_post) {
			return '';
		}

		$text  = (string) $the_post->post_content;
		$text  = strip_tags(strip_shortcodes($text));
		$words = preg_split('/\s+/', trim($text)) ?: [];

		if (count($words) > (int) $length) {
			$words = array_slice($words, 0, (int) $length);
			$words[] = '…';
		}

		return '<p>' . esc_html(implode(' ', $words)) . '</p>';
	}
	
	/**
	 * Related Posts (über Tags).
	 *
	 * Wirkung:
	 * - Gibt eine kleine Liste "Ähnliche Beiträge" aus.
	 * - Matcht Posts, die mindestens ein Tag mit dem aktuellen Post teilen.
	 *
	 * @param int $limit Anzahl Beiträge (Default 3)
	 */
	public static function related_posts(int $limit = 3): void
	{
		if (!is_singular('post')) {
			return;
		}
	
		$post_id = get_the_ID();
		if (!$post_id) {
			return;
		}
	
		$tags = wp_get_post_tags($post_id, ['fields' => 'ids']);
		if (empty($tags)) {
			return;
		}
	
		$q = new WP_Query([
			'post_type'           => 'post',
			'posts_per_page'      => max(1, $limit),
			'post__not_in'        => [$post_id],
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'tag__in'             => $tags,
		]);
	
		if (!$q->have_posts()) {
			wp_reset_postdata();
			return;
		}
	
		echo '<div class="wus-related-posts uk-margin-large-top">';
		echo '<h4 class="uk-h4">' . esc_html__('Ähnliche Beiträge', 'webundso') . '</h4>';
		echo '<ul class="uk-list uk-list-divider">';
	
		while ($q->have_posts()) {
			$q->the_post();
	
			$rel_id = get_the_ID();
			$title  = get_the_title($rel_id);
			$link   = get_permalink($rel_id);
	
			echo '<li class="related-post">';
			echo '<a href="' . esc_url($link) . '">' . esc_html($title) . '</a>';
	
			// Optional: Byline nur wenn Template Part existiert
			$byline_part = locate_template('parts/content-byline.php');
			if (!empty($byline_part)) {
				get_template_part('parts/content', 'byline');
			}
	
			echo '</li>';
		}
	
		echo '</ul>';
		echo '</div>';
	
		wp_reset_postdata();
	}

}
