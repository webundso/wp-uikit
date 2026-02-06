<?php
/**
 * nav-offcanvas-topbar.php
 * Topbar: Logo links, Offcanvas Toggle rechts (UIkit Offcanvas).
 */

defined('ABSPATH') || exit;

$home_url   = esc_url(home_url('/'));
$brand_name = defined('WUS_BRAND_NAME') ? (string) WUS_BRAND_NAME : get_bloginfo('name');
$brand_name = $brand_name ?: esc_html__('Website', 'webundso');

// Optional Asset Logo (wie im nav-topbar.php)
$logo_rel = defined('WUS_LOGO_ASSET') ? (string) WUS_LOGO_ASSET : '';
$logo_rel = ltrim($logo_rel, '/');

$logo_abs = $logo_rel ? (get_stylesheet_directory() . '/' . $logo_rel) : '';
$logo_uri = $logo_rel ? (get_stylesheet_directory_uri() . '/' . $logo_rel) : '';

$logo_alt = defined('WUS_LOGO_ALT') ? (string) WUS_LOGO_ALT : $brand_name;

$offcanvas_id = 'offcanvas-nav-primary';
?>
<div class="uk-container">
		<nav class="uk-navbar-container" uk-navbar aria-label="<?php echo esc_attr__('Hauptnavigation', 'webundso'); ?>">
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
						<button
								type="button"
								class="uk-button uk-button-default"
								uk-toggle="target: #<?php echo esc_attr($offcanvas_id); ?>"
								aria-controls="<?php echo esc_attr($offcanvas_id); ?>"
								aria-expanded="false"
								aria-label="<?php echo esc_attr__('Menu öffnen', 'webundso'); ?>"
						>
								<span uk-icon="icon: menu" aria-hidden="true"></span>
						</button>
				</div>
		</nav>
</div>

<div
		id="<?php echo esc_attr($offcanvas_id); ?>"
		uk-offcanvas="overlay: true"
		role="dialog"
		aria-modal="true"
		aria-label="<?php echo esc_attr__('Navigation', 'webundso'); ?>"
>
		<div class="uk-offcanvas-bar uk-flex uk-flex-column">
				<button class="uk-offcanvas-close" type="button" uk-close aria-label="<?php echo esc_attr__('Menu schliessen', 'webundso'); ?>"></button>

				<?php wus_offcanvas_nav('offcanvas-nav'); ?>
		</div>
</div>
