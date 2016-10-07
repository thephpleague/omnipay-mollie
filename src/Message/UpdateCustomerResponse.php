<?php

namespace Omnipay\Mollie\Message;

class UpdateCustomerResponse extends AbstractResponse
{
    /**
     * @return string
     */
    public function getCustomerReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }
}
