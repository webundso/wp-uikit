<?php
defined('ABSPATH') || exit;

/**
 * webundso.php
 * Projekt-Konfiguration + Feature-Switches (sichere Defaults).
 *
 * Ziel:
 * - neue Projekte schnell starten
 * - Defaults zentral halten
 * - pro Projekt via wp-config.php oder früh geladenem Must-Use Plugin überschreibbar
 *
 * Regel:
 * - hier nur Konfig + kleine Helpers, keine Hook-Logik
 */

/* -----------------------------------------------------------------------------
 * 1) Environment
 * -------------------------------------------------------------------------- */

/** Umgebung: steuert z.B. Debug-Verhalten/Logging (local|development|staging|production). */
if (!defined('WUS_ENV')) {
    define('WUS_ENV', 'production');
}


/* -----------------------------------------------------------------------------
 * 2) Branding / Projekt-Defaults
 * -------------------------------------------------------------------------- */

/** Markenname: für Footer, Admin-Texte, Mails. */
if (!defined('WUS_BRAND_NAME')) {
    define('WUS_BRAND_NAME', 'webundso GmbH');
}

/** Marken-URL: Default-Linkziel für Branding/Signaturen. */
if (!defined('WUS_BRAND_URL')) {
    define('WUS_BRAND_URL', 'https://www.webundso.ch');
}

/** Support-E-Mail: Default-Absender/Reply-To in Projekt-Mails. */
if (!defined('WUS_BRAND_SUPPORT_EMAIL')) {
    define('WUS_BRAND_SUPPORT_EMAIL', 'support@webundso.ch');
}

/**
 * Header Logo (optional)
 * - Wenn gesetzt, wird statt Text ein Bild aus /assets verwendet.
 * - Pfad relativ zum Theme (stylesheet), z.B. '/assets/images/logo.svg'
 */
if (!defined('WUS_LOGO_ASSET')) {
    define('WUS_LOGO_ASSET', '/assets/images/logo.svg');
}

/**
 * Logo Alt Text (Fallback: Brand Name)
 */
if (!defined('WUS_LOGO_ALT')) {
    define('WUS_LOGO_ALT', WUS_BRAND_NAME);
}

/**
 * Footer Spalten-Modus (pro Spalte unabhängig):
 * - 'auto'    (default) = Widgets wenn Sidebar befüllt, sonst HTML-Fallback
 * - 'widgets'           = immer Widgets
 * - 'html'              = immer HTML-Fallback
 *
 * HTML-Fallback direkt in footer.php anpassen.
 */
if (!defined('WUS_FOOTER_COL1')) {
    define('WUS_FOOTER_COL1', 'auto');
}
if (!defined('WUS_FOOTER_COL2')) {
    define('WUS_FOOTER_COL2', 'auto');
}
if (!defined('WUS_FOOTER_COL3')) {
    define('WUS_FOOTER_COL3', 'auto');
}

/**
 * Page Title Anzeige
 * - true  = Titel anzeigen (Default)
 * - false = Titel global ausblenden
 */
if (!defined('WUS_PAGE_SHOW_TITLE')) {
    define('WUS_PAGE_SHOW_TITLE', true);
}

/**
 * Blog Loop Variant:
 * - 'grid' = parts/loop-blog-grid.php
 * - 'list' = parts/loop-blog.php
 */
if (!defined('WUS_BLOG_LOOP_VARIANT')) {
  define('WUS_BLOG_LOOP_VARIANT', 'list');
}

/**
 * Blog Posts per Page:
 * - Anzahl Posts beim ersten Laden UND pro AJAX-Nachladen.
 */
if (!defined('WUS_BLOG_POSTS_PER_PAGE')) {
    define('WUS_BLOG_POSTS_PER_PAGE', 10);
}

/**
 * Blog Load More Button:
 * - true  = "Mehr laden" Button statt Pagination
 * - false = normale WP-Pagination
 */
if (!defined('WUS_BLOG_LOAD_MORE')) {
    define('WUS_BLOG_LOAD_MORE', true);
}


