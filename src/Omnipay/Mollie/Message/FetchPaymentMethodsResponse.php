<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Message\FetchPaymentMethodsResponseInterface;
use Omnipay\Mollie\PaymentMethod;

class FetchPaymentMethodsResponse extends AbstractResponse implements FetchPaymentMethodsResponseInterface
{
    /**
     * Return available paymentmethods as an associative array.
     *
     * @return \Omnipay\Mollie\PaymentMethod[]
     */
    public function getPaymentMethods()
    {
        $paymentMethods = array();

        if (isset($this->data['data'])) {
            foreach ($this->data['data'] as $method) {
                $paymentMethods[] = new PaymentMethod(
                    $method['id'],
                    $method['description'],
                    $method['amount'],
                    $method['image']
                );
            }
        }

        return $paymentMethods;
    }
}
