<?php get_header(); ?>

<main id="main-content" class="site-main">
    <div class="container content-area">
        <div class="<?php echo is_active_sidebar( 'sidebar-blog' ) ? 'two-col-layout' : ''; ?>">

            <div>
                <?php while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <?php compassion_breadcrumbs(); ?>

                        <!-- Post Header -->
                        <header class="single-post-header">
                            <div class="post-meta">
                                <?php
                                $cats = get_the_category();
                                if ( $cats ) {
                                    echo '<span class="post-category-badge">' . esc_html( $cats[0]->name ) . '</span>';
                                }
                                ?>
                                <time datetime="<?php the_date( 'c' ); ?>"><?php the_date(); ?></time>
                                <span><?php the_author(); ?></span>
                                <span><?php echo compassion_reading_time(); ?></span>
                            </div>
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>

                        <!-- Featured Image -->
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="post-thumbnail" style="margin-bottom:2rem; border-radius:8px; overflow:hidden;">
                                <?php the_post_thumbnail( 'compassion-hero' ); ?>
                            </div>
                        <?php endif; ?>

                        <!-- Content -->
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Tags -->
                        <?php the_tags( '<div class="post-tags" style="margin-top:2rem;">', ' ', '</div>' ); ?>

                        <!-- Author Box -->
                        <div class="author-box" style="display:flex;gap:1.25rem;align-items:flex-start;padding:1.5rem;background:var(--color-light);border-radius:8px;margin-top:2rem;">
                            <?php echo get_avatar( get_the_author_meta( 'ID' ), 64, '', '', [ 'style' => 'border-radius:50%;flex-shrink:0;' ] ); ?>
                            <div>
                                <strong style="display:block;margin-bottom:0.25rem;"><?php the_author(); ?></strong>
                                <p style="font-size:0.9rem;color:var(--color-mid);margin:0;"><?php the_author_meta( 'description' ); ?></p>
                            </div>
                        </div>

                        <!-- Post Navigation -->
                        <nav class="post-navigation" style="display:flex;justify-content:space-between;gap:1rem;margin-top:2rem;padding-top:2rem;border-top:1px solid var(--color-border);">
                            <div><?php previous_post_link( '%link', '&larr; %title' ); ?></div>
                            <div><?php next_post_link( '%link', '%title &rarr;' ); ?></div>
                        </nav>

                        <!-- Related Posts -->
                        <?php compassion_related_posts(); ?>

                    </article>

                    <!-- Comments -->
                    <?php if ( comments_open() || get_comments_number() ) : ?>
                        <div style="margin-top:3rem; padding-top:2rem; border-top:1px solid var(--color-border);">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>

                <?php endwhile; ?>
            </div>

            <?php if ( is_active_sidebar( 'sidebar-blog' ) ) : ?>
                <?php get_sidebar(); ?>
            <?php endif; ?>

        </div>
    </div>
</main>

<?php get_footer(); ?>
