<?php

namespace Omnipay\Mollie\Message\Response;

class CompletePurchaseResponse extends FetchTransactionResponse
{
    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && $this->isPaid();
    }
}
