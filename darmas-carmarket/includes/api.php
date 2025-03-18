<?php
/**
 * API functionality for Darmas Carmarket plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register REST API routes
 */
function darmas_carmarket_register_rest_routes() {
    register_rest_route('mobile-de', '/get/darmas/ad/(?P<internal_number>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'darmas_carmarket_get_vehicle_data_api',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'darmas_carmarket_register_rest_routes');

/**
 * API callback to get vehicle data
 * 
 * @param WP_REST_Request $request Request object
 * @return WP_REST_Response Response object
 */
function darmas_carmarket_get_vehicle_data_api($request) {
    // Get internal number from request
    $internal_number = $request->get_param('internal_number');
    
    if (empty($internal_number)) {
        return new WP_REST_Response(array(
            'error' => 'Internal number is required',
        ), 400);
    }
    
    // This is a placeholder function that would normally fetch data from your actual API
    // In a real implementation, you would make a request to the Darmas API here
    $vehicle_data = darmas_carmarket_fetch_vehicle_from_api($internal_number);
    
    if (!$vehicle_data) {
        return new WP_REST_Response(array(
            'error' => 'Vehicle not found',
        ), 404);
    }
    
    return new WP_REST_Response($vehicle_data, 200);
}

/**
 * Fetch vehicle data from the Darmas API
 * 
 * @param string $internal_number Vehicle internal number
 * @return array|false Vehicle data or false on failure
 */
function darmas_carmarket_fetch_vehicle_from_api($internal_number) {
    // In a real implementation, you would make a request to the Darmas API here
    // For now, we'll return a placeholder vehicle
    
    // API endpoint for the actual Darmas API
    // $api_url = 'https://api.darmas.de/mobile-de/get/darmas/ad/' . $internal_number;

    // Check if the site is running on localhost
    if (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false) {
        $api_url = 'http://localhost:8031/v1/mobile-de/get/darmas/ad/' . $internal_number;
    } else {
        $api_url = 'https://api.main.mobile.dispo24.de/v1/mobile-de/get/darmas/ad/' . $internal_number;
    }

    
    // Make API request
    $response = wp_remote_get($api_url);

    
    
    // Check for errors
    if (is_wp_error($response)) {
        error_log('Darmas Carmarket API Error: ' . $response->get_error_message());
        return darmas_carmarket_get_placeholder_vehicle($internal_number);
    }
    
    // Check response code
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        error_log('Darmas Carmarket API Error: Received response code ' . $response_code);
        return darmas_carmarket_get_placeholder_vehicle($internal_number);
    }
    
    // Get response body
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // echo '<pre>';
    // echo $data;
    // echo '</pre>';
    
    // Check if data was successfully decoded
    if (empty($data) || !is_array($data)) {
        error_log('Darmas Carmarket API Error: Invalid response data');
        return darmas_carmarket_get_placeholder_vehicle($internal_number);
    }
    
    return $data;
}

/**
 * Get placeholder vehicle data for testing
 * 
 * @param string $internal_number Vehicle internal number
 * @return array Placeholder vehicle data
 */
function darmas_carmarket_get_placeholder_vehicle($internal_number) {
    // Create a placeholder vehicle for testing
    return array(
        'ad' => array(
            'internalNumber' => $internal_number,
            'make' => 'Hyundai',
            'model' => 'IONIQ 5',
            'modelDescription' => 'Hyundai IONIQ 5 Elektro',
            'price' => array(
                'consumerPriceGross' => 45990,
            ),
            'mileage' => 10000,
            'power' => 160,
            'fuel' => 'ELECTRICITY',
            'gearbox' => 'AUTOMATIC_GEAR',
            'firstRegistration' => '202201',
            'condition' => 'USED',
            'exteriorColor' => 'Weiß',
            'images' => array(
                array(
                    'url' => 'https://example.com/image1.jpg',
                ),
            ),
        ),
        'images' => array(
            array(
                'url' => 'https://via.placeholder.com/800x600.png?text=Hyundai+IONIQ+5',
            ),
        ),
    );
}
