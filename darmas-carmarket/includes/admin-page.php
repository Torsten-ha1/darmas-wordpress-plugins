<?php
/**
 * Admin page for Darmas Carmarket plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register admin menu
 */
function darmas_carmarket_admin_menu() {
    add_menu_page(
        __('Darmas Carmarket', 'darmas-carmarket'),
        __('Darmas Carmarket', 'darmas-carmarket'),
        'manage_options',
        'darmas-carmarket',
        'darmas_carmarket_admin_page',
        'dashicons-car',
        30
    );

    add_options_page(
        __('Darmas Carmarket', 'darmas-carmarket'),
        __('Darmas Carmarket', 'darmas-carmarket'),
        'manage_options',       // Capability
        'darmas-carmarket',            // Menu slug
        'darmas_carmarket_admin_page' // Callback function
    );
}

/**
 * Display admin page
 */
function darmas_carmarket_admin_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // Save settings if form is submitted
    if (isset($_POST['darmas_carmarket_save_settings']) && check_admin_referer('darmas_carmarket_settings', 'darmas_carmarket_nonce')) {
        $options = get_option('darmas_carmarket_options', array());
        
        // Sanitize and save vehicle IDs
        if (isset($_POST['vehicle_ids'])) {
            $vehicle_ids = sanitize_textarea_field($_POST['vehicle_ids']);
            $options['vehicle_ids'] = $vehicle_ids;
        }
        
        // Sanitize and save cards per row
        if (isset($_POST['cards_per_row'])) {
            $cards_per_row = absint($_POST['cards_per_row']);
            $options['cards_per_row'] = $cards_per_row > 0 ? $cards_per_row : 3;
        }
        
        // Sanitize and save max cards
        if (isset($_POST['max_cards'])) {
            $max_cards = absint($_POST['max_cards']);
            $options['max_cards'] = $max_cards > 0 ? $max_cards : 12;
        }
        
        // Update options
        update_option('darmas_carmarket_options', $options);
        
        // Show success message
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Settings saved successfully!', 'darmas-carmarket') . '</p></div>';
    }
    
    // Get current options
    $options = get_option('darmas_carmarket_options', array(
        'vehicle_ids' => '',
        'cards_per_row' => 3,
        'max_cards' => 12
    ));
    
    // Display the settings form
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('darmas_carmarket_settings', 'darmas_carmarket_nonce'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="vehicle_ids"><?php _e('Vehicle Internal Numbers', 'darmas-carmarket'); ?></label>
                    </th>
                    <td>
                        <textarea name="vehicle_ids" id="vehicle_ids" rows="10" cols="50" class="large-text"><?php echo esc_textarea($options['vehicle_ids']); ?></textarea>
                        <p class="description">
                            <?php _e('Enter the internal numbers of the vehicles to display, one per line.', 'darmas-carmarket'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="cards_per_row"><?php _e('Cards Per Row', 'darmas-carmarket'); ?></label>
                    </th>
                    <td>
                        <input type="number" name="cards_per_row" id="cards_per_row" min="1" max="6" value="<?php echo esc_attr($options['cards_per_row']); ?>" class="small-text">
                        <p class="description">
                            <?php _e('Number of cards to display per row (1-6).', 'darmas-carmarket'); ?>
                        </p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="max_cards"><?php _e('Maximum Cards', 'darmas-carmarket'); ?></label>
                    </th>
                    <td>
                        <input type="number" name="max_cards" id="max_cards" min="1" max="100" value="<?php echo esc_attr($options['max_cards']); ?>" class="small-text">
                        <p class="description">
                            <?php _e('Maximum number of cards to display (1-100).', 'darmas-carmarket'); ?>
                        </p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="darmas_carmarket_save_settings" class="button button-primary" value="<?php _e('Save Settings', 'darmas-carmarket'); ?>">
            </p>
        </form>
        
        <div class="card">
            <h2><?php _e('Shortcode Usage', 'darmas-carmarket'); ?></h2>
            <p><?php _e('Use the following shortcode to display the vehicle cards on any page or post:', 'darmas-carmarket'); ?></p>
            <code>[darmas_carmarket]</code>
            
            <p><?php _e('You can also override the default settings with shortcode attributes:', 'darmas-carmarket'); ?></p>
            <code>[darmas_carmarket cards_per_row="4" max_cards="8"]</code>
            
            <p><?php _e('To display specific vehicles, you can use the vehicle_ids attribute:', 'darmas-carmarket'); ?></p>
            <code>[darmas_carmarket vehicle_ids="123456,789012"]</code>
        </div>
    </div>
    <?php
}
