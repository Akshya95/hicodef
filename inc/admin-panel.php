<?php
/**
 * HICODEF Site Manager — Complete Admin Panel
 * @package CompassionNGO
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// -- Helpers --
function hicodef_opt( $key, $default = '' ) {
    return get_option( $key, $default );
}

function hicodef_slides() {
    $raw = get_option( 'hicodef_slides', '[]' );
    $arr = json_decode( $raw, true );
    return ( is_array( $arr ) && ! empty( $arr ) ) ? $arr : [];
}

function hicodef_partners_list() {
    $raw = get_option( 'hicodef_partners', '[]' );
    $arr = json_decode( $raw, true );
    return ( is_array( $arr ) && ! empty( $arr ) ) ? $arr : [];
}

// -- ADMIN MENU --
add_action( 'admin_menu', function () {
    add_menu_page( 'HICODEF Manager', 'HICODEF Manager', 'edit_posts', 'hicodef-manager', 'hicodef_page_homepage', 'dashicons-admin-site-alt3', 3 );
    add_submenu_page( 'hicodef-manager', 'Homepage', 'Homepage', 'edit_posts', 'hicodef-manager', 'hicodef_page_homepage' );
    add_submenu_page( 'hicodef-manager', 'Slider', 'Slider', 'edit_posts', 'hicodef-slider', 'hicodef_page_slider' );
    add_submenu_page( 'hicodef-manager', 'Partners', 'Partners', 'edit_posts', 'hicodef-partners', 'hicodef_page_partners' );
} );

// -- UI HELPERS --
function hm_wrap( $title ) {
    ?>
    <div class="wrap hicodef-admin-wrap" style="background:#fff; padding:20px; border:1px solid #ccd0d4; margin-top:20px;">
        <h1><?php echo esc_html( $title ); ?></h1><hr>
    <?php
}
function hm_wrap_end() { echo '</div>'; }
function hm_notice( $msg ) { echo '<div class="updated notice is-dismissible"><p>' . esc_html( $msg ) . '</p></div>'; }

// -- PAGE: HOMEPAGE --
function hicodef_page_homepage() {
    if ( isset( $_POST['hm_homepage_save'] ) ) {
        update_option( 'hicodef_hero_title', sanitize_text_field($_POST['hicodef_hero_title']) );
        update_option( 'hicodef_hero_desc', sanitize_textarea_field($_POST['hicodef_hero_desc']) );
        hm_notice( 'Homepage settings saved.' );
    }
    hm_wrap( 'Homepage Settings' );
    ?>
    <form method="post">
        <table class="form-table">
            <tr>
                <th scope="row">Hero Title</th>
                <td><input type="text" name="hicodef_hero_title" value="<?php echo esc_attr(hicodef_opt('hicodef_hero_title')); ?>" class="regular-text"></td>
            </tr>
            <tr>
                <th scope="row">Hero Description</th>
                <td><textarea name="hicodef_hero_desc" rows="5" class="large-text"><?php echo esc_textarea(hicodef_opt('hicodef_hero_desc')); ?></textarea></td>
            </tr>
        </table>
        <p class="submit"><button type="submit" name="hm_homepage_save" class="button button-primary">Save Changes</button></p>
    </form>
    <?php
    hm_wrap_end();
}

// -- PAGE: PARTNERS --
function hicodef_page_partners() {
    if ( isset( $_POST['hm_partner_add'] ) ) {
        $name = sanitize_text_field( $_POST['partner_name'] );
        if ( $name ) {
            $partners = hicodef_partners_list();
            $partners[] = [
                'id'   => time(),
                'name' => $name,
                'img'  => esc_url_raw( $_POST['partner_img'] )
            ];
            update_option( 'hicodef_partners', wp_json_encode( array_values($partners) ), false );
            hm_notice( "Partner " . $name . " added successfully." );
        }
    }
    hm_wrap( 'Partner Carousel Manager' );
    ?>
    <form method="post" style="max-width: 600px;">
        <p><label>Partner Name:</label><br><input type="text" name="partner_name" class="regular-text" required></p>
        <p><label>Logo URL:</label><br><input type="text" name="partner_img" class="regular-text" required></p>
        <p><button type="submit" name="hm_partner_add" class="button button-primary">Add Partner</button></p>
    </form>
    <?php
    hm_wrap_end();
}

// Rest of your display logic...
