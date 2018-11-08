<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 */
class CompleteOrderResponse extends FetchOrderResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isPaid();
    }

    /**
     * The order status is never a redirect
     *
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return false;
    }
}
