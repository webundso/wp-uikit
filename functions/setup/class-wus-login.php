<?php
defined('ABSPATH') || exit;

/**
 * WUS_Login
 * Login Screen Branding:
 * - eigenes Logo
 * - Linkziel (Logo klick) definieren
 * - Alt-/Title-Text definieren
 *
 * Schalter/Defaults:
 * - Logo: /assets/images/login-logo.svg (aus Child/Stylesheet Theme)
 * - WUS_BRAND_URL / WUS_BRAND_NAME falls definiert
 */

class WUS_Login
{
		/**
		 * Boot: registriert Login Hooks.
		 */
		public static function init(): void
		{
				add_action('login_enqueue_scripts', [__CLASS__, 'enqueue_login_branding']);

				// Header-Link/Text
				add_filter('login_headerurl', [__CLASS__, 'login_headerurl']);
				add_filter('login_headertext', [__CLASS__, 'login_headertext']);
		}

		/**
		 * Login Branding CSS.
		 * Effekt: ersetzt WP-Logo durch dein SVG.
		 */
		public static function enqueue_login_branding(): void
		{
				// Projekt-Asset soll überschreibbar sein -> stylesheet
				$logo_url = get_stylesheet_directory_uri() . '/assets/images/login-logo.svg';

				// Optional: wenn Datei fehlt, nichts tun (fail-soft)
				// (Wenn du hart failen willst: check weglassen)
				$logo_abs = get_stylesheet_directory() . '/assets/images/login-logo.svg';
				if (!file_exists($logo_abs)) {
						return;
				}

				$logo_url = esc_url($logo_url);

				// wp_add_inline_style() statt direktem echo '<style>':
				// - CSP-kompatibler (kein inline style tag im HTML)
				// - WP-konform (Style wird korrekt in die Queue eingereiht)
				wp_register_style('wus-login', false);
				wp_enqueue_style('wus-login');
				wp_add_inline_style('wus-login', '
						.login #login { padding-top: 0; }
						.login h1 { width: 320px; height: 200px; }
						.login h1 a {
								background: url("' . $logo_url . '") 50% 50% no-repeat;
								background-size: contain;
								width: 320px;
								height: 200px;
						}
				');
		}

		/**
		 * Login Header URL.
		 * Effekt: Klick auf Logo führt zur Brand-URL (oder Site-Home als Fallback).
		 */
		public static function login_headerurl(): string
		{
				$url = defined('WUS_BRAND_URL') ? WUS_BRAND_URL : home_url('/');
				return esc_url($url);
		}

		/**
		 * Login Header Text.
		 * Effekt: Tooltip/Screenreader-Text am Login-Logo.
		 */
		public static function login_headertext(): string
		{
				$name = defined('WUS_BRAND_NAME') ? WUS_BRAND_NAME : get_bloginfo('name');
				return esc_html($name);
		}
}
