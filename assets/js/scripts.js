/*
=================================================================
Filename: scripts.js
Description: additional Javascript functions
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

jQuery( document ).ready(function($) {

	//  target _blank external links & pdf
	$('a[href^="http://"], a[href^="https://"').not('a[href*="' + location.hostname + '"]').attr('target', '_blank').addClass('linkIcon-external'); 
	$('a[href$=".pdf"]').addClass('linkIcon-pdf').attr('target', '_blank');
	
	
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