<?php
/**
 * Plugin Name: Webhole Homepage Manager
 * Description: Displays a maintenance page or replaces the homepage without modifying the active theme.
 * Version: 0.1.2
 * Author: Clifford Webhole
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ---------------------------------------------------------
 * CONSTANTS
 * --------------------------------------------------------- */
define( 'WHM_VER',  '0.1.3' );
define( 'WHM_PATH', plugin_dir_path( __FILE__ ) );
define( 'WHM_URL',  plugin_dir_url( __FILE__ ) );
define( 'WHM_OPT_SHOW_DEVMSG', 'whm_show_dev_message' );
/* ---------------------------------------------------------
 * OPTION KEYS
 * --------------------------------------------------------- */
define( 'WHM_OPT_ENABLED', 'whm_enabled' );
define( 'WHM_OPT_MODE',    'whm_mode' );        // maintenance | front_page
define( 'WHM_OPT_SOURCE',  'whm_source' );      // template | page
define( 'WHM_OPT_PAGE_ID', 'whm_page_id' );

/* ---------------------------------------------------------
 * ACTIVATION DEFAULTS
 * --------------------------------------------------------- */
register_activation_hook( __FILE__, function () {
    add_option( WHM_OPT_ENABLED, 1 );
    add_option( WHM_OPT_MODE, 'maintenance' );
    add_option( WHM_OPT_SOURCE, 'template' );
    add_option( WHM_OPT_PAGE_ID, 0 );
});

/* ---------------------------------------------------------
 * PREVIEW DETECTION
 * --------------------------------------------------------- */
function whm_is_preview() {
    return (
        current_user_can( 'manage_options' ) &&
        isset( $_GET['whm_preview'], $_GET['_whm_nonce'] ) &&
        wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_GET['_whm_nonce'] ) ),
            'whm_preview'
        )
    );
}

function whm_preview_exit_url() {
    return remove_query_arg( [ 'whm_preview', '_whm_nonce' ], home_url( '/' ) );
}

/* ---------------------------------------------------------
 * ADMIN BAR
 * --------------------------------------------------------- */
add_action( 'admin_notices', function () {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( ! get_option( WHM_OPT_ENABLED ) ) {
        return;
    }

    $mode   = get_option( WHM_OPT_MODE, 'maintenance' );
    $source = get_option( WHM_OPT_SOURCE, 'template' );

    $mode_label   = ( $mode === 'maintenance' ) ? 'Maintenance Mode' : 'Front Page Mode';
    $source_label = ( $source === 'template' ) ? 'Built-in Template' : 'Existing Page';

    ?>
    <div class="notice notice-info">
        <p>
            <strong>Webhole Homepage Manager is active.</strong>
            Mode: <strong><?php echo esc_html( $mode_label ); ?></strong> —
            Source: <strong><?php echo esc_html( $source_label ); ?></strong>
        </p>
    </div>
    <?php
});
add_action( 'admin_bar_menu', function ( $bar ) {

    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $settings = admin_url( 'options-general.php?page=webhole-homepage-manager' );
    $preview  = add_query_arg(
        [
            'whm_preview' => '1',
            '_whm_nonce'  => wp_create_nonce( 'whm_preview' ),
        ],
        home_url( '/' )
    );

    $bar->add_node([
        'id'    => 'whm-root',
        'title' => 'Homepage Manager',
        'href'  => $settings,
    ]);

    $bar->add_node([
        'id'     => 'whm-settings',
        'parent' => 'whm-root',
        'title'  => 'Settings',
        'href'   => $settings,
    ]);

    $bar->add_node([
        'id'     => 'whm-preview',
        'parent' => 'whm-root',
        'title'  => 'Preview',
        'href'   => $preview,
        'meta'   => [ 'target' => '_blank' ],
    ]);

    if ( whm_is_preview() ) {
        $bar->add_node([
            'id'     => 'whm-exit',
            'parent' => 'whm-root',
            'title'  => 'Exit Preview',
            'href'   => whm_preview_exit_url(),
        ]);
    }
}, 100 );

/* ---------------------------------------------------------
 * EXISTING PAGE MODE
 * --------------------------------------------------------- */
