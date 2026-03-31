<?php
/**
 * Template Name: Get Involved Page
 */
get_header(); ?>

<main id="main-content" class="site-main">

    <div class="bg-light" style="padding:4rem 0 3rem;text-align:center;">
        <div class="container" style="max-width:680px;">
            <span class="hero-eyebrow"><?php _e( 'Make a Difference', 'compassion-ngo' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php _e( 'There are many ways to support HICODEF\'s mission — whether through volunteering, donating, partnering, or joining as an intern.', 'compassion-ngo' ); ?></p>
        </div>
    </div>

    <div class="container content-area">

        <?php the_content(); ?>

        <!-- Four Ways to Get Involved -->
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1.5rem;margin-bottom:3rem;">

            <!-- Volunteer -->
            <div style="padding:2rem;border:1px solid var(--color-border);border-radius:16px;background:var(--color-white);">
                <div style="font-size:2.5rem;margin-bottom:1rem;">🙋</div>
                <h3><?php _e('Volunteer With Us','compassion-ngo'); ?></h3>
                <p style="color:var(--color-mid);font-size:.9rem;"><?php _e('Join our field teams and community programs. We welcome skilled volunteers in areas such as health, education, agriculture, and community development.','compassion-ngo'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:1rem;"><?php _e('Express Interest','compassion-ngo'); ?></a>
            </div>

            <!-- Donate -->
            <div style="padding:2rem;border:2px solid var(--color-primary);border-radius:16px;background:var(--color-white);">
                <div style="font-size:2.5rem;margin-bottom:1rem;">💚</div>
                <h3><?php _e('Donate','compassion-ngo'); ?></h3>
                <p style="color:var(--color-mid);font-size:.9rem;"><?php _e('Your financial support directly funds programs in education, health, livelihoods, and disaster risk reduction for Nepal\'s most vulnerable communities.','compassion-ngo'); ?></p>
                <a href="<?php echo esc_url(home_url('/donate')); ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1rem;"><?php _e('Donate Now','compassion-ngo'); ?></a>
            </div>

            <!-- Partner -->
            <div style="padding:2rem;border:1px solid var(--color-border);border-radius:16px;background:var(--color-white);">
                <div style="font-size:2.5rem;margin-bottom:1rem;">🤝</div>
                <h3><?php _e('Partner With Us','compassion-ngo'); ?></h3>
                <p style="color:var(--color-mid);font-size:.9rem;"><?php _e('HICODEF is open to strategic partnerships with INGOs, government bodies, academic institutions, and the private sector to scale impact.','compassion-ngo'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:1rem;"><?php _e('Discuss Partnership','compassion-ngo'); ?></a>
            </div>

            <!-- OJT -->
            <div style="padding:2rem;border:1px solid var(--color-border);border-radius:16px;background:var(--color-white);">
                <div style="font-size:2.5rem;margin-bottom:1rem;">🎓</div>
                <h3><?php _e('On-the-Job Training (OJT)','compassion-ngo'); ?></h3>
                <p style="color:var(--color-mid);font-size:.9rem;"><?php _e('We accept students from universities and colleges for OJT placements in development programs. Build real-world skills while contributing to community change.','compassion-ngo'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:1rem;"><?php _e('Apply for OJT','compassion-ngo'); ?></a>
            </div>

        </div>

        <!-- Current Vacancies -->
        <?php
        $vacancies = new WP_Query([
            'post_type'      => 'vacancy',
            'posts_per_page' => 5,
            'meta_query'     => [
                [
                    'key'     => '_vac_deadline',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE',
                ],
            ],
        ]);

        if ( $vacancies->have_posts() ) : ?>
            <div style="margin-top:2rem;">
                <h2><?php _e('Current Vacancies','compassion-ngo'); ?></h2>
                <div style="display:flex;flex-direction:column;gap:1rem;margin-top:1.25rem;">
                    <?php while ( $vacancies->have_posts() ) : $vacancies->the_post();
                        $deadline = get_post_meta(get_the_ID(),'_vac_deadline',true);
                        $type     = get_post_meta(get_the_ID(),'_vac_type',true);
                        $location = get_post_meta(get_the_ID(),'_vac_location',true);
                        $apply    = get_post_meta(get_the_ID(),'_vac_apply_url',true);
                        $days_left = $deadline ? max(0,ceil((strtotime($deadline)-time())/DAY_IN_SECONDS)) : null;
                    ?>
                        <div style="padding:1.25rem;border:1px solid var(--color-border);border-radius:10px;background:var(--color-white);display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                            <div style="flex:1;min-width:200px;">
                                <h4 style="margin:0 0 .4rem;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <div style="display:flex;gap:.75rem;flex-wrap:wrap;font-size:.82rem;color:var(--color-mid);">
                                    <?php if($type)     : ?><span>💼 <?php echo esc_html($type); ?></span><?php endif; ?>
                                    <?php if($location) : ?><span>📍 <?php echo esc_html($location); ?></span><?php endif; ?>
                                    <?php if($deadline) : ?>
                                        <span style="color:<?php echo ($days_left !== null && $days_left < 7) ? 'var(--color-accent)' : 'inherit'; ?>;">
                                            ⏳ <?php printf(_n('Closes in %d day','Closes in %d days',$days_left,'compassion-ngo'),$days_left); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="<?php echo $apply ? esc_url($apply) : the_permalink(); ?>" class="btn btn-primary" style="font-size:.85rem;padding:.5rem 1.1rem;flex-shrink:0;">
                                <?php _e('Apply Now','compassion-ngo'); ?>
                            </a>
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <a href="<?php echo esc_url(get_post_type_archive_link('vacancy')); ?>" class="read-more" style="margin-top:1rem;display:inline-flex;"><?php _e('View All Vacancies','compassion-ngo'); ?></a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
