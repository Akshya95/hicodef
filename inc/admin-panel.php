<?php
/**
 * HICODEF Site Manager — Complete Admin Panel
 * @package CompassionNGO
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// -- Helper: get option with typed default --
function hicodef_opt( $key, $default = '' ) {
    return get_option( $key, $default );
}

// -- Helper: slides list from DB --
function hicodef_slides() {
    $raw = get_option( 'hicodef_slides', '[]' );
    $arr = json_decode( $raw, true );
    return ( is_array( $arr ) && ! empty( $arr ) ) ? $arr : [];
}

// -- Helper: partners list (DB first, CPT fallback) --
function hicodef_partners_list() {
    $raw = get_option( 'hicodef_partners', '[]' );
    $arr = json_decode( $raw, true );
    if ( is_array( $arr ) && ! empty( $arr ) ) return $arr;
    
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

// -- INJECT DATA INTO FRONT-END --
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

// -- ADMIN MENU REGISTRATION --
add_action( 'admin_menu', function () {
    add_menu_page( 'HICODEF Manager', 'HICODEF Manager', 'edit_posts', 'hicodef-manager', 'hicodef_page_homepage', 'dashicons-admin-site-alt3', 3 );
    add_submenu_page( 'hicodef-manager', 'Homepage', 'Homepage', 'edit_posts', 'hicodef-manager', 'hicodef_page_homepage' );
    add_submenu_page( 'hicodef-manager', 'Slider', 'Slider', 'edit_posts', 'hicodef-slider', 'hicodef_page_slider' );
    add_submenu_page( 'hicodef-manager', 'Partners', 'Partners', 'edit_posts', 'hicodef-partners', 'hicodef_page_partners' );
    add_submenu_page( 'hicodef-manager', 'Header', 'Header', 'edit_posts', 'hicodef-header', 'hicodef_page_header' );
    add_submenu_page( 'hicodef-manager', 'Footer', 'Footer', 'edit_posts', 'hicodef-footer', 'hicodef_page_footer' );
} );

// -- ADMIN ASSETS --
add_action( 'admin_enqueue_scripts', function ( $hook ) {
    $our_pages = ['toplevel_page_hicodef-manager', 'hicodef-manager_page_hicodef-slider', 'hicodef-manager_page_hicodef-partners', 'hicodef-manager_page_hicodef-header', 'hicodef-manager_page_hicodef-footer'];
    if ( ! in_array( $hook, $our_pages, true ) ) return;
    wp_enqueue_media();
    wp_enqueue_style( 'hicodef-admin-css', get_template_directory_uri() . '/css/admin.css', [], '2.3.1' );
    wp_enqueue_script( 'hicodef-admin-js', get_template_directory_uri() . '/js/admin.js', ['jquery', 'media-upload'], '2.3.1', true );
} );

// -- SHARED LAYOUT HELPERS --
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
        <div class="hm-brand"><div class="hm-brand-mark">H</div><div><div class="hm-brand-name">HICODEF</div><div class="hm-brand-sub">Site Manager</div></div></div>
        <nav class="hm-nav">
          <?php foreach ( $nav as $slug => [ $icon, $label ] ) : ?>
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug ) ); ?>" class="hm-nav-item <?php echo $cur === $slug ? 'active' : ''; ?>">
              <span class="dashicons <?php echo esc_attr( $icon ); ?>"></span><?php echo esc_html( $label ); ?>
            </a>
          <?php endforeach; ?>
        </nav>
      </aside>
      <div class="hm-main"><div class="hm-topbar"><h1 class="hm-title"><?php echo esc_html( $title ); ?></h1></div><div class="hm-body">
    <?php
}

function hm_wrap_end() { echo '</div></div></div>'; }
function hm_notice( $msg, $type = 'updated' ) { echo '<div class="notice notice-' . esc_attr( $type ) . ' is-dismissible"><p>' . esc_html( $msg ) . '</p></div>'; }
function hm_section( $title, $desc = '' ) { echo '<div class="hm-section"><div class="hm-section-head"><strong>' . esc_html( $title ) . '</strong>' . ($desc ? '<span class="hm-section-desc">' . esc_html( $desc ) . '</span>' : '') . '</div>'; }
function hm_section_end() { echo '</div>'; }

function hm_field( $label, $name, $value, $type = 'text', $args = [] ) {
    echo '<div class="hm-field"><label for="hm_' . esc_attr( $name ) . '">' . esc_html( $label ) . '</label>';
    if ( $type === 'textarea' ) {
        echo '<textarea id="hm_' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" rows="' . (int) ($args['rows'] ?? 3) . '">' . esc_textarea( $value ) . '</textarea>';
    } elseif ( $type === 'checkbox' ) {
        echo '<label class="hm-toggle"><input type="checkbox" id="hm_' . esc_attr( $name ) . '" name="' . esc_attr( $name ) . '" value="1" ' . checked( $value, 1, false ) . '><span class="hm-toggle-slider"></span></label>';
    } else {
        echo '<input type="' . esc_attr($type) . '" id="hm_' . esc_attr($name) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '">';
    }
    echo '</div>';
}

function hm_save_btn( $name, $label = 'Save changes' ) { echo '<p class="hm-save-row"><button type="submit" name="' . esc_attr( $name ) . '" class="button button-primary hm-save-btn">' . esc_html( $label ) . '</button></p>'; }

// -- PAGE: HOMEPAGE --
function hicodef_page_homepage() {
    if ( isset( $_POST['hm_homepage_save'] ) && check_admin_referer( 'hm_homepage' ) ) {
        $fields = ['hicodef_hero_kicker' => 'text', 'hicodef_hero_title' => 'text', 'hicodef_hero_desc' => 'textarea', 'hicodef_cta1_label' => 'text', 'hicodef_cta1_url' => 'url', 'hicodef_cta2_label' => 'text', 'hicodef_cta2_url' => 'url'];
        foreach ( $fields as $key => $type ) {
            if ( isset( $_POST[ $key ] ) ) update_option( $key, ($type === 'url' ? esc_url_raw( wp_unslash( $_POST[ $key ] ) ) : sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) )), false );
        }
        hm_notice( 'Homepage settings saved.' );
    }
    hm_wrap( 'Homepage settings' );
    echo '<form method="post">'; wp_nonce_field( 'hm_homepage' );
    hm_section( 'Hero section' );
    hm_field( 'Headline', 'hicodef_hero_title', hicodef_opt( 'hicodef_hero_title', 'Empowering Communities' ) );
    hm_section_end();
    hm_save_btn( 'hm_homepage_save' );
    echo '</form>';
    hm_wrap_end();
}

// -- PAGE: SLIDER --
function hicodef_page_slider() {
    if ( isset( $_POST['hm_slide_add'] ) && check_admin_referer( 'hm_slider' ) ) {
        $slides = hicodef_slides();
        $slides[] = ['id' => time(), 'img' => esc_url_raw($_POST['slide_img']), 'title' => sanitize_text_field($_POST['slide_title'])];
        update_option( 'hicodef_slides', wp_json_encode( $slides ), false );
        hm_notice( 'Slide added.' );
    }
    hm_wrap( 'Homepage slider' );
    hm_section( 'Current slides' );
    // List logic here...
    hm_section_end();
    hm_wrap_end();
}

// -- PAGE: PARTNERS --
function hicodef_page_partners() {
    if ( isset( $_POST['hm_partner_add'] ) && check_admin_referer( 'hm_partners' ) ) {
        $name = sanitize_text_field( $_POST['partner_name'] );
        if ( $name ) {
            $partners = hicodef_partners_list();
            $partners[] = ['id' => time(), 'name' => $name, 'img' => esc_url_raw($_POST['partner_img'])];
            update_option( 'hicodef_partners', wp_json_encode( $partners ), false );
            hm_notice( 'Partner added.' ); // Fixed syntax error previously here
        }
    }
    hm_wrap( 'Partner carousel' );
    hm_wrap_end();
}

// Header & Footer Pages...
function hicodef_page_header() { hm_wrap('Header'); hm_wrap_end(); }
function hicodef_page_footer() { hm_wrap('Footer'); hm_wrap_end(); }

function hicodef_fallback_nav() { /* nav logic */ }
