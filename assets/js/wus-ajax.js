/*
=================================================================
Filename: wus-ajax.js
Description: load posts with ajax
Author: Noël Girstmair | webundso GmbH
Last changes: 3.6.2024
=================================================================
*/


 UIkit.util.on('#ueber-uns', 'show', function () {
		 $("#post-container").html("content loading");
		$.ajax({
				url: my_ajax_obj.ajax_url,
				method: 'POST',
				data: {
						action: 'load_post_content',
						post_id: 9
				},
				success: function(response) {
						$('#post-container').html(response);
				}
		});

		 
 });
 
 // oder mit trigger
 // jQuery(document).ready(function($) {
	// 	 $('#load-post-button').on('click', function() {
	// 			 var post_id = $(this).data('post-id');
 // 
	// 			 $.ajax({
	// 					 url: my_ajax_obj.ajax_url,
	// 					 method: 'POST',
	// 					 data: {
	// 							 action: 'load_post_content',
	// 							 post_id: post_id
	// 					 },
	// 					 success: function(response) {
	// 							 $('#post-content-div').html(response);
	// 					 }
	// 			 });
	// 	 });
 // });
 
 // Trigger:
 /* <button id="load-post-button" data-post-id="1">Load Post</button>
 <div id="post-content-div"></div> */