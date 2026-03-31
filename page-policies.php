<?php
/**
 * Template Name: Policies Page
 */
get_header(); ?>

<main id="main-content" class="site-main">

    <div class="bg-light" style="padding:4rem 0 3rem;text-align:center;">
        <div class="container" style="max-width:640px;">
            <span class="hero-eyebrow"><?php _e( 'Governance & Accountability', 'compassion-ngo' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php _e( 'HICODEF is committed to transparency, accountability, and the safety of all community members and staff.', 'compassion-ngo' ); ?></p>
        </div>
    </div>

    <div class="container content-area" style="max-width:860px;">

        <?php the_content(); ?>

        <!-- Policy Cards -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-top:2rem;">
            <?php
            $policies = [
                [
                    'icon'  => '👶',
                    'title' => __( 'Child Protection Policy', 'compassion-ngo' ),
                    'desc'  => __( 'HICODEF is committed to ensuring the safety and wellbeing of all children in our programs and communities. This policy outlines our standards and procedures.', 'compassion-ngo' ),
                    'color' => '#e8f5ee',
                ],
                [
                    'icon'  => '🛡️',
                    'title' => __( 'Safeguarding Policy', 'compassion-ngo' ),
                    'desc'  => __( 'We maintain a zero-tolerance approach to exploitation and abuse. This policy protects all participants, staff, and volunteers from harm.', 'compassion-ngo' ),
                    'color' => '#fff8e8',
                ],
                [
                    'icon'  => '💰',
                    'title' => __( 'Financial Policy', 'compassion-ngo' ),
                    'desc'  => __( 'Our financial management policy ensures transparent, accountable, and efficient use of all funds in alignment with donor requirements and Nepali law.', 'compassion-ngo' ),
                    'color' => '#e8f5ee',
                ],
                [
                    'icon'  => '👥',
                    'title' => __( 'HR Policy', 'compassion-ngo' ),
                    'desc'  => __( 'Our human resources policy covers recruitment, employment, performance management, and the rights and responsibilities of all HICODEF staff.', 'compassion-ngo' ),
                    'color' => '#fff8e8',
                ],
            ];
            foreach ( $policies as $policy ) : ?>
                <div style="padding:1.75rem;border-radius:16px;background:<?php echo $policy['color']; ?>;border:1px solid var(--color-border);">
                    <div style="font-size:2.2rem;margin-bottom:.75rem;"><?php echo $policy['icon']; ?></div>
                    <h3 style="font-size:1.05rem;margin-bottom:.6rem;"><?php echo esc_html($policy['title']); ?></h3>
                    <p style="font-size:.88rem;color:var(--color-mid);margin-bottom:1rem;"><?php echo esc_html($policy['desc']); ?></p>
                    <a href="<?php echo esc_url(get_post_type_archive_link('publication') . '?type=annual-report'); ?>"
                       style="font-size:.85rem;font-weight:600;color:var(--color-primary);">
                        <?php _e('Download Policy →','compassion-ngo'); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- SWC & Registration -->
        <div style="margin-top:3rem;padding:2rem;background:var(--color-light);border-radius:12px;border-left:4px solid var(--color-primary);">
            <h3><?php _e('Legal Status & Affiliations','compassion-ngo'); ?></h3>
            <p style="color:var(--color-mid);font-size:.92rem;"><?php _e('HICODEF is registered with the Social Welfare Council (SWC) of Nepal and operates in compliance with all relevant national laws and regulations. We maintain affiliations with relevant networks and maintain full transparency with government bodies, donors, and communities.','compassion-ngo'); ?></p>
            <a href="<?php echo esc_url(home_url('/about')); ?>" class="read-more" style="display:inline-flex;"><?php _e('Learn More About HICODEF','compassion-ngo'); ?></a>
        </div>

    </div>
</main>

<?php get_footer(); ?>
