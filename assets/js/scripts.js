/*
=================================================================
Filename: scripts.js
Description: additional Javascript functions
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

jQuery( document ).ready(function($) {
 
	//megamenu
	UIkit.util.on('.uk-navbar', 'beforeshow', function () {
			$('.megamenu-wrap').show();
			$('.menu-item-has-children').addClass('is-open');
	});
	UIkit.util.on('.uk-navbar', 'beforehide', function () {
			$('.megamenu-wrap').hide();
			$('.menu-item-has-children').removeClass('is-open');
	});
	
	
	// to top
	var amountScrolled = 300;
	
	$(window).scroll(function() {
			if ( $(window).scrollTop() > amountScrolled ) {
					$('a.footer-to-top').fadeIn('slow');
			} else {
					$('a.footer-to-top').fadeOut('slow');
			}
	});	
	
	$('.icon-bar .contact-wrap').click(function(){
		$(this).toggleClass('active');
	});
	
	
 
});