add_action( 'pre_get_posts', function ( $query ) {

    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( ! get_option( WHM_OPT_ENABLED ) ) {
        return;
    }

    if ( current_user_can( 'manage_options' ) && ! whm_is_preview() ) {
        return;
    }

    if ( get_option( WHM_OPT_SOURCE ) !== 'page' ) {
        return;
    }

    $page_id = absint( get_option( WHM_OPT_PAGE_ID ) );
    if ( ! $page_id ) {
        return;
    }

    $mode = get_option( WHM_OPT_MODE, 'maintenance' );

    if ( $mode === 'maintenance' || ( $mode === 'front_page' && ( is_home() || is_front_page() || whm_is_preview() ) ) ) {
        $query->set( 'page_id', $page_id );
        $query->set( 'post_type', 'page' );
        $query->is_page = true;
        $query->is_home = false;
        $query->is_front_page = true;
        $query->is_404 = false;
    }
}, 1 );

/* ---------------------------------------------------------
 * TEMPLATE MODE OUTPUT (NO THEME)
 * --------------------------------------------------------- */
add_action( 'template_redirect', function () {

    if ( ! get_option( WHM_OPT_ENABLED ) ) {
        return;
    }

    if ( get_option( WHM_OPT_SOURCE ) !== 'template' ) {
        return;
    }

    if ( current_user_can( 'manage_options' ) && ! whm_is_preview() ) {
        return;
    }

    if (
        is_admin() ||
        defined( 'DOING_AJAX' ) ||
        defined( 'REST_REQUEST' ) ||
        strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false
    ) {
        return;
    }

    $mode = get_option( WHM_OPT_MODE, 'maintenance' );

    if ( $mode === 'front_page' && ! is_front_page() && ! whm_is_preview() ) {
        return;
    }

    status_header( $mode === 'maintenance' ? 503 : 200 );
    nocache_headers();

    $css = WHM_URL . 'assets/webhole-homepage-manager.css';

    echo '<!DOCTYPE html><html><head>';
    echo '<meta charset="' . esc_attr( get_bloginfo( 'charset' ) ) . '">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . esc_html( get_bloginfo( 'name' ) ) . '</title>';
    echo '<link rel="stylesheet" href="' . esc_url( $css ) . '">';
    echo '</head><body>';

    if ( whm_is_preview() ) {
        echo '<div class="whm-preview-banner">Preview Mode <a href="' . esc_url( whm_preview_exit_url() ) . '">Exit</a></div>';
    }

    echo '<main class="whm-container">';
    echo '<h1>' . esc_html( get_bloginfo( "name" ) ) . '</h1>';
    echo '<p><strong>Maintenance Mode Active</strong></p>';
    echo '<p>This site is currently undergoing maintenance and will be back shortly.</p>';
    echo '<footer class="whm-footer">&copy; ' . esc_html( date( "Y" ) ) . ' ' . esc_html( get_bloginfo( "name" ) ) . '</footer>';
    echo '</main>';

    echo '</body></html>';
    exit;
}, 0 );
/**
 * Preview banner for EXISTING PAGE mode
 * Injected safely into theme output
 */
add_action( 'wp_body_open', function () {

    if ( ! whm_is_preview() ) {
        return;
    }

    // Only for existing page mode
    if ( get_option( WHM_OPT_SOURCE ) !== 'page' ) {
        return;
    }

    ?>
    <div class="whm-preview-banner">
        Preview Mode —
        <a href="<?php echo esc_url( whm_preview_exit_url() ); ?>">
            Exit
        </a>
    </div>
    <?php
});
/**
 * Fetch developer message (remote, cached)
 * Informational only – no ads, no tracking
 */
function whm_get_developer_message() {

    // Cache for 12 hours
    $cached = get_transient( 'whm_dev_message' );
    if ( false !== $cached ) {
        return $cached;
    }

    $url = 'https://dev.cliffordswebhole.com/messages/whm-announcements.txt';

    $response = wp_remote_get(
        $url,
        array(
            'timeout'    => 5,
            'user-agent' => 'Webhole Homepage Manager/' . WHM_VER,
        )
    );

    if ( is_wp_error( $response ) ) {
        return false;
    }

    if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
        return false;
    }

    $body = trim( (string) wp_remote_retrieve_body( $response ) );
    if ( '' === $body ) {
        return false;
    }

    // Allow safe formatting only
    $body = wp_kses_post( $body );

    set_transient( 'whm_dev_message', $body, 12 * HOUR_IN_SECONDS );

    return $body;
}
/* ---------------------------------------------------------
 * LOAD ADMIN UI
 * --------------------------------------------------------- */
if ( is_admin() ) {
    require_once WHM_PATH . 'admin/settings-page.php';
}
