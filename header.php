<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="visually-hidden" href="#main-content"><?php _e( 'Skip to content', 'compassion-ngo' ); ?></a>

<?php
$hstyle  = get_option( 'hicodef_header_style', 'light' );
$variant = $hstyle === 'dark' ? 'header-dark' : ( $hstyle === 'forest' ? 'header-forest' : '' );
$org     = get_option( 'hicodef_org_name',     get_bloginfo( 'name' ) );
$nepali  = get_option( 'hicodef_nepali_name',  'हिमालयन सामुदायिक विकास मञ्च' );
$badge   = get_option( 'hicodef_est_badge',    'Est. 2053 BS' );
$don_lbl = get_option( 'hicodef_donate_label', 'Donate / Partner' );
$don_url = get_option( 'hicodef_donate_url',   home_url( '/get-involved' ) );
$search  = get_option( 'hicodef_show_search',  '1' );
?>

<div class="site-header-wrap" role="banner">
<header class="site-header" id="site-header">
  <div class="header-pill <?php echo esc_attr( $variant ); ?>">

    <!-- Logo -->
    <a class="logo-block" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
      <?php if ( has_custom_logo() ) : ?>
        <div class="logo-img-wrap"><?php the_custom_logo(); ?></div>
      <?php else : ?>
        <div class="logo-mark" aria-hidden="true">
          <div class="lm-sky"><div class="lm-peak"></div></div>
          <div class="lm-hill"><span class="lm-text">HICODEF</span></div>
        </div>
      <?php endif; ?>
      <div class="brand-text">
        <span class="brand-en"><?php echo esc_html( $org ); ?></span>
        <span class="brand-np"><?php echo esc_html( $nepali ); ?></span>
      </div>
    </a>

    <div class="header-divider" aria-hidden="true"></div>

    <!-- Primary nav -->
    <nav class="header-nav" id="site-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'compassion-ngo' ); ?>">
      <?php wp_nav_menu( [
        'theme_location' => 'primary',
        'menu_id'        => 'primary-menu',
        'menu_class'     => 'nav-links',
        'container'      => false,
        'fallback_cb'    => 'hicodef_fallback_nav',
      ] ); ?>
    </nav>

    <!-- Right actions -->
    <div class="header-actions">
      <?php if ( $badge ) : ?>
        <span class="est-badge"><?php echo esc_html( $badge ); ?></span>
      <?php endif; ?>

      <?php if ( $search === '1' ) : ?>
        <button class="header-search-btn" id="header-search-toggle"
                aria-label="<?php esc_attr_e( 'Search', 'compassion-ngo' ); ?>">
          <svg width="15" height="15" viewBox="0 0 15 15" fill="none" aria-hidden="true">
            <circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.5"/>
            <path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
          </svg>
        </button>
      <?php endif; ?>

      <a href="<?php echo esc_url( $don_url ); ?>" class="header-donate-btn">
        <?php echo esc_html( $don_lbl ); ?>
      </a>
    </div>

    <!-- Mobile toggle -->
    <button class="mobile-toggle" id="mobile-toggle"
            aria-controls="site-navigation"
            aria-expanded="false"
            aria-label="<?php esc_attr_e( 'Toggle menu', 'compassion-ngo' ); ?>">
      <span class="mt-bar"></span>
      <span class="mt-bar"></span>
      <span class="mt-bar"></span>
    </button>

  </div><!-- .header-pill -->

  <!-- Expandable search bar -->
  <?php if ( $search === '1' ) : ?>
  <div class="header-search-bar" id="header-search-bar" hidden>
    <?php get_search_form(); ?>
  </div>
  <?php endif; ?>

</header>
</div><!-- .site-header-wrap -->
