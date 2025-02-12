<?php

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

