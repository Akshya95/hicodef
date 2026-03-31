<?php get_header(); ?>

<main id="main-content" class="site-main">
    <div class="container content-area">
        <div class="two-col-layout">

            <div>
                <?php while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <header class="single-post-header">
                            <span class="post-category-badge"><?php _e( 'Program', 'compassion-ngo' ); ?></span>
                            <h1 class="entry-title" style="margin-top:0.5rem;"><?php the_title(); ?></h1>
                        </header>

                        <?php if ( has_post_thumbnail() ) : ?>
                            <div style="margin-bottom:2rem;border-radius:8px;overflow:hidden;">
                                <?php the_post_thumbnail( 'compassion-hero' ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Related Campaigns -->
                        <?php
                        $campaigns = new WP_Query( [ 'post_type' => 'campaign', 'posts_per_page' => 2 ] );
                        if ( $campaigns->have_posts() ) : ?>
                            <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-border);">
                                <h3><?php _e( 'Support This Work', 'compassion-ngo' ); ?></h3>
                                <div class="cards-grid">
                                    <?php while ( $campaigns->have_posts() ) : $campaigns->the_post();
                                        $goal   = (float) get_post_meta( get_the_ID(), '_campaign_goal', true );
                                        $raised = (float) get_post_meta( get_the_ID(), '_campaign_raised', true );
                                        $pct    = $goal ? min( 100, round( $raised / $goal * 100 ) ) : 0;
                                    ?>
                                        <div class="card">
                                            <div class="card-body">
                                                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                <?php if ( $goal ) : ?>
                                                    <div class="campaign-progress">
                                                        <div class="progress-bar"><div class="progress-fill" style="width:<?php echo $pct; ?>%"></div></div>
                                                        <div class="progress-meta">
                                                            <span><strong>$<?php echo number_format( $raised ); ?></strong></span>
                                                            <span><?php echo $pct; ?>%</span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <a href="<?php the_permalink(); ?>" class="btn btn-primary" style="margin-top:1rem;width:100%;justify-content:center;"><?php _e( 'Donate', 'compassion-ngo' ); ?></a>
                                            </div>
                                        </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </article>

                <?php endwhile; ?>
            </div>

            <?php get_sidebar(); ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