/* -----------------------------------------------------------------------------
 * 3) Assets / Libraries
 * -------------------------------------------------------------------------- */

/** UIkit Version: dient als zentrales Single-Source-of-Truth für Enqueue/CDN-Pfade. */
if (!defined('WUS_UIKIT_VERSION')) {
    define('WUS_UIKIT_VERSION', '3.17.11');
}

/** Script Debug: true = unminified Assets bevorzugen (Dev/Debug). */
if (!defined('WUS_SCRIPT_DEBUG')) {
    define('WUS_SCRIPT_DEBUG', false);
}


/* -----------------------------------------------------------------------------
 * 4) Feature Toggles (sichere Defaults)
 * -------------------------------------------------------------------------- */

/** Feeds deaktivieren: schaltet RSS/Atom Endpoints ab (weniger Angriffsfläche/Noise). */
if (!defined('WUS_DISABLE_FEEDS')) {
    define('WUS_DISABLE_FEEDS', true);
}

/** Kommentare deaktivieren: entfernt Kommentar-Funktionalität im Front-/Backend. */
if (!defined('WUS_DISABLE_COMMENTS')) {
    define('WUS_DISABLE_COMMENTS', true);
}

/**
 * Admin-Bar deaktivieren: blendet die WP-Admin-Bar im Frontend aus.
 * Empfehlung: eher rollenbasiert lösen (Admins sehen sie, Kunden nicht).
 */
if (!defined('WUS_DISABLE_ADMIN_BAR')) {
    define('WUS_DISABLE_ADMIN_BAR', false);
}

/**
 * wpautop deaktivieren: entfernt automatische <p>/<br> Einfügungen.
 * Default OFF, weil globales Entfernen oft Content kaputt macht.
 */
if (!defined('WUS_DISABLE_WPAUTOP')) {
    define('WUS_DISABLE_WPAUTOP', false);
}

/**
 * REST API deaktivieren: NICHT im Theme global killen.
 * Wenn du es brauchst: gezielt (Route/Auth) und eher pluginseitig.
 */
// if (!defined('WUS_DISABLE_REST_API')) {
//     define('WUS_DISABLE_REST_API', false);
// }


/* -----------------------------------------------------------------------------
 * 5) Search Defaults
 * -------------------------------------------------------------------------- */

/** Such-Post-Types: welche Inhalte in der WP-Suche berücksichtigt werden. */
if (!defined('WUS_SEARCH_POST_TYPES')) {
    define('WUS_SEARCH_POST_TYPES', ['post', 'page']);
}


/* -----------------------------------------------------------------------------
 * 6) Uploads (Security: SVG , Plugin Safe SVG verwenden
 * -------------------------------------------------------------------------- */

/** VCF Upload erlauben: ermöglicht .vcf (Kontakte) im Medien-Upload. */
if (!defined('WUS_ALLOW_VCF_UPLOAD')) {
    define('WUS_ALLOW_VCF_UPLOAD', true);
}

/**
 * SVG Upload Policy
 *
 * Empfehlung:
 * - Wenn wir das Plugin "Safe SVG" nutzen: Theme-seitig SVG NICHT freischalten.
 *   Das Plugin übernimmt Allowlist + Sanitizing.
 * - Wenn wir KEIN Plugin nutzen: WUS_ALLOW_SVG_UPLOAD true setzen und Sanitizer erzwingen.
 */
if (!defined('WUS_ALLOW_SVG_UPLOAD')) {
  define('WUS_ALLOW_SVG_UPLOAD', false);
}

/**
 * Theme SVG Regeln (nur relevant wenn WUS_ALLOW_SVG_UPLOAD = true).
 * Bei Nutzung von "Safe SVG" werden diese Werte vom Theme nicht verwendet.
 */
if (!defined('WUS_SVG_ALLOWED_CAPABILITY')) {
  // Default: nur Admins
  define('WUS_SVG_ALLOWED_CAPABILITY', 'manage_options');
}

