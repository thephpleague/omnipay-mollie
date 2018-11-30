<?php

namespace Omnipay\Mollie\Message\Request;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Mollie\Message\Response\CompletePurchaseResponse;

/**
 * Retrieve a single payment object by its payment token.
 *
 * @see https://docs.mollie.com/reference/v2/payments-api/get-payment
 * @method CompletePurchaseResponse send()
 */
class CompletePurchaseRequest extends FetchTransactionRequest
{
    /**
     * @return array
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate('apiKey');

        $data = [];
        $data['id'] = $this->getTransactionReference();

        if (!isset($data['id'])) {
            $data['id'] = $this->httpRequest->request->get('id');
        }

        if (empty($data['id'])) {
            throw new InvalidRequestException("The transactionReference parameter is required");
        }

        return $data;
    }

    /**
     * @param array $data
     * @return CompletePurchaseResponse
     */
    public function sendData($data)
    {
        $response = $this->sendRequest(self::GET, '/payments/' . $data['id']);

        return $this->response = new CompletePurchaseResponse($this, $response);
    }
}
