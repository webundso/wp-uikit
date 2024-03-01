<?php
/**
 * The off-canvas menu uses the Off-Canvas Component
 *
 * For more info: http://webundso_wp.com/docs/off-canvas-menu/
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

			<?php  // 	__urbi_navbar_walker_print_menu_location('main-nav');  ?>



<div id="offcanvas-nav-primary" uk-offcanvas="overlay: true">
		<div class="uk-offcanvas-bar uk-flex uk-flex-column">
			<button class="uk-offcanvas-close" type="button" uk-close></button>

			<?php   	__urbi_nav_walker_print_menu_location('offcanvas-nav');  ?>

		</div>
</div>

