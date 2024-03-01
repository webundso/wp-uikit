<?php
function site_scripts() {
  global $wp_styles; // Call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way
    
            
    // Register Foundation styles
    wp_enqueue_style( 'uikit-css', get_template_directory_uri() . '/assets/styles/site.css', array(), "3.17.11", 'all' );
     
 		// Backstretch
 //   wp_enqueue_script( 'backstretch', get_template_directory_uri() . '/assets/scripts/jquery.backstretch.min.js', array( 'jquery' ), filemtime(get_template_directory() . '/assets/scripts'), false );  

		// Slick JS
 //   wp_enqueue_script( 'slickslider', get_template_directory_uri() . '/assets/scripts/jquery.slick.min.js', array( 'jquery' ), filemtime(get_template_directory() . '/assets/scripts'), false );  
     
    // Adding scripts file in the footer
//    wp_enqueue_script( 'site-js', get_template_directory_uri() . '/assets/scripts/scripts.js', array( 'jquery' ), filemtime(get_template_directory() . '/assets/scripts'), true );
       
    // Register main stylesheet
 //   wp_enqueue_style( 'site-css', get_template_directory_uri() . '/assets/styles/style.css', array(), filemtime(get_template_directory() . '/assets/styles'), 'all' );
    
    // Comment reply script for threaded comments
}
add_action('wp_enqueue_scripts', 'site_scripts', 999);

// Adds site styles to the Gutenberg editor

add_action('enqueue_block_assets', 'enqueue_my_block_styles');
function enqueue_my_block_styles() {
    wp_enqueue_style('my-block-style', get_template_directory_uri() . '/assets/styles/gutenberg.css' );
}