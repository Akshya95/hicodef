<?php
/**
 * HICODEF Theme — functions.php
 * @package CompassionNGO
 * @version 2.4.1
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Safe include helper
 */
if ( ! function_exists( 'hicodef_require' ) ) {
    function hicodef_require( $file ) {
        $path = get_template_directory() . $file;
        if ( file_exists( $path ) ) {
            require_once $path;
        }
    }
}

// Load required files
hicodef_require( '/inc/template-tags.php' );
hicodef_require( '/inc/customizer-colors.php' );
hicodef_require( '/inc/admin-panel.php' );

/**
 * Theme setup
 */
add_action( 'after_setup_theme', 'hicodef_setup' );
function hicodef_setup() {
    load_theme_textdomain( 'compassion-ngo', get_template_directory() . '/languages' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ) );
    
    add_image_size( 'compassion-card', 560, 360, true );
    add_image_size( 'compassion-hero', 1920, 700, true );
    add_image_size( 'compassion-team', 300, 300, true );
    
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'compassion-ngo' ),
        'footer'  => __( 'Footer Menu',  'compassion-ngo' ),
    ) );
}

/**
 * Scripts & styles
 */
add_action( 'wp_enqueue_scripts', 'hicodef_scripts' );
function hicodef_scripts() {
    wp_enqueue_style( 'hicodef-fonts',
        'https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&family=Inter:wght@300;400;500&display=swap',
        array(), null );
    wp_enqueue_style( 'compassion-style', get_stylesheet_uri(), array( 'hicodef-fonts' ), '2.4' );
    wp_enqueue_style( 'hicodef-slider', get_template_directory_uri() . '/css/slider.css', array(), '2.4' );
    
    wp_enqueue_script( 'compassion-main', get_template_directory_uri() . '/js/main.js', array(), '2.4', true );
    
    if ( is_front_page() ) {
        wp_enqueue_script( 'hicodef-slider',   get_template_directory_uri() . '/js/slider.js',            array( 'compassion-main' ), '2.4', true );
        wp_enqueue_script( 'hicodef-partners', get_template_directory_uri() . '/js/partners-carousel.js', array( 'compassion-main' ), '2.4', true );
    }
    
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

/**
 * Widgets
 */
add_action( 'widgets_init', 'hicodef_widgets' );
function hicodef_widgets() {
    $sidebars = array(
        array( 'id' => 'sidebar-1',  'name' => 'Main Sidebar' ),
        array( 'id' => 'footer-1',   'name' => 'Footer Column 1' ),
        array( 'id' => 'footer-2',   'name' => 'Footer Column 2' ),
        array( 'id' => 'footer-3',   'name' => 'Footer Column 3' ),
    );
    foreach ( $sidebars as $s ) {
        register_sidebar( array(
            'id'            => $s['id'],
            'name'          => $s['name'],
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>',
        ) );
    }
}

/**
 * Custom Post Types
 */
add_action( 'init', 'hicodef_register_cpts' );
function hicodef_register_cpts() {
    // Projects
    register_post_type( 'program', array(
        'labels'      => array( 'name' => 'Projects', 'singular_name' => 'Project', 'menu_name' => 'Projects' ),
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'projects' ),
        'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'menu_icon'   => 'dashicons-clipboard',
        'show_in_rest'=> true,
    ) );

    // Partners
    register_post_type( 'partner', array(
        'labels'      => array( 'name' => 'Partners & Donors', 'singular_name' => 'Partner', 'menu_name' => 'Partners' ),
        'public'      => false,
        'show_ui'     => true,
        'supports'    => array( 'title', 'thumbnail' ),
        'menu_icon'   => 'dashicons-groups',
        'show_in_rest'=> true,
    ) );

    // Vacancies
    register_post_type( 'vacancy', array(
        'labels'      => array( 'name' => 'Vacancies', 'singular_name' => 'Vacancy', 'menu_name' => 'Vacancies' ),
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'vacancies' ),
        'supports'    => array( 'title', 'editor', 'excerpt' ),
        'menu_icon'   => 'dashicons-businessperson',
        'show_in_rest'=> true,
    ) );

    // Publications
    register_post_type( 'publication', array(
        'labels'      => array( 'name' => 'Publications', 'singular_name' => 'Publication', 'menu_name' => 'Publications' ),
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'publications' ),
        'supports'    => array( 'title', 'thumbnail', 'excerpt' ),
        'menu_icon'   => 'dashicons-media-document',
        'show_in_rest'=> true,
    ) );

    // Success Stories
    register_post_type( 'story', array(
        'labels'      => array( 'name' => 'Success Stories', 'singular_name' => 'Story', 'menu_name' => 'Stories' ),
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'stories' ),
        'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'   => 'dashicons-heart',
        'show_in_rest'=> true,
    ) );

    // Campaigns
    register_post_type( 'campaign', array(
        'labels'      => array( 'name' => 'Campaigns', 'singular_name' => 'Campaign', 'menu_name' => 'Campaigns' ),
        'public'      => true,
        'has_archive' => true,
        'rewrite'     => array( 'slug' => 'campaigns' ),
        'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'   => 'dashicons-megaphone',
        'show_in_rest'=> true,
    ) );

    // Team
    register_post_type( 'team_member', array(
        'labels'      => array( 'name' => 'Team', 'singular_name' => 'Team Member', 'menu_name' => 'Team' ),
        'public'      => true,
        'has_archive' => false,
        'rewrite'     => array( 'slug' => 'team' ),
        'supports'    => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'   => 'dashicons-id',
        'show_in_rest'=> true,
    ) );
}

