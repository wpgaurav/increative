<?php

/**
 * Theme filters.
 */

namespace App;

/**
 * Add "… Continued" to the excerpt.
 *
 * @return string
 */
add_filter('excerpt_more', function () {
    return '&hellip;';
});

/**
 * Calculate reading time for posts
 *
 * @param int|null $post_id
 * @return int
 */
function reading_time($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = max(1, ceil($word_count / 200)); // 200 words per minute
    
    return $reading_time;
}

/**
 * Add custom body classes
 */
add_filter('body_class', function ($classes) {
    // Add dark mode class if set
    $classes[] = 'antialiased';
    
    // Add page-specific classes
    if (is_front_page()) {
        $classes[] = 'is-front-page';
    }
    
    if (is_singular('post')) {
        $classes[] = 'is-single-post';
    }
    
    return $classes;
});

/**
 * Customize navigation menu item classes
 */
add_filter('nav_menu_link_attributes', function ($atts, $item, $args) {
    if (isset($args->link_class)) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}, 10, 3);

/**
 * Add custom image sizes
 */
add_action('after_setup_theme', function () {
    add_image_size('card-thumbnail', 640, 360, true);
    add_image_size('hero-image', 1920, 1080, false);
});

/**
 * Register custom block pattern categories
 */
add_action('init', function () {
    register_block_pattern_category('increative', [
        'label' => __('Increative', 'sage'),
    ]);
});
