# webundso WP UIKit Theme (Classic + Tokens + ACF Blocks)

Dieses Theme ist ein klassisches (PHP) WordPress Theme mit UIkit, ACF Blocks und einem Token-System via `theme.json`.
Ziel: schnell neue Projekte starten, konsistent bleiben, Redaktionsfreiheit kontrollieren, CI sauber abbilden.

---

## Voraussetzungen

- WordPress aktuell (empfohlen: aktuellste Minor/Stable)
- ACF Pro (für Blocks)
- Plugin: **WP-SCSS** (Server-seitiges SASS Compiling)
- UIkit liegt im Theme (z.B. `uikit/` oder als Submodule)
- Optional: WPML

---

## Ordnerstruktur (wichtig)

- `assets/styles/`
  - `site.scss` → wird zu `site.css` kompiliert (Frontend)
  - `gutenberg.scss` → wird zu `gutenberg.css` kompiliert (Editor)
- `assets/blocks/*/`
  - je Block: `block.json`, `render.php`, optional `assets/…`
- `acf-json/`
  - ACF Field Group JSON (Vorlagen / wiederverwendbar)
- `theme.json`
  - Design Tokens (Farben, Schriften, Spacing, Font Sizes)

---

## Setup: 1) Plugins

1. ACF Pro aktivieren
2. WP-SCSS aktivieren
3. (Optional) WPML aktivieren

---

## Setup: 2) WP-SCSS konfigurieren

WP-SCSS so konfigurieren, dass folgende Dateien kompiliert werden:

- `assets/styles/site.scss` → `assets/styles/site.css`
- `assets/styles/gutenberg.scss` → `assets/styles/gutenberg.css`

Wichtig:
- Das Theme lädt **nur** `site.css` im Frontend.
- Im Editor wird `gutenberg.css` geladen (siehe `gutenberg.php`).

---

## Setup: 3) UIkit Build Strategie

Dieses Theme nutzt UIkit via SCSS-Import (nicht fertiges dist CSS), damit Metriken/Mechanik über Sass-Variablen angepasst werden können.

Empfohlene Reihenfolge in `site.scss`:

1) Tools / Mixins  
2) Fonts (lokal, pro Projekt)  
3) UIkit Sass Overrides (nur Mechanik)  
4) UIkit Build  
5) Token-Bridge + Theme Layer

## wp-config.php Vorlage

Im Theme liegt eine Vorlage:
- `wp-config.example.php`

Vorgehen bei neuen Projekten:
1. Datei öffnen
2. Relevante Konstanten in die echte `wp-config.php` kopieren
3. Environment (`WP_ENVIRONMENT_TYPE`) setzen
4. Projekt starten