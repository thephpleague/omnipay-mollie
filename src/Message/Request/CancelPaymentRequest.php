<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Mollie\Message\Response\CancelPaymentResponse;

/**
 * Cancels a payment or releases the authorization of a payment created with "captureMode" = "manual"
 *
 * @see https://docs.mollie.com/reference/cancel-payment
 * @method CancelPaymentResponse send()
 */
final class CancelPaymentRequest extends AbstractMollieRequest
{
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        return [];
    }

    public function sendData($data)
    {
        return $this->response = new CancelPaymentResponse(
            $this,
            $this->sendRequest(self::DELETE, '/payments/' . $this->getTransactionReference(), $data)
        );
    }
}
