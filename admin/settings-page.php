<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ---------------------------------------------------------
 * SANITIZERS
 * --------------------------------------------------------- */
function whm_sanitize_checkbox( $value ) {
    return $value ? 1 : 0;
}

function whm_sanitize_mode( $value ) {
    $allowed = array( 'maintenance', 'front_page' );
    return in_array( $value, $allowed, true ) ? $value : 'maintenance';
}

function whm_sanitize_source( $value ) {
    $allowed = array( 'template', 'page' );
    return in_array( $value, $allowed, true ) ? $value : 'template';
}

function whm_sanitize_page_id( $value ) {
    return absint( $value );
}

/* ---------------------------------------------------------
 * REGISTER SETTINGS
 * --------------------------------------------------------- */
add_action( 'admin_init', function () {

    register_setting(
        'whm_settings',
        WHM_OPT_ENABLED,
        array( 'sanitize_callback' => 'whm_sanitize_checkbox' )
    );

    register_setting(
        'whm_settings',
        WHM_OPT_MODE,
        array( 'sanitize_callback' => 'whm_sanitize_mode' )
    );

    register_setting(
        'whm_settings',
        WHM_OPT_SOURCE,
        array( 'sanitize_callback' => 'whm_sanitize_source' )
    );

    register_setting(
        'whm_settings',
        WHM_OPT_PAGE_ID,
        array( 'sanitize_callback' => 'whm_sanitize_page_id' )
    );

    register_setting(
        'whm_settings',
        WHM_OPT_SHOW_DEVMSG,
        array( 'sanitize_callback' => 'whm_sanitize_checkbox' )
    );
});

/* ---------------------------------------------------------
 * ADMIN MENU
 * --------------------------------------------------------- */
add_action( 'admin_menu', function () {

    add_options_page(
        'Webhole Homepage Manager',
        'Homepage Manager',
        'manage_options',
        'webhole-homepage-manager',
        'whm_settings_page'
    );
});

/* ---------------------------------------------------------
 * SETTINGS PAGE UI
 * --------------------------------------------------------- */
function whm_settings_page() {

    $preview_url = add_query_arg(
        array(
            'whm_preview' => '1',
            '_whm_nonce'  => wp_create_nonce( 'whm_preview' ),
        ),
        home_url( '/' )
    );
    ?>
    <div class="wrap">
        <h1>Webhole Homepage Manager</h1>

        <p>
            <a href="<?php echo esc_url( $preview_url ); ?>"
               target="_blank"
               class="button button-secondary">
                Preview Frontend
            </a>
        </p>

        <form method="post" action="options.php">
            <?php settings_fields( 'whm_settings' ); ?>

            <table class="form-table">

                <tr>
                    <th scope="row">Enable Plugin</th>
                    <td>
                        <label>
                            <input type="checkbox"
                                   name="<?php echo esc_attr( WHM_OPT_ENABLED ); ?>"
                                   value="1"
                                   <?php checked( 1, get_option( WHM_OPT_ENABLED, 0 ) ); ?>>
                            Activate Webhole Homepage Manager
                        </label>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Mode</th>
                    <td>
                        <select name="<?php echo esc_attr( WHM_OPT_MODE ); ?>">
                            <option value="maintenance"
                                <?php selected( get_option( WHM_OPT_MODE, 'maintenance' ), 'maintenance' ); ?>>
                                Maintenance Mode (entire site)
                            </option>
                            <option value="front_page"
                                <?php selected( get_option( WHM_OPT_MODE, 'front_page' ), 'front_page' ); ?>>
                                Custom Front Page Only
                            </option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Front Page Source</th>
                    <td>
                        <select name="<?php echo esc_attr( WHM_OPT_SOURCE ); ?>">
                            <option value="template"
                                <?php selected( get_option( WHM_OPT_SOURCE, 'template' ), 'template' ); ?>>
                                Built-in Maintenance Template
                            </option>
                            <option value="page"
                                <?php selected( get_option( WHM_OPT_SOURCE, 'page' ), 'page' ); ?>>
                                Existing WordPress Page
                            </option>
                        </select>
                        <p class="description">
                            Choose whether to display the built-in maintenance layout
                            or an existing page.
                        </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Select Existing Page</th>
                    <td>
                        <?php
                        wp_dropdown_pages(
                            array(
                                'name'              => WHM_OPT_PAGE_ID,
                                'show_option_none'  => '— Select a page —',
                                'option_none_value' => 0,
                                'selected'          => (int) get_option( WHM_OPT_PAGE_ID, 0 ),
                            )
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Developer Messages</th>
                    <td>
                        <label>
                            <input type="checkbox"
                                   name="<?php echo esc_attr( WHM_OPT_SHOW_DEVMSG ); ?>"
                                   value="1"
                                   <?php checked( 1, get_option( WHM_OPT_SHOW_DEVMSG, 1 ) ); ?>>
                            Show messages from the developer
                        </label>
                    </td>
                </tr>

            </table>

            <?php submit_button(); ?>
        </form>

        <?php
        /* -------------------------------------------------
         * DEVELOPER MESSAGE (REMOTE, TOGGLEABLE)
         * ------------------------------------------------- */
        if ( (int) get_option( WHM_OPT_SHOW_DEVMSG, 1 ) ) :
            $message = whm_get_developer_message();
            if ( $message ) :
        ?>
            <div style="
                margin-top:30px;
                padding:15px 20px;
                background:#0f172a;
                color:#e5e7eb;
                border-left:4px solid #7c3aed;
                border-radius:4px;
            ">
                <h2 style="margin-top:0;color:#c4b5fd;">
                    Message from the Developer
                </h2>
                <div style="white-space:pre-line;">
                    <?php echo wp_kses_post( $message ); ?>
                </div>
            </div>
        <?php
            endif;
        endif;
        ?>
    </div>
    <?php
}
