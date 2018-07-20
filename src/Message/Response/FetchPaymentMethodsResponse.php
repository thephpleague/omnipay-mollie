<?php

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Common\PaymentMethod;
use Omnipay\Common\PaymentMethod as CommonPaymentMethod;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{
    /**
     * Return available paymentmethods as an associative array.
     *
     * @return CommonPaymentMethod[]
     */
    public function getPaymentMethods()
    {
        if (isset($this->data['_embedded']["methods"])) {
            $paymentMethods = [];
            foreach ($this->data['_embedded']["methods"] as $method) {
                $paymentMethods[] = new PaymentMethod($method['id'], $method['description']);
            }

            return $paymentMethods;
        }

        return [];
    }
}
