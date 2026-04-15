<?php
defined('ABSPATH') || exit;

/**
 * WUS_TokenSheet
 *
 * Zeigt alle --t-* Design Tokens als Cheat Sheet im Frontend.
 * Nur für eingeloggte Admins sichtbar (manage_options).
 *
 * Schalter:
 * - WUS_TOKEN_SHEET (bool): true = aktiv, false = deaktiviert (default)
 */
class WUS_TokenSheet
{
	public static function init(): void
	{
		if (!defined('WUS_TOKEN_SHEET') || !WUS_TOKEN_SHEET) {
			return;
		}

		add_action('wp_footer', [__CLASS__, 'render'], 99);
	}

	public static function render(): void
	{
		if (!current_user_can('manage_options')) {
			return;
		}
		?>
		<!-- WUS Design Token Sheet -->
		<div id="wus-ts-toggle" title="Design Tokens" aria-label="Design Token Sheet öffnen">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
				<circle cx="12" cy="12" r="3"/><circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
				<circle cx="5" cy="12" r="1.5"/><circle cx="19" cy="12" r="1.5"/>
			</svg>
		</div>

		<div id="wus-ts-panel" role="dialog" aria-modal="true" aria-label="Design Token Sheet">
			<div id="wus-ts-header">
				<span id="wus-ts-title">Design Tokens</span>
				<div id="wus-ts-tabs">
					<button class="wus-tab active" data-tab="colors">Farben</button>
					<button class="wus-tab" data-tab="shades">Schattierung</button>
					<button class="wus-tab" data-tab="spacing">Abstände</button>
					<button class="wus-tab" data-tab="fonts">Typografie</button>
				</div>
				<button id="wus-ts-close" aria-label="Schliessen">✕</button>
			</div>

			<div id="wus-ts-body">
				<!-- FARBEN -->
				<div class="wus-pane active" id="wus-pane-colors">
					<div id="wus-color-grid"></div>
				</div>
				<!-- SCHATTIERUNG -->
				<div class="wus-pane" id="wus-pane-shades">
					<div id="wus-shade-controls">
						<div class="wus-ctrl-row">
							<label>Farbe</label>
							<select id="wus-shade-select"></select>
						</div>
						<div class="wus-ctrl-row">
							<label>Schwarz <span id="wus-dark-val">0%</span></label>
							<input type="range" id="wus-shade-dark" min="0" max="80" value="0" step="5">
						</div>
						<div class="wus-ctrl-row">
							<label>Weiss <span id="wus-light-val">0%</span></label>
							<input type="range" id="wus-shade-light" min="0" max="80" value="0" step="5">
						</div>
					</div>
					<div id="wus-shade-strip"></div>
					<div id="wus-shade-result">← Chip anklicken zum Kopieren</div>
				</div>
				<!-- ABSTÄNDE -->
				<div class="wus-pane" id="wus-pane-spacing">
					<div id="wus-space-list"></div>
				</div>
				<!-- TYPOGRAFIE -->
				<div class="wus-pane" id="wus-pane-fonts">
					<div id="wus-font-list"></div>
				</div>
			</div>

			<div id="wus-ts-toast"></div>
		</div>

		<style>
		/* ── Toggle Button ───────────────────────────── */
		#wus-ts-toggle {
			position: fixed;
			bottom: 24px;
			right: 24px;
			z-index: 99998;
			width: 44px;
			height: 44px;
			border-radius: 50%;
			background: #e07a5f;
			color: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
			cursor: pointer;
			box-shadow: 0 4px 16px rgba(0,0,0,0.3);
			transition: transform .2s, box-shadow .2s;
			user-select: none;
		}
		#wus-ts-toggle:hover { transform: scale(1.08); box-shadow: 0 6px 24px rgba(0,0,0,0.4); }
		/* ── Panel ───────────────────────────────────── */
		#wus-ts-panel {
			position: fixed;
			bottom: 80px;
			right: 24px;
			z-index: 99999;
			width: 540px;
			max-width: calc(100vw - 32px);
			max-height: 78vh;
			background: #1a1a1c;
			border: 1px solid #333336;
			border-radius: 12px;
			box-shadow: 0 20px 60px rgba(0,0,0,0.6);
			display: flex;
			flex-direction: column;
			font-family: -apple-system, system-ui, sans-serif;
			font-size: 13px;
			color: #e8e4d4;
			opacity: 0;
			pointer-events: none;
			transform: translateY(12px) scale(0.98);
			transition: opacity .2s ease, transform .2s ease;
		}
		#wus-ts-panel.open {
			opacity: 1;
			pointer-events: all;
			transform: translateY(0) scale(1);
		}
		/* ── Header ──────────────────────────────────── */
		#wus-ts-header {
			display: flex;
			align-items: center;
			gap: 8px;
			padding: 12px 16px 0;
			border-bottom: 1px solid #333336;
			padding-bottom: 0;
			flex-shrink: 0;
		}
		#wus-ts-title {
			font-size: 11px;
			font-weight: 600;
			letter-spacing: .1em;
			text-transform: uppercase;
			color: #888;
			margin-right: 4px;
			white-space: nowrap;
		}
		#wus-ts-tabs {
			display: flex;
			gap: 2px;
			flex: 1;
			overflow-x: auto;
		}
		.wus-tab {
			background: none;
			border: none;
			color: #888;
			font-size: 12px;
			padding: 8px 12px;
			cursor: pointer;
			border-bottom: 2px solid transparent;
			margin-bottom: -1px;
			white-space: nowrap;
			transition: color .15s, border-color .15s;
		}
		.wus-tab:hover { color: #e8e4d4; }
		.wus-tab.active { color: #e07a5f; border-bottom-color: #e07a5f; }
		#wus-ts-close {
			background: none;
			border: none;
			color: #555;
			font-size: 16px;
			cursor: pointer;
			padding: 4px 8px;
			line-height: 1;
			transition: color .15s;
			margin-bottom: 4px;
		}
		#wus-ts-close:hover { color: #e8e4d4; }
		/* ── Body / Panes ────────────────────────────── */
		#wus-ts-body { overflow-y: auto; flex: 1; padding: 16px; }
		.wus-pane { display: none; }
		.wus-pane.active { display: block; }
		/* ── Color Grid ──────────────────────────────── */
		#wus-color-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
			gap: 8px;
		}
		.wus-color-card {
			border-radius: 6px;
			overflow: hidden;
			border: 1px solid #333336;
			cursor: pointer;
			transition: transform .15s;
		}
		.wus-color-card:hover { transform: translateY(-2px); }
		.wus-swatch {
			height: 56px;
			position: relative;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.wus-swatch-hint {
			font-size: 10px;
			font-weight: 600;
			letter-spacing: .04em;
			opacity: 0;
			transition: opacity .15s;
		}
		.wus-color-card:hover .wus-swatch-hint { opacity: 1; }
		.wus-color-meta {
			background: #242426;
			padding: 7px 10px;
		}
		.wus-color-token {
			font-family: "SF Mono", "Fira Code", monospace;
			font-size: 10px;
			color: #e07a5f;
			display: block;
			margin-bottom: 2px;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		.wus-color-hex {
			font-family: "SF Mono", "Fira Code", monospace;
			font-size: 11px;
			color: #e8e4d4;
			cursor: pointer;
		}
		.wus-color-hex:hover { text-decoration: underline; }
		/* ── Shade Tester ────────────────────────────── */
		#wus-shade-controls { margin-bottom: 14px; }
		.wus-ctrl-row {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 8px;
		}
		.wus-ctrl-row label {
			font-size: 11px;
			color: #888;
			width: 80px;
			flex-shrink: 0;
		}
		#wus-shade-select {
			background: #242426;
			border: 1px solid #333336;
			color: #e8e4d4;
			padding: 4px 8px;
			border-radius: 4px;
			font-size: 12px;
			cursor: pointer;
		}
		input[type=range] {
			flex: 1;
			accent-color: #e07a5f;
			cursor: pointer;
		}
		#wus-shade-strip {
			display: flex;
			gap: 4px;
			flex-wrap: wrap;
			margin-bottom: 10px;
		}
		.wus-shade-chip {
			width: 40px;
			height: 40px;
			border-radius: 5px;
			border: 1px solid rgba(255,255,255,.08);
			display: flex;
			align-items: flex-end;
			justify-content: center;
			padding-bottom: 3px;
			font-size: 9px;
			font-family: monospace;
			cursor: pointer;
			transition: transform .1s;
		}
		.wus-shade-chip:hover { transform: scale(1.12); z-index: 1; position: relative; }
		#wus-shade-result {
			font-family: monospace;
			font-size: 11px;
			color: #888;
			min-height: 18px;
			margin-top: 4px;
			word-break: break-all;
		}
		/* ── Spacing ─────────────────────────────────── */
		.wus-space-row {
			display: flex;
			align-items: center;
			gap: 12px;
			margin-bottom: 8px;
			cursor: pointer;
		}
		.wus-space-row:hover .wus-space-token { text-decoration: underline; }
		.wus-space-token {
			font-family: monospace;
			font-size: 11px;
			color: #e07a5f;
			width: 100px;
			flex-shrink: 0;
		}
		.wus-space-track {
			flex: 1;
			background: #242426;
			border-radius: 3px;
			height: 22px;
			display: flex;
			align-items: center;
			padding: 3px;
			border: 1px solid #333336;
		}
		.wus-space-bar {
			background: rgba(224,122,95,.45);
			border-radius: 2px;
			height: 100%;
			min-width: 4px;
		}
		.wus-space-val {
			font-size: 11px;
			color: #888;
			width: 30px;
			text-align: right;
			flex-shrink: 0;
		}
		/* ── Typografie ──────────────────────────────── */
		.wus-fs-row {
			display: flex;
			align-items: baseline;
			gap: 12px;
			padding: 10px 0;
			border-bottom: 1px solid #242426;
		}
		.wus-fs-row:last-child { border-bottom: none; }
		.wus-fs-meta {
			width: 105px;
			flex-shrink: 0;
			cursor: pointer;
		}
		.wus-fs-meta:hover .wus-fs-token { text-decoration: underline; }
		.wus-fs-token {
			font-family: monospace;
			font-size: 10px;
			color: #e07a5f;
			display: block;
		}
		.wus-fs-px { font-size: 10px; color: #888; }
		.wus-fs-samples { flex: 1; min-width: 0; }
		.wus-fs-base    { color: #e8e4d4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
		.wus-fs-heading { color: #e8e4d4; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
		.wus-font-block { margin-bottom: 18px; }
		.wus-font-label {
			font-family: monospace;
			font-size: 10px;
			color: #888;
			margin-bottom: 6px;
			display: block;
		}
		/* ── Toast ───────────────────────────────────── */
		#wus-ts-toast {
			position: absolute;
			bottom: 12px;
			left: 50%;
			transform: translateX(-50%) translateY(8px);
			background: #e07a5f;
			color: #fff;
			padding: 5px 16px;
			border-radius: 100px;
			font-size: 11px;
			font-weight: 600;
			opacity: 0;
			transition: opacity .2s, transform .2s;
			pointer-events: none;
			white-space: nowrap;
		}
		#wus-ts-toast.show {
			opacity: 1;
			transform: translateX(-50%) translateY(0);
		}
		</style>

		<script>
		(function () {
			'use strict';

			function getVar(name) {
				return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
			}
			function hexToRgb(hex) {
				hex = hex.replace(/[^0-9a-f]/gi, '').slice(0, 8);
				if (hex.length === 3 || hex.length === 4) hex = hex.replace(/(.)/g, '$1$1');
				hex = hex.slice(0, 6);
				const n = parseInt(hex, 16);
				return [(n >> 16) & 255, (n >> 8) & 255, n & 255];
			}
			function mixHex(hex, target, pct) {
				const [r1,g1,b1] = hexToRgb(hex);
				const [r2,g2,b2] = hexToRgb(target);
				const t = pct / 100;
				const r = Math.round(r1*(1-t)+r2*t);
				const g = Math.round(g1*(1-t)+g2*t);
				const b = Math.round(b1*(1-t)+b2*t);
				return '#' + [r,g,b].map(x => x.toString(16).padStart(2,'0')).join('');
			}
			function luminance(hex) {
				const [r,g,b] = hexToRgb(hex).map(c => {
					c /= 255;
					return c <= 0.03928 ? c/12.92 : Math.pow((c+0.055)/1.055, 2.4);
				});
				return 0.2126*r + 0.7152*g + 0.0722*b;
			}
			function textOn(hex) {
				try { return luminance(hex) > 0.35 ? '#1a1a1c' : '#f4f1de'; }
				catch(e) { return '#fff'; }
			}
			function resolveColor(val) {
				if (!val || !val.includes('color-mix')) return val;
				const el = document.createElement('div');
				el.style.cssText = 'position:absolute;width:1px;height:1px;background:' + val;
				document.body.appendChild(el);
				const computed = getComputedStyle(el).backgroundColor;
				document.body.removeChild(el);
				const m = computed.match(/rgb\((\d+),\s*(\d+),\s*(\d+)\)/);
				if (m) return '#' + [m[1],m[2],m[3]].map(x => parseInt(x).toString(16).padStart(2,'0')).join('');
				return val;
			}
			function toast(msg) {
				const t = document.getElementById('wus-ts-toast');
				t.textContent = msg;
				t.classList.add('show');
				clearTimeout(t._t);
				t._t = setTimeout(() => t.classList.remove('show'), 1800);
			}
			function copy(text) {
				navigator.clipboard.writeText(text).catch(() => {});
				toast('Kopiert: ' + text);
			}

			const colorTokens = [
				{ token: '--t-color-primary',         label: 'Buttons, Links, Active' },
				{ token: '--t-color-secondary',        label: 'Akzente' },
				{ token: '--t-color-muted',            label: 'Trennlinien, Hilfen' },
				{ token: '--t-color-danger',           label: 'Fehler, Warnungen' },
				{ token: '--t-color-text',             label: 'Fliesstext' },
				{ token: '--t-color-bg',               label: 'Seitenhintergrund' },
				{ token: '--t-color-border',           label: 'Rahmen' },
				{ token: '--t-color-surface',          label: 'Karten, Flächen' },
				{ token: '--t-color-primary-hover',    label: 'Primary hover' },
				{ token: '--t-color-primary-active',   label: 'Primary active' },
				{ token: '--t-color-secondary-hover',  label: 'Secondary hover' },
				{ token: '--t-color-secondary-active', label: 'Secondary active' },
				{ token: '--t-color-danger-hover',     label: 'Danger hover' },
				{ token: '--t-color-danger-active',    label: 'Danger active' },
				{ token: '--t-editor-outline',         label: 'Editor outline' },
				{ token: '--t-editor-fill',            label: 'Editor fill' },
			];
			const spaceTokens = ['--t-space-xs','--t-space-s','--t-space-m','--t-space-l','--t-space-xl'];
			const fsTokens    = ['--t-fs-xs','--t-fs-s','--t-fs-m','--t-fs-l','--t-fs-xl','--t-fs-xxl'];

			function buildColors() {
				const grid = document.getElementById('wus-color-grid');
				grid.innerHTML = '';
				colorTokens.forEach(c => {
					const raw = getVar(c.token);
					const hex = resolveColor(raw);
					const fg  = textOn(hex.startsWith('#') ? hex : '#888888');
					const card = document.createElement('div');
					card.className = 'wus-color-card';
					card.innerHTML = `
						<div class="wus-swatch" style="background:${hex}">
							<span class="wus-swatch-hint" style="color:${fg}">kopieren</span>
						</div>
						<div class="wus-color-meta">
							<span class="wus-color-token" title="${c.token}">${c.token}</span>
							<span class="wus-color-hex">${hex}</span>
						</div>`;
					card.querySelector('.wus-swatch').addEventListener('click', () => copy(c.token));
					card.querySelector('.wus-color-hex').addEventListener('click', e => { e.stopPropagation(); copy(hex); });
					grid.appendChild(card);
				});
			}
			function buildShadeSelect() {
				const sel = document.getElementById('wus-shade-select');
				sel.innerHTML = '';
				colorTokens.slice(0,8).forEach(c => {
					const raw = getVar(c.token);
					const hex = resolveColor(raw);
					if (!hex.startsWith('#')) return;
					const opt = document.createElement('option');
					opt.value = hex;
					opt.textContent = c.token.replace('--t-color-', '') + ' (' + hex + ')';
					sel.appendChild(opt);
				});
			}
			function buildShades() {
				const hex   = document.getElementById('wus-shade-select').value;
				const dark  = parseInt(document.getElementById('wus-shade-dark').value);
				const light = parseInt(document.getElementById('wus-shade-light').value);
				document.getElementById('wus-dark-val').textContent  = dark  + '%';
				document.getElementById('wus-light-val').textContent = light + '%';
				const strip = document.getElementById('wus-shade-strip');
				strip.innerHTML = '';
				[0,5,10,15,20,30,40,50,60,70,80].forEach(p => {
					const col  = p === 0 ? hex : mixHex(hex, '#000000', p);
					const chip = document.createElement('div');
					chip.className = 'wus-shade-chip';
					chip.style.background = col;
					chip.style.color = textOn(col);
					chip.textContent = p + '%';
					chip.title = p === 0 ? hex : `color-mix(in srgb, ${hex}, #000 ${p}%)`;
					chip.addEventListener('click', () => {
						const val = p === 0 ? hex : `color-mix(in srgb, var(--t-color-...), #000 ${p}%)`;
						document.getElementById('wus-shade-result').textContent = '→ ' + chip.title;
						copy(val);
					});
					strip.appendChild(chip);
				});
				const sep = document.createElement('div');
				sep.style.cssText = 'width:1px;background:#333336;margin:0 2px;border-radius:1px;align-self:stretch';
				strip.appendChild(sep);
				[5,10,15,20,30,40,50,60,70,80].forEach(p => {
					const col  = mixHex(hex, '#ffffff', p);
					const chip = document.createElement('div');
					chip.className = 'wus-shade-chip';
					chip.style.background = col;
					chip.style.color = textOn(col);
					chip.textContent = '+' + p + '%';
					chip.title = `color-mix(in srgb, ${hex}, #fff ${p}%)`;
					chip.addEventListener('click', () => {
						const val = `color-mix(in srgb, var(--t-color-...), #fff ${p}%)`;
						document.getElementById('wus-shade-result').textContent = '→ ' + chip.title;
						copy(val);
					});
					strip.appendChild(chip);
				});
			}
			function buildSpacing() {
				const list = document.getElementById('wus-space-list');
				list.innerHTML = '';
				const max = 40;
				spaceTokens.forEach(token => {
					const val = getVar(token);
					const px  = parseInt(val) || 0;
					const row = document.createElement('div');
					row.className = 'wus-space-row';
					row.title = 'Token kopieren';
					row.innerHTML = `
						<span class="wus-space-token">${token}</span>
						<div class="wus-space-track">
							<div class="wus-space-bar" style="width:${Math.min(px/max*100,100)}%"></div>
						</div>
						<span class="wus-space-val">${val}</span>`;
					row.addEventListener('click', () => copy(token));
					list.appendChild(row);
				});
			}
			function buildFonts() {
				const list = document.getElementById('wus-font-list');
				list.innerHTML = '';
				const fsSection = document.createElement('div');
				fsSection.style.marginBottom = '20px';
				fsTokens.forEach(token => {
					const val         = getVar(token);
					const fontBase    = getVar('--t-font-base');
					const fontHeading = getVar('--t-font-heading');
					const row = document.createElement('div');
					row.className = 'wus-fs-row';
					row.innerHTML = `
						<div class="wus-fs-meta">
							<span class="wus-fs-token">${token}</span>
							<span class="wus-fs-px">${val}</span>
						</div>
						<div class="wus-fs-samples">
							<div class="wus-fs-base"    style="font-size:${val}">Edition Muttern</div>
							<div class="wus-fs-heading" style="font-size:${val};font-weight:400">Edition Muttern</div>
						</div>`;
					row.querySelector('.wus-fs-base').style.setProperty('font-family', 'var(--t-font-base)');
					row.querySelector('.wus-fs-heading').style.setProperty('font-family', 'var(--t-font-heading)');
					row.querySelector('.wus-fs-meta').addEventListener('click', () => copy(token));
					fsSection.appendChild(row);
				});
				list.appendChild(fsSection);
				[
					{ token: '--t-font-base',    label: 'Base' },
					{ token: '--t-font-heading', label: 'Heading' },
				].forEach(f => {
					const val   = getVar(f.token);
					const block = document.createElement('div');
					block.className = 'wus-font-block';
					block.innerHTML = `
						<span class="wus-font-label" style="cursor:pointer" title="Kopieren">${f.token}</span>
						<div style="font-size:20px;color:#e8e4d4">
							Edition Muttern — Aa Bb Cc 0123
						</div>`;
					block.querySelector('.wus-font-label').addEventListener('click', () => copy(f.token));
					block.querySelector('div').style.setProperty('font-family', 'var(' + f.token + ')');
					list.appendChild(block);
				});
			}

			// Tabs
			document.querySelectorAll('.wus-tab').forEach(btn => {
				btn.addEventListener('click', () => {
					document.querySelectorAll('.wus-tab').forEach(b => b.classList.remove('active'));
					document.querySelectorAll('.wus-pane').forEach(p => p.classList.remove('active'));
					btn.classList.add('active');
					document.getElementById('wus-pane-' + btn.dataset.tab).classList.add('active');
				});
			});

			// Open / Close
			const panel  = document.getElementById('wus-ts-panel');
			const toggle = document.getElementById('wus-ts-toggle');
			toggle.addEventListener('click', () => {
				const opening = !panel.classList.contains('open');
				panel.classList.toggle('open');
				if (opening) {
					buildColors();
					buildShadeSelect();
					buildShades();
					buildSpacing();
					buildFonts();
				}
			});
			document.getElementById('wus-ts-close').addEventListener('click', () => {
				panel.classList.remove('open');
			});

			// Shade-Controls
			document.getElementById('wus-shade-select').addEventListener('change', buildShades);
			document.getElementById('wus-shade-dark').addEventListener('input',   buildShades);
			document.getElementById('wus-shade-light').addEventListener('input',  buildShades);

			// ESC schliesst Panel
			document.addEventListener('keydown', e => {
				if (e.key === 'Escape') panel.classList.remove('open');
			});
		})();
		</script>
		<!-- /WUS Design Token Sheet -->
		<?php
	}
}
