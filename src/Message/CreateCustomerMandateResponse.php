<?php
/**
 * Date: 21/08/17
 * Time: 13:16
 */

namespace Omnipay\Mollie\Message;


class CreateCustomerMandateResponse extends AbstractResponse
{
    /**
     * @return string
     */
    public function getCustomerReference()
    {
        if (isset($this->data['customerId'])) {
            return $this->data['customerId'];
        }
    }

    public function getMandateId()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }
}