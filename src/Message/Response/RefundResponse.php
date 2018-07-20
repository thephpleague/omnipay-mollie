<?php


namespace Omnipay\Mollie\Message\Response;

class RefundResponse extends AbstractResponse
{
    /**
     * @return null|string
     */
    public function getTransactionReference()
    {
        return $this->data['paymentId'];
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->data['id'];
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['id']);
    }
}
