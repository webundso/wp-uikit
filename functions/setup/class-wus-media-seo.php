<?php
defined('ABSPATH') || exit;

/**
 * WUS_MediaSeo
 * Verhindert die Indexierung von unverlinkten Mediathek-Dateien:
 * - Attachment-Pages: Redirect auf Elternbeitrag/Startseite statt eigener HTML-Seite
 * - Core-Sitemap: Attachments aus wp-sitemap.xml ausschliessen
 * - robots.txt: Direkte Uploads-Dateitypen (virtuell) disallowen
 *
 * Hinweis:
 * - Der X-Robots-Tag-Header auf die Datei-URLs selbst (z.B. /wp-content/uploads/*.pdf)
 *   kann NICHT über PHP/WordPress gesetzt werden (WP wird bei einem direkten Datei-
 *   Request gar nicht geladen). Das muss auf Server-Ebene passieren (.htaccess/nginx),
 *   siehe README.md, Abschnitt "Deployment / Server-Config".
 *
 * Schalter (siehe webundso.php):
 * - WUS_NOINDEX_ATTACHMENTS (bool): Attachment-Redirect + Sitemap-Ausschluss aktivieren
 * - WUS_ATTACHMENT_REDIRECT_TARGET ('parent'|'home'): Redirect-Ziel für Attachment-Pages
 * - WUS_NOINDEX_FILE_TYPES (array): Dateitypen für robots.txt-Disallow (Uploads)
 */
class WUS_MediaSeo
{
    /**
     * Boot: hängt die Hooks an, sofern das Feature nicht explizit deaktiviert ist.
     */
    public static function init(): void
    {
        if (defined('WUS_NOINDEX_ATTACHMENTS') && !WUS_NOINDEX_ATTACHMENTS) {
            return;
        }

        add_action('template_redirect', [__CLASS__, 'redirect_attachment_pages']);
        add_filter('wp_sitemaps_post_types', [__CLASS__, 'exclude_attachments_from_sitemap']);
        add_filter('robots_txt', [__CLASS__, 'append_robots_rules'], 10, 2);
    }

    /**
     * Attachment-Pages abschalten:
     * Redirect (301) auf den Elternbeitrag, falls vorhanden, sonst auf die Startseite.
     */
    public static function redirect_attachment_pages(): void
    {
        if (!is_attachment()) {
            return;
        }

        global $post;

        $target = defined('WUS_ATTACHMENT_REDIRECT_TARGET') ? WUS_ATTACHMENT_REDIRECT_TARGET : 'parent';

        $redirect_url = home_url('/');

        if ($target === 'parent' && $post instanceof WP_Post && $post->post_parent) {
            $parent_url = get_permalink($post->post_parent);
            if ($parent_url) {
                $redirect_url = $parent_url;
            }
        }

        wp_safe_redirect($redirect_url, 301);
        exit;
    }

    /**
     * Entfernt 'attachment' aus den Post-Types der WP-Core-Sitemap (wp-sitemap.xml).
     */
    public static function exclude_attachments_from_sitemap(array $post_types): array
    {
        unset($post_types['attachment']);
        return $post_types;
    }

    /**
     * Ergänzt die (virtuelle) robots.txt um Disallow-Regeln für definierte
     * Uploads-Dateitypen. Nur ergänzend zum Server-seitigen X-Robots-Tag-Header
     * (siehe README.md) – verhindert Crawling, aber keine Deindexierung bereits
     * bekannter URLs.
     */
    public static function append_robots_rules(string $output, bool $public): string
    {
        if (!$public) {
            return $output;
        }

        $file_types = defined('WUS_NOINDEX_FILE_TYPES')
            ? WUS_NOINDEX_FILE_TYPES
            : ['pdf', 'docx', 'doc', 'xlsx', 'xls', 'zip'];

        if (empty($file_types)) {
            return $output;
        }

        $upload_dir = wp_upload_dir();
        $uploads_path = trim((string) parse_url($upload_dir['baseurl'], PHP_URL_PATH), '/');

        $output .= "\n";
        foreach ($file_types as $ext) {
            $ext = ltrim((string) $ext, '.');
            $output .= 'Disallow: /' . $uploads_path . '/*.' . $ext . "$\n";
        }

        return $output;
    }
}
