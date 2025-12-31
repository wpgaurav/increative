<?php

/**
 * Theme Hooks
 * 
 * Provides action hooks throughout the theme for extensibility.
 * Developers can use these hooks to add custom functionality.
 */

namespace App;

/**
 * ============================================
 * HEADER HOOKS
 * ============================================
 */

/**
 * Fires before the header.
 * Use for: announcement bars, floating bars, etc.
 */
function do_before_header() {
    do_action('increative_before_header');
}

/**
 * Fires after the header opens.
 * Use for: skip links, notices, etc.
 */
function do_header_start() {
    do_action('increative_header_start');
}

/**
 * Fires before navigation.
 */
function do_before_nav() {
    do_action('increative_before_nav');
}

/**
 * Fires after navigation.
 */
function do_after_nav() {
    do_action('increative_after_nav');
}

/**
 * Fires before header closes.
 */
function do_header_end() {
    do_action('increative_header_end');
}

/**
 * Fires after the header.
 */
function do_after_header() {
    do_action('increative_after_header');
}

/**
 * ============================================
 * CONTENT HOOKS
 * ============================================
 */

/**
 * Fires before main content.
 */
function do_before_content() {
    do_action('increative_before_content');
}

/**
 * Fires at the start of main content.
 */
function do_content_start() {
    do_action('increative_content_start');
}

/**
 * Fires before single post content.
 */
function do_before_post() {
    do_action('increative_before_post');
}

/**
 * Fires at the start of post header.
 */
function do_post_header_start() {
    do_action('increative_post_header_start');
}

/**
 * Fires at the end of post header.
 */
function do_post_header_end() {
    do_action('increative_post_header_end');
}

/**
 * Fires before post content.
 */
function do_before_post_content() {
    do_action('increative_before_post_content');
}

/**
 * Fires after post content.
 */
function do_after_post_content() {
    do_action('increative_after_post_content');
}

/**
 * Fires after the post.
 */
function do_after_post() {
    do_action('increative_after_post');
}

/**
 * Fires at the end of main content.
 */
function do_content_end() {
    do_action('increative_content_end');
}

/**
 * Fires after main content.
 */
function do_after_content() {
    do_action('increative_after_content');
}

/**
 * ============================================
 * SIDEBAR HOOKS
 * ============================================
 */

/**
 * Fires before sidebar.
 */
function do_before_sidebar() {
    do_action('increative_before_sidebar');
}

/**
 * Fires after sidebar.
 */
function do_after_sidebar() {
    do_action('increative_after_sidebar');
}

/**
 * ============================================
 * FOOTER HOOKS
 * ============================================
 */

/**
 * Fires before the footer.
 */
function do_before_footer() {
    do_action('increative_before_footer');
}

/**
 * Fires at the start of footer.
 */
function do_footer_start() {
    do_action('increative_footer_start');
}

/**
 * Fires at the end of footer.
 */
function do_footer_end() {
    do_action('increative_footer_end');
}

/**
 * Fires after the footer.
 * Use for: popups, modals, floating elements, etc.
 */
function do_after_footer() {
    do_action('increative_after_footer');
}

/**
 * ============================================
 * ARCHIVE HOOKS
 * ============================================
 */

/**
 * Fires before archive loop.
 */
function do_before_loop() {
    do_action('increative_before_loop');
}

/**
 * Fires after archive loop.
 */
function do_after_loop() {
    do_action('increative_after_loop');
}

/**
 * ============================================
 * UTILITY HOOKS
 * ============================================
 */

/**
 * Fires in head for custom scripts/styles.
 */
function do_head() {
    do_action('increative_head');
}

/**
 * Fires at end of body for scripts.
 */
function do_body_end() {
    do_action('increative_body_end');
}
