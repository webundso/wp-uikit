<?php
/*
=================================================================
Filename: content-missing.php
Description: Content shown if nothing is found
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/
?>

<div class="post-not-found">

	<?php if ( is_search() ) : ?>

	<div class="uk-alert-warning" uk-padding uk-alert>

		<h2><?php _e( 'Entschuldigung, aber wir konnten keine Ergebnisse für Ihre Suchanfrage finden', 'webundso_wp' );?></h2>
		<p><?php _e( 'Möglicherweise ist der Begriff falsch geschrieben oder nicht vorhanden. Bitte überprüfen Sie Ihre Eingabe und versuchen Sie es erneut.', 'webundso_wp' );?></p>

	</div>

	<section class="uk-section">
		<h2>Suche</h2>
		<p><?php get_search_form(); ?></p>
	</section> <!-- end search section -->

	<?php else: ?>

	<h1><?php _e( 'Der Artikel wurde nicht gefunden!', 'webundso_wp' ); ?></h1>

	<div class="uk-alert-warning" uk-padding uk-alert>
		<p><?php _e( 'Leider wurde kein Beitrag gefunden.', 'webundso_wp' ); ?></p>
	</div>

	<section class="uk-section">
		<h2>Suche</h2>
		<p><?php get_search_form(); ?></p>
	</section> <!-- end search section -->


	<?php endif; ?>

</div>