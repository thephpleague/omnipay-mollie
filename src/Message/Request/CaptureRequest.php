<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Message\ResponseInterface;
use Omnipay\Mollie\Message\Response\CaptureResponse;

/**
 * Captures an authorized payment created with "captureMode" = "manual" (e.g. Klarna or cards).
 * Without amount, the full authorized amount is captured.
 *
 * @see https://docs.mollie.com/reference/create-capture
 * @method CaptureResponse send()
 */
class CaptureRequest extends AbstractMollieRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey', 'transactionReference');

        $data = [];

        if ($this->getParameter('amount') !== null) {
            $this->validate('currency');
            $data['amount'] = [
                'value' => $this->getAmount(),
                'currency' => $this->getCurrency(),
            ];
        }

        if (is_string($this->getParameter('description'))) {
            $data['description'] = $this->getParameter('description');
        }

        return $data;
    }

    /**
     * @param array $data
     * @return ResponseInterface|CaptureResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(
            self::POST,
            '/payments/' . $this->getTransactionReference() . '/captures',
            $data
        );

        return $this->response = new CaptureResponse($this, $response);
    }
}
