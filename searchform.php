<?php
/*
=================================================================
Filename: searchform.php
Description: the searchform
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/
?>

<div class="uk-margin">
	
	<form class="uk-search uk-search-default" method="get" action="<?php echo home_url( '/' ); ?>">
		<button class="uk-search-icon-flip" uk-search-icon></button>
		<input class="uk-search-input" name="s" type="search" placeholder="<?php echo esc_attr_x( 'Suchbegriff', 'webundso_wp' ) ?>" aria-label="<?php echo esc_attr_x( 'Suchbegriff', 'webundso_wp' ) ?>" value="<?php echo get_search_query() ?>">
	</form>
	
</div>