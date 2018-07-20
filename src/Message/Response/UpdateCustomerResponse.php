<?php

namespace Omnipay\Mollie\Message\Response;

class UpdateCustomerResponse extends AbstractResponse
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
