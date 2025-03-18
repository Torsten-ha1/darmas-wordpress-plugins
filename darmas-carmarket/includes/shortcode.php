<?php
/**
 * Shortcode functionality for Darmas Carmarket plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode callback function
 * 
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function darmas_carmarket_shortcode($atts) {
    // Start output buffering
    ob_start();
    
    // Get plugin options
    $options = get_option('darmas_carmarket_options', array(
        'vehicle_ids' => '',
        'cards_per_row' => 3,
        'max_cards' => 12
    ));
    
    // Parse shortcode attributes
    $atts = shortcode_atts(array(
        'vehicle_ids' => $options['vehicle_ids'],
        'cards_per_row' => $options['cards_per_row'],
        'max_cards' => $options['max_cards']
    ), $atts, 'darmas_carmarket');
    
    // Process vehicle IDs
    $vehicle_ids = array();
    if (!empty($atts['vehicle_ids'])) {
        // Check if it's a comma-separated list (from shortcode attribute)
        if (strpos($atts['vehicle_ids'], ',') !== false) {
            $vehicle_ids = array_map('trim', explode(',', $atts['vehicle_ids']));
        } else {
            // Assume it's a newline-separated list (from admin settings)
            $vehicle_ids = array_filter(array_map('trim', explode("\n", $atts['vehicle_ids'])));
        }
    }
    
    // Limit to max cards
    $vehicle_ids = array_slice($vehicle_ids, 0, intval($atts['max_cards']));
    
    // If no vehicle IDs, show a message
    if (empty($vehicle_ids)) {
        echo '<div class="darmas-carmarket-notice">';
        echo __('No vehicles configured. Please add vehicle internal numbers in the Darmas Carmarket settings.', 'darmas-carmarket');
        echo '</div>';
        return ob_get_clean();
    }
    
    // Calculate column class based on cards per row
    $cards_per_row = intval($atts['cards_per_row']);
    if ($cards_per_row < 1) $cards_per_row = 1;
    if ($cards_per_row > 6) $cards_per_row = 6;
    
    $column_class = 'darmas-col-' . (12 / $cards_per_row);
    
    // Start the grid container
    echo '<div class="grid-container">';
    
    // Loop through vehicle IDs and fetch data
    foreach ($vehicle_ids as $internal_number) {
        // Fetch vehicle data
        $vehicle_data = darmas_carmarket_get_vehicle_data($internal_number);

        // echo 'DATA::' . $vehicle_data['_id'];
        
        if ($vehicle_data) {
            // Display vehicle card
            echo '<div class="' . esc_attr($column_class) . '">';
            include DARMAS_CARMARKET_PLUGIN_DIR . 'templates/card.php';
            echo '</div>';
        }
    }
    
    echo '</div>';
    
    // Return the buffered output
    return ob_get_clean();
}

/**
 * Fetch vehicle data from the API
 * 
 * @param string $internal_number Vehicle internal number
 * @return array|false Vehicle data or false on failure
 */
function darmas_carmarket_get_vehicle_data($internal_number) {
    // API endpoint
    $api_url = site_url('/wp-json/mobile-de/get/darmas/ad/' . $internal_number);
    
    // Make API request
    $response = wp_remote_get($api_url);
    
    // Check for errors
    if (is_wp_error($response)) {
        error_log('Darmas Carmarket API Error: ' . $response->get_error_message());
        return false;
    }
    
    // Check response code
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        error_log('Darmas Carmarket API Error: Received response code ' . $response_code);
        return false;
    }
    
    // Get response body
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    
    // Check if data was successfully decoded
    if (empty($data) || !is_array($data)) {
        error_log('Darmas Carmarket API Error: Invalid response data');
        return false;
    }
    
    return $data;
}

/**
 * Format price with German number format
 * 
 * @param float $price Price to format
 * @return string Formatted price
 */
function darmas_carmarket_format_price($price) {
    return number_format($price, 0, ',', '.') . ' €';
}

/**
 * Format mileage with German number format
 * 
 * @param int $mileage Mileage to format
 * @return string Formatted mileage
 */
function darmas_carmarket_format_mileage($mileage) {
    return number_format($mileage, 0, ',', '.') . ' km';
}

/**
 * Format power in kW and PS
 * 
 * @param int $power Power in kW
 * @return string Formatted power
 */
function darmas_carmarket_format_power($power) {
    $ps = round($power * 1.36);
    return $power . ' kW (' . $ps . ' PS)';
}

/**
 * Format first registration date
 * 
 * @param string $date Date in format YYYYMM
 * @return string Formatted date
 */
function darmas_carmarket_format_registration_date($date) {
    if (strlen($date) !== 6) {
        return $date;
    }
    
    $year = substr($date, 0, 4);
    $month = substr($date, 4, 2);
    
    return $month . '/' . $year;
}
