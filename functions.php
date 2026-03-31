<?php
/**
 * HICODEF Theme — functions.php
 * @package CompassionNGO
 * @version 2.4.1
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// -- Safe include helper --
if ( ! function_exists( 'hicodef_require' ) ) {
    function hicodef_require( $file ) {
        $path = get_template_directory() . $file;
        if ( file_exists( $path ) ) {
            require_once $path;
        }
    }
}

// Load components
hicodef_require( '/inc/template-tags.php' );
hicodef_require( '/inc/customizer-colors.php' );
hicodef_require( '/inc/admin-panel.php' );

// -- Theme setup --
add_action( 'after_setup_theme', 'hicodef_setup' );
function hicodef_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption'] );
    add_theme_support( 'custom-logo' );
    
    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'compassion-ngo' ),
        'footer'  => __( 'Footer Menu',  'compassion-ngo' ),
    ] );
}

// -- Scripts & styles --
add_action( 'wp_enqueue_scripts', 'hicodef_scripts' );
function hicodef_scripts() {
    wp_enqueue_style( 'compassion-style', get_stylesheet_uri(), [], '2.4.1' );
    wp_enqueue_script( 'compassion-main', get_template_directory_uri() . '/js/main.js', [], '2.4.1', true );
}

// -- Custom Post Types --
add_action( 'init', 'hicodef_register_cpts' );
function hicodef_register_cpts() {
    register_post_type( 'program', [
        'labels'      => ['name' => 'Projects', 'singular_name' => 'Project'],
        'public'      => true,
        'has_archive' => true,
        'supports'    => ['title', 'editor', 'thumbnail', 'excerpt'],
        'menu_icon'   => 'dashicons-clipboard',
    ] );
    
    register_post_type( 'partner', [
        'labels'      => ['name' => 'Partners', 'singular_name' => 'Partner'],
        'public'      => false,
        'show_ui'     => true,
        'supports'    => ['title', 'thumbnail'],
        'menu_icon'   => 'dashicons-groups',
    ] );
}

// Flush rewrite on activation
add_action( 'after_switch_theme', function() { flush_rewrite_rules(); } );
