# Darmas Carmarket WordPress Plugin

A WordPress plugin that displays a grid of vehicle cards from the Darmas carmarket.

## Description

The Darmas Carmarket plugin allows you to display a grid of vehicle cards on your WordPress website. The plugin fetches vehicle data from the Darmas API and displays it in a responsive grid layout.

## Features

- Responsive grid layout for vehicle cards
- Configurable number of cards per row
- Configurable maximum number of cards to display
- Admin settings page for easy configuration
- Shortcode support for flexible placement
- Displays key vehicle information including:
  - Vehicle image
  - Make and model
  - Price
  - First registration date
  - Mileage
  - Power (kW/PS)
  - Fuel type
  - Transmission type
  - Condition
  - Exterior color

## Installation

1. Upload the `darmas-carmarket` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin settings under 'Darmas Carmarket' in the admin menu
4. Add the shortcode `[darmas_carmarket]` to any page or post where you want to display the vehicle cards

## Configuration

### Admin Settings

1. Navigate to 'Darmas Carmarket' in the WordPress admin menu
2. Enter the internal numbers of the vehicles you want to display, one per line
3. Configure the number of cards per row (1-6)
4. Configure the maximum number of cards to display (1-100)
5. Save your settings

### Shortcode Usage

Basic usage:
```
[darmas_carmarket]
```

Override default settings:
```
[darmas_carmarket cards_per_row="4" max_cards="8"]
```

Display specific vehicles:
```
[darmas_carmarket vehicle_ids="123456,789012"]
```

## API Integration

The plugin integrates with the Darmas API to fetch vehicle data. It uses the following endpoint:

```
/wp-json/mobile-de/get/darmas/ad/{internal_number}
```

Where `{internal_number}` is the internal number of the vehicle.

## Styling

The plugin includes its own CSS for styling the vehicle cards. The styles are designed to be responsive and work with most WordPress themes.

## Requirements

- WordPress 5.0 or higher
- PHP 7.0 or higher

## Support

For support, please contact the Darmas team.

## License

This plugin is licensed under the GPL v2 or later.
