# Code Review – webundso UIKit Starter Theme

**Datum:** März 2026
**Reviewer:** Claude (Anthropic)
**Scope:** Best Practice · Sicherheit · Performance · Modernes Webdesign
**Modus:** Nur Dokumentation – keine Änderungen vorgenommen

---

## Übersicht & Architektur

Das Theme ist ein klassisches WordPress PHP-Theme (kein FSE/Block Theme) auf Basis von UIkit 3. Die Architektur ist durchdacht:

- Zentrales Config-System in `functions/webundso.php` mit Feature-Flags via PHP-Konstanten
- OOP-Module in `functions/setup/class-wus-*.php`
- Boot-Sequenz in `functions/cleanup.php` (Name irreführend, da das die eigentliche Setup-Datei ist)
- SCSS mit eigenem Token-Layer (`_bridge.scss`), der WP-Presets auf eigene CSS-Custom-Properties mappt
- `theme.json` als Single Source of Truth für Farben, Typo und Spacing

Gesamtqualität: **gut bis sehr gut** – sauber strukturiert, gut kommentiert, sicherheitsbewusst entworfen. Die nachfolgenden Punkte sind Verbesserungspotenziale, keine grundlegenden Mängel.

---

## 🔴 Bugs / Kritisch

### 1. `WUS_DEBUG_BLOCK_REGISTER` immer `true` — kein `!defined()`-Guard
**Datei:** `functions/webundso.php`, Zeile 334

```php
// AKTUELL (Bug):
define('WUS_DEBUG_BLOCK_REGISTER', true);

// Alle anderen Konstanten haben:
if (!defined('WUS_SOME_CONST')) { define(...); }
```

Diese Konstante ist als einzige im File **nicht** mit `if (!defined())` geschützt. Das bedeutet:
- Sie kann nicht von außen (wp-config.php, MU-Plugin) überschrieben werden
- Der Debug-Modus läuft in **jedem Environment**, auch Production
- In `gutenberg.php` werden dadurch bei jedem Block-Rendering `error_log()`-Aufrufe ausgeführt

### 2. Doppelte Sidebar-Registrierung
**Dateien:** `functions/setup/class-wus-core.php` (Zeile 104–124) und `functions/setup/class-wus-widgets.php` (Zeile 47–59)

Beide Klassen registrieren `wus-sidebar-pages` und `wus-sidebar-blog` über `widgets_init`. WordPress gibt dabei eine `_doing_it_wrong()`-Notice aus. `WUS_Core::register_sidebars()` ist vermutlich ein Relikt aus einer früheren Version und sollte entfernt oder geleert werden.

---

## 🟠 Sicherheit

### 3. Debug-Code (error_log) in Production-Dateien
**Datei:** `functions.php`, Zeilen 28–62

```php
add_action('init', function() {
    if (!is_admin()) return;
    error_log('=== WUS DEBUG START ===');
    // ... mehrere error_log Aufrufe
}, 5);

add_action('acf/init', function() {
    error_log('ACF/INIT Hook gefeuert');
    // ...
}, 999);
```

Diese Blöcke sind offensichtlich aus einer Debug-Session übrig geblieben und sollten aus dem Starter-Theme entfernt werden. Sie schreiben bei jedem Admin-Seitenaufruf in das Error-Log.

**Datei:** `functions/gutenberg.php`, Zeile 278

```php
add_filter('allowed_block_types_all', function ($allowed_blocks, $editor_context) {
    error_log('WUS: allowed_block_types_all fired');  // läuft bei JEDEM Seitenaufruf
    ...
}, 20, 2);
```

Dieser `error_log` ist nicht durch ein Debug-Flag geschützt und läuft auf Frontend-Requests, da der Filter nicht nur im Admin gilt.

### 4. Pingback-URL im Header exponiert
**Datei:** `header.php`, Zeile 29

```php
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
```

Auch wenn Pingbacks via `WUS_DISABLE_COMMENTS` deaktiviert werden (in `class-wus-cleanup.php`), bleibt dieser `<link>` immer im HTML. Die XML-RPC-Schnittstelle wird als Angriffsvektor für DDoS-Amplification-Angriffe genutzt. Empfehlung: Conditional rendering basierend auf `WUS_DISABLE_COMMENTS` oder einem eigenen Flag.

### 5. Falscher Sanitizer-Check (class_exists statt function_exists)
**Datei:** `functions/setup/class-wus-content.php`, Zeile 146

