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

/** ACF WYSIWIG Toolbar */
add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
function my_toolbars( $toolbars )
  {
    // Uncomment to view format of $toolbars
    /*
    echo '< pre >';
        print_r($toolbars);
    echo '< /pre >';
    die;
    */
  
    // Add a new toolbar called "Very Simple"
    // - this toolbar has only 1 row of buttons
    $toolbars['Very Simple' ] = array();
    $toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline' );
  
    // Edit the "Full" toolbar and remove 'code'
    // - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
    if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
    {
        unset( $toolbars['Full' ][2][$key] );
    }
  
    // remove the 'Basic' toolbar completely
    unset( $toolbars['Basic' ] );
  
    // return $toolbars - IMPORTANT!
    return $toolbars;
  }
  
  // Ajax Call
  function my_load_post_content() {
      // if (isset($_POST['post_id'])) {
         // $post_id = intval($_POST['post_id']);
          $post_id = 9;
          $post = get_post($post_id);
          if ($post) {
              echo apply_filters('the_content', $post->post_content);
          }
    //  }
      wp_die(); // All AJAX handlers should die when finished
  }
  add_action('wp_ajax_load_post_content', 'my_load_post_content');
  add_action('wp_ajax_nopriv_load_post_content', 'my_load_post_content');