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

add_action('enqueue_block_assets', 'enqueue_my_block_styles');
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
}
add_action( 'acf/init', 'be_register_blocks' );

/* Add/remove Blocks */

//  add_filter( 'allowed_block_types', 'wus_allowed_block_types', 10, 2 );
 
// function wus_allowed_block_types( $allowed_blocks, $post ) {
//  
// 	$allowed_blocks = array(
//   'core/legacy-widget',
//   'core/widget-group',
//   'core/archives',
//   'core/avatar',
//   'core/block',
//   'core/calendar',
//   'core/categories',
//   'core/comment-author-name',
//   'core/comment-content',
//   'core/comment-date',
//   'core/comment-edit-link',
//   'core/comment-reply-link',
//   'core/comment-template',
//   'core/comments',
//   'core/comments-pagination',
//   'core/comments-pagination-next',
//   'core/comments-pagination-numbers',
//   'core/comments-pagination-previous',
//   'core/comments-title',
//   'core/cover',
//   'core/file',
//   'core/footnotes',
//   'core/gallery',
//   'core/heading',
//   'core/home-link',
//   'core/image',
//   'core/latest-comments',
//   'core/latest-posts',
//   'core/loginout',
//   'core/navigation',
//   'core/navigation-link',
//   'core/navigation-submenu',
//   'core/page-list',
//   'core/page-list-item',
//   'core/pattern',
//   'core/post-author',
//   'core/post-author-biography',
//   'core/post-author-name',
//   'core/post-comments-form',
//   'core/post-content',
//   'core/post-date',
//   'core/post-excerpt',
//   'core/post-featured-image',
//   'core/post-navigation-link',
//   'core/post-template',
//   'core/post-terms',
//   'core/post-title',
//   'core/query',
//   'core/query-no-results',
//   'core/query-pagination',
//   'core/query-pagination-next',
//   'core/query-pagination-numbers',
//   'core/query-pagination-previous',
//   'core/query-title',
//   'core/read-more',
//   'core/rss',
//   'core/search',
//   'core/shortcode',
//   'core/site-logo',
//   'core/site-tagline',
//   'core/site-title',
//   'core/social-link',
//   'core/tag-cloud',
//   'core/template-part',
//   'core/term-description',
//   'core/audio',
//   'core/button',
//   'core/buttons',
//   'core/code',
//   'core/column',
//   'core/columns',
//   'core/details',
//   'core/embed',
//   'core/freeform',
//   'core/group',
//   'core/html',
//   'core/list',
//   'core/list-item',
//   'core/media-text',
//   'core/missing',
//   'core/more',
//   'core/nextpage',
//   'core/paragraph',
//   'core/preformatted',
//   'core/pullquote',
//   'core/quote',
//   'core/separator',
//   'core/social-links',
//   'core/spacer',
//   'core/table',
//   'core/text-columns',
//   'core/verse',
//   'core/video',
//   'core/post-comments',
// );
//  
//  // for specific post-types
//  /*
// 	if( $post->post_type === 'page' ) {
// 		$allowed_blocks[] = 'core/shortcode';
// 	}
//  */
// 	return $allowed_blocks;
 
//}

// Prevent the loading of patterns from wordpress.org
add_filter('should_load_remote_block_patterns', '__return_false');

// remove patterns that ship with WP Core
function wus_remove_core_block_patterns(){
  remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'wus_remove_core_block_patterns');