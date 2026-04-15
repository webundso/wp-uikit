<?php
/**
 * Template Part: Comments
 * Datei: parts/comments.php
 * Aufruf in single.php: comments_template();
 * oder: get_template_part( 'template-parts/comments' );
 *
 * Best Practice:
 * - Passwortgeschützte Posts ausschliessen
 * - comments_template() für WP-native Kommentar-Verarbeitung verwenden
 * - Paginierung unterstützen
 * - UIkit Komponenten: uk-comment, uk-grid, uk-form
 */

// Passwortgeschützte Posts: keine Kommentare anzeigen
if ( post_password_required() ) {
    return;
}
?>

<div id="comments" class="wus-comments uk-margin-large-top">

    <?php if ( have_comments() ) : ?>

        <h3 class="uk-heading-divider uk-h4">
            <?php
            comments_number(
                esc_html__( 'Keine Kommentare', 'webundso' ),
                esc_html__( 'Ein Kommentar', 'webundso' ),
                esc_html__( '% Kommentare', 'webundso' )
            );
            ?>
        </h3>

        <ul class="uk-comment-list">
            <?php
            wp_list_comments( [
                'style'       => 'ul',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'webundso_comment_template',
                'end-callback'=> 'webundso_comment_end',
            ] );
            ?>
        </ul>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
        <nav class="uk-margin-medium-top" aria-label="<?php esc_attr_e( 'Kommentar-Navigation', 'webundso' ); ?>">
            <div class="uk-grid-small uk-flex-between" uk-grid>
                <div><?php previous_comments_link( '&larr; ' . esc_html__( 'Ältere Kommentare', 'webundso' ) ); ?></div>
                <div><?php next_comments_link( esc_html__( 'Neuere Kommentare', 'webundso' ) . ' &rarr;' ); ?></div>
            </div>
        </nav>
        <?php endif; ?>

    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p class="uk-text-muted uk-text-small">
            <?php esc_html_e( 'Kommentare sind geschlossen.', 'webundso' ); ?>
        </p>
    <?php endif; ?>

    <?php
    // Kommentarformular
    if ( comments_open() ) :
        $commenter     = wp_get_current_commenter();
        $req           = get_option( 'require_name_email' );
        $aria_req      = $req ? ' aria-required="true"' : '';
        $required_text = $req ? ' <span class="uk-text-danger">*</span>' : '';

        $fields = [
            'author' => sprintf(
                '<div class="uk-margin">
                    <label class="uk-form-label" for="author">%s%s</label>
                    <div class="uk-form-controls">
                        <input id="author" class="uk-input" name="author" type="text" value="%s" autocomplete="name" %s>
                    </div>
                </div>',
                esc_html__( 'Name', 'webundso' ),
                $required_text,
                esc_attr( $commenter['comment_author'] ),
                $aria_req
            ),
            'email' => sprintf(
                '<div class="uk-margin">
                    <label class="uk-form-label" for="email">%s%s</label>
                    <div class="uk-form-controls">
                        <input id="email" class="uk-input" name="email" type="email" value="%s" autocomplete="email" %s>
                    </div>
                </div>',
                esc_html__( 'E-Mail', 'webundso' ),
                $required_text,
                esc_attr( $commenter['comment_author_email'] ),
                $aria_req
            ),
            'url' => sprintf(
                '<div class="uk-margin">
                    <label class="uk-form-label" for="url">%s</label>
                    <div class="uk-form-controls">
                        <input id="url" class="uk-input" name="url" type="url" value="%s" autocomplete="url">
                    </div>
                </div>',
                esc_html__( 'Website (optional)', 'webundso' ),
                esc_attr( $commenter['comment_author_url'] )
            ),
        ];

        $args = [
            'id_form'              => 'commentform',
            'class_form'           => 'uk-comment-form uk-margin-large-top',
            'id_submit'            => 'submit',
            'class_submit'         => 'uk-button uk-button-primary',
            'label_submit'         => esc_html__( 'Kommentar senden', 'webundso' ),
            'title_reply'          => esc_html__( 'Schreib einen Kommentar', 'webundso' ),
            'title_reply_to'       => esc_html__( 'Antworten an %s', 'webundso' ),
            'title_reply_before'   => '<h3 class="uk-heading-divider uk-h4">',
            'title_reply_after'    => '</h3>',
            'cancel_reply_before'  => ' &mdash; ',
            'cancel_reply_after'   => '',
            'cancel_reply_link'    => esc_html__( 'Abbrechen', 'webundso' ),
            'comment_notes_before' => $req
                ? '<p class="uk-text-small uk-text-muted">' . esc_html__( 'Pflichtfelder sind markiert', 'webundso' ) . ' <span class="uk-text-danger">*</span></p>'
                : '',
            'comment_field'        => sprintf(
                '<div class="uk-margin">
                    <label class="uk-form-label" for="comment">%s%s</label>
                    <div class="uk-form-controls">
                        <textarea id="comment" class="uk-textarea" name="comment" rows="6" required aria-required="true"></textarea>
                    </div>
                </div>',
                esc_html__( 'Kommentar', 'webundso' ),
                $required_text
            ),
            'fields'               => $fields,
            'logged_in_as'         => sprintf(
                '<p class="uk-text-small uk-text-muted">%s <a href="%s">%s</a>. <a href="%s">%s</a></p>',
                esc_html__( 'Eingeloggt als', 'webundso' ),
                esc_url( get_edit_user_link() ),
                esc_html( wp_get_current_user()->display_name ),
                esc_url( wp_logout_url( apply_filters( 'the_permalink', get_permalink() ) ) ),
                esc_html__( 'Ausloggen', 'webundso' )
            ),
        ];

        comment_form( $args );

    endif;
    ?>

</div>