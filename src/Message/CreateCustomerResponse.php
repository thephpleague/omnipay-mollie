<?php


namespace Omnipay\Mollie\Message;


class CreateCustomerResponse extends AbstractResponse
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
