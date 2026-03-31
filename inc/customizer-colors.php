<?php
/**
 * Compassion NGO — Customizer Colour Controls
 *
 * @package CompassionNGO
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'customize_register', 'compassion_colors_customizer' );

function compassion_colors_customizer( $wp_customize ) {
    $wp_customize->add_section( 'compassion_colors', [
        'title'    => __( 'Theme Colors', 'compassion-ngo' ),
        'priority' => 25,
    ] );

    $colors = [
        'compassion_color_primary' => [
            'label'   => __( 'Primary Color (green)', 'compassion-ngo' ),
            'default' => '#1a6b4a',
        ],
        'compassion_color_accent' => [
            'label'   => __( 'Accent Color (gold)', 'compassion-ngo' ),
            'default' => '#f4a926',
        ],
        'compassion_color_dark' => [
            'label'   => __( 'Dark / Text Color', 'compassion-ngo' ),
            'default' => '#1c1c1c',
        ],
        'compassion_color_light' => [
            'label'   => __( 'Light Background', 'compassion-ngo' ),
            'default' => '#f7f5f2',
        ],
    ];

    foreach ( $colors as $id => $args ) {
        $wp_customize->add_setting( $id, [
            'default'           => $args['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage',
        ] );
        $wp_customize->add_control(
            new WP_Customize_Color_Control( $wp_customize, $id, [
                'label'   => $args['label'],
                'section' => 'compassion_colors',
            ] )
        );
    }
}

// Output custom CSS variables from Customizer
add_action( 'wp_head', 'compassion_customizer_css' );

function compassion_customizer_css() {
    $primary = get_theme_mod( 'compassion_color_primary', '#1a6b4a' );
    $accent  = get_theme_mod( 'compassion_color_accent',  '#f4a926' );
    $dark    = get_theme_mod( 'compassion_color_dark',    '#1c1c1c' );
    $light   = get_theme_mod( 'compassion_color_light',   '#f7f5f2' );

    // Only output if different from defaults
    $defaults = [ '#1a6b4a', '#f4a926', '#1c1c1c', '#f7f5f2' ];
    if ( [ $primary, $accent, $dark, $light ] === $defaults ) return;

    echo '<style id="compassion-custom-colors">:root{';
    echo '--color-primary:' . sanitize_hex_color($primary) . ';';
    echo '--color-accent:'  . sanitize_hex_color($accent)  . ';';
    echo '--color-dark:'    . sanitize_hex_color($dark)    . ';';
    echo '--color-light:'   . sanitize_hex_color($light)   . ';';
    echo '}</style>';
}
