<?php get_header(); ?>

<main id="main-content" class="site-main">
    <div class="container content-area">
        <?php while ( have_posts() ) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <header class="single-post-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <?php if ( has_post_thumbnail() ) : ?>
                    <div style="margin-bottom:2rem;border-radius:8px;overflow:hidden;">
                        <?php the_post_thumbnail( 'compassion-hero' ); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>

            </article>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-border);">
                    <?php comments_template(); ?>
                </div>
            <?php endif; ?>

        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
