<?php

use Pagarme\Core\Payment\Aggregates\Order as OriginalOrder;
use PagarmeCoreApiLib\Models\CreateOrderRequest;

class Custom_Pagarme_Order extends OriginalOrder
{
    public function convertToSDKRequest()
    {
        $orderRequest = new CreateOrderRequest();

        $orderRequest->antifraudEnabled = $this->isAntifraudEnabled();
        $orderRequest->closed = $this->isClosed();
        $orderRequest->code = $this->getCode();
        $orderRequest->customer = $this->getCustomer()->convertToSDKRequest();

        $orderRequest->payments = [];
        foreach ($this->getPayments() as $payment) {
            $orderRequest->payments[] = $payment->convertToSDKRequest();
        }

        if (!empty($this->getSplitData())) {
            $orderRequest = $this->fixRoundedValuesInCharges($orderRequest);
        }

        $orderRequest->items = [];
        foreach ($this->getItems() as $item) {
            $orderRequest->items[] = $item->convertToSDKRequest();
        }

        $shipping = $this->getShipping();
        if ($shipping !== null) {
            $orderRequest->shipping = $shipping->convertToSDKRequest();
        }

        if (!empty($this->getSplitData())) {
            $splitData = $this->getSplitData();
            
            global $wp_filter;
            if (isset($wp_filter['do_split_order'])) {
                $orderRequest = apply_filters('do_split_order', $orderRequest, $splitData, $this);
            }
        }

        return $orderRequest;
    }
}