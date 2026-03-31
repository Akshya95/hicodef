<?php get_header(); ?>

<?php
$role     = get_post_meta( get_the_ID(), '_team_role',     true );
$email    = get_post_meta( get_the_ID(), '_team_email',    true );
$twitter  = get_post_meta( get_the_ID(), '_team_twitter',  true );
$linkedin = get_post_meta( get_the_ID(), '_team_linkedin', true );
?>

<main id="main-content" class="site-main">
    <div class="container content-area" style="max-width:820px;">

        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <!-- Profile Header -->
                <div style="display:flex;gap:2rem;align-items:center;flex-wrap:wrap;padding:2.5rem;background:var(--color-light);border-radius:16px;margin-bottom:2rem;">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <?php the_post_thumbnail( 'compassion-team', [ 'style' => 'width:120px;height:120px;border-radius:50%;object-fit:cover;border:4px solid var(--color-white);box-shadow:0 4px 16px rgba(0,0,0,.1);flex-shrink:0;' ] ); ?>
                    <?php else : ?>
                        <div style="width:120px;height:120px;border-radius:50%;background:var(--color-border);display:flex;align-items:center;justify-content:center;font-size:3rem;flex-shrink:0;">👤</div>
                    <?php endif; ?>

                    <div>
                        <h1 style="margin-bottom:.25rem;font-size:2rem;"><?php the_title(); ?></h1>
                        <?php if ( $role ) : ?>
                            <p class="team-role" style="margin-bottom:.75rem;font-size:.9rem;"><?php echo esc_html( $role ); ?></p>
                        <?php endif; ?>

                        <!-- Social / Contact -->
                        <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
                            <?php if ( $email ) : ?>
                                <a href="mailto:<?php echo esc_attr($email); ?>" style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1px solid var(--color-border);border-radius:99px;font-size:.82rem;font-weight:600;color:var(--color-mid);background:var(--color-white);">
                                    ✉ <?php _e( 'Email', 'compassion-ngo' ); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ( $twitter ) : ?>
                                <a href="https://twitter.com/<?php echo ltrim( esc_attr($twitter), '@' ); ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1px solid var(--color-border);border-radius:99px;font-size:.82rem;font-weight:600;color:var(--color-mid);background:var(--color-white);">
                                    𝕏 <?php echo esc_html( $twitter ); ?>
                                </a>
                            <?php endif; ?>
                            <?php if ( $linkedin ) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:.35rem;padding:.35rem .85rem;border:1px solid var(--color-border);border-radius:99px;font-size:.82rem;font-weight:600;color:var(--color-mid);background:var(--color-white);">
                                    in LinkedIn
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Bio Content -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Back to Team -->
                <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-border);">
                    <a href="<?php echo esc_url( get_post_type_archive_link('team_member') ); ?>" style="display:inline-flex;align-items:center;gap:.4rem;font-weight:600;font-size:.9rem;color:var(--color-primary);">
                        ← <?php _e( 'Back to Team', 'compassion-ngo' ); ?>
                    </a>
                </div>

            </article>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
