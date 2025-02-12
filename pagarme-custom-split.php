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

class Pagarme_Custom_Split_Plugin {
    public function __construct() {
        // Registra o autoloader personalizado
        spl_autoload_register(array($this, 'custom_pagarme_order_autoload'), true, true);

        // Adiciona o filtro personalizado para o split
        // add_filter('do_split_order', array($this, 'custom_pagarme_do_split_order'), 10, 3);
    }

    public function custom_pagarme_order_autoload($class) {
        if ($class === 'Pagarme\Core\Payment\Aggregates\Order') {
            require_once __DIR__ . '/includes/class-custom-pagarme-order-wrapper.php';
            class_alias('Custom_Pagarme_Order_Wrapper', $class);
        }
    }

    // public function custom_pagarme_do_split_order($orderRequest, $splitData, $order) {
    //     foreach ($orderRequest->payments as $key => $paymentObject) {
    //         $split = [];    
    //         $sellerCount = count($splitData->getSellersData());
            
    //         foreach ($splitData->getSellersData() as $index => $sellerData) {
    //             $isLastSeller = ($index === $sellerCount - 1);
                
    //             $split[] = [
    //                 "amount" => $sellerData['commission'],
    //                 "recipient_id" => $sellerData['pagarmeId'],
    //                 "type" => "flat",
    //                 "options" => [
    //                     "charge_processing_fee" => true,
    //                     "charge_remainder_fee" => $isLastSeller,
    //                     "liable" => true
    //                 ]
    //             ];
    //         }
    //         $paymentObject->split = $split;
    //     }

    //     return $orderRequest;
    // }
}

// Initialize the plugin
new Pagarme_Custom_Split_Plugin();