if (!defined('WUS_SVG_REQUIRE_SANITIZER')) {
  define('WUS_SVG_REQUIRE_SANITIZER', true);
}


/* -----------------------------------------------------------------------------
 * 7) Gutenberg / Editor Assets
 * -------------------------------------------------------------------------- */

/** Blocks Verzeichnis: Pfad zu registrierten Custom Blocks (Build-Artefakte). */
 
if (!defined('WUS_BLOCKS_DIR')) {
    define('WUS_BLOCKS_DIR', get_stylesheet_directory() . '/assets/blocks');
}

/**
 * Blocks URI: URL Pendant zu WUS_BLOCKS_DIR
 */
if (!defined('WUS_BLOCKS_URI')) {
    define('WUS_BLOCKS_URI', get_stylesheet_directory_uri() . '/assets/blocks');
}

/** Remote Patterns deaktivieren: verhindert das Laden externer Pattern-Verzeichnisse. */
if (!defined('WUS_DISABLE_REMOTE_PATTERNS')) {
    define('WUS_DISABLE_REMOTE_PATTERNS', true);
}

/** Core Patterns deaktivieren: entfernt WP Core-Patterns (weniger Auswahl/Chaos). */
if (!defined('WUS_DISABLE_CORE_PATTERNS')) {
    define('WUS_DISABLE_CORE_PATTERNS', true);
}

/** Editor CSS enqueuen: lädt Theme/Gutenberg-Styles im Editor für WYSIWYG-Nähe. */
if (!defined('WUS_EDITOR_ENQUEUE_GUTENBERG_CSS')) {
    define('WUS_EDITOR_ENQUEUE_GUTENBERG_CSS', true);
}

/** Functions JS im Editor: lädt Editor-spezifische JS Helfer (nur falls genutzt). */
if (!defined('WUS_EDITOR_ENQUEUE_FUNCTIONS_JS')) {
    define('WUS_EDITOR_ENQUEUE_FUNCTIONS_JS', true);
}


/* -----------------------------------------------------------------------------
 * 8) ACF JSON (nur relevant wenn ACF aktiv ist)
 * -------------------------------------------------------------------------- */

/** ACF JSON Dir: Export/Import Pfad für Field Groups (child-theme-friendly). */
if (!defined('WUS_ACF_JSON_DIR')) {
    define('WUS_ACF_JSON_DIR', trailingslashit(get_stylesheet_directory()) . 'assets/acf-json');
}

/** ACF JSON Dir auto-create: legt den Ordner bei Bedarf automatisch an. */
if (!defined('WUS_ACF_JSON_AUTOCREATE_DIR')) {
    define('WUS_ACF_JSON_AUTOCREATE_DIR', true);
}


/* -----------------------------------------------------------------------------
 * 9) Editor UX / Einschränkungen
 * -------------------------------------------------------------------------- */

/** Fullscreen im Editor deaktivieren: Editor startet nicht im Fullscreen-Modus. */
if (!defined('WUS_EDITOR_DISABLE_FULLSCREEN')) {
    define('WUS_EDITOR_DISABLE_FULLSCREEN', true);
}

/** Block-Styles Logging: Debug/Analyse von Block-Styles im Editor. */
if (!defined('WUS_EDITOR_LOG_BLOCK_STYLES')) {
    define('WUS_EDITOR_LOG_BLOCK_STYLES', false);
}

/**
 * Panels entfernen: aktiviert das Entfernen definierter Sidebar-Panels.
 * Default: OFF. (Wenn ON, nutze WUS_EDITOR_PANELS_TO_REMOVE.)
 */
if (!defined('WUS_EDITOR_REMOVE_PANELS')) {
    define('WUS_EDITOR_REMOVE_PANELS', false);
}

/**
 * Panels to remove (Denylist): IDs von Panels, die ausgeblendet werden.
 * Beispiel-IDs: 'discussion-panel', 'post-excerpt', 'featured-image'
 */
