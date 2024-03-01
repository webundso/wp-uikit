<?php
/*
=================================================================
Filename: 404.php
Description: Error page
Author: Noël Girstmair | webundso GmbH
Last changes: 8.2.2024
=================================================================
*/

get_header(); ?>		
			 
	<div class="content">	
		<div class="uk-container">

			<h1><?php _e( '404 - Seite nicht gefunden', 'webundso_wp' ); ?></h1>

			<section class="uk-alert-warning">
				<div class="uk-padding"><?php _e( 'Die gewünschte Seite konnte nicht gefunden werden.', 'webundso_wp' ); ?></div>
			</section> <!-- end article section -->

			<section class="search uk-margin-top">
				<h2><?php _e( 'Suchen:', 'webundso_wp' ); ?></h2>
		    <p><?php get_search_form(); ?></p>
			</section> <!-- end search section -->

		</div> <!-- end #inner-content -->
	</div> <!-- end #content -->

<?php get_footer(); ?>