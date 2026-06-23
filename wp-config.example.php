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
 * Möglich:
 * local | development | staging | production
 *
 * Empfehlung:
 * - via Server-Env setzen (z.B. WP_ENVIRONMENT_TYPE)
 * - Fallback hier auf production
 */
 
 // define('WP_ENVIRONMENT_TYPE', 'local'); 
 
if (!defined('WP_ENVIRONMENT_TYPE')) {
  define('WP_ENVIRONMENT_TYPE', getenv('WP_ENVIRONMENT_TYPE') ?: 'production');
}

$is_local = WP_ENVIRONMENT_TYPE === 'local';
$is_dev   = WP_ENVIRONMENT_TYPE === 'development';
$is_stage = WP_ENVIRONMENT_TYPE === 'staging';
$is_prod  = WP_ENVIRONMENT_TYPE === 'production';


/* =============================================================
 * Debug / Developer Experience
 * =========================================================== */

/**
 * Debug nur lokal / dev aktiv
 */
define('WP_DEBUG', $is_local || $is_dev);

/**
 * Debug-Log:
 * - local/dev: an
 * - staging: optional (standard: aus)
 * - prod: aus
 */
define('WP_DEBUG_LOG', $is_local || $is_dev ? true : false);

/**
 * Debug-Ausgabe im Browser:
 * - local: an
 * - sonst: aus (kein Leak)
 */
define('WP_DEBUG_DISPLAY', $is_local ? true : false);
@ini_set('display_errors', $is_local ? '1' : '0');

/**
 * Unminified Assets (hilfreich beim Debugging)
 */
define('SCRIPT_DEBUG', $is_local || $is_dev);

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
if ($is_prod || $is_stage) {
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
define('WUS_TOKEN_SHEET',        $is_local); // Design Token Sheet nur lokal
define('WUS_DEBUG_BLOCK_REGISTER', false);

// ── 9) UIkit ──────────────────────────────────────────────────
define('WUS_UIKIT_VERSION', '3.17.11');

