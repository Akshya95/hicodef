<?php get_header(); ?>

<main id="main-content" class="site-main">

    <!-- Archive Header -->
    <div class="bg-light" style="padding:4rem 0 3rem;">
        <div class="container text-center">
            <span class="hero-eyebrow"><?php post_type_archive_title(); ?></span>
            <h1><?php post_type_archive_title(); ?></h1>
            <?php
            $queried = get_queried_object();
            if ( $queried && isset( $queried->description ) && $queried->description ) {
                echo '<p class="lead" style="max-width:600px;margin:0 auto;">' . esc_html( $queried->description ) . '</p>';
            }
            ?>
        </div>
    </div>

    <div class="container content-area">

        <?php if ( get_post_type() === 'campaign' ) : ?>
            <!-- Campaign Archive -->
            <div class="cards-grid">
                <?php while ( have_posts() ) : the_post();
                    $goal    = (float) get_post_meta( get_the_ID(), '_campaign_goal',   true );
                    $raised  = (float) get_post_meta( get_the_ID(), '_campaign_raised', true );
                    $percent = $goal ? min( 100, round( $raised / $goal * 100 ) ) : 0;
                ?>
                    <div class="card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="card-image">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'compassion-card' ); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="card-category"><?php _e( 'Campaign', 'compassion-ngo' ); ?></span>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php the_excerpt(); ?></p>
                            <?php if ( $goal ) : ?>
                                <div class="campaign-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width:<?php echo $percent; ?>%"></div>
                                    </div>
                                    <div class="progress-meta">
                                        <span><strong>$<?php echo number_format( $raised ); ?></strong></span>
                                        <span><?php echo $percent; ?>% of $<?php echo number_format( $goal ); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <a class="btn btn-primary" href="<?php the_permalink(); ?>" style="margin-top:1rem;width:100%;justify-content:center;">
                                <?php _e( 'Donate', 'compassion-ngo' ); ?>
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php elseif ( get_post_type() === 'team_member' ) : ?>
            <!-- Team Archive -->
            <div class="cards-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="team-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'compassion-team', [ 'class' => 'team-avatar' ] ); ?>
                        <?php else : ?>
                            <div class="team-avatar" style="background:var(--color-border);display:flex;align-items:center;justify-content:center;font-size:2rem;">👤</div>
                        <?php endif; ?>
                        <h3><?php the_title(); ?></h3>
                        <p class="team-role"><?php echo esc_html( get_post_meta( get_the_ID(), '_team_role', true ) ); ?></p>
                        <p class="team-bio"><?php the_excerpt(); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>

        <?php else : ?>
            <!-- Generic / Program Archive -->
            <div class="cards-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <div class="card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="card-image">
                                <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'compassion-card' ); ?></a>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php the_excerpt(); ?></p>
                            <a class="read-more" href="<?php the_permalink(); ?>"><?php _e( 'Learn More', 'compassion-ngo' ); ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <nav class="pagination" style="margin-top:3rem;">
            <?php the_posts_pagination( [ 'mid_size' => 2, 'prev_text' => '&larr;', 'next_text' => '&rarr;' ] ); ?>
        </nav>

    </div>
</main>

<?php get_footer(); ?>
