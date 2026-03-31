<?php
/**
 * Template Name: Donate Page
 */
get_header(); ?>

<main id="main-content" class="site-main">

    <!-- Hero -->
    <div style="background:var(--color-light);padding:5rem 0 3.5rem;text-align:center;">
        <div class="container" style="max-width:680px;">
            <span class="hero-eyebrow"><?php _e( 'Make a Difference', 'compassion-ngo' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php _e( 'Your generosity directly funds our programs and helps communities thrive. Every dollar counts.', 'compassion-ngo' ); ?></p>
        </div>
    </div>

    <div class="container" style="padding:4rem 1.5rem;max-width:960px;">

        <div style="display:grid;grid-template-columns:1fr 360px;gap:3rem;align-items:start;">

            <!-- Donation Form Area -->
            <div>
                <h2 style="margin-bottom:1.5rem;"><?php _e( 'Choose Your Amount', 'compassion-ngo' ); ?></h2>

                <!-- Preset Amounts -->
                <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem;margin-bottom:1.5rem;">
                    <?php
                    $amounts = [
                        10  => __( 'Feeds a family for a week', 'compassion-ngo' ),
                        25  => __( 'Provides school supplies', 'compassion-ngo' ),
                        50  => __( 'Funds a medical check-up', 'compassion-ngo' ),
                        100 => __( 'Sponsors a child for a month', 'compassion-ngo' ),
                    ];
                    foreach ( $amounts as $amount => $desc ) : ?>
                        <div class="donate-amount-card" style="border:2px solid var(--color-border);border-radius:10px;padding:1rem;text-align:center;cursor:pointer;transition:all .2s;">
                            <strong style="display:block;font-family:var(--font-heading);font-size:1.6rem;color:var(--color-primary);">$<?php echo $amount; ?></strong>
                            <span style="font-size:.75rem;color:var(--color-mid);line-height:1.4;display:block;margin-top:.25rem;"><?php echo esc_html($desc); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Page Content (embed donation plugin shortcode here) -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Placeholder callout when no plugin shortcode is present -->
                <div style="background:var(--color-light);border:2px dashed var(--color-border);border-radius:10px;padding:2rem;text-align:center;margin-top:1.5rem;">
                    <p style="margin:0;color:var(--color-mid);font-size:.95rem;">
                        <?php _e( '💳 Insert your donation plugin shortcode here (e.g. Give, Charitable, Stripe Payments).', 'compassion-ngo' ); ?>
                    </p>
                </div>

                <!-- Frequency toggle info -->
                <div style="margin-top:2rem;padding:1.25rem;background:rgba(26,107,74,.06);border-left:4px solid var(--color-primary);border-radius:0 8px 8px 0;">
                    <strong style="color:var(--color-primary);"><?php _e( '💚 Monthly giving multiplies impact', 'compassion-ngo' ); ?></strong>
                    <p style="margin:.4rem 0 0;font-size:.9rem;color:var(--color-mid);"><?php _e( 'Recurring donors help us plan long-term programs that create lasting change.', 'compassion-ngo' ); ?></p>
                </div>
            </div>

            <!-- Trust / Info Sidebar -->
            <aside>
                <div style="background:var(--color-light);border-radius:16px;padding:1.75rem;margin-bottom:1.5rem;">
                    <h3 style="font-size:1.1rem;margin-bottom:1.25rem;"><?php _e( 'Your Donation Is Safe', 'compassion-ngo' ); ?></h3>
                    <?php
                    $trust = [
                        ['🔒', __( '256-bit SSL encryption', 'compassion-ngo' )],
                        ['✅', __( 'Registered non-profit organization', 'compassion-ngo' )],
                        ['📋', __( 'Full financial transparency', 'compassion-ngo' )],
                        ['🧾', __( 'Tax-deductible receipt by email', 'compassion-ngo' )],
                    ];
                    foreach ( $trust as [$icon, $text] ) : ?>
                        <div style="display:flex;gap:.75rem;align-items:flex-start;margin-bottom:.85rem;">
                            <span style="font-size:1.1rem;flex-shrink:0;"><?php echo $icon; ?></span>
                            <span style="font-size:.9rem;"><?php echo esc_html($text); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Active Campaigns -->
                <?php
                $campaigns = new WP_Query([
                    'post_type'      => 'campaign',
                    'posts_per_page' => 3,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                ]);
                if ( $campaigns->have_posts() ) : ?>
                    <div style="border:1px solid var(--color-border);border-radius:16px;padding:1.75rem;">
                        <h3 style="font-size:1rem;margin-bottom:1.25rem;"><?php _e( 'Active Campaigns', 'compassion-ngo' ); ?></h3>
                        <?php while ( $campaigns->have_posts() ) : $campaigns->the_post();
                            $goal   = (float) get_post_meta( get_the_ID(), '_campaign_goal',   true );
                            $raised = (float) get_post_meta( get_the_ID(), '_campaign_raised', true );
                            $pct    = $goal ? min( 100, round( $raised / $goal * 100 ) ) : 0;
                        ?>
                            <div style="margin-bottom:1.25rem;padding-bottom:1.25rem;border-bottom:1px solid var(--color-border);">
                                <a href="<?php the_permalink(); ?>" style="font-weight:600;font-size:.92rem;display:block;margin-bottom:.5rem;"><?php the_title(); ?></a>
                                <?php if ( $goal ) : ?>
                                    <div class="progress-bar"><div class="progress-fill" style="width:<?php echo $pct; ?>%"></div></div>
                                    <div style="display:flex;justify-content:space-between;font-size:.78rem;color:var(--color-mid);margin-top:.3rem;">
                                        <span>$<?php echo number_format($raised); ?> raised</span>
                                        <span><?php echo $pct; ?>%</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php endif; ?>

            </aside>
        </div>
    </div>

</main>

<style>
.donate-amount-card:hover,
.donate-amount-card.selected {
    border-color: var(--color-primary) !important;
    background: rgba(26,107,74,.05);
}
</style>
<script>
document.querySelectorAll('.donate-amount-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.donate-amount-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
    });
});
</script>

<?php get_footer(); ?>
