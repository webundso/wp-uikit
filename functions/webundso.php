<?php
/*
=================================================================
Filename: webundso.php
Description: additional functions for this website
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/
 

/** Standard template name (page.php) **/
add_filter('default_page_template_title', function() {
    return __('Standard (Template ganze Breite)', 'webundso_wp');
});

/** No fullscreen in Gutenberg **/

if (is_admin()) { 
  function jba_disable_editor_fullscreen_by_default() {
      $script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
      wp_add_inline_script( 'wp-blocks', $script );
  }
  add_action( 'enqueue_block_editor_assets', 'jba_disable_editor_fullscreen_by_default' );
}

/** Allow SVG Upload **/
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/* editor block styles */
// https://soderlind.no/hide-block-styles-in-gutenberg/
// https://wordpress.stackexchange.com/questions/339436/removing-panels-meta-boxes-in-the-block-editor  
add_action( 'init', 'remove_block_style' );

function remove_block_style() {
  // Register the block editor script.
  wp_register_script( 'remove-block-style', get_stylesheet_directory_uri() . '/assets/js/editor-functions.js', [ 'wp-blocks', 'wp-edit-post' ] );
  // register block editor script.
  register_block_type( 'remove/block-style', [
    'editor_script' => 'remove-block-style',
  ] );
}


/** ACF JSON **/
add_filter('acf/settings/save_json', 'my_acf_json_save_point');
 
function my_acf_json_save_point( $path ) {  
  $path = get_stylesheet_directory() . '/assets/acf-json';
  return $path;
    
}
