<?php
defined('ABSPATH') || exit;

/**
 * setup.php
 * Theme Setup Loader: lädt die Setup-Module und initialisiert sie beim Theme-Boot.
 *
 * Zweck:
 * - kleine, wartbare Entry-Point-Datei
 * - Module kapseln ihre Hooks selbst (init())
 */

// -----------------------------------------------------------------------------
// Module includes
// -----------------------------------------------------------------------------

/** Assets: Enqueue von CSS/JS, UIkit, Editor-Assets etc. */
require_once __DIR__ . '/setup/class-wus-assets.php';

/** Core: theme_support, image sizes, nav menus, grundlegende Defaults. */
require_once __DIR__ . '/setup/class-wus-core.php';

/** Cleanup: deaktiviert unnötigen WP-Ballast (feeds, emojis, comments, etc.) je nach Flags. */
require_once __DIR__ . '/setup/class-wus-cleanup.php';

/** Admin: Backend UX, Admin-Bar-Regeln, Admin-spezifische Anpassungen. */
require_once __DIR__ . '/setup/class-wus-admin.php';

/** Login: Login-Screen Branding, Login-Redirects, Security/UX rund um Auth. */
require_once __DIR__ . '/setup/class-wus-login.php';

/** Content: Content-Filters, Shortcodes, CPT/Tax (falls bei dir dort), Editor-Defaults. */
require_once __DIR__ . '/setup/class-wus-content.php';

/** Sidebars initalisieren */
require_once __DIR__ . '/setup/class-wus-widgets.php';

/** Token Sheet: Design-Token-Cheat-Sheet im Frontend (nur Admins). Schalter: WUS_TOKEN_SHEET */
require_once __DIR__ . '/setup/class-wus-token-sheet.php';


// -----------------------------------------------------------------------------
// Boot sequence
// -----------------------------------------------------------------------------

add_action('after_setup_theme', function (): void {

		/**
		 * Core: früh initialisieren (theme_support, defaults).
		 * Muss vor vielen anderen Setup-Schritten passieren.
		 */
		if (class_exists('WUS_Core')) {
				WUS_Core::init();
		}

		/**
		 * Admin / Login: nur aktiv, wenn Klassen vorhanden.
		 * Jede Klasse soll intern nur die benötigten Hooks registrieren.
		 */
		if (class_exists('WUS_Admin')) {
				WUS_Admin::init();
		}

		if (class_exists('WUS_Login')) {
				WUS_Login::init();
		}
		
		/**
		 * Menus: Menu-Locations + Walker/Helpers.
		 * Muss früh genug initialisieren, damit WP Menüs als “supported” erkennt.
		 */
		if (class_exists('WUS_Menus')) {
				WUS_Menus::init();
		}

		/**
		 * Content: zentrale Content-Regeln und Editor-nahe Anpassungen.
		 */
		if (class_exists('WUS_Content')) {
				WUS_Content::init();
		}

		/**
		 * Sidebars 
		 */
		if (class_exists('WUS_Widgets')) {
			WUS_Widgets::init();
		}

		/**
		 * Cleanup: kann nach Core laufen, weil es oft Features/Outputs entfernt.
		 */
		if (class_exists('WUS_Cleanup')) {
				WUS_Cleanup::init();
		}

		/**
		 * Assets: Enqueue-Setup kann am Schluss, weil es meist nur Hooks registriert.
		 */
		if (class_exists('WUS_Assets')) {
				WUS_Assets::init();
		}

		/**
		 * Token Sheet: Dev-Tool, nur wenn WUS_TOKEN_SHEET aktiv.
		 */
		if (class_exists('WUS_TokenSheet')) {
				WUS_TokenSheet::init();
		}

}, 5);