if (!defined('WUS_EDITOR_PANELS_TO_REMOVE')) {
    define('WUS_EDITOR_PANELS_TO_REMOVE', [
        // 'discussion-panel',
        // 'taxonomy-panel-post_tag',
        // 'post-excerpt',
    ]);
}

/** Block-Styles entfernen: entfernt registrierte Block-Styles (Editor aufraeumen). */
if (!defined('WUS_EDITOR_REMOVE_BLOCK_STYLES')) {
    define('WUS_EDITOR_REMOVE_BLOCK_STYLES', true);
}

/** Block-Styles to remove: konkrete [block, style]-Paare, wenn Remove aktiv ist. */
if (!defined('WUS_EDITOR_BLOCK_STYLES_TO_REMOVE')) {
    define('WUS_EDITOR_BLOCK_STYLES_TO_REMOVE', [
        // ['core/image', 'rounded'],
    ]);
}

/** Formats entfernen: entfernt Richtext-Formate (z.B. Strikethrough, Code). */
if (!defined('WUS_EDITOR_REMOVE_FORMATS')) {
    define('WUS_EDITOR_REMOVE_FORMATS', true);
}

/** Formats to remove: Liste von Format-Slugs, wenn Remove aktiv ist. */
if (!defined('WUS_EDITOR_FORMATS_TO_REMOVE')) {
    define('WUS_EDITOR_FORMATS_TO_REMOVE', [
        // 'core/strikethrough',
        // 'core/code',
    ]);
}


/* -----------------------------------------------------------------------------
 * 10) Custom Block Kategorie + Icons
 * -------------------------------------------------------------------------- */

/** Block-Kategorie Slug: eigene Kategorie im Inserter. */
if (!defined('WUS_BLOCK_CATEGORY_SLUG')) {
    define('WUS_BLOCK_CATEGORY_SLUG', 'webundso-spezial');
}

/** Block-Kategorie Titel: sichtbarer Name im Inserter. */
if (!defined('WUS_BLOCK_CATEGORY_TITLE')) {
    define('WUS_BLOCK_CATEGORY_TITLE', 'webundso-Elemente');
}

/** Block-Icon Hintergrundfarbe (für ACF Block Icons etc.). */
if (!defined('WUS_BLOCK_ICON_BG')) {
    define('WUS_BLOCK_ICON_BG', '#ffffff');
}

/** Block-Icon Vordergrundfarbe (für ACF Block Icons etc.). */
if (!defined('WUS_BLOCK_ICON_FG')) {
    define('WUS_BLOCK_ICON_FG', '#f9a13a');
}


/* -----------------------------------------------------------------------------
 * 11) Block Control (Allow/Deny)
 * -------------------------------------------------------------------------- */


if (!defined('WUS_DEBUG_BLOCK_REGISTER')) {
    define('WUS_DEBUG_BLOCK_REGISTER', false);
}

/* -----------------------------------------------------------------------------
 * 12) Helpers (klein halten; keine Hook-Logik)
 * -------------------------------------------------------------------------- */

/** Support-Mail als String: z.B. für Templates/Notifications. */
function wus_support_email(): string
{
    return defined('WUS_BRAND_SUPPORT_EMAIL') ? WUS_BRAND_SUPPORT_EMAIL : '';
}

/**
 * Topnav Walker Default:
 * - 'dropdown' = normales UIkit Dropdown
 * - 'mega'     = Megamenu
 */
if (!defined('WUS_TOPNAV_MODE')) {
    define('WUS_TOPNAV_MODE', 'dropdown');
}

/**
 * Nav Suche:
 * - true  = Suchicon nach letztem Navpunkt, öffnet Drop mit Suchfeld
 * - false = kein Suchicon
 */
if (!defined('WUS_NAV_SEARCH')) {
    define('WUS_NAV_SEARCH', true);
}
/**
 * Design Token Sheet:
 * - true  = Token-Cheat-Sheet im Frontend (nur für eingeloggte Admins sichtbar)
 * - false = deaktiviert
 */
if (!defined('WUS_TOKEN_SHEET')) {
    define('WUS_TOKEN_SHEET', false);
}
