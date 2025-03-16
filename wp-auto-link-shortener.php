<?php
/**
 * Plugin Name: WP Auto Link Shortener
 * Plugin URI: https://yourwebsite.com/
 * Description: Automates internal & external linking and shortens post URLs.
 * Version: 1.0
 * Author: Dharmendra Kumar
 * Author URI: https://yourwebsite.com/
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include necessary files
require_once plugin_dir_path(__FILE__) . 'includes/link-shortener.php';
require_once plugin_dir_path(__FILE__) . 'includes/internal-links.php';
require_once plugin_dir_path(__FILE__) . 'includes/external-links.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';

// Activation Hook
function wp_auto_link_shortener_activate() {
    add_option('wp_auto_link_shortener_enabled', '1');
}
register_activation_hook(__FILE__, 'wp_auto_link_shortener_activate');

// Deactivation Hook
function wp_auto_link_shortener_deactivate() {
    delete_option('wp_auto_link_shortener_enabled');
}
register_deactivation_hook(__FILE__, 'wp_auto_link_shortener_deactivate');

function wp_auto_enqueue_styles() {
    wp_enqueue_style('wp-auto-links-style', plugin_dir_url(__FILE__) . 'assets/styles.css');
}
add_action('wp_enqueue_scripts', 'wp_auto_enqueue_styles');

// Add a new column to display short links
function wp_auto_add_short_link_column($columns) {
    $columns['short_link'] = 'Short Link';
    return $columns;
}
add_filter('manage_posts_columns', 'wp_auto_add_short_link_column');

// Populate the new column with short links
function wp_auto_display_short_link_column($column_name, $post_id) {
    if ($column_name === 'short_link') {
        $short_url = wp_auto_get_short_link($post_id);
        echo '<a href="' . esc_url($short_url) . '" target="_blank">' . esc_html($short_url) . '</a>';
    }
}
add_action('manage_posts_custom_column', 'wp_auto_display_short_link_column', 10, 2);


