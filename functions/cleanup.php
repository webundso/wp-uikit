<?php
/*
=================================================================
Filename: cleanup.php
Description: removes Junk from WP head, includes JS, CSS
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/

function im_setup() {

// ++++++++++++++++ REGISTER THEME OBJECTS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Register and enqueue Javascript 
	function wus_js() {

		wp_deregister_script( 'jquery' );
		// UIKit
		wp_register_script('uikit', get_template_directory_uri() . '/uikit/dist/js/uikit.min.js', false, '2.0.0', true );
		wp_register_script('uikit-icons', get_template_directory_uri() . '/uikit/dist/js/uikit-icons.min.js', false, '2.0.0', true );
		
		wp_enqueue_script( 'uikit' );
		wp_enqueue_script( 'uikit-icons' );

		// Custom
		wp_register_script( 'wus-script', get_template_directory_uri() . '/assets/js/scripts.js', false, '1.2', true );
		wp_enqueue_script( 'wus-script' );
		
		wp_enqueue_style( 'uikit-css', get_template_directory_uri() . '/assets/styles/site.css', array(), "3.17.11", 'all' );

	}
	add_action( 'wp_enqueue_scripts', 'wus_js' );

	// Register Feed Links, Post Formats, Custom Thumbnails, HTML5
	if ( function_exists( 'add_theme_support' ) ):
		add_theme_support( 'automatic-feed-links' );
		
		add_theme_support( 'post-formats',
			array(
				'aside',             // title less blurb
				'gallery',           // gallery of images
				'link',              // quick link to other site
				'image',             // an image
				'quote',             // a quick quote
				'status',            // a Facebook like status update
				'video',             // video
				'audio',             // audio
				'chat'               // chat transcript
			)
		); 
		
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
	endif;
	

	// Register image sizes
	if ( function_exists( 'add_image_size' ) ):
		add_image_size( 'large', 800, 600 );
	endif;



// ++++++++++++++++ PERFORMANCE +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //


	// Remove the version query string from scripts and styles - allows for better caching
	function im_remove_script_version( $src ){
		$parts = explode( '?', $src );
		return $parts[0];
	}
	add_filter( 'script_loader_src', 'im_remove_script_version', 15, 1 );
	add_filter( 'style_loader_src', 'im_remove_script_version', 15, 1 );


	// Prevents WordPress from testing SSL capability on domain.com/xmlrpc.php?rsd when XMLRPC not in use
	remove_filter('atom_service_url','atom_service_url_filter');


// ++++++++++++++++ SECURITY MODIFICATIONS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Remove WordPress version number
	function im_remove_wp_version() { return ''; }
	add_filter('the_generator', 'im_remove_wp_version');


	// Disable XMLRPC
	add_filter('xmlrpc_enabled', '__return_false');
	
	// Disable REST API (users, etc)
	// add_filter( 'rest_authentication_errors', 'wp_snippet_disable_rest_api' );
	//  function wp_snippet_disable_rest_api( $access ) {
	// 		return new WP_Error( 'rest_disabled', __('The WordPress REST API has been disabled.'), array( 'status' => rest_authorization_required_code()));
	//  }


// ++++++++++++++++ WP CLEANUP ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// General Cleanup - removes unnecessary WordPress features
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link' );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head');


	// Unregister all default WP Widgets
	function wpturbo_disable_standard_widgets() {
			unregister_widget( 'WP_Widget_Categories' );
			unregister_widget( 'WP_Widget_Recent_Posts' );
			unregister_widget( 'WP_Widget_Recent_Comments' );
			unregister_widget( 'WP_Widget_RSS' );
			unregister_widget( 'WP_Widget_Meta' );
			unregister_widget( 'WP_Widget_Search' );
	}
	add_action( 'widgets_init', 'wpturbo_disable_standard_widgets' );
	
	// remove Block Templates (Page-Templates)
	function wus_remove_tmpl() {
		remove_theme_support('block-templates');
		remove_theme_support('widgets-block-editor');
	}
	add_action('after_setup_theme', 'wus_remove_tmpl' );

	// Disable Emojis

	function disable_wp_emoji() {
	
		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	
		// filter to remove TinyMCE emojis
		add_filter( 'tiny_mce_plugins', 'disable_emoji_tinymce' );
		
		// filter to remove DNS prefetch
		add_filter( 'emoji_svg_url', '__return_false' );
	}
	add_action( 'init', 'disable_wp_emoji' );
	
	function disable_emoji_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
	
	/************* DASHBOARD WIDGETS *****************/
	
	// Disable default dashboard widgets
	function disable_default_dashboard_widgets() {
		remove_meta_box('dashboard_right_now', 'dashboard', 'core');    // Right Now Widget
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'core');  // Incoming Links Widget
		remove_meta_box('dashboard_plugins', 'dashboard', 'core');         // Plugins Widget
		remove_meta_box('dashboard_activity', 'dashboard', 'core');         // Aktivität Widget
	
		remove_meta_box('dashboard_quick_press', 'dashboard', 'core');  // Quick Press Widget
		remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');   // Recent Drafts Widget
		remove_meta_box('dashboard_primary', 'dashboard', 'core');         //
		remove_meta_box('dashboard_secondary', 'dashboard', 'core');       //
	
		// Removing plugin dashboard boxes
		remove_meta_box('yoast_db_widget', 'dashboard', 'normal');         // Yoast's SEO Plugin Widget
	
	}
	add_action('admin_menu', 'disable_default_dashboard_widgets');
	
	// webundso Dashboard widget
	
	add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
		
	function my_custom_dashboard_widgets() {
	global $wp_meta_boxes;
	 
	wp_add_dashboard_widget('custom_help_widget', 'Support Webseite', 'custom_dashboard_help');
	}
	 
	function custom_dashboard_help() {
	 
	// Content you want to show inside the widget
	 
	 echo '<p>Support-Anfrage an webundso senden: <a href="mailto:support@webundso.ch">support@webundso.ch</a></p>'; 
	}
	
	

	// Set the maximum number of post revisions unless the constant is already set in wp-config.php
	if (!defined('WP_POST_REVISIONS')) define('WP_POST_REVISIONS', 15);


	// Disable Auto-Formatting in Content and Excerpt
	remove_filter( 'the_content', 'wpautop' );
	remove_filter( 'the_excerpt', 'wpautop' );


	// Disable Auto Linking of URLs in comments
	remove_filter('comment_text', 'make_clickable', 9);


	// Disable self-ping
	function im_self_ping( &$links ) {
		$home = get_option( 'home' );
		foreach ( $links as $l => $link )
			if ( 0 === strpos( $link, $home ) )
				unset($links[$l]);
	}
	add_action( 'pre_ping', 'im_self_ping' );


