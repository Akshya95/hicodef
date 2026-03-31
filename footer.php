<?php
// Read all footer options from DB
$addr1   = get_option( 'hicodef_footer_addr1',   'Kawasoti-02, Shivabasti' );
$addr2   = get_option( 'hicodef_footer_addr2',   'Nawalparasi (Bardaghat Susta Purba)' );
$addr3   = get_option( 'hicodef_footer_addr3',   'Gandaki Province, Nepal' );
$email   = get_option( 'hicodef_footer_email',   'info@hicodef.org' );
$phone   = get_option( 'hicodef_footer_phone',   '' );
$hours   = get_option( 'hicodef_footer_hours',   'Sun–Fri, 9am – 5pm NPT' );
$desc    = get_option( 'hicodef_footer_desc',    'Working for marginalised communities across Nepal since 2053 BS (1996 AD). SWC registered.' );
$copy    = get_option( 'hicodef_footer_copy',    '© {year} HICODEF. All rights reserved.' );
$fb      = get_option( 'hicodef_footer_fb',      '' );
$twitter = get_option( 'hicodef_footer_twitter', '' );
$li      = get_option( 'hicodef_footer_linkedin', '' );
$yt      = get_option( 'hicodef_footer_youtube', '' );
$copy    = str_replace( '{year}', date( 'Y' ), $copy );
?>

<footer class="site-footer" role="contentinfo">
  <div class="container">
    <div class="footer-grid">

      <!-- Brand -->
      <div class="footer-col footer-brand">
        <?php if ( has_custom_logo() ) : ?>
          <div class="footer-logo"><?php the_custom_logo(); ?></div>
        <?php else : ?>
          <div class="footer-logo-text"><?php echo esc_html( get_option( 'hicodef_org_name', get_bloginfo( 'name' ) ) ); ?></div>
        <?php endif; ?>
        <p class="footer-nepali"><?php echo esc_html( get_option( 'hicodef_nepali_name', 'हिमालयन सामुदायिक विकास मञ्च' ) ); ?></p>
        <?php if ( $desc ) : ?><p class="footer-desc"><?php echo esc_html( $desc ); ?></p><?php endif; ?>

        <?php if ( $fb || $twitter || $li || $yt ) : ?>
          <div class="footer-social">
            <?php if ( $fb )      : ?><a href="<?php echo esc_url($fb); ?>"      target="_blank" rel="noopener" aria-label="Facebook">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a><?php endif; ?>
            <?php if ( $twitter ) : ?><a href="<?php echo esc_url($twitter); ?>"  target="_blank" rel="noopener" aria-label="Twitter / X">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4l16 16M20 4 4 20" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none"/></svg></a><?php endif; ?>
            <?php if ( $li )      : ?><a href="<?php echo esc_url($li); ?>"       target="_blank" rel="noopener" aria-label="LinkedIn">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg></a><?php endif; ?>
            <?php if ( $yt )      : ?><a href="<?php echo esc_url($yt); ?>"       target="_blank" rel="noopener" aria-label="YouTube">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/></svg></a><?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

      <!-- Our Work -->
      <div class="footer-col">
        <?php if ( is_active_sidebar( 'footer-1' ) ) :
          dynamic_sidebar( 'footer-1' );
        else : ?>
          <h4><?php _e( 'Our work', 'compassion-ngo' ); ?></h4>
          <ul>
            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'program' ) ); ?>"><?php _e( 'Projects', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'campaign' ) ); ?>"><?php _e( 'Campaigns', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/publications' ) ); ?>"><?php _e( 'Publications', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/get-involved' ) ); ?>"><?php _e( 'Get involved', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/policies' ) ); ?>"><?php _e( 'Policies', 'compassion-ngo' ); ?></a></li>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Quick Links -->
      <div class="footer-col">
        <?php if ( is_active_sidebar( 'footer-2' ) ) :
          dynamic_sidebar( 'footer-2' );
        else : ?>
          <h4><?php _e( 'Information', 'compassion-ngo' ); ?></h4>
          <ul>
            <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php _e( 'About us', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/team' ) ); ?>"><?php _e( 'Our team', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/news' ) ); ?>"><?php _e( 'News', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( get_post_type_archive_link( 'vacancy' ) ); ?>"><?php _e( 'Vacancies', 'compassion-ngo' ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php _e( 'Contact', 'compassion-ngo' ); ?></a></li>
          </ul>
        <?php endif; ?>
      </div>

      <!-- Contact -->
      <div class="footer-col">
        <?php if ( is_active_sidebar( 'footer-3' ) ) :
          dynamic_sidebar( 'footer-3' );
        else : ?>
          <h4><?php _e( 'Contact', 'compassion-ngo' ); ?></h4>
          <ul class="footer-contact-list">
            <?php if ( $addr1 ) : ?><li><?php echo esc_html( $addr1 ); ?></li><?php endif; ?>
            <?php if ( $addr2 ) : ?><li><?php echo esc_html( $addr2 ); ?></li><?php endif; ?>
            <?php if ( $addr3 ) : ?><li><?php echo esc_html( $addr3 ); ?></li><?php endif; ?>
            <?php if ( $email ) : ?><li><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a></li><?php endif; ?>
            <?php if ( $phone ) : ?><li><a href="tel:<?php echo esc_attr( preg_replace('/[^+0-9]/', '', $phone) ); ?>"><?php echo esc_html( $phone ); ?></a></li><?php endif; ?>
            <?php if ( $hours ) : ?><li><?php echo esc_html( $hours ); ?></li><?php endif; ?>
          </ul>
        <?php endif; ?>
      </div>

    </div><!-- .footer-grid -->

    <div class="footer-bottom">
      <span><?php echo esc_html( $copy ); ?></span>
      <span>
        <?php $pp = get_privacy_policy_url(); if ( $pp ) : ?>
          <a href="<?php echo esc_url( $pp ); ?>"><?php _e( 'Privacy Policy', 'compassion-ngo' ); ?></a> &middot;
        <?php endif; ?>
        SWC Registered &middot; Est. 2053 BS
      </span>
    </div>

  </div><!-- .container -->
</footer>

<?php wp_footer(); ?>
</body>
</html>
