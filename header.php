<?php
/**
 * header.php
 * Theme Header (Head + Site Header + Topbar/Nav).
 */

defined('ABSPATH') || exit;

$theme_uri = get_stylesheet_directory_uri();
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1">

		<?php
		// Favicons / Manifest (child-theme-freundlich)
		// Hinweis: wenn Dateien fehlen, ist das ok (Browser ignoriert 404), aber du kannst optional existence-checks machen.
		?>
		<link rel="icon" type="image/png" href="<?php echo esc_url($theme_uri . '/assets/images/favicon-96x96.png'); ?>" sizes="96x96">
		<link rel="icon" type="image/svg+xml" href="<?php echo esc_url($theme_uri . '/assets/images/favicon.svg'); ?>">
		<link rel="shortcut icon" href="<?php echo esc_url($theme_uri . '/assets/images/favicon.ico'); ?>">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($theme_uri . '/assets/images/apple-touch-icon.png'); ?>">
		<link rel="manifest" href="<?php echo esc_url($theme_uri . '/assets/images/site.webmanifest'); ?>">



		<?php if (!defined('WUS_DISABLE_COMMENTS') || !WUS_DISABLE_COMMENTS) : ?>
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
	<?php endif; ?>

		<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

	
		<button
				type="button"
				class="mobileTrigger uk-hidden@m"
				id="menuToggle"
				aria-controls="offcanvas-mobile"
				aria-expanded="false"
				uk-toggle="target: #offcanvas-mobile"
		>
				<span class="top"></span>
				<span class="middle"></span>
				<span class="bottom"></span>
				<span class="screen-reader-text"><?php echo esc_html__('Menu öffnen', 'webundso'); ?></span>
		</button>

		<header class="header" role="banner">
				<?php get_template_part('parts/nav', 'topbar'); ?>
				<?php // get_template_part('parts/nav', 'offcanvas-topbar'); ?>
		</header>
