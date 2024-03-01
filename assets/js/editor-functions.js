/*
=================================================================
Filename: editor-functions.js
Description: add/remove block styles | add/remove panels
Author: Noël Girstmair | webundso GmbH
Last changes: 5.2.2024
=================================================================
*/

// Show all block Styles in console
wp.domReady(() => {
	// find blocks styles
	wp.blocks.getBlockTypes().forEach((block) => {
		if (_.isArray(block['styles'])) {
			console.log(block.name, _.pluck(block['styles'], 'name'));
		}
	});
	
	// remove, only without Adminimize
	// wp.data.dispatch('core/edit-post').removeEditorPanel('post-status'); // Status and Visibility
	// wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-category'); // Categories
	// wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-TAXONOMY-NAME'); // custom taxonomy
	// wp.data.dispatch('core/edit-post').removeEditorPanel('taxonomy-panel-post_tag'); // Tags
	// wp.data.dispatch('core/edit-post').removeEditorPanel('featured-image'); // Featured Image
	// wp.data.dispatch('core/edit-post').removeEditorPanel('post-excerpt'); // Excerpt
	// wp.data.dispatch('core/edit-post').removeEditorPanel('post-link'); // permalink
	// wp.data.dispatch('core/edit-post').removeEditorPanel('page-attributes'); // page attributes
	// wp.data.dispatch('core/edit-post').removeEditorPanel('discussion-panel'); // Discussion
	
	wp.blocks.unregisterBlockStyle('core/image', 'default');
	wp.blocks.unregisterBlockStyle('core/image', 'rounded');
	// quote
	wp.blocks.unregisterBlockStyle('core/quote', 'default');
	wp.blocks.unregisterBlockStyle('core/quote', 'plain');
	// button
	wp.blocks.unregisterBlockStyle('core/button', 'fill');
	wp.blocks.unregisterBlockStyle('core/button', 'outline');
	// pullquote
	wp.blocks.unregisterBlockStyle('core/pullquote', 'default');
	wp.blocks.unregisterBlockStyle('core/pullquote', 'solid-color');
	// separator
	wp.blocks.unregisterBlockStyle('core/separator', 'default');
	wp.blocks.unregisterBlockStyle('core/separator', 'wide');
	wp.blocks.unregisterBlockStyle('core/separator', 'dots');
	// table
	wp.blocks.unregisterBlockStyle('core/table', 'regular');
	wp.blocks.unregisterBlockStyle('core/table', 'stripes');
	// social-links
	wp.blocks.unregisterBlockStyle('core/social-links', 'default');
	wp.blocks.unregisterBlockStyle('core/social-links', 'logos-only');
	wp.blocks.unregisterBlockStyle('core/social-links', 'pill-shape');
	// tag-cloud
	wp.blocks.unregisterBlockStyle('core/tag-cloud', 'default');
	wp.blocks.unregisterBlockStyle('core/tag-cloud', 'outline');
	
	
	
	// remove format buttons from paragraph block 
	wp.data.select( 'core/rich-text' ).getFormatTypes()
	wp.richText.unregisterFormatType( 'core/italic' );
	wp.richText.unregisterFormatType( 'core/image' );
	wp.richText.unregisterFormatType( 'core/strikethrough' );
	wp.richText.unregisterFormatType( 'core/keyboard' );
	wp.richText.unregisterFormatType( 'core/text-color' );
	wp.richText.unregisterFormatType( 'core/code' );
	wp.richText.unregisterFormatType( 'core/subscript' );
	wp.richText.unregisterFormatType( 'core/superscript' );
	wp.richText.unregisterFormatType( 'core/footnote' );
	wp.richText.unregisterFormatType( 'core/language' );
	
});

/**** add special Block style to paragraph block ****/

// ( function( wp ) {
// 	var SpecialTagButton = function( props ) {
// 			return wp.element.createElement(
// 					wp.blockEditor.RichTextToolbarButton, {
// 							icon: 'image-flip-horizontal',
// 							title: 'Special Tag',
// 							onClick: function() {
// 									props.onChange( wp.richText.toggleFormat(
// 											props.value,
// 											{ type: 'special-tag/output' }
// 									) );
// 							},
// 							isActive: props.isActive,
// 					}
// 			);
// 	}
// 	wp.richText.registerFormatType(
// 			'special-tag/output', {
// 					title: 'Special Tag',
// 					tagName: 'span',
// 					className: 'special-tag',
// 					edit: SpecialTagButton,
// 			}
// 	);
// } )( window.wp );

