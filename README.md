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

**Seit UIkit 3.21+ nutzt das Theme die vorkompilierte Distribution** (`uikit/dist/css/uikit.css`,
per `WUS_Assets::enqueue_uikit_css()` eingebunden), **nicht** mehr den SCSS-Import der
UIkit-Quelle. Grund: UIkit-Core-SCSS nutzt seit 3.21 das Dart-Sass-Modulsystem
(`@use`/`@forward`, inkl. Built-in-Module wie `sass:string`), das WP-SCSS (basiert auf
`scssphp`) nicht unterstützt und laut Projekt-Roadmap auch erst mit einem geplanten
2.x-Rewrite unterstützen wird (Stand 2026, kein Termin).

Mechanik-Anpassungen (Container-Breite, Grid-Gutter, Button-Padding/Radius, Navbar-Höhe,
Accordion-Spacing), die vorher über Sass-Variablen liefen, sind jetzt reine CSS-Overrides
in `assets/scss/_uikit-overrides.scss` (lädt nach `uikit.css`, per `wp_enqueue_style`-Dependency
garantiert in der richtigen Reihenfolge).

Empfohlene Reihenfolge in `site.scss`:

1) Tools / Mixins  
2) Fonts (lokal, pro Projekt)  
3) UIkit CSS Overrides (Mechanik, wirkt gegen `uikit/dist/css/uikit.css`)  
4) Token-Bridge + Theme Layer

Bei neuen Projekten mit älterem UIkit (<3.21) kann weiterhin klassisch per SCSS-Import
+ Sass-Variablen gearbeitet werden — dann WP-SCSS wie gehabt auf `uikit/src/scss` zeigen lassen.

## wp-config.php Vorlage

Im Theme liegt eine Vorlage:
- `wp-config.example.php`

Vorgehen bei neuen Projekten:
1. Datei öffnen
2. Relevante Konstanten in die echte `wp-config.php` kopieren
3. Environment (`WP_ENVIRONMENT_TYPE`) setzen
4. Projekt starten

---

## Deployment / Server-Config

Das Theme regelt Mediathek-SEO (Attachment-Redirect, Sitemap-Ausschluss, robots.txt) via
`WUS_MediaSeo` (siehe `functions/setup/class-wus-media-seo.php`, Schalter in `functions/webundso.php`).

Ein Teil der Massnahme kann **nicht** über PHP/WordPress abgedeckt werden, weil direkte
Datei-Requests (`/wp-content/uploads/*.pdf` etc.) WordPress gar nicht laden: der
X-Robots-Tag-Header muss auf Server-Ebene gesetzt werden. Das ist projektspezifisch
(abhängig von Apache/nginx) und gehört **nicht** ins Theme-Repo.

**Apache (`.htaccess`)** — muss **oberhalb** von `# BEGIN WordPress` stehen, sonst wird
das Snippet beim nächsten Permalink-Resave von WordPress überschrieben:

```apache
<FilesMatch "\.(pdf|docx?|xlsx?|zip)$">
    Header set X-Robots-Tag "noindex, nofollow"
</FilesMatch>
```

**nginx** (Server-Block):

```nginx
location ~* \.(pdf|docx?|xlsx?|zip)$ {
    add_header X-Robots-Tag "noindex, nofollow";
}
```

Dateitypen-Liste hier an `WUS_NOINDEX_FILE_TYPES` (webundso.php) angleichen.

**Go-Live-Checkliste (kein Code):**
1. Bereits indexierte Dateien über Google Search Console → „Entfernen"-Tool zur Löschung einreichen.
2. Kontrolle: `site:deinedomain.de filetype:pdf` in Google, Vorher/Nachher vergleichen.