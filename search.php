<?php get_header(); ?>

<main id="main-content" class="site-main">
    <div class="container content-area">

        <header class="page-header" style="margin-bottom:2rem;">
            <h1>
                <?php
                /* translators: %s: search query */
                printf( __( 'Search Results for: %s', 'compassion-ngo' ), '<span>' . get_search_query() . '</span>' );
                ?>
            </h1>
        </header>

        <?php get_search_form(); ?>

        <div style="margin-top:2rem;">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article <?php post_class( 'post' ); ?>>
                        <div class="post-meta">
                            <time><?php the_date(); ?></time>
                            <span><?php the_post_type_label(); ?></span>
                        </div>
                        <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php the_excerpt(); ?></p>
                    </article>
                <?php endwhile; ?>

                <nav class="pagination" style="margin-top:2rem;">
                    <?php the_posts_pagination( [ 'mid_size' => 2, 'prev_text' => '&larr;', 'next_text' => '&rarr;' ] ); ?>
                </nav>

            <?php else : ?>
                <p><?php _e( 'No results found. Try a different search term.', 'compassion-ngo' ); ?></p>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php get_footer(); ?>
