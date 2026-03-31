<?php
/**
 * Compassion NGO — Template Tags & Helpers
 *
 * @package CompassionNGO
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// -------------------------------------------------------
// Breadcrumbs
// -------------------------------------------------------
function compassion_breadcrumbs() {
    if ( is_front_page() ) return;

    $separator = '<span class="breadcrumb-sep" aria-hidden="true"> / </span>';
    $home      = '<a href="' . esc_url( home_url('/') ) . '">' . __( 'Home', 'compassion-ngo' ) . '</a>';

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'compassion-ngo' ) . '" style="font-size:.82rem;color:var(--color-mid);margin-bottom:1.25rem;">';
    echo $home;

    if ( is_singular('post') ) {
        $cats = get_the_category();
        if ( $cats ) echo $separator . '<a href="' . esc_url( get_category_link($cats[0]->term_id) ) . '">' . esc_html($cats[0]->name) . '</a>';
        echo $separator . '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';

    } elseif ( is_singular('program') ) {
        echo $separator . '<a href="' . esc_url( get_post_type_archive_link('program') ) . '">' . __( 'Programs', 'compassion-ngo' ) . '</a>';
        echo $separator . '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';

    } elseif ( is_singular('campaign') ) {
        echo $separator . '<a href="' . esc_url( get_post_type_archive_link('campaign') ) . '">' . __( 'Campaigns', 'compassion-ngo' ) . '</a>';
        echo $separator . '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';

    } elseif ( is_singular('team_member') ) {
        echo $separator . '<a href="' . esc_url( get_post_type_archive_link('team_member') ) . '">' . __( 'Team', 'compassion-ngo' ) . '</a>';
        echo $separator . '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';

    } elseif ( is_singular() ) {
        echo $separator . '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';

    } elseif ( is_post_type_archive() ) {
        echo $separator . '<span aria-current="page">' . post_type_archive_title( '', false ) . '</span>';

    } elseif ( is_category() ) {
        echo $separator . '<span aria-current="page">' . single_cat_title( '', false ) . '</span>';

    } elseif ( is_tag() ) {
        echo $separator . '<span aria-current="page">' . single_tag_title( '', false ) . '</span>';

    } elseif ( is_search() ) {
        echo $separator . '<span aria-current="page">' . __( 'Search Results', 'compassion-ngo' ) . '</span>';

    } elseif ( is_404() ) {
        echo $separator . '<span aria-current="page">' . __( '404 Not Found', 'compassion-ngo' ) . '</span>';
    }

    echo '</nav>';
}

// -------------------------------------------------------
// Related Posts
// -------------------------------------------------------
function compassion_related_posts( $post_id = null, $count = 3 ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $cats = wp_get_post_categories( $post_id );
    if ( ! $cats ) return;

    $related = new WP_Query([
        'post_type'           => 'post',
        'posts_per_page'      => $count,
        'category__in'        => $cats,
        'post__not_in'        => [ $post_id ],
        'ignore_sticky_posts' => 1,
        'orderby'             => 'rand',
    ]);

    if ( ! $related->have_posts() ) return;
    ?>
    <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid var(--color-border);">
        <h3><?php _e( 'Related Articles', 'compassion-ngo' ); ?></h3>
        <div class="cards-grid" style="margin-top:1rem;">
            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                <div class="card">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="card-image">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('compassion-card'); ?></a>
                        </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <div class="card-meta"><time><?php the_date(); ?></time></div>
                        <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                        <a class="read-more" href="<?php the_permalink(); ?>"><?php _e( 'Read', 'compassion-ngo' ); ?></a>
                    </div>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
}

// -------------------------------------------------------
// Impact Banner (reusable section)
// -------------------------------------------------------
function compassion_impact_banner( $title = '', $text = '', $cta_label = '', $cta_url = '' ) {
    $title     = $title     ?: __( 'Join Us in Making a Difference', 'compassion-ngo' );
    $text      = $text      ?: __( 'Together we can build a more just and compassionate world. Your support matters.', 'compassion-ngo' );
    $cta_label = $cta_label ?: __( 'Get Involved', 'compassion-ngo' );
    $cta_url   = $cta_url   ?: get_theme_mod('compassion_donate_url', '/donate');
    ?>
    <section style="background:var(--color-primary);color:#fff;padding:5rem 0;text-align:center;">
        <div class="container" style="max-width:640px;">
            <h2 style="color:#fff;margin-bottom:1rem;"><?php echo esc_html($title); ?></h2>
            <p style="opacity:.85;margin-bottom:2rem;"><?php echo esc_html($text); ?></p>
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn-accent">
                <?php echo esc_html($cta_label); ?>
            </a>
        </div>
    </section>
    <?php
}

// -------------------------------------------------------
// Post reading time estimate
// -------------------------------------------------------
function compassion_reading_time( $post_id = null ) {
    if ( ! $post_id ) $post_id = get_the_ID();
    $content   = get_post_field( 'post_content', $post_id );
    $word_count = str_word_count( strip_tags( $content ) );
    $minutes    = max( 1, (int) ceil( $word_count / 200 ) );
    return sprintf( _n( '%d min read', '%d min read', $minutes, 'compassion-ngo' ), $minutes );
}
