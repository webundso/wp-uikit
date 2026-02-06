<?php
/**
 * nav-topbar.php
 * Topbar: Logo links, Menu rechts (UIkit Navbar).
 */

defined('ABSPATH') || exit;

$home_url   = esc_url(home_url('/'));
$brand_name = defined('WUS_BRAND_NAME') ? (string) WUS_BRAND_NAME : get_bloginfo('name');
$brand_name = $brand_name ?: esc_html__('Website', 'webundso');

// Optional Asset Logo
$logo_rel = defined('WUS_LOGO_ASSET') ? (string) WUS_LOGO_ASSET : '';
$logo_rel = ltrim($logo_rel, '/');

$logo_abs = $logo_rel ? (get_stylesheet_directory() . '/' . $logo_rel) : '';
$logo_uri = $logo_rel ? (get_stylesheet_directory_uri() . '/' . $logo_rel) : '';

$logo_alt = defined('WUS_LOGO_ALT') ? (string) WUS_LOGO_ALT : $brand_name;
?>
<div class="uk-container">
		<nav
				class="uk-navbar-container uk-navbar-transparent"
				uk-navbar
				aria-label="<?php echo esc_attr__('Hauptnavigation', 'webundso'); ?>"
		>
				<div class="uk-navbar-left">
						<a class="uk-navbar-item uk-logo" href="<?php echo $home_url; ?>">
								<?php if ($logo_rel && $logo_abs && file_exists($logo_abs)): ?>
										<img
												src="<?php echo esc_url($logo_uri); ?>"
												alt="<?php echo esc_attr($logo_alt); ?>"
												loading="eager"
												decoding="async"
										>
								<?php else: ?>
										<?php echo esc_html($brand_name); ?>
								<?php endif; ?>
						</a>
				</div>

				<div class="uk-navbar-right">
						<?php wus_topnav('main-nav'); ?>
				</div>
		</nav>
</div>