// ++++++++++++++++ ADMIN MODIFICATIONS +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Add visual editor stylesheet
	add_editor_style( 'editor-style.css' );
	
	// Allow editors to edit menu
	$role_object = get_role( 'editor' );
	$role_object->add_cap( 'edit_theme_options' );


	// Change the default WordPress greeting in Admin
	function im_replace_howdy( $wp_admin_bar ) {
		$my_account=$wp_admin_bar->get_node('my-account');
		$newtitle = str_replace( 'Howdy,', 'Welcome, ', $my_account->title );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}
	add_filter( 'admin_bar_menu', 'im_replace_howdy', 25 );


	// Add new Admin menu item "All Settings"
	function im_all_settings_link() {
		add_options_page(__('Alle Einstellungen'), __('ALLE Einstellungen'), 'administrator', 'options.php');
	}
	add_action('admin_menu', 'im_all_settings_link');


	// Remove dashboard menu items
	function dashboard_tweaks() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu('about');
		$wp_admin_bar->remove_menu('wporg');
		$wp_admin_bar->remove_menu('documentation');
		$wp_admin_bar->remove_menu('support-forums');
		$wp_admin_bar->remove_menu('feedback');
	//	$wp_admin_bar->remove_menu('view-site');
	}
	add_action( 'wp_before_admin_bar_render', 'dashboard_tweaks' );
	
	// Clean up the Admin Menu
	
	// This will help you retain all your permissions but remove the menu items from the admin. Helpfull if your client needs admin rights but, you want to keep them away from things they either dont need or are likely to break.
	
	function remove_menus(){
		
		remove_menu_page( 'index.php' ); //Dashboard                  
		remove_menu_page( 'edit-comments.php' );
		remove_menu_page( 'themes.php' );
		remove_menu_page( 'plugins.php' );
		remove_menu_page( 'tools.php' );
		remove_menu_page( 'options-general.php' );
		remove_menu_page( 'edit.php?post_type=acf' ); //ACF Custom Fields
	}
	// add_action( 'admin_menu', 'remove_menus' );
	
	/** Hide Adminbar **/
	add_filter('show_admin_bar', '__return_false');


	// ++++++++++++++++ Admin Footer +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Custom Backend Footer
	function wus_custom_admin_footer() {
		_e('<span id="footer-thankyou">Erstellt von <a href="https://www.webundso.ch" target="_blank">webundso GmbH</a></span>.', 'webundso');
	}
	// adding it to the admin area
	add_filter('admin_footer_text', 'wus_custom_admin_footer');


// ++++++++++++++++ LOGIN MODIFICATIONS +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Custom login logo
	function im_custom_login_logo() {
		echo '<style type="text/css">
			.login #login { padding-top: 0;}
			.login h1 { width: 320px; height: 200px; }
			.login h1 a { background:url(' . get_template_directory_uri() . '/assets/images/login-logo.svg) 50% 50% no-repeat; background-size:contain; height: 200px; width: 320px; }
			</style>';
	}
	add_action('login_head', 'im_custom_login_logo');



