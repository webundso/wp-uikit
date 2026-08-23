<?php
defined('ABSPATH') || exit;

///**
// * gutenberg.php
// * Gutenberg + ACF Blocks Bootstrap:
// * - Editor CSS/JS laden
// * - Custom Block Kategorie registrieren
// * - ACF Blocks aus /assets/blocks/*/block.json auto-registrieren
// * - Patterns optional deaktivieren
// * - Allowed Blocks per Allow/Deny Mode steuern
// */


/* -----------------------------------------------------------------------------
 * 1) Editor Assets
 * -------------------------------------------------------------------------- */

add_action('enqueue_block_editor_assets', function (): void {

    /**
     * Editor CSS: sorgt für “WYSIWYG näher dran” im Backend.
     * Schalter: WUS_EDITOR_ENQUEUE_GUTENBERG_CSS (default true in config).
     */
    $enqueue_css = defined('WUS_EDITOR_ENQUEUE_GUTENBERG_CSS') ? (bool) WUS_EDITOR_ENQUEUE_GUTENBERG_CSS : true;
    if ($enqueue_css) {
        $rel = '/assets/styles/gutenberg.css';
        $abs = get_stylesheet_directory() . $rel;

        if (file_exists($abs)) {
            wp_enqueue_style(
                'wus-gutenberg-editor',
                get_stylesheet_directory_uri() . $rel,
                [],
                filemtime($abs)
            );
        }
    }

    /**
     * Editor JS: zentrale Editor-UX Regeln (Fullscreen off, Panels/Formats/Styles entfernen, etc.).
     * Schalter: WUS_EDITOR_ENQUEUE_FUNCTIONS_JS (default true in config).
     */
    $enqueue_js = defined('WUS_EDITOR_ENQUEUE_FUNCTIONS_JS') ? (bool) WUS_EDITOR_ENQUEUE_FUNCTIONS_JS : true;
    if (!$enqueue_js) {
        return;
    }

    $rel = '/assets/js/editor-functions.js';
    $abs = get_stylesheet_directory() . $rel;
    $uri = get_stylesheet_directory_uri() . $rel;

    if (!file_exists($abs)) {
        return;
    }

    wp_enqueue_script(
        'wus-editor-functions',
        $uri,
        ['wp-data', 'wp-edit-post', 'wp-blocks', 'wp-rich-text', 'wp-dom-ready'],
        filemtime($abs),
        true
    );

    /**
     * Config Injection: macht die PHP-Flags in JS verfügbar als window.WUS_EDITOR.
     * Vorteil: du kannst dein editor-functions.js generisch halten.
     */
    $cfg = [
        'disableFullscreen'   => defined('WUS_EDITOR_DISABLE_FULLSCREEN') ? (bool) WUS_EDITOR_DISABLE_FULLSCREEN : true,
        'logBlockStyles'      => defined('WUS_EDITOR_LOG_BLOCK_STYLES') ? (bool) WUS_EDITOR_LOG_BLOCK_STYLES : false,

        'removePanels'        => defined('WUS_EDITOR_REMOVE_PANELS') ? (bool) WUS_EDITOR_REMOVE_PANELS : false,
        'panelsToRemove'      => defined('WUS_EDITOR_PANELS_TO_REMOVE') ? array_values((array) WUS_EDITOR_PANELS_TO_REMOVE) : [],

        'removeBlockStyles'   => defined('WUS_EDITOR_REMOVE_BLOCK_STYLES') ? (bool) WUS_EDITOR_REMOVE_BLOCK_STYLES : true,
        'blockStylesToRemove' => defined('WUS_EDITOR_BLOCK_STYLES_TO_REMOVE') ? array_values((array) WUS_EDITOR_BLOCK_STYLES_TO_REMOVE) : [],

        'removeFormats'       => defined('WUS_EDITOR_REMOVE_FORMATS') ? (bool) WUS_EDITOR_REMOVE_FORMATS : true,
        'formatsToRemove'     => defined('WUS_EDITOR_FORMATS_TO_REMOVE') ? array_values((array) WUS_EDITOR_FORMATS_TO_REMOVE) : [],
    ];

    wp_add_inline_script(
        'wus-editor-functions',
        'window.WUS_EDITOR = ' . wp_json_encode($cfg) . ';',
        'before'
    );
});


/* -----------------------------------------------------------------------------
 * 2) Block Kategorie (Inserter)
 * -------------------------------------------------------------------------- */

