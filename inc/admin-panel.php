<?php
/**
 * HICODEF Site Manager — Complete Admin Panel
 * Single source of truth for all site options.
 * All options stored as individual wp_options keys.
 *
 * Managed options (get_option / update_option keys):
 *   hicodef_slides         JSON array  — slider slides
 *   hicodef_partners       JSON array  — partner logos
 *   hicodef_org_name       string      — org name (English)
 *   hicodef_nepali_name    string      — org name (Nepali)
 *   hicodef_est_badge      string      — "Est. 2053 BS"
 *   hicodef_donate_label   string      — donate button text
 *   hicodef_donate_url     string      — donate button URL
 *   hicodef_show_search    bool (0|1)  — show search in header
 *   hicodef_header_style   string      — light|forest|dark
 *   hicodef_footer_addr1   string      — address line 1
 *   hicodef_footer_addr2   string      — address line 2
 *   hicodef_footer_addr3   string      — address line 3
 *   hicodef_footer_email   string      — contact email
 *   hicodef_footer_phone   string      — contact phone
 *   hicodef_footer_hours   string      — office hours
 *   hicodef_footer_desc    string      — footer brand blurb
 *   hicodef_footer_copy    string      — copyright line
 *   hicodef_footer_fb      string      — Facebook URL
 *   hicodef_footer_twitter string      — Twitter URL
 *   hicodef_footer_linkedin string     — LinkedIn URL
 *   hicodef_footer_youtube  string     — YouTube URL
 *   hicodef_hero_kicker    string      — hero eyebrow
 *   hicodef_hero_title     string      — hero headline
 *   hicodef_hero_desc      string      — hero description
 *   hicodef_cta1_label     string      — primary CTA label
 *   hicodef_cta1_url       string      — primary CTA URL
 *   hicodef_cta2_label     string      — secondary CTA label
 *   hicodef_cta2_url       string      — secondary CTA URL
 *   hicodef_stat_{1-4}_num string      — stats bar numbers
 *   hicodef_stat_{1-4}_lbl string      — stats bar labels
 *
 * @package CompassionNGO
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// ── Helper: get option with typed default ───────────────────
function hicodef_opt( $key, $default = '' ) {
    return get_option( $key, $default );
}

// ── Helper: slides list from DB ────────────────────────────
function hicodef_slides() {
    $raw = get_option( 'hicodef_slides', '[]' );
    $arr = json_decode( $raw, true );
    return ( is_array( $arr ) && ! empty( $arr ) ) ? $arr : [];
}

// ── Helper: partners list (DB first, CPT fallback) ─────────
function hicodef_partners_list() {
    $raw = get_option( 'hicodef_partners', '[]' );
    $arr = json_decode( $raw, true );
    if ( is_array( $arr ) && ! empty( $arr ) ) return $arr;
    // Fallback: pull from Partner CPT posts
    $posts = get_posts( [
        'post_type'      => 'partner',
        'posts_per_page' => 30,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
    ] );
    if ( empty( $posts ) ) return [];
    return array_map( function ( $p ) {
        return [
            'id'   => $p->ID,
            'name' => get_the_title( $p ),
            'type' => get_post_meta( $p->ID, '_partner_type', true ) ?: 'ingo',
            'url'  => get_post_meta( $p->ID, '_partner_url', true )  ?: '',
            'img'  => get_the_post_thumbnail_url( $p->ID, 'medium' ) ?: '',
        ];
    }, $posts );
}

// ── INJECT DATA INTO FRONT-END (wp_footer, priority 5 = before JS) ──
add_action( 'wp_footer', function () {
    if ( ! is_front_page() ) return;
    $slides   = hicodef_slides();
    $partners = hicodef_partners_list();
    echo '<script id="hicodef-data">';
    echo 'window.hicodefSlides='   . wp_json_encode( $slides )   . ';';
    echo 'window.hicodefPartners=' . wp_json_encode( $partners ) . ';';
    echo 'window.hicodefIsAdmin='  . ( current_user_can( 'edit_posts' ) ? 'true' : 'false' ) . ';';
    echo '</script>';
}, 5 );

// ── ADMIN MENU REGISTRATION ─────────────────────────────────
add_action( 'admin_menu', function () {
    add_menu_page(
        'HICODEF Manager',
        'HICODEF Manager',
        'edit_posts',
        'hicodef-manager',
        'hicodef_page_homepage',
        'dashicons-admin-site-alt3',
        3
    );
    add_submenu_page( 'hicodef-manager', 'Homepage',       'Homepage',       'edit_posts', 'hicodef-manager',  'hicodef_page_homepage' );
    add_submenu_page( 'hicodef-manager', 'Slider',         'Slider',         'edit_posts', 'hicodef-slider',   'hicodef_page_slider' );
    add_submenu_page( 'hicodef-manager', 'Partners',       'Partners',       'edit_posts', 'hicodef-partners', 'hicodef_page_partners' );
    add_submenu_page( 'hicodef-manager', 'Header',         'Header',         'edit_posts', 'hicodef-header',   'hicodef_page_header' );
    add_submenu_page( 'hicodef-manager', 'Footer',         'Footer',         'edit_posts', 'hicodef-footer',   'hicodef_page_footer' );
} );

// ── ADMIN ASSETS ────────────────────────────────────────────
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    $our_pages = [
        'toplevel_page_hicodef-manager',
        'hicodef-manager_page_hicodef-slider',
        'hicodef-manager_page_hicodef-partners',
        'hicodef-manager_page_hicodef-header',
        'hicodef-manager_page_hicodef-footer',
    ];
    if ( ! in_array( $hook, $our_pages, true ) ) return;
    wp_enqueue_media();
    wp_enqueue_style(  'hicodef-admin-css', get_template_directory_uri() . '/css/admin.css', [], '2.3.1' );
    wp_enqueue_script( 'hicodef-admin-js',  get_template_directory_uri() . '/js/admin.js',  [ 'jquery', 'media-upload' ], '2.3.1', true );
    wp_localize_script( 'hicodef-admin-js', 'hicodefAdmin', [
        'nonce'   => wp_create_nonce( 'hicodef_nonce' ),
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    ] );
} );

// ── SHARED LAYOUT HELPERS ───────────────────────────────────
function hm_wrap( $title ) {
    $nav = [
        'hicodef-manager'  => [ 'dashicons-admin-home',   'Homepage' ],
        'hicodef-slider'   => [ 'dashicons-format-image',  'Slider' ],
        'hicodef-partners' => [ 'dashicons-groups',        'Partners' ],
        'hicodef-header'   => [ 'dashicons-arrow-up-alt',  'Header' ],
        'hicodef-footer'   => [ 'dashicons-arrow-down-alt','Footer' ],
    ];
    $cur = $_GET['page'] ?? 'hicodef-manager';
    ?>
    <div class="hm-wrap">
      <aside class="hm-sidebar">
        <div class="hm-brand">
          <div class="hm-brand-mark">H</div>
          <div>
            <div class="hm-brand-name">HICODEF</div>
            <div class="hm-brand-sub">Site Manager</div>
          </div>
        </div>
        <nav class="hm-nav">
          <?php foreach ( $nav as $slug => [ $icon, $label ] ) : ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug ) ); ?>"
               class="hm-nav-item <?php echo $cur === $slug ? 'active' : ''; ?>">
              <span class="dashicons <?php echo esc_attr( $icon ); ?>"></span>
              <?php echo esc_html( $label ); ?>
            </a>
          <?php endforeach; ?>
        </nav>
        <div class="hm-sidebar-foot">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">View website ↗</a>
        </div>
      </aside>
      <div class="hm-main">
        <div class="hm-topbar">
          <h1 class="hm-title"><?php echo esc_html( $title ); ?></h1>
        </div>
        <div class="hm-body">
    <?php
}

function hm_wrap_end() {
    echo '</div></div></div>';
}

function hm_notice( $msg, $type = 'updated' ) {
    echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
}

function hm_section( $title, $desc = '' ) {
    echo '<div class="hm-section"><div class="hm-section-head"><strong>' . esc_html( $title ) . '</strong>';
    if ( $desc ) echo '<span class="hm-section-desc">' . esc_html( $desc ) . '</span>';
    echo '</div>';
}

function hm_section_end() { echo '</div>'; }

function hm_field( $label, $name, $value, $type = 'text', $args = [] ) {
    $placeholder = $args['placeholder'] ?? '';
    $rows        = $args['rows'] ?? 3;
    echo '<div class="hm-field">';
    echo '<label for="hm_' . esc_attr( $name ) . '">' . esc_html( $label ) . '</label>';
    if ( $type === 'textarea' ) {
        echo '<textarea id="hm_' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" rows="' . (int) $rows . '" placeholder="' . esc_attr( $placeholder ) . '">' . esc_textarea( $value ) . '</textarea>';
    } elseif ( $type === 'checkbox' ) {
        echo '<label class="hm-toggle"><input type="checkbox" id="hm_' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, 1, false ) . '><span class="hm-toggle-slider"></span></label>';
    } else {
        echo '<input type="' . esc_attr( $type ) . '" id="hm_' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" placeholder="' . esc_attr( $placeholder ) . '">';
    }
    echo '</div>';
}

function hm_save_btn( $name, $label = 'Save changes' ) {
    echo '<p class="hm-save-row"><button type="submit" name="' . esc_attr( $name ) . '" class="button button-primary hm-save-btn">' . esc_html( $label ) . '</button></p>';
}

// ── PAGE: HOMEPAGE ──────────────────────────────────────────
function hicodef_page_homepage() {
    if ( isset( $_POST['hm_homepage_save'] ) && check_admin_referer( 'hm_homepage' ) ) {
        $fields = [
            'hicodef_hero_kicker' => 'text',
            'hicodef_hero_title'  => 'text',
            'hicodef_hero_desc'   => 'textarea',
            'hicodef_cta1_label'  => 'text',
            'hicodef_cta1_url'    => 'url',
            'hicodef_cta2_label'  => 'text',
            'hicodef_cta2_url'    => 'url',
        ];
        foreach ( $fields as $key => $type ) {
            if ( ! isset( $_POST[ $key ] ) ) continue;
            $val = $type === 'url'
                ? esc_url_raw( wp_unslash( $_POST[ $key ] ) )
                : sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) );
            update_option( $key, $val, false );
        }
        for ( $i = 1; $i <= 4; $i++ ) {
            if ( isset( $_POST["hicodef_stat_{$i}_num"] ) ) update_option( "hicodef_stat_{$i}_num", sanitize_text_field( wp_unslash( $_POST["hicodef_stat_{$i}_num"] ) ), false );
            if ( isset( $_POST["hicodef_stat_{$i}_lbl"] ) ) update_option( "hicodef_stat_{$i}_lbl", sanitize_text_field( wp_unslash( $_POST["hicodef_stat_{$i}_lbl"] ) ), false );
        }
        hm_notice( 'Homepage settings saved. Changes are live on the website.' );
    }

    hm_wrap( 'Homepage settings' );
    ?>
    <form method="post">
      <?php wp_nonce_field( 'hm_homepage' ); ?>

      <?php hm_section( 'Hero section', 'Appears below the image slider' ); ?>
        <?php hm_field( 'Eyebrow / kicker text', 'hicodef_hero_kicker', hicodef_opt( 'hicodef_hero_kicker', 'Himalayan Community Development Forum' ) ); ?>
        <?php hm_field( 'Hero headline', 'hicodef_hero_title', hicodef_opt( 'hicodef_hero_title', 'Empowering Communities for a Resilient Future' ) ); ?>
        <?php hm_field( 'Hero description', 'hicodef_hero_desc', hicodef_opt( 'hicodef_hero_desc', "HICODEF works with marginalised communities across Nepal on natural resource management, livelihoods, climate change adaptation, health, education, and governance." ), 'textarea', ['rows'=>3] ); ?>
      <?php hm_section_end(); ?>

      <?php hm_section( 'Call-to-action buttons' ); ?>
        <?php hm_field( 'Primary button label', 'hicodef_cta1_label', hicodef_opt( 'hicodef_cta1_label', 'Our Projects' ) ); ?>
        <?php hm_field( 'Primary button URL',   'hicodef_cta1_url',   hicodef_opt( 'hicodef_cta1_url',   '/projects' ), 'url' ); ?>
        <?php hm_field( 'Secondary button label', 'hicodef_cta2_label', hicodef_opt( 'hicodef_cta2_label', 'Get Involved' ) ); ?>
        <?php hm_field( 'Secondary button URL',   'hicodef_cta2_url',   hicodef_opt( 'hicodef_cta2_url',   '/get-involved' ), 'url' ); ?>
      <?php hm_section_end(); ?>

      <?php hm_section( 'Stats bar', 'The four figures shown on the green strip' ); ?>
        <div class="hm-stats-grid">
          <?php
          $stat_defaults = [ ['30+','Years of Service'], ['15+','Active Projects'], ['20+','Districts'], ['50K+','Beneficiaries'] ];
          for ( $i = 1; $i <= 4; $i++ ) :
          ?>
            <div class="hm-stat-pair">
              <input type="text" name="hicodef_stat_<?php echo $i; ?>_num"
                     value="<?php echo esc_attr( hicodef_opt( "hicodef_stat_{$i}_num", $stat_defaults[$i-1][0] ) ); ?>"
                     placeholder="e.g. 30+">
              <input type="text" name="hicodef_stat_<?php echo $i; ?>_lbl"
                     value="<?php echo esc_attr( hicodef_opt( "hicodef_stat_{$i}_lbl", $stat_defaults[$i-1][1] ) ); ?>"
                     placeholder="e.g. Years of Service">
            </div>
          <?php endfor; ?>
        </div>
      <?php hm_section_end(); ?>

      <?php hm_save_btn( 'hm_homepage_save' ); ?>
    </form>
    <?php
    hm_wrap_end();
}

// ── PAGE: SLIDER ────────────────────────────────────────────
function hicodef_page_slider() {
    // Handle add
    if ( isset( $_POST['hm_slide_add'] ) && check_admin_referer( 'hm_slider' ) ) {
        $slides   = hicodef_slides();
        $slides[] = [
            'id'    => time(),
            'img'   => esc_url_raw( wp_unslash( $_POST['slide_img'] ?? '' ) ),
            'title' => sanitize_text_field( wp_unslash( $_POST['slide_title'] ?? '' ) ),
            'tag'   => sanitize_text_field( wp_unslash( $_POST['slide_tag'] ?? '' ) ),
            'desc'  => sanitize_text_field( wp_unslash( $_POST['slide_desc'] ?? '' ) ),
        ];
        update_option( 'hicodef_slides', wp_json_encode( $slides ), false );
        hm_notice( 'Slide added. It is now live on the homepage.' );
    }
    // Handle delete
    if ( isset( $_POST['hm_slide_delete'] ) && check_admin_referer( 'hm_slider' ) ) {
        $del_id = absint( $_POST['slide_id'] ?? 0 );
        $slides  = array_values( array_filter( hicodef_slides(), function( $s ) use ( $del_id ) {
            return (int) $s['id'] !== $del_id;
        } ) );
        update_option( 'hicodef_slides', wp_json_encode( $slides ), false );
        hm_notice( 'Slide removed.' );
    }
    // Handle edit
    if ( isset( $_POST['hm_slide_edit'] ) && check_admin_referer( 'hm_slider' ) ) {
        $edit_id = absint( $_POST['slide_id'] ?? 0 );
        $slides  = hicodef_slides();
        foreach ( $slides as &$s ) {
            if ( (int)$s['id'] === $edit_id ) {
                $s['img']   = esc_url_raw( wp_unslash( $_POST['slide_img'] ?? '' ) );
                $s['title'] = sanitize_text_field( wp_unslash( $_POST['slide_title'] ?? '' ) );
                $s['tag']   = sanitize_text_field( wp_unslash( $_POST['slide_tag'] ?? '' ) );
                $s['desc']  = sanitize_text_field( wp_unslash( $_POST['slide_desc'] ?? '' ) );
            }
        }
        update_option( 'hicodef_slides', wp_json_encode( array_values( $slides ) ), false );
        hm_notice( 'Slide updated. Changes are live.' );
    }

    $slides = hicodef_slides();
    hm_wrap( 'Homepage slider (' . count( $slides ) . ' slides)' );
    ?>

    <?php hm_section( 'Current slides', 'Slides appear in this order on the homepage. The slider height is fixed — images auto-crop to fill.' ); ?>
    <?php if ( empty( $slides ) ) : ?>
      <p class="hm-empty">No slides yet. Add your first slide below.</p>
    <?php else : ?>
      <table class="hm-table">
        <thead><tr><th>Preview</th><th>Title</th><th>Tag</th><th>Description</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ( $slides as $s ) : ?>
          <tr>
            <td class="hm-table-thumb">
              <?php if ( $s['img'] ) : ?>
                <img src="<?php echo esc_url( $s['img'] ); ?>" alt="">
              <?php else : ?>
                <div class="hm-no-img">No image</div>
              <?php endif; ?>
            </td>
            <td><?php echo esc_html( $s['title'] ); ?></td>
            <td><?php echo esc_html( $s['tag'] ); ?></td>
            <td><?php echo esc_html( $s['desc'] ); ?></td>
            <td class="hm-table-actions">
              <!-- Edit form inline toggle -->
              <button class="button hm-toggle-edit" data-id="<?php echo esc_attr( $s['id'] ); ?>">Edit</button>
              <form method="post" style="display:inline;" onsubmit="return confirm('Remove this slide?')">
                <?php wp_nonce_field( 'hm_slider' ); ?>
                <input type="hidden" name="slide_id" value="<?php echo esc_attr( $s['id'] ); ?>">
                <button type="submit" name="hm_slide_delete" class="button hm-btn-danger">Delete</button>
              </form>
              <!-- Edit row (hidden) -->
              <div class="hm-edit-row" id="edit-<?php echo esc_attr( $s['id'] ); ?>" style="display:none">
                <form method="post">
                  <?php wp_nonce_field( 'hm_slider' ); ?>
                  <input type="hidden" name="slide_id" value="<?php echo esc_attr( $s['id'] ); ?>">
                  <div class="hm-edit-fields">
                    <div>
                      <?php if ( $s['img'] ) : ?><img src="<?php echo esc_url($s['img']); ?>" class="hm-edit-preview"><?php endif; ?>
                      <input type="hidden" name="slide_img" id="slide-img-<?php echo esc_attr($s['id']); ?>" value="<?php echo esc_url($s['img']); ?>">
                      <button type="button" class="button hm-media-btn" data-target="slide-img-<?php echo esc_attr($s['id']); ?>">Change image</button>
                    </div>
                    <div class="hm-edit-text">
                      <input type="text" name="slide_title" value="<?php echo esc_attr($s['title']); ?>" placeholder="Slide title">
                      <input type="text" name="slide_tag"   value="<?php echo esc_attr($s['tag']); ?>"   placeholder="Tag line">
                      <input type="text" name="slide_desc"  value="<?php echo esc_attr($s['desc']); ?>"  placeholder="Description">
                      <button type="submit" name="hm_slide_edit" class="button button-primary">Save changes</button>
                    </div>
                  </div>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <?php hm_section_end(); ?>

    <?php hm_section( 'Add a new slide' ); ?>
    <form method="post" class="hm-add-form">
      <?php wp_nonce_field( 'hm_slider' ); ?>
      <div class="hm-add-thumb">
        <div class="hm-no-img" id="new-slide-placeholder">No image selected</div>
        <img id="new-slide-preview" src="" alt="" style="display:none;max-width:100%;max-height:100%;object-fit:cover;border-radius:8px;">
      </div>
      <div class="hm-add-fields">
        <input type="hidden" name="slide_img" id="new-slide-img" value="">
        <button type="button" class="button hm-media-btn" data-target="new-slide-img" data-preview="new-slide-preview" data-placeholder="new-slide-placeholder">
          Select image from media library
        </button>
        <input type="text" name="slide_title" placeholder="Slide title  e.g. Farmers in Nawalparasi">
        <input type="text" name="slide_tag"   placeholder="Tag line  e.g. Programs · STTP">
        <input type="text" name="slide_desc"  placeholder="Description (optional)">
        <button type="submit" name="hm_slide_add" class="button button-primary">Add slide to homepage</button>
      </div>
    </form>
    <?php hm_section_end(); ?>

    <?php hm_wrap_end(); ?>
    <?php
}

// ── PAGE: PARTNERS ──────────────────────────────────────────
function hicodef_page_partners() {
    // Handle add
    if ( isset( $_POST['hm_partner_add'] ) && check_admin_referer( 'hm_partners' ) ) {
        $name = sanitize_text_field( wp_unslash( $_POST['partner_name'] ?? '' ) );
        if ( $name ) {
            $partners   = hicodef_partners_list();
            $partners[] = [
                'id'   => time(),
                'name' => $name,
                'type' => sanitize_key( wp_unslash( $_POST['partner_type'] ?? 'ingo' ) ),
                'url'  => esc_url_raw( wp_unslash( $_POST['partner_url'] ?? '' ) ),
                'img'  => esc_url_raw( wp_unslash( $_POST['partner_img'] ?? '' ) ),
            ];
            update_option( 'hicodef_partners', wp_json_encode( array_values( $partners ) ), false );
            hm_notice( "Partner "{$name}" added. Now visible in the homepage carousel." );
        }
    }
    // Handle delete
    if ( isset( $_POST['hm_partner_delete'] ) && check_admin_referer( 'hm_partners' ) ) {
        $del_id   = absint( $_POST['partner_id'] ?? 0 );
        $partners = array_values( array_filter( hicodef_partners_list(), function( $p ) use ( $del_id ) {
            return (int) $p['id'] !== $del_id;
        } ) );
        update_option( 'hicodef_partners', wp_json_encode( $partners ), false );
        hm_notice( 'Partner removed from carousel.' );
    }

    $types    = [ 'ingo' => 'INGO / International', 'govt' => 'Government / SWC', 'local' => 'Local partner', 'donor' => 'Donor' ];
    $partners = hicodef_partners_list();
    hm_wrap( 'Partner carousel (' . count( $partners ) . ' partners)' );
    ?>

    <?php hm_section( 'Current partners', 'These logos scroll in the homepage carousel. Changes are live immediately after saving.' ); ?>
    <?php if ( empty( $partners ) ) : ?>
      <p class="hm-empty">No partners yet. Add your first partner below.</p>
    <?php else : ?>
      <table class="hm-table">
        <thead><tr><th>Logo</th><th>Name</th><th>Type</th><th>Website</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ( $partners as $p ) : ?>
          <tr>
            <td class="hm-table-thumb">
              <?php if ( $p['img'] ) : ?><img src="<?php echo esc_url($p['img']); ?>" alt="" style="max-height:40px;max-width:80px;object-fit:contain;"><?php else : ?><div class="hm-no-img" style="width:80px;height:40px;"><?php echo esc_html(strtoupper(substr($p['name'],0,4))); ?></div><?php endif; ?>
            </td>
            <td><?php echo esc_html( $p['name'] ); ?></td>
            <td><?php echo esc_html( $types[ $p['type'] ] ?? $p['type'] ); ?></td>
            <td><?php if ( $p['url'] ) : ?><a href="<?php echo esc_url($p['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($p['url']); ?></a><?php endif; ?></td>
            <td>
              <form method="post" style="display:inline;" onsubmit="return confirm('Remove this partner?')">
                <?php wp_nonce_field( 'hm_partners' ); ?>
                <input type="hidden" name="partner_id" value="<?php echo esc_attr( $p['id'] ); ?>">
                <button type="submit" name="hm_partner_delete" class="button hm-btn-danger">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
    <?php hm_section_end(); ?>

    <?php hm_section( 'Add a new partner' ); ?>
    <form method="post" class="hm-add-form">
      <?php wp_nonce_field( 'hm_partners' ); ?>
      <div class="hm-add-thumb" style="background:#f2ede5;">
        <div class="hm-no-img" id="new-partner-placeholder" style="color:#8a7d6e;">No logo</div>
        <img id="new-partner-preview" src="" alt="" style="display:none;max-width:100%;max-height:100%;object-fit:contain;">
      </div>
      <div class="hm-add-fields">
        <input type="hidden" name="partner_img" id="new-partner-img" value="">
        <button type="button" class="button hm-media-btn" data-target="new-partner-img" data-preview="new-partner-preview" data-placeholder="new-partner-placeholder">
          Select logo from media library
        </button>
        <input type="text"  name="partner_name" placeholder="Organisation name *" required>
        <select name="partner_type">
          <?php foreach ( $types as $val => $lbl ) : ?>
            <option value="<?php echo esc_attr($val); ?>"><?php echo esc_html($lbl); ?></option>
          <?php endforeach; ?>
        </select>
        <input type="url" name="partner_url" placeholder="Website URL  https://...">
        <button type="submit" name="hm_partner_add" class="button button-primary">Add to carousel</button>
      </div>
    </form>
    <?php hm_section_end(); ?>

    <?php hm_wrap_end(); ?>
    <?php
}

// ── PAGE: HEADER ────────────────────────────────────────────
function hicodef_page_header() {
    if ( isset( $_POST['hm_header_save'] ) && check_admin_referer( 'hm_header' ) ) {
        update_option( 'hicodef_org_name',     sanitize_text_field( wp_unslash( $_POST['hicodef_org_name']     ?? '' ) ), false );
        update_option( 'hicodef_nepali_name',  sanitize_text_field( wp_unslash( $_POST['hicodef_nepali_name']  ?? '' ) ), false );
        update_option( 'hicodef_est_badge',    sanitize_text_field( wp_unslash( $_POST['hicodef_est_badge']    ?? '' ) ), false );
        update_option( 'hicodef_donate_label', sanitize_text_field( wp_unslash( $_POST['hicodef_donate_label'] ?? '' ) ), false );
        update_option( 'hicodef_donate_url',   esc_url_raw( wp_unslash( $_POST['hicodef_donate_url'] ?? '' ) ),            false );
        update_option( 'hicodef_show_search',  isset( $_POST['hicodef_show_search'] ) ? '1' : '0',                         false );
        update_option( 'hicodef_header_style', sanitize_key( wp_unslash( $_POST['hicodef_header_style'] ?? 'light' ) ),    false );
        hm_notice( 'Header settings saved. Changes are live on the website.' );
    }

    hm_wrap( 'Header settings' );
    ?>
    <form method="post">
      <?php wp_nonce_field( 'hm_header' ); ?>

      <?php hm_section( 'Branding', 'Shown in the header pill. If you upload a custom logo via Appearance → Customize, it replaces the CSS mountain mark.' ); ?>
        <?php hm_field( 'Organisation name (English)', 'hicodef_org_name',    hicodef_opt( 'hicodef_org_name',    get_bloginfo('name') ) ); ?>
        <?php hm_field( 'Organisation name (Nepali)',  'hicodef_nepali_name', hicodef_opt( 'hicodef_nepali_name', 'हिमालयन सामुदायिक विकास मञ्च' ) ); ?>
        <?php hm_field( 'Est. badge text',             'hicodef_est_badge',   hicodef_opt( 'hicodef_est_badge',   'Est. 2053 BS' ) ); ?>
      <?php hm_section_end(); ?>

      <?php hm_section( 'Donate / CTA button' ); ?>
        <?php hm_field( 'Button label', 'hicodef_donate_label', hicodef_opt( 'hicodef_donate_label', 'Donate / Partner' ) ); ?>
        <?php hm_field( 'Button URL',   'hicodef_donate_url',   hicodef_opt( 'hicodef_donate_url',   '/get-involved' ), 'url' ); ?>
      <?php hm_section_end(); ?>

      <?php hm_section( 'Options' ); ?>
        <?php hm_field( 'Show search button', 'hicodef_show_search', hicodef_opt( 'hicodef_show_search', '1' ), 'checkbox' ); ?>
        <div class="hm-field">
          <label for="hm_hicodef_header_style">Header colour variant</label>
          <select id="hm_hicodef_header_style" name="hicodef_header_style">
            <option value="light"  <?php selected( hicodef_opt('hicodef_header_style','light'), 'light'  ); ?>>Light — white pill (default)</option>
            <option value="forest" <?php selected( hicodef_opt('hicodef_header_style','light'), 'forest' ); ?>>Forest green</option>
            <option value="dark"   <?php selected( hicodef_opt('hicodef_header_style','light'), 'dark'   ); ?>>Dark / charcoal</option>
          </select>
        </div>
      <?php hm_section_end(); ?>

      <?php hm_save_btn( 'hm_header_save' ); ?>
    </form>
    <?php
    hm_wrap_end();
}

// ── PAGE: FOOTER ────────────────────────────────────────────
function hicodef_page_footer() {
    if ( isset( $_POST['hm_footer_save'] ) && check_admin_referer( 'hm_footer' ) ) {
        // Text fields
        $text_fields = [
            'hicodef_footer_addr1',
            'hicodef_footer_addr2',
            'hicodef_footer_addr3',
            'hicodef_footer_phone',
            'hicodef_footer_hours',
            'hicodef_footer_desc',
            'hicodef_footer_copy',
            'hicodef_org_name',
            'hicodef_nepali_name',
        ];
        foreach ( $text_fields as $k ) {
            update_option( $k, sanitize_text_field( wp_unslash( $_POST[$k] ?? '' ) ), false );
        }
        // Email
        update_option( 'hicodef_footer_email', sanitize_email( wp_unslash( $_POST['hicodef_footer_email'] ?? '' ) ), false );
        // URLs
        foreach ( [ 'hicodef_footer_fb', 'hicodef_footer_twitter', 'hicodef_footer_linkedin', 'hicodef_footer_youtube' ] as $k ) {
            update_option( $k, esc_url_raw( wp_unslash( $_POST[$k] ?? '' ) ), false );
        }
        hm_notice( 'Footer settings saved. Changes are live on the website.' );
    }

    hm_wrap( 'Footer settings' );
    ?>
    <form method="post">
      <?php wp_nonce_field( 'hm_footer' ); ?>

      <?php hm_section( 'Contact information' ); ?>
        <?php hm_field( 'Address line 1', 'hicodef_footer_addr1', hicodef_opt( 'hicodef_footer_addr1', 'Kawasoti-02, Shivabasti' ) ); ?>
        <?php hm_field( 'Address line 2', 'hicodef_footer_addr2', hicodef_opt( 'hicodef_footer_addr2', 'Nawalparasi (Bardaghat Susta Purba)' ) ); ?>
        <?php hm_field( 'Address line 3', 'hicodef_footer_addr3', hicodef_opt( 'hicodef_footer_addr3', 'Gandaki Province, Nepal' ) ); ?>
        <?php hm_field( 'Email address',  'hicodef_footer_email', hicodef_opt( 'hicodef_footer_email', 'info@hicodef.org' ), 'email' ); ?>
        <?php hm_field( 'Phone number',   'hicodef_footer_phone', hicodef_opt( 'hicodef_footer_phone', '' ), 'text', ['placeholder'=>'+977-78-540172'] ); ?>
        <?php hm_field( 'Office hours',   'hicodef_footer_hours', hicodef_opt( 'hicodef_footer_hours', 'Sun–Fri, 9am – 5pm NPT' ) ); ?>
        <?php hm_field( 'About blurb (below logo)', 'hicodef_footer_desc', hicodef_opt( 'hicodef_footer_desc', 'Working for marginalised communities across Nepal since 2053 BS (1996 AD). SWC registered.' ), 'textarea' ); ?>
      <?php hm_section_end(); ?>

      <?php hm_section( 'Bottom bar' ); ?>
        <?php hm_field( 'Copyright text', 'hicodef_footer_copy', hicodef_opt( 'hicodef_footer_copy', '© {year} HICODEF. All rights reserved.' ), 'text', ['placeholder'=>'Use {year} for the current year'] ); ?>
      <?php hm_section_end(); ?>

      <?php hm_section( 'Social media links', 'Leave blank to hide any icon.' ); ?>
        <?php hm_field( 'Facebook URL',   'hicodef_footer_fb',       hicodef_opt( 'hicodef_footer_fb',       '' ), 'url', ['placeholder'=>'https://facebook.com/...'] ); ?>
        <?php hm_field( 'Twitter / X URL','hicodef_footer_twitter',   hicodef_opt( 'hicodef_footer_twitter',  '' ), 'url', ['placeholder'=>'https://twitter.com/...'] ); ?>
        <?php hm_field( 'LinkedIn URL',   'hicodef_footer_linkedin',  hicodef_opt( 'hicodef_footer_linkedin', '' ), 'url', ['placeholder'=>'https://linkedin.com/...'] ); ?>
        <?php hm_field( 'YouTube URL',    'hicodef_footer_youtube',   hicodef_opt( 'hicodef_footer_youtube',  '' ), 'url', ['placeholder'=>'https://youtube.com/...'] ); ?>
      <?php hm_section_end(); ?>

      <?php hm_save_btn( 'hm_footer_save' ); ?>
    </form>
    <?php
    hm_wrap_end();
}

// ── FALLBACK NAV (when no WP menu assigned) ─────────────────
function hicodef_fallback_nav() {
    $links = [
        'Home'         => home_url( '/' ),
        'About'        => home_url( '/about' ),
        'Projects'     => home_url( '/projects' ),
        'Publications' => home_url( '/publications' ),
        'Team'         => home_url( '/team' ),
        'News'         => home_url( '/news' ),
        'Contact'      => home_url( '/contact' ),
    ];
    echo '<ul class="nav-links">';
    foreach ( $links as $label => $url ) {
        $active = trailingslashit( $url ) === trailingslashit( get_pagenum_link() ) ? ' class="current-menu-item"' : '';
        echo '<li' . $active . '><a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul>';
}
