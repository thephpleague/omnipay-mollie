<?php

namespace Omnipay\Mollie\Message;

class FetchPaymentMethodsResponse extends AbstractResponse
{
    /**
     * Return available paymentmethods as an associative array.
     *
     * @return array|null
     */
    public function getPaymentMethods()
    {
        if (isset($this->data['data'])) {
            $result = array();

            foreach ($this->data['data'] as $method) {
                $result[] = array(
                    'id'          => $method['id'],
                    'description' => $method['description'],
                    'amount'      => $method['amount'],
                    'image'       => $method['image']
                );
            }

            return $result;
        }

        return null;
    }
}
