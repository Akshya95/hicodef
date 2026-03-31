<?php get_header(); ?>

<main id="main-content" class="site-main">

  <!-- ================================================
       IMAGE SLIDER — data injected by wp_footer hook
       ================================================ -->
  <div class="hicodef-slider-wrap" id="hicodef-slider-section">
    <div id="hicodef-slider">
      <?php
      // Server-side fallback: render first slide as <noscript> + visible default
      $slides = hicodef_slides();
      if ( empty( $slides ) ) : ?>
        <div class="hicodef-slide active slide-empty">
          <div class="slide-caption">
            <span class="caption-tag"><?php echo esc_html( get_option( 'hicodef_hero_kicker', 'Himalayan Community Development Forum' ) ); ?></span>
            <div class="caption-title"><?php echo esc_html( get_option( 'hicodef_hero_title', 'Empowering Communities for a Resilient Future' ) ); ?></div>
            <div class="caption-sub"><?php echo esc_html( get_option( 'hicodef_hero_desc', '' ) ); ?></div>
          </div>
        </div>
      <?php else :
        foreach ( $slides as $i => $s ) :
          $active = $i === 0 ? 'active' : '';
      ?>
        <div class="hicodef-slide <?php echo esc_attr( $active ); ?>">
          <?php if ( $s['img'] ) : ?>
            <img class="slide-img" src="<?php echo esc_url( $s['img'] ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>">
            <div class="slide-overlay"></div>
          <?php endif; ?>
          <?php if ( $s['title'] || $s['tag'] || $s['desc'] ) : ?>
            <div class="slide-caption">
              <?php if ( $s['tag'] )   : ?><span class="caption-tag"><?php echo esc_html( $s['tag'] ); ?></span><?php endif; ?>
              <?php if ( $s['title'] ) : ?><div class="caption-title"><?php echo esc_html( $s['title'] ); ?></div><?php endif; ?>
              <?php if ( $s['desc'] )  : ?><div class="caption-sub"><?php echo esc_html( $s['desc'] ); ?></div><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; endif; ?>
    </div>
    <?php if ( count( $slides ) > 1 ) : ?>
      <button class="slider-arrow prev" id="hicodef-slider-prev" aria-label="<?php esc_attr_e( 'Previous', 'compassion-ngo' ); ?>">&#8592;</button>
      <button class="slider-arrow next" id="hicodef-slider-next" aria-label="<?php esc_attr_e( 'Next', 'compassion-ngo' ); ?>">&#8594;</button>
      <div class="slider-counter" id="hicodef-slider-counter"></div>
      <div class="slider-dots" id="hicodef-slider-dots"></div>
    <?php endif; ?>
  </div>

  <!-- ================================================
       STATS BAR
       ================================================ -->
  <section class="stats-bar">
    <div class="container">
      <div class="stats-grid">
        <?php
        $stat_defaults = [
          1 => [ '30+',  'Years of Service' ],
          2 => [ '15+',  'Active Projects'  ],
          3 => [ '20+',  'Districts'        ],
          4 => [ '50K+', 'Beneficiaries'    ],
        ];
        for ( $i = 1; $i <= 4; $i++ ) :
          $num = get_option( "hicodef_stat_{$i}_num", $stat_defaults[$i][0] );
          $lbl = get_option( "hicodef_stat_{$i}_lbl", $stat_defaults[$i][1] );
        ?>
          <div class="stat-item">
            <span class="stat-number"><?php echo esc_html( $num ); ?></span>
            <span class="stat-label"><?php echo esc_html( $lbl ); ?></span>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  </section>

  <!-- ================================================
       THEMATIC AREAS
       ================================================ -->
  <section class="section-pad">
    <div class="container">
      <div class="section-header">
        <span class="eyebrow"><?php _e( 'What We Do', 'compassion-ngo' ); ?></span>
        <h2><?php _e( 'Our Thematic Areas', 'compassion-ngo' ); ?></h2>
        <div class="section-divider"></div>
        <p><?php _e( "HICODEF delivers integrated programs across ten thematic areas, reaching marginalised communities in Nepal's hills and plains.", 'compassion-ngo' ); ?></p>
      </div>
      <div class="thematic-grid">
        <?php foreach ( [
          [ '🌿', 'Natural Resource Management', 'nrm'          ],
          [ '💼', 'Livelihoods & Food Security', 'livelihoods'  ],
          [ '🌡️', 'Climate Change Adaptation',   'climate-change'],
          [ '🌾', 'Sustainable Agriculture',      'agriculture'  ],
          [ '♿', 'Disability Inclusion',         'disability'   ],
          [ '🏥', 'Health',                       'health'       ],
          [ '📚', 'Education',                    'education'    ],
          [ '🏛️', 'Governance',                   'governance'   ],
          [ '👶', 'Child Rights',                 'child-rights' ],
          [ '⚠️', 'Disaster Risk Reduction',      'drr'          ],
        ] as [ $icon, $label, $slug ] ) : ?>
          <a href="<?php echo esc_url( home_url( '/thematic-area/' . $slug ) ); ?>" class="thematic-card">
            <span class="thematic-icon"><?php echo $icon; ?></span>
            <span class="thematic-label"><?php echo esc_html( $label ); ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- ================================================
       ONGOING PROJECTS
       ================================================ -->
  <section class="bg-light section-pad">
    <div class="container">
      <div class="section-header">
        <span class="eyebrow"><?php _e( 'Current Work', 'compassion-ngo' ); ?></span>
        <h2><?php _e( 'Ongoing Projects', 'compassion-ngo' ); ?></h2>
        <div class="section-divider"></div>
      </div>
      <?php
      $projects = new WP_Query( [ 'post_type' => 'program', 'posts_per_page' => 4, 'orderby' => 'menu_order', 'order' => 'ASC' ] );
      if ( $projects->have_posts() ) :
      ?>
        <div class="cards-grid">
          <?php while ( $projects->have_posts() ) : $projects->the_post();
            $area    = get_post_meta( get_the_ID(), '_proj_area',    true );
            $partner = get_post_meta( get_the_ID(), '_proj_partner', true );
            $bene    = get_post_meta( get_the_ID(), '_proj_bene',    true );
          ?>
            <div class="card">
              <?php if ( has_post_thumbnail() ) : ?>
                <div class="card-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'compassion-card' ); ?></a></div>
              <?php endif; ?>
              <div class="card-body">
                <span class="card-category"><?php _e( 'Project', 'compassion-ngo' ); ?></span>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p><?php the_excerpt(); ?></p>
                <?php if ( $area )    : ?><p class="card-meta">📍 <?php echo esc_html( $area ); ?></p><?php endif; ?>
                <?php if ( $bene )    : ?><p class="card-meta">👥 <?php echo esc_html( $bene ); ?></p><?php endif; ?>
                <?php if ( $partner ) : ?><p class="card-meta">🤝 <?php echo esc_html( $partner ); ?></p><?php endif; ?>
                <a class="read-more" href="<?php the_permalink(); ?>"><?php _e( 'Learn More', 'compassion-ngo' ); ?></a>
              </div>
            </div>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <p class="text-center hm-empty"><?php _e( 'Projects coming soon.', 'compassion-ngo' ); ?></p>
      <?php endif; ?>
      <div class="view-all-row">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>" class="btn btn-outline"><?php _e( 'View all projects', 'compassion-ngo' ); ?></a>
      </div>
    </div>
  </section>

  <!-- ================================================
       PARTNER CAROUSEL — data injected by wp_footer
       ================================================ -->
  <section class="hicodef-partners-section" id="hicodef-partner-carousel">
    <div class="partners-section-head">
      <span class="partners-kicker"><?php _e( 'We Work With', 'compassion-ngo' ); ?></span>
      <div class="partners-title"><?php _e( 'Partners &amp; Donors', 'compassion-ngo' ); ?></div>
      <div class="partners-rule"></div>
    </div>

    <div class="partner-track-outer">
      <div class="partner-fade-l"></div>
      <div class="partner-fade-r"></div>
      <div class="hicodef-partner-track" id="hicodef-partner-track">
        <?php
        // Server-side rendered logos for SEO + no-JS fallback
        $partners = hicodef_partners_list();
        foreach ( $partners as $p ) :
        ?>
          <div class="hicodef-partner-card">
            <?php if ( $p['url'] ) : ?><a href="<?php echo esc_url( $p['url'] ); ?>" target="_blank" rel="noopener" title="<?php echo esc_attr( $p['name'] ); ?>"><?php endif; ?>
            <?php if ( $p['img'] ) : ?>
              <img src="<?php echo esc_url( $p['img'] ); ?>" alt="<?php echo esc_attr( $p['name'] ); ?>">
            <?php else : ?>
              <span class="partner-name-fallback"><?php echo esc_html( $p['name'] ); ?></span>
            <?php endif; ?>
            <?php if ( $p['url'] ) : ?></a><?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="partner-carousel-nav">
      <button class="partner-arrow" id="hicodef-partner-prev" aria-label="<?php esc_attr_e( 'Previous', 'compassion-ngo' ); ?>">&#8592;</button>
      <div class="partner-dots" id="hicodef-partner-dots"></div>
      <button class="partner-arrow" id="hicodef-partner-next" aria-label="<?php esc_attr_e( 'Next', 'compassion-ngo' ); ?>">&#8594;</button>
    </div>
  </section>

  <!-- ================================================
       NEWS + SUCCESS STORIES
       ================================================ -->
  <section class="bg-light section-pad">
    <div class="container">
      <div class="news-stories-grid">

        <div>
          <span class="eyebrow"><?php _e( 'Latest Updates', 'compassion-ngo' ); ?></span>
          <h2 class="section-h2"><?php _e( 'News &amp; Events', 'compassion-ngo' ); ?></h2>
          <div class="section-divider" style="margin-left:0;"></div>
          <?php
          $news = new WP_Query( [ 'post_type' => 'post', 'posts_per_page' => 4 ] );
          if ( $news->have_posts() ) :
            while ( $news->have_posts() ) : $news->the_post(); ?>
              <div class="news-item">
                <?php if ( has_post_thumbnail() ) : ?>
                  <a href="<?php the_permalink(); ?>" class="news-thumb"><?php the_post_thumbnail( [ 64, 64 ] ); ?></a>
                <?php endif; ?>
                <div class="news-body">
                  <time class="news-date"><?php the_date(); ?></time>
                  <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                </div>
              </div>
            <?php endwhile; wp_reset_postdata();
          else : ?>
            <p class="hm-empty"><?php _e( 'No news yet.', 'compassion-ngo' ); ?></p>
          <?php endif; ?>
          <a href="<?php echo esc_url( home_url( '/news' ) ); ?>" class="read-more"><?php _e( 'All news', 'compassion-ngo' ); ?></a>
        </div>

        <div>
          <span class="eyebrow"><?php _e( 'From the Field', 'compassion-ngo' ); ?></span>
          <h2 class="section-h2"><?php _e( 'Success Stories', 'compassion-ngo' ); ?></h2>
          <div class="section-divider" style="margin-left:0;"></div>
          <?php
          $stories = new WP_Query( [ 'post_type' => 'story', 'posts_per_page' => 3 ] );
          if ( $stories->have_posts() ) :
            while ( $stories->have_posts() ) : $stories->the_post(); ?>
              <div class="story-item">
                <h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <p><?php echo wp_trim_words( get_the_excerpt(), 15 ); ?></p>
              </div>
            <?php endwhile; wp_reset_postdata();
          else : ?>
            <p class="hm-empty"><?php _e( 'Field stories coming soon.', 'compassion-ngo' ); ?></p>
          <?php endif; ?>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'story' ) ); ?>" class="read-more"><?php _e( 'All stories', 'compassion-ngo' ); ?></a>
        </div>

      </div>
    </div>
  </section>

  <!-- ================================================
       IMPACT BANNER
       ================================================ -->
  <?php
  compassion_impact_banner(
    __( 'Join HICODEF in Building Resilient Communities', 'compassion-ngo' ),
    __( "Partner with us, volunteer, or support our work in Nepal's most vulnerable communities.", 'compassion-ngo' ),
    __( 'Get Involved', 'compassion-ngo' ),
    home_url( '/get-involved' )
  );
  ?>

</main>

<?php get_footer(); ?>
