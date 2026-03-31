<?php get_header(); ?>

<?php
$goal    = (float) get_post_meta( get_the_ID(), '_campaign_goal',   true );
$raised  = (float) get_post_meta( get_the_ID(), '_campaign_raised', true );
$end     = get_post_meta( get_the_ID(), '_campaign_end', true );
$percent = $goal ? min( 100, round( $raised / $goal * 100 ) ) : 0;
$days_left = $end ? max( 0, ceil( ( strtotime( $end ) - time() ) / DAY_IN_SECONDS ) ) : null;
?>

<main id="main-content" class="site-main">
    <div class="container content-area">
        <div class="two-col-layout">

            <div>
                <?php while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <header class="single-post-header">
                            <span class="post-category-badge"><?php _e( 'Campaign', 'compassion-ngo' ); ?></span>
                            <h1 class="entry-title" style="margin-top:0.5rem;"><?php the_title(); ?></h1>
                            <div class="post-meta">
                                <time datetime="<?php the_date('c'); ?>"><?php the_date(); ?></time>
                                <?php if ( $days_left !== null ) : ?>
                                    <span style="color:<?php echo $days_left < 7 ? 'var(--color-accent)' : 'var(--color-mid)'; ?>; font-weight:600;">
                                        <?php printf( _n( '%d day left', '%d days left', $days_left, 'compassion-ngo' ), $days_left ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div style="margin-bottom:2rem;border-radius:8px;overflow:hidden;">
                                <?php the_post_thumbnail( 'compassion-hero' ); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Funding Progress Box -->
                        <?php if ( $goal ) : ?>
                        <div style="background:var(--color-light);border-radius:12px;padding:1.75rem;margin-bottom:2rem;">
                            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;text-align:center;margin-bottom:1.25rem;">
                                <div>
                                    <span style="display:block;font-family:var(--font-heading);font-size:1.8rem;color:var(--color-primary);">$<?php echo number_format( $raised ); ?></span>
                                    <span style="font-size:0.8rem;color:var(--color-mid);text-transform:uppercase;letter-spacing:.08em;"><?php _e( 'Raised', 'compassion-ngo' ); ?></span>
                                </div>
                                <div>
                                    <span style="display:block;font-family:var(--font-heading);font-size:1.8rem;color:var(--color-dark);">$<?php echo number_format( $goal ); ?></span>
                                    <span style="font-size:0.8rem;color:var(--color-mid);text-transform:uppercase;letter-spacing:.08em;"><?php _e( 'Goal', 'compassion-ngo' ); ?></span>
                                </div>
                                <div>
                                    <span style="display:block;font-family:var(--font-heading);font-size:1.8rem;color:var(--color-accent);"><?php echo $percent; ?>%</span>
                                    <span style="font-size:0.8rem;color:var(--color-mid);text-transform:uppercase;letter-spacing:.08em;"><?php _e( 'Funded', 'compassion-ngo' ); ?></span>
                                </div>
                            </div>
                            <div class="progress-bar" style="height:10px;">
                                <div class="progress-fill" style="width:<?php echo $percent; ?>%"></div>
                            </div>
                            <?php if ( $end ) : ?>
                                <p style="font-size:0.82rem;color:var(--color-mid);margin:0.75rem 0 0;text-align:right;">
                                    <?php printf( __( 'Campaign ends: %s', 'compassion-ngo' ), date_i18n( get_option('date_format'), strtotime($end) ) ); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                    </article>

                <?php endwhile; ?>
            </div>

            <!-- Sidebar with Donate CTA -->
            <aside class="widget-area">
                <div class="widget widget-donate" style="position:sticky;top:calc(var(--header-height) + 1.5rem);">
                    <h3 class="widget-title"><?php _e( 'Make a Donation', 'compassion-ngo' ); ?></h3>
                    <p><?php _e( 'Every contribution, big or small, makes a real difference.', 'compassion-ngo' ); ?></p>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin:1rem 0;">
                        <?php foreach ( [10, 25, 50, 100] as $amount ) : ?>
                            <a href="<?php echo esc_url( get_theme_mod('compassion_donate_url', '/donate') . '?amount=' . $amount ); ?>"
                               style="display:block;padding:.65rem;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.25);border-radius:var(--radius);text-align:center;color:#fff;font-weight:700;font-size:.95rem;transition:background .2s;">
                                $<?php echo $amount; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?php echo esc_url( get_theme_mod('compassion_donate_url', '/donate') ); ?>"
                       class="btn btn-accent" style="width:100%;justify-content:center;margin-top:.25rem;">
                        <?php _e( 'Custom Amount →', 'compassion-ngo' ); ?>
                    </a>
                </div>

                <!-- Share -->
                <div class="widget" style="margin-top:1rem;">
                    <h3 class="widget-title"><?php _e( 'Share This Campaign', 'compassion-ngo' ); ?></h3>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                        <?php
                        $share_url   = urlencode( get_permalink() );
                        $share_title = urlencode( get_the_title() );
                        $networks = [
                            'Twitter'  => "https://twitter.com/intent/tweet?url={$share_url}&text={$share_title}",
                            'Facebook' => "https://www.facebook.com/sharer/sharer.php?u={$share_url}",
                            'LinkedIn' => "https://www.linkedin.com/shareArticle?mini=true&url={$share_url}&title={$share_title}",
                            'Email'    => "mailto:?subject={$share_title}&body={$share_url}",
                        ];
                        foreach ( $networks as $label => $url ) : ?>
                            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer"
                               style="padding:.4rem .9rem;background:var(--color-light);border:1px solid var(--color-border);border-radius:var(--radius);font-size:.82rem;font-weight:600;color:var(--color-dark);">
                                <?php echo esc_html($label); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</main>

<?php get_footer(); ?>
