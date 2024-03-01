<?php
/*
=================================================================
Filename: page-navi.php
Description: Paginate, if needed
Author: Noël Girstmair | webundso GmbH
Last changes: 27.2.2024
=================================================================
*/
function wus_page_navi() {
		global $wp_query;
		$big = 999999999; // This needs to be an unlikely integer

		// For more options and info view the docs for paginate_links()
		// http://codex.wordpress.org/Function_Reference/paginate_links
		$paginate_links = paginate_links(array(
				'base' => str_replace($big, '%#%', html_entity_decode(get_pagenum_link($big))),
				'current' => max(1, get_query_var('paged')),
				'total' => $wp_query->max_num_pages,
				'mid_size' => 5,
				'prev_next' => true,
				'prev_text' => __( '<span uk-pagination-previous></span>', 'webundso_wp' ),
				'next_text' => __( '<span uk-pagination-next></span>', 'webundso_wp' ),
				'type' => 'array', // Change type to array for custom pagination
		));

		if ($paginate_links) {
				echo '<ul class="uk-pagination uk-flex-center">';
				foreach ($paginate_links as $page_link) {
						if (strpos($page_link, 'current') !== false) {
								echo '<li class="uk-active">' . $page_link . '</li>';
						} elseif (strpos($page_link, 'dots') !== false) {
								echo '<li class="uk-disabled">' . $page_link . '</li>';
						} else {
								echo '<li>' . $page_link . '</li>';
						}
				}
				echo '</ul><!--// end .uk-pagination -->';
		}
}