```php
$sanitizer_ok = function_exists('enshrined\svgSanitize\Sanitizer')
    || function_exists('svg_sanitizer_init');
```

`enshrined\svgSanitize\Sanitizer` ist ein Klassenname (mit Namespace), kein Funktionsname. `function_exists()` gibt hier immer `false` zurück. Der korrekte Check wäre `class_exists('enshrined\\svgSanitize\\Sanitizer')`. Dadurch wird der Sanitizer-Check umgangen, wenn `WUS_SVG_REQUIRE_SANITIZER = true` und das Plugin aktiv ist.

### 6. Inline-CSS im Login-Screen via direktem `echo`
**Datei:** `functions/setup/class-wus-login.php`, Zeilen 49–58

```php
echo '<style>
    .login #login { ... }
    .login h1 a {
        background: url("' . $logo_url . '") ...
    }
</style>';
```

Die URL ist mit `esc_url()` escaped (korrekt). Der Block funktioniert, aber der Best-Practice-Ansatz wäre `wp_add_inline_style()` mit einem registrierten Handle. Das aktuelle Vorgehen kann zu CSP-Problemen führen, wenn ein `Content-Security-Policy`-Header gesetzt wird.

### 7. Capability vs. Role in `add_options_page()`
**Datei:** `functions/setup/class-wus-admin.php`, Zeile 144

```php
add_options_page(..., 'administrator', 'options.php');
```

WordPress-Best-Practice ist die Verwendung einer **Capability** (`'manage_options'`) statt einer **Rolle** (`'administrator'`). Rollen können sich je nach Setup unterscheiden; Capabilities sind stabiler und erlauben feinere Berechtigungskontrollen.

---

## 🟡 Performance

### 8. jQuery-Abhängigkeit in scripts.js
**Datei:** `assets/js/scripts.js`

Das gesamte JS nutzt jQuery (`jQuery(document).ready(...)`, `$('.class').on(...)` etc.). UIkit selbst ist jQuery-unabhängig. Modern wäre Vanilla JS:

- `document.querySelectorAll()` statt `$('.class')`
- `element.addEventListener()` statt `.on()`
- `IntersectionObserver` oder ScrollEvent für "Back to Top"

Der `resize`-Event-Listener (Zeile 116–119) ist leer, registriert aber trotzdem einen Listener bei jedem Seitenaufruf.

### 9. TTF-Fontdateien im Repo
**Verzeichnis:** `assets/fonts/`

Alle drei Fonts sind sowohl als `.woff2` als auch als `.ttf` vorhanden:
- `adamina-v22-latin-regular.ttf` (größer)
- `roboto-v50-latin-700.ttf`
- `roboto-v50-latin-regular.ttf`

WOFF2 wird von allen modernen Browsern unterstützt (>97% globale Nutzung). TTF-Dateien sind deutlich größer und für Web-Auslieferung nicht nötig. Sie erhöhen nur die Repo-Größe.

### 10. Keine Font-Preloads im Header
**Datei:** `header.php`

Web Fonts werden via CSS geladen, aber nicht mit `<link rel="preload">` vorgeladen. Das führt zu FOUT (Flash of Unstyled Text) und verschlechtert den LCP-Wert (Largest Contentful Paint). Empfehlung: `<link rel="preload" as="font" type="font/woff2" href="..." crossorigin>` für die primären Fonts hinzufügen.

### 11. Source Maps in Production ausgeliefert
**Datei:** `assets/styles/site.css.map`

Die Source Map liegt im Theme-Verzeichnis und wird öffentlich ausgeliefert. Sie enthüllt die SCSS-Dateistruktur und Pfade des Projekts. Empfehlung: Source Maps entweder per `.gitignore` oder Build-Skript für Production-Deploys ausschließen.

### 12. `overflow-x: hidden` auf `html`-Element
**Datei:** `assets/scss/_wus.scss`, Zeile 9

```scss
html {
    overflow-x: hidden; // (wg fullwidth)
}
```

`overflow-x: hidden` auf `html` oder `body` bricht `position: sticky` und kann subtile Layout-Bugs verursachen. Der Kommentar deutet auf ein Full-Width-Problem hin — besser wäre es, das Layout-Problem an der Ursache zu beheben (z.B. `max-width: 100%; overflow: hidden` auf dem problematischen Element selbst).

### 13. `jquery.min.js` im Theme-Verzeichnis
**Datei:** `assets/js/jquery.min.js`

