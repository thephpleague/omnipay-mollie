<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/mandates-api/create-mandate
 */
class CreateCustomerMandateResponse extends AbstractMollieResponse
{
    /**
     * @return string
     */
    public function getMandateId()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['id']);
    }
}
