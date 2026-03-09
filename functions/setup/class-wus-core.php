<?php
defined('ABSPATH') || exit;

/**
 * WUS_Core
 * Theme-Basics / Supports:
 * - title-tag, thumbnails, html5
 * - optionale Feed-Links (nur wenn Feeds nicht deaktiviert sind)
 * - Bildgrössen
 * - Block-/Widget-Editor Policy (project decision)
 *
 * Schalter:
 * - WUS_DISABLE_FEEDS (bool): wenn true, keine Feed-Links aktivieren
 */

class WUS_Core
{
		/**
		 * Boot: registriert Theme-Setup auf after_setup_theme.
		 */
		public static function init(): void
		{
				add_action('after_setup_theme', [__CLASS__, 'setup'], 10);
		}

		/**
		 * Setup: setzt theme_support und Policy-Entscheide.
		 */
		public static function setup(): void
		{
				// ---------------------------------------------------------------------
				// Core Supports
				// ---------------------------------------------------------------------

				/** Title Tag: WP managed <title>. */
				add_theme_support('title-tag');

				/** Post thumbnails: Featured Images aktivieren. */
				add_theme_support('post-thumbnails');

				/** HTML5 Markup: sauberes HTML für Formulare/Listen. */
				add_theme_support('html5', ['comment-list', 'comment-form', 'search-form']);

				/**
				 * Feed Links im Head:
				 * Nur aktivieren, wenn Feeds nicht global deaktiviert werden.
				 */
				$feeds_disabled = defined('WUS_DISABLE_FEEDS') && WUS_DISABLE_FEEDS;
				if (!$feeds_disabled) {
						add_theme_support('automatic-feed-links');
				}

				/**
				 * Post Formats:
				 * Legacy-Feature. Nur aktivieren, wenn du es wirklich nutzt.
				 * (Standard: aus)
				 */
				// add_theme_support('post-formats', [
				//     'aside', 'gallery', 'link', 'image', 'quote',
				//     'status', 'video', 'audio', 'chat',
				// ]);

				// ---------------------------------------------------------------------
				// Image sizes
				// ---------------------------------------------------------------------

				/**
				 * Mini Thumb: z.B. Teaser-Listen/Icons.
				 * Hinweis: Names nicht “large” nennen, weil WP das schon hat.
				 */
				add_image_size('wus-mini', 100, 100, true);

				/**
				 * Projekt “Large”: Beispielgrösse für Content/Teaser.
				 * Crop = true/false je nach Design-Entscheid.
				 */
				add_image_size('wus-large', 800, 600, true);

				// ---------------------------------------------------------------------
				// Editor / Block Policy
				// ---------------------------------------------------------------------

				/**
				 * Block Templates (FSE):
				 * Classic Theme: meist irrelevant, aber als Sicherheitsleine ok.
				 */
				remove_theme_support('block-templates');

				/**
				 * Widgets Block Editor:
				 * Deaktiviert den Block-Widgets Screen (zurück zu Classic Widgets).
				 */
				remove_theme_support('widgets-block-editor');

				// Optional: Editor Style (wenn du’s wirklich pflegst)
				// add_editor_style('editor-style.css');
		}
}
