/*
=================================================================
Filename: scripts.js
Description: additional Javascript functions
Author: Noël Girstmair | webundso GmbH
Last changes: 9.2.2024
=================================================================
*/

jQuery(document).ready(function ($) {

		// ---------------------------------------------------------------------
		// External Links + PDF
		// Effekt:
		// - externe Links: target _blank + rel noopener + Icon-Klasse
		// - PDF Links: target _blank + rel noopener + Icon-Klasse
		// ---------------------------------------------------------------------

		var host = location.hostname;

		// Externe http(s) Links (nicht gleiche Domain)
		$('a[href^="http://"], a[href^="https://"]')
				.not('a[href*="' + host + '"]')
				.attr('target', '_blank')
				.attr('rel', 'noopener noreferrer')
				.addClass('linkIcon-external');

		// PDF Links (auch mit Query/Hash)
		$('a[href*=".pdf"]')
				.filter(function () {
						var href = ($(this).attr('href') || '').toLowerCase();
						return href.indexOf('.pdf') !== -1;
				})
				.attr('target', '_blank')
				.attr('rel', 'noopener noreferrer')
				.addClass('linkIcon-pdf');


		// ---------------------------------------------------------------------
		// To top visibility
		// Effekt: "Back to top" Link ein-/ausblenden ab Scroll-Schwelle.
		// ---------------------------------------------------------------------

		var amountScrolled = 300;

		$(window).on('scroll', function () {
				if ($(window).scrollTop() > amountScrolled) {
						$('a.footer-to-top').fadeIn('slow');
				} else {
						$('a.footer-to-top').fadeOut('slow');
				}
		});


		// ---------------------------------------------------------------------
		// Contact icon bar
		// Effekt: toggelt active state auf Klick.
		// ---------------------------------------------------------------------

		$('.icon-bar .contact-wrap').on('click', function () {
				$(this).toggleClass('active');
		});


		// ---------------------------------------------------------------------
		// Hamburger
		// Effekt: toggelt active + verhindert Scrollen im offenen Zustand.
		// ---------------------------------------------------------------------

		$('.mobileTrigger').on('click', function () {
				var $btn = $(this);
				$btn.toggleClass('active');
				$btn.attr('aria-expanded', $btn.hasClass('active') ? 'true' : 'false');

				if ($btn.hasClass('active')) {
						$('html, body').css('overflow', 'hidden');
				} else {
						$('html, body').css('overflow', 'auto');
				}
		});


		// ---------------------------------------------------------------------
		// Offcanvas accordion arrows (UIkit)
		// Effekt: Pfeil-Icon toggeln (up/down).
		// ---------------------------------------------------------------------

		$('.uk-nav .uk-accordion-toggle').on('click', function () {
				$(this).children('span').toggleClass('up');
		});

		// Aktive Parent/Ancestor: Pfeil setzen + Submenu öffnen
		if ($('.current_page_parent').hasClass('uk-active')) {
				$('.current_page_parent, .current-page-ancestor')
						.children('.uk-accordion-toggle')
						.find('span')
						.addClass('up');

				$('.current_page_ancestor')
						.filter('.uk-active')
						.children('ul')
						.removeAttr('hidden');
		}

		// Offcanvas close: Button-Active-State zurücksetzen
		$('#offcanvas-mobile').on('hide.uk.offcanvas', function () {
				$('.mobileTrigger').removeClass('active');
				$('html, body').css('overflow', 'auto');
		});


		// ---------------------------------------------------------------------
		// Responsive sizing hooks (derzeit auskommentiert)
		// Effekt: Placeholder für “square boxes” etc.
		// ---------------------------------------------------------------------

		// $(window).on('resize', function () {
		// 		// Beispiele:
		// 		// $('.akbox').height($('.akbox').width());
		// }).trigger('resize');
		// ⚠ Auskommentiert: leerer resize-Listener registriert unnötig einen Event-Handler bei jedem Seitenaufruf.
		// Nur aktivieren wenn tatsächlich resize-Logik benötigt wird.


		// ---------------------------------------------------------------------
		// Gallery Block + UIkit Lightbox
		// Effekt: WP Gallery wird UIkit Lightbox, Bilder werden verlinkt.
		// ---------------------------------------------------------------------

		$('.wp-block-gallery').each(function () {
				var $gallery = $(this);

				$gallery.attr('uk-lightbox', 'animation: fade');

				$gallery.find('figure.wp-block-image').each(function () {
						var $figure = $(this);
						var $img = $figure.find('img').first();

						// Nicht doppelt wrappen
						if ($img.length === 0 || $img.parent('a').length) {
								return;
						}

						var $link = $('<a>').attr({
								href: $img.attr('src'),
								'data-caption': $img.attr('alt') || ''
						});

						$img.wrap($link);
				});
		});


	// ---------------------------------------------------------------------
	// Nav Suche: aria-expanded synchronisieren (UIkit Drop Events)
	// Effekt: Screenreader weiss ob das Suchfeld offen oder geschlossen ist.
	// ---------------------------------------------------------------------

	UIkit.util.on('#wus-nav-search-drop', 'show', function () {
			$('.wus-nav-search-wrap .uk-navbar-toggle').attr('aria-expanded', 'true');
	});

	UIkit.util.on('#wus-nav-search-drop', 'hide', function () {
			$('.wus-nav-search-wrap .uk-navbar-toggle').attr('aria-expanded', 'false');
	});

});
