<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/customers-api/update-customer
 */
class UpdateCustomerResponse extends AbstractMollieResponse
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
