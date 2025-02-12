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
    }

    public function custom_pagarme_order_autoload($class) {
        if ($class === 'Pagarme\Core\Payment\Aggregates\Order') {
            class Custom_Pagarme_Order_Wrapper {
                private $originalOrder;

                public function __construct() {
                    $this->originalOrder = new \Pagarme\Core\Payment\Aggregates\Order();
                }

                public function __call($method, $arguments) {
                    if ($method === 'convertToSDKRequest') {
                        return $this->customConvertToSDKRequest();
                    }
                    return call_user_func_array([$this->originalOrder, $method], $arguments);
                }

                private function customConvertToSDKRequest() {
                    $orderRequest = $this->originalOrder->convertToSDKRequest();

                    if (!empty($this->originalOrder->getSplitData())) {
                        $splitData = $this->originalOrder->getSplitData();
                        
                        global $wp_filter;
                        if (isset($wp_filter['do_split_order'])) {
                            $orderRequest = apply_filters('do_split_order', $orderRequest, $splitData, $this->originalOrder);
                        }
                    }

                    return $orderRequest;
                }

                public function __get($name) {
                    return $this->originalOrder->$name;
                }

                public function __set($name, $value) {
                    $this->originalOrder->$name = $value;
                }
            }

            class_alias('Custom_Pagarme_Order_Wrapper', $class);
        }
    }
}

// Initialize the plugin
new Pagarme_Custom_Split_Plugin();

