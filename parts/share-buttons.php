<?php
/**
 * Social Share Buttons
 * UIkit Icons + Grid
 * Einbetten: get_template_part( 'template-parts/share-buttons' );
 *
 * UIkit-Icons verfügbar: facebook, instagram, pinterest, whatsapp, mail, x, linkedin, telegram, reddit, tiktok, threads
 * Ohne native Share-URL (copy-to-clipboard): instagram, tiktok, threads, snapchat
 * Snapchat: offizielles Brand-SVG (kein UIkit-Icon)
 */

$post_url   = urlencode( get_permalink() );
$post_title = urlencode( get_the_title() );
$post_thumb = urlencode( get_the_post_thumbnail_url( get_the_ID(), 'large' ) );
$mail_body  = urlencode( get_the_title() . ' – ' . get_permalink() );
$plain_url  = get_permalink();

// Snapchat offizielles Brand-SVG
$snapchat_svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12.01 2C9.18 2 6.84 3.93 6.84 6.5v.57c-.57-.1-1.1-.16-1.47-.16-.34 0-.57.04-.57.04l-.08.67s.14.01.37.05c.46.07 1.22.27 1.75.82.08.08.09.2.03.29-.48.7-1.45 1.27-1.87 2.36-.06.16.02.34.17.4.18.07.38.1.6.1.18 0 .37-.02.55-.07.03-.01.05-.01.08-.02.08.18.2.53.24.96.01.12.11.21.23.21h.06c.13 0 .24-.09.25-.22.07-.73.29-1.32.52-1.72.55.43 1.39.9 2.62.9.56 0 1.06-.1 1.49-.26.43.16.93.26 1.49.26 1.23 0 2.07-.47 2.62-.9.23.4.45.99.52 1.72.01.13.12.22.25.22h.06c.12 0 .22-.09.23-.21.04-.43.16-.78.24-.96.03.01.05.01.08.02.18.05.37.07.55.07.22 0 .42-.03.6-.1.15-.06.23-.24.17-.4-.42-1.09-1.39-1.66-1.87-2.36-.06-.09-.05-.21.03-.29.53-.55 1.29-.75 1.75-.82.23-.04.37-.05.37-.05l-.08-.67s-.23-.04-.57-.04c-.37 0-.9.06-1.47.16V6.5C17.16 3.93 14.84 2 12.01 2zm0 1.5c2.06 0 3.65 1.39 3.65 3v.98c0 .13.08.24.2.28.6.19 1.19.35 1.65.44l.02.12c-.44.07-1.3.31-1.96.97-.38.38-.41.95-.08 1.43.3.44.93.9 1.36 1.67-.06.01-.13.02-.21.02-.13 0-.27-.02-.41-.05-.22-.06-.42-.08-.58-.02-.19.07-.32.26-.36.54-.05.36-.14.67-.2.87-.47-.35-1.26-.77-2.39-.77-.56 0-1.05.1-1.48.26-.08.03-.17.03-.25 0-.43-.16-.92-.26-1.48-.26-1.13 0-1.92.42-2.39.77-.06-.2-.15-.51-.2-.87-.04-.28-.17-.47-.36-.54-.16-.06-.36-.04-.58.02-.14.03-.28.05-.41.05-.08 0-.15-.01-.21-.02.43-.77 1.06-1.23 1.36-1.67.33-.48.3-1.05-.08-1.43-.66-.66-1.52-.9-1.96-.97l.02-.12c.46-.09 1.05-.25 1.65-.44.12-.04.2-.15.2-.28V6.5c0-1.61 1.59-3 3.65-3zm0 14.12c-1.06 0-2.1.17-3.07.5-.14.05-.29-.02-.35-.15-.12-.29-.22-.62-.29-.98 1.65-.3 2.87-1.25 3.71-2.13.84.88 2.06 1.83 3.71 2.13-.07.36-.17.69-.29.98-.06.13-.21.2-.35.15-.97-.33-2.01-.5-3.07-.5z"/></svg>';

