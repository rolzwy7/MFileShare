<?php

$mfsp_admin_page = "mfsp_admin_page";

function mfsp_admin_page()
{
    include("admin_page__html.php");
}

function mfsp_admin_page__menu()
{
    global $mfsp_admin_page;
    add_menu_page(
        "MFSP Admin", # page title (header) #
        "MFSP Admin", # menu title
        "manage_mfsp",
        $mfsp_admin_page . ".php", # slug; ?page=XXX
        "mfsp_admin_page", # page function
        "dashicons-share", # icon class
        1
    );
}

add_action("admin_menu", "mfsp_admin_page__menu");
// add_action("user_admin_menu", "mfsp_admin_page__menu");



// wp_localize_script('wp-api', 'wpApiSettings', array(
//     'root' => esc_url_raw(rest_url()),
//     'nonce' => wp_create_nonce('wp_rest')
// ));
