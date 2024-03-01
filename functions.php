<?php
/*
=================================================================
Filename: functions.php
Description: Includes all files from folder functions/
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/

// WP Head and other cleanup functions
require_once(get_template_directory().'/functions/cleanup.php'); 

// Register custom menus and menu walkers
require_once(get_template_directory().'/functions/menus.php'); 

// Register sidebars/widget areas
require_once(get_template_directory().'/functions/sidebar.php'); 

// Gutenberg Special
require_once(get_template_directory().'/functions/gutenberg.php'); 

// webundso definitions for this project
 require_once(get_template_directory().'/functions/webundso.php'); 

// Replace 'older/newer' post links with numbered navigation
// require_once(get_template_directory().'/functions/page-navi.php'); 

// Makes WordPress comments suck less
// require_once(get_template_directory().'/functions/comments.php'); 

// Customize the WordPress admin/dashboard
// require_once(get_template_directory().'/functions/admin.php'); 

// Related post function - no need to rely on plugins
// require_once(get_template_directory().'/functions/related-posts.php'); 

