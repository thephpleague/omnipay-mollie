<?php

namespace Omnipay\Mollie\Message\Response;

/**
 * Captures are processed asynchronously, the payment status changes to "paid" afterwards
 * (a webhook is called for the payment).
 *
 * @see https://docs.mollie.com/reference/create-capture
 */
class CaptureResponse extends AbstractMollieResponse
{
    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return isset($this->data['resource']) && 'capture' === $this->data['resource']
            && isset($this->data['status']) && 'failed' !== $this->data['status'];
    }

    /**
     * @return string|null Payment ID
     */
    public function getTransactionReference()
    {
        return isset($this->data['paymentId']) ? $this->data['paymentId'] : null;
    }

    /**
     * @return string|null Capture ID
     */
    public function getCaptureReference()
    {
        return isset($this->data['id']) ? $this->data['id'] : null;
    }

    /**
     * @return string|null Capture status ("pending", "succeeded" or "failed")
     */
    public function getStatus()
    {
        return isset($this->data['status']) ? $this->data['status'] : null;
    }
}