/**
 * Meta boxes
 */
add_action( 'add_meta_boxes', 'hicodef_add_meta_boxes' );
function hicodef_add_meta_boxes() {
    add_meta_box( 'hicodef_project_meta', 'Project Details', 'hicodef_project_meta_cb', 'program', 'side', 'high' );
    add_meta_box( 'hicodef_partner_meta', 'Partner Details', 'hicodef_partner_meta_cb', 'partner', 'side', 'high' );
    add_meta_box( 'hicodef_vacancy_meta', 'Vacancy Details', 'hicodef_vacancy_meta_cb', 'vacancy', 'side', 'high' );
    add_meta_box( 'hicodef_pub_meta',     'Publication Details', 'hicodef_pub_meta_cb', 'publication', 'side', 'high' );
}

function hicodef_project_meta_cb( $post ) {
    wp_nonce_field( 'hicodef_proj_nonce', 'proj_nonce' );
    $status  = get_post_meta( $post->ID, '_proj_status',  true );
    $area    = get_post_meta( $post->ID, '_proj_area',    true );
    $partner = get_post_meta( $post->ID, '_proj_partner', true );
    $start   = get_post_meta( $post->ID, '_proj_start',   true );
    $end     = get_post_meta( $post->ID, '_proj_end',     true );
    $bene    = get_post_meta( $post->ID, '_proj_bene',    true );
    echo '<table class="form-table" style="width:100%">';
    echo '<tr><th style="width:40%">Status</th><td><select name="proj_status" style="width:100%">';
    foreach ( array( 'Ongoing', 'Completed', 'Upcoming' ) as $s ) {
        echo '<option value="' . esc_attr($s) . '"' . selected($status,$s,false) . '>' . esc_html($s) . '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><th>Working area</th><td><input type="text" name="proj_area" value="' . esc_attr($area) . '" style="width:100%" placeholder="e.g. Kawasoti Municipality"></td></tr>';
    echo '<tr><th>Partner</th><td><input type="text" name="proj_partner" value="' . esc_attr($partner) . '" style="width:100%"></td></tr>';
    echo '<tr><th>Start date</th><td><input type="date" name="proj_start" value="' . esc_attr($start) . '" style="width:100%"></td></tr>';
    echo '<tr><th>End date</th><td><input type="date" name="proj_end" value="' . esc_attr($end) . '" style="width:100%"></td></tr>';
    echo '<tr><th>Beneficiaries</th><td><input type="text" name="proj_bene" value="' . esc_attr($bene) . '" style="width:100%" placeholder="e.g. 1,200 households"></td></tr>';
    echo '</table>';
}

function hicodef_partner_meta_cb( $post ) {
    wp_nonce_field( 'hicodef_partner_nonce', 'partner_nonce' );
    $url  = get_post_meta( $post->ID, '_partner_url',  true );
    $type = get_post_meta( $post->ID, '_partner_type', true );
    echo '<table class="form-table" style="width:100%">';
    echo '<tr><th style="width:40%">Type</th><td><select name="partner_type" style="width:100%">';
    foreach ( array( 'ingo' => 'INGO / International', 'govt' => 'Government', 'local' => 'Local Partner', 'donor' => 'Donor' ) as $val => $lbl ) {
        echo '<option value="' . esc_attr($val) . '"' . selected($type,$val,false) . '>' . esc_html($lbl) . '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><th>Website URL</th><td><input type="url" name="partner_url" value="' . esc_url($url) . '" style="width:100%"></td></tr>';
    echo '</table>';
}

function hicodef_vacancy_meta_cb( $post ) {
    wp_nonce_field( 'hicodef_vac_nonce', 'vac_nonce' );
    $deadline = get_post_meta( $post->ID, '_vac_deadline',  true );
    $type     = get_post_meta( $post->ID, '_vac_type',      true );
    $location = get_post_meta( $post->ID, '_vac_location',  true );
    $apply    = get_post_meta( $post->ID, '_vac_apply_url', true );
    echo '<table class="form-table" style="width:100%">';
    echo '<tr><th style="width:40%">Deadline</th><td><input type="date" name="vac_deadline" value="' . esc_attr($deadline) . '" style="width:100%"></td></tr>';
    echo '<tr><th>Type</th><td><select name="vac_type" style="width:100%">';
    foreach ( array( 'Full-time', 'Part-time', 'Contract', 'OJT / Intern', 'Volunteer' ) as $t ) {
        echo '<option' . selected($type,$t,false) . '>' . esc_html($t) . '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><th>Location</th><td><input type="text" name="vac_location" value="' . esc_attr($location) . '" style="width:100%" placeholder="e.g. Kawasoti-02"></td></tr>';
    echo '<tr><th>Apply URL</th><td><input type="text" name="vac_apply_url" value="' . esc_attr($apply) . '" style="width:100%"></td></tr>';
    echo '</table>';
}

function hicodef_pub_meta_cb( $post ) {
    wp_nonce_field( 'hicodef_pub_nonce', 'pub_nonce' );
    $type     = get_post_meta( $post->ID, '_pub_type',     true );
    $year     = get_post_meta( $post->ID, '_pub_year',     true );
    $download = get_post_meta( $post->ID, '_pub_download', true );
    echo '<table class="form-table" style="width:100%">';
    echo '<tr><th style="width:40%">Type</th><td><select name="pub_type" style="width:100%">';
    foreach ( array( 'annual-report' => 'Annual Report', 'quarterly-report' => 'Quarterly Report', 'case-study' => 'Case Study', 'research' => 'Research' ) as $val => $lbl ) {
        echo '<option value="' . esc_attr($val) . '"' . selected($type,$val,false) . '>' . esc_html($lbl) . '</option>';
    }
    echo '</select></td></tr>';
    echo '<tr><th>Year</th><td><input type="number" name="pub_year" value="' . esc_attr($year) . '" style="width:100%" min="2000" max="2099"></td></tr>';
    echo '<tr><th>Download URL</th><td><input type="url" name="pub_download" value="' . esc_attr($download) . '" style="width:100%"></td></tr>';
    echo '</table>';
}

/**
 * Save meta
 */
add_action( 'save_post', 'hicodef_save_all_meta' );
function hicodef_save_all_meta( $post_id ) {
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

    // Project
    if ( isset($_POST['proj_nonce']) && wp_verify_nonce($_POST['proj_nonce'], 'hicodef_proj_nonce') && current_user_can('edit_post', $post_id) ) {
        $proj_fields = array( 'proj_status' => '_proj_status', 'proj_area' => '_proj_area', 'proj_partner' => '_proj_partner', 'proj_start' => '_proj_start', 'proj_end' => '_proj_end', 'proj_bene' => '_proj_bene' );
        foreach ( $proj_fields as $k => $m ) {
            if ( isset($_POST[$k]) ) update_post_meta( $post_id, $m, sanitize_text_field( wp_unslash($_POST[$k]) ) );
        }
    }
    // Partner
    if ( isset($_POST['partner_nonce']) && wp_verify_nonce($_POST['partner_nonce'], 'hicodef_partner_nonce') && current_user_can('edit_post', $post_id) ) {
        if ( isset($_POST['partner_type']) ) update_post_meta( $post_id, '_partner_type', sanitize_key( wp_unslash($_POST['partner_type']) ) );
        if ( isset($_POST['partner_url'])  ) update_post_meta( $post_id, '_partner_url',  esc_url_raw( wp_unslash($_POST['partner_url']) ) );
    }
    // Vacancy
    if ( isset($_POST['vac_nonce']) && wp_verify_nonce($_POST['vac_nonce'], 'hicodef_vac_nonce') && current_user_can('edit_post', $post_id) ) {
        $vac_fields = array( 'vac_deadline' => '_vac_deadline', 'vac_type' => '_vac_type', 'vac_location' => '_vac_location', 'vac_apply_url' => '_vac_apply_url' );
        foreach ( $vac_fields as $k => $m ) {
            if ( isset($_POST[$k]) ) update_post_meta( $post_id, $m, sanitize_text_field( wp_unslash($_POST[$k]) ) );
        }
    }
    // Publication
    if ( isset($_POST['pub_nonce']) && wp_verify_nonce($_POST['pub_nonce'], 'hicodef_pub_nonce') && current_user_can('edit_post', $post_id) ) {
        $pub_fields = array( 'pub_type' => '_pub_type', 'pub_year' => '_pub_year' );
        foreach ( $pub_fields as $k => $m ) {
            if ( isset($_POST[$k]) ) update_post_meta( $post_id, $m, sanitize_text_field( wp_unslash($_POST[$k]) ) );
        }
        if ( isset($_POST['pub_download']) ) update_post_meta( $post_id, '_pub_download', esc_url_raw( wp_unslash($_POST['pub_download']) ) );
    }
}

/**
 * Excerpt filters
 */
add_filter( 'excerpt_length', function() { return 20; } );
add_filter( 'excerpt_more',   function() { return '&hellip;'; } );

/**
 * Body classes
 */
add_filter( 'body_class', function( $classes ) {
    if ( is_singular() ) $classes[] = 'singular';
    return $classes;
} );

/**
 * Flush rewrite on activation
 */
add_action( 'after_switch_theme', function() { flush_rewrite_rules(); } );
