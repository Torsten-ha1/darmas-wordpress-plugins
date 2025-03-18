<?php
/**
 * Plugin Name: Darmas Carmarket
 * Plugin URI: https://lab.darmas.de/wordpress-plugin-carmarket
 * Description: Displays a grid of vehicle cards from the Darmas carmarket. Configure via Settings/Darmas Carmarket.
 * Version: 1.0.0
 * Author: Torsten Barthel<t.barthel@darmas.de>
 * Author URI: https://darmas.de
 * Text Domain: darmas-carmarket
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DARMAS_CARMARKET_VERSION', '1.0.0');
define('DARMAS_CARMARKET_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DARMAS_CARMARKET_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DARMAS_CARMARKET_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once DARMAS_CARMARKET_PLUGIN_DIR . 'includes/admin-page.php';
require_once DARMAS_CARMARKET_PLUGIN_DIR . 'includes/shortcode.php';
require_once DARMAS_CARMARKET_PLUGIN_DIR . 'includes/api.php';

/**
 * Initialize the plugin
 */
function darmas_carmarket_init() {
    // Register styles and scripts
    add_action('wp_enqueue_scripts', 'darmas_carmarket_enqueue_scripts');
    
    // Register admin menu
    add_action('admin_menu', 'darmas_carmarket_admin_menu');
    
    // Register shortcode
    add_shortcode('darmas_carmarket', 'darmas_carmarket_shortcode');
}
add_action('plugins_loaded', 'darmas_carmarket_init');

/**
 * Enqueue scripts and styles
 */
function darmas_carmarket_enqueue_scripts() {
    // Enqueue CSS
    wp_enqueue_style(
        'darmas-carmarket-style',
        DARMAS_CARMARKET_PLUGIN_URL . 'assets/css/style.css',
        array(),
        DARMAS_CARMARKET_VERSION
    );
    
    // Enqueue JavaScript
    wp_enqueue_script(
        'darmas-carmarket-script',
        DARMAS_CARMARKET_PLUGIN_URL . 'assets/js/script.js',
        array('jquery'),
        DARMAS_CARMARKET_VERSION,
        true
    );
    
    // Pass variables to JavaScript
    wp_localize_script(
        'darmas-carmarket-script',
        'darmasCarmarket',
        array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('darmas-carmarket-nonce')
        )
    );
}

/**
 * Plugin activation hook
 */
function darmas_carmarket_activate() {
    // Create default options
    $default_options = array(
        'vehicle_ids' => '',
        'cards_per_row' => 3,
        'max_cards' => 12
    );
    
    add_option('darmas_carmarket_options', $default_options);
}
register_activation_hook(__FILE__, 'darmas_carmarket_activate');

/**
 * Plugin deactivation hook
 */
function darmas_carmarket_deactivate() {
    // Cleanup if needed
}
register_deactivation_hook(__FILE__, 'darmas_carmarket_deactivate');

/**
 * Plugin uninstall hook
 */
function darmas_carmarket_uninstall() {
    // Remove plugin options
    delete_option('darmas_carmarket_options');
}
register_uninstall_hook(__FILE__, 'darmas_carmarket_uninstall');
