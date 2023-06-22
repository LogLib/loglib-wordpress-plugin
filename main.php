<?php
/*
Plugin Name: Loglib
Plugin URI: https://loglib.io/plugin
Description: a web analytics platform.
Version: 1.0.0
Author: Getasew
Author URI: https://www.linkedin.com/in/geta-walelign/
License: GPL2
*/

// Add settings page to WordPress dashboard
function loglib_add_settings_page() {
    add_options_page(
        'Loglib', // Page title
        'Loglib', // Menu title
        'manage_options', // Capability required to access the page
        'loglib-settings', // Menu slug
        'loglib_render_settings_page' // Callback function to render the page content
    );
}
add_action('admin_menu', 'loglib_add_settings_page');

// Render the settings page content
function loglib_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Loglib Settings</h1>
        <form method="post" action="<?php echo plugin_dir_url(__FILE__) . 'handle_form.php'; ?>">
            <?php
            // Output the settings fields
            settings_fields('loglib-settings-group');
            do_settings_sections('loglib-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function loglib_register_settings() {
    // Register a settings group
    register_setting(
        'loglib-settings-group', // Option group
        'loglib-settings', // Option name
        'loglib_validate_settings' // Validation callback (optional)
    );

    // Add a settings section
    add_settings_section(
        'loglib-settings-section', // Section ID
        'LogLib Settings', // Section title
        'loglib_render_section', // Callback function to render section content
        'loglib-settings' // Page slug
    );

    // Add a settings field
    add_settings_field(
        'loglib_host', // Field ID
        'LogLib Host', // Field label
        'loglib_render_field', // Callback function to render field content
        'loglib-settings', // Page slug
        'loglib-settings-section' // Section ID
    );

    add_settings_field(
        'loglib_id', // Field ID
        'LogLib id', // Field label
        'loglib_render_id_field', // Callback function to render field content
        'loglib-settings', // Page slug
        'loglib-settings-section' // Section ID
    );
}
add_action('admin_init', 'loglib_register_settings');

// Render the settings section content
function loglib_render_section() {
    echo 'Loglib setting section to enter your Loglib id and Loglib host.';
}

// Render the settings field content
function loglib_render_field() {
    $value = get_option('loglib-settings');
    echo '<input type="text" name="loglib_host" value="' . esc_attr($value['loglib_host']) . '" required/>';
}
// Render the settings field content
function loglib_render_id_field() {
    $value = get_option('loglib-settings');
    echo '<input type="text" name="loglib_id" value="' . esc_attr($value['loglib_id']) . '"/>';
}
// Validate the settings
function loglib_validate_settings($input) {
    return $input;
}


// Inject HTML tag into header
function loglib_inject_html_tag() {
    $jsonFilePath = plugin_dir_path(__FILE__) . 'data.json';
    $jsonData = file_get_contents($jsonFilePath);
    $data = json_decode($jsonData);

    $host=isset($data->host)?$data->host:"https://loglib.io";
    $id=isset($data->id)?$data->id:"";
    
    // https://loglib.io
    echo '<script>
    const r = window.document.createElement("script");
    r.type = "text/javascript";
    r.async = !0;
    r.src =
      "https://cdn.jsdelivr.net/npm/@loglib/tracker@latest/dist/index.global.js";
    const a = document.getElementsByTagName("script")[0];
    a.parentNode.insertBefore(r, a);
    r.onload = () => {
      loglib.record({ host:"'. $host .'",id:"'.$id.'"});
    };
  </script>';
}
add_action('wp_head', 'loglib_inject_html_tag');
