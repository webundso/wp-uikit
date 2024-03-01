<?php
/*
=================================================================
Filename: template-redirect-to-child-page.php
Description: redirects to first subpage
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

global $post;
$parent = $post->ID;

// In case we use WPML
if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
  $_type  = get_post_type( $post->ID );
	$parent = icl_object_id( $post->ID, $_type, true, ICL_LANGUAGE_CODE );
}

$postChildren = get_children( array(
  'numberposts' => 1,
  'orderby' => 'menu_order',
  'order' => 'ASC',
  'post_type' => 'page',
  'post_status' => 'publish',
  'post_parent' => $parent
) );
wp_redirect( get_permalink( array_pop( $postChildren ) -> ID ), 301 );

?>