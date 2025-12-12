<?php
/**
 * Simple WordPress Function Mocks
 *
 * @package Soma\Tests
 */

namespace {
    // WordPress core functions
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }

    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1) {
        return true;
    }

    function remove_action($hook, $callback, $priority = 10) {
        return true;
    }

    function remove_filter($hook, $callback, $priority = 10) {
        return true;
    }

    function do_action($hook, ...$args) {
        return null;
    }

    function apply_filters($hook, $value, ...$args) {
        return $value;
    }

    function __($text, $domain = 'default') {
        return $text;
    }

    function esc_html($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    function esc_attr($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    function wp_enqueue_script(...$args) {
        return true;
    }

    function wp_enqueue_style(...$args) {
        return true;
    }

    function register_post_type(...$args) {
        return true;
    }

    function register_nav_menus(...$args) {
        return true;
    }

    function get_template_directory() {
        return SOMA_THEME_DIR;
    }

    function get_template_directory_uri() {
        return 'https://example.org/wp-content/themes/soma';
    }
}
