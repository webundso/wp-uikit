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



