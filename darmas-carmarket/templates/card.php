<?php
/**
 * Template for vehicle card
 * 
 * @var array $vehicle_data Vehicle data from API
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Extract vehicle data
$ad = isset($vehicle_data['ad']) ? $vehicle_data['ad'] : array();
$images = isset($vehicle_data['images']) ? $vehicle_data['images'] : array();

// Log images array to browser console
if (!empty($images)) {
    echo '<script>console.log(' . json_encode($images) . ');</script>';
} else {
    echo '<script>console.log("No images available");</script>';
}

// error_log(print_r($images, true));

// Get primary image URL
$image_url = '';
if (!empty($images)) {
    $image_url = $images[0]['mobileDeImage']['ref'];
}

// Get vehicle details
$make = isset($ad['make']) ? $ad['make'] : '';
$model = isset($ad['model']) ? $ad['model'] : '';
$model_description = isset($ad['modelDescription']) ? $ad['modelDescription'] : '';
$title = $model_description ? $model_description : "$make $model";

// Price
$price = '';
if (isset($ad['price']['consumerPriceGross'])) {
    $price = darmas_carmarket_format_price($ad['price']['consumerPriceGross']);
}

// Vehicle specs
$mileage = isset($ad['mileage']) ? darmas_carmarket_format_mileage($ad['mileage']) : '';
$power = isset($ad['power']) ? darmas_carmarket_format_power($ad['power']) : '';
$fuel = isset($ad['fuel']) ? $ad['fuel'] : '';
$gearbox = isset($ad['gearbox']) ? $ad['gearbox'] : '';
$first_registration = isset($ad['firstRegistration']) ? darmas_carmarket_format_registration_date($ad['firstRegistration']) : '';
$condition = isset($ad['condition']) ? $ad['condition'] : '';
$exterior_color = isset($ad['exteriorColor']) ? $ad['exteriorColor'] : '';

// Map fuel types to German
$fuel_types = array(
    'PETROL' => 'Benzin',
    'DIESEL' => 'Diesel',
    'ELECTRICITY' => 'Elektro',
    'HYBRID' => 'Hybrid',
    'HYBRID_DIESEL' => 'Hybrid (Diesel)',
    'HYBRID_PETROL' => 'Hybrid (Benzin)',
    'LPG' => 'Autogas (LPG)',
    'CNG' => 'Erdgas (CNG)',
    'HYDROGEN' => 'Wasserstoff',
    'OTHER' => 'Sonstige'
);

// Map gearbox types to German
$gearbox_types = array(
    'MANUAL_GEAR' => 'Schaltgetriebe',
    'AUTOMATIC_GEAR' => 'Automatik',
    'SEMI_AUTOMATIC_GEAR' => 'Halbautomatik'
);

// Map condition types to German
$condition_types = array(
    'NEW' => 'Neu',
    'USED' => 'Gebraucht',
    'EMPLOYEE_CAR' => 'Jahreswagen',
    'DEMONSTRATION' => 'Vorführwagen',
    'PRE_REGISTRATION' => 'Vorregistrierung',
    'OLDTIMER' => 'Oldtimer',
    'ANTIQUE' => 'Antik'
);

// Translate values
$fuel = isset($fuel_types[$fuel]) ? $fuel_types[$fuel] : $fuel;
$gearbox = isset($gearbox_types[$gearbox]) ? $gearbox_types[$gearbox] : $gearbox;
$condition = isset($condition_types[$condition]) ? $condition_types[$condition] : $condition;
?>

<div class="darmas-card">
    <div class="darmas-card-image">
        <?php if ($image_url) : ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" class="darmas-card-img">
        <?php else : ?>
            <div class="darmas-card-no-image">
                <?php _e('No image available', 'darmas-carmarket'); ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="darmas-card-body">
        <h3 class="darmas-card-title"><?php echo esc_html($title); ?></h3>
        
        <?php if ($price) : ?>
            <div class="darmas-card-price"><?php echo esc_html($price); ?></div>
        <?php endif; ?>
        
        <div class="darmas-card-specs">
            <ul class="darmas-specs-list">
                <?php if ($first_registration) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Erstzulassung', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($first_registration); ?></span>
                    </li>
                <?php endif; ?>
                
                <?php if ($mileage) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Km', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($mileage); ?></span>
                    </li>
                <?php endif; ?>
                
                <?php if ($power) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Power', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($power); ?></span>
                    </li>
                <?php endif; ?>
                
                <?php if ($fuel) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Typ', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($fuel); ?></span>
                    </li>
                <?php endif; ?>
                
                <?php if ($gearbox) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Getriebe', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($gearbox); ?></span>
                    </li>
                <?php endif; ?>
                
                <?php if ($condition) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Zustand', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($condition); ?></span>
                    </li>
                <?php endif; ?>
                
                <?php if ($exterior_color) : ?>
                    <li>
                        <span class="darmas-specs-label"><?php _e('Farbe', 'darmas-carmarket'); ?></span>
                        <span class="darmas-specs-value"><?php echo esc_html($exterior_color); ?></span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    
    <div class="darmas-card-footer">
        
        <a href="<?php echo esc_url('https://darmas.de/fahrzeugmarkt/details/' . $vehicle_data['_id']); ?>" class="darmas-btn darmas-btn-primary" target="_blank"><?php _e('View Details', 'darmas-carmarket'); ?></a>
    </div>
</div>