add_filter('block_categories_all', function (array $categories): array {

    /**
     * Kategorie Slug/Titel: gruppiert deine Custom Blocks im Inserter.
     * Schalter: WUS_BLOCK_CATEGORY_SLUG / WUS_BLOCK_CATEGORY_TITLE
     */
    $slug  = defined('WUS_BLOCK_CATEGORY_SLUG') ? WUS_BLOCK_CATEGORY_SLUG : 'webundso-spezial';
    $title = defined('WUS_BLOCK_CATEGORY_TITLE') ? WUS_BLOCK_CATEGORY_TITLE : 'webundso-Elemente';

    // Duplikate vermeiden
    foreach ($categories as $cat) {
        if (!empty($cat['slug']) && $cat['slug'] === $slug) {
            return $categories;
        }
    }

    array_unshift($categories, [
        'slug'  => $slug,
        'title' => $title,
        'icon'  => null,
    ]);

    return $categories;
});


/* -----------------------------------------------------------------------------
 * 3) ACF JSON speichern
 * -------------------------------------------------------------------------- */

add_filter('acf/settings/save_json', function () {
   return get_stylesheet_directory() . '/assets/acf-json';
 });
 
 add_filter('acf/settings/load_json', function ($paths) {
   unset($paths[0]);
   $paths[] = get_stylesheet_directory() . '/assets/acf-json';
   return $paths;
 });

/* -----------------------------------------------------------------------------
 * 3b) ACF Blocks Auto-Registration (block.json + render.php)
 * -------------------------------------------------------------------------- */

add_action('acf/init', function (): void {

    // Prüfe ob ACF Blocks API verfügbar ist
    if (!function_exists('acf_register_block_type')) {
        return;
    }

    // Blocks Directory: Standard /assets/blocks, überschreibbar via WUS_BLOCKS_DIR
    $blocks_dir = defined('WUS_BLOCKS_DIR')
        ? WUS_BLOCKS_DIR
        : trailingslashit(get_stylesheet_directory()) . 'assets/blocks';

    if (!is_dir($blocks_dir)) {
        return;
    }

    // Default Kategorie für Blocks
    $default_category = defined('WUS_BLOCK_CATEGORY_SLUG') 
        ? WUS_BLOCK_CATEGORY_SLUG 
        : 'webundso-spezial';

    // Hole alle Unterordner im Blocks-Verzeichnis
    $dirs = glob(trailingslashit($blocks_dir) . '*', GLOB_ONLYDIR);
    if (!$dirs) {
        return;
    }

    foreach ($dirs as $dir) {
        // Prüfe auf block.json
        $json_file = trailingslashit($dir) . 'block.json';
        if (!file_exists($json_file)) {
            continue;
        }

        $raw = file_get_contents($json_file);
        if (!$raw) {
            continue;
        }

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            continue;
        }

        // Minimum: name + title müssen vorhanden sein
        if (empty($data['name']) || empty($data['title'])) {
            continue;
        }

        /**
         * Name normalisieren zu einem BAREN Slug, OHNE "acf/"-Prefix:
         * acf_register_block_type() haengt den Prefix selbst an (via
         * acf_slugify() in acf_validate_block_type()), und diese Funktion
         * wandelt "/" dabei zu "-" um. Wird hier schon "acf/hero" uebergeben,
         * entsteht dadurch "acf/acf-hero" (doppelter Prefix). Deshalb: nur
         * der bereinigte Slug wird an $args['name'] uebergeben.
         * - "hero" -> "hero"
         * - "acf-hero" -> "hero"
         * - "acf/hero" -> "hero"
         */
        $name = ltrim(preg_replace('#^acf[-/]#', '', (string) $data['name']), '/');

        // Prüfe auf render.php Template
        $render_template = trailingslashit($dir) . 'render.php';
        if (!file_exists($render_template)) {
            continue;
        }

        // Icon aus block.json oder Fallback
        $icon_name = $data['icon'] ?? 'block-default';
        
        // Wenn icon ein String ist (Dashicon-Name), erweitere es mit Farben
        if (is_string($icon_name)) {
            $icon = [
                'src'        => $icon_name,
                'background' => defined('WUS_BLOCK_ICON_BG') ? WUS_BLOCK_ICON_BG : '#0f172a',
                'foreground' => defined('WUS_BLOCK_ICON_FG') ? WUS_BLOCK_ICON_FG : '#ffffff',
            ];
        } else {
            // Falls icon bereits ein Array ist, übernehme es
            $icon = $icon_name;
        }

        // Block-Argumente zusammenstellen
        $args = [
            'name'            => $name,
            'title'           => (string) $data['title'],
            'description'     => (string) ($data['description'] ?? ''),
            'category'        => (string) ($data['category'] ?? $default_category),
            'icon'            => $icon,
            'keywords'        => (array) ($data['keywords'] ?? []),
            'mode'            => (string) ($data['mode'] ?? 'preview'),
            'align'           => (string) ($data['align'] ?? ''),
            'supports'        => (array) ($data['supports'] ?? []),
            'post_types'      => (array) ($data['post_types'] ?? []),
            'render_template' => $render_template,
        ];

        /**
         * ACF Block API/Version-Keys: nur durchreichen, wenn in block.json gesetzt.
         * Kein erzwungener Default hier - api_version wird sonst von ACF selbst
         * korrekt aus acf_block_version abgeleitet (siehe acf_register_block_type()).
         * WICHTIG: api_version (WP Gutenberg Block-API) und acf_block_version
         * (ACF's eigenes "Blocks V3"-Feature-Level, u.a. für hideFieldsInSidebar/
         * autoInlineEditing) sind zwei unabhängige Werte - nicht verwechseln.
         */
        $acf_meta = (array) ($data['acf'] ?? []);

        if (isset($data['apiVersion'])) {
            $args['api_version'] = (int) $data['apiVersion'];
        }
        if (isset($acf_meta['blockVersion'])) {
            $args['acf_block_version'] = (int) $acf_meta['blockVersion'];
        }
        if (isset($acf_meta['autoInlineEditing'])) {
            $args['auto_inline_editing'] = (bool) $acf_meta['autoInlineEditing'];
        }
        if (isset($acf_meta['hideFieldsInSidebar'])) {
            $args['hide_fields_in_sidebar'] = (bool) $acf_meta['hideFieldsInSidebar'];
        }

        // Debug-Ausgabe (optional)
        $debug = defined('WUS_DEBUG_BLOCK_REGISTER') 
            ? (bool) WUS_DEBUG_BLOCK_REGISTER 
            : (defined('WP_DEBUG') && WP_DEBUG);
            
        if ($debug) {
            error_log('WUS registering block: acf/' . $args['name'] . ' from ' . $json_file);
        }

        // Block registrieren
        acf_register_block_type($args);
    }
});


