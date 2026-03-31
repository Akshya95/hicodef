<?php
/**
 * Template Name: Publications Page
 */
get_header(); ?>

<main id="main-content" class="site-main">

    <div class="bg-light" style="padding:4rem 0 3rem;text-align:center;">
        <div class="container" style="max-width:680px;">
            <span class="hero-eyebrow"><?php _e( 'Resources', 'compassion-ngo' ); ?></span>
            <h1><?php the_title(); ?></h1>
            <p class="lead"><?php _e( 'Download HICODEF annual reports, quarterly project updates, case studies, and research documents.', 'compassion-ngo' ); ?></p>
        </div>
    </div>

    <div class="container content-area">

        <?php the_content(); ?>

        <!-- Filter tabs -->
        <?php
        $pub_types = [
            'all'              => __( 'All', 'compassion-ngo' ),
            'annual-report'    => __( 'Annual Reports', 'compassion-ngo' ),
            'quarterly-report' => __( 'Quarterly Reports', 'compassion-ngo' ),
            'case-study'       => __( 'Case Studies', 'compassion-ngo' ),
            'research'         => __( 'Research', 'compassion-ngo' ),
        ];
        $current_filter = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : 'all';
        ?>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;">
            <?php foreach ( $pub_types as $slug => $label ) :
                $active = $current_filter === $slug;
                $href   = $slug === 'all' ? get_permalink() : add_query_arg('type', $slug, get_permalink());
            ?>
                <a href="<?php echo esc_url($href); ?>"
                   style="padding:.4rem 1rem;border-radius:99px;font-size:.85rem;font-weight:600;border:1px solid <?php echo $active ? 'var(--color-primary)' : 'var(--color-border)'; ?>;background:<?php echo $active ? 'var(--color-primary)' : 'transparent'; ?>;color:<?php echo $active ? '#fff' : 'var(--color-mid)'; ?>;">
                    <?php echo esc_html($label); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php
        $meta_query = [];
        if ( $current_filter !== 'all' ) {
            $meta_query = [[ 'key' => '_pub_type', 'value' => $current_filter ]];
        }

        $publications = new WP_Query([
            'post_type'      => 'publication',
            'posts_per_page' => 20,
            'meta_query'     => $meta_query,
            'orderby'        => 'meta_value',
            'meta_key'       => '_pub_year',
            'order'          => 'DESC',
        ]);

        if ( $publications->have_posts() ) : ?>
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <?php while ( $publications->have_posts() ) : $publications->the_post();
                    $type     = get_post_meta(get_the_ID(), '_pub_type', true);
                    $year     = get_post_meta(get_the_ID(), '_pub_year', true);
                    $download = get_post_meta(get_the_ID(), '_pub_download', true);
                    $type_labels = [
                        'annual-report'    => 'Annual Report',
                        'quarterly-report' => 'Quarterly Report',
                        'case-study'       => 'Case Study',
                        'research'         => 'Research',
                    ];
                    $type_label = $type_labels[$type] ?? 'Publication';
                ?>
                    <div style="display:flex;align-items:center;gap:1.25rem;padding:1.25rem;border:1px solid var(--color-border);border-radius:10px;background:var(--color-white);transition:box-shadow .2s;" onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,.07)'" onmouseout="this.style.boxShadow=''">

                        <!-- Icon -->
                        <div style="width:52px;height:52px;background:rgba(26,107,74,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">📄</div>

                        <!-- Info -->
                        <div style="flex:1;min-width:0;">
                            <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;margin-bottom:.3rem;">
                                <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--color-primary);"><?php echo esc_html($type_label); ?></span>
                                <?php if ($year) : ?><span style="font-size:.75rem;color:var(--color-mid);"><?php echo esc_html($year); ?></span><?php endif; ?>
                            </div>
                            <h3 style="font-size:1rem;margin:0 0 .25rem;"><?php the_title(); ?></h3>
                            <?php if (get_the_excerpt()) : ?><p style="font-size:.85rem;color:var(--color-mid);margin:0;"><?php echo wp_trim_words(get_the_excerpt(),20); ?></p><?php endif; ?>
                        </div>

                        <!-- Download -->
                        <?php if ($download) : ?>
                            <a href="<?php echo esc_url($download); ?>" target="_blank" rel="noopener"
                               class="btn btn-outline" style="flex-shrink:0;font-size:.85rem;padding:.5rem 1.1rem;">
                                ⬇ <?php _e('Download PDF','compassion-ngo'); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>" class="btn btn-outline" style="flex-shrink:0;font-size:.85rem;padding:.5rem 1.1rem;">
                                <?php _e('View','compassion-ngo'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div style="text-align:center;padding:4rem 0;color:var(--color-mid);">
                <p style="font-size:2rem;">📂</p>
                <p><?php _e('No publications found. Check back soon.','compassion-ngo'); ?></p>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
