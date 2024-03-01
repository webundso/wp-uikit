<?php
/*
=================================================================
Filename: sidebar.php
Description: Sidebar and widget area
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/
 ?>

<div class="sidebar">

	<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

		<?php dynamic_sidebar( 'sidebar1' ); ?>

	<?php endif; ?>

</div>