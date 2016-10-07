<?php

namespace Omnipay\Mollie\Message;

class FetchCustomerResponse extends AbstractResponse
{
    /**
     * @return mixed
     */
    public function getCustomerReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }
}
