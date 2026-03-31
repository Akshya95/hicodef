<?php get_header(); ?>

<main id="main-content" class="site-main">

    <div class="bg-light" style="padding:4rem 0 3rem;text-align:center;">
        <div class="container" style="max-width:680px;">
            <span class="hero-eyebrow"><?php _e( 'Careers', 'compassion-ngo' ); ?></span>
            <h1><?php _e( 'Vacancies & Opportunities', 'compassion-ngo' ); ?></h1>
            <p class="lead"><?php _e( 'Join the HICODEF team and contribute to meaningful community development work across Nepal.', 'compassion-ngo' ); ?></p>
        </div>
    </div>

    <div class="container content-area" style="max-width:900px;">

        <?php if ( have_posts() ) : ?>

            <div style="display:flex;flex-direction:column;gap:1.25rem;">
                <?php while ( have_posts() ) : the_post();
                    $deadline  = get_post_meta(get_the_ID(),'_vac_deadline',true);
                    $type      = get_post_meta(get_the_ID(),'_vac_type',true);
                    $location  = get_post_meta(get_the_ID(),'_vac_location',true);
                    $apply     = get_post_meta(get_the_ID(),'_vac_apply_url',true);
                    $days_left = $deadline ? max(0,ceil((strtotime($deadline)-time())/DAY_IN_SECONDS)) : null;
                    $is_expired = $deadline && strtotime($deadline) < time();
                ?>
                    <div style="padding:1.5rem;border:1px solid var(--color-border);border-radius:12px;background:var(--color-white);opacity:<?php echo $is_expired?'.55':'1'; ?>;">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
                            <div style="flex:1;min-width:200px;">
                                <?php if($is_expired) : ?><span style="font-size:.72rem;font-weight:700;color:#999;text-transform:uppercase;letter-spacing:.08em;"><?php _e('Closed','compassion-ngo'); ?></span><br><?php endif; ?>
                                <h3 style="margin:.3rem 0 .6rem;font-size:1.15rem;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div style="display:flex;gap:.75rem;flex-wrap:wrap;font-size:.83rem;color:var(--color-mid);">
                                    <?php if($type)     : ?><span>💼 <?php echo esc_html($type); ?></span><?php endif; ?>
                                    <?php if($location) : ?><span>📍 <?php echo esc_html($location); ?></span><?php endif; ?>
                                    <?php if($deadline && !$is_expired) : ?>
                                        <span style="color:<?php echo ($days_left<7)?'var(--color-accent)':'inherit'; ?>;font-weight:600;">
                                            ⏳ <?php printf(_n('Deadline: %s (%d day left)','Deadline: %s (%d days left)',$days_left,'compassion-ngo'), date_i18n(get_option('date_format'),strtotime($deadline)), $days_left); ?>
                                        </span>
                                    <?php elseif($is_expired) : ?>
                                        <span>📅 <?php printf(__('Closed: %s','compassion-ngo'), date_i18n(get_option('date_format'),strtotime($deadline))); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if(get_the_excerpt()) : ?><p style="font-size:.88rem;color:var(--color-mid);margin:.75rem 0 0;"><?php the_excerpt(); ?></p><?php endif; ?>
                            </div>
                            <?php if(!$is_expired) : ?>
                            <div style="display:flex;gap:.5rem;flex-direction:column;flex-shrink:0;">
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="font-size:.85rem;padding:.5rem 1.1rem;"><?php _e('View Details','compassion-ngo'); ?></a>
                                <a href="<?php echo $apply ? esc_url($apply) : the_permalink(); ?>" class="btn btn-primary" style="font-size:.85rem;padding:.5rem 1.1rem;"><?php _e('Apply Now','compassion-ngo'); ?></a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <nav class="pagination" style="margin-top:2.5rem;">
                <?php the_posts_pagination(['mid_size'=>2,'prev_text'=>'&larr;','next_text'=>'&rarr;']); ?>
            </nav>

        <?php else : ?>
            <div style="text-align:center;padding:4rem 0;">
                <p style="font-size:3rem;">📭</p>
                <h3><?php _e('No open vacancies at this time','compassion-ngo'); ?></h3>
                <p style="color:var(--color-mid);"><?php _e('Check back soon or send us your CV speculatively.','compassion-ngo'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact')); ?>" class="btn btn-primary" style="margin-top:1rem;"><?php _e('Contact Us','compassion-ngo'); ?></a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
