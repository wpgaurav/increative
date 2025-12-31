<?php

/**
 * Theme Customizer settings.
 */

namespace App;

/**
 * Register customizer settings.
 */
add_action('customize_register', function ($wp_customize) {
    // ============================================
    // Increative Theme Panel
    // ============================================
    $wp_customize->add_panel('increative_panel', [
        'title' => __('Increative Theme Options', 'sage'),
        'priority' => 30,
    ]);

    // ============================================
    // Typography Section
    // ============================================
    $wp_customize->add_section('increative_typography', [
        'title' => __('Typography', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 10,
    ]);

    // Custom Font Family
    $wp_customize->add_setting('increative_custom_font', [
        'default' => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport' => 'refresh',
    ]);

    $wp_customize->add_control('increative_custom_font', [
        'label' => __('Custom Font Family', 'sage'),
        'description' => __('Enter a Google Font name (e.g., "Inter", "Roboto", "Outfit"). Leave empty for system fonts.', 'sage'),
        'section' => 'increative_typography',
        'type' => 'text',
    ]);

    // Font Weight
    $wp_customize->add_setting('increative_font_weight', [
        'default' => '400;500;600;700',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('increative_font_weight', [
        'label' => __('Font Weights', 'sage'),
        'description' => __('Comma-separated font weights to load (e.g., 400;500;600;700)', 'sage'),
        'section' => 'increative_typography',
        'type' => 'text',
    ]);

    // ============================================
    // Social Links Section
    // ============================================
    $wp_customize->add_section('increative_social', [
        'title' => __('Social Links', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 20,
    ]);

    $social_links = [
        'twitter' => __('Twitter/X URL', 'sage'),
        'linkedin' => __('LinkedIn URL', 'sage'),
        'github' => __('GitHub URL', 'sage'),
        'youtube' => __('YouTube URL', 'sage'),
        'instagram' => __('Instagram URL', 'sage'),
        'facebook' => __('Facebook URL', 'sage'),
    ];

    foreach ($social_links as $key => $label) {
        $wp_customize->add_setting("increative_{$key}", [
            'default' => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control("increative_{$key}", [
            'label' => $label,
            'section' => 'increative_social',
            'type' => 'url',
        ]);
    }

    // ============================================
    // Header Section
    // ============================================
    $wp_customize->add_section('increative_header', [
        'title' => __('Header', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 30,
    ]);

    // Sticky Header
    $wp_customize->add_setting('increative_sticky_header', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_sticky_header', [
        'label' => __('Enable Sticky Header', 'sage'),
        'section' => 'increative_header',
        'type' => 'checkbox',
    ]);

    // Dark Mode Toggle
    $wp_customize->add_setting('increative_dark_mode_toggle', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_dark_mode_toggle', [
        'label' => __('Show Dark Mode Toggle', 'sage'),
        'section' => 'increative_header',
        'type' => 'checkbox',
    ]);

    // ============================================
    // Footer Section
    // ============================================
    $wp_customize->add_section('increative_footer', [
        'title' => __('Footer', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 40,
    ]);

    // Copyright Text
    $wp_customize->add_setting('increative_copyright', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);

    $wp_customize->add_control('increative_copyright', [
        'label' => __('Custom Copyright Text', 'sage'),
        'description' => __('Leave empty for default copyright.', 'sage'),
        'section' => 'increative_footer',
        'type' => 'textarea',
    ]);

    // Show Newsletter in Footer
    $wp_customize->add_setting('increative_footer_newsletter', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_footer_newsletter', [
        'label' => __('Show Newsletter Signup in Footer', 'sage'),
        'section' => 'increative_footer',
        'type' => 'checkbox',
    ]);

    // ============================================
    // Blog Section
    // ============================================
    $wp_customize->add_section('increative_blog', [
        'title' => __('Blog Settings', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 50,
    ]);

    // Posts Per Row
    $wp_customize->add_setting('increative_posts_per_row', [
        'default' => '3',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('increative_posts_per_row', [
        'label' => __('Posts Per Row', 'sage'),
        'section' => 'increative_blog',
        'type' => 'select',
        'choices' => [
            '2' => __('2 Columns', 'sage'),
            '3' => __('3 Columns', 'sage'),
            '4' => __('4 Columns', 'sage'),
        ],
    ]);

    // Show Reading Time
    $wp_customize->add_setting('increative_reading_time', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_reading_time', [
        'label' => __('Show Reading Time', 'sage'),
        'section' => 'increative_blog',
        'type' => 'checkbox',
    ]);

    // Show Author Box
    $wp_customize->add_setting('increative_author_box', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_author_box', [
        'label' => __('Show Author Box on Posts', 'sage'),
        'section' => 'increative_blog',
        'type' => 'checkbox',
    ]);

    // Show Related Posts
    $wp_customize->add_setting('increative_related_posts', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_related_posts', [
        'label' => __('Show Related Posts', 'sage'),
        'section' => 'increative_blog',
        'type' => 'checkbox',
    ]);

    // ============================================
    // Single Post Section
    // ============================================
    $wp_customize->add_section('increative_single', [
        'title' => __('Single Post', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 55,
    ]);

    // Show Table of Contents
    $wp_customize->add_setting('increative_show_toc', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_show_toc', [
        'label' => __('Show Table of Contents', 'sage'),
        'description' => __('Auto-generated from post headings', 'sage'),
        'section' => 'increative_single',
        'type' => 'checkbox',
    ]);

    // TOC Minimum Headings
    $wp_customize->add_setting('increative_toc_min_headings', [
        'default' => '3',
        'sanitize_callback' => 'absint',
    ]);

    $wp_customize->add_control('increative_toc_min_headings', [
        'label' => __('Minimum Headings for TOC', 'sage'),
        'section' => 'increative_single',
        'type' => 'number',
        'input_attrs' => [
            'min' => 2,
            'max' => 10,
        ],
    ]);

    // Show Share Buttons
    $wp_customize->add_setting('increative_show_share', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_show_share', [
        'label' => __('Show Social Share Buttons', 'sage'),
        'section' => 'increative_single',
        'type' => 'checkbox',
    ]);

    // Show Reading Progress Bar
    $wp_customize->add_setting('increative_show_progress', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_show_progress', [
        'label' => __('Show Reading Progress Bar', 'sage'),
        'section' => 'increative_single',
        'type' => 'checkbox',
    ]);

    // Show Breadcrumbs
    $wp_customize->add_setting('increative_show_breadcrumbs', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_show_breadcrumbs', [
        'label' => __('Show Breadcrumbs', 'sage'),
        'section' => 'increative_single',
        'type' => 'checkbox',
    ]);

    // ============================================
    // Performance Section
    // ============================================
    $wp_customize->add_section('increative_performance', [
        'title' => __('Performance', 'sage'),
        'panel' => 'increative_panel',
        'priority' => 100,
    ]);

    // Lazy Load Images
    $wp_customize->add_setting('increative_lazy_load', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_lazy_load', [
        'label' => __('Enable Lazy Loading', 'sage'),
        'description' => __('Defer loading of images until they enter the viewport', 'sage'),
        'section' => 'increative_performance',
        'type' => 'checkbox',
    ]);

    // Disable Emoji Scripts
    $wp_customize->add_setting('increative_disable_emoji', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_disable_emoji', [
        'label' => __('Remove Emoji Scripts', 'sage'),
        'description' => __('Removes WordPress emoji scripts for faster loading', 'sage'),
        'section' => 'increative_performance',
        'type' => 'checkbox',
    ]);

    // Inline Critical CSS
    $wp_customize->add_setting('increative_inline_critical', [
        'default' => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ]);

    $wp_customize->add_control('increative_inline_critical', [
        'label' => __('Inline Critical CSS', 'sage'),
        'description' => __('Includes above-the-fold CSS in the page for faster rendering', 'sage'),
        'section' => 'increative_performance',
        'type' => 'checkbox',
    ]);
});

/**
 * Enqueue Google Fonts if custom font is set.
 */
add_action('wp_enqueue_scripts', function () {
    $custom_font = get_theme_mod('increative_custom_font', '');

    if (!empty($custom_font)) {
        $font_weights = get_theme_mod('increative_font_weight', '400;500;600;700');
        $font_name = str_replace(' ', '+', $custom_font);
        $font_url = "https://fonts.googleapis.com/css2?family={$font_name}:wght@{$font_weights}&display=swap";

        wp_enqueue_style('increative-google-font', $font_url, [], null);
    }
});

/**
 * Add custom font CSS variable.
 */
add_action('wp_head', function () {
    $custom_font = get_theme_mod('increative_custom_font', '');

    if (!empty($custom_font)) {
        echo "<style>:root { --font-sans: '{$custom_font}', ui-sans-serif, system-ui, sans-serif; }</style>\n";
    }
}, 100);
