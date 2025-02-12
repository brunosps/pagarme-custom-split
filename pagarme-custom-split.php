<?php
/*
Plugin Name: Pagarme Custom Split for WooCommerce
Description: Customizes the split functionality of the Pagarme WooCommerce plugin
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Ensure WooCommerce and Pagarme for WooCommerce are active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) return;
if (!in_array('woocommerce-pagarme/woocommerce-pagarme.php', apply_filters('active_plugins', get_option('active_plugins')))) return;

// Include your custom Order class
require_once plugin_dir_path(__FILE__) . 'includes/class-custom-pagarme-order.php';

// Hook to replace the original Order class
add_action('plugins_loaded', 'replace_pagarme_order_class', 20);

function replace_pagarme_order_class() {
    if (class_exists('Pagarme\Core\Payment\Aggregates\Order')) {
        class_alias('Custom_Pagarme_Order', 'Pagarme\Core\Payment\Aggregates\Order');
    }
}

// Add your custom split filter
add_filter('do_split_order', 'custom_pagarme_do_split_order', 10, 3);

function custom_pagarme_do_split_order($orderRequest, $splitData, $order) {
    foreach ($orderRequest->payments as $key => $paymentObject) {
        $split = [];    
        $sellerCount = count($splitData->getSellersData());
        
        foreach ($splitData->getSellersData() as $index => $sellerData) {
            $isLastSeller = ($index === $sellerCount - 1);
            
            $split[] = [
                "amount" => $sellerData['commission'],
                "recipient_id" => $sellerData['pagarmeId'],
                "type" => "flat",
                "options" => [
                    "charge_processing_fee" => true,
                    "charge_remainder_fee" => $isLastSeller,
                    "liable" => true
                ]
            ];
        }
        $paymentObject->split = $split;
    }

    return $orderRequest;
}