/* -----------------------------------------------------------------------------
 * 4) Patterns deaktivieren (optional)
 * -------------------------------------------------------------------------- */

/** Remote Patterns deaktivieren: verhindert externe Pattern-Feeds. */
if (defined('WUS_DISABLE_REMOTE_PATTERNS') && WUS_DISABLE_REMOTE_PATTERNS) {
    add_filter('should_load_remote_block_patterns', '__return_false');
}

/** Core Patterns deaktivieren: entfernt WP-Core Pattern Support. */
if (defined('WUS_DISABLE_CORE_PATTERNS') && WUS_DISABLE_CORE_PATTERNS) {
    add_action('after_setup_theme', function (): void {
        remove_theme_support('core-block-patterns');
    }, 20);
}


/* -----------------------------------------------------------------------------
 * 5) Allowed Blocks (Allow/Off)
 * -------------------------------------------------------------------------- */

add_filter('allowed_block_types_all', function ($allowed_blocks, $editor_context) {

    // Default: allow (strict) damit kein Wildwuchs entsteht
    $mode = defined('WUS_EDITOR_BLOCK_CONTROL_MODE') ? WUS_EDITOR_BLOCK_CONTROL_MODE : 'allow';

    // WP default
    if ($mode === 'off') {
        return $allowed_blocks;
    }

    $registry = WP_Block_Type_Registry::get_instance();

    // Allowlist aus config
    $show = defined('WUS_EDITOR_BLOCKS_SHOW') ? (array) WUS_EDITOR_BLOCKS_SHOW : [];

    // Wenn allow aktiv ist aber Liste leer: minimal statt alles
    if ($mode === 'allow' && empty($show)) {
        $show = [
            'core/heading',
            'core/paragraph',
            'core/media-text',
            'core/list',
            'core/image',
            'core/gallery',
            'core/quote',
            'core/audio',
            'core/cover',
            'core/file',
            'core/video',
            'core/table',
            'core/buttons',
            'core/button',
            'core/group',
            'core/columns',
            'core/column',
            'core/html',
            'core/separator',
            'core/spacer',
        ];
    }

    // Deine Projekt-Blocks (Namen entsprechen der Normalisierung im acf/init-Loop oben)
    $project_blocks = [
        'acf/hero',
        'acf/accordion',
        'acf/innerblock',
        'acf/latest-posts',
    ];

    foreach ($project_blocks as $slug) {
        if ($registry->is_registered($slug) && !in_array($slug, $show, true)) {
            $show[] = $slug;
        }
    }

    // Nur registrierte Blocks zurückgeben (verhindert “tote” Slugs)
    $allowed = [];
    foreach ($show as $slug) {
        if ($registry->is_registered($slug)) {
            $allowed[] = $slug;
        }
    }

    return array_values(array_unique($allowed));

}, 20, 2);
