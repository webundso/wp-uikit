/*
=================================================================
Filename: editor-functions.js
Description: Gutenberg editor tweaks (styles, formats, panels)
Author: Noël Girstmair | webundso GmbH
Last changes: 05.02.2026
=================================================================
*/

// Blockliste ausgeben
wp.domReady(() => {
	const blocks = wp.blocks.getBlockTypes().map((b) => ({
		name: b.name,
		title: b.title,
		description: b.description,
		category: b.category,
	}));

	// sortiert nach name
	blocks.sort((a,b) => (a.name > b.name ? 1 : -1));

	console.table(blocks);

	// Wenn du es copy/paste-faehig brauchst:
	console.log(JSON.stringify(blocks, null, 2));
});


/* global WUS_EDITOR */

(function () {
	if (!window.wp || !wp.domReady) return;

	const cfg = window.WUS_EDITOR || {};

	const has = (obj, key) => obj && Object.prototype.hasOwnProperty.call(obj, key);

	const safeUnregisterBlockStyle = (blockName, styleName) => {
		if (!wp.blocks || !wp.blocks.unregisterBlockStyle) return;
		try {
			wp.blocks.unregisterBlockStyle(blockName, styleName);
		} catch (e) {
			// ignore (style might not exist in this WP version)
		}
	};

	const safeRemovePanel = (panelName) => {
		if (!wp.data || !wp.data.dispatch) return;
		const editor = wp.data.dispatch('core/edit-post');
		if (!editor || !editor.removeEditorPanel) return;

		try {
			editor.removeEditorPanel(panelName);
		} catch (e) {
			// ignore
		}
	};

	const safeUnregisterFormat = (formatName) => {
		if (!wp.richText || !wp.richText.unregisterFormatType) return;
		try {
			wp.richText.unregisterFormatType(formatName);
		} catch (e) {
			// ignore
		}
	};

	const safeDisableFullscreen = () => {
		if (!wp.data || !wp.data.select || !wp.data.dispatch) return;

		const sel = wp.data.select('core/edit-post');
		const dis = wp.data.dispatch('core/edit-post');

		if (!sel || !dis || !sel.isFeatureActive || !dis.toggleFeature) return;

		try {
			if (sel.isFeatureActive('fullscreenMode')) {
				dis.toggleFeature('fullscreenMode');
			}
		} catch (e) {
			// ignore
		}
	};

	const logBlockStyles = () => {
		if (!wp.blocks || !wp.blocks.getBlockTypes) return;

		try {
			wp.blocks.getBlockTypes().forEach((block) => {
				const styles = Array.isArray(block.styles) ? block.styles.map((s) => s.name) : [];
				if (styles.length) {
					console.log(`[WUS] ${block.name}:`, styles);
				}
			});
		} catch (e) {
			// ignore
		}
	};

	// Defaults (falls cfg nicht gesetzt)
	const defaultStylesToRemove = [
		['core/image', 'default'],
		['core/image', 'rounded'],
		['core/quote', 'default'],
		['core/quote', 'plain'],
		['core/button', 'fill'],
		['core/button', 'outline'],
		['core/pullquote', 'default'],
		['core/pullquote', 'solid-color'],
		['core/separator', 'default'],
		['core/separator', 'wide'],
		['core/separator', 'dots'],
		['core/table', 'regular'],
		['core/table', 'stripes'],
		['core/social-links', 'default'],
		['core/social-links', 'logos-only'],
		['core/social-links', 'pill-shape'],
		['core/tag-cloud', 'default'],
		['core/tag-cloud', 'outline']
	];

	const defaultFormatsToRemove = [
		'core/italic',
		'core/strikethrough',
		'core/keyboard',
		'core/text-color',
		'core/code',
		'core/subscript',
		'core/superscript',
		'core/footnote',
		'core/language'
	];

	wp.domReady(() => {
		// optional: log styles
		if (cfg.logBlockStyles === true) {
			logBlockStyles();
		}

		// fullscreen off
		if (cfg.disableFullscreen === true) {
			safeDisableFullscreen();
		}

		// remove panels
		if (cfg.removePanels === true && Array.isArray(cfg.panelsToRemove)) {
			cfg.panelsToRemove.forEach(safeRemovePanel);
		}

		// remove block styles
		if (cfg.removeBlockStyles === true) {
			const list = Array.isArray(cfg.blockStylesToRemove) && cfg.blockStylesToRemove.length
				? cfg.blockStylesToRemove
				: defaultStylesToRemove;

			list.forEach(([blockName, styleName]) => safeUnregisterBlockStyle(blockName, styleName));
		}

		// remove formats
		if (cfg.removeFormats === true) {
			const list = Array.isArray(cfg.formatsToRemove) && cfg.formatsToRemove.length
				? cfg.formatsToRemove
				: defaultFormatsToRemove;

			list.forEach(safeUnregisterFormat);
		}
	});
})();

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

// eigene Blockstyles
// wp.blocks.registerBlockStyle( 'core/heading', [
// 	{
// 		name: 'default',
// 		label: 'Default',
// 		isDefault: true,
// 	},
// 	{
// 		name: 'alt',
// 		label: 'Alternate',
// 	}
// ]);
