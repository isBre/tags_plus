<?php
/*
Plugin Name: Tags+ Solo Calcio Femminile
Description: Aggiunge campi personalizzati per i tag WordPress (Giocatrice, Squadra ...).
Version: 1.5.1
Author: isbre
Text Domain: tags-plus-solo-calcio-femminile
Domain Path: /languages
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('TAGS_PLUS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TAGS_PLUS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include other files
require_once TAGS_PLUS_PLUGIN_DIR . 'tag/admin-functions.php';
require_once TAGS_PLUS_PLUGIN_DIR . 'tag/public-functions.php';
require_once TAGS_PLUS_PLUGIN_DIR . 'tag/styles.php';

/**
 * Initialize the plugin.
 */
function tags_plus_scf_init() {
    load_plugin_textdomain('tags-plus-solo-calcio-femminile', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'tags_plus_scf_init');

/**
 * Activation hook callback function.
 */
function tags_plus_scf_activate() {
    // Perform actions upon plugin activation
}
register_activation_hook(__FILE__, 'tags_plus_scf_activate');

/**
 * Deactivation hook callback function.
 */
function tags_plus_scf_deactivate() {
    // Perform actions upon plugin deactivation
}
register_deactivation_hook(__FILE__, 'tags_plus_scf_deactivate');
?>