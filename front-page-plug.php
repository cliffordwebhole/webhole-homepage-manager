<?php
/**
 * Plugin Name: Front Page Plug
 * Description: Adds a custom front page or enables maintenance mode without modifying the active theme.
 * Version: 0.1.0
 * Author: Clifford Webhole
 * Author URI: https://cliffordswebhole.com
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Constants
 */
define('FPP_PATH', plugin_dir_path(__FILE__));
define('FPP_URL', plugin_dir_url(__FILE__));

/**
 * Set default options on activation
 */
function fpp_activate_plugin() {
    add_option('fpp_enabled', 1);
    add_option('fpp_mode', 'front_page');
    add_option('fpp_front_source', 'template');
    add_option('fpp_front_page_id', '');
}
register_activation_hook(__FILE__, 'fpp_activate_plugin');

/**
 * Admin notice
 */
function fpp_admin_notice() {
    if (!get_option('fpp_enabled')) {
        return;
    }
    ?>
    <div class="notice notice-success is-dismissible">
        <p>
            <strong>Front Page Plug active.</strong>
            Mode: <?php echo esc_html(get_option('fpp_mode')); ?>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'fpp_admin_notice');
/**
 * Admin Bar Status Badge
 */
function fpp_admin_bar_status($wp_admin_bar) {

    if (!current_user_can('manage_options')) {
        return;
    }

    $enabled = get_option('fpp_enabled');
    $mode    = get_option('fpp_mode', 'maintenance');

    // Detect preview mode
    $is_preview = (
        isset($_GET['fpp_preview'], $_GET['_fpp_nonce']) &&
        wp_verify_nonce($_GET['_fpp_nonce'], 'fpp_preview')
    );

    if ($is_preview) {
        $status = 'Preview';
        $color  = '#7c3aed'; // purple
    } elseif (!$enabled) {
        $status = 'Disabled';
        $color  = '#6b7280'; // gray
    } elseif ($mode === 'maintenance') {
        $status = 'Maintenance';
        $color  = '#dc2626'; // red
    } else {
        $status = 'Front Page';
        $color  = '#16a34a'; // green
    }

    $wp_admin_bar->add_node([
        'id'    => 'fpp-status',
        'title' => '<span style="color:' . esc_attr($color) . '; font-weight:600;">
                        Front Page Plug: ' . esc_html($status) . '
                    </span>',
        'href'  => admin_url('options-general.php?page=front-page-plug'),
        'meta'  => ['title' => 'Front Page Plug Status'],
    ]);

    // Preview link
    $preview_url = add_query_arg(
        [
            'fpp_preview' => '1',
            '_fpp_nonce'  => wp_create_nonce('fpp_preview'),
        ],
        home_url('/')
    );

    $wp_admin_bar->add_node([
        'id'     => 'fpp-preview',
        'parent' => 'fpp-status',
        'title'  => 'Preview Front Page',
        'href'   => esc_url($preview_url),
        'meta'   => ['target' => '_blank'],
    ]);

    // Settings link
    $wp_admin_bar->add_node([
        'id'     => 'fpp-settings',
        'parent' => 'fpp-status',
        'title'  => 'Settings',
        'href'   => admin_url('options-general.php?page=front-page-plug'),
    ]);
}
add_action('admin_bar_menu', 'fpp_admin_bar_status', 100);
/**
 * Preview banner (admin preview only)
 */
function fpp_preview_banner() {
    ?>
    <style>
        body {
            margin-top: 48px !important;
        }
    </style>

    <div style="
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        height: 48px;
        background: #5b21b6;
        color: #fff;
        text-align: center;
        padding: 8px 12px;
        font-size: 13px;
        font-weight: 600;
        z-index: 99999;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    ">
        <span>Front Page Plug — Preview Mode (Admins Only)</span>
        <a href="<?php echo esc_url(home_url('/')); ?>"
           style="color:#fff;text-decoration:underline;">
            Exit Preview
        </a>
    </div>
    <?php
}
/**
 * Load admin UI
 */
if (is_admin()) {
    require_once FPP_PATH . 'admin/settings-page.php';
}
/**
 * Fetch developer message (cached)
 */
function fpp_get_developer_message() {

    // Check cache first (12 hours)
    $cached = get_transient('fpp_dev_message');
    if ($cached !== false) {
        return $cached;
    }

    $response = wp_remote_get(
        'https://dev.cliffordswebhole.com/messages/fpp-announcements.txt',
        [
            'timeout' => 5,
            'user-agent' => 'Front Page Plug',
        ]
    );

    if (is_wp_error($response)) {
        return false;
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code !== 200) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    if (empty($body)) {
        return false;
    }

    // Sanitize & cache
    $body = wp_kses_post($body);
    set_transient('fpp_dev_message', $body, 12 * HOUR_IN_SECONDS);

    return $body;
}
/**
 * Front-end logic
 */
function fpp_handle_frontend() {

    /**
     * ADMIN PREVIEW MODE (nonce protected)
     * Runs BEFORE admin bypass
     */
    if (
        current_user_can('manage_options') &&
        isset($_GET['fpp_preview'], $_GET['_fpp_nonce']) &&
        wp_verify_nonce($_GET['_fpp_nonce'], 'fpp_preview')
    ) {
     //Output Preview Banner
        fpp_preview_banner();
   $source = get_option('fpp_front_source', 'template');

        if ($source === 'page') {
            $page_id = get_option('fpp_front_page_id');
            if ($page_id) {
                echo apply_filters(
                    'the_content',
                    get_post_field('post_content', $page_id)
                );
                exit;
            }
        }

        include FPP_PATH . 'templates/front-page.php';
        exit;
    }

    /**
     * Plugin disabled
     */
    if (!get_option('fpp_enabled')) {
        return;
    }

    /**
     * Admin bypass (except preview)
     */
    if (current_user_can('manage_options')) {
        return;
    }

    /**
     * Allow wp-login and admin access
     */
    if (is_admin() || strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false) {
        return;
    }

    $mode   = get_option('fpp_mode', 'maintenance');
    $source = get_option('fpp_front_source', 'template');

    /**
     * MODE 1 — Maintenance Mode
     * Show front page only, block everything else
     */
    if ($mode === 'maintenance') {

        if ($source === 'page') {
            $page_id = get_option('fpp_front_page_id');
            if ($page_id) {
                echo apply_filters(
                    'the_content',
                    get_post_field('post_content', $page_id)
                );
                exit;
            }
        }

        include FPP_PATH . 'templates/front-page.php';
        exit;
    }

    /**
     * MODE 2 — Custom Front Page Mode
     * Replace only the front page
     */
    if ($mode === 'front_page' && is_front_page()) {

        if ($source === 'page') {
            $page_id = get_option('fpp_front_page_id');
            if ($page_id) {
                echo apply_filters(
                    'the_content',
                    get_post_field('post_content', $page_id)
                );
                exit;
            }
        }

        include FPP_PATH . 'templates/front-page.php';
        exit;
    }
}
add_action('template_redirect', 'fpp_handle_frontend');
