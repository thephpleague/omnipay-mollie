<?php

namespace Omnipay\Mollie\Message\Response;

class FetchCustomerResponse extends AbstractResponse
{
    /**
     * @return string|null
     */
    public function getCustomerReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }

        return null;
    }
}
