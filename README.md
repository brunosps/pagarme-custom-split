# Pagarme Custom Split for WooCommerce

This plugin customizes the split functionality of the Pagarme WooCommerce plugin.

## Description

Pagarme Custom Split for WooCommerce extends the functionality of the official Pagarme WooCommerce plugin by allowing custom split logic for orders. It overrides the default Order class to implement a custom split mechanism using WordPress filters.

## Installation

1. Ensure that WooCommerce and the Pagarme for WooCommerce plugins are installed and activated.
2. Upload the `pagarme-custom-split` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin through the 'Plugins' menu in WordPress.

## Usage

Once activated, the plugin will automatically apply the custom split logic to Pagarme orders. You can modify the split logic by editing the `custom_pagarme_do_split_order` function in the main plugin file.

## Customization

To further customize the split logic or add additional functionality, you can modify the following files:

- `pagarme-custom-split.php`: Main plugin file, contains the split logic and hooks.
- `includes/class-custom-pagarme-order.php`: Custom Order class that overrides the original Pagarme Order class.

## Support

For support, please contact [Your Name/Company] at [your@email.com].

## License

This plugin is licensed under the GPL v2 or later.
