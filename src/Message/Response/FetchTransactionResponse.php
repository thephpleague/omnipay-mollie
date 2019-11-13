<?php

namespace Omnipay\Mollie\Message\Response;

use Omnipay\Common\Message\RedirectResponseInterface;

/**
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 */
class FetchTransactionResponse extends AbstractMollieResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRedirectMethod()
    {
        return 'GET';
    }

    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data['_links']['checkout']['href']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['_links']['checkout']['href'];
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return parent::isSuccessful();
    }

    /**
     * @return boolean
     */
    public function isOpen()
    {
        return isset($this->data['status'])
            && ('open' === $this->data['status'] || 'created' === $this->data['status']);
    }

    /**
     * @return boolean
     */
    public function isCancelled()
    {
        return isset($this->data['status']) && 'canceled' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isPaid()
    {
        return isset($this->data['status']) && 'paid' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isAuthorized()
    {
        return isset($this->data['status']) && 'authorized' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isPaidOut()
    {
        return isset($this->data['_links']['settlement']);
    }

    /**
     * @return boolean
     */
    public function isExpired()
    {
        return isset($this->data['status']) && 'expired' === $this->data['status'];
    }

    public function isRefunded()
    {
        return isset($this->data['_links']['refunds']);
    }

    public function isPartialRefunded()
    {
        return $this->isRefunded()
            && isset($this->data['amountRemaining'])
            && $this->data['amountRemaining']['value'] > 0;
    }

    /**
     * @return boolean
     */
    public function hasChargebacks()
    {
        return !empty($this->data['_links']['chargebacks']);
    }

    /**
     * @return string|null
     */
    public function getTransactionReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getTransactionId()
    {
        if (isset($this->data['metadata']['transactionId'])) {
            return $this->data['metadata']['transactionId'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getStatus()
    {
        if (isset($this->data['status'])) {
            return $this->data['status'];
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getAmount()
    {
        if (isset($this->data['amount']) && is_array($this->data['amount'])) {
            /**
             * $this->data['amount'] = [
             *      "currency" => "EUR",
             *      "value" => "50",
             * ]
             */
            return $this->data['amount']['value'];
        }

        return null;
    }

    public function getCurrency()
    {
        if ($this->isSuccessful() && is_array($this->data['amount'])) {
            /**
             * $this->data['amount'] = [
             *      "currency" => "EUR",
             *      "value" => "50",
             * ]
             */
            return $this->data['amount']['currency'];
        }

        return null;
    }

    /**
     * @return array|null
     */
    public function getMetadata()
    {
        if (isset($this->data['metadata'])) {
            return $this->data['metadata'];
        }

        return null;
    }
}
