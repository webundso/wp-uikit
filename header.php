<?php
/*
=================================================================
Filename: header.php
Description: Page header
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/
?>
<!doctype html>
<html class="no-js"  <?php language_attributes(); ?>>

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1">
		
		<link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_directory'); ?>/assets/images/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_directory'); ?>/assets/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_directory'); ?>/assets/images/favicon-16x16.png">
		<link rel="manifest" href="<?php bloginfo('template_directory'); ?>/assets/images/site.webmanifest">
		<link rel="mask-icon" href="<?php bloginfo('template_directory'); ?>/assets/images/safari-pinned-tab.svg" color="#003350">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="theme-color" content="#ffffff">

		<meta name="description" content="" />
		<meta name="keywords" content="" />

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php wp_head(); ?>

	</head>
			
	<body <?php body_class(); ?>>

		<header class="header" role="banner">
					
			 <?php  // get_template_part( 'parts/nav', 'topbar' ); ?>
			 <?php  get_template_part( 'parts/nav', 'offcanvas-topbar' ); ?>
	
		</header> 