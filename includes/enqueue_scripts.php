<?php



function mfsp_enqueue_scripts()
{
    global $wp;
    $current_slug = add_query_arg(array(), $wp->request);
    $appear_in_mfsp_admin = isset($_GET['page']) and $_GET["page"] == "mfsp_admin_page.php";
    $appear_in_mfsp_client = $current_slug == "mfsp-share";

    if ($appear_in_mfsp_admin or $appear_in_mfsp_client) {
        // // Axios
        // wp_enqueue_script(
        //     'mfsp-axios',
        //     plugins_url('../public/js/axios.min.js', __FILE__),
        //     [],
        //     time(), //version
        //     false
        // );
        // 
        wp_enqueue_script(
            'mfsp-bundle-js',
            plugins_url('../public/bundle/mfsp-bundle.js', __FILE__),
            [],
            time(), //version
            true
        );
        wp_localize_script("mfsp-bundle-js", "MfspApiConfig", [
            "api_base_url" => esc_url_raw(rest_url()),
            "nonce" => wp_create_nonce('wp_rest')
        ]);
    }

    // // Api client
    // wp_enqueue_script(
    //     'mfsp-api-client',
    //     plugins_url('../public/js/api.js', __FILE__),
    //     [],
    //     time(), //version
    //     true
    // );
    // wp_localize_script("mfsp-api-client", "MfspApiConfig", [
    //     "api_base_url" => esc_url_raw(rest_url()),
    //     "nonce" => wp_create_nonce('wp_rest')
    // ]);
    // // Test Script
    // wp_enqueue_script(
    //     'mfsp-main-js',
    //     plugins_url('../public/js/script.js', __FILE__),
    //     [],
    //     time(), //version
    //     true
    // );
    // wp_localize_script("mfsp-main-js", "WpPluginConfig", [
    //     "message" => "To nie jest fajne " . $current_slug
    // ]);
}

add_action("wp_enqueue_scripts", 'mfsp_enqueue_scripts');
add_action("admin_enqueue_scripts", 'mfsp_enqueue_scripts');


function mfsp_enqueue_styles()
{

    global $wp;
    $current_slug = add_query_arg(array(), $wp->request);
    $appear_in_mfsp_admin = isset($_GET['page']) and $_GET["page"] == "mfsp_admin_page.php";
    $appear_in_mfsp_client = $current_slug == "mfsp-share";

    if ($appear_in_mfsp_admin || $appear_in_mfsp_client) {
        // Register the CSS like this for a plugin:
        wp_enqueue_style(
            'mfsp-bundle-css',
            plugins_url('../public/css/jstree/default/jstree.style.css', __FILE__),
            [],
            time(),
            'all'
        );
        wp_enqueue_style(
            'fontawesome-css',
            plugins_url('../public/css/fontawesome.min.css', __FILE__),
            [],
            time(),
            'all'
        );
        // wp_enqueue_style(
        //     'light-grid',
        //     plugins_url('../public/css/light-grid.css', __FILE__),
        //     [],
        //     time(),
        //     'all'
        // );
        wp_enqueue_style(
            'custom_css',
            plugins_url('../public/css/custom.css', __FILE__),
            [],
            time(),
            'all'
        );
        wp_enqueue_style(
            'sweetalert2',
            plugins_url('../public/css/sweetalert2.css', __FILE__),
            [],
            time(),
            'all'
        );
        wp_enqueue_style(
            'mfsp_css_bundle',
            plugins_url('../public/bundle/mfsp-bundle.css', __FILE__),
            [],
            time(),
            'all'
        );
    }
}

add_action('wp_enqueue_scripts', 'mfsp_enqueue_styles');
add_action("admin_enqueue_scripts", 'mfsp_enqueue_styles');
