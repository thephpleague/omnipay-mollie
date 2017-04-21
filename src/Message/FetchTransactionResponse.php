<?php

namespace Omnipay\Mollie\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class FetchTransactionResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * {@inheritdoc}
     */
    public function isRedirect()
    {
        return isset($this->data['links']['paymentUrl']);
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUrl()
    {
        if ($this->isRedirect()) {
            return $this->data['links']['paymentUrl'];
        }
    }

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
    public function getRedirectData()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccessful()
    {
        return parent::isSuccessful() && self::isPaid();
    }

    /**
     * @return boolean
     */
    public function isOpen()
    {
        return isset($this->data['status']) && 'open' === $this->data['status'];
    }

    /**
     * @return boolean
     */
    public function isCancelled()
    {
        return isset($this->data['status']) && 'cancelled' === $this->data['status'];
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
    public function isPaidOut()
    {
        return isset($this->data['status']) && 'paidout' === $this->data['status'];
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
        return isset($this->data['status']) && 'refunded' === $this->data['status'];
    }

    public function isPartialRefunded()
    {
        return $this->isRefunded() && isset($this->data['amountRemaining']) && $this->data['amountRemaining'] > 0;
    }

    /**
     * @return mixed
     */
    public function getTransactionReference()
    {
        if (isset($this->data['id'])) {
            return $this->data['id'];
        }
    }

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        if (isset($this->data['metadata']['transactionId'])) {
            return $this->data['metadata']['transactionId'];
        }
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        if (isset($this->data['status'])) {
            return $this->data['status'];
        }
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        if (isset($this->data['amount'])) {
            return $this->data['amount'];
        }
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        if (isset($this->data['metadata'])) {
            return $this->data['metadata'];
        }
    }
}