$shares = [

    // ── Mit nativer Share-URL ──────────────────────────────────────────────

    [
        'url'   => 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url,
        'label' => 'Facebook',
        'icon'  => 'facebook',
        'popup' => true,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'https://x.com/intent/tweet?url=' . $post_url . '&text=' . $post_title,
        'label' => 'X (Twitter)',
        'icon'  => 'x',
        'popup' => true,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $post_url,
        'label' => 'LinkedIn',
        'icon'  => 'linkedin',
        'popup' => true,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumb . '&description=' . $post_title,
        'label' => 'Pinterest',
        'icon'  => 'pinterest',
        'popup' => true,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'https://t.me/share/url?url=' . $post_url . '&text=' . $post_title,
        'label' => 'Telegram',
        'icon'  => 'telegram',
        'popup' => false,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'https://www.reddit.com/submit?url=' . $post_url . '&title=' . $post_title,
        'label' => 'Reddit',
        'icon'  => 'reddit',
        'popup' => true,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'https://api.whatsapp.com/send?text=' . $post_title . '%20' . $post_url,
        'label' => 'WhatsApp',
        'icon'  => 'whatsapp',
        'popup' => false,
        'copy'  => false,
        'svg'   => false,
    ],
    [
        'url'   => 'mailto:?subject=' . urlencode( get_the_title() ) . '&body=' . $mail_body,
        'label' => 'E-Mail',
        'icon'  => 'mail',
        'popup' => false,
        'copy'  => false,
        'svg'   => false,
    ],

    // ── Ohne native Share-URL → Copy to Clipboard ─────────────────────────

    [
        'url'   => '#',
        'label' => 'Instagram',
        'icon'  => 'instagram',
        'popup' => false,
        'copy'  => true,
        'svg'   => false,
    ],
    [
        'url'   => '#',
        'label' => 'TikTok',
        'icon'  => 'tiktok',
        'popup' => false,
        'copy'  => true,
        'svg'   => false,
    ],
    [
        'url'   => '#',
        'label' => 'Threads',
        'icon'  => 'threads',
        'popup' => false,
        'copy'  => true,
        'svg'   => false,
    ],
    [
        'url'   => '#',
        'label' => 'Snapchat',
        'icon'  => '',
        'popup' => false,
        'copy'  => true,
        'svg'   => $snapchat_svg,
    ],
];
?>

<div class="wus-share uk-margin-medium-top">

    <p class="uk-text-small uk-text-muted uk-margin-small-bottom">
        <?php esc_html_e( 'Teilen', 'webundso' ); ?>
    </p>

    <div class="uk-grid-small uk-child-width-auto" uk-grid>
        <?php foreach ( $shares as $share ) : ?>
        <div>
            <?php if ( $share['copy'] ) : ?>

                <button type="button"
                        class="uk-icon-button wus-copy-link"
                        data-label="<?php echo esc_attr( $share['label'] ); ?>"
                        data-url="<?php echo esc_attr( $plain_url ); ?>"
                        <?php if ( ! $share['svg'] ) : ?>
                        uk-icon="icon: <?php echo esc_attr( $share['icon'] ); ?>; ratio: 1.2"
                        <?php endif; ?>
                        uk-tooltip="title: <?php echo esc_attr( $share['label'] ) . ' &ndash; ' . esc_attr__( 'Link kopieren', 'webundso' ); ?>; pos: top">
                    <?php if ( $share['svg'] ) : ?>
                        <?php echo $share['svg']; // SVG ist hardcoded, kein user input ?>
                    <?php endif; ?>
                </button>

            <?php elseif ( $share['popup'] ) : ?>

                <a href="<?php echo esc_url( $share['url'] ); ?>"
                   class="uk-icon-button"
                   title="<?php echo esc_attr( $share['label'] ); ?>"
                   onclick="window.open(this.href, 'share', 'width=600,height=400'); return false;"
                   rel="noopener noreferrer"
                   uk-icon="icon: <?php echo esc_attr( $share['icon'] ); ?>; ratio: 1.2">
                </a>

            <?php else : ?>

                <a href="<?php echo esc_url( $share['url'] ); ?>"
                   class="uk-icon-button"
                   title="<?php echo esc_attr( $share['label'] ); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   uk-icon="icon: <?php echo esc_attr( $share['icon'] ); ?>; ratio: 1.2">
                </a>

            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
( function() {
    document.querySelectorAll( '.wus-copy-link' ).forEach( function( btn ) {
        btn.addEventListener( 'click', function( e ) {
            e.preventDefault();
            var url   = this.dataset.url;
            var label = this.dataset.label;

            navigator.clipboard.writeText( url ).then( function() {
                btn.setAttribute( 'uk-tooltip', 'title: ' + label + ' \u2013 Link kopiert!; pos: top' );
                UIkit.tooltip( btn ).show();
                setTimeout( function() {
                    btn.setAttribute( 'uk-tooltip', 'title: ' + label + ' \u2013 Link kopieren; pos: top' );
                    UIkit.tooltip( btn ).hide();
                }, 2000 );
            } ).catch( function() {
                prompt( 'Link kopieren:', url );
            } );
        } );
    } );
} )();
</script>