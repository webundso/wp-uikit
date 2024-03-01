<?php
/*
=================================================================
Filename: author.php
Description: Redirect author pages to the homepage with WP redirect function
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/


wp_safe_redirect( get_home_url(), 301 );
exit;

?>
