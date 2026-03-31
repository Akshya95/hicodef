<?php get_header(); ?>

<main id="main-content" class="site-main">
    <div class="container content-area" style="text-align:center; padding:6rem 1.5rem;">
        <p style="font-size:6rem;line-height:1;margin-bottom:1rem;">🌿</p>
        <h1><?php _e( 'Page Not Found', 'compassion-ngo' ); ?></h1>
        <p style="color:var(--color-mid); max-width:420px; margin:0 auto 2rem;">
            <?php _e( 'The page you\'re looking for might have moved. Let\'s get you back on track.', 'compassion-ngo' ); ?>
        </p>
        <div class="btn-group">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">
                <?php _e( 'Go Home', 'compassion-ngo' ); ?>
            </a>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>" class="btn btn-outline">
                <?php _e( 'Our Programs', 'compassion-ngo' ); ?>
            </a>
        </div>
    </div>
</main>

<?php get_footer(); ?>
