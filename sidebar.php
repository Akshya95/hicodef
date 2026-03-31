<aside class="widget-area" role="complementary" aria-label="<?php _e( 'Blog Sidebar', 'compassion-ngo' ); ?>">

    <!-- Donate Widget -->
    <div class="widget widget-donate">
        <h3 class="widget-title"><?php _e( 'Support Our Cause', 'compassion-ngo' ); ?></h3>
        <p><?php _e( 'Your donation helps us continue our mission and serve communities in need.', 'compassion-ngo' ); ?></p>
        <a href="<?php echo esc_url( get_theme_mod( 'compassion_donate_url', '/donate' ) ); ?>" class="btn btn-accent" style="width:100%;justify-content:center;margin-top:0.5rem;">
            <?php _e( 'Donate Now', 'compassion-ngo' ); ?>
        </a>
    </div>

    <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-blog' ); ?>
    <?php else : ?>

        <!-- Fallback: Recent Posts -->
        <div class="widget">
            <h3 class="widget-title"><?php _e( 'Recent Posts', 'compassion-ngo' ); ?></h3>
            <?php
            $recent = new WP_Query( [ 'posts_per_page' => 5 ] );
            if ( $recent->have_posts() ) :
            ?>
            <ul>
                <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
            <?php endif; ?>
        </div>

        <!-- Fallback: Programs -->
        <div class="widget">
            <h3 class="widget-title"><?php _e( 'Our Programs', 'compassion-ngo' ); ?></h3>
            <?php
            $progs = new WP_Query( [ 'post_type' => 'program', 'posts_per_page' => 5 ] );
            if ( $progs->have_posts() ) :
            ?>
            <ul>
                <?php while ( $progs->have_posts() ) : $progs->the_post(); ?>
                    <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
            <?php endif; ?>
        </div>

        <!-- Fallback: Categories -->
        <div class="widget">
            <h3 class="widget-title"><?php _e( 'Categories', 'compassion-ngo' ); ?></h3>
            <ul><?php wp_list_categories( [ 'title_li' => '', 'show_count' => 1 ] ); ?></ul>
        </div>

    <?php endif; ?>

</aside>