// ++++++++++++++++ CUSTOM POST TYPES +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //
	
	// Show Custom Post Types in search results
	function im_searchAll( $query ) {
		if ( $query->is_search ) { $query->set( 'post_type', array( 'site', 'plugin', 'theme', 'person' )); } 
		return $query;
	}
	add_filter( 'the_search_query', 'im_searchAll' );


	// Add Custom Post Types to the default RSS feed
	function im_custom_feed_request( $vars ) {
		if (isset($vars['feed']) && !isset($vars['post_type']))
			$vars['post_type'] = array( 'post', 'site', 'plugin', 'theme', 'person' );
		return $vars;
	}
	add_filter( 'request', 'im_custom_feed_request' );


// ++++++++++++++++ CUSTOM USER PROFILE FIELDS ++++++++++++++++++++++++++++++++++++++++++++++++++++ //

// 	function im_custom_userfields( $contactmethods ) {
// 
// 		$contactmethods['contact_phone'] 	= 'Contact Number';
// 		$contactmethods['social_fb'] 		= 'Facebook Profile';
// 		$contactmethods['social_tw'] 		= 'Twitter Profile';
// 		$contactmethods['social_gp'] 		= 'Google Plus Profile';
// 		
// 		return $contactmethods;
// 	}
// 	add_filter('user_contactmethods','im_custom_userfields',10,1);


// ++++++++++++++++ BASIC HELPER FUNCTIONS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //


	// Change the length of the default excerpt (number of words, default is 55)
	function im_excerpt_length($length) { 
		return 80;
	}
	add_filter('excerpt_length', 'im_excerpt_length');


	// Disable RSS feeds
	function im_disable_feed() {
		wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
	}
	add_action('do_feed', 'im_disable_feed', 1);
	add_action('do_feed_rdf', 'im_disable_feed', 1);
	add_action('do_feed_atom', 'im_disable_feed', 1);
	add_action('do_feed_rss', 'im_disable_feed', 1);
	add_action('do_feed_rss2', 'im_disable_feed', 1);


	// Gets the URL of the current page
	function get_url() {
		return (isset($_SERVER['HTTPS']) == 'on' ? 'https' : 'http').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	}


	// Allow custom MIME types to be uploaded
	function im_custom_upload_mimes ( $existing_mimes=array() ) {
		$existing_mimes['vcf'] = 'text/vcard';
		return $existing_mimes;
	}
	add_filter('upload_mimes', 'im_custom_upload_mimes');


	// Get post/page excerpt from ID
	function get_excerpt_by_id($post_id){
		$the_post = get_post($post_id);
		$the_excerpt = $the_post->post_content;
		$excerpt_length = 35;
		$the_excerpt = strip_tags(strip_shortcodes($the_excerpt));
		$words = explode(' ', $the_excerpt, $excerpt_length + 1);
		if(count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words, '&hellip;');
			$the_excerpt = implode(' ', $words);
		endif;
		$the_excerpt = '<p>' . $the_excerpt . '</p>';
		return $the_excerpt;
	}


	// Use HTML5 FIGURE and FIGCAPTION for images and captions
	function im_cleaner_captions( $output, $attr, $content ) {
		if ( is_feed() )
			return $output;

		$defaults = array(
			'id' => '',
			'align' => 'alignnone',
			'width' => '',
			'caption' => ''
		);

		$attr = shortcode_atts( $defaults, $attr );

		if ( 1 > $attr['width'] || empty( $attr['caption'] ) )
			return $content;

		$attributes = ( !empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
		$attributes .= ' class="wp-caption ' . esc_attr( $attr['align'] ) . '"';

		$output = '<figure' . $attributes .'>';
		$output .= do_shortcode( $content );
		$output .= '<figcaption class="wp-caption-text"><p>' . $attr['caption'] . '</p></figcaption>';
		$output .= '</figure>';

		return $output;
	}
	add_filter( 'img_caption_shortcode', 'im_cleaner_captions', 10, 3 );


// ++++++++++++++++ COMMENTS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Disable comments on media files
	function filter_media_comment_status( $open, $post_id ) {
		$post = get_post( $post_id );
		if( $post->post_type == 'attachment' ) {
			return false;
		}
		return $open;
	}
	add_filter( 'comments_open', 'filter_media_comment_status', 10 , 2 );


	// Close comments globally
	function im_closeCommentsGlobaly($data) { return false; }
	add_filter('comments_number', 'im_closeCommentsGlobaly');
	add_filter('comments_open', 'im_closeCommentsGlobaly');


// ++++++++++++++++ LOAD THEME OPTIONS ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ //

	// Load theme options
//	require_once ( get_template_directory() . '/_inc/options.php' );

// ====== END ====== //

}

add_action( 'after_setup_theme', 'im_setup' );
