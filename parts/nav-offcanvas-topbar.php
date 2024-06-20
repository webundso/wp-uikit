<?php
/*
=================================================================
Filename: nav-offcanvas-topbar.php
Description: Topbar, logo left, menu Offcanvas width Toggle
Author: Noël Girstmair | webundso GmbH
Last changes: 20.6.2024
=================================================================
*/
?>

<div class="uk-container">
	<nav class="uk-navbar-container" uk-navbar>
		<div class="uk-navbar-left">
			<a class="uk-navbar-item uk-logo" href="/">webundso Template</a>
		</div>
		<div class="uk-navbar-right">
			<button class="uk-button uk-button-default uk-margin-small-right" id="toggleOffcanvas" type="button" uk-toggle="target: #offcanvas-nav-primary">
				<span id="icon-open" uk-icon="icon: menu" class="uk-animation-fade"></span>
				<span id="icon-close" class="uk-hidden" uk-icon="icon: close"></span>
			</button>
		</div>
	</nav>
</div>

<div id="offcanvas-nav-primary" uk-offcanvas="overlay: true">
		<div class="uk-offcanvas-bar uk-flex uk-flex-column">
			<button class="uk-offcanvas-close" type="button" uk-close></button>

			<?php wus_offcanvas_nav('offcanvas-nav');  ?>

		</div>
</div>

