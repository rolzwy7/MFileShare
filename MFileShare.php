<?php

/**
 * @package MFileShare
 * @version 0.0.1
 */
/*
Plugin Name: MFileShare
Description: Moduł w środowisku Wordpress służący do udostępniania plików
Author: Bartosz Nowakowski
Version: 0.0.1
Author URI: https://rolzwy7.github.io
*/

// error_reporting(E_ALL ^ E_WARNING);

defined("ABSPATH") or die("No direct access!");

define('MFSP__PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MFSP__MINIMUM_WP_VERSION', '5.7');
define('MFSP__DATABASE_VERSION', '1.0');

define('MFSP__CRYPTO_FILE', MFSP__PLUGIN_DIR . "mfsp_crypto_secret.php");
define('MFSP__CRYPTO_CIPHER', "aes-256-cbc");

// 3rd party
// require_once(MFSP__PLUGIN_DIR . 'defuse-crypto.phar');

// Init crypto, Create mfsp_crypto_secret.php file
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.crypto-init.php');

// Load crypto secrets
require_once(MFSP__PLUGIN_DIR . 'mfsp_crypto_secret.php');

// Load crypto utils
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.crypto-utils.php');

// Load consts
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.consts.php');

// Load utils
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.utils.php');

// Load db utils
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.db-utils.php');

// Load email utils
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.email-utils.php');

// Load permissions utils
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.permission-utils.php');

// Load settings api
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.settings-api.php');

// 3rd party

require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';

// Init Plugin
register_activation_hook(__FILE__, array('Mfsp', 'plugin_activation'));
register_deactivation_hook(__FILE__, array('Mfsp', 'plugin_deactivation'));
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.php');
add_action('init', array('Mfsp', 'init'));

// Initialize REST Api
require_once(MFSP__PLUGIN_DIR . 'class.mfsp.rest-api.php');
add_action("rest_api_init", array("Mfsp_REST_API", "init"));

// Enqueue scripts & styles
require_once(MFSP__PLUGIN_DIR . 'includes/enqueue_scripts.php');

// Define pages
require_once(MFSP__PLUGIN_DIR . 'pages/admin_page.php');

function mfsp_parse_request__vpage_share(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if ($current_slug == "mfsp-share") {
        include 'pages/vpage_share.php';
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_share');

function mfsp_parse_request__vpage_clientgui(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if ($current_slug == "mfsp-share-explorer") {
        include 'pages/vpage_clientgui.php';
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_clientgui');

function mfsp_parse_request__vpage_code(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if (explode("/", $current_slug)[0] == "mfsp-code") {
        $url_uuid = explode("/", $current_slug)[1];
        if ($url_uuid != "") {
            wp_redirect("/wp-json/msfp/v1/share/$url_uuid");
        } else {
            wp_redirect("/404");
        }
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_code');

function mfsp_parse_request__vpage_passphrase(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if (explode("/", $current_slug)[0] == "mfsp-passphrase") {
        $url_uuid = explode("/", $current_slug)[1];
        if ($url_uuid != "") {

            include 'pages/vpage_form_passphrase.php';
            exit();
        } else {
            wp_redirect("/404");
        }
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_passphrase');

function mfsp_parse_request__vpage_sharing_off(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if ($current_slug == "mfsp-sharing-off") {
        include 'pages/vpage_sharing_off.php';
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_sharing_off');

function mfsp_parse_request__vpage_sharing_expired(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if ($current_slug == "mfsp-sharing-expired") {
        include 'pages/vpage_sharing_expired.php';
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_sharing_expired');

function mfsp_parse_request__vpage_sharing_exhausted(&$wp)
{
    $current_slug = add_query_arg(array(), $wp->request);
    if ($current_slug == "mfsp-sharing-exhausted") {
        include 'pages/vpage_sharing_exhausted.php';
        exit();
    }
    return;
}
add_action('parse_request', 'mfsp_parse_request__vpage_sharing_exhausted');
