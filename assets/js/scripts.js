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
	
	// Hamburger
	$('.mobileTrigger').click(function() {
	 $(this).toggleClass('active');
	 if($(this).hasClass('active')){
		 $('html, body').css('overflow','hidden'); 	   
	 }else {
		 $('html, body').css('overflow','auto'); 	   	   
	 }
	});
	
	
	// Offcanvas
	$('.uk-nav .uk-accordion-toggle').click(function() {
			$(this).children('span').toggleClass('up');		
		});
		
	// Überprüfen, ob das Element die Klasse 'uk-active' hat
		if ($('.current_page_parent').hasClass('uk-active')) {
				// Falls ja, füge der span unter '.uk-accordion-title' die Klasse 'up' hinzu
				$('.current_page_parent, .current-page-ancestor').children('.uk-accordion-toggle').find('span').addClass('up');
				$('.current_page_ancestor').filter('.uk-active').children('ul').removeAttr('hidden');
		}

	
 
});