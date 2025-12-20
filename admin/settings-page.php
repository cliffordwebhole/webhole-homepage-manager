<?php
if (!defined('ABSPATH')) exit;

/**
 * Register settings
 */
function fpp_register_settings() {
    register_setting('fpp_settings', 'fpp_enabled');
    register_setting('fpp_settings', 'fpp_mode');
    register_setting('fpp_settings', 'fpp_front_source');
    register_setting('fpp_settings', 'fpp_front_page_id');
    register_setting('fpp_settings', 'fpp_show_dev_message');
}
add_action('admin_init', 'fpp_register_settings');

/**
 * Add menu item
 */
function fpp_add_menu() {
    add_options_page(
        'Front Page Plug',
        'Front Page Plug',
        'manage_options',
        'front-page-plug',
        'fpp_settings_page'
    );
}
add_action('admin_menu', 'fpp_add_menu');

/**
 * Settings page markup
 */
function fpp_settings_page() {
?>
<div class="wrap">
    <h1>Front Page Plug</h1>
<?php
$preview_url = add_query_arg(
    [
        'fpp_preview' => '1',
        '_fpp_nonce'  => wp_create_nonce('fpp_preview'),
    ],
    home_url('/')
);
?>

<p>
    <a href="<?php echo esc_url($preview_url); ?>"
       target="_blank"
       class="button button-secondary">
        Preview Front Page
    </a>
</p>
    <form method="post" action="options.php">
        <?php settings_fields('fpp_settings'); ?>

        <table class="form-table">

            <tr>
                <th scope="row">Enable Plugin</th>
                <td>
                    <label>
                        <input type="checkbox" name="fpp_enabled" value="1"
                            <?php checked(1, get_option('fpp_enabled')); ?>>
                        Activate Front Page Plug
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row">Mode</th>
                <td>
                    <select name="fpp_mode">
                        <option value="maintenance" <?php selected(get_option('fpp_mode'), 'maintenance'); ?>>
                            Maintenance Mode
                        </option>
                        <option value="front_page" <?php selected(get_option('fpp_mode'), 'front_page'); ?>>
                            Custom Front Page Mode
                        </option>
                    </select>
                    <p class="description">
                        <strong>Maintenance:</strong> Only the front page is visible to visitors.<br>
                        <strong>Custom Front Page:</strong> Replace the homepage without blocking site access.
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">Front Page Source</th>
                <td>
                    <select name="fpp_front_source">
                        <option value="template" <?php selected(get_option('fpp_front_source'), 'template'); ?>>
                            Built-in Front Page Template
                        </option>
                        <option value="page" <?php selected(get_option('fpp_front_source'), 'page'); ?>>
                            Use Existing Page
                        </option>
                    </select>
                    <p class="description">
                        Choose whether to use the plugin’s built-in front page or an existing WordPress page.
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">Select Front Page</th>
                <td>
                    <?php
                    wp_dropdown_pages([
                        'name'              => 'fpp_front_page_id',
                        'show_option_none'  => '— Select a page —',
                        'option_none_value' => '',
                        'selected'          => get_option('fpp_front_page_id'),
                    ]);
                    ?>
                    <p class="description">
                        Only used when “Use Existing Page” is selected.
                    </p>
                </td>
            </tr>
<tr>
    <th scope="row">Developer Messages</th>
    <td>
        <label>
            <input type="checkbox" name="fpp_show_dev_message" value="1"
                <?php checked(1, get_option('fpp_show_dev_message', 1)); ?>>
            Show messages from the plugin developer
        </label>
        <p class="description">
            Displays occasional announcements, updates, and thank-you notes from the developer.
        </p>
    </td>
</tr>
        </table>

        <?php submit_button(); ?>
    </form>
<?php
if (get_option('fpp_show_dev_message', 1)) :

    $message = fpp_get_developer_message();

    if ($message) :
?>
    <div style="
        margin-top: 30px;
        padding: 15px 20px;
        background: #0f172a;
        color: #e5e7eb;
        border-left: 4px solid #7c3aed;
        border-radius: 4px;
    ">
        <h2 style="margin-top:0;color:#c4b5fd;">
            Message from the Developer
        </h2>
        <div style="white-space: pre-line;">
            <?php echo esc_html($message); ?>
        </div>
    </div>
<?php
    endif;
endif;
?>
</div>
<?php
}
