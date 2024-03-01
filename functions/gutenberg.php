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
//     'core/paragraph',
//     'core/image',
//     'core/heading',
//     'core/gallery',
//     'core/list',
//     'core/quote',
//     'core/audio',
//     'core/cover',
//     'core/file',
//     'core/video',
//     'core/table',
//     'core/verse',
//     'core/code',
//     'core/freeform',
//     'core/html',
//     'core/preformatted',
//     'core/pullquote',
//     'core/button',
//     'core/columns',
//     'core/media-text',
//     'core/more',
//     'core/nextpage',
//     'core/separator',
//     'core/spacer',
//     'core/shortcode',
//     'core/archives',
//     'ore/categories',
//     'core/latest-comments',
//     'core/latest-posts',
//     'core/calendar',
//     'core/rss',
//     'core/search',
//     'core/tag-cloud',
//     'core/embed',
//     'core-embed/twitter',
//     'core-embed/youtube',
//     'core-embed/facebook',
//     'core-embed/instagram',
//     'core-embed/wordpress',
//     'core-embed/soundcloud',
//     'core-embed/spotify',
//     'core-embed/flickr',
//     'core-embed/vimeo',
//     'core-embed/animoto',
//     'core-embed/cloudup',
//     'core-embed/collegehumor',
//     'core-embed/dailymotion',
//     'core-embed/funnyordie',
//     'core-embed/hulu',
//     'core-embed/imgur',
//     'core-embed/issuu',
//     'core-embed/kickstarter',
//     'core-embed/meetup-com',
//     'core-embed/mixcloud',
//     'core-embed/photobucket',
//     'core-embed/polldaddy',
//     'core-embed/reddit',
//     'core-embed/reverbnation',
//     'core-embed/screencast',
//     'core-embed/scribd',
//     'core-embed/slideshare',
//     'core-embed/smugmug',
//     'core-embed/speaker',
//     'core-embed/ted',
//     'core-embed/tumblr',
//     'core-embed/videopress',
//     'core-embed/wordpress-tv',
//   );
//  
//  // for specific post-types
//  /*
// 	if( $post->post_type === 'page' ) {
// 		$allowed_blocks[] = 'core/shortcode';
// 	}
//  */
// 	return $allowed_blocks;
 
//}

