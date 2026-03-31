<?php
/**
 * Template Name: Contact Page
 */
get_header(); ?>

<main id="main-content" class="site-main">

    <div style="background:var(--color-light);padding:5rem 0 3.5rem;text-align:center;">
        <div class="container" style="max-width:640px;">
            <span class="hero-eyebrow"><?php _e( 'Get In Touch', 'compassion-ngo' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php _e( 'We\'d love to hear from you — whether you\'re a potential partner, volunteer, donor, or just want to learn more.', 'compassion-ngo' ); ?></p>
        </div>
    </div>

    <div class="container" style="padding:4rem 1.5rem;max-width:1060px;">
        <div style="display:grid;grid-template-columns:1fr 320px;gap:3rem;align-items:start;">

            <!-- Contact Form -->
            <div>
                <h2 style="margin-bottom:1.5rem;"><?php _e( 'Send Us a Message', 'compassion-ngo' ); ?></h2>

                <?php
                // Show success message
                if ( isset( $_GET['sent'] ) && $_GET['sent'] === '1' ) : ?>
                    <div style="padding:1.25rem 1.5rem;background:#e8f5ee;border:1px solid var(--color-primary);border-radius:8px;color:var(--color-primary);margin-bottom:1.5rem;">
                        ✅ <?php _e( 'Thank you! Your message has been sent.', 'compassion-ngo' ); ?>
                    </div>
                <?php endif; ?>

                <!-- Page content first (for shortcodes like CF7 / WPForms) -->
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

                <!-- Fallback HTML form (replace with CF7/WPForms shortcode in editor) -->
                <div style="background:var(--color-light);border:2px dashed var(--color-border);border-radius:10px;padding:1.5rem;margin-top:1.5rem;text-align:center;">
                    <p style="margin:0;color:var(--color-mid);font-size:.9rem;">
                        <?php _e( '📬 Paste your contact form shortcode (Contact Form 7, WPForms, etc.) into this page\'s content area.', 'compassion-ngo' ); ?>
                    </p>
                </div>
            </div>

            <!-- Contact Info -->
            <aside>
                <div style="background:var(--color-primary);color:#fff;border-radius:16px;padding:2rem;margin-bottom:1.5rem;">
                    <h3 style="color:#fff;margin-bottom:1.25rem;font-size:1.1rem;"><?php _e( 'Contact Information', 'compassion-ngo' ); ?></h3>
                    <?php
                    $info = [
                        ['📍', __( 'Address', 'compassion-ngo' ), 'Kawasoti-02, Shivabasti, Nawalparasi (Bardaghat Susta Purba), Nepal'],
                        ['📞', __( 'Phone', 'compassion-ngo' ),   '+1 (000) 000-0000'],
                        ['✉',  __( 'Email', 'compassion-ngo' ),   'info@hicodef.org'],
                        ['🕐', __( 'Hours', 'compassion-ngo' ),   'Mon–Fri, 9am – 5pm'],
                    ];
                    foreach ( $info as [$icon, $label, $value] ) : ?>
                        <div style="display:flex;gap:.75rem;margin-bottom:1rem;">
                            <span style="font-size:1.1rem;flex-shrink:0;"><?php echo $icon; ?></span>
                            <div>
                                <span style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.1em;opacity:.7;margin-bottom:.1rem;"><?php echo esc_html($label); ?></span>
                                <span style="font-size:.92rem;"><?php echo esc_html($value); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Volunteer CTA -->
                <div style="border:1px solid var(--color-border);border-radius:16px;padding:1.5rem;text-align:center;">
                    <span style="font-size:2rem;display:block;margin-bottom:.5rem;">🤝</span>
                    <h3 style="font-size:1rem;margin-bottom:.5rem;"><?php _e( 'Want to Volunteer?', 'compassion-ngo' ); ?></h3>
                    <p style="font-size:.88rem;color:var(--color-mid);margin-bottom:1rem;"><?php _e( 'Join our network of dedicated volunteers making a difference on the ground.', 'compassion-ngo' ); ?></p>
                    <a href="<?php echo esc_url( home_url('/volunteer') ); ?>" class="btn btn-outline" style="width:100%;justify-content:center;">
                        <?php _e( 'Volunteer With Us', 'compassion-ngo' ); ?>
                    </a>
                </div>
            </aside>

        </div>
    </div>

</main>

<?php get_footer(); ?>
