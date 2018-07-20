<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/refunds-api/create-refund
 */
class RefundResponse extends AbstractMollieResponse
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
