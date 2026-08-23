<?php
/**
 * wp-config.example.php
 *
 * Vorlage für neue Projekte.
 * Diese Datei wird NICHT automatisch geladen.
 * Relevante Teile in die echte wp-config.php kopieren.
 *
 * Ziel:
 * - klare Environments
 * - sauberes Debugging
 * - sichere Defaults
 * - kein Wildwuchs im Backend
 */

/* =============================================================
 * Environment
 * =========================================================== */

/**
 * Nur zwei Environments: development | production
 *
 * Empfehlung:
 * - via Server-Env setzen (z.B. WP_ENVIRONMENT_TYPE)
 * - Fallback hier auf production
 */
 
// define( 'WP_ENVIRONMENT_TYPE', 'development' );

 
if (!defined('WP_ENVIRONMENT_TYPE')) {
  define('WP_ENVIRONMENT_TYPE', getenv('WP_ENVIRONMENT_TYPE') ?: 'production');
}

$is_dev  = WP_ENVIRONMENT_TYPE !== 'production';
$is_prod = !$is_dev;


/* =============================================================
 * Debug / Developer Experience
 * =========================================================== */

/**
 * Manueller Override: Error-Log gezielt auf Live aktivieren, OHNE die ganze
 * Seite in den Dev-Modus zu schalten (z.B. akuten Bug eingrenzen).
 * Schreibt NUR ins Log, zeigt NICHTS im Browser. Nach Gebrauch wieder
 * auskommentieren.
 */
// define('WUS_FORCE_DEBUG_LOG', true);

$force_debug_log = defined('WUS_FORCE_DEBUG_LOG') && WUS_FORCE_DEBUG_LOG;

/**
 * Debug: an bei development, oder wenn WUS_FORCE_DEBUG_LOG gesetzt ist
 */
define('WP_DEBUG', $is_dev || $force_debug_log);

/**
 * Debug-Log (Datei): gleiche Bedingung wie WP_DEBUG
 */
define('WP_DEBUG_LOG', $is_dev || $force_debug_log);

/**
 * Debug-Ausgabe im Browser: NIE bei force_debug_log, Live bleibt visuell sauber
 */
define('WP_DEBUG_DISPLAY', $is_dev);
@ini_set('display_errors', $is_dev ? '1' : '0');

/**
 * Unminified Assets (hilfreich beim Debugging)
 */
define('SCRIPT_DEBUG', $is_dev);

/**
 * Query Debug (nur bei gezielter Analyse aktivieren)
 */
define('SAVEQUERIES', false);


/* =============================================================
 * Updates / Hardening
 * =========================================================== */

/**
 * Kein Theme/Plugin-Editor im Backend
 * (Security + saubere Workflows)
 */
define('DISALLOW_FILE_EDIT', true);

/**
 * Optional: Updates komplett per Code/CI erzwingen
 * Vorsicht auf Shared Hosting.
 */
// define('DISALLOW_FILE_MODS', $is_prod ? true : false);

/**
 * Core Updates:
 * - minor automatisch ok
 * - major manuell
 */
define('WP_AUTO_UPDATE_CORE', 'minor');

/**
 * Admin immer über HTTPS (nur wenn sicher vorhanden)
 */
if ($is_prod) {
  define('FORCE_SSL_ADMIN', true);
}


/* =============================================================
 * Content Editing Defaults
 * =========================================================== */

/**
 * Revisionen begrenzen
 */
define('WP_POST_REVISIONS', 15);

/**
 * Autosave Intervall (Sekunden)
 */
define('AUTOSAVE_INTERVAL', 120);

/**
 * Papierkorb-Leerung (Tage)
 */
define('EMPTY_TRASH_DAYS', 14);


/* =============================================================
 * Cron / Performance
 * =========================================================== */

/**
 * Wenn echter Server-Cron existiert (empfohlen):
 * WP-Cron deaktivieren.
 */
// define('DISABLE_WP_CRON', true);


/* =============================================================
 * Memory (nur wenn nötig)
 * =========================================================== */

/**
 * Nur setzen, wenn Server/Hosting es erlaubt.
 */
// define('WP_MEMORY_LIMIT', '256M');
// define('WP_MAX_MEMORY_LIMIT', '512M');


/* =============================================================
 * Cache / Media (optional)
 * =========================================================== */

/**
 * Nur aktivieren, wenn wirklich ein Cache genutzt wird
 * (Plugin oder Server).
 */
// define('WP_CACHE', true);

/**
 * Upload-Limits (nicht überall wirksam)
 */
// define('WP_MAX_UPLOAD_SIZE', 64 * 1024 * 1024); // 64 MB

/* =============================================================
 * WUS Theme Konfiguration
 * Alle Werte hier setzen — webundso.php greift nur als Fallback.
 * =========================================================== */

// ── 1) Branding ───────────────────────────────────────────────
define('WUS_BRAND_NAME',          'Projektname GmbH');
define('WUS_BRAND_URL',           'https://www.example.ch');
define('WUS_BRAND_SUPPORT_EMAIL', 'info@example.ch');
define('WUS_LOGO_ASSET',          '/assets/images/logo.svg');

// ── 2) Navigation ─────────────────────────────────────────────
define('WUS_TOPNAV_MODE', 'dropdown'); // 'dropdown' | 'mega'
define('WUS_NAV_SEARCH',  true);

// ── 3) Footer ─────────────────────────────────────────────────
define('WUS_FOOTER_COL1', 'auto'); // 'auto' | 'widgets' | 'html'
define('WUS_FOOTER_COL2', 'auto');
define('WUS_FOOTER_COL3', 'auto');

// ── 4) Blog ───────────────────────────────────────────────────
define('WUS_BLOG_LOOP_VARIANT',   'grid'); // 'grid' | 'list'
define('WUS_BLOG_POSTS_PER_PAGE', 10);
define('WUS_BLOG_LOAD_MORE',      true);

// ── 5) Feature Toggles ────────────────────────────────────────
define('WUS_DISABLE_FEEDS',    true);
define('WUS_DISABLE_COMMENTS', true);
define('WUS_DISABLE_ADMIN_BAR', false); // false | true (Nicht-Admins) | 'all'
define('WUS_DISABLE_WPAUTOP',  false);
define('WUS_PAGE_SHOW_TITLE',  true);

// ── 6) Uploads ────────────────────────────────────────────────
define('WUS_ALLOW_VCF_UPLOAD', true);
define('WUS_ALLOW_SVG_UPLOAD', false); // lieber Plugin "Safe SVG" nutzen

// ── 7) Editor / Gutenberg ─────────────────────────────────────
define('WUS_DISABLE_REMOTE_PATTERNS', true);
define('WUS_DISABLE_CORE_PATTERNS',   true);
define('WUS_EDITOR_DISABLE_FULLSCREEN', true);
define('WUS_EDITOR_REMOVE_FORMATS',   true);
define('WUS_EDITOR_REMOVE_BLOCK_STYLES', true);

// ── 8) Dev Tools ──────────────────────────────────────────────
define('WUS_TOKEN_SHEET',          $is_dev); // Design Token Sheet nur in Dev
define('WUS_DEBUG_BLOCK_REGISTER', $is_dev); // Block-Registrierung ins Log
define('WUS_SCRIPT_DEBUG',         $is_dev); // Unminified UIkit Assets in Dev

// ── 9) UIkit ──────────────────────────────────────────────────
define('WUS_UIKIT_VERSION', '3.25.20');

