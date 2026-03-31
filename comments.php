<?php
if ( post_password_required() ) return;
?>

<section id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="comments-title" style="margin-bottom:1.5rem;">
            <?php
            $count = get_comments_number();
            printf(
                _n( '%1$s Comment on &ldquo;%2$s&rdquo;', '%1$s Comments on &ldquo;%2$s&rdquo;', $count, 'compassion-ngo' ),
                number_format_i18n( $count ),
                get_the_title()
            );
            ?>
        </h2>

        <ol class="comment-list" style="list-style:none;padding:0;margin:0 0 2rem;">
            <?php
            wp_list_comments( [
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 50,
                'callback'    => 'compassion_comment_template',
            ] );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>
    <?php endif; ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
        <p><?php _e( 'Comments are closed.', 'compassion-ngo' ); ?></p>
    <?php endif; ?>

    <?php
    comment_form( [
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title" style="margin-bottom:1.25rem;">',
        'title_reply_after'  => '</h3>',
        'submit_button'      => '<button type="submit" class="btn btn-primary">%4$s</button>',
        'comment_field'      => '<p><label for="comment" style="display:block;font-weight:600;margin-bottom:.4rem;">' . __( 'Comment', 'compassion-ngo' ) . '</label><textarea id="comment" name="comment" rows="6" required></textarea></p>',
    ] );
    ?>

</section><!-- #comments -->

<?php
function compassion_comment_template( $comment, $args, $depth ) {
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'comment', $comment ); ?>>
        <div style="display:flex;gap:1rem;padding:1.25rem 0;border-bottom:1px solid var(--color-border);">
            <div style="flex-shrink:0;"><?php echo get_avatar( $comment, 50, '', '', [ 'style' => 'border-radius:50%;' ] ); ?></div>
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:.4rem;flex-wrap:wrap;">
                    <strong style="font-size:.95rem;"><?php comment_author(); ?></strong>
                    <time style="font-size:.8rem;color:var(--color-mid);" datetime="<?php comment_date('c'); ?>"><?php comment_date(); ?></time>
                    <?php comment_reply_link( array_merge( $args, [ 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '<span style="font-size:.8rem;">', 'after' => '</span>' ] ) ); ?>
                </div>
                <?php if ( '0' === $comment->comment_approved ) : ?>
                    <p style="font-style:italic;color:var(--color-mid);font-size:.88rem;"><?php _e( 'Your comment is awaiting moderation.', 'compassion-ngo' ); ?></p>
                <?php endif; ?>
                <div class="comment-content" style="font-size:.95rem;"><?php comment_text(); ?></div>
            </div>
        </div>
    </li>
    <?php
}
?>
