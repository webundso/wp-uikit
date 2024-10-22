<?php
/*
=================================================================
Filename: gutenberg.php
Description: Definitions for project blocks w. ACF
Author: Noël Girstmair | webundso GmbH
Last changes: 27.2.2024
=================================================================
*/

// Adds site styles to the Gutenberg editor

add_action('admin_head', 'enqueue_my_block_styles');
function enqueue_my_block_styles() {
  wp_enqueue_style('my-block-style', get_template_directory_uri() . '/assets/styles/gutenberg.css' );
}


/* Registzer ACF Block for Gutenberg */

function be_register_blocks()  {

  acf_register_block_type(array(
    'name'              => 'test-block',
    'title'             => ('Test Block'),
    'description'       => (''),
    'render_template'   => 'assets/blocks/block-test.php',
    'category'          => 'layout',
    'icon' => array(
      'background' => '#7e70af',
      'foreground' => '#fff',
      'src' => 'book-alt',
     ),
      'mode'			=> 'auto', // auto, preview, edit
      'keywords'          => array( 'employer' ),
      //An array of post types to restrict this block type to.
      'post_types' => array('post', 'page'),
      //The default block alignment. Available settings are “left”, “center”, “right”, “wide” and “full”. Defaults to an empty string.
      'align' => 'full',
  ));
  
  acf_register_block_type( array(
    'name'			=> 'inner-block',
    'title'			=> 'Block Gefäss',
    'render_template'	=> 'assets/blocks/block-inner.php',
    'mode'			=> 'preview',
    'supports'		=> [
      'align'			=> false,
      'anchor'		=> true,
      'customClassName'	=> true,
      'jsx' 			=> true,
    ],
    'icon' => array(
      // Specifying a background color to appear with the icon e.g.: in the inserter.
      'background' => '#D74635',
      // Specifying a color for the icon (optional: if not set, a readable color will be automatically defined)
      'foreground' => '#fff',
      // Specifying a dashicon for the block
      'src' => 'fullscreen-exit-alt',
     ),
  ));
}
add_action( 'acf/init', 'be_register_blocks' );


// Prevent the loading of patterns from wordpress.org
add_filter('should_load_remote_block_patterns', '__return_false');

// remove patterns that ship with WP Core
function wus_remove_core_block_patterns(){
  remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'wus_remove_core_block_patterns');