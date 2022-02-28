<?php

add_action('admin_menu', 'mfsp_add_admin_menu_settings_page');
add_action('admin_init', 'mfsp_settings_init');

function mfsp_add_admin_menu_settings_page()
{
    add_options_page(
        'Settings API Page',
        'MFSP Settings',
        'manage_options',
        'mfsp-settings-page',
        'mfsp_admin_menu_settings_page',
        0
    );
}

function mfsp_admin_menu_settings_page()
{
?>
    <div style="margin-top: 2rem;">
        <form action='options.php' method='post'>

            <h1>MFSP Settings</h1>

            <?php
            settings_fields('mfspGroup');
            do_settings_sections('mfspGroup');
            submit_button();
            ?>

        </form>
    </div>
<?php
}

function mfsp_settings__section__callback()
{
    echo '<p>Introduction.</p>';
}

function mfsp_settings_init()
{
    // SMPT Host Setting
    register_setting(
        'mfspGroup', // Group Name
        'mfsp_setting__smtp_host' // Key name in options db
    );
    register_setting(
        'mfspGroup', // Group Name
        'mfsp_setting__smtp_port' // Key name in options db
    );
    register_setting(
        'mfspGroup', // Group Name
        'mfsp_setting__smtp_user' // Key name in options db
    );
    register_setting(
        'mfspGroup', // Group Name
        'mfsp_setting__smtp_password' // Key name in options db
    );

    // SECTIONS
    add_settings_section(
        'mfsp_settings__section',
        'SMTP Server Configuration',
        'mfsp_settings__section__callback',
        'mfspGroup' // Group Name
    );

    // FIELDS
    add_settings_field(
        'mfsp_setting__smtp_host__field', // slug
        'SMTP Host', // Label
        'mfsp_setting__smtp_host__field__callback', // 
        'mfspGroup', // Group Name
        'mfsp_settings__section' // Section name
    );

    add_settings_field(
        'mfsp_setting__smtp_port__field', // slug
        'SMTP Port', // Label
        'mfsp_setting__smtp_port__field__callback', // 
        'mfspGroup', // Group Name
        'mfsp_settings__section' // Section name
    );

    add_settings_field(
        'mfsp_setting__smtp_user__field', // slug
        'SMTP User', // Label
        'mfsp_setting__smtp_user__field__callback', // 
        'mfspGroup', // Group Name
        'mfsp_settings__section' // Section name
    );

    add_settings_field(
        'mfsp_setting__smtp_password__field', // slug
        'SMTP Password', // Label
        'mfsp_setting__smtp_password__field__callback', // 
        'mfspGroup', // Group Name
        'mfsp_settings__section' // Section name
    );
}

function mfsp_setting__smtp_host__field__callback()
{
    $setting = get_option('mfsp_setting__smtp_host');
?>
    <input type="text" name="mfsp_setting__smtp_host" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}

function mfsp_setting__smtp_port__field__callback()
{
    $setting = get_option('mfsp_setting__smtp_port');
?>
    <input type="number" name="mfsp_setting__smtp_port" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}

function mfsp_setting__smtp_user__field__callback()
{
    $setting = get_option('mfsp_setting__smtp_user');
?>
    <input type="text" name="mfsp_setting__smtp_user" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}

function mfsp_setting__smtp_password__field__callback()
{
    $setting = get_option('mfsp_setting__smtp_password');
?>
    <input type="password" name="mfsp_setting__smtp_password" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
<?php
}