WordPress liefert bereits jQuery aus. Diese Datei im Theme ist vermutlich ein Überrest und sollte entweder entfernt oder begründet werden. Sie wird aktuell nicht enqueued (WP's jQuery wird genutzt), belegt aber Speicherplatz.

---

## 🔵 Best Practice & Code-Qualität

### 14. `functions/cleanup.php` ist eigentlich der Setup-Loader
**Datei:** `functions/cleanup.php`

Diese Datei lädt alle Setup-Module und enthält die Boot-Sequenz — aber sie heißt `cleanup.php`. Die Klasse `WUS_Cleanup` ist eine separate Datei. Der Dateiname ist irreführend für jeden, der das Theme zum ersten Mal sieht (und für neue Entwickler).

### 15. `theme.json` nutzt `trunk`-Schema
**Datei:** `theme.json`, Zeile 2

```json
"$schema": "https://schemas.wp.org/trunk/theme.json"
```

`trunk` verweist immer auf die neueste Entwicklungsversion und kann Breaking Changes enthalten. Für Stabilität sollte eine versionierte Schema-URL verwendet werden, z.B.:
```json
"$schema": "https://schemas.wp.org/wp/6.6/theme.json"
```

### 16. Logo-`<img>` ohne `width`/`height`-Attribute
**Datei:** `parts/nav-topbar.php`, Zeile 31–36

```php
<img
    src="<?php echo esc_url($logo_uri); ?>"
    alt="<?php echo esc_attr($logo_alt); ?>"
    loading="eager"
    decoding="async"
>
```

Fehlende `width`- und `height`-Attribute führen zu Cumulative Layout Shift (CLS), einem Core Web Vitals-Metrik. Der Browser reserviert keinen Platz für das Bild, bevor es geladen wird, und der Inhalt springt.

### 17. `WUS_SEARCH_POST_TYPES` Default enthält WordPress.org-spezifische Types
**Datei:** `functions/webundso.php`, Zeile 151

```php
define('WUS_SEARCH_POST_TYPES', ['post', 'page', 'site', 'plugin', 'theme', 'person']);
```

`'site'`, `'plugin'`, `'theme'` sind Custom Post Types, die auf WordPress.org-Projekten existieren, nicht in Standard-Projekten. Dieser Default ist für ein allgemeines Starter-Theme irreführend. Empfehlung: Default auf `['post', 'page']` reduzieren.

### 18. Doppelte CSS Custom Properties in `_bridge.scss`
**Datei:** `assets/scss/_bridge.scss`

`--t-color-primary-hover`, `--t-color-primary-active`, `--t-color-border` und `--t-color-surface` werden zweimal im `:root` deklariert — zuerst einfach, dann mit `var(..., fallback)`-Syntax. Die zweite Definition überschreibt die erste (korrekt, und offensichtlich beabsichtigt), aber es ist unnötiger Code-Ballast. Die ersten Deklarationen (Zeilen 22–27) können entfernt werden.

### 19. `.gitignore` ist eine WordPress-Root-Konfiguration, nicht Theme-spezifisch
**Datei:** `.gitignore`

```gitignore
!wp-content/
wp-content/*
!wp-content/mu-plugins/
```

Das ist ein `.gitignore` für ein WordPress-Root-Repository. Da dieses Repo ein Theme-Repository ist, sollten stattdessen theme-spezifische Einträge vorhanden sein:
- `node_modules/` (schon vorhanden)
- `/assets/styles/*.css.map` (Source Maps)
- `assets/fonts/*.ttf` (optional)
- `.env`-Dateien

### 20. Inkonsistente Einrückung (Tabs vs. Spaces)
Die PHP-Dateien verwenden teilweise Tabs (z.B. `class-wus-assets.php`), teilweise Spaces (z.B. `class-wus-content.php`). Eine `.editorconfig`-Datei fehlt im Repo.

### 21. Fehlende `aria-expanded` Synchronisation beim Offcanvas
**Datei:** `header.php`, Zeile 52-53 + `assets/js/scripts.js`

Der `aria-expanded="false"` am Hamburger-Button wird im JavaScript beim Klick via `toggleClass('active')` nicht auf `true` gesetzt. Der Kommentar im `header.php` erwähnt das selbst: "aria-expanded wird durch UIkit nicht automatisch gepflegt." Für Screen-Reader-Nutzer ist das ein Accessibility-Problem.

---

## ✅ Was gut gemacht ist

Diese Punkte sind ausdrücklich positiv zu erwähnen:

**Sicherheit:**
- `defined('ABSPATH') || exit;` konsequent in allen PHP-Dateien
- `esc_html()`, `esc_url()`, `esc_attr()`, `wp_kses_post()` überall korrekt verwendet
- `noopener noreferrer` für `target="_blank"` sowohl serverseitig (Walker) als auch clientseitig (JS)
- `wp_generator` und `the_generator` entfernt (WP-Version-Leak verhindert)
- SVG-Upload standardmäßig deaktiviert, mit Capability-Check und Sanitizer-Gate
- Feeds mit HTTP 410 (Gone) beantwortet, nicht 404
- Self-Pings korrekt deaktiviert
- `DISALLOW_FILE_EDIT` in wp-config.example.php
- `FORCE_SSL_ADMIN` für staging/production

**Architektur:**
- Sauberes Feature-Flag-System mit `if (!defined())` Guard (bis auf Bug #1)
- Gute Trennung: Konfig / Module / Templates
- Fail-soft Patterns überall (`file_exists()` vor Enqueue)
- `filemtime()` Cache-Busting für Assets
- ACF JSON in Theme-Verzeichnis (versionierbar)
- Config-Injection ins Editor-JS via `wp_add_inline_script()` + `wp_json_encode()`

**CSS / Design:**
- Sauberer Token-Layer (`_bridge.scss`) der WP-Presets auf eigene Variablen mappt
- `color-mix()` für State-Farben (modernes CSS, kein SCSS-Mixin nötig)
- `theme.json` deaktiviert Custom Colors/Gradients/Duotone (Editor bleibt sauber)
- Gute Typografieskala mit sinnvollen Defaults
- Block-Styles und Core-Patterns deaktiviert (weniger Editor-Wildwuchs)

**Performance:**
- UIkit wird lokal ausgeliefert (kein CDN-Dependency)
- JS im Footer (`true` als letzter Parameter von `wp_register_script`)
- UIkit-Icons als separates Script (nur laden wenn nötig)
- `loading="eager"` auf Logo-Bild (korrekt für LCP)
- `decoding="async"` auf Logo-Bild

**WordPress-Integration:**
- Korrekte Walker-Implementierungen für Navbar, Offcanvas und Megamenu
- `wp_body_open()` Hook vorhanden
- `language_attributes()`, `body_class()`, `wp_head()`, `wp_footer()` alle vorhanden
- Custom Block-Kategorie mit auto-registration via `block.json`

---

## Zusammenfassung der Prioritäten

| # | Thema | Priorität | Datei |
|---|-------|-----------|-------|
| 1 | `WUS_DEBUG_BLOCK_REGISTER` ohne `!defined()`-Guard | 🔴 Bug | webundso.php:334 |
| 2 | Doppelte Sidebar-Registrierung | 🔴 Bug | class-wus-core.php + class-wus-widgets.php |
| 3 | Debug-Code / error_log in Production | 🟠 Sicherheit | functions.php:28–62, gutenberg.php:278 |
| 4 | Pingback-URL immer exponiert | 🟠 Sicherheit | header.php:29 |
| 5 | Falscher Sanitizer-Check (function_exists statt class_exists) | 🟠 Sicherheit | class-wus-content.php:146 |
| 6 | jQuery-Abhängigkeit | 🟡 Performance | scripts.js |
| 7 | TTF-Fonts im Repo | 🟡 Performance | assets/fonts/ |
| 8 | Keine Font-Preloads | 🟡 Performance | header.php |
| 9 | Source Maps in Production | 🟡 Performance | assets/styles/site.css.map |
| 10 | `overflow-x: hidden` auf `html` | 🟡 Performance | _wus.scss:9 |
| 11 | Logo img ohne width/height | 🔵 Best Practice | nav-topbar.php |
| 12 | WUS_SEARCH_POST_TYPES Default-Werte | 🔵 Best Practice | webundso.php:151 |
| 13 | Doppelte CSS Custom Properties | 🔵 Best Practice | _bridge.scss |
| 14 | capability vs role in add_options_page | 🔵 Best Practice | class-wus-admin.php:144 |
| 15 | .gitignore für Root, nicht für Theme | 🔵 Best Practice | .gitignore |
| 16 | theme.json trunk-Schema | 🔵 Best Practice | theme.json:2 |
| 17 | aria-expanded nicht synchronisiert | 🔵 Accessibility | header.php + scripts.js |
| 18 | Fehlende .editorconfig | 🔵 DX | – |

---

*Erstellt mit Claude (Anthropic) – Nur Analyse, keine Codeänderungen.*
