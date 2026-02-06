<?php
/*
=================================================================
Filename: functions.php
Description: Includes all files from folder functions/
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/

require_once get_template_directory() . '/functions/webundso.php';
require_once get_template_directory() . '/functions/cleanup.php';
require_once get_template_directory() . '/functions/menus.php';
require_once get_template_directory() . '/functions/gutenberg.php';

// Replace 'older/newer' post links with numbered navigation
// require_once(get_template_directory().'/functions/page-navi.php'); 

// Makes WordPress comments suck less
// require_once(get_template_directory().'/functions/comments.php'); 

// Customize the WordPress admin/dashboard
// require_once(get_template_directory().'/functions/admin.php'); 

// Related post function - no need to rely on plugins
// require_once(get_template_directory().'/functions/related-posts.php'); 

// === DEBUG: Block Registration Check ===
add_action('init', function() {
    if (!is_admin()) return;
    
    error_log('=== WUS DEBUG START ===');
    error_log('ACF verfügbar: ' . (function_exists('acf_register_block_type') ? 'JA' : 'NEIN'));
    
    $blocks_dir = get_stylesheet_directory() . '/assets/blocks';
    error_log('Blocks Dir: ' . $blocks_dir);
    error_log('Dir existiert: ' . (is_dir($blocks_dir) ? 'JA' : 'NEIN'));
    
    if (is_dir($blocks_dir)) {
        $dirs = glob($blocks_dir . '/*', GLOB_ONLYDIR);
        error_log('Gefundene Ordner: ' . count($dirs));
        
        foreach ($dirs as $dir) {
            $name = basename($dir);
            error_log("  - $name");
            error_log("    block.json: " . (file_exists($dir . '/block.json') ? 'JA' : 'NEIN'));
            error_log("    render.php: " . (file_exists($dir . '/render.php') ? 'JA' : 'NEIN'));
        }
    }
}, 5);

add_action('acf/init', function() {
    error_log('ACF/INIT Hook gefeuert');
    
    // Nach der Registration
    $types = function_exists('acf_get_block_types') ? acf_get_block_types() : [];
    error_log('Registrierte ACF Blocks: ' . count($types));
    
    foreach ($types as $type) {
        error_log('  - ' . $type['name'] . ' | ' . $type['title']);
    }
}, 999);