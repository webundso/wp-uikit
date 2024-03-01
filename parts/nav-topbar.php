<?php
/*
=================================================================
Filename: nav-topbar.php
Description: Topbar, logo left, menu right
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/
?>
<div class="uk-container">
	
	<nav class="uk-navbar-container" uk-navbar>
		<div class="uk-navbar-left">
			<a class="uk-navbar-item uk-logo" href="/">webundso Template</a>
		</div>
		<div class="uk-navbar-right">
			<?php  	__urbi_navbar_walker_print_menu_location('main-nav');  ?>
		</div>
	</nav>
	
</div>