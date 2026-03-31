<?php get_header(); ?>

<main id="main-content" class="site-main">
    <div class="container content-area">
        <div class="<?php echo is_active_sidebar( 'sidebar-blog' ) ? 'two-col-layout' : ''; ?>">

            <div class="posts-list">
                <?php if ( have_posts() ) : ?>

                    <?php if ( is_home() && ! is_front_page() ) : ?>
                        <header class="page-header" style="margin-bottom:2rem;">
                            <h1 class="page-title"><?php single_post_title(); ?></h1>
                        </header>
                    <?php endif; ?>

                    <?php while ( have_posts() ) : the_post(); ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' ); ?>>

                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="post-thumbnail">
                                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'compassion-card' ); ?></a>
                                </div>
                            <?php endif; ?>

                            <div class="post-meta">
                                <?php
                                $cats = get_the_category();
                                if ( $cats ) {
                                    echo '<span class="post-category-badge">' . esc_html( $cats[0]->name ) . '</span>';
                                }
                                ?>
                                <time datetime="<?php the_date( 'c' ); ?>"><?php the_date(); ?></time>
                                <span><?php the_author(); ?></span>
                            </div>

                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <a class="read-more" href="<?php the_permalink(); ?>">
                                <?php _e( 'Read More', 'compassion-ngo' ); ?>
                            </a>

                        </article>
                    <?php endwhile; ?>

                    <!-- Pagination -->
                    <nav class="pagination" aria-label="<?php _e( 'Posts navigation', 'compassion-ngo' ); ?>">
                        <?php
                        the_posts_pagination( [
                            'mid_size'  => 2,
                            'prev_text' => '&larr;',
                            'next_text' => '&rarr;',
                        ] );
                        ?>
                    </nav>

                <?php else : ?>
                    <p><?php _e( 'No posts found. Check back soon!', 'compassion-ngo' ); ?></p>
                <?php endif; ?>
            </div><!-- .posts-list -->

            <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
                <?php get_sidebar(); ?>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
