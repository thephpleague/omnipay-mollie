<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Common\PaymentMethod;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{
    /**
     * Return available paymentmethods as an associative array.
     *
     * @return \Omnipay\Common\PaymentMethod[]
     */
    public function getPaymentMethods()
    {
        $paymentMethods = array();

        if (isset($this->data['data'])) {
            foreach ($this->data['data'] as $method) {
                $paymentMethods[] = new PaymentMethod($method['id'], $method['description']);
            }
        }

        return $paymentMethods;
    }
}
