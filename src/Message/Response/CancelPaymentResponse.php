<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * @see https://docs.mollie.com/reference/cancel-payment
 */
final class CancelPaymentResponse extends FetchTransactionResponse
{
    public function isSuccessful()
    {
        return $this->isCancelled();
    }
}
