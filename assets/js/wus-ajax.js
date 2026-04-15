/*
=================================================================
Filename: wus-ajax.js
Description: AJAX Load More for blog posts
Author: Noël Girstmair | webundso GmbH
Last changes: 18.3.2026
=================================================================
*/

jQuery(document).ready(function ($) {
	var $btn       = $('#wus-load-more');
	var $container = $('#wus-blog-posts');

	if (!$btn.length || !$container.length) {
		return;
	}

	var currentPage = 1;
	var maxPages    = parseInt($btn.data('max-pages'), 10) || 1;

	// Button verstecken, wenn keine weiteren Seiten vorhanden
	if (currentPage >= maxPages) {
		$btn.hide();
	}

	$btn.on('click', function () {
		var $self = $(this);

		$self.prop('disabled', true);
		$self.text(wusAjax.loading);

		$.ajax({
			url:    wusAjax.ajaxUrl,
			method: 'POST',
			data: {
				action: 'wus_load_more',
				nonce:  wusAjax.nonce,
				paged:  currentPage + 1,
			},
			success: function (response) {
				if (!response.success) {
					$self.prop('disabled', false);
					$self.text($self.data('label'));
					return;
				}

				$container.append(response.data.html);

				// UIkit-Komponenten in neuen Elementen initialisieren
				if (window.UIkit) {
					UIkit.update($container[0]);
				}

				currentPage = parseInt(response.data.paged, 10);
				maxPages    = parseInt(response.data.max_pages, 10);

				if (currentPage >= maxPages) {
					$self.text(wusAjax.noMore);
					$self.prop('disabled', true);
				} else {
					$self.prop('disabled', false);
					$self.text($self.data('label'));
				}
			},
			error: function () {
				$self.prop('disabled', false);
				$self.text($self.data('label'));
			},
		});
	});
});
