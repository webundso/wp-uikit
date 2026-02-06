<?php
defined('ABSPATH') || exit;

/**
 * searchform.php
 *
 * Zweck:
 * - Globales Suchformular für das Theme
 * - Wird von get_search_form() geladen (404, Header, Sidebar, etc.)
 *
 */

?>

<div class="uk-margin">

	<form
		class="uk-search uk-search-default"
		method="get"
		action="<?php echo esc_url(home_url('/')); ?>"
		role="search"
	>
		<button
			type="submit"
			class="uk-search-icon-flip"
			uk-search-icon
			aria-label="<?php echo esc_attr__('Suche starten', 'webundso'); ?>"
		></button>

		<input
			class="uk-search-input"
			name="s"
			type="search"
			placeholder="<?php echo esc_attr__('Suchbegriff', 'webundso'); ?>"
			aria-label="<?php echo esc_attr__('Suchbegriff', 'webundso'); ?>"
			value="<?php echo esc_attr(get_search_query()); ?>"
		>
	</form>

</